
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Letter Head</title>
    <link href="https://fonts.googleapis.com/css?family=PT+Serif|Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#dragable').draggable();
        });
    </script>
    <style type="text/css">
        body {
            font-family: 'Times New Roman', sans-serif;
            font-size: 13pt;
        }
        #canvas {
            /* width: 8.3in; */
            height: 11.7in;
            background-color: #fff;
            position: relative;
        }
        #header {
            margin-bottom: 0px;
            padding: 10px;
            padding-bottom: 0px;
        }
        #header .logo {
            width: 100%;
        }
        .address {
            font-family: 'Times New Roman', sans-serif;
            font-size: 10pt;
        }
        .address h2 {
            margin: 0px 0px 0px 0px;
            padding: 0px;
            color: #fd550a;
            font-size: 18px;
        }
        .address p {
            margin: 0px;
            padding: 0px;
        }
        #foot {
            width: 95%;
            /* height: 0.75in; */
            position: absolute;
            text-align: center;
            bottom: 0;
        }
        #foot img {
            /* width: 4.25in; */
            height: 100%;
        }
        .date span, .ref span {
            text-decoration: underline;
        }
        @media print {
            body {
                padding: 0px;
                margin: 0px;
            }
            #canvas {
                width: 100% !important;
            }
            .noprint {
                display: none !important;
            }
        }
        #content {
            padding: 10px 50px;
        }
        @media screen {
            body {
                background-color: #ccc;
            }
            #canvas {
                box-shadow: 0px 3px 5px rgba(0,0,0,.3);
                margin: auto;
            }
        }
        #dragable {
            position: absolute;
            left: 0px;
            top: 0px;
            z-index: 999999;
        }
    </style>
</head>
<body>

    <div id="canvas">

        <div id="header">
            <table cellspacing="0" cellpadding="0" width="100%" border="0">
                <tr>
                    <td class="address" valign="top" width="37%">
                        <br />
                        <h2 style="color:#000;">Postal Address</h2>
                        <p>P.O Box 2457 GPO, <br />Sialkot (51310) Pakistan. <br /> <strong>Tel</strong>: +92-300-6464270, <br /><strong>Fax</strong>: +92-52-6618150</p>
                    </td>
                    <td width="26%" align="center">
                        <img width="340" height="210" style="object-fit:contain;" src="{{ asset('images/final_logo.png') }}" alt="">
                    </td>
                    <td class="address" valign="top" align="right" width="37%" style="
                    background-image: url('/accounts/dashboard/barcode/?text=CEOINT0831');
                    background-position: bottom right;
                    background-repeat: no-repeat;
                    ">
                        <br />
                        <h2  style="color:#000;">Factory Address</h2>
                        <p>New Abadi Sohawa, Circular Road <br />
                        Daska 51010, Dist. Sialkot - Pakistan</p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="meta">
            <table cellpadding="15" cellspacing="0" width="100%" border="0">
                <tr>
                    <td width="50%" class="date"><strong>Date: </strong><span>{{ \Carbon\Carbon::parse($letterHead->date)->format('l d M, Y') }}</span></td>
                    <td width="50%" align="right" class="ref">
                        <strong>Ref#: </strong><span>{{ $letterHead->ref_no }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div id="content">
            <div class="content-inner" style="padding:10px 37px;">
                <p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{{ $letterHead->title }}</strong></p>
                {!! $letterHead->content !!}
            </div>
            <div id="foot"><img src="{{ asset('images/logo-for-invoice.png') }}" alt="no image preview" width="600" height="">
            </div>
                        <img height="120" id="dragable" src="{{ asset('images/ves_stamp_transparent.png') }}" alt="">
                    </div>
    </div>
    <button onclick="window.print();" class="noprint" style="width:100%; padding: 10px;">Print</button>
</body>
</html>
