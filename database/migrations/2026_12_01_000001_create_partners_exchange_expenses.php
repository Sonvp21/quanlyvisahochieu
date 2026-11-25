<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('partner_type', 20)->default('university'); // university, company, organization, government, other
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete(); // đơn vị phụ trách
            $table->string('agreement_type', 20)->default('mou');     // mou, moa, contract, other
            $table->string('title');
            $table->string('reference_no')->nullable();
            $table->date('signed_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('is_terminated')->default(false);
            $table->text('scope')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
            $table->index('expires_at');
        });

        Schema::create('exchange_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agreement_id')->nullable()->constrained()->nullOnDelete();
            $table->string('program_type', 20)->default('semester'); // semester, short_term, summer, dual_degree, internship
            $table->string('level', 20)->nullable();                // dai_hoc, thac_si, tien_si
            $table->string('country')->nullable();
            $table->date('apply_deadline')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedSmallInteger('slots')->nullable();
            $table->text('benefits')->nullable();       // học bổng, hỗ trợ
            $table->text('requirements')->nullable();   // điều kiện GPA, ngoại ngữ
            $table->string('status', 10)->default('open'); // open, closed
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('exchange_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('status', 15)->default('applied'); // applied, shortlisted, approved, rejected, ongoing, completed, withdrawn
            $table->decimal('gpa', 4, 2)->nullable();
            $table->string('language_cert')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
            $table->unique(['exchange_program_id', 'person_id']);
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exchange_application_id')->nullable()->constrained('exchange_applications')->nullOnDelete();
        });

        Schema::create('trip_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('category', 20);              // airfare, lodging, living, visa, insurance, local, other
            $table->string('kind', 10)->default('plan'); // plan = dự toán, actual = thực chi
            $table->string('description')->nullable();
            $table->unsignedBigInteger('amount');
            $table->string('funding_source')->nullable();
            $table->date('spent_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['trip_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_expenses');
        Schema::table('trips', function (Blueprint $table) {
            $table->dropConstrainedForeignId('exchange_application_id');
            $table->dropConstrainedForeignId('partner_id');
        });
        Schema::dropIfExists('exchange_applications');
        Schema::dropIfExists('exchange_programs');
        Schema::dropIfExists('agreements');
        Schema::dropIfExists('partners');
    }
};
