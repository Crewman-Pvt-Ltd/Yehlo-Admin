@extends('layouts.admin.app')


@section('title', translate('messages.Add New Plan'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/plan.png') }}" class="" alt="">
                </span>
                <span>
                    {{ translate('Add New Plan') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.add-plan') }}" method="post" enctype="multipart/form-data">
                @csrf
                <ul class="nav nav-tabs mb-4 border-0">
                    <li class="nav-item">
                        <a class="nav-link lang_link active" href="#"
                            id="default-link">{{ translate('messages.default') }}</a>
                    </li>



                </ul>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group  lang_form" id="">
                            {{-- <label class="input-label"
                                for="exampleFormControlInput1">{{ translate('messages.plan_variation') }}
                            </label>
                            <select id="plan_variation" name="plan_variation" class="form-control js-select2-custom">
                                <option value="" selected disabled>{{ translate('Add Plan Variation') }}</option>
                                @foreach (\App\Models\Plan::where('plan_id', 0)->get() as $variation)
                                    <option value="{{ $variation->id }}">{{ $variation->name }}</option>
                                @endforeach


                            </select> --}}

                        </div>
                        <div class="form-group lang_form" id="default-form">
                            <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.plan_name') }}
                                ({{ translate('messages.default') }})
                                <span class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Required.') }}"> *
                                </span>

                            </label>
                            <input type="text" name="plan_name" class="form-control"
                                placeholder="{{ translate('messages.plan_name') }}" maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="default">

                        <div class="form-group  lang_form" id="">
                            <label class="input-label"
                                for="exampleFormControlInput1">{{ translate('messages.plan_price') }}
                            </label>
                            <input type="number" name="plan_price" class="form-control"
                                placeholder="{{ translate('messages.plan_price') }}" maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="">

                        <div class="form-group  lang_form" id="">
                            <label class="input-label"
                                for="exampleFormControlInput1">{{ translate('messages.product_limit') }}
                            </label>
                            <input type="text" name="product_limit" class="form-control"
                                placeholder="{{ translate('messages.product_limit') }}" maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="">

                        {{-- <div class="form-group  lang_form" id="">
                            <label class="input-label"
                                for="exampleFormControlInput1">{{ translate('messages.plan_validate') }}
                            </label>
                            <select id="exampleFormControlSelect1" name="plan_duration"
                                class="form-control js-select2-custom" required>
                                <option value="" selected disabled>{{ translate('Select Plan Duration') }}</option>

                                <option value="3"> Quaterly</option>
                                <option value="6"> Half Year</option>
                                <option value="12"> Yearly</option>

                            </select>
                        </div>
                        <input type="hidden" name="lang[]" value=""> --}}

                        {{-- <div class="form-group  lang_form" id="">
                        <label class="input-label"
                            for="exampleFormControlInput1">{{translate('messages.plan_description')}}
                        </label>
                        <textarea id="description" class="ckeditor form-control" name="description[]"></textarea>
                    </div> --}}
                        {{-- <input type="hidden" name="lang[]" value=""> --}}







                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center flex-column">
                            <label class="mb-3 text-center">{{ translate('messages.image') }} <small class="text-danger">*
                                    (
                                    {{ translate('messages.ratio') }} 1:1)</small></label>
                            <label class="text-center my-auto position-relative d-inline-block">
                                <img class="img--176 border" id="viewer"
                                    @if (isset($category)) src="{{ asset('storage/app/public/plan') }}/" @else
                                src="{{ asset('public/assets/admin/img/upload-img.png') }}" @endif
                                    alt="image" />
                                <div class="icon-file-group">
                                    <div class="icon-file">
                                        <input type="file" name="imagePlan" id="customFileEg1"
                                            class="custom-file-input read-url"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <i class="tio-edit"></i>
                                    </div>
                                </div>
                            </label>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group  lang_form" id="">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.Description 1') }}
                                    </label>
                                    <input type="text" name="desc_1" class="form-control"
                                        placeholder="{{ translate('messages.product_limit') }}" maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="">

                                <div class="form-group  lang_form" id="">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.Description 2') }}
                                    </label>
                                    <input type="text" name="desc_2" class="form-control"
                                        placeholder="{{ translate('messages.product_limit') }}" maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="">
                                <div class="form-group  lang_form" id="">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.Description 3') }}
                                    </label>
                                    <input type="text" name="desc_3" class="form-control"
                                        placeholder="{{ translate('messages.product_limit') }}" maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="">
                            </div>

                        </div>
                    </div>


                </div>
                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" id="reset_btn"
                        class="btn btn--reset">{{ translate('messages.reset') }}</button>

                    <button type="submit" class="btn btn--primary">{{ translate('messages.add') }}</button>
                </div>

            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header py-2 border-0">
            <!-- Your search and export form code here -->
        </div>
        <div class="card-body p-0">
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{ translate('sl') }}</th>

                            <th class="border-0 w--1">{{ translate('messages.name') }}</th>
                            <th class="border-0 w--1">{{ translate('messages.plan_product_limit') }}</th>
                            <th class="border-0 w--1">{{ translate('messages.plan_price') }}</th>
                            <th class="border-0 w--1">{{ translate('messages.description_1') }}</th>
                            <th class="border-0 w--1">{{ translate('messages.description_2') }}</th>
                            <th class="border-0 w--1">{{ translate('messages.description_3') }}</th>
                            <th class="border-0 text-center">{{ translate('messages.status') }}</th>
                            <th class="border-0 text-center">{{ translate('messages.recommeded_plan') }}</th>
                            <!-- Add more table headers as needed -->
                            <th class="border-0 text-center">{{ translate('messages.action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($plans as $key => $plan)
                            <tr>
                                <td>{{ $key + $plans->firstItem() }}</td>

                                <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{ Str::limit($plan->name, 20, '...') }}
                                    </span>
                                </td>
                                <td>
                                    {{ $plan->product_limit }} Products
                                </td>


                                </td>
                                <td>
                                    {{ $plan->currency_symbol }} {{ $plan->price }}
                                </td>
                                <td>
                                    {{ $plan->desc_1 }} Months
                                </td>
                                <td>
                                    {{ $plan->desc_2 }} Months
                                </td>
                                <td>
                                    {{ $plan->desc_3 }} Months
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm"
                                        for="stocksCheckbox{{ $plan->id }}">
                                        <input type="checkbox"
                                            data-url="{{ route('admin.plan.status', [$plan['id'], $plan->status ? 0 : 1]) }}"
                                            class="toggle-switch-input redirect-url"
                                            id="stocksCheckbox{{ $plan->id }}" {{ $plan->status ? 'checked' : '' }}>
                                        <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>

                                <td>
                                    <label class="toggle-switch toggle-switch-sm"
                                        for="recommendedCheckbox{{ $plan->id }}">
                                        <input type="checkbox"
                                            data-url="{{ route('admin.plan.recommend', [$plan['id'], $plan->recommed ? 0 : 1]) }}"
                                            class="toggle-switch-input redirect-url"
                                            id="recommendedCheckbox{{ $plan->id }}"
                                            {{ $plan->recommed ? 'checked' : '' }}>
                                        <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>

                                <!-- Add more table cells for other plan attributes -->
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--primary btn-outline-primary"
                                            href="{{ route('admin.plan.edit', [$plan['id']]) }}"
                                            title="{{ translate('messages.edit_plan') }}"><i class="tio-edit"></i>
                                        </a>
                                        <!-- Add delete button with form for deleting plans -->

                                        <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                            href="javascript:" data-id="plan-{{ $plan['id'] }}"
                                            data-message="{{ translate('Want to delete this plan') }}"
                                            title="{{ translate('messages.delete_plan') }}"><i
                                                class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{ route('admin.plan.delete', [$plan['id']]) }}" method="post"
                                            id="plan-{{ $plan['id'] }}">
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
        <div class="page-area">
            {!! $plans->appends(request()->query())->links() !!}
        </div>
        @if ($plans->isEmpty())
            <div class="empty--data">
                <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                <h5>{{ translate('no_data_found') }}</h5>
            </div>
        @endif
    </div>


@endsection



@push('script_2')
    <script src="{{ asset('public/assets/admin/ckeditor/ckeditor.js') }}"></script>

    <script>
        "use strict";

        $("#customFileEg1").change(function() {
            readURL(this);
            $('#viewer').show(1000)
        });


        $(document).ready(function() {
            $('#plan_variation').change(function() {
                var planVariationId = $(this).val();
                if (planVariationId) {
                    $.ajax({
                        url: '{{ route('admin.get-plan-variation-details') }}',
                        type: 'POST',
                        data: {
                            plan_variation: planVariationId,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('input[name="plan_name"]').val(data.name);
                            $('input[name="product_limit"]').val(data.product_limit);

                            // Parse JSON-encoded description array
                            var descriptionArray = JSON.parse(data.description);

                            // Extract text from <p> tags and concatenate
                            var descriptionText = descriptionArray.map(function(html) {
                                // Create a temporary div to hold the HTML content
                                var tempDiv = $('<div>').html(html);
                                // Extract text from <p> tags and join with line breaks
                                return tempDiv.find('p').map(function() {
                                    return $(this).text();
                                }).get().join('\n');
                            }).join('\n\n'); // Add double line break between paragraphs

                            // Set the value of the textarea
                            $('textarea[id="description"]').val(descriptionText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
