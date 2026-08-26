<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CategoriesImport implements ToCollection, WithHeadingRow
{
    /** @var int[] */
    public array $importedIds = [];

    /** @var array<int, array{row: int, reason: string}> */
    public array $skipped = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            // +1 because $index is 0-based, +1 again because row 1 is the heading row.
            $rowNumber = $index + 2;
            $path = trim((string) ($row['path'] ?? ''));

            if ($path === '') {
                $this->skipped[] = ['row' => $rowNumber, 'reason' => 'Missing Path.'];
                continue;
            }

            $segments = array_values(array_filter(
                array_map('trim', explode('>', $path)),
                fn ($segment) => $segment !== ''
            ));

            if (empty($segments)) {
                $this->skipped[] = ['row' => $rowNumber, 'reason' => 'Path had no usable segments.'];
                continue;
            }

            $createdIds = $this->walkPath($segments, $rowNumber);

            if ($createdIds !== null) {
                array_push($this->importedIds, ...$createdIds);
            }
        }
    }

    /**
     * @param string[] $segments
     * @return int[]|null
     */
    private function walkPath(array $segments, int $rowNumber): ?array
    {
        $parentId = null;
        $createdIds = [];

        foreach ($segments as $segment) {
            if (mb_strlen($segment) > 255) {
                $this->skipped[] = ['row' => $rowNumber, 'reason' => "Segment \"{$segment}\" exceeds 255 characters."];

                return null;
            }

            $existing = Category::where('name', $segment)->where('parent_id', $parentId)->first();

            if ($existing) {
                $parentId = $existing->id;

                continue;
            }

            
            $trashed = Category::onlyTrashed()->where('name', $segment)->where('parent_id', $parentId)->first();

            $category = $trashed
                ? tap($trashed)->restore()
                : Category::create(['name' => $segment, 'parent_id' => $parentId]);

            $createdIds[] = $category->id;
            $parentId = $category->id;
        }

        return $createdIds;
    }
}
