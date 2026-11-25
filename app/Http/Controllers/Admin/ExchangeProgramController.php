<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\ExchangeProgram;
use App\Models\Partner;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExchangeProgramController extends Controller
{
    public function index(Request $request)
    {
        $q = ExchangeProgram::with('partner')->withCount('applications');
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        return view('admin.exchange.index', ['programs' => $q->orderByRaw("status = 'open' desc")->orderByDesc('id')->paginate(20)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.exchange.form', $this->formData(new ExchangeProgram(['status' => 'open', 'program_type' => 'semester'])));
    }

    public function store(Request $request)
    {
        $p = ExchangeProgram::create($this->data($request) + ['created_by' => $request->user()->id]);

        return redirect()->route('admin.exchange-programs.show', $p)->with('success', 'Đã tạo chương trình trao đổi.');
    }

    public function show(ExchangeProgram $exchangeProgram)
    {
        $exchangeProgram->load(['partner', 'agreement', 'applications.person.unit', 'applications.trip']);
        $taken = $exchangeProgram->applications->pluck('person_id');

        return view('admin.exchange.show', [
            'program' => $exchangeProgram,
            'candidates' => Person::whereIn('person_type', ['student_vn', 'student_intl'])->where('is_active', true)
                ->whereNotIn('id', $taken)->orderBy('full_name')->get(['id', 'full_name', 'student_code', 'person_type']),
        ]);
    }

    public function edit(ExchangeProgram $exchangeProgram)
    {
        return view('admin.exchange.form', $this->formData($exchangeProgram));
    }

    public function update(Request $request, ExchangeProgram $exchangeProgram)
    {
        $exchangeProgram->update($this->data($request));

        return redirect()->route('admin.exchange-programs.show', $exchangeProgram)->with('success', 'Đã cập nhật chương trình.');
    }

    public function destroy(ExchangeProgram $exchangeProgram)
    {
        if ($exchangeProgram->applications()->exists()) {
            return back()->with('error', 'Chương trình đã có hồ sơ đăng ký, hãy đóng thay vì xóa.');
        }
        $exchangeProgram->delete();

        return redirect()->route('admin.exchange-programs.index')->with('success', 'Đã xóa chương trình.');
    }

    private function formData(ExchangeProgram $p): array
    {
        return [
            'program' => $p,
            'partners' => Partner::where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'agreements' => Agreement::with('partner:id,name')->where('is_terminated', false)->orderBy('title')->get(['id', 'title', 'partner_id']),
        ];
    }

    private function data(Request $request): array
    {
        return $request->validate([
            'name'           => 'required|string|max:255',
            'partner_id'     => 'nullable|exists:partners,id',
            'agreement_id'   => 'nullable|exists:agreements,id',
            'program_type'   => ['required', Rule::in(array_keys(ExchangeProgram::TYPES))],
            'level'          => ['nullable', Rule::in(array_keys(Person::LEVELS))],
            'country'        => 'nullable|string|max:100',
            'apply_deadline' => 'nullable|date',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'slots'          => 'nullable|integer|min:1|max:1000',
            'benefits'       => 'nullable|string|max:3000',
            'requirements'   => 'nullable|string|max:3000',
            'status'         => ['required', Rule::in(['open', 'closed'])],
        ]);
    }
}
