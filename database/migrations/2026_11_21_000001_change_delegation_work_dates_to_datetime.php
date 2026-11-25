<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delegations', function (Blueprint $table) {
            $table->dateTime('work_date_from')->nullable()->change();
            $table->dateTime('work_date_to')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('delegations', function (Blueprint $table) {
            $table->date('work_date_from')->nullable()->change();
            $table->date('work_date_to')->nullable()->change();
        });
    }
};
