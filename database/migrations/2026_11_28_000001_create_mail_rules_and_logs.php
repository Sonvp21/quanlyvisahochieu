<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_rules', function (Blueprint $table) {
            $table->id();
            $table->string('event', 30);                    // step_pending, approved, returned, rejected, report_submitted, report_due
            $table->string('direction', 3)->nullable();     // in | out | null = cả hai
            $table->string('name');
            $table->boolean('to_next_approver')->default(false);
            $table->boolean('to_creator')->default(false);
            $table->boolean('to_members')->default(false);
            $table->boolean('to_unit_manager')->default(false);
            $table->json('to_roles')->nullable();           // ['bgh','htqt',...]
            $table->text('extra_emails')->nullable();       // phân tách bằng dấu phẩy
            $table->json('attachments')->nullable();        // ['to_trinh','quyet_dinh','bao_cao']
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('mail_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->nullable()->constrained('trips')->nullOnDelete();
            $table->foreignId('mail_rule_id')->nullable()->constrained('mail_rules')->nullOnDelete();
            $table->string('event', 30);
            $table->text('recipients');
            $table->string('subject');
            $table->string('status', 10);                   // sent | failed
            $table->text('error')->nullable();
            $table->timestamps();
            $table->index(['trip_id', 'created_at']);
        });

        $now = now();
        $rows = [
            ['step_pending', null, 'Báo người duyệt có hồ sơ chờ xử lý', ['to_next_approver' => true], [], []],
            ['approved', 'out', 'Đoàn ra được duyệt: báo người đi, báo cấp trên', ['to_creator' => true, 'to_members' => true], ['bgh', 'htqt'], ['to_trinh', 'quyet_dinh']],
            ['approved', 'in', 'Báo đoàn vào: chuyển lên Ban Giám hiệu và các đơn vị liên quan', ['to_creator' => true, 'to_unit_manager' => true], ['bgh', 'htqt'], ['to_trinh']],
            ['returned', null, 'Hồ sơ bị trả lại: báo người lập', ['to_creator' => true], [], []],
            ['rejected', null, 'Hồ sơ không được duyệt: báo người lập', ['to_creator' => true], [], []],
            ['report_submitted', null, 'Báo cáo sau chuyến: chuyển lên cấp trên', ['to_creator' => true], ['bgh', 'htqt'], ['bao_cao']],
            ['report_due', null, 'Nhắc nộp báo cáo khi sắp đến hạn', ['to_creator' => true, 'to_members' => true], [], []],
        ];
        foreach ($rows as [$event, $dir, $name, $flags, $roles, $att]) {
            DB::table('mail_rules')->insert([
                'event' => $event, 'direction' => $dir, 'name' => $name,
                'to_next_approver' => $flags['to_next_approver'] ?? false,
                'to_creator' => $flags['to_creator'] ?? false,
                'to_members' => $flags['to_members'] ?? false,
                'to_unit_manager' => $flags['to_unit_manager'] ?? false,
                'to_roles' => json_encode($roles), 'attachments' => json_encode($att),
                'enabled' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_logs');
        Schema::dropIfExists('mail_rules');
    }
};
