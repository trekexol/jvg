<table>
    <thead>
    <tr>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->amount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>