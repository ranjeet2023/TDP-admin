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
                                    <div class="alert alert-danger alert-icon" role="alert"><i
                                            class="uil uil-times-circle"></i>
                                        {{ session()->get('update') }}
                                    </div>
                                @endif
                                <form role="form" method="post" action="{{ url('leads-edit-post') }}" enctype="multipart/form-data" id="leads_edit_post">
                                    @csrf
                                    <div class="card mb-3 gutter-b">
                                        <div class="card-header">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bolder fs-3">Edit Lead Form</span>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label for="firstname">First Name<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="firstname" value="{{ $lead->firstname }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="lastname">Last Name<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="lastname" value="{{ $lead->lastname }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="lead_type">Lead Type<span class='text-danger'>*</span></label>
                                                    <select name="lead_type" class="form-control" id="lead" >
                                                        <option value="">Select lead type</option>
                                                        <option value="supplier" {{ ($lead->type == 'supplier') ? 'selected' : '' }}>Supplier</option>
                                                        <option value="customer" {{ ($lead->type == 'customer') ? 'selected' : '' }}>Customer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label for="date_of_birth">Date Of Birth<span class='text-danger'>*</span></label>
                                                    <input type="date" class="form-control" name="date_of_birth" value="{{ $lead->date_of_birth }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="email">E-mail<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="email" value="{{ $lead->email }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="additional_email">Additional E-Mail<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="additional_email" value="{{ $lead->additional_email }}">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label for="mobile_number">Mobile Number<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="mobile_number" value="{{ $lead->mobile_number }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="phone_number">Phone Number<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="phone_number" value="{{ $lead->phone_number }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="fax_number">Additional Mobile Number<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="additional_mobile_number" value="{{ $lead->additional_mobile_number }}">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label for="country">Country
                                                        <span class='text-danger'>*</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Country of origination" aria-label="Country of origination"></i>
                                                    </label>
                                                    <select name="country" class="form-control" id="countySel" size="1">
                                                        <option value="{{ $lead->country }}" selected="selected">{{ $lead->country }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="State">State
                                                        <span class='text-danger'>*</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="State of origination" aria-label="State of origination"></i>
                                                    </label>
                                                    <select name="state" class="form-control" id="stateSel" size="1">
                                                        <option value="{{ $lead->state }}" selected="selected">{{ $lead->state }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="City">City
                                                        <span class='text-danger'>*</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="City of origination" aria-label="City of origination"></i>
                                                    </label>
                                                    <select name="city" class="form-control" id="districtSel" size="1">
                                                        <option value="{{ $lead->city }}" selected="selected">{{ $lead->city }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label for="fax_number">FAX Number<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="fax_number" value="{{ $lead->fax_number }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="created_by_userID">Created By<span class='text-danger'>*</span></label>
                                                    <select name="created_by" class="form-select" id="created_by">
                                                        <option value="">Select An User</option>
                                                        @foreach ($sales as $user)
                                                            <option value="{!! $user->id !!}" {{ ($lead->created_by_userID == $user->id) ? 'selected' : '' }}>{!! $user->firstname.' '.$user->lastname !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="assign_to">Assign To<span class='text-danger'>*</span></label>
                                                    <select name="assign_to" class="form-select" id="assign_to">
                                                        <option value="">Select An User</option>
                                                        @foreach ($sales as $user)
                                                            <option value="{!! $user->id !!}" {{ ($lead->assign_to == $user->id) ? 'selected' : '' }}>{!! $user->firstname.' '.$user->lastname !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label for="lead_status">Lead Status<span class='text-danger'>*</span></label>
                                                    <select name="lead_status" class="form-control" id="lead">
                                                        <option value="new" {{ ($lead->lead_status == 'new') ? 'selected' : '' }}>New Lead</option>
                                                        <option value="follow_up" {{ ($lead->lead_status == 'follow_up') ? 'selected' : '' }}>Follow-Up</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="last_contacted">Last Contacted<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="last_contacted" value="{{ $lead->last_contacted }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="company_name">Company Name<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="company_name" value="{{ $lead->company_name }}">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label for="website_url">Website URL<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="website_url" value="{{ $lead->website_url }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="associated_company">Associated Company<span class='text-danger'>*</span></label>
                                                    <input type="text" class="form-control" name="associated_company" value="{{ $lead->associated_company }}">
                                                </div>
                                                <input type="hidden" class="form-control" name="id" value="{{ $lead->id }}">
                                            </div>
                                            <div class="row mb-5">
                                                <div class="form-group mb-3 mt-3">
                                                    <button type="submit" class="ckditor btn btn-sm btn-primary">Edit The Lead</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <script src="{{asset('assets/js/countries.js')}}" type="text/javascript"></script>
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
        });
    </script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
