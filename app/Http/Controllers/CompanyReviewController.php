<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CompanyReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

/** Task 26: /otzyvy — reviews about the company + «Оставить отзыв» form (moderated). */
class CompanyReviewController extends Controller
{
    public function index(): View
    {
        $ready = Schema::hasTable('company_reviews');
        $reviews = $ready ? CompanyReview::approved()->latest()->paginate(20) : collect();
        $stats = $ready ? CompanyReview::approved()->selectRaw('COUNT(*) c, AVG(rating) a')->first() : null;

        return view('company_reviews', [
            'reviews' => $reviews,
            'count' => (int) ($stats->c ?? 0),
            'avg' => $stats && $stats->c ? round((float) $stats->a, 1) : null,
            'ready' => $ready,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // honeypot: bots fill the hidden field
        if (trim((string) $request->input('website', '')) !== '') {
            return back()->with('review_sent', true);
        }
        $key = 'company-review:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withInput()->withErrors(['text' => 'Слишком много отзывов подряд. Попробуйте через час.']);
        }
        $data = $request->validate([
            'name' => 'required|string|min:2|max:80',
            'country' => 'nullable|string|max:80',
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string|min:20|max:3000',
            'booking_code' => 'nullable|string|max:40',
        ], [
            'name.required' => 'Укажите имя.',
            'rating.required' => 'Поставьте оценку.',
            'text.required' => 'Напишите отзыв.',
            'text.min' => 'Отзыв слишком короткий (минимум 20 символов).',
        ]);
        RateLimiter::hit($key, 3600);

        $code = trim((string) ($data['booking_code'] ?? ''));
        $verified = false;
        if ($code !== '' && Schema::hasTable('bookings')) {
            try {
                $verified = \Modules\TourBooking\App\Models\Booking::where('booking_code', $code)->exists();
            } catch (\Throwable) {
                $verified = false;
            }
        }

        CompanyReview::create([
            'name' => strip_tags($data['name']),
            'country' => isset($data['country']) ? strip_tags($data['country']) : null,
            'rating' => (int) $data['rating'],
            'text' => strip_tags($data['text']),
            'booking_code' => $code !== '' ? strip_tags($code) : null,
            'is_verified' => $verified,
            'status' => 'pending',
            'ip' => $request->ip(),
        ]);

        return redirect()->to(url()->previous() . '#review-form')->with('review_sent', true);
    }
}
