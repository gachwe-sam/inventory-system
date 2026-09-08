<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ItemsImportTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithEvents
{
    public function array(): array
    {
        return [
            ['CAN Fertilizer 25kg', 'Nitrogen fertilizer', 'Fertilizer > CAN > 25 KG BAG', '2027-01-01', 'xxx', 'xxx', 'xxx'],
        ];
    }

    // Same order ItemsImport::headings() expects — WithHeadingRow slugifies
    // these into the array keys ItemsImport::collection() reads.
    public function headings(): array
    {
        return ['Name', 'Description', 'Category', 'Expiry Date', 'Unit Price', 'Quantity', 'Reorder Level'];
    }

    public function columnWidths(): array
    {
        return ['A' => 24, 'B' => 30, 'C' => 34, 'D' => 14, 'E' => 12, 'F' => 10, 'G' => 14];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $sheet->getParent();

                $sheet->getStyle('A1:G1')->getFont()->setBold(true);
                $sheet->getStyle('A1:G1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('DCE6F1');

                $breadcrumbs = $this->leafBreadcrumbs();

                $listSheet = $spreadsheet->createSheet();
                $listSheet->setTitle('Categories (reference)');
                foreach ($breadcrumbs as $i => $breadcrumb) {
                    $listSheet->setCellValue('A' . ($i + 1), $breadcrumb);
                }
                $listSheet->getColumnDimension('A')->setWidth(40);
                $spreadsheet->setActiveSheetIndex(0);

                $lastRow = max(count($breadcrumbs), 1);
                $range = "'Categories (reference)'!\$A\$1:\$A\${$lastRow}";

                for ($row = 2; $row <= 200; $row++) {
                    $validation = $sheet->getCell("C{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_WARNING);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Unknown category');
                    $validation->setError('Pick from the dropdown, or check the "Categories (reference)" sheet for exact spelling.');
                    $validation->setFormula1($range);
                }
            },
        ];
    }

    // Only leaves — matches the isLeaf() rule ItemsImport enforces on upload.
    private function leafBreadcrumbs(): array
    {
        $breadcrumbs = [];

        $walk = function ($categories, string $prefix = '') use (&$walk, &$breadcrumbs) {
            foreach ($categories as $category) {
                $path = $prefix ? $prefix . ' > ' . $category->name : $category->name;

                if ($category->childrenRecursive->isEmpty()) {
                    $breadcrumbs[] = $path;
                } else {
                    $walk($category->childrenRecursive, $path);
                }
            }
        };

        $walk(Category::tree());

        return $breadcrumbs;
    }
}
