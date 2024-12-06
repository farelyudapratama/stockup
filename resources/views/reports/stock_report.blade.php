<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Jumlah Masuk</th>
            <th>Jumlah Keluar</th>
            <th>Periode</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stockMovements as $movement)
            <tr>
                <td>{{ $movement->product->name }}</td>
                <td>{{ $movement->type == 'Masuk' ? $movement->quantity : 0 }}</td>
                <td>{{ $movement->type == 'Keluar' ? $movement->quantity : 0 }}</td>
                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>