<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    protected $fillable = ['ip_address', 'zone', 'vlan', 'status'];

    public function client()
    {
        // Una IP pertenece a un solo cliente a la vez
        return $this->hasOne(Client::class); 
    }
}
