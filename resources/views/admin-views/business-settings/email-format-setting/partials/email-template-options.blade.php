<div>
    <select id="mail-route-selector" class="custom-select w-auto min-width-170px">
        <option value="admin" {{ Request::is('admin/business-settings/email-setup/admin*') ? 'selected' : '' }}><a href="https://support.yehlo.com/">{{ translate('Admin_Mail_Templates') }}</a></option>
        <option value="store" {{ Request::is('admin/business-settings/email-setup/store*') ? 'selected' : '' }}><a href="https://support.yehlo.com/">{{ translate('Store_Mail_Templates') }}</a></option>
        <option value="dm" {{ Request::is('admin/business-settings/email-setup/dm*') ? 'selected' : '' }}><a href="https://support.yehlo.com/">{{ translate('Delivery_Man_Mail_Templates') }}</a></option>
        <option value="user" {{ Request::is('admin/business-settings/email-setup/user*') ? 'selected' : '' }}><a href="https://support.yehlo.com/">{{ translate('Customer_Mail_Templates') }}</a></option>
    </select>
    <div class="d-flex justify-content-end mt-2">
        <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button"   id="see-how-it-works"  >
            <strong class="mr-2">{{translate('See_how_it_works!')}}</strong>
            <div>
                <i class="tio-info-outined"></i>
            </div>
        </div>
    </div>
</div>
