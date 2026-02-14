<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VisaController extends Controller
{
    public function update(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'visa_type'    => 'required|string|max:100',
            'country'      => 'nullable|string|max:100',
            'visa_number'  => 'nullable|string|max:50',
            'issue_date'   => 'nullable|date|before:today',
            'expiry_date'  => 'required|date|after:issue_date',
            'entry_type'   => 'nullable|in:single,multiple',
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
            $path = $request->file('image')->store('visas', 'public');
            $data['image'] = $path;

            // Xóa ảnh cũ
            if ($student->visa && $student->visa->image) {
                Storage::disk('public')->delete($student->visa->image);
            }
        }

        // Defaults & tracking
        $data['entry_type'] = $data['entry_type'] ?? 'single';
        $data['status'] = 'valid'; // Sinh viên cập nhật thì luôn set valid
        $data['last_updated_by'] = 'student';

        Visa::updateOrCreate(
            ['student_id' => $student->id],
            $data
        );

        return redirect()
            ->route('student.profile.show')
            ->with('success', 'Đã cập nhật visa thành công');
        //     return redirect(
        //     route('student.profile.show', ['edit_visa' => 1]) . '#visa-section'
        // )->with('success', 'Đã cập nhật visa thành công');
    }
}
