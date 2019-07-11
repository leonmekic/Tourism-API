<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable, HasApiTokens, SoftDeletes;

    const SuperAdminId = 3;
    const UserRoleId = 2;

    public function scopeInAppUsers($query)
    {
        $user = auth()->user();
        if ($user->id == User::SuperAdminId) {
            return $query;
        }

        return $query->where('app_id', $user->app_id)->where('id','!=', User::SuperAdminId);
    }

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "phone_number",
        'active',
        'activation_token',
        'terms_and_conditions',
        'app_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
}
