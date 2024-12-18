
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Print Packing List</title>
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
	<!-- <script type="text/javascript">
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
	</script> -->
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
			color: #fd550a;
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
    							<h3 style="color: #000;">Postal Address: </h3>
    							<p contenteditable="true" >P.O Box 2457 GPO, Sialkot (51310) Pakistan. <br>
    							Tel: +92-300-6464270, Fax: +92-52-6618150
    							</p>
    						</div>
    						<div class="party">
    							<h3 contenteditable="true">PACKING LIST: <span style="font-size:15px;;" class="editable">{{ $packagingList->customer->full_name }}</span></h3>
    							<div contenteditable="true" style="font-weight:bold; width:100%; overflow-wrap: break-word;" class="editable">
                                    SHIP TO: {{ $packagingList->customer->address }} {{ $packagingList->customer->city }}
                                </div>
                                <div contenteditable="true" class="editable" style="width:100%; overflow-wrap: break-word;" rows="3">
                                    32462  Florida , US
                                </div>
    							<strong contenteditable="true">Phone: {{ $packagingList->customer->phone }}</strong><span></span>
    						</div>
    					</td>
    					<td align="center"  style="padding:0px 0 0 0;">
                            
                            
                            
                            <img width="334" height="186" style="object-fit:contain;" src="{{ asset('images/final_logo.png') }}" alt="">
                            
    					</td>
    					<td width="33.33%" style="padding:13px 0 0 0;">
    						<div class="head-text" style="text-align:right" contenteditable="true">
    							<h3 style="color: #000;">Factory Address: </h3>
    							<p  >New Abadi Sohawa, Circular Road <br> Daska 51010, Dist. Sialkot - Pakistan <br>
    							</p>
    						</div>
    						<div class="party" style="padding:0px 0 0 0; overflow:hidden;">
									<div style="display: flex;">
										<div contenteditable="true" style="font-weight:bold; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; border-right:1px solid #000; width: 140px;" class="editable"><strong>DATE:</strong> {{ \Carbon\Carbon::parse($packagingList->date)->format('Y-m-d') }}</div>
										<div contenteditable="true" style="font-weight:normal; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; width:170px;" class="editable"><strong>TERM:</strong> Payment terms</div>
									</div>
									<div style="display: flex;">
                                    	<div contenteditable="true" class="editable" id="" style="width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; border-right:1px solid #000; width: 140px;" rows="3"><strong>Invoice:</strong> {{ $packagingList->invoice_no }}</div>
										<div contenteditable="true" style="font-weight:normal; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; width:170px;" class="editable"><strong>PO:</strong>PO-123</div>
									</div>
									<div style="display: flex;">
                                    	<div contenteditable="true" class="editable" id="" style="width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; border-right:1px solid #000; width: 140px;" rows="3"><strong>NTN No:</strong> 2130732-6</div>
										<div contenteditable="true" style="font-weight:normal; width:-webkit-fill-available; padding:8px; border-bottom:1px solid #000; width:170px;" class="editable"><strong>HS CODE:</strong>9022-2100</div>
									</div>
									<div style="display: flex;">
                                    	<div contenteditable="true" class="editable" id="" style="width:-webkit-fill-available; padding:8px;" ><strong>Financial Instrument:</strong>HBL-EXP-088620-29052024 </div>
										

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
                					<td width="25%"><strong>DATE: </strong> <input type="text" value="2022-02-16" style="border:none; padding:0px; margin:0px; font-size: 14px; width:191px;"></td>
                					<td width="25%"><strong>INVOICE: </strong><input type="text" class="editable" value="VES-1936"></td>
                					<td width="25%"><strong>NTN No.: </strong>2130732-6</td>
                					<td width="25%"><strong>HS CODE:</strong> <input type="text" value="9022-2100" style="border:none; padding:0px; margin:0px; font-size: 14px;"></td>
                				</tr> -->
                				<tr>
                					<!-- <td width="25%" contenteditable="true"><strong>TERM: </strong><br />Payment terms</td> -->
                					<!-- <td width="33.10%"><strong>BANK: </strong><br />Habib Bank Limited</td> -->
                                    
                                    <td width="25.00%" contenteditable="true"><strong>Port of Loading: </strong> {{ $packagingList->port_of_landing }}</td>
                                    <td width="25.00%" contenteditable="true"><strong>Port of Discharge: </strong> {{ $packagingList->port_of_discharge }}</td>
                                    <!-- <td width="33.40%"><strong>P.O No:</strong><br /><input type="text" value="PO-123" style="border:none; padding:0px; margin:0px; font-size: 14px;"></td> -->
                				</tr>
                			</table>
    				    </td>
    	        </tr>
    				<tr>
    				    <td colspan="3">
    				        <table class="table new_one" width="100%" border="1" cellpadding="4" style="background-color: #fff; border-top:0;">
                                <thead>
                                    <tr>
                                        <th width="30">SR #</th>
                                        <th width="50">Ref #</th>
                                        <th width="420">Product</th>
                                        <th width="120">Size</th>
                                        <th width="50">Total Qty</th>
                                        <th width="1">Cartons</th>
                                    </tr>
                                </thead>

                    			<tbody>
									@foreach($packagingList->boxes as $key=>$box)
                                    <tr id="row_{{$key+1}}" class="item">
                                        
                                        <td contenteditable="true">{{$key+1}}</td>
                                        <td><input class="item-name" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="N/A" /></td>
                                        <td><input type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $box->details }}" /></td>
                                        <td><input class="update qty" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $box->size_qty }}" /></td>
                                        <td><input class="update rate" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $box->total_qty }}" /></td>
                                        <td><input class="update discount" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%" value="{{ $box->cartons }}" /></td>

                                    </tr>
									@endforeach
                                    
                    				<tr>
                    					<td colspan="4" align="right"><strong>Total: </strong></td>
                    					<td><strong><input id="total_qty" type="text" style="border:none; padding:0px; margin:0px; font-size: 14px; width:100%; font-weight:bold;" value="{{ $packagingList->boxes->sum('qty_cartons') }} PCs"></strong></td>
                                        <td colspan="4" align="left"><strong>{{ $packagingList->boxes->sum('cartons') }} Cartons</strong></td>
                    			</tbody>
                    		</table>
    				    </td>
    	        </tr>

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

                                        <div class="container"><img style="position: absolute"  height="120" id="dragable" src="https://app.vesvacuuminternational.com/static/stamp2.png" alt=""></div>
                    
        		</tfoot>
			</table>
		</div>
	</div>
	<button style="width: 100%; padding: 10px;" onclick="window.print();" class="noprint">Print</button>


</body>
</html>
