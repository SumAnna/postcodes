<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class UnzipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;
    protected string $extractTo;

    /**
     * Job constructor.
     */
    public function __construct($path, $extractTo)
    {
        $this->path = $path;
        $this->extractTo = $extractTo;
    }

    /**
     * Handle the request.
     *
     * @return void
     */
    public function handle(): void
    {
        $zip = new ZipArchive;
        $filePath = Storage::disk('local')->path($this->path);
        $extractPath = Storage::disk('local')->path($this->extractTo);

        if ($zip->open($filePath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            Log::info(sprintf("%s extracted to %s", $filePath, $extractPath));
        } else {
            Log::error(sprintf("Failed to unzip %s", $filePath));
        }
    }
}
