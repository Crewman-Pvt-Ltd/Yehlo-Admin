@extends('layouts.admin.app')

@section('title', translate('messages.Add new brand'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/category.png') }}" class="w--20" alt="">
                </span>
                <span>
                    Add New Brand
                </span>
            </h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.brand-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_name">Brand Name</label>
                                <input type="text" name="brand_name"value="{{isset($brand) ? $brand->brand_name : ""  }}" class="form-control" placeholder="Enter brand name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            @if ($brand->position == 0)
                            <div class="h-100 d-flex align-items-center flex-column">
                                <label class="mb-4">{{translate('messages.image')}}
                                    <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1 )</small>
                                </label>
                                <label class="text-center my-auto position-relative d-inline-block">
                                    <img class="img--176 border" id="viewer"
                                    src="{{\App\CentralLogics\Helpers::onerror_image_helper($brand['image'], asset('storage/app/public/brand/').'/'.$brand['image'], asset('public/assets/admin/img/upload-img.png'), 'brand/') }}"
                                        data-onerror-image="{{asset('public/assets/admin/img/upload-img.png')}}"
                                        alt=""/>
                                    <div class="icon-file-group">
                                        <div class="icon-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input read-url"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <i class="tio-edit"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">Reset</button>
                        <button type="submit" class="btn btn--primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

      
    </div>

@endsection
@push('script_2')
    <script src="{{asset('public/assets/admin')}}/js/view-pages/brand-index.js"></script>
    <script>
        "use strict";
        $('#reset_btn').click(function(){
            $('#module_id').val("{{ $brand->module_id }}").trigger('change');
            $('#viewer').attr('src', "{{\App\CentralLogics\Helpers::onerror_image_helper($brand['image'], asset('storage/app/public/brand/').'/'.$brand['image'], asset('public/assets/admin/img/upload-img.png'), 'brand/') }}");
        })
        $("#customFileEg1").change(function() {
            readURL(this);
            $('#viewer').show(1000)
        });



    </script>
@endpush