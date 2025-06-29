@extends('admin.layout.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Products</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('products.create')}}" class="btn btn-primary">New Product</a>
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
        <div class="card">
            <form action="" method="get">
                
                <div class="card-header">
                    <div class="card-title">
                        <a href="{{route('rating.list')}}" class="btn btn-default btn-sm">Reset</a>
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
                            <th width="60">ID</th>
                            <th width="80">Username</th>
                            <th>Email</th>
                            <th>Proudct Name</th>
                            <th>Comment</th>
                            <th width="100">Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($ratings->isNotEmpty())
                        @foreach ($ratings as $item)
                       
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->username}}</td>
                            <td>Name</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->comment}}</td>											
                            <td>
                                @if($item->status == 1)
                                    <svg class="text-success-500 h-6 w-6 text-success" onclick="updateStatus(0,{{$item->id}})" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @else
                                    <svg class="text-danger h-6 w-6" onclick="updateStatus(1,{{$item->id}})" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @endif
                            </td>
                            <td>
                                
                                <a href="#"  onclick="deleteProduct({{$item->id}})" class="text-danger w-4 h-4 mr-1">
                                    <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                      </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>No product Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{$ratings->links()}}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJs')
<script>
    function updateStatus(status,rid){
        if(confirm('Are you sure you want to change the status')){
            $.ajax({
                url:'{{route("rating.update.status")}}',
                method:'post',
                data:{rid:rid,status:status},
                dataType:'json',
                success:function(res){
                    window.location.href="{{route('rating.list')}}";
                }
            });
        }
        
    }
    function deleteProduct(id){
        var url = "{{route('rating.delete','ID')}}";
        var newUrl = url.replace('ID',id);
        // alert(newUrl);
        if(confirm('Are you sure you want to delete')){
            $.ajax({
            url: newUrl,
            method:'delete',
            data:{},
            dataType:'json',
            success:function(res){
                if(res['status']){
                    window.location.href="{{route('rating.list')}}";
                }
            }
            
        });
        }
    }
 
</script>
@endsection