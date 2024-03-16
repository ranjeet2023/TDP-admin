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

	<link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
	@include('admin/css')

</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			@include('admin/sidebar')
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				@include('admin/header')
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<div id="kt_content_container" class="container-xxl">
                        @if(Session::has('update'))
                            <div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                {{ session()->get('update') }}
                            </div>
                        @endif
                        @if(Session::has('failed'))
                            <div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                {{ session()->get('failed') }}
                            </div>
                        @endif
                        <div class="card card-custom gutter-b">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Staff</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button class="btn btn-primary btn-sm me-4 total_record" title="Total Stone" data-placement="top" data-toggle="tooltip" data-original-title="Total Stone">Total Employee = <span id="total_stone_record">{{$emp_count}}</span></button>
                                    <!-- <button class="btn btn-sm btn-secondary me-4">Admin : <span id="total_pcs">{{$emp_count}}</span></button>
                                    <button class="btn btn-sm btn-secondary me-4">Sales : <span id="totalcarate">{{$emp_count}}</span></button>
                                    <button class="btn btn-sm btn-secondary me-4">Suppliers : <span id="totalpercarat">{{$emp_count}}</span></button>
                                    <button class="btn btn-sm btn-secondary">Accountent : <span id="totalamount">{{$emp_count}}</span></button> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                                <thead>
                                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                        <th class="column-title">Action </th>
                                                        <th class="column-title">Fisrt Name</th>
                                                        <th class="column-title">Last Name</th>
                                                        <th class="column-title">Access Type</th>
                                                        <th class="column-title">Mobile</th>
                                                        <th class="column-title">Email</th>
                                                        <th class="column-title">Skype</th>
                                                        <th class="column-title">Marketing Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-bold">
                                                    @foreach ($staff as $user)
                                                    <tr>
                                                        <td>
                                                        <a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                            <span class="svg-icon svg-icon-5 m-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
                                                                </svg>
                                                            </span>
                                                        </a>
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                                <div class="menu-item px-3">
                                                                    <a href="{{ url('staff-edit')}}/{{ $user->id }}" class="menu-link px-3">Edit</a>
                                                                </div>
                                                                <div class="menu-item px-3">
                                                                    <a href="{{ url('staff-permission')}}/{{ $user->id }}" class="menu-link px-3" >Permission</a>
                                                                </div>
                                                                <div class="menu-item px-3">
                                                                    <a href="{{ url('staff-delete')}}/{{ $user->id }}" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>
                                                                </div>

                                                            </div>
														</td>
                                                        <td class="">{{$user->firstname}}</td>
                                                        <td class="">{{$user->lastname}}</td>
                                                        <td class="">{{$user->admins->account_type}}</td>
                                                        <td class="">{{$user->mobile}}</td>
                                                        <td class="">{{$user->email}}</td>
                                                        <td class="">{{$user->admins->skypeid}}</td>
                                                        <td class="">{{$user->admins->marketing_mail}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <ul class="pagination">
                                                <li class="page-item previous disabled" id="previous_page">
                                                    <a href="javascript:void(0)"><span class="page-link">Previous</span></a>
                                                </li>
                                                <li class="page-item next" id="next_page">
                                                    <a href="javascript:void(0)" class="page-link">Next</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link"><span id="pagecount">1</span> to <span id="totalrecord"></span> Total Pages</a>
                                                </li>
                                            </ul>
                                            <!--<div id="add_to_cart">add_to_cart   </div>-->
                                        </div>
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
    <div class="modal fade" id="header-modal" aria-hidden="true"></div>

	<script>var hostUrl = "/assets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->

    <script>
        $(document).ready(function() {
            var xhr;
            var total_selected = 0;
            var selected_ids = "";
            var page_record_from = 0;
            function request_call(url, mydata) {
                if (xhr && xhr.readyState != 4) {
                    xhr.abort();
                }

                xhr = $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: mydata,
                });
            }
        });
    </script>
</body>
</html>
