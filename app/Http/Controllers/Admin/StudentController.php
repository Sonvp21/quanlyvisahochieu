<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Passport;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'passport', 'visa']);

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('student_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('email', 'like', "%{$search}%");
                    });
            });
        }


        if ($request->filled('filter')) {
            $now = Carbon::now();

            switch ($request->filter) {

                case 'passport_expiring':
                    $query->whereHas('passport', function ($q) {
                        $q->whereRaw(
                            "STRFTIME('%Y-%m-%d 23:59:59', expiry_date) >= ?
             AND STRFTIME('%Y-%m-%d 23:59:59', expiry_date) <= ?",
                            [
                                now()->toDateTimeString(),
                                now()->addDays(29)->endOfDay()->toDateTimeString()
                            ]
                        );
                    });
                    break;


                // Hộ chiếu đã hết hạn (sau 23:59:59 ngày hết hạn)
                case 'passport_expired':
                    $query->whereHas('passport', function ($q) {
                        $q->whereRaw(
                            "STRFTIME('%Y-%m-%d 23:59:59', expiry_date) < ?",
                            [now()->toDateTimeString()]
                        );
                    });
                    break;


                // Visa sắp hết hạn (từ hiện tại đến 30 ngày tới, tính đến 23:59:59 của ngày hết hạn)
                case 'visa_expiring':
                    $query->whereHas('visa', function ($q) {
                        $q->whereRaw(
                            "STRFTIME('%Y-%m-%d 23:59:59', expiry_date) >= ?
             AND STRFTIME('%Y-%m-%d 23:59:59', expiry_date) <= ?",
                            [
                                now()->toDateTimeString(),
                                now()->addDays(29)->endOfDay()->toDateTimeString()
                            ]
                        );
                    });
                    break;


                // Visa đã hết hạn (sau 23:59:59 của ngày hết hạn)
                case 'visa_expired':
                    $query->whereHas('visa', function ($q) {
                        $q->whereRaw(
                            "STRFTIME('%Y-%m-%d 23:59:59', expiry_date) < ?",
                            [now()->toDateTimeString()]
                        );
                    });
                    break;


                case 'recently_updated':
                    $query->where('updated_at', '>=', $now->subDays(7));
                    break;
            }
        }

        $sort = $request->get('sort', 'latest');

        switch ($sort) {
            case 'oldest':
                $query->oldest('created_at');
                break;
            case 'name_asc':
                $query->orderBy('full_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('full_name', 'desc');
                break;
            case 'latest':
            default:
                $query->latest('created_at');
                break;
        }
        $students = $query->paginate(6)->withQueryString();

        // $students = $query->latest()->paginate(2)->withQueryString();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // USER
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',

            // STUDENT
            'full_name'    => 'required|string|max:255',
            'student_code' => 'required|string|max:50|unique:students,student_code',
            'student_type' => 'nullable|in:exchange,regular,postgraduate',
            'date_of_birth' => 'nullable|date|before:today',
            'gender'       => 'nullable|in:male,female,other',
            'nationality'  => 'nullable|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'major'        => 'nullable|string|max:255',
            'enrollment_date' => 'nullable|date',
        ]);

        // CREATE USER
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'student',
        ]);

        // CREATE STUDENT
        Student::create([
            'user_id'         => $user->id,
            'full_name'       => $data['full_name'],
            'student_code'    => $data['student_code'],
            'student_type'    => $data['student_type'] ?? null,
            'date_of_birth'   => $data['date_of_birth'] ?? null,
            'gender'          => $data['gender'] ?? null,
            'nationality'     => $data['nationality'] ?? null,
            'phone'           => $data['phone'] ?? null,
            'address'         => $data['address'] ?? null,
            'major'           => $data['major'] ?? null,
            'enrollment_date' => $data['enrollment_date'] ?? null,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Đã thêm sinh viên thành công');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'passport', 'visa']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load(['user', 'passport', 'visa']);
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        // Validation - Tách riêng validation cho ảnh
        $validated = $request->validate([
            // USER EMAIL
            'email' => 'required|email|unique:users,email,' . $student->user_id,

            // STUDENT
            'full_name'    => 'required|string|max:255',
            'student_code' => 'required|string|max:50|unique:students,student_code,' . $student->id,
            'student_type' => 'nullable|in:exchange,regular,postgraduate',
            'date_of_birth' => 'nullable|date|before:today',
            'gender'       => 'nullable|in:male,female,other',
            'nationality'  => 'nullable|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'major'        => 'nullable|string|max:255',
            'enrollment_date' => 'nullable|date',

            // PASSPORT
            'passport_number'      => 'nullable|string|max:50',
            'passport_country'     => 'nullable|string|max:100',
            'passport_place'       => 'nullable|string|max:100',
            'passport_issue_date'  => 'nullable|date|before:today',
            'passport_expiry_date' => 'nullable|date|after:passport_issue_date',
            'passport_image'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // VISA
            'visa_type'        => 'nullable|string|max:100',
            'visa_country'     => 'nullable|string|max:100',
            'visa_number'      => 'nullable|string|max:50',
            'visa_issue_date'  => 'nullable|date|before:today',
            'visa_expiry_date' => 'nullable|date|after:visa_issue_date',
            'entry_type'       => 'nullable|in:single,multiple',
            'visa_status'      => 'nullable|in:valid,expired,cancelled',
            'visa_image'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            // Custom error messages
            'passport_image.mimes' => 'Ảnh hộ chiếu phải có định dạng: JPG, PNG hoặc PDF',
            'passport_image.max' => 'Ảnh hộ chiếu không được vượt quá 2MB',
            'visa_image.mimes' => 'Ảnh visa phải có định dạng: JPG, PNG hoặc PDF',
            'visa_image.max' => 'Ảnh visa không được vượt quá 2MB',
        ]);

        // UPDATE USER EMAIL
        $student->user->update([
            'email' => $validated['email'],
        ]);

        // UPDATE STUDENT
        $student->update([
            'full_name'       => $validated['full_name'],
            'student_code'    => $validated['student_code'],
            'student_type'    => $validated['student_type'] ?? null,
            'date_of_birth'   => $validated['date_of_birth'] ?? null,
            'gender'          => $validated['gender'] ?? null,
            'nationality'     => $validated['nationality'] ?? null,
            'phone'           => $validated['phone'] ?? null,
            'address'         => $validated['address'] ?? null,
            'major'           => $validated['major'] ?? null,
            'enrollment_date' => $validated['enrollment_date'] ?? null,
        ]);

        // PASSPORT UPDATE
        if (!empty($validated['passport_number']) || $request->hasFile('passport_image')) {
            $passportData = [
                'passport_number'  => $validated['passport_number'] ?? $student->passport?->passport_number,
                'country_of_issue' => $validated['passport_country'] ?? $student->passport?->country_of_issue,
                'place_of_issue'   => $validated['passport_place'] ?? $student->passport?->place_of_issue,
                'issue_date'       => $validated['passport_issue_date'] ?? $student->passport?->issue_date,
                'expiry_date'      => $validated['passport_expiry_date'] ?? $student->passport?->expiry_date,
                'last_updated_by'  => 'admin',
            ];

            // Xử lý upload ảnh passport
            if ($request->hasFile('passport_image')) {
                try {
                    // Xóa ảnh cũ nếu có
                    if ($student->passport && $student->passport->image) {
                        Storage::disk('public')->delete($student->passport->image);
                    }

                    $file = $request->file('passport_image');
                    $path = $file->store('passports', 'public');

                    if ($path) {
                        $passportData['image'] = $path;
                    }
                } catch (\Exception $e) {
                    return back()->withErrors(['passport_image' => 'Không thể upload ảnh hộ chiếu: ' . $e->getMessage()]);
                }
            }

            //  $passport = Passport::updateOrCreate(
            //     ['student_id' => $student->id],
            //     $passportData
            // );

            // // ✅ THÊM DÒNG NÀY - CẬP NHẬT updated_at
            // $passport->touch();
            $passport = Passport::firstOrNew(['student_id' => $student->id]);
            $passport->fill($passportData);

            if ($passport->isDirty()) {
                $passport->save();
            }
        }

        // VISA UPDATE
        if (!empty($validated['visa_type']) || $request->hasFile('visa_image')) {
            $visaData = [
                'visa_type'       => $validated['visa_type'] ?? $student->visa?->visa_type,
                'country'         => $validated['visa_country'] ?? $student->visa?->country,
                'visa_number'     => $validated['visa_number'] ?? $student->visa?->visa_number,
                'issue_date'      => $validated['visa_issue_date'] ?? $student->visa?->issue_date,
                'expiry_date'     => $validated['visa_expiry_date'] ?? $student->visa?->expiry_date,
                'entry_type'      => $validated['entry_type'] ?? $student->visa?->entry_type ?? 'single',
                'status'          => $validated['visa_status'] ?? $student->visa?->status ?? 'valid',
                'last_updated_by' => 'admin',
            ];

            // Xử lý upload ảnh visa
            if ($request->hasFile('visa_image')) {
                try {
                    // Xóa ảnh cũ nếu có
                    if ($student->visa && $student->visa->image) {
                        Storage::disk('public')->delete($student->visa->image);
                    }

                    $file = $request->file('visa_image');
                    $path = $file->store('visas', 'public');

                    if ($path) {
                        $visaData['image'] = $path;
                    }
                } catch (\Exception $e) {
                    return back()->withErrors(['visa_image' => 'Không thể upload ảnh visa: ' . $e->getMessage()]);
                }
            }

            // $visa = Visa::updateOrCreate(
            //     ['student_id' => $student->id],
            //     $visaData
            // );

            // // ✅ THÊM DÒNG NÀY - CẬP NHẬT updated_at
            // $visa->touch();
            $visa = Visa::firstOrNew(['student_id' => $student->id]);
            $visa->fill($visaData);

            if ($visa->isDirty()) {
                $visa->save();
            }
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Đã cập nhật hồ sơ sinh viên thành công');
    }


    public function destroy(Student $student)
    {
        $student->passport?->delete();
        $student->visa?->delete();
        $student->user?->delete();
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', '🗑️ Đã xóa sinh viên thành công');
    }
    // public function destroy(Student $student)
    // {
    //     // XÓA ẢNH PASSPORT
    //     if ($student->passport && $student->passport->image) {
    //         Storage::disk('public')->delete($student->passport->image);
    //         $student->passport->delete();
    //     }

    //     // XÓA ẢNH VISA
    //     if ($student->visa && $student->visa->image) {
    //         Storage::disk('public')->delete($student->visa->image);
    //         $student->visa->delete();
    //     }

    //     // XÓA USER
    //     $student->user?->delete();

    //     // XÓA STUDENT
    //     $student->delete();

    //     return redirect()
    //         ->route('admin.students.index')
    //         ->with('success', '🗑️ Đã xóa sinh viên + toàn bộ ảnh liên quan');
    // }


}
