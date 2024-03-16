<html>
    <head>
        <style>
            table {display: table; width: 100%; border-collapse: collapse;}
            .pricedetail tr td{font-family:Verdana;font-size: 10px;}
            .pricedetail tr th{font-family:Verdana;font-size: 10px;}
        </style>
    </head>
<body>
    <table class="pricedetail">
        <tr><td colspan="100%" align="center">
                Tax Invoice
            </td>
        </tr>
    </table>
    <table class="pricedetail" style="border: 2px solid #333;">
        <tr>
            <td width="50%" style="border-right: 2px solid #333; "  valign="top">
                <table class="pricedetail" width="100%">
                    <tr>
                        <td colspan="100%">
                            <strong>R K EXPORTS</strong><br/>
                            <span style="text-transform:uppercase">
                                U-4C GROUND FLOOR,<br/>
                                shankheshwar complex,sagrampura,<br/>
                                surat<br/>
                            </span>
                            GSTIN/UIN : 24AVKPM1835G1ZN<br/>
                            State Name : Gujarat <br/>
                            Code : 24 &nbsp;&nbsp;&nbsp; District Code:459
                        </td>
                    </tr>
                    <tr style="border-top: 2px #333333 solid;">
                        <td width="60%" valign="top">
                            Buyer(Bill to)<br />
                            <span style="text-transform:uppercase">
                                <b>{{ $companyname }}</b><br />

                                {!! $shipping_address !!}
                            </span><br/>
                            GSTIN/UIN : {!! $cus_gst !!}<br/>
                            State Name : {!! $cus_state !!} , Code : {!! $cus_state_code !!}<br/>
                            Place Of Supply : {!! $cus_POS !!}
                        </td>
                        <td width="40%"> &nbsp;</td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <table class="pricedetail">
                    <tr>
                        <td width="50%" style="border-right: 2px solid #333;" valign="top">
                            <b><span>Invoice no</span></b><br />
                            <span><b>{!! $invoice_number !!}</b></span>
                        </td>
                        <td width="50%" valign="top">
                            Date<br/>
                            <b>{!! $date1 !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-right: 2px solid #333;border-top: 2px solid #333;"></td>
                        <td width="50%" style="border-top: 2px solid #333;" valign="top">
                            Mode/Terms Of Payment<br/>
                            <b>7 DAYS</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%" style="border-top: 2px solid#333;">
                            Terms Of Delivery<br/>
                            <b>45 DAYS RETURN POLICY<br/>
                            GOODS SUPPLIED TO {!! $cus_POS !!}</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="pricedetail" style="border-left:2px solid #333;border-right:2px solid #333; border-bottom:2px solid #333;">
        <tr style="border-bottom:2px solid #333;">
            <th style="border-bottom:2px solid #333;" width="6%">SI No.</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;" width="45%"> Description of Goods</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;">HSN/SAC</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;">Quantity</th>
            <th style="border-left:2px solid #333;border-bottom:2px solid #333;">Rate</th>
            <th style="border-left:2px solid #333;">Per</th>
            <th style="border-left:2px solid #333;">Amount</th>
        </tr>
        <tr>
            <td></td>
            <td style="border-left:2px solid #333;"><b>{!! $diamondsname !!} Polish Diamonds</b></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
            <td style="border-left:2px solid #333;"></td>
        </tr>
        {!! $diamond_html !!}
        <tr>
            <td></td>
            <td style="border-left:2px solid #333;" align="right">&nbsp;</td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;"  align="right"></td>
        </tr>
        <tr>
            <td></td>
            <td style="border-left:2px solid #333;" align="right">&nbsp;</td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;"  align="right"></td>
        </tr><tr>
            <td></td>
            <td style="border-left:2px solid #333;" align="right">&nbsp;</td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;"  align="right"></td>
        </tr>
        {!! $tax_html !!}
        <tr>
            <td></td>
            <td style="border-left:2px solid #333;" align="right"><b><i>ROUND OFF</i></b></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;"  align="right">{!! $round_off !!}</td>
        </tr>
        <tr>
            <td></td>
            <td style="border-left:2px solid #333;" align="right">&nbsp;</td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;"  align="right"></td>
        </tr>
        <tr>
            <td style="border-top:2px solid #333;"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right"><b><i>Total</i></b></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center">{!! $totalcarat !!} Carat</td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;"  align="right"><span style="font-family: DejaVu Sans, sans-serif;">&#8377;</span>{!! $final_total_inr !!}</td>
        </tr>
        <tr>
            <td colspan="6" style="border-top:2px solid #333;">
                Amount Chargeable(in words)<br/>
                <b><span style="font-size:15px;">INR {!! $wordnumber !!} Only</span></b>
            </td>
            <td style="border-top:2px solid #333;" align="right" valign="top"><i>E. & O.E&nbsp;</i></td>
        </tr>
    </table>
    <table class="pricedetail" style="border-left:2px solid #333;border-right:2px solid #333; border-bottom:2px solid #333;">
        <tr>
            <th width="50%" rowspan="2">HSN/SAC</th>
            <th style="border-left:2px solid #333;" rowspan="2">Taxable Value</th>
            @if($cus_state_code != 24)
                <th style="border-left:2px solid #333;" colspan="2">Integrated Tax</th>
            @else
                <th style="border-left:2px solid #333;" colspan="2">Central Tax</th>
                <th style="border-left:2px solid #333;" colspan="2">State Tax</th>
            @endif
            <th style="border-left:2px solid #333;" rowspan="2">Total<br/>Tax Amount</th>
        </tr>
        <tr>
            <th style="border-left:2px solid#333;border-top:2px solid #333;">Rate</th>
            <th style="border-left:2px solid#333;border-top:2px solid #333;">Amount</th>
            @if($cus_state_code == 24)
                <th style="border-left:2px solid#333;border-top:2px solid #333;">Rate</th>
                <th style="border-left:2px solid#333;border-top:2px solid #333;">Amount</th>
            @endif
        </tr>
        {!! $taxation_html !!}
        <tr>
            <td style="border-top:2px solid #333;" align="right"><b>Total</b></td>
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">{!! number_format($total_amo,2) !!}</td>
            @if($cus_state_code != 24)
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">{!! number_format($total_igst,2) !!}</td>
            @else
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">{!! number_format($total_cgst,2) !!}</td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="center"></td>
                <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">{!! number_format($total_sgst,2) !!}</td>
            @endif
            <td style="border-left:2px solid #333;border-top:2px solid #333;" align="right">{!! number_format($total_tax,2) !!}</td>
        </tr>
    </table>
    <table class="pricedetail" style="border-left:2px solid #333;border-right:2px solid #333; border-bottom:2px solid #333;">
        <tr>
            <td colspan="100%">
                Tax Amount(in words) : <b>INR {!! $tax_words !!} Only</b><br/>
                Company's PAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>AVKPM1835G</b><br/><br/><br/>
                <table width="50%">
                    <tr>
                        <td colspan="100%">
                            <u>Declaration</u><br/>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="">
                            The Goods herein invoiced have been purchased from legitimate sources not involved in fundingconflict and in compliance with The United Nations
                            resolutions. The seller hereby guarantees that these diamonds are conflict free, based on personal knowledge and / or written provided by
                            the supplier of these goods.
                        </td>
                        <td width="50%">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Company's Bank Details<br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A/C Holder's Name : <b>{!! $as_name !!}</b><br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>{!! $bank_name !!}</b><br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A/C No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>{!! $ac_no !!}</b><br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Branch & IFS Code : <b>{!! $branch_name !!} & {!! $ifsc_code !!}</b><br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%" style="border-top: 2px solid #333;">
                Customer's Seal and Signature
            </td>
            <td width="50%" style="border-left: 2px solid #333;border-top: 2px solid #333;border-right:2px solid #333;" align="right">
                <span><b>for {!! $as_name !!}</b></span>
            </td>
            <td></td>
        </tr>
        <tr>
            <td width="50%">
            </td>
            <td width="50%" style="border-left: 2px solid #333;border-right:2px solid #333; " align="right">
                <span><br><br></span>
            </td>
            <td></td>
        </tr>
        <tr>
            <td width="50%">
            </td>
            <td width="50%" style="border-left: 2px solid #333;border-right:2px solid #333;" align="right">
                <span>Authorised Signatory</span>
            </td>
            <td></td>
        </tr>
    </table>
</body></html>
