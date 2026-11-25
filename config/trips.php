<?php

/*
| Luồng phê duyệt chuyến công tác (đoàn ra). Sửa mảng 'steps' để đổi thứ tự/số bước.
| Mỗi bước phải có tên (step_labels) và vai trò được duyệt (step_roles).
| Vai trò 'admin' luôn có quyền duyệt thay ở mọi bước.
*/
return [
    'steps' => ['unit', 'htqt', 'bgh'],

    'step_labels' => [
        'unit' => 'Lãnh đạo đơn vị',
        'htqt' => 'Phòng chuyên trách đối ngoại (thẩm định)',
        'bgh'  => 'Ban Giám hiệu',
    ],

    'step_roles' => [
        'unit' => 'unit_manager',
        'htqt' => 'htqt',
        'bgh'  => 'bgh',
    ],

    // Số ngày kể từ ngày về phải nộp báo cáo sau chuyến
    'report_days' => 15,
];
