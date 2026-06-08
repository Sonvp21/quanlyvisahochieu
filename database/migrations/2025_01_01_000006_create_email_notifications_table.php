<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('notifiable_type', 20);
            $table->bigInteger('notifiable_id');
            $table->date('expiry_date_at_send')->nullable();
            $table->dateTime('last_sent_at')->nullable();
            $table->dateTime('next_send_at')->nullable();
            $table->integer('send_count')->default(0);
            $table->string('status', 20)->default('pending');
            $table->string('inactive_reason')->nullable();
            $table->timestamp('inactive_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'notifiable_type', 'notifiable_id']);
            $table->index('status');
            $table->index(['status', 'notifiable_type'], 'idx_status_type');
            $table->index(['student_id', 'status'], 'idx_student_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_notifications');
    }
};
