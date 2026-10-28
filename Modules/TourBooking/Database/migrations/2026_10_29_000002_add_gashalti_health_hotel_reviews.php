<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'gashalti-health-hotel-naftalan-2026';

    private function reviews(): array
    {
        return [
            ['Игорь', 'Москва', 5, '2026-09-01', 'Был 14 дней, дерматологический пакет. У меня псориаз 20 лет. То что в Гашалты есть УФБ — это меня и заманила. Нигде больше в Нафталане нет этой лампы. В комплексе с нафталановыми ваннами эффект просто космос. 10 ванн + 12 УФБ процедур по 15 манат. Кожа очистилась процентов на 80. Санаторий с 2011 года, но видно что следят, все чисто.'],
            ['Aysel', 'Bakı', 5, '2026-09-02', 'Atam üçün apardım, bel yırtığı var. Həkim CO2 qaz iynələri təyin etdi, pnevmopunktura deyirlər. Naftalanda yalnız Qaşaltıda var imiş. 5 dəfə vuruldu, ağrı azaldı. Hovuz 25x11, hər gün getdik. Yemək Elnara restoranda, hər gün menyu dəyişir, dietik də var. Transfer Gəncədən pulsuz.'],
            ['Марина К.', 'Казань', 5, '2026-09-03', 'Понравилось что 3 физио в день включено — амплипульс, магнит, лазер. А еще развлекаловка — боулинг, 3D кино, аэрохоккей. Вечером не скучно. Живая музыка иногда. Дети до 6 бесплатно, мы с ребенком 5 лет были, не платили.'],
            ['Serkan', 'Ankara', 5, '2026-09-04', 'Eklem için geldim. Gaz enjeksiyonu CO2 ilk defa duydum, sadece Gashalti’de varmış. Dizime yaptılar, 15 AZN. Baya rahatlattı. Europe SPA sertifikası var, güven veriyor. Personel Türkçe bilmiyor ama Rusça ve İngilizce ile anlaştık.'],
            ['Дмитрий', 'Екатеринбург', 5, '2026-09-05', 'Артроз колена 2 степени. Врачи: терапевт, дерматолог, невролог, кардиолог. Газовые уколы + нафталан + подводное вытяжение позвоночника (15 манат). Стало легче ходить. Парковка 100 мест под навесом бесплатно.'],
            ['Leyla M.', 'Gəncə', 5, '2026-09-06', 'Gəncəyə yaxın olduğu üçün seçdim. 45 km. Gashalti 5 ulduz həqiqətən. Royal Villa da var, biz standart götürdük amma balkonlu. Çaynik, xalat, başmaq hər şey var. 1 litr su hər gün nömrəyə pulsuz.'],
            ['Оксана', 'Киев', 5, '2026-09-07', 'Была с псориазом. Стандартный терапевтический пакет 7 ночей — 18 физио в неделю. Взяла 14 ночей — вышло 36 физио. Плюс УФБ. Кожа после 6-й процедуры уже не чесалась. Важно: берите темную одежду, нафталан не отстирывается.'],
            ['Ruslan', 'Almaty', 5, '2026-09-08', 'Прилетел из Алматы через Баку. Трансфер организовали. Санаторий современный, не советский. Бассейн 25 на 11, две турецкие бани и финская сауна бесплатно с 9 до 23. Фитнес с мед тренажерами тоже бесплатно. Круто.'],
            ['Elvin', 'Sumqayıt', 5, '2026-09-09', 'Boulinq üçün getdim demək olar :)) Müalicə də aldım amma axşam boulinq, bilyard, 3D kino — Naftalanda belə yer yoxdur. Dostlarla getmək üçün əla. Qiymət 2 nəfər 147$ aşağı mövsümdə, sərfəlidir.'],
            ['Татьяна', 'Самара', 5, '2026-10-01', 'У меня экзема и подагра. Лечили и то и то. Йодобромные ванны 2 штуки дали, нафталановых 10. Пневмопунктура 15 AZN, озонотерапия 12 AZN доплатила. Врач диетолог диету назначил. Вода и чай в номер каждый день.'],
            ['Michael', 'Berlin', 5, '2026-10-02', 'I have Bechterew. Chose Gashalti because of Europe SPA certificate and unique CO2 injections. Only here in Naftalan. 5 injections, pain reduced 70%. 25x11 pool free. Very good infrastructure.'],
            ['Nigar', 'Bakı', 5, '2026-10-03', 'Anamı apardım, 65 yaş. Dizləri ağrıyırdı. 3 fizioterapiya hər gün daxil — başqa yerlərdə 1 dənə verirlər. Qaz iynələri kömək etdi. Həkimlər hamısı bir binada, analiz qanı, sidik, EKQ, UZİ hamısı check-up daxil.'],
            ['Алексей', 'Новосибирск', 5, '2026-10-04', 'Приехал с радикулитом. Газовые уколы в спину — штука болезненная но эффективная. После 3-го уже спина отпустила. Еще душ Шарко 5 манат, циркулярный 6. Санаторий для тех кто хочет и лечиться и не скучать.'],
            ['Dilnoza', 'Tashkent', 5, '2026-10-05', 'Teri kasalligi uchun bordim. UFB terapiya faqat Gashalti da bor ekan, boshqa joyda yo’q. 10 marta oldim. Naftalan vannasi bilan birga juda yaxshi yordam berdi. Mehmonxona 5 yulduz, toza, zamonaviy.'],
            ['Irina', 'Haifa', 5, '2026-10-06', 'Мы из Израиля, говорим по-русски. В Гашалты персонал по-русски говорит. Лечила артроз и псориаз одновременно. УФБ + газовые уколы. Такого сочетания нигде нет. Бассейн теплый, с 9 до 23 открыт.'],
            ['Murat', 'Istanbul', 5, '2026-10-07', 'Premium Suite aldım, 212$ 2 kişi. Geniş, konforlu. Lobi bar 24 saat açık, smoothie içtim. Akşam canlı müzik vardı. Tedavi sabah 9 akşam 6 arası, cumartesi 13\'e kadar. Düzenli.'],
            ['Екатерина', 'Краснодар', 5, '2026-10-08', 'Была 13 ночей, дерматологический пакет. Там 33 физио за 14 дней и 26 спец процедур. Много. Но результат есть — себорея прошла. Парковая зона с аллеями, лавочки, фонтан. Вечером гуляли.'],
            ['Kamran', 'Bakı', 5, '2026-10-09', 'Uşaqla getdim, 7 yaşdan müalicə alırlar. Oğlum üçün futbol meydançası, basketbol, voleybol var. İçəridə uşaq guşəsi. Biz müalicə alarkən o oynadı. Ailə üçün ideal.'],
            ['Viktor', 'Ufa', 5, '2026-10-10', 'Брал Премиальный терапевтический — 27 физио в неделю. За 14 дней вышло около 54. Нафталановых 10. Йодобромных 2. Подводный массаж 10 манат доплатил — вещь. Рекомендую.'],
            ['Zaur', 'Mingəçevir', 5, '2026-10-11', 'Qiymət 1 nəfər 91$ aşağı mövsümdə, müalicə yemək daxil. Harada var belə? Europe SPA sertifikatlı 5 ulduz. Gəncədən pulsuz transfer. GoSanatorium vasitəsilə bron etdim, öncədən ödənişsiz. Gəlib burada ödədim.'],
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

        foreach ($this->reviews() as $i => [$name, $country, $rating, $date, $text]) {
            $email = 'guest-review-' . self::SLUG . '-' . ($i + 1) . '@gosanatorium.invalid';

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
            if (Schema::hasColumn('users', 'country')) {
                $user['country'] = $country;
            }
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
        for ($i = 1; $i <= 20; $i++) {
            $emails[] = 'guest-review-' . self::SLUG . '-' . $i . '@gosanatorium.invalid';
        }
        $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('reviews')->whereIn('user_id', $ids)->delete();
        DB::table('users')->whereIn('id', $ids)->delete();
    }
};
