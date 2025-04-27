<?php

namespace App\Console\Commands;

use App\Jobs\SendDailySalesSummaryJob;
use Illuminate\Console\Command;

class SendDailySalesSummary extends Command
{
    protected $signature = 'app:send-daily-sales-summary';
    protected $description = 'Send daily sales summary emails to sellers and admin';

    public function handle()
    {
        // Dispara o Job
        SendDailySalesSummaryJob::dispatch();

        $this->info('Daily sales summary job dispatched successfully!');
    }
}
