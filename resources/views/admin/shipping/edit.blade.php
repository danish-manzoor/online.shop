@extends('admin.layout.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Shipping</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('shipping.create')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form  method="post" id="updateForm">
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
                                            <option {{($item->id == $shipping->country_id?'selected':'')}} value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Amount</label>
                                <input type="text" value="{{$shipping->amount}}" name="amount" id="amount" class="form-control" placeholder="Amount">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-primary" id="btn_create">Update</button>
                            </div>
                        </div>
						
                    </div>
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
            $('#updateForm').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                $.ajax({
                    url:'{{route("shipping.update",$shipping->id)}}',
                    method:'put',
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

    </script>
@endsection