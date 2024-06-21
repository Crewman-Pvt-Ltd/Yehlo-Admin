@extends('layouts.admin.app')

@section('title', translate('New_Brand_requests'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center g-2">
                <div class="col-md-9 col-12">
                    <h1 class="page-header-title">

                        <span>
                        </span>
                    </h1>
                </div>

            </div>

        </div>

        <div class="card mt-2">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">Brand List<span class="badge badge-soft-dark ml-2" id="itemCount"></span></h5>

                    {{-- <form class="search-form">
                      
                        <div class="input-group input--group">
                            <input id="datatableSearch" name="search" value="" type="search" class="form-control"
                                placeholder="Ex : search sub categories" aria-label="Ex : sub categories">
                            <input type="hidden" name="position" value="1">

                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                       
                    </form> --}}
                    <!-- Unfold -->
                    {{-- <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40"
                            href="javascript:;"
                            data-hs-unfold-options="{
                                    &quot;target&quot;: &quot;#usersExportDropdown&quot;,
                                    &quot;type&quot;: &quot;css-animation&quot;
                                }"
                            data-hs-unfold-target="#usersExportDropdown" data-hs-unfold-invoker="">
                            <i class="tio-download-to mr-1"></i> Export
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right hs-unfold-hidden hs-unfold-content-initialized hs-unfold-css-animation animated hs-unfold-reverse-y"
                            data-hs-target-height="131.512" data-hs-unfold-content=""
                            data-hs-unfold-content-animation-in="slideInUp" data-hs-unfold-content-animation-out="fadeOut"
                            style="animation-duration: 300ms;">

                            <span class="dropdown-header">Download options</span>
                            <a id="export-excel" class="dropdown-item" href="">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="http://localhost/yehlo_server/admin/public/assets/admin/svg/components/excel.svg"
                                    alt="Image Description">
                                Excel
                            </a>
                            <a id="export-csv" class="dropdown-item" href="">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="http://localhost/yehlo_server/admin/public/assets/admin/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                                .Csv
                            </a>

                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options="{
                            &quot;search&quot;: &quot;#datatableSearch&quot;,
                            &quot;entries&quot;: &quot;#datatableEntries&quot;,
                            &quot;isResponsive&quot;: false,
                            &quot;isShowPaging&quot;: false,
                            &quot;paging&quot;:false,
                        }">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Sl</th>
                                <th class="border-0">Vendor</th>
                                <th class="border-0 w--1">Brand Name</th>
                                <th class="border-0 w--1">Image</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands->where('created_by', 'vendor') as $key => $brand)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $brand['vendor_id'] }}</td>
                                    <td>{{ $brand['name'] }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/app/public/brand/' . $brand['image']) }}"
                                            alt="Brand Image" style="max-width: 100px; max-height: 100px;">
                                    </td>
                                    <td class="text-center">{{ $brand['status'] }}</td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            {{-- <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn"
                                                data-toggle="tooltip" data-placement="top" data-original-title=""
                                                href="">
                                                <i class="tio-invisible"></i>
                                            </a> --}}

                                            <a class="btn action-btn btn--primary btn-outline-primary request_alert"
                                                data-original-title="{{ translate('messages.approve') }}"
                                                data-url="{{ route('admin.brand-approve', ['id' => $brand['id']]) }}"
                                                data-message="{{ translate('messages.you_want_to_approve_this_product') }}"
                                                href="javascript:">
                                                <i class="tio-done font-weight-bold"></i>
                                            </a>


                                            <a class="btn action-btn btn--danger btn-outline-danger cancelled_status request_alert"data-original-title="{{ translate('messages.deny') }}"
                                                data-url="{{ route('admin.brand.deny', ['id' => $brand['id']]) }}"
                                                data-message="{{ translate('you_want_to_deny_this_product') }}"
                                                href="javascript:">
                                                <i class="tio-clear font-weight-bold"></i>
                                            </a>

                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                href="javascript:" data-id="brand-{{ $brand['id'] }}"
                                                data-message="{{ translate('Want to delete this brand') }}">
                                                <i class="tio-delete-outlined"></i>
                                            </a>

                                            <form action="{{ route('admin.brand.destroy', $brand['id']) }}" method="post"
                                                id="brand-{{ $brand['id'] }}">
                                                @csrf
                                                @method('delete')
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

    </div>
@endsection
