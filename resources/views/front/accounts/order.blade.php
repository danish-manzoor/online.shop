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
                            <div class="table-responsive">
                                <table class="table">
                                    <thead> 
                                        <tr>
                                            <th>Orders #</th>
                                            <th>Date Purchased</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($orders->isNotEmpty())
                                            @foreach ($orders as $item)
                                                
                                            
                                        <tr>
                                            <td>
                                                <a href="{{route('front.accounts.orderDetails',$item->id)}}">{{$item->id}}</a>
                                            </td>
                                            <td>{{\Carbon\Carbon::parse($item->created_at)->format('d M, Y')}}</td>
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
                                        </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">No order Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection