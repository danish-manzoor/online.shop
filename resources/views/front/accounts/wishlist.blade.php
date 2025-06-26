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
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                        </div>
                        <div class="card-body p-4">
                            @if ($wishlists->isNotEmpty())
                            @foreach ($wishlists as $item)
                            <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                    <a class="d-block flex-shrink-0 mx-auto me-sm-4" href="#" style="width: 10rem;">
                                        @php
                                            $productImages = getProductImage($item->pid);
                                        @endphp
                                        @if (!empty($productImages))
                                            <img src="{{asset('uploads/products/small/'.$productImages->image)}}" alt="Product">
                                        @else
                                            <img src="{{asset('uploads/products/small/default-150x150.png')}}" alt="Product">
                                        @endif
                                        
                                    </a>
                                    <div class="pt-2">
                                        <h3 class="product-title fs-base mb-2"><a href="shop-single-v1.html">{{$item->title}}</a></h3>                                        
                                        <div class="fs-lg text-accent pt-2">${{number_format($item->price,2)}}
                                            @if (!empty($item->compare_price))
                                            <span class="h6 text-underline"><del>${{number_format($item->compare_price,2)}}</del></span>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteWishlist({{$item->id}})" type="button"><i class="fas fa-trash-alt me-2"></i>Remove</button>
                                </div>
                            </div> 
                            @endforeach 
                            @else
                            <div class="">
                                <h2>Your wishlist is empty</h2>
                            </div>
                            @endif
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
        function deleteWishlist(id){
            if(confirm('Are you you want to remove the product from wishlist')){
                $.ajax({
                    url:'{{route("front.accounts.removeWishlist")}}',
                    method:'post',
                    data:{id:id},
                    dataType:'json',
                    success:function(res){
                        if(res['status'] == true){
                            window.location.href= "{{route('front.accounts.wishlist')}}";
                        }
                    }
                });
            }
            
        }
    </script>
@endsection