<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

Class NaturalExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct($data, $customer_id)
    {
        $this->data = $data;
        $this->customer_id = $customer_id;
        $customer_id = $this->customer_id;

        if(!empty($customer_id))
        {
            $customer_data  = DB::table('customers')->where('cus_id', $customer_id)->first();
            $this->customer_discount = $customer_data->discount;
            $this->customer_lab_discount = $customer_data->lab_discount;
        }
        else
        {
            $this->customer_discount = 0;
            $this->customer_lab_discount = 0;
        }
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
                'Color',
                'Clarity',
                'Cut',
                'Polish',
                'Symmetry',
                'Fluorescence',
                'Lab',
                'Certificate No',
                'Certificate URL',
                'Price',
                'Price Per Carat',
                'depth Percentage',
                'table Percentage',
                'length',
                'width',
                'depth',
                'image',
                'video',
                'CrownAngle',
                'CrownHeight',
                'PavilionAngle',
                'PavilionDepth',
                'eyeclean'
            ],
        ];
    }


    public function map($row): array
    {
        $base_price = $row->rate + (($row->rate * ($row->aditional_discount)) / 100);
        $carat_price = $base_price + ($base_price * ($this->customer_discount / 100));
        $net_price = round($carat_price * $row->carat, 2);

        $certificate_url = '';
        if($row->lab == 'IGI')
        {
            $certificate_url = 'https://www.igi.org/viewpdf.php?r='.$row->certificate_no;
        }
        elseif($row->lab == 'IGI')
        {
            $certificate_url = 'https://www.gia.edu/report-check?reportno='.$row->certificate_no;
        }

        return [
            $row->id,
            $row->shape,
            $row->carat,
            $row->color,
            $row->clarity,
            $row->cut,
            $row->polish,
            $row->symmetry,
            $row->fluorescence,
            $row->lab,
            $row->certificate_no,
            $certificate_url,
            $net_price,
            $carat_price,
            $row->depth_per,
            $row->table_per,
            $row->length,
            $row->width,
            $row->depth,
            $row->image,
            $row->video,
            $row->crown_angle,
            $row->crown_height,
            $row->pavilion_angle,
            $row->pavilion_depth,
            $row->eyeclean,
        ];
    }

}

