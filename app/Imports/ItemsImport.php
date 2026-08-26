<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Branchstock;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ItemsImport implements ToCollection, WithHeadingRow
{
    /** @var int[] */
    public array $importedIds = [];

    /** @var array<int, array{row: int, reason: string}> */
    public array $skipped = [];

    public function collection(Collection $rows): void
    {
        $defaultBranch = Branch::whereNull('parent_id')->first()
            ?? Branch::create(['name' => 'Head Office']);

        foreach ($rows as $index => $row) {
            // +1 because $index is 0-based, +1 again because row 1 is the heading row.
            $rowNumber = $index + 2;

            $category = $this->resolveCategory($row['category'] ?? null);

            if (! $category) {
                $this->skipped[] = ['row' => $rowNumber, 'reason' => "Category \"{$row['category']}\" not found."];
                continue;
            }

            if (! $category->isLeaf()) {
                $this->skipped[] = ['row' => $rowNumber, 'reason' => "\"{$category->name}\" has its own subcategories — choose the lowest-level one."];
                continue;
            }

            if (empty($row['name']) || ! is_numeric($row['quantity'] ?? null)) {
                $this->skipped[] = ['row' => $rowNumber, 'reason' => 'Missing required Name or Quantity.'];
                continue;
            }

            $item = Item::create([
                'name' => $row['name'],
                'description' => $row['description'] ?? null,
                'category_id' => $category->id,
                'expiry_date' => $row['expiry_date'] ?: null,
                'unit_price' => $row['unit_price'] ?: null,
            ]);

            Branchstock::create([
                'branch_id' => $defaultBranch->id,
                'item_id' => $item->id,
                'quantity' => $row['quantity'],
                'reorder_level' => $row['reorder_level'] ?: 0,
            ]);

            $this->importedIds[] = $item->id;
        }
    }

    
    private function resolveCategory(?string $breadcrumb): ?Category
    {
        if (! $breadcrumb) {
            return null;
        }

        $parentId = null;
        $category = null;

        foreach (array_map('trim', explode('>', $breadcrumb)) as $segment) {
            $category = Category::where('name', $segment)->where('parent_id', $parentId)->first();

            if (! $category) {
                return null;
            }

            $parentId = $category->id;
        }

        return $category;
    }
}
