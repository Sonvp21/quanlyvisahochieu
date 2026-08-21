<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            $table->string('facility_name')->nullable()->comment('Tên cơ sở lưu trú');
            $table->string('address')->nullable()->comment('Địa chỉ (SN/Tổ/Xóm)');
            $table->string('ward')->nullable()->comment('Phường, xã');

            $table->date('arrival_date')->nullable()->comment('Ngày đến CSLT');
            $table->date('expected_departure_date')->nullable()->comment('Ngày dự kiến đi');

            $table->string('certificate_no')->nullable()->comment('Số chứng nhận tạm trú');
            $table->string('category')->nullable()->comment('Ký hiệu (DH, LD, DL...)');

            $table->text('notes')->nullable();
            $table->enum('last_updated_by', ['student', 'admin'])->default('admin');

            $table->timestamps();

            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residences');
    }
};