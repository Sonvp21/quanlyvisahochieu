<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "Người" dùng chung: cán bộ, giảng viên, sinh viên VN, sinh viên quốc tế, khách
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('person_type', 30)->default('staff'); // staff | student_vn | student_intl | guest
            $table->string('full_name');
            $table->string('gender', 10)->nullable();            // male | female | other
            $table->date('date_of_birth')->nullable();
            $table->string('nationality', 100)->default('Việt Nam');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('position')->nullable();              // Chức vụ / nghề nghiệp
            $table->string('academic_title')->nullable();        // Học hàm, học vị (GS, PGS, TS, ThS…)
            $table->string('student_code', 50)->nullable();
            $table->string('student_level', 20)->nullable();     // dai_hoc | thac_si | tien_si (sinh viên)
            $table->string('organization')->nullable();         // Cơ quan/doanh nghiệp (khách)
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->unique()->constrained('students')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('person_type');
            $table->index('full_name');
        });

        // Hộ chiếu/visa gắn được với bất kỳ người nào (không chỉ sinh viên quốc tế)
        Schema::table('passports', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('student_id')->constrained('people')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->change();
        });
        Schema::table('visas', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('student_id')->constrained('people')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('person_id');
        });
        Schema::table('passports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('person_id');
        });
        Schema::dropIfExists('people');
    }
};
