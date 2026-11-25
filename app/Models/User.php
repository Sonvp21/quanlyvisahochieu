<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = [
        'admin'        => 'Quản trị viên',
        'bgh'          => 'Ban Giám hiệu',
        'htqt'         => 'Phòng chuyên trách đối ngoại',
        'unit_manager' => 'Lãnh đạo đơn vị',
        'staff'        => 'Cán bộ, giảng viên',
        'student'      => 'Sinh viên quốc tế',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function person()
    {
        return $this->hasOne(Person::class);
    }

    /** Id đơn vị của người dùng và mọi đơn vị con cháu */
    public function unitSubtreeIds(): array
    {
        return $this->unit_id ? (Unit::find($this->unit_id)?->selfAndDescendantIds() ?? []) : [];
    }
}
