<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="{{ config('app.website') }}" />
    <meta name="keywords" content="{{ config('app.website') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    @include('admin/css')
</head>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed"
    style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            @include('admin/sidebar')
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('admin/header')
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div id="kt_content_container" class="container-xxl">
                        <div class="row gy-5 g-xl-8">
                            <div class="col-xl-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-icon" role="alert"><i
                                            class="uil uil-times-circle"></i>
                                        {{ session()->get('success') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-icon" role="alert"><i
                                            class="uil uil-times-circle"></i>
                                        @foreach ($errors->all() as $error)
                                            {{ $error }}
                                        @endforeach
                                    </div>
                                @endif

                                @if (Session::has('update'))
                                    <div class="alert alert-success alert-icon" role="alert"><i
                                            class="uil uil-times-circle"></i>
                                        {{ session()->get('update') }}
                                    </div>
                                @endif
                                <div class="card mb-3 gutter-b">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder fs-3">Add New Return Diamond</span>
                                        </h3>
                                        <div class="card-toolbar">
                                            <a href="{{ url('return-diamond-list') }}" class="btn btn-primary btn-icon btn-sm"><i class="fa fa-arrow-left"></i></a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-5">
                                            <div class="col-md-5 col-sm-18">
                                                <div class="form-group">
                                                    <label for="title"> Certificate Number<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control"name="certificate" id="certificate">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-18 offset-md-1">
                                                <label for="title"> </label><br>
                                                <button type="button" class="btn btn-warning" id="searchbtn">Search The Diamond</button>
                                            </div>
                                        </div>
                                        <form role="form" method="post" action="{{ route('admin.add-return-diamond-post') }}" enctype="multipart/form-data" id="purchase_bill_form">
                                            @csrf
                                            <input type="hidden" name="certificate" class="certificate">
                                                <div class='details'>

                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin/footer')
            </div>
        </div>
    </div>

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)"
                    fill="black" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="black" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Scrolltop-->
    <!--end::Main-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/countries.js') }}" type="text/javascript"></script> --}}
    <!--end::Global Javascript Bundle-->

    <!--begin::Page Custom Javascript(used by this page)-->

    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/intro.js') }}"></script>
    <!--end::Page Custom Javascript-->

    <script type="text/javascript">
        localStorage.setItem("ak_search", "");
        localStorage.setItem("lg_search", "");

        // $('#kt_table_users').DataTable({
        //     'processing': true,
        // });

        $(document).ready(function() {
            var xhr;
            var total_selected = 0;
            var page_record_from = 0;
            var selected_ids = "";

            function request_call(url, mydata) {
                if (xhr && xhr.readyState != 4) {
                    xhr.abort();
                }

                xhr = $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: mydata,
                });
            }
            $('.card-body').delegate('#searchbtn', 'click', function() {
                var certificate = $('#certificate').val();
                $('.certificate').val(certificate);
                blockUI.block();
                request_call("{{ url('search-return-diamond') }}", "certificate=" + certificate);
                xhr.done(function(mydata) {
                    blockUI.release();
                    if ($.trim(mydata.detail) != "") {
                        $('.details').empty();
                        $('.details').html($.trim(mydata.detail));
                    }
                    else{
                        $('.details').html($.trim(mydata.detail));
                    }
                });
            });
        });
    </script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>