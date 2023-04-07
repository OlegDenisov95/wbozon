<?php

namespace App\Console\Commands;

use App\Jobs\SyncWb as JobsSyncWb;
use DateTime;
use Illuminate\Console\Command;

class SyncWb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-wb {from?} {to?}';

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
        $from = $this->argument('from');
        $to = $this->argument('to');

        $from = empty($from) ? new DateTime('-1 day') : new DateTime($from);
        $to = empty($to) ? new DateTime() : new DateTime($to);

        JobsSyncWb::dispatchSync($from, $to);
    }

}
