<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    public const TYPES = [
        'cong_tac'  => 'Công tác',
        'hoi_nghi'  => 'Hội nghị, hội thảo',
        'dao_tao'   => 'Đào tạo, bồi dưỡng',
        'hoc_bong'  => 'Học bổng',
        'trao_doi'  => 'Trao đổi sinh viên',
        'tham_quan' => 'Tham quan, học tập',
        'khao_sat'  => 'Khảo sát đối tác',
        'khac'      => 'Khác',
    ];

    /** [nhãn, class màu] */
    public const STATUSES = [
        'draft'         => ['Dự thảo', 'bg-slate-100 text-slate-600'],
        'returned'      => ['Trả lại bổ sung', 'bg-orange-50 text-orange-700 ring-1 ring-orange-200'],
        'pending_unit'  => ['Chờ lãnh đạo đơn vị', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
        'pending_htqt'  => ['Chờ thẩm định', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
        'pending_bgh'   => ['Chờ Ban Giám hiệu', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
        'approved'      => ['Đã duyệt', 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'],
        'completed'     => ['Hoàn tất (đã báo cáo)', 'bg-blue-50 text-blue-700 ring-1 ring-blue-200'],
        'rejected'      => ['Không duyệt', 'bg-red-50 text-red-700 ring-1 ring-red-200'],
    ];

    public const FILE_KINDS = [
        'invitation' => 'Giấy mời / thư mời',
        'decision'   => 'Quyết định cử đi',
        'ticket'     => 'Vé, lịch trình',
        'insurance'  => 'Bảo hiểm',
        'other'      => 'Tài liệu khác',
    ];

    protected $fillable = [
        'code', 'direction', 'trip_type', 'title', 'purpose', 'country', 'city', 'host_org',
        'depart_at', 'return_at', 'unit_id', 'created_by', 'budget_estimate', 'funding_source',
        'status', 'submitted_at', 'decided_at', 'report_due_at', 'notes',
    ];

    protected $casts = [
        'depart_at'      => 'datetime',
        'return_at'      => 'datetime',
        'submitted_at'   => 'datetime',
        'decided_at'     => 'datetime',
        'report_due_at'  => 'date',
        'budget_estimate' => 'integer',
    ];

    // ---------- Quan hệ ----------
    public function unit()      { return $this->belongsTo(Unit::class); }
    public function creator()   { return $this->belongsTo(User::class, 'created_by'); }
    public function approvals() { return $this->hasMany(TripApproval::class)->orderBy('id'); }
    public function report()    { return $this->hasOne(TripReport::class); }
    public function files()     { return $this->hasMany(TripFile::class)->orderBy('id'); }

    public function members()
    {
        return $this->belongsToMany(Person::class, 'trip_members')->withPivot('role_in_trip')->orderBy('trip_members.id');
    }

    // ---------- Hiển thị ----------
    public function getStatusLabelAttribute(): string { return self::STATUSES[$this->status][0] ?? $this->status; }
    public function getStatusClassAttribute(): string { return self::STATUSES[$this->status][1] ?? 'bg-slate-100 text-slate-600'; }
    public function getTypeLabelAttribute(): string   { return self::TYPES[$this->trip_type] ?? $this->trip_type; }

    public function getDaysAttribute(): int
    {
        return $this->depart_at->copy()->startOfDay()->diffInDays($this->return_at->copy()->startOfDay()) + 1;
    }

    public function getTimeRangeAttribute(): string
    {
        $f = $this->depart_at;
        $t = $this->return_at;
        if ($f->isSameDay($t)) {
            return $f->format('H:i') . ' – ' . $t->format('H:i') . ' ngày ' . $f->format('d/m/Y');
        }
        return $f->format('H:i d/m/Y') . ' → ' . $t->format('H:i d/m/Y');
    }

    // ---------- Luồng duyệt ----------
    public function pendingStep(): ?string
    {
        return str_starts_with($this->status, 'pending_') ? substr($this->status, 8) : null;
    }

    public static function stepsFor(?string $creatorRole): array
    {
        $roles = config('trips.step_roles', []);

        // Bỏ bước do chính vai trò của người tạo đảm nhiệm
        return array_values(array_filter(config('trips.steps', []), fn ($s) => ($roles[$s] ?? null) !== $creatorRole));
    }

    /** Các bước còn lại cần duyệt cho hồ sơ này (theo vai trò của người tạo) */
    public function flowSteps(): array
    {
        return self::stepsFor($this->creator?->role);
    }

    public function nextStepAfter(string $step): ?string
    {
        $flow = $this->flowSteps();
        $i = array_search($step, $flow, true);

        return ($i === false) ? null : ($flow[$i + 1] ?? null);
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'returned'], true);
    }

    public function canEditBy(User $u): bool
    {
        return $this->isEditable() && ($u->role === 'admin' || $u->role === 'htqt' || $u->id === $this->created_by);
    }

    public function canActBy(User $u): bool
    {
        $step = $this->pendingStep();
        if (!$step) {
            return false;
        }
        if ($u->role === 'admin') {
            return true;
        }
        if (($roles = config('trips.step_roles')[$step] ?? null) !== $u->role) {
            return false;
        }
        if ($step === 'unit') {
            return $u->unit_id && in_array($this->unit_id, $u->unitSubtreeIds(), true);
        }

        return true;
    }

    public function isMemberOrOwner(User $u): bool
    {
        return $u->id === $this->created_by
            || $this->members()->where('people.user_id', $u->id)->exists()
            || in_array($u->role, ['admin', 'htqt'], true);
    }

    public static function nextCode(): string
    {
        $year = now()->year;
        $n = static::whereYear('created_at', $year)->count() + 1;

        do {
            $code = sprintf('DR-%d-%04d', $year, $n++);
        } while (static::where('code', $code)->exists());

        return $code;
    }

    // ---------- Phạm vi nhìn thấy ----------
    public function scopeVisibleTo(Builder $q, User $u): Builder
    {
        if ($u->role === 'admin') {
            return $q;
        }

        return $q->where(function ($w) use ($u) {
            $w->where('created_by', $u->id)
                ->orWhereHas('members', fn ($m) => $m->where('people.user_id', $u->id));

            if (in_array($u->role, ['htqt', 'bgh'], true)) {
                $w->orWhere('status', '!=', 'draft');
            }
            if ($u->role === 'unit_manager' && $u->unit_id) {
                $w->orWhereIn('unit_id', $u->unitSubtreeIds());
            }
        });
    }

    /** Hồ sơ đang chờ người dùng này duyệt */
    public function scopeAwaiting(Builder $q, User $u): Builder
    {
        $roles = config('trips.step_roles', []);
        $steps = collect(config('trips.steps', []))
            ->filter(fn ($s) => $u->role === 'admin' || ($roles[$s] ?? null) === $u->role);

        $q->whereIn('status', $steps->map(fn ($s) => 'pending_' . $s)->values()->all());

        if ($u->role === 'unit_manager') {
            $q->whereIn('unit_id', $u->unitSubtreeIds() ?: [0]);
        }

        return $q;
    }
}
