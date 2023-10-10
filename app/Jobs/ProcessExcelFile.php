<?php

namespace App\Jobs;

use App\Models\Row;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessExcelFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $filePath;
    private string $key;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, string $key)
    {
        $this->filePath = $filePath;
        $this->key = $key;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $spreadsheet = IOFactory::load($this->filePath);
        File::delete($this->filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $maxRow = $worksheet->getHighestDataRow();

        Cache::put($this->key, 0, 3600);

        for ($startRow = 2; $startRow < $maxRow; $startRow += 1001) {
            $endRow = min($startRow + 1000, $maxRow);

            $toInsert = $worksheet->rangeToArray(
                "A$startRow:C$endRow",
                null,
                true,
                true,
                true
            );
            foreach ($toInsert as $index => $value) {
                if (empty($value['B'])) {
                    unset($toInsert[$index]);
                    continue;
                }

                $dateParts = explode('.', $value['C']);

                $toInsert[$index] = [
                    'id' => $value['A'],
                    'name' => $value['B'],
                    'date' => Carbon::create("20$dateParts[2]", $dateParts[1], $dateParts[0]),
                ];
            }
            Row::query()->insert($toInsert);

            if (count($toInsert) < $endRow - $startRow) {
                Cache::put($this->key, $startRow + count($toInsert) - 1, 3600);
                break;
            }

            Cache::put($this->key, $endRow - 1, 3600);
        }
    }
}
