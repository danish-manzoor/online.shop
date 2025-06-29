@extends('front.layouts.app')


@section('content')
<main>
	<section class="section-5 pt-3 pb-3 mb-3 bg-white">
		<div class="container">
			<div class="light-font">
				<ol class="breadcrumb primary-color mb-0">
					<li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
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
								@if ($categories->isNotEmpty())
								@foreach ($categories as $key => $category)
									
								
								<div class="accordion-item">
									@if ($category->sub_category->isNotEmpty())
									<h2 class="accordion-header" id="heading{{$key}}">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="false" aria-controls="collapse{{$key}}">
											{{$category->name}}
										</button>
									</h2>
									@else
									<a href="{{route('front.shop',[$category->slug])}}" class="nav-item nav-link {{($category->id==$categorySelected?'text-primary':'')}}">{{$category->name}}</a>
									@endif


									@if ($category->sub_category->isNotEmpty())
									<div id="collapse{{$key}}" class="accordion-collapse collapse {{($category->id==$categorySelected?'show':'')}}" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample" style="">
										<div class="accordion-body">
											<div class="navbar-nav">
												@foreach ($category->sub_category as $subCategory)
												<a href="{{route('front.shop',[$category->slug,$subCategory->slug])}}" class="nav-item nav-link {{($subCategory->id==$subcategorySelected?'text-primary':'')}}">{{$subCategory->name}}</a>
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
							@if ($brands->isNotEmpty())
							@foreach ($brands as $item)

							<div class="form-check mb-2">
								<input {{(in_array($item->id, $selectedBrands)?'checked':'')}} class="form-check-input brand-label" type="checkbox" name="brand[]" value="{{$item->id}}" id="brand-{{$item->id}}">
								<label class="form-check-label" for="brand-{{$item->id}}">
									{{$item->name}}
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
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="row pb-3">
						<div class="col-12 pb-1">
							<div class="d-flex align-items-center justify-content-end mb-4">
								<div class="ml-2">
									<select name="sort" class="form-control" id="sort">
										<option value="latest" {{($sort=='latest'?'selected':'')}}>Latest</option>
										<option value="price_desc"  {{($sort=='price_desc'?'selected':'')}}>Price High</option>
										<option value="price_asc"  {{($sort=='price_asc'?'selected':'')}}>Price Low</option>
									</select>                                    
								</div>
							</div>
						</div>
						@if ($products->isNotEmpty())
							@foreach ($products as $product)
							@php
								$productImages = $product->product_images->first();
							@endphp
								<div class="col-md-4">
									<div class="card product-card">
										<div class="product-image position-relative">
											<a href="{{route('front.product',$product->slug)}}" class="product-img">
												@if (!empty($productImages))
												<img class="card-img-top" src="{{asset('uploads/products/small/'.$productImages->image)}}" alt="">
												@else
												<img class="card-img-top" src="{{asset('admin-assets/img/default-150x150.png')}}" alt="">
												@endif
											   
											</a>
											<a class="whishlist" onclick="handleWishList({{$product->id}})" href="javascript:void(0);">
												<i class="far fa-heart"></i>
											</a>                            

											<div class="product-action">
												@if ($product->track_qty == 'Yes' && $product->qty >0)
												<a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}})">
													<i class="fa fa-shopping-cart"></i> Add To Cart
												</a>  
												@else
												<a class="btn btn-dark" href="javascript:void(0);">
													Out of stock
												</a>  
												@endif
												{{-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}})">
													<i class="fa fa-shopping-cart"></i> Add To Cart
												</a>                             --}}
											</div>
										</div>                        
										<div class="card-body text-center mt-3">
											<a class="h6 link" href="#">{{$product->title}}</a>
											<div class="price mt-2">
												<span class="h5"><strong>${{$product->price}}</strong></span>
												@if (!empty($product->compare_price))
												<span class="h6 text-underline"><del>${{$product->compare_price}}</del></span>
												@endif
												
											</div>
										</div>                        
									</div>                                               
								</div> 
							@endforeach 
						@endif
						  

						<div class="col-md-12 pt-5">
							<nav aria-label="Page navigation example">
								{{$products->withQueryString()->links()}}
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
</main>
@endsection

@section('customJs')
	<script>
		$(".js-range-slider").ionRangeSlider({
			type: "double",
			min: 0,
			max: 1000,
			step:10,
			from: {{$min_price}},
			to: {{$max_price}},
			grid: true,
			prefix: "$",
			max_postfix: "+",
			skin: "modern",
			onFinish:function(){
				apply_filters();
			}
		});
		let slider = $(".js-range-slider").data("ionRangeSlider");
		
		$('#sort').change(function(){
			apply_filters();
		});
		$('.brand-label').change(function(){
			apply_filters();
		});

		function apply_filters(){
			var brands = [];
			$.each($('.brand-label'),function(){
				if($(this).is(':checked') == true){
					brands.push($(this).val());
				}
			});
			var url = "{{url()->current()}}?";
			// apply brands filter
			if(brands.length > 0){
				url += "&brands="+brands.toString();
			}

			//Apply price slider filters
			url += "&price_min="+slider.result.from+"&price_max="+slider.result.to;
			
			if($('#search').val().length > 0){
				url += "&search="+$('#search').val();
			}
			// Apply sort filter
			url += "&sort="+$('#sort').val();
			
			window.location.href= url;
		}
	</script>
@endsection