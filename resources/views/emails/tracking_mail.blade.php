<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style type="text/css">
        body {margin: 0px;}
    </style>
</head>
<body style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
        <tbody>
            <tr>
                <td align="center" valign="center" style="text-align:center; padding: 40px">
                    <a href="{{ url('') }}" rel="noopener" target="_blank">
                        <img src="{{asset('assets/images/logo-dark-email.png')}}" alt="{{config('app.name')}}" style="width: 300px;" class="h-45px" />
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" align="center" style="width:500px;background-color:rgb(255,255,255);border-radius:10px;padding:38px 45px 32px 35px;">
                        <tbody>
                            <tr>
                                <td style="padding:20px 0px 18px;letter-spacing:0.6px" colspan=100%><strong>Hello, {{ $customer->firstname }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0px 18px;font-size:14px;letter-spacing:0.6px;" colspan=100%>
                                    Your Awesome Art Box has Shipped!
                                </td>
                            </tr>
                            <tr style="padding:20px 0px 18px;font-size:14px;letter-spacing:0.6px;" >
                                <td colspan=100%>
                                    Your shipment began <b> {!! $date !!} </b> via <b> {!! $customer->pre_carriage !!} </b> Priority Mail (takes 2-3 business days) to:
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:14px;letter-spacing:0.6px; padding-top: 10px; width:45%">
                                    {!! $address->address !!}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size:14px;letter-spacing:0.6px; padding-top: 10px;" colspan="100%">
                                    Track Your Shipment:
                                    <a href="{{ url('') }}" style="text-decoration-color: #4f4f4f"> <strong> {!! $tracking_no !!}</strong></a><br/>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:18px 0px 0px;line-height:24px;letter-spacing:0.5px;opacity:0.8;font-weight: 700; font-size:14px;" colspan=100%>
                                    Thanks,<br />
                                    <a href="{{ url('') }}" style="text-decoration-color: #4f4f4f"> <strong>{{config('app.name')}}</strong></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                    <p>Copyright &copy;
                    <a href="{{ url('') }}" rel="noopener" target="_blank">{{config('app.name')}}</a></p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
