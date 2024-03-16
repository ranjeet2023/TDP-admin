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
                                    <span class="card-label fw-bolder text-dark">Login History</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-success" onClick="window.location.href='login-history-customer'">Clear</button>
                                    <a href="{{ url('/login-history-total')}}"><button type="button" class="btn btn-primary ml-1" >Total Login</button></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                                <thead>
                                                   <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th class="column-title">Company Name</th>
                                                        <th class="column-title">Date</th>
                                                        <th class="column-title">Ip Address</th>
                                                        <th class="column-title">City</th>
                                                        <th class="column-title">Country</th>
                                                        <th class="column-title">User Type</th>
                                                        <th class="column-title">Browser</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">
                                                    @if (!empty($loginHistoryData))
														@foreach ($loginHistoryData as $value)
															<tr>
																<td><a href="{{ route('admin.log.loginhistorycustomer',array( 'id' => $value->user->id)) }}">{{ $value->user->companyname }}</a></td>
																<td>{{ $value->lastlogin }}</td>
																<td>{{ $value->ip }}</td>
																<td>{{ $value->city }}</td>
																<td>{{ $value->country }}</td>
                                                                <td>
                                                                    @if($value->user->user_type == 1)
                                                                        {{ "Admin" }}
                                                                    @elseif($value->user->user_type == 2)
                                                                        {{ "Customer" }}
                                                                        @elseif($value->user->user_type == 3)
                                                                        {{ "Supplier" }}
                                                                    @endif
                                                                </td>
																<td>{{ $value->browser }}</td>
															</tr>
                                                        @endforeach
													@else
														<tr><td colspan="100%">No Record Found!!</td></tr>
													@endif
                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-center">
                                                {!! $loginHistoryData->links() !!}
                                            </div>
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

            $('#kt_table_users').DataTable({
                'ordering':false,
                'processing': true,
                "pageLength": 100
            });
        });
    </script>
</body>
</html>
