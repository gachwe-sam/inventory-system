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
    public bool $dryRun = false;
    private int $fakePreviewId = 0;
    /** @var array<init, array{row: int,path:string, status:string,reason: ?string}> */
    public array $preview = [];

        public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $segments = $this->readLevels($row, $rowNumber);

            if ($segments === null) {
                continue;
            }

            $path = implode(' > ', $segments);

            $result = $this->walkPath($segments, $rowNumber, $path);

            if ($result === null) {
                continue;
            }

            [$createdIds, $newCount] = $result;

            if (! $this->dryRun) {
                array_push($this->importedIds, ...$createdIds);
            }

            $status = $newCount > 0
                ? 'will create ' . $newCount . ' new categor' . ($newCount === 1 ? 'y' : 'ies')
                : 'already exists';

            $this->preview[] = ['row' => $rowNumber, 'path' => $path, 'status' => $status, 'reason' => null];
        }
    }


    /**
     * @param string[] $segments
     * @return int[]|null
     * @return array{0: int[],I:int}|null
     */
    private function walkPath(array $segments, int $rowNumber,string $fullPath): ?array
    {
        $parentId = null;
        $createdIds = [];
        $newCount = 0;

        foreach ($segments as $segment) {
            if (mb_strlen($segment) > 255) {
                $this->fail($rowNumber, $fullPath, "segment \"{$segment}\" exceeds 255 characters.");

                return null;
            }

            $existing = Category::where('name', $segment)->where('parent_id', $parentId)->first();

            if ($existing) {
                $parentId = $existing->id;

                continue;
            }

            if ($this->dryRun){
                $parentId = --$this->fakePreviewId;
                $newCount++;
                continue;
            }// this is just a placeholder 

            
            $trashed = Category::onlyTrashed()->where('name', $segment)->where('parent_id', $parentId)->first();

            $category = $trashed
                ? tap($trashed)->restore()
                : Category::create(['name' => $segment, 'parent_id' => $parentId]);

            $createdIds[] = $category->id;
            $parentId = $category->id;
        }

        return [$createdIds,$newCount];
    }

    private function fail(int $rowNumber, string $path, string $reason): void
    {
        $this->skipped[] = ['row' => $rowNumber, 'reason' => $reason];
        $this->preview[] = ['row' => $rowNumber, 'path' => $path, 'status' => 'problem', 'reason' => $reason];
    }

    private function readlevels(array $row, int $rowNumber): ?array
    {
        $levels=[
            trim((string) ($row['level_1'] ?? '')),
            trim((string) ($row['level_2'] ?? '')),
            trim((string) ($row['level_3'] ?? '')),
            trim((string) ($row['level_4'] ?? '')),
            trim((string) ($row['level_5'] ?? '')),
        ];

        $lastfilled = -1;
        foreach ($levels as $i => $value) {
            if ($value !== ''){
                $lastfilled = $i;
            }
        }

        if ($lastFilled === -1){
            $this->fail($rowNumber, '', 'At least 1 must be filled in.');
            return null;
        }

        for ($i = 0; $i <= $lastFilled; $i++){
            if($levels[$i] === ''){
                $this->fail($rowNumber, implode('>',array_slice($levels, 0, $lastFilled + 1)), 'Level'.($i+1).'is blank, but a later level has a value - fill in every level up to that point.');
                return null;
            }
        }

        return array_slice($levels, 0, $lastFilled + 1);
    }
    
}
