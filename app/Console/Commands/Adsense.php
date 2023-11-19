<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GoogleAdsController;
class Adsense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:adsense';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // $job =new GoogleAdsController();
        // $job->checkAdSenseTags();
         $this->info('command executed successfully!');
       return Command::SUCCESS;
    }
}
