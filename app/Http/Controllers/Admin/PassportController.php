<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passport;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PassportController extends Controller
{
    public function index()
    {
        $passports = Passport::with('student.user')->latest()->paginate(15);
        return view('admin.passports.index', compact('passports'));
    }

    public function create()
    {
        // Chỉ lấy sinh viên chưa có passport
        $students = Student::with('user')
            ->doesntHave('passport')
            ->get();
        return view('admin.passports.create', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'       => 'required|exists:students,id|unique:passports,student_id',
            'passport_number'  => 'required|string|max:50|unique:passports,passport_number',
            'country_of_issue' => 'nullable|string|max:100',
            'place_of_issue'   => 'nullable|string|max:100',
            'issue_date'       => 'nullable|date|before:today',
            'expiry_date'      => 'required|date|after:issue_date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'student_id.required' => 'Vui lòng chọn sinh viên',
            'student_id.unique' => 'Sinh viên này đã có hộ chiếu',
            'passport_number.required' => 'Vui lòng nhập số hộ chiếu',
            'passport_number.unique' => 'Số hộ chiếu đã tồn tại',
            'expiry_date.required' => 'Vui lòng nhập ngày hết hạn',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày cấp',
            'issue_date.before' => 'Ngày cấp phải trước hôm nay',
            'image.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        // Upload ảnh nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('passports', 'public');
        }

        // Track người cập nhật
        $data['last_updated_by'] = 'admin';

        Passport::create($data);

        return redirect()->route('admin.passports.index')
            ->with('success', 'Đã thêm hộ chiếu thành công');
    }


    public function show(Passport $passport)
    {
        $passport->load('student.user');
        return view('admin.passports.show', compact('passport'));
    }

    public function edit(Passport $passport)
    {
        $students = Student::with('user')->get();
        return view('admin.passports.edit', compact('passport', 'students'));
    }

    public function update(Request $request, Passport $passport)
    {
        $data = $request->validate([
            'passport_number'  => 'required|string|max:50|unique:passports,passport_number,' . $passport->id,
            'country_of_issue' => 'nullable|string|max:100',
            'place_of_issue'   => 'nullable|string|max:100',
            'issue_date'       => 'nullable|date|before:today',
            'expiry_date'      => 'required|date|after:issue_date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'passport_number.required' => 'Vui lòng nhập số hộ chiếu',
            'passport_number.unique' => 'Số hộ chiếu đã tồn tại',
            'expiry_date.required' => 'Vui lòng nhập ngày hết hạn',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày cấp',
            'issue_date.before' => 'Ngày cấp phải trước hôm nay',
            'image.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        // Upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($passport->image) {
                Storage::disk('public')->delete($passport->image);
            }
            $data['image'] = $request->file('image')->store('passports', 'public');
        }

        // Track người cập nhật
        $data['last_updated_by'] = 'admin';

        $passport->update($data);

        return redirect()->route('admin.passports.index')
            ->with('success', 'Đã cập nhật hộ chiếu thành công');
    }
    public function destroy(Passport $passport)
    {
        // Xóa ảnh nếu có
        if ($passport->image) {
            Storage::disk('public')->delete($passport->image);
        }

        $passport->delete();

        return redirect()->route('admin.passports.index')
            ->with('success', 'Đã xóa hộ chiếu thành công');
    }
}
