<?php
namespace App\Imports;

use App\Models\Master_Product_Prop;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;

class ImportExcel implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private const CHUNK_SIZE = 500;
    private const BATCH_SIZE = 250;
    private const SLEEP_MICROSECONDS = 50000;

    public $successCount = 0;
    public $failureCount = 0;
    private $buffer = [];

    public function collection(Collection $rows)
    {

        DB::disableQueryLog();

        // เพิ่ม memory limit
        ini_set('memory_limit', '2048M');

        foreach ($rows->chunk(self::CHUNK_SIZE) as $chunk) {
            $this->processChunk($chunk);
        }

        if (!empty($this->buffer)) {
            $this->insertBuffer();
        }
    }

    private function processChunk($chunk)
    {
        DB::beginTransaction();
        try {
            foreach ($chunk as $row) {
                if (!$this->isValidRow($row)) {
                    $this->failureCount++;
                    continue;
                }

                $this->buffer[] = $this->prepareRowData($row);
                $this->successCount++;

                if (count($this->buffer) >= self::BATCH_SIZE) {
                    $this->insertBuffer();
                }
            }

            DB::commit();
            usleep(self::SLEEP_MICROSECONDS);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Import error in chunk: " . $e->getMessage());
            throw $e;
        }
    }

    private function isValidRow($row): bool
    {
        return !empty($row['custid']) &&
               !empty($row['productid']) &&
               !empty($row['productname']);
    }

    private function prepareRowData($row): array
    {
        return [
            'CustID' => $row['custid'],
            'StatusID' => $row['statusid'] ?? '',
            'StatusDes' => $row['statusdes'] ?? null,
            'Remark' => $row['remark'] ?? null,
            'ProductID' => $row['productid'],
            'ProductName' => $row['productname'],
            'Target' => (float)($row['target'] ?? 0),
            'Add' => (float)($row['add'] ?? 0),
            'CurrentMonth' => (float)($row['currentmonth'] ?? 0),
            'Recommend' => (float)($row['recommend'] ?? 0),
            'Create_Date' => now()
        ];
    }

    private function insertBuffer(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        try {
            foreach (array_chunk($this->buffer, self::BATCH_SIZE) as $batch) {
                Master_Product_Prop::insert($batch);
            }
        } catch (\Exception $e) {
            \Log::error("Buffer insert error: " . $e->getMessage());
            throw $e;
        }

        $this->buffer = [];
    }

    public function chunkSize(): int
    {
        return self::CHUNK_SIZE;
    }

    public function batchSize(): int
    {
        return self::BATCH_SIZE;
    }
}
