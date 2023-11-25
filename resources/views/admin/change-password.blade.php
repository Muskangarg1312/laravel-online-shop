@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Change Password</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="POST" name="changePasswordForm" id="changePasswordForm">
            @include('admin.message')
            <div class="card">
                <div class="card-body">	
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">               
                                <label for="old_password">Old Password</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">               
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">               
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" id="submit" class="btn btn-primary">Update</button>
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
    $(function() { 
        $('#changePasswordForm').submit(function (e) { 
            e.preventDefault();
            $('#submit').prop('disabled', true); 
            $.ajax({
                type: "post",
                url: "{{ route('admin.changePassword') }}",
                data: $(this).serializeArray(),
                dataType: "json",
                success: function (response) {
                    
                    $('#submit').prop('disabled', false); 
                    
                    if (response.status == true) {
                        $('#old_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#new_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        $('#confirm_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        window.location.href = '{{ route('admin.showChangePasswordForm') }}'
                    } else {
                        var errors = response.errors;
                        
                        if (errors.old_password) {
                            $('#old_password').addClass('is-invalid').siblings('p').html(errors.old_password).addClass('invalid-feedback');
                        }
                        else {
                            $('#old_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.new_password) {
                            $('#new_password').addClass('is-invalid').siblings('p').html(errors.new_password).addClass('invalid-feedback');
                        }
                        else {
                            $('#new_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                        if (errors.confirm_password) {
                            $('#confirm_password').addClass('is-invalid').siblings('p').html(errors.confirm_password).addClass('invalid-feedback');
                        }
                        else {
                            $('#confirm_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                        }
                    } 
                }
            });
        });
    });
</script>
@endsection