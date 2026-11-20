<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\RoomStopSale;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Services\StopSales;

/** Task 42: room availability calendar — «Нет мест» (stop-sale) or rooms left for a date range, CSV import. */
final class StopSaleController extends Controller
{
    public function index(Request $request, Service $service): View
    {
        $rooms = $service->rooms()->get(['id', 'name', 'service_id', 'status']);
        $month = preg_match('/^\d{4}-\d{2}$/', (string) $request->query('month')) ? (string) $request->query('month') : now('Asia/Baku')->format('Y-m');
        $start = Carbon::createFromFormat('!Y-m', $month, 'Asia/Baku')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $periods = StopSales::ready()
            ? RoomStopSale::whereIn('service_room_id', $rooms->pluck('id'))
                ->where('date_to', '>=', now('Asia/Baku')->subDays(1)->toDateString())
                ->orderBy('date_from')->get()
            : collect();

        // grid: room id => day => 'stop' | int (rooms left)
        $grid = [];
        foreach ($periods as $p) {
            for ($d = max($p->date_from, $start)->copy(); $d->lte(min($p->date_to, $end)); $d->addDay()) {
                $cur = $grid[$p->service_room_id][$d->day] ?? null;
                if ($p->isStopSale()) {
                    $grid[$p->service_room_id][$d->day] = 'stop';
                } elseif ($cur !== 'stop' && ($cur === null || $p->rooms_left < $cur)) {
                    $grid[$p->service_room_id][$d->day] = (int) $p->rooms_left;
                }
            }
        }

        return view('tourbooking::admin.stop_sales.index', [
            'service' => $service,
            'rooms' => $rooms,
            'periods' => $periods,
            'grid' => $grid,
            'start' => $start,
            'ready' => StopSales::ready(),
            'selectedRoom' => (int) $request->query('room', 0),
        ]);
    }

    public function store(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'integer',
            'date_from' => 'required|date_format:Y-m-d',
            'date_to' => 'required|date_format:Y-m-d|after_or_equal:date_from',
            'type' => 'required|in:stop,left',
            'rooms_left' => 'nullable|required_if:type,left|integer|min:1|max:999',
            'note' => 'nullable|string|max:255',
        ]);
        $roomIds = $service->rooms()->whereIn('id', $data['room_ids'])->pluck('id');
        foreach ($roomIds as $id) {
            RoomStopSale::create([
                'service_room_id' => $id,
                'date_from' => $data['date_from'],
                'date_to' => $data['date_to'],
                'rooms_left' => $data['type'] === 'left' ? (int) $data['rooms_left'] : null,
                'note' => $data['note'] ?? null,
            ]);
        }

        return back()->with(['message' => 'Сохранено: ' . $roomIds->count() . ' номер(а)', 'alert-type' => 'success']);
    }

    public function destroy(Service $service, RoomStopSale $stopSale): RedirectResponse
    {
        abort_unless($service->rooms()->whereKey($stopSale->service_room_id)->exists(), 404);
        $stopSale->delete();

        return back()->with(['message' => 'Удалено', 'alert-type' => 'success']);
    }

    /** CSV: room (id or exact name), from, to[, rooms_left]. Dates Y-m-d or d.m.Y. Separator , or ; */
    public function import(Request $request, Service $service): RedirectResponse
    {
        $request->validate(['csv' => 'required|file|max:1024|mimes:csv,txt']);
        $rooms = $service->rooms()->get(['id', 'name']);
        $byName = $rooms->keyBy(fn ($r) => mb_strtolower(trim((string) $r->name)));
        $parseDate = function (string $v): ?Carbon {
            $v = trim($v);
            foreach (['!Y-m-d', '!d.m.Y', '!d/m/Y'] as $fmt) {
                $d = \DateTime::createFromFormat($fmt, $v);
                if ($d && $d->format(ltrim($fmt, '!')) === $v) {
                    return Carbon::instance($d);
                }
            }
            return null;
        };

        $ok = 0;
        $errors = [];
        $lines = preg_split('/\r\n|\r|\n/', (string) file_get_contents($request->file('csv')->getRealPath()));
        foreach ($lines as $n => $line) {
            $line = trim(preg_replace('/^\xEF\xBB\xBF/', '', $line));
            if ($line === '') {
                continue;
            }
            $cols = str_getcsv($line, substr_count($line, ';') > substr_count($line, ',') ? ';' : ',');
            if (count($cols) < 3) {
                $errors[] = 'Строка ' . ($n + 1) . ': нужно минимум 3 колонки';
                continue;
            }
            $key = trim((string) $cols[0]);
            $room = ctype_digit($key) ? $rooms->firstWhere('id', (int) $key) : $byName->get(mb_strtolower($key));
            $from = $parseDate((string) $cols[1]);
            $to = $parseDate((string) $cols[2]);
            if (! $room || ! $from || ! $to || $to->lt($from)) {
                if ($n === 0 && ! $from) {
                    continue; // header row
                }
                $errors[] = 'Строка ' . ($n + 1) . ': ' . (! $room ? 'номер «' . $key . '» не найден' : 'неверные даты');
                continue;
            }
            $left = isset($cols[3]) && is_numeric(trim((string) $cols[3])) && (int) $cols[3] > 0 ? (int) $cols[3] : null;
            RoomStopSale::create([
                'service_room_id' => $room->id,
                'date_from' => $from->toDateString(),
                'date_to' => $to->toDateString(),
                'rooms_left' => $left,
                'note' => 'CSV',
            ]);
            $ok++;
        }

        $msg = 'Импортировано строк: ' . $ok . ($errors ? '. Ошибки: ' . implode('; ', array_slice($errors, 0, 5)) : '');

        return back()->with(['message' => $msg, 'alert-type' => $errors ? 'warning' : 'success']);
    }
}
