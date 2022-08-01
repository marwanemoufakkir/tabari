
<!DOCTYPE html>

<html direction="rtl" dir="rtl" style="direction: rtl">
<!--begin::Head-->
    <head><base href="../../">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <!--begin::Fonts-->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" /> --}}
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="assets/plugins/global/plugins.bundle.rtl.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.rtl.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <style>

        .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 0;
            padding-right: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            /* font-weight: 900; */
            white-space: nowrap;
            color: black;
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-column flex-column-fluid">
            <!--begin::Header-->
            <div id="kt_header" class="header align-items-stretch">
                <!--begin::Container-->
                <div class="container-xxl d-flex align-items-stretch justify-content-between">
                    <!--begin::Brand-->
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 w-lg-225px me-5">
                        <!--begin::Aside mobile toggle-->
                        <div class="btn btn-icon btn-active-icon-primary ms-n2 me-2 d-flex d-lg-none" id="kt_aside_toggle">
                            <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
                                    <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </div>
                        <!--end::Aside mobile toggle-->
                        <!--begin::Logo-->
                        <a href="/">
                            <img alt="Logo" src="assets/media/logos/LOGO-x76x53.png" class="d-none d-lg-inline h-50px" />
                            <img alt="Logo" src="assets/media/logos/LOGO-x76x53.png" class="d-lg-none h-40px" />
                        </a>
                        <!--end::Logo-->
                    </div>
                    <!--end::Brand-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                        <!--begin::Navbar-->
                        <div class="d-flex align-items-stretch" id="kt_header_nav">
                            <!--begin::Menu wrapper-->
                            <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                                <!--begin::Menu-->
                                <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
                                    <div class="menu-item me-lg-1">
                                        <a class="menu-link  active py-3" href="{{'/search'}}">
                                            <span class="text-dark fw-bolder">Search</span>
                                        </a>
                                    </div>
                                    
                                    <div class="menu-item me-lg-1">
                                        <a class="menu-link  py-3" href="/topics?surah=&chart=packedbubble">
                                            <span class="text-dark fw-bolder">Graph</span>
                                        </a>
                                    </div>

                                    <div class="menu-item me-lg-1">
                                        <a class="menu-link  py-3" href="#">
                                            <span class="text-dark fw-bolder">About</span>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Menu wrapper-->
                        </div>
                        <!--end::Navbar-->

                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Header-->
            <!--begin::Container-->
            <div id="kt_content_container" class="d-flex flex-column-fluid align-items-stretch container-xxl">

                <!--begin::Wrapper-->
                <div class="wrapper d-flex flex-column flex-row-fluid mt-5 mt-lg-10" id="kt_wrapper">
                    <!--begin::Content-->
                    <div class="content flex-column-fluid" id="kt_content">
                        <!--begin::Toolbar-->
                        <div class="toolbar d-flex flex-stack flex-wrap mb-5 mb-lg-7" id="kt_toolbar">
                            <!--begin::Page title-->
                            <div class="page-title d-flex flex-column py-1">
                                <!--begin::Title-->
                                <h1 class="d-flex align-items-center my-1">
                                    <span class="text-dark fw-bolder fs-1">Search</span>
                                </h1>
                                <!--end::Title-->
                                                                    <!--begin::Breadcrumb-->
                                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                                    <!--begin::Item-->
                                    <li class="breadcrumb-item text-muted">
                                        <a href="/topics?surah=&chart=packedbubble" class="text-muted text-hover-primary">Graph</a>
                                    </li>
                                    <!--end::Item-->

                                    <!--begin::Item-->
                                    <li class="breadcrumb-item">
                                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                                    </li>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <li class="breadcrumb-item text-dark">Search</li>
                                    <!--end::Item-->
                                </ul>
                                <!--end::Breadcrumb-->
                            </div>
                            <!--end::Page title-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Post-->
                        <div class="post" id="kt_post">
                            <!--begin::Form-->
                            <form method="get" action="/result" enctype="multipart/form-data" >
                                
                                <!--begin::Card-->
                                <div class="card mb-7">
                                    <!--begin::Card body-->
                                    <div class="card-body">

                                        <!--begin::Advance form-->
                                        <div class="collapse show" id="kt_advanced_search_form">

                                            <!--begin::Row-->
                                            <div class="row g-8 mb-8">
                                                    <!--begin::Repeater-->
                                                    <div id="kt_docs_repeater_advanced">
                                                        @if ($errors->any())
                                                        <!--begin::Alert-->
                                                        <div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10">
                                                            <!--begin::Icon-->
                                                            <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="currentColor"></path>
                                                                <path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="currentColor"></path>
                                                            </svg></span>
                                                            <!--end::Icon-->

                                                            <!--begin::Wrapper-->
                                                            <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                                                                <!--begin::Title-->
                                                                <h4 class="mb-2 light">This is an alert</h4>
                                                                <!--end::Title-->

                                                                <!--begin::Content-->
                                                                @foreach ($errors->all() as $error)
                                                                <span class="fs-2">{{ $error }}</span>
                                                                @endforeach
                                                                <!--end::Content-->
                                                            </div>
                                                            <!--end::Wrapper-->

                                                            <!--begin::Close-->
                                                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                                <span class="svg-icon svg-icon-2x svg-icon-light"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                                                </svg></span>
                                                            </button>
                                                            <!--end::Close-->
                                                        </div>
                                                        <!--end::Alert-->
                                                        @endif

                                                        
                                                        <!--begin::Form group-->
                                                        <div class="form-group">
                                                            <div data-repeater-list="kt_docs_repeater_advanced">
                                                                <div data-repeater-item>
                                                                    <div class="form-group row mb-5">
                                                                        <div class="position-relative w-md-200px me-md-2">
                                                                            <select name="search_type" class="form-select form-select-solid"  data-kt-repeater="select2"  data-placeholder="Default" >
                                                                                <option value="default" selected="selected">Default</option>
                                                                                <option value="exact">Exact</option>
                                    
                                                                            </select>
                                                                        </div>
                                                                        <div class="position-relative w-md-400px me-md-2">
                                                                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                                                            <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                                                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                                                                                </svg>
                                                                            </span>
                                                                            <!--end::Svg Icon-->
                                                                            <input type="text" class="form-control form-control-solid ps-10" name="content" value="" placeholder=" بحث  search" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mb-5">
                                                                        <div class="col-md-3">
                                                                            <label class="fs-2 form-label fw-bolder text-dark"> سورة  Surah  :</label>
                                                                            <select  name="chapter" class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder=" سورة  Surah  ">
                                                                                <option value=""></option>
                                                                                
                                                                                @foreach (\App\Models\Surah::get() as $surah)
                                                                                <option value="{{ $surah->id }}">{!! str_replace('سورة', ' ', $surah->title) !!} - {{ $surah->transliteration }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="fs-2 form-label fw-bolder text-dark"> آية Ayah :</label>
                                                                            <select name="ayah" class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder=" آية Ayah " disabled>
                                                                                <option value=""></option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="fs-2 form-label fw-bolder text-dark">موضوع Topic :</label>
                                                                            <select name="topic" class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder="موضوع Topic" >
                                                                                <option value=""></option>
                                                                                @php
                                                                                    \DB::statement("SET SQL_MODE=''");
                                                                                @endphp
                                                                                @foreach (\App\Models\Topic::groupBy('name')->get()->toArray() as $topic)
                                                                                <option value="{{ $topic['tag']}}">{{ $topic['name']}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="fs-2 form-label fw-bolder text-dark">موضوع فرعي Subtopic :</label>
                                                                            <select name="subtopic" class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder="موضوع فرعي Subtopic" >
                                                                                <option value=""></option>
                                                                                @foreach (\App\Models\Subtopic::groupBy('name')->get()->toArray() as $subtopic)
                                                                                <option value="{{ $subtopic['tag']}}">{{ $subtopic['name']}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="fs-2 form-label fw-bolder text-dark">نوع Type :</label>
                                                                            <select name="type" class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder="نوع Type" >
                                                                                <option value=""></option>
                                                                                <option value="hadith">hadith</option>
                                                                                <option value="nothadith">nothadith</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mb-5">
                                                                        <!--begin::Col-->
                                                                        <div class="col-lg-2">
                                                                            <label class="fs-2 form-label fw-bolder text-dark">Operator :</label>
                                                                            <!--begin::Radio group-->
                                                                            <div class="nav-group nav-group-fluid">
                                                                                <!--begin::Option-->
                                                                                <label>
                                                                                    <input type="radio" class="btn-check" name="bool" value="MUST" checked="checked" />
                                                                                    <span class="btn btn-active btn-active-warning fw-bolder btn-text-dark px-4"> و AND</span>
                                                                                </label>
                                                                                <!--end::Option-->
                                                                                <!--begin::Option-->
                                                                                <label>
                                                                                    <input type="radio" class="btn-check" name="bool" value="SHOULD" />
                                                                                    <span class="btn btn-active btn-active-warning fw-bolder btn-text-dark px-4"> أو OR</span>
                                                                                </label>
                                                                                <!--end::Option-->
                                                                                <!--begin::Option-->
                                                                                <label>
                                                                                    <input type="radio" class="btn-check" name="bool" value="MUST_NOT" />
                                                                                    <span class="btn btn-active btn-active-warning fw-bolder btn-text-dark px-4"> لا NOT</span>
                                                                                </label>
                                                                                <!--end::Option-->
                                                                            </div>
                                                                            <!--end::Radio group-->
                                                                        </div>
                                                                        <!--end::Col-->
                                                                        <div class="col-md-2 pt-3">
                                                                            
                                                                            <a href="javascript:;" data-repeater-delete class="btn btn-icon btn-danger fw-bolder btn-text-dark  mt-3 mt-md-9">
                                                                                <i class="text-dark la la-remove  fs-2"></i>
                                                                            </a>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="separator separator-dashed mt-9 mb-6"></div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                        <!--end::Form group-->

                                                        <div class="d-flex align-items-center">
                                                            <button type="button" data-repeater-create class="btn btn-icon btn-secondary fw-bolder me-2"><i class="text-dark la la-plus"></i></button>
                                                            <button type="submit" class="btn btn-icon btn-warning fw-bolder btn-text-dark"><i class="text-dark la la-search fs-2 fw-bolder"></i></button>
                                                        </div>
      
                                               
                                                    </div>
                                                    <!--end::Repeater-->
                                            </div>
                                            <!--end::Row-->
                                        </div>
                                        <!--end::Advance form-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
                            </form>
                            <!--end::Form-->

                            <!--begin::Tab Content-->
                            <div class="tab-content">
                                <!--begin::Tab pane-->
                                <div id="kt_project_users_card_pane" class="tab-pane fade show active">
                                    <!--begin::Row-->
                                    <div class="row g-6 g-xl-9">

                                    </div>
                                    <!--end::Row-->

                                </div>
                                <!--end::Tab pane-->
                            </div>
                            <!--end::Tab Content-->
                        </div>
                        <!--end::Post-->
                    </div>
                    <!--end::Content-->
             
                </div>
                </div>
                <!--end::Wrapper-->
                <div class="footer pt-10 pb-5 d-flex flex-column flex-md-row flex-stack" id="kt_footer" style="background-color: #757052;padding-left: 40px;padding-right: 40px;">
                    <!--begin::Copyright-->
                    <div class="text-dark order-2 order-md-1">
                        <span class="text-white fw-semibold me-1">2022©</span>
                    </div>
                    <!--end::Copyright-->
                    <!--begin::Menu-->
                    <ul class="menu fw-semibold order-1">
                        <li class="menu-item">
                            <a href="/topics?surah=&amp;chart=packedbubble"  class="text-white  px-2">Graph</a>
                        </li>
                        <li class="menu-item">
                            <a href="/"  class="text-white  px-2">Search</a>
                        </li>
                        <li class="menu-item">
                            <a href="/about"  class="text-white  ps-2 pe-0">About</a>
                        </li>
                    </ul>
                    <!--end::Menu-->
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Scrolltop-->
    <!--end::Main-->
    <script>var hostUrl = "assets/";</script>
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Vendors Javascript(used by this page)-->
    {{-- <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script> --}}
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->
    {{-- <script src="assets/js/custom/pages/search/horizontal.js"></script> --}}
    {{-- <script src="assets/js/custom/pages/projects/users/users.js"></script> --}}
    <script src="assets/js/custom/widgets.js"></script>
    <script src="assets/plugins/custom/formrepeater/jquery.repeater.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#kt_docs_repeater_advanced').repeater({
                defaultValues: {
                    'type': 'and'
                },
                show: function () {
                    $(this).slideDown();

                                        

                    var ayahQuerySelector=$(this).find('[data-kt-repeater="select2"]:eq(2)');

                    $(this).find('[data-kt-repeater="select2"]').select2();
                    $(this).find('[data-kt-repeater="select2"]').val("default").trigger("change");
                    $(this).find('[data-kt-repeater="select2"]:eq(1)').on('select2:select', function (e) {
                        var selectQ= $(this).find('[data-kt-repeater="select2"]:eq(2)');
                        var data = e.params.data;
                        var idSurah =data.id;
                        
                        $.ajax({
                            url: "{{url('api/fetch-ayah')}}",
                            type: "POST",
                            data: {
                                surah_id: idSurah,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (result) {
                               ayahQuerySelector.empty();
                               ayahQuerySelector.append('<option value=""></option>');
                                $.each(result.data, function (key, value) {
                                    if(value.ayah_number!=0){
                                        ayahQuerySelector.prop("disabled", false); // are now enabled.
                                        ayahQuerySelector.append('<option value="' + value.surah_id+"."+value.number + '">' + value.number + '</option>');
                                    }

                                    
                                    
                                });
                                
                                
                            }
                        });
                    });

                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function(){
                    
                    // Init select2
                    $('[data-kt-repeater="select2"]').select2();
                   
                    $('[data-kt-repeater="select2"]:eq(1)').on('select2:select', function (e) {
                        var data = e.params.data;
                        var idSurah =data.id;
                        $.ajax({
                            url: "{{url('api/fetch-ayah')}}",
                            type: "POST",
                            data: {
                                surah_id: idSurah,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (result) {
                                console.log(result.data)
                                $('[data-kt-repeater="select2"]:eq(2)').empty();
                                $('[data-kt-repeater="select2"]:eq(2)').append('<option value=""></option>');
                                $.each(result.data, function (key, value) {
                                    if(value.number!=0){

                                    $('[data-kt-repeater="select2"]:eq(2)').prop("disabled", false); // are now enabled.
                                    $('[data-kt-repeater="select2"]:eq(2)').append('<option value="' + value.surah_id+"."+value.number + '">' + value.number + '</option>');
                                    
                                    }
                                });
                                
                                
                            }
                        });
                    });

                },
                isFirstItemUndeletable: true
            });



				
		});
        </script>
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
