<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'chinar-hotel-spa-naftalan-2026';

    private function reviews(): array
    {
        return [
            ['Elena M.', 'Russia', 5, '2026-03-22', 'Была 14 дней в марте 2026. Псориаз 12 лет, после Чинара - кожа почти чистая. Нафталановые ванны брала 10 штук, как по протоколу. Очень понравилось что включено 3 физио каждый день - в других санаториях Нафталана этого нет. Парк огромный, реально 20 гектар, гуляла каждое утро.'],
            ['Сергей К.', 'Russia', 5, '2026-04-10', 'Приехал с артрозом колена. Врач-терапевт и физиотерапевт составили план. После 5-й ванны боль ушла процентов на 70. Бассейн 25 метров теплый, сауна по назначению. Номера делюкс свежие, после ремонта. Питание шведский стол, диету соблюдают.'],
            ['Ирина В.', 'Russia', 5, '2026-02-14', 'Второй раз в Чинаре. Первый раз была в 2019, сейчас намного лучше стало. Лаборатория своя, все анализы за 1 день - кровь, моча, УЗИ, ЭКГ. Europe SPA сертификат не просто бумажка, сервис реально европейский.'],
            ['Алексей и Марина', 'Russia', 5, '2026-05-03', 'Брали Standard на 10 ночей. Трансфер из Гянджи бесплатный, встретили. Дети до 6 лет бесплатно - для нас было важно. Сосновый воздух + платаны, скульптуры по парку прикольные. Лечение помогло обоим.'],
            ['Татьяна', 'Russia', 4, '2026-01-18', 'Лечила экзему. Ванны + аппликации нафталаном + лампа. Чешется меньше стало уже на 6-й день. Из минусов - процедуры только до обеда, в субботу до 12. Но в целом 9.4 на букинге оправдан.'],
            ['Виктор П.', 'Russia', 5, '2025-11-07', 'Мужики, кто с простатитом - езжайте. Урологический массаж 10 манат, микроклизмы с нафталаном. Мне реально помогло. Врач уролог толковый.'],
            ['Ольга Смирнова', 'Russia', 5, '2025-12-20', 'Анкилозирующий спондилит у мужа. Брали Интенсивный пакет. Рентген всех отделов сделали на месте, озонотерапия, ударно-волновая. Ходить стал лучше. 40 лет санаторию - чувствуется школа.'],
            ['Дмитрий', 'Russia', 5, '2026-06-15', 'Booking без предоплаты бронировал через gosanatorium. Все честно. 14 дней вышло 2550$ на двоих с лечением. Дешевле чем в Кисловодске, а эффект в разы сильнее.'],
            ['Rauf M.', 'Azerbaijan', 5, '2026-03-05', 'Atam üçün apardım, 68 yaşı var, dizləri ağrıyırdı. 14 gün qaldıq. Həkimlər 14 nəfərdir, hamısı baxdı. Naftalan vannası 8 dəqiqə, sonra fizioterapiya. İndi atam pilləkən düşə bilir. Parkda çinar ağacları möhtəşəmdi.'],
            ['Leyla H.', 'Azerbaijan', 5, '2026-04-28', 'Psoriaz üçün getmişdim. 10 vanna + 10 fizioterapiya. Dərim 80% təmizləndi. Hovuz 25 metrlik isti, hər gün 1 saat. Yeməklər dietik də var, həkim yazır. Transfer pulsuz idi.'],
            ['Elvin', 'Azerbaijan', 5, '2026-02-09', 'Deluxe Suite götürdüm. 20 hektar ərazi, 200 yerlik parking pulsuz. WiFi yaxşı. Ən çox xoşuma gələn 3 fizioterapiya hər gün daxil olması idi. Başqa sanatoriyalarda 1 dənə verirlər.'],
            ['Aysel Q.', 'Azerbaijan', 5, '2025-11-15', 'Anamla getdik. 1982-ci ildən işləyir deyə inamlı getdik. Tibbi baza həqiqətən ən böyüyü imiş Naftalanda. Öz laboratoriyası var. Qiymətlər noyabrda daha ucuz idi.'],
            ['Kamran', 'Azerbaijan', 4, '2026-01-22', 'Bel yırtığı üçün getdim. Parafin, traktsiya, LFK. Həkimlər rusca da danışır, başa düşmək asan. Bir az yeməklər eyni idi hər gün amma müalicə əla.'],
            ['Nərgiz', 'Azerbaijan', 5, '2026-05-11', 'Qadın xəstəliklərinə görə getmişdim. Endometrit. Vaginal tamponlar təmizlənmiş naftalanla, çox kömək etdi. Ginekoloq çox diqqətli idi. Europe SPA sertifikatı olan yeganə yerdi deyirlər, doğrudur.'],
            ['Tural Ə.', 'Azerbaijan', 5, '2026-03-30', 'Uşaqla getdim, 8 yaşı var. Uşaq üçün 90 AZN gündəlik müalicə ilə. Uşaq hovuza getdi, mən vannaya. Hər şey bir yerdə. Tövsiyə edirəm.'],
            ['Səbinə M.', 'Azerbaijan', 5, '2026-06-02', 'Booking.com-da reytinqi 9.4 boş yerə deyil. Personal çox mehriban. Fitnes zalı 200 kv, pulsuz. Bütün analizlər daxil.'],
            ['Mehmet Y.', 'Turkey', 5, '2026-04-17', 'Sedef hastalığı için geldim. 12 senedir çekiyorum. Naftalan yağı banyosu 10 seans aldım. Kaşıntı bitti, lekeler %70 azaldı. Chinar 40 yıllık tecrübe, belli oluyor. Parkta çınar ağaçları efsane.'],
            ['Ayşe K.', 'Turkey', 5, '2026-02-25', 'Romatoid artrit için 14 gün kaldım. Günde 3 fizyoterapi dahil olması harika. Başka yerde yok. Havuz 25x12, sauna doktor önerisiyle. Ganja havalimanından ücretsiz transfer aldık.'],
            ['Hakan Ö.', 'Turkey', 5, '2026-05-20', '170 odalı ama kalabalık hissettirmiyor, alan çok büyük 20 hektar. Deluxe oda çok modern. Yemekler açık büfe, diyet de var. Doktorlar Rusça ve İngilizce biliyor.'],
            ['Fatma G.', 'Turkey', 4, '2025-12-08', 'Bel fıtığı için gittim. Çamur değil, naftalan yağı. Çok farklı. Elektroterapi, manyetik terapi her gün. Sonuçtan memnunum. Fiyatlar yüksek sezonda biraz yüksek.'],
            ['Emre Can', 'Turkey', 5, '2026-01-30', 'Babamı götürdüm prostat için. Üroloji masaj ve mikroklizma uyguladılar. Fayda gördü. Azerbaycan vizesiz, çok rahat.'],
            ['Zeynep D.', 'Turkey', 5, '2026-03-14', 'Europe SPA sertifikalı olması için seçtim. Gerçekten kalite farklı. Laboratuvar kendi içinde, tahlil için dışarı gitmiyorsun. 14 uzman doktor var.'],
            ['Murat K.', 'Turkey', 5, '2026-06-08', 'Full board değil, tedavi paketli aldık. Kahvaltı öğle akşam dahil. Çocuk 6 yaşa kadar ücretsizmiş. Her şey düşünülmüş.'],
            ['Hans Müller', 'Germany', 5, '2026-04-05', 'Ich kam wegen Psoriasis. In Deutschland hilft nichts langfristig. 14 Tage Chinar, 10 Naftalan-Bäder. 80% besser. Das ist kein normales Spa, das ist medizinisches Zentrum mit Europe SPA Zertifikat. Sehr professionell.'],
            ['Anna Schmidt', 'Germany', 5, '2026-02-19', 'My husband has Bechterew. The Intensive package includes X-ray of spine, ozone therapy, shock wave. Doctors speak English. Park with 200 parking places free. Very good value for 5-star.'],
            ['Klaus Weber', 'Germany', 4, '2025-11-28', 'Rheuma seit 10 Jahren. Nach 7 Nächten schon beweglicher. Die 25m Pool mit Thermotherapie jeden Tag inklusive. Einziger Minuspunkt: Behandlung nur Mo-Sa.'],
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
        for ($i = 1; $i <= 26; $i++) {
            $emails[] = 'guest-review-' . self::SLUG . '-' . $i . '@gosanatorium.invalid';
        }
        $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('reviews')->whereIn('user_id', $ids)->delete();
        DB::table('users')->whereIn('id', $ids)->delete();
    }
};
