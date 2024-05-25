<?php

namespace App\Jobs;

use App\Models\Postcode;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $extractTo;

    protected string $localPath;

    /**
     * Job constructor.
     */
    public function __construct($extractTo, $localPath)
    {
        $this->extractTo = $extractTo;
        $this->localPath = $localPath;
    }

    /**
     * Handle the request.
     *
     * @return void
     */
    public function handle(): void
    {
        $files = Storage::disk('local')->files($this->localPath);
        $csvFile = $this->findCsvFile($files);

        if (!$csvFile) {
            Log::error("No CSV file found matching the pattern.");

            return;
        }

        $csvPath = Storage::disk('local')->path($csvFile);
        $batchSize = 100;
        $totalRecords = 0;

        if ($this->isFileNotEmpty($csvPath)) {
            Postcode::truncate();
            Log::info("Postcode table has been truncated.");
        } else {
            Log::info("CSV file is empty, skipping truncate and import.");

            return;
        }

        LazyCollection::make(function() use ($csvPath, &$totalRecords) {
            $fileHandle = fopen($csvPath, 'r');

            if ($fileHandle !== FALSE) {
                $headers = fgetcsv($fileHandle);

                while (($row = fgetcsv($fileHandle)) !== FALSE) {
                    $totalRecords++;
                    yield array_combine($headers, $row);
                }

                fclose($fileHandle);
            }
        })
            ->chunk($batchSize)
            ->each(function ($batch) {
                $data = [];

                foreach ($batch as $row) {
                    $data[] = [
                        'pcd' => $row['pcd'],
                        'lat' => $row['lat'],
                        'long' => $row['long'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($data)) {
                    Try {
                        Postcode::upsert($data, ['pcd'], ['lat', 'long', 'updated_at']);
                    } catch(Exception $e) {
                        Log::error(sprintf('Error occurred when inserting postcodes into DB: %s', $e->getMessage()));

                        return;
                    }

                }
            });

        Log::info(sprintf("Total records inserted: %d", $totalRecords));
    }

    private function findCsvFile($files)
    {
        foreach ($files as $file) {
            if (preg_match("/ONSPD_\\w+_UK\\.csv/", $file)) {
                Log::info("CSV file found: " . $file);
                return $file;
            }
        }

        return null;
    }

    private function isFileNotEmpty($filePath): bool
    {
        $fileHandle = fopen($filePath, 'r');
        $notEmpty = (fgets($fileHandle) !== false);
        fclose($fileHandle);
        return $notEmpty;
    }
}
