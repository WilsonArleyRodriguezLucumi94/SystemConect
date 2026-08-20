<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IpAddress;
use Carbon\Carbon;

class IpAddressSeeder extends Seeder
{
    public function run(): void
    {
        $ips = [];
        $now = Carbon::now();

        // 1. Red de Caloto (10.0.0.0/24) -> 10.0.0.1 a 10.0.0.254
        for ($i = 1; $i <= 254; $i++) {
            $ips[] = [
                'ip_address' => "10.0.0.$i",
                'zone'       => 'Red de Caloto',
                'vlan'       => '1',
                'status'     => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 2. Red Caucadesa (10.0.5.0/26) -> 10.0.5.1 a 10.0.5.62
        for ($i = 1; $i <= 62; $i++) {
            $ips[] = [
                'ip_address' => "10.0.5.$i",
                'zone'       => 'Red Caucadesa',
                'vlan'       => '1',
                'status'     => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 3. Red Caicedo (10.0.10.0/26) -> 10.0.10.1 a 10.0.10.62
        for ($i = 1; $i <= 62; $i++) {
            $ips[] = [
                'ip_address' => "10.0.10.$i",
                'zone'       => 'Red Caicedo',
                'vlan'       => '1',
                'status'     => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insertar los 378 registros de forma masiva y eficiente
        IpAddress::insert($ips);
    }
}
