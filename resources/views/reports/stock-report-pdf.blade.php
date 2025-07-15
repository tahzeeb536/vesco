<!DOCTYPE html>
<html>
<head>
    <title>Stock Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            width: 100%;
            max-width: 794px;
            margin: 0 auto;
            box-sizing: border-box;
        }
        h1 {
            text-align: left;
            font-size: 16px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .table-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            font-size: 12px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stock Report</h1>
        <div class="table-wrapper">
            @if($records->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Article Number</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Room</th>
                            <th>Rack</th>
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
                                <td>
                                    @php
                                        $rack = $room = 'N/A';
                                        $shelfId = $record->getFirstShelfIdByVariant($record->productVariant->id);
                                        $shelf = \App\Models\Shelf::find($shelfId);
                                        if($shelf) {
                                            $rackData = $shelf->rack;
                                            $rack = $rackData?->name;
                                            $roomData = $rackData->room;
                                            $room = $roomData?->name;
                                        } 
                                    @endphp
                                    {{ $room }}
                                </td>
                                <td>{{ $rack }}</td>
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
    </div>
</body>
</html>
