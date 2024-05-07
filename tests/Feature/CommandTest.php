<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\DownloadJob;
use App\Jobs\UnzipJob;
use App\Jobs\ImportJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CommandTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * Testing command without options.
     */
    public function testHandlesWithoutOptions(): void
    {
        Queue::fake();

        $this->artisan('upload:postcodes')
            ->assertExitCode(0);

        Queue::assertPushed(DownloadJob::class);
        Queue::assertPushed(UnzipJob::class);
        Queue::assertPushed(ImportJob::class);
    }

    /**
     * Testing command with no-download option.
     */
    public function testHandlesNoDownloadOption(): void
    {
        Queue::fake();

        $this->artisan('upload:postcodes --no-download')
            ->assertExitCode(0);

        Queue::assertNotPushed(DownloadJob::class);
        Queue::assertPushed(UnzipJob::class);
        Queue::assertPushed(ImportJob::class);
    }

    /**
     * Testing command with no-unzip option.
     */
    public function testHandlesNoUnzipOption(): void
    {
        Queue::fake();

        $this->artisan('upload:postcodes --no-unzip')
            ->assertExitCode(0);

        Queue::assertPushed(DownloadJob::class);
        Queue::assertNotPushed(UnzipJob::class);
        Queue::assertPushed(ImportJob::class);
    }

    /**
     * Testing command with no-import option.
     */
    public function testHandlesNoImportOption(): void
    {
        Queue::fake();

        $this->artisan('upload:postcodes --no-import')
            ->assertExitCode(0);

        Queue::assertPushed(DownloadJob::class);
        Queue::assertPushed(UnzipJob::class);
        Queue::assertNotPushed(ImportJob::class);
    }

}
