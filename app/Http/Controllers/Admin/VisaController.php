<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visa;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    public function index(Request $request)
    {
        $query = Visa::with(['student.user'])->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('visa_number', 'like', '%'.$request->search.'%')
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

        if ($request->entry_type) {
            $query->where('entry_type', $request->entry_type);
        }

        $visas = $query->paginate(20)->withQueryString();

        return view('admin.visas.index', compact('visas'));
    }

    public function create() { return redirect()->route('admin.students.create'); }
    public function store(Request $request) { return redirect()->route('admin.students.index'); }
    public function show(Visa $visa) { return redirect()->route('admin.students.show', $visa->student); }
    public function edit(Visa $visa) { return redirect()->route('admin.students.edit', $visa->student); }
    public function update(Request $request, Visa $visa) { return redirect()->route('admin.visas.index'); }
    public function destroy(Visa $visa)
    {
        $visa->delete();
        return redirect()->route('admin.visas.index')->with('success', 'Đã xóa visa.');
    }
}