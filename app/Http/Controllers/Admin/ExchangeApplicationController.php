<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeApplication;
use App\Models\ExchangeProgram;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExchangeApplicationController extends Controller
{
    public function store(Request $request, ExchangeProgram $exchangeProgram)
    {
        $d = $request->validate([
            'person_id'     => 'required|exists:people,id',
            'gpa'           => 'nullable|numeric|min:0|max:10',
            'language_cert' => 'nullable|string|max:255',
            'note'          => 'nullable|string|max:2000',
        ]);

        if ($exchangeProgram->applications()->where('person_id', $d['person_id'])->exists()) {
            return back()->with('error', 'Sinh viên này đã có trong chương trình.');
        }
        $exchangeProgram->applications()->create($d);

        return back()->with('success', 'Đã thêm hồ sơ đăng ký.');
    }

    public function update(Request $request, ExchangeApplication $application)
    {
        $d = $request->validate([
            'status' => ['required', Rule::in(array_keys(ExchangeApplication::STATUSES))],
            'note'   => 'nullable|string|max:2000',
        ]);

        $decided = in_array($d['status'], ['approved', 'rejected'], true) && $application->status !== $d['status'];
        $application->update($d + ($decided ? ['decided_by' => $request->user()->id, 'decided_at' => now()] : []));

        return back()->with('success', 'Đã cập nhật trạng thái.');
    }

    public function destroy(ExchangeApplication $application)
    {
        if ($application->trip) {
            return back()->with('error', 'Hồ sơ đã tạo chuyến đi, không xóa được.');
        }
        $application->delete();

        return back()->with('success', 'Đã xóa hồ sơ đăng ký.');
    }

    /** Tạo hồ sơ đi (đoàn ra) ở dạng dự thảo từ đơn đăng ký được chọn */
    public function toTrip(Request $request, ExchangeApplication $application)
    {
        if ($application->trip) {
            return redirect()->route('trips.show', $application->trip);
        }
        abort_unless(in_array($application->status, ['approved', 'ongoing'], true), 403);

        $p = $application->program->load('partner');
        $person = $application->person;

        $trip = Trip::create([
            'code'       => Trip::nextCode('out'),
            'direction'  => 'out',
            'trip_type'  => 'trao_doi',
            'title'      => 'Trao đổi sinh viên: ' . $p->name,
            'purpose'    => 'Tham gia chương trình ' . $p->name . ($p->partner ? ' tại ' . $p->partner->name : '') . '.',
            'country'    => $p->country ?: $p->partner?->country,
            'city'       => $p->partner?->city,
            'host_org'   => $p->partner?->name,
            'partner_id' => $p->partner_id,
            'depart_at'  => $p->start_date ?? now()->addMonth(),
            'return_at'  => $p->end_date ?? now()->addMonths(2),
            'unit_id'    => $person->unit_id,
            'created_by' => $request->user()->id,
            'status'     => 'draft',
            'exchange_application_id' => $application->id,
        ]);
        $trip->members()->attach($person->id, ['role_in_trip' => 'Sinh viên trao đổi']);

        return redirect()->route('trips.edit', $trip)->with('success', 'Đã tạo hồ sơ đi dự thảo. Kiểm tra lại thời gian, kinh phí rồi gửi duyệt.');
    }
}
