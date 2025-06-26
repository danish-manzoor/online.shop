@extends('admin.layout.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Shipping</h1>
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
        <form  method="post" id="shippingForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Country</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @if ($countries->isNotEmpty())
                                        @foreach ($countries as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                
                                    @endif
                                    <option value="rest_of_world">Rest of the World</option>
                                </select>	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-primary" id="btn_create">Create</button>
                            </div>
                        </div>
						
                    </div>
                </div>							
            </div>
            <div class="card">
                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($shipping != null)
                                @foreach ($shipping as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>
                                        @if ($item->country_name != null)
                                            {{$item->country_name}}
                                        @else    
                                            {{'Rest of World'}}
                                        @endif
                                    </td>
                                    <td>${{$item->amount}}</td>
                                    <td>
                                        <a href="{{route('shipping.edit',$item->id)}}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                        <a href="#" onclick="deleteShipping({{$item->id}})" class="text-danger w-4 h-4 mr-1">
                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="5">No Record Found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>										
                </div>
            </div>
           
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@section('customJs')
    <script>
       $(document).ready(function(){
            $('#shippingForm').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                $.ajax({
                    url:'{{route("shipping.store")}}',
                    method:'post',
                    data:element.serializeArray(),
                    dataType:'json',
                    success:function(res){
                        $('#btn_create').prop('disabled',false);
                        if(res['status'] == true){
                            window.location.href="{{route('shipping.create')}}";
                        }else{
                            var errors = res['errors'];
                            if(errors['country']){
                                $('#country').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['country']);
                            }else{
                                $('#country').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }
                            if(errors['amount']){
                                $('#amount').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['amount']);
                            }else{
                                $('#amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }
                        }
                        
                    },error:function(XqHR, error){
                        console.log('something went wrong');
                    }
                });
            });

       });
    function deleteShipping(id){
        var url = "{{route('shipping.delete','ID')}}";
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
                    window.location.href="{{route('shipping.create')}}";
                }
            }
            
        });
        }
    }
    </script>
@endsection