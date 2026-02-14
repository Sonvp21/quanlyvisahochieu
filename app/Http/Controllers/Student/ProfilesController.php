<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{
    public function show(Request $request)
    {
        $student = Student::with(['passport', 'visa'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('student.profile', [
            'student' => $student,
            'editStudent'  => $request->has('edit_student'),
            'editPassport' => $request->has('edit_passport'),
            'editVisa'     => $request->has('edit_visa'),
        ]);
    }

    public function updateStudent(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'full_name'       => 'required|string|max:255',
            'student_code'    => 'required|string|max:50',
            'student_type'    => 'nullable|in:exchange,regular,postgraduate',
            'date_of_birth'   => 'nullable|date|before:today',
            'gender'          => 'nullable|in:male,female,other',
            'nationality'     => 'nullable|string|max:100',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'major'           => 'nullable|string|max:255',
            'enrollment_date' => 'nullable|date',
        ]);

        $student->update($data);

        return redirect()
            ->route('student.profile.show')
            ->with('success', 'Đã cập nhật thông tin sinh viên thành công');
    }
}
