@extends('layouts.vendor.app')

@section('title',translate('messages.category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/categories.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{-- {{translate('messages.category_list')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$categories->total()}}</span> --}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('vendor.brand-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_name">Brand Name</label>
                                <input type="text" name="brand_name" class="form-control" placeholder="Enter brand name">
                            </div>
                            <div class="form-group">
                                <label for="brand_class">Brand Class</label>
                                <input type="text" name="brand_class" class="form-control" placeholder="Enter brand class">
                            </div>
                          
                        </div>
    
                        <div class="col-md-6">
                            <div class="h-100 d-flex align-items-center flex-column">
                                <label class="mb-3 text-center">Image<small class="text-danger">* ( {{translate('messages.ratio')}} 1:1)</small></label>
                                <label class="text-center my-auto position-relative d-inline-block">
                                    <img class="img--176 border" id="viewer"
                                        @if(isset($brand))
                                        src="{{asset('storage/app/public/brand')}}/{{$brand['image']}}"
                                        @else
                                        src="{{asset('public/assets/admin/img/upload-img.png')}}"
                                        @endif
                                        alt="image"/>
                                    <div class="icon-file-group">
                                        <div class="icon-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input read-url"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                                <i class="tio-edit"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
    
    
                    <div class="row">
                        <div class="col-md-6">
                          
                             <div class="form-group">
                                <label for="trademark">Trademark</label>
                                <input type="text" name="trademark" class="form-control" placeholder="Enter trademark">
                            </div>
                           
                        </div>
    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="file">File</label>
                                <input type="file" name="file" class="form-control" placeholder="Choose file">
                            </div> 
                        </div>
                    </div>
    
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    
        <div class="card mt-2">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">Brand List<span class="badge badge-soft-dark ml-2" id="itemCount"></span></h5>
    
                    <form class="search-form">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch" name="search" value="" type="search" class="form-control" placeholder="Ex : search sub categories" aria-label="Ex : sub categories">
                            <input type="hidden" name="position" value="1">
                           
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                                        <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;" data-hs-unfold-options="{
                                    &quot;target&quot;: &quot;#usersExportDropdown&quot;,
                                    &quot;type&quot;: &quot;css-animation&quot;
                                }" data-hs-unfold-target="#usersExportDropdown" data-hs-unfold-invoker="">
                            <i class="tio-download-to mr-1"></i> Export
                        </a>
    
                        <div id="usersExportDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right hs-unfold-hidden hs-unfold-content-initialized hs-unfold-css-animation animated hs-unfold-reverse-y" data-hs-target-height="131.512" data-hs-unfold-content="" data-hs-unfold-content-animation-in="slideInUp" data-hs-unfold-content-animation-out="fadeOut" style="animation-duration: 300ms;">
    
                            <span class="dropdown-header">Download options</span>
                            <a id="export-excel" class="dropdown-item" href="">
                                <img class="avatar avatar-xss avatar-4by3 mr-2" src="http://localhost/yehlo_server/admin/public/assets/admin/svg/components/excel.svg" alt="Image Description">
                                Excel
                            </a>
                            <a id="export-csv" class="dropdown-item" href="">
                                <img class="avatar avatar-xss avatar-4by3 mr-2" src="http://localhost/yehlo_server/admin/public/assets/admin/svg/components/placeholder-csv-format.svg" alt="Image Description">
                                .Csv
                            </a>
    
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options="{
                            &quot;search&quot;: &quot;#datatableSearch&quot;,
                            &quot;entries&quot;: &quot;#datatableEntries&quot;,
                            &quot;isResponsive&quot;: false,
                            &quot;isShowPaging&quot;: false,
                            &quot;paging&quot;:false,
                        }">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Sl</th>
                                
                                <th class="border-0 w--1">Brand Name</th>
                                <th class="border-0 text-center">Image</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands->where('created_by', 'vendor') as $key => $brand)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                  
                                    <td>{{ $brand->brand_name }}</td>
                                    <td class="text-center">
                                        <img src="{{asset('storage/app/public/brand')}}/{{$brand['image']}}" alt="Brand Image" style="max-width: 100px; max-height: 100px;">
                                    </td>
                                  
                                    <td class="text-center">
                                        @if ($brand['is_approved'] == 0)
                                            Pending
                                        @elseif ($brand['is_approved'] == 1)
                                            Approved
                                        @elseif ($brand['is_approved'] == 2)
                                            Rejected
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary"
                                            href="{{ route('vendor.brand-edit', [$brand['id']]) }}"
                                            title=""><i
                                                class="tio-edit"></i>
                                        </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                            data-id="brand-{{$brand['id']}}" data-message="{{ translate('Want to delete this brand') }}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('vendor.brand-destroy',[$brand['id']])}}" method="post" id="brand-{{$brand['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                            <hr>
                      
                    </div>
    </div>
    
    @endsection
    
    @push('script_2')
        <script src="{{asset('public/assets/admin')}}/js/view-pages/category-index.js"></script>
        <script>
        "use strict";
          
            $("#customFileEg1").change(function() {
                readURL(this);
                $('#viewer').show(1000)
            });
            $('#reset_btn').click(function(){
                $('#exampleFormControlSelect1').val(null).trigger('change');
                    $('#viewer').attr('src', "{{asset('public/assets/admin/img/upload-img.png')}}");
            })
        </script>
    @endpush
    

