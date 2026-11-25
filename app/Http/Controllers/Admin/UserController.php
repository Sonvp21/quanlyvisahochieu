<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /** Vai trò quản lý ở trang này (sinh viên quốc tế quản lý ở mục Sinh viên) */
    private function roles(): array
    {
        return collect(User::ROLES)->except('student')->all();
    }

    public function index(Request $request)
    {
        $query = User::with(['unit', 'person'])->where('role', '!=', 'student');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'ilike', "%{$s}%")->orWhere('email', 'ilike', "%{$s}%"));
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', ['users' => $users, 'roles' => $this->roles()]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'user' => new User(['role' => 'staff']), 'roles' => $this->roles(), 'units' => Unit::flatTree(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $user = User::create($data);
        $this->syncPerson($user);

        return redirect()->route('admin.users.index')->with('success', 'Đã tạo tài khoản.');
    }

    public function edit(User $user)
    {
        abort_if($user->role === 'student', 404);

        return view('admin.users.edit', [
            'user' => $user, 'roles' => $this->roles(), 'units' => Unit::flatTree(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role === 'student', 404);

        $data = $this->validated($request, $user);

        // Không cho tự hạ quyền của chính mình
        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->withInput()->withErrors(['role' => 'Không thể tự hạ quyền quản trị của chính mình.']);
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);
        $this->syncPerson($user);

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật tài khoản.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($user->role === 'student', 404);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Không thể xóa tài khoản đang đăng nhập.');
        }
        if (\App\Models\Trip::where('created_by', $user->id)->exists()) {
            return back()->with('error', 'Tài khoản này đã tạo hồ sơ chuyến đi nên không xóa được. Hãy đổi mật khẩu hoặc chuyển vai trò nếu cần khóa.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa tài khoản.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
            'role'     => ['required', Rule::in(array_keys($this->roles()))],
            'unit_id'  => ['nullable', 'exists:units,id', Rule::requiredIf($request->role === 'unit_manager')],
        ], [
            'unit_id.required' => 'Lãnh đạo đơn vị cần chọn đơn vị phụ trách.',
        ]);

        return $data;
    }

    /** Mỗi tài khoản cán bộ có một hồ sơ "người" để làm thành viên đoàn */
    private function syncPerson(User $user): void
    {
        $person = Person::firstOrNew(['user_id' => $user->id]);

        if (!$person->exists) {
            $person->fill(['person_type' => 'staff', 'nationality' => 'Việt Nam', 'is_active' => true]);
        }
        $person->full_name = $user->name;
        $person->email     = $user->email;
        $person->unit_id   = $user->unit_id;
        $person->save();
    }
}
