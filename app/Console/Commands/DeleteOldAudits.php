<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldAudits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-audits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete audit records older than 45 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = \App\Models\Audit::where('created_at', '<', now()->subDays(45))->delete();

        $this->info("Deleted {$deleted} old audit records.");
    }
}
