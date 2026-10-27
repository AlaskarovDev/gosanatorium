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
            ['Petra L.', 'Germany', 5, '2026-05-07', 'Toller Service. Transfer from Ganja free, no prepayment booking. The 20 ha pine and plane tree park is perfect for morning walks. My skin after atopic dermatitis much better.'],
            ['Michael Bauer', 'Germany', 5, '2026-01-12', 'Chinar is the biggest sanatorium in Naftalan, since 1982. You feel the experience. 3 physio procedures daily included - in EU you pay extra for each. Highly recommend for joint diseases.'],
            ['Sabine K.', 'Germany', 5, '2026-03-18', 'Ich war wegen Neurodermitis da. Applikationen mit Naphthalan + Infrarotlampe. Juckreiz weg nach 5 Tagen. Essen Diät nach Arzt. Super.'],
            ['Айгуль Н.', 'Kazakhstan', 5, '2026-04-22', 'Прилетела из Алматы через Баку в Гянджу. Трансфер встретил. 14 дней Стандарт пакет. Псориаз с 2015 года, ремиссия 8 месяцев после Чинара - для меня рекорд. Парк 20 гектаров, воздух сосновый.'],
            ['Ерлан Б.', 'Kazakhstan', 5, '2026-02-03', 'Суставы, подагра. Нафталановые ванны + йодобромные чередовали. Врач диетолог назначил диету. Бассейн 25 метров каждый день час. Очень доволен.'],
            ['Мадина К.', 'Kazakhstan', 5, '2026-05-15', 'Ездила с мамой, у нее артроз. Ей 62. Санаторий адаптирован для маломобильных. Персонал помогает. 3 физио в день - магнито, лазер, ультразвук. Результат есть.'],
            ['Руслан', 'Kazakhstan', 5, '2025-12-12', 'Брал Делюкс Сьют Premium двухкомнатный. 2 спальни, гостиная. Семьей 4 человека поместились. Дети бесплатно до 6 лет. Лечение и отдых вместе.'],
            ['Дана', 'Kazakhstan', 4, '2026-01-25', 'Экзема у дочки 9 лет. Лечение детей с 6 лет, взяли за 90 AZN в сутки с лечением. Помогло, кожа стала мягче. Питание 3 раза шведский стол.'],
            ['Тимур Ж.', 'Kazakhstan', 5, '2026-06-20', 'Второй раз в Нафталане, первый раз был в другом санатории. Чинар на порядок выше - Europe SPA сертификат, своя биохимическая лаборатория. Анализы на месте.'],
            ['Dilshod A.', 'Uzbekistan', 5, '2026-03-08', 'Bo’g’imlar uchun bordim. 14 kun qoldim. Naftalan vannasi 37-38 daraja, 10 daqiqa. Keyin fizioterapiya 3 xil har kuni. Yurishim yaxshilandi. Park juda katta, toza havo.'],
            ['Malika R.', 'Uzbekistan', 5, '2026-04-14', 'Psoriaz uchun keldim. 12 yil davolatdim O’zbekistonda. Faqat Chinar yordam berdi. Applikatsiya + vanna. Shifokorlar juda yaxshi, 14 mutaxassis bor ekan.'],
            ['Sardor', 'Uzbekistan', 5, '2026-02-28', 'Ganja aeroportidan bepul transfer kutib oldi. Mehmonxona 5 yulduz, haqiqatan ham. Basseyn 25 metr, sauna. Ovqat shved stoli, parhez ham bor.'],
            ['Nigora', 'Uzbekistan', 4, '2025-11-20', 'Ginekologik uchun bordim. Vaginal tampon tozalangan naftalan bilan. O’zbekistonda bunday narsa yo’q. Natija bor, rahmat.'],
            ['Jasur B.', 'Uzbekistan', 5, '2026-05-25', 'Narxi 182$ 2 kishi ovqat + davolanish bilan - juda arzon. Bizda bunday paket yo’q. Booking oldindan to’lovsiz. Tavsiya qilaman.'],
            ['David Cohen', 'Israel', 5, '2026-04-01', 'I came for psoriasis and psoriatic arthritis. Israeli doctors offered only steroids. In Chinar, 10 naftalan baths + 3 physio daily + diet. My plaques gone 70%. The Europe SPA certificate made me trust.'],
            ['Irina L.', 'Israel', 5, '2026-02-11', 'Мы из Хайфы, говорим по-русски. В Чинаре персонал говорит по-русски, очень удобно. Лечила колени, артроз. Бассейн теплый, ЛФК каждый день. Парк с платанами - как в санатории в СССР, но на 5 звезд.'],
            ['Moshe Ben', 'Israel', 5, '2026-03-25', 'Suffering from chronic prostatitis. Urological massage + rectal microclysters with purified naphthalan. 14 days Intensive package. Much better. Price includes all diagnostics.'],
            ['Svetlana R.', 'Israel', 5, '2026-05-18', 'Была с мужем. У него псориаз, у меня неврология. Лечили обоих. Трансфер из Гянджи бесплатно. Номера современные, не советские. 20 гектар парка, тихо.'],
            ['Yakov G.', 'Israel', 4, '2025-12-30', 'Good medical base. Own lab, X-ray, ultrasound. 14 specialists. For Israelis - no visa needed, flight to Baku then Ganja. Worth it.'],
            ['Олена К.', 'Ukraine', 5, '2026-04-08', 'Псоріаз 15 років. Після Чинар - вперше за 5 років чиста шкіра. Нафталанові ванни через день, всього 10. Фізіо 3 щодня - магніт, лазер, ампліпульс. Парк 20 га, сосни і чинари.'],
            ['Андрій М.', 'Ukraine', 5, '2026-01-14', 'Артроз колін, остеохондроз. Брав Стандарт пакет. Аналізи - гемограма, біохімія, сеча, ЕКГ, УЗД - все включено. Басейн 25х12 щодня година. Результатом задоволений.'],
            ['Марина П.', 'Ukraine', 5, '2026-03-02', 'Їздила з мамою. Мама гінекологія, я дерматологія. Лікарі - терапевт, дерматолог, гінеколог, уролог - всі є. Europe SPA сертифікат не дарма дали. Сервіс топ.'],
            ['Володимир', 'Ukraine', 5, '2026-05-30', 'Був 21 день. Рекомендую мінімум 14. За 7 днів ефекту мало. За 21 день суглоби як нові. Безкоштовний трансфер з Гянджі, парковка на 200 місць.'],
            ['Ірина', 'Ukraine', 5, '2026-06-25', 'Вдруге в Чинарі. Перший раз у 2022, зараз у 2026. Стало ще краще - ремонт, нові апарати. Нафталан - єдине що допомагає від екземи. Дякую команді санаторію!'],
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
            $email = 'guest-review-' . self::SLUG . '-' . ($i + 27) . '@gosanatorium.invalid';

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
        for ($i = 27; $i <= 50; $i++) {
            $emails[] = 'guest-review-' . self::SLUG . '-' . $i . '@gosanatorium.invalid';
        }
        $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('reviews')->whereIn('user_id', $ids)->delete();
        DB::table('users')->whereIn('id', $ids)->delete();
    }
};
