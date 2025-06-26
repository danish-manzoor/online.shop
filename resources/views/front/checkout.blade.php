@extends('front.layouts.app')


@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form action="" method="post" name="checkoutForm" id="checkoutForm">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->fname)?$customerAddress->fname:'')}}" name="first_name" id="first_name" class="form-control" placeholder="First Name">
                                            <p></p>
                                        </div>            
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->lname)?$customerAddress->lname:'')}}" name="last_name" id="last_name" class="form-control" placeholder="Last Name">
                                            <p></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->email)?$customerAddress->email:'')}}" name="email" id="email" class="form-control" placeholder="Email">
                                            <p></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                        <option {{(!empty($customerAddress->country_id) && $customerAddress->country_id == $country->id?'selected':'')}} value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach
                                                @endif
                                                {{-- <option value="rest_of_world">Rest of the Word</option> --}}
                                            </select>
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address"  id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{(!empty($customerAddress->address)?$customerAddress->address:'')}}</textarea>
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->apartment)?$customerAddress->apartment:'')}}" name="appartment" id="appartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)">
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->city)?$customerAddress->city:'')}}" name="city" id="city" class="form-control" placeholder="City">
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->state)?$customerAddress->state:'')}}" name="state" id="state" class="form-control" placeholder="State">
                                            <p></p>
                                        </div>            
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->zip)?$customerAddress->zip:'')}}" name="zip" id="zip" class="form-control" placeholder="Zip">
                                            <p></p>
                                        </div>            
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" value="{{(!empty($customerAddress->mobile)?$customerAddress->mobile:'')}}" name="mobile" id="mobile" class="form-control" placeholder="Mobile No.">
                                            <p></p>
                                        </div>            
                                    </div>
                                    

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control">{{(!empty($customerAddress->notes)?$customerAddress->notes:'')}}</textarea>
                                            <p></p>
                                        </div>            
                                    </div>

                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>                    
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{$item->name}} X {{$item->qty}}</div>
                                    <div class="h6">${{$item->price}}</div>
                                </div>
                                @endforeach
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>${{number_format($subtotal,2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6" id="ShippingCost"><strong>${{number_format($shippingCost,2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Discount</strong></div>
                                    <div class="h6" id="discountAmount"><strong>${{number_format($discount,2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal">${{number_format($grandTotal,2)}}</strong></div>
                                </div>                            
                            </div>
                        </div>   


                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" id="coupon-code">
                            <button class="btn btn-dark" type="button" id="apply-coupon-btn">Apply Coupon</button>
                            <p></p>

                            
                        </div> 
                        <div class="" id="coupon-wrapper">
                            @if (session()->has('code'))
                            <div class="input-group apply-coupan mt-4" id="coupon-row">
                                <span id="couponText">{{session('code')->code}}</span>
                                <button type="button" class="btn btn-danger btn-sm ms-3" id="removeCoupon">Remove</button>
                            </div>
                            @endif
                        </div>
                       
                            
                        

                        <div class="card payment-form ">                        
                            <h3 class="card-title h5 mb-3">Payment Details</h3>
                            <div class="mb-3" id="payment_method_one_div">
                                <input checked type="radio" value="cod" name="payment_methods" id="payment_method_one">
                                <label for="payment_method_one">COD</label>
                            </div>
                            <div class="mb-3" id="payment_method_two_div">
                                <input type="radio" value="stripe" name="payment_methods" id="payment_method_two">
                                <label for="payment_method_two">Stripe</label>
                            </div>
                            <div class="card-body p-0 d-none" id="stripe_div">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                    </div>
                                </div>
                            </div> 
                            <div class="pt-4">
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>                       
                        </div>

                            
                        <!-- CREDIT CARD FORM ENDS HERE -->
                        
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>  
@endsection
@section('customJs')
    <script>
        $(document).ready(function(){
            $('input[type="radio"]').click(function(){
                if($('#payment_method_one').is(":checked") == true){
                    $('#stripe_div').addClass('d-none');
                }else{
                    $('#stripe_div').removeClass('d-none');   
                }
            });
            
            $('#checkoutForm').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url:'{{route("process.checkout")}}',
                    method:'POST',
                    data:$(this).serializeArray(),
                    dataType:'json',
                    success:function(response){
                       if(response['status'] == true){
                        var orderId = response.orderID;
                        var url = "{{route('order.thankyou','ID')}}";
                        var newURL = url.replace('ID',orderId);
                        window.location.href= newURL;
                       }else{
                            var errors = response['errors'];
                            if(errors['first_name']){
                                $('#first_name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['first_name']);
                            }else{
                                $('#first_name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html(errors['first_name'])
                            }

                            if(errors['last_name']){
                                $('#last_name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['last_name']);
                            }else{
                                $('#last_name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }

                            if(errors['email']){
                                $('#email').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email']);
                            }else{
                                $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }

                            if(errors['mobile']){
                                $('#mobile').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['mobile']);
                            }else{
                                $('#mobile').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }

                            if(errors['country']){
                                $('#country').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['country']);
                            }else{
                                $('#country').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }


                            if(errors['address']){
                                $('#address').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['address']);
                            }else{
                                $('#address').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }

                            if(errors['city']){
                                $('#city').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['city']);
                            }else{
                                $('#city').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }

                            if(errors['state']){
                                $('#state').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['state']);
                            }else{
                                $('#state').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }

                            if(errors['zip']){
                                $('#zip').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['zip']);
                            }else{
                                $('#zip').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                            }
                       }
                    },
                    error:function(xhr,error){
                        console.log('something went wrong');
                    }
                });
            });


            //APPLY COUPON CODE
            $("#apply-coupon-btn").click(function(){
                var code = $('#coupon-code').val();
                var id = $('#country').val();
                $.ajax({
                    url:'{{route("cart.applyCoupon")}}',
                    method:'POST',
                    data:{id:id,code:code},
                    dataType:'json',
                    success:function(res){
                        if(res['status'] == true){
                            $('#apply-coupon-btn').siblings('p').removeClass('text-danger').html('');
                            $('#ShippingCost').html('$'+res['shipping']);
                            $('#discountAmount').html('$'+res['discount']);
                            $('#grandTotal').html('$'+res['total']);
                            $('#coupon-wrapper').html(res['coponString']);
                            
                        }else{
                            $('#apply-coupon-btn').siblings('p').addClass('text-danger').html(res['message']);
                        }
                    }
                });
            });


            $(document).on('click','#removeCoupon',function(){
                var id = $('#country').val();
                $.ajax({
                    url:'{{route("cart.removeCoupon")}}',
                    method:'POST',
                    data:{id,id},
                    dataType:'json',
                    success:function(res){
                        if(res['status'] == true){
                            $('#apply-coupon-btn').siblings('p').removeClass();
                            // $('#apply-coupon-btn').siblings('p').addClass('text-success').html(res['message']);
                            $('#ShippingCost').html('$'+res['shipping']);
                            $('#discountAmount').html('$'+res['discount']);
                            $('#grandTotal').html('$'+res['total']);
                            $('#coupon-code').val('');
                            $('#coupon-row').remove();
                        }else{
                            $('#apply-coupon-btn').siblings('p').addClass('text-danger').html(res['message']);
                        }
                    }
                });
            });




            $('#country').change(function(){
                $.ajax({
                    url:'{{route("cart.summary")}}',
                    method:'POST',
                    data:{id:$(this).val()},
                    dataType:'json',
                    success:function(res){
                        if(res['status'] == true){
                            $('#ShippingCost').html('$'+res['shipping']);
                            $('#grandTotal').html('$'+res['total']);
                        }
                    }
                });
            });
        }); //end of document
    </script>
@endsection