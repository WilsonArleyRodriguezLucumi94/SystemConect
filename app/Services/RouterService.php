<?php

namespace App\Services;

use App\Models\Router;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RouterService
{
    /**
     * Agrega una IP a la lista de SUSPENDIDOS en un MikroTik específico
     */
    public function suspenderIp(string $ip, Router $router): void
    {
        $url = "http://{$router->ip_address}/rest/ip/firewall/address-list";

        $response = Http::timeout(10)
            ->withBasicAuth($router->username, $router->password)
            ->put($url, [
                'list'    => 'SUSPENDIDOS',
                'address' => $ip,
                'comment' => 'Corte Automático por Mora',
            ]);

        if ($response->failed()) {
            Log::error("Error al suspender la IP {$ip} en el router '{$router->name}': " . $response->body());
        }
    }

    /**
     * Elimina una IP de la lista de SUSPENDIDOS en un MikroTik específico
     */
    public function activarIp(string $ip, Router $router): void
    {
        $baseUrl = "http://{$router->ip_address}/rest/ip/firewall/address-list";

        // Buscar el ID del registro de la IP en la lista de SUSPENDIDOS
        $response = Http::timeout(10)
            ->withBasicAuth($router->username, $router->password)
            ->get($baseUrl, [
                'address' => $ip,
                'list'    => 'SUSPENDIDOS',
            ]);

        if ($response->successful()) {
            $items = $response->json();

            if (!empty($items)) {
                foreach ($items as $item) {
                    $id = $item['.id'];
                    Http::timeout(10)
                        ->withBasicAuth($router->username, $router->password)
                        ->delete("{$baseUrl}/{$id}");
                }
            }
        } else {
            Log::error("Error al buscar la IP {$ip} para reconexión en '{$router->name}': " . $response->body());
        }
    }
}