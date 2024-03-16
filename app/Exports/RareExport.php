<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

Class RareExport implements FromCollection, WithHeadings, WithMapping
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
                'ItemId',
                'ImagesURLValue',
                'VideosURLValue',
                'CertificateLab',
                'CertificateURL',
                'CertificateID',
                'B2BPrice',
                'Shape',
                'Carat',	'Cut',	'Color',	'Clarity',	'Polish',	'Symmetry',	'Fluorescence',	'TableWidthPercentage',
                'Girdle',	'CrownAngle',	'CrownHeightPercentage',	'PavilionAngle',
                'PavilionDepthPercentage',	'DepthPercentage',	'LengthToWidthRatio',	'Measurements',
                'StarLengthPercentage',	'LowerHalfLengthPercentage',	'Luster',	'ColorShade',	'EyeClean',	'LabGrown',	'Availability',
                'ShippingDays',	'Country',	'State',	'City',	'SupplierName',
            ],
        ];
    }

    public function map($row): array
    {


        if($row->diamond_type == 'W')
        {
            if($row->net_dollar > 20000)
            {
                $carat_price = $row->orignal_rate;
                $o_price = $carat_price * $row->carat;
                $net_price = round($o_price + 350, 2);
            }
            elseif($row->net_dollar > 10000)
            {
                $carat_price = $row->orignal_rate;
                $o_price = $carat_price * $row->carat;
                $net_price = round($o_price + 200, 2);
            }
            else
            {
                $carat_price = $row->rate + (($row->rate * ($this->customer_lab_discount)) / 100);
                $net_price = round($carat_price * $row->carat, 2);
            }
            $type = "No";
        }
        else
        {
            if($row->net_dollar > 6500)
            {
                $carat_price = $row->orignal_rate;
                $o_price = $carat_price * $row->carat;
                $net_price = round($o_price + 145, 2);
            }
            else
            {
                $carat_price = $row->rate + (($row->rate * ($this->customer_lab_discount)) / 100);
                $net_price = round($carat_price * $row->carat, 2);
            }
            $type = "Yes";
        }

        return [
            $row->id,
            $row->image,
            $row->video,
            $row->lab,
            '',
            $row->certificate_no,
            $net_price,
            $row->shape,
            $row->carat,
            $row->cut,
            $row->color,
            $row->clarity,
            $row->polish,
            $row->symmetry,
            $row->fluorescence,
            $row->table_per,
            $row->gridle,

            $row->crown_angle,
            $row->crown_height,
            $row->pavilion_angle,
            $row->pavilion_depth,

            $row->depth_per,
            ( (!empty($row->length) && !empty($row->width)) ? round($row->length/$row->width, 2) : 0 ),
            $row->length. '*'. $row->width. '*'. $row->depth,
            '','','','',
            $row->eyeclean,
            $type,
            "Y",
            "4",
            $row->country,
        ];
    }

}
