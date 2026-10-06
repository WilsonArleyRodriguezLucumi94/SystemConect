<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MikrotikService
{
    /**
     * Suspender servicio (Añadir la IP del cliente a la lista SUSPENDIDOS)
     */
    public function suspendClient(Client $client): bool
    {
        $router = $client->router;
        $ipAddress = $client->ipAddress?->ip_address;

        if (!$router || !$ipAddress) {
            Log::warning("No se pudo suspender al cliente ID {$client->id}: Falta Router o IP asignada.");
            return false;
        }

        try {
            // Se realiza la petición a la IP/Puerto mapeado en AWS Lightsail (ej: 198.51.100.1:8001)
            $response = Http::withBasicAuth($router->username, $router->password)
                ->timeout(5)
                ->post("http://{$router->ip_address}/rest/ip/firewall/address-list", [
                    'list' => 'SUSPENDIDOS',
                    'address' => $ipAddress,
                    'comment' => "SUSPENDIDO: {$client->full_name}",
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Error al suspender cliente {$client->full_name} en MikroTik: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reactivar servicio (Eliminar la IP del cliente de la lista SUSPENDIDOS)
     */
    public function reactivateClient(Client $client): bool
    {
        $router = $client->router;
        $ipAddress = $client->ipAddress?->ip_address;

        if (!$router || !$ipAddress) {
            Log::warning("No se pudo reactivar al cliente ID {$client->id}: Falta Router o IP asignada.");
            return false;
        }

        try {
            // 1. Consultar si la IP está registrada en la lista SUSPENDIDOS del router
            $findResponse = Http::withBasicAuth($router->username, $router->password)
                ->timeout(5)
                ->get("http://{$router->ip_address}/rest/ip/firewall/address-list", [
                    'list' => 'SUSPENDIDOS',
                    'address' => $ipAddress,
                ]);

            // 2. Si existe la regla, obtener su ID (.id) y eliminarla
            if ($findResponse->successful() && count($findResponse->json()) > 0) {
                $ruleId = $findResponse->json()[0]['.id'];

                Http::withBasicAuth($router->username, $router->password)
                    ->timeout(5)
                    ->delete("http://{$router->ip_address}/rest/ip/firewall/address-list/{$ruleId}");
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Error al reactivar cliente {$client->full_name} en MikroTik: " . $e->getMessage());
            return false;
        }
    }
}