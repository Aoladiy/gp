<?php

namespace App\Exports;

use App\Models\StockMovement;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 *
 */
class StockMovementsExport implements FromCollection, WithHeadings
{

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return StockMovement::with(['partItem.part', 'stockMovementType', 'fromLocation', 'toLocation', 'user'])
            ->get()
            ->map(function ($movement) {
                return [
                    'ID' => $movement->id,
                    'Запчасть' => $movement->partItem->part->name ?? '',
                    'Серийный номер' => $movement->partItem->serial_number ?? '',
                    'Тип движения' => $movement->stockMovementType->name ?? '',
                    'Из локации' => $movement->fromLocation->name ?? '',
                    'В локацию' => $movement->toLocation->name ?? '',
                    'Дата' => $movement->moved_at,
                    'Ответственный' => $movement->user->name ?? '',
                    'Примечание' => $movement->note ?? '',
                ];
            });
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return ['ID', 'Запчасть', 'Серийный номер', 'Тип движения', 'Из локации', 'В локацию', 'Дата', 'Ответственный', 'Примечание'];
    }
}
