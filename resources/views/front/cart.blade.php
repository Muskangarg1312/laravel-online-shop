@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                <li class="breadcrumb-item">Cart</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-9 pt-4">
    <div class="container">
        <div class="row">
            
            @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! Session::get('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            
            @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            @if (Cart::count() > 0)
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table" id="cart">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($cartContent as $item)
                            
                            <tr>
                                <td class="text-start">
                                    <div class="d-flex align-items-start">
                                        {{-- <img src="images/product-1.jpg" width="" height=""> --}}
                                        @if (!empty($item->options->productImage->image))
                                        <img class="card-img-top1" src="{{ asset('storage/product/small/'.$item->options->productImage->image) }}" ></a>
                                        @else
                                        <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" >
                                        @endif
                                        <h2>{{ $item->name }}</h2>
                                    </div>
                                </td>
                                <td>${{ $item->price }}</td>
                                <td>
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{ $item->rowId }}">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm  border-0 text-center" value="{{ $item->qty }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{ $item->rowId }}">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    ${{ $item->price*$item->qty }} 
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteItem('{{ $item->rowId }}')"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">            
                <div class="card cart-summery">
                        <div class="card-body">
                            <div class="sub-title">
                                <h2 class="bg-white">Cart Summery</h3>
                                </div> 
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>${{ Cart::subtotal() }}</div>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>     
                    {{-- <div class="input-group apply-coupan mt-4">
                        <input type="text" placeholder="Coupon Code" class="form-control">
                        <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                    </div>  --}}
                </div>
            </div>
            @else
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">Your Cart is Empty</h4>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endsection
    
    @section('customJs')
    <script>
        $('.add').click(function(){
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 100) {
                qtyElement.val(qtyValue+1);        
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }            
        });
        
        $('.sub').click(function(){
            var qtyElement = $(this).parent().next(); 
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue-1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }        
        });
        
        function updateCart(rowId, qty) {
            $.ajax({
                type: "post",
                url: "{{ route('front.updateCart') }}",
                data: {rowId: rowId, qty: qty},
                dataType: "json",
                success: function (response) {
                    window.location.href = '{{ route('front.cart') }}';
                }
            });
        }
        
        function deleteItem(rowId) {
            if (confirm('Are you sure you want to delete?')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('front.deleteItem.cart') }}",
                    data: {rowId: rowId},
                    dataType: "json",
                    success: function (response) {
                        window.location.href = '{{ route('front.cart') }}';
                    }
                });
            }  
        }
        
    </script>
    @endsection