<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Services\RouterService;

class CheckMoraClients extends Command
{
    protected $signature = 'isp:corte-mora';
    protected $description = 'Suspende en MikroTik los clientes con pagos vencidos';

    public function handle(RouterService $router)
    {
        // Buscar clientes activos con facturas no pagadas y con fecha límite vencida
        $clients = Client::where('status', 'active')
            ->whereHas('payments', function ($query) {
                $query->where('status', 'pending')
                      ->where('due_date', '<', now()->toDateString());
            })->get();

        foreach ($clients as $client) {
            $client->update(['status' => 'suspended']);

            if ($client->ipAddress) {
                $router->suspenderIp($client->ipAddress->ip_address);
            }
        }

        $this->info("Corte completado. Clientes suspendidos: {$clients->count()}");
    }
}