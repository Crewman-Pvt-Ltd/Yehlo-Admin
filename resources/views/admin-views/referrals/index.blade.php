@extends('layouts.admin.app')

@section('title', translate('messages.create_referals'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/items.png') }}" class="w--22" alt="">
                </span>
                <span>
                    {{ translate('Create Referrals') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <form action="{{ route('admin.business-settings.referrals.create') }}" method="post" id="referal_form">
            @csrf

            <div class="row g-2">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div id="default-form">

                                <div class="form-group">
                                    <label class="input-label"
                                        for="referralPrefix">{{ translate('messages.referral_code_prefix') }}</label>
                                    <input type="text" name="referral_prefix" id="referralPrefix" class="form-control"
                                        placeholder="{{ translate('messages.enter_prefix') }}">
                                </div>
                                <input type="hidden" name="referral_code" id="referralCode">

                                <input type="hidden" name="lang[]" value="default">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" id="submitButton"
                            class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card mt-3">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">Referral List<span
                            class="badge badge-soft-dark ml-2" id="itemCount">{{ $referrals->total() }}</span></h5>

                    <form class="search-form">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input type="search" name="search" value="{{ request()?->search ?? null }}"
                                class="form-control min-height-45"
                                placeholder="{{ translate('messages.search_referrals') }}"
                                aria-label="{{ translate('messages.ex_:_referrals') }}">
                            <input type="hidden" name="position" value="0">
                            <button type="submit" class="btn btn--secondary min-height-45"><i
                                    class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    @if (request()->get('search'))
                        <button type="reset" class="btn btn--primary ml-2 location-reload-to-category"
                            data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                    @endif
                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40"
                            href="javascript:;"
                            data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                            <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                            <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                            <a id="export-excel" class="dropdown-item" href="#">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item" href="#">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>


            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-align-middle"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('sl') }}</th>
                                <th class="border-0">{{ translate('refferal_code') }}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                            @foreach ($referrals as $key => $referral)
                                <tr>
                                    <td>{{ $key + $referrals->firstItem() }}</td>
                                    <td>{{ $referral->referral_code }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if (count($referrals) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $referrals->appends($_GET)->links() !!}
            </div>
            @if (count($referrals) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
        </div>

    </div>

    @push('script_2')


        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}", {
                    closeButton: true,
                    progressBar: true,
                });
            </script>
        @endif
    @endpush
@endsection
