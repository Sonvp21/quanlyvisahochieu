<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cơ cấu đơn vị dạng cây: Trường → Phòng/Khoa/Trung tâm/Viện → Bộ môn
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('code', 50)->unique();          // Mã đơn vị (viết tắt)
            $table->string('name');                         // Tên đơn vị
            $table->string('type', 30)->default('phong');   // bgh | phong | khoa | trung_tam | vien | bo_mon | khac
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('role')->constrained('units')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
        });
        Schema::dropIfExists('units');
    }
};
