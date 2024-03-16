<html>
    <head><style>
    table {display: table; width: 100%; border-collapse: collapse;}
    .pricedetail tr td{font-family:Verdana;font-size: 15px;}
    .pricedetail tr th{font-family:Verdana;font-size: 15px;}
    .diamonddetail tr td{font-family:Verdana;font-size: 14px;}
    </style>
    </head>
<body>
    <table class="pricedetail" style="margin-top:15%">
        <tr>
            <td width="40%" valign="top">
                <table class="pricedetail">
                    <tr>
                        <td>
                            <b>{!! $companyname !!}</b><br/>
                            <b>Attn </b>: {!! $attendie !!}<br/>
                            <b>ABN </b>: 24 991 655 307<br/>
                            <b>Ph </b>: {!! $mobile !!}<br/>
                            <b>Email </b>: {!! $email !!}<br/>
                            <b>Address </b>: {!! $shipping_address !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="25%" valign="top">
                <table class="pricedetail">
                    <tr>
                        <td>
                            <b>Invoice Number</b><br/>
                            {!! $invoice_number !!}
                        </td>
                    </tr>
                    <tr>
                        <td style="margin-top:2%">
                            <b>Reference </b><br/>
                            Diamond Sale

                        </td>
                    </tr>
                    <tr>
                        <td style="margin-top:2%">
                            <b>ABN </b><br/>
                            73 630 564 028
                        </td>
                    </tr>
                </table>
            </td>
            <td width="35%" valign="top">
                <table class="pricedetail">
                    <tr>
                        <td>
                            <b>{!! $as_name !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! $as_address !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Ph </b>:{!! $as_mobile !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Email </b>:{!! \Cons::EMAIL_INFO !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="diamonddetail" style="margin-top:5%;">
        <thead style="border-bottom:2px solid #333;">
            <th>Description</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Amount</th>
        </thead>
        <tbody>
            {!! $aus_diamond_html !!}

            <tr>
                <td></td>
                <td style="border-bottom:1px solid #333;border-right:1px solid #333;"colspan="2" align="right">Subtotal&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style="border-bottom:1px solid #333;"align="right">{!! number_format($total_aus_amount,2) !!}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-bottom:1px solid #333;border-right:1px solid #333;"colspan="2" align="right">Total GST 10%&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style="border-bottom:1px solid #333;"align="right">{!! number_format($aus_gst,2) !!}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-bottom:1px solid #333;border-right:1px solid #333;"colspan="2" align="right">Clearing Charges&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style="border-bottom:1px solid #333;"align="right">{!! number_format($clearing_charge_aud,2) !!}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-bottom:1px solid #333;border-right:1px solid #333;"colspan="2" align="right">Postage Charges&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style="border-bottom:1px solid #333;"align="right">{!! number_format($shipping_charge,2) !!}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-bottom:1px solid #333;border-right:1px solid #333;"colspan="2" align="right">Total AUD&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td style="border-bottom:1px solid #333;"align="right">{!! $total_amount_aus !!}</td>
            </tr>
        </tbody>

    </table>
    <table class="pricedetail" style="margin-top:10%">
        <tr>
            <td colspan="100%">
                <b>Date:{!! date('d-M-Y') !!}</b><br/>
                {!! $bank_name !!}<br/>
                Account Name: Krishna Sholapurwala<br/>
                BSB : {!! $bsb_code !!}<br/>
                Account# : {!! $ac_no !!}<br/>
                Please Reference Invoice Number To Any EFT<br/><br/>
                Terms: Payment on Order<br/>
                When the total purchase if for $3000 AUD or less, Postage Will be charged.

            </td>
        </tr>
    </table>
</body>
</html>
>
