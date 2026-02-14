<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo visa sắp hết hạn</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .alert-box {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid;
        }
        .alert-warning {
            background-color: #fef3c7;
            border-color: #f59e0b;
            color: #92400e;
        }
        .alert-danger {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #6b7280;
            width: 40%;
        }
        .info-table td:last-child {
            color: #1f2937;
            font-weight: 500;
        }
        .days-remaining {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            line-height: 1.4;
        }
        .days-remaining.warning {
            color: #f59e0b;
        }
        .days-remaining.danger {
            color: #ef4444;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #8b5cf6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #7c3aed;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .instructions {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .instructions h3 {
            color: #1f2937;
            margin-top: 0;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
            color: #4b5563;
        }
        .warning-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .warning-box strong {
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">📗</div>
            <div class="title">Thông báo về Visa</div>
        </div>

        @if($isExpired)
            <div class="alert-box alert-danger">
                <strong>⚠️ CẢNH BÁO:</strong> Visa của bạn đã hết hạn!
            </div>
            <div class="days-remaining danger">
                Đã hết hạn {{ $daysRemaining }} ngày {{ $hoursRemaining }} giờ {{ $minutesRemaining }} phút {{ $secondsRemaining }} giây
            </div>
        @else
            <div class="alert-box alert-warning">
                <strong>⏰ NHẮC NHỞ:</strong> Visa của bạn sắp hết hạn!
            </div>
            <div class="days-remaining warning">
                Còn {{ $daysRemaining }} ngày {{ $hoursRemaining }} giờ {{ $minutesRemaining }} phút {{ $secondsRemaining }} giây
            </div>
        @endif

        <p>Xin chào <strong>{{ $student->full_name }}</strong>,</p>

        <p>Đây là thông báo tự động từ hệ thống quản lý hồ sơ du học sinh.
        Visa của bạn {{ $isExpired ? 'đã hết hạn' : 'sắp hết hạn' }}.</p>

        <table class="info-table">
            <tr>
                <td>Họ tên</td>
                <td>{{ $student->full_name }}</td>
            </tr>
            <tr>
                <td>Mã sinh viên</td>
                <td>{{ $student->student_code }}</td>
            </tr>
            <tr>
                <td>Loại visa</td>
                <td>{{ $student->visa->visa_type }}</td>
            </tr>
            @if($student->visa->visa_number)
            <tr>
                <td>Số visa</td>
                <td>{{ $student->visa->visa_number }}</td>
            </tr>
            @endif
            <tr>
                <td>Ngày hết hạn</td>
                <td><strong style="color: {{ $isExpired ? '#ef4444' : '#f59e0b' }}">{{ $expiryDate }}</strong></td>
            </tr>
        </table>

        @if($isExpired)
            <div class="warning-box">
                <strong>🚨 QUAN TRỌNG:</strong> Visa hết hạn có thể ảnh hưởng đến tình trạng lưu trú hợp pháp của bạn.
                Vui lòng liên hệ ngay với cơ quan di trú hoặc nhà trường để được hỗ trợ.
            </div>
        @endif

        <div class="instructions">
            <h3>📋 Vui lòng thực hiện:</h3>
            <ul>
                <li>Liên hệ với cơ quan quản lý di trú hoặc phòng quan hệ quốc tế của trường</li>
                <li>Chuẩn bị hồ sơ gia hạn visa theo quy định</li>
                <li>Kiểm tra tình trạng hộ chiếu (phải còn hạn ít nhất 6 tháng)</li>
                <li>Cập nhật thông tin visa mới vào hệ thống sau khi hoàn tất</li>
                @if($isExpired)
                <li><strong style="color: #ef4444;">Tránh vi phạm luật di trú - có thể dẫn đến trục xuất</strong></li>
                @else
                <li>Lưu ý: Quá trình gia hạn visa có thể mất 2-4 tuần</li>
                @endif
            </ul>
        </div>

        <center>
            <a href="{{ config('app.url') }}/student/profile" class="button">
                Cập nhật visa ngay
            </a>
        </center>

        <div class="footer">
            <p><strong>Lưu ý:</strong> Đây là email tự động. Bạn sẽ nhận được email nhắc nhở mỗi 7 ngày cho đến khi cập nhật thông tin mới.</p>
            <p>Nếu bạn đã gia hạn/cập nhật visa, vui lòng cập nhật vào hệ thống để dừng nhận email nhắc nhở.</p>
            <p style="margin-top: 20px; color: #9ca3af;">
                © {{ date('Y') }} Hệ thống quản lý du học sinh
            </p>
        </div>
    </div>
</body>
</html>
