<!DOCTYPE html>
<html lang="vi">
<body style="margin:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#334155;">
    <div style="max-width:560px;margin:24px auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
        <div style="background:#1d4ed8;color:#ffffff;padding:16px 24px;font-size:14px;">GIRC System · Hợp tác quốc tế &amp; Đối ngoại</div>
        <div style="padding:24px;">
            <p style="margin:0 0 12px;font-size:16px;font-weight:bold;color:#0f172a;">{{ $headline }}</p>
            <table style="width:100%;font-size:14px;border-collapse:collapse;">
                <tr><td style="padding:4px 0;color:#64748b;width:120px;">Mã hồ sơ</td><td style="padding:4px 0;">{{ $trip->code }} · {{ $trip->direction_label }}</td></tr>
                <tr><td style="padding:4px 0;color:#64748b;">Nội dung</td><td style="padding:4px 0;">{{ $trip->title }}</td></tr>
                <tr><td style="padding:4px 0;color:#64748b;">{{ $trip->isIncoming() ? 'Tổ chức cử đoàn' : 'Nơi đến' }}</td><td style="padding:4px 0;">{{ $trip->isIncoming() ? ($trip->host_org ?: '—') : trim(($trip->city ? $trip->city . ', ' : '') . $trip->country) }}</td></tr>
                <tr><td style="padding:4px 0;color:#64748b;">Thời gian</td><td style="padding:4px 0;">{{ $trip->time_range }}</td></tr>
                <tr><td style="padding:4px 0;color:#64748b;">Đơn vị</td><td style="padding:4px 0;">{{ $trip->unit?->name ?? '—' }}</td></tr>
                <tr><td style="padding:4px 0;color:#64748b;">Trạng thái</td><td style="padding:4px 0;">{{ $trip->status_label }}</td></tr>
            </table>
            @if ($note)
                <p style="margin:16px 0 0;padding:12px;background:#f8fafc;border-left:3px solid #94a3b8;font-size:14px;white-space:pre-line;">{{ $note }}</p>
            @endif
            <p style="margin:24px 0 0;"><a href="{{ $url }}" style="display:inline-block;background:#1d4ed8;color:#ffffff;text-decoration:none;padding:10px 20px;border-radius:8px;font-size:14px;">Mở hồ sơ trên hệ thống</a></p>
        </div>
        <div style="padding:12px 24px;background:#f8fafc;font-size:12px;color:#94a3b8;">Email tự động từ hệ thống, vui lòng không trả lời trực tiếp.</div>
    </div>
</body>
</html>
