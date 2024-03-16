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
                                <td style="font-weight: 700;">Hi, {{ $firstname }} {{ $lastname }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;letter-spacing:0.6px;margin-top:5px;">
                                    We are glad to inform you that your account is approved.
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;letter-spacing:0.6px;margin-top:5px;">
                                    Welcome Aboard to {{config('app.name')}} <br/>
                                    Browse our large collections of Natural & Lab Grown diamonds.<br/>
                                    Use our API services to upload the stock & iframe services to use the search widget.
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;letter-spacing:0.6px;margin-top:5px;">
                                    Your new account is approved with following details:
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;letter-spacing:0.6px;margin-top:5px;">
                                    Name: {{ $firstname.' '.$lastname }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;letter-spacing:0.6px;">
                                    Company name: {{ $companyname }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:16px;letter-spacing:0.6px;">
                                    Email: {{ $email }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 700;">
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
