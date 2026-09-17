<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
Use PhpOffice\PhpSpreadsheet\Style\Fill;

class CategoriesImportTemplateExport implements FromArray,WithHeadings, WithColumnWidths, WithEvents
{
    public function array(): array
    {
        return [
            ['SUGAR','PACKED','50KG','',''],

        ];

    }
    public function headings(): array
    {
        return ['level 1','level 2','level 3','level 4', 'level 5'];
        
    }

    public function columnwidths(): array
    {
        return ['A' => 20, 'B' => 20, 'C' => 20, 'D' => '20', 'E' => '20'];
    }

    public function registerevents():array
    {
        return[
            AfterSheet::class=>function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();
                

                $sheet->getstyle('A1:E1')->getfont()->setBold(true);
                $sheet->getstyle('A1:E1')->getfill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('DCE6F1');

            
                
            }
     
     ];
    }

    
}