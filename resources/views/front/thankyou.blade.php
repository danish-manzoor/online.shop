@extends('front.layouts.app')


@section('content')
    <div class="mt-5 text-center">
        <h2>Thank you for your order</h2>
        <p>Your order ID is : {{$orderID}}</p>
    </div>
@endsection