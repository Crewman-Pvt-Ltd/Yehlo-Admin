@extends('layouts.admin.app')

@section('title', translate('messages.admin_landing_page'))

@section('content')
    <div class="content container-fluid">
        <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <div class="card mb-3">
            <div class="card-header py-2">
                <h5 class="card-title">First Section</h5>
            </div>
            <div class="card-body">

                <div class="row g-3 lang_form" id="default-form">
                    <div class="col-sm-6">
                        <label for="title" class="form-label">Title (Default)<span class="form-label-secondary"
                                data-toggle="tooltip" data-placement="right"
                                data-original-title="Write the title within 20 characters">
                                <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg"
                                    alt="">
                            </span></label>
                        <input id="delivery_partner_title" type="text" maxlength="20" name="delivery_partner_title[]"
                            class="form-control" placeholder="Title here...">
                    </div>
                    <div class="col-sm-6">
                        <label for="sub_title" class="form-label">Sub Title (Default)<span class="form-label-secondary"
                                data-toggle="tooltip" data-placement="right"
                                data-original-title="Write the title within 80 characters">
                                <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg"
                                    alt="">
                            </span></label>
                        <input id="delivery_sub_title" type="text" maxlength="80" name="delivery_sub_title[]"
                            class="form-control" placeholder="Sub title here...">
                    </div>
                </div>
                <input type="hidden" name="lang[]" value="default">

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label d-block mb-2">
                            Image <span class="text--primary">(size: 3:1)</span>
                        </label>
                        <label class="upload-img-3 m-0 d-block">
                            <div class="img">
                                <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/upload-4.png"
                                    data-onerror-image="http://localhost/yehlo_server/admin/public/assets/admin/img/upload-4.png"
                                    class="vertical-img mw-100 vertical onerror-image" alt="">
                            </div>
                            <input type="file" name="image" hidden="">
                        </label>
                    </div>
                </div>
               
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header py-2">
                <h5 class="card-title">Second Section</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                                                        <div class="col-md-6 lang_form default-form">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="title" class="form-label">Title
                                        (Default)<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="Write the title within 20 characters">
                                            <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg" alt="">
                                        </span></label>
                                    <input id="card_header" type="text" maxlength="20" name="card_header[]" class="form-control" placeholder="Ex : Shopping">
                                </div>
                                <div class="col-12">
                                    <label for="sub_title" class="form-label">Sub Title
                                        (Default)<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="Write the title within 80 characters">
                                            <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg" alt="">
                                        </span></label>
                                    <input id="card_title" type="text" maxlength="80" name="card_title[]" class="form-control" placeholder="Ex : Best shopping experience">
                                </div>
                                <div class="col-12">
                                    <label for="sub_title" class="form-label">Sub Title
                                        (Default)<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="Write the title within 80 characters">
                                            <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg" alt="">
                                        </span></label>
                                    <input id="card_sub_title" type="text" maxlength="80" name="card_sub_title[]" class="form-control" placeholder="Ex : Best shopping experience">
                                </div>
                               
                            </div>
                        </div>
                        <input type="hidden" name="lang[]" value="default">
                                                             
                        
                                                        
                    <div class="col-md-6">
                        <label class="form-label d-block mb-2">
                            Image <span class="text--primary">(size: 1:1)</span>
                        </label>
                        <label class="upload-img-3 m-0">
                            <div class="img">
                                <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/aspect-1.png" data-onerror-image="http://localhost/yehlo_server/admin/public/assets/admin/img/aspect-1.png" alt="image" class="img__aspect-1 min-w-187px max-w-187px onerror-image">
                            </div>
                            <input type="file" name="image" hidden="">
                        </label>
                    </div>
                </div>
                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" class="btn btn--reset">Reset</button>
                    <button type="button" class="btn btn--primary mb-2">Add</button>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header py-2">
                <h5 class="card-title">Footer Section</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                                                        <div class="col-md-6 lang_form default-form">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="title" class="form-label">Title
                                        (Default)<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="Write the title within 20 characters">
                                            <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg" alt="">
                                        </span></label>
                                    <input id="footer_heading" type="text" maxlength="20" name="footer_heading[]" class="form-control" placeholder="Ex : Shopping">
                                </div>
                                <div class="col-12">
                                    <label for="sub_title" class="form-label">Sub Title
                                        (Default)<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="Write the title within 80 characters">
                                            <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg" alt="">
                                        </span></label>
                                    <input id="footer_sub_heading" type="text" maxlength="80" name="footer_sub_heading[]" class="form-control" placeholder="Ex : Best shopping experience">
                                </div>
                                <div class="col-12">
                                    <label for="sub_title" class="form-label">Sub Title
                                        (Default)<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="Write the title within 80 characters">
                                            <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg" alt="">
                                        </span></label>
                                    <input id="footer_text" type="text" maxlength="80" name="footer_text[]" class="form-control" placeholder="Ex : Best shopping experience">
                                </div>
                                <div class="col-12">
                                    <label for="sub_title" class="form-label">Sub Title
                                        (Default)<span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="Write the title within 80 characters">
                                            <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/info-circle.svg" alt="">
                                        </span></label>
                                    <input id="footer_sub_title" type="text" maxlength="80" name="footer_sub_title[]" class="form-control" placeholder="Ex : Best shopping experience">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="lang[]" value="default">
                                                             
                        
                                                        
                    <div class="col-md-6">
                        <label class="form-label d-block mb-2">
                            Image <span class="text--primary">(size: 1:1)</span>
                        </label>
                        <label class="upload-img-3 m-0">
                            <div class="img">
                                <img src="http://localhost/yehlo_server/admin/public/assets/admin/img/aspect-1.png" data-onerror-image="http://localhost/yehlo_server/admin/public/assets/admin/img/aspect-1.png" alt="image" class="img__aspect-1 min-w-187px max-w-187px onerror-image">
                            </div>
                            <input type="file" name="image" hidden="">
                        </label>
                    </div>
                </div>
             
            </div>
        </div>
        <div class="btn--container justify-content-end mt-3">
         
            <button type="submit" class="btn btn--primary mb-2">Submit</button>
        </div>
      
    </form>
    </div>
    <!-- How it Works -->
    @include('admin-views.business-settings.landing-page-settings.partial.how-it-work')
@endsection
