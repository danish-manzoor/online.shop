@extends('admin.layout.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('sub-category.list')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form  method="post" id="subcategoryForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">Select Category</option>
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p></p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text"  name="slug" id="slug" class="form-control" placeholder="Slug">	
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
                                <label for="showHome">Show Home</label>
                                <select name="showHome" id="showHome" class="form-control">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>	
                            </div>
                        </div>									
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" id="btn_create">Create</button>
                <a href="{{route('category.list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
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
            $('#subcategoryForm').submit(function(e){
                e.preventDefault();
                var element = $(this);
                $('#btn_create').prop('disabled',true);
                $.ajax({
                    url:'{{route("sub-category.store")}}',
                    method:'post',
                    data:element.serializeArray(),
                    dataType:'json',
                    success:function(res){
                        $('#btn_create').prop('disabled',false);
                        if(res['status'] == true){
                            window.location.href="{{route('sub-category.list')}}";
                        }else{
                            var errors = res['errors'];
                            if(errors['name']){
                                $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                            }else{
                                $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }
                            if(errors['slug']){
                                $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                            }else{
                                $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
                            }

                            if(errors['category']){
                                $('#category').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['category']);
                            }else{
                                $('#category').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                                
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

        Dropzone.autoDiscover = false;    
        const dropzone = $("#image").dropzone({ 
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url:  "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    </script>
@endsection