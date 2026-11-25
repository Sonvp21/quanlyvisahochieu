<?php

namespace App\Services;

use App\Models\Trip;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Sinh biểu mẫu Word từ hồ sơ đoàn ra / đoàn vào.
 * Bản tạm theo thể thức văn bản hành chính thông dụng; thay nội dung theo mẫu chuẩn của Trường khi có.
 */
class TripDocuments
{
    public const NAMES = [
        'to_trinh'   => 'Tờ trình',
        'quyet_dinh' => 'Quyết định cử đi',
        'bao_cao'    => 'Báo cáo kết quả',
    ];

    public static function available(Trip $trip): array
    {
        $docs = ['to_trinh' => self::NAMES['to_trinh']];
        if (!$trip->isIncoming()) {
            $docs['quyet_dinh'] = self::NAMES['quyet_dinh'];
        }
        if ($trip->report) {
            $docs['bao_cao'] = self::NAMES['bao_cao'];
        }

        return $docs;
    }

    /** @return array{0:string,1:string} [tên file, nội dung nhị phân] */
    public function build(Trip $trip, string $doc): array
    {
        $trip->loadMissing(['members.unit', 'unit', 'creator', 'report.submitter']);

        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(13);
        $s = $phpWord->addSection(['marginLeft' => 1701, 'marginRight' => 1134, 'marginTop' => 1134, 'marginBottom' => 1134]);

        match ($doc) {
            'to_trinh'   => $this->toTrinh($s, $trip),
            'quyet_dinh' => $this->quyetDinh($s, $trip),
            'bao_cao'    => $this->baoCao($s, $trip),
            default      => abort(404),
        };

        ob_start();
        IOFactory::createWriter($phpWord, 'Word2007')->save('php://output');
        $bin = ob_get_clean();

        $code = str_replace(['/', '\\'], '-', $trip->code);

        return [self::NAMES[$doc] . ' - ' . $code . '.docx', $bin];
    }

    // ------------------------------------------------------------------ Biểu mẫu
    private function toTrinh($s, Trip $t): void
    {
        $in = $t->isIncoming();
        $this->head($s, 'Số: ....../TTr-' . config('trips_org.code'), $in ? 'Về việc tiếp đoàn khách nước ngoài' : 'Về việc cử cán bộ đi công tác nước ngoài', 'TỜ TRÌNH');

        $s->addText('Kính gửi: Ban Giám hiệu ' . $this->orgShort(), ['bold' => true], ['alignment' => Jc::CENTER]);
        $s->addTextBreak(0);

        $this->p($s, ($t->unit?->name ?: 'Đơn vị') . ' kính trình Ban Giám hiệu xem xét, phê duyệt ' . ($in ? 'việc tiếp đoàn khách nước ngoài' : 'việc cử cán bộ đi công tác nước ngoài') . ' với các nội dung sau:');

        $this->p($s, '1. Tên chuyến: ' . $t->title, true);
        $this->p($s, '2. Mục đích, nội dung: ' . ($t->purpose ?: '......'));
        $this->p($s, $in
            ? '3. Tổ chức cử đoàn: ' . ($t->host_org ?: '......') . ($t->country ? ' (' . $t->country . ')' : '')
            : '3. Nơi đến: ' . trim(($t->city ? $t->city . ', ' : '') . ($t->country ?: '......')) . ($t->host_org ? '; đơn vị mời: ' . $t->host_org : ''));
        $this->p($s, '4. Thời gian: ' . $t->time_range . ' (' . $t->days . ' ngày).');
        if ($in) {
            $this->p($s, '5. Địa điểm làm việc: ' . ($t->work_location ?: '......') . '; nơi lưu trú: ' . ($t->accommodation ?: '......') . '.');
            if ($t->reception_notes) {
                $this->p($s, '6. Chương trình, đón tiếp: ' . $t->reception_notes);
            }
        }
        $n = $in ? ($t->reception_notes ? 7 : 6) : 5;
        $this->p($s, $n . '. Thành viên ' . ($in ? 'đoàn khách' : 'đoàn') . ' (' . $t->members->count() . ' người):');
        $this->membersTable($s, $t);
        $this->p($s, ($n + 1) . '. Kinh phí: ' . ($t->budget_estimate ? number_format($t->budget_estimate, 0, ',', '.') . ' đồng' : '......') . '; nguồn: ' . ($t->funding_source ?: '......') . '.');
        $s->addTextBreak(0);
        $this->p($s, 'Kính trình Ban Giám hiệu xem xét, quyết định.');

        $this->signBlock($s, [
            ['Nơi nhận:', ['- Như trên;', '- Lưu: VT, ' . config('trips_org.office') . '.'], 'left'],
            [$t->unit?->name ? mb_strtoupper($t->unit->name) : 'ĐƠN VỊ ĐỀ XUẤT', [$t->creator?->name ?? ''], 'right'],
        ]);
    }

    private function quyetDinh($s, Trip $t): void
    {
        $this->head($s, 'Số: ....../QĐ-' . config('trips_org.code'), 'Về việc cử cán bộ đi công tác nước ngoài', 'QUYẾT ĐỊNH', mb_strtoupper(config('trips_org.leader_title')) . ' ' . config('trips_org.name'));

        foreach ([
            'Căn cứ chức năng, nhiệm vụ và quyền hạn của ' . $this->orgShort() . ';',
            'Căn cứ các quy định hiện hành về quản lý đoàn ra, đoàn vào;',
            'Xét đề nghị của ' . ($t->unit?->name ?: 'đơn vị') . ' và ' . config('trips_org.office_name') . ',',
        ] as $line) {
            $this->p($s, $line, false, true);
        }

        $s->addText('QUYẾT ĐỊNH:', ['bold' => true], ['alignment' => Jc::CENTER, 'spaceBefore' => 120]);

        $this->p($s, 'Điều 1. Cử ' . $t->members->count() . ' cán bộ có tên trong danh sách kèm theo đi công tác tại ' . trim(($t->city ? $t->city . ', ' : '') . ($t->country ?: '......')) . ($t->host_org ? ' theo lời mời của ' . $t->host_org : '') . '; thời gian: ' . $t->time_range . '; mục đích: ' . ($t->purpose ?: $t->title) . '.');
        $this->membersTable($s, $t);
        $this->p($s, 'Điều 2. Kinh phí thực hiện: ' . ($t->budget_estimate ? number_format($t->budget_estimate, 0, ',', '.') . ' đồng' : '......') . ', nguồn: ' . ($t->funding_source ?: '......') . '.');
        $this->p($s, 'Điều 3. Trong vòng ' . config('trips.report_days', 15) . ' ngày kể từ ngày kết thúc chuyến công tác, các cán bộ nêu trên có trách nhiệm nộp báo cáo kết quả cho ' . config('trips_org.office_name') . ' để tổng hợp, báo cáo Ban Giám hiệu.');
        $this->p($s, 'Điều 4. Quyết định có hiệu lực kể từ ngày ký. Trưởng các đơn vị liên quan và những cán bộ có tên tại Điều 1 chịu trách nhiệm thi hành quyết định này.');

        $this->signBlock($s, [
            ['Nơi nhận:', ['- Như Điều 4;', '- Lưu: VT, ' . config('trips_org.office') . '.'], 'left'],
            [mb_strtoupper(config('trips_org.leader_title')), ['', '', config('trips_org.leader_name')], 'right'],
        ]);
    }

    private function baoCao($s, Trip $t): void
    {
        $in = $t->isIncoming();
        $r = $t->report;
        $this->head($s, 'Số: ....../BC-' . config('trips_org.code'), $in ? 'Kết quả tiếp đoàn khách nước ngoài' : 'Kết quả chuyến công tác nước ngoài', 'BÁO CÁO');

        $s->addText('Kính gửi: Ban Giám hiệu ' . $this->orgShort(), ['bold' => true], ['alignment' => Jc::CENTER]);
        $s->addTextBreak(0);
        $this->p($s, '1. Thông tin chung', true);
        $this->p($s, '- Tên chuyến: ' . $t->title);
        $this->p($s, $in ? '- Tổ chức cử đoàn: ' . ($t->host_org ?: '......') . ($t->country ? ' (' . $t->country . ')' : '')
                         : '- Nơi đến: ' . trim(($t->city ? $t->city . ', ' : '') . ($t->country ?: '......')));
        $this->p($s, '- Thời gian: ' . $t->time_range . '.');
        $this->p($s, '- Thành viên:');
        $this->membersTable($s, $t);
        $this->p($s, '2. Nội dung đã thực hiện', true);
        $this->block($s, $r?->summary);
        $this->p($s, '3. Kết quả đạt được', true);
        $this->block($s, $r?->results);
        $this->p($s, '4. Đề xuất, kiến nghị', true);
        $this->block($s, $r?->recommendations);

        $this->signBlock($s, [
            ['Nơi nhận:', ['- Như trên;', '- Lưu: VT, ' . config('trips_org.office') . '.'], 'left'],
            ['NGƯỜI BÁO CÁO', ['', '', $r?->submitter?->name ?? ''], 'right'],
        ]);
    }

    // ------------------------------------------------------------------ Thành phần dùng chung
    private function head($s, string $so, string $trichYeu, string $title, ?string $issuer = null): void
    {
        $tbl = $s->addTable(['borderSize' => 0]);
        $tbl->addRow();
        $l = $tbl->addCell(4600);
        $c = ['alignment' => Jc::CENTER, 'spaceAfter' => 0];
        $l->addText(config('trips_org.parent'), [], $c);
        $l->addText($issuer ?: config('trips_org.name'), ['bold' => true], $c);
        $l->addText('———', [], $c);
        $l->addText($so, [], $c);
        $r = $tbl->addCell(5000);
        $r->addText('CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM', ['bold' => true], $c);
        $r->addText('Độc lập - Tự do - Hạnh phúc', ['bold' => true], $c);
        $r->addText('———————', [], $c);
        $d = now();
        $r->addText(config('trips_org.place') . ', ngày ' . $d->format('d') . ' tháng ' . $d->format('m') . ' năm ' . $d->format('Y'), ['italic' => true], $c);

        $s->addTextBreak(1);
        $s->addText($title, ['bold' => true, 'size' => 15], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
        $s->addText($trichYeu, ['bold' => true], ['alignment' => Jc::CENTER]);
    }

    private function p($s, string $text, bool $bold = false, bool $italic = false): void
    {
        $s->addText($text, ['bold' => $bold, 'italic' => $italic], ['alignment' => Jc::BOTH, 'spaceAfter' => 80, 'lineHeight' => 1.2]);
    }

    private function block($s, ?string $text): void
    {
        foreach (preg_split('/\R/u', trim((string) $text) ?: '......') as $line) {
            $this->p($s, $line);
        }
    }

    private function membersTable($s, Trip $t): void
    {
        $in = $t->isIncoming();
        $tb = $s->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 60]);
        $tb->addRow();
        foreach ([[600, 'STT'], [3300, 'Họ và tên'], [3300, $in ? 'Cơ quan, chức vụ' : 'Đơn vị, chức vụ'], [2100, 'Vai trò']] as [$w, $h]) {
            $tb->addCell($w)->addText($h, ['bold' => true], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
        }
        foreach ($t->members as $i => $m) {
            $tb->addRow();
            $org = $in ? ($m->organization ?: $m->unit?->name) : ($m->unit?->name ?: $m->organization);
            $cells = [
                [600, (string) ($i + 1), Jc::CENTER],
                [3300, trim(($m->academic_title ? $m->academic_title . '. ' : '') . $m->full_name), Jc::LEFT],
                [3300, trim(($org ?: '') . ($m->position ? ($org ? ', ' : '') . $m->position : '')), Jc::LEFT],
                [2100, (string) $m->pivot->role_in_trip, Jc::LEFT],
            ];
            foreach ($cells as [$w, $txt, $al]) {
                $tb->addCell($w)->addText($txt, [], ['alignment' => $al, 'spaceAfter' => 0]);
            }
        }
        $s->addTextBreak(0);
    }

    private function signBlock($s, array $cols): void
    {
        $s->addTextBreak(1);
        $tb = $s->addTable(['borderSize' => 0]);
        $tb->addRow();
        foreach ($cols as [$title, $lines, $side]) {
            $cell = $tb->addCell(4800);
            if ($side === 'left') {
                $cell->addText($title, ['bold' => true, 'italic' => true, 'size' => 11], ['spaceAfter' => 0]);
                foreach ($lines as $l) {
                    $cell->addText($l, ['size' => 11], ['spaceAfter' => 0]);
                }
            } else {
                $cell->addText($title, ['bold' => true], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
                $cell->addText('(Ký, ghi rõ họ tên)', ['italic' => true, 'size' => 11], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
                foreach ($lines as $l) {
                    $cell->addText($l, ['bold' => true], ['alignment' => Jc::CENTER, 'spaceBefore' => 600]);
                }
            }
        }
    }

    private function orgShort(): string
    {
        return mb_convert_case(config('trips_org.name'), MB_CASE_TITLE, 'UTF-8');
    }
}
