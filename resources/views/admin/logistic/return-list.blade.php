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
                                    <div class="card-header">
                                        <h3 class="card-title" data-toggle="tooltip" data-placement="right" title="Return List">Return List</span></h3>

                                        <div class="card-toolbar">
                                            <button class="btn btn-sm btn-secondary me-2"><span id="total_pcs">0</span></button>
                                            <button class="btn btn-sm btn-secondary me-2">CT : <span id="totalcarat">0.00</span></button>
                                            <button class="btn btn-sm btn-secondary me-2">$/ct $<span id="totalpercarat">0.00</span></button>
                                            <button class="btn btn-sm btn-secondary me-2">$<span id="totalamount">0.00</span></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form>
                                            <div class="row mb-6">
                                                <div class="col-lg-3 mb-lg-0 mb-6">
                                                    <label>Location:</label>
                                                    <select class="form-control datatable-input" data-col-index="2">
                                                        <option value="" selected>Select city</option>
                                                        <option value="Surat">Surat</option>
                                                        <option value="Mumbai">Mumbai</option>
                                                        <option value="Hongkong">Hongkong</option>
                                                        <option value="Direct Ship Hongkong">Direct Ship Hongkong</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>Status:</label>
                                                    <select class="form-control datatable-input" data-col-index="3">
                                                        <option value="">Select Status</option>
                                                        <option value="PENDING">PENDING</option>
                                                        <option value="READY_TO_PICKUP">READY TO PICKUP</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 mb-lg-0 mb-6">
                                                    <label>Supplier:</label>
                                                    <select class="form-control datatable-input" data-col-index="5">
                                                        <option value="" selected>Select Supplier</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label></label>
                                                    <button class="form-control btn btn-primary btn-primary--icon" id="kt_search">
                                                        <span>
                                                            <i class="la la-search"></i>
                                                            <span>Search</span>
                                                        </span>
                                                    </button>&#160;&#160;
                                                </div>
                                                <!-- <div class="col-lg-2">
                                                    <label></label>
                                                    <button class="form-control btn btn-secondary btn-secondary--icon" id="kt_reset">
                                                        <span>
                                                            <i class="la la-close"></i>
                                                            <span>Reset</span>
                                                        </span>
                                                    </button>
                                                </div> -->
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Action</th>
                                                        <th class="text-center">Action</th>
                                                        <th nowrap="nowrap">Supplier</th>
                                                        <th nowrap="nowrap">Status</th>
                                                        <th class="text-center">Location</th>
                                                        <th>Shape</th>
                                                        <th nowrap="nowrap">SKU</th>
                                                        <th nowrap="nowrap">Ref No</th>
                                                        <th>Carat</th>
                                                        <th>Color</th>
                                                        <th>Clarity</th>
                                                        <th>Lab</th>
                                                        <th>Certificate</th>
                                                        <th>Rap</th>
                                                        <th nowrap="nowrap">A Dis(%)</th>
                                                        <th nowrap="nowrap">A $/ct</th>
                                                        <th style="min-width: 103px;">A Price</th>
                                                        <th>Invoice</th>
														<th nowrap="nowrap" style="min-width:100px;">Pickup Date</th>
                                                        <th nowrap="nowrap">Confirm Pickup Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">
                                                    <?php
                                                    if (!empty($pickup_data)) {
                                                        foreach ($pickup_data as $row) {
                                                            // dd($row);
                                                            $C_Weight = $row->orderdetail->carat;
                                                            $a_carat_price = (!empty($row->a_C_Rate) && $row->a_C_Rate != '0.00') ? $row->a_C_Rate : $row->orderdetail->rate;
                                                            $a_net_price = round($row->buy_price, 2);
                                                            $a_discount_main = 0;//($row->c_raprate != 0) ? (round(($a_carat_price - $row->c_raprate) / $row->c_raprate * 100, 2)) : '0';

                                                            if (!empty($row->a_price) && !empty($row->a_discount)) {
                                                                $a_discount_main = $row->a_discount;
                                                                $a_carat_price = $row->a_C_Rate;
                                                                $a_net_price = round($row->a_price, 2);
                                                            }

                                                            if ($row->diamond_type == "L") {
                                                                $stock_id = $row->orderdetail->id;
                                                            } else {
                                                                $stock_id = $row->orderdetail->id;
                                                            }

                                                            $color = $row->orderdetail->color;
                                                            if ($row->orderdetail->color == "fancy") {
                                                                $color = $row->orderdetail->fancy_color; //. ' ' .$row->f_overtone . ' '. $row->f_intensity;
                                                            }
                                                        ?>
                                                        {!! QrCode::generate($row->certificate_no, 'assets/qrcodes/' . $row->certificate_no . '.svg'); !!}
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i id="<?php echo $row->orders_id; ?>" data-userid="<?php echo $row->orderdetail->customer_id; ?>" data-cerino="<?php echo $row->certificate_no; ?>" data-netprice="" class="fas fa-plus-square main_plus me-2 cursor-pointer"></i>
                                                                    <label class="checkbox checkbox-success justify-content-center me-2">
                                                                        <input type="checkbox" class="check_box" data-pickupid="<?= $row->pickups->pickup_id; ?>" data-stock_id="<?= $stock_id; ?>" data-shape="<?= $row->orderdetail->shape; ?>" data-cut="<?= $row->orderdetail->cut; ?>" data-lab="<?= $row->orderdetail->lab; ?>" data-certi="<?= $row->certificate_no; ?>" data-carat="<?= $C_Weight ?>" data-stone="<?= 1 ?>" data-acprice="<?= $a_carat_price ?>" data-aprice="<?= $a_net_price ?>" data-pol="<?= $row->orderdetail->polish; ?>" data-mea="<?= $row->orderdetail->length . '*' . $row->orderdetail->width . '*' . $row->orderdetail->depth; ?>" data-color="<?= $color; ?>" data-clarity="<?= $row->orderdetail->clarity; ?>" data-fl="<?= $row->orderdetail->fluorescence; ?>" data-sym="<?= $row->orderdetail->symmetry; ?>" data-tb="<?= $row->orderdetail->table_per; ?>" <?php if (!empty($row->orderdetail->length) && !empty($row->orderdetail->width && $row->orderdetail->width != 0)) { ?> data-ratio="<?= round(@$row->orderdetail->length / @$row->orderdetail->width, 2); ?>" <?php } ?> data-dp="<?= $row->orderdetail->depth_per; ?>">
                                                                        <span></span>
                                                                    </label>
                                                                    <!--<input type="checkbox" ng-click="chClick($event)" data-carat="<?= $C_Weight ?>" data-stone="<?= 1 ?>" data-acprice="<?= $a_carat_price ?>" data-aprice="<?= $a_net_price ?>">-->
                                                                        <button class="btn btn-primary btn-sm edit_price" id="hide_edit_<?= $row->pickups->pickup_id ?>" data-pickupid="<?php echo $row->pickups->pickup_id; ?>" data-location="<?= $row->pickups->destination; ?>">Edit</button>
                                                                        <button class="btn btn-success btn-sm save_price" id="hide_save_<?= $row->pickups->pickup_id ?>" data-pickupid="<?php echo $row->pickups->pickup_id; ?>" data-certino="<?= $row->certificate_no; ?>" data-carat="<?= $C_Weight; ?>" style="display:none;">Save</button>
                                                                </div>
                                                            </td>
                                                            <td class="datatable-cell text-center" nowrap="nowrap">
                                                                <div class="d-flex align-items-center">
                                                                        <a class="btn btn-sm btn-clean btn-icon btn-success pickup_done me-2" data-id="<?php echo $row->pickups->pickup_id; ?>" data-order_id="{!! $row->orders_id !!}" data-location="<?php echo $row->pickups->destination; ?>"  data-toggle="tooltip" data-placement="right" title="Receive"><i class="fas fa-truck"></i></a>
                                                                </div>
                                                            </td>
															<td class="text-capitalize" nowrap="nowrap"><?php echo $row->orderdetail->supplier_name; ?></td>
                                                            <td nowrap="nowrap"><span class="badge badge-warning"><?php echo $row->pickups->status; ?></span></td>
                                                            <td class="text-center" nowrap="nowrap" style="min-width:100px;">
                                                                <span id="p_city_hidden_<?= $row->pickups->pickup_id ?>"><?php echo $row->pickups->destination; ?></span>
                                                                <select id="p_city_change_<?= $row->pickups->pickup_id ?>" name="city" class="city form-select" style="display:none;">
                                                                    <option value="" <?= ($row->pickups->destination == '') ? 'selected' : '' ?>>Please select city</option>
                                                                </select>
                                                            </td>

															<td><?php echo $row->orderdetail->shape; ?></td>
                                                            <td nowrap><?php echo $stock_id; ?></td>
                                                            <td><?php echo $row->ref_no; ?></td>
                                                            <td><?php echo $C_Weight; ?></td>
                                                            <td><?php echo $row->orderdetail->color; ?></td>
                                                            <td><?php echo $row->orderdetail->clarity; ?></td>
                                                            <td nowrap="nowrap"><?php echo $row->orderdetail->lab; ?></td>
                                                            <?php if ($row->orderdetail->lab == 'GIA') {
                                                                $render_string = '<td nowrap="nowrap"><a href="http://www.gia.edu/report-check?reportno=' . $row->certificate_no . '" target="_blank">' . $row->certificate_no . '</a></td>';
                                                            } elseif ($row->orderdetail->lab == 'IGI') {
                                                                $render_string = '<td nowrap="nowrap"><a href="https://www.igi.org/viewpdf.php?r=' . $row->certificate_no . '" target="_blank">' . $row->certificate_no . '</a></td>';
                                                            } elseif ($row->orderdetail->lab == 'HRD') {
                                                                $render_string = '<td nowrap="nowrap"><a href="https://my.hrdantwerp.com/?id=34&record_number=' . $row->certificate_no . '&weight=" target="_blank">' . $row->certificate_no . '</a></td>';
                                                            } else {
                                                                $render_string = '<td nowrap="nowrap">' . $row->certificate_no . '</td>';
                                                            }
                                                            echo $render_string;

                                                            $row->c_raprate = 0;
                                                            ?>
                                                            <td>$<?php echo $row->c_raprate; ?></td>
                                                            <td>
                                                                <?php
                                                                if ($row->orderdetail->color == "fancy") { ?>
                                                                    <span id="a_disc_lbl_<?= $row->pickups->pickup_id ?>">0%</span>
                                                                <?php } else { ?>
                                                                    <span id="a_disc_lbl_<?= $row->pickups->pickup_id ?>"><?php echo $a_discount_main; ?>%</span>
                                                                <?php }
                                                                ?>
                                                                <input class="form-control a_discount_change_input" id="a_discount_change_<?= $row->pickups->pickup_id ?>" data-net="<?= $a_net_price ?>" value="<?= $a_discount_main ?>" type="text" size="4" style="min-width:95px;display:none;">
                                                            </td>
                                                            <td>$<?php echo round($a_carat_price, 2); ?></td>
                                                            <td style="min-width: 103px;">
                                                                <span id="a_price_hidden_<?= $row->pickups->pickup_id ?>">$<?php echo $a_net_price; ?> </span>
                                                                <input class="form-control a_price_change_input" id="a_price_change_<?= $row->pickups->pickup_id ?>" data-sale_price="<?= $row->sale_price ?>" data-buy_price="<?= $row->buy_price ?>" data-carat="<?= $row->orderdetail->carat ?>" data-rap="<?= $row->c_raprate ?>" data-id="<?= $row->pickups->pickup_id ?>" value="<?= $a_net_price ?>" type="number" size="4" style="width:100px;display:none;">
                                                            </td>
                                                            <td nowrap="nowrap"><?php echo $row->pickups->invoice_number; ?></td>
															<td nowrap="nowrap" style="min-width:100px;">
                                                                <span id="p_date_hidden_<?= $row->pickups->pickup_id ?>"><?php echo $row->pickups->expected_delivery_at; ?></span>
                                                                <input class="form-control date-picker pickup_date" id="p_date_change_<?= $row->pickups->pickup_id ?>" data-id="<?= $row->pickups->pickup_id ?>" value="<?= $row->pickups->expected_delivery_at; ?>" type="text" style="display:none;">
                                                            </td>
                                                            <td nowrap="nowrap"><?php echo $row->pickups->created_at; ?> </td>
                                                        </tr>
                                                        <?php
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="21" class="text-center">No Record Found!!</td>
                                                        </tr>
                                                    <?php } ?>
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
		$(document).ready(function() {
			var xhr;
			var req;
			var total_selected = 0;
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

			var table = $('#datatable').DataTable({
				// "ordering": false,
				"scrollX": true,
				"pageLength": 50,
				// 'exactvalue': true,
				initComplete: function() {
					this.api().columns().every(function() {
						var column = this;
						switch (column.header().innerHTML) {
							case 'Supplier':
								// var select3 = $('.datatable-input[data-col-index="5"]').on( 'change', function () {
								// 	var val = $.fn.dataTable.util.escapeRegex(
								// 		$(this).val()
								// 	);
								// 	column.search( val ? '^'+val+'$' : '', true, false ).draw();
								// });
								column.data().unique().sort().each(function(d, j) {
									$('.datatable-input[data-col-index="5"]').append('<option value="' + d + '">' + d + '</option>');
								});
								// var select = $('<select><option value=""></option></select>')
								// 	.appendTo( $(column.header()).empty() )
								// 	.on( 'change', function () {
								// 		var val = $.fn.dataTable.util.escapeRegex(
								// 			$(this).val()
								// 		);
								// 		column
								// 			.search( val ? '^'+val+'$' : '', true, false )
								// 			.draw();
								// 	});

								// column.data().unique().sort().each( function ( d, j ) {
								// 	select.append( '<option value="'+d+'">'+d+'</option>' )
								// } );
								break;
						}
					});
				}
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
					//table.column(i).search(val ? val : '', false, false);
					table.column(i).search(val ? "^" + val : '', true, false);
					console.log(table.column(i).header().innerHTML)
					console.log(params)
					console.log(val)
				});
				table.table().draw();
			});

			// $('.date-picker').daterangepicker({
			// 	"singleDatePicker": true,
			// 	"autoApply": true,
			// 	"locale": {
        	// 		"format": "YYYY-MM-DD",
			// 	},
			// });

			// $('#render_string').delegate('.order_a_r', 'click', function()
			// {

			// 	// var id = $(this).attr('id');
			// 	var certino = $(this).attr('data-certino');
			// 	var customer_id = $(this).attr('data-userid');
			// 	var order_status = $(this).attr('data-status');
			// 	var id = $(this).attr('data-orders_id');
			// 	// alert(id);
			// 	Swal.fire({
			// 			title: 'Are you sure you want to Undo The Diamond?',
			// 			// text: "You won't be able to revert this!",
			// 			icon: 'warning',
			// 			showCancelButton: true,
			// 			confirmButtonColor: '#3085d6',
			// 			cancelButtonColor: '#d33',
			// 			confirmButtonText: 'Yes, Undo it!',
			// 		}).then((result) => {
			// 			if (result.isConfirmed) {
			// 				request_call("{{ url('admin-update-order-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino);
			// 				xhr.done(function(mydata) {
			// 					Swal.fire({
			// 						title: "UNDO!",
			// 						text: 'QC Undo successfully...!!',
			// 						type: "success",
			// 					}).then((result) => {
			// 						location.reload();
			// 					});
			// 				});
			// 			}
			// 		});
			// });


			$('#render_string').delegate('.main_plus', 'click', function() {
				$(".detail_view").each(function(e) {
					$(this).remove();
				});

				$(".main_minus").each(function(e) {
					$(this).removeClass("fas fa-minus-square").addClass("fas fa-plus-square");
					$(this).removeClass("main_minus").addClass("main_plus");
				});

				$(this).removeClass("fas fa-plus-square").addClass("fas fa-minus-square");
				$(this).removeClass("main_plus").addClass("main_minus");

				// var parent_tr = $(this).parents('tr');
				// var diamond_id = $(this).attr('id');
				// var userid = $(this).attr('data-userid');
				// var maindiscount = $(this).attr('data-discount');
				// var netprice = $(this).attr('data-netprice');
				// var cerino = $(this).attr('data-cerino');
				// request_call("{{ url('admin-confirmToSupplier') }}Logistics/GetDiamondDetailPickup", "diamond_id=" + diamond_id + "&userid=" + userid + "&netprice=" + netprice + "&maindiscount=" + maindiscount + "&cerino=" + cerino);
				// xhr.done(function(mydata) {
				// 	if ($.trim(mydata.success) != "") {
				// 		parent_tr.after("<tr class='detail_view'> <td colspan='100%'> " + $.trim(mydata.success) + " </td></tr>");
				// 	}
				// });

			});

			$('#render_string').delegate('.main_minus', 'click', function() {
				$(this).removeClass("fas fa-minus-square").addClass("fas fa-plus-square");
				$(this).removeClass("main_minus").addClass("main_plus");
				var parent_tr = $(this).parents('tr');
				parent_tr.next("tr.detail_view").remove();
			});

			$('#render_string').delegate('.pickup_done', 'click', function() {
				var ids = $(this).data('id');
				var location = $(this).data('location');
				var order_id = $(this).data('order_id');
				var status = 'PICKUP_DONE';
				Swal.fire({
					title: "Are you sure?",
					text: "Are you sure you want to pickup done?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, Pickup done!"
				}).then(function(result) {
					if (result.value) {
                        blockUI.block();
						request_call("{{ url('logistics-pickup-done') }}", "ids=" + ids + "&location=" + location + "&order_id=" + order_id + "&status=" + status );
						xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Success",
                                text: mydata.success,
                                type: "success",
                                icon: "success",
                            }).then((result) => {
                                window.location.reload();
                            });
						});
					}
				});
			});

			$('.check_box').on("change", function() {
				var carat = 0;
				var stone = 0;
				var acprice = 0;
				var acpricetotal = 0;
				var aprice = 0;
				$(this).parents("tr").removeClass("success");
				$('.check_box:checked').each(function() {
					$(this).parents("tr").addClass("success");
					stone += parseInt($(this).data('stone'));
					carat += parseFloat($(this).data('carat'));
					acprice += parseFloat($(this).data('acprice'));
					aprice += parseFloat($(this).data('aprice'));
					acpricetotal = aprice / carat;
				});
				$('#totalcarat').html(carat.toFixed(2));
				$('#total_pcs').html(stone);
				$('#totalApercarat').html(acpricetotal.toFixed(2));
				$('#totalAamount').html(aprice.toFixed(2));
			});

			//start angular check box
			//		var app = angular.module("myApp", []);
			//		app.controller("myCntroller", function ($scope) {
			//			$scope.chClick = function (item) {
			//				alert(item.target.getAttribute('data-carat'));
			//				alert(item.target.getAttribute('data-stone'));
			//				alert(item.target.getAttribute('data-acprice'));
			//				alert(item.target.getAttribute('data-aprice'));
			//			};
			//		});
			//end angular check box

			//		$('#render_string').delegate('.check_box', 'click', function () {
			//
			//            if ($(this).prop('checked') == true)
			//            {
			//                $(this).parents("tr").addClass("success");
			//                manage_selected_ids("add_only", $(this).attr('data-certi_no'));
			//            }
			//			else
			//            {
			//                $('.check_box_all').each(function () {
			//                    this.checked = false;
			//                });
			//                $(this).parents("tr").removeClass("success");
			//                manage_selected_ids("remove_only", $(this).attr('data-certi_no'));
			//            }
			//
			//            if ($('.check_box:checked').length == $('.check_box').length) {
			//                $('.check_box_all').each(function () {
			//                    this.checked = true;
			//                });
			//            }
			//        });
			//
			//		function manage_selected_ids(option_tag, real_value)
			//        {
			//			showPageloader();
			//            if (option_tag == "add_only")
			//            {
			//                var real_value = $.trim(real_value).toUpperCase() + ",";
			//                if (selected_ids.indexOf(real_value) < 0)
			//                {
			//                    selected_ids += real_value;
			//                }
			//                total_selected++;
			//                $('#total_pcs').text(total_selected);
			//                request_call("{{ url('admin-confirmToSupplier') }}orderhistory/avg_total_discount_confirmed", "certificate=" + $.trim(selected_ids));
			//                xhr.done(function (mydata) {
			//					hidePageloader();
			//                    $('#totalcarat').html(mydata['totalcarat']);
			//                    $('#totalrap').html(mydata['totalrap']);
			//                    $('#totaldiscount').html(mydata['totaldiscount']);
			//                    $('#totalpercarat').html(mydata['totalpercarat']);
			//                    $('#totalamount').html(mydata['totalamount']);
			//					$('#totalApercarat').html(mydata['totalApercarat']);
			//					$('#totalAamount').html(mydata['totalAamount']);
			//                });
			//            }
			//			else if (option_tag == "remove_only")
			//            {
			//				showPageloader();
			//                var real_value = $.trim(real_value).toUpperCase() + ",";
			//                while (selected_ids.indexOf(real_value) >= 0)
			//                {
			//                    selected_ids = selected_ids.replace(real_value, "");
			//                }
			//                total_selected--;
			//                $('#total_pcs').text(total_selected);
			//                request_call("{{ url('admin-confirmToSupplier') }}orderhistory/avg_total_discount_confirmed", "certificate=" + $.trim(selected_ids));
			//                xhr.done(function (mydata) {
			//					hidePageloader();
			//                    $('#totalcarat').html(mydata['totalcarat']);
			//                    $('#totalrap').html(mydata['totalrap']);
			//                    $('#totaldiscount').html(mydata['totaldiscount']);
			//                    $('#totalpercarat').html(mydata['totalpercarat']);
			//                    $('#totalamount').html(mydata['totalamount']);
			//					$('#totalApercarat').html(mydata['totalApercarat']);
			//					$('#totalAamount').html(mydata['totalAamount']);
			//                });
			//            }
			//        }
			//-------------------------------------------pickup done

			$('#render_string').delegate('.a_price_change_input', 'blur', function() {
				var id = $(this).attr('data-id');
				var chg_price = $(this).val();
				var rap_hidden = $(this).attr('data-rap');
				var t_price = $(this).attr('data-t_price');

				if (rap_hidden == 0) {
					rap_hidden = 1;
				}
				var carat_hidden = $(this).attr('data-carat');

				var tototot = rap_hidden * carat_hidden;
				var percentsavings = ((tototot - chg_price) / tototot) * 100;
				var per_display = Math.round(percentsavings * 100) / 100;

				var doller_per_carat = parseFloat(chg_price / carat_hidden).toFixed(2);
				if (rap_hidden == 1) {
					var diss_cal = 0;
				} else {
					var diss_cal = parseFloat((doller_per_carat - rap_hidden) / rap_hidden * 100).toFixed(2);
				}

				if (parseFloat(chg_price) > parseFloat(t_price)) {
					alert("Original Price greater than Amount!  " + t_price);
				}
				if (parseFloat(per_display) < 0) {
					per_display = Math.abs(per_display);
					$('#a_discount_change_' + id).val(diss_cal);
					$('#a_disc_lbl_' + id).html(diss_cal);
				} else {
					$('#a_discount_change_' + id).val(diss_cal);
					$('#a_disc_lbl_' + id).html(diss_cal);
				}

				if (chg_price == '') {
					$(this).val('');
				}
			});

			$("#render_string").delegate('.edit_price', 'click', function() {
				var ids = $(this).attr('data-pickupid');
				$('#p_date_change_'+ids).daterangepicker({
					"singleDatePicker": true,
					"autoApply": true,
					"locale": {
						"format": "YYYY-MM-DD",
					},
				});

				var ids = $(this).attr('data-pickupid');
				var location = $(this).attr('data-location');
				$('#a_price_hidden_' + ids).hide();
				$('#a_price_change_' + ids).show();

				$('#p_date_hidden_' + ids).hide();
				$('#p_date_change_' + ids).show();

				$('#p_city_hidden_' + ids).hide();
				$('#p_city_change_' + ids).append('<option value="Surat">Surat</option><option value="Mumbai">Mumbai</option><option value="Hongkong">Hongkong</option><option value="Direct Ship Hongkong">Direct Ship Hongkong</option>');
				$('#p_city_change_' + ids).val(location);
				$('#p_city_change_' + ids).show();

				$('#hide_edit_' + ids).hide();
				$('#hide_save_' + ids).show();
			});

			$("#render_string").delegate('.save_price', 'click', function() {
				var ids = $(this).attr('data-pickupid');
				var value = $('#a_price_change_' + ids).val();
				var a_dis = $('#a_discount_change_' + ids).val();
				var location = $('#p_city_change_' + ids).val();
				var pickup_date = $('#p_date_change_' + ids).val();
				var certino = $(this).attr('data-certino');
				var carat = $(this).attr('data-carat');

				console.log(value + " " + certino + " " + location + " " + pickup_date);
				if (value != "" && certino != "" && location != "" && pickup_date != "") {
					request_call("{{ url('admin-confirmToSupplier') }}admin/Logistics/Change_price", "value=" + value + "&certino=" + certino + "&a_dis=" + a_dis + "&carat=" + carat + "&location=" + location + "&pickup_date=" + pickup_date);
					xhr.done(function(mydataorder) {
						$('#hide_save_' + ids).hide();
						$('#a_price_hidden_' + ids).show();
						$('#a_price_change_' + ids).hide();
						$('#hide_edit_' + ids).show();
						$('#a_price_hidden_' + ids).html("$" + value);
						$('#discont_message').html(mydataorder.success);
						window.setTimeout(function() {
							window.location.reload()
						}, 1000)
					});
				} else {
					alert("Location, Pickup date, Price can't be empty..!");
				}
			});

			$("#render_string").delegate(".edit_price_admin", "click", function() {
				var ids = $(this).attr('data-pickupid');
				var certi = $(this).attr('data-certino');
				$('#a_price_hidden_admin_' + certi).hide();
				$('#a_price_change_admin' + ids).show();
				$('#hide_edit_admin' + ids).hide();
				$('#hide_save_admin_' + ids).show();
			});

			$("#render_string").delegate(".save_price_admin", "click", function() {
				var ids = $(this).attr('data-pickupid');
				var certino = $(this).attr('data-certino');
				var value = $('#a_price_change_admin' + ids).val();
				var a_dis = $('#a_discount_change_admin_' + ids).val();
				var carat = $(this).attr('data-carat');

				if (value != "" && certino != "") {
					request_call("{{ url('admin-confirmToSupplier') }}admin/Logistics/Change_price", "value=" + value + "&certino=" + certino + "&a_dis=" + a_dis + "&carat=" + carat);
					xhr.done(function(mydataorder) {
						$('#discont_message').html(mydataorder.success);
						$('#hide_save_admin_' + ids).hide();
						$('#a_price_hidden_admin_' + certino).show();
						$('#a_price_change_admin' + ids).hide();
						$('#hide_edit_admin' + ids).show();
						console.log('#a_price_hidden_' + ids)
						console.log(value)

						$('#a_price_hidden_' + ids).html(value);

						pickup_listdata();
					});
				} else {
					alert("Empty Price..!");
				}
			});

			$('#render_string').delegate('.a_price_change_input_admin', 'blur', function() {
				var id = $(this).attr('data-id_admin');
				var chg_price = $(this).val();
				var rap_hidden = $(this).attr('data-rap_admin');
				var t_price = $(this).attr('data-t_price');

				if (rap_hidden == 0) {
					rap_hidden = 1;
				}
				var carat_hidden = $(this).attr('data-carat_admin');

				var tototot = rap_hidden * carat_hidden;
				var percentsavings = ((tototot - chg_price) / tototot) * 100;
				var per_display = Math.round(percentsavings * 100) / 100;

				var doller_per_carat = parseFloat(chg_price / carat_hidden).toFixed(2);
				if (rap_hidden == 1) {
					var diss_cal = 0;
				} else {
					var diss_cal = parseFloat((doller_per_carat - rap_hidden) / rap_hidden * 100).toFixed(2);
				}

				if (parseFloat(chg_price) > parseFloat(t_price)) {
					alert("Original Price greater than Amount!  " + t_price);
				}

				if (parseFloat(per_display) < 0) {
					per_display = Math.abs(per_display);
					$('#a_discount_change_admin_' + id).val(diss_cal);
					$('#a_disc_lbl_admin_' + id).html(diss_cal);
				} else {
					$('#a_discount_change_admin_' + id).val(diss_cal);
					$('#a_disc_lbl_admin_' + id).html(diss_cal);
				}

				if (chg_price == '') {
					$(this).val('');
				}
			});
		});
	</script>
</body>
</html>
