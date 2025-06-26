@extends('front.layouts.app')


@section('content')
<main>
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
            @include('front.accounts.message')
            <div class="row">
                <div class="col-md-3">
                    @include('front.accounts.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <div class="card-body p-4">
                            <form action="" method="post" id="updateProfile" name="updateProfile">
                                <div class="row">
                                    <div class="mb-3">               
                                        <label for="name">Name</label>
                                        <input type="text" value="{{$user->name}}" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">            
                                        <label for="email">Email</label>
                                        <input type="text" value="{{$user->email}}" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                        
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="phone">Phone</label>
                                        <input type="text" value="{{$user->phone}}" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
    
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Shipping Address</h2>
                        </div>
                        <div class="card-body p-4">
                            <form action="" method="post" id="updateAddress">
                                <div class="row">
                                    <div class="col-md-6 mb-3">               
                                        <label for="name">First Name</label>
                                        <input type="text" value="{{(!empty($address->fname)?$address->fname:'')}}" name="first_name" id="first_name" placeholder="Enter Your First Name" class="form-control">
                                        <p class="errors"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">               
                                        <label for="name">Last Name</label>
                                        <input type="text" value="{{(!empty($address->lname)?$address->lname:'')}}" name="last_name" id="last_name" placeholder="Enter Your Last Name" class="form-control">
                                        <p class="errors"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">            
                                        <label for="email">Email</label>
                                        <input type="text" value="{{(!empty($address->email)?$address->email:'')}}" name="emailAddress" id="emailAddress" placeholder="Enter Your Email" class="form-control">
                                        <p class="errors"></p>
                                    </div>
                                    <div class="col-md-6 mb-3">                                    
                                        <label for="phone">Mobile</label>
                                        <input type="text" value="{{(!empty($address->mobile)?$address->mobile:'')}}" name="mobile" id="mobile" placeholder="Enter Your Mobile" class="form-control">
                                        <p class="errors"></p>
                                    </div>
                        
                                    <div class="mb-3">                                    
                                        <label for="phone">Country</label>
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select</option>
                                            @if ($countries->isNotEmpty())
                                                @foreach ($countries as $country)
                                                    <option {{(!empty($address->country_id) && $country->id == $address->country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            @endif
                                            
                                        </select>
                                        <p class="errors"></p>
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="phone">Address</label>
                                        <textarea name="address" class="form-control" id="address" cols="30" rows="5">{{(!empty($address->zip)?$address->zip:'')}}</textarea>
                                        <p class="errors"></p>
                                    </div>
                                    <div class=" mb-3">                                    
                                        <label for="phone">Apartment</label>
                                        <input type="text" name="apartment" value="{{(!empty($address->apartment)?$address->apartment:'')}}" id="apartment" placeholder="Apartment" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">                                    
                                        <label for="phone">City</label>
                                        <input type="text" value="{{(!empty($address->city)?$address->city:'')}}" name="city" id="city" placeholder="City" class="form-control">
                                        <p class="errors"></p>
                                    </div>
                                    <div class="col-md-4 mb-3">                                    
                                        <label for="phone">State</label>
                                        <input type="text" value="{{(!empty($address->state)?$address->state:'')}}" name="state" id="state" placeholder="State" class="form-control">
                                        <p class="errors"></p>
                                    </div>
                                    <div class="col-md-4 mb-3">                                    
                                        <label for="phone">zip</label>
                                        <input type="text" value="{{(!empty($address->zip)?$address->zip:'')}}" name="zip" id="zip" placeholder="Zip" class="form-control">
                                        <p class="errors"></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJs')
    <script>
        $(document).ready(function(){
            $('#updateProfile').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url:'{{route("front.accounts.updateProfile")}}',
                    method:'post',
                    data:$(this).serializeArray(),
                    dataType:'json',
                    success:function(res){
                       if(res['status'] == true){
                        window.location.href ="{{route('front.accounts.profile')}}";
                       }else{
                            var errors = res['errors'];
                            if(errors['name']){
                                $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                            }else{
                                $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                            }
                            if(errors['email']){
                                $('#email').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email']);
                            }else{
                                $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                            }

                            if(errors['phone']){
                                $('#phone').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['phone']);
                            }else{
                                $('#phone').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                            }

                       }
                    }
                });
            });



            $('#updateAddress').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url:'{{route("front.accounts.updateAddress")}}',
                    method:'post',
                    data:$(this).serializeArray(),
                    dataType:'json',
                    success:function(res){
                        if(res['status'] == true){
                        window.location.href = "{{route('front.accounts.profile')}}";
                        }else{
                            var errors = res['errors'];
                            
                            $('.errors').removeClass('invalid-feedback').html('');
                            $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                            $.each(errors,function(key,value){
                                console.log(key);
                                $(`#${key}`).addClass('is-invalid')
                                            .siblings('p')
                                            .addClass('invalid-feedback')
                                            .html(value[0]);
                            });
                            // if(errors['email']){
                            //     $('#emailAddress').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email']);
                            // }else{
                            //     $('#emailAddress').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                            // }
                        }
                    }
                });
            });
        });
    </script>
@endsection