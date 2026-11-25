<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 20);              // created, updated, deleted, login
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('trip_id')->nullable()->index();
            $table->string('summary');
            $table->json('changes')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });

        Schema::create('annual_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->string('direction', 3)->default('out');   // out | in
            $table->string('title');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $table->string('country')->nullable();
            $table->unsignedTinyInteger('planned_month')->nullable();
            $table->unsignedSmallInteger('planned_members')->nullable();
            $table->unsignedBigInteger('budget')->nullable();
            $table->string('status', 12)->default('planned'); // planned, postponed, cancelled
            $table->foreignId('trip_id')->nullable()->constrained('trips')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['year', 'direction']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_plans');
        Schema::dropIfExists('audit_logs');
    }
};
