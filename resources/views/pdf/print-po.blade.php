<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Print Purchase Order</title>
	<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,500,700' rel='stylesheet' type='text/css'>
	<style type="text/css">
		body{
			font-size: 15px;
			font-family:'Ubuntu', Arial, sans-serif;
			margin: 0px;
			padding: 0px;
		}
		p{
			line-height: 17px;
		}
		p strong{
			font-weight: 800
		}
		table.table{
			border-color: #000;
			border-collapse: collapse;
		}
		table.table thead th{
			text-align: left
		}
        @media print{
			.noprint{
				display: none !important;
			}
		}
	</style>
</head>
<body>
	<table align="center" width="100%" border="0" cellpadding="5">
		<tr>
			<td colspan="2" align="left" style="border-bottom:2px solid #000">
                				<img height="60" src="https://app.vesvacuuminternational.com/static/img/ilogo.png" alt="">
                			</td>
		</tr>
		<tr>
			<td width="50%" style="border-bottom:1px dashed #000">
				<p>
				<strong>Address: </strong> <br>Circular Road New Abadi Sohawa <br>Daska 51010 Sialkot Pakistan. <br>
				<strong>Phone:</strong> 052 6618383 <br>
				<strong>Fax:</strong> 052 6618150 <br>
				<strong>Mob:</strong> 03006464270 <br>
				<strong>Email: </strong> blind.daska@gmail.com
				</p>
			</td>
			<td width="50%" valign="bottom" style="border-bottom:1px dashed #000">
				<h1 style="margin:0px 0px 5px 0px; padding:0px;">PURCHASE ORDER</h1>
				<p>
					<strong>Dated as : </strong> {{ $purchaseOrder->order_date }}<br>
					<strong>P.O Number: {{ $purchaseOrder->purchase_order_number }}</strong>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<h2 style="margin:0px 0px 5px 0px; padding:0px;">Shipped to:</h2>
				<p>
				<strong>IMRAN & Sons Corporation</strong> <br>
				<strong>Address: </strong> <br>Circular Road New Abadi Sohawa <br>Daska 51010 Sialkot Pakistan. <br>
				<strong>Phone:</strong> 052 6618383 <br>
				<strong>Fax:</strong> 052 6618150 <br>
				<strong>Mob:</strong> 03006464270
				</p>
			</td>
			<td>
				<h2 style="margin:0px 0px 5px 0px; padding:0px;">Shipped from:</h2>
				<p>
				<strong>Organization:</strong> {{ $purchaseOrder->vendor->organization }} <br>
				<strong>Contact Person:</strong> {{ $purchaseOrder->vendor->full_name }}<br>
				<strong>Address: </strong><br> {{ $purchaseOrder->vendor->address }} <br>
				<strong>Phone:</strong> {{ $purchaseOrder->vendor->phone }} <br>
				<strong>EMail: </strong> {{ $purchaseOrder->vendor->email }}
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
                <table class="table new_one" width="100%" border="1" cellpadding="4" style="background-color: #fff; border-top:1;">
					<thead>
						<tr>
							<th width="50">Sr#</th>
							<th>Description</th>
							<th width="100">Quantity</th>
							<th width="100">Unit Price</th>
							<th width="100">Total Price</th>
						</tr>
					</thead>
					<tbody>

						@foreach($purchaseOrder->items as $key => $item)
						<tr id="row_{{ $key + 1 }}" class="item">
							<td>{{ $key + 1 }}</td>
							<td>{{ $item->variant->name }}</td>
							<td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit_price }}</td>
                            <td  class="total">{{ $item->total_price}}</td>
                        </tr>
						@endforeach                          

                        <tr>
							<td colspan="3"></td>
							<td align="right"><strong>Total</strong></td>
                            <td class="grand-total"><strong>{{ $purchaseOrder->total_amount }}</strong></td>
						</tr>
                    </tbody>
                </table>
			</td>
		</tr>
                
        
		<tr>
			<td colspan="2">
				<h2 style="margin:0px 0px 5px 0px; padding:0px;">Additional Notes:</h2>
				<p>{{ $purchaseOrder->note }}</p>
			</td>
		</tr>
		<tr>
			<td height="100" valign="bottom" colspan="2"><strong>This is a computer generated purchase order and needs no signature or stamp.</strong></td>
		</tr>
		<tr>
			<td colspan="2" height="20"></td>
		</tr>
	</table>
	<button onclick="window.print();" style="width:100%; padding: 10px" class="noprint">Print</button>

</body>
</html>