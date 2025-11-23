<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Collector extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name','phone','area','username','password','status'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function parties()
    {
        return $this->hasMany(Party::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
