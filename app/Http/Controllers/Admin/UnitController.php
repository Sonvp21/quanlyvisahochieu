<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::flatTree();
        $counts = \App\Models\Person::selectRaw('unit_id, count(*) as c')->whereNotNull('unit_id')->groupBy('unit_id')->pluck('c', 'unit_id');

        return view('admin.units.index', compact('units', 'counts'));
    }

    public function create()
    {
        return view('admin.units.create', [
            'unit'    => new Unit(['type' => 'phong', 'is_active' => true, 'sort_order' => 0]),
            'parents' => Unit::flatTree(),
        ]);
    }

    public function store(Request $request)
    {
        Unit::create($this->validated($request));

        return redirect()->route('admin.units.index')->with('success', 'Đã thêm đơn vị.');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', [
            'unit'    => $unit,
            'parents' => Unit::flatTree($unit->selfAndDescendantIds()), // không cho chọn chính nó/con cháu làm cha
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $this->validated($request, $unit);

        if (!empty($data['parent_id']) && in_array((int) $data['parent_id'], $unit->selfAndDescendantIds(), true)) {
            return back()->withInput()->withErrors(['parent_id' => 'Không thể chọn chính đơn vị này hoặc đơn vị con làm đơn vị cha.']);
        }

        $unit->update($data);

        return redirect()->route('admin.units.index')->with('success', 'Đã cập nhật đơn vị.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->children()->exists()) {
            return back()->with('error', 'Đơn vị còn đơn vị con, hãy xóa hoặc chuyển các đơn vị con trước.');
        }
        if ($unit->people()->exists()) {
            return back()->with('error', 'Đơn vị còn nhân sự, hãy chuyển nhân sự sang đơn vị khác trước.');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')->with('success', 'Đã xóa đơn vị.');
    }

    private function validated(Request $request, ?Unit $unit = null): array
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => ['required', 'string', 'max:50', Rule::unique('units', 'code')->ignore($unit?->id)],
            'type'       => ['required', Rule::in(array_keys(Unit::TYPES))],
            'parent_id'  => 'nullable|exists:units,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active']  = $request->boolean('is_active');

        return $data;
    }
}
