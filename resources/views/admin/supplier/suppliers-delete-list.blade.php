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
						<div class="row gy-5 g-xl-8">
							<div class="col-xl-12">
								@if(Session::has('success'))
								<div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
									{{ session()->get('success') }}
								</div>
								@endif
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


								@if ($errors->any())
									<div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
										@foreach ($errors->all() as $error)
											{{ $error }}
										@endforeach
									</div>
								@endif
								<div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Supplier Delete List</span>
											</h3>
										</div>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
															<th class="w-10px pe-2 sorting_disabled"></th>
															<th>Actions</th>
                                                            <th>ID</th>
															<th>Supplier</th>
															<th>Email</th>
															<th>Type</th>
															<th>Mobile</th>
															<th>City</th>
															<th>Country</th>
															<th>Created At</th>
                                                            <th>Action</th>
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold" id="render_string">
														@if(!empty($suppliers))
															@foreach($suppliers as $supplier)
															<tr>
																<td><i class="fa fa-plus" data-id="" data-name=""></i></td>
																<td>
																	<a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
																		<span class="svg-icon svg-icon-5 m-0">
																			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																				<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
																			</svg>
																		</span>
																	</a>
																	<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
																		<div class="menu-item px-3">
																			<a href="#" class="menu-link px-3">Edit</a>
																		</div>
                                                                        <div class="menu-item px-3">
																			<a href="{{url('activate-supplier-account')}}/{{$supplier->sup_id}}" class="menu-link px-3">Active </a>
																		</div>
                                                                        <div class="menu-item px-3">
																			<a href="{{url('move-supplier-pending-list')}}/{{$supplier->sup_id}}" class="menu-link px-3">Move To Pending</a>
																		</div>

																	</div>
																</td>
                                                                <td>
																	<div class="d-flex flex-column">
																		<span>{{$supplier->sup_id}}</span>
																	</div>
																</td>
																<td>
																	<div class="d-flex flex-column">
																		<a class="text-gray-800 text-hover-primary mb-1">{{ $supplier->users->companyname }}</a>
																		<span>{{ $supplier->users->firstname }} {{ $supplier->users->lastname }}</span>
																	</div>
																</td>
                                                                <td>{{ $supplier->users->email }}{!! ($supplier->users->email_verified_at != null) ? '<i class="fa fa-check" aria-hidden="true" style="color: green;"></i>' : ''; !!}</td>
																<td>{{ $supplier->diamond_type }}</td>
																<td>{{ $supplier->users->mobile }}</td>
																<td>{{ $supplier->city }}</td>
																<td>{{ $supplier->country }}</td>
																<td>{{ $supplier->users->created_at }}</td>
                                                                <td>{{ $supplier->users->updated_at }}</td>
															</tr>
															@endforeach
														@else
															<tr>
																<td colspan="100%"> No record Found</td>
															</tr>
														@endif
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
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data: mydata,
				});
			}

            $('#render_string').delegate('.fa-plus', 'click', function() {
                $(".detail_view").each(function(e) {
                    $(this).remove();
                });

                $(".fa-minus").each(function(e) {
                    $(this).removeClass("fa-minus").addClass("fa-plus");
                });

                $(this).removeClass("fa-plus").addClass("fa-minus");

                var parent_tr = $(this).parents('tr');
                var id = $(this).data('id');
                blockUI.block();
                request_call("{{ url('supplier-upload-report')}}", "id="+id);
                xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        blockUI.release();
                        parent_tr.after("<tr class='detail_view'> <td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('#render_string').delegate('.fa-minus', 'click', function() {
                $(this).removeClass("fa-minus").addClass("fa-plus");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
            });
        });
	</script>
</body>
</html>