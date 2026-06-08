<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('passport_count')->default(0);
            $table->integer('visa_count')->default(0);
            $table->integer('total_count')->default(0);
            $table->text('details')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->timestamp('run_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_reports');
    }
};
