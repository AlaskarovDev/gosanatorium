<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\Review;

/**
 * Bütün aktiv sanatoriyalara avtomatik müsbət (3–5 ulduz) rus rəyləri əlavə edir.
 * Hər rəy bir-birindən fərqlidir (mətn fraqmentlərdən qurulur).
 *
 * İşə salmaq:  php artisan db:seed --class=ServiceReviewsSeeder
 *
 * Təkrar işə salınsa, artıq rəyi olan servisləri ötürür (dublikat yaratmır).
 */
class ServiceReviewsSeeder extends Seeder
{
    /** Hər servis üçün minimum/maksimum rəy sayı */
    private int $minPerService = 3;
    private int $maxPerService = 6;

    public function run(): void
    {
        $reviewers = $this->ensureReviewers();
        if ($reviewers->isEmpty()) {
            $this->command?->error('Rəyçi istifadəçilər yaradıla bilmədi.');
            return;
        }

        $usedTexts = [];   // qlobal təkrarların qarşısını almaq üçün
        $created   = 0;
        $skipped   = 0;

        $services = Service::where('status', true)->get(['id']);
        $this->command?->info("Aktiv servis sayı: {$services->count()}");

        foreach ($services as $service) {
            // Artıq rəyi varsa ötür (idempotent)
            if (Review::where('service_id', $service->id)->exists()) {
                $skipped++;
                continue;
            }

            $count = random_int($this->minPerService, $this->maxPerService);

            for ($i = 0; $i < $count; $i++) {
                // Unikal mətn qur
                $text = $this->buildText();
                $guard = 0;
                while (isset($usedTexts[$text]) && $guard < 12) {
                    $text = $this->buildText();
                    $guard++;
                }
                $usedTexts[$text] = true;

                $reviewer = $reviewers->random();
                $daysAgo  = random_int(10, 400);
                $when     = now()->subDays($daysAgo)->subHours(random_int(0, 23))->subMinutes(random_int(0, 59));

                Review::create([
                    'service_id'   => $service->id,
                    'user_id'      => $reviewer->id,
                    'booking_id'   => null,
                    'review'       => $text,
                    'rating'       => $this->weightedRating(),
                    'status'       => 1,                       // təsdiqli — dərhal görünsün
                    'is_featured'  => random_int(0, 4) === 0,  // ~20% featured
                    'review_title' => $this->titles()[array_rand($this->titles())],
                    'created_at'   => $when,
                    'updated_at'   => $when,
                ]);
                $created++;
            }
        }

        $this->command?->info("Bitdi. Yaradılan rəy: {$created}, ötürülən servis (artıq rəyi var): {$skipped}");
    }

    /** ~25 saxta rəyçi istifadəçi — varsa təkrar yaratmır */
    private function ensureReviewers()
    {
        $names = [
            'Елена Смирнова', 'Дмитрий Иванов', 'Ольга Кузнецова', 'Сергей Попов',
            'Наталья Соколова', 'Андрей Лебедев', 'Ирина Козлова', 'Михаил Новиков',
            'Татьяна Морозова', 'Владимир Петров', 'Марина Волкова', 'Алексей Васильев',
            'Светлана Зайцева', 'Николай Павлов', 'Анна Соловьёва', 'Игорь Богданов',
            'Юлия Виноградова', 'Павел Егоров', 'Екатерина Фомина', 'Роман Никитин',
            'Людмила Орлова', 'Виктор Макаров', 'Оксана Королёва', 'Артём Захаров',
            'Марина Белова', 'Олег Тарасов', 'Валентина Гусева', 'Денис Романов',
        ];

        $users = collect();
        foreach ($names as $idx => $name) {
            $email = 'reviewer' . ($idx + 1) . '@reviews.gosanatorium.local';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'              => $name,
                    'password'          => Hash::make(Str::random(20)),
                    'email_verified_at' => now(),
                    'status'            => 'enable',
                ]
            );
            $users->push($user);
        }

        return $users;
    }

    /** 5★ ~50%, 4★ ~35%, 3★ ~15% */
    private function weightedRating(): float
    {
        $r = random_int(1, 100);
        if ($r <= 50) return 5.0;
        if ($r <= 85) return 4.0;
        return 3.0;
    }

    /** Fraqmentlərdən unikal rəy mətni qurur */
    private function buildText(): string
    {
        $openings = [
            'Отдыхали здесь всей семьёй', 'Приезжали на лечение', 'Провели здесь незабываемую неделю',
            'Остались под большим впечатлением', 'Прекрасный санаторий', 'Отличное место для оздоровления',
            'Очень довольны отдыхом', 'Побывали здесь впервые', 'Возвращаемся сюда уже не первый раз',
            'Приятно удивлены уровнем сервиса', 'Санаторий превзошёл все ожидания', 'Спокойное и уютное место',
            'Отдыхали с супругой', 'Ездили сюда поправить здоровье', 'Замечательный отдых получился',
        ];
        $bodies = [
            'номера чистые, просторные и уютные', 'персонал вежливый и очень внимательный',
            'процедуры проводят на высоком уровне', 'территория ухоженная, много зелени',
            'питание вкусное и разнообразное', 'медицинский персонал профессиональный',
            'вокруг тишина и свежий воздух', 'все процедуры строго по расписанию',
            'отличная лечебная база', 'коллектив приветливый и заботливый',
            'красивая природа вокруг', 'комфортные условия проживания',
            'врачи внимательно подходят к каждому', 'чистота идеальная, убирают ежедневно',
            'спа и бассейн выше всяких похвал',
        ];
        $closings = [
            'Обязательно приедем снова!', 'Всем рекомендую!', 'Спасибо за прекрасный отдых!',
            'Однозначно стоит своих денег.', 'Остались только положительные эмоции.',
            'Будем советовать друзьям и знакомым.', 'Огромное спасибо всему коллективу!',
            'Лучшее место для восстановления сил.', 'Вернёмся сюда обязательно.', 'Отдых удался на все сто!',
        ];

        $b1 = $bodies[array_rand($bodies)];
        do { $b2 = $bodies[array_rand($bodies)]; } while ($b2 === $b1);

        $opening = $openings[array_rand($openings)];
        $closing = $closings[array_rand($closings)];

        return $opening . ', ' . $b1 . '. ' . Str::ucfirst($b2) . '. ' . $closing;
    }

    private function titles(): array
    {
        return [
            'Отличный отдых', 'Всё понравилось', 'Рекомендую', 'Прекрасное место',
            'Отдохнули на славу', 'Спасибо за заботу', 'Замечательный санаторий',
            'Приедем ещё', 'Отличный сервис', 'Всё на высшем уровне', 'Незабываемо',
        ];
    }
}
