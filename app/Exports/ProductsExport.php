<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   
    public function headings(): array
    {
        return [
            'name',
            'category_name',
            'description',
            'price',
            'quantity'
        ];
    }
}
