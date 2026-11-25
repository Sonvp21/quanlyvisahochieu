<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripExpense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TripExpenseController extends Controller
{
    public function store(Request $request, Trip $trip)
    {
        $this->authorizeManage($request, $trip);

        $d = $request->validate([
            'category'       => ['required', Rule::in(array_keys(TripExpense::CATEGORIES))],
            'kind'           => ['required', Rule::in(array_keys(TripExpense::KINDS))],
            'description'    => 'nullable|string|max:255',
            'amount'         => 'required|integer|min:0|max:100000000000',
            'funding_source' => 'nullable|string|max:255',
            'spent_at'       => 'nullable|date',
        ]);
        $trip->expenses()->create($d + ['created_by' => $request->user()->id]);

        return back()->with('success', 'Đã ghi khoản chi.');
    }

    public function destroy(Request $request, TripExpense $expense)
    {
        $this->authorizeManage($request, $expense->trip);
        $expense->delete();

        return back()->with('success', 'Đã xóa khoản chi.');
    }

    private function authorizeManage(Request $request, Trip $trip): void
    {
        $u = $request->user();
        abort_unless(in_array($u->role, ['admin', 'htqt'], true) || $u->id === $trip->created_by, 403);
    }
}
