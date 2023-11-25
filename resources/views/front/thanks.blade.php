@extends('front.layouts.app')

@section('content')

<section class="container p-5">
    <div class="col-md-12 text-center">
        @if (Session::has('success'))
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! Session::get('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <h1>Thank You!</h1>
        <p>Your Order Id is - {{ $id }}</p>
        @endif
        @if (Session::has('error'))
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@section('customJs')
@endsection