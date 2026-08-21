<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentExportController extends Controller
{
    /**
     * Xuất danh sách sinh viên ra Excel theo Mẫu số 01
     * (Danh sách người nước ngoài do đơn vị mời, bảo lãnh).
     *
     * Route: GET /admin/students/export
     */
    public function export(Request $request)
    {
        $query = Student::with(['user', 'passport', 'visa', 'residence']);

        // Áp dụng cùng bộ lọc với trang danh sách (nếu có)
        if ($request->search) {
            $query->where('full_name', 'like', '%'.$request->search.'%')
                  ->orWhere('student_code', 'like', '%'.$request->search.'%');
        }

        $students = $query->orderBy('full_name')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách');

        // ==================== SET UP CỘT ====================
        // A:STT B:Họ tên C:Ngày sinh D:Giới tính E:Quốc tịch F:Số hộ chiếu
        // G:Tên CSLT H:Địa chỉ I:Phường xã J:Ngày đến K:Số CN L:Ký hiệu M:Thời hạn
        // N:Giấy phép LĐ O:Mục đích nhập cảnh P:Nơi học/làm Q:Chức vụ R:Đơn vị bảo lãnh S:Ghi chú

        $colWidths = [
            'A' => 5,  'B' => 26, 'C' => 12, 'D' => 8,  'E' => 14, 'F' => 14,
            'G' => 20, 'H' => 22, 'I' => 16, 'J' => 14, 'K' => 12, 'L' => 8,
            'M' => 12, 'N' => 14, 'O' => 12, 'P' => 20, 'Q' => 14, 'R' => 20, 'S' => 22,
        ];
        foreach ($colWidths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // ==================== TIÊU ĐỀ ====================
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'TRƯỜNG ĐẠI HỌC NÔNG LÂM');
        $sheet->mergeCells('H1:S1');
        $sheet->setCellValue('H1', 'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM');

        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'ĐỊA CHỈ: Quyết Thắng, Thái Nguyên');
        $sheet->mergeCells('H2:S2');
        $sheet->setCellValue('H2', 'Độc lập - Tự do - Hạnh phúc');

        $sheet->mergeCells('A5:S5');
        $sheet->setCellValue('A5', 'DANH SÁCH');
        $sheet->mergeCells('A6:S6');
        $sheet->setCellValue('A6', 'NGƯỜI NƯỚC NGOÀI DO ĐƠN VỊ MỜI, BẢO LÃNH');
        $sheet->mergeCells('A7:S7');
        $sheet->setCellValue('A7', '(Số liệu tính đến ngày '.now()->format('d/m/Y').')');

        foreach (['A5', 'A6'] as $c) {
            $sheet->getStyle($c)->getFont()->setBold(true)->setSize(13);
        }
        foreach (['A1', 'H1'] as $c) {
            $sheet->getStyle($c)->getFont()->setBold(true);
        }
        foreach (['A5', 'A6', 'A7'] as $c) {
            $sheet->getStyle($c)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // ==================== HEADER BẢNG (2 dòng) ====================
        $headerRow1 = 9;
        $headerRow2 = 10;

        $sheet->mergeCells("A{$headerRow1}:A{$headerRow2}");
        $sheet->setCellValue("A{$headerRow1}", 'STT');

        $sheet->mergeCells("B{$headerRow1}:B{$headerRow2}");
        $sheet->setCellValue("B{$headerRow1}", 'Họ tên (fullname)');

        $sheet->mergeCells("C{$headerRow1}:C{$headerRow2}");
        $sheet->setCellValue("C{$headerRow1}", 'Ngày sinh (date of birth)');

        $sheet->mergeCells("D{$headerRow1}:D{$headerRow2}");
        $sheet->setCellValue("D{$headerRow1}", 'Giới tính (Gender)');

        $sheet->mergeCells("E{$headerRow1}:E{$headerRow2}");
        $sheet->setCellValue("E{$headerRow1}", 'Quốc tịch (Nationality)');

        $sheet->mergeCells("F{$headerRow1}:F{$headerRow2}");
        $sheet->setCellValue("F{$headerRow1}", 'Số hộ chiếu (Passport number)');

        $sheet->mergeCells("G{$headerRow1}:M{$headerRow1}");
        $sheet->setCellValue("G{$headerRow1}", 'Thông tin tạm trú (Temporary residence information)');
        $sheet->setCellValue('G'.$headerRow2, 'Tên cơ sở lưu trú');
        $sheet->setCellValue('H'.$headerRow2, 'Địa chỉ');
        $sheet->setCellValue('I'.$headerRow2, 'Phường, xã');
        $sheet->setCellValue('J'.$headerRow2, 'Ngày đến CSLT');
        $sheet->setCellValue('K'.$headerRow2, 'Số CN');
        $sheet->setCellValue('L'.$headerRow2, 'Ký hiệu');
        $sheet->setCellValue('M'.$headerRow2, 'Thời hạn visa');

        $sheet->mergeCells("N{$headerRow1}:N{$headerRow2}");
        $sheet->setCellValue("N{$headerRow1}", 'Giấy phép lao động');

        $sheet->mergeCells("O{$headerRow1}:O{$headerRow2}");
        $sheet->setCellValue("O{$headerRow1}", 'Mục đích nhập cảnh');

        $sheet->mergeCells("P{$headerRow1}:P{$headerRow2}");
        $sheet->setCellValue("P{$headerRow1}", 'Nơi học/làm việc');

        $sheet->mergeCells("Q{$headerRow1}:Q{$headerRow2}");
        $sheet->setCellValue("Q{$headerRow1}", 'Chức vụ');

        $sheet->mergeCells("R{$headerRow1}:R{$headerRow2}");
        $sheet->setCellValue("R{$headerRow1}", 'Đơn vị bảo lãnh');

        $sheet->mergeCells("S{$headerRow1}:S{$headerRow2}");
        $sheet->setCellValue("S{$headerRow1}", 'Ghi chú');

        $headerRange = "A{$headerRow1}:S{$headerRow2}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9E1F2');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // ==================== DỮ LIỆU ====================
        $row = $headerRow2 + 1;
        $stt = 1;

        foreach ($students as $student) {
            $residence = $student->residence;
            $visa = $student->visa;
            $passport = $student->passport;

            $sheet->setCellValue("A{$row}", $stt);
            $sheet->setCellValue("B{$row}", $student->full_name);
            $sheet->setCellValue("C{$row}", $student->date_of_birth ? date('d/m/Y', strtotime($student->date_of_birth)) : '');
            $sheet->setCellValue("D{$row}", match($student->gender) { 'male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác', default => '' });
            $sheet->setCellValue("E{$row}", $student->nationality);
            $sheet->setCellValue("F{$row}", $passport->passport_number ?? '');

            $sheet->setCellValue("G{$row}", $residence->facility_name ?? '');
            $sheet->setCellValue("H{$row}", $residence->address ?? '');
            $sheet->setCellValue("I{$row}", $residence->ward ?? '');
            $sheet->setCellValue("J{$row}", $residence && $residence->arrival_date ? date('d/m/Y', strtotime($residence->arrival_date)) : '');
            $sheet->setCellValue("K{$row}", $residence->certificate_no ?? '');
            $sheet->setCellValue("L{$row}", $residence->category ?? ($visa->visa_type ?? ''));
            $sheet->setCellValue("M{$row}", $visa && $visa->expiry_date ? date('d/m/Y', strtotime($visa->expiry_date)) : '');

            $sheet->setCellValue("N{$row}", '');
            $sheet->setCellValue("O{$row}", $visa->visa_type ?? 'Sinh viên');
            $sheet->setCellValue("P{$row}", 'Trường ĐHNL');
            $sheet->setCellValue("Q{$row}", '');
            $sheet->setCellValue("R{$row}", 'Trường ĐHNL');
            $sheet->setCellValue("S{$row}", $residence->notes ?? '');

            $row++;
            $stt++;
        }

        $dataRange = "A".($headerRow2+1).":S".($row-1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($dataRange)->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
        $sheet->getStyle("A".($headerRow2+1).":A".($row-1))
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C".($headerRow2+1).":C".($row-1))
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D".($headerRow2+1).":D".($row-1))
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("J".($headerRow2+1).":M".($row-1))
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->freezePane("B".($headerRow2+1));

        // ==================== XUẤT FILE ====================
        $filename = 'DanhSachSinhVien_' . now()->format('Ymd_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}