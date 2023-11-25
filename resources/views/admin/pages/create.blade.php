@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Page</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="pages.html" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="POST" name="pageForm" id="pageForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                <p></p>	
                            </div>
                        </div>	
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="summernote" cols="30" rows="10"></textarea>
                            </div>								
                        </div>                                    
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('pages.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $('#pageForm').submit(function (event) { 
            event.preventDefault();
            var element = $(this);    
            $("button[type=submit]").prop('disabled', true);        
            $.ajax({
                type: "POST",
                url: '{{ route("pages.store") }}',
                data: element.serializeArray(),
                dataType: 'json',
                success: function (response) {
                    
                    $("button[type=submit]").prop('disabled', false);  
                    
                    if(response['status'] == true){
                        
                        window.location.href = '{{ route('pages.index') }}';
                        
                        $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');         
                        
                    }
                    else{
                        
                        var errors = response['errors'];
                        
                        if(errors['name']){
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                        }else{
                            $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }
                        
                        if(errors['slug']){
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                        }else{
                            $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }
                        
                    }
                },
                error: function(jqXHR, exception){
                    console.log("Something went wrong");
                }
            });
        }); 
        
        $("#name").change(function (e) { 
            e.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);        
            
            $.ajax({
                type: "GET",
                url: '{{ route("getSlug") }}',
                data: {title: element.val()},
                dataType: 'json',
                success: function (response) {
                    
                    $("button[type=submit]").prop('disabled', false);   
                    
                    if(response['status'] == true){
                        
                        $('#slug').val(response['slug']);
                        
                    }
                }
            });
        });
    });
</script>
@endsection