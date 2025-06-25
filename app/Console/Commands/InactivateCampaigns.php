<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use Carbon\Carbon;

class InactivateCampaigns extends Command
{
    protected $signature = 'campaigns:inactivate';

    protected $description = 'Inativa campanhas que não sejam repick';

    public function handle()
    {
        $yesterday = Carbon::yesterday();

        $campaigns = Campaign::whereDate('end_date', $yesterday)
            ->where('status', true)
            ->where('repick', false)
            ->get();

        foreach ($campaigns as $campaign) {
            $campaign->update(['status' => false]);
            $this->info("Campanha {$campaign->name} foi inativada.");
        }

        $this->info('Verificação concluída.');
    }
}
