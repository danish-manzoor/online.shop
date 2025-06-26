@extends('admin.layout.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Discount</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('discount.list')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form  method="post" id="createDiscountForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">Code Name</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Code Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses">Max Uses</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_user_uses">Max User Uses</label>
                                <input type="number" name="max_user_uses" id="max_user_uses" class="form-control" placeholder="Max User Uses">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="percent">Percent</option>
                                    <option value="fixed">Fixed</option>
                                </select>	
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_amount">Discount Amount</label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="Amount">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_amount">Min Amount</label>
                                <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>	
                            </div>
                        </div>	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="starts_at">Starts at</label>
                                <input type="text" autocomplete="off" name="starts_at" id="starts_at" class="form-control" placeholder="Starts at">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at">Expires at</label>
                                <input type="text" autocomplete="off" name="expires_at" id="expires_at" class="form-control" placeholder="Expires at">	
                                <p></p>
                            </div>
                        </div>								
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" id="btn_create">Create</button>
                <a href="{{route('discount.list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
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
            $('#starts_at').datetimepicker({
                format:'Y-m-d H:i:s',
            });
            $('#expires_at').datetimepicker({
                format:'Y-m-d H:i:s',
            });
            $('#createDiscountForm').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                $.ajax({
                    url:'{{route("discount.store")}}',
                    method:'post',
                    data:element.serializeArray(),
                    dataType:'json',
                    success:function(res){
                        $('#btn_create').prop('disabled',false);
                        if(res['status'] == true){
                            window.location.href="{{route('discount.list')}}";
                        }else{
                            var errors = res['errors'];
                            if(errors['code']){
                                $('#code').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['code']);
                            }else{
                                $('#code').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }
                            if(errors['discount_amount']){
                                $('#discount_amount').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['discount_amount']);
                            }else{
                                $('#discount_amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }

                            if(errors['starts_at']){
                                $('#starts_at').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['starts_at']);
                            }else{
                                $('#starts_at').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }

                            if(errors['expires_at']){
                                $('#expires_at').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['expires_at']);
                            }else{
                                $('#expires_at').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
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