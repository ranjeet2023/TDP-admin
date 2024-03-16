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
    <style>
        .tooltip-inner {
            background-color: #000 !important;
            color: #fff !important ;
        }
    </style>
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

								<div class="card mb-6">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Order List - Customer</span>
											</h3>
										</div>
                                        <div class="card-toolbar">
                                            <div class="me-4">
                                                <select class="form-select form-select-sm" data-show-subtext="true" data-live-search="true" id="holdfilter" name="holdfilter">
                                                    <option value="">Hold Filter</option>
                                                    <option value="1" {{ (1 == request()->get('hold')) ? 'selected' : '' }}>Hold</option>
                                                </select>
                                            </div>
                                            <div>
                                                <select class="form-select form-select-sm" data-show-subtext="true" data-live-search="true" id="countryddl" name="countryddl">
                                                    <option value="">Country Name</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->country }}" {{ ($country->country == request()->get('country')) ? 'selected' : '' }}>{{ $country->country }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
											<button class="btn btn-danger btn-sm" type="button" onClick="window.location.href='order-list-sales'" style="margin:0 5px 0 5px;">clear</button>
                                            <a href="#" class="btn btn-light btn-primary btn-sm d-flex align-items-center gap-2 gap-lg-3 me-2 " data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <span class="svg-icon svg-icon-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-success text-hover-inverse-success supplier_confirm_popup">Qc Request</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-info text-hover-inverse-info excel_download" data-orders="{{ $orders }}">Excel Download</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-danger text-hover-inverse-danger reverse_diamond">Reverse</span></a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-danger text-hover-inverse-danger release_diamond">Release</span></a>
                                                </div>
                                                @if (Auth::user()->user_type == 1 || Auth::user()->id == 721 || Auth::user()->user_type == 496)
                                                <a href="{{ url('all-order-list')}}" class="btn btn-sm btn-warning">All Order List</a>
                                                @endif
                                            </div>
                                            <button class="btn btn-sm btn-secondary me-2"><span id="total_pcs">0</span></button>
											<button class="btn btn-sm btn-secondary me-2">CT : <span id="totalcarat">0.00</span></button>
											<button class="btn btn-sm btn-secondary me-2">$/ct $ : <span id="totalpercarat">0.00</span></button>
											<button class="btn btn-sm btn-secondary me-2">$ : <span id="totalamount">0.00</span></button>
											<button class="btn btn-sm btn-secondary me-2">$/ct A $ : <span id="totalApercarat">0</button>
											<button class="btn btn-sm btn-secondary me-2">Price A $ : <span id="totalAamount">0</button>
										</div>
									</div>
                                </div>

                                <div class="card mb-6">
                                    <div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Pending From Customer Side</span>
											</h3>
										</div>
                                    </div>
                                    <div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="fw-bolder fs-6 text-gray-800 px-7">
															<th>Customer Status</th>
                                                            <th>Return</th>
                                                            {{-- <th>Internal Confirmation</th> --}}
															<th>Days</th>
                                                            <th>Prority</th>
                                                            <th>Customer</th>
                                                            @if(!empty($permission) && ($permission->full == 1 || (in_array(Auth::user()->user_type, array(1)) || Auth::user()->id ==721) ) )
                                                                <th>Supplier Name</th>
                                                            @endif
                                                            <th>Supplier Status</th>
                                                            <th>Country</th>
															<th>Shape</th>
															<th>SKU</th>
															<th>Order ID</th>
															<th>Ref No</th>
                                                            <th>Carat</th>
															<th>Color</th>
															<th>Clarity</th>
															<th>Cut</th>
															<th>Polish</th>
															<th>Symmetry</th>
                                                            <th>Flour</th>
															<th>Lab</th>
															<th>Certificate</th>
                                                            @if(!empty($permission) && ($permission->full == 1 || in_array(Auth::user()->user_type, array(1))) )
                                                                <th>Sell %</th>
                                                                <th>Sell Price</th>
                                                                <th>Buy %</th>
                                                                <th>Buy Price</th>
                                                                <th>Ex Rate</th>
                                                            @endif
															<th>Date</th>
														</tr>
													</thead>
													<tbody id="render_string">
														@if(!empty($orders))
                                                        	@foreach($orders as $value)
                                                                @if ($value->order_status == 'PENDING')
                                                                    @php
                                                                        $color = '';
                                                                        if (in_array($value->orders_id, $getrowcheck)) {
                                                                            $color = "text-success";
                                                                        }
                                                                    @endphp
                                                                    <tr class="{{ $color }}">
                                                                        <td>
                                                                            <div class="position-relative ps-6 pe-3 py-2">
                                                                            <label class="checkbox justify-content-center">
                                                                                <input class="check_box" data-orders_id="<?= $value->orderdetail->orders_id ?>" data-customer_id="<?= $value->customer_id ?>"
                                                                                        data-ref_no='<?= $value->orderdetail->ref_no ?>'
                                                                                        data-certi_no='<?= $value->orderdetail->certificate_no ?>'
                                                                                        data-carat='<?= $value->orderdetail->carat ?>'  data-price='<?= $value->sale_price ?>' data-discount="<?= $value->sale_discount ?>"  data-aprice='<?= $value->buy_price ?>' name="multiaction" value="<?= $value->orders_id ?>" type="checkbox">
                                                                                    <span></span>
                                                                            </label>
                                                                            <i class="fa fa-plus" data-id="{{ $value->orders_id }}" data-customer_id="{{ $value->customer_id }}"></i>
                                                                            @if($value->order_status == "PENDING")
                                                                                <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                                                                    <span class="mb-1 text-primary fw-bolder">{{ $value->hold == 1 ? 'Hold ' : '' }}</span>
                                                                                    <span class="mb-1 text-info fw-bolder">Pending</span><br/>
                                                                            @elseif($value->order_status == "APPROVED")
                                                                                <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-success"></div>
                                                                                    <span class="mb-1 text-primary fw-bolder">{{ $value->hold == 1 ? 'Hold ' : '' }}</span>
                                                                                    <span class="mb-1 text-success fw-bolder">Approved</span><br/>
                                                                            @elseif($value->order_status == "REJECT")
                                                                                <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-danger"></div>
                                                                                    <span class="mb-1 text-primary fw-bolder">{{ $value->hold == 1 ? 'Hold ' : '' }}</span>
                                                                                    <span class="mb-1 text-danger fw-bolder">Rejected</span><br/>
                                                                            @endif
                                                                            @if ($value->pickups != null)

                                                                                @if($value->pickups->status == "PENDING")
                                                                                    <span class="mb-1 text-dark fw-bolder">Requested For QC</span>
                                                                                @elseif($value->pickups->status == "PICKUP_DONE" && $value->pickups->export_number == "" && $value->qc_list == null)
                                                                                    <span class="mb-1 text-warning fw-bolder">On Hand</span>
                                                                                @elseif($value->pickups->status == "PICKUP_DONE" && $value->pickups->export_number == "" && $value->qc_list != null)
                                                                                    <span class="mb-1 text-primary fw-bolder">Done QC</span>
                                                                                @elseif($value->pickups->status == "QCRETURN")
                                                                                    <span class="mb-1 text-danger fw-bolder">QC Done & Return </span>
                                                                                @elseif($value->pickups->status == "IN_TRANSIT")
                                                                                    <span class="mb-1 text-warning fw-bolder">In Transit</span>
                                                                                @elseif($value->pickups->status == "REACHED")
                                                                                    <span class="mb-1 text-success fw-bolder">SENT TO CUSTOMER</span>
                                                                                @endif
                                                                            @endif
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-danger fw-bolder">{{ ($value->return_price > 0.00) ? ( (Auth::user()->user_type == 1) ? 'R' : 'R') : ''; }}</td>
                                                                        {{-- <td>
                                                                            @if($value->internal_confirmation == "PENDING")
                                                                                <span class="mb-1 text-info fw-bolder">{{ $value->internal_confirmation }}</span><br/>
                                                                                <button class="btn btn-sm btn-icon btn-primary me-1 status_internal_confirmation" data-order="{{ $value->orders_id }}"data-status="APPROVED" ><i class="fa fa-check"></i></button>
                                                                            <button class="btn btn-sm btn-icon btn-danger status_internal_confirmation" data-order="{{ $value->orders_id }}" data-status="REJECT" ><i class="fa fa-times"></i></button></td>
                                                                            @elseif($value->internal_confirmation == "APPROVED")
                                                                                <span class="mb-1 text-success fw-bolder">Approved</span>
                                                                            @elseif($value->internal_confirmation == "REJECT")
                                                                                <span class="mb-1 text-danger fw-bolder">Rejected</span>
                                                                            @endif
                                                                        </td> --}}
                                                                        <td>
                                                                            @if($value->hold_at != null || $value->approved_at != null)
                                                                            @if($value->hold_at != null)
                                                                                <span class="badge badge-circle badge-primary badge-lg">{{ intval((time() - strtotime($value->hold_at))/(60*60*24)) }}</span><br/>
                                                                            @endif
                                                                            @if($value->approved_at != null)
                                                                                <span class="badge badge-circle badge-success badge-lg mt-3">{{ intval((time() - strtotime($value->approved_at))/(60*60*24)) }}</span>
                                                                            @endif
                                                                            @else
                                                                                <span class="badge badge-circle badge-success badge-lg mt-3">{{ intval((time() - strtotime($value->created_at))/(60*60*24)) }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @php
                                                                                $priority_color = '';
                                                                                $priority = $value->priority;
                                                                                if ($value->priority == 'low'){
                                                                                    $priority_color = 'svg-icon-gray-400';
                                                                                }
                                                                                elseif($value->priority == 'medium'){
                                                                                    $priority_color = 'svg-icon-gray-800';
                                                                                }
                                                                                elseif($value->priority == 'high'){
                                                                                    $priority_color = 'svg-icon-danger';
                                                                                }
                                                                            @endphp
                                                                            <span class="svg-icon svg-icon-2hx {{ $priority_color }}"data-bs-toggle="tooltip" data-bs-original-title="Prority: {{ $priority }}">
                                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path opacity="0.3" d="M20.9 12.9C20.3 12.9 19.9 12.5 19.9 11.9C19.9 11.3 20.3 10.9 20.9 10.9H21.8C21.3 6.2 17.6 2.4 12.9 2V2.9C12.9 3.5 12.5 3.9 11.9 3.9C11.3 3.9 10.9 3.5 10.9 2.9V2C6.19999 2.5 2.4 6.2 2 10.9H2.89999C3.49999 10.9 3.89999 11.3 3.89999 11.9C3.89999 12.5 3.49999 12.9 2.89999 12.9H2C2.5 17.6 6.19999 21.4 10.9 21.8V20.9C10.9 20.3 11.3 19.9 11.9 19.9C12.5 19.9 12.9 20.3 12.9 20.9V21.8C17.6 21.3 21.4 17.6 21.8 12.9H20.9Z" fill="currentColor"/>
                                                                                    <path d="M16.9 10.9H13.6C13.4 10.6 13.2 10.4 12.9 10.2V5.90002C12.9 5.30002 12.5 4.90002 11.9 4.90002C11.3 4.90002 10.9 5.30002 10.9 5.90002V10.2C10.6 10.4 10.4 10.6 10.2 10.9H9.89999C9.29999 10.9 8.89999 11.3 8.89999 11.9C8.89999 12.5 9.29999 12.9 9.89999 12.9H10.2C10.4 13.2 10.6 13.4 10.9 13.6V13.9C10.9 14.5 11.3 14.9 11.9 14.9C12.5 14.9 12.9 14.5 12.9 13.9V13.6C13.2 13.4 13.4 13.2 13.6 12.9H16.9C17.5 12.9 17.9 12.5 17.9 11.9C17.9 11.3 17.5 10.9 16.9 10.9Z" fill="currentColor"/>
                                                                                </svg>
                                                                            </span>
                                                                        </td>
                                                                        <td>{{ $value->user->companyname }}</td>
                                                                        @if(!empty($permission) && ($permission->full == 1 ||  (in_array(Auth::user()->user_type, array(1)) || Auth::user()->id ==721) ) )
                                                                            <td>{{ $value->orderdetail->supplier_name }}
                                                                                <a href="https://wa.me/?text={{ $value->orderdetail->shape }} {{ $value->orderdetail->ref_no }} {{ $value->orderdetail->carat }} {{ $value->orderdetail->color }} {{ $value->orderdetail->clarity }} {{ $value->orderdetail->cut }} {{ $value->orderdetail->polish }} {{ $value->orderdetail->symmetry }} {{ $value->orderdetail->fluorescence }} {{ $value->orderdetail->lab }} {{ $value->certificate_no }}" target="_blank">
                                                                                    <svg width="24" height="24" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg">
                                                                                        <g opacity="0.2">
                                                                                            <path d="M128.00049,32A96.02264,96.02264,0,0,0,45.4292,176.99807l.00049-.00061-9.47315,33.15661a8,8,0,0,0,9.89014,9.8899l33.15625-9.47327v.001A96.00624,96.00624,0,1,0,128.00049,32ZM152.11377,183.9999A80.0001,80.0001,0,0,1,72,103.88625,27.97634,27.97634,0,0,1,100,76h0a6.89208,6.89208,0,0,1,5.98438,3.4729l11.6914,20.45923a8.00129,8.00129,0,0,1-.08594,8.08521l-9.38916,15.64843h0a48.18271,48.18271,0,0,0,24.1333,24.13379l.00049-.00012,15.64795-9.389a8.00033,8.00033,0,0,1,8.08545-.08594l20.459,11.69092A6.89223,6.89223,0,0,1,180,156,28.081,28.081,0,0,1,152.11377,183.9999Z"/>
                                                                                        </g>
                                                                                        <path d="M128.00049,24a104.0281,104.0281,0,0,0-91.189,154.041l-8.54687,29.915A15.99944,15.99944,0,0,0,48.044,227.73635l29.916-8.54688A104.00728,104.00728,0,1,0,128.00049,24Zm0,192a87.86347,87.86347,0,0,1-44.90772-12.30566,8.00324,8.00324,0,0,0-6.28759-.81641l-33.15674,9.47363,9.47265-33.15625a7.99679,7.99679,0,0,0-.8164-6.28613A88.01132,88.01132,0,1,1,128.00049,216Zm52.4956-72.93066L160.03662,131.378a16.01881,16.01881,0,0,0-16.17041.17285l-11.85107,7.11133a40.03607,40.03607,0,0,1-14.67627-14.67676l7.11084-11.85156a16.01674,16.01674,0,0,0,.17187-16.16992L112.93066,75.503A14.92643,14.92643,0,0,0,100,68a36.01385,36.01385,0,0,0-36,35.876A87.99949,87.99949,0,0,0,151.999,192c.042,0,.09473.02344.126,0A36.01427,36.01427,0,0,0,188,156,14.9238,14.9238,0,0,0,180.49609,143.06936ZM152.10254,176H152a72.00036,72.00036,0,0,1-72-72.10254A19.99027,19.99027,0,0,1,99.36328,84.00979l11.36621,19.8916-9.38867,15.64844a7.99972,7.99972,0,0,0-.43652,7.39746,56.03179,56.03179,0,0,0,28.14892,28.14843,7.99583,7.99583,0,0,0,7.397-.43652l15.64843-9.38867,19.8916,11.36621A19.99027,19.99027,0,0,1,152.10254,176Z"/>
                                                                                    </svg>
                                                                                </a>
                                                                            </td>
                                                                        @endif
                                                                        <td nowrap>
                                                                            @if($value->supplier_status == "PENDING")
                                                                                <span class="mb-1 text-info fw-bolder">Pending</span><br/>
                                                                                <button class="btn btn-sm btn-icon btn-primary me-1 order_approve_supplier" data-order="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="APPROVED" ><i class="fa fa-check"></i></button>
                                                                                <button class="btn btn-sm btn-icon btn-danger order_reject_supplier" data-reject="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="REJECT" ><i class="fa fa-times"></i></button></td>
                                                                            @elseif($value->supplier_status == "APPROVED")
                                                                                <span class="mb-1 text-success fw-bolder">Approved</span>
                                                                            @elseif($value->supplier_status == "REJECT")
                                                                                <span class="mb-1 text-danger fw-bolder">Rejected</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $value->orderdetail->country }}</td>
                                                                        <td>{{ $value->orderdetail->shape }}</td>
                                                                        <td>{{ $value->orderdetail->id }}</td>
                                                                        <td>{{ $value->orders_id }}</td>
                                                                        <td>{{ $value->ref_no }}</td>
                                                                        <td>{{ $value->orderdetail->carat }}</td>
                                                                        <td>{{ $value->orderdetail->color }}</td>
                                                                        <td>{{ $value->orderdetail->clarity }}</td>
                                                                        <td>{{ $value->orderdetail->cut }}</td>
                                                                        <td>{{ $value->orderdetail->polish }}</td>
                                                                        <td>{{ $value->orderdetail->symmetry }}</td>
                                                                        <td>{{ $value->orderdetail->fluorescence }}</td>
                                                                        <td>{{ $value->orderdetail->lab }}</td>
                                                                        <td>{{ $value->certificate_no }}</td>
                                                                        @if(!empty($permission) && ($permission->full == 1 || in_array(Auth::user()->user_type, array(1))) )
                                                                            <td>{{ $value->sale_discount }}</td>
                                                                            <td>{{ $value->sale_price }}</td>
                                                                            <td>{{ $value->buy_discount }}</td>
                                                                            <td>{{ $value->buy_price }}</td>
                                                                            <td><input class="form-control exchange_rate" id="exchange_<?= $value->orders_id ?>" data-id="<?= $value->orders_id ?>" value="<?= $value->exchange_rate ?>" type="number" size="4" style="min-width:100px;"></td>
                                                                        @endif
                                                                        <td>{{ $value->orderdetail->created_at }}</td>
                                                                    </tr>
                                                                @endif
															@endforeach
														@endif
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

								<div class="card">
                                    <div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="fw-bolder fs-6 text-gray-800 px-7">
															<th>Customer Status</th>
                                                            <th>Return</th>
                                                            {{-- <th>Internal Confirmation</th> --}}
															<th>Prority</th>
															<th>Days</th>
                                                            <th>Customer</th>
                                                            @if(!empty($permission) && ($permission->full == 1 || (in_array(Auth::user()->user_type, array(1)) || Auth::user()->id ==721) ) )
                                                                <th>Supplier Name</th>
                                                            @endif
                                                            <th>Supplier Status</th>
                                                            <th>Country</th>
															<th>Shape</th>
															<th>SKU</th>
															<th>Order ID</th>
															<th>Ref No</th>
                                                            <th>Carat</th>
															<th>Color</th>
															<th>Clarity</th>
															<th>Cut</th>
															<th>Polish</th>
															<th>Symmetry</th>
                                                            <th>Flour</th>
															<th>Lab</th>
															<th>Certificate</th>
                                                            @if(!empty($permission) && ($permission->full == 1 || in_array(Auth::user()->user_type, array(1))) )
                                                                <th>Sell %</th>
                                                                <th>Sell Price</th>
                                                                <th>Buy %</th>
                                                                <th>Buy Price</th>
                                                                <th>Ex Rate</th>
                                                            @endif
															<th>Date</th>
														</tr>
													</thead>
													<tbody id="render_string">
														@if(!empty($orders))
                                                        	@foreach($orders as $value)
                                                            @php
                                                                $color = '';
                                                                if (in_array($value->orders_id, $getrowcheck)) {
                                                                    $color = "text-success";
                                                                }
                                                            @endphp
															<tr class="{{ $color }}">
																<td>
                                                                    <div class="position-relative ps-6 pe-3 py-2">
																	<label class="checkbox justify-content-center">
																		<input class="check_box" data-stone={!! 1 !!} data-orders_id="<?= $value->orderdetail->orders_id ?>" data-customer_id="<?= $value->customer_id ?>"
																				data-ref_no='<?= $value->orderdetail->ref_no ?>'
																				data-certi_no='<?= $value->orderdetail->certificate_no ?>'
																				data-carat='<?= $value->orderdetail->carat ?>'  data-price='<?= $value->sale_price ?>' data-discount="<?= $value->sale_discount ?>"  data-aprice='<?= $value->buy_price ?>' name="multiaction" value="<?= $value->orders_id ?>" type="checkbox">
																			<span></span>
																	</label>
                                                                    <i class="fa fa-plus" data-id="{{ $value->orders_id }}" data-customer_id="{{ $value->customer_id }}"></i>
																	@if($value->order_status == "PENDING")
																		<div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                                                            <span class="mb-1 text-primary fw-bolder">{{ $value->hold == 1 ? 'Hold ' : '' }}</span>
                                                                            <span class="mb-1 text-info fw-bolder">Pending</span><br/>
																	@elseif($value->order_status == "APPROVED")
																		<div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-success"></div>
                                                                            <span class="mb-1 text-primary fw-bolder">{{ $value->hold == 1 ? 'Hold ' : '' }}</span>
                                                                            <span class="mb-1 text-success fw-bolder">Approved</span><br/>
																	@elseif($value->order_status == "REJECT")
																		<div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-danger"></div>
                                                                            <span class="mb-1 text-primary fw-bolder">{{ $value->hold == 1 ? 'Hold ' : '' }}</span>
                                                                            <span class="mb-1 text-danger fw-bolder">Rejected</span><br/>
																	@endif
                                                                    @if ($value->pickups != null)

                                                                        @if($value->pickups->status == "PENDING")
                                                                            <span class="mb-1 text-dark fw-bolder">Requested For QC</span>
                                                                        @elseif($value->pickups->status == "PICKUP_DONE" && $value->pickups->export_number == "" && $value->qc_list == null)
                                                                            <span class="mb-1 text-warning fw-bolder">On Hand</span>
                                                                        @elseif($value->pickups->status == "PICKUP_DONE" && $value->pickups->export_number == "" && $value->qc_list != null)
                                                                            <span class="mb-1 text-primary fw-bolder">Done QC</span>
                                                                        @elseif($value->pickups->status == "QCRETURN")
                                                                            <span class="mb-1 text-danger fw-bolder">QC Done & Return </span>
                                                                        @elseif($value->pickups->status == "IN_TRANSIT")
                                                                            <span class="mb-1 text-warning fw-bolder">In Transit</span>
                                                                        @elseif($value->pickups->status == "REACHED")
                                                                            <span class="mb-1 text-success fw-bolder">SENT TO CUSTOMER</span>
                                                                        @endif
                                                                    @endif
                                                                    </div>
																</td>
                                                                <td class="text-danger fw-bolder">{{ ($value->return_price > 0.00) ? ( (Auth::user()->user_type == 1) ? 'R' : 'R') : ''; }}</td>
																{{-- <td>
                                                                    @if($value->internal_confirmation == "PENDING")
																		<span class="mb-1 text-info fw-bolder">{{ $value->internal_confirmation }}</span><br/>
                                                                        <button class="btn btn-sm btn-icon btn-primary me-1 status_internal_confirmation" data-order="{{ $value->orders_id }}"data-status="APPROVED" ><i class="fa fa-check"></i></button>
                                                                    <button class="btn btn-sm btn-icon btn-danger status_internal_confirmation" data-order="{{ $value->orders_id }}" data-status="REJECT" ><i class="fa fa-times"></i></button></td>
																	@elseif($value->internal_confirmation == "APPROVED")
																		<span class="mb-1 text-success fw-bolder">Approved</span>
																	@elseif($value->internal_confirmation == "REJECT")
																		<span class="mb-1 text-danger fw-bolder">Rejected</span>
																	@endif
                                                                </td> --}}
                                                                <td>
                                                                    @if($value->hold_at != null)
                                                                        <span class="badge badge-circle badge-primary badge-lg">{{ intval((time() - strtotime($value->hold_at))/(60*60*24)) }}</span><br/>
                                                                    @endif
                                                                    @if($value->approved_at != null)
                                                                        <span class="badge badge-circle badge-success badge-lg mt-3">{{ intval((time() - strtotime($value->approved_at))/(60*60*24)) }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $priority_color = '';
                                                                        $priority = $value->priority;
                                                                        if ($value->priority == 'low'){
                                                                            $priority_color = 'svg-icon-gray-400';
                                                                        }
                                                                        elseif($value->priority == 'medium'){
                                                                            $priority_color = 'svg-icon-gray-800';
                                                                        }
                                                                        elseif($value->priority == 'high'){
                                                                            $priority_color = 'svg-icon-danger';
                                                                        }
                                                                    @endphp
                                                                    <span class="svg-icon svg-icon-2hx {{ $priority_color }}"data-bs-toggle="tooltip" data-bs-original-title="Prority: {{ $priority }}">
                                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path opacity="0.3" d="M20.9 12.9C20.3 12.9 19.9 12.5 19.9 11.9C19.9 11.3 20.3 10.9 20.9 10.9H21.8C21.3 6.2 17.6 2.4 12.9 2V2.9C12.9 3.5 12.5 3.9 11.9 3.9C11.3 3.9 10.9 3.5 10.9 2.9V2C6.19999 2.5 2.4 6.2 2 10.9H2.89999C3.49999 10.9 3.89999 11.3 3.89999 11.9C3.89999 12.5 3.49999 12.9 2.89999 12.9H2C2.5 17.6 6.19999 21.4 10.9 21.8V20.9C10.9 20.3 11.3 19.9 11.9 19.9C12.5 19.9 12.9 20.3 12.9 20.9V21.8C17.6 21.3 21.4 17.6 21.8 12.9H20.9Z" fill="currentColor"/>
                                                                            <path d="M16.9 10.9H13.6C13.4 10.6 13.2 10.4 12.9 10.2V5.90002C12.9 5.30002 12.5 4.90002 11.9 4.90002C11.3 4.90002 10.9 5.30002 10.9 5.90002V10.2C10.6 10.4 10.4 10.6 10.2 10.9H9.89999C9.29999 10.9 8.89999 11.3 8.89999 11.9C8.89999 12.5 9.29999 12.9 9.89999 12.9H10.2C10.4 13.2 10.6 13.4 10.9 13.6V13.9C10.9 14.5 11.3 14.9 11.9 14.9C12.5 14.9 12.9 14.5 12.9 13.9V13.6C13.2 13.4 13.4 13.2 13.6 12.9H16.9C17.5 12.9 17.9 12.5 17.9 11.9C17.9 11.3 17.5 10.9 16.9 10.9Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </td>
                                                                <td>{{ $value->user->companyname }}</td>
																@if(!empty($permission) && ($permission->full == 1 ||  (in_array(Auth::user()->user_type, array(1)) || Auth::user()->id ==721) ) )
                                                                    <td>{{ $value->orderdetail->supplier_name }}
                                                                        <a href="https://wa.me/?text={{ $value->orderdetail->shape }} {{ $value->orderdetail->ref_no }} {{ $value->orderdetail->carat }} {{ $value->orderdetail->color }} {{ $value->orderdetail->clarity }} {{ $value->orderdetail->cut }} {{ $value->orderdetail->polish }} {{ $value->orderdetail->symmetry }} {{ $value->orderdetail->fluorescence }} {{ $value->orderdetail->lab }} {{ $value->certificate_no }}" target="_blank">
                                                                            <svg width="24" height="24" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg">
                                                                                <g opacity="0.2">
                                                                                    <path d="M128.00049,32A96.02264,96.02264,0,0,0,45.4292,176.99807l.00049-.00061-9.47315,33.15661a8,8,0,0,0,9.89014,9.8899l33.15625-9.47327v.001A96.00624,96.00624,0,1,0,128.00049,32ZM152.11377,183.9999A80.0001,80.0001,0,0,1,72,103.88625,27.97634,27.97634,0,0,1,100,76h0a6.89208,6.89208,0,0,1,5.98438,3.4729l11.6914,20.45923a8.00129,8.00129,0,0,1-.08594,8.08521l-9.38916,15.64843h0a48.18271,48.18271,0,0,0,24.1333,24.13379l.00049-.00012,15.64795-9.389a8.00033,8.00033,0,0,1,8.08545-.08594l20.459,11.69092A6.89223,6.89223,0,0,1,180,156,28.081,28.081,0,0,1,152.11377,183.9999Z"/>
                                                                                </g>
                                                                                <path d="M128.00049,24a104.0281,104.0281,0,0,0-91.189,154.041l-8.54687,29.915A15.99944,15.99944,0,0,0,48.044,227.73635l29.916-8.54688A104.00728,104.00728,0,1,0,128.00049,24Zm0,192a87.86347,87.86347,0,0,1-44.90772-12.30566,8.00324,8.00324,0,0,0-6.28759-.81641l-33.15674,9.47363,9.47265-33.15625a7.99679,7.99679,0,0,0-.8164-6.28613A88.01132,88.01132,0,1,1,128.00049,216Zm52.4956-72.93066L160.03662,131.378a16.01881,16.01881,0,0,0-16.17041.17285l-11.85107,7.11133a40.03607,40.03607,0,0,1-14.67627-14.67676l7.11084-11.85156a16.01674,16.01674,0,0,0,.17187-16.16992L112.93066,75.503A14.92643,14.92643,0,0,0,100,68a36.01385,36.01385,0,0,0-36,35.876A87.99949,87.99949,0,0,0,151.999,192c.042,0,.09473.02344.126,0A36.01427,36.01427,0,0,0,188,156,14.9238,14.9238,0,0,0,180.49609,143.06936ZM152.10254,176H152a72.00036,72.00036,0,0,1-72-72.10254A19.99027,19.99027,0,0,1,99.36328,84.00979l11.36621,19.8916-9.38867,15.64844a7.99972,7.99972,0,0,0-.43652,7.39746,56.03179,56.03179,0,0,0,28.14892,28.14843,7.99583,7.99583,0,0,0,7.397-.43652l15.64843-9.38867,19.8916,11.36621A19.99027,19.99027,0,0,1,152.10254,176Z"/>
                                                                            </svg>
                                                                        </a>
                                                                    </td>
                                                                @endif
                                                                <td nowrap>
                                                                    @if($value->supplier_status == "PENDING")
																		<span class="mb-1 text-info fw-bolder">Pending</span><br/>
                                                                        <button class="btn btn-sm btn-icon btn-primary me-1 order_approve_supplier" data-order="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="APPROVED" ><i class="fa fa-check"></i></button>
                                                                        <button class="btn btn-sm btn-icon btn-danger order_reject_supplier" data-reject="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="REJECT" ><i class="fa fa-times"></i></button></td>
																	@elseif($value->supplier_status == "APPROVED")
																		<span class="mb-1 text-success fw-bolder">Approved</span>
																	@elseif($value->supplier_status == "REJECT")
																		<span class="mb-1 text-danger fw-bolder">Rejected</span>
																	@endif
                                                                </td>
																<td>{{ $value->orderdetail->country }}</td>
																<td>{{ $value->orderdetail->shape }}</td>
																<td>{{ $value->orderdetail->id }}</td>
                                                                <td>{{ $value->orders_id }}</td>
                                                                <td>{{ $value->ref_no }}</td>
                                                                <td>{{ $value->orderdetail->carat }}</td>
																<td>{{ $value->orderdetail->color }}</td>
																<td>{{ $value->orderdetail->clarity }}</td>
																<td>{{ $value->orderdetail->cut }}</td>
																<td>{{ $value->orderdetail->polish }}</td>
																<td>{{ $value->orderdetail->symmetry }}</td>
                                                                <td>{{ $value->orderdetail->fluorescence }}</td>
																<td>{{ $value->orderdetail->lab }}</td>
																<td>{{ $value->certificate_no }}</td>
                                                                @if(!empty($permission) && ($permission->full == 1 || in_array(Auth::user()->user_type, array(1))) )
                                                                    <td>{{ $value->sale_discount }}</td>
                                                                    <td>{{ $value->sale_price }}</td>
                                                                    <td>{{ $value->buy_discount }}</td>
                                                                    <td>{{ $value->buy_price }}</td>
                                                                    <td><input class="form-control exchange_rate" id="exchange_<?= $value->orders_id ?>" data-id="<?= $value->orders_id ?>" value="<?= $value->exchange_rate ?>" type="number" size="4" style="min-width:100px;"></td>
                                                                @endif
																<td>{{ $value->orderdetail->created_at }}</td>
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
			$('#countryddl').change(function(e){
				var country = $('#countryddl').val();
				location.href = "{{ url('order-list-sales') }}?country="+country;
			});
            $('#holdfilter').change(function(e){
				var hold = $('#holdfilter').val();
				location.href = "{{ url('order-list-sales') }}?hold="+hold;
			});

            $('.check_box').change(function(e){
				var stone = 0;
				var Carat = 0;
				var PerCarat = 0;
				var Price = 0;
				var APerCarat = 0;
				var APrice = 0;
				$('.check_box:checked').each(function() {
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
                $('#totalApercarat').html(APerCarat.toFixed(2));
                $('#totalAamount').html(APrice.toFixed(2));

				totalstone = stone;
				TotalCarat = Carat.toFixed(2);
				TotalPerCarat = PerCarat.toFixed(2);
				TotalPrice = Price.toFixed(2);
				TotalAPerCarat = APerCarat.toFixed(2);
				TotalAPrice = APrice.toFixed(2);
			});

            $('#kt_content_container').delegate('.excel_download', 'click', function() {
                var id = [];
                $(":checkbox").each(function() {
					id.push($(this).val());
				});
                request_call("{{ url('all-order-excel-download')}}", "id=" + id );
                xhr.done(function(mydata) {
                    document.location.href = ("uploads/" + mydata.file_name);
                });
            })

            // $('#render_string').delegate('.status_internal_confirmation', 'click', function() {
            //     let order_id = $(this).attr('data-order');
            //     let status = $(this).attr('data-status');
            //     if(status == 'APPROVED'){
            //         Swal.fire({
            //             title: 'Are you sure you want to Approve Confirmation?',
            //             text: "You won't be able to revert this!",
            //             icon: 'warning',
            //             showCancelButton: true,
            //             confirmButtonColor: '#3085d6',
            //             cancelButtonColor: '#d33',
            //             confirmButtonText: 'Yes, Approve it!',
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 request_call("{{ url('admin-internal-confirmation')}}", "order_id=" + order_id + "&status=" + status);
            //                 xhr.done(function(mydata) {
            //                     Swal.fire({
            //                         title: "Approved",
            //                         text: 'Approved successfully...!!',
            //                         type: "success",
            //                         icon: "success",
            //                     }).then((result) => {
            //                         location.reload();
            //                     });
            //                 });
            //             }
            //         });
            //     }
            //     else{
            //         Swal.fire({
            //             title: 'Are you sure you want to Reject Confirmation?',
            //             text: "You won't be able to revert this!",
            //             icon: 'warning',
            //             showCancelButton: true,
            //             confirmButtonColor: '#3085d6',
            //             cancelButtonColor: '#d33',
            //             confirmButtonText: 'Yes, Reject it!',
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 request_call("{{ url('admin-internal-confirmation')}}", "order_id=" + order_id + "&status=" + status);
            //                 xhr.done(function(mydata) {
            //                     Swal.fire({
            //                         title: "Rejected",
            //                         text: 'Rejected successfully...!!',
            //                         type: "error",
            //                         icon: "error",
            //                     }).then((result) => {
            //                         location.reload();
            //                     });
            //                 });
            //             }
            //         });
            //     }
            // })

            $('#kt_content_container').delegate('.order_approve_supplier', 'click', function(event) {
                let order_id = $(this).attr('data-order');
                let certi_no = $(this).attr('data-certino');
                // let customer_id = $(this).attr('data-userid');
                let status = $(this).attr('data-status');
                Swal.fire({
                    heightAuto: false,
                    width: '50%' ,
                    html:`<div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="float-start">Stone Location</label>
                                <select class="form-select" id="stonelocation" >
                                    <option value="">Stone Location</option>
                                    <option value="surat">SURAT</option>
                                    <option value="mumbai">MUMBAI</option>
                                    <option value="hongkong">HONG KONG</option>
                                    <option value="usa">USA</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="float-start">Certificate Location</label>
                                <select class="form-select" id="certificatelocation">
                                    <option value="">Certificate Location</option>
                                    <option value="surat">SURAT</option>
                                    <option value="mumbai">MUMBAI</option>
                                    <option value="hongkong">HONG KONG</option>
                                    <option value="usa">USA</option>
                                </select>
                            </div>
                        </div>
                        <div class="row m-2">
                            <div class="col-md-6">
                                <label class="float-start">No BGM :</label>
                                <label for="yes">YES</label>
                                <input type="radio" name="bgm" id="yes-bgm" value="YES">
                                <label for="no">NO </label>
                                <input type="radio" name="bgm" id="no-bgm" value="NO" checked>
                            </div>
                            <div class="col-md-6">
                                <label class="float-start">Eye Clean :</label>
                                <label for="YES">YES</label>
                                <input type="radio" name="eyeclean" id="yes-eye-clean" value="YES" checked>
                                <label for="NO">NO</label>
                                <input type="radio" name="eyeclean" id="no-eye-clean" value="NO" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="float-start" >Brown</label>
                                <select class="form-select" id="brown">
                                    <option value="">Please Select</option>
                                    <option value="none" >None</option>
                                    <option value="light">Light</option>
                                    <option value="medimu">Medium</option>
                                    <option value="heavy">Heavy</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="float-start" id>Green</label>
                                <select class="form-select" id="green">
                                <option value="">Please Select</option>
                                    <option value="none">None</option>
                                    <option value="light">Light</option>
                                    <option value="medimum">Medium</option>
                                    <option value="heavy">Heavy</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="float-start">Milky</label>
                                <select class="form-select" id="milky">
                                <option value="">Please Select</option>
                                    <option value="none" >None</option>
                                    <option value="light">Light</option>
                                    <option value="medium">Medium</option>
                                    <option value="heavy">Heavy</option>
                                </select>
                            </div>
                        <div>
                        <div class="row mt-3">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="comment"></textarea>
                                    <label for="floatingTextarea">Comments</label>
                                </div>
                        </div>
                    </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Approve',
                    preConfirm: () => {
                        const stonelocation = Swal.getPopup().querySelector('#stonelocation').value
                        const certificatelocation = Swal.getPopup().querySelector('#certificatelocation').value
                        // const bgm = Swal.getPopup().querySelector('#bgm').checked
                        // const eyeclean = Swal.getPopup().querySelector('#eyeclean').checked
                        const milky = Swal.getPopup().querySelector('#milky').value
                        const brown = Swal.getPopup().querySelector('#brown').value
                        const green = Swal.getPopup().querySelector('#green').value
                        const comment = Swal.getPopup().querySelector('#comment').value

                        if(!comment)
                        {
                            Swal.showValidationMessage(`Please Comment what You want`)
                        }
                        if(!green)
                        {
                            Swal.showValidationMessage(`Please Select Green`)
                        }
                        if(!brown)
                        {
                            Swal.showValidationMessage(`Please Select Brown`)
                        }
                        if(!milky)
                        {
                            Swal.showValidationMessage(`Please Select Milky`)
                        }
                        if (!certificatelocation) {
                        Swal.showValidationMessage(`Please Select Certificate Location`)
                        }
                        if (!stonelocation) {
                        Swal.showValidationMessage(`Please Select Stone Location `)
                        }
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        let stonelocation = $('#stonelocation').val();
                        let certificatelocation = $('#certificatelocation').val();
                        let bgm = $("input[name='bgm']:checked").val();
                        let eyeclean = $("input[name='eyeclean']:checked").val();
                        let milky = $('#milky').val();
                        let brown = $('#brown').val();
                        let green = $('#green').val();
                        let comment = $('#comment').val();
                        // request_call("{{ url('supplier-order-status')}}","order_id=" + order_id +"&certi_no=" + certi_no + "&status=" + status +"&comment="+comment);
                        request_call("{{ url('update-enquiry-list')}}", "order_id=" + order_id +"&certi_no=" + certi_no + "&status=" + status +"&comment="+comment +"&milky="+milky +"&brown="+brown+"&green="+green+"&stonelocation="+stonelocation+"&certificatelocation="+certificatelocation+"&bgm="+bgm+"&eyeclean="+eyeclean);
                        xhr.done(function(mydata) {
                            Swal.fire({
                                text: 'Approve!',
                                type: "success",
                                icon: 'success',
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });
            });

            $('#kt_content_container').delegate('.order_reject_supplier', 'click', function(event) {
                let order_id = $(this).attr('data-reject');
                let certi_no = $(this).attr('data-certino');
                // let customer_id = $(this).attr('data-userid');
                let status = $(this).attr('data-status');

                Swal.fire({
                    width: 700,
                    icon:'question',
                    html:`<div class="container">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="float-start">Sold :</label>
                                <label for="yes">YES</label>
                                <input type="radio" name="sold" id="sold-yes" value="YES" >
                                <label for="no">NO </label>
                                <input type="radio" name="sold" id="sold-no" value="NO" checked>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="float-start">Hold For Other :</label>
                                <label for="YES">YES</label>
                                <input type="radio" name="hold" id="hold-yes" value="YES" >
                                <label for="NO">NO</label>
                                <input type="radio" name="hold" id="hold-no" value="NO" checked>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="comment"></textarea>
                                <label for="floatingTextarea">Comments</label>
                            </div>
                        </div>
                    </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    preConfirm: () => {
                        let hold = $("input[name='hold']:checked").val();
                        let sold = $("input[name='sold']:checked").val();
                        const comment = Swal.getPopup().querySelector('#comment').value
                        if(!comment)
                        {
                            Swal.showValidationMessage(`Please Comment what You want`)
                        }
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        let comment =$('#comment').val();
                        let hold = $("input[name='hold']:checked").val();
                        let sold = $("input[name='sold']:checked").val();
                        request_call("{{ url('update-enquiry-list')}}","order_id=" + order_id +"&certi_no=" + certi_no + "&status=" + status +"&comment="+comment+"&hold="+hold+"&sold="+sold);
                        xhr.done(function(mydata) {
                            Swal.fire({
                                text: 'Rejected!',
                                type: "warning",
                                icon: "error"
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });
            });

			$('#render_string').delegate('.exchange_rate', 'blur', function (e) {
				if (e.keyCode === 109 || e.keyCode === 189) {
					alert("Not allowed (-)minus sign");
					$(this).val('');
				}

				var id			= $(this).attr('data-id');
				var exchange_rate	= $(this).val();
				if (exchange_rate != "")
				{
					request_call("{{ url('admin_UpdateExchangeRrate') }}", "id=" + $.trim(id) + "&exchange_rate=" + exchange_rate);
					xhr.done(function (mydata) {

					});
				}
			});

			$('#kt_content_container').delegate('.supplier_confirm_popup', 'click', function() {
				var id = [];
				var certi_no = [];
				var customer_id = '';
				$(":checkbox:checked").each(function() {
					certi_no.push($(this).attr('data-certi_no'));
					id.push($(this).val());
					customer_id = $(this).attr('data-customer_id');
				});
				var checkpricesave = 0;
				$(".save_active").each(function() {
					checkpricesave += 1;
				});

				if (checkpricesave != 0) {
					Swal.fire("Warning!", "save diamond price before pickup.", "warning");
				} else if (id == "" && certi_no == "") {
					Swal.fire("Warning!", "Please Select at least one record.", "warning");
				} else {
					request_call("{{ url('admin-confirm-to-supplier') }}", "certi_no=" + certi_no + "&orders_id=" + id + "&customer_id=" + customer_id);
					xhr.done(function(mydataorder) {
						if (mydataorder.error == false) {
							Swal.fire("Warning!", "diamond already Confirm to supplier.", "warning");
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
						if (mydata.success) {
                            blockUI.release();
							Swal.fire("Success!", mydata.success, "success");
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
							Swal.fire("Warning!", mydata.error, "warning");
						}
					});
				}
			});

            $('#kt_content_container').delegate('.fa-plus', 'click', function() {
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
                request_call("{{ url('admin-view-order-detail')}}", "id="+id);
                xhr.done(function(mydata) {
                    blockUI.release();
                    if ($.trim(mydata.detail) != "") {
                        parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('#kt_content_container').delegate('.fa-minus', 'click', function() {
                $(this).removeClass("fa-minus").addClass("fa-plus");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
            });

            $('.reverse_diamond').click(function() {
                var orders_id = [];
                $("#render_string :checkbox:checked").each(function() {
					orders_id.push($(this).attr('data-orders_id'));
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
                    blockUI.block();
                    if (result.isConfirmed) {
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
                    customer_id = $(this).attr('data-customer_id')
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
