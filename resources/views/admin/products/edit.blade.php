@extends('admin.layout.app')



@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('products.list')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="post" id="productForm">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" value="{{$products->title}}" name="title" id="title" class="form-control" placeholder="Title">	
                                            <p class="errors"></p>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" value="{{$products->slug}}" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                            <p class="errors"></p>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote">{{$products->short_description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{$products->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="shipping_returns">Shipping & returns</label>
                                            <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" >{{$products->shipping_returns}}</textarea>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>								
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>                                            
                                    </div>
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="row" id="image-gallary">
                            @if ($productImages->isNotEmpty())
                                @foreach ($productImages as $item)
                                <div class="col-md-3">
                                    <div class="card" id="image-gallary-container{{$item->id}}">
                                        <input type="hidden" name="imageArray[]" value="{{$item->id}}">    
                                        <img src="{{asset('uploads/products/small/'.$item->image)}}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <a href="javascript:void" onclick="removeImage({{$item->id}})" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                </div>
                                @endforeach
                                
                            @endif
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" value="{{$products->price}}" name="price" id="price" class="form-control" placeholder="Price">	
                                            <p class="errors"></p>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" value="{{$products->compare_price}}" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                            </p>	
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>								
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" value="{{$products->sku}}" name="sku" id="sku" class="form-control" placeholder="sku">	
                                            <p class="errors"></p>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" value="{{$products->barcode}}" name="barcode" id="barcode" class="form-control" placeholder="Barcode">	
                                        </div>
                                    </div>   
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" value="Yes" id="track_qty" name="track_qty" {{$products->track_qty == 'Yes'?'checked':''}}>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            </div>
                                        </div>
                                        <p class="errors"></p>
                                        <div class="mb-3">
                                            <input type="number" value="{{$products->qty}}" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">	
                                            <p class="errors"></p>
                                        </div>
                                        
                                    </div>                                         
                                </div>
                            </div>	                                                                      
                        </div>
                        Lorem 
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Related Products</h2>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select name="related_products[]" multiple id="related_products" class="form-control">
                                            @if ($relatedProducts != '')
                                                @foreach ($relatedProducts as $item)
                                                    <option selected value="{{$item->id}}">{{$item->title}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option {{$products->status == 1?'selected':''}} value="1">Active</option>
                                        <option {{$products->status == 0?'selected':''}} value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card">
                            <div class="card-body">	
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $item)
                                            <option {{$products->category_id == $item->id?'selected':''}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                            
                                        @endif
                                    </select>
                                    <p class="errors"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Select Sub Category</option>
                                        @if ($subCategories->isNotEmpty())
                                            @foreach ($subCategories as $item)
                                            <option {{$products->sub_category_id == $item->id?'selected':''}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                            
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select Brands</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $item)
                                            <option {{$products->brand_id == $item->id?'selected':''}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                            
                                        @endif
                                    
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option {{$products->status == 'No'?'selected':''}} value="No">No</option>
                                        <option {{$products->status == 'Yes'?'selected':''}} value="Yes">Yes</option>                                                
                                    </select>
                                    <p class="errors"></p>
                                </div>
                            </div>
                        </div>                                 
                    </div>
                </div>
                
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{route('products.list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
        </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $('#related_products').select2({
            ajax: {
                url: '{{route("products.related")}}',
                dataType:'json',
                tags:true,
                multiple:true,
                minimumInputLength:3,
                processResults: function (data) {
                    return {
                        results: data.tags
                    };
                }
            }
        });

        $('#productForm').submit(function(e){
            e.preventDefault();
            var fromArray = $(this).serializeArray();
            $.ajax({
                url:'{{route("products.update",$products->id)}}',
                method:'PUT',
                data:fromArray,
                dataType:'json',
                success:function(res){
                    if(res['status'] == true){
                        window.location.href = "{{route('products.list')}}";
                    }else{
                        var errors = res['errors'];
                        $('.errors').removeClass('invalid-feedback').html('');
                        $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                        $.each(errors,function(key,value){
                            // console.log(value);
                            $(`#${key}`).addClass('is-invalid')
                                        .siblings('p')
                                        .addClass('invalid-feedback')
                                        .html(value);
                        });
                    }
                },
                error:function(){
                    console.log('something went wrong');
                }
            });
        });
        $('#title').change(function(){
                $('#btn_create').prop('disabled',true);
                var name = $(this).val();
                $.ajax({
                    url:'{{route("getSlug")}}',
                    method:'GET',
                    data:{title:name},
                    dataType:'json',
                    success:function(response){
                        $('#btn_create').prop('disabled',false);
                        // console.log(response);
                        if(response['status'] == true){
                            $('#slug').val(response['slug']);
                        }
                    }
                });
        });

        $('#category').change(function(){
            var id = $(this).val();
            $.ajax({
                    url:'{{route("get.subcategory")}}',
                    method:'GET',
                    data:{id:id},
                    dataType:'json',
                    success:function(response){
                        if(response['status'] == true){
                            $('#sub_category').find('option').not(':first').remove();
                            $.each(response['subcategories'],function(key,item){
                                $('#sub_category').append(`<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                    }
                });
        });


        Dropzone.autoDiscover = false;    
        const dropzone = $("#image").dropzone({ 
            url:  "{{ route('products.upload.image') }}",
            maxFiles: 10,
            paramName: 'image',
            params:{product_id:'{{$products->id}}'},
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                var html = `<div class="col-md-3"><div class="card" id="image-gallary-container${response.image_id}">
                            <input type="hidden" name="imageArray[]" value="${response.image_id}">    
                            <img src="${response.image_path}" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <a href="javascript:void" onclick="removeImage(${response.image_id})" class="btn btn-danger">Delete</a>
                                </div>
                                </div></div>`;
                $('#image-gallary').append(html);

                //console.log(response)
            },
            complete:function(file){
                this.removeFile(file);
            }
        });
        function removeImage(id){
            $('#image-gallary-container'+id).remove();
            if(confirm('Are you sure you want to delete this image ?')){
                $.ajax({
                    url:'{{route("products.image.destroy")}}',
                    method:'POST',
                    data:{id:id},
                    dataType:'json',
                    success:function(res){
                        if(res['status'] == true){
                            alert(res.message);
                        }else{
                            alert(res.message);
                        }
                    }
                });
            }
        }
    </script>
@endsection