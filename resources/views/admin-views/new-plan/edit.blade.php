@extends('layouts.admin.app')


@section('title',translate('messages.Edit Plan'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/plan.png')}}" class="" alt="">
            </span>
            <span>
                {{translate('Edit Plan')}}
            </span>
        </h1>
    </div>
    <!-- End Page Header -->
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.plan.updateValue') }}" method="post" enctype="multipart/form-data">
            @csrf
            <ul class="nav nav-tabs mb-4 border-0">
                <li class="nav-item">
                    <a class="nav-link lang_link active" href="#"
                        id="default-link">{{translate('messages.default')}}</a>
                </li>



            </ul>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group  lang_form" id="">
                        <label class="input-label"
                            for="exampleFormControlInput1">{{translate('messages.plan_variation')}}
                        </label>
                        <select id="plan_variation" name="plan_variation" class="form-control js-select2-custom">
                            <option value="" selected disabled>{{ translate('Add Plan Variation') }}</option>
                            @foreach(\App\Models\Plan::all() as $variation)
                            <option value="{{ $variation->id }}">{{ $variation->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group lang_form" id="default-form">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.plan_name')}}
                            ({{ translate('messages.default') }})
                            <span class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('messages.Required.')}}"> *
                            </span>

                        </label>

                        <input type="hidden" id="editValue" name="editValue" value="{{$plan->id}}">
                        <input type="text" name="plan_name" class="form-control"
                            placeholder="{{translate('messages.plan_name')}}" maxlength="191"
                            value="{{ $plan->name ?? old('plan_name') }}">
                    </div>
                    <input type="hidden" name="lang[]" value="default">

                    <div class="form-group  lang_form" id="">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.plan_price')}}
                        </label>
                        <input type="number" name="plan_price" class="form-control"
                            placeholder="{{translate('messages.plan_price')}}" maxlength="191"
                            value="{{ $plan->price ?? old('plan_price') }}">
                    </div>
                    <input type="hidden" name="lang[]" value="">

                    <div class="form-group  lang_form" id="">
                        <label class="input-label"
                            for="exampleFormControlInput1">{{translate('messages.product_limit')}}
                        </label>
                        <input type="text" name="product_limit" class="form-control"
                            placeholder="{{translate('messages.product_limit')}}" maxlength="191"
                            value="{{ $plan->product_limit ?? old('product_limit') }}">
                    </div>
                    <input type="hidden" name="lang[]" value="">

                    {{-- <div class="form-group  lang_form" id="">
                        <label class="input-label"
                            for="exampleFormControlInput1">{{translate('messages.plan_validate')}}
                        </label>
                        <select id="exampleFormControlSelect1" name="plan_duration"
                            class="form-control js-select2-custom" required>
                            <option value="" selected disabled>{{ translate('Select Plan Duration') }}</option>
                            <option value="3" {{ $plan->plan_duration == 3 ? 'selected' : '' }}>Quaterly</option>
                            <option value="6" {{ $plan->plan_duration == 6 ? 'selected' : '' }}>Half Year</option>
                            <option value="12" {{ $plan->plan_duration == 12 ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>
                    <input type="hidden" name="lang[]" value="">

                    <div class="form-group  lang_form" id="">
                        <label class="input-label"
                            for="exampleFormControlInput1">{{translate('messages.plan_description')}}
                        </label>


                        <textarea id="description" class="ckeditor form-control" name="description[]">

                        {!! json_decode($plan->description)[0] ?? old('description') !!}

                    </textarea>
                    </div> --}}
                    {{-- <input type="hidden" name="lang[]" value=""> --}}



                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center flex-column">
                        <label class="mb-3 text-center">{{translate('messages.image')}} <small class="text-danger">* (
                                {{translate('messages.ratio')}} 1:1)</small></label>
                        <label class="text-center my-auto position-relative d-inline-block">
                            <img class="img--176 border" id="viewer" src="{{ isset($plan) ? asset('storage/app/public/plan/' . $plan->image) : asset('public/assets/admin/img/upload-img.png') }}" alt="image" />

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
                                <input type="text" name="desc_1" class="form-control" value="{{ $plan->desc_1 ?? old('desc_1') }}"
                                    placeholder="{{ translate('messages.product_limit') }}" maxlength="191">
                            </div>
                            <input type="hidden" name="lang[]" value="">

                            <div class="form-group  lang_form" id="">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.Description 2') }}
                                </label>
                                <input type="text" name="desc_2" class="form-control"value="{{ $plan->desc_2 ?? old('desc_2') }}"
                                    placeholder="{{ translate('messages.product_limit') }}" maxlength="191">
                            </div>
                            <input type="hidden" name="lang[]" value="">
                            <div class="form-group  lang_form" id="">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.Description 3') }}
                                </label>
                                <input type="text" name="desc_3" class="form-control" value="{{ $plan->desc_3 ?? old('desc_3') }}"
                                    placeholder="{{ translate('messages.product_limit') }}" maxlength="191">
                            </div>
                            <input type="hidden" name="lang[]" value="">
                        </div>

                    </div>
                </div>
            </div>
            <div class="btn--container justify-content-end mt-3">
                <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>

                <button type="submit" class="btn btn--primary">{{translate('messages.edit')}}</button>
            </div>

        </form>
    </div>
</div>



@endsection



@push('script_2')
<script src="{{asset('public/assets/admin/ckeditor/ckeditor.js')}}"></script>

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
                url: '{{ route("admin.get-plan-variation-details") }}',
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
