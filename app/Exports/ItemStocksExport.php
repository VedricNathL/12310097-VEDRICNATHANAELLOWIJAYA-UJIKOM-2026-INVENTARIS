<?php

namespace App\Exports;

use App\Models\ItemStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemStocksExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ItemStock::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Item Name',
            'Category',
            'Total Stock',
            'Total Borrowed',
            'Total Repaired',
            'Created At',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->item_name,
            $item->category->category_name,
            $item->total_stock,
            $item->total_borrowed ?: '-', 
            $item->total_repaired ?: '-', 
            $item->created_at->format('F d, Y')?: '-',
        ];
    }
}