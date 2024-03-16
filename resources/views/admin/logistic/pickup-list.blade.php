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

    <style>
        .table tr{
            white-space: nowrap; overflow: hidden; text-overflow:ellipsis;
        }
    </style>

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
                                        <h3 class="card-title" data-toggle="tooltip" data-placement="right" title="Pickup List">QC List</span></h3>
                                        <div class="card-toolbar">
                                            <button class="btn btn-sm btn-light-primary excel_download me-2"><i class="far fa-file-excel"></i> Excel Download</button>
                                            <a class="btn btn-sm btn-primary me-2" id="pickupdoneprint"><span>Print Lable</span></a>
                                                <!-- <a class="btn btn-sm btn-primary" id="generatememo"><span>Generate Memo</span></a> -->
                                        </div>
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
                                                    <select class="form-control datatable-input" data-col-index="3">
                                                        <option value="" selected>Select city</option>
                                                        <option value="Surat">Surat</option>
                                                        <option value="Mumbai">Mumbai</option>
                                                        <option value="Hongkong">Hongkong</option>
                                                        <option value="USA">USA</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 mb-lg-0 mb-6">
                                                    <label>Supplier:</label>
                                                    <select class="form-control datatable-input" data-col-index="2">
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
                                                <div class="col-lg-2">
                                                    <label></label>
                                                    <button class="form-control btn btn-secondary btn-secondary--icon" id="kt_reset">
                                                        <span>
                                                            <i class="la la-close"></i>
                                                            <span>Reset</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Action</th>
                                                        <th class="text-center">Action</th>
                                                        <th nowrap="nowrap">Supplier</th>
                                                        {{-- <th nowrap="nowrap">Status</th> --}}
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

                                                            $C_Weight = $row->orderdetail->carat;
                                                            $a_carat_price = (!empty($row->buy_rate) && $row->buy_rate != '0.00') ? $row->buy_rate : $row->orderdetail->rate;
                                                            $a_net_price = round($row->buy_price, 2);
                                                            $a_discount_main = $row->buy_discount;//($row->c_raprate != 0) ? (round(($a_carat_price - $row->c_raprate) / $row->c_raprate * 100, 2)) : '0';

                                                            $colour = '';
                                                            if ($row->order_status == 'REJECT' || $row->order_status == 'RELEASED') {
                                                                $colour = 'color:#FF0000 !important';
                                                            }

                                                            if (!empty($row->memo_name)) {
                                                                $colour = 'color:#1bc5bd !important';
                                                            }

                                                            if (!empty($row->export_name)) {
                                                                $colour = 'color:#ffa800 !important';
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
                                                                    <i id="{{ $row->orders_id; }}" data-orders_id="{{ $row->orders_id }}" data-userid="{{ $row->customer_id; }}" data-cerino="{{ $row->certificate_no; }}" data-netprice="" class="fa fa-plus-square main_plus me-2 cursor-pointer"></i>
                                                                    <label class="checkbox checkbox-success justify-content-center me-2">
                                                                        <input type="checkbox" class="check_box" data-pickupid="{{ $row->pickups->pickup_id; }}" data-stock_id="{{ $stock_id; }}" data-irm_no="{{ $row->irm_no; }}" data-shape="{{ $row->orderdetail->shape; }}" data-cut="{{ $row->orderdetail->cut; }}" data-lab="{{ $row->orderdetail->lab; }}" data-certi="{{ $row->certificate_no; }}" data-carat="{{ $C_Weight }}" data-stone="{{ 1 }}" data-acprice="{{ $a_carat_price }}" data-aprice="{{ $a_net_price }}" data-pol="{{ $row->orderdetail->polish; }}" data-mea="{{ $row->orderdetail->length . '*' . $row->orderdetail->width . '*' . $row->orderdetail->depth; }}" data-color="{{ $color; }}" data-clarity="{{ $row->orderdetail->clarity; }}" data-fl="{{ $row->orderdetail->fluorescence; }}" data-sym="{{ $row->orderdetail->symmetry; }}" data-tb="{{ $row->orderdetail->table_per; }}" <?php if (!empty($row->orderdetail->length) && !empty($row->orderdetail->width && $row->orderdetail->width != 0)) { ?> data-ratio="{{ round(@$row->orderdetail->length / @$row->orderdetail->width, 2); }}" <?php } ?> data-dp="{{ $row->orderdetail->depth_per; }}">
                                                                        <span></span>
                                                                    </label>
                                                                    <!--<input type="checkbox" ng-click="chClick($event)" data-carat="{{ $C_Weight }}" data-stone="{{ 1 }}" data-acprice="{{ $a_carat_price }}" data-aprice="{{ $a_net_price }}">-->
                                                                        <button class="btn btn-primary btn-sm edit_price" id="hide_edit_{!! $row->pickups->pickup_id !!}" data-pickupid="{!! $row->pickups->pickup_id !!}" data-location="{!! $row->pickups->destination !!}">Edit</button>
                                                                        <button class="btn btn-success btn-sm save_price" id="hide_save_{!! $row->pickups->pickup_id !!}" data-pickupid="{{ $row->pickups->pickup_id; }}" data-certino="{!! $row->certificate_no !!}" data-carat="{{ $C_Weight; }}" style="display:none;">Save</button>
                                                                </div>
                                                            </td>
                                                            <td class="datatable-cell text-center" nowrap="nowrap">
                                                                <div class="d-flex align-items-center">
                                                                        <a class="btn btn-sm btn-clean btn-icon btn-danger qcreturn me-2" data-id="{{ $row->pickups->pickup_id; }}" data-location="{{ $row->pickups->destination; }}" data-order_id="{{ $row->orders_id }}" data-status="QCRETURN" title="QCRETURN"><i class="fas fa-undo"></i></a>
                                                                        @if(!empty($row->qc_list->qc_comment))
                                                                            <button class="btn btn-sm btn-icon btn-success qcreview" order_id="{{$row->orders_id}}" data-title="{{$row->qc_list->qc_comment}}" data-status="QCREVIEW" ><i class="fa fa-check" title="{{$row->qc_list->qc_comment}}"></i></button>
                                                                        @else
                                                                            <button class="btn btn-sm btn-icon btn-warning qcreview" order_id="{{$row->orders_id}}" data-title="" data-status="QCREVIEW"><i class="fa fa-comment"  title="QC Review"></i></button>
                                                                        @endif
                                                                </div>
                                                            </td>
															<td class="text-capitalize" nowrap="nowrap" style="{{ $colour; }}">{{ $row->orderdetail->supplier_name; }}</td>
                                                            {{-- <td nowrap="nowrap"><span class="badge badge-warning">{{ $row->pickup_status; }}</span></td> --}}
                                                            <td class="text-center" nowrap="nowrap" style="min-width:100px;{{ $colour; }}">
                                                                <span id="p_city_hidden_{{ $row->pickups->pickup_id }}">{{ $row->pickups->destination; }}</span>
                                                                <select id="p_city_change_{{ $row->pickups->pickup_id }}" name="city" class="city form-select" style="display:none;">
                                                                    <option value="" {{ ($row->pickups->destination == '') ? 'selected' : '' }}>Please select city</option>
                                                                </select>
                                                            </td>
															<td style="{{ $colour; }}">{{ $row->orderdetail->shape; }}</td>
															<td style="{{ $colour; }}" nowrap>{{ $stock_id; }}</td>
															<td style="{{ $colour; }}">{{ $row->orderdetail->ref_no; }}</td>
															<td style="{{ $colour; }}">{{ number_format($C_Weight,2); }}</td>
															<td style="{{ $colour; }}">{{ $row->orderdetail->color; }}</td>
															<td style="{{ $colour; }}">{{ $row->orderdetail->clarity; }}</td>
															<td style="{{ $colour; }}" nowrap="nowrap">{{ $row->orderdetail->lab; }}</td>
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
                                                            <td style="{{ $colour; }}">${{ $row->c_raprate; }}</td>
                                                            <td style="{{ $colour; }}">
                                                                <?php
                                                                if ($row->orderdetail->color == "fancy") { ?>
                                                                    <span id="a_disc_lbl_{{ $row->pickups->pickup_id }}">0%</span>
                                                                <?php } else { ?>
                                                                    <span id="a_disc_lbl_{{ $row->pickups->pickup_id }}">{{ $a_discount_main; }}%</span>
                                                                <?php } ?>
                                                                <input class="form-control a_discount_change_input" id="a_discount_change_{{ $row->pickups->pickup_id }}" data-net="{{ $a_net_price }}" value="{{ $a_discount_main }}" type="text" size="4" style="min-width:95px;display:none;">
                                                            </td>
                                                            <td style="{{ $colour; }}">${{ round($a_carat_price, 2); }}</td>
                                                            <td style="min-width: 103px; {{ $colour; }}">
                                                                <span id="a_price_hidden_{{ $row->pickups->pickup_id }}">${{ $a_net_price; }} </span>
                                                                <input class="form-control a_price_change_input" id="a_price_change_{{ $row->pickups->pickup_id }}" data-sale_price="{{ $row->sale_price }}" data-buy_price="{{ $row->buy_price }}" data-carat="{{ $row->orderdetail->carat }}" data-rap="{{ $row->c_raprate }}" data-id="{{ $row->pickups->pickup_id }}" value="{{ $a_net_price }}" type="number" size="4" style="width:100px;display:none;">
                                                            </td>
                                                            <td style="{{ $colour; }}" nowrap="nowrap">{{ $row->pickups->invoice_number; }}</td>
															<td nowrap="nowrap" style="min-width:100px;{{ $colour; }}">
																<span id="p_date_hidden_{{ $row->pickups->pickup_id }}">{{ $row->pickups->expected_delivery_at; }}</span>
																<input class="form-control date-picker pickup_date" id="p_date_change_{{ $row->pickups->pickup_id }}" data-id="{{ $row->pickups->pickup_id }}" value="{{ $row->pickups->expected_delivery_at; }}" type="text" style="display:none;">
															</td>
                                                            <td style="{{ $colour; }}" nowrap="nowrap">{{ $row->pickups->created_at; }} </td>
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
                order: [[17, 'desc']],
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
									$('.datatable-input[data-col-index="2"]').append('<option value="' + d + '">' + d + '</option>');
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

			$("#pickupdoneprint").click(function() {
                $("#header-modal").html('');
                if ($('.check_box:checked').length > 0 && $('.check_box:checked').length <= 20) {
					var checkbox=$('.check_box:checked');
					$('.check_box:checked').each(function(v) {
                        var certi = $(this).data('certi');
						var stock_id = $(this).data('stock_id');
						var pickupid = $(this).data('pickupid');

						var irm_no = $(this).data('irm_no');
						var shape = $(this).data('shape');
						var cut = $(this).data('cut');
						var lab = $(this).data('lab');
						var certi = $(this).data('certi');
						var key = v;
						var carat = $(this).data('carat');
						var pol = $(this).data('pol');
						var mea = $(this).data('mea');

						var ratio = $(this).data('ratio');

						var color = $(this).data('color');
						var sym = $(this).data('sym');
						var tb = $(this).data('tb');

						var clarity = $(this).data('clarity');
						var fl = $(this).data('fl');
						var dp = $(this).data('dp');

						if(v == 0){
							var padding = '5px';
						}
						else{
							var padding = '20px';
						}

                        $("#header-modal").append(
                            // '<div style="height:168px!important;width: 310px;box-sizing: border-box;margin: 25px 0 10px 0; padding-top:10px;">' +
							// 	'<table cellspacing="0" style="border:1px solid #000;width:95%;height: 90%;font-size: 12px; margin:4px 4px;line-height: 14px;">' +
							// 		'<tbody>' +
							// 			'<tr>' +
							// 				'<td colspan="2" style="padding-left: 7px;padding-top: 5px;width: 95px;white-space: nowrap;"><b>' + shape + '</b></td>' +
							// 			'</tr>' +
							// 			'<tr>' +
							// 				'<td style="padding-left: 7px;white-space: nowrap;">Ct.: <b>' + carat + '</b></td>' +
							// 				'<td align="top" colspan ="2" rowspan="2" style="text-align: right;">' +
							// 					'<img src="./assets/frontend/images/logo-dark.svg" height="28" style="margin-top: -24px;padding-right: 7px;">' +
							// 				'</td>' +
							// 			'</tr>' +
							// 			'<tr>' +
							// 				'<td style="padding-left: 7px;white-space: nowrap;">Col: <b>' + color + '</b></td>' +
							// 			'</tr>' +
							// 			'<tr>' +
							// 				'<td style="padding-left: 7px;white-space: nowrap;">CL: <b>' + clarity + '</b></td>' +
							// 				'<td style="white-space: nowrap;padding-left:10px;"><b>' + lab + ' : ' + certi + '</b></td>' +
							// 				'<td rowspan="4" style="text-align: right;padding-left: 5px;"><img src="./assets/qrcodes/' + certi + '.svg" style="height: 70px"></td>' +
							// 			'</tr>' +
							// 			'<tr>' +
							// 				'<td style="padding-left: 7px;white-space: nowrap;">Cut: <b>' + cut + '</b></td>' +
							// 				'<td style="white-space: nowrap;padding-left:10px;"><b>' + mea + '</b></td>' +
							// 			'</tr>' +
							// 			'<tr>' +
							// 				'<td style="padding-left: 7px;white-space: nowrap;">Pol: <b>' + pol + '</b></td>' +
							// 				'<td style="white-space: nowrap;padding-left:10px;">TB: <b>' + tb + '%</b></td>' +
							// 			'</tr>' +
							// 			'<tr>' +
							// 				'<td style="padding-left: 7px;white-space: nowrap;">Sym: <b>' + sym + '</b></td>' +
							// 				'<td style="white-space: nowrap;padding-left:10px;">TD: <b>' + dp + '%</b></td>' +
							// 			'</tr>' +
							// 			'<tr>' +
							// 				'<td style="padding-left: 7px;white-space: nowrap;">Flo: <b>' + fl + '</b></td>' +
							// 				'<td style="white-space: nowrap;padding-left:10px;">Ratio: <b>' + ratio + '</b></td>' +
							// 				'<td style="text-align: right;padding-right: 7px;"><b>' + stock_id + '</b></td>' +
							// 			'</tr>' +
							// 		'</tbody>' +
							// 	'</table>' +
                            // '</div>'
							'<div class="main" style="height:190px!important;width: 310px;margin: 5 0 8px 0;box-sizing: border-box;padding-top:'+ padding +'" >' +
								'<table cellspacing="0" style="border-top-left-radius:10px;	border-top-right-radius:10px;	border-top:1px solid #000;	border-right:1px solid #000;	border-left:1px solid #000;	width:95%;	height:25%;	font-size: 16px; margin-top:0px;	margin-left:4px;	line-height: 14px;">' +
									'<tbody>' +
										'<tr style="height: 16px;">'+
											'<td align="top" rowspan="4" colspan ="2" rowspan="2" style="width:20%;">' +
												'<img src="./assets/frontend/images/logo-dark.svg" height="25" style="padding:5px 15px;">' +
											'</td>' +
											'<td align="center" >'+
												'<b>The Diamond Port</b>'+
											'</td>'+
											'<td rowspan="4" style="text-align:	right;padding-left: 5px;"><img src="./assets/qrcodes/' + certi + '.svg" style="height:50px;padding-right:10px; margin-top:2px;"></td>' +
										'</tr>'+

									'</tbody>' +
								'</table>' +
								'<table cellspacing="0" style="border-bottom-left-radius:10px;	border-bottom-right-radius:10px;	border-right:1px solid #000;	border-left:1px solid #000;	border-bottom:1px solid #000;	width:95%;	height:75%;	font-size: 14px; margin-bottom:4px;	margin-left:4px;	line-height: 14px;">'+
									'<tbody>' +
										'<tr style="vertical-align:top; padding-top:3px;height:16px;">'+
											'<td style="padding-left: 5px;white-space: nowrap;width:50%;"><b>' + shape + '</b></td>' +
											'<td style="padding-left: 5px;white-space: nowrap;">Ratio:<b>' + ratio + '</b></td>' +
										'</tr>'+
										'<tr style="vertical-align:top; padding-top:3px;height:16px;">'+
											'<td style="padding-left: 5px;white-space: nowrap;width:50%;">Ct : <b>' + carat + '</b></td>' +
											'<td style="padding-left: 5px;white-space: nowrap;">Col: <b>' + color + '</b></td>' +
										'</tr>'+
										'<tr style="vertical-align:top; padding-top:3px;height:16px;">'+
											'<td style="padding-left: 5px;white-space: nowrap;width:50%;">CL: <b>' + clarity + '</b></td>' +
											'<td style="white-space: nowrap;padding-left:5px;"><b>' + lab + ' : ' + certi + '</b></td>' +
										'</tr>'+
										'<tr style="vertical-align:top; padding-top:3px;height:16px;">' +
											'<td style="padding-left: 5px;white-space: nowrap;width:50%;">Cut: <b>' + cut + '</b></td>' +
											'<td style="white-space: nowrap;padding-left:5px;"><b>' + mea + '</b></td>' +
										'</tr>' +
										'<tr style="vertical-align:top; padding-top:3px;height:16px;">' +
											'<td style="padding-left: 5px;white-space: nowrap;width:50%;">Pol: <b>' + pol + '</b></td>' +
											'<td style="white-space: nowrap;padding-left:5px;">TB: <b>' + tb + '%</b></td>' +
										'</tr>' +
										'<tr style="vertical-align:top; padding-top:3px;height:16px;">' +
											'<td style="padding-left: 5px;white-space: nowrap;width:50%;">Sym: <b>' + sym + '</b></td>' +
											'<td style="white-space: nowrap;padding-left:5px;">TD: <b>' + dp + '%</b></td>' +
										'</tr>' +
										'<tr style="vertical-align:top; padding-top:3px;height:16px;">' +
											'<td style="padding-left: 5px;white-space: nowrap;width:50%;">Flo: <b>' + fl + '</b></td>' +
											'<td style="white-space: nowrap;padding-left:5px;"><b>' + irm_no + '</b></td>' +
										'</tr>' +
									'</tbody>' +
								'</table>'+
                            '</div>'
                        );
					});

					var newWin = window.open("");
					newWin.document.write('<html><style type="text/css">@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");body{font-family: Nunito Sans, Helvetica, Arial, sans-serif;font-size: 10px;}</style><body style="">');
					newWin.document.write(document.getElementById('header-modal').innerHTML);
					newWin.document.write('</body></html>');
					newWin.document.close();
					setTimeout(function() {
						newWin.print();
					}, 500);
				} else {
                    Swal.fire("Warning!", "Please Select more then One Record and less then 7 !", "warning");
				}
			});

			$("#generatememo").click(function() {
				var total_val = 0;
				var pickupid = [];
				$('.check_box:checked').each(function() {
					total_val += 1;
					pickupid.push($(this).attr('data-pickupid'));
				})
				if (total_val == 0) {
					Checked_Stone();
				} else {
                    blockUI.block();
					request_call("{{ url('admin-confirmToSupplier') }}Logistics/pickupGenerateMemo", "pickupid=" + pickupid);
					xhr.done(function(mydata) {
                        blockUI.release();
						$('#discont_message').html(mydata.success);
						// pickup_listdata();
						document.location.href = ("assets/rkdimond_download/" + mydata.file_name);
					});
				}
			});

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

				var parent_tr = $(this).parents('tr');
				var id = $(this).data('orders_id');
                blockUI.block();
				request_call("{{ url('admin-view-order-detail')}}", "id="+id);
				xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        blockUI.release();
						parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
					}
				});

			});

			$('#render_string').delegate('.main_minus', 'click', function() {
                blockUI.block();
				$(this).removeClass("fas fa-minus-square").addClass("fas fa-plus-square");
				$(this).removeClass("main_minus").addClass("main_plus");
				var parent_tr = $(this).parents('tr');
				parent_tr.next("tr.detail_view").remove();
                blockUI.release();
			});

			$('#render_string').delegate('.pickup_qc_review', 'click', function() {
				var id = $(this).data('id');
				var order_id = $(this).data('order_id');
				var msg = 'QC Review';
				$("#header-modal").html('<div class="modal-dialog modal-lg">' +
					'<div class="modal-content">' +
                        '<div class="modal-header">' +
                            '<h4 class="modal-title">Order ID : ' + id + '</h4>' +
                            '<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>' +
                        '</div>' +
                        '<div class="modal-body">' +
                            '<div class="row">' +
                                '<div class="col-md-12 col-sm-12 col-xs-12">' +
                                    '<div class="row m-b-10" style="width: 100%;">' +
                                        '<p>Are you sure you want to ' + msg + ' request?</p>' +
                                    '</div>' +
                                '</div>' +
					        '</div>' +
                            '<div class="row">' +
                                '<div class="col-md-12 col-sm-12 col-xs-12">' +
                                    '<textarea class="form-control comment" rows="2" placeholder="Comments" required name="comment" maxlength="100" id="comment"></textarea>'+
                                    '<span class="com text-danger"></span> &nbsp;<span class="GFG">100 </span> Characters Remaining'+
                                '</div>' +
					        '</div>' +
					    '</div>' +
					    '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>' +
                            '<button type="button" data-order_id="' + order_id + '" class="btn btn-primary qc_review_in mr-2">QC Review In</button>' +
                            '<button type="button" data-order_id="' + order_id + '" class="btn btn-primary qc_review_out">QC Review Out</button>' +
					    '</div>' +
					'</div>' +
					'</div>'
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

				$('#header-modal').delegate('.qc_review_in', 'click', function() {
					var comment = $('.comment').val();
					if (comment == '') {
						var mssag = 'Please enter comment';
						$('.com').html(mssag);
					} else {
						var status = "IN";
						var newcomment = $('.comment').val();
						var order_id = $(this).attr('data-order_id');
						qcReviewStatus(id, newcomment, status, order_id);
					}
				});
				$('#header-modal').delegate('.qc_review_out', 'click', function() {
					var comment = $('.comment').val();
					if (comment == '') {
						var mssag = 'Please enter comment';
						$('.com').html(mssag);
					} else {
						var status = "OUT";
						var newcomment = $('.comment').val();
						var order_id = $(this).attr('data-order_id');
						qcReviewStatus(id, newcomment, status, order_id);
					}
				});
			});

			function qcReviewStatus(id, newcomment, status, order_id) {
				$('.qc_review_in').hide();
				$('.qc_review_out').hide();
				request_call("{{ url('admin-qc-review-inout') }}", "id=" + id + "&comment=" + encodeURIComponent(newcomment) + "&status=" + status + "&order_id=" + order_id);
				xhr.done(function(mydata) {
                    $('#header-modal').modal('hide');
                    Swal.fire("Success!", "Diamond " + status + " successfully", "success");
				});
			}

            $('#render_string').delegate('.qcreview', 'click', function() {
                var order_id = $(this).attr('order_id');
				var status = $(this).attr('data-status');
				var title = $(this).attr('data-title');

                $("#header-modal").html('<div class="modal-dialog ">'
                                +'<div class="modal-content">'
                                    +'<div class="modal-header">'
                                        +'<h4 class="modal-title">QC Review What You Observe </h4>'
                                        +'<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>'
                                    +'</div>'
                                    +'<div class="modal-body">'
                                        +'<div class="row">'
                                            +'<div class="col-md-12 col-sm-12 col-xs-12">'
                                                +'<div class="row " style="width: 100%;">'
                                                    +'<textarea class="form-control comment" rows="3" placeholder="Comments" required name="comment" maxlength="100" id="comment">' + title + '</textarea>'
                                                +'</div>'
                                            +'</div>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="modal-footer justify-content-between">'
                                        +'<button type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</button>'
                                        +'<button type="button" class="btn btn-primary reject_comment_diamond">Yes</button>'
                                    +'</div>'
                                +'</div>'
                            +'</div>'
                );
                $('#header-modal').modal('show');

                $('#header-modal').delegate('.reject_comment_diamond', 'click', function() {
                    $('.com').html('');
                    var comment = $("#comment").val();

                    if (comment == '') {
                        var mssag = 'Please enter comment';
                        $('.com').html(mssag);
                    }else{
                        blockUI.block();
                        $('#header-modal').modal('hide');
                        request_call("{{ url('tracking-status-update')}}",  "status=" + status + "&order_id=" + order_id + "&comment=" + encodeURIComponent(comment));
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Review !",
                                text: 'QC Review successfully...!!',
                                type: "Warning",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            });

			$('#render_string').delegate('.qcreturn', 'click', function() {
				var ids = $(this).data('id');
				var location = $(this).data('location');
				var status = $(this).data('status');
				var order_id= $(this).data('order_id');
				Swal.fire({
					title: "Are you sure?",
					text: "Are you sure you want to Return Stone?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, Return !"
				}).then(function(result) {
					if (result.value) {
                        blockUI.block();
						request_call("{{ url('tracking-status-update') }}", "ids=" + ids + "&location=" + location + "&status=" + status + "&order_id=" + order_id );
						xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Success",
                                text: "Diamond Return Successfully!",
                            }).then((result) => {
                                window.location.reload();
                            });
						});
					}
				});
			});

			$('#render_string').delegate('.pickup_return', 'click', function() {
				var ids = $(this).data('id');
				Swal.fire({
					title: "Are you sure?",
					text: "Are you sure you want to order cancel?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, Order release!"
				}).then(function(result) {
					if (result.value) {
                        blockUI.block();
						request_call("{{ url('admin-confirmToSupplier') }}admin/Logistics/pickup_return", "ids=" + ids);
						xhr.done(function(mydata) {
                            blockUI.release();
							$('#discont_message').html(mydata.success);
							window.location.reload();
						});
					}
				});
			});

			$('.excel_download').click(function() {
				var total_val = 0;
				var pickupid = [];
				$('.check_box:checked').each(function() {
					total_val += 1;
					pickupid.push($(this).attr('data-pickupid'));
				})
				if (total_val == 0) {
					Checked_Stone();
				} else {
                    blockUI.block();
					request_call("{{ url('admin-confirmToSupplier') }}Logistics/pickupExcelDownload", "pickupid=" + pickupid);
					xhr.done(function(mydata) {
                        blockUI.release();
						// $('#discont_message').html(mydata.success);
						document.location.href = ("assets/rkdimond_download/" + mydata.file_name);
					});
				}

			})

			function Checked_Stone() {
				Swal.fire("Warning!", "Please Select at least One Record !", "warning");
			}

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
				$('#totalpercarat').html(acpricetotal.toFixed(2));
				$('#totalamount').html(aprice.toFixed(2));
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

			$('#render_string').delegate('.fa-plus-square-o', 'click', function() {
				$(".detail_view").each(function(e) {
					$(this).remove();
				});

				$(".fa-minus-square-o").each(function(e) {
					$(this).removeClass("fa-minus-square-o").addClass("fa-plus-square-o");
				});

				$(this).removeClass("fa-plus-square-o").addClass("fa-minus-square-o");

				var parent_tr = $(this).parents('tr');
                blockUI.block();
				request_call("{{ url('admin-confirmToSupplier') }}Logistics/GetList_pickupdoneDetail", "id=" + parent_tr.attr('id'));
				xhr.done(function(mydata) {
					if ($.trim(mydata.detail) != "") {
                        blockUI.release();
						parent_tr.after("<tr class='detail_view'> <td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
					}
				});
			});

			$('#render_string').delegate('.fa-minus-square-o', 'click', function() {
                blockUI.block();
				$(this).removeClass("fa-minus-square-o").addClass("fa-plus-square-o");
				var parent_tr = $(this).parents('tr');
				parent_tr.next("tr.detail_view").remove();
                blockUI.release();
			});

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
				$('#p_city_change_' + ids).append('<option value="Surat">Surat</option><option value="Mumbai">Mumbai</option><option value="Hongkong">Hongkong</option><option value="Direct Ship Hongkong">Direct Ship Hongkong</option><option value="Direct Ship USA">Direct Ship USA</option>');
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
					request_call("{{ url('edit-pickups') }}", "pickup_id=" + ids + "&value=" + value + "&certino=" + certino + "&a_dis=" + a_dis + "&carat=" + carat + "&location=" + location + "&pickup_date=" + pickup_date);
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
