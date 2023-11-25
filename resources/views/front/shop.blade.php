@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Shop</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">            
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>Categories</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if($categories->isNotEmpty())
                                @foreach ($categories as $key => $category )
                                <div class="accordion-item">
                                    @if($category->sub_category->isNotEmpty())
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $key }}" aria-expanded="false" aria-controls="collapseOne-{{ $key }}">
                                            {{ $category->name }}
                                        </button>
                                    </h2>
                                    @else
                                    <a href="{{ route('front.shop', $category->slug) }}" class="nav-item nav-link {{ ($categorySelected ==$category->id) ? 'text-primary' : '' }}">{{ $category->name }}</a>
                                    @endif
                                    @if($category->sub_category->isNotEmpty())
                                    <div id="collapseOne-{{ $key }}" class="accordion-collapse collapse {{ ($categorySelected ==$category->id) ? 'show' : '' }}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                        <div class="accordion-body">
                                            <div class="navbar-nav">
                                                @foreach ($category->sub_category as $subCategory)
                                                <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}" class="nav-item nav-link {{ ($subCategorySelected ==$subCategory->id) ? 'text-primary' : '' }}">{{ $subCategory->name }}</a> 
                                                @endforeach                                     
                                            </div>
                                        </div>
                                    </div>
                                    @endif  
                                </div> 
                                @endforeach 
                                @endif                                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="sub-title mt-5">
                        <h2>Brand</h3>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                @if($brands->isNotEmpty())
                                @foreach ($brands as $brand )
                                <div class="form-check mb-2">
                                    <input {{ (in_array($brand->id, $brandsArray)) ? 'checked' : '' }} class="form-check-input brand-label" name="brand[]" type="checkbox" value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                                    <label class="form-check-label" for="brand-{{ $brand->id }}">
                                        {{ $brand->name }}
                                    </label>
                                </div> 
                                @endforeach 
                                @endif              
                            </div>
                        </div>
                        
                        <div class="sub-title mt-5">
                            <h2>Price</h3>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <input type="text" class="js-range-slider" name="my_range" value="" />
                                    {{-- <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            $0-$100
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            $100-$200
                                        </label>
                                    </div>                 
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            $200-$500
                                        </label>
                                    </div> 
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            $500+
                                        </label>
                                    </div>                  --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row pb-3">
                                <div class="col-12 pb-1">
                                    <div class="d-flex align-items-center justify-content-end mb-4">
                                        <div class="ml-2">
                                            {{-- <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Latest</a>
                                                    <a class="dropdown-item" href="#">Price High</a>
                                                    <a class="dropdown-item" href="#">Price Low</a>
                                                </div>
                                            </div>                                     --}}
                                            
                                            <select name="sort" id="sort" class="form-select">
                                                <option value="latest" {{ ($sort == 'latest') ? 'selected' : '' }}>Latest</option>
                                                <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Price High</option>
                                                <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : '' }}>Price Low</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @if ($products->isNotEmpty())
                                
                                @foreach ($products as $product)
                                
                                @php
                                $productImage = $product->product_images->first();
                                @endphp
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            {{-- <a href="" class="product-img"><img class="card-img-top" src="images/product-1.jpg" alt=""></a> --}}
                                            <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                                @if (!empty($productImage->image))
                                                <img class="card-img-top" src="{{ 
                                                    asset('storage/product/small/'.$productImage->image) }}" ></a>
                                                    @else
                                                    <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" >
                                                    @endif
                                                </a>
                                                <a onclick="addToWishlist({{ $product->id }})" class="whishlist" href="javascript:void(0)"><i class="far fa-heart"></i></a>                                
                                                
                                                <div class="product-action">
                                                    @if ($product->track_qty == 'Yes')
                                                    @if ($product->qty > 0)
                                                    <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart({{ $product->id }});">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a>   
                                                    @else
                                                    <a class="btn btn-danger" href="javascript:void(0)">
                                                        <i class="fa fa-exclamation-circle"></i> Out Of Stock
                                                    </a>     
                                                    @endif
                                                    @else
                                                    <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart({{ $product->id }});">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a> 
                                                    @endif                            
                                                </div>
                                            </div>                        
                                            <div class="card-body text-center mt-3">
                                                <a class="h6 link" href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                                <div class="price mt-2">
                                                    <span class="h5"><strong>${{ $product->price }}</strong></span>
                                                    @if ($product->compare_price > $product->price)
                                                    <span class="h6 text-underline"><del>${{ $product->compare_price }}</del></span> 
                                                    @endif
                                                </div>
                                            </div>                        
                                        </div>                                               
                                    </div>  
                                    @endforeach
                                    
                                    @endif  
                                    
                                    <div class="col-md-12 pt-5">
                                        <nav aria-label="Page navigation example">
                                            {{ $products->withQueryString()->links() }}
                                            {{-- <ul class="pagination justify-content-end">
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                                </li>
                                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">Next</a>
                                                </li>
                                            </ul> --}}
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @endsection
                
                @section('customJs')
                <script>
                    $(document).ready(function() {
                        rangeSlider =  $(".js-range-slider").ionRangeSlider({
                            type: "double",
                            min: 0,
                            max: 1000,
                            from: {{ $priceMin }},
                            step: 10, 
                            to: {{ $priceMax }},
                            skin: "square",
                            max_postfix: '+',
                            prefix: '$',
                            onFinish: function () {
                                apply_filters()
                            }
                        });
                        
                        var slider = $('.js-range-slider').data('ionRangeSlider');
                        
                        $('.brand-label').change(function (e) { 
                            e.preventDefault();
                            
                            apply_filters();
                            
                        });
                        
                        $('#sort').change(function (e) { 
                            e.preventDefault();
                            
                            apply_filters();
                            
                        });
                        
                        function apply_filters() { 
                            var brands = [];
                            
                            $('.brand-label').each(function () { 
                                if ($(this).is(':checked') == true) {
                                    brands.push($(this).val());
                                }
                            });
                            
                            // console.log(brands.toString());
                            
                            var url = '{{ url()->current() }}?';
                            
                            // Brand Filter
                            
                            if(brands.length > 0) {
                                url += '&brand='+brands.toString()
                            }
                            
                            // Price Range Filter 
                            
                            url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;
                            
                            // 

                            var keyword = $('#search').val();
                            if (keyword.length > 0) {
                                url += '&search='+keyword;
                            }

                            // Sorting Filter
                            
                            url += '&sort='+$('#sort').val();
                            
                            window.location.href = url;
                        }
                    });  
                </script>
                @endsection