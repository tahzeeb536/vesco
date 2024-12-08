<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order #{{ $purchaseOrder->purchase_order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header, .footer {
            text-align: center;
        }
        .header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 20px;
        }
        .content {
            margin-top: 20px;
        }
        .details {
            width: 100%;
            margin-bottom: 20px;
        }
        .details th, .details td {
            text-align: left;
            padding: 5px;
        }
        .details th {
            background: #f0f0f0;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .items th {
            background: #f0f0f0;
        }
        .total {
            text-align: right;
            padding: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Purchase Order</h1>
            <h3>Order #: {{ $purchaseOrder->purchase_order_number }}</h3>
        </div>
        <div class="content">
            <table class="details">
                <tr>
                    <th>Vendor:</th>
                    <td>{{ $purchaseOrder->vendor->full_name }}</td>
                </tr>
                <tr>
                    <th>Order Date:</th>
                    <td>{{ $purchaseOrder->order_date->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>Delivery Date:</th>
                    <td>{{ $purchaseOrder->delivery_date ? $purchaseOrder->delivery_date->format('Y-m-d') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>{{ ucfirst($purchaseOrder->status) }}</td>
                </tr>
                <tr>
                    <th>Note:</th>
                    <td>{{ $purchaseOrder->note }}</td>
                </tr>
            </table>

            <h3>Order Items</h3>
            <table class="items">
                <thead>
                    <tr>
                        <th>Variant</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->items as $item)
                        <tr>
                            <td>{{ $item->variant->product->name }} - {{ $item->variant->size->name }} - {{ $item->variant->color->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                Total Amount: {{ number_format($purchaseOrder->total_amount, 2) }}
            </div>
        </div>
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
