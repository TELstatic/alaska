<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LoanExport implements FromCollection, WithHeadings
{
    public $loans;

    public function __construct($loans)
    {
        $this->loans = $loans;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->loans);
    }

    public function headings(): array
    {
        return [
            '还款期数',
            '还款本金',
            '还款利息',
            '本息小计',
            '还款时间',
        ];
    }

    public function columnFormats()
    {
        return [
            'B' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'C' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }
}
