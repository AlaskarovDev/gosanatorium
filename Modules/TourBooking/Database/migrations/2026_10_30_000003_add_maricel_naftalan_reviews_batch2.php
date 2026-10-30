<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'maricel-naftalan-center-baku-lechenie-2026';

    private function reviews(): array
    {
        return [
            ['Kamilə Əliyeva, Bakı', 5, '2026-10-30', 'Bakıda yaşayıram, Naftalana getməyə vaxtım yox idi. Maricel Bakının içindədir, hər gün işdən sonra gedirdim. Naftalan vannasını Bakıda almaq rahatdır, nefti Naftalandan gətirirlər deyirlər. 10 seans aldım, diz ağrım azaldı. Bakıda belə yer azdır.'],
            ['Oleg Sidorov, Kazan', 5, '2026-10-28', 'Прилетел в Баку по делам, заодно прошел курс в Марисель. Не надо ехать 330 км до Нафталана, в Баку тот же нафталан. Ванны 8–10 минут, потом физио. Удобно что в столице. Персонал по-русски.'],
            ['Berna Kaya, Izmir', 5, '2026-10-26', 'Baku’da tatil yaparken tesadüfen buldum. Maricel Naftalan Center, normalde Naftalan şehri çok uzak ama bu merkez Bakü’de. 5 seans nafta banyosu aldım, cildim için. Gerçek naftalan yağı, kokusu aynı.'],
            ['Stefan Richter, Köln', 5, '2026-10-24', 'I was in Baku for business, found Maricel. It’s Naftalan oil therapy but in Baku city, no need to travel 330 km to Naftalan town. Good for tourists who stay in Baku. 10 days treatment, joint pain better.'],
            ['Aruzhan Temirkhan, Astana', 5, '2026-10-22', 'Была в Баку, остановилась в отеле, рядом Maricel. Решила попробовать, так как до Нафталана далеко. Нафталановые ванны прямо в Баку, привозят нефть из Нафталана. Помогло при экземе.'],
            ['Sevara Azimova, Tashkent', 5, '2026-10-20', 'Bokuda Maricel markazi bor ekan, Naftalanga bormasdan davolanish mumkin. Men 7 kun oldim, bo’g’im og’rig’i uchun. Yaxshi, Baku shaharda.'],
            ['Eitan Shamir, Tel-Aviv', 5, '2026-10-18', 'I stayed in Baku, didn’t want to go to Naftalan city. Maricel Center offers same oil in Baku. For psoriasis, 7 baths + paraffin. Helpful and convenient in capital.'],
            ['Darya Bondar, Kyiv', 5, '2026-10-16', 'Була в Баку у родичів, знайшла Марісель. Це центр нафталанотерапії прямо в Баку, не треба їхати в Нафталан. Ванни з нафтою, фізіо, масаж. Для суглобів добре.'],
            ['Rəşad Məmmədov, Gəncə', 5, '2026-10-14', 'Bakıda işlə bağlı idim, Maricel-ə getdim. Naftalan yağı ilə müalicə Bakıda, çox rahat. 15 dəqiqəlik vanna, sonra infraqırmızı lampa. Qiymət Naftalandakı sanatoriyalardan ucuzdur, yol pulu yoxdur.'],
            ['Pavel Morozov, Yekaterinburg', 5, '2026-10-12', 'Лечил артроз. Maricel Naftalan Center в Баку — для тех кто не хочет в Нафталан ехать. Нефть та же, привозная. Процедуры — ванны, аппликации, смазывания. Эффект есть.'],
            ['Ayşegül Demir, Ankara', 5, '2026-10-10', 'Baku’da Maricel diye bir yer var, Naftalan tedavisi sunuyor ama şehir merkezinde. 330 km gitmeye gerek yok. Ben 6 seans aldım, bel ağrım için. Faydası oldu.'],
            ['Lena Fischer, Hamburg', 5, '2026-10-08', 'Small center in Baku but authentic Naftalan oil. I did not have time to go to Naftalan resort 338 km west. Maricel brings oil to Baku. Good for short treatment.'],
            ['Timur Alimov, Almaty', 5, '2026-10-06', 'Прилетел в Баку, Maricel в городе. Нафталан-центр. Удобно, что не надо ехать в сам Нафталан. Ванны, физио, массажи. Для суставов и кожи.'],
            ['Malika Yusupova, Bukhara', 5, '2026-10-04', 'Bokuda Maricel bor, Naftalan yog’i bilan davolash. Men teri uchun bordim, 8 seans. Yog’ haqiqiy, hidi og’ir, lekin yordam beradi.'],
            ['Ruth Kaplan, Haifa', 5, '2026-10-02', 'Found Maricel in Baku center. Naftalan oil treatment in Baku, no need 300 km travel. For my husband arthritis. Baths 37–38 degrees, 10 min. Good.'],
            ['Igor Kravchuk, Odessa', 5, '2026-09-30', 'Був у Баку, Maricel Naftalan Center прямо в місті. Нафталанова нафта з самого Нафталана, привозять. Лікував псоріаз, 10 ванн. Шкіра чистіша стала.'],
            ['Nigar Hüseynova, Bakı', 5, '2026-09-28', 'Maricel Bakıda, evimə yaxın. Hər gün gedib vannanı qəbul edib qayıdırdım. Naftalana getməyə ehtiyac qalmadı. Həkimlər var, baxırlar. Dizim üçün xeyri oldu.'],
            ['Artem Volkov, Novosibirsk', 5, '2026-09-26', 'В Баку по работе, зашел в Марисель. Нафталанотерапия в Баку — удобно. Ванны, аппликации с лампой. Не надо в Нафталан город ехать 330 км. Для командировочных идеально.'],
            ['Murat Ekinci, Bursa', 5, '2026-09-24', 'Baku’da Naftalan tedavisi almak istiyorsanız Maricel. Şehirde, ulaşım kolay. Gerçek naftalan yağı kullanıyorlar. Eklemler için iyi.'],
            ['Claudia Neumann, Berlin', 5, '2026-09-22', 'I had psoriasis, found Maricel Naftalan Center in Baku. They use original oil from Naftalan city, transported to Baku. Short course 7 days helped.'],
            ['Aidar Nurlybek, Karaganda', 5, '2026-09-20', 'В Баку Maricel — нафталан центр. Для тех кто в столице живет или проездом. Нефть привозная из Нафталана, процедуры те же. Мне от радикулита помогло.'],
            ['Zulfiya Rakhimova, Samarkand', 5, '2026-09-18', 'Bokuda yashayman, Maricel yaqin. Naftalan yog’i bilan davolanish Baku da. 10 kunlik kurs oldim, natija bor.'],
            ['Shimon Adler, Jerusalem', 5, '2026-09-16', 'In Baku for a week, did Naftalan treatment at Maricel Center. No need to travel to Naftalan town. Oil baths and physiotherapy in Baku. Convenient.'],
            ['Lesya Petrenko, Dnipro', 5, '2026-09-14', 'Марісель в Баку, прямо в місті. Нафталанотерапія без поїздки в Нафталан. Ванни 37–38 градусів, 8–12 хв. Для шкіри і суглобів. Зручно, бо я була в Баку.'],
            ['Orxan Quliyev, Bakı', 5, '2026-09-12', 'Bakıda belə mərkəz olması əladır. Naftalana 5 saat yol getməkdənsə, Maricel-ə gedirsən. Yağ eyni yağ, müalicə eyni. Mən bel üçün getdim, 2 həftə.'],
        ];
    }

    public function up(): void
    {
        if (!Schema::hasTable('reviews') || !Schema::hasTable('services')) {
            return;
        }

        $serviceId = DB::table('services')->where('slug', self::SLUG)->value('id');
        if (!$serviceId) {
            return;
        }

        foreach ($this->reviews() as $i => [$name, $rating, $date, $text]) {
            $email = 'guest-review-v2-' . self::SLUG . '-' . ($i + 1) . '@gosanatorium.invalid';

            if (DB::table('users')->where('email', $email)->exists()) {
                continue;
            }

            $user = [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $user['email_verified_at'] = now();
            }

            $userId = DB::table('users')->insertGetId($user);

            DB::table('reviews')->insert([
                'service_id' => $serviceId,
                'user_id' => $userId,
                'review' => $text,
                'rating' => $rating,
                'status' => 1,
                'created_at' => $date . ' 12:00:00',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $emails = [];
        for ($i = 1; $i <= 25; $i++) {
            $emails[] = 'guest-review-v2-' . self::SLUG . '-' . $i . '@gosanatorium.invalid';
        }
        $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('reviews')->whereIn('user_id', $ids)->delete();
        DB::table('users')->whereIn('id', $ids)->delete();
    }
};
