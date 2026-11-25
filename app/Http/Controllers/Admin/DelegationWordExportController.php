<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;

class DelegationWordExportController extends Controller
{
    public function export(Delegation $delegation)
    {
        $delegation->load('members');

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(13);

        $section = $phpWord->addSection([
            'marginLeft' => 1134,
            'marginRight' => 1134,
            'marginTop' => 1134,
            'marginBottom' => 1134,
        ]);

        $noBorderTable = [
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
        ];
        $noBorderCell = [
            'borderTopSize' => 0, 'borderTopColor' => 'FFFFFF',
            'borderBottomSize' => 0, 'borderBottomColor' => 'FFFFFF',
            'borderLeftSize' => 0, 'borderLeftColor' => 'FFFFFF',
            'borderRightSize' => 0, 'borderRightColor' => 'FFFFFF',
        ];

        $bold = ['bold' => true];

        // Số công văn: nếu anh đã nhập sẵn có "/" (vd "111/ĐHNL-HTQT") thì giữ nguyên,
        // nếu chỉ nhập số thuần (vd "111") thì mới tự nối "/ĐHNL-HTQT"
        $docNo = trim((string) $delegation->document_no);
        if ($docNo === '') {
            $docNo = '......../ĐHNL-HTQT';
        } elseif (!str_contains($docNo, '/')) {
            $docNo = $docNo . '/ĐHNL-HTQT';
        }

        // ===== Header: 2 cột (đơn vị - quốc hiệu), không viền =====
        $headerTable = $section->addTable($noBorderTable);
        $headerTable->addRow();

        $leftCell = $headerTable->addCell(5500, $noBorderCell);
        $leftCell->addText('ĐẠI HỌC THÁI NGUYÊN', $bold, ['alignment' => Jc::CENTER]);
        $leftCell->addText('TRƯỜNG ĐẠI HỌC NÔNG LÂM', $bold, ['alignment' => Jc::CENTER]);
        $leftCell->addText('—————', [], ['alignment' => Jc::CENTER]);
        $leftCell->addText('Số: ' . $docNo, [], ['alignment' => Jc::CENTER]);
        $leftCell->addText('V/v khách nước ngoài đến làm việc tại Trường', ['italic' => true], ['alignment' => Jc::CENTER]);

        $rightCell = $headerTable->addCell(5500, $noBorderCell);
        $rightCell->addText('CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM', $bold, ['alignment' => Jc::CENTER]);
        $rightCell->addText('Độc lập - Tự do - Hạnh phúc', $bold, ['alignment' => Jc::CENTER]);
        $rightCell->addText('—————', [], ['alignment' => Jc::CENTER]);
        $rightCell->addText(
            'Thái Nguyên, ngày ' . now()->format('d') . ' tháng ' . now()->format('m') . ' năm ' . now()->format('Y'),
            ['italic' => true],
            ['alignment' => Jc::CENTER]
        );

        $section->addTextBreak(1);

        // ===== Kính gửi =====
        $section->addText('Kính gửi:', $bold);
        $recipients = array_filter(array_map('trim', explode(',', (string) $delegation->recipient_units)));
        if (empty($recipients)) {
            $recipients = ['Các đơn vị liên quan'];
        }
        foreach ($recipients as $recipient) {
            $section->addText('- ' . $recipient . ';');
        }

        $section->addTextBreak(1);

        // ===== Nội dung =====
        $section->addText(
            'Trường Đại học Nông Lâm - Đại học Thái Nguyên xin báo cáo về việc chuyên gia người nước ngoài đến thăm và làm việc tại Trường, thông tin cụ thể như sau:',
            [],
            ['alignment' => Jc::BOTH]
        );

        $memberCount = $delegation->members->count();
        $section->addText("1. Thông tin khách nước ngoài: {$memberCount} người (thông tin cụ thể tại phụ lục đính kèm).");

        $timeRange = '......';
        if ($delegation->work_date_from) {
            $from = $delegation->work_date_from;
            $to = $delegation->work_date_to;
            if (!$to || $to->eq($from)) {
                $timeRange = $from->format('H:i') . ' ngày ' . $from->format('d/m/Y');
            } elseif ($to->isSameDay($from)) {
                $timeRange = 'từ ' . $from->format('H:i') . ' đến ' . $to->format('H:i') . ' ngày ' . $from->format('d/m/Y');
            } else {
                $timeRange = 'từ ' . $from->format('H:i') . ' ngày ' . $from->format('d/m/Y') . ' đến ' . $to->format('H:i') . ' ngày ' . $to->format('d/m/Y');
            }
        }
        $section->addText("2. Thời gian: {$timeRange}.");
        $section->addText('3. Nội dung: ' . ($delegation->purpose ?: '......') . '.');
        $section->addText('4. Địa điểm công tác: ' . ($delegation->work_location ?: ($delegation->host_unit ?: '......')) . '.');

        $accommodation = trim(($delegation->accommodation_name ?: '') . ($delegation->accommodation_address ? ' - ' . $delegation->accommodation_address : ''));
        $section->addText('5. Địa chỉ tạm trú dự kiến: ' . ($accommodation ?: '......') . '.');

        $section->addTextBreak(1);
        $section->addText(
            'Trường Đại học Nông Lâm kính đề nghị Quý cơ quan tạo điều kiện giúp đỡ cho khách nói trên tới làm việc tại Trường thuận lợi.',
            [],
            ['alignment' => Jc::BOTH]
        );
        $section->addText('Trân trọng cảm ơn!', $bold);

        $section->addTextBreak(1);

        // ===== Nơi nhận / Ký tên, không viền =====
        $footerTable = $section->addTable($noBorderTable);
        $footerTable->addRow();
        $footerLeft = $footerTable->addCell(5500, $noBorderCell);
        $footerLeft->addText('Nơi nhận:', ['italic' => true, 'bold' => true]);
        $footerLeft->addText('- Như kính gửi;', ['italic' => true]);
        $footerLeft->addText('- Lưu VT, HTQT.', ['italic' => true]);

        $footerRight = $footerTable->addCell(5500, $noBorderCell);
        $footerRight->addText('HIỆU TRƯỞNG', $bold, ['alignment' => Jc::CENTER]);
        $footerRight->addTextBreak(4);

        // ===== Trang phụ lục =====
        $section->addPageBreak();
        $section->addText('PHỤ LỤC', ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
        $section->addText(
            '(Kèm theo Công văn số ' . $docNo . ' ngày ' . now()->format('d/m/Y') . ')',
            ['italic' => true],
            ['alignment' => Jc::CENTER]
        );
        $section->addTextBreak(1);

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
        ]);

        $headers = ['TT', 'Họ và tên', 'Giới tính', 'Năm sinh', 'Quốc tịch', 'Nghề nghiệp', 'Loại, số hộ chiếu', 'Thời hạn hộ chiếu', 'Số thị thực'];
        $widths  = [500, 2000, 1000, 1100, 1100, 1600, 1600, 1500, 1500];

        $table->addRow();
        foreach ($headers as $i => $label) {
            $cell = $table->addCell($widths[$i], ['bgColor' => 'D9E2F3']);
            $cell->addText($label, ['bold' => true, 'size' => 11], ['alignment' => Jc::CENTER]);
        }

        foreach ($delegation->members as $index => $member) {
            $table->addRow();
            $values = [
                $index + 1,
                $member->full_name,
                $member->gender,
                $member->date_of_birth?->format('d/m/Y'),
                $member->nationality,
                $member->occupation,
                trim(($member->passport_type ?? '') . ', ' . ($member->passport_no ?? ''), ', '),
                $member->passport_expiry?->format('d/m/Y'),
                $member->visa_no,
            ];
            foreach ($values as $i => $val) {
                $cell = $table->addCell($widths[$i]);
                $cell->addText((string) ($val ?: '—'), ['size' => 11], ['alignment' => Jc::CENTER]);
            }
        }

        $section->addTextBreak(1);
        $section->addText("Ổn định danh sách có {$memberCount} người./.", ['italic' => true]);

        $fileName = 'Cong_van_doan_khach_' . $delegation->id . '.docx';

        return response()->streamDownload(function () use ($phpWord) {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
}
