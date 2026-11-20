<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

/** Task 26: moderation of reviews about the company. */
class CompanyReviewController extends Controller
{
    public function index(Request $request): View
    {
        $ready = Schema::hasTable('company_reviews');
        $status = array_key_exists((string) $request->query('status'), CompanyReview::STATUSES) ? (string) $request->query('status') : null;
        $reviews = $ready
            ? CompanyReview::when($status, fn ($q) => $q->where('status', $status))->latest()->paginate(30)->withQueryString()
            : collect();
        $pending = $ready ? CompanyReview::where('status', 'pending')->count() : 0;

        return view('admin.company_reviews', compact('reviews', 'status', 'pending', 'ready'));
    }

    public function update(Request $request, CompanyReview $review): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'reply' => 'nullable|string|max:2000',
        ]);
        $review->update($data);

        return back()->with(['message' => 'Сохранено', 'alert-type' => 'success']);
    }

    public function destroy(CompanyReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with(['message' => 'Удалено', 'alert-type' => 'success']);
    }
}
