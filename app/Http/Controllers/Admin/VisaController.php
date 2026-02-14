<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visa;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    public function index()
    {
        $visas = Visa::with('student.user')->latest()->paginate(15);
        return view('admin.visas.index', compact('visas'));
    }

    public function create()
    {
        // Chỉ lấy sinh viên chưa có visa
        $students = Student::with('user')
            ->doesntHave('visa')
            ->get();
        return view('admin.visas.create', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'   => 'required|exists:students,id|unique:visas,student_id',
            'visa_type'    => 'required|string|max:100',
            'country'      => 'nullable|string|max:100',
            'visa_number'  => 'nullable|string|max:50',
            'issue_date'   => 'nullable|date|before:today',
            'expiry_date'  => 'required|date|after:issue_date',
            'entry_type'   => 'nullable|in:single,multiple',
            'status'       => 'nullable|in:valid,expired,cancelled',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'student_id.required' => 'Vui lòng chọn sinh viên',
            'student_id.unique' => 'Sinh viên này đã có visa',
            'visa_type.required' => 'Vui lòng nhập loại visa',
            'expiry_date.required' => 'Vui lòng nhập ngày hết hạn',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày cấp',
            'issue_date.before' => 'Ngày cấp phải trước hôm nay',
            'image.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        // Upload ảnh nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('visas', 'public');
        }

        // Defaults
        $data['entry_type'] = $data['entry_type'] ?? 'single';
        $data['status'] = $data['status'] ?? 'valid';
        $data['last_updated_by'] = 'admin';

        Visa::create($data);

        return redirect()->route('admin.visas.index')
            ->with('success', 'Đã thêm visa thành công');
    }

    public function show(Visa $visa)
    {
        $visa->load('student.user');
        return view('admin.visas.show', compact('visa'));
    }

    public function edit(Visa $visa)
    {
        $students = Student::with('user')->get();
        return view('admin.visas.edit', compact('visa', 'students'));
    }

    public function update(Request $request, Visa $visa)
    {
        $data = $request->validate([
            'visa_type'    => 'required|string|max:100',
            'country'      => 'nullable|string|max:100',
            'visa_number'  => 'nullable|string|max:50',
            'issue_date'   => 'nullable|date|before:today',
            'expiry_date'  => 'required|date|after:issue_date',
            'entry_type'   => 'nullable|in:single,multiple',
            'status'       => 'nullable|in:valid,expired,cancelled',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'visa_type.required' => 'Vui lòng nhập loại visa',
            'expiry_date.required' => 'Vui lòng nhập ngày hết hạn',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày cấp',
            'issue_date.before' => 'Ngày cấp phải trước hôm nay',
            'image.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        // Upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($visa->image) {
                Storage::disk('public')->delete($visa->image);
            }
            $data['image'] = $request->file('image')->store('visas', 'public');
        }

        // Track người cập nhật
        $data['last_updated_by'] = 'admin';

        $visa->update($data);

        return redirect()->route('admin.visas.index')
            ->with('success', 'Đã cập nhật visa thành công');
    }
    public function destroy(Visa $visa)
    {
        // Xóa ảnh nếu có
        if ($visa->image) {
            Storage::disk('public')->delete($visa->image);
        }

        $visa->delete();

        return redirect()->route('admin.visas.index')
            ->with('success', 'Đã xóa visa thành công');
    }
}
