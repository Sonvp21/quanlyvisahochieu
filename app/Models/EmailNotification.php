<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class EmailNotification extends Model
// {
//     protected $fillable = [
//         'student_id',
//         'notifiable_type',
//         'notifiable_id',
//         'expiry_date_at_send',  // ← THÊM DÒNG NÀY
//         'last_sent_at',
//         'next_send_at',
//         'send_count',
//         'status',
//     ];

//     protected $casts = [
//         'last_sent_at' => 'datetime',
//         'next_send_at' => 'datetime',
//         'expiry_date_at_send' => 'date',  // ← THÊM DÒNG NÀY
//     ];

//     // ==================== RELATIONSHIPS ====================

//     public function student()
//     {
//         return $this->belongsTo(Student::class);
//     }

//     // ==================== HELPER METHODS ====================

//     /**
//      * Đánh dấu là đã dừng gửi (khi sinh viên cập nhật mới)
//      */
//     public function stop()
//     {
//         $this->update(['status' => 'stopped']);
//     }

//     /**
//      * Scope: Lấy các thông báo đang pending
//      */
//     public function scopePending($query)
//     {
//         return $query->where('status', 'pending');
//     }

//     /**
//      * Scope: Lấy các thông báo cho passport
//      */
//     public function scopeForPassport($query)
//     {
//         return $query->where('notifiable_type', 'passport');
//     }

//     /**
//      * Scope: Lấy các thông báo cho visa
//      */
//     public function scopeForVisa($query)
//     {
//         return $query->where('notifiable_type', 'visa');
//     }
// }


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{
    protected $fillable = [
        'student_id',
        'notifiable_type',
        'notifiable_id',
        'expiry_date_at_send',
        'last_sent_at',
        'next_send_at',
        'send_count',
        'status',
        'inactive_reason',  // ← THÊM MỚI
        'inactive_at',      // ← THÊM MỚI
    ];

    protected $casts = [
        'last_sent_at' => 'datetime',
        'next_send_at' => 'datetime',
        'inactive_at' => 'datetime',  // ← THÊM MỚI
        'expiry_date_at_send' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Đánh dấu là đã dừng gửi (khi hết hạn)
     */
    public function stop()
    {
        $this->update([
            'status' => 'stopped',
            'next_send_at' => null,
        ]);
    }

    /**
     * Đánh dấu là inactive (khi gia hạn hoặc xóa tài liệu)
     */
    public function markInactive($reason)
    {
        $this->update([
            'status' => 'inactive',
            'next_send_at' => null,
            'inactive_reason' => $reason,
            'inactive_at' => now(),
        ]);
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeStopped($query)
    {
        return $query->where('status', 'stopped');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'stopped']);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeForPassport($query)
    {
        return $query->where('notifiable_type', 'passport');
    }

    public function scopeForVisa($query)
    {
        return $query->where('notifiable_type', 'visa');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // ==================== ACCESSORS ====================

    public function shouldSendNow()
    {
        if ($this->status !== 'pending' || !$this->next_send_at) {
            return false;
        }
        return now()->gte($this->next_send_at);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Đang gửi',
            'stopped' => 'Đã dừng',
            'inactive' => 'Không hoạt động',
            default => 'Không xác định',
        };
    }

    public function getDocumentTypeAttribute()
    {
        return match($this->notifiable_type) {
            'passport' => 'Hộ chiếu',
            'visa' => 'Visa',
            default => 'Không xác định',
        };
    }
}
