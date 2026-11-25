<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailRule extends Model
{
    public const EVENTS = [
        'step_pending'     => 'Hồ sơ chuyển đến bước duyệt tiếp theo',
        'approved'         => 'Hồ sơ được duyệt xong',
        'returned'         => 'Hồ sơ bị trả lại',
        'rejected'         => 'Hồ sơ không được duyệt',
        'report_submitted' => 'Đã nộp báo cáo sau chuyến / tiếp đoàn',
        'report_due'       => 'Nhắc hạn nộp báo cáo',
    ];

    public const DOCS = [
        'to_trinh'   => 'Tờ trình',
        'quyet_dinh' => 'Quyết định cử đi',
        'bao_cao'    => 'Báo cáo kết quả',
    ];

    protected $fillable = [
        'event', 'direction', 'name', 'to_next_approver', 'to_creator', 'to_members', 'to_unit_manager',
        'to_roles', 'extra_emails', 'attachments', 'enabled',
    ];

    protected $casts = [
        'to_next_approver' => 'boolean', 'to_creator' => 'boolean', 'to_members' => 'boolean',
        'to_unit_manager' => 'boolean', 'to_roles' => 'array', 'attachments' => 'array', 'enabled' => 'boolean',
    ];
}
