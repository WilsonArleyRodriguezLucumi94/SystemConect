<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'username',
        'password',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}