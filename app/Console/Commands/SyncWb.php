<?php

namespace App\Console\Commands;

use App\Jobs\SyncWb as JobsSyncWb;
use Illuminate\Console\Command;

class SyncWb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-wb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        JobsSyncWb::dispatchSync();
    }
}
