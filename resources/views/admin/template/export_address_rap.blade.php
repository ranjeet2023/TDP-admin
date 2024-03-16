<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .maintable{
            border-collapse: collapse;
            width:100%
        }
    </style>
</head>
<body>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <b>
    <table class="maintable" style="outline:2px solid black; padding:5% 7%">
        <tr>
            <td style="padding-bottom:2%">
                <table width="100%" style="border:2px solid black; border-collapse:collapse;">
                    <tr><td colspan="2" align="center" style="border-bottom:2px solid black;"><b>BY AIR FREIGHT</b></td></tr>
                    <tr>
                        <td style="border-right:0px;border-bottom:2px solid black;" align="center">SHIPPING BILL #</td>
                        <td style="border-left:0px;border-bottom:2px solid black;" width="35%" >DATE : {{ $date }}</td>
                    </tr>
                    <tr></tr>
                        <td style="border-right:0px;" align="center">EDF NO.</td>
                        <td style="border-left:0px; border-top:0px;" width="35%">DATE :</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-bottom:2%">
                <table width="100%" style="outline:2px solid black;">
                    <tr>
                        <td width="2%"  align="right">TO,</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="8%">&nbsp;</td>
                        <td width="37%" style="padding-bottom: 2%;">
                            {{ $customer->user->companyname }}<br/>
                                {!! $customer->address !!}
                        </td>
                        <td width="55%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            PHONE : {{ $customer->user->mobile }}<br/>
                            EMAIL : {{ $customer->user->email }}<br/>
                            ATTEN : {{ $customer->user->firstname }} {{ $customer->user->lastname }}<br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td >
                <table width="100%" style="outline:2px solid black;">
                    <tr>
                        <td width="2%"  align="right">FROM,</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="8%">&nbsp;</td>
                        <td width="37%">
                            {{ $associate->name }}<br/>
                            {!! $associate->address !!}
                        </td>
                        <td width="55%" valign="bottom" align="right">Tel:-{{ $associate->mobile }}</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</b>
</body>
</html>
