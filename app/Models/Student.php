<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_code',
        'full_name',
        'student_type',
        'date_of_birth',
        'gender',
        'nationality',
        'phone',
        'address',
        'major',
        'enrollment_date',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passport()
    {
        return $this->hasOne(Passport::class);
    }

    public function visa()
    {
        return $this->hasOne(Visa::class);
    }

    // ==================== PASSPORT HELPERS ====================

    /**
     * Trạng thái passport: valid | expiring_soon | expired | none
     */
   public function getPassportStatus()
{
    if (!$this->passport || !$this->passport->expiry_date) {
        return 'none';
    }

    $expiryDate = Carbon::parse($this->passport->expiry_date)->endOfDay();
    $now = Carbon::now();

    // Đã hết hạn: qua 23:59:59 của ngày hết hạn
    if ($now->greaterThan($expiryDate)) {
        return 'expired';
    }

    // Sắp hết hạn: còn trong vòng 30 ngày
    if ($now->diffInDays($expiryDate) <= 30) {
        return 'expiring_soon';
    }

    // Còn hạn
    return 'valid';
}


    /**
     * Số ngày còn lại đến khi passport hết hạn
     */
    public function getDaysUntilPassportExpiry()
    {
        if (!$this->passport || !$this->passport->expiry_date) {
            return null;
        }

        return now()->diffInDays(
            Carbon::parse($this->passport->expiry_date)->endOfDay(),
            false
        );
    }

    public function getPassportStatusText()
    {
        return match ($this->getPassportStatus()) {
            'valid' => 'Còn hạn',
            'expiring_soon' => 'Sắp hết hạn',
            'expired' => 'Đã hết hạn',
            'none' => 'Chưa có',
            default => 'Không xác định',
        };
    }

    public function getPassportStatusColor()
    {
        return match ($this->getPassportStatus()) {
            'valid' => 'green',
            'expiring_soon' => 'yellow',
            'expired' => 'red',
            'none' => 'gray',
            default => 'gray',
        };
    }

    // ==================== VISA HELPERS ====================

    /**
     * Trạng thái visa: valid | expiring_soon | expired | none
     */
    public function getVisaStatus()
    {
        if (!$this->visa || !$this->visa->expiry_date) {
            return 'none';
        }

        $expiryDate = Carbon::parse($this->visa->expiry_date)->endOfDay();
        $days = now()->diffInDays($expiryDate, false);

        if ($days < 0) {
            return 'expired';
        }

        if ($days <= 30) {
            return 'expiring_soon';
        }

        return 'valid';
    }

    /**
     * Số ngày còn lại đến khi visa hết hạn
     */
    public function getDaysUntilVisaExpiry()
    {
        if (!$this->visa || !$this->visa->expiry_date) {
            return null;
        }

        return now()->diffInDays(
            Carbon::parse($this->visa->expiry_date)->endOfDay(),
            false
        );
    }

    public function getVisaStatusText()
    {
        return match ($this->getVisaStatus()) {
            'valid' => 'Còn hạn',
            'expiring_soon' => 'Sắp hết hạn',
            'expired' => 'Đã hết hạn',
            'none' => 'Chưa có',
            default => 'Không xác định',
        };
    }

    public function getVisaStatusColor()
    {
        return match ($this->getVisaStatus()) {
            'valid' => 'green',
            'expiring_soon' => 'yellow',
            'expired' => 'red',
            'none' => 'gray',
            default => 'gray',
        };
    }
}
