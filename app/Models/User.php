<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasHashid, HashidRouting, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'job',
        'photo',
        'verification_token',
        'email_verified_at',
        'demo_quota',
        'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'email_verified_at',
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];
    protected $guard_name = 'api';
    protected $appends = ['hashid'];
    protected $with = ['company'];

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function tutorial()
    {
        return $this->hasOne(Tutorial::class);
    }
}
