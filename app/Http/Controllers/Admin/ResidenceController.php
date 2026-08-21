<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Residence;
use Illuminate\Http\Request;

class ResidenceController extends Controller
{
    /**
     * Cập nhật hoặc tạo mới thông tin tạm trú cho 1 sinh viên (admin thao tác).
     * Route: PUT /admin/students/{student}/residence
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'facility_name'            => 'nullable|string|max:255',
            'address'                  => 'nullable|string|max:255',
            'ward'                     => 'nullable|string|max:255',
            'arrival_date'             => 'nullable|date',
            'expected_departure_date'  => 'nullable|date',
            'certificate_no'           => 'nullable|string|max:100',
            'category'                 => 'nullable|string|max:50',
            'notes'                    => 'nullable|string',
        ]);

        $data['last_updated_by'] = 'admin';

        Residence::updateOrCreate(
            ['student_id' => $student->id],
            $data
        );

        return back()->with('success', 'Đã cập nhật thông tin tạm trú.');
    }
}