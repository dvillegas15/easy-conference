<?php

namespace App\Models\Core;


use App\Traits\CapableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'notificationChannels'
    ];

    // public function people()
    // {
    //     return $this->hasMany('App\Models\Core\Person');
    // }

    // public function getPersonAttribute()
    // {
    //     return $this->people()->first();
    // }

}
