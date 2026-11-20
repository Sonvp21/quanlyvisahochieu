<?php

namespace Database\Seeders;

use App\Models\Delegation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DelegationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id');

        $demos = [
            [
                'data' => [
                    'document_no' => '201/ĐHNL-HTQT',
                    'host_unit' => 'Trung tâm Nghiên cứu Địa tin học',
                    'purpose' => 'Thăm và làm việc với Trung tâm Nghiên cứu Địa tin học về trao đổi, thảo luận cơ hội hợp tác đào tạo và nghiên cứu khoa học',
                    'work_date_from' => now()->addDays(6)->toDateString(),
                    'work_date_to' => now()->addDays(7)->toDateString(),
                    'work_location' => 'Trung tâm Nghiên cứu Địa tin học, Trường Đại học Nông Lâm - Đại học Thái Nguyên',
                    'accommodation_name' => 'THE KING HOTEL',
                    'accommodation_address' => 'Phường Phan Đình Phùng, tỉnh Thái Nguyên',
                    'recipient_units' => 'Văn phòng UBND tỉnh Thái Nguyên (Phòng Ngoại vụ), Công an tỉnh Thái Nguyên (PA03, PA01), Đại học Thái Nguyên (Ban KHCN&ĐN)',
                    'status' => 'draft',
                    'notes' => 'Đoàn demo - dữ liệu mẫu',
                ],
                'members' => [
                    ['Chen, Wei-Ming', 'Nam', '1968-03-14', 'Đài Loan', 'Giảng viên, Giáo sư', 'P', '360111201', '2032-05-10', '108200101/EV'],
                    ['Lin, Mei-Hua', 'Nữ', '1972-09-02', 'Đài Loan', 'Giảng viên, Phó Giáo sư', 'P', '360111202', '2031-11-21', '108200102/EV'],
                    ['Wang, Chih-Hao', 'Nam', '1981-07-25', 'Đài Loan', 'Giảng viên', 'P', '370222303', '2034-02-18', '108200103/EV'],
                    ['Huang, Yu-Ting', 'Nữ', '1995-12-08', 'Đài Loan', 'Viên chức, Trợ lý dự án', 'P', '371333404', '2035-08-30', '108200104/EV'],
                ],
            ],
            [
                'data' => [
                    'document_no' => '187/ĐHNL-HTQT',
                    'host_unit' => 'Khoa Quản lý Tài nguyên',
                    'purpose' => 'Làm việc về chương trình trao đổi sinh viên và nghiên cứu chung lĩnh vực quản lý đất đai',
                    'work_date_from' => now()->subDays(2)->toDateString(),
                    'work_date_to' => now()->addDays(2)->toDateString(),
                    'work_location' => 'Khoa Quản lý Tài nguyên, Trường Đại học Nông Lâm',
                    'accommodation_name' => 'Khách sạn Mường Thanh Thái Nguyên',
                    'accommodation_address' => 'Phường Phan Đình Phùng, tỉnh Thái Nguyên',
                    'recipient_units' => 'Công an tỉnh Thái Nguyên (PA03, PA01), Sở Ngoại vụ',
                    'status' => 'sent',
                    'notes' => 'Đoàn demo - đang diễn ra',
                ],
                'members' => [
                    ['Tanaka, Hiroshi', 'Nam', '1965-01-30', 'Nhật Bản', 'Giáo sư', 'P', 'TK1234567', '2031-04-12', '108210201/EV'],
                    ['Sato, Yuki', 'Nữ', '1978-06-17', 'Nhật Bản', 'Phó Giáo sư', 'P', 'TK2345678', '2033-09-05', '108210202/EV'],
                    ['Suzuki, Kenji', 'Nam', '1990-10-22', 'Nhật Bản', 'Nghiên cứu viên', 'P', 'TK3456789', '2034-12-01', '108210203/EV'],
                ],
            ],
            [
                'data' => [
                    'document_no' => '142/ĐHNL-HTQT',
                    'host_unit' => 'Phòng Hợp tác Quốc tế',
                    'purpose' => 'Ký kết biên bản ghi nhớ hợp tác đào tạo và nghiên cứu khoa học giữa hai trường',
                    'work_date_from' => now()->subDays(40)->toDateString(),
                    'work_date_to' => now()->subDays(38)->toDateString(),
                    'work_location' => 'Hội trường A, Trường Đại học Nông Lâm - Đại học Thái Nguyên',
                    'accommodation_name' => 'Khách sạn Hoàng Gia',
                    'accommodation_address' => 'Phường Quang Trung, tỉnh Thái Nguyên',
                    'recipient_units' => 'Văn phòng UBND tỉnh Thái Nguyên (Phòng Ngoại vụ), Công an tỉnh Thái Nguyên (PA03, PA01)',
                    'status' => 'completed',
                    'notes' => 'Đoàn demo - đã hoàn tất',
                ],
                'members' => [
                    ['Kim, Min-Jun', 'Nam', '1970-05-09', 'Hàn Quốc', 'Hiệu trưởng', 'P', 'M12345678', '2032-03-15', '108220301/EV'],
                    ['Park, So-Yeon', 'Nữ', '1983-11-03', 'Hàn Quốc', 'Trưởng phòng Hợp tác Quốc tế', 'P', 'M23456789', '2033-07-20', '108220302/EV'],
                    ['Lee, Dong-Hyun', 'Nam', '1988-02-27', 'Hàn Quốc', 'Giảng viên', 'P', 'M34567890', '2035-01-09', '108220303/EV'],
                    ['Choi, Ji-Woo', 'Nữ', '1992-08-14', 'Hàn Quốc', 'Trợ lý', 'P', 'M45678901', '2034-06-25', '108220304/EV'],
                    ['Jung, Tae-Yang', 'Nam', '1975-04-01', 'Hàn Quốc', 'Giáo sư', 'P', 'M56789012', '2031-10-11', '108220305/EV'],
                ],
            ],
        ];

        foreach ($demos as $demo) {
            $delegation = Delegation::updateOrCreate(
                ['document_no' => $demo['data']['document_no']],
                $demo['data'] + ['created_by' => $adminId]
            );

            // Làm mới danh sách thành viên để chạy lại seeder không bị trùng
            $delegation->members()->delete();

            foreach ($demo['members'] as $m) {
                $delegation->members()->create([
                    'full_name' => $m[0],
                    'gender' => $m[1],
                    'date_of_birth' => $m[2],
                    'nationality' => $m[3],
                    'occupation' => $m[4],
                    'passport_type' => $m[5],
                    'passport_no' => $m[6],
                    'passport_expiry' => $m[7],
                    'visa_no' => $m[8],
                ]);
            }
        }
    }
}
