<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/delivery-landing-page-settings/fixed-data') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.delivery-landing-page-settings', 'fixed-data') }}">{{translate('messages.fixed_data')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/delivery-landing-page-settings/promotional-section*') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.delivery-landing-page-settings', 'promotional-section') }}">{{translate('messages.promotional_section')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/delivery-landing-page-settings/faq*') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.delivery-landing-page-settings', 'faq') }}">{{translate('messages.delivery_faq')}}</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/earn-money') ? 'active' : '' }}"
                    href="{{ route('admin.business-settings.admin-landing-page-settings', 'earn-money') }}">

                    Vendor Main Setting
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/feature-list*') ? 'active' : '' }}"
                    href="{{ route('admin.business-settings.admin-landing-page-settings', 'feature-list') }}">

                    Vendor Features
                </a>
            </li> --}}
            {{-- <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/why-choose-us*') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.admin-landing-page-settings', 'why-choose-us') }}">{{translate('messages.why_choose_us')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/download-apps') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.admin-landing-page-settings', 'download-apps') }}">{{translate('messages.download_apps')}}</a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/delivery-man-setting') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.admin-landing-page-settings', 'delivery-man-setting') }}">Delivery Landing Setting</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/testimonials*') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.admin-landing-page-settings', 'testimonials') }}">{{translate('messages.testimonials')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/contact-us') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.admin-landing-page-settings', 'contact-us') }}">{{translate('messages.contact_us_page')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/pages/admin-landing-page-settings/background-color') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.admin-landing-page-settings', 'background-color') }}">{{translate('messages.background_colors')}}</a>
            </li> --}}
        </ul>
        <!-- End Nav -->
    </div>
</div>
