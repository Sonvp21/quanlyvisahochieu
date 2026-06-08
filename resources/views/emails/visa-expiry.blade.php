<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo Visa</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;color:#334155;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 16px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

    {{-- HEADER --}}
    <tr>
        <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:16px 16px 0 0;padding:32px;text-align:center;">
            <div style="display:inline-block;width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:16px;line-height:56px;font-size:28px;margin-bottom:16px;">🛂</div>
            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;">
                @if($isExpired) Visa đã hết hạn @else Visa sắp hết hạn @endif
            </h1>
            <p style="margin:8px 0 0;color:rgba(255,255,255,0.75);font-size:14px;">
                Hệ thống Quản lý Du học sinh · GIRC
            </p>
        </td>
    </tr>

    {{-- BODY --}}
    <tr>
        <td style="background:#ffffff;padding:32px;">

            {{-- COUNTDOWN BOX --}}
            <div style="background:{{ $isExpired ? '#fef2f2' : '#fffbeb' }};border:1px solid {{ $isExpired ? '#fecaca' : '#fde68a' }};border-radius:12px;padding:20px;text-align:center;margin-bottom:24px;">
                <p style="margin:0 0 6px;font-size:12px;font-weight:600;color:{{ $isExpired ? '#dc2626' : '#d97706' }};text-transform:uppercase;letter-spacing:0.05em;">
                    @if($isExpired) Đã hết hạn @else Còn lại @endif
                </p>
                <p style="margin:0;font-size:28px;font-weight:800;color:{{ $isExpired ? '#dc2626' : '#d97706' }};">
                    {{ $daysRemaining }} ngày {{ $hoursRemaining }} giờ {{ $minutesRemaining }} phút
                </p>
            </div>

            {{-- GREETING --}}
            <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">
                Xin chào <strong>{{ $student->full_name }}</strong>,
            </p>
            <p style="margin:0 0 24px;font-size:14px;line-height:1.7;color:#64748b;">
                Đây là thông báo tự động từ Hệ thống Quản lý Du học sinh.
                Visa của bạn <strong>{{ $isExpired ? 'đã hết hạn' : 'sắp hết hạn' }}</strong>.
                Vui lòng thực hiện các bước cần thiết để gia hạn kịp thời.
            </p>

            {{-- INFO TABLE --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:24px;">
                <tr style="background:#f8fafc;">
                    <td colspan="2" style="padding:12px 16px;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e2e8f0;">
                        Thông tin Visa
                    </td>
                </tr>
                @php
                    $rows = [
                        ['Họ và tên', $student->full_name],
                        ['Mã sinh viên', $student->student_code],
                        ['Loại visa', $student->visa->visa_type],
                        ['Số visa', $student->visa->visa_number ?? '—'],
                        ['Quốc gia', $student->visa->country ?? '—'],
                    ];
                @endphp
                @foreach($rows as $i => [$label, $value])
                <tr style="{{ $i % 2 === 0 ? 'background:#ffffff' : 'background:#f8fafc' }}">
                    <td style="padding:12px 16px;font-size:13px;color:#64748b;width:45%;border-bottom:1px solid #f1f5f9;">{{ $label }}</td>
                    <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#1e293b;border-bottom:1px solid #f1f5f9;">{{ $value }}</td>
                </tr>
                @endforeach
                <tr style="background:{{ $isExpired ? '#fef2f2' : '#fffbeb' }}">
                    <td style="padding:12px 16px;font-size:13px;color:#64748b;">Ngày hết hạn</td>
                    <td style="padding:12px 16px;font-size:13px;font-weight:700;color:{{ $isExpired ? '#dc2626' : '#d97706' }};">{{ $expiryDate }}</td>
                </tr>
            </table>

            {{-- WARNING nếu đã hết hạn --}}
            @if($isExpired)
            <div style="background:#fef2f2;border-left:4px solid #dc2626;border-radius:0 8px 8px 0;padding:16px;margin-bottom:24px;">
                <p style="margin:0;font-size:13px;font-weight:700;color:#dc2626;margin-bottom:4px;">🚨 Quan trọng</p>
                <p style="margin:0;font-size:13px;color:#b91c1c;line-height:1.6;">
                    Visa hết hạn có thể ảnh hưởng đến tình trạng lưu trú hợp pháp của bạn.
                    Liên hệ ngay phòng Quan hệ Quốc tế để được hỗ trợ.
                </p>
            </div>
            @endif

            {{-- STEPS --}}
            <div style="background:#f8fafc;border-radius:12px;padding:20px;margin-bottom:24px;">
                <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#334155;">📋 Các bước cần thực hiện:</p>
                <ol style="margin:0;padding-left:20px;">
                    @php
                        $steps = [
                            'Liên hệ phòng Quan hệ Quốc tế hoặc cơ quan quản lý di trú',
                            'Chuẩn bị hồ sơ gia hạn visa theo quy định',
                            'Kiểm tra hộ chiếu còn hạn ít nhất 6 tháng',
                            'Cập nhật thông tin visa mới vào hệ thống sau khi hoàn tất',
                        ];
                        if ($isExpired) $steps[] = 'Tránh vi phạm luật di trú — có thể bị xử lý theo pháp luật';
                        else $steps[] = 'Lưu ý: Quá trình gia hạn thường mất 2–4 tuần';
                    @endphp
                    @foreach($steps as $step)
                    <li style="font-size:13px;color:#475569;line-height:1.7;margin-bottom:4px;">{{ $step }}</li>
                    @endforeach
                </ol>
            </div>

            {{-- CTA BUTTON --}}
            <div style="text-align:center;margin-bottom:24px;">
                <a href="{{ config('app.url') }}/student/profile"
                    style="display:inline-block;background:#4f46e5;color:#ffffff;font-size:14px;font-weight:700;padding:14px 32px;border-radius:10px;text-decoration:none;letter-spacing:0.02em;">
                    Cập nhật Visa ngay →
                </a>
            </div>

            {{-- NOTE --}}
            <div style="border-top:1px solid #f1f5f9;padding-top:20px;">
                <p style="margin:0;font-size:12px;color:#94a3b8;line-height:1.7;text-align:center;">
                    Email này được gửi tự động. Bạn sẽ nhận thông báo mỗi ngày cho đến khi cập nhật thông tin mới.<br>
                    Nếu đã gia hạn, vui lòng cập nhật vào hệ thống để dừng nhận thông báo.
                </p>
            </div>

        </td>
    </tr>

    {{-- FOOTER --}}
    <tr>
        <td style="background:#f8fafc;border-radius:0 0 16px 16px;padding:20px;text-align:center;border-top:1px solid #e2e8f0;">
            <p style="margin:0;font-size:12px;color:#94a3b8;">
                © {{ date('Y') }} GIRC System · Hệ thống Quản lý Visa & Hộ chiếu<br>
                Trường Đại học Nông Lâm, Quyết Thắng, Thái Nguyên
            </p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>