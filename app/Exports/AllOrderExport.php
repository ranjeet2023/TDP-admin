<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllOrderExport implements FromCollection, WithColumnWidths, WithColumnFormatting, WithHeadings, WithMapping, WithStyles
{
    public function __construct($data)
    {
        $this->data = $data;
        $this->count = count($data);
    }

    public function collection()
    {
        return $this->data;
    }

    public function columnWidths(): array
    {
        return [
            'C' => 55,
            'B' => 45,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D2:D'.$this->count+1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
    }

    public function headings(): array
    {
        return [
            [
                'Status',
                'Return',
                'Date',
                'Supplier Name',
                'Supplier Status',
                'Country',
                'Shape',
                'SKU',
                'Reference Number',
                'Carat',
                'Color',
                'Clarity',
                'Cut',
                'Polish',
                'Synmentry',
                'Fluorescence',
                'Lab',
                'Certificate Number',
                'Sale Discount',
                'Sale price',
                'Buy Discount',
                'Buy price',
            ],
        ];
    }

    public function map($row): array
    {
        return [
            $row->order_status,
            ($row->return_price > 0.00) ? 'R' : '',
            $row->created_at,
            $row->orderdetail->supplier_name,
            $row->supplier_status,
            $row->orderdetail->country,
            $row->orderdetail->shape,
            $row->orderdetail->id,
            $row->ref_no,
            $row->orderdetail->carat,
            $row->orderdetail->color,
            $row->orderdetail->clarity,
            $row->orderdetail->cut,
            $row->orderdetail->polish,
            $row->orderdetail->symmetry,
            $row->orderdetail->fluorescence,
            $row->orderdetail->lab,
            $row->certificate_no,
            $row->sale_discount,
            $row->sale_price,
            $row->buy_discount,
            $row->buy_price,
        ];
    }
}
