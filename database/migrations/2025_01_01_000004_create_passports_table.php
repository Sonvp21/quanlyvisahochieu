<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('passport_number')->unique();
            $table->string('country_of_issue', 100)->nullable()->comment('Quốc gia cấp hộ chiếu');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date');
            $table->string('place_of_issue')->nullable();
            $table->string('image')->nullable();
            $table->enum('last_updated_by', ['student', 'admin'])->default('admin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
