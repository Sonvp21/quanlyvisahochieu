<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('work_location')->nullable()->after('host_org');      // Địa điểm làm việc (đoàn vào)
            $table->string('accommodation')->nullable()->after('work_location'); // Nơi lưu trú (đoàn vào)
            $table->text('reception_notes')->nullable()->after('purpose');       // Chương trình, đón tiếp: xe, ăn nghỉ...
            $table->foreignId('delegation_id')->nullable()->after('unit_id')->constrained('delegations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delegation_id');
            $table->dropColumn(['work_location', 'accommodation', 'reception_notes']);
        });
    }
};
