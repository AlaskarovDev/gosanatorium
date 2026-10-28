<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'borjomi-palace-sanatoriy-2026';

    private function reviews(): array
    {
        return [
            ['Елена М.', 'Москва', 5, '2025-09-06 11:20', 'Была 14 ночей в июне. Боржоми Палас — это бывшая крепость 1870 года, история чувствуется, но корпуса современные 2015 года реновация. Главное — вода Боржоми привозят 3 раза в день прямо в санаторий, не надо идти к источнику. Пила по 300 мл за 30 мин до еды как врач назначил. Желудок перестал болеть на 5-й день. Гастрит у меня 10 лет.'],
            ['Rauf', 'Bakı', 5, '2025-09-21 15:40', 'Mədə üçün getdim, xora var idi. Borjomi suyu 80-dən çox element var deyirlər. Həqiqətən kömək etdi. OxySmartLife kamerası var, Gürcüstanda ilk dəfə burda quraşdırılıb, Fransa istehsalı. 1 saat girdim, oksigen terapiyası. 164 nömrə var, tarixi korpusda qaldım, balkon +10$ gecə.'],
            ['Mehmet Y.', 'İstanbul', 5, '2025-10-04 10:05', 'Mide ve metabolizma için gittim. Rakım 900 metre, hava tertemiz. Borjomi suyu 3 kere geliyor binaya, sıcaklığını doktor ayarlıyor 10-50 derece. Detoks, SPA kapsül, tuz odası her gün. Yemekler Gürcü ve Avrupa, çok lezzetli.'],
            ['Hans Müller', 'Berlin', 5, '2025-10-19 18:30', 'I came for diabetes type 2 and obesity. Borjomi Palace has endocrinologist-nutritionist, diet therapy. They measured BMI, fat and muscle %. Water therapy + hydrogen therapy H2 + oxygen cocktail. Lost 4 kg in 14 days. Building is old fortress but modern inside. 170 km from Tbilisi.'],
            ['Айгуль', 'Алматы', 5, '2025-11-08 13:15', 'Была с дочкой 9 лет. Педиатр есть в штате, это важно. Детская комната с играми. Лечили ЖКТ. Сифонное орошение кишечника Боржоми водой — процедура 1 раз за 7 ночей, но эффект есть. Бассейн крытый 24 кв, круглый год.'],
            ['Dilshod', 'Tashkent', 5, '2025-11-23 16:50', 'Oshqozon uchun bordim. Borjomi suvi dunyoda mashhur, lekin manbasida ichish boshqa narsa. Shifokor gastroenterolog, kardiolog, ginekolog ham bor. Laboratoriya o’zida. 7-9 kecha paketda 2 fizioterapiya, 5 tuz xonasi, 3 SPA kapsula.'],
            ['Irina L.', 'Haifa', 5, '2025-12-12 12:10', 'Мы из Хайфы, по-русски говорят. Боржоми Палас в центре Боржоми, 15 мин до парка Боржоми-Харагаули пешком. Бювет с водой рядом. Брала пакет 10-13 ночей, там уже грязевые аппликации 1 зона, озонотерапия комбинезон 2 раза. Очень довольна.'],
            ['Olena', 'Kyiv', 5, '2026-01-05 19:25', 'Була 7 ночей. Економ 80$ за ніч одна людина з лікуванням і харчуванням — дешево. Вода Боржомі 3 рази на день в санаторій привозять. Інгаляції з Боржомі водою, сольова кімната 5 разів. Бронхіт хронічний, стало легше дихати.'],
            ['Elvin', 'Sumqayıt', 4, '2026-01-22 14:00', 'Gürcüstana vizasız getdim, amma 2026-dan sığorta məcburidir deyirlər, 30 lari cərimə var. Mən almışdım. Borjomi Palace-da hər şey binanın içində, bayıra çıxmağa ehtiyac yoxdur. Öz tibb mərkəzi var. 20-dən çox prosedur.'],
            ['Ayşe K.', 'Ankara', 5, '2026-02-14 11:45', 'Obezite için gittim. Endokrinolog çok iyi, diyet yazdı. Sifonik irrigasyon, tübaj, detoks kuru ve ıslak, hidrojen banyosu. 14 gece pakette 9 tuz odası, 5 SPA kapsül. Açık ve kapalı havuz var.'],
            ['Сергей К.', 'Екатеринбург', 5, '2026-03-03 17:35', 'Лечил подагру и панкреатит. Вода Боржоми щелочная, нормализует пищеварение. Дуоденальное зондирование делали, анализ желудочного сока. Врачи: пульмонолог, ортопед, рефлексотерапевт. Такого набора нигде не видел.'],
            ['Klaus Weber', 'Hamburg', 4, '2026-03-19 09:50', 'For stomach ulcer remission. Borjomi water delivered 3 times daily. Hyperbaric oxygen chamber OxySmartLife — first in Georgia, very modern. Salt room, hydrogen therapy. Historical building renovated 2013-2015.'],
            ['Мадина', 'Шымкент', 5, '2026-04-07 15:20', 'Ездила с мамой 65 лет. Есть номера для людей с ограниченными возможностями. Лифт есть. Маме кардиолог + ЭКГ сделали. Мне гинекологическое орошение Боржоми водой. 3 корпуса — исторический и 2 современных.'],
            ['Tural M.', 'Bakı', 5, '2026-04-24 20:10', 'Borjomi suyu mağazada içdiyimizdən fərqlidir, mənbədən başqadır. Həkim dozası və temperaturu təyin edir. Mənə 10 dərəcə soyuq verdi. Qaraciyər üçün yaxşı oldu. Qiymət 2 nəfər 150$ aşağı mövsüm.'],
            ['David Cohen', 'Tel Aviv', 5, '2026-05-09 12:40', 'I had gastritis and metabolic syndrome. Borjomi water has 80+ microelements. Drinking course 3 times a day before meals. Plus SPA capsule hydromassage with Borjomi water. Pool, gym, billiard daily free. Very good.'],
            ['Nigora', 'Samarkand', 5, '2026-05-26 10:30', 'Jigar uchun bordim. Borjomi Palace 1876 yildan beri bor ekan, tarixiy joy. Lekin ichi zamonaviy. 164 xona. OxySmart kamera fransuz, birinchi marta Gruziyada. Detoks terapiya qildim, yoqdi.'],
            ['Fatma G.', 'Bursa', 4, '2026-06-15 16:05', 'Mide için gittim ama solunum da düzeldi. İnhalasyon Borjomi suyu ile, tuz odası. 900 metre yükseklik, hava temiz. Tiflis’e 170 km, transfer ayarladılar.'],
            ['Андрей', 'Днепр', 5, '2026-07-02 18:45', 'Был 21 ночь, максимальный курс. Физио 8 процедур, кислородный коктейль 8, СПА-капсула 9, парафин 10, соляная комната 12. Это много, но для ЖКТ надо долго. Результат — минус 6 кг, желудок не болит.'],
            ['Petra L.', 'Düsseldorf', 5, '2026-07-20 13:55', 'Toller Ort. Borjomi Palace has own biochemical lab, balneo, physio, gynecology cabinets in building. No need to go out. Water Borjomi 3x daily. Salt room, detox, myostimulation. Breakfast 08:00-10:30 buffet.'],
            ['Zaur', 'Mingəçevir', 5, '2026-08-06 11:00', 'Ailəlik getdik, 3 nəfərlik nömrə 205$ aşağı mövsüm. Uşaq 6 yaşa qədər pulsuz. 6-12 yaş +30$. Biz 2 uşaqla getdik, biri pulsuz. Uşaq meydançası, oyun otağı var.'],
            ['Светлана', 'Ростов', 5, '2026-08-23 14:30', 'Лечила щитовидку и ожирение. Эндокринолог-нутрициолог назначил анализ на сахар, протромбин, копрологию, УЗИ щитовидки. Определение ИМТ и жировой массы. Диету соблюдала, вода Боржоми помогла.'],
            ['Murat K.', 'Almaty', 4, '2026-09-10 17:15', 'Поехал из Алматы через Тбилиси. До Боржоми 2,5 часа от Тбилиси. Санаторий в центре, все рядом. Виза не нужна, только паспорт и страховка с 2026 года. Персонал по-русски.'],
            ['Ayten', 'Bakı', 5, '2026-09-27 12:25', 'Kosmetologiya da var — qızıl maska, ilbiz terapiyası, mikro cərəyan. Üz masajı etdirdim. Borjomi suyu ilə vanna, 4 kameralı qalvanik vanna. Çox bəyəndim.'],
            ['Владимир', 'Haifa', 4, '2026-10-12 19:00', 'Были с женой. Люкс Романова 240$ низкий сезон. Просторный, исторический корпус. Винный бар Chveni Ojakhi, лобби-бар. Вечерняя программа. Лечение и отдых вместе.'],
            ['Emre', 'Izmir', 5, '2026-10-25 10:20', 'Mide ülseri için gittim. Doktorlar çok ilgili: gastroenterolog, kardiyolog, ürolog, jinekolog, nörolog, fizyoterapist. Her gün 3 öğün açık büfe, diyet de var. Borjomi suyu içmek için en iyi yer burası.'],
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
                'created_at' => $date . ':00',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $emails = [];
        for ($i = 1; $i <= 25; $i++) {
            $emails[] = 'guest-review-' . self::SLUG . '-' . $i . '@gosanatorium.invalid';
        }
        $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('reviews')->whereIn('user_id', $ids)->delete();
        DB::table('users')->whereIn('id', $ids)->delete();
    }
};
