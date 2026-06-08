<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('student_code')->nullable();
            $table->string('full_name');
            $table->enum('student_type', ['exchange', 'regular', 'postgraduate'])->nullable()->comment('Loại sinh viên');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('phone')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('major', 255)->nullable();
            $table->date('enrollment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
