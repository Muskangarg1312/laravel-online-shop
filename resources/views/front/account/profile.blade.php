@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('front.account.common.sidebar')    
            </div>
            <div class="col-md-9">
                @include('front.account.common.message')
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <form action="" name="profileForm" id="profileForm" method="POST" >
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="mb-3">               
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control" value="{{ $user->name }}">
                                    <p></p>
                                </div>
                                <div class="mb-3">            
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control" value="{{ $user->email }}">
                                    <p></p>
                                </div>
                                <div class="mb-3">                                    
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control" value="{{ $user->phone }}">
                                    <p></p>
                                </div>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                    </div>
                    <form action="" name="addressForm" id="addressForm" method="POST" >
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">               
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" placeholder="Enter Your First Name" class="form-control" value="{{ $address->first_name ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="col-md-6 mb-3">               
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" placeholder="Enter Your Last Name" class="form-control" value="{{ $address->last_name ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="col-md-6 mb-3">            
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control" value="{{ $address->email ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="col-md-6 mb-3">                                    
                                    <label for="mobile">Mobile</label>
                                    <input type="text" name="mobile" id="mobile" placeholder="Enter Your Mobile" class="form-control" value="{{ $address->mobile ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">                                    
                                    <label for="country_id">Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="" selected disabled>Select a Country</option>
                                        @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id ?? '' }}" {{ (!empty($address) && $address->country_id ==$country->id) ? 'selected' : '' }}>{{ $country->name ?? '' }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">                                    
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ $address->address ?? '' }}</textarea>
                                    <p class="error"></p>
                                </div>
                                <div class="col-md-6 mb-3">                                    
                                    <label for="apartment">Apartment</label>
                                    <input type="text" name="apartment" id="apartment" placeholder="Apartment" class="form-control" value="{{ $address->apartment ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="col-md-6 mb-3">                                    
                                    <label for="city">City</label>
                                    <input type="text" name="city" id="city" placeholder="City" class="form-control" value="{{ $address->city ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="col-md-6 mb-3">                                    
                                    <label for="state">State</label>
                                    <input type="text" name="state" id="state" placeholder="State" class="form-control" value="{{ $address->state ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="col-md-6 mb-3">                                    
                                    <label for="zip">ZIP</label>
                                    <input type="text" name="zip" id="zip" placeholder="ZIP" class="form-control" value="{{ $address->zip ?? '' }}">
                                    <p class="error"></p>
                                </div>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $('#profileForm').submit(function (e) { 
        e.preventDefault();
        
        $.ajax({
            type: "post",
            url: "{{ route('account.updateProfile') }}",
            data: $(this).serializeArray(),
            dataType: "json",
            success: function (response) {
                if (response.status == true) {
                    $('#name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $('#email').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $('#phone').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    window.location.href = '{{ route('account.profile') }}'
                } else {
                    var errors = response.errors;
                    
                    if (errors.name) {
                        $('#name').addClass('is-invalid').siblings('p').html(errors.name).addClass('invalid-feedback');
                    }
                    else {
                        $('#name').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }
                    if (errors.email) {
                        $('#email').addClass('is-invalid').siblings('p').html(errors.email).addClass('invalid-feedback');
                    }
                    else {
                        $('#email').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }
                    if (errors.phone) {
                        $('#phone').addClass('is-invalid').siblings('p').html(errors.phone).addClass('invalid-feedback');
                    }
                    else {
                        $('#phone').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }
                } 
            }
        });
    });
    
    // addressForm
    
    $('#addressForm').submit(function (e) { 
        e.preventDefault();
        
        $.ajax({
            type: "post",
            url: "{{ route('account.updateAddress') }}",
            data: $(this).serializeArray(),
            dataType: "json",
            success: function (response) {
                if (response.status == true) {
                    $('.error').removeClass('invalid-feedback').html('');
                        $('#addressForm input[type=text], #addressForm input[type=number], #addressForm select').removeClass('is-invalid');
                        isFormSubmitted = true;
                        $("#addressForm button[type=submit]").prop('disabled', true); ;
                    window.location.href = '{{ route('account.profile') }}'
                } else {
                    var errors = response['errors'];
                    
                    $('.error').removeClass('invalid-feedback').html('');
                    $('#addressForm input[type=text], #addressForm input[type=number], #addressForm select').removeClass('is-invalid');
                    $.each(errors, function(key, value){
                        $(`#addressForm #${key}`).addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(value);
                    });
                } 
            }
        });
    });
</script>
@endsection