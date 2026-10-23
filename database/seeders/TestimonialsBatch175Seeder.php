<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Language\App\Models\Language;
use Modules\Testimonial\App\Models\Testimonial;
use Modules\Testimonial\App\Models\TestimonialTrasnlation;

/**
 * Adds 20 testimonials (Task #175). Safe to run repeatedly: existing names are skipped.
 *
 * Run:  php artisan db:seed --class=TestimonialsBatch175Seeder
 */
class TestimonialsBatch175Seeder extends Seeder
{
    /** [country key, name, rating, trip type, text] */
    private array $items = [
        ['uz', 'Dilnoza Karimova', 5, 'Азербайджан · Отдых и сервис', 'Отдыхали с мамой, всё в целом понравилось. Номер был чистый, персонал вежливый, питание хорошее. Особенно понравилась спокойная атмосфера. Спасибо за гостеприимство!'],
        ['uz', 'Azizbek Rakhimov', 5, 'Грузия · Семейный отдых', 'Juda yaxshi dam oldik. Xodimlar samimiy, joy esa tinch va ozoda. Bir necha kun kundalik ishlardan uzoqlashib, rohatlandik. Yana kelishga arziydi.'],
        ['uz', 'Madina Yusupova', 4, '', 'Санаторий оставил хорошее впечатление. Понравились природа, чистота и отношение сотрудников. Хотелось бы немного больше разнообразия в меню, но в целом отдых прошёл хорошо.'],
        ['uz', 'Bekzod Tursunov', 5, '', 'Xizmat yaxshi, xona qulay, atrof juda chiroyli. Eng muhimi, shovqin yo\'q, bemalol dam olish mumkin. Oilaviy sayohat uchun yaxshi variant.'],
        ['ru', 'Елена Смирнова', 5, 'Азербайджан · Отдых', 'Хорошее место для спокойного отдыха. Номер чистый, персонал доброжелательный, питание вполне достойное. Понравилось, что на территории тихо и можно спокойно погулять.'],
        ['ru', 'Александр Волков', 4, '', 'В целом всё понравилось. Заселение прошло без проблем, сотрудники отвечали на вопросы и помогали, когда было нужно. Хотелось бы более разнообразного питания, но впечатление осталось хорошее.'],
        ['ru', 'Наталья Петрова', 5, '', 'Ездила отдохнуть и сменить обстановку. Очень понравилась природа, спокойная атмосфера и отношение персонала. Несколько дней прошли незаметно. С удовольствием рассмотрю это место для следующей поездки.'],
        ['ru', 'Дмитрий Соколов', 5, '', 'Удобное размещение, чистые комнаты и приветливые сотрудники. Всё необходимое было под рукой. Хороший вариант для тех, кто хочет отдохнуть подальше от городской суеты.'],
        ['il', 'Dana Cohen', 5, 'Грузия · Спокойный отдых', 'נהנינו מאוד מהאווירה השקטה ומהיחס של הצוות. החדר היה נקי והשהייה הייתה נעימה. מקום טוב למי שרוצה לקחת הפסקה מהשגרה.'],
        ['il', 'Avi Levi', 4, '', 'בסך הכול הייתה חוויה טובה. הצוות היה אדיב, הסביבה יפה והיה נעים לטייל באזור. הייתי שמח למבחר גדול יותר של אוכל, אבל נהנינו מהשהייה.'],
        ['il', 'Michal Avraham', 5, '', 'מקום שקט ונעים לחופשה. אהבתי במיוחד את הטבע ואת האווירה הרגועה. השירות היה טוב והרגשנו בנוח לאורך כל השהייה.'],
        ['il', 'Yossi Mizrahi', 5, '', 'הגענו לכמה ימים של מנוחה וחזרנו מרוצים. הצוות היה נחמד, החדר היה מסודר והאווירה הייתה נעימה. בהחלט חוויה חיובית.'],
        ['kz', 'Алия Нурланова', 5, 'Азербайджан · Семейный отдых', 'Отдыхали всей семьёй, остались довольны. Территория ухоженная, в номере чисто, сотрудники приветливые. Хорошее место, чтобы провести несколько спокойных дней.'],
        ['kz', 'Ерлан Садыков', 4, '', 'Всё прошло нормально, без неприятных сюрпризов. Понравились расположение и спокойная обстановка. Есть небольшие моменты, которые можно улучшить, но в целом отдыхом доволен.'],
        ['kz', 'Жанар Ахметова', 5, '', 'Очень приятная атмосфера, вежливый персонал и красивые окрестности. Отдельно понравилось, что можно спокойно гулять и не думать о повседневных делах. Спасибо за хороший приём!'],
        ['kz', 'Тимур Беков', 5, '', 'Неплохой вариант для отдыха и смены обстановки. С бронированием всё было понятно, разместили без лишней суеты. Условия соответствовали нашим ожиданиям.'],
        ['de', 'Anna Müller', 5, 'Грузия · Отдых', 'Ein schöner Ort für eine ruhige Auszeit. Das Zimmer war sauber, das Personal freundlich und die Umgebung sehr angenehm. Wir haben unseren Aufenthalt genossen.'],
        ['de', 'Thomas Schneider', 4, '', 'Insgesamt ein guter Aufenthalt. Der Check-in war unkompliziert und die Mitarbeiter waren hilfsbereit. Beim Essen wäre etwas mehr Auswahl schön gewesen. Trotzdem waren wir zufrieden.'],
        ['de', 'Sabine Weber', 5, '', 'Wer Ruhe und Natur sucht, ist hier gut aufgehoben. Die Atmosphäre war entspannt und die Anlage gepflegt. Wir konnten den Alltag für ein paar Tage hinter uns lassen.'],
        ['de', 'Michael Fischer', 5, '', 'Freundlicher Service, ein ordentliches Zimmer und eine angenehme Umgebung. Die Organisation hat gut funktioniert. Für einen erholsamen Aufenthalt eine gute Wahl.'],
    ];

    public function run(): void
    {
        if (!Schema::hasColumn('testimonials', 'country')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->string('country', 50)->nullable();
            });
        }

        $langCodes = Language::pluck('lang_code')->all() ?: ['en', 'ru'];

        foreach ($this->items as [$country, $name, $rating, $trip, $text]) {
            $exists = TestimonialTrasnlation::where('name', $name)->where('comment', $text)->exists();
            if ($exists) {
                continue;
            }

            $testimonial = new Testimonial();
            $testimonial->image = '';
            $testimonial->status = 'active';
            $testimonial->rating = $rating;
            $testimonial->country = $country;
            $testimonial->save();

            foreach ($langCodes as $code) {
                $t = new TestimonialTrasnlation();
                $t->testimonial_id = $testimonial->id;
                $t->lang_code = $code;
                $t->name = $name;
                $t->designation = $trip;
                $t->comment = $text;
                $t->save();
            }
        }
    }
}
