<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;

class CheckDailyInvoices extends Command
{
    /**
     * El nombre con el que ejecutas el comando en Artisan.
     */
    protected $signature = 'app:check-daily-invoices';

    /**
     * Descripción de la tarea.
     */
    protected $description = 'Genera facturas pendientes para clientes con vencimiento hoy y actualiza su próxima fecha.';

    /**
     * Lógica de ejecución de la tarea.
     */
    public function handle()
    {
        // 1. Busca clientes activos con fecha de cobro para HOY
        $clientsToBill = Client::where('status', 'active')
            ->whereDate('next_due_date', Carbon::today())
            ->get();

        if ($clientsToBill->isEmpty()) {
            $this->info('No hay clientes pendientes de cobro para el día de hoy.');
            return 0;
        }

        $count = 0;

        foreach ($clientsToBill as $client) {
            // 2. Crea el cobro pendiente en la tabla payments
            Payment::create([
                'client_id' => $client->id,
                'amount'    => $client->plan->price,
                'status'    => 'pending',
                'due_date'  => $client->next_due_date,
                'paid_at'   => null,
            ]);

            // 3. Proyecta el cobro para el siguiente mes exacto (+1 mes)
            $client->update([
                'next_due_date' => Carbon::parse($client->next_due_date)->addMonth(),
            ]);

            $count++;
        }

        $this->info("Proceso completado: se generaron {$count} cobros pendientes.");
        return 0;
    }
}