<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Kiểm tra xem column đã tồn tại chưa trước khi thêm
            if (!Schema::hasColumn('students', 'student_type')) {
                $table->enum('student_type', ['exchange', 'regular', 'postgraduate'])
                      ->nullable()
                      ->after('student_code')
                      ->comment('Loại sinh viên: trao đổi | chính quy | sau đại học');
            }

            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')
                      ->nullable()
                      ->after('full_name')
                      ->comment('Ngày sinh');
            }

            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])
                      ->nullable()
                      ->after('date_of_birth')
                      ->comment('Giới tính');
            }

            if (!Schema::hasColumn('students', 'nationality')) {
                $table->string('nationality', 100)
                      ->nullable()
                      ->after('gender')
                      ->comment('Quốc tịch');
            }

            if (!Schema::hasColumn('students', 'address')) {
                $table->string('address', 255)
                      ->nullable()
                      ->after('phone')
                      ->comment('Địa chỉ tại VN');
            }

            if (!Schema::hasColumn('students', 'major')) {
                $table->string('major', 255)
                      ->nullable()
                      ->after('address')
                      ->comment('Ngành học');
            }

            if (!Schema::hasColumn('students', 'enrollment_date')) {
                $table->date('enrollment_date')
                      ->nullable()
                      ->after('major')
                      ->comment('Ngày nhập học');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columns = ['student_type', 'date_of_birth', 'gender', 'nationality', 'address', 'major', 'enrollment_date'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
