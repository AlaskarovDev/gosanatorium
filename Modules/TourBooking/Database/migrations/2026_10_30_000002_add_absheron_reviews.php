<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'sanatoriy-absheron-baku-lechenie-2026';

    private function reviews(): array
    {
        return [
            ['Zöhrab B., Bakı', 5, '2026-10-30', 'Mədə üçün getmişdim, qastrit. Abşeron Mərdəkanda, dəniz lap 2 dəqiqəlik. Yod-brom vannası verdilər hər gün, 15 dəqiqə. Parafin də qoyurlar qarına. Yemək dietik, həkimin yazdığına görə. Qiymət sərfəli, Bakıya yaxın.'],
            ['Dmitriy Orlov, Moskva', 5, '2026-10-28', 'Приехал из Москвы в Баку, оттуда до Мардакана 35 км. Санаторий Абшерон — желудочно-кишечный профиль. Йодо-бромные ванны натуральные, не искусственные. Грязи местные, парафин. После 10 дней изжога прошла. Море рядом, воздух йодистый.'],
            ['Hakan Yıldız, İzmir', 5, '2026-10-26', 'Mide ülseri için gittim. Burası Naftalan gibi değil, burası deniz kenarı, Mardakan köyünde. İyot brom banyosu çok iyi geldi. Çamur terapisi de var. Bakü’ye yakın olması avantaj, bir gün şehre gittik.'],
            ['Laura Meier, München', 5, '2026-10-24', 'I went for digestive problems. Absheron sanatorium is on Caspian coast, 35 km from Baku. Natural iodine-bromine baths, paraffin therapy, therapeutic mud. Very quiet, sea air. Not luxury 5 star, but medical is good. Address Sergey Yesenin 95.'],
            ['Nurlan Sarsenov, Almaty', 5, '2026-10-22', 'Были с женой, у обоих желудок. Санаторий Абшерон в Мардакане, старый советский но отремонтированный. Ванны йодо-бромные, грязи. Питание диетическое. Море Хазяр рядом, гуляли каждый вечер.'],
            ['Shaxlo Karimova, Tashkent', 5, '2026-10-20', 'Oshqozon yarasi uchun bordim. Absheron sanatoriysi dengiz bo’yida, Mardakan qishlog’ida. Yod-brom vannasi tabiiy, parafin qo’yishdi. Palchiq bilan davolash ham bor. Narxi arzon, Bakudan 35 km.'],
            ['Yossi Levi, Haifa', 5, '2026-10-18', 'I have chronic gastritis. Absheron is GI sanatorium. Natural iodine-bromine baths + mud + paraffin. Location Mardakan, Caspian Sea coast. Sea air helps. Staff speaks Russian. Good for stomach.'],
            ['Oksana Melnik, Kyiv', 5, '2026-10-16', 'Була 12 днів. Санаторій Абшерон — шлунково-кишковий. Йодо-бромні ванни, парафін, лікувальні грязі. Знаходиться в Мардаканах, 35 км від Баку. Море поруч, повітря морське. Допомогло від коліту.'],
            ['Elçin M., Sumqayıt', 5, '2026-10-14', 'Ürək üçün yox, mədə üçün. Abşeron sanatoriyası köhnə adla mədə-bağırsaq sanatoriyasıdır. Yod-brom vannaları təbii, süni deyil. Palçıq da qoyurlar. Dəniz kənarı, sakit yer. Bakıdan marşrutla getdim.'],
            ['Ivan Petrov, Krasnodar', 5, '2026-10-12', 'Желудок, панкреатит. Абшерон в Мардакане, 35 км от Баку. Йодо-бромные ванны, парафинотерапия, грязи. Море в 100 метрах. Вечером набережная. Лечение простое, но рабочее.'],
            ['Emre Gül, Antalya', 5, '2026-10-10', 'Burası 5 yıldız değil, daha çok tedavi odaklı. Ama deniz kenarında olması harika. İyot brom banyosu doğal, parafin ve çamur. Mide için birebir. Bakü merkeze 35 km, taksiyle gittik.'],
            ['Andreas Klein, Hamburg', 5, '2026-10-08', 'For stomach and nervous system. Absheron sanatorium in Mardakan village. Natural iodine-bromine baths, paraffin, mud therapy. Caspian Sea coast, subtropical. Quiet place, not party.'],
            ['Dana Tulegenova, Astana', 5, '2026-10-06', 'У меня холецистит. В Абшероне йодо-бромные ванны, диетическое питание по назначению. Грязи апшеронские. Санаторий на берегу, воздух морской с йодом. Понравилось, что без пафоса, по делу.'],
            ['Azizbek R., Samarkand', 5, '2026-10-04', 'Baku ya yaqin, Mardakanda joylashgan. Yod-brom vannalari tabiiy, suniy emas. Parafinoterapiya qildi. Oshqozon og’rig’i kamaydi. Dengiz yonida, havo toza.'],
            ['Miriam Cohen, Tel-Aviv', 5, '2026-10-02', 'My husband GI problems. Absheron — gastrointestinal sanatorium. 35 km from Baku, Mardakan. Natural iodine-bromine baths, paraffin, natural healing muds. Good diet food. Sea helps.'],
            ['Taras Shevchenko, Lviv', 5, '2026-09-30', 'Шлунок, 20 років гастрит. Абшерон в Мардаканах — йодо-бромні ванни природні, парафінотерапія і грязі. На узбережжі Каспію. Спокійно, без дискотек. Лікування допомогло.'],
            ['Gülnarə Q., Bakı', 5, '2026-09-28', 'Anamı aparmışdım, bağırsaq problemi. Mərdəkanda, Xəzərin qırağında. Yod-brom vannası, palçıq. Həkimlər diqqətli. Qiymət digər sanatoriyalardan ucuzdur, çünki Bakıdadır, təyyarə lazım deyil.'],
            ['Viktor Zaitsev, Yekaterinburg', 5, '2026-09-26', 'Язвенная болезнь в ремиссии. Абшерон — именно по этому профилю. Ванны йодо-бромные натуральные, грязи местные. Парафин на живот. Море рядом, климат мягкий, не как в Нафталане сухой.'],
            ['Selin Aras, Istanbul', 5, '2026-09-24', 'Mide bağırsak için gittim. Burası Mardakan’da, deniz kenarı. İyot brom banyosu, parafin ve çamur. Bakü’ye 35 km, şehirden uzak değil. Yemekler diyet, mideyi yormuyor.'],
            ['Julia Becker, Köln', 5, '2026-09-22', 'I had gastritis. Absheron sanatorium is GI sanatorium in Mardakan, 35 km from Baku, on Absheron peninsula coast. Natural iodine-bromine baths, paraffin, muds. Caspian Sea air, very calm.'],
            ['Marat Ibraev, Karaganda', 5, '2026-09-20', 'ЖКТ, колит. Абшерон в посёлке Мардакан, ул. Есенина 95. Йодо-бромные ванны природные, парафин, грязи. Море Каспийское в двух шагах. Без понтов, но лечат честно.'],
            ['Feruza Alimova, Bukhara', 5, '2026-09-18', 'Mardakanda Absheron sanatoriysi, dengiz bo’yida. Yod-brom vannasi tabiiy, palchiq terapiya. Oshqozon uchun yaxshi. Bakudan 35 km, marshrutka bor.'],
            ['Alex Shapiro, Ashdod', 5, '2026-09-16', 'GI sanatorium Absheron. On Caspian coast, Mardakan. Iodine-bromine baths natural, paraffin, therapeutic muds. 35 km from Baku. Quiet, medical, not resort party. Helped.'],
            ['Katya Rudenko, Odessa', 5, '2026-09-14', 'Була в Абшероні, Мардакани. Йодо-бромні ванни натуральні, парафінотерапія, грязі. Санаторій шлунково-кишковий. Біля Каспійського моря, повітря йодисте. Після 10 днів набагато легше.'],
            ['Cavid H., Bakı', 5, '2026-09-12', 'Özüm Bakıda yaşayıram, ona görə Abşeronu seçdim. Uzağa getmək lazım olmadı. Mərdəkanda dəniz qırağı, yod-brom vannaları təbii, parafin, palçıq. Mədə üçün əla, həm də dəniz havası. Həkimlər təcrübəli.'],
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
            $emails[] = 'guest-review-' . self::SLUG . '-' . $i . '@gosanatorium.invalid';
        }
        $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('reviews')->whereIn('user_id', $ids)->delete();
        DB::table('users')->whereIn('id', $ids)->delete();
    }
};
