<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {display: table; width: 100%; border-collapse: collapse;}
        .pricedetail tr td{font-family:Verdana;font-size: 10px;}
        .pricedetail tr th{font-family:Verdana;font-size: 10px;}
    </style>
</head>
<body>
    <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
            <th colspan="100%"><b>PACKING LISTS</b></th>
        </thead>
        <tbody>
            <tr>
                <td width="50%" valign="top">
                    <table class="pricedetail" style="border-collapse:collapse;" border="0" width="100%">
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td colspan="3" style="border-bottom:1px solid #333333;">
                                <strong>EXPORTER</strong><br/>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%">
                                <strong>{{ $associate->name }}</strong><br/>
                                {!! $associate->address !!}
                            </td>
                            <td width="25%">

                            </td>
                            <td width="40%" style="border-left:1px solid #333333;">
                                TEL-{{ $associate->mobile }}<br/>
                                24AVKPM1835G1ZN<br/>
                                PAN NO- AVKPM1835G<br/>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%"  valign="top">
                    <table class="pricedetail" height="100%" width="100%" border="0" style="border-right:1px solid #333333">
                        <tr>
                            <td width="100%" style="border-bottom: 1px #333333 solid;" valign="top" colspan="3">
                                <b><span>Invoice no & Date : &nbsp;&nbsp;&nbsp;&nbsp;
                                @if($consignment == 1)
                                    EXPCON-{{ $exp_no }}/{{ $year }}
                                @else
                                    EXP-{{ $exp_no }}/{{ $year }}
                                @endif &nbsp;&nbsp; {{ $date }}</span></b>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="border-bottom:1px #333333 solid" colspan="3">
                                <b>BUYER</b><br/>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">
                                <b>{{ $address->company_name }}</b><br/>
                                {{ $address->address }}<br/>
                            </td>
                            <td width="20%"></td>
                            <td style="border-left: 1px #333333 solid;">
                                <span>PHONE : {{ $customer->user->mobile }} </span><br/>
                                <span>EMAIL : {{ $customer->user->email }}</span><br/>
                                <span>ATTEN : {{ $customer->user->firstname }}&nbsp;{{ $customer->user->lastname }}</span><br/>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="pricedetail"  border="1" width="100%" style="border-collapse: collapse"><tbody>
            <tr>
                <td align="center"><b>SR NO</b></td>
                <td align="center"><b>LAB</b></td>
                <td align="center"><b>CERT</b></td>
                <td align="center"><b>SHAPE</b></td>
                <td align="center" colspan="2"><b>PCS</b></td>
                <td align="center"><b>CARATS</b></td>
                <td align="center"><b>COLOR</b></td>
                <td align="center"><b>CLARITY</b></td>
                <td align="center"><b>NET RATE</b></td>
                <td align="center"><b>VALUE</b></td>
            </tr>
            @if ($natural_diamond_pcs > 0 )
                <tr>
                    <td> </td>
                    <td style="border-right:0px; "></td>
                    <td style="border-right:0px; border-left:0px;"></td>
                    <td style="border-right:0px; border-left:0px;" align="center"><b>CUT & POLISHED NATURAL DIAMOND</b></td>
                    <td style="border-right:0px; border-left:0px;"></td>
                    <td style="border-left:0px;"></td>
                    <td></td>
                    <td align="center"><b>71023910</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @php
                    $id_increamenting_natural = 1;
                    $total_pc_natural = 0;
                    $total_carat_natural = 0;
                    $total_net_price_natural =0;
                    $total_value_natural = 0;
                    @endphp
                @foreach($order_stones_natural as $natural_diamond)
                    @php
                    $percarat = round($natural_diamond->orders->sale_rate,2);
                    @endphp
                    <tr>
                        <td align="center">{{ $id_increamenting_natural }}</td>
                        <td align="center">{{ $natural_diamond->lab }}</td>
                        <td align="center">{{ $natural_diamond->certificate_no }}</td>
                        <td align="center">{{ $natural_diamond->shape }} {{ $natural_diamond->length. ' x '.$natural_diamond->width. ' x '.$natural_diamond->depth }}</td>
                        <td align="center">1</td>
                        <td align="center">PC</td>
                        <td align="center">{{ $natural_diamond->carat }}</td>
                        <td align="center">{{ $natural_diamond->color }}</td>
                        <td align="center">{{ $natural_diamond->clarity }}</td>
                        @if ($consignment == 0 && ($customer->cus_id == \Cons::ASSOCIATE_HK_ID || $customer->cus_id == \Cons::ASSOCIATE_USA_ID || $customer->cus_id == \Cons::ASSOCIATE_AUS_ID ) )
                            {!!
                            $natural_sale_price = round(($percarat * $natural_diamond->carat) - 15, 2);
                            !!}
                        @else
                            {!!
                            $natural_sale_price = round(($percarat * $natural_diamond->carat), 2);
                            !!}
                        @endif
                        {!!
                            $natural_sale_rate = round($natural_sale_price/$natural_diamond->carat, 2);

                            $total_net_price_natural += $natural_sale_rate;
                            $total_value_natural += $natural_sale_price;
                        !!}
                        <td align="center">${{ $natural_sale_rate }}</td>
                        <td align="center">${{ $natural_sale_price }}</td>
                    </tr>

                    @php
                        $id_increamenting_natural += 1;
                        $total_pc_natural +=1;
                        $total_carat_natural += $natural_diamond->carat;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="11" style="border-left:0px;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-right:0px;">&nbsp;</td>
                    <td style="border-left:0px;" align="center">TOTAL</td>
                    <td align="center"><b>{{ $total_pc_natural }}</b></td>
                    <td align="center"><b>PC</b></td>
                    <td align="center"><b>{{ $total_carat_natural }}</b></td>
                    <td></td>
                    <td></td>
                    <td align="center"><b>${{ round($total_net_price_natural,2) }}</b></td>
                    <td align="center"><b>${{ round($total_value_natural,2) }}</b></td>
                </tr>
            @endif
            <tr>
                <td colspan="11" style="border-left:0px;">&nbsp;</td>
            </tr>
            @if ($lab_diamond_pcs > 0 )
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="center"><b>CUT & POLISHED LAB GROWN DIAMOND</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="center"><b>71049100</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @php
                    $id_increamenting_lab = 1;
                    $total_pc_lab = 0;
                    $total_carat_lab = 0;
                    $total_net_price_lab =0;
                    $total_value_lab = 0;
                @endphp
                @foreach($order_stones_lab as $labgrown_diamond)
                    @php
                        if($labgrown_diamond->lab == "IGI")
                        {
                            $certificate = str_replace("LG", '', $labgrown_diamond->certificate_no);
                            $certificate = "LG".$certificate;
                        }
                        else
                        {
                            $certificate = $labgrown_diamond->certificate_no;
                        }
                        $percarat = round($labgrown_diamond->orders->sale_rate,2);
                    @endphp
                    <tr>
                        <td align="center">{{ $id_increamenting_lab }}</td>
                        <td align="center">{{ $labgrown_diamond->lab }}</td>
                        <td align="center">{{ $certificate }}</td>
                        <td align="center">{{ $labgrown_diamond->shape }} {{ $labgrown_diamond->length. ' x '.$labgrown_diamond->width. ' x '.$labgrown_diamond->depth }}</td>
                        <td align="center">1</td>
                        <td align="center">PC</td>
                        <td align="center">{{ $labgrown_diamond->carat }}</td>
                        <td align="center">{{ $labgrown_diamond->color }}</td>
                        <td align="center">{{ $labgrown_diamond->clarity }}</td>
                        @if ($consignment == 0 && ($customer->cus_id == \Cons::ASSOCIATE_HK_ID || $customer->cus_id == \Cons::ASSOCIATE_USA_ID || $customer->cus_id == \Cons::ASSOCIATE_AUS_ID ) )
                            {!!
                            $lab_sale_price = round(($percarat * $labgrown_diamond->carat) - 15, 2);
                            !!}
                        @else
                            {!!
                            $lab_sale_price = round(($percarat * $labgrown_diamond->carat), 2);
                            !!}
                        @endif
                        {!!
                            $lab_sale_rate = round($lab_sale_price/$labgrown_diamond->carat, 2);

                            $total_net_price_lab += $lab_sale_rate;
                            $total_value_lab += $lab_sale_price;
                        !!}
                        <td align="center">${{ $lab_sale_rate }}</td>
                        <td align="center">${{ $lab_sale_price }}</td>
                    </tr>

                    @php
                        $id_increamenting_lab += 1;
                        $total_pc_lab +=1;
                        $total_carat_lab += $labgrown_diamond->carat;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="11" style="border-left:0px;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-right:0px;">&nbsp;</td>
                    <td style="border-left:0px;" align="center">TOTAL</td>
                    <td align="center"><b>{{ $total_pc_lab }}</b></td>
                    <td align="center"><b>PC</b></td>
                    <td align="center"><b>{{ $total_carat_lab }}</b></td>
                    <td></td>
                    <td></td>
                    <td align="center"><b>${{ round($total_net_price_lab, 2) }}</b></td>
                    <td align="center"><b>${{ round($total_value_lab, 2) }}</b></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
