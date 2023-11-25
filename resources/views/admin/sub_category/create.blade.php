@extends('admin.layouts.app')
@section('content')
	<!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" name="subCategoryForm" id="subCategoryForm">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="" disabled selected>Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
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
                                    <input type="text" name="slug" id="slug" readonly class="form-control" placeholder="Slug">	
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
                                    <label for="showHome">Show on Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>	
                                    <p></p>
                                </div>
                            </div>								
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $('#subCategoryForm').submit(function (event) { 
            event.preventDefault();
            var element = $(this);    
            $("button[type=submit]").prop('disabled', true);        
            $.ajax({
                type: "POST",
                url: '{{ route("sub-categories.store") }}',
                data: element.serializeArray(),
                dataType: 'json',
                success: function (response) {

                    $("button[type=submit]").prop('disabled', false);  

                    if(response['status'] == true){
              
                        window.location.href = '{{ route('sub-categories.index') }}';
                        $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           

                    }
                    else{

                        var errors = response['errors'];

                        if(errors['category']){
                            $('#category').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['category']);
                        }else{
                            $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');           
                        }

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