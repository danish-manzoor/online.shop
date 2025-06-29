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
            @include('front.accounts.message')
            <div class="row">
                <div class="col-md-3">
                    @include('front.accounts.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                        </div>
                        <div class="card-body p-4">
                            <form action="" method="post" id="updatePassword">
                                <div class="row">
                                    <div class="mb-3">               
                                        <label for="name">Old Password</label>
                                        <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">               
                                        <label for="name">New Password</label>
                                        <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">               
                                        <label for="name">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Old Password" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
@section('customJs')
    <script>
        $('#updatePassword').submit(function(e){
            e.preventDefault();
            $.ajax({
                url:'{{route("change.password.process")}}',
                method:'post',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status'] == true){
                        window.location.href="{{route('change.password')}}";
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
                }
            });
        });
    </script>
@endsection