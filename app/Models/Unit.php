<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public const TYPES = [
        'bgh'       => 'Ban Giám hiệu',
        'phong'     => 'Phòng/Ban',
        'khoa'      => 'Khoa',
        'trung_tam' => 'Trung tâm',
        'vien'      => 'Viện',
        'bo_mon'    => 'Bộ môn',
        'khac'      => 'Khác',
    ];

    protected $fillable = ['parent_id', 'code', 'name', 'type', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Unit::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function people()
    {
        return $this->hasMany(Person::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /** Id của chính nó và mọi đơn vị con cháu */
    public function selfAndDescendantIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->selfAndDescendantIds());
        }
        return $ids;
    }

    /** Danh sách phẳng theo thứ tự cây, mỗi phần tử có thuộc tính depth */
    public static function flatTree(?array $excludeIds = null)
    {
        $all = static::orderBy('sort_order')->orderBy('name')->get()->groupBy('parent_id');
        $out = collect();
        $walk = function ($parentId, $depth) use (&$walk, $all, $out, $excludeIds) {
            foreach ($all->get($parentId, collect()) as $unit) {
                if ($excludeIds && in_array($unit->id, $excludeIds)) {
                    continue;
                }
                $unit->depth = $depth;
                $out->push($unit);
                $walk($unit->id, $depth + 1);
            }
        };
        $walk(null, 0);
        return $out;
    }
}
