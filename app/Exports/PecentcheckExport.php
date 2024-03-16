<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

Class PecentcheckExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct($data, $customer_id)
    {
        $this->data = $data;
        $this->customer_id = $customer_id;

        $customer_id = $this->customer_id;
        $customer_data  = DB::table('customers')->where('cus_id', $customer_id)->first();
        $this->customer_discount = $customer_data->discount;
		$this->customer_lab_discount = $customer_data->lab_discount;
    }

    public function collection()
    {
        return $this->data;
    }


    // This generates the headings in the spreadsheet
    public function headings(): array
    {
        return [
            [
                'SKU',
                'Shape',
                'Carat',
                'Cut',
                'Color',
                'Clarity',
                'Supplier',
                'CertificateLab',
                'CertificateID',
                'Price',
                '%'
            ],
        ];
    }


    public function map($row): array
    {
        $base_price = $row->rate + (($row->rate * ($row->aditional_discount)) / 100);
        $carat_price = $base_price + ($base_price * ($this->customer_lab_discount / 100));
        $net_price = round($carat_price * $row->carat, 2);
        $discount_main = !empty($row->raprate) ? round(($carat_price - $row->raprate) / $row->raprate * 100, 2) : 0;

        return [
            $row->id,
            $row->shape,
            $row->carat,
            $row->cut,
            $row->color,
            $row->clarity,
            $row->supplier_name,
            $row->lab,
            $row->certificate_no,
            $net_price,
            $discount_main,
        ];
    }

}

