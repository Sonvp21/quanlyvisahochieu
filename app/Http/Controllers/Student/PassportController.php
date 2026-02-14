<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Passport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PassportController extends Controller
{
    public function update(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'passport_number'  => 'required|string|max:50',
            'country_of_issue' => 'nullable|string|max:100',
            'place_of_issue'   => 'nullable|string|max:100',
            'issue_date'       => 'nullable|date|before:today',
            'expiry_date'      => 'required|date|after:issue_date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'passport_number.required' => 'Vui lòng nhập số hộ chiếu',
            'expiry_date.required' => 'Vui lòng nhập ngày hết hạn',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày cấp',
            'issue_date.before' => 'Ngày cấp phải trước hôm nay',
            'image.image' => 'File phải là ảnh',
            'image.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        // Upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('passports', 'public');
            $data['image'] = $path;

            // Xóa ảnh cũ nếu có
            if ($student->passport && $student->passport->image) {
                Storage::disk('public')->delete($student->passport->image);
            }
        }

        // Track người cập nhật
        $data['last_updated_by'] = 'student';

        Passport::updateOrCreate(
            ['student_id' => $student->id],
            $data
        );

        return redirect()
            ->route('student.profile.show')
            ->with('success', 'Đã cập nhật hộ chiếu thành công');
    }
}
