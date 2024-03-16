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

								@if ($errors->any())
									<div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
										@foreach ($errors->all() as $error)
											{{ $error }}
										@endforeach
									</div>
								@endif
								<div class="card">
									<div class="card-header border-0 pt-6">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Expired Reports List</span>
											</h3>
                                        <div class="card-toolbar">
                                            <form>
                                                <div class="row">
                                                    <div class="col-lg-4 mb-lg-0">
                                                        <label>Type:</label>
                                                        <select class="form-select form-select-sm datatable-input" data-col-index="4">
                                                            <option value="" selected>Select type</option>
                                                            <option value="Natural">Natural</option>
                                                            <option value="Lab Grown">Lab Grown</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 mb-lg-0">
                                                        <label>Mode:</label>
                                                        <select class="form-select form-select-sm datatable-input" data-col-index="8">
                                                            <option value="" selected>Select Mode</option>
                                                            <option value="File">File</option>
                                                            <option value="FTP">FTP</option>
                                                            <option value="API">API</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label></label>
                                                        <button class="form-control btn btn-primary btn-primary--icon" id="kt_search">
                                                            <span>
                                                                <i class="la la-search"></i>
                                                            </span>
                                                        </button>&#160;&#160;
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label></label>
                                                        <button class="form-control btn btn-secondary btn-secondary--icon" id="kt_reset">
                                                            <span>
                                                                <i class="la la-close"></i>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
										</div>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
															<th class="w-10px pe-2 sorting_disabled"></th>
															<th class="min-w-105px">ID</th>
															<th class="min-w-105px">Supplier ID</th>
															<th class="min-w-105px">Supplier Name</th>
                                                            <th class="min-w-105px">Diamond Type</th>
															<th class="min-w-105px">Total Number Of Stones</th>
															<th class="min-w-105px">Valid Diamonds</th>
															<th class="min-w-105px">Invalid Diamonds</th>
															<th class="min-w-80px">Uploaded Mode</th>
                                                            <th class="min-w-80px">Created At</th>
                                                            <th class="min-w-105px">File Name</th>
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold">
                                                        @foreach($suppliers as $user)
															<tr>
																<td><i class="fa fa-plus" data-id="171" data-name="{{ $user->info }}"></i></td>

																<td>{{ $user->id }}</td>
																<td>
																	{{ $user->supplier_id }}
																</td>
																<td><span class="text-gray-800 text-hover-primary mb-1">{{ $user->supplier->supplier_name }}</span></td>
																<td>{{ $user->supplier->diamond_type }}</td>
                                                                <td>{{ $user->no_of_stone }}</td>
																<td>{{ $user->valid_diamond }}</td>
																<td>{{ $user->invalid_diamond }}</td>
																<td>{{ $user->upload_mode }}</td>
																<td>{{ $user->created_at }}</td>
                                                                <td>
																	<div class="d-flex flex-column">
																		<a class="text-gray-800 text-hover-primary mb-1">{{ $user->info }}</a>
																	</div>
																</td>
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
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data: mydata,
				});
			}

            var target = document.querySelector("#kt_table_users_wrapper");
            var blockUI = new KTBlockUI(target, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            var table = $('#kt_table_users').DataTable({
                'processing': true,
                "pageLength": 100
            });

            $('#kt_table_users_zero').DataTable({
                'processing': true,
                "pageLength": 100
            });

			$('#kt_search').on('click', function(e) {
				e.preventDefault();
				var params = {};
				$('.datatable-input').each(function() {
					var i = $(this).data('col-index');
					if (params[i]) {
						params[i] += '|' + $(this).val();
					} else {
						params[i] = $(this).val();
                    }
			});

				$.each(params, function(i, val) {
					// apply search params to datatable
					table.column(i).search(val ? "^" + val : '', true, false);
					console.log(table.column(i).header().innerHTML)
					console.log(params)
					console.log(val)
				});
			});
		});
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
