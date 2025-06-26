@extends('front.layouts.app')


@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">

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

            
            @if (Cart::count() > 0)
            <div class="row">
               
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::content() as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start">
                                            @if (!empty($item->options->productImage->image))
                                            <img src="{{asset('uploads/products/small/'.$item->options->productImage->image)}}" width="" height="">
                                            @else
                                            <img src="images/product-1.jpg" width="" height="">
                                            @endif
                                            

                                            <h2>{{$item->name}}</h2>
                                        </div>
                                    </td>
                                    <td>${{$item->price}}</td>
                                    <td>
                                        <div class="input-group quantity mx-auto" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{$item->rowId}}">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm  border-0 text-center" value="{{$item->qty}}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{$item->rowId}}">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        ${{$item->price * $item->qty}}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="destroyCart('{{$item->rowId}}')"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>      
                                @endforeach                       
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">            
                    <div class="card cart-summery">
                        <div class="sub-title">
                            <h2 class="bg-white">Cart Summery</h3>
                        </div> 
                        <div class="card-body">
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>${{Cart::subtotal()}}</div>
                            </div>
                            {{-- <div class="d-flex justify-content-between pb-2">
                                <div>Shipping</div>
                                <div>$0</div>
                            </div> --}}
                            {{-- <div class="d-flex justify-content-between pb-2">
                                <div>Tax</div>
                                <div>${{Cart::tax()}}</div>
                            </div> --}}
                            <div class="d-flex justify-content-between summery-end">
                                <div>Total</div>
                                <div>${{Cart::total()}}</div>
                            </div>
                            <div class="pt-5">
                                <a href="{{route('front.checkout')}}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>     
                    
                </div>
            </div>
            @else
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">You'r cart is empty</h2>
                    </div>
                </div>
                
            </div>
            @endif
        </div>
    </section>
</main>
@endsection

@section('customJs')
    <script>
        function destroyCart(id){
            if(confirm('Are you sure you want to remove this cart?')){
                $.ajax({
                    url:'{{route("front.cart.delete")}}',
                    method:'post',
                    data:{id:id},
                    dataType:'json',
                    success:function(res){
                        window.location.href="{{route('front.cart')}}";
                    }
                });
            }
        }
        $('.add').click(function(){
            var rowId = $(this).data('id');
            var qtyElemnt = $(this).parent().prev();
            var qty       = qtyElemnt.val();
            if(qty < 10){
                qtyElemnt.val(parseInt(qty)+1);
                updateCart(rowId,qtyElemnt.val());
            }
    
        });

        $('.sub').click(function(){
            var rowId = $(this).data('id');
            var qtyElemnt = $(this).parent().next();
            var qty       = qtyElemnt.val();
            if(qty > 1){
                qtyElemnt.val(parseInt(qty)-1);
                updateCart(rowId,qtyElemnt.val());
            }
    
        });

        function updateCart(id,qty){
            $.ajax({
                url:'{{route("front.cart.update")}}',
                method:'post',
                data:{id:id,qty:qty},
                dataType:'json',
                success:function(res){
                    window.location.href="{{route('front.cart')}}";
                }
            });
        }
    </script>
@endsection