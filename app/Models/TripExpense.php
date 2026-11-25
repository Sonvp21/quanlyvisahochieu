<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripExpense extends Model
{
    public const CATEGORIES = [
        'airfare'   => 'Vé máy bay, đi lại quốc tế',
        'lodging'   => 'Lưu trú',
        'living'    => 'Ăn ở, sinh hoạt phí',
        'visa'      => 'Visa, lệ phí',
        'insurance' => 'Bảo hiểm',
        'local'     => 'Đi lại, đón tiếp trong nước',
        'other'     => 'Khác',
    ];

    public const KINDS = ['plan' => 'Dự toán', 'actual' => 'Thực chi'];

    protected $fillable = ['trip_id', 'category', 'kind', 'description', 'amount', 'funding_source', 'spent_at', 'created_by'];

    protected $casts = ['amount' => 'integer', 'spent_at' => 'date'];

    public function trip() { return $this->belongsTo(Trip::class); }
}
