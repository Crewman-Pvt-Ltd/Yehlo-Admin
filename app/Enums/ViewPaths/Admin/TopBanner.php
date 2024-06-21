<?php

namespace App\Enums\ViewPaths\Admin;

enum TopBanner
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.top-banner.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.top-banner.index'
    ];

    const UPDATE = [
        URI => 'edit',
        VIEW => 'admin-views.top-banner.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const UPDATE_STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const UPDATE_FEATURED = [
        URI => 'featured',
        VIEW => ''
    ];

    const SEARCH = [
        URI => 'search',
        VIEW => 'admin-views.top-banner.partials._table'
    ];

}
