@extends('admin.layout.app')


@section('content')
   				<!-- Content Header (Page header) -->
                   <section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Order: #{{$order->id}}</h1>
							</div>
							<div class="col-sm-6 text-right">
                                <a href="{{route('order.list')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
                        @include('admin.message')
						<div class="row">
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header pt-3">
                                        <div class="row invoice-info">
                                            <div class="col-sm-4 invoice-col">
                                            <h1 class="h5 mb-3">Shipping Address</h1>
                                            <address>
                                                <strong>{{$order->fname.' '.$order->lname}}</strong><br>
                                                {{$order->address}}<br>
                                                {{$order->city}}, {{$order->state}} {{$order->zip}}<br>
                                                Phone: {{$order->mobile}}<br>
                                                Email: {{$order->email}}
                                            </address>
                                            </div>
                                            
                                            
                                            
                                            <div class="col-sm-4 invoice-col">
                                                <b>Invoice #007612</b><br>
                                                <br>
                                                <b>Order ID:</b> 4F3S8J<br>
                                                <b>Total:</b> $90.40<br>
                                                <b>Status:</b> 
                                                @if ($order->status == 'delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                @elseif($order->status == 'pending')
                                                    <span class="badge bg-danger">Pending</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge bg-info">Shipped</span>
                                                @else
                                                    <span class="badge bg-warning">Cancelled</span>
                                                @endif
                                                {{-- <span class="text-success">Delivered</span> --}}
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-3">								
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th width="100">Price</th>
                                                    <th width="100">Qty</th>                                        
                                                    <th width="100">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($orderItems->isNotEmpty())
                                                @foreach ($orderItems as $item)
                                                <tr>
                                                    <td>{{$item->name}}</td>
                                                    <td>${{number_format($item->price,2)}}</td>                                        
                                                    <td>{{($item->qty)}}</td>
                                                    <td>${{number_format($item->total,2)}}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4">No Order Items found</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <th colspan="3" class="text-right">Subtotal:</th>
                                                    <td>${{number_format($order->subtotal,2)}}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th colspan="3" class="text-right">Shipping:</th>
                                                    <td>${{number_format($order->shipping,2)}}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">Discount @if ($order->discount>0) ({{$order->coupon_code}})@endif:</th>
                                                    <td>${{number_format($order->discount,2)}}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">Grand Total:</th>
                                                    <td>${{number_format($order->grand_total,2)}}</td>
                                                </tr>
                                            </tbody>
                                        </table>								
                                    </div>                            
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <form action="" method="post" id="OrderUpdateForm">
                                        <div class="card-body">
                                            <h2 class="h4 mb-3">Order Status</h2>
                                            <div class="mb-3">
                                                <select name="status" id="status" class="form-control">
                                                    <option {{($order->status == 'pending')?'selected':''}}  value="pending">Pending</option>
                                                    <option {{($order->status == 'shipped')?'selected':''}} value="shipped">Shipped</option>
                                                    <option {{($order->status == 'delivered')?'selected':''}} value="delivered">Delivered</option>
                                                    <option {{($order->status == 'cancelled')?'selected':''}} value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" id="shipped_date" name="shipped_date" class="form-control" placeholder="Shipped Date">
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card">
                                   <form action="" method="post" id="sendInvoice" name="sendInvoice">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Send Inovice Email</h2>
                                        <div class="mb-3">
                                            <select name="userType" id="userType" class="form-control">
                                                <option value="customer">Customer</option>                                                
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary">Send</button>
                                        </div>
                                    </div>
                                   </form>
                                </div>
                            </div>
                        </div>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content --> 
@endsection

@section('customJs')
    <script>
        $('#shipped_date').datetimepicker({
                format:'Y-m-d H:i:s',
        });

        $('#OrderUpdateForm').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                if(confirm('Are you sure you want to update the status')){
                    $.ajax({
                        url:'{{route("order.update",$order->id)}}',
                        method:'post',
                        data:element.serializeArray(),
                        dataType:'json',
                        success:function(res){
                            $('#btn_create').prop('disabled',false);
                            if(res['status'] == true){
                                window.location.href="{{route('order.details',$order->id)}}";
                            }else{
                                
                            }
                            
                        },error:function(XqHR, error){
                            console.log('something went wrong');
                        }
                    });
                }
        });


        $('#sendInvoice').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                if(confirm('Are you sure you want to send Invoice')){
                    $.ajax({
                        url:'{{route("order.orderInvoice",$order->id)}}',
                        method:'POST',
                        data:element.serializeArray(),
                        dataType:'json',
                        success:function(res){
                            $('#btn_create').prop('disabled',false);
                            if(res['status'] == true){
                                window.location.href="{{route('order.details',$order->id)}}";
                            }
                            
                        },error:function(XqHR, error){
                            console.log('something went wrong');
                        }
                    });
                }
            });

            
    </script>
@endsection