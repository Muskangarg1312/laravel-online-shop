@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="POST" name="productForm" id="productForm">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $product->title }}">
                                        <p class="error"></p>	
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $product->slug }}">
                                        <p class="error"></p>	
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="short_description">Short Description</label>
                                        <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" >{{ $product->short_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" >{{ $product->description }}</textarea>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="shipping_returns">Shipping & Returns</label>
                                        <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" >{{ $product->shipping_returns }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>								
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">    
                                    <br>Drop files here or click to upload.<br><br>                                            
                                </div>
                            </div>
                        </div>
                        <div class="row" id="product-gallery">
                            @if ($productImages->isNotEmpty())
                            @foreach ($productImages as $image)
                            <div class="col-md-3" id="image-row-{{ $image->id }}">
                                <div class="card">
                                    <input type="hidden" name="image_array[]" value="{{ $image->id }}" />
                                    <img src="{{ asset('storage/product/small/'.$image->image) }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <a href="javascript:void(0)" onclick="deleteImage({{ $image->id }})" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{ $product->compare_price }}">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                        </p>	
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" value="{{ $product->sku }}">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{ $product->barcode }}">	
                                    </div>
                                </div>   
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No" >
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" {{ ($product->track_qty) == 'Yes' ?  'checked' : ''}}>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                        <p class="error"></p>
                                    </div>
                                </div>                                         
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Related products</h2>
                            <div class="mb-3">
                                <select multiple name="related_products[]" id="related_products" class="w-100 related-products">
                                    @if (!empty($relatedProducts))
                                        @foreach ($relatedProducts as $relatedProduct)
                                            <option selected value="{{ $relatedProduct->id }}">{{ $relatedProduct->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($product->status == '1') ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($product->status == '0') ? 'selected' : '' }} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card">
                        <div class="card-body">	
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="" disabled selected>Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                            <option {{ ($product->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="category">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="" disabled selected>Select a Sub Category</option>
                                    @if ($subCategories->isNotEmpty())
                                        @foreach ($subCategories as $subCategory)
                                        <option {{ ($product->sub_category_id == $subCategory->id) ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="" disabled selected>Select a Brand</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                            <option {{ ($product->brand_id == $brand->id) ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option {{ ($product->is_featured == 'No') ? 'selected' : '' }} value="No">No</option>
                                    <option {{ ($product->is_featured == 'Yes') ? 'selected' : '' }} value="Yes">Yes</option>                                                
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>
                                                    
                </div>
            </div>
            
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    $(function() {

        $('.related-products').select2({
            ajax: {
                url: '{{ route('products.getProducts') }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function (data) {
                    return {
                        results: data.tags
                    };
                }
            }
        }); 

        $('#productForm').submit(function (event) { 

            event.preventDefault();

            var formArray = $(this).serializeArray();   

            $("button[type=submit]").prop('disabled', true); 

            $.ajax({
                type: "PUT",
                url: "{{ route('products.update', $product->id) }}",
                data: formArray,
                dataType: "json",
                success: function (response) {
                    if(response['status'] == true){

                        $("button[type=submit]").prop('disabled', false); 
              
                        window.location.href = '{{ route('products.index') }}';
                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type=text], input[type=number], select').removeClass('is-invalid');
                    }
                    else{

                        if(response['notFound'] == true){
                            
                            window.location.href = '{{ route('products.index') }}';
                            return false;
                            
                        }

                        var errors = response['errors'];

                        $('.error').removeClass('invalid-feedback').html('');
                        $('input[type=text], input[type=number], select').removeClass('is-invalid');
                        $.each(errors, function(key, value){
                            $(`#${key}`).addClass('is-invalid')
                                        .siblings('p')
                                        .addClass('invalid-feedback')
                                        .html(value);
                        });

                    }
                },
                error: function(){
                    console.log("Something went wrong");
                }
            });
        });

        $("#title").change(function (e) { 
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

        $("#category").change(function (e) { 
            var category_id = $(this).val();
            $.ajax({
                type: "GET",
                url: '{{ route('products-subcategories.index') }}',
                data: {category_id: category_id},
                dataType: 'json',
                success: function (response) {
                    
                    // console.log(response);

                    $('#sub_category').find("option").not(":first").remove();

                    $.each(response['subCategories'], function (key, item) { 
                        $('#sub_category').append(`<option value = '${item.id}'>${item.name}</option>`);
                    });

                },
                error: function(){
                    console.log("Something went wrong");
                }
            });
        });
    });

    Dropzone.autoDiscover = false;    
        const dropzone = $("#image").dropzone({ 
            url:  "{{ route('product-images.update') }}",
            maxFiles: 10,
            paramName: 'image',
            params: {'product_id': '{{ $product->id }}'},
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response) {
                //console.log(response, file);

               // if (response && response.file) {
                 //   $("#image_id").val(response.file.id);

                //     var imageName = response.file.name;
                //     var imageUrl = "{{ asset('storage/') }}/" + imageName;
                //     var data = $(response.image_id);
                //    console.log(data);
                    var html =  `<div class="col-md-3" id="image-row-${response.image_id}">
                                    <div class="card">
                                        <input type="hidden" name="image_array[]" value="${response.image_id}" />
                                        <img src="${response.ImagePath}" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>`;
                                
                    $('#product-gallery').append(html);
               // }
            },
            complete: function (file) {
                this.removeFile(file);
              }
        });

        function deleteImage(id) { 
            $('#image-row-'+id).remove();

           if (confirm("Are you sure you want to Delete Image?")) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('product-images.destroy') }}",
                    data: {id: id},
                    success: function (response) {
                        if (response.status == true) {
                            alert(response.message);
                        }
                        else{
                            alert(response.message);
                        }
                    }
                });
            }
        }
</script>
@endsection