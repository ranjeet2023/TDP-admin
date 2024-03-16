<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>{{config('app.name')}}</title>
	<meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

	<meta name="description" content="{{config('app.website')}}" />
	<meta name="keywords" content="{{config('app.website')}}" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" />

	@include('admin/css')
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			@include('admin/sidebar')
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				@include('admin/header')
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div id="kt_content_container" class="container-xxl">
                            <div class="card mb-5 mb-xl-10">
                                <div class="card-body pt-9 pb-0">
                                    <!--begin::Details-->
                                    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                                        <!--begin: Pic-->
                                        <div class="me-7 mb-4">
                                            <div class="position-relative">
                                                <img src="assets/images/logo-small.png" alt="image" width="200px">
                                            </div>
                                        </div>
                                        <!--end::Pic-->
                                        <!--begin::Info-->
                                        <div class="flex-grow-1">
                                            <!--begin::Title-->
                                            <div
                                                class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                                <!--begin::User-->
                                                <div class="d-flex flex-column">
                                                    <!--begin::Name-->
                                                    <div class="d-flex align-items-center mb-2">
                                                        <a
                                                            class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{ $admin->companyname }}</a>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <a
                                                            class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{ $admin->firstname . ' ' . $admin->lastname }}</a>
                                                        <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px"
                                                                height="24px" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                                    fill="#00A3FF"></path>
                                                                <path class="permanent"
                                                                    d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                                    fill="white"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <!--end::Name-->
                                                    <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                                        <a href="#"
                                                            class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                            <span class="svg-icon svg-icon-4 me-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                                        fill="black"></path>
                                                                    <path
                                                                        d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                                        fill="black"></path>
                                                                </svg>
                                                            </span>
                                                            {{ $admin->email }}</a>
                                                    </div>
                                                </div>
                                                <div class="d-flex my-4">
                                                    <!-- <a href="#" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_offer_a_deal">Hire Me</a> -->
                                                </div>
                                                <!--end::Actions-->
                                            </div>
                                            <!--end::Title-->
                                            <div class="d-flex flex-wrap flex-stack">
                                                <!--begin::Wrapper-->
                                                <!-- <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                                    <span class="fw-bold fs-6 text-gray-400">Profile Compleation</span>
                                                    <span class="fw-bolder fs-6">50%</span>
                                                    </div>
                                                    <div class="h-5px mx-3 w-100 bg-light mb-3">
                                                    <div class="bg-success rounded h-5px" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-icon" role="alert"><i
                                        class="uil uil-times-circle"></i>
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif
                            <form id="kt_account_profile_details_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                action="{{ route('admin.profile.edit') }}" enctype='multipart/form-data'
                                novalidate="novalidate" method="post">
                                @csrf
                                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                                    <span class="text-primary">{{ session('message') }}</span>
                                        <div class="card-header cursor-pointer">
                                            <div class="card-title m-0">
                                                <h3 class="fw-bolder m-0">Profile Details</h3>
                                            </div>
                                        </div>
                                        <div class="card-body p-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row mb-6">
                                                        <label
                                                            class="col-lg-4 col-form-label required fw-bold fs-6">Full
                                                            Name</label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                                                    <input type="text" name="firstname"
                                                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                                        placeholder="First name"
                                                                        value="{{ $admin->firstname }}">
                                                                    <div
                                                                        class="fv-plugins-message-container invalid-feedback">
                                                                        <span class="text-danger">
                                                                            @error('firstname')
                                                                                {{ $message }}
                                                                            @enderror
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                                                    <input type="text" name="lastname"
                                                                        class="form-control form-control-lg form-control-solid"
                                                                        placeholder="Last name"
                                                                        value="{{ $admin->lastname }}">
                                                                    <div
                                                                        class="fv-plugins-message-container invalid-feedback">
                                                                        <span class="text-danger">
                                                                            @error('lastname')
                                                                                {{ $message }}
                                                                            @enderror
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-6">
                                                        <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                            <span class="required">Contact Phone</span>
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Phone number must be active"
                                                                aria-label="Phone number must be active"></i>
                                                        </label>
                                                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                            <input type="tel" name="mobile"
                                                                class="form-control form-control-lg form-control-solid"
                                                                placeholder="Phone number"
                                                                value="{{ $admin->mobile }}">
                                                            <div class="fv-plugins-message-container invalid-feedback">
                                                                <span class="text-danger">
                                                                    @error('mobile')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <label
                                                            class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
                                                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                            <input type="text" name="email"
                                                                class="form-control form-control-lg form-control-solid"
                                                                placeholder="Company Email"
                                                                value="{{ $admin->email }}">
                                                            <div class="fv-plugins-message-container invalid-feedback">
                                                                <span class="text-danger">
                                                                    @error('email')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end py-6 px-9">
                                    <button type="reset"
                                        class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
                                </div>
                        </div>
                        </form>
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
        var hostUrl = "massets/";
    </script>
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{ asset('assets/admin/js/custom/intro.js') }}"></script>
    <!--end::Page Custom Javascript-->

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
    <!--end::Javascript-->
</body>

</html>
