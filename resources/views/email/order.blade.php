<!doctype html>
<html lang="en">
<head>
    <title>Order Email</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="p-2">
    @if ($mailData['userType'] == "customer")
    <h1 class="text-success text-bold text-center">Thanks for your Order!</h1>
    <h4 class="text-center text-primary">Your Order Id is: #{{ $mailData['order']->id }}</h4>
    @else 
    <h1 class="text-success text-bold text-center">You have received an Order!</h1>
    <h4 class="text-center text-primary">Order Id is: #{{ $mailData['order']->id }}</h4> 
    @endif
    
    <h6>Shipping Address</h6>
    <address>
        <strong>{{ $mailData['order']->first_name. ' ' .$mailData['order']->last_name }}</strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{ $mailData['order']->zip }}, {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
        Phone: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </address>
    <br>
    <h6>Your Ordered Products :- </h6>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>                                        
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $orderItem)
            <tr>
                <td>{{ $orderItem->name }}</td>
                <td>${{ number_format($orderItem->price, 2) }}</td>                                        
                <td>{{ $orderItem->qty }}</td>
                <td>${{ number_format($orderItem->total, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="3" class="text-right">Subtotal:</th>
                <td>${{ number_format($mailData['order']->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Discount: <span class="badge p-2 bg-success">{{ !empty($mailData['order']->coupon_code) ? '('.$mailData['order']->coupon_code.')' : '' }}</span></th>
                <td>${{ number_format($mailData['order']->discount, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Shipping:</th>
                <td>${{ number_format($mailData['order']->shipping, 2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Grand Total:</th>
                <td>${{ number_format($mailData['order']->grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>