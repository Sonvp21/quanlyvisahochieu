<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DelegationExportController extends Controller
{
    public function export(Delegation $delegation)
    {
        $delegation->load('members');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Phu luc danh sach doan');

        $headers = [
            'A' => 'TT',
            'B' => 'Họ và tên',
            'C' => 'Giới tính',
            'D' => 'Năm sinh',
            'E' => 'Quốc tịch',
            'F' => 'Nghề nghiệp',
            'G' => 'Loại, số hộ chiếu',
            'H' => 'Thời hạn hộ chiếu',
            'I' => 'Số thị thực nhập cảnh',
        ];

        // Tiêu đề phụ lục
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'PHỤ LỤC DANH SÁCH ĐOÀN KHÁCH NƯỚC NGOÀI');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:I2');
        $docLabel = $delegation->document_no
            ? "(Kèm theo Công văn số {$delegation->document_no})"
            : '(Kèm theo Công văn báo cáo đoàn khách nước ngoài)';
        $sheet->setCellValue('A2', $docLabel);
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headerRow = 4;
        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$headerRow}", $label);
        }
        $headerRange = "A{$headerRow}:I{$headerRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('2E5496');
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $row = $headerRow + 1;
        foreach ($delegation->members as $index => $member) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $member->full_name);
            $sheet->setCellValue("C{$row}", $member->gender);
            $sheet->setCellValue("D{$row}", $member->date_of_birth?->format('d/m/Y'));
            $sheet->setCellValue("E{$row}", $member->nationality);
            $sheet->setCellValue("F{$row}", $member->occupation);
            $sheet->setCellValue("G{$row}", trim(($member->passport_type ?? '') . ', ' . ($member->passport_no ?? '')));
            $sheet->setCellValue("H{$row}", $member->passport_expiry?->format('d/m/Y'));
            $sheet->setCellValue("I{$row}", $member->visa_no);
            $row++;
        }

        $lastDataRow = $row - 1;
        if ($lastDataRow >= $headerRow + 1) {
            $sheet->getStyle("A{$headerRow}:I{$lastDataRow}")
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A" . ($headerRow + 1) . ":I{$lastDataRow}")
                ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

        $note = $row + 1;
        $sheet->mergeCells("A{$note}:I{$note}");
        $sheet->setCellValue("A{$note}", 'Ổn định danh sách có ' . $delegation->members->count() . ' người./.');
        $sheet->getStyle("A{$note}")->getFont()->setItalic(true);

        $widths = ['A' => 5, 'B' => 24, 'C' => 10, 'D' => 12, 'E' => 12, 'F' => 24, 'G' => 20, 'H' => 16, 'I' => 18];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }
        $sheet->setShowGridlines(false);

        $fileName = 'Phu_luc_doan_khach_' . $delegation->id . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
