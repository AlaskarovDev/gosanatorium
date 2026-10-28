<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Sanatorium name patterns (matched against normalized titles + slugs of all translations). */
    private function patterns(): array
    {
        return [
            'chinar' => '/chinar|чинар/',
            'garabagh' => '/garabagh|qarabag|karabakh|гарабаг|карабах/',
            'gashalti' => '/gashalti|гашалты|гашалти/',
            'suncity' => '/sun city|сан сити/',
            'naftalan_central' => '/naftalan central|нафталан централ/',
            'park_naftalan' => '/park naftalan|парк нафталан/',
            'karvan' => '/karvan|карван/',
            'gold_naftalan' => '/gold naftalan|голд нафталан/',
            'nur_naftalan' => '/nur naftalan|нур нафталан/',
            'nafta' => '/nafta sanatori|нафта санатори/',
            'maricel' => '/maricel|марисел|маричел/',
            'absheron' => '/absheron|абшерон/',
            'bilgya' => '/bilgya|bilgah|бильгя|бильга/',
            'guneshli' => '/guneshli|gunesli|гюнешли|гюнешлы/',
            'tskaltubo_spa' => '/tskaltubo spa|цхалтубо спа/',
            'lankaran' => '/lankaran|ланкаран|лянкяран/',
            'shihovo' => '/shihovo|shikhovo|шихово/',
            'prometey' => '/prometey|prometheus|прометей/',
            'borjomi_likani' => '/likani|ликани/',
            'miras' => '/miras|мирас/',
            'borjomi_palace' => '/borjomi palace|боржоми палас/',
            'sairme' => '/sairme|сайрме/',
        ];
    }

    private function diseases(): array
    {
        // slug => [ru, az, en, image, sanatoriums]
        return [
            'sustavy' => ['Суставы / артрит / артроз', 'Oynaqlar / artrit / artroz', 'Joints / arthritis / arthrosis', 'sustavy.jpg',
                ['chinar', 'garabagh', 'gashalti', 'suncity', 'naftalan_central', 'park_naftalan', 'karvan', 'gold_naftalan', 'nur_naftalan', 'nafta', 'maricel', 'absheron', 'bilgya', 'guneshli', 'tskaltubo_spa', 'lankaran', 'shihovo', 'prometey', 'borjomi_likani']],
            'psoriaz' => ['Кожа / псориаз / экзема', 'Dəri / psoriaz / ekzema', 'Skin / psoriasis / eczema', 'kozha.jpg',
                ['chinar', 'garabagh', 'gashalti', 'suncity', 'naftalan_central', 'park_naftalan', 'karvan', 'gold_naftalan', 'nur_naftalan', 'maricel', 'absheron', 'bilgya', 'lankaran', 'shihovo']],
            'nevrologiya' => ['Неврология / невриты', 'Nevrologiya / nevritlər', 'Neurology / neuritis', 'nevrologiya.jpg',
                ['chinar', 'garabagh', 'gashalti', 'suncity', 'naftalan_central', 'park_naftalan', 'karvan', 'gold_naftalan', 'nur_naftalan', 'nafta', 'maricel', 'guneshli', 'miras', 'absheron', 'bilgya', 'tskaltubo_spa', 'borjomi_palace', 'lankaran', 'shihovo', 'prometey', 'borjomi_likani']],
            'zhkt' => ['ЖКТ / желудок / печень', 'MİT / mədə / qaraciyər', 'Digestive system / stomach / liver', 'zhkt.jpg',
                ['park_naftalan', 'absheron', 'miras', 'borjomi_palace', 'tskaltubo_spa', 'lankaran', 'sairme', 'borjomi_likani']],
            'urologiya' => ['Урология / гинекология', 'Urologiya / ginekologiya', 'Urology / gynecology', 'urologiya.jpg',
                ['chinar', 'garabagh', 'gashalti', 'suncity', 'naftalan_central', 'park_naftalan', 'gold_naftalan', 'nur_naftalan', 'maricel', 'miras', 'bilgya', 'absheron', 'tskaltubo_spa', 'borjomi_palace', 'lankaran', 'shihovo', 'sairme']],
            'kardio' => ['Кардио / гипертония', 'Kardiologiya / hipertoniya', 'Cardiology / hypertension', 'kardio.jpg',
                ['chinar', 'garabagh', 'gashalti', 'karvan', 'gold_naftalan', 'nur_naftalan', 'bilgya', 'absheron', 'tskaltubo_spa', 'borjomi_palace', 'lankaran']],
            'diabet' => ['Диабет / обмен веществ', 'Diabet / maddələr mübadiləsi', 'Diabetes / metabolism', 'metabolizm.jpg',
                ['garabagh', 'suncity', 'gashalti', 'absheron', 'tskaltubo_spa', 'lankaran', 'borjomi_likani']],
            'lor' => ['ЛОР / синусит / тонзиллит', 'LOR / sinusit / tonzillit', 'ENT / sinusitis / tonsillitis', 'lor.jpg',
                ['chinar', 'garabagh', 'gashalti', 'park_naftalan', 'gold_naftalan', 'suncity', 'absheron', 'borjomi_palace', 'tskaltubo_spa', 'lankaran', 'shihovo']],
            'dyhanie' => ['Дыхание / астма / бронхит', 'Tənəffüs / astma / bronxit', 'Respiratory / asthma / bronchitis', 'dyhanie.jpg',
                ['suncity', 'garabagh', 'maricel', 'absheron', 'bilgya', 'borjomi_palace', 'tskaltubo_spa', 'lankaran', 'sairme', 'shihovo']],
            'pochki' => ['Почки / пиелонефрит', 'Böyrək / piyelonefrit', 'Kidneys / pyelonephritis', 'pochki.jpg',
                ['chinar', 'gashalti', 'karvan', 'absheron', 'borjomi_palace', 'lankaran', 'sairme']],
            'allergiya' => ['Аллергия', 'Allergiya', 'Allergy', 'allergiya.jpg',
                ['lankaran', 'borjomi_palace', 'sairme']],
            'oftalmologiya' => ['Офтальмология', 'Oftalmologiya', 'Ophthalmology', 'oftalmologiya.jpg',
                ['gold_naftalan', 'naftalan_central']],
        ];
    }

    private function normalize(string $s): string
    {
        return trim(preg_replace('/[^\p{L}\p{N}]+/u', ' ', mb_strtolower($s)));
    }

    public function up(): void
    {
        if (!Schema::hasTable('diseases') || !Schema::hasTable('disease_sanatorium')) {
            return;
        }

        // Resolve sanatorium keys to service ids.
        $texts = [];
        foreach (DB::table('services')->select('id', 'slug')->get() as $s) {
            $texts[$s->id] = $this->normalize((string) $s->slug);
        }
        foreach (DB::table('service_translations')->select('service_id', 'title')->get() as $t) {
            if (isset($texts[$t->service_id])) {
                $texts[$t->service_id] .= ' | ' . $this->normalize((string) $t->title);
            }
        }

        $ids = [];
        foreach ($this->patterns() as $key => $regex) {
            $ids[$key] = [];
            foreach ($texts as $id => $text) {
                if (!preg_match($regex, $text)) {
                    continue;
                }
                if ($key === 'tskaltubo_spa' && preg_match($this->patterns()['prometey'], $text)) {
                    continue;
                }
                $ids[$key][] = $id;
            }
            if (!$ids[$key]) {
                Log::warning("diseases migration: sanatorium '{$key}' not found");
            }
        }

        $order = 0;
        foreach ($this->diseases() as $slug => [$ru, $az, $en, $image, $keys]) {
            $order++;
            $data = [
                'name_ru' => $ru,
                'name_az' => $az,
                'name_en' => $en,
                'image' => 'images/diseases/' . $image,
                'sort_order' => $order,
                'is_active' => true,
                'updated_at' => now(),
            ];
            $row = DB::table('diseases')->where('slug', $slug)->first();
            if ($row) {
                DB::table('diseases')->where('id', $row->id)->update($data);
                $diseaseId = $row->id;
            } else {
                $diseaseId = DB::table('diseases')->insertGetId($data + ['slug' => $slug, 'created_at' => now()]);
            }

            DB::table('disease_sanatorium')->where('disease_id', $diseaseId)->delete();
            $serviceIds = array_unique(array_merge(...array_map(fn ($k) => $ids[$k], $keys)));
            foreach ($serviceIds as $sid) {
                DB::table('disease_sanatorium')->insert([
                    'disease_id' => $diseaseId,
                    'sanatorium_id' => $sid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Hide the old image-less placeholder diseases replaced by the list above.
        DB::table('diseases')
            ->whereIn('slug', ['bolezni-sustavov', 'bolezni-kozhi', 'bolezni-nervnoj-sistemy', 'bolezni-organov-pishchevareniya', 'bolezni-oporno-dvigatel-nogo'])
            ->whereNull('image')
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        // Data migration; nothing to revert.
    }
};
