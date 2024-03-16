<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;

class EdiDeclaration implements FromCollection, WithColumnWidths, WithColumnFormatting, WithHeadings, WithMapping, WithStyles, WithTitle

{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($data,$exp_no,$sheetname,$extra,$customer_id,$consignment)
    {
        $this->data = $data;
        $this->exp_no = $exp_no;
        $this->index = 1;
        $this->count = count($data);
        $this->sheetname = $sheetname;
        $this->extra = $extra;
        $this->customer_id = $customer_id;
        $this->consignment = $consignment;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function title(): string
    {
        return $this->sheetname;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'D' => 85,
            'G' => 15,
            'I' => 15,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'X' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('D2:D'.$this->count+1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
        $sheet->getStyle('G2:G'.$this->count+1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
        $sheet->getStyle('I2:I'.$this->count+1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
        $sheet->getStyle('U2:U'.$this->count+1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
        $sheet->getStyle('V2:V'.$this->count+1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
        $sheet->getStyle('W2:W'.$this->count+1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFFF00'],]);
        $sheet->getStyle('AB1:AC1')->applyFromArray(['font' => ['size' => 12 , 'color' => ['rgb' =>'FF0000']]]);

        $rightBorder=array(
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        );
        $sheet->getStyle("A1:AC".$this->count+1)->applyFromArray($rightBorder);
    }

    public function headings(): array
    {
        return [
            [
                'Invoice No',
                'Item No.',
                'RITC',
                'Item Description',
                'Qty',
                'Unit of Qty',
                'Unit Price',
                'Per',
                'PMV Unit Price',
                'Scheme Code',
                'Drawback Sr. No.',
                'DBK Qty No.',
                'DBK Qty Unit.',
                'Reward Item',
                'STR Code',
                'End User',
                'IGST Payment Status',
                'Taxable Value',
                'IGST Amount',
                'IGST Rate',
                'Compensation Cess Amount',
                'State Code',
                'District Code',
                'Standard Qty',
                'Standard Qty Unit',
                'FTA Code',
                'Accessory Status',
                'RODTEP',
                'STATEMENT CODE'
            ],
        ];
    }

    public function map($row): array
    {
        $diamond_type = $row['diamond_type'];
        if($diamond_type == "L"){
            $hsn = '71049100';

            if($row['lab'] == "IGI")
            {
                $certificate = str_replace("LG", '', $row['certificate_no']);
                $certificate = "LG".$certificate;
            }
            else
            {
                $certificate = $row['certificate_no'];
            }
        }
        else{
            $certificate = $row['certificate_no'];
            $hsn = '71023910';
        }

        if($this->consignment == 0)
        {
            $rodtep = 'RODTEPY';
        }
        else
        {
            $rodtep = 'RODTEPN';
        }

        $percarat = round($row['orders']['sale_rate'],2);

        if($this->consignment == 0 && ($this->customer_id == \Cons::ASSOCIATE_HK_ID || $this->customer_id == \Cons::ASSOCIATE_USA_ID || $customer->cus_id == \Cons::ASSOCIATE_AUS_ID)){
            $sale_price = round(($percarat * $row['carat']) - 15, 2);
        }
        else{
            $sale_price = round(($percarat * $row['carat']), 2);
        }
        $carat_per = round($sale_price/$row['carat'], 2);

        $desc = $row['lab'].' '.$certificate.' '.$row['shape'].' '.$row['length'].' x '.$row['width'].' x '.$row['depth'].' 1 PC '.$row['color'].' '.$row['clarity'].$this->extra;
        return [
            $this->exp_no,
            $this->index++,
            $hsn,
            $desc,
            $row['carat'],
            'CTM',
            $carat_per,
            '1',
            $sale_price,
            '00',
            '',
            '0.000',
            '',
            '',
            '',
            'GNX100',
            'LUT',
            '0.00',
            '0.00',
            '',
            '',
            '24',
            '459',
            $row['carat'],
            'CTM',
            'NCPTI',
            '0',
            $rodtep,
            '',
        ];
    }

}
