@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Management</h1>
            </div>
            {{-- <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div> --}}
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="" method="POST" id="shippingForm" slug="shippingForm">
            <div class="card">
                <div class="card-body">	
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select name="country" id="country" class="form-control">
                                    <option value="" selected disabled>Select a country</option>
                                    @if (!empty($countries))
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                    @endif
                                    <option value="rest_of_world">Rest of the world</option>
                                </select>
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" id="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>							
            </div>
        </form>   
        <div class="card">
            <div class="card-body">	
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body table-responsive p-0">								
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($shippingCharges->isNotEmpty())
                                    @foreach ($shippingCharges as $shippingCharge)
                                    <tr>  
                                        <td>{{ $shippingCharge->id }}</td>
                                        <td>{{ ($shippingCharge->country_id == 'rest_of_world') ? 'Rest of the world' : $shippingCharge->name }}</td>
                                        <td>${{ $shippingCharge->amount }}</td>
                                        <td>
                                            <a href="{{ route('shipping.edit', $shippingCharge->id) }}" class="btn btn-info"><i class="fas fa-pencil-alt    "></i> Edit</a>
                                            <a href="javascript:void(0)" onclick="deleteRecord({{ $shippingCharge->id }})" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    
                                    @endforeach 
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    $(function() { 
        $('#shippingForm').submit(function (event) { 
            event.preventDefault();
            var element = $(this);    
            $("button[type=submit]").prop('disabled', true);        
            $.ajax({
                type: "POST",
                url: '{{ route("shipping.store") }}',
                data: element.serializeArray(),
                dataType: 'json',
                success: function (response) {
                    
                    $("button[type=submit]").prop('disabled', false);  
                    
                    if(response['status'] == true){
                        
                        window.location.href = '{{ route('shipping.create') }}';
                        // $('#country').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        // $('#amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        
                    }
                    else{
                        
                        var errors = response['errors'];
                        
                        if(errors['country']){
                            $('#country').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['country']);
                        }else{
                            $('#country').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }
                        
                        if(errors['amount']){
                            $('#amount').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['amount']);
                        }else{
                            $('#amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }
                        
                    }
                },
                error: function(jqXHR, exception){
                    console.log("Something went wrong");
                }
            });
        });
    });
    
    function deleteRecord(id) {
        var url = '{{ route("shipping.delete", 'ID') }}';
        var newUrl = url.replace('ID', id);
        //alert(newUrl);
        
        if (confirm("Are you sure you want to Delete ?")) {
            $.ajax({
                type: "delete",
                url: newUrl,
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },success: function (response) {
                    // console.log(response);
                    
                    if(response['status']){
                        // console.log(response);
                        window.location.href = '{{ route('shipping.create') }}';
                        // $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        // $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        
                    }
                    else{
                        
                        if(response['notFound'] == true){
                            
                            window.location.href = '{{ route('shipping.create') }}';
                            
                        }
                    }
                }
            }); 
        }
    }   
</script>
@endsection