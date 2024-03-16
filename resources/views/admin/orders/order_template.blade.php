<html>
    <head><style>
    table {display: table; width: 100%; border-collapse: collapse;}
    .pricedetail tr td{font-family:Verdana;font-size: 10px;}
    .pricedetail tr th{font-family:Verdana;font-size: 10px;}
    </style>
    </head>
<body>
    <table class="pricedetail">
        <tr><td colspan="100%" align="center">
            {{ $consignee == 1 ? 'DELIVERY CHALLAN iNVOICE' : 'INVOICE' }}
            </td>
        </tr>
    </table>
    <table class="pricedetail" style="border: 2px solid #333;">
        <tr>
            <td width="50%" style="border-right: 2px solid #333; "  valign="top">
                <table class="pricedetail" width="100%">
                <tr>
                    <td width="75%">
                        <strong>Exporter</strong><br>
                        <strong>{{ $as_name }}</strong> <br />
                        {!! $as_address !!}<br /><br />
                        PHONE #:  {{ $as_mobile }}<br />
                        EMAIL #:  {{ $as_email }}
                    </td>
                    <td width="25%">
                        {{-- <img src="{{asset('assets/images/logo1.png')}}" style="width: 153px;"> --}}
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 2px #333333 solid;" colspan="100%" valign="top">
                        <b>To</b><br />
                        <b>{{ $companyname }}</b><br />
                        {!! $shipping_address !!}<br />
                        PHONE : {{ $mobile }}<br/>
                        EMAIL : {{ $shiping_email }}<br/>
                        @if ($firstname != '')
                            ATTEN : {{ ucwords($firstname)." ".ucwords($lastname) }}
                        @endif
                        <br /><br />
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top" style="border-top: 2px #333333 solid; border-right: 2px #333333 solid;">
                        <span style="font-family:Verdana;font-size: 11px"><b>Pre-Carriage By</b></span><br>
                        <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $pre_carriage }}</span></div>
                    </td>
                    <td width="50%" valign="top" style="border-top: 2px #333333 solid;">
                        <span style="font-family:Verdana;font-size: 11px"><b>Place of Rcpt by Pre carrier</b></span><br>
                        <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $as_carrier_place }}</span></div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top" style="border-top: 2px #333333 solid; border-right: 2px #333333 solid;">
                        <span style="font-family:Verdana;font-size: 11px"><b>Vessel / Flight No</b></span><br>
                        <div align="center"><span style="font-family:Verdana;font-size: 11px">N.A.</span></div>
                    </td>
                    <td width="50%" valign="top" style="border-top: 2px #333333 solid;">
                        <span style="font-family:Verdana;font-size: 11px"><b>Port Of Loading</b></span><br>
                        <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $as_port_loading }}</span></div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top" style="border-top: 2px #333333 solid; border-right: 2px #333333 solid;">
                        <span style="font-family:Verdana;font-size: 11px"><b>Port of Discharge</b></span><br>
                        <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $portof_dischargeuser }}</span></div>
                        </td>
                    <td width="50%" valign="top" style="border-top: 2px #333333 solid;">
                        <span style="font-family:Verdana;font-size: 11px"><b>Final Destination</b></span><br>
                        <div align="center"><span style="font-family:Verdana;font-size: 11px">{{ $finaldestination }}</span></div>
                    </td>
                </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <table class="pricedetail">
                    <tr>
                        <td width="50%" style="border-right: 2px solid #333;" valign="top">
                            <b><span>Invoice no & Date</span></b><br />
                            <span>{{ ($consignee == 1) ? $consignee_no : 'TDP -'.$invoice_number }}</span><br />
                            <span>DATE : {{ date('Y-m-d') }}</span>
                        </td>
                        <td width="50%" valign="top">
                            @if($as_name == "R.K Export")
                            <b><span>Exporters Reference</span></b><br />
                            <span>{{ $importref }} UNDER CHAPTER 4</span>
                            @else
                            <b><span>Importers Ref</span></b><br />
                            <span>{{ $importref }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%" style="border-top: 2px #333333 solid;"  valign="top" align="center">
                            @if($as_name == "R.K Export")
                            <b>Buyers Order No & Date</b>
                            @else
                            <b><span style="text-align:center">DOOR-TO-DOOR</span></b><br />
                            <span style="text-align:center">INSURANCE COVERED {{ $amount_description }}</span><br /><br />
                            @endif
                        </td>
                    </tr>
                    @if($as_name == "R.K Export")
                    <tr>
                        <td colspan="100%" style="border-top: 2px #333333 solid;"  valign="top" style="text-align:left">
                            <b>Other Reference (s)</b><br />
                            GST NO : 24AVKPM1835G1ZN   &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp; IEC NO : 5215919381<br/>
                            PAN NO : AVKPM1835G<br/>
                            {{ $consignee == 1 ? '' : 'LUT/ARN NO. : AD2404210119976' }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="100%" style="border-top: 2px #333333 solid;"  valign="top" >
                            <b><span style="text-align:center">Buyer (if other than consignee)</span></b><br />
                            @if($consignee == 1)
                                {!! $consignee_buyer_name !!}
                            @endif
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 2px #333333 solid; border-right: 2px #333333 solid;"  valign="top">
                            Country of Origin of Goods
                            <div style="font-family:Verdana;font-size: 12px" align="center"><b>INDIA</b></div>
                        </td>
                        <td style="border-top: 2px #333333 solid;"  valign="top">
                            Country of Final Destination
                            <div style="font-family:Verdana;font-size: 12px" align="center"><b>{{ $finaldestination }}</b></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%" style="border-top: 2px #333333 solid;" valign="top">
                            <b>Terms : {{ $consignee == 1 ? 'CONSIGNMENT BASIS : 90 DAYS' : 'COD' }}</b><br>
                            <b>Terms of Delivery and Payment</b><br>
                            <b>Bank Details<br>
                            A/C NO : {{ $ac_no }}<br>
                            BANK NAME: {{ $bank_name }}<br>
                            BANK ADD: {{ $bank_address }}<br>
                            SWIFT Code: {{ $swift_code }}<br>

                            @if($inter_bank_address)
                                INTERMEDIARY BANK ADD: {{ $inter_bank_address }}<br>
                            @endif

                            @if($inter_swift_code)
                                INTERMEDIARY SWIFT Code: {{ $inter_swift_code }}<br>
                            @endif

                            @if($ad_code)
                                AD CODE. : {{ $ad_code }}<br>
                            @endif
                            </b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="pricedetail" style="border-left:2px solid #333;border-right:2px solid #333; border-bottom:2px solid #333;">
        <tr style="border-bottom:2px solid #333;">
            <th style="border-bottom:2px solid #333;">No</th>
            <th style="border-bottom:2px solid #333;"> No & Kind of Packages Description of Goods</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;" rowspan="2">HS CODE</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;" rowspan="2">No of PCS</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;" rowspan="2">CARATS</th>
            <th style="border-left:2px solid #333;">RATE</th>
            <th style="border-left:2px solid #333;">AMOUNT</th>
        </tr>
        <tr>
            <th style="border-bottom:2px solid #333;" colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ONE BOX CONTAINING CUT & POLISHED DIAMONDS LOTS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;border-top:2px solid #333;" align="center">US $/CARAT</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;border-top:2px solid #333;" align="center">US $</th>
        </tr>
        <tr>
            <td></td>
            <td ><span style="font-size: 10px!important;"> {{ $diamondsname }} CUT AND POLISHED DIAMONDS</span></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
        </tr>
        {!! $diamond_html !!}
        <tr>
            <td height="80" style="" align="center"></td>
            <td></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
        </tr>
        <tr>
            <td style="border-top:2px solid #333;" ></td>
            <td style="border-top:2px solid #333;" align="right">Shipping and insurance &nbsp;</td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;"  align="center">${{ round($shipping_charge, 2) }}</td>
        </tr>
        @if (!empty($discount_extra_order))
            <tr>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td align="right" style="border-top:2px solid #333;" >Extra Discount &nbsp;</td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;"  align="center">-$ {{ round($discount_extra_order, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="2" style="border-top:2px solid #333;">
                <table class="pricedetail">
                    <tr>
                    <td style="font-size: 18px;" >Country Of Origin : INDIA</td>
                    <td align="right"><b>TOTAL</b> &nbsp;</td>
                    </tr>
                </table>
            </td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center">{{ $pcs }} &nbsp;PCS</td>
            <td style="border-left:2px solid #333; border-top:2px solid #333;" align="center"><b>{{ $totalcarat }}</b></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">{{ $insurrance }}</td>
            <td style="border-left:2px solid #333; border-top:2px solid #333;"  align="center"><b>${{ $final_amount }}</b></td>
        </tr>
    </table>
    <table class="pricedetail" style="border-left:2px solid #333;border-right:2px solid #333; border-bottom:2px solid #333;">
        <tr>
            <td colspan="100%">
                <b>Amount Chargeable  {{ $insurrance }} ( US $ {{ $wordnumber }} only)</b><br /><br />

                @if ($consignee == 1)
                We undertake to provide Insurance certificate and Transport document evidencing Insurance and Freight being<br />
                arranged into India for consignment shipment under Invoice / Delivery Challan no. {{ $consignee_no }} as per Regulatory guidelines<br />
                "Insurance and Freight are being arranged in India"<br />
                REMIT THE PROCEEDS TO WELL FARGO BANK, N.A. (FORMERLY KNOWS AS WACHOVIA) NEW YORK, USA<br />
                SWIFT CODE: PNBPUS3NNYC<br />
                GIVING FINAL CREDIT Ground Floor, G/2 Union Trade Centre, Ring Rd, Udhana Darwaja, Surat, Gujarat 395002<br />
                BANK AC NO: 250909090000 SWIFT CODE: INDBINBBSUR<br />
                STATE CODE: 24<br />
                DISTRICT CODE: 459<br />
                LIEU OF INVOICE AS PER PROVISION UNDER RULE 55 CGST RULE 2017
                @endif
                <br />
                <b> Declaration:  </b><br />
                    @if($as_name == "R.K Export")
                        We shall file any claim against this invoice under RoDTEP scheme and shipping bill for this invoice is filed with custom<br/> icegate info code as RoDTEPY"<br/>
                    @else
                        We Declare that this Invoice shows the actual price of the goods described and that all particulars are true and correct.
                    @endif
                    The diamonds herein invoiced have been purchased from legitimate sources not involved in funding conflict and in compliance with United Nations Resolutions.<br/>
                    The seller hereby guarantees that these diamonds are conflict free based on personal knowledge <br/>
                    and/or written guarantees provided by supplier of these diamonds. <br/>
                <br/>

                @if($pre_carriage == 'JK-MALCA AMIT' && $as_name == "R.K Export")
                    DOOR TO DOOR INSURANCE COVERED BY  - JK MALCA AMIT
                @elseif($pre_carriage == 'BVC' && $as_name == "R.K Export")
                    DOOR TO DOOR INSURANCE COVERED BY  - BRINKS
                @endif
            </td>
        </tr>
        <tr>
            <td width="70%">
                <b> Declaration </b><br />
                <p>We declare that this invoice shows the actual price of the goods described and that all the particulars are true and correct.</p>
            </td>
            <td width="30%" style="border-left: 2px solid #333;border-top: 2px solid #333;border-right: 2px #333333 solid;">
                <b>Signature & Date {{ $date }} <br /> For  <br /><br /></b>
            </td>
        </tr>
    </table>
</body></html>
