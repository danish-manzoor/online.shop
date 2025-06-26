@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item">Fortogt Password</li>
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
                <form action="{{route('front.accounts.forgotpassword')}}" method="post">
                    @csrf
                    <h4 class="modal-title">Forgot Password</h4>
                    <div class="form-group">
                        <input type="text" value="{{old('email')}}" class="form-control  @error('email') is-invalid @enderror" name="email" placeholder="Email" required="required">
                        @error('email')
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