<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $items)
    {
    }

    public function collection(): Enumerable
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Category',
            'Total Stock',
            'Expiry Date',
            'Unit Price',
        ];
    }

    /**
     * @param Item $item
     */
    public function map($item): array
    {
        return [
            $item->name,
            $item->description,
            $item->category
                ? ($item->category->parent ? $item->category->parent->name . ' > ' : '') . $item->category->name
                : 'N/A',
            $item->totalQuantity(),
            $item->expiry_date?->format('Y-m-d'),
            $item->unit_price,
        ];
    }
}
