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
                                            <span class="card-label fw-bolder text-dark">Supplier Requests</span>
                                            <span class="text-muted fw-bold fs-7">{{ $customer->companyname}}</span>
                                        </h3>
										<form style="width:70%">
											<h3>
												<div class="row">
													<div class="col-md-3" style="width:20% !important"><label>Diamond Type:</label></div>
													<div class="col-md-5">
														<select name="type" id="type" class="form-control datatable-input" data-col-index="3">
															<option value="">Select Diamond Type</option>
															<option value="Natural">Natural</option>
															<option value="Lab Grown">Lab Grown</option>
														</select>
													</div>
													<div class="col-md-4">
														<button class="form-control btn btn-primary" id="kt_search" style="width:40%">
															<span>
																<i class="la la-search"></i>
																<span>Search</span>
															</span>
														</button>&#160;&#160;
														<button class="form-control btn btn-secondary" id="kt_reset" style="width:40%">
															<span>
																<i class="la la-close"></i>
																<span>Reset</span>
															</span>
														</button>
													</div>
												</div>
											</h3>
										</form>
                                        <div class="card-toolbar">
                                            @if (Auth::user()->user_type == 1)
                                            <input type="hidden" name='customer_id' id="customer_id" value="{{ $customer->id }}">
											<button class="btn btn-sm btn-primary me-2 turn_on_all">Turn on all</button>
											<button class="btn btn-sm btn-danger me-2 turn_off_all">Turn off all</button>
											@endif
										</div>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
															<th class="w-10px pe-2 sorting_disabled"></th>
															<th>id</th>
															<th>Supplier Name</th>
															<th>Type</th>
															<th>Status</th>
															<th>Action</th>
															<th>Created Date</th>
															<th>Updated Date</th>
															<th>Reason</th>
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold">
														@if(!empty($suppliers))
															@foreach($suppliers as $value)
															<tr>
																{{-- @dd($value); --}}
                                                                <td></td>
																<td>{{ $value->sup_id }}</td>
                                                                <td>{{ $value->users->companyname }}</td>
                                                                <td>{{ $value->diamond_type }}</td>
                                                                <td><span id='status-{{ $value->id }}' class="statuschange">{{ $value->request_status }}</span></td>
                                                                <td>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <input class="form-check-input turnonoff" type="checkbox" value="" name="turnonoff" id="flexSwitchDefault" {{ ($value->request_status == 1)  ? 'checked="checked"' : '' }} data-id="{{ $value->id }}" data-sup_id="{{ $value->sup_id }}" data-user_id="{{ $value->user_id }}"/>
                                                                    </div>
                                                                </td>
																<td>{{ $value->created_at }}</td>
                                                                <td>{{ $value->updated_at }}</td>
																<td>{{ $value->cancel_reason }}</td>
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

			var table = $('#kt_table_users').DataTable({
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
					table.column(i).search(val ? "^" + val : '', true, false);
				});
				table.table().draw();
			});



            $(".turn_on_all").click(function(event) {
                let id = $("#customer_id").val();
                blockUI.block();
                request_call("{{ url('customer-supplier-request-trun-all')}}", "customer_id="+id);
                xhr.done(function(mydata) {
                    blockUI.release();
                    $(".statuschange").html('1');
                    var ele=document.getElementsByName('turnonoff');
                    for(var i=0; i<ele.length; i++){
                        if(ele[i].type=='checkbox'){
                            ele[i].checked=true;
                        }
                    }
                    Swal.fire({title: "Success", text: 'Turn All.', type: "success"});
                });
            });

            $(".turn_off_all").click(function(event) {
                let id = $("#customer_id").val();
                blockUI.block();
                request_call("{{ url('customer-supplier-request-trun-off')}}", "customer_id="+id);
                xhr.done(function(mydata) {
                    blockUI.release();
                    $(".statuschange").html('0');
                    var ele=document.getElementsByName('turnonoff');
                    for(var i=0; i<ele.length; i++){
                        if(ele[i].type=='checkbox'){
                            ele[i].checked=false;
                        }
                    }
                    Swal.fire({title: "Success", text: 'Turn off.', type: "success"});
                });
            });

            $('.table').delegate('.form-check-input', 'click', function () {
                let id = $(this).data('id');
                let customer_id = $(this).data('user_id');
                let supplier_id = $(this).data('sup_id');
                var status = $(this).prop('checked') == true ? 1 : 0;

                blockUI.block();
                request_call("{{ url('customer-supplier-request-trun')}}", "id=" + $.trim(id) + "&customer_id="+ customer_id + "&supplier_id="+supplier_id+ "&status="+status);
                xhr.done(function (mydata) {
                    blockUI.release();
                    $("#status-"+id).html(status);
                    Swal.fire({title: "Success", text: 'Customer Account Approved.', type: "success"});
                });
            });
        });
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>

