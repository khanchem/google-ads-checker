<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\GoogleAdsController;
use Illuminate\Support\Facades\Http;
use App\Models\Urls;
use App\Models\Report;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
// Get the absolute path to the lib folder
$libPath = realpath(__DIR__ . '/../../lib');

// Require the simple_html_dom.php file from the lib folder


class URlChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
   protected $url;
   
   
    /**
     * Create a new job instance.
     *
     * @return void
     */
  
   public function __construct($url)
{
    $this->url = $url;
   
}
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $url = $this->url;
         $job =new GoogleAdsController();
        $job->process($url);
       
    }
}
