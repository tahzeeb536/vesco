<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
        }
        .print-btn {
            background-color: #0ea5e9;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .print-btn:hover {
            opacity: .9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        th {
            font-weight: bold;
            background-color: #f2f2f2;
            text-transform: uppercase;
        }
        td {
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Stock Report</h1>
            @if ($records->isNotEmpty())
                <button class="print-btn" onclick="downloadPdf()">Print</button>
            @endif
        </div>
        @if ($records->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Article Number</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Category</th>
                        <th>Shelf</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $record)
                        <tr>
                            <td>{{ optional($record->productVariant->product)->name }}</td>
                            <td>{{ optional($record->productVariant->product)->article_number }}</td>
                            <td>{{ optional($record->productVariant->size)->name ?? 'N/A' }}</td>
                            <td>{{ optional($record->productVariant->color)->name ?? 'N/A' }}</td>
                            <td>{{ optional($record->productVariant->product->category)->name ?? 'N/A' }}</td>
                            <td>{{ $record->getFirstShelfNameByVariant($record->productVariant->id) }}</td>
                            <td>{{ $record->total_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        @else
            <div style="text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                No records found.
            </div>
        @endif
    </div>
    <script>
        function downloadPdf() {
            const urlParams = new URLSearchParams(window.location.search);
            const baseRoute = "{{ route('stock.report.pdf') }}";
            const fullUrl = `${baseRoute}?${urlParams.toString()}`;
            window.location.href = fullUrl;
        }
    </script>
</body>
</html>
