<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
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
                                <td style="padding:20px 0px 18px;letter-spacing:0.6px"><strong>Hi, {{ $firstname }} {{ $lastname }}</strong></td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;letter-spacing:0.6px">
                                    Thank you for registering with {{config('app.name')}}. <br/> Please verify your email using a below link.
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" style="padding:18px 0px 0px;">
                                    <p style="margin: 0 auto;text-align: center;width:62px;background-color:#2b2258;font-size:20px;color:rgb(255,255,255);border-radius:35px;min-width:260px" >
                                        <a href="{{ url('') }}/verification/{{ $link }}" target="_blank" style="color: #ffffff; text-decoration: none; display: inline-block; line-height: 49px;min-width: 260px;">verify now</a>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:18px 0px 0px;font-size:16px;letter-spacing:0.6px">
                                    You have received this email because you requested for registration with {{config('app.name')}} s web portal {{config('app.website')}} with this email address. If you did not intend to, you can ignore this email the account has not been created yet.
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:18px 0px 0px;line-height:24px;letter-spacing:0.5px;opacity:0.8;font-weight: 700;">
                                    Thanks,<br /> {{config('app.name')}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                    <p>{{ \Cons::ADDRESS_USA }}</p>
                    <p>Copyright &copy;
                    <a href="{{ url('') }}" rel="noopener" target="_blank">{{config('app.name')}}</a></p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
