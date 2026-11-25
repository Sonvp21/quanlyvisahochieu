<?php

/*
| Nguồn duy nhất cho các ô chức năng ở trang chủ (kiểu VNeID).
| Mỗi nhóm có: title, color (blue|indigo|emerald|amber|rose|sky|violet|slate), roles, items.
| Mỗi item: label, route, params (tuỳ chọn), icon (khoá trong 'icons'), roles (tuỳ chọn, thu hẹp thêm),
|           badge ('to_approve'), post (true = gửi POST, dùng cho đăng xuất).
| Route không tồn tại sẽ tự bị ẩn. Thêm chức năng mới chỉ cần thêm một dòng ở đây.
*/
return [
    'icons' => [
        'home'     => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'list'     => 'M4 6h16M4 12h16M4 18h16',
        'send'     => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
        'users'    => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4',
        'check'    => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'user'     => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'plus'     => 'M12 4v16m8-8H4',
        'doc'      => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'card'     => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'globe'    => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'chart'    => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'grid'     => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z',
        'mail'     => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'academic' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
        'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        'account'  => 'M15 7a3 3 0 11-6 0 3 3 0 016 0zM3 20a6 6 0 0112 0M17 11l2 2 4-4',
        'download' => 'M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3',
        'logout'   => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
        'history'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    ],

    'groups' => [
        [
            'title' => 'Của tôi', 'color' => 'sky', 'roles' => ['student'],
            'items' => [
                ['label' => 'Tổng quan', 'route' => 'student.dashboard', 'icon' => 'grid'],
                ['label' => 'Hồ sơ của tôi', 'route' => 'student.profile.show', 'icon' => 'user'],
                ['label' => 'Hộ chiếu', 'route' => 'student.passport.index', 'icon' => 'doc'],
            ],
        ],
        [
            'title' => 'Đoàn ra & đoàn vào', 'color' => 'blue', 'roles' => ['admin', 'bgh', 'htqt', 'unit_manager', 'staff'],
            'items' => [
                ['label' => 'Đi công tác', 'route' => 'trips.create', 'icon' => 'plus'],
                ['label' => 'Tiếp đoàn vào', 'route' => 'trips.create', 'params' => ['direction' => 'in'], 'icon' => 'plus'],
                ['label' => 'Chờ tôi duyệt', 'route' => 'trips.index', 'params' => ['filter' => 'to_approve'], 'icon' => 'check', 'roles' => ['admin', 'bgh', 'htqt', 'unit_manager'], 'badge' => 'to_approve'],
                ['label' => 'Hồ sơ của tôi', 'route' => 'trips.index', 'params' => ['filter' => 'mine'], 'icon' => 'user'],
                ['label' => 'Tất cả hồ sơ', 'route' => 'trips.index', 'icon' => 'list'],
                ['label' => 'Đoàn ra', 'route' => 'trips.index', 'params' => ['direction' => 'out'], 'icon' => 'send'],
                ['label' => 'Đoàn vào', 'route' => 'trips.index', 'params' => ['direction' => 'in'], 'icon' => 'users'],
            ],
        ],
        [
            'title' => 'Tổng quan & báo cáo', 'color' => 'indigo', 'roles' => ['admin', 'bgh', 'htqt'],
            'items' => [
                ['label' => 'Tổng quan', 'route' => 'overview', 'icon' => 'grid'],
                ['label' => 'Thống kê', 'route' => 'stats.index', 'icon' => 'chart'],
                ['label' => 'Kế hoạch năm', 'route' => 'plans.index', 'icon' => 'calendar'],
                ['label' => 'Báo cáo email', 'route' => 'admin.notification-reports.index', 'icon' => 'mail', 'roles' => ['admin', 'htqt']],
            ],
        ],
        [
            'title' => 'Hợp tác', 'color' => 'emerald', 'roles' => ['admin', 'htqt'],
            'items' => [
                ['label' => 'Đối tác', 'route' => 'admin.partners.index', 'icon' => 'globe'],
                ['label' => 'MOU & hạn hiệu lực', 'route' => 'admin.agreements.index', 'icon' => 'doc'],
                ['label' => 'Trao đổi sinh viên', 'route' => 'admin.exchange-programs.index', 'icon' => 'academic'],
            ],
        ],
        [
            'title' => 'Visa, hộ chiếu & hồ sơ', 'color' => 'amber', 'roles' => ['admin', 'htqt'],
            'items' => [
                ['label' => 'Sinh viên quốc tế', 'route' => 'admin.students.index', 'icon' => 'users'],
                ['label' => 'Thêm sinh viên', 'route' => 'admin.students.create', 'icon' => 'plus'],
                ['label' => 'Hộ chiếu', 'route' => 'admin.passports.index', 'icon' => 'doc'],
                ['label' => 'Visa', 'route' => 'admin.visas.index', 'icon' => 'card'],
                ['label' => 'Công văn báo đoàn', 'route' => 'admin.delegations.index', 'icon' => 'globe'],
                ['label' => 'Xuất Excel mẫu 01', 'route' => 'admin.students.export', 'icon' => 'download'],
                ['label' => 'Nhân sự & khách', 'route' => 'admin.people.index', 'icon' => 'user'],
                ['label' => 'Cơ cấu đơn vị', 'route' => 'admin.units.index', 'icon' => 'building'],
            ],
        ],
        [
            'title' => 'Hệ thống', 'color' => 'rose', 'roles' => ['admin'],
            'items' => [
                ['label' => 'Tài khoản', 'route' => 'admin.users.index', 'icon' => 'account'],
                ['label' => 'Quy tắc email', 'route' => 'admin.mail-rules.index', 'icon' => 'mail'],
                ['label' => 'Nhật ký thao tác', 'route' => 'admin.audit-logs.index', 'icon' => 'history'],
                ['label' => 'Dashboard visa', 'route' => 'admin.dashboard', 'icon' => 'grid'],
            ],
        ],
        [
            'title' => 'Cá nhân', 'color' => 'slate', 'roles' => ['admin', 'bgh', 'htqt', 'unit_manager', 'staff', 'student'],
            'items' => [
                ['label' => 'Tài khoản của tôi', 'route' => 'profile.edit', 'icon' => 'user'],
                ['label' => 'Đăng xuất', 'route' => 'logout', 'icon' => 'logout', 'post' => true],
            ],
        ],
    ],
];
