<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <table width="100%">
        <tr>
            <td colspan="4"><h2 align="center">Annexure-A</h2></td>
        </tr>
        <tr height="">
            <td colspan="4"><h4 align="center">EXPORT VALUE DECLARATION</h4></td>
        </tr>
        <tr>
            <td colspan="4"><h4 align="center">(See Rule 7 of Customs Valuation(Determination of Value of Exports Goods) Rules,2007)</h4></td>
        </tr>
        <tr>
            <td width="2%">1).</td>
            <td colspan="3">Shipping Bill No.& Date:-</td>
        </tr>
        <tr>
            <td width="2%">2).</h4></td>
            <td>Invoice No.& Date:-</td>
            <td>
                @if($consignment == 1)
                    EXPCON-{{ $exp_no }}/{{ $year }}
                @else
                    EXP-{{ $exp_no }}/{{ $year }}
                @endif
            </td>
            <td>Date:-{{ $date }}</td>
        </tr>
        <tr>
            <td width="2%">3).</td>
            <td colspan="3">Nature of Transaction </td>
        </tr>
        <tr>
            <td width="2%"></td>
            <td>sale :- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" {{ ($consignment != 1) ? 'checked' : '' }}></td>
            <td>Sale of Consignment&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" {{ ($consignment == 1) ? 'checked' : '' }}></td>
            <td>Gift&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"></td>
        </tr>
        <tr>
            <td>Sample</td>
            <td><input type="checkbox" size="2"></td>
            <td colspan="2">Other&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"></td>
        </tr>
        <tr>
            <td width="2%">4).</td>
            <td>Method of Valuation</td>
            <td colspan="2">Rule 3&nbsp;&nbsp;&nbsp;<input type="checkbox" {{ ($consignment == 1) ? 'checked' : '' }}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Rule 4&nbsp;&nbsp;&nbsp;<input type="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Rule 5&nbsp;&nbsp;&nbsp;<input type="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Rule 6&nbsp;&nbsp;&nbsp;<input type="checkbox"></td>
        </tr>
        <tr>
            <td width="2%">5).</td>
            <td>Whether  seller and buyer are Related</td>
            <td>Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"></td>
            <td>No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" checked></td>
        </tr>
        <tr>
            <td width="2%">6).</td>
            <td>If yes,whether relationship has influenced the price</td>
            <td>Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"></td>
            <td>No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" checked></td>
        </tr>
        <tr>
            <td width="2%">7).</td>
            <td>Terms Of Payment</td>
            <td colspan="2">COD</td>
        </tr>
        <tr>
            <td width="2%">8).</td>
            <td>Terms Of Delivery</td>
            <td colspan="2">Direct Parcel</td>
        </tr>
        <tr>
            <td width="2%">9).</td>
            <td colspan="3">Previous Exports of identical/similar goods,If any </td>
        </tr>
        <tr>
            <td colspan="2">Shipping Bill No.& Date:</td>
            <td colspan="2">N.A</td>
        </tr>
        <tr>
            <td width="2%">10).</td>
            <td colspan="3">Any other relevant information(Attach separate sheet,if necessary)</td>
        </tr>
        <tr>
            <td colspan="4"><u>DECLARATION</u></td>
        </tr>
        <tr>
            <td width="2%">1.</td>
            <td colspan="3">I/We hereby declare that the information furnished above is true,complete and correct in every respect.</td>
        </tr>
        <tr>
            <td width="2%">2.</td>
            <td colspan="3">I/We also undertake to bring to the notice of proper officer any particulars which subsequently come to my/our knowledge which will have bearing on a vacation</td>
        </tr>
        <tr>
            <td>Place:</td>
            <td>Mumbai</td>
            <td colspan="2">date:- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $date }}</td>
        </tr>
        <tr>
            <td colspan="3"> </td>
            <td height="125px"width="38%" valign="bottom">SIGNATURE OF THE EXPORTER<br/>NAME OF THE SIGNATORY</td>
        </tr>
    </table>
</body>
</html>
