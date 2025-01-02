<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembelian</title>
    <style>
        /* Style untuk PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: 700px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #004085;
        }

        .header p {
            font-size: 14px;
            color: #6c757d;
        }

        .details {
            margin-top: 20px;
        }

        .details .info {
            margin-bottom: 15px;
        }

        .details .info span {
            font-weight: bold;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f8f9fa;
            color: #333;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detail Pembelian</h1>
            <p>ID Pembelian: {{ $purchase->id }}</p>
        </div>

        <div class="details">
            <div class="info">
                <span>Nama Pemasok:</span> {{ $purchase->vendor->name }}
            </div>
            <div class="info">
                <span>Tanggal Pembelian:</span> {{ $purchase->purchase_date->format('d M Y') }}
            </div>
            <div class="info">
                <span>Total Pembelian:</span> Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Kuantitas</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }} Unit</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total Pembelian: Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
        </div>
    </div>
</body>
</html>