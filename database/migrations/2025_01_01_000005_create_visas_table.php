<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('visa_type');
            $table->string('visa_number')->nullable();
            $table->string('country', 100)->nullable()->comment('Quốc gia cấp visa');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date');
            $table->enum('entry_type', ['single', 'multiple'])->default('single')->comment('Loại nhập cảnh');
            $table->enum('status', ['valid', 'expired', 'cancelled'])->default('valid');
            $table->string('image')->nullable();
            $table->enum('last_updated_by', ['student', 'admin'])->default('admin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visas');
    }
};
