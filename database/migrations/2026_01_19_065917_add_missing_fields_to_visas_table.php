<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            if (!Schema::hasColumn('visas', 'country')) {
                $table->string('country', 100)
                      ->nullable()
                      ->after('visa_type')
                      ->comment('Quốc gia cấp visa');
            }

            if (!Schema::hasColumn('visas', 'entry_type')) {
                $table->enum('entry_type', ['single', 'multiple'])
                      ->default('single')
                      ->after('expiry_date')
                      ->comment('Loại nhập cảnh: đơn | nhiều lần');
            }

            if (!Schema::hasColumn('visas', 'status')) {
                $table->enum('status', ['valid', 'expired', 'cancelled'])
                      ->default('valid')
                      ->after('entry_type')
                      ->comment('Trạng thái visa');
            }

            if (!Schema::hasColumn('visas', 'last_updated_by')) {
                $table->enum('last_updated_by', ['student', 'admin'])
                      ->default('admin')
                      ->after('image')
                      ->comment('Người cập nhật cuối cùng');
            }
        });
    }

    public function down(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $columns = ['country', 'entry_type', 'status', 'last_updated_by'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('visas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
