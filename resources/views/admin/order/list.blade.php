@extends('admin.layout.app')


@section('content')
    				<!-- Content Header (Page header) -->
                    <section class="content-header">					
                        <div class="container-fluid my-2">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Orders</h1>
                                </div>
                                <div class="col-sm-6 text-right">
                                </div>
                            </div>
                        </div>
                        <!-- /.container-fluid -->
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        <!-- Default box -->
                        <div class="container-fluid">
                            <div class="card">
                                <form action="" method="get">
                
                                    <div class="card-header">
                                        <div class="card-title">
                                            <a href="{{route('order.list')}}" class="btn btn-default btn-sm">Reset</a>
                                        </div>
                                        <div class="card-tools">
                                            <div class="input-group input-group" style="width: 250px;">
                                                <input type="text" value="{{Request::get('keyword')}}" name="keyword" class="form-control float-right" placeholder="Search">
                            
                                                <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="card-body table-responsive p-0">								
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Orders #</th>											
                                                <th>Customer</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Date Purchased</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($orders->isNotEmpty())
                                                @foreach ($orders as $item)

                                            <tr>
                                                <td><a href="{{route('order.details',$item->id)}}">{{$item->id}}</a></td>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->email}}</td>
                                                <td>{{$item->mobile}}</td>
                                                <td>
                                                    @if ($item->status == 'delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                    @elseif($item->status == 'pending')
                                                    <span class="badge bg-danger">Pending</span>
                                                    @elseif($item->status == 'shipped')
                                                    <span class="badge bg-info">Shipped</span>
                                                    @else
                                                    <span class="badge bg-warning">Cancelled</span>
                                                    @endif
                                                   
                                                </td>
                                                <td>${{number_format($item->grand_total,2)}}</td>
                                                <td>
                                                    {{\Carbon\Carbon::parse($item->created_at)->format('d M, Y')}}
                                                </td>																				
                                            </tr>
                                            @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5">No orders Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>										
                                </div>
                                <div class="card-footer clearfix">
                                    {{$orders->links()}}
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </section>
                    <!-- /.content -->
@endsection