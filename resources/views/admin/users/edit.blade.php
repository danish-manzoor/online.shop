@extends('admin.layout.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('users.list')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form  method="post" id="usersForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" value="{{!empty($user->name)?$user->name:''}}" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="text" value="{{!empty($user->email)?$user->email:''}}" name="email" id="email" class="form-control" placeholder="Email">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Password</label>
                                <input type="password"  name="password" id="password" class="form-control" placeholder="Password">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" value="{{!empty($user->phone)?$user->phone:''}}"  name="phone" id="phone" class="form-control" placeholder="Phone">	
                                <p></p>
                            </div>
                        </div>
                        	
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{$user->status == 1?'selected':''}} value="1">Active</option>
                                    <option  {{$user->status == 0?'selected':''}} value="0">Block</option>
                                </select>	
                            </div>
                        </div>	
                        								
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" id="btn_create">Update</button>
                <a href="{{route('users.list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
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
            $('#usersForm').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                $.ajax({
                    url:'{{route("users.update",$user->id)}}',
                    method:'put',
                    data:element.serializeArray(),
                    dataType:'json',
                    success:function(res){
                        $('#btn_create').prop('disabled',false);
                        if(res['status'] == true){
                            window.location.href="{{route('users.list')}}";
                        }else{
                            var errors = res['errors'];
                            if(errors['name']){
                                $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                            }else{
                                $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }
                            if(errors['email']){
                                $('#email').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email']);
                            }else{
                                $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }
                            if(errors['password']){
                                $('#password').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['password']);
                            }else{
                                $('#password').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }
                            if(errors['phone']){
                                $('#phone').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['phone']);
                            }else{
                                $('#phone').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
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