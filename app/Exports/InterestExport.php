<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InterestExport implements FromCollection, WithHeadings
{
    public $interests;

    public function __construct($interests)
    {
        $this->interests = $interests;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->interests);
    }

    public function headings(): array
    {
        return [
            '年份',
            '本金',
            '利息',
            '本息小计',
        ];
    }
}
