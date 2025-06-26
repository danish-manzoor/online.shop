@extends('admin.layout.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Change Password</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- <a href="{{route('pages.list')}}" class="btn btn-primary">Back</a> --}}
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
        <form  method="post" id="pageForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="mb-3">               
                                <label for="name">Old Password</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">               
                                <label for="name">New Password</label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">               
                                <label for="name">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Old Password" class="form-control">
                                <p></p>
                            </div>
                        </div>
							
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" id="btn_create">Create</button>
                {{-- <a href="{{route('pages.list')}}" class="btn btn-outline-dark ml-3">Cancel</a> --}}
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
            $('#pageForm').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                $.ajax({
                    url:'{{route("settings.changePassword")}}',
                    method:'post',
                    data:element.serializeArray(),
                    dataType:'json',
                    success:function(res){
                        $('#btn_create').prop('disabled',false);
                        if(res['status'] == true){
                            window.location.href="{{route('settings.passwrod.change')}}";
                        }else{
                            var errors = res['errors'];
                        if(errors['confirm_password']){
                            $('#confirm_password').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['confirm_password']);
                        }else{
                            $('#confirm_password').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

                        }

                        if(errors['old_password']){
                            $('#old_password').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['old_password']);
                        }else{
                            $('#old_password').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

                        }

                        if(errors['new_password']){
                            $('#new_password').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['new_password']);
                        }else{
                            $('#new_password').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

                        }
                        }
                        
                    },error:function(XqHR, error){
                        console.log('something went wrong');
                    }
                });
            });



            $('#name').change(function(){
                $('#btn_create').prop('disabled',true);
                var name = $(this).val();
                $.ajax({
                    url:'{{route("getSlug")}}',
                    method:'GET',
                    data:{title:name},
                    dataType:'json',
                    success:function(response){
                        $('#btn_create').prop('disabled',false);
                        console.log(response);
                        if(response['status'] == true){
                            $('#slug').val(response['slug']);
                        }
                    }
                });
            });
       });

       
    </script>
@endsection