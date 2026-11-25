<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passport;
use App\Models\Person;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Hộ chiếu, visa của cán bộ và khách (sinh viên quốc tế vẫn quản lý ở mục Sinh viên) */
class PersonDocumentController extends Controller
{
    public function storePassport(Request $request, Person $person)
    {
        abort_if($person->student_id, 403, 'Giấy tờ của sinh viên quốc tế quản lý ở mục Sinh viên.');

        $data = $request->validate([
            'passport_number'  => ['required', 'string', 'max:50', Rule::unique('passports', 'passport_number')],
            'country_of_issue' => 'nullable|string|max:100',
            'place_of_issue'   => 'nullable|string|max:255',
            'issue_date'       => 'nullable|date',
            'expiry_date'      => 'required|date|after:issue_date',
        ], [
            'passport_number.unique' => 'Số hộ chiếu này đã tồn tại trong hệ thống.',
            'expiry_date.after'      => 'Ngày hết hạn phải sau ngày cấp.',
        ]);

        $person->passports()->create($data + ['last_updated_by' => 'admin']);

        return redirect()->route('admin.people.edit', $person)->with('success', 'Đã thêm hộ chiếu.');
    }

    public function storeVisa(Request $request, Person $person)
    {
        abort_if($person->student_id, 403, 'Giấy tờ của sinh viên quốc tế quản lý ở mục Sinh viên.');

        $data = $request->validate([
            'visa_type'   => 'required|string|max:100',
            'visa_number' => 'nullable|string|max:50',
            'country'     => 'nullable|string|max:100',
            'issue_date'  => 'nullable|date',
            'expiry_date' => 'required|date|after:issue_date',
            'entry_type'  => 'required|in:single,multiple',
        ], ['expiry_date.after' => 'Ngày hết hạn phải sau ngày cấp.']);

        $person->visas()->create($data + ['status' => 'valid', 'last_updated_by' => 'admin']);

        return redirect()->route('admin.people.edit', $person)->with('success', 'Đã thêm visa.');
    }

    public function destroyPassport(Person $person, Passport $passport)
    {
        abort_unless($passport->person_id === $person->id && !$person->student_id, 404);
        $passport->delete();

        return back()->with('success', 'Đã xóa hộ chiếu.');
    }

    public function destroyVisa(Person $person, Visa $visa)
    {
        abort_unless($visa->person_id === $person->id && !$person->student_id, 404);
        $visa->delete();

        return back()->with('success', 'Đã xóa visa.');
    }
}
