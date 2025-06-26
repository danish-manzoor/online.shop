@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item">Reset Password</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> {{Session::get('success')}}
                </div>
            @endif


            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Error!</strong> {{Session::get('error')}}
                </div>
            @endif
            
            <div class="login-form">    
                <form action="{{route('front.resetpasswordprocess')}}" method="post">
                    @csrf
                    <h4 class="modal-title">Reset Password</h4>
                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="form-group">
                        <input type="password" value="{{old('new_password')}}" class="form-control  @error('new_password') is-invalid @enderror" name="new_password" placeholder="New Password" required="required">
                        @error('new_password')
                            <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" value="{{old('confirm_password')}}" class="form-control  @error('confirm_password') is-invalid @enderror" name="confirm_password" placeholder="Confirm Password" required="required">
                        @error('confirm_password')
                            <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    
                     
                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">              
                </form>			
                <div class="text-center small"> <a href="{{route('front.accounts.login')}}">Login</a></div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJs')
    
@endsection