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
										<div class="card-title">
                                            <h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder text-dark">Hold List</span>
												<span class="text-muted fw-bold fs-7">{{ !empty($customer) ? $customer->companyname : ''; }}</span>
											</h3>
										</div>
										<div class="card-toolbar">
                                            <input type="hidden" value="{{ $customer->id }}" id="customer_id" />
											<a href="{{ url('enquiry-list-detail')}}/{{ $customer->id }}" class="btn btn-sm btn-primary me-2"><i class="fa fa-arrow-left"></i> Back</a>
											<button class="btn btn-primary btn-sm me-2 total_record" title="Total Diamond" data-placement="top" data-toggle="tooltip" data-original-title="Total Stone">Total Stone = <span id="total_stone_record">0</span></button>

											<button class="btn btn-sm btn-secondary me-2"><span id="total_pcs">0</span></button>
											<button class="btn btn-sm btn-secondary me-2">CT : <span id="totalcarat">0.00</span></button>
											<button class="btn btn-sm btn-secondary me-2">$/ct $<span id="totalpercarat">0.00</span></button>
											<button class="btn btn-sm btn-secondary me-2">$<span id="totalamount">0.00</span></button>
											@if (Auth::user()->user_type == 1)
											<button class="btn btn-sm btn-secondary me-2">$/ct A $<span id="totalApercarat">0</button>
											<button class="btn btn-sm btn-secondary me-2">Price A $<span id="totalAamount">0</button>
											@endif
										</div>
									</div>
                                    <div class="card-header border-0">
                                        <div class="card-title"></div>
										<div class="card-toolbar">
                                            <a class="btn btn-sm btn-danger me-2 qc_request">QC Request</span></a>
                                            <a class="btn btn-sm btn-danger me-2 release_diamond">Release</span></a>
											@if (Auth::user()->user_type == 1)
												<button class="btn btn-sm btn-danger me-2 reverse_diamond">
													<span><i class="fa fa-trash"></i> Reverse</span>
												</button>
												<a href="{{ url('admin-release-list/'.$customer->id) }}" class="btn btn-sm btn-primary me-2" style="float: right;">Release List</a>
											@endif
                                        </div>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-7 gy-2" id="kt_table_users">
													<thead>
														<tr class="fw-bolder fs-6 text-gray-800 px-7">
															<th><input class="check_box check_all" name="multiaction" type="checkbox"></th>
															<th>Action</th>
															<th>Date</th>
															@if (Auth::user()->user_type == 1)
																<th>Supplier Name</th>
															@endif
															<th>S Status</th>
															<th>Shape</th>
															<th>SKU</th>
															<th>Ref No</th>
															<th>Carat</th>
															<th>Color</th>
															<th>Clarity</th>
															<th>Cut</th>
															<th>Polish</th>
															<th>Symm</th>
                                                            <th>Flour</th>
															<th>Lab</th>
															<th>Certificate</th>
															<th>Sell %</th>
															<th>Sell Price</th>
															@if (Auth::user()->user_type == 1)
															<th>Buy %</th>
															<th>Buy Price</th>
															@endif
                                                            <th>Country</th>
														</tr>
													</thead>
													<tbody id="render_string">
														@if(!empty($holddata))
															@foreach($holddata as $value)
															<tr>
																<td>
																	<div class="position-relative ps-6 pe-3 py-2">
																	<label class="checkbox  justify-content-center">
																		<input class="check_box" data-orders_id="{{ $value->orders_id }}" data-customer_id="{{ $value->customer_id }}"
																				data-ref_no='{{ $value->ref_no }}'
																				data-certi_no='{{ $value->certificate_no }}'
                                                                                data-pickups = '{{ ($value->pickups != null) ? 1 : 0 }}'
																				data-carat='{{ optional($value->orderdetail)->carat }}'  data-price='{{ $value->sale_price }}' data-discount="{{ $value->sale_discount }}"  data-aprice='{{ $value->buy_price }}' name="multiaction" value="{{ $value->orders_id }}" type="checkbox">
																			<span></span>
																	</label>
                                                                    <i class="fa fa-plus" data-id="{{ $value->orders_id }}" data-customer_id="{{ $value->customer_id }}"></i>
																	@if($value->hold_status == "PENDING")
																		<div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
																		<span class="mb-1 text-info fw-bolder">Pending</span>
																	@elseif($value->hold_status == "APPROVED")
																		<div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-success"></div>
																		<span class="mb-1 text-success fw-bolder">Approved</span>
																	@elseif($value->hold_status == "REJECT")
																		<div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-danger"></div>
																		<span class="mb-1 text-danger fw-bolder">Rejected</span>
																	@endif
                                                                    <br/>
                                                                    @if ($value->pickups != null)
                                                                        @if($value->pickups->status == "PENDING")
                                                                            <span class="mb-1 text-success fw-bolder">Requested For QC</span>
                                                                        @elseif($value->pickups->status == "PICKUP_DONE" && $value->pickups->export_number == "" && $value->qc_comment == "")
                                                                            <span class="mb-1 text-success fw-bolder">On Hand</span>
                                                                        @elseif($value->pickups->status == "PICKUP_DONE" && $value->pickups->export_number == "" && $value->qc_comment != "")
                                                                            <span class="mb-1 text-success fw-bolder">Done QC</span>
                                                                        @elseif($value->pickups->status == "QCRETURN")
                                                                            <span class="mb-1 text-success fw-bolder">QC Done & Return </span>
                                                                        @elseif($value->pickups->status == "PICKUP_DONE" && $value->pickups->export_number != "")
                                                                            <span class="mb-1 text-success fw-bolder">In Transit</span>
                                                                        @endif
                                                                    @endif
																	</div>
																</td>
																<td nowrap>
																	@if($value->hold_status == "PENDING")
																	<button class="btn btn-sm btn-icon btn-primary me-1 hold_a_r" data-id="{{ $value->orders_id }}" data-userid="{{ $value->customer_id }}" data-certino="{{ $value->certificate_no }}" data-status="APPROVED"><i class="fa fa-check"></i></button>
																	<button class="btn btn-sm btn-icon btn-danger hold_a_r" data-id="{{ $value->orders_id }}" data-userid="{{ $value->customer_id }}" data-certino="{{ $value->certificate_no }}" data-status="REJECT"><i class="fa fa-times"></i></button></td>
																	@endif
																</td>
																<td>{{ optional($value->orderdetail)->created_at }}</td>
																@if (Auth::user()->user_type == 1)
																<td>{{ optional($value->orderdetail)->supplier_name }}
                                                                    <a href="https://wa.me/?text={{ optional($value->orderdetail)->shape }} {{ $value->ref_no }} {{ optional($value->orderdetail)->carat }} {{ optional($value->orderdetail)->color }} {{ optional($value->orderdetail)->clarity }} {{ optional($value->orderdetail)->cut }} {{ optional($value->orderdetail)->polish }} {{ optional($value->orderdetail)->symmetry }} {{ optional($value->orderdetail)->fluorescence }} {{ optional($value->orderdetail)->lab }} {{ $value->certificate_no }}" target="_blank">
                                                                        <svg width="24" height="24" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg">
                                                                            <g opacity="0.2">
                                                                                <path d="M128.00049,32A96.02264,96.02264,0,0,0,45.4292,176.99807l.00049-.00061-9.47315,33.15661a8,8,0,0,0,9.89014,9.8899l33.15625-9.47327v.001A96.00624,96.00624,0,1,0,128.00049,32ZM152.11377,183.9999A80.0001,80.0001,0,0,1,72,103.88625,27.97634,27.97634,0,0,1,100,76h0a6.89208,6.89208,0,0,1,5.98438,3.4729l11.6914,20.45923a8.00129,8.00129,0,0,1-.08594,8.08521l-9.38916,15.64843h0a48.18271,48.18271,0,0,0,24.1333,24.13379l.00049-.00012,15.64795-9.389a8.00033,8.00033,0,0,1,8.08545-.08594l20.459,11.69092A6.89223,6.89223,0,0,1,180,156,28.081,28.081,0,0,1,152.11377,183.9999Z"/>
                                                                            </g>
                                                                            <path d="M128.00049,24a104.0281,104.0281,0,0,0-91.189,154.041l-8.54687,29.915A15.99944,15.99944,0,0,0,48.044,227.73635l29.916-8.54688A104.00728,104.00728,0,1,0,128.00049,24Zm0,192a87.86347,87.86347,0,0,1-44.90772-12.30566,8.00324,8.00324,0,0,0-6.28759-.81641l-33.15674,9.47363,9.47265-33.15625a7.99679,7.99679,0,0,0-.8164-6.28613A88.01132,88.01132,0,1,1,128.00049,216Zm52.4956-72.93066L160.03662,131.378a16.01881,16.01881,0,0,0-16.17041.17285l-11.85107,7.11133a40.03607,40.03607,0,0,1-14.67627-14.67676l7.11084-11.85156a16.01674,16.01674,0,0,0,.17187-16.16992L112.93066,75.503A14.92643,14.92643,0,0,0,100,68a36.01385,36.01385,0,0,0-36,35.876A87.99949,87.99949,0,0,0,151.999,192c.042,0,.09473.02344.126,0A36.01427,36.01427,0,0,0,188,156,14.9238,14.9238,0,0,0,180.49609,143.06936ZM152.10254,176H152a72.00036,72.00036,0,0,1-72-72.10254A19.99027,19.99027,0,0,1,99.36328,84.00979l11.36621,19.8916-9.38867,15.64844a7.99972,7.99972,0,0,0-.43652,7.39746,56.03179,56.03179,0,0,0,28.14892,28.14843,7.99583,7.99583,0,0,0,7.397-.43652l15.64843-9.38867,19.8916,11.36621A19.99027,19.99027,0,0,1,152.10254,176Z"/>
                                                                        </svg>
                                                                    </a>
                                                                </td>
																@endif
																<td>
                                                                    @if($value->supplier_status == "PENDING")
																		<span class="mb-1 text-info fw-bolder">Pending</span>
																	@elseif($value->supplier_status == "APPROVED")
																		<span class="mb-1 text-success fw-bolder">Approved</span>
																	@elseif($value->supplier_status == "REJECT")
																		<span class="mb-1 text-danger fw-bolder">Rejected</span>
																	@endif
                                                                </td>
																<td>{{ optional($value->orderdetail)->shape }}</td>
																<td>{{ optional($value->orderdetail)->id }}</td>
																<td>{{ $value->ref_no }}</td>
																<td>{{ optional($value->orderdetail)->carat }}</td>
																<td>{{ optional($value->orderdetail)->color }}</td>
																<td>{{ optional($value->orderdetail)->clarity }}</td>
																<td>{{ optional($value->orderdetail)->cut }}</td>
																<td>{{ optional($value->orderdetail)->polish }}</td>
																<td>{{ optional($value->orderdetail)->symmetry }}</td>
                                                                <td>{{ optional($value->orderdetail)->fluorescence }}</td>
																<td>{{ optional($value->orderdetail)->lab }}</td>
																<td>{{ $value->certificate_no }}</td>
																<td>{{ $value->sale_discount }}</td>
																<td>{{ $value->sale_price }}</td>
																@if (Auth::user()->user_type == 1)
																<td>{{ $value->buy_discount }}</td>
																<td>{{ $value->buy_price }}</td>
                                                                <td>{{ optional($value->orderdetail)->country }}</td>
																@endif
															</tr>
															@endforeach
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
	<div class="modal fade" id="header-modal" aria-hidden="true"></div>

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

            var totalstone = 0;
			var TotalCarat = 0;
			var TotalPerCarat = 0;
			var TotalPrice = 0;
			var TotalAPerCarat = 0;
			var TotalAPrice = 0;

            $('.table').delegate('.check_all', 'change', function() {
                if ($(this).prop('checked') == true) {
                    $('.check_box').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.check_box').each(function() {
                        this.checked = false;
                    });
                }
                if ($('#render_string .check_box:checked').length == $('#render_string .check_box').length) {
                    $('.check_all').each(function() {
                        this.checked = true;
                    });
                }

                var stone = 0;
				var Carat = 0;
				var PerCarat = 0;
				var Price = 0;
				var APerCarat = 0;
				var APrice = 0;
                $('#render_string .check_box:checked').each(function() {
					stone += 1;
					Carat += parseFloat($(this).data('carat'));
					Price += parseFloat($(this).data('price'));
					APrice += parseFloat($(this).data('aprice'));
					PerCarat = Price / Carat;
					APerCarat = APrice / Carat;
				});

				$('#total_pcs').text(stone);
				$('#totalcarat').html(Carat.toFixed(2));
				$('#totalpercarat').html(PerCarat.toFixed(2));
				$('#totalamount').html(Price.toFixed(2));
				@if (Auth::user()->user_type == 1)
					$('#totalApercarat').html(APerCarat.toFixed(2));
					$('#totalAamount').html(APrice.toFixed(2));
				@endif
			});

			$('#render_string').delegate('.check_box', 'change', function() {
				var stone = 0;
				var Carat = 0;
				var PerCarat = 0;
				var Price = 0;
				var APerCarat = 0;
				var APrice = 0;
				$('#render_string .check_box:checked').each(function() {
					stone += 1;
					Carat += parseFloat($(this).data('carat'));
					Price += parseFloat($(this).data('price'));
					APrice += parseFloat($(this).data('aprice'));
					PerCarat = Price / Carat;
					APerCarat = APrice / Carat;
				});

				$('#total_pcs').text(stone);
				$('#totalcarat').html(Carat.toFixed(2));
				$('#totalpercarat').html(PerCarat.toFixed(2));
				$('#totalamount').html(Price.toFixed(2));
				@if (Auth::user()->user_type == 1)
					$('#totalApercarat').html(APerCarat.toFixed(2));
					$('#totalAamount').html(APrice.toFixed(2));
				@endif

				totalstone = stone;
				TotalCarat = Carat.toFixed(2);
				TotalPerCarat = PerCarat.toFixed(2);
				TotalPrice = Price.toFixed(2);
				TotalAPerCarat = APerCarat.toFixed(2);
				TotalAPrice = APrice.toFixed(2);
			});

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
                request_call("{{ url('admin-view-order-detail')}}", "id="+id);
                xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('#render_string').delegate('.fa-minus', 'click', function() {
                $(this).removeClass("fa-minus").addClass("fa-plus");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
            });

            $('.card-header').delegate('.qc_request', 'click', function() {
				var id = [];
				var certi_no = [];
				var customer_id = '';
				var checkpickups = 0;
				$(":checkbox:checked").each(function() {
					certi_no.push($(this).attr('data-certi_no'));
					id.push($(this).val());
					customer_id = $(this).attr('data-customer_id');
                    if($(this).data('pickups') == 1){
                        checkpickups += 1;
                    }
				});
                console.log(checkpickups);
				if (checkpickups != 0) {
					Swal.fire("Warning!", "QC Request Already Done From The Seleced Stone ..!!", "warning");
				}else if (id == "" && certi_no == "") {
					Swal.fire("Warning!", "Please Select at least one record.", "warning");
				} else {
                    blockUI.block();
					request_call("{{ url('admin-confirm-to-supplier') }}", "certi_no=" + certi_no + "&orders_id=" + id + "&customer_id=" + customer_id);
					xhr.done(function(mydataorder) {
                        blockUI.release();
						if (mydataorder.error == false) {
							Swal.fire("Warning!", "diamond already Requested for QC.", "warning");
						} else {
							$("#header-modal").html("<div class='modal-dialog modal-dialog-centered modal-fullscreen' role='document'>"
								+ "<div class='modal-content'>"
									+ "<div class='modal-header'>"
										+ "<h4 class='modal-title'>Are you sure you want to send diamond to qc?</h4>"
										+ "<div class='card-toolbar'><a id='save_confirm_data' class='btn btn-sm btn-success me-2'>Confirm</a>"
										+ "<button class='btn btn-sm btn-danger' data-bs-dismiss='modal'>Cancel</button></div>"
										+ "<div class='btn btn-icon btn-sm btn-active-light-primary ms-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>"
									+ "</div>" +
									"<div class='modal-body'>" +
										"<div class='row grid-block' style='margin:0px;'>" +
										"<div id='discont_message_popup'></div>" +
										"<table class='table center table-striped table-bordered bulk_action'>" +
										"<tr><td>Total Stone : <b>" + mydataorder.totalstone + "</b> | Total Carat : <b>" + mydataorder.TotalCarat + "</b> | Total A Per Carat : <b>" + mydataorder.TotalAPerCarat + "</b> | Total A Price : <b>" + mydataorder.TotalAPrice + "</b></td></tr>" +
										"</table>" +
										"" + mydataorder.render_msg + "" +
									"</div>" +
								"</div>" +
								"</div>"
							);
							$('#header-modal').modal('show');
							$('.date-picker').daterangepicker({
								singleDatePicker: true,
								parentEl: "#header-modal .modal-body",
								autoApply: true,
							});
						}
					});
				}
			});

            $('#header-modal').delegate('#save_confirm_data', 'click', function(event) {
				var flag = true;
				$(".city").removeClass("border-danger");
				$(".city").each(function() {
					if ($(this).val() == "") {
						$(this).addClass("border-danger");
						flag = false;
					}
				});
				if (flag == true) {
					var array_value = [];
					$('.pickup_row').each(function() {
                        twizzer_video = 0;
                        if($("#twizzer_video").is(':checked')){
                            twizzer_video = 1;
                        }
						array_value.push({
							dateval: $(this).find(".pickup_date").val(),
							id: $(this).find(".pickup_date").attr('id'),
							city: $(this).find(".city").val(),
                            twizzer_video : twizzer_video,
						});
					});
					var temp_data = JSON.stringify(array_value);
                    $('#header-modal').modal('hide');
                    blockUI.block();
					request_call("{{ url('admin-confirmToSupplier') }}", "data=" + temp_data);
					xhr.done(function(mydata) {
                        blockUI.release();
						if (mydata.success) {
							Swal.fire("Success!", "Confirm to supplier successfully.", "success");
							$('.check_box:checked').each(function() {
								$(this).parents("tr").addClass("text-success");
								this.checked = false;
							});
							selected_ids = "";
							total_selected = totalstone = 0;
							$('#total_pcs').text(0);
							$('#totalcarat').html(0);
							$('#totalrap').html(0);
							$('#totaldiscount').html(0);
							$('#totalpercarat').html(0);
							$('#totalamount').html(0);
							$('#totalApercarat').html(0);
							$('#totalAamount').html(0);
						}
						else
                        {
							Swal.fire("Warning!", "Fail to confirm to supplier.", "warning");
						}
					});
				}
			});

			$('#render_string').delegate('.hold_a_r', 'click', function() {

                var id = $(this).data('id');
				var certino = $(this).attr('data-certino');
				var customer_id = $(this).attr('data-userid');
				var order_status = $(this).attr('data-status');

				if(order_status == 'REJECT'){
					$("#header-modal").html('<div class="modal-dialog modal-lg">'
									+'<div class="modal-content">'
										+'<div class="modal-header">'
											+'<h4 class="modal-title"> Reject Hold?  Order ID : '+id+'</h4>'
											+'<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>'
										+'</div>'
										+'<div class="modal-body">'
											+'<div class="row">'
												+'<div class="col-md-12 col-sm-12 col-xs-12">'
													+'<div class="row " style="width: 100%;">'
														+'<textarea class="form-control comment" rows="3" placeholder="Comments" required name="comment" maxlength="100" id="comment"></textarea>'
														+'<span class="com"></span></br><Span><span class="GFG">100</span> Characters Remaining </span>'
													+'</div>'
												+'</div>'
											+'</div>'
										+'</div>'
										+'<div class="modal-footer justify-content-between">'
											+'<button type="button" class="btn  btn-danger" data-dismiss="modal">Close</button>'
											+'<button type="button" class="btn btn-primary reject_comment_diamond">Yes</button>'
										+'</div>'
									+'</div>'
								+'</div>'
					);
					$('#header-modal').modal('show');

					var max_length = 100;
					$('.comment').keyup(function() {
						$('.com').html('');
						var textlen = $(this).val().length;
						if (textlen = 100) {
						var len = max_length - $(this).val().length;
						$('.GFG').text(len);
						}
					});
					$('#header-modal').delegate('.reject_comment_diamond', 'click', function() {
						$('.com').html('');
						var comment = $("#comment").val();

						if (comment == '') {
							var mssag = 'Please enter comment';
							$('.com').html(mssag);
						}else{
                            $('#header-modal').modal('hide');
                            blockUI.block();
							request_call("{{ url('admin-update-hold-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino + "&comment=" + encodeURIComponent(comment));
							xhr.done(function(mydata) {
                                blockUI.release();
								Swal.fire({
									title: "Rejected",
									text: 'Rejected successfully...!!',
									type: "Warning",
								}).then((result) => {
									location.reload();
								});
							});
						}
					});
				} else if (order_status == "APPROVED") {

					Swal.fire({
						title: 'Are you sure you want to Approve Diamond?',
						text: "You won't be able to revert this!",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Approve it!',
					}).then((result) => {
						if (result.isConfirmed) {
                            blockUI.block();
							request_call("{{ url('admin-update-hold-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino);
							xhr.done(function(mydata) {
                                blockUI.release();
								Swal.fire({
									title: "Approved",
									text: 'Approved successfully...!!',
									type: "success",
								}).then((result) => {
									location.reload();
								});
							});
						}
					});
				}
			});

            $('.reverse_diamond').click(function() {
                var orders_id = [];
                let customer_id = '';
                $("#render_string :checkbox:checked").each(function() {
					orders_id.push($(this).attr('data-orders_id'));
                    customer_id = $(this).attr('data-customer_id');
				});

                Swal.fire({
                    title: 'Are you sure you want to reverse Diamond?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reverse it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{ url('admin-order-reverse')}}", "customer_id=" + customer_id + "&orders_id=" + orders_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Approved",
                                text: 'reverse successfully...!!',
                                type: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            });

            $('.release_diamond').click(function() {
                var orders_id = [];
                let customer_id = '';
                $("#render_string :checkbox:checked").each(function() {
					orders_id.push($(this).attr('data-orders_id'));
                    customer_id = $(this).attr('data-customer_id');
				});

                Swal.fire({
                    title: 'Are you sure you want to release Diamond?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, release it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{ url('admin-order-release')}}", "customer_id=" + customer_id + "&orders_id=" + orders_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Approved",
                                text: 'release successfully...!!',
                                type: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            });
		});
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
