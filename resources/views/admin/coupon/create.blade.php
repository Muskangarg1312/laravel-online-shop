@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Coupon Code</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('coupons.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="POST" id="discountForm" slug="discountForm">
            <div class="card">
                <div class="card-body">	
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Code">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses">Max Uses</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses_user">Max Uses User</label>
                                <input type="number" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="percent">Percent</option>
                                    <option value="fixed">Fixed</option>
                                </select>	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_amount">Discount Amount</label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_amount">Min Amount</label>
                                <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="starts_at">Starts At</label>
                                <input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Starts At" autocomplete="off">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at">Expires At</label>
                                <input type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expires At" autocomplete="off">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
                                <p></p>	
                            </div>
                        </div>								
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" id="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </div>							
            </div>
        </form>   
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    $(document).ready(function(){

        $('#starts_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });

        $('#expires_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });

        $('#discountForm').submit(function (event) { 
            event.preventDefault();
            var element = $(this);    
            $("button[type=submit]").prop('disabled', true);        
            $.ajax({
                type: "POST",
                url: '{{ route('coupon.store') }}',
                data: element.serializeArray(),
                dataType: 'json',
                success: function (response) {
                    
                    $("button[type=submit]").prop('disabled', false);  
                    
                    if(response['status'] == true){
                        
                        window.location.href = '{{ route('coupons.index') }}';
                        $('#code').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        $('#discount_amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');     
                        $('#starts_at').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        $('#expires_at').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        
                    }
                    else{
                        
                        var errors = response['errors'];
                        
                        if(errors['code']){
                            $('#code').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['code']);
                        }else{
                            $('#code').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }
                        
                        if(errors['discount_amount']){
                            $('#discount_amount').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['discount_amount']);
                        }else{
                            $('#discount_amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }

                        if(errors['starts_at']){
                            $('#starts_at').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['starts_at']);
                        }else{
                            $('#starts_at').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }

                        if(errors['expires_at']){
                            $('#expires_at').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['expires_at']);
                        }else{
                            $('#expires_at').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }
                        
                    }
                },
                error: function(jqXHR, exception){
                    console.log("Something went wrong");
                }
            });
        });
        
    });
</script>
@endsection