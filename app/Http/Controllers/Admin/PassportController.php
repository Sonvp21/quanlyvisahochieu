<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passport;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    public function index(Request $request)
    {
        $query = Passport::with(['student.user'])->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('passport_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('student', fn($s) => $s->where('full_name', 'like', '%'.$request->search.'%'));
            });
        }

        if ($request->status) {
            $today = now();
            $soon  = now()->addDays(30);

            $query->when($request->status === 'valid', fn($q) =>
                $q->where('expiry_date', '>', $soon)
            )->when($request->status === 'expiring_soon', fn($q) =>
                $q->whereBetween('expiry_date', [$today, $soon])
            )->when($request->status === 'expired', fn($q) =>
                $q->where('expiry_date', '<', $today)
            );
        }

        $passports = $query->paginate(20)->withQueryString();

        return view('admin.passports.index', compact('passports'));
    }

    public function create() { return redirect()->route('admin.students.create'); }
    public function store(Request $request) { return redirect()->route('admin.students.index'); }
    public function show(Passport $passport) { return redirect()->route('admin.students.show', $passport->student); }
    public function edit(Passport $passport) { return redirect()->route('admin.students.edit', $passport->student); }
    public function update(Request $request, Passport $passport) { return redirect()->route('admin.passports.index'); }
    public function destroy(Passport $passport)
    {
        $passport->delete();
        return redirect()->route('admin.passports.index')->with('success', 'Đã xóa hộ chiếu.');
    }
}