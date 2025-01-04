
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Airway Bill # 93106112</title>
    <style type="text/css">
        body{
            padding: 0px;
            margin: 0px;
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        *{
        box-sizing: border-box;
        }
        input[type=text], textarea{
            border:0px;
            padding:0px;
            margin: 0px;
            outline: none;
        }
        textarea{
            resize:none
        }
        input[type=text]:focus, textarea:focus{
            background-color: #F1F1F1;
        }
        .canvas{
            width: 10in;
            background-size: 100%;
            position: relative;
            margin: 20px auto;
        }	
        .marker{
            background-color: green;
            width: 100%;
            height: 100%;
            opacity: .6;
        }
        .header{
            border-bottom: 2px solid #000;
            padding-top: 20px;
        }
        .header:after{
            clear: both;
            content: " ";
            display: table;
        }
        .header .col-left{
            width: 50%;
            float: left
        }
        .header .col-left:after{
            clear: both;
            display: table;
            content: " ";
        }
        .header .col-left .logo{
            float: left;
            width: 150px;
        }
        .header .col-left .logo img{
            width: 100%;
        }
        .header .col-left .data{
            margin-left: 160px;
        }
        .header .col-left .data:after{
            clear: both;
            content: " ";
            display: table;
        }
        .header .col-left .data .destination{
            width: 75px;
            float: left;
        }
        .header .col-left .data .destination label{
            font-weight: bold;
        }
        .header .col-left .data .destination input{
        border: 2px solid #000;
        border-bottom: 0px;
        border-right: 0px;
        font-size: 20px;
        text-align: center;
        font-weight: bold;
        padding: 8px 3px 9px 3px;
        text-transform: uppercase;
        }
        .header .col-left .data .date{
            width: 75px;
            float: left;
        }
        .header .col-left .data .date label{
            font-weight: bold;
            padding-left: 20px;
        }
        .header .col-left .data .date .textarea{
            border: 2px solid #000;
            border-bottom: 0px;
            padding:5px 2px;
        }
        .header .col-left .data .date .textarea input{
            max-width: 100%;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
        .header .col-right{
            width: 50%;
            float: left;
            position: relative;
        }
        .header .col-right .title{
            position: absolute;
            font-size: 16px;
            font-weight: bold;
            top: -22px;
            left: -50px;
        }
        .header .col-right .data{

        }
        .header .col-right .data:after{
            clear: both;
            display: table;
            content: " ";
        }
        .header .col-right .data .destination{
            width: 75px;
            float: left;
        }
        .header .col-right .data .destination label{
            font-weight: bold;
        }
        .header .col-right .data .destination input{
        border: 2px solid #000;
        border-bottom: 0px;
        font-size: 20px;
        text-align: center;
        font-weight: bold;
        padding: 8px 3px 9px 3px;
        text-transform: uppercase;
        }
        .header .col-right .data .airwaybill{
                margin-left: 75px;
            padding-top: 10px;
            text-align: center;
        }
        .header .col-right .data .airwaybill input{
            width: 100%;
            font-family: 'Times New Roman',sans-serif;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
        }
        .header .col-right .data .airwaybill .label{
            text-transform: uppercase;
            font-weight: bold;
            font-size: 12px;
        }

        .content{
            border: 2px solid #000;
            border-top:0px;
        }
        .content:after{
            clear: both;
            display: table;
            content: " ";
        }
        .content .left{
            float: left;
            width: 50%;
            
        }
        .content .right{
            width: 50%;
            float: left;
            border-left: 2px solid #000;
        }


        .row{
            border-bottom: 2px solid #000;
            padding:4px;
        }
        .row:after{
            clear: both;
            content: " ";
            display: table;
        }
        .row.row-nop{
            padding: 0px;
        }
        .row:last-child{
            border-bottom: 0px;
        }
        .row .col{
            width: 100%;
        }
        .row .col.col50{
            width: 50%;
            float: left;
        }
        .row .col label{
            text-transform: uppercase;
            font-weight: bold;
            display: block;
        }
        .row .col input{
            width: 100%;
            display: block;
        }
        .row p{ 
            padding: 0px;
            margin: 0px;
                color: #fe0000;
        }
        .row .block{
            border-right:2px solid #000;
            float: left;
            padding: 4px;
        }
        .row .block:last-child{
            border-right:0px;
        }
        .row .block.block10{ width: 10% }
        .row .block.block20{ width: 20% }
        .row .block.block30{ width: 30% }
        .row .block.block40{ width: 40% }
        .row .block.block50{ width: 50% }
        .row .block.block60{ width: 60% }
        .row .block.block70{ width: 70% }
        .row .block.block80{ width: 80% }
        .row .block.block90{ width: 90% }
        .row .block.block100{ width: 100% }


        .tmain{
            border-left: 0px;
            border-top: 0px;
            text-transform: uppercase;
        }

        .b{ border:2px solid #000; }
        .bt{ border-top:2px solid #000; }
        .br{ border-right:2px solid #000; }
        .bb{ border-bottom:2px solid #000; }
        .bl{ border-left:2px solid #000; }
        .b1{ border:1px solid #000; }
        .bt1{ border-top:1px solid #000; }
        .br1{ border-right:1px solid #000; }
        .bb1{ border-bottom:1px solid #000; }
        .bl1{ border-left:1px solid #000; }
        .bg{
            background-color: #fe0000;
            color: #fff;
        }
        @media print {
            .noprint {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print();" class="noprint" style="padding: 10px; width: 100%">Print</button>

    <div class="canvas">
        <div class="header">
            <div class="col-left">
                <div class="logo"><img src="https://pk.vesco.app/public/images/apx-logo.png" alt=""></div>
                <div class="data">
                    <div class="destination">
                        <label for="header-destination">DESTINATION</label>
                        <input type="text" id="header-destination" class="destination" name="destination" value="{{ $receipt->destination_code }}">
                    </div>
                    <div class="date">
                        <label for="header-date">DATE</label>
                        <div class="textarea">
                            <div class="dateline1"><input type="text" value="{{ \Carbon\Carbon::parse($receipt->date)->format('M d'); }}"></div>
                            <div class="dateline1"><input type="text" value="{{ \Carbon\Carbon::parse($receipt->date)->format('Y'); }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="title">AIRWAYBILL</div>
                <div class="data">
                    <div class="destination">
                        <label for="header-destination">ORIGIN CODE</label>
                        <input type="text" id="header-destination" class="destination" name="destination" value="{{ $receipt->origin_code }}">
                    </div>
                    <div class="airwaybill">
                        <input type="text" name="number" id="number" class="number" value="* {{ $receipt->airway_bill_number }} *">
                        <div class="label">Consignment Number</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="left">
                <div class="row">
                    <div class="col col50">
                        <label for="shippet_account_no">Shipper Account Number</label>
                        <input type="text" id="shippet_account_no" class="input" value="{{ $receipt->shipper_account_number }}">
                    </div>
                    <div class="col col50">
                        <label for="cheque_no">Credit Card / Cheque No</label>
                        <input type="text" id="cheque_no" class="input" value="{{ $receipt->shipper_credit_card }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="shipper_name">Shipper Name</label>
                        <input type="text" id="shipper_name" class="input" value="{{ $receipt->shipper_name }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="shipper_address">Shipper Address</label>
                        <input type="text" id="shipper_address" class="input" value="{{ $receipt->shipper_address }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col50">
                        <input type="text" id="shipper_address2" class="input" value="">
                        <input type="text" id="shipper_address3" class="input" value="">
                    </div>
                    <div class="col col50">
                        <label for="shipper_city">City</label>
                        <input type="text" id="shipper_city" class="input" value="{{ $receipt->shipper_city }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col50">
                        <label for="shipper_zip">Post / ZIP code</label>
                        <input type="text" id="shipper_zip" class="input" value="{{ $receipt->shipper_zip }}">
                    </div>
                    <div class="col col50">
                        <label for="shipper_country">Country</label>
                        <input type="text" id="shipper_country" class="input" value="{{ $receipt->shipper_country }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col50">
                        <label for="shipper_phone">Telephone</label>
                        <input type="text" id="shipper_phone" class="input" value="{{ $receipt->shipper_phone }}">
                    </div>
                    <div class="col col50">
                        <label for="shipper_department">Sent By (Name dept.)</label>
                        <input type="text" id="shipper_department" class="input" value="{{ $receipt->shipper_department }}">
                    </div>
                </div>
                <div class="row">
                    <p><strong>Shipper's Agreement:</strong> Unless Otherwise agreed in Writing. I/We Agree the Carrier's Terms and Condition Are All The Terms Of The Contract Between Me/us And Carrier's And (1) Such Terms &anp; Conditions And Where Applicable. The Warsaw Convention Limits And / Or Excludes Carrier's Liability For Loss Damage Or Delay And (2) This Shipment Does Not Contain Cash Or Dangerous Good.<br /> Note: Claim Will be Entertained According to the Provided Invoice Value. If Less Then Us $100 Or Us $100 Maximum. insurance is Compulsory From the Shipper Side. If the Shipment Value is More than US $100 Otherwise APX is not Responsible For Any Loss Or Damage Of Good.</p>
                </div>

                <div class="row row-nop">
                    <div class="block block30">
                        <div class="col">
                            <label for="shipper_signature">Shipper Signature</label>
                            <input type="text" id="shipper_signature" value="" class="input">
                        </div>
                    </div>
                    <div class="block block30">
                        <div class="col">
                            <label for="booking_date">Booking Date</label>
                            <input type="text" id="booking_date" value="" class="input">
                        </div>
                    </div>
                    <div class="block block40">
                        <div class="col">
                            <label for="shipper_ref">Shipper's Peference</label>
                            <input type="text" id="shipper_ref" value="" class="input">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="content_desc">Description Of Content <small>(for package service attach invoice for proforma invoice for your letterhead)</small></label>
                        <input type="text" id="content_desc" value="" class="input">
                        <input type="text" id="content_desc2" value="" class="input">
                        <input type="text" id="content_desc3" value="" class="input">
                        <input type="text" id="content_desc4" value="" class="input">
                    </div>
                </div>

                <div class="row row-nop">
                    <div class="block block50">
                        <div class="col">
                            <label for="value_and_currency">Value &amp; Currency <small>(Package Service Only)</small></label>
                            <input type="text" id="value_and_currency" value="" class="input">
                        </div>
                    </div>
                    <div class="block block50">
                        <div class="col">
                            <label for="sample_ncv">Sample of NCV</label>
                            <input type="text" id="sample_ncv" value="" class="input">
                        </div>
                    </div>
                </div>

                <div class="row row-nop">
                    <div class="block block50">
                        <div class="col">
                            <label for="recv">Received By Asian Express</label>
                            <input type="text" id="recv" class="input">
                        </div>
                    </div>
                    <div class="block block50">
                        <div class="col">
                            <label for="sh_date_time">Shipment Date   Time</label>
                            <input id="sh_date_time" type="text" class="input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="row">
                    <div class="col">
                        <label for="rcv_company">Receiver Company Name</label>
                        <input type="text" class="input" id="rcv_company" value="{{ $receiver_company }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="rcv_name">For the Attention Of (NAME / DEPARTMENT)</label>
                        <input type="text" class="input" id="rcv_name" value="{{ $receipt->receiver_attention_to }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="rcv_address">Street Address</label>
                        <input type="text" class="input" id="rcv_address" value="{{ $receipt->receiver_address }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="text" class="input" id="rcv_address2">
                        <input type="text" class="input" id="rcv_address3">
                    </div>
                </div>
                <div class="row">
                    <div class="col col50">
                        <label for="rcv_city">City</label>
                        <input type="text" name="rcv_city" class="input" value="{{ $receipt->receiver_city }}">
                    </div>
                    <div class="col col50">
                        <label for="rcv_country_state">Country / State</label>
                        <input type="text" name="rcv_country_state" class="input" value="{{ $receipt->receiver_state }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col col50">
                        <label for="rcv_country">Country</label>
                        <input type="text" name="rcv_country" class="input" value="{{ $receipt->receiver_country }}">
                    </div>
                    <div class="col col50">
                        <label for="rcv_zip">POST / ZIP CODE</label>
                        <input type="text" name="rcv_zip" class="input" value="{{ $receipt->receiver_zip }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col col50">
                        <label for="rcv_sig">Receiver<br />Signature</label>
                    </div>
                    <div class="col col50">
                        <label for="rcv_phone">Telephone No. / Telex No.</label>
                        <input type="text" name="rcv_phone" class="input" value="{{ $receipt->receiver_phone }}">
                    </div>
                </div>

                <table cellpadding="4" cellspacing="0" width="100%" border="0" class="tmain">
                    <tr>
                        <th class="bg" colspan="4" align="left">PRINT NAME</th>
                        <th class="bg">Date</th>
                        <th class="bg">Time</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="br1 bg bb" align="left" valign="top" height="100">Billing Desc</th>
                        <th rowspan="2" class="bt1 bl1 br bb" align="center" valign="top">Product<br>Code </th>
                        <th class="bt1 br bb"></th>
                        <th class="bb br bt1" align="center">ITEMS</th>
                        <th colspan="2" class="bb bt1" align="center">KILOS - WEIGHT - GRAMS</th>
                    </tr>
                    <tr>
                        <th class="bb br" align="center" valign="top">SHIPPER<br>COUNT<br><br></th>
                        <th class="bb br"><input type="text" value="{{ $receipt->items }}" style="width: 50px;"></th>
                        <th class="bb" colspan="2"><input type="text" value="{{ $receipt->kilos }}"></th>
                    </tr>
                    <tr>
                        <th class="bb" height="74">DOCUMENT<br><br><br> </th>
                        <th colspan="5" rowspan="2" class="bl" valign="top"><textarea rows="10" style="width: 100%; height: 100%; min-height:143px;">VIA UK UPS To USA Please do not change our packing.</textarea></th>
                    </tr>
                    <tr>
                        <th height="77">PACKAGE<br><small>(NON DOCUMENT)</small><br><br>  ***** </th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
