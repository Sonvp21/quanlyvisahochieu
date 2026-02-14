<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_notifications', function (Blueprint $table) {
            // Lưu ngày hết hạn tại thời điểm gửi email
            $table->date('expiry_date_at_send')->nullable()->after('notifiable_id');
        });
    }

    public function down(): void
    {
        Schema::table('email_notifications', function (Blueprint $table) {
            $table->dropColumn('expiry_date_at_send');
        });
    }
};
