@extends('front.layouts.app')


@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Home</a></li>
                    <li class="breadcrumb-item">{{$page->name}}</li>
                </ol>
            </div>
        </div>
    </section>
@if ($page->slug == 'contact-us')
<section class=" section-10">
    <div class="container">
        <h1 class="my-3">Love to Hear From You</h1> 
    </div>
</section>
<section>
    <div class="container">          
        <div class="row">
            <div class="col-md-6 mt-3 pe-lg-5">
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content.</p>
                <address>
                Cecilia Chapman <br>
                711-2880 Nulla St.<br> 
                Mankato Mississippi 96522<br>
                <a href="tel:+xxxxxxxx">(XXX) 555-2368</a><br>
                <a href="mailto:jim@rock.com">jim@rock.com</a>
                </address>                    
            </div>

            <div class="col-md-6">
                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>{{Session::get('error')}}
                    </div>
                    @endif



                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>{{Session::get('success')}}
                    </div>
                    @endif
                <form class="shake" role="form" method="post" id="contactForm" name="contact-form">
                    <div class="mb-3">
                        <label class="mb-2" for="name">Name</label>
                        <input class="form-control" id="name" type="text" name="name"  data-error="Please enter your name">
                        <p class="help-block with-errors"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="mb-2" for="email">Email</label>
                        <input class="form-control" id="email" type="email" name="email"  data-error="Please enter your Email">
                        <p class="help-block with-errors"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="mb-2">Subject</label>
                        <input class="form-control" id="msg_subject" type="text" name="subject"  data-error="Please enter your message subject">
                        <p class="help-block with-errors"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="mb-2">Message</label>
                        <textarea class="form-control" rows="3" id="message" name="message"  data-error="Write your message"></textarea>
                        <p class="help-block with-errors"></p>
                    </div>
                  
                    <div class="form-submit">
                        <button class="btn btn-dark" type="submit" id="form-submit"><i class="material-icons mdi mdi-message-outline"></i> Send Message</button>
                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@else 
<section class=" section-10">
    <div class="container">
        <h1 class="my-3">{{$page->name}}</h1>
        @if (!empty($page->content))
           
            {!!$page->content !!}
        @endif

    </div>
</section>
@endif
    
</main>
@endsection

@section('customJs')
    <script>
        $('#contactForm').submit(function(e){
            e.preventDefault();
            $.ajax({
                url:'{{route("front.accounts.contact")}}',
                method:'post',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(res){
                    if(res['status'] == true){
                        window.location.href = "{{route('front.accounts.pages','contact-us')}}";
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
                        if(errors['subject']){
                            $('#msg_subject').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['subject']);
                        }else{
                            $('#msg_subject').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                        if(errors['message']){
                            $('#message').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['message']);
                        }else{
                            $('#message').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                    }
                }
            });
        });
    </script>
@endsection