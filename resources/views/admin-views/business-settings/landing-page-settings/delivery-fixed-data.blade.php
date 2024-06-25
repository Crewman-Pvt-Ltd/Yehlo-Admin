@extends('layouts.admin.app')

@section('title', 'Deliveryman Landging Page')

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/landing.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        Deliveryman Landging Page
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
        @php($delivery_heading = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_heading')->first())
        @php($delivery_sub_heading = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_sub_heading')->first())
        @php($delivery_head_image = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_head_image')->first())

        @php($delivery_footer_heading = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_footer_heading')->first())
        @php($delivery_footer_image = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_footer_image')->first())
        @php($delivery_footer_sub_heading = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'delivery_footer_sub_heading')->first())
        @php($sub_head_2 = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'sub_head_2')->first())
        @php($download_link_button = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'delivery_landing_page')->where('key', 'download_link_button')->first())





        @php($fixed_module_sub_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'fixed_module_sub_title')->first())
        @php($fixed_referal_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'fixed_referal_title')->first())
        @php($fixed_referal_sub_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'fixed_referal_sub_title')->first())
        @php($fixed_newsletter_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'fixed_newsletter_title')->first())
        @php($fixed_newsletter_sub_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'fixed_newsletter_sub_title')->first())
        @php($fixed_footer_article_title = \App\Models\DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'fixed_footer_article_title')->first())
        @php($fixed_link = \App\Models\DataSetting::where(['key' => 'fixed_link', 'type' => 'admin_landing_page'])->first())
        @php($fixed_link = isset($fixed_link->value) ? json_decode($fixed_link->value, true) : null)
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
                    {{-- <li class="nav-item">
                    <a class="nav-link lang_link"
                        href="#"
                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                </li> --}}
                @endforeach
            </ul>
        @endif

        <div class="tab-content">
            <div class="tab-pane fade show active">
                <form action="{{ route('admin.business-settings.delivery-landing-page-settings', 'fixed-data') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <h5 class="card-title mb-3">
                            <span class="card-header-icon mr-2"><i class="tio-calendar"></i></span>
                            <span>Hero Section</span>
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="fixed_newsletter_title" class="form-label">Heading<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="fixed_newsletter_title" type="text" maxlength=""
                                            name="delivery_heading[]"
                                            value="{{ $delivery_heading?->getRawOriginal('value') }}" class="form-control"
                                            placeholder="{{ translate('Ex_:_Sign_Up_to_Our_Newsletter') }}">

                                        <label for="fixed_newsletter_title" class="form-label">Sub Heading<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="fixed_newsletter_title" type="text" maxlength=""
                                            name="delivery_sub_heading[]"
                                            value="{{ $delivery_sub_heading?->getRawOriginal('value') }}"
                                            class="form-control"
                                            placeholder="{{ translate('Ex_:_Sign_Up_to_Our_Newsletter') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label d-block mb-2">
                                            Home Image <span class="text--primary">{{ translate('(size: 4:4)') }}</span>
                                        </label>
                                        <label class="upload-img-3 m-0 d-block">
                                            <div class="position-relative">
                                                <div class="img">
                                                    <img src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                                                        $delivery_head_image['value'] ?? '',
                                                        asset('storage/app/public/deliveryman') . '/' . $delivery_head_image['value'] ?? '',
                                                        asset('/public/assets/admin/img/upload-4.png'),
                                                        'deliveryman/',
                                                    ) }}"
                                                        data-onerror-image="{{ asset('/public/assets/admin/img/upload-4.png') }}"
                                                        class="vertical-img mw-100 vertical onerror-image" alt="">

                                                </div>
                                                <input type="file" name="delivery_head_image" hidden>
                                                @if (isset($delivery_head_image['value']))
                                                    <span id="delivery_head_image" class="remove_image_button remove-image"
                                                        data-id="delivery_head_image"
                                                        data-title="{{ translate('Warning!') }}"
                                                        data-text="<p>{{ translate('Are_you_sure_you_want_to_remove_this_image_?') }}</p>">
                                                        <i class="tio-clear"></i></span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>


                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                        <button type="submit" class="btn btn--primary mb-2">{{ translate('Save Information') }}</button>
                    </div>
                </form>
                <!-- Module Setup Section View -->
                <form action="{{ route('admin.business-settings.delivery-landing-page-settings', 'footer-fixed-data') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <h5 class="card-title mb-3">
                            <span class="card-header-icon mr-2"><i class="tio-calendar"></i></span>
                            <span>Footer Section</span>
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="fixed_newsletter_title" class="form-label">Heading<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="fixed_newsletter_title" type="text" maxlength=""
                                            name="delivery_footer_heading[]"
                                            value="{{ $delivery_footer_heading?->getRawOriginal('value') }}"
                                            class="form-control"
                                            placeholder="{{ translate('Ex_:_Sign_Up_to_Our_Newsletter') }}">

                                        <label for="fixed_newsletter_title" class="form-label">Sub Heading<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="fixed_newsletter_title" type="text" maxlength=""
                                            name="delivery_footer_sub_heading[]"
                                            value="{{ $delivery_footer_sub_heading?->getRawOriginal('value') }}"
                                            class="form-control"
                                            placeholder="{{ translate('Ex_:_Sign_Up_to_Our_Newsletter') }}">
                                        <label for="fixed_newsletter_title" class="form-label">Sub-Head 2<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="fixed_newsletter_title" type="text" maxlength=""
                                            name="sub_head_2[]"
                                            value="{{ $sub_head_2?->getRawOriginal('value') }}"
                                            class="form-control"
                                            placeholder="{{ translate('Ex_:_Sign_Up_to_Our_Newsletter') }}">
                                        <label for="fixed_newsletter_title" class="form-label">Download Button Link<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                                <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                    alt="">
                                            </span></label>
                                        <input id="fixed_newsletter_title" type="text" maxlength=""
                                            name="download_link_button[]"
                                            value="{{ $download_link_button?->getRawOriginal('value') }}"
                                            class="form-control"
                                            placeholder="{{ translate('Ex_:_Sign_Up_to_Our_Newsletter') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label d-block mb-2">
                                            Home Image <span class="text--primary">{{ translate('(size: 4:4)') }}</span>
                                        </label>
                                        <label class="upload-img-3 m-0 d-block">
                                            <div class="position-relative">
                                                <div class="img">
                                                    <img src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                                                        $delivery_footer_image['value'] ?? '',
                                                        asset('storage/app/public/deliveryman') . '/' . $delivery_footer_image['value'] ?? '',
                                                        asset('/public/assets/admin/img/upload-4.png'),
                                                        'deliveryman/',
                                                    ) }}"
                                                        data-onerror-image="{{ asset('/public/assets/admin/img/upload-4.png') }}"
                                                        class="vertical-img mw-100 vertical onerror-image" alt="">

                                                </div>
                                                <input type="file" name="delivery_footer_image" hidden>
                                                @if (isset($delivery_footer_image['value']))
                                                    <span id="delivery_footer_image"
                                                        class="remove_image_button remove-image"
                                                        data-id="delivery_footer_image"
                                                        data-title="{{ translate('Warning!') }}"
                                                        data-text="<p>{{ translate('Are_you_sure_you_want_to_remove_this_image_?') }}</p>">
                                                        <i class="tio-clear"></i></span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>


                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{ translate('Reset') }}</button>
                        <button type="submit" class="btn btn--primary mb-2">{{ translate('Save Information') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- How it Works -->
    @include('admin-views.business-settings.landing-page-settings.partial.how-it-work')
@endsection
