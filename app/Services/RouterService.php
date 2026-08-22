<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RouterService
{
    protected string $url;
    protected string $user;
    protected string $pass;

    public function __construct()
    {
        $host       = env('MIKROTIK_HOST', '10.100.100.2');
        $this->url  = "http://{$host}/rest";
        $this->user = (string) env('MIKROTIK_USER', 'admin');
        $this->pass = (string) env('MIKROTIK_PASS', 'g3st10n21cauc4');
    }

    /**
     * Agrega una IP a la lista de SUSPENDIDOS (Corte)
     */
    public function suspenderIp(string $ip): void
    {
        Http::withBasicAuth($this->user, $this->pass)
            ->put("{$this->url}/ip/firewall/address-list", [
                'list'    => 'SUSPENDIDOS',
                'address' => $ip,
                'comment' => 'Corte Automático por Mora',
            ]);
    }

    /**
     * Elimina una IP de la lista de SUSPENDIDOS (Reconexión)
     */
    public function activarIp(string $ip): void
    {
        $response = Http::withBasicAuth($this->user, $this->pass)
            ->get("{$this->url}/ip/firewall/address-list", [
                'address' => $ip,
                'list'    => 'SUSPENDIDOS',
            ]);

        $items = $response->json();

        if (!empty($items)) {
            $id = $items[0]['.id'];
            Http::withBasicAuth($this->user, $this->pass)
                ->delete("{$this->url}/ip/firewall/address-list/{$id}");
        }
    }
}