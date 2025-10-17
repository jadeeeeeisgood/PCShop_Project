<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
</head>

<body>
    <h1>Thank you for your order!</h1>
    <p>Order ID: #{{ $order->id }}</p>
    <p>Total: {{ number_format($order->total, 0, ',', '.') }} VNĐ</p>
    <p>Status: {{ $order->status }}</p>
    <p>Payment Method: {{ $order->payment_method }}</p>

    <h2>Order Items:</h2>
    <ul>
        @foreach($order->orderItems as $item)
            <li>{{ $item->product->name }} - Quantity: {{ $item->quantity }} - Price:
                {{ number_format($item->price, 0, ',', '.') }} VNĐ</li>
        @endforeach
    </ul>

    <h2>Shipping Address:</h2>
    <p>{{ $order->customer_name }}</p>
    <p>{{ $order->customer_address }}</p>
    <p>{{ $order->customer_email }}</p>

    <p>We will process your order soon!</p>
</body>

</html>