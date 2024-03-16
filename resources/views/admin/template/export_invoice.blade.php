<html>
    <head><style>
    table {display: table; width: 100%; border-collapse: collapse;}
    .pricedetail tr td{font-family:Verdana;font-size: 10px;}
    .pricedetail tr th{font-family:Verdana;font-size: 10px;}
    </style>
    </head>
<body>
    <table class="pricedetail" width="100%">
        <tr><td colspan="100%" align="center">
            @if ($consignment == 1)
                DELIVERY CHALLAN CONSIGNMENT
            @else
                INVOICE
            @endif
            </td>
        </tr>
    </table>
    <table class="pricedetail" width="100%"  style="border:1px solid #333;">
        <tr>
            <td width="50%" valign="top" style="border-right:1px solid #333;">
                <table class="pricedetail" width="100%" >
                    <tr>
                        <td width="30%">
                            <strong>Exporter</strong><br>
                            <strong>{{ $associate->name }}</strong> <br />
                            {!! $associate->address !!}
                            <br />
                            TELEPHONE #: {{ $associate->mobile }}<br />
                            MOBILE #: {{ $associate->mobile }}
                        </td>
                        <td width="70%">
                            {{-- <img src="{{asset('assets/images/logo1.png')}}" style="width: 153px;"> --}}
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px #333333 solid;" width="60%" valign="top">
                            <b>CONSIGNEE</b><br />
                            <br/>
                            <b>{{ $address->company_name }}<br/>
                                {!! $address->address !!}
                            </b><br />
                            <br/>
                            PHONE : {{ $customer->user->mobile }}<br/>
                            EMAIL : {{ $address->user->email }}<br/>
                            ATTEN : {{ $customer->user->firstname }} {{ $customer->user->lastname }}
                            <br /><br />
                        </td>
                        <td style="border-top: 1px #333333 solid;"></td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top" style="border-top: 1px #333333 solid; border-right: 1px #333333 solid;">
                            <span style="font-family:Verdana;font-size: 11px"><b>Pre-Carriage By</b></span><br>
                            <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $broker_name }}</span></div>
                        </td>
                        <td width="50%" valign="top" style="border-top: 1px #333333 solid;">
                            <span style="font-family:Verdana;font-size: 11px"><b>Place of Rcpt by Pre carrier</b></span><br>
                            <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $associate->carrier_place }}</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top" style="border-top: 1px #333333 solid; border-right: 1px #333333 solid;">
                            <span style="font-family:Verdana;font-size: 11px"><b>Vessel / Flight No</b></span><br>
                            <div align="center"><span style="font-family:Verdana;font-size: 11px">AIR FREIGHT</span></div>
                        </td>
                        <td width="50%" valign="top" style="border-top: 1px #333333 solid;">
                            <span style="font-family:Verdana;font-size: 11px"><b>Port Of Loading</b></span><br>
                            <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $associate->port_loading }}</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top" style="border-top: 1px #333333 solid; border-right: 1px #333333 solid;">
                            <span style="font-family:Verdana;font-size: 11px"><b>Port of Discharge</b></span><br>
                            <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $address->port_of_discharge }}</span></div>
                            </td>
                        <td width="50%" valign="top" style="border-top: 1px #333333 solid;">
                            <span style="font-family:Verdana;font-size: 11px"><b>Final Destination</b></span><br>
                            <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $address->country }}</span></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <table class="pricedetail">
                    <tr>
                        <td width="50%" style="border-right: 1px solid #333;" valign="top">
                            <b><span>Invoice no & Date</span></b><br />
                            <span>@if($consignment == 1)
                                        EXPCON-{{ $exp_no }}/{{ $year }}
                                    @else
                                        EXP-{{ $exp_no }}/{{ $year }}
                                    @endif
                                    </span><br />
                            <span>DATE : {{ $date }}</span>
                        </td>
                        <td width="50%" valign="top">
                            <b><span>Exporters Reference</span></b><br />
                            <span>UNDER CHAPTER 4</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%" style="border-top: 1px #333333 solid;"  valign="top" align="center">
                            @if($associate->name == "R.K Exports")
                                <b>Buyers Order No & Date</b>
                            @else
                                <b><span style="text-align:center">DOOR-TO-DOOR</span></b><br />
                                <span style="text-align:center">INSURANCE COVERED BY {{ $broker_name }}</span><br /><br />
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <td colspan="100%" style="border-top: 1px #333333 solid;"  valign="top" style="text-align:left">
                            <b>Other Reference (s)</b><br />
                            GST NO : 24AVKPM1835G1ZN   &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp; IEC NO : 5215919381<br/>
                            PAN NO : AVKPM1835G<br/>
                            LUT/ARN NO. AD240323004790L
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%" style="border-top: 1px #333333 solid;"  valign="top" >
                            <b><span style="text-align:center">Buyer (if other than consignee)</span></b><br />
                            <br />
                            @if($consignment == 1 && $customer->shiping_email != '')
                                {!! $address->attend_name !!}
                            @else
                                DIRECT PARCEL<br/><br/><br/>
                            @endif
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px #333333 solid; border-right: 1px #333333 solid;"  valign="top">
                            Country of Origin of Goods
                            <div style="font-family:Verdana;font-size: 12px" align="center"><b>INDIA</b></div>
                        </td>
                        <td style="border-top: 1px #333333 solid;"  valign="top">
                            Country of Final Destination
                            <div style="font-family:Verdana;font-size: 12px" align="center"><b>{{ $address->country }}</b></div>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%" style="border-top: 1px #333333 solid;" valign="top">
                            <b>Terms of Delivery and Payment<br/>
                            @if ($consignment == 1)
                                TERMS CONSIGNMENT BASIS : 90 DAYS<br/>
                            @else
                                Terms : COD<br/>
                            @endif
                            BANKER : {{ $associate->bank_name }}<br/>
                            {!! $associate->address !!}
                            SWIFT CODE: {{ $associate->swift_code }}<br/>
                            BANK AC NO: {{ $associate->account_number }}<br/>
                            AD code: {{ $associate->address_code }}<br/>
                            </b>
                        </td>
                        <td style="border-top: 1px #333333 solid;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="pricedetail" width="100%"style="border-left:1px solid #333;border-right:1px solid #333;border-bottom:1px solid #333;">
        @php $k = 1; @endphp
        <tr style="border-bottom:1px solid #333;">
            <td style="border-bottom:1px solid #333;" rowspan="2"> Marks and Nos/</td>
            <td style="border-bottom:1px solid #333;" rowspan="2" colspan="2"> No & Kind of Packages Description of Goods</td>
            <td style="border-left:1px solid #333;border-bottom:1px solid #333;" rowspan="2">No of PCS</td>
            <td style="border-left:1px solid #333;border-bottom:1px solid #333;" rowspan="2">CARATS</td>
            <td style="border-left:1px solid #333;">RATE</td>
            <td style="border-left:1px solid #333;">AMOUNT</td>
        </tr>
        <tr>
            <td style="border-left:1px solid #333;border-bottom:1px solid #333;border-top:1px solid #333;" align="center">US $</td>
            <td style="border-left:1px solid #333;border-bottom:1px solid #333;border-top:1px solid #333;" align="center">US $</td>
        </tr>
        <tr>
            <td style="border-bottom:1px solid #333;">Container No 1</td>
            <td style="border-bottom:1px solid #333;"><span style="font-size: 10px!important;">ONE TIN BOX(1) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @if ($natural_diamond_pcs > 0 ) 71023910 @endif @if($natural_diamond_pcs > 0 && $lab_diamond_pcs > 0) - @endif @if ($lab_diamond_pcs > 0 ) 71049100 @endif</span>
            </td>
            <td style="border-bottom:1px solid #333;"></td>
            <td style="border-left:1px solid #333;border-bottom:1px solid #333;"></td>
            <td style="border-left:1px solid #333;"></td>
            <td style="border-left:1px solid #333;"></td>
            <td style="border-left:1px solid #333;"></td>
        </tr>
        @if ($natural_diamond_pcs > 0 )
            <tr>
                <td height="40" align="center">{{ $k; }}@php $k++; @endphp</td>
                <td>CUT & POLISHED NATURAL DIAMOND</td>
                <td>&nbsp;</td>
                <td style="border-left:1px solid #333;" align="center">{{ $natural_diamond_pcs }}</td>
                <td style="border-left:1px solid #333;" align="center">{{ round($natural_diamond_carat,2) }}</td>
                <td style="border-left:1px solid #333;" align="center">${{ round($natural_diamond_rate,2) }}</td>
                <td style="border-left:1px solid #333;" align="center">${{ round($natural_diamond_net_value,2) }}</td>
            </tr>
        @endif
        @if ($lab_diamond_pcs > 0 )
            <tr>
                <td height="40" align="center">{{ $k }}</td>
                <td>CUT & POLISHED LABORATORY GROWN DIAMOND</td>
                <td>&nbsp;</td>
                <td style="border-left:1px solid #333;" align="center">{{ $lab_diamond_pcs }}</td>
                <td style="border-left:1px solid #333;" align="center">{{ round($lab_diamond_carat,2) }}</td>
                <td style="border-left:1px solid #333;" align="center">${{ round($lab_diamond_rate,2) }}</td>
                <td style="border-left:1px solid #333;" align="center">${{ round($lab_diamond_net_value,2) }}</td>
            </tr>
        @endif
        <tr>
            <td height="40"></td>
            <td >AS PER PACKING LIST</td>
            <td >&nbsp;</td>
            <td style="border-left:1px solid #333;" ></td>
            <td style="border-left:1px solid #333;" ></td>
            <td style="border-left:1px solid #333;" ></td>
            <td style="border-left:1px solid #333;" ></td>
        </tr>
        <tr>
            <td >GR.WT.KG&nbsp;&nbsp;&nbsp;&nbsp;{{ $weight_box }}</td>
            <td >WITH TIN BOX</td>
            <td >&nbsp;</td>
            <td style="border-left:1px solid #333;" ></td>
            <td style="border-left:1px solid #333;" ></td>
            <td style="border-left:1px solid #333;" ></td>
            <td style="border-left:1px solid #333;" ></td>
        </tr>
        <tr>
           <td height="40" style="border-left:1px solid #333;" colspan="2" align="center">DETAILS OF PREFERENTIAL AGREEMENT NCPTI <br/>STANDARD UNIT QUANTITY CODE:- CTM</td>
           <td>&nbsp;</td>
           <td style="border-left:1px solid #333;" ></td>
           <td style="border-left:1px solid #333;" ></td>
           <td style="border-left:1px solid #333;" ></td>
           <td style="border-left:1px solid #333;" ></td>
        </tr>
        <tr>
            <td style="border-top:1px solid #333;border-bottom:1px solid #333;">&nbsp;</td>
            <td style="border-top:1px solid #333;border-bottom:1px solid #333;">&nbsp;</td>
            <td style="border-top:1px solid #333;border-bottom:1px solid #333;">&nbsp;</td>
            <td style="border-left:1px solid #333;border-top:1px solid #333;border-bottom:1px solid #333;">TOTAL CTS</td>
            <td style="border-right:1px solid #333;border-left:1px solid #333;border-top:1px solid #333;border-bottom:1px solid #333;">{{ round($total_carat,2) }}</td>
            <td style="border-right:1px solid #333; border-top:1px solid #333; border-bottom:1px solid #333;">TOTAL US</td>
            <td style="border-top:1px solid #333;border-bottom:1px solid #333;">${{ round($subtotal_amount,2) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-left:1px solid #333;">&nbsp;</td>
            <td style="border-left:1px solid #333;">&nbsp;</td>
            <td style="border-left:1px solid #333;border-bottom:1px solid #333;">Shipping</td>
            <td style="border-left:1px solid #333;border-bottom:1px solid #333;">
                @if(!empty($shipping_charge))
                    ${{ $shipping_charge }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Amount Chargeable
                @if($pre_carriage == 'CF')
                    C&F
                @else
                    {{ $pre_carriage }}
                @endif US (US $ {{ $wordnumber }})
            </td>
            <td style="border-left:1px solid #333;">&nbsp;</td>
            <td style="border-left:1px solid #333;border-right:1px solid #333;">&nbsp;</td>
            <td style="">
                @if($pre_carriage == 'CF')
                    C&F
                @else
                    {{ $pre_carriage }}
                @endif
            </td>
            <td style="border-left:1px solid #333;">${{ $total_amount }}</td>
        </tr>
    </table>
    <table class="pricedetail"  width="100%" style="border-left:1px solid #333;border-right:1px solid #333; border-bottom:1px solid #333;">
        <tr>
            <td colspan="2">
                @if ($consignment == 1)
                    We undertake to provide Insurance certificate and Transport document evidencing Insurance and Freight being <br/>
                    arranged into India for consignment shipment under Invoice / Delivery Challan No. EXPCON-{{ $exp_no }}/{{ $year }} as per Regulatory guidelines <br/>
                    "Insurance and Freight are being arranged in India"
                @endif
                REMIT THE PROCEEDS TO WELL FARGO BANK, N.A. (FORMERLY KNOWS AS WACHOVIA) NEW YORK, USA<br />
                SWIFT CODE: PNBPUS3NNYC<br />
                GIVING FINAL CREDIT Ground Floor, G/2 Union Trade Centre, Ring Rd, Udhana Darwaja, Surat, Gujarat 395002<br />
                BANK AC NO: 250909090000 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SWIFT CODE: INDBINBBSUR<br />
                STATE CODE: 24<br />
                DISTRICT CODE: 459<br />
                @if ($consignment == 1)
                    LIEU OF INVOICE AS PER PROVISION UNDER RULE 55 CGST RULE 2017
                @else
                    SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING WITHOUT PAYMENT OF INTEGRATED TAX.
                @endif
                <br />
                <b> Declaration:  </b><br />

                        @if ($consignment == 1)
                            We shall not file any claim against this invoice under RoDTEP scheme and shipping bill for this invoice is filed with custom<br/> icegate info code as RoDTEPN"<br/>
                        @else
                            We shall file any claim against this invoice under RoDTEP scheme and shipping bill for this invoice is filed with custom<br/> icegate info code as RoDTEPY"<br/>
                        @endif
                    SOURCES NOT INVOLVED IN FUNDING CONFLICT AND IN COMPLINANCE WITH <br/>
                    UNITED NATIONS RESOLUTIONS.THE SELLER HEREBY GUARANTEES THAT <br/>
                    THESE DIAMONDS ARE CONFLICT FREE BASED ON PERSONAL <br/>
                    KNOWLEDGE AND / OR WRITTEN GUARANTEES BY <br/>
                    THE SUPPLIER OF THESE DIAMONDS <br/>
                <br/>

                    DOOR TO DOOR INSURANCE COVERED BY  -
                    @if($broker_name  == "B.V.C")
                        BRINKS
                    @else
                        {{ $broker_name }}
                    @endif

            </td>
        </tr>
        <tr>
            <td width="70%">
                <b> Declaration </b><br />
                <p>We declare that this invoice shows the actual price of the goods described and that all the particulars are true and correct.</p>
            </td>
            <td width="30%" style="border-left: 1px solid #333;border-top: 1px solid #333;border-right: 1px solid #333;">
                <b>Signature & Date :- {{ $date }} <br /> For  {{ $associate->name }}<br /><br /><br /><br />PROPRIETOR</b>
            </td>
        </tr>
    </table>
</body></html>
