@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Shop</a></li>
                    <li class="breadcrumb-item">{{$product->title}}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">
                @include('front.accounts.message')
                <div class="col-md-5">
                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner bg-light">
                        @if ($product->product_images->isNotEmpty())
                           
                            @foreach ($product->product_images as $key => $item)
                            <div class="carousel-item {{($key == 0?'active':'')}}">
                                <img class="w-100 h-100" src="{{asset('uploads/products/large/'.$item->image)}}" alt="Image">
                            </div>
                            @endforeach
                            
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="bg-light right">
                        <h1>{{$product->title}}</h1>
                        <div class="d-flex mb-3">
                            <div class="star-rating mt-2" title="70%" style="font-size: 1em;">
                                <div class="back-stars">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    
                                    <div class="front-stars" style="width: {{$perRating}}%">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div> 
                            <small class="pt-2 pl-2">({{($product->product_rating_count > 1)?$product->product_rating_count.' reviews':$product->product_rating_count.' review'}})</small>
                        </div>
                        @if (!empty($product->compare_price))
                        <h2 class="price text-secondary"><del>${{$product->compare_price}}</del></h2>
                        @endif
                        
                        <h2 class="price ">${{$product->price}}</h2>

                        <p>{!!$product->short_description!!}</p>
                        @if ($product->track_qty == 'Yes' && $product->qty >0)
                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}})">
                                <i class="fa fa-shopping-cart"></i> Add To Cart
                            </a>  
                        @else
                            <a class="btn btn-dark" href="javascript:void(0);">
                                Out of stock
                            </a>  
                        @endif
                        
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="bg-light">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                {!! $product->description!!}
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                            <p>{!!$product->shipping_returns!!}</p>
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="col-md-8">
                                    <form method="post" name="addproductRating" id="addproductRating">
                                        <div class="row">
                                            <h3 class="h4 pb-3">Write a Review</h3>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                                                <p></p>
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                                                <p></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="rating">Rating</label>
                                                <br>
                                                <div class="rating" style="width: 10rem">
                                                    <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-4" type="radio" name="rating" value="4"/><label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
                                                </div>
                                                <p class="rating-error text-danger"></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="">How was your overall experience?</label>
                                                <textarea name="comment"  id="comment" class="form-control" cols="30" rows="10" placeholder="How was your overall experience?"></textarea>
                                                <p></p>
                                            </div>
                                            <div>
                                                <button class="btn btn-dark">Submit</button>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <div class="overall-rating mb-3">
                                        <div class="d-flex">
                                            <h1 class="h3 pe-3">{{$avgRating}}</h1>
                                            <div class="star-rating mt-2" title="70%">
                                                <div class="back-stars">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    
                                                    <div class="front-stars" style="width: {{$perRating}}%">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="pt-2 ps-2">({{($product->product_rating_count > 1)?$product->product_rating_count.' reviews':$product->product_rating_count.' review'}})</div>
                                        </div>
                                        
                                    </div>
                                    @if ($product->product_rating->isNotEmpty())
                                        @foreach ($product->product_rating as $rating)
                                        @php
                                            $perRating = ($rating->rating*100)/5;
                                            // echo $perRating;
                                        @endphp
                                        <div class="rating-group mb-4">
                                            <span> <strong>{{$rating->username}} </strong></span>
                                             <div class="star-rating mt-2" title="">
                                                 <div class="back-stars">
                                                     <i class="fa fa-star" aria-hidden="true"></i>
                                                     <i class="fa fa-star" aria-hidden="true"></i>
                                                     <i class="fa fa-star" aria-hidden="true"></i>
                                                     <i class="fa fa-star" aria-hidden="true"></i>
                                                     <i class="fa fa-star" aria-hidden="true"></i>
                                                     
                                                     <div class="front-stars" style="width: {{$perRating}}%">
                                                         <i class="fa fa-star" aria-hidden="true"></i>
                                                         <i class="fa fa-star" aria-hidden="true"></i>
                                                         <i class="fa fa-star" aria-hidden="true"></i>
                                                         <i class="fa fa-star" aria-hidden="true"></i>
                                                         <i class="fa fa-star" aria-hidden="true"></i>
                                                     </div>
                                                 </div>
                                             </div>   
                                             <div class="my-3">
                                                 <p>{{$rating->comment}}</p>
                                             </div>
                                         </div>
                                        @endforeach
                                    @endif
                                    
    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>           
        </div>
    </section>
    @if (!empty($relatedProducts))
    <section class="pt-5 section-8">
        <div class="container">
            <div class="section-title">
                <h2>Related Products</h2>
            </div> 
            <div class="col-md-12">
                <div id="related-products" class="carousel">
                    @foreach ($relatedProducts as $item)
                        @php
                            $productImages = $item->product_images->first();
                        @endphp
                    
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{route('front.product',$item->slug)}}" class="product-img">
                                @if (!empty($productImages))
                                <img class="card-img-top" src="{{asset('uploads/products/small/'.$productImages->image)}}" alt="">
                                @else
                                <img class="card-img-top" src="{{asset('uploads/products/small/default-150x150.png')}}" alt="">
                                @endif
                                
                            </a>
                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                            <div class="product-action">
                                @if ($item->track_qty == 'Yes' && $item->qty >0)
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$item->id}})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>  
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                     Out of stock
                                </a>  
                                @endif
                                {{-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$item->id}})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>                             --}}
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{route('front.product',$item->slug)}}">{{$item->title}}</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>${{$item->price}}</strong></span>
                                @if ($item->compare_price != '')
                                <span class="h6 text-underline"><del>${{$item->compare_price}}</del></span>
                                @endif
                               
                            </div>
                        </div>                        
                    </div> 
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif
</main>
@endsection


@section('customJs')
   <script>
    $('#addproductRating').submit(function(e){
        e.preventDefault();
        $.ajax({
            url:'{{route("front.rating",$product->id)}}',
            method:'POST',
            data:$(this).serializeArray(),
            dataType:'json',
            success:function(res){
                if(res['status'] == true){
                    window.location.href="{{route('front.product',$product->slug)}}";
                }else{
                    var errors = res['errors'];
                    // console.log(errors['name']);
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
                    if(errors['comment']){
                        $('#comment').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['comment']);
                    }else{
                        $('#comment').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

                    }

                    if(errors['rating']){
                        $('.rating-error').html(errors['rating']);
                    }else{
                        $('.rating-error').html('');
                    }
                }
            }
        });
    });
   </script>
@endsection

