<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportExcel;
use Illuminate\Support\Facades\Cache;

class ProcessExcelImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    public $timeout = 21600;
    public $tries = 3;
    private $jobId;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->jobId = uniqid('import_');
        $this->queue = 'default';
    }

    public function handle()
    {
        try {
            \Log::info('Starting import job...', [
                'file' => $this->filePath,
                'job_id' => $this->jobId
            ]);

            if (!file_exists($this->filePath)) {
                throw new \Exception("File not found: {$this->filePath}");
            }

            ini_set('memory_limit', '4096M');
            \DB::disableQueryLog();
            gc_enable();

            Cache::put("import_status_{$this->jobId}", 'processing', 21600);
            Cache::put("import_progress_{$this->jobId}", 0, 21600);

            $import = new ImportExcel();
            Excel::import($import, $this->filePath);

            $now = now()->format('Y-m-d H:i:s');
            Cache::put("import_status_{$this->jobId}", 'completed', 21600);
            Cache::put("import_result_{$this->jobId}", [
                'total_rows' => '',
                'total_success' => $import->successCount,
                'total_fail' => $import->failureCount,
                'imported_at' => $now,
                'status' => 'completed'
            ], 21600);

            \Log::info('Import completed', [
                'job_id' => $this->jobId,
                'total_rows' => '',
                'success' => $import->successCount,
                'failures' => $import->failureCount,
                'imported_at' => $now
            ]);

        } catch (\Exception $e) {
            Cache::put("import_status_{$this->jobId}", 'failed', 21600);
            Cache::put("import_error_{$this->jobId}", $e->getMessage(), 21600);
            \Log::error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
