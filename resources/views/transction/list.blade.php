<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Payment Intent Id</th>
            <th scope="col">Amount</th>
            <th scope="col">Last 4 Degit</th>
            <th scope="col">Product</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($transctions as $transaction)
        <tr>
            <td> {{ $loop->index + 1 }} </td>
            <td> {{ $transaction->payment_intent_id }}</td>
            <td> ${{ number_format(($transaction->amount / 100), 2, '.', ',') }} </td>
            <td> {{ $transaction->method->last4degit }} </td>
            <td> {{ $transaction->product_name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>