<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'lankaran-springs-wellness-resort-2026';

    private function reviews(): array
    {
        return [
            ['Елена П.', 'Москва', 5, '2025-09-05 11:20', 'Была 14 дней по Стандартному пакету. Это единственный 5* термальный курорт Азербайджана, реально. 12 источников Истису прямо на территории, вода хлоридно-натриевая. Пила 3 раза в день из бювета. Для ЖКТ и обмена веществ супер. 11 гектар территории, сад с прудом. Субтропики, чайные плантации вокруг.'],
            ['Ramil H.', 'Bakı', 5, '2025-09-23 15:40', 'Ailəlik getdik, 182 nömrə var, biz terraslı standart götürdük — 108 kv terası var, panorama meşəyə baxır. 16x12 qapalı hovuz, açıq hovuz, uşaq hovuzu və termal hovuz — hamısı pulsuz. Uşaq klubu animatorla pulsuz. Transfer Lənkəran aeroportundan pulsuz, 10 dəqiqə.'],
            ['Mehmet K.', 'Istanbul', 5, '2025-10-08 10:05', 'Tansiyon ve metabolizma için gittim. Lankaran Springs 2019\'da yenilenmiş, her yer tertemiz. 9 katlı bina, otel, tıp merkezi ve SPA aynı binada, dışarı çıkmıyorsun. Günde 3 kez İstisu içme kürü. 9 uzman doktor var. Çok memnun kaldım.'],
            ['Hans Müller', 'Berlin', 5, '2025-10-26 18:30', 'I came for stress and chronic fatigue. Took ZEN program 12 days — gut-brain axis, morning walks with breathing, yoga, meditation, thermal bath, music therapy. Unique. 12 thermal springs, subtropical climate, near Caspian Sea. Eco label Hotel Sustainability Basics.'],
            ['Айгуль Н.', 'Алматы', 5, '2025-11-11 13:15', 'Была с мамой. У мамы артроз, брали Медицинский пакет при артрозе — спец программа для суставов. Минеральные ванны 36-39 градусов, йодобромные, сероводородные, скипидарные. Душ Шарко, циркулярный. Лечение детей с 12 лет, нам подошло.'],
            ['Dilshod', 'Tashkent', 5, '2025-11-27 16:50', 'Qandli diabet 2 tip uchun bordim. Istisu suvi metabolizmni yaxshilaydi deyishdi. 3 mahal ichdim. Basseyn 4 ta, fin sauna, turk hamam, bug’ hammomi — hammasi bepul. Ovqat Sumakh restoranda shved stoli, dietik ham bor.'],
            ['Irina S.', 'Haifa', 5, '2025-12-09 12:10', 'Мы из Хайфы. 258 км от Баку на юг, субтропики. Талышские горы, хвойные леса. Воздух другой. 12 источников, вода исследована в Германии и в России. Ванны минеральные каждый день, 3 физио в день по Стандарту. Понравилось.'],
            ['Olena K.', 'Kyiv', 5, '2025-12-22 19:25', 'Була 7 ночей Базовий пакет. Діагностика: ЕКГ, рентген грудної клітки, УЗД черевної порожнини, аналізи крові 18 параметрів. Лікування: ванни мінеральні щодня + питне лікування 3 рази + 1 фізіо на день. Для профілактики вистачило.'],
            ['Elvin M.', 'Gəncə', 5, '2026-01-14 14:00', 'Lənkəran Springs 5 ulduz həqiqətən. Restoran Kuku-da balıq levengi, cənub plovu, nərə balığı xüsusi bişirilir. Barbekü bar bağda. Termal hovuz İstisu suyu ilə, çox qəşəng. Fitnes zalı pulsuz.'],
            ['Ayşe D.', 'Ankara', 4, '2026-02-04 11:45', 'Eklem için gittim ama stres de geçti. ZEN programı 4-8-12 gün, ben 8 gün aldım. Fonksiyonel yoga, meditasyon, termal balneoterapi. Lənkəran Həftoni qəsəbəsində yerləşir, çox sakit.'],
            ['Сергей В.', 'Екатеринбург', 5, '2026-02-19 17:35', 'Был 21 день Интенсивный пакет. Там уже 3 физио + 2 доп процедуры в день. Парафиновые обертывания с нафталаном (6 процедур за 21 день). Гидроколонотерапия 25 AZN доплатил. Результат по суставам есть.'],
            ['Klaus Bauer', 'Hamburg', 4, '2026-03-10 09:50', 'Only 5-star balneo resort in Azerbaijan, 12 natural Istisu springs on site. Own drinking pavilion. Indoor pool 16x12, outdoor, kids, thermal. All in one 9-storey building. 10 min from Caspian Sea and Lankaran airport.'],
            ['Мадина К.', 'Шымкент', 5, '2026-03-27 15:20', 'Ездила одна. Стандарт с балконом — 141 номер такой категории, вид на лес и горы. Интерьер минимализм+классика. Ортопедические кровати. Ежедневная уборка. Вид панорамный.'],
            ['Tural', 'Bakı', 5, '2026-04-13 20:10', 'Premium Medical paket götürdüm, ən dolu paket. Geniş analizlər, fərdi proqram, eksklüziv SPA. Transfer çimərliyə pulsuz, Xəzərə apardılar. 10 dəqiqəlik yol.'],
            ['David Ben', 'Tel Aviv', 5, '2026-04-29 12:40', 'Suffering from gout and obesity. Istisu water high mineralization, doctor prescribed for internal and external use. Drinking 3 times daily normalized digestion. Free transfer from Lankaran airport.'],
            ['Nigora', 'Bukhara', 5, '2026-05-12 10:30', 'Endokrin uchun bordim. Shifokorlar 9 ta — terapevt, kardiolog, endokrinolog, dermatolog, ginekolog. Lab o’zida. Har kuni mineral vanna 36-39 daraja. Yoqdi.'],
            ['Fatma Y.', 'Bursa', 4, '2026-05-28 16:05', 'Tiroit ve metabolizma için gittim. Sülfür banyoları cildi yeniliyor, kaplıcalar. Sirküler duş, sualtı duş masajı. Çok memnun kaldım. Personel Rusça İngilizce biliyor.'],
            ['Андрей', 'Киев', 5, '2026-06-16 18:45', 'Был 14 дней Стандарт. Диагностика: АЛТ, АСТ, глюкоза. Лечение: 3 физио в день + парафиновые обертывания 4 раза. Ванны минеральные каждый день. Питье 3 раза. Бассейн 16х12 бесплатно.'],
            ['Petra S.', 'München', 5, '2026-07-03 13:55', 'Sehr schön. Subtropical climate, mountain air, tea plantations. 11 ha park with pond. Eco label second year. Hotel Sustainability Basics — energy, water saving. Parking free.'],
            ['Zaur M.', 'Sumqayıt', 5, '2026-07-21 11:00', 'Uşaqla getdim, 12 yaşdan müalicə verirlər, mənim oğlum 13 yaş, götürdülər. King Suite 73 kv, 2 ədəd var, lüks. Ailəvi nömrə 46 kv. Hamısında balkon, kondisioner, mini-bar, seyf.'],
            ['Светлана', 'Казань', 5, '2026-08-07 14:30', 'Брала программу YENEE — детокс лечебный. Очищение организма. Гидроколонотерапия, озоновая ванна, лимфодренаж. После 7 дней легкость.'],
            ['Murat', 'Almaty', 4, '2026-08-25 17:15', 'Прилетел через Баку. 258 км от Баку до Ленкорани. Трансфер из Ленкорани бесплатно. Санаторий единственный 5* бальнео в Азербайджане, аналогов нет. Всё в одном здании, удобно.'],
            ['Ayten', 'Bakı', 5, '2026-09-12 12:25', 'Restoran Sumakh-da 3 dəfə şved masası — səhər 08:00-11:00, günorta 13:00-16:00, axşam 19:00-21:00. Canlı musiqi həftəsonu. Kuku restoranında levengi əla idi.'],
            ['Oksana', 'Dnipro', 5, '2026-10-09 19:00', 'Була з чоловіком. Чоловік артроз, я — стрес. Він — ванни мінеральні, я — ZEN програма. Розваги: боулінг, більярд, дартс, лук і стріли, страйкбол, міні-футбол, кінотеатр. Не нудно.'],
            ['Emre Can', 'Izmir', 5, '2026-10-26 10:20', 'Sadece otel değil, tam termal kür merkezi. 12 kaynak, içme kür, 4 havuz, sauna, hamam, buhar banyosu, jakuzi, sedir fıçısı. Masaj Bali ve Tayland teknikleri. Çok iyi.'],
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
