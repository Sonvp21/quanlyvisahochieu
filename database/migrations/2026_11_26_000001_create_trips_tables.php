<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();               // DR-2026-0001
            $table->string('direction', 10)->default('out');    // out = đoàn ra (in = đoàn vào, giai đoạn sau)
            $table->string('trip_type', 30);
            $table->string('title');
            $table->text('purpose')->nullable();
            $table->string('country', 100);
            $table->string('city', 150)->nullable();
            $table->string('host_org')->nullable();             // Đơn vị mời / tiếp nhận
            $table->dateTime('depart_at');
            $table->dateTime('return_at');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete(); // Đơn vị đề xuất
            $table->foreignId('created_by')->constrained('users');
            $table->decimal('budget_estimate', 15, 0)->nullable();
            $table->string('funding_source')->nullable();
            $table->string('status', 30)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->date('report_due_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('depart_at');
        });

        Schema::create('trip_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('role_in_trip', 100)->default('Thành viên');
            $table->unique(['trip_id', 'person_id']);
        });

        Schema::create('trip_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('step', 30)->nullable();             // unit | htqt | bgh (null khi gửi duyệt)
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 20);                       // submitted | approved | returned | rejected
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('trip_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('submitted_by')->constrained('users');
            $table->text('summary');
            $table->text('results')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
        });

        Schema::create('trip_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 30)->default('other');       // invitation | decision | ticket | insurance | other
            $table->string('original_name');
            $table->string('path');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_files');
        Schema::dropIfExists('trip_reports');
        Schema::dropIfExists('trip_approvals');
        Schema::dropIfExists('trip_members');
        Schema::dropIfExists('trips');
    }
};
