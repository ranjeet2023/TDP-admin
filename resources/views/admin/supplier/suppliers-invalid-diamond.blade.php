<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->
<head>
	<title>{{config('app.name')}}</title>
	<meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

	<meta name="description" content="{{config('app.website')}}" />
	<meta name="keywords" content="{{config('app.website')}}" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="{{config('app.website')}}" />
	<meta property="og:url" content="https://{{config('app.website')}}" />
	<meta property="og:site_name" content="{{config('app.website')}}" />
	<link rel="canonical" href="https://{{config('app.website')}}/dashboard" />
	<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" />

	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->

	<!--begin::Global Stylesheets Bundle(used by all pages)-->
	<link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('assets/admin/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	<!--end::Global Stylesheets Bundle-->

	{{-- @include('customer/common') --}}
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"   class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<!--begin::Main-->
	<!--begin::Root-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="page  flex-row flex-column-fluid">
			@include('admin/sidebar')
			<!--begin::Wrapper-->
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				@include('admin/header')
				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<!--begin::Post-->
					<div class="post d-flex flex-column-fluid" id="kt_post">
						<!--begin::Container-->
						<div id="kt_content_container" class="container-xxl">
							<!--begin::Row-->
							<div class="row gy-5 g-xl-8">

								<!--begin::Col-->
								<div class="col-xl-12">
                                <div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Invalid Diamond</span>
                                                <span class="text-muted fw-bold fs-7">{{ !empty($supplier) ? $supplier->users->companyname : 'bcv'; }}</span>
											</h3>
										</div>
									</div>
									<div class="card-body py-4">

                                    <div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">

												<table class="table align-middle table-row-dashed fs-7 gy-5" id="kt_table_users">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                            <th class="">View</th>
                                                            <th class="">Reason</th>

															<th class="">Shape</th>
															<th class="">Carat</th>
															<th class="">Color</th>
															<th class="">Clarity</th>
															<th class="">Cut</th>
															<th class="">polish</th>
															<th class="">symmetry</th>
															<th class="">fluorescence</th>
															<th class="">Lab</th>
															<th class="">Certi No</th>
															<th class="">Table %</th>
															<th class="">Depth %</th>
														</tr>
													</thead>

													<tbody class="text-gray-600 fw-bold">
														@if(!empty($diamonds))
															@foreach($diamonds as $diamond)
															<tr>
                                                            <td><button class="btn btn-primary p-2 diamond_detail" id="{{ $diamond->certificate_no}}">View</button></td>
																<td class="text-danger fs-8">{{$diamond->reason}}</td>

																<td>{{ $diamond->shape }}</td>
																<td>{{ $diamond->carat }}</td>
																<td>{{ $diamond->color }}</td>
																<td>{{ $diamond->clarity }}</td>
																<td>{{ $diamond->cut }}</td>
																<td>{{ $diamond->polish }}</td>
																<td>{{ $diamond->symmetry }}</td>
																<td>{{ $diamond->fluorescence }}</td>
																<td>{{ $diamond->lab }}</td>
																<td>{{ $diamond->certificate_no }}</td>
																<td>{{ $diamond->table_per }}</td>
																<td>{{ $diamond->depth_per }}</td>
															</tr>
															@endforeach
														@else
															<tr>
																<td colspan="100%"> No record Found</td>
															</tr>
														@endif
													</tbody>
												</table>

											</div>
										</div>
									</div>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				{{-- @include('supplier/sup_footer') --}}
			</div>
		</div>
	</div>

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
    <div class="modal fade" id="header-modal" aria-hidden="true"></div>
	<script>
		var hostUrl = "assets/";
	</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->
	<script type="text/javascript">
	$(document).ready(function(){

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

        $('#kt_table_users_wrapper').delegate('.diamond_detail', 'click', function() {
            var loatno = this.id;
            blockUI.block();
            request_call("{{url('invalid-diamond')}}","certificate_no=" + $.trim(loatno)+"&diamond_type=L");
            xhr.done(function(mydata) {
                blockUI.release();
                $("#header-modal").html(mydata.success);
                $('#header-modal').modal('show');
            });
        });
	});
	</script>
	<!--end::Javascript-->
</body>
</html>
