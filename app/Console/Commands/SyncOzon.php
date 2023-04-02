<?php

namespace App\Console\Commands;

use App\Jobs\SyncOzon as JobsSyncOzon;
use Illuminate\Console\Command;

class SyncOzon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-ozon';

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
        JobsSyncOzon::dispatchSync();
    }
}
