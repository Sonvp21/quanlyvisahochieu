<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

        $table->string('student_code')->nullable();
        $table->string('full_name');
        $table->string('student_type')->nullable(); // exchange | regular | postgraduate
        $table->date('date_of_birth')->nullable();
        $table->string('gender')->nullable();       // male | female | other
        $table->string('nationality')->nullable();
        $table->string('phone')->nullable();
        $table->string('address')->nullable();
        $table->string('major')->nullable();
        $table->date('enrollment_date')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
