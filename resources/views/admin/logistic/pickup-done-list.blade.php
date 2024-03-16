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
                                    <div class="card-header align-items-center">
                                        <h3 class="card-title">Received List</h3>
                                        <form class="m-2" method="post" action="{{ url('pickup-done-list') }}" id="qr_code_form">
											@csrf
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                <input type="text" placeholder="Scan QR code" name="search" class="form-control form-control-sm me-2 qr_search" value="{{ $search }}"/>
                                                </div>
                                                <div class="input-group-append me-2">
                                                <input  class="btn btn-success btn-sm" type="button" id="btnSearch" value="Search">
                                                <button class="btn btn-danger btn-sm" type="button" onClick="window.location.href='pickup-done-list'">clear</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="card-toolbar">
                                            <!-- <button class="btn btn-sm btn-light-primary send_mumbai mr-2"><i class="fab fa-medium-m"></i> Send to Mumbai</button>
                                            <button class="btn btn-sm btn-light-primary send_hongkong mr-2"><i class="fab fa-hire-a-helper"></i> Send to Hongkong</button> -->
                                            <button class="btn btn-sm btn-light-primary excel_download me-2"><i class="far fa-file-excel"></i> Excel Download</button>
                                            <a class="btn btn-sm btn-primary me-2" id="pickupdoneprint"><span>Print Lable</span></a>
                                            <!-- <a class="btn btn-sm btn-success mr-2" id="generatememo"><span>Generate Memo</span></a> -->
                                            {{-- <a class="btn btn-sm btn-success me-2" href="{{ url('generate-memo') }}"><span>Generate Memo</span></a> --}}
                                            <!-- <a class="btn btn-sm btn-warning mr-2" id="generateexport"><span>Generate Export</span></a> -->
                                            <a class="btn btn-sm btn-warning me-2 generate-pdf" data-generated_by="{{ auth::user()->id }}"><span>Generate Export</span></a>
                                        </div>
                                    </div>
                                    <!--begin: Datatable-->
                                    <div class="card-body">
                                        <div class="card-header border-0">
                                            <div class="card-title"></div>
                                            <div class="card-toolbar">
                                                <button class="btn btn-sm btn-secondary me-2">Total Pcs : <span id="total_pcs">0</span></button>
                                                <button class="btn btn-sm btn-secondary me-2">CARAT : <span id="totalcarat">0.00</span></button>
                                                <button class="btn btn-sm btn-secondary me-2">Sell $/ct $<span id="totalpercarat">0.00</span></button>
                                                <button class="btn btn-sm btn-secondary me-2">Sell $<span id="totalamount">0.00</span></button>
                                                <button class="btn btn-sm btn-secondary me-2">Buy $/ct $<span id="totalApercarat">0</button>
                                                <button class="btn btn-sm btn-secondary me-2">Buy $<span id="totalAamount">0</button>
                                            </div>
                                        </div>
                                        {{-- <table class="table center table-striped table-bordered bulk_action">
                                            <thead>
                                                <tr class="headings">
                                                    <th>Total Stone</th>
                                                    <th>Total Carat</th>
                                                    <!--<th>Total Rap</th>-->
                                                    <th>$/CARAT A PRICE</th><?php //A Price Price-> ?>
                                                    <th>TOTAL A PRICE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><span id="total_pcs">0</span></td>
                                                    <td><span id="totalcarat">0</span></td>
                                                    <!--<td><span id="totalrap">0</span></td>-->
                                                    <td>$<span id="totalApercarat">0</span></td>
                                                    <td>$<span id="totalAamount">0</span></td>
                                                </tr>
                                            </tbody>
                                        </table> --}}
                                        <form>
                                            <div class="row mb-6">
                                                <div class="col-lg-3 mb-lg-0 mb-6">
                                                    <label>Location:</label>
                                                    <select class="form-select datatable-input" data-col-index="5">
                                                        <option value="" selected>Select city</option>
                                                        <option value="Surat">Surat</option>
                                                        <option value="Mumbai">Mumbai</option>
                                                        <option value="Hongkong">Hongkong</option>
                                                        <option value="Direct Ship Hongkong">Direct Ship Hongkong</option>
                                                        <option value="Direct Ship USA">Direct Ship USA</option>
                                                    </select>
                                                </div>
                                                {{-- <div class="col-lg-3 mb-lg-0 mb-6">
                                                    <label>Status:</label>
                                                    <select class="form-select datatable-input" data-col-index="5">
                                                        <option value="">Select Status</option>
                                                        <option value="PENDING">PENDING</option>
                                                        <option value="PICKUP_DONE">PICKUP DONE</option>
                                                        <!-- <option value="READY_TO_PICKUP">READY TO PICKUP</option> -->
                                                        <option value="RECEIVED">RECEIVED</option>
                                                    </select>
                                                </div> --}}
                                                <div class="col-lg-3 mb-lg-0 mb-6">
                                                    <label>Supplier:</label>
                                                    <select class="form-select datatable-input" data-col-index="3">
                                                        <option value="" selected>Select Supplier</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 d-flex align-items-center gap-2 gap-lg-3">
                                                    <label></label>
                                                    <button class="btn btn-sm btn-primary me-2 mt-4" id="kt_search"><i class="la la-search"></i> Search</button>
                                                    <button class="btn btn-sm btn-secondary me-2 mt-4" id="kt_reset"><i class="la la-close"></i> Reset</button>
                                                </div>
                                            </div>
                                        </form>
                                        <table id="datatable" class="table table-bordered table-hover dataTable no-footer">
                                            <thead>
                                                <th>
                                                    <label class="checkbox justify-content-center mr-2">
                                                        <input type="checkbox" id="checkAll"/><span></span>
                                                    </label>
                                                </th>
                                                <th>Action</th>
                                                <th>Action</th>
                                                <th>Supplier</th>
                                                <th>Return</th>
                                                {{-- <th class="text-centecarat r">Status</th> --}}
                                                <th class="text-center">Location</th>
                                                <th>Shape</th>
                                                <th>SKU</th>
                                                <th>Ref No.</th>
                                                <th>Carat</th>
                                                <th>Color</th>
                                                <th>Clarity</th>
                                                <th>Lab</th>
                                                <th>Certificate</th>
                                                <th>Rap</th>
                                                <th>Invoice</th>
                                                <th>buy Dis(%)</th>
                                                <th>buy $/ct</th>
                                                <th>buy Price</th>
                                                <th>Pickup Done Date</th>
                                                <th>Confirm Pickup Date</th>
                                                <!-- <th>Undo</th> -->
                                            </thead>

                                            <tbody id="render_pickup_done">
                                            <?php

                                            if (!empty($pickup_done)) {
                                                foreach ($pickup_done as $row) {
                                                    $row->carat = $row->carat;
                                                    $t_discount_main = $row->sale_discount;

                                                    $a_discount_main = round($row->sale_discount, 2);
                                                    $carat_price = ($row->sale_price/$row->carat);// $row->raprate + (($row->raprate * $a_discount_main) / 100 );
                                                    $net_price = round($row->sale_price, 2);

                                                    $a_carat_price = ($row->buy_price/$row->carat);// $row->raprate + (($row->raprate * $a_discount_main) / 100 );
                                                    $a_net_price = round($row->buy_price, 2);
                                                    $a_netprice_input = $a_net_price;
                                                    $a_discount_input = number_format($a_discount_main);

                                                    if($row->diamond_type == "L")
                                                    {
                                                        $stock_id = $row->id;
                                                    }
                                                    else
                                                    {
                                                        $stock_id = $row->id;
                                                    }

                                                    if ($row->lab == 'GIA') {
                                                        $certilink = '<a href="http://www.gia.edu/report-check?reportno=' . $row->certificate_no . '" target="_blank">' . $row->certificate_no . '</a>';
                                                    } elseif ($row->lab == 'IGI') {
                                                        $certilink = '<a href="https://www.igi.org/viewpdf.php?r=' . $row->certificate_no . '" target="_blank">' . $row->certificate_no . '</a>';
                                                    } elseif ($row->lab == 'HRD') {
                                                        $certilink = '<a href="https://my.hrdantwerp.com/?id=34&record_number=' . $row->certificate_no . '&weight=" target="_blank">' . $row->certificate_no . '</a>';
                                                    } else {
                                                        $certilink = $row->certificate_no;
                                                    }

                                                    $color = '';
                                                    if ($row->order_status == 'REJECT' || $row->order_status == 'RELEASED') {
                                                        $color = 'color:#FF0000 !important';
                                                    }

                                                    if (!empty($row->memo_name)) {
                                                        $color = 'color:#1bc5bd !important';
                                                    }

                                                    if (!empty($row->export_name)) {
                                                        $color = 'color:#ffa800 !important';
                                                    }
                                                    ?>
                                                    <tr style="<?= $color; ?>">
                                                        <td style="<?= $color; ?>">
                                                            <div class="d-flex align-items-center">
                                                                <i id="{{ $row->orders_id }}" data-userid="{{ $row->customer_id }}" data-pickup_id="{{ $row->pickup_id }}" data-orders_id="{{ $row->orders_id }}" data-certificate="{{ $row->certificate_no; }}" data-netprice="" class="fas fa-plus-square main_plus me-2 cursor-pointer"></i>
                                                                @if($row->order_status != 'REJECT' && $row->order_status != 'RELEASED')
                                                                    <label class="checkbox justify-content-center me-2">
                                                                    <input type="checkbox" data-order_id="<?= $row->orders_id; ?>"
                                                                    data-stock_id="<?= $stock_id; ?>"
                                                                    data-diamond_type="<?= $row->diamond_type; ?>"
                                                                    data-shape="<?= $row->shape; ?>"
                                                                    data-carat="<?= $row->carat; ?>"
                                                                    data-irm_no="<?= $row->irm_no; ?>"
                                                                    data-color="<?= $row->color; ?>"
                                                                    data-clarity="<?= $row->clarity; ?>"
                                                                    data-cut="<?= $row->cut; ?>"
                                                                    data-pol="<?= $row->polish; ?>"
                                                                    data-sym="<?= $row->symmetry; ?>"
                                                                    data-fl="<?= $row->fluorescence; ?>"
                                                                    data-lab="<?= $row->lab; ?>"
                                                                    data-certi="<?= $row->certificate_no; ?>"
                                                                    data-mea="<?= $row->length.'*'.$row->width.'*'.$row->depth; ?>"
                                                                    <?php if(!empty($row->length && $row->length != 0) && !empty($row->width && $row->width != 0)){ ?> data-ratio="<?= round(@$row->length / @$row->width, 2); ?>" <?php } ?>
                                                                    data-tb="<?= $row->table_per; ?>"
                                                                    data-dp="<?= $row->depth_per; ?>"
                                                                    data-qc_com =  "<?= $row->qc_comment; ?>"
                                                                    data-location="<?= $row->pickup_city; ?>" data-cprice="<?= $carat_price; ?>" data-price="<?= $net_price; ?>" data-acprice="<?= $a_carat_price; ?>" data-aprice="<?= $a_net_price; ?>" class="check_box"><span></span>
                                                                    </label>
                                                                @endif
                                                            </div>
                                                        </td>
															<?php
															if (Auth::user()->user_type == 1) {
																$a_discount_input = '<span id="a_disc_lbl_admin_' . $row->pickup_id . '">' . $a_discount_main . '%</span>'
																		. '<input class="form-control a_discount_change_input" id="a_discount_change_admin_' . $row->pickup_id . '" data-net="' . $a_net_price . '"  data-carat="' . $row->carat . '" data-rap="' . $row->raprate . '"  data-id="' . $row->pickup_id . '"     value="' . $a_discount_main . '" type="text" size="4" style="min-width:95px;display:none;">';
																$a_netprice_input = '<span id="a_price_hidden_admin_' . $row->certificate_no . '">' . $a_net_price . '</span>'
																		. '<input class="form-control a_price_change_input_admin" id="a_price_change_admin' . $row->pickup_id . '" data-certi_admin="' . $row->certificate_no . '" data-t_price="' . $row->sale_price . '" data-net="' . $a_net_price . '"  data-carat_admin="' . $row->carat . '" data-rap_admin="' . $row->raprate . '" data-id_admin="' . $row->pickup_id . '"   value="' . $a_net_price . '" type="number" size="4" style="min-width:110px;display:none;">';
															}
															?>
                                                        <!-- <td class="text-center">
                                                            <button class="btn btn-primary btn-sm edit_price_admin" data-pickupid="<?= $row->pickup_id ?>" id="hide_edit_admin<?= $row->pickup_id ?>" data-certino="<?= $row->certificate_no ?>" >Edit</button>
                                                            <button class="btn btn-primary btn-sm save_price_admin" data-pickupid="<?= $row->pickup_id ?>" id="hide_save_admin_<?= $row->pickup_id ?>" data-certino="<?= $row->certificate_no ?>" data-carat="<?= $row->carat ?>" style="display:none;">Save</button>
                                                            <a class="btn btn-sm btn-clean btn-icon btn-success pickup_done me-2" data-id="<?php echo $row->pickup_id; ?>" data-location="<?php echo $row->pickup_city; ?>" data-toggle="tooltip" data-placement="right" title="Recive"><i class="fas fa-truck"></i></a>
                                                                        <?php if (empty($row->invoicenumber)) { ?>
                                                                            <a class="btn btn-sm btn-clean btn-icon btn-success pickup_qc_review me-2" data-id="<?php echo $row->pickup_id; ?>" data-order_id="<?php echo $row->orders_id; ?>" data-toggle="tooltip" data-placement="right" title="QC Review">
                                                                                <span class="svg-icon svg-icon-md">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="64" height="64" data-order_id="<?php echo $row->orders_id; ?>" viewBox="0 0 64 64">
                                                                                        <defs>
                                                                                            <clipPath id="clip-verify_1">
                                                                                                <rect width="64" height="64"></rect>
                                                                                            </clipPath>
                                                                                        </defs>
                                                                                        <g id="verify_1" data-name="verify – 1" clip-path="url(#clip-verify_1)">
                                                                                            <g id="noun_Document_1484530" transform="translate(-6.1 -6.2)">
                                                                                                <g id="Group" transform="translate(6.1 10.9)">
                                                                                                    <path id="Compound_Path" data-name="Compound Path" d="M51.225,26.209,36.791,11.337a1.458,1.458,0,0,0-1.021-.437H14.848A8.748,8.748,0,0,0,6.1,19.648V59.233a8.748,8.748,0,0,0,8.748,8.748h35.43a1.458,1.458,0,0,0,1.094-.51L69.742,46.694a1.458,1.458,0,0,0,.073-1.823l-5.322-7.509a5.1,5.1,0,0,0-4.155-2.114H51.663V27.23a1.458,1.458,0,0,0-.437-1.021ZM36.864,15.566,47.289,26.355H41.238a4.374,4.374,0,0,1-4.374-4.374ZM9.016,59.233V19.575a5.832,5.832,0,0,1,5.832-5.832h19.1v8.238a7.29,7.29,0,0,0,7.29,7.29h7.509v5.9H40.217a5.1,5.1,0,0,0-4.155,2.114L30.74,44.872a1.458,1.458,0,0,0,.073,1.823L47.07,65.065H14.848A5.832,5.832,0,0,1,9.016,59.233ZM65.368,47.2,54.433,59.6,59.1,47.2ZM34.75,44.288l3.718-5.176a2.187,2.187,0,0,1,1.75-.948H42.7l-1.385,6.124ZM41.457,47.2,46.122,59.6,35.187,47.2Zm8.821,15.09L44.592,47.2H56.037ZM44.3,44.288l1.385-6.124H54.87l1.458,6.124Zm17.788-5.176,3.718,5.176H59.317l-1.531-6.124h2.479a2.187,2.187,0,0,1,1.823.875Z" transform="translate(-6.1 -10.9)" fill="#707070"></path>
                                                                                                    <path id="Path" d="M26.256,54.4h-7a1.458,1.458,0,1,0,0,2.916h7a1.458,1.458,0,0,0,0-2.916Z" transform="translate(-9.271 -22.688)" fill="#707070"></path>
                                                                                                    <path id="Path-2" data-name="Path" d="M26.256,41.7h-7a1.458,1.458,0,1,0,0,2.916h7a1.458,1.458,0,1,0,0-2.916Z" transform="translate(-9.271 -19.247)" fill="#707070"></path>
                                                                                                    <path id="Path-3" data-name="Path" d="M26.256,67.1h-7a1.458,1.458,0,1,0,0,2.916h7a1.458,1.458,0,1,0,0-2.916Z" transform="translate(-9.271 -26.13)" fill="#707070"></path>
                                                                                                </g>
                                                                                            </g>
                                                                                        </g>
                                                                                    </svg>
                                                                                </span>
                                                                            </a>
                                                                        <?php } ?>
                                                                    <?php if (empty($row->invoicenumber)) { ?>
                                                                         <a class="btn btn-sm btn-clean btn-icon btn-danger pickup_return mr-2" data-id="<?php echo $row->pickup_id; ?>" data-toggle="tooltip" data-placement="right" title="Order release"><i class="fas fa-undo"></i></a>
                                                                    <?php } ?>
                                                        </td>
                                                             -->
														<td class="datatable-cell text-center" nowrap="nowrap">
															<div class="d-flex align-items-center">
																	<button class="btn btn-primary btn-sm edit_price " id="hide_edit_<?= $row->pickup_id ?>" data-pickupid="<?php echo $row->pickup_id; ?>" data-location="<?= $row->pickup_city; ?>">Edit</button>
																	<button class="btn btn-success btn-sm save_price " id="hide_save_<?= $row->pickup_id ?>" data-pickupid="<?php echo $row->pickup_id; ?>" data-certino="<?= $row->certificate_no; ?>" data-carat="" style="display:none;">Save</button>
																	<!-- <a class="btn btn-icon btn-bg-light btn-primary btn-sm me-1 edit_price" data-id="" id="" data-certino="" data-sale_price='' data-buy_price='' title="Store Given Back">
																			<span class="svg-icon svg-icon-3">
																				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																					<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
																					<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
																				</svg>
																			</span>
																		</a>
																	<button class="btn btn-sm btn-icon btn-primary me-1 order_a_r" id="" data-userid="" data-certino="" data-status="APPROVED"><i class="fa fa-check" title="QC Approve"></i></button>
																	<button class="btn btn-sm btn-icon btn-danger order_a_r" id="" data-userid="" data-certino="" data-status="REJECT"><i class="fa fa-times" title="QC Reject"></i></button></td> -->
															</div>
														</td>
														<td class="datatable-cell text-center" nowrap="nowrap">
															<div class="d-flex align-items-center">
																	<button class="btn btn-sm btn-icon btn-danger me-1 order_a_r" id="{{$row->orders_id}}" data-userid="{{$row->customer_id}}" data-certino="{{$row->certificate_no}}"data-status="UNDOTOQC"><i class="fa fa-undo" title="Undo"></i></button>
																	<button class="btn btn-sm btn-icon btn-danger me-1 order_a_r" id="{{$row->orders_id}}" data-userid="{{$row->customer_id}}" data-certino="{{$row->certificate_no}}"data-status="QCRETURN"><i class="fa fa-truck" title="QC Return Back"></i></button>
																	@if(!empty($row->qc_comment))
																	    <button class="btn btn-sm btn-icon btn-success order_a_r" id="{{$row->orders_id}}" data-userid="{{$row->customer_id}}" data-certino="{{$row->certificate_no}}" data-title="{{$row->qc_comment}}" data-status="QCREVIEW" ><i class="fa fa-check" aria-hidden="true" title="{{$row->qc_comment}}"></i></button>
																	@else
																	    <button class="btn btn-sm btn-icon btn-success order_a_r" id="{{$row->orders_id}}" data-userid="{{$row->customer_id}}" data-certino="{{$row->certificate_no}}" data-title="" data-status="QCREVIEW"><i class="fa fa-comment" title="QC Review"></i></button>
																	@endif
																</td>

															</div>
														</td>
														<td style="{{ $color; }}">{{ $row->supplier_name; }}</td>
														<td style="{{ $color; }}"> {{ ($row->return_price != 0) ? 'R' : '' }} </td>
                                                        {{-- <td class="text-center" style="{{ $color; }}">{{ $row->pickup_status; }}</td> --}}
                                                        <td class="text-center min-w-150px" style="{{ $color; }}">
                                                            <span id="p_city_hidden_<?= $row->pickup_id ?>"><?php echo $row->pickup_city; ?></span>
                                                                <select id="p_city_change_<?= $row->pickup_id ?>" name="city" class="city form-select" style="display:none;">
                                                                    <option value="" <?= ($row->pickup_city == '') ? 'selected' : '' ?>>Please select city</option>
                                                                </select>
                                                            @if($row->pickup_city == 'Direct Ship Hongkong' || $row->pickup_city == 'Direct Ship USA')
                                                                <br/><button type="button" class="btn btn-sm btn-icon btn-warning directshipping" data-invoice="{{ $row->invoice_number }}" data-pickupid="{{ $row->pickup_id }}" data-location="{{ $row->pickup_city }}"><i  class="fas fa-plane"></i></button>
                                                            @endif
                                                        </td>
                                                            <!-- <td style="width: 125px;">
                                                                <select data-id="<?= $row->pickup_id ?>" name="city" class="city form-control">
                                                                    <option value="">City to send</option>
                                                                    <?php if($row->pickup_city != 'Surat') { echo '<option value="Surat">Surat</option>'; } ?>
                                                                    <?php if($row->pickup_city != 'Mumbai') { echo '<option value="Mumbai">Mumbai</option>'; } ?>
                                                                    <?php if($row->pickup_city != 'Hongkong') { echo '<option value="Hongkong">Hongkong</option>'; } ?>
                                                                    <?php if($row->pickup_city != 'Direct Ship Hongkong') { echo '<option value="Direct Ship Hongkong">Direct Ship Hongkong</option>'; } ?>
                                                                </select>
                                                            </td> -->
														<td style="{{ $color; }}">{{  $row->shape; }}</td>
														<td nowrap style="{{  $color; }}">{{  $stock_id; }}</td>
                                                        <?php //if ($this->session->user_type == 1) { ?>
                                                            <td style="{{  $color; }}">{{  $row->ref_no; }}</td>
                                                        <?php //} ?>
                                                        <td style="{{ $color; }}">{{  $row->carat; }}</td>
                                                        <td style="{{ $color; }}">{{  $row->color; }}</td>
                                                        <td style="{{ $color; }}">{{  $row->clarity; }}</td>
                                                        <td style="{{  $color; }}">{{  $row->lab; }}</td>
                                                        <td style="<?= $color; ?>"><?= $certilink ?></td>
                                                        <td style="{{ $color; }}">{{ $row->raprate; }}</td>
                                                        <td style="{{ $color; }}">{{ $row->invoice_number; }}</td>
                                                        <td style="{{ $color; }}"><?= $a_discount_input; ?></td>
                                                        <td style="{{ $color; }}"><?= number_format($a_carat_price,2); ?></td>
                                                        <td style="{{ $color; }}"><?= $a_netprice_input; ?></td>
                                                        <td nowrap style="{{ $color; }}">{{ $row->expected_delivery_at; }}</td>
                                                        <td nowrap style="{{ $color; }}">{{ $row->created_at; }}</td>
                                                        <!-- <td>
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <button class="btn btn-danger btn-sm pickup_cancel" data-toggle="tooltip" data-placement="right" title="Undo" id="<?= $row->pickup_id ?>" >Undo</button>&nbsp;
                                                                <?php
                                                                if ($row->invoice_number == 0 || $row->invoice_number == '') { ?>
                                                                    <button class="btn btn-danger btn-sm pickup_done_return" data-toggle="tooltip" data-placement="right" title="Order Release" id="<?= $row->pickup_id ?>" data-orders_id="<?= $row->orders_id; ?>">Return</button>
                                                                <?php } ?>
                                                            </div>
                                                        </td> -->
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr>
                                                    <td colspan="100%" class="text-center">No Record Found!!</td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
							        <!--end: Datatable-->
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

			// $('.qr_search').focus();
			var input = $(".qr_search");
			var len = input.val().length;
			input[0].focus();
			input[0].setSelectionRange(len, len);
			function request_call(url, mydata) {
				if (xhr && xhr.readyState != 4) {
					xhr.abort();
				}
				xhr = $.ajax({
					url: url,
					type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					dataType: 'json',
					data: mydata,
				});
			}


			var table = $('#datatable').DataTable({
				"scrollX": true,
				"pageLength": 50,
                order: [[19, 'desc']],
				initComplete: function () {
					this.api().columns().every( function () {
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
									$('.datatable-input[data-col-index="3"]').append('<option value="' + d + '">' + d + '</option>');
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
					} );
				}
			});

			$('#kt_search').on('click', function(e) {
				e.preventDefault();
				var params = {};
				$('.datatable-input').each(function() {
					var i = $(this).data('col-index');
					if (params[i]) {
						params[i] += '|' + $(this).val();
					}
					else {
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


			// $("#qr_code_form").validate({
			// 	rules:
			// 	{
			// 		search: {required: true},
			// 	},
			// 	messages:
			// 	{
			// 		search: {required: ""},
			// 	},
			// 	submitHandler: function (form)
			// 	{
			// 		form.submit();
			// 	}
			// });

			$("#btnSearch").click(function(){
				$( "#qr_code_form" ).submit();
			});

            $("#render_pickup_done").delegate('.edit_price', 'click', function() {
				var ids = $(this).attr('data-pickupid');
				var location = $(this).attr('data-location');

				$('#p_city_hidden_' + ids).hide();
				$('#p_city_change_' + ids).append('<option value="Surat">Surat</option><option value="Mumbai">Mumbai</option><option value="Hongkong">Hongkong</option><option value="Direct Ship Hongkong">Direct Ship Hongkong</option><option value="Direct Ship USA">Direct Ship USA</option>');
				$('#p_city_change_' + ids).val(location);
				$('#p_city_change_' + ids).show();

				$('#hide_edit_' + ids).hide();
				$('#hide_save_' + ids).show();
			});

            $("#render_pickup_done").delegate('.save_price', 'click', function() {
				var ids = $(this).attr('data-pickupid');
				var location = $('#p_city_change_' + ids).val();

				if (location != "" ) {
					request_call("{{ url('edit-pickups') }}", "pickup_id=" + ids + "&location=" + location );
					xhr.done(function(mydataorder) {
						$('#hide_save_' + ids).hide();
						$('#hide_edit_' + ids).show();
						window.setTimeout(function() {
							window.location.reload()
						}, 1000)
					});
				} else {
					alert("Location, Pickup date, Price can't be empty..!");
				}
			});

			$('.qr_search').on('change', function(event) {

				var qr_codes = $('.qr_search').val();
				$('.qr_search').val(qr_codes+' ');
				$('.qr_search').focus();
				event.preventDefault();
			});

			$("#pickupdoneprint").click(function() {
				$("#myModal").empty();
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
											'<td style="white-space: nowrap;padding-left:5px;">Ratio: <b>' + ratio + '</b></td>' +
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
				// if ($('.check_box:checked').length > 0 && $('.check_box:checked').length <= 20) {
				// 	$('.check_box:checked').each(function() {
				// 		var stock_id = $(this).data('stock_id');
				// 		var pickupid = $(this).data('pickupid');

				// 		var shape = $(this).data('shape');
				// 		var cut = $(this).data('cut');
				// 		var lab = $(this).data('lab');
				// 		var certi = $(this).data('certi');

				// 		var carat = $(this).data('carat');
				// 		var pol = $(this).data('pol');
				// 		var mea = $(this).data('mea');

				// 		var ratio = $(this).data('ratio');

				// 		var color = $(this).data('color');
				// 		var sym = $(this).data('sym');
				// 		var tb = $(this).data('tb');

				// 		var clarity = $(this).data('clarity');
				// 		var fl = $(this).data('fl');
				// 		var dp = $(this).data('dp');

				// 		$.post("{{ url('Logistics-saveqrcode') }}", {
				// 			'certi': stock_id
				// 		}).done(function(data) {
				// 			$("#myModal").append(
				// 				'<div style="height:145.68px!important;width: 302.36px;box-sizing: border-box;margin: 0px 0 0px 0;">' +
				// 				'<table cellspacing="0" style="border:1px solid #000;width:93%;height: 90%;font-size: 12px; margin:4px 4px;line-height: 14px;">' +
				// 				'<tbody >' +
				// 				'<tr>' +
				// 				'<td colspan="2" style="padding-left: 7px;padding-top: 5px;width: 95px;white-space: nowrap;"><b>' + shape + '</b></td>' +
				// 				'</tr>' +
				// 				'<tr>' +
				// 				'<td style="padding-left: 7px;white-space: nowrap;">Ct.: <b>' + carat + '</b></td>' +
				// 				'<td align="top" colspan ="2" rowspan="2" style="text-align: right;">' +
				// 				'<img src="assets/images/qr.svg" height="28" style="margin-top: -24px;padding-right: 7px;">' +
				// 				'</td>' +
				// 				'</tr>' +
				// 				'<tr>' +
				// 				'<td style="padding-left: 7px;white-space: nowrap;">Col: <b>' + color + '</b></td>' +
				// 				'</tr>' +
				// 				'<tr>' +
				// 				'<td style="padding-left: 7px;white-space: nowrap;">CL: <b>' + clarity + '</b></td>' +
				// 				'<td style="white-space: nowrap;padding-left:10px;"><b>' + lab + ' : ' + certi + '</b></td>' +
				// 				'<td rowspan="4" style="text-align: right; padding-left: 5px;"><img src="./assets/uploads/' + stock_id + '.png" style="height: 70px"></td>' +
				// 				'</tr>' +
				// 				'<tr>' +
				// 				'<td style="padding-left: 7px;white-space: nowrap;">Cut: <b>' + cut + '</b></td>' +
				// 				'<td style="white-space: nowrap;padding-left:10px;"><b>' + mea + '</b></td>' +
				// 				'</tr>' +
				// 				'<tr>' +
				// 				'<td style="padding-left: 7px;white-space: nowrap;">Pol: <b>' + pol + '</b></td>' +
				// 				'<td style="white-space: nowrap;padding-left:10px;">TB: <b>' + tb + '%</b></td>' +
				// 				'</tr>' +
				// 				'<tr>' +
				// 				'<td style="padding-left: 7px;white-space: nowrap;">Sym: <b>' + sym + '</b></td>' +
				// 				'<td style="white-space: nowrap;padding-left:10px;">TD: <b>' + dp + '%</b></td>' +
				// 				'</tr>' +
				// 				'<tr>' +
				// 				'<td style="padding-left: 7px;white-space: nowrap;">Flo: <b>' + fl + '</b></td>' +
				// 				'<td style="white-space: nowrap;padding-left:10px;">Ratio: <b>' + ratio + '</b></td>' +
				// 				'<td style="text-align: right;padding-right: 7px;"><b>' + stock_id + '</b></td>' +
				// 				'</tr>' +
				// 				'</tbody>' +
				// 				'</table>' +
				// 				'</div>'
				// 			);
				// 		});
				// 	});

				// 	setTimeout(function() {
				// 		var newWin = window.open("");
				// 		newWin.document.write('<html><style type="text/css">@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");body{font-family: Nunito Sans, Helvetica, Arial, sans-serif;font-size: 10px;}</style><body style="margin-top:-4px;margin-bottom:-4px;">');
				// 		newWin.document.write(document.getElementById('myModal').innerHTML);
				// 		newWin.document.write('</body></html>');
				// 		newWin.document.close();
				// 		newWin.print();
				// 	}, 500);
				// } else {
				// 	Swal.fire("Warning!", "Please Select at least One Record !", "warning");
				// }
			});

			$('.excel_download').click(function() {
				var total_val = 0;
				var pickupid = [];
				$('#render_pickup_done .check_box:checked').each(function() {
					total_val += 1;
					pickupid.push($(this).attr('data-pickupid'));
				})
				if (total_val == 0) {
					Swal.fire("Warning!", "Please Select at least One Record !", "warning");
				} else {
					request_call("{{ url('Logistics/pickupExcelDownload') }}", "pickupid=" + pickupid);
					xhr.done(function(mydata) {
						$('#discont_message').html(mydata.success);
						document.location.href = ("assets/rkdimond_download/" + mydata.file_name);
					});
				}

			});

            $('.generate-pdf').click(function() {
				var total_val = 0;
				var order_id = [];
				var qc_not = 0;
                var direct_ship_validation = 0;
				var generated_by = $(this).data('generated_by');

				$('#render_pickup_done .check_box:checked').each(function() {
					total_val += 1;
					order_id.push($(this).attr('data-order_id'));
                    if($(this).attr('data-qc_com') == "")
                    {
                        qc_not += 1;
                    }
                    if($(this).attr('data-location') == "Direct Ship Hongkong" || $(this).attr('data-location') == "Direct Ship USA"){
                        direct_ship_validation += 1;
                    }
				})
				if (total_val == 0) {
					Swal.fire("Warning!", "Please Select at least One Record !", "warning");
				}
                else if(direct_ship_validation > 0){
                    Swal.fire("Warning!", "Please Remove Direct Shippment Stones!", "warning");
                }
                else if(qc_not > 0) {
                    Swal.fire("Warning!", "Please Do QC Of all Stones!", "warning");
                }
                else{
                    Swal.fire({
                        	width:'70%',
                        	html: `<div class="container">
                        			<div class="row">
                        				<div class="col-md-3">
                        					Export No: <br/>
                        					<input type="text" class="form-control" name="export_no" id="export_no">
                        				</div>
                        				<div class="col-md-3">
                        					Custom Broker Name: <br/>
                        					<select for="broker" id="broker_name" class="form-select">
                        						<option value="B.V.C">B.V.C</option>
                        						<option value="MALCA AMIT-JK">MALCA AMIT-JK</option>
                        					</select>
                        				</div>
                        				<div class="col-md-3">
                        					Pre - Carriage No:- <br/>
                        					<select for="pre-carriage" id="pre_carriage" class="form-select">
                        						<option value="CF"> C & F</option>
                        						<option value="FOB"> F.O.B</option>
                        					</select>
                        				</div>
                        				<div class="col-md-3">
                        					Weight of Box : <br/>
                        					<input type="text" class="form-control" name="weight_box" id="weight_box">
                        				</div>
                        			</div>
                        			<div class="row">
                        				<div class="col-md-3">
                        					Exporter: <br/>
                        					<select for="associate" id="associate" class="form-select">
                        						<?php foreach($associates as $associate){
                        							print_r('<option value="'.$associate->id.'">'.$associate->name.'</option>');
                        						} ?>
                        					</select>
                        				</div>
                        				<div class ="col-md-3">
                        					Consignee: <br/>
                        					<select for="customer" id="customer" class="form-select">
                        						<?php foreach($customers as $customer){
                        							print_r('<option value="'.$customer->user->id.'">'.$customer->user->companyname.'</option>');
                        						} ?>
                        					</select>
                        				</div>
                        				<div class ="col-md-3">
                        					Shipping Charge(IN $): <br/>
                        					<input type="text" id="shipping_charge" value="0"class="form-control">
                        				</div>
                        				<div class ="col-md-3">
                        					<br/>
                        					<input type="checkbox" id="consignment" value='1'><label for="consignment"> Consignment</label>
                        				</div>
                        			</div>
                        		</div>`,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                        	preConfirm: () => {
                                const export_no = Swal.getPopup().querySelector('#export_no').value
                        		const broker_name = Swal.getPopup().querySelector('#broker_name').value
                        		const weight_box = Swal.getPopup().querySelector('#weight_box').value
                        		const shipping_charge = Swal.getPopup().querySelector('#shipping_charge').value

                                if(!export_no){
                                    Swal.showValidationMessage(`Please Enter Export Number`)
                        		}
                                if(isNaN(export_no)){
                                    Swal.showValidationMessage(`Please Enter A Number`)
                        		}

                                if(isNaN(shipping_charge)){
                                    Swal.showValidationMessage(`Please Input Shipping Charge in Number`)
                                }
                        		if(!weight_box){
                                    Swal.showValidationMessage(`Please Enter Weight Of Box`)
                        		}
                        		if(!broker_name){
                                    Swal.showValidationMessage(`Please Select Broker`)
                        		}
                        	},
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Confirm it!',
                        }).then((result) => {
                        	if (result.isConfirmed){
                        		let broker_name = Swal.getPopup().querySelector('#broker_name').value;
                        		let exportno = Swal.getPopup().querySelector('#export_no').value;
                        		let associate = Swal.getPopup().querySelector('#associate').value;
                        		let customer = Swal.getPopup().querySelector('#customer').value;
                        		let shipping_charge = Swal.getPopup().querySelector('#shipping_charge').value;
                        		let pre_carriage = Swal.getPopup().querySelector('#pre_carriage').value;
                        		let weight_box = Swal.getPopup().querySelector('#weight_box').value;
                        		let consignment = Swal.getPopup().querySelector('#consignment').value;
                        		let consignee = 0;
                        		if ($("#consignment").is(':checked')) {
                        			consignee = 1;
                        		}
                        		request_call("{{ url('generate-export') }}", "order_id=" + order_id + "&consignee=" + consignee + "&exportno=" + exportno + "&generated_by=" + generated_by + "&brokername=" + broker_name+ "&associate=" + associate + "&customer=" + customer + "&shipping_charge=" + shipping_charge + "&pre_carriage=" + pre_carriage + "&weight_box=" + weight_box );
                        		xhr.done(function(mydata) {
                                    if(mydata.success){
                                        Swal.fire("Success!", mydata.success,'success').then((result) => {
                                            window.location.reload();
                                        });
                                    }
                                    else{
                                        Swal.fire("Warning!", mydata.warning,'warning');
                                    }
                        		});
                        	};
                        })
                }
			});


			$("#checkAll").click(function(){
				$('#render_pickup_done input:checkbox').not(this).prop('checked', this.checked);
				checkbox_event();
			});

			function Checked_Stone(msg) {
				Swal.fire("Warning!", msg, "warning");
			}

			$('#render_pickup_done').delegate('.main_plus', 'click', function() {
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
				var orders_id = $(this).attr('data-orders_id');
				var certificate = $(this).attr('data-certificate');
				var pickup_id = $(this).attr('data-pickup_id');

				request_call("{{ url('logistics-stonedetails') }}", "orders_id=" + orders_id + "&certificate=" + certificate + "&pickup_id=" + pickup_id);
				xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
					}
				});
			});

			$('#render_pickup_done').delegate('.main_minus', 'click', function() {
				$(this).removeClass("fas fa-minus-square").addClass("fas fa-plus-square");
				$(this).removeClass("main_minus").addClass("main_plus");
				var parent_tr = $(this).parents('tr');
				parent_tr.next("tr.detail_view").remove();
			});

			$("#generatememo").click(function() {
				KTApp.blockPage({
					overlayColor: '#000000',
					state: 'primary',
					message: 'Processing...'
				});

				var total_val = 0;
				var pickupid = [];
				var location = [];
				$('.check_box:checked').each(function() {
					total_val += 1;
					pickupid.push($(this).attr('data-pickupid'));
					location.push($(this).attr('data-location'));
				})

				var unique = location.filter(function(itm, i, a) {
					return i == a.indexOf(itm);
				});

				if (total_val == 0) {
					KTApp.unblockPage();
					Swal.fire("Warning!", "Please Select at least One Record !", "warning");
				} else if(unique.length > 1) {
					KTApp.unblockPage();
					Swal.fire("Warning!", "Please select stone from same location.", "warning");
				} else if(unique[0] != "Surat") {
					KTApp.unblockPage();
					Swal.fire("Warning!", "Please select stone from SURAT location only.", "warning");
				} else {
					request_call("{{ url('Logistics/pickupGenerateMemo') }}", "pickupid=" + pickupid);
					xhr.done(function(mydata) {
						KTApp.unblockPage();
						if(mydata.success)
						{
							Swal.fire("Done", mydata.message, "success").then((result) => {
								window.location.reload();
							});
						}
						else
						{
							Swal.fire("Warning!", mydata.message, "warning");
						}
					});
				}
			});

			$("#generateexport").click(function() {
				KTApp.blockPage({
					overlayColor: '#000000',
					state: 'primary',
					message: 'Processing...'
				});
				var total_val = 0;
				var pickupid = [];
				var location = [];
				$('.check_box:checked').each(function() {
					total_val += 1;
					pickupid.push($(this).attr('data-pickupid'));
					location.push($(this).attr('data-location'));
				})

				var unique = location.filter(function(itm, i, a) {
					return i == a.indexOf(itm);
				});

				if (total_val == 0) {
					KTApp.unblockPage();
					Swal.fire("Warning!", "Please Select at least One Record !", "warning");
				} else if(unique.length > 1) {
					KTApp.unblockPage();
					Swal.fire("Warning!", "Please select stone from same location.", "warning");
				} else if(unique[0] != "Mumbai") {
					KTApp.unblockPage();
					Swal.fire("Warning!", "Please select stone from Mumbai location only.", "warning");
				} else {
					KTApp.unblockPage();
					request_call("{{ url('Logistics/pickupGenerateExport') }}", "pickupid=" + pickupid);
					xhr.done(function(mydata) {
						if(mydata.success)
						{
							Swal.fire("Done", mydata.message, "success").then((result) => {
								window.location.reload();
							});
						}
						else
						{
							Swal.fire("Warning",mydata.message, "Warning");
						}
					});
				}
			});

			$('.check_box').on("change", function() {
				checkbox_event();
			});

			function checkbox_event()
			{
				var carat = 0;
				var stone = 0;
				var cprice = 0;
				var cpricetotal = 0;
				var price = 0;
				var acprice = 0;
				var acpricetotal = 0;
				var aprice = 0;
				$('#render_pickup_done .check_box:checked').each(function() {
					stone += 1;
					carat += parseFloat($(this).data('carat'));
                    cprice +=parseFloat($(this).data('cprice'));
                    price +=parseFloat($(this).data('price'));
					cpricetotal = price / carat;
					acprice += parseFloat($(this).data('acprice'));
					aprice += parseFloat($(this).data('aprice'));
					acpricetotal = aprice / carat;
				});
				$('#totalcarat').html(carat.toFixed(2));
				$('#total_pcs').html(stone);
				$('#totalpercarat').html(cpricetotal.toFixed(2));
				$('#totalamount').html(price.toFixed(2));
				$('#totalApercarat').html(acpricetotal.toFixed(2));
				$('#totalAamount').html(aprice.toFixed(2));
			}

			$('#render_pickup_done').delegate('.pickup_cancel', 'click', function() {
				var ids = $(this).attr('id');
				var done_status = $(this).data('done_status');
				Swal.fire({
					title: "Are you sure?",
					text: "Are you sure you want to cancel?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, Order cancel!"
				}).then(function(result) {
					if (result.value) {
						request_call("{{ url('admin/Logistics/pickupCancelList') }}", "ids=" + ids +"&done_status="+done_status);
						xhr.done(function(mydata) {
							$('#discont_message').html(mydata.success);
							window.location.reload();
						});
					}
					$(this).closest("tr").remove();
				});
			});

			$('#render_pickup_done').delegate('.pickup_done_return', 'click', function() {
				var ids = $(this).attr('id');
				var orders_id = $(this).data('orders_id');
				var return_status = "Pickup_done";
				Swal.fire({
					title: "Are you sure?",
					text: "Are you sure you want to order return?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, Order release!"
				}).then(function(result) {
					if (result.value) {
						KTApp.blockPage({
							overlayColor: '#000000',
							state: 'primary',
							message: 'Processing...'
						});
						request_call("{{ url('Logistics/pickup_return') }}", "ids=" + ids + "&return_status="+return_status + "&orders_id=" + orders_id);
						xhr.done(function(mydata) {
							KTApp.unblockPage();
							$('#discont_message').html(mydata.success);
							window.location.reload();
						});
					}
				});
			});

            $('#render_pickup_done').delegate('.directshipping', 'click', function()
			{
				var location = $(this).data('location');
                Swal.fire({
                    title: "Confirm ?",
                    text: 'Confirmation of '+ location +'!',
                    type: "success",
					icon: "warning",
                    showCancelButton:true,
                    confirmButtonColor: "#00CC00",
                    confirmButtonText: "Yes, Confirm!",
                    cancelButtonText: "No, cancel!",
                    closeOnConfirm: false
                }).then((result) => {
                    if (result.isConfirmed){
                        invoice = $(this).data('invoice');
                        pickupid = $(this).data('pickupid');
						request_call("{{ url('directshippment-confirmation') }}", "location=" + location + "&invoice="+ invoice + "&pickupid=" + pickupid );
                        xhr.done(function(mydata) {
                            if(mydata.success){
                                Swal.fire({
                                    title: "success",
                                    text: mydata.success,
                                    type: "Success",
                                    icon: "success",
                                }).then((result) => {
                                    window.location.reload();
                                });
                            }
                            else{
                                Swal.fire({
                                    title: "Warning",
                                    text: mydata.error,
                                    icon: "Warning",
                                    type: "warning",
                                }).then((result) => {
                                    window.location.reload();
                                });
                            }
                        })
                    }
                })

            })

            $('#render_pickup_done').delegate('.order_a_r', 'click', function()
			{

				var id = $(this).attr('id');
				var certino = $(this).attr('data-certino');
				var customer_id = $(this).attr('data-userid');
				var order_status = $(this).attr('data-status');
				var title = $(this).attr('data-title');

				if(order_status == 'QCREVIEW')
				{
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
														+'<span class="com"></span></br><Span><span class="GFG">100</span> Characters Remaining </span>'
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
							request_call("{{ url('admin-update-qc-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino + "&comment=" + encodeURIComponent(comment));
							xhr.done(function(mydata) {
								$('#header-modal').modal('hide');
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
				} else if (order_status == "UNDOTOQC") {
					Swal.fire({
						title: 'Are you sure you want to Undo The Diamond?',
						// text: "You won't be able to revert this!",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Undo it!',
					}).then((result) => {
						if (result.isConfirmed) {
							request_call("{{ url('admin-update-qc-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino );
							xhr.done(function(mydata) {
								Swal.fire({
									title: "UNDO!",
									text: 'QC Undo successfully...!!',
									type: "success",
								}).then((result) => {
									location.reload();
								});
							});
						}
					});
				}
				else if (order_status == "QCRETURN") {
					Swal.fire({
					title: 'Are you sure you want to Return  This  Diamond To Supplier ?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, Return It!',
					}).then((result) => {
					if (result.isConfirmed) {
						request_call("{{ url('admin-update-qc-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino );
						xhr.done(function(mydata) {
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
		});
	</script>
</body>
</html>
