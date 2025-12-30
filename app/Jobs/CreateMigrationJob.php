<?php

namespace App\Jobs;

use App\Constants\Constant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateMigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $migrations_path = database_path('migrations');
            $files = File::files($migrations_path);
            $db_bkp_destination_path = Constant::DB_BKP . "/bkp_" . date('y_m_d_' . time());
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $filepath = $file->getPath();
                $bkp_file_url = Storage::putFileAs($db_bkp_destination_path . "/", $filepath . "/" . $filename, $filename);

                if (!empty($bkp_file_url)) {
                    File::delete($filepath . "/" . $filename);
                }
            }
            Artisan::call("mig:generate -q --ignore=migrations");
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
