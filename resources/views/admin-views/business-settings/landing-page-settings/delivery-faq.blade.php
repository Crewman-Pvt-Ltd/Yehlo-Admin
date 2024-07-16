@extends('layouts.admin.app')

@section('title', 'Deliveryman FAQ')

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/landing.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        Deliveryman FAQ
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal"
                    data-target="#how-it-works">
                    <strong class="mr-2">{{ translate('See_how_it_works!') }}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-4 mt-2">
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.business-settings.landing-page-settings.top-menu-links.delivery-landing-page-links')
            </div>
        </div>

        @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
        @php($language = $language->value ?? null)
        @php($defaultLang = str_replace('_', '-', app()->getLocale()))
        @if ($language)
            <ul class="nav nav-tabs mb-4 border-0">
                <li class="nav-item">
                    <a class="nav-link lang_link active" href="#"
                        id="default-link">{{ translate('messages.default') }}</a>
                </li>

            </ul>
        @endif
        <div class="tab-content">
            <div class="tab-pane fade show active">
                <form action="{{ route('admin.business-settings.delivery-landing-page-settings', 'faq') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">

                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <label for="title" class="form-label">Questions</label>
                                    <input id="title" type="text" name="questions[]" class="form-control"
                                        placeholder="{{ translate('messages.Questions...') }}">
                                </div>
                                <div class="col-sm-12">
                                    <label for="title" class="form-label">{{ translate('Anwser') }}</label>
                                    {{-- <input id="title" type="text" name="anwser[]" class="form-control"
                                        placeholder="{{ translate('messages.Anwser...') }}"> --}}
                                        <textarea class="ckeditor form-control" name="anwser[]"></textarea>
                                </div>


                            </div>


                                <input type="hidden" name="lang[]" value="default">


                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn--primary mb-2">{{ translate('Add') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                @php($banners = \App\Models\DeliveryFaq::all())
                <div class="card">
                    <div class="card-header py-2">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">FAQ List
                                {{-- <span class="badge badge-secondary ml-1">5</span>  --}}
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Table -->
                        <div class="table-responsive datatable-custom">
                            <table id="columnSearchDatatable"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                data-hs-datatables-options='{
                                    "order": [],
                                    "orderCellsTop": true,
                                    "paging":false

                                }'>
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0">{{ translate('sl') }}</th>
                                        <th class="border-0">{{ translate('Question') }}</th>
                                        <th class="border-0">{{ translate('Anwser') }}</th>
                                        <th class="border-0">{{ translate('Status') }}</th>
                                        <th class="text-center border-0">{{ translate('messages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banners as $key => $banner)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="text--title">
                                                    {{ $banner->question }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block font-size-sm text-body">
                                                    {{ $banner->anwser }}
                                                </span>
                                            </td>

                                            <td>
                                                <label class="toggle-switch toggle-switch-sm">
                                                    <input type="checkbox" data-id="status-{{ $banner->id }}"
                                                        data-type="status"
                                                        data-image-on="{{ asset('/public/assets/admin/img/modal/promotional-on.png') }}"
                                                        data-image-off="{{ asset('/public/assets/admin/img/modal/promotional-off.png') }}"
                                                        data-title-on="{{ translate('By Turning ON FAQ Section') }}"
                                                        data-title-off="{{ translate('By Turning OFF FAQ Section') }}"
                                                        data-text-on="<p>{{ translate('FAQ will be enabled. You will be able to see  activity') }}</p>"
                                                        data-text-off="<p>{{ translate('FAQ will be disabled. You will be unable to see  activity') }}</p>"
                                                        class="status toggle-switch-input dynamic-checkbox"
                                                        id="status-{{ $banner->id }}"
                                                        {{ $banner->status ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <form
                                                    action="{{ route('admin.business-settings.delivery-faq-status', [$banner->id, $banner->status ? 0 : 1]) }}"
                                                    method="get" id="status-{{ $banner->id }}_form">
                                                </form>
                                            </td>

                                            <td>
                                                <div class="btn--container justify-content-center">
                                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                                        href="{{ route('admin.business-settings.delivery-faq-edit', [$banner['id']]) }}">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                        href="javascript:" data-id="banner-{{ $banner['id'] }}"
                                                        data-message="Do Want to Delete"
                                                        title="{{ 'Delete' }}"><i
                                                            class="tio-delete-outlined"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.business-settings.delivery-faq-delete', [$banner['id']]) }}"
                                                        method="post" id="banner-{{ $banner['id'] }}">
                                                        @csrf @method('delete')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <!-- End Table -->
                    </div>
                    @if (count($banners) === 0)
                        <div class="empty--data">
                            <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                            <h5>
                                {{ translate('no_data_found') }}
                            </h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- How it Works -->
    @include('admin-views.business-settings.landing-page-settings.partial.how-it-work')
@endsection
@push('script_2')
    <script src="{{asset('public/assets/admin/ckeditor/ckeditor.js')}}"></script>
@endpush
