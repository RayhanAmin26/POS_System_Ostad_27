<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>
    <h2>Invoice #{{ $invoice->id }}</h2>
    <p><strong>Customer:</strong> {{ $invoice->customer->name }}</p>
    <p><strong>Date:</strong> {{ $invoice->created_at->format('Y-m-d') }}</p>
    <hr>
    <p><strong>Items:</strong></p>
    @if($invoice->items)
        <ul>
            @foreach($invoice->items as $item)
                <li>{{ $item['name'] }} x {{ $item['quantity'] }} — {{ number_format($item['price'],2) }}</li>
            @endforeach
        </ul>
    @endif
    <hr>
    <p><strong>Total:</strong> {{ number_format($invoice->total,2) }}</p>
</body>
</html>
