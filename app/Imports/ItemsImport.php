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

    public bool $dryRun = false;
    /** @var array<int, array{row:int, name: string, status:string,reason: ?string}> */
    public array $preview = [];

    public function collection(Collection $rows): void
    {
        $defaultBranch = Branch::whereNull('parent_id')->first()
            ?? Branch::create(['name' => 'Head Office']);

        foreach ($rows as $index => $row) {
            // +1 because $index is 0-based, +1 again because row 1 is the heading row.
            $rowNumber = $index + 2;
            $name = $row['name'] ?? '';

            $category = $this->resolveCategory($row['category'] ?? null);

            if (! $category) {
                    $this->fail($rowNumber, $name, 'Category "' . ($row['category'] ?? '') . '" not found.');
                continue;
            }

            if (! $category->isLeaf()) {
                $this->fail($rowNumber, $name, "\"{$category->name}\" has its own subcategories - choose the lowest-level one.");
                continue;
            }

            if (empty($row['name']) || ! is_numeric($row['quantity'] ?? null)) {
                $this->fail($rowNumber, $name, 'Missing required Name or Quantity.');
                continue;
            }

            if (mb_strlen($row['name']) > 255) {
                $this->fail($rowNumber,$name, 'Name is too long (max 255 characters).');
                continue;
            }

            if ((float) $row['quantity'] < 0){
                $this->fail($rowNumber,$name, 'quantity cannot be negative');
                continue;
            }

            if (! empty($row['unit_price']) && ! is_numeric($row['unit_price'])) {
                $this->fail($rowNUmber,$name, "Unit Price \"{$row['unit_price']}\" is not a number");
                continue;
            }// refuses characters 

            if(! empty($row['unit_price']) && (float) $row['unit_price'] < 0) {
                $this->fail($rowNUmber,$name, 'unit price cannot be a negative');
                continue;
            } //refuses any negative numbers 

            if (! empty($row['reorder_level']) && ! is_numeric($row['reorder_level'])){
                $this->fail($rowNumber, $name, "Reorder Level \"{$row['reorder_level']}\" is not a number.");
                continue;
            }
            
            if (! empty($row['expiry_date']) && strtotime($row['expiry_date']) === false){
                $this->fail($rowNumber,$name, "Expiry Date \"{$row['expiry_date']}\" is not a real date.");
                continue;
            }

            $duplicate = Item::where('name',$row['name'])->where('category_id',$category->id)->exists();

            if ($duplicate){
                $this->fail($rowNumber,$name, "\"{$row['name']}\" already exists in \"category->name}\"-skipped to avoid a duplicate.");
                continue;
            }// this is for duplication

            if (! $this->dryRun){
                $item = Item::create([
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                    'category_id' => $category->id,
                    'expiry_date' =>$row['expiry_date'] ?:null,
                    'unit_price' =>$row['unit_price'] ?: null,
                ]);

                Branchstock::create([
                    'branch_id' => $defaultBranch->id,
                    'item_id' => $item->id,
                    'quantity' => $row['quantity'],
                    'reorder_level' => $row['reorder_level'] ?: 0,
                ]);

                $this->importedIds[] = $item->id;

            }
            $this ->preview[] = ['row' => $rowNumber,'name' =>$name, 'status' => 'will import', 'reason' => null];
            // writes what row has a problem 
        }
    }

    private function fail(int $rowNumber, string $name, string $reason): void
    {
        $this->skipped[] = ['row' => $rowNumber, 'reason' => $reason];
        $this->preview[] = ['row' => $rowNumber, 'name' => $name, 'status' => 'problem', 'reason' => $reason];
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'category',
            'expiry_date',
            'unit_price',
            'quantity',
            'reorder_level',
        ];
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
