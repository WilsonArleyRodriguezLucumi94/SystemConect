<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Services\RouterService;
use Illuminate\Support\Facades\Log;

class CheckMoraClients extends Command
{
    protected $signature = 'isp:corte-mora';
    protected $description = 'Suspende el servicio a clientes con pagos vencidos';

    public function handle(RouterService $routerService)
    {
        // Usamos whereDate para comparar la fecha ignorando la hora
        $clients = Client::with(['ipAddress', 'router'])
            ->where('status', 'active')
            ->whereHas('payments', function ($query) {
                $query->where('status', 'pending')
                      ->whereDate('due_date', '<', now());
            })->get();

        if ($clients->isEmpty()) {
            $this->info("No se encontraron clientes activos con pagos vencidos.");
            return Command::SUCCESS;
        }

        foreach ($clients as $client) {
            if (!$client->router || !$client->ipAddress) {
                $this->warn("El cliente '{$client->full_name}' no tiene Router o IP asignada. Omitiendo...");
                continue;
            }

            // Cambiar estado en BD
            $client->update(['status' => 'suspended']);

            try {
                // Ejecutar corte en MikroTik
                $routerService->suspenderIp($client->ipAddress->ip_address, $client->router);
                $this->info("✓ Corte aplicado a '{$client->full_name}' ({$client->ipAddress->ip_address}) en '{$client->router->name}'");
            } catch (\Exception $e) {
                Log::error("Error cortando IP {$client->ipAddress->ip_address} en {$client->router->name}: " . $e->getMessage());
                $this->error("X Falló la comunicación con MikroTik para '{$client->full_name}'");
            }
        }

        return Command::SUCCESS;
    }
}