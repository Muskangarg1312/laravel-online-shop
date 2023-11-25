@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-6">							
                <div class="small-box card bg-blue">
                    <div class="inner">
                        <h3>{{ $totalOrders }}</h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <a href="{{ route('orders.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card bg-green">
                    <div class="inner">
                        <h3>{{ $totalProducts }}</h3>
                        <p>Total Products</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <a href="{{ route('products.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-4 col-6">							
                <div class="small-box card bg-red">
                    <div class="inner">
                        <h3>{{ $totalCustomers }}</h3>
                        <p>Total Customers</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="{{ route('users.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-3 col-4">							
                <div class="small-box card shadow">
                    <div class="inner">
                        <h3>${{ number_format($totalRevenue, 2) }}</h3>
                        <p>Total Sale</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    {{-- <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a> --}}
                </div>
            </div>

            <div class="col-lg-3 col-4">							
                <div class="small-box card shadow">
                    <div class="inner">
                        <h3>${{ number_format($revenueThisMonth, 2) }}</h3>
                        <p>Total Revenue this Month ({{ $thisMonthName }})</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    {{-- <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a> --}}
                </div>
            </div>

            <div class="col-lg-3 col-4">							
                <div class="small-box card shadow">
                    <div class="inner">
                        <h3>${{ number_format($revenueLastMonth, 2) }}</h3>
                        <p>Total Revenue Last Month ({{ $lastMonthName }})</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    {{-- <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a> --}}
                </div>
            </div>

            <div class="col-lg-3 col-4">							
                <div class="small-box card shadow">
                    <div class="inner">
                        <h3>${{ number_format($revenueLastThirtyDays, 2) }}</h3>
                        <p>Total Revenue Last 30 days </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    {{-- <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a> --}}
                </div>
            </div>

        </div>
    </div>					
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    // alert('hi');
</script>
@endsection