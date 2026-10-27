<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'naftalan-central-wellness-resort-2026';

    private function reviews(): array
    {
        return [
            ['Svetlana K.', 'Russia', 5, '2026-06-12', 'Прилетели с мужем 12 ночей в мае. Взяли акцию - оплатили 10 вместо 12, до 30 июня акция еще действует. Санаторий совсем новый, 2023 года, пахнет еще ремонтом в хорошем смысле. Все блестит. Брала Премиум пакет - 63 процедуры вышло. Ударно-волновая реально помогла плечу. Берите темную одежду, светлую футболку испортила нафталаном, не отстирывается.'],
            ['Ramil M.', 'Azerbaijan', 5, '2026-07-07', 'Ailəlik getdik. Junior Suite götürdük, meyvə səbəti gətirdilər girişdə. Uşaq 8 yaş, 7 yaşdan müalicə alır deyə götürdülər. Xoşuma gələn yoqa səhər açıq havada, sonra pilates. Başqa sanatoriyalarda belə şey yoxdur. Yemək Zümrüd restoranda, hər gün fərqli. Hamam 10-cu mərtəbədə, qadın saatı 15:00-18:00.'],
            ['Aigerim', 'Kazakhstan', 5, '2026-07-28', 'Была одна 7 ночей, оплатила 6. Стандарт Плюс брала. За 7 ночей 29 процедур. Нафталановые ванны 5, массаж 3, парафин 3. Врачей 6, все внимательные: ревматолог, дерматолог, кардиолог с ЭКГ. Лаборатория своя, анализы с 8 до 10:30. Территория 10 тыс кв, уютно.'],
            ['Mehmet', 'Turkey', 5, '2026-08-21', 'Eklem ağrıları için geldim. Burası klasik sanatoryum gibi değil, wellness resort. Her gün yoga ve pilates hocası var, detoks içecek veriyorlar. Bunu Naftalan\'da başka yerde görmedim. 88 odalı, sakin. Personel Türkçe de biliyor.'],
            ['Елена', 'Russia', 5, '2026-08-25', 'Лечила псориаз. УФО-Б лампа + нафталан - отличное комбо. В других местах УФО отдельно платное, тут в пакете. Номер Стандарт 29 кв, с балконом, чайник, халат, тапочки. Лифта 2, очереди нет. 9 этажей.'],
            ['Farid', 'Azerbaijan', 5, '2026-09-04', 'Gəncədən transfer pulsuzdur, 30 dəqiqəyə çatdıq. Həqiqətən yenidir, avadanlıqlar təzə. Murov Bistro parkda gözəldir, axşam çay içdik. Qiymət 200 AZN 2 nəfər gecə - sərfəlidir.'],
            ['Olga P.', 'Russia', 5, '2026-08-27', 'Мне 58 лет, остеохондроз. Лечебное отделение пн-сб до 17:00 работает, а не до обеда как везде. Йога каждый день - сначала скептически, потом втянулась. Детокс-напитки имбирь-лимон, вкусные. Сауна финская и хамам бесплатно на 10 этаже.'],
            ['Jasur', 'Uzbekistan', 5, '2026-09-01', 'Toshkentdan Baku, keyin Ganja. Transfer bepul. Naftalan Central eng yangisi ekan, 2023 ochilgan. 2 paket bor ekan, men Premium oldim. 14 kunda 71 ta protsedura oldim. Natija bor.'],
            ['Anna', 'Germany', 5, '2026-08-20', 'Ich war wegen Neurodermitis. Das neue Gebäude, 88 Zimmer, sehr ruhig. UVB-Therapie war inklusive, das hat mir in Deutschland immer extra gekostet. Yoga daily included - unique for Naftalan. Dark clothes advice is real, take black T-shirts.'],
            ['İlqar', 'Azerbaijan', 5, '2026-06-22', 'Atamı apardım, 70 yaşı var. Diz artrozu. Udarno-volnovaya terapiya 4 dəfə elədilər, xeyri var. 6 həkim var, hamısı bir yerdə baxır. Oğlan bilyard oynadı, uşaq otağı da var. Ailə üçün yaxşıdır.'],
            ['Marina', 'Ukraine', 5, '2026-04-27', 'Була 10 ночей. Акція 12-10 спрацювала, заплатила за 10. Стандарт Плюс. Ванни нафталанові 8 штук. Масаж класичний, роблять добре. Парафінотерапія на шию. Ресторан Yaqut цілодобово - вночі чай пила.'],
            ['David', 'Israel', 5, '2026-04-03', 'I chose it because it\'s new. Opened 2023. No old Soviet smell. 88 rooms, 170 guests max. Very clean. Lab, ECG, ultrasound all in one building. Staff speaks English and Russian. Shockwave therapy helped my knee.'],
            ['Сабина', 'Russia', 5, '2026-06-24', 'Ездила с подругой. Брали два Стандарта. Понравилось что без предоплаты бронь через GoSanatorium, оплата при заселении. Документы - только паспорт, виза не нужна. Йога на улице утром - кайф. Вид на парк Самэда Вургуна.'],
            ['Tural', 'Azerbaijan', 5, '2026-06-09', 'Premium paket götürdüm. 5 gecə 30 prosedur, 7 gecə 39 prosedur - saylar saytda düz yazılıb. Həkimlər rusca danışır, hamısını başa düşdüm. Fitnes zalı 09:00-21:00 açıq, pulsuz.'],
            ['Наталья', 'Russia', 5, '2026-07-12', 'Псориаз волосистой части. УФО-Б + нафталан. Перхоть ушла на 5 день. Дерматолог назначил ещё лимфодренаж. 6 специалистов - этого достаточно. Кафе на террасе классное.'],
            ['Erkan', 'Turkey', 5, '2026-04-25', 'Burası 4 yıldız ama 5 yıldız gibi. Yeni bina, 9 kat, 2 asansör. Odalar 29-58 m2. Ben 58\'lik aile odasında kaldım. Çay seti, kasa, mini bar var. Her gün yoga pilates olması beni buraya çekti. Memnun kaldım.'],
            ['Viktor', 'Russia', 5, '2026-09-24', 'Был 17 ночей - оплатил 14 по акции. Очень выгодно. Премиум пакет: нафталановых ванн 12, массажей 7, парафина 7, лимфодренаж 8, ударно-волновой 5, физио 45. Итого 84 процедуры. Устал, но результат отличный.'],
            ['Leyla', 'Azerbaijan', 5, '2026-05-23', 'Anamla getdim. 130 AZN 1 nəfər, 200 AZN 2 nəfər Superior. Yemək 3 dəfə şved masası, Azərbaycan mətbəxi də var. Hamam türk, sauna fin. Təmiz.'],
            ['Оксана', 'Belarus', 5, '2026-07-14', 'Лечила дерматит. Детокс-напитки каждый день - отличная фишка. И йога с тренером. Территория 10 000 кв, небольшая, но рядом парк Вургуна, там гуляли. Персонал говорит по-русски.'],
            ['Kamran M.', 'Azerbaijan', 5, '2026-04-27', 'İkinci dəfə Naftalanda idim, birinci başqa yerdə. Central daha cavanlar üçündür, wellness konsepti. Köhnə sanatoriya havası yoxdur. 10-cu mərtəbədə wellness zonası pulsuz. Bilyard, tennis, nərd var. Darıxdırıcı deyil.'],
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
