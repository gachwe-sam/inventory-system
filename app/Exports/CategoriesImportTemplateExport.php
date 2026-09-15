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
            ['SUGAR > PACKED > 2KG'],

        ];

    }
    public function headings(): array
    {
        return ['Path'];
        
    }

    public function columnwidths(): array
    {
        return ['A' => 50];
    }

    public function registerevents():array
    {
        return[
            AfterSheet::class=>function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();
                

                $sheet->getstyle('A1')->getfont()->setBold(true);
                $sheet->getstyle('A1')->getfill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('DCE6F1');

            
                
            }
     
     ];
    }

    
}