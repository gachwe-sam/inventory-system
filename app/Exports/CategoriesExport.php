<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $categories)
    {
    }

    public function collection(): Enumerable
    {
        return $this->categories;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Parent Category',
            'Items Count',
        ];
    }

    /**
     * @param Category $category
     */
    public function map($category): array
    {
        return [
            $category->name,
            $category->parent?->name ?? '',
            $category->items()->count(),
        ];
    }
}
