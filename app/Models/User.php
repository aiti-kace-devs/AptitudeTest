<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str; // ✅ Add this line

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'exam',
        'status',
        'mobile_no',
        'password',
        'userId',
        'ghcard',
        'gender',
        'network_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];




        /**
     * Boot function to generate UUID for userId before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->userId)) {
                $user->userId = (string) Str::uuid();
            }
        });
    }


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmitted()
    {
        return UserAdmission::where('user_id', $this->userId)
            ->whereNotNull('confirmed')->count() == 1;
    }

    public function admissionEmailSent()
    {
        return UserAdmission::where('user_id', $this->userId)
            ->whereNotNull('email_sent')->count() == 1;
    }

    public function detailsUpdated()
    {
        return $this->updated_at != $this->created_at;
    }

    public function isSuper()
    {
        return $this->is_super;
    }
}
