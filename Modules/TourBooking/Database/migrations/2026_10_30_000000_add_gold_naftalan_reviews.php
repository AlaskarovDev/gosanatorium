<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const SLUG = 'gold-naftalan-hotel-spa-2026';

    private function reviews(): array
    {
        return [
            ['Сергей К., Москва', 5, '2026-10-28', 'Был 14 дней лечебный пакет от 7 ночей. Тут 19 нафталановых ванн — самый большой бальнеоцентр в Нафталане, очередей нет. Женских больше, мужских 8. Ванны через день по 8–12 минут, 37–38 градусов. Суставы перестали хрустеть после 6-й. В каждом номере балкон, у меня вид на горы.'],
            ['Leyla H., Bakı', 5, '2026-10-26', 'Atam üçün apardım, psoriaz var. Gold Naftalan-da SPA 400 kv.m, sertifikatlı masajçılar. Yaşıl çay skrabı, kofe skrabı etdilər. 5-ci mərtəbədə çay terapiyası var — bitki çayları, fitoterapiya, heç bir başqa sanatoriyada yoxdur. Çox bəyəndik.'],
            ['Mehmet A., İstanbul', 5, '2026-10-24', 'Diz artrozu için geldim. Burada iğne tedavisi akupunktur var, Naftalan’da nadir. 15 AZN. Naftalan banyosu ile birlikte etkisi artıyor dediler. Doğruymuş. 2,5 hektar alan çınar ve çam ormanı içinde. Hava harika.'],
            ['Hans Weber, Berlin', 5, '2026-10-22', 'I chose Gold Naftalan because of 19 baths and 400 m2 SPA. 145 rooms, 6 floors. Every room has balcony — this is noted by all guests. My room view to garden. Salt room, tea therapy on 5th floor unique. Transfer from Ganja free.'],
            ['Айгуль, Алматы', 5, '2026-10-20', 'Была с мужем, у него простатит. Уролог, гинеколог, кардиолог, дерматолог — все в одном здании, поликлиника 10 отделений. Анализы — кровь общий, биохимия, сифилис, ВИЧ, гепатит B C, моча, УЗИ — всё включено при брони от 7 ночей. Удобно.'],
            ['Dilshod, Tashkent', 5, '2026-10-18', 'Bo’g’imlar uchun bordim. Naftalan nefti faqat bir joyda bor — Naftalanda, 50–60% naften uglevodorod, yallig’lanishga qarshi kuchli. 8–14 vanna kurs. Men 10 ta oldim. Natija 3 oy saqlanadi deyishdi.'],
            ['Irina P., Haifa', 5, '2026-10-16', 'Мы из Хайфы. 19 ванн — это реально много, не ждешь. Иглотерапия 15 AZN, озонотерапия 10 AZN доплатила. Бассейн крытый 4x10 полуолимпийский, открытый тоже есть. Турецкий хаммам, сауна бесплатно.'],
            ['Olena, Dnipro', 5, '2026-10-14', 'Була 10 ночей. Харчування Agat ресторан 250 місць, шведський стіл, халяльне м\'ясо, власна випічка. Дієтичне під контролем дієтолога. Жива музика ввечері. Балкон в кожному номері — зранку повітря Малого Кавказу.'],
            ['Elvin, Sumqayıt', 5, '2026-10-12', 'Fitnes 200 kv.m, trenajorlar elektron və mexaniki. Müalicə vaxtı formadan düşmədim. Qiymət 7 gecədən yuxarı paketdə hər şey daxil — yemək, vanna, parafin, fizioterapiya. 7 gecədən az olanda yalnız yemək və hovuz.'],
            ['Ayşe K., Ankara', 5, '2026-10-10', 'Sedef için geldim. Naftalan + tuz odası + çay terapisi. 5. kat tamamen bitki çayları, doğal otlar, çok farklı. Başka hiçbir Naftalan sanatoryumunda yok. Çok sevdim.'],
            ['Дмитрий, Екатеринбург', 5, '2026-10-08', 'Артрит и экзема. В Gold Naftalan соляная комната, галотерапия. ЛОР, кожа, стресс — помогает. Парафиновые аппликации по назначению. Лечебные отделения с 09:00 до 21:00, дольше чем везде.'],
            ['Klaus Müller, Hamburg', 4, '2026-10-06', 'For joint pain. Acupuncture rare for Naftalan, available here. 19 naftalan baths, 1 iodine, 1 aromatic. Most baths for women. Very experienced doctors.'],
            ['Мадина, Шымкент', 5, '2026-10-04', 'Ездила с дочкой 12 лет, лечение детей с 6 лет. Standard 23 кв (117 номеров таких), есть Superior 40 кв до 4 взрослых. Мы взяли Superior. Балкон, ЖК ТВ 81 см, мини-бар, фен.'],
            ['Tural M., Baku', 5, '2026-10-02', 'Gold Naftalan-da hər nömrədə balkon var, bu hamının qeyd etdiyi xüsusiyyətdir. Mənzərə dağa və bağa. Restoran Shusha a la carte açıq havada, pulludur amma dadlı. Diamond Bar-da təzə sıxılmış şirələr.'],
            ['David Cohen, Tel Aviv', 5, '2026-09-30', 'Came for psoriasis and chronic prostatitis. Tampons with de-resined naphthalan 15 AZN, inhalations with naphthalan 8 AZN. Good urological procedures. Balcony in every room, mountain view.'],
            ['Nigora, Samarkand', 4, '2026-09-28', 'Teri uchun bordim. Naftalan vannalari 8–12 daqiqa, har kuni emas, kun ora. Shifokorlar tajribali. SPA 400 kv, massaj oyoq, yuz, tana. Yoqdi.'],
            ['Fatma G., Bursa', 5, '2026-09-26', 'Bel fıtığı için gittim. İğne terapisi + naftalan + tuz odası. 10 bölümlü poliklinik: kardiyoloji, jinekoloji, üroloji, nöropatoloji, ultrason, dermatoloji, KBB. Hepsi bir binada.'],
            ['Андрей, Киев', 5, '2026-09-24', 'Был с женой. Deluxe 94 кв трёхкомнатный, 2 спальни + гостиная, до 5 взрослых + ребёнок. 2 телевизора 81 и 101 см. Мебель изысканная. Парковка 200 авто бесплатно.'],
            ['Petra L., Düsseldorf', 5, '2026-09-22', 'Toll. 145 rooms 4 categories. Fitness 200 m2 free. Indoor semi-Olympic pool 4x10 free. Outdoor pool seasonal. Hammam, sauna free. Tennis court, billiard, table tennis, bike rental, football field.'],
            ['Zaur, Mingachevir', 5, '2026-09-20', 'Qiymət 6 gecə +1 aksiyası var idi, 7 gecə qaldım 6 ödədim. GoSanatorium vasitəsilə bron, öncədən ödəniş yoxdur. Gəlib burada ödədim. Sanatoriya kartı məcburi deyil.'],
            ['Светлана, Казань', 5, '2026-09-18', 'Лечила гинекологию — эндометрит. Гинекологический тампон с обессмоленным нафталаном 15 AZN, с лекарствами 12 AZN. Помогло. Врач гинеколог внимательный.'],
            ['Murat, Almaty', 4, '2026-09-16', 'Прилетел Москва–Гянджа 3 часа, оттуда 50 км до Нафталана, трансфер бесплатный. Виза не нужна. Персонал говорит по-русски, по-английски, по-турецки и по-арабски.'],
            ['Aysel, Bakı', 5, '2026-09-14', 'Uşaqla getdim, 6 yaşa qədər pulsuz. 6 yaşdan müalicə alırlar. Mənim qızım 7 yaş, götürdülər. Uşaq meydançası yelləncək, sürüşkən var, disko bar, konfrans zalı.'],
            ['Oksana, Dnipro', 4, '2026-09-12', 'Була з псоріазом. Ванни нафталанові головна процедура, потім аплікації і змащування з інфрачервоною лампою. Ефект тримається кілька місяців, у деяких кілька років кажуть.'],
            ['Emre Can, Izmir', 5, '2026-09-10', 'Sadece tedavi değil, tatil de. Açık havuz güneşlenme alanı, tenis kortu, bilardo, yürüyüş alanları, açık satranç. Çocuk oyun alanı. Mini market, eczane, güzellik salonu. Her şey var.'],
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
