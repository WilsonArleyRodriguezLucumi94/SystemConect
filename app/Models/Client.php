<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['document_number', 'full_name', 'phone', 'address', 'ip_address_id', 'plan_id', 'billing_day', 'status'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function ip()
    {
        return $this->belongsTo(IpAddress::class, 'ip_address_id');
    }
}
