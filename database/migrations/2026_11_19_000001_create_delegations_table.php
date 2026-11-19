<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delegations', function (Blueprint $table) {
            $table->id();
            $table->string('document_no')->nullable();           // Số công văn, ví dụ: 123/ĐHNL-HTQT
            $table->string('host_unit')->nullable();              // Đơn vị tiếp đón, VD: Trung tâm Nghiên cứu Địa tin học
            $table->text('purpose')->nullable();                  // Nội dung làm việc
            $table->date('work_date_from')->nullable();           // Ngày bắt đầu làm việc
            $table->date('work_date_to')->nullable();             // Ngày kết thúc làm việc
            $table->string('work_location')->nullable();          // Địa điểm công tác
            $table->string('accommodation_name')->nullable();     // Tên nơi tạm trú (khách sạn...)
            $table->string('accommodation_address')->nullable();  // Địa chỉ tạm trú dự kiến
            $table->string('recipient_units')->nullable();        // Nơi gửi công văn (PA03, PA01, Sở Ngoại vụ...)
            $table->enum('status', ['draft', 'sent', 'completed'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('delegation_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delegation_id')->constrained('delegations')->cascadeOnDelete();
            $table->string('full_name');
            $table->enum('gender', ['Nam', 'Nữ'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->default('Đài Loan');
            $table->string('occupation')->nullable();             // Giảng viên, Giáo sư, Trợ lý dự án...
            $table->string('passport_type')->nullable();          // Loại hộ chiếu, ví dụ: P
            $table->string('passport_no')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('visa_no')->nullable();                // Số thị thực nhập cảnh
            $table->string('passport_scan_path')->nullable();     // Ảnh scan hộ chiếu (tùy chọn)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegation_members');
        Schema::dropIfExists('delegations');
    }
};
