<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Season;

final class SeasonController extends Controller
{
    public function index(): View
    {
        $seasons = Season::orderBy('start_date')->get();

        return view('tourbooking::admin.seasons.index', compact('seasons'));
    }

    public function create(): View
    {
        return view('tourbooking::admin.seasons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateInput($request);

        $candidate = new Season($validated);
        if ($overlap = $candidate->findOverlap()) {
            return back()->withInput()->withErrors([
                'start_date' => __('translate.Season overlaps with') . ' "' . $overlap->name . '" ('
                    . $overlap->start_date->format('Y-m-d') . ' — '
                    . $overlap->end_date->format('Y-m-d') . ').',
            ]);
        }

        $candidate->status = $request->boolean('status', true);
        $candidate->save();

        return redirect()->route('admin.tourbooking.seasons.index')
            ->with('success', __('translate.Season created successfully.'));
    }

    public function edit(Season $season): View
    {
        return view('tourbooking::admin.seasons.edit', compact('season'));
    }

    public function update(Request $request, Season $season): RedirectResponse
    {
        $validated = $this->validateInput($request);

        $season->fill($validated);
        if ($overlap = $season->findOverlap()) {
            return back()->withInput()->withErrors([
                'start_date' => __('translate.Season overlaps with') . ' "' . $overlap->name . '" ('
                    . $overlap->start_date->format('Y-m-d') . ' — '
                    . $overlap->end_date->format('Y-m-d') . ').',
            ]);
        }

        $season->status = $request->boolean('status', true);
        $season->save();

        return redirect()->route('admin.tourbooking.seasons.index')
            ->with('success', __('translate.Season updated successfully.'));
    }

    public function destroy(Season $season): RedirectResponse
    {
        $season->delete();

        return redirect()->route('admin.tourbooking.seasons.index')
            ->with('success', __('translate.Season deleted successfully.'));
    }

    /**
     * JSON feed for FullCalendar.
     */
    public function calendar(): JsonResponse
    {
        $events = Season::orderBy('start_date')->get()->map(function (Season $s) {
            return [
                'id'              => $s->id,
                'title'           => $s->name . ' (' . ($s->percentage >= 0 ? '+' : '') . $s->percentage . '%)',
                'start'           => $s->start_date->toDateString(),
                'end'             => $s->end_date->copy()->addDay()->toDateString(), // FullCalendar end is exclusive
                'backgroundColor' => $s->color,
                'borderColor'     => $s->color,
                'url'             => route('admin.tourbooking.seasons.edit', $s),
            ];
        });

        return response()->json($events);
    }

    /**
     * AJAX overlap check for the create/edit form.
     */
    public function checkOverlap(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'id'         => 'nullable|integer',
        ]);

        $candidate = new Season([
            'start_date' => $request->input('start_date'),
            'end_date'   => $request->input('end_date'),
        ]);
        if ($id = $request->input('id')) {
            $candidate->id = (int) $id;
            $candidate->exists = true;
        }

        $overlap = $candidate->findOverlap();

        return response()->json([
            'overlap' => $overlap ? [
                'id'   => $overlap->id,
                'name' => $overlap->name,
                'from' => $overlap->start_date->format('Y-m-d'),
                'to'   => $overlap->end_date->format('Y-m-d'),
            ] : null,
        ]);
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'percentage' => 'required|numeric|between:-100,500',
            'color'      => 'nullable|string|max:20',
        ]);
    }
}
