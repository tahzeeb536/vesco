
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Print Invoice</title>
	<style type="text/css">
		@media print{
			.noprint{
				display: none !important;
			}
		}
	</style>
	<link href='https://fonts.googleapis.com/css?family=Rubik:400,500,700' rel='stylesheet' type='text/css'>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript">
		function getFormattedCurrency(num) {
		    num = num.toFixed(2)
		    var cents = (num - Math.floor(num)).toFixed(2);
		    return Math.floor(num).toLocaleString() + '.' + cents.split('.')[1];
		}

        function hideCompanyInfo() {
        var companyInfoContainer = document.getElementById("companyInfoContainer");
        companyInfoContainer.style.display = "none";
    }

		function updateNow(){
			var total_amount = 0;
				var total_qty = 0;
				var currency = 'USD'; // Placeholder for currency
                var freight_amount = ($("#freight_amount").val()).replace(' ' + currency,'');
                var tax_amount = ($("#tax_amount").val()).replace(' ' + currency,'');


				$("tr.item").each(function(){
					var qty = $(this).find('.qty');
					var rate = $(this).find('.rate');
					var discount = $(this).find('.discount').val().replace('%','');
					var total = $(this).find('.total');

					var c_total = rate.val() * qty.val();
                    console.log(qty, rate, total);
					total.val( getFormattedCurrency( c_total - (c_total*discount/100) ) );
					total_amount += ( (c_total - (c_total*discount/100) ) );
					total_qty += parseInt(qty.val());
				});

				$("#total_amount").val( getFormattedCurrency(total_amount)+' '+currency );
				$("#total_qty").val(total_qty);

				$("#final_amount").val( getFormattedCurrency((parseFloat(total_amount) + parseFloat(freight_amount) + parseFloat(tax_amount))) + ' '+currency );
		}

		$(document).ready(function(){

            $('#dragable').draggable();

			$(".btnDelete").click(function(e) {
                var id = $(this).attr("data-id");
                $("#row_" + id).remove();
                updateNow(); // Call a function to update the total sum
            });

			/// Update
			$(".update").keyup(function(){
				updateNow();
			});

		});
	</script>
	<style type="text/css">
		body{
			font-size: 14px;
			font-family: Arial, sans-serif;
			margin: 0px;
			padding: 0px;
		}

		input, textarea{
			font-family: Arial, sans-serif;
		}

		.container{
			width: 1000px;
			margin: 0px auto;
			position: relative;
		}

		p{
			line-height: 17px;
		}
		p strong{
			font-weight: 800
		}
		.content-block{
			margin-bottom: 100px;
		}
		table.table{
			border-color: #000;
			border-collapse: collapse;
		}
		table.table thead th{
			text-align: left
		}
		.head-text{

		}
		.head-text h3{
			color: #000;
			font-weight: bold;
			padding: 0px;
			margin: 0px;
			text-decoration: underline;
		}
		.head-text p{
			padding: 0px;
			margin: 10px 0px;
			line-height: 20px;
		}
		.party{
			border:1px solid #000;
			border-bottom-width: 0px;
			padding: 7px 10px;
			margin-top: 40px;
			min-height: 126px;
		}
		.party h3{
			padding: 0px;
			margin: 0px 0px 10px 0px;
			font-size: 15px;
		}
		.editable{
			border:none;
			outline: none;
			display: inline-block;
			padding: 0px;
			margin: 0px;
			font-size: 14px;
			font-family: Arial, sans-serif;
		}

		div#footer_wrapper {
			position: fixed;
			bottom: 0;
			width: 100%;
			border-top: 1px solid black; /* for demo */
			background: yellow;
		}

		@media print {
			tfoot {
				display: table-footer-group;
				position: relative;
				bottom: 3;
			}
			#footer_content {
				position: fixed;
				bottom: 0px;
			}
			#dragable{
				position: absolute;
				right:0px;
				bottom: 230px;
				z-index: 99;
			}
			div#footer_content {
				font-weight: bold;
				text-align: center;
				width: 100%;
			}
		}

	</style>
</head>
<body>

	<div class="container" style="padding: 0 10px;" >
		<!-- <img  height="120" id="dragable" src="path/to/your/image.png" alt=""> -->

		<!-- HEADER -->
		<div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			    <thead></thead>
							    <tbody>

			        <tr>
			         <td colspan="3">
			        <table width="100%" border="0" style="border-collapse:collapse" cellpadding="3" cellspacing="0">

                        <tr>
    					<td width="33.33%" style="padding:3px 0 0 0;">
    						<div class="head-text">
    							<h3>Postal Address : </h3>
    							<p contenteditable="true" >P.O Box 2457 GPO, Sialkot (51310) Pakistan. <br>
    							Tel : +92-300-6464270, Fax : +92-52-6618150
    							</p>
    						</div>
    						<div class="party">
    							<h3 contenteditable="true">PERFORMA INVOICE : <span style="font-size:15px;;" class="editable">{{ $saleInvoice->customer->full_name }}</span></h3>
    							<div contenteditable="true" style="width:100%; overflow-wrap: break-word;" class="editable">
                                  <b>  SHIP TO : </b> {{ $saleInvoice->customer->address}} <br>
                                </div>
                                <div contenteditable="true" class="editable" style="width:100%; overflow-wrap: break-word;" rows="3">
									{{ $saleInvoice->customer->post_code }} {{ $saleInvoice->customer->city }}, {{ $saleInvoice->customer->state }}, {{ $saleInvoice->customer->country }}
                                </div>

    							<strong contenteditable="true" class="editable">Phone : </strong> <span contenteditable="true">{{ $saleInvoice->customer->phone }}</span>
    						</div>
    					</td>
    					<td align="center"  style="padding:0px 0 0 0;">
							<img style="width: 200px; height: auto; object-fit:contain;" src="{{ asset('images/final_logo.png') }}" alt="">
							<img src="path/to/your/barcode.png" alt="">
    					</td>
    					<td width="33.33%" style="padding:13px 0 0 0;">
    						<div class="head-text" style="text-align:right" contenteditable="true">
    							<h3>Factory Address : </h3>
    							<p  >New Abadi Sohawa, Circular Road <br> Daska 51010, Dist. Sialkot - Pakistan <br>
    							</p>
    						</div>
    						<div class="party" style="padding:0px 0 0 0; overflow:hidden;">
									<div style="display: flex;">
										<div contenteditable="true" style="font-weight:bold; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; border-right:1px solid #000; width: 140px;" class="editable"><strong>DATE : </strong> {{ \Carbon\Carbon::parse($saleInvoice->invoice_date)->format('Y-m-d') }}</div>
										<div contenteditable="true" style="font-weight:normal; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; width:170px;" class="editable"><strong>TERM : </strong> {{ $saleInvoice->term }}</div>
									</div>
									<div style="display: flex;">
                                    	<div contenteditable="true" class="editable" id="" style="width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; border-right:1px solid #000; width: 140px;" rows="3"><strong>Invoice : </strong> {{ $saleInvoice->invoice_number }}</div>
										<div contenteditable="true" style="font-weight:normal; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; width:170px;" class="editable"><strong>P.O : </strong>{{ (!empty($saleInvoice->po_no)) ? $saleInvoice->po_no : 'N/A' }}</div>
									</div>
									<div style="display: flex;">
                                    	<div contenteditable="true" class="editable" id="" style="width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; border-right:1px solid #000; width: 140px;" rows="3"><strong>NTN No : </strong> 2130732-6</div>
										<div contenteditable="true" style="font-weight:normal; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; width:170px;" class="editable"><strong>BANK : </strong>{{ $saleInvoice->bank_name }}</div>
									</div>
									<div style="display: flex;">
                                    	<div contenteditable="true" class="editable" id="" style="width:-webkit-fill-available; padding:8px; border-right:1px solid #000; width: 140px;" rows="3"><strong>HS CODE : </strong> {{ $saleInvoice->hs_code }}</div>
										<div contenteditable="true" style="font-weight:normal; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; width:170px;" class="editable">
                                            <strong>Shipping: </strong>{{ $saleInvoice->shipping }}
                                        </div>

									</div>
    						</div>
    					</td>
    				</tr>

    				</table>

    				</td>
                    </tr>


    				<tr>
    				<tr>
    				    <td colspan="3">
    				        <table width="100%" border="1" style="border-collapse:collapse; border-color:#000;" cellpadding="5" cellspacing="0">
                				<!-- <tr>
                					<td width="25%"><strong>DATE: </strong> <input type="text" value="2019-01-02" style="border:none; padding:0px; margin:0px; font-size: 14px; width:191px;"></td>
                					<td width="25%"><strong>INVOICE: </strong><input type="text" class="editable" value="VES-1323"></td>
                					<td width="25%"><strong>NTN No.: </strong>2130732-6</td>
                					<td width="25%"><strong>HS CODE: </strong> <input type="text" value="9022-2100" style="border:none; padding:0px; margin:0px; font-size: 14px;"></td>
                				</tr> -->
                				<tr>
                					<!-- <td width="25%" contenteditable="true"><strong>TERM: </strong><br />Payment terms</td> -->
                					<!-- <td width="33.10%"><strong>BANK: </strong><br />Habib Bank Limited</td> -->
                					<td width="50.00%" contenteditable="true"><strong>FINANCIAL INSTRUMENT NO : </strong>{{ $saleInvoice->financial_instrument_no }}</td>
                                    <td width="25.00%" contenteditable="true"><strong>Port of Loading : </strong>{{ $saleInvoice->port_of_loading }}</td>
                                    <td width="25.00%" contenteditable="true"><strong>Port of Discharge : </strong>{{ $saleInvoice->port_of_discharge }}</td>
                					<!-- <td width="33.40%"><strong>P.O No: </strong><br /><input type="text" value="PO-123" style="border:none; padding:0px; margin:0px; font-size: 14px;"></td> -->
                				</tr>
                			</table>
    				    </td>
    	</tr>
    				<tr>
    				    <td colspan="3">
    				        <table class="table new_one" width="100%" border="1" cellpadding="4" style="background-color: #fff; border-top:0;">
                    			<thead>
                    				<tr>
                    					<th width="30">SR#</th>
                    					<th width="60">Ref#</th>
                    					<th>Product</th>
                    					<th width="100">Size</th>
                    					<th width="40">Qty</th>
                    					<th width="75">Unit Price</th>
                    					<th width="75">Discount</th>
                    					<th width="100">Total</th>
                    					<th class="noprint" width="50">Delete</th>
                    				</tr>
                    			</thead>
                    			<tbody>
                                    @foreach($saleInvoice->items as $key=>$item)
                                    <tr id="row_{{$key+1}}" class="item">
                                        <td contenteditable="true">{{ $key+1 }}</td>
                                        <td contenteditable="true">{{ $item->article_number }}</td>
                                        <td><input class="item-name" data-id="1" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $item->product_name }}" /></td>
                                        <td><input type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%"  value="{{ $item->size }}" /></td>
                                        <td><input class="update qty" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $item->quantity }}" /></td>
                                        <td><input class="update rate" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $item->unit_price }}" /></td>
                                        <td><input class="update discount" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $item->discount }}%" /></td>
                                        <td><input class="update total" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $item->total_price }}" /></td>
                                        <td class="noprint"><button class="btnDelete" data-id="{{ $key + 1 }}">X</button></td>
                                    </tr>
                                    @endforeach
                                    
                    				<tr>
                    					<td colspan="5" align="right"><strong>Total: </strong></td>
                    					<td colspan="2"><strong><input id="total_qty" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%; font-weight:bold;" value="{{ $saleInvoice->items->sum('unit_price') }}"></strong></td>
                    					<td><strong><input id="total_amount" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%; font-weight:bold" value="{{ $saleInvoice->items->sum('total_price') }}"></strong></td>
                    				</tr>
                    				<tr>
                    					<td colspan="5" rowspan="3" style="border:none">

                    						<div id="companyInfoContainer"
                                            style="border:0px; width:100%; height:90%; overflow:hidden" contenteditable="true">
                                            COMPANY BANK INFORMATION:-<br />Bank Name: Allied Bank Mian Branch Sialkot Branch Code: (0378) Swift Code: ABPAPKKAXXX <br />Account Title: Ves Vacuum International Account # 0378-001-000003452-003-4 <br />IBAN : PK61ABPA0010000034520034 Company Address: St #2 New abadui Sohawa Daska. Disst. Sialkot Pakistan
                                            <button class="btnDelete noprint" onclick="hideCompanyInfo()">X</button>
                                        </div>

                    					</td>
                    					<td colspan="2"><strong>Freight Charges : </strong></td>
                    					<td><input id="freight_amount" class="update" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $saleInvoice->freight_charges }} {{ $saleInvoice->customer->currency ?? 'USD' }}"></td>
                    				</tr>
                    				<tr>
                    					<td colspan="2"><strong>Bank Charges : </strong></td>
                    					<td>        <input id="tax_amount" class="update" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="0.00  {{ $saleInvoice->customer->currency ?? 'USD' }}">
                                        </td>
                    				</tr>
                    				<tr>
                    					<td colspan="2"><strong>Grand Total : </strong></td>
                    					<td><input id="final_amount" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $saleInvoice->items->sum('total_price') + $saleInvoice->tax_charges + $saleInvoice->freight_charges }}  {{ $saleInvoice->customer->currency ?? 'USD' }}"></td>
                    				</tr>


                    			</tbody>
                    		</table>
    				    </td>
    	        </tr>

    			@if($saleInvoice->payments->count())
					<tr>
						<td colspan="3">
							<table align="center" width="1000" border="0" cellpadding="0" class="content-block">
								<tbody>
									<tr class="new_class">
										<td>
											<h3>Payments</h3>
											<table class="table" width="100%" border="1" cellpadding="5">
												<thead>
													<tr>
														<th width="100">Trans ID.</th>
														<th width="100">Date</th>
														<th>Details</th>
														<th width="100">Amount</th>
													</tr>
												</thead>
												<tbody>
													@foreach($saleInvoice->payments as $payment)
													<tr>
														<td>{{ $payment->id }}</td>
														<td>{{ $payment->date }}</td>
														<td><input type="text" style="border:none; padding:0px; margin:0px; font-size: 14px;" value=""></td>
														<td>{{ $payment->amount }}</td>
													</tr>
													@endforeach
													<tr>
														<td colspan="3" align="right"><strong>Total Payments: </strong></td>
														<td><strong>{{ $saleInvoice->payments->sum('amount') }}</strong></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				@endif
	
			    </tbody>
				
				<tfoot>
        	        <div id="footer_wrapper">
        	            <tr align="center" height="100">
                            <td class="footer_content" align="center" height="100">
                                <div id="footer_content" style="padding: 0 0 10px 0;">
                                    <img src="{{ asset('images/logo-for-invoice.png') }}" alt="no image preview" width="600" height="">
                                </div>
                            </td>

        	            </tr>
                    </div>

                    
        		</tfoot>
			</table>
		</div>
	</div>
	<button style="width: 100%; padding: 10px;" onclick="window.print();" class="noprint">Print</button>


</body>
</html>
