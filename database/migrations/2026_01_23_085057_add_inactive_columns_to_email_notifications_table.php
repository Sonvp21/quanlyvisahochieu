<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_notifications', function (Blueprint $table) {
            $table->string('inactive_reason')->nullable()->after('status');
            $table->timestamp('inactive_at')->nullable()->after('inactive_reason');

            $table->index(['status', 'notifiable_type'], 'idx_status_type');
            $table->index(['student_id', 'status'], 'idx_student_status');
        });
    }

    public function down(): void
    {
        Schema::table('email_notifications', function (Blueprint $table) {
            $table->dropIndex('idx_status_type');
            $table->dropIndex('idx_student_status');
            $table->dropColumn(['inactive_reason', 'inactive_at']);
        });
    }
};
