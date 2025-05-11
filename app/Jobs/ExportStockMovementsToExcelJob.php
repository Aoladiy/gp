<?php

namespace App\Jobs;

use App\Exports\StockMovementsExport;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportStockMovementsToExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filename = "exports/stock_movements_{$this->userId}_" . now()->format('Ymd_His') . ".xlsx";

        Excel::store(new StockMovementsExport, $filename, 'public');

        Report::query()
            ->create([
                'user_id' => $this->userId,
                'path' => $filename,
                'type' => 'stock_movement',
            ]);
    }
}
