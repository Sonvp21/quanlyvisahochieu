<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            if (!Schema::hasColumn('passports', 'country_of_issue')) {
                $table->string('country_of_issue', 100)
                      ->nullable()
                      ->after('passport_number')
                      ->comment('Quốc gia cấp hộ chiếu');
            }

            if (!Schema::hasColumn('passports', 'last_updated_by')) {
                $table->enum('last_updated_by', ['student', 'admin'])
                      ->default('admin')
                      ->after('image')
                      ->comment('Người cập nhật cuối cùng');
            }
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            if (Schema::hasColumn('passports', 'country_of_issue')) {
                $table->dropColumn('country_of_issue');
            }

            if (Schema::hasColumn('passports', 'last_updated_by')) {
                $table->dropColumn('last_updated_by');
            }
        });
    }
};
