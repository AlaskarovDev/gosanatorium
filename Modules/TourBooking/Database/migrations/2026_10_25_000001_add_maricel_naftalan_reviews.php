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
            ['Monika S.', 'Germany', 5, 'Bei einem Sanatorium ist mir wichtig, dass die Behandlungen gut organisiert sind und man weiß, an wen man sich bei Fragen wenden kann. Vor allem die ärztliche Beratung und die Abläufe während des Aufenthalts sollten stimmen. Das sind für mich die entscheidenden Punkte bei der Wahl einer solchen Einrichtung.'],
            ['Klaus M.', 'Germany', 5, 'Für einen längeren Aufenthalt zählen nicht nur die Behandlungen, sondern auch Ruhe, Sauberkeit und gutes Essen. Vor der Buchung würde ich genau prüfen, welche Leistungen im Preis enthalten sind und wie der Tagesablauf aussieht.'],
            ['Дилноза Р.', 'Uzbekistan', 5, 'Для меня главное в санатории — чтобы врач внимательно выслушал, объяснил назначенные процедуры и ответил на вопросы. При лечении в Нафталане особенно важно заранее уточнить противопоказания и подобрать программу вместе со специалистом.'],
            ['Бахром Т.', 'Uzbekistan', 5, 'Когда едешь в санаторий на несколько дней или недель, хочется, чтобы всё было понятно: проживание, питание, расписание процедур и дополнительные расходы. Эти детали лучше уточнить заранее, чтобы спокойно спланировать поездку.'],
            ['Айгерим С.', 'Kazakhstan', 5, 'При выборе санатория я обращаю внимание не на красивые фотографии, а на медицинскую часть: консультацию врача, перечень процедур и условия их проведения. Перед приездом важно получить подробную информацию о программе лечения.'],
            ['Нурлан К.', 'Kazakhstan', 4, 'Перед поездкой важно понимать, что входит в стоимость путёвки, а за что нужно доплачивать отдельно. Я бы сравнивал не только цену номера, но и питание, количество процедур и общие условия проживания.'],
            ['Ольга В.', 'Russia', 5, 'Нафталановые процедуры — это то, ради чего многие едут в этот регион. Но начинать лечение нужно после консультации врача, а не выбирать процедуры самостоятельно. Перед бронированием стоит уточнить состав лечебной программы и её продолжительность.'],
            ['Сергей М.', 'Russia', 4, 'Для меня в санатории важны понятное расписание, спокойная обстановка и нормальные условия отдыха между процедурами. Ещё стоит заранее узнать, как организовано питание и можно ли обсудить индивидуальные потребности.'],
            ['David L.', 'Israel', 5, 'Before choosing a sanatorium abroad, I want clear information about the medical consultation, treatment plan and any additional costs. For me, good communication and realistic expectations are just as important as the accommodation.'],
            ['Sarah K.', 'Israel', 5, 'When planning a wellness stay, I look at the whole experience: the room, meals, daily schedule and access to medical staff. Having these details explained clearly beforehand makes it much easier to decide whether a place is suitable.'],
            ['Əli H.', 'Azerbaijan', 5, 'Sanatoriya seçərkən mənim üçün əsas məsələ müalicənin həkim məsləhəti ilə təşkil olunmasıdır. Hansı prosedurların uyğun olduğunu və kursun neçə gün davam edəcəyini əvvəlcədən bilmək istəyərdim. Bu məlumatlar seçim edərkən çox vacibdir.'],
            ['Günel M.', 'Azerbaijan', 5, 'Naftalana müalicə və istirahət üçün gələnlər üçün yaşayış şəraiti ilə yanaşı yemək, təmizlik və prosedurların vaxtında keçirilməsi də əhəmiyyətlidir. Rezervasiya etməzdən əvvəl qiymətə daxil olan xidmətləri dəqiqləşdirmək yaxşı olar.'],
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

        foreach ($this->reviews() as $i => [$name, $country, $rating, $text]) {
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
                'created_at' => now()->subDays(12 - $i),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $emails = [];
        for ($i = 1; $i <= 12; $i++) {
            $emails[] = 'guest-review-' . self::SLUG . '-' . $i . '@gosanatorium.invalid';
        }
        $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('reviews')->whereIn('user_id', $ids)->delete();
        DB::table('users')->whereIn('id', $ids)->delete();
    }
};
