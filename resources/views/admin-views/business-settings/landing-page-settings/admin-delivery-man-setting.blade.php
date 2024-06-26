@extends('layouts.admin.app')

@section('title', translate('messages.admin_landing_page'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/landing.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        {{-- {{ translate('messages.admin_landing_pages') }} --}}
                        Admin Delivery Landing
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal"
                    data-target="#how-it-works">
                    x {{-- <strong class="mr-2">{{ translate('See_how_it_works!') }}</strong> --}}
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-4 mt-2">
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.business-settings.landing-page-settings.top-menu-links.admin-landing-page-links')
            </div>
        </div>
        @php($earning_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'earning_title')->first())
        @php($earning_sub_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'earning_sub_title')->first())
        @php($earning_seller_image = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'earning_seller_image')->first())
        @php($earning_delivery_image = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'earning_delivery_image')->first())
        {{--  --}}

        @php($delivery_man_header_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_man_header_title')->first())
        @php($delivery_man_sub_header_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_man_sub_header_title')->first())

        @php($home_delivery_image = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'home_delivery_image')->first())


        @php($delivery_footer_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_footer_title')->first())
        @php($delivery_footer_sub_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_footer_sub_title')->first())
        @php($delivery_footer_heading = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_footer_heading')->first())

        @php($delivery_footer_sub_heading = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_footer_sub_heading')->first())
        @php($footer_delivery_image = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'footer_delivery_image')->first())

        @php($seller_description = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'seller_description')->first())
        @php($seller_purchase = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'seller_purchase')->first())

        {{--  --}}

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

                {{-- Home Header --}}


                <form action="{{ route('admin.business-settings.admin-landing-page-settings', 'deliveryman-header') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <h5 class="card-title mb-3">
                        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                        <span>Home Section</span>
                    </h5>
                    <div class="card mb-3">
                        <div class="card-body">
                            @if ($language)
                                <div class="row g-3 lang_form" id="default-form">
                                    <div class="col-sm-6">
                                        <label for="home_tile" class="form-label">{{ translate('Title') }}
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="home_tile" type="text" name="delivery_man_header_title[]"
                                            class="form-control"
                                            value="{{ $delivery_man_header_title?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.title_here...') }}">
                                        <br>
                                        <label for="home_sub_title" class="form-label">{{ translate('Sub Title') }}
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="home_sub_title" type="text" name="delivery_man_sub_header_title[]"
                                            class="form-control"
                                            value="{{ $delivery_man_sub_header_title?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}">
                                        <br>
                                        {{-- <label for="home-heading" class="form-label">
                                            Home Heading
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="home-heading" type="text" maxlength="80" name="home_heading[]"
                                            class="form-control" value="{{ $home_heading?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}"> --}}
                                        <br>
                                        {{-- <label for="home-sub-heading" class="form-label">Home Sub Heading
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="home-sub-heading" type="text" maxlength="80" name="home_sub_heading[]"
                                            class="form-control" value="{{ $home_sub_heading?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}"> --}}
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label d-block mb-2">
                                            {{ translate('Banner') }} <span
                                                class="text--primary">{{ translate('(size: 1:1)') }}</span>
                                        </label>
                                        <label class="upload-img-3 m-0 d-block">
                                            <div class="position-relative">
                                                <div class="img">
                                                    <img src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                                                        $home_delivery_image['value'] ?? '',
                                                        asset('storage/app/public/earning') . '/' . $home_delivery_image['value'] ?? '',
                                                        asset('/public/assets/admin/img/upload-4.png'),
                                                        'earning/',
                                                    ) }}"
                                                        data-onerror-image="{{ asset('/public/assets/admin/img/upload-4.png') }}"
                                                        class="vertical-img mw-100 vertical onerror-image" alt="">

                                                </div>
                                                <input type="file" name="home_delivery_image" hidden>
                                                @if (isset($home_delivery_image['value']))
                                                    <span id="home_delivery_image" class="remove_image_button remove-image"
                                                        data-id="home_delivery_image"
                                                        data-title="{{ translate('Warning!') }}"
                                                        data-text="<p>{{ translate('Are_you_sure_you_want_to_remove_this_image_?') }}</p>">
                                                        <i class="tio-clear"></i></span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach (json_decode($language) as $lang)
                                    <?php
                                    if (isset($earning_title->translations) && count($earning_title->translations)) {
                                        $earning_title_translate = [];
                                        foreach ($earning_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_title') {
                                                $earning_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    if (isset($earning_sub_title->translations) && count($earning_sub_title->translations)) {
                                        $earning_sub_title_translate = [];
                                        foreach ($earning_sub_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_sub_title') {
                                                $earning_sub_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    ?>

                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                @endforeach
                            @else
                                <input type="hidden" name="lang[]" value="default">
                            @endif
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn--primary mb-2">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- Home Header --}}


                {{-- <form action="{{ route('admin.business-settings.admin-landing-page-settings', 'earning-title') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <h5 class="card-title mb-3">
                        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                        <span></span>
                    </h5>
                    <div class="card mb-3">
                        <div class="card-body">
                            @if ($language)
                                <div class="row g-3 lang_form" id="default-form">
                                    <div class="col-sm-6">
                                        <label for="earning_title" class="form-label">{{ translate('Title') }}
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="earning_title" type="text" maxlength="40" name="earning_title[]"
                                            class="form-control" value="{{ $earning_title?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.title_here...') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="sub-text" class="form-label">{{ translate('Sub Title') }}
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="sub-text" type="text" maxlength="80" name="earning_sub_title[]"
                                            class="form-control"
                                            value="{{ $earning_sub_title?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}">
                                    </div>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach (json_decode($language) as $lang)
                                    <?php
                                    if (isset($earning_title->translations) && count($earning_title->translations)) {
                                        $earning_title_translate = [];
                                        foreach ($earning_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_title') {
                                                $earning_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    if (isset($earning_sub_title->translations) && count($earning_sub_title->translations)) {
                                        $earning_sub_title_translate = [];
                                        foreach ($earning_sub_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_sub_title') {
                                                $earning_sub_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="row g-3 d-none lang_form" id="{{ $lang }}-form">
                                        <div class="col-sm-6">
                                            <label for="earning_title" class="form-label">{{ translate('Title') }}
                                                ({{ strtoupper($lang) }})
                                                <span class="form-label-secondary" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                    <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                        alt="">
                                                </span></label>
                                            <input id="earning_title" type="text" maxlength="40"
                                                name="earning_title[]" class="form-control"
                                                value="{{ $earning_title_translate[$lang]['value'] ?? '' }}"
                                                placeholder="{{ translate('messages.title_here...') }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="sub-title" class="form-label">{{ translate('Sub Title') }}
                                                ({{ strtoupper($lang) }})<span class="form-label-secondary"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                    <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                        alt="">
                                                </span></label>
                                            <input id="sub-title" type="text" maxlength="80"
                                                name="earning_sub_title[]" class="form-control"
                                                value="{{ $earning_sub_title_translate[$lang]['value'] ?? '' }}"
                                                placeholder="{{ translate('messages.sub_title_here...') }}">
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                @endforeach
                            @else
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="earning-title" class="form-label">{{ translate('Title') }}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="earning-title" type="text" maxlength="40" name="earning_title[]"
                                            class="form-control" placeholder="{{ translate('messages.title_here...') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="earning-sub-title"
                                            class="form-label">{{ translate('Sub Title') }}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="earning-sub-title" type="text" maxlength="80"
                                            name="earning_sub_title[]" class="form-control"
                                            placeholder="{{ translate('messages.sub_title_here...') }}">
                                    </div>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                            @endif
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn--primary mb-2">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form> --}}

                {{-- yehlo purchase --}}

                {{-- <form action="{{ route('admin.business-settings.admin-landing-page-settings', 'seller-purchase') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <h5 class="card-title mb-3">
                        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                        <span>Seller Purchase</span>
                    </h5>
                    <div class="card mb-3">
                        <div class="card-body">
                            @if ($language)
                                <div class="row g-3 lang_form" id="default-form">
                                    <div class="col-sm-6">
                                        <label for="seller-purchase" class="form-label">{{ translate('Title') }}
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="seller-purchase" type="text" maxlength="40"
                                            name="seller_purchase[]" class="form-control"
                                            value="{{ $seller_purchase?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.title_here...') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="seller-description" class="form-label">Seller Description
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right" <img
                                                src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                alt="">
                                            </span></label>
                                        <input id="seller-description" type="text" name="seller_description[]"
                                            class="form-control"
                                            value="{{ $seller_description?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}">
                                    </div>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach (json_decode($language) as $lang)
                                    <?php
                                    if (isset($earning_title->translations) && count($earning_title->translations)) {
                                        $earning_title_translate = [];
                                        foreach ($earning_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_title') {
                                                $earning_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    if (isset($earning_sub_title->translations) && count($earning_sub_title->translations)) {
                                        $earning_sub_title_translate = [];
                                        foreach ($earning_sub_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_sub_title') {
                                                $earning_sub_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    ?>

                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                @endforeach
                            @else

                                <input type="hidden" name="lang[]" value="default">
                            @endif
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn--primary mb-2">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>  --}}


                {{-- yehlo purchase --}}







                {{-- Home Footer --}}

                <form action="{{ route('admin.business-settings.admin-landing-page-settings', 'delivery-footer') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <h5 class="card-title mb-3">
                        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                        <span>Delivery Footer Section</span>
                    </h5>
                    <div class="card mb-3">
                        <div class="card-body">
                            @if ($language)
                                <div class="row g-3 lang_form" id="default-form">
                                    <div class="col-sm-6">
                                        <label for="Footer_tile" class="form-label">{{ translate('Title') }}
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="Footer_tile" type="text" maxlength="40"
                                            name="delivery_footer_title[]" class="form-control"
                                            value="{{ $delivery_footer_title?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.title_here...') }}">
                                        <br>
                                        <label for="footer_sub_title" class="form-label">{{ translate('Sub Title') }}
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="footer_sub_title" type="text" maxlength="80"
                                            name="delivery_footer_sub_title[]" class="form-control"
                                            value="{{ $delivery_footer_sub_title?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}">
                                        <br>
                                        <label for="footer-heading" class="form-label">
                                            Footer Heading
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="footer-heading" type="text" maxlength="80"
                                            name="delivery_footer_heading[]" class="form-control"
                                            value="{{ $delivery_footer_heading?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}">
                                        <br>
                                        <label for="footer-sub-heading" class="form-label">Footer Sub Heading
                                            ({{ translate('messages.default') }})<span class="form-label-secondary"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="footer-sub-heading" type="text" maxlength="80"
                                            name="delivery_footer_sub_heading[]" class="form-control"
                                            value="{{ $delivery_footer_sub_heading?->getRawOriginal('value') }}"
                                            placeholder="{{ translate('messages.sub_title_here...') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label d-block mb-2">
                                            {{ translate('Banner') }} <span
                                                class="text--primary">{{ translate('(size: 1:1)') }}</span>
                                        </label>
                                        <label class="upload-img-3 m-0 d-block">
                                            <div class="position-relative">
                                                <div class="img">
                                                    <img src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                                                        $footer_delivery_image['value'] ?? '',
                                                        asset('storage/app/public/earning') . '/' . $footer_delivery_image['value'] ?? '',
                                                        asset('/public/assets/admin/img/upload-4.png'),
                                                        'earning/',
                                                    ) }}"
                                                        data-onerror-image="{{ asset('/public/assets/admin/img/upload-4.png') }}"
                                                        class="vertical-img mw-100 vertical onerror-image" alt="">

                                                </div>
                                                <input type="file" name="footer_delivery_image" hidden>
                                                @if (isset($footer_delivery_image['value']))
                                                    <span id="footer_delivery_image"
                                                        class="remove_image_button remove-image"
                                                        data-id="footer_delivery_image"
                                                        data-title="{{ translate('Warning!') }}"
                                                        data-text="<p>{{ translate('Are_you_sure_you_want_to_remove_this_image_?') }}</p>">
                                                        <i class="tio-clear"></i></span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach (json_decode($language) as $lang)
                                    <?php
                                    if (isset($earning_title->translations) && count($earning_title->translations)) {
                                        $earning_title_translate = [];
                                        foreach ($earning_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_title') {
                                                $earning_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    if (isset($earning_sub_title->translations) && count($earning_sub_title->translations)) {
                                        $earning_sub_title_translate = [];
                                        foreach ($earning_sub_title->translations as $t) {
                                            if ($t->locale == $lang && $t->key == 'earning_sub_title') {
                                                $earning_sub_title_translate[$lang]['value'] = $t->value;
                                            }
                                        }
                                    }
                                    ?>

                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                @endforeach
                            @else
                                <input type="hidden" name="lang[]" value="default">
                            @endif
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn--primary mb-2">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Home Footer --}}


                {{-- delivery-partner-list --}}

                <form action="{{ route('admin.business-settings.admin-landing-page-settings', 'deliveryman-list') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <h5 class="card-title mb-3">
                        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                        <span>Delivery List</span>
                    </h5>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row g-4">
                                @if ($language)
                                    <div class="col-md-6 lang_form default-form">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="title" class="form-label">{{ translate('Title') }}
                                                    ({{ translate('messages.default') }})<span
                                                        class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                                        <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                            alt="">
                                                    </span></label>
                                                <input id="title" type="text" maxlength="20" name="title[]"
                                                    class="form-control" placeholder="{{ translate('Ex_:_Shopping') }}">
                                            </div>
                                            <div class="col-12">
                                                <label for="sub_title" class="form-label">{{ translate('Sub Title') }}
                                                    ({{ translate('messages.default') }})<span
                                                        class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                        <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                            alt="">
                                                    </span></label>
                                                <input id="sub_title" type="text" maxlength="80" name="sub_title[]"
                                                    class="form-control"
                                                    placeholder="{{ translate('Ex_:_Best_shopping_experience') }}">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6 lang_form default-form">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="sub_title" class="form-label">Sub Title 2
                                                    ({{ translate('messages.default') }})<span
                                                        class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                        <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                            alt="">
                                                    </span></label>
                                                <input id="sub_title" type="text" maxlength="80" name="sub_title2[]"
                                                    class="form-control"
                                                    placeholder="{{ translate('Ex_:_Best_shopping_experience') }}">
                                            </div>
                                            <div class="col-12">
                                                <label for="sub_title" class="form-label">Sub Title 3
                                                    ({{ translate('messages.default') }})<span
                                                        class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_title_within_80_characters') }}">
                                                        <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                            alt="">
                                                    </span></label>
                                                <input id="sub_title" type="text" maxlength="80" name="sub_title3[]"
                                                    class="form-control"
                                                    placeholder="{{ translate('Ex_:_Best_shopping_experience') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                @else
                                @endif

                            </div>
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn--primary mb-2">{{ translate('Add') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- delivery-partner-list --}}



                {{-- <form action="{{ route('admin.business-settings.admin-landing-page-settings', 'earning-seller-link') }}"
                    method="POST" enctype="multipart/form-data">
                    @php($seller_app_links = \App\Models\DataSetting::where(['key' => 'seller_app_earning_links', 'type' => 'admin_landing_page'])->first())
                    @php($seller_app_links = isset($seller_app_links->value) ? json_decode($seller_app_links->value, true) : null)

                    @csrf
                    <h5 class="card-title mb-3">
                        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                        <span>{{ translate('Download_Store_App_Section') }}</span>
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label d-block mb-2">
                                        {{ translate('Banner') }} <span
                                            class="text--primary">{{ translate('(size: 3:1)') }}</span>
                                    </label>
                                    <label class="upload-img-3 m-0 d-block">
                                        <div class="position-relative">
                                            <div class="img">
                                                <img src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                                                    $earning_seller_image['value'] ?? '',
                                                    asset('storage/app/public/earning') . '/' . $earning_seller_image['value'] ?? '',
                                                    asset('/public/assets/admin/img/upload-4.png'),
                                                    'earning/',
                                                ) }}"
                                                    data-onerror-image="{{ asset('/public/assets/admin/img/upload-4.png') }}"
                                                    class="vertical-img mw-100 vertical onerror-image" alt="">

                                            </div>
                                            <input type="file" name="earning_seller_image" hidden>
                                            @if (isset($earning_seller_image['value']))
                                                <span id="earning_seller_img" class="remove_image_button remove-image"
                                                    data-id="earning_seller_img"
                                                    data-title="{{ translate('Warning!') }}"
                                                    data-text="<p>{{ translate('Are_you_sure_you_want_to_remove_this_image_?') }}</p>">
                                                    <i class="tio-clear"></i></span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title mb-2">
                                        <img src="{{ asset('public/assets/admin/img/playstore.png') }}" class="mr-2"
                                            alt="">
                                        {{ translate('Playstore Button') }}
                                    </h5>
                                    <div class="__bg-F8F9FC-card">
                                        <div class="form-group mb-md-0">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="playstore_url" class="form-label text-capitalize m-0">
                                                    {{ translate('Download Link') }}
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('When_disabled,_the_Play_Store_download_button_will_be_hidden_from_the_landing_page') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                                <label class="toggle-switch toggle-switch-sm m-0">
                                                    <input type="checkbox" name="playstore_url_status"
                                                        data-id="play-store-seller-status" data-type="toggle"
                                                        data-image-on='{{ asset('/public/assets/admin/img/modal') }}/play-store-on.png'
                                                        data-image-off="{{ asset('/public/assets/admin/img/modal') }}/play-store-off.png"
                                                        data-title-on="{{ translate('Want_to_enable_the_Play_Store_button_for_Store_App?') }}"
                                                        data-title-off="{{ translate('Want_to_disable_the_Play_Store_button_for_Store_App?') }}"
                                                        data-text-on="<p>{{ translate('If_enabled,_the_Store_app_download_button_will_be_visible_on_the_Landing_page.') }}</p>"
                                                        data-text-off="<p>{{ translate('If_disabled,_this_button_will_be_hidden_from_the_landing_page.') }}</p>"
                                                        id="play-store-seller-status"
                                                        class="status toggle-switch-input dynamic-checkbox-toggle"
                                                        value="1"
                                                        {{ isset($seller_app_links) && $seller_app_links['playstore_url_status'] ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label text mb-0">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <input id="playstore_url" type="text"
                                                placeholder="{{ translate('Ex: https://play.google.com/store/apps') }}"
                                                class="form-control h--45px" name="playstore_url"
                                                value="{{ $seller_app_links['playstore_url'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title mb-2">
                                        <img src="{{ asset('public/assets/admin/img/ios.png') }}" class="mr-2"
                                            alt="">
                                        {{ translate('App Store Button') }}
                                    </h5>
                                    <div class="__bg-F8F9FC-card">
                                        <div class="form-group mb-md-0">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="apple_store_url" class="form-label text-capitalize m-0">
                                                    {{ translate('Download Link') }}
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('When_disabled,_the_App_Store_download_button_will_be_hidden_from_the_landing_page') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                                <label class="toggle-switch toggle-switch-sm m-0">
                                                    <input type="checkbox" name="apple_store_url_status"
                                                        data-id="apple-seller-status" data-type="toggle"
                                                        data-image-on='{{ asset('/public/assets/admin/img/modal') }}/apple-on.png'
                                                        data-image-off="{{ asset('/public/assets/admin/img/modal') }}/apple-off.png"
                                                        data-title-on="{{ translate('Want_to_enable_the_App_Store_button_for_Store_App?') }}"
                                                        data-title-off="{{ translate('Want_to_disable_the_App_Store_button_for_Store_App') }}"
                                                        data-text-on="<p>{{ translate('If_enabled,_the_Store_app_download_button_will_be_visible_on_the_Landing_page.') }}</p>"
                                                        data-text-off="<p>{{ translate('If_disabled,_this_button_will_be_hidden_from_the_landing_page.') }}</p>"
                                                        id="apple-seller-status"
                                                        class="status toggle-switch-input dynamic-checkbox-toggle"
                                                        value="1"
                                                        {{ isset($seller_app_links) && $seller_app_links['apple_store_url_status'] ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label text mb-0">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <input type="text" id="apple_store_url"
                                                placeholder="{{ translate('Ex: https://www.apple.com/app-store/') }}"
                                                class="form-control h--45px" name="apple_store_url"
                                                value="{{ $seller_app_links['apple_store_url'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                                <button type="submit" class="btn btn--primary mb-2">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form> --}}

                <form id="earning_seller_img_form" action="{{ route('admin.remove_image') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $earning_seller_image?->id }}">
                    {{-- <input type="hidden" name="json" value="1" > --}}
                    <input type="hidden" name="model_name" value="DataSetting">
                    <input type="hidden" name="image_path" value="earning">
                    <input type="hidden" name="field_name" value="value">
                </form>


            </div>
        </div>
    </div>
    <!-- How it Works -->
    @include('admin-views.business-settings.landing-page-settings.partial.how-it-work')
@endsection
@push('script_2')
    <script src="{{ asset('public/assets/admin/ckeditor/ckeditor.js') }}"></script>
@endpush
