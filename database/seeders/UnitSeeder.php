<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /** Đơn vị khởi tạo — có thể sửa/xóa/thêm trong trang Đơn vị */
    public function run(): void
    {
        $rows = [
            ['BGH',    'Ban Giám hiệu',                'bgh',       1],
            ['HTQT',   'Phòng Hợp tác quốc tế',        'phong',     2],
            ['QLKHCN', 'Phòng Quản lý KHCN',           'phong',     3],
            ['HCTC',   'Phòng HCTC',                   'phong',     4],
            ['GIRC',   'Trung tâm Địa tin học (GIRC)', 'trung_tam', 5],
        ];

        foreach ($rows as [$code, $name, $type, $order]) {
            Unit::updateOrCreate(['code' => $code], ['name' => $name, 'type' => $type, 'sort_order' => $order, 'is_active' => true]);
        }
    }
}
