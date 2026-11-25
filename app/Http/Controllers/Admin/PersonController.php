<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::with('unit');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'ilike', "%{$s}%")
                    ->orWhere('email', 'ilike', "%{$s}%")
                    ->orWhere('student_code', 'ilike', "%{$s}%")
                    ->orWhere('organization', 'ilike', "%{$s}%");
            });
        }
        if ($request->filled('type')) {
            $query->where('person_type', $request->type);
        }
        if ($request->filled('unit_id')) {
            $unit = Unit::find($request->unit_id);
            if ($unit) {
                $query->whereIn('unit_id', $unit->selfAndDescendantIds());
            }
        }

        $people = $query->orderBy('full_name')->paginate(15)->withQueryString();

        $typeCounts = Person::selectRaw('person_type, count(*) as c')->groupBy('person_type')->pluck('c', 'person_type');
        $units = Unit::flatTree();

        return view('admin.people.index', compact('people', 'typeCounts', 'units'));
    }

    public function create()
    {
        return view('admin.people.create', $this->formData(new Person([
            'person_type' => request('type', 'staff'), 'nationality' => 'Việt Nam', 'is_active' => true,
        ])));
    }

    public function store(Request $request)
    {
        $person = Person::create($this->validated($request));

        return redirect()->route('admin.people.edit', $person)->with('success', 'Đã thêm hồ sơ.');
    }

    public function edit(Person $person)
    {
        $person->load(['passports', 'visas', 'unit']);

        return view('admin.people.edit', $this->formData($person));
    }

    public function update(Request $request, Person $person)
    {
        $person->update($this->validated($request, $person));

        return redirect()->route('admin.people.edit', $person)->with('success', 'Đã cập nhật hồ sơ.');
    }

    public function destroy(Person $person)
    {
        if ($person->student_id) {
            return back()->with('error', 'Hồ sơ này gắn với sinh viên quốc tế, hãy quản lý ở mục Sinh viên.');
        }

        $person->delete();

        return redirect()->route('admin.people.index')->with('success', 'Đã xóa hồ sơ.');
    }

    private function formData(Person $person): array
    {
        return [
            'person' => $person,
            'units'  => Unit::flatTree(),
            // Tài khoản chưa gắn với hồ sơ nào (cộng thêm tài khoản đang gắn với hồ sơ này)
            'users'  => User::whereDoesntHave('person', fn ($q) => $q->where('id', '!=', $person->id ?? 0))
                ->orderBy('name')->get(['id', 'name', 'email']),
        ];
    }

    private function validated(Request $request, ?Person $person = null): array
    {
        $data = $request->validate([
            'person_type'    => ['required', Rule::in(array_keys(Person::TYPES))],
            'full_name'      => 'required|string|max:255',
            'gender'         => 'nullable|in:male,female,other',
            'date_of_birth'  => 'nullable|date|before:today',
            'nationality'    => 'nullable|string|max:100',
            'unit_id'        => 'nullable|exists:units,id',
            'position'       => 'nullable|string|max:255',
            'academic_title' => 'nullable|string|max:100',
            'student_code'   => 'nullable|string|max:50',
            'student_level'  => ['nullable', Rule::in(array_keys(Person::LEVELS))],
            'organization'   => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:30',
            'user_id'        => ['nullable', 'exists:users,id', Rule::unique('people', 'user_id')->ignore($person?->id)],
            'notes'          => 'nullable|string|max:2000',
        ]);

        $data['nationality'] = $data['nationality'] ?: 'Việt Nam';
        $data['is_active']   = $request->boolean('is_active');

        return $data;
    }
}
