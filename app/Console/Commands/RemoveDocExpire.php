<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveDocExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:removedocexpire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Document expire and remove document daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return 0;
        \Log::info("Check Document expire and remove document daily");
    }
}
