<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $clients = Client::where('status', 'active')->get();

        foreach ($clients as $client) {
            Payment::create([
                'client_id' => $client->id,
                'amount'    => $client->plan->price,
                'status'    => 'pending',
                'paid_at'   => null,
            ]);
        }
}
