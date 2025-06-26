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
            @if (Session::has('success'))
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                <div>
                    {{Session::get('success')}}
                </div>
              </div>
            @endif
            @if (Session::has('error'))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>
                 {{Session::get('error')}}
                </div>
              </div>
            @endif
            <div class="row">
                <div class="col-md-3">
                    @include('front.accounts.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0 pt-2 pb-2">{{$plan}} Plan</h2>
                            
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="mb-3">               
                                    <label for="name">Card Details</label>
                                    <div id="card-element" style="border: 1px solid black;padding: 11px;"></div>
                                </div>
            

                                <div class="d-flex">
                                    <button class="btn btn-dark" id="card-button">Pay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJs')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51IQs0dAPZ0qgh87REeTS5BozczQKgFDCOo8eIrrBRi2rTW1hu3Fh91jqqkCx26XiPfiIubdkRFfCcShe5LmOvxCk00Mj4meDZu');
    var style = {
        base: {
            border: '1px solid #E8E8E8',
        },
    };
    var elements = stripe.elements({style: style});
    var cardElement = elements.create('card');

    cardElement.mount('#card-element');



    var cardholderName = document.getElementById('cardholder-name');
    var cardButton = document.getElementById('card-button');

    cardButton.addEventListener('click', function(ev) {
    stripe.createPaymentMethod('card',cardElement).then(function(result) {
        if (result.error) {
        // Show error in payment form
        } else {

        // Otherwise send paymentMethod.id to your server (see Step 2)
        fetch('{{route("front.accounts.subscription.create")}}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json','X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify({ payment_method_id: result.paymentMethod.id,plan:"{{$plan}}" })
        }).then(function(result) {
            

            // Handle server response (see Step 3)
            result.json().then(function(json) {
            handleServerResponse(json);
            })
        });
        }
    });
    });



    function handleServerResponse(response) {
    
        if (response.error) {
            // Show error from server on payment form
            console.log(response.error);
        } else if (response.requires_action) {
            // Use Stripe.js to handle required card action
            stripe.confirmCardPayment(
            response.payment_intent_client_secret
            ).then(function(result) {
            
            if (result.error) {
                // Show error in payment form
            } else {
                // The card action has been handled
                // The PaymentIntent can be confirmed again on the server
                fetch('{{route("front.accounts.subscription.create")}}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ payment_intent_id: result.paymentIntent.id })
                }).then(function(confirmResult) {
                return confirmResult.json();
                }).then(handleServerResponse);
            }
            });
        } else {
            // Show success message
            if(response.success == true){
                alert('Subscription created successfully');
                window.location.href = "{{route('front.accounts.subscription')}}";
            }
        }
    }
</script>
@endsection