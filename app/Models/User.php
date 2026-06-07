<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Submission;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'staff_id',
        'email',
        'password',
        'photo',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function isAcademician()
    {
        return $this->role === 'Academician';
    }

    public function isRiStaff()
    {
        return $this->role === 'R&I Staff';
    }


    public function isHead()
    {
        return $this->role === 'Head';
    }


    public function submissions()
    {
        return $this->hasMany(Submission::class, 'staff_id', 'staff_id');
    }
}
