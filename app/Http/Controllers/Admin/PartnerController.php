<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $q = Partner::query()->withCount(['agreements', 'trips']);
        if ($s = trim((string) $request->get('q'))) {
            $q->where(fn ($w) => $w->where('name', 'ilike', "%{$s}%")->orWhere('short_name', 'ilike', "%{$s}%")->orWhere('country', 'ilike', "%{$s}%"));
        }
        if ($request->filled('type')) {
            $q->where('partner_type', $request->type);
        }

        return view('admin.partners.index', ['partners' => $q->orderBy('name')->paginate(20)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.partners.form', ['partner' => new Partner(['partner_type' => 'university', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $partner = Partner::create($this->data($request));

        return redirect()->route('admin.partners.show', $partner)->with('success', 'Đã thêm đối tác.');
    }

    public function show(Partner $partner)
    {
        $partner->load(['agreements.unit', 'programs', 'trips' => fn ($q) => $q->limit(10)]);

        return view('admin.partners.show', ['partner' => $partner, 'units' => Unit::flatTree()]);
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.form', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $partner->update($this->data($request));

        return redirect()->route('admin.partners.show', $partner)->with('success', 'Đã cập nhật đối tác.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->agreements()->exists() || $partner->programs()->exists()) {
            return back()->with('error', 'Đối tác còn thỏa thuận hoặc chương trình trao đổi, hãy ngừng hoạt động thay vì xóa.');
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Đã xóa đối tác.');
    }

    private function data(Request $request): array
    {
        $d = $request->validate([
            'name'          => 'required|string|max:255',
            'short_name'    => 'nullable|string|max:100',
            'partner_type'  => ['required', Rule::in(array_keys(Partner::TYPES))],
            'country'       => 'nullable|string|max:100',
            'city'          => 'nullable|string|max:100',
            'website'       => 'nullable|string|max:255',
            'contact_name'  => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'notes'         => 'nullable|string|max:3000',
        ]);
        $d['is_active'] = $request->boolean('is_active');

        return $d;
    }
}
