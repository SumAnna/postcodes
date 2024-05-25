<?php

namespace App\Console\Commands;

use App\Jobs\DownloadJob;
use App\Jobs\ImportJob;
use App\Jobs\UnzipJob;
use Illuminate\Console\Command;

class UploadPostcodes extends Command
{
    protected $signature = 'upload:postcodes {--no-download} {--no-unzip} {--no-import}';
    protected $description = 'Command to download a file';

    /**
     * Handle the request.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $url = 'https://parlvid.mysociety.org/os/ONSPD/2022-11.zip';
        $extractPath = 'postcodes';
        $path = 'postcodes.zip';
        $localPath = 'postcodes/Data';

        $this->info("Dispatching job to download file from: $url");

        if (!$this->option('no-download')) {
            DownloadJob::dispatch($url, $path)->onQueue('high');
        }

        if (!$this->option('no-unzip')) {
            UnzipJob::dispatch($path, $extractPath)->onQueue('default')->afterCommit();
        }

        if (!$this->option('no-import')) {
            ImportJob::dispatch($extractPath, $localPath)->onQueue('low')->afterCommit();
        }
    }
}
