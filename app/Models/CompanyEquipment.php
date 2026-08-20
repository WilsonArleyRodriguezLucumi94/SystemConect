<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyEquipment extends Model
{
    protected $table = 'company_equipments';

    protected $fillable = [
        'mac_address',
        'name',
        'model',
        'mode',
        'ip_address_id',
        'status',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function ipAddress()
    {
        return $this->belongsTo(IpAddress::class, 'ip_address_id');
    }
}
