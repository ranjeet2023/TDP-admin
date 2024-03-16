<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>{{config('app.name')}}</title>
	<meta charset="utf-8" />
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
						<div class="row gy-5 g-xl-8">
							<div class="col-xl-12">
								@if(Session::has('success'))
								<div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
									{{ session()->get('success') }}
								</div>
								@endif

								@if ($errors->any())
									<div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
										@foreach ($errors->all() as $error)
											{{ $error }}
										@endforeach
									</div>
								@endif
								<div class="card">
									<div class="card-header border-0">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder text-dark">Invalid Diamond</span>
                                            <span class="text-muted fw-bold fs-7">{{ $supplier->companyname }} - {{ count($diamonds) }}</span>
                                        </h3>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-7 gy-5" id="kt_table_users">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                            <th>View</th>
															<th>Reason</th>
															<th>Shape</th>
															<th>Carat</th>
															<th>Color</th>
															<th>Clarity</th>
															<th>Cut</th>
															<th>polish</th>
															<th>symmetry</th>
															<th>fluorescence</th>
															<th>Lab</th>
															<th>Certi No</th>
															<th>Table %</th>
															<th>Depth %</th>
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold">
														@if(!empty($diamonds))
															@foreach($diamonds as $diamond)
															<tr>
                                                            <td><i class="fa fa-eye diamond_detail" id="' . $value->certificate_no .'"></i></td>
																<td class="text-danger fs-8">{{ $diamond->reason }}</td>
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
				@include('admin/footer')
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
	<script>
		var hostUrl = "assets/";
	</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->

	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
	<!--end::Global Javascript Bundle-->

	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->

	<script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");

		$('#kt_table_users').DataTable({
            'processing': true,
            "pageLength": 50
		});
	</script>





</body>
</html>
