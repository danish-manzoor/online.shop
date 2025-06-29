@extends('front.layouts.app')



@section('content')
<main>
    <section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <!-- <img src="images/carousel-1.jpg" class="d-block w-100" alt=""> -->

                    <picture>
                        <source media="(max-width: 799px)" srcset="images/carousel-1-m.jpg" />
                        <source media="(min-width: 800px)" srcset="images/carousel-1.jpg" />
                        <img src="images/carousel-1.jpg" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Kids Fashion</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    
                    <picture>
                        <source media="(max-width: 799px)" srcset="images/carousel-2-m.jpg" />
                        <source media="(min-width: 800px)" srcset="images/carousel-2.jpg" />
                        <img src="images/carousel-2.jpg" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Womens Fashion</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <!-- <img src="images/carousel-3.jpg" class="d-block w-100" alt=""> -->

                    <picture>
                        <source media="(max-width: 799px)" srcset="images/carousel-3-m.jpg" />
                        <source media="(min-width: 800px)" srcset="images/carousel-3.jpg" />
                        <img src="images/carousel-2.jpg" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Shop Online at Flat 70% off on Branded Clothes</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                    </div>                    
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Free Shipping</h2>
                    </div>                    
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                    </div>                    
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                    </div>                    
                </div>
            </div>
        </div>
    </section>
    <section class="section-3">
        <div class="container">
            <div class="section-title">
                <h2>Categories</h2>
            </div>           
            <div class="row pb-3">
                @if ($categories->isNotEmpty())
                    @foreach ($categories as $category)
                    <div class="col-lg-3">
                        <div class="cat-card">
                            <div class="left">
                                @if (!empty($category->image))
                                <img src="{{asset('uploads/category/thumb/'.$category->image)}}" alt="" class="img-fluid">
                                @else
                                <img src="{{asset('admin-assets/img/default-150x150.png')}}" alt="" class="img-fluid">
                                @endif
                                
                            </div>
                            <div class="right">
                                <div class="cat-data">
                                    <h2>{{$category->name}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach  
                @endif
                
            </div>
        </div>
    </section>
    
    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
            </div>    
            <div class="row pb-3">
                @if ($is_featuredProducts->isNotEmpty())
                    @foreach ($is_featuredProducts as $featuredProduct)
                    @php
                        $productimage = $featuredProduct->product_images->first();
                    @endphp
                <div class="col-md-3">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{route('front.product',$featuredProduct->slug)}}" class="product-img">
                                @if (!empty($productimage))
                                <img class="card-img-top" src="{{asset('uploads/products/small/'.$productimage->image)}}" alt="">
                                @else
                                <img class="card-img-top" src="{{asset('admin-assets/img/default-150x150.png')}}" alt="">
                                @endif
                                
                            </a>
                            <a class="whishlist" onclick="handleWishList({{$featuredProduct->id}})" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            

                            <div class="product-action">
                                @if ($featuredProduct->track_qty == 'Yes' && $featuredProduct->qty >0)
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$featuredProduct->id}})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>  
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                     Out of stock
                                </a>  
                                @endif
                                {{-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$featuredProduct->id}})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>                             --}}
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{route('front.product',$featuredProduct->slug)}}">{{$featuredProduct->title}}</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>${{$featuredProduct->price}}</strong></span>
                                @if (!empty($featuredProduct->compare_price))
                                <span class="h6 text-underline"><del>${{$featuredProduct->compare_price}}</del></span>  
                                @endif
                                
                            </div>
                        </div>                        
                    </div>                                               
                </div>  
                @endforeach
                @endif
                            
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Latest Produsts</h2>
            </div>    
            <div class="row pb-3">
                @if ($latest_products->isNotEmpty())
                @foreach ($latest_products as $latest_product)
                    @php
                        $productimage = $latest_product->product_images->first();
                    @endphp
                <div class="col-md-3">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{route('front.product',$latest_product->slug)}}" class="product-img">
                                @if (!empty($productimage))
                                <img class="card-img-top" src="{{asset('uploads/products/small/'.$productimage->image)}}" alt="">
                                @else
                                <img class="card-img-top" src="{{asset('admin-assets/img/default-150x150.png')}}" alt="">
                                @endif
                                {{-- <img class="card-img-top" src="images/product-1.jpg" alt=""> --}}
                            </a>
                            <a class="whishlist" onclick="handleWishList({{$latest_product->id}})" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            

                            <div class="product-action">
                                @if ($latest_product->track_qty == 'Yes' && $latest_product->qty >0)
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$latest_product->id}})">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>  
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                     Out of stock
                                </a>  
                                @endif
                                                          
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{route('front.product',$latest_product->slug)}}">{{$latest_product->title}}</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>${{$latest_product->price}}</strong></span>
                                @if (!empty($latest_product->compare_price))
                                <span class="h6 text-underline"><del>${{$latest_product->compare_price}}</del></span>  
                                @endif
                            </div>
                        </div>                        
                    </div>                                               
                </div> 
                @endforeach
                @endif 
                            
            </div>
        </div>
    </section>
</main>
@endsection