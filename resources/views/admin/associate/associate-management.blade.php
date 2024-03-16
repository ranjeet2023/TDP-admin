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
                                    <span class="card-label fw-bolder text-dark">Associate List</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table fs-6 gy-5" id="kt_table_users">
                                                <thead>
                                                   <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th class="column-title">Edit</th>
                                                        <th class="column-title">Name</th>
                                                        <th class="column-title">Email</th>
                                                        <th class="column-title">Mobile</th>
                                                        <th class="column-title">Address</th>
                                                        <th class="column-title">country</th>

                                                        <th class="column-title">GST No.</th>
                                                        <th class="column-title">PAN No.</th>

                                                        <th class="column-title">Account Number</th>
                                                        <th class="column-title">Bank Name</th>
                                                        <th class="column-title">Bank Address</th>
                                                        <th class="column-title">Swift Code</th>
                                                        <th class="column-title">Address Code</th>

                                                        <th class="column-title">Routing Number (Bank Wire)</th>
                                                        <th class="column-title">Routig Number (Directs Deposite & ACH)</th>
                                                        <th class="column-title">Intermediary Bank</th>
                                                        <th class="column-title">Intermediary Bank Swift Code</th>

                                                        <th class="column-title">HSN Code Natural</th>
                                                        <th class="column-title">HSN Code Natural One</th>
                                                        <th class="column-title">HSN Code Lab</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">
                                                    @foreach ($associate as $associate)
                                                    <tr>
                                                        <td> <a href="{{url('associate-edit')}}/{{$associate->id}}" class="btn btn-sm btn-primary">Edit</a></td>
                                                        <td>{{$associate->name}}</td>
                                                        <td>{{$associate->email}}</td>
                                                        <td>{{$associate->mobile}}</td>
                                                        <td>{{$associate->address}}</td>
                                                        <td>{{$associate->country}}</td>

                                                        <td>{{$associate->gst_no}}</td>
                                                        <td>{{$associate->pan_no}}</td>

                                                        <td>{{$associate->account_number}}</td>
                                                        <td>{{$associate->bank_name}}</td>
                                                        <td>{{$associate->bank_address}}</td>
                                                        <td>{{$associate->swift_code}}</td>
                                                        <td>{{$associate->address_code}}</td>

                                                        <td>{{$associate->routing_bank_number}}</td>
                                                        <td>{{$associate->routig_number_directs_deposite}}</td>
                                                        <td>{{$associate->intermediary_bank}}</td>
                                                        <td>{{$associate->intermediary_swift_code}}</td>

                                                        <td>{{$associate->hsn_code_natural}}</td>
                                                        <td>{{$associate->hsn_code_natural_one}}</td>
                                                        <td>{{$associate->hsn_code_lab}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
