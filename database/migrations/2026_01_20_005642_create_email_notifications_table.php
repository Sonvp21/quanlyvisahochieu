<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('notifiable_type', 20); // 'passport' hoặc 'visa'
            $table->bigInteger('notifiable_id'); // ID của passport hoặc visa
            $table->dateTime('last_sent_at')->nullable(); // Lần gửi mail gần nhất
            $table->dateTime('next_send_at')->nullable(); // Lần dự kiến gửi tiếp theo
            $table->integer('send_count')->default(0); // Số lần đã gửi
            $table->string('status', 20)->default('pending'); // 'pending' hoặc 'stopped'
            $table->timestamps();

            // Index để tìm kiếm nhanh
            $table->index(['student_id', 'notifiable_type', 'notifiable_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_notifications');
    }
};
