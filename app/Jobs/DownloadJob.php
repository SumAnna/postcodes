<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $url;
    protected string $path;

    /**
     * Job constructor.
     */
    public function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;
    }

    /**
     * Handle the request.
     *
     * @return void
     */
    public function handle(): void
    {
        $resource = fopen($this->url, 'r');

        if (!$resource) {
            Log::error(sprintf("Failed to open the source URL: %s", $this->url));

            return;
        }

        $dest = fopen(Storage::disk('local')->path($this->path), 'w');
        if (!$dest) {
            fclose($resource);
            Log::error(sprintf("Failed to open the destination path: %s", $this->path));

            return;
        }

        while (!feof($resource)) {
            $chunk = fread($resource, 1024 * 1024);
            fwrite($dest, $chunk);
        }

        fclose($dest);
        fclose($resource);

        Log::info(sprintf("File downloaded successfully to %s", $this->path));
    }
}
