@extends('layouts.admin.app')

@section('title', 'Delivery Section Edit')

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/landing.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        Delivery Section Edit
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
                @foreach (json_decode($language) as $lang)
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#"
                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="tab-content">
            <div class="tab-pane fade show active">
                <form action="{{route('admin.business-settings.delivery-promotional-update',[$delivery['id']]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-3">
                    <div class="card-body">

                        <div class="row g-3">
                            <div class="col-sm-12">


                                <label for="title" class="form-label">Main Title</label>
                                <input id="title" type="text" name="main_title[]" class="form-control" value="{{ $delivery_heading?->getRawOriginal('main_heading') }}"
                                    placeholder="{{ translate('messages.title_here...') }}">
                            </div>

                        </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="title" class="form-label">{{ translate('Title') }}</label>
                                    <input id="title" type="text" name="title[]" class="form-control" value="{{ $delivery?->getRawOriginal('title') }}"
                                        placeholder="{{ translate('messages.title_here...') }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="sub_title" class="form-label">{{ translate('Sub Title') }}</label>
                                    <input id="sub_title" type="text" name="sub_title[]" class="form-control" value="{{ $delivery?->getRawOriginal('subtitle') }}"
                                        placeholder="{{ translate('messages.sub_title_here...') }}">
                                </div>
                            </div>
                            <input type="hidden" name="lang[]" value="default">

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label d-block mb-2">
                                    {{ translate('Banner') }} <span
                                        class="text--primary">{{ translate('(size: 1:1)') }}</span>
                                </label>
                                <label class="upload-img-3 m-0 d-block">
                                    <label class="upload-img-3 m-0 d-block">
                                        <div class="img">
                                            {{-- <img
                                            src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                                                $delivery['image'] ?? '',
                                                asset('storage/app/public/promotional_delivery').'/'.$delivery['image'] ?? '',
                                                asset('/public/assets/admin/img/upload-4.png'),
                                                'promotional_delivery/'
                                            ) }}"
                                            data-onerror-image="{{asset('/public/assets/admin/img/upload-4.png')}}" class="vertical-img mw-100 vertical onerror-image" alt=""> --}}


                                            <img src="{{ asset('storage/app/public/delivery_promotion') . '/' . $delivery->image }}"
                                                data-onerror-image="{{ asset('/public/assets/admin/img/upload-3.png') }}"
                                                class="__size-105 onerror-image" alt="">
                                        </div>
                                            <input type="file" name="image"  hidden>
                                    </label>
                                    @if (isset($delivery['image'] ))
                                        <span id="delivery_image" class="remove_image_button remove-image"
                                              data-id="delivery_image"
                                              data-title="{{translate('Warning!')}}"
                                              data-text="<p>{{translate('Are_you_sure_you_want_to_remove_this_image_?')}}</p>"
                                        > <i class="tio-clear"></i></span>
                                    @endif
                                </label>
                            </div>
                        </div>
                        <div class="btn--container justify-content-end mt-3">
                            <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                            <button type="submit" class="btn btn--primary mb-2">{{ translate('Add') }}</button>
                        </div>
                    </div>
                </div>
            </form>
                <form id="delivery_image_form" action="{{ route('admin.remove_image') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $delivery?->id }}">
                    <input type="hidden" name="model_name" value="AdminPromotionalBanner">
                    <input type="hidden" name="image_path" value="promotional_banner">
                    <input type="hidden" name="field_name" value="image">
                </form>
            </div>
        </div>
    </div>
    <!-- How it Works -->
    @include('admin-views.business-settings.landing-page-settings.partial.how-it-work')
@endsection
