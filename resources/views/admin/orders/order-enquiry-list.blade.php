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
								<div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
                                            <h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder text-dark">Enquiry List</span>
												<span class="text-muted fw-bold fs-7">{{ !empty($customer) ? $customer->companyname : ''; }}</span>
											</h3>
										</div>
										<div class="card-toolbar">
                                            <input type="hidden" value="{{ $customer->id }}" id="customer_id" />
											<a href="{{ url('enquiry-list')}}" class="btn btn-sm btn-primary me-2"><i class="fa fa-arrow-left"></i> Back</a>
											<button class="btn btn-primary btn-sm me-2 total_record" title="Total Diamond" data-placement="top" data-toggle="tooltip" data-original-title="Total Diamonds">Diamonds = <span id="total_stone_record">{{ $orders->count() }}</span></button>

											<button class="btn btn-sm btn-secondary me-2"><span id="total_pcs">0</span></button>
											<button class="btn btn-sm btn-secondary me-2">CT : <span id="totalcarat">0.00</span></button>
											<button class="btn btn-sm btn-secondary me-2">$/ct $<span id="totalpercarat">0.00</span></button>
											<button class="btn btn-sm btn-secondary me-2">$<span id="totalamount">0.00</span></button>
											@if (Auth::user()->user_type == 1||4||5||6)
											<button class="btn btn-sm btn-secondary me-2">$/ct A $<span id="totalApercarat">0</button>
											<button class="btn btn-sm btn-secondary me-2">Price A $<span id="totalAamount">0</button>
											@endif
										</div>
									</div>
									<div class="card-header border-0">
                                        <div class="card-title"></div>
										<div class="card-toolbar">
                                            <a href="#" class="btn btn-light btn-primary btn-sm d-flex align-items-center gap-2 gap-lg-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <span class="svg-icon svg-icon-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                                @if (Auth::user()->user_type == 1||4||5||6)
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3 bg-hover-primary text-hover-inverse-primary invoice_popup">Invoice</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3 bg-hover-primary text-hover-inverse-primary perfoma_invoice_popup">Proforma Invoice</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3 bg-hover-success text-hover-inverse-success supplier_confirm_popup">Qc Request</a>
                                                    </div>
                                                @endif
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3 bg-hover-info text-hover-inverse-info excel_download">Excel Download</a>
                                                    </div>
                                                @if (Auth::user()->user_type == 1||4||5||6)
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3 bg-hover-danger text-hover-inverse-danger reverse_diamond">Reverse</span></a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3 bg-hover-danger text-hover-inverse-danger release_diamond">Release</span></a>
                                                    </div>
                                                @endif
                                            </div>
                                            @if (Auth::user()->user_type == 1||4||5||6)
                                                <div class="menu-item px-3">
                                                    <a href="{{ url('hold-diamond-list/'.$customer->id)}}" class="btn btn-sm btn-warning">Hold List</a>
                                                </div>
                                                <div class="menu-item">
                                                    <a href="{{ url('admin-release-list/'.$customer->id)}}" class="btn btn-sm btn-primary">Release List</a>
                                                </div>
                                            @endif
										</div>
									</div>
									<div class="card-body py-0">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="fw-bolder fs-6 text-gray-800 px-7">
															<th><input class="check_box check_all" name="multiaction" type="checkbox"></th>
															<th>Action</th>
                                                            <th>Return</th>
															<th>Days</th>
                                                            <th>Priority</th>
															@if (Auth::user()->user_type == 1 || $permission->full == 1)
																<th>Supplier Name</th>
															@endif
                                                            <th>S Status</th>
                                                            <th>Country</th>
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
															@if (Auth::user()->user_type == 1 || $permission->full == 1)
															<th>Buy %</th>
															<th>Buy Price</th>
															@endif
															<th>Ex Rate</th>
															<th>Date</th>
															<th>Port</th>
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

                                                                    if($value->order_status == "PENDING")
                                                                    {
                                                                        $color = "text-info";
                                                                    }
																    elseif($value->order_status == "REJECT")
                                                                    {
                                                                        $color = "text-danger";
                                                                    }
                                                                @endphp
                                                                <tr class="{{ $color }}">
                                                                    <td>
                                                                        <div class="position-relative ps-6 pe-3 py-2">
                                                                        <label class="checkbox  justify-content-center">
                                                                            <input class="check_box" data-orders_id="<?= $value->orders_id ?>" data-customer_id="<?= $value->customer_id ?>"
                                                                                    data-ref_no='<?= $value->ref_no ?>'
                                                                                    data-certi_no='<?= $value->certificate_no ?>'
                                                                                    data-pickups = '{{ ($value->pickups != null) ? 1 : 0 }}'
																				data-carat='{{ $value->orderdetail->carat }}' data-price='{{ $value->sale_price }}' data-discount="{{ $value->sale_discount }}"  data-aprice='{{ $value->buy_price }}' name="multiaction" value="{{ $value->orders_id }}" type="checkbox">
                                                                                <span></span>
                                                                        </label>
                                                                        <i class="fa fa-plus" data-id="{{ $value->orders_id }}" data-customer_id="{{ $value->customer_id }}"></i>
                                                                        @if($value->order_status == "PENDING")
                                                                            <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                                                            <span class="mb-1 text-info fw-bolder">P</span>
                                                                        @elseif($value->order_status == "APPROVED")
                                                                            <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-success"></div>
                                                                            <span class="mb-1 text-success fw-bolder">A</span>
                                                                        @elseif($value->order_status == "REJECT")
                                                                            <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-danger"></div>
                                                                            <span class="mb-1 text-danger fw-bolder">R</span>
                                                                        @endif

                                                                        @if ($value->pickups != null)
                                                                            @if($value->pickups->status == "PENDING")
                                                                                <span class="mb-1 text-success fw-bolder">Requested For QC</span>
                                                                            @elseif($value->pickups->status == "PICKUP_DONE" && ($value->qc_list == null))
                                                                                <span class="mb-1 text-success fw-bolder">On Hand</span>
                                                                            @elseif($value->pickups->status == "PICKUP_DONE" && $value->qc_list->qc_comment != "")
                                                                                <span class="mb-1 text-success fw-bolder">Done QC</span>
                                                                            @elseif($value->pickups->status == "QCRETURN")
                                                                                <span class="mb-1 text-success fw-bolder">QC Done & Return </span>
                                                                            @elseif($value->pickups->status == "IN_TRANSIT")
                                                                                <span class="mb-1 text-success fw-bolder">In Transit</span>
                                                                            @elseif($value->pickups->status == "REACHED")
                                                                                <span class="mb-1 text-success fw-bolder">SENT TO CUSTOMER</span>
                                                                            @endif
                                                                        @endif
                                                                       </div>
                                                                    </td>
                                                                    <td nowrap>
                                                                        @if($value->order_status == "PENDING")
                                                                            <button class="btn btn-sm btn-icon btn-primary me-1 order_a_r w-25px h-25px" id="{{ $value->orders_id }}" data-userid="{{ $value->customer_id }}" data-certino="{{ $value->certificate_no }}" data-status="APPROVED"><i class="fa fa-check"></i></button>
                                                                            <button class="btn btn-sm btn-icon btn-danger order_a_r w-25px h-25px" id="{{ $value->orders_id }}" data-userid="{{ $value->customer_id }}" data-certino="{{ $value->certificate_no }}" data-status="REJECT"><i class="fa fa-times"></i></button></td>
                                                                        @elseif($value->order_status == "APPROVED")
                                                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                                            <a class="btn btn-icon btn-bg-light btn-primary btn-sm me-1 edit_price w-25px h-25px" data-id="{{ $value->orders_id }}" id="hide_edit_{{ $value->orders_id }}" data-carat="{{ $value->orderdetail->carat }}" data-certino="{{ $value->certificate_no }}" data-sale_price='<?= $value->sale_price ?>' data-buy_price='<?= $value->buy_price ?>'>
                                                                                    <span class="svg-icon svg-icon-3">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
                                                                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
                                                                                        </svg>
                                                                                    </span>
                                                                                </a>
                                                                                <a class="btn btn-icon btn-bg-light btn-primary btn-sm me-1 save_price w-25px h-25px" style="display:none;" data-id="{{ $value->orders_id }}" id="hide_save_{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}">
                                                                                    <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                            <path opacity="0.3" d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="black"/>
                                                                                            <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="black"/>
                                                                                            <path d="M10.3629 14.0084L8.92108 12.6429C8.57518 12.3153 8.03352 12.3153 7.68761 12.6429C7.31405 12.9967 7.31405 13.5915 7.68761 13.9453L10.2254 16.3488C10.6111 16.714 11.215 16.714 11.6007 16.3488L16.3124 11.8865C16.6859 11.5327 16.6859 10.9379 16.3124 10.5841C15.9665 10.2565 15.4248 10.2565 15.0789 10.5841L11.4631 14.0084C11.1546 14.3006 10.6715 14.3006 10.3629 14.0084Z" fill="black"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-danger fw-bolder">{{ ($value->return_price > 0.00) ? ( (Auth::user()->user_type == 1) ? '$'.$value->return_price : 'R') : ''; }}</td>
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
                                                                        <span class="svg-icon svg-icon-2hx {{ $priority_color }} cursor-pointer symbol symbol-30px"data-bs-toggle="tooltip" title="Prority: {{ $priority }}" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path opacity="0.3" d="M20.9 12.9C20.3 12.9 19.9 12.5 19.9 11.9C19.9 11.3 20.3 10.9 20.9 10.9H21.8C21.3 6.2 17.6 2.4 12.9 2V2.9C12.9 3.5 12.5 3.9 11.9 3.9C11.3 3.9 10.9 3.5 10.9 2.9V2C6.19999 2.5 2.4 6.2 2 10.9H2.89999C3.49999 10.9 3.89999 11.3 3.89999 11.9C3.89999 12.5 3.49999 12.9 2.89999 12.9H2C2.5 17.6 6.19999 21.4 10.9 21.8V20.9C10.9 20.3 11.3 19.9 11.9 19.9C12.5 19.9 12.9 20.3 12.9 20.9V21.8C17.6 21.3 21.4 17.6 21.8 12.9H20.9Z" fill="currentColor"/>
                                                                                <path d="M16.9 10.9H13.6C13.4 10.6 13.2 10.4 12.9 10.2V5.90002C12.9 5.30002 12.5 4.90002 11.9 4.90002C11.3 4.90002 10.9 5.30002 10.9 5.90002V10.2C10.6 10.4 10.4 10.6 10.2 10.9H9.89999C9.29999 10.9 8.89999 11.3 8.89999 11.9C8.89999 12.5 9.29999 12.9 9.89999 12.9H10.2C10.4 13.2 10.6 13.4 10.9 13.6V13.9C10.9 14.5 11.3 14.9 11.9 14.9C12.5 14.9 12.9 14.5 12.9 13.9V13.6C13.2 13.4 13.4 13.2 13.6 12.9H16.9C17.5 12.9 17.9 12.5 17.9 11.9C17.9 11.3 17.5 10.9 16.9 10.9Z" fill="currentColor"/>
                                                                            </svg>
                                                                        </span>
                                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 bg-gray-200 text-gray-700 py-4 fs-7 w-200px " data-kt-menu="true">
                                                                            <div class="menu-item px-3">
                                                                                <a class="menu-link px-3 bg-hover-primary text-hover-inverse-primary prioritychange" data-priority="Low" data-order_id = "{{ $value->orders_id }}">Low Priority</a>
                                                                            </div>
                                                                            <div class="menu-item px-3">
                                                                                <a class="menu-link px-3 bg-hover-primary text-hover-inverse-primary prioritychange" data-priority="Medium" data-order_id = "{{ $value->orders_id }}">Medium Priority</a>
                                                                            </div>
                                                                            <div class="menu-item px-3">
                                                                                <a class="menu-link px-3 bg-hover-primary text-hover-inverse-primary prioritychange" data-priority="High" data-order_id = "{{ $value->orders_id }}">Hign Priority</a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    @if (Auth::user()->user_type == 1 || $permission->full == 1)
                                                                    <td>{{ $value->orderdetail->supplier_name }}
                                                                        <a href="https://api.whatsapp.com/send?text={{ $value->orderdetail->shape }} {{ $value->orderdetail->ref_no }} {{ $value->orderdetail->carat }} {{ $value->orderdetail->color }} {{ $value->orderdetail->clarity }} {{ $value->orderdetail->cut }} {{ $value->orderdetail->polish }} {{ $value->orderdetail->symmetry }} {{ $value->orderdetail->fluorescence }} {{ $value->orderdetail->lab }} {{ $value->certificate_no }}" target="_blank">
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
                                                                    <td>{{ $value->sale_discount }}</td>
                                                                    <td>{{ $value->sale_price }}</td>
                                                                    @if (Auth::user()->user_type == 1 || $permission->full == 1)
                                                                    <td>{{ $value->buy_discount }}</td>
                                                                    <td>{{ $value->buy_price }}</td>
                                                                    @endif
                                                                    <td><input class="form-control exchange_rate" id="exchange_<?= $value->orders_id ?>" data-id="<?= $value->orders_id ?>" value="<?= $value->exchange_rate ?>" type="number" size="4" style="min-width:100px;"></td>
                                                                    <td>{{ $value->orderdetail->created_at }}</td>
                                                                    <td nowrap>
                                                                        <div class="d-flex align-items-center flex-column">
                                                                            <span id="portspan_{{ $value->orders_id }}">{!! $value->port !!}</span>
                                                                            <a class="btn btn-icon btn-bg-light btn-primary btn-sm me-1 edit_port w-25px h-25px" data-id="{{ $value->orders_id }}" id="hide_edit_port_{{ $value->orders_id }}">
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
                                                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </a>
                                                                                <a class="btn btn-icon btn-bg-light btn-primary btn-sm me-1 save_port w-25px h-25px" style="display:none;" data-id="{{ $value->orders_id }}" id="hide_save_port_{{ $value->orders_id }}">
                                                                                <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                        <path opacity="0.3" d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="black"/>
                                                                                        <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="black"/>
                                                                                        <path d="M10.3629 14.0084L8.92108 12.6429C8.57518 12.3153 8.03352 12.3153 7.68761 12.6429C7.31405 12.9967 7.31405 13.5915 7.68761 13.9453L10.2254 16.3488C10.6111 16.714 11.215 16.714 11.6007 16.3488L16.3124 11.8865C16.6859 11.5327 16.6859 10.9379 16.3124 10.5841C15.9665 10.2565 15.4248 10.2565 15.0789 10.5841L11.4631 14.0084C11.1546 14.3006 10.6715 14.3006 10.3629 14.0084Z" fill="black"/>
                                                                                    </svg>
                                                                                </span>
                                                                            </a>
                                                                        </div>
                                                                        <select id="p_city_change_{{ $value->orders_id }}" name="port" class="port form-select mt-4" style="display:none; min-width:150px;">

                                                                            </select>
                                                                    </td>
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

            $("#render_string").delegate('.edit_port', 'click', function() {

				var ids = $(this).attr('data-id');

				$('#hide_edit_port_' + ids).hide();
				$('#portspan_' + ids).hide();
				$('#p_city_change_' + ids).append('<option value="">Please select city</option><option value="India">India</option><option value="Hongkong">Hongkong</option><option value="USA">USA</option><option value="Australia">Australia</option>');
				$('#p_city_change_' + ids).val(location);
				$('#p_city_change_' + ids).show();
				$('#hide_save_port_' + ids).show();
                $('select option[value=""]').attr("selected",true);
            });
            $("#render_string").delegate('.save_port', 'click', function() {
				var ids = $(this).attr('data-id');
				var port = $('#p_city_change_' + ids).val();
                if (port != "" && port != null) {
                    blockUI.block();
                    request_call("{{ url('port-enquiry-status') }}", "id=" + ids +"&port=" + port);
                    xhr.done(function(mydataorder) {
                        blockUI.release();
						$('#hide_save_port_' + ids).hide();
						$('#hide_edit_port_' + ids).show();
				        $('#p_city_change_' + ids).hide();
                        $('#portspan_' + ids).html(port);
                        $('#portspan_' + ids).show();
						Swal.fire("Success!", mydataorder.success,'success').then((result) => {
                            window.location.reload();
                        });
                    });
				} else {
					alert("Location can't be empty..!");
				}
            });

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
				@if (Auth::user()->user_type == 1||4||5||6)
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
				@if (Auth::user()->user_type == 1||4||5||6)
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

			$('.menu-item').delegate('.prioritychange', 'click', function() {
                let priority = $(this).attr('data-priority');
                let order_id = $(this).attr('data-order_id');
                Swal.fire({
                    title: 'Change Priority?',
                    text: "Are you sure to Change Priority To - "+ priority,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Change it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{ url('admin-update-priority-status') }}", "priority=" + priority + "&order_id=" + order_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                text: mydata.success,
                                type: "success",
                                title: "Priority Changed Successfully!",
                                icon: 'success',
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });
            })

            $('#render_string').delegate('.order_approve_supplier', 'click', function(event) {
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
                        blockUI.block();
                        request_call("{{ url('update-enquiry-list')}}", "order_id=" + order_id +"&certi_no=" + certi_no + "&status=" + status +"&comment="+comment +"&milky="+milky +"&brown="+brown+"&green="+green+"&stonelocation="+stonelocation+"&certificatelocation="+certificatelocation+"&bgm="+bgm+"&eyeclean="+eyeclean);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                text: 'Approve!',
                                type: "success",
                                icon:'success',
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });
            });

            $('#render_string').delegate('.order_reject_supplier', 'click', function(event) {
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
                        blockUI.block();
                        request_call("{{ url('update-enquiry-list')}}","order_id=" + order_id +"&certi_no=" + certi_no + "&status=" + status +"&comment="+comment+"&hold="+hold+"&sold="+sold);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                text: 'Rejected!',
                                type: "warning",
                                icon: 'error',
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

				var id= $(this).attr('data-id');
				var exchange_rate	= $(this).val();
				if (exchange_rate != "")
				{
					request_call("{{ url('admin-update-exchange-rate') }}", "id=" + $.trim(id) + "&exchange_rate=" + exchange_rate);
					xhr.done(function (mydata) {

					});
				}
			});

			$('#render_string').delegate('.order_a_r', 'click', function() {

				var id = $(this).attr('id');
				var certino = $(this).attr('data-certino');
				var customer_id = $(this).attr('data-userid');
				var order_status = $(this).attr('data-status');

				if(order_status == 'REJECT'){
					$("#header-modal").html('<div class="modal-dialog modal-lg">'
									+'<div class="modal-content">'
										+'<div class="modal-header">'
											+'<h4 class="modal-title">Are you sure you want to Reject request?  Order ID : '+id+'</h4>'
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
							request_call("{{ url('admin-update-order-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino + "&comment=" + encodeURIComponent(comment));
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
							request_call("{{ url('admin-update-order-status')}}", "customer_id=" + customer_id + "&orders_id=" + id + "&order_status=" + order_status + "&certino=" + certino);
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

			$('#kt_content_container').delegate('.supplier_confirm_popup', 'click', function() {
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
				var checkpricesave = 0;
                $(".save_active").each(function() {
					checkpricesave += 1;
				});
				if (checkpickups != 0) {
					Swal.fire("Warning!", "QC Request Already Done From The Seleced Stone ..!!", "warning");
				}else if (checkpricesave != 0) {
					Swal.fire("Warning!", "save diamond price before pickup.", "warning");
				} else if (id == "" && certi_no == "") {
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

                            Swal.fire({
								title: "success",
								text: 'Qc Request sent successfully.',
								type: "success",
							}).then((result) => {
								location.reload();
							});
						}
						else
                        {
							Swal.fire("Warning!", "Fail to confirm to supplier.", "warning");
						}
					});
				}
			});

            $('.invoice_popup').click(function() {
				var id = [];
				var orders_id = [];
				var certi_no = [];
				var qc_review = [];
				var qc_small_status = [];
				var customer_id = $('#customer_id').val();

				$("#render_string :checkbox:checked").each(function() {
					orders_id.push($(this).attr('data-orders_id'));
					certi_no.push($(this).attr('data-certi_no'));
					qc_review = $(this).attr('data-qc_review');
					qc_small_status = $(this).attr('data-qc_small_status');
					id.push($(this).val());
					if((qc_review == 'QC REVIEW OUT' || qc_review == 'QC REVIEW IN') && (qc_small_status == 'N' || qc_small_status == '') ){
						qc_review_status_popup();
						return false;
					}
				});
				var checkpricesave = 0;
				$(".save_active").each(function() {
					checkpricesave += 1;
				});
				if((qc_review == 'QC REVIEW OUT' || qc_review == 'QC REVIEW IN') && (qc_small_status == 'N' || qc_small_status == '')){
					qc_review_status_popup();
				}else{
					if (checkpricesave != 0) {
						Swal.fire("Warning!", "Save diamond price before creating invoice", "warning");
					} else if (id == "" && orders_id == "" && certi_no == "") {
						Swal.fire("Warning!", "Please Select at least one record", "warning");
					} else {
                        blockUI.block();
						request_call("{{ url('admin-invoice-popup') }}", "orders_id=" + orders_id + "&certi_no=" + certi_no + "&customer_id=" + customer_id);
						xhr.done(function(mydataorder) {
                            blockUI.release();
							if (mydataorder.error == false) {
								Swal.fire("Warning!", "First confirm to supplier", "warning");
							} else {
                                var option = '';
                                var address = '';
                                $.each(mydataorder.associate, function(associates, item) {
                                    option += "<option value='"+ item.id +"'>"+ item.name +"</option>";
                                });
                                $.each(mydataorder.addresses, function(addresses, item) {
                                    var selec = '';
                                    if(item.by_default == 1){
                                        selec = 'selected';
                                    }
                                    address += "<option value='"+ item.add_id +"' "+ selec +">"+ item.state +"</option>";
                                });
								$("#header-modal").html("<div class='modal-dialog modal-xl modal-dialog-centered modal-fullscreen' style='min-width:65%;'>" +
									"<div class='modal-content'>" +
									"<div class='modal-header'>" +
										"<h4 class='modal-title'><strong>Create Invoice</strong></h4>" +
										"<div><label class='text-muted fs-7 form-label'>Default input</label><select id='associate' class='form-select mw-150px me-2'>"+ option +"</select></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>Local</label><br/><input type='checkbox' id='local' value='1'></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>address</label><select id='address_id' class='form-select mw-150px me-2'><option value=''>SELECT AN ADDRESS</option>"+ address +"</select></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>Shipping</label><select id='shipping_val' class='form-select mw-150px me-2'><option value='FEDEX'>FEDEX</option><option value='UPS'>UPS</option><option value='MA-Express'>MA-Express</option><option value='JK-MALCA AMIT'>JK-MALCA AMIT</option><option value='B.V.C'>BVC</option><option value='BVC'>BVC FOB</option><option value='Other'>Other</option></select></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>shipping charge</label><input class='form-control mw-90px' id='shipping_charge' type='number' value='0'></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>Extra Discount $</label><input class='form-control mw-90px' id='discount_extra_order' type='number' value='0'></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>Consignee</label><br/><input type='checkbox' id='consignee' value='1'></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>consignee no</label><input class='form-control mw-90px' id='consignee_no' type='text' value='' placeholder='consignee no'></div>"+
										"<button id='create_invoice' class='btn btn-success me-2'>Invoice</button>" +
										"<div class='btn btn-icon btn-sm btn-active-light-primary ms-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>" +
									"</div>" +
									"<div class='modal-body'>" +
										"<div class='row grid-block'>" +
                                        "<table class='table center table-striped table-bordered bulk_action'>" +
                                            "<tr>" +
                                                "<td colspan='5'>Diamonds : <b>" + totalstone + "</b> | Carats : <b>" + TotalCarat + "</b> | Sale Per Carat : <b>" + TotalPerCarat + "</b> | Sale Price : <b>" + TotalPrice + "</b></td>"+
                                                "<input class='form-control mw-150px' id='extra_save' type='hidden' value='" + mydataorder.discountamount + "'></td>"+
                                            "</tr>" +
                                        "</table>" +
										"" + mydataorder.render_msg + "" +
										"</div>" +
									"</div>" +
									"</div>" +
									"</div>"
								);
								$('#header-modal').modal('show');
							}
						});
					}

				}
			});

            $('#header-modal').delegate('#create_invoice', 'click', function(event) {
				var id = [];
				var orders_id = [];
				var certi_no = [];
				var discount = [];
				var net = [];
				var sumrap = 0;
				var netsum = 0;
				var customer_id = $('#customer_id').val();
				var discount_extra_order = $('#discount_extra_order').val();
				var extra_save = $('#extra_save').val();
				if (!extra_save) {
					extra_save = 0;
				}
				var shipping = $('#shipping_val').val();
                var associate = $('#associate').val();
                var shipping_charge = $('#shipping_charge').val();
                let consignee_no = $('#consignee_no').val();
                let address_id = $('#address_id').val();
                let consignee = 0;
                let local = 0;
                if ($("#consignee").is(':checked')) {
                    consignee = 1;
                }
                if ($("#local").is(':checked')) {
                    local = 1;
                }

				$("#render_string :checkbox:checked").each(function() {
					net.push($(this).attr('data-price'));
					orders_id.push($(this).attr('data-orders_id'));
					certi_no.push($(this).attr('data-certi_no'));
					discount.push($(this).attr('data-discount'));
					id.push($(this).val());
					sumrap = sumrap + parseInt($(this).attr('data-onerap'));
					netsum = netsum + parseFloat($(this).attr('data-price'));
				});
				var avg_discount = (netsum / sumrap * 100 - 100).toFixed(2);
				if (id == "" && orders_id == "" && certi_no == "") {
					Checked_Stone();
				} else {
                    $('#header-modal').modal('hide');
                    blockUI.block();
					request_call("{{ url('admin-create-invoice') }}", "orders_id=" + orders_id + "&certi_no=" + certi_no + "&customer_id=" + customer_id + "&discount_extra_order=" + discount_extra_order + "&extra_save=" + extra_save + "&shipping=" + shipping + "&discount=" + avg_discount + "&amt=" + netsum + "&associate="+ associate + "&shipping_charge=" + shipping_charge + "&consignee=" + consignee + "&consignee_no=" + consignee_no + "&address_id=" + address_id + "&local=" + local);
					xhr.done(function(mydataorder) {
                        blockUI.release();
						if ($.trim(mydataorder.success) != "") {
							$(":checkbox:checked").each(function() {
								$(this).closest('tr').html('');
							});
							Swal.fire({
								title: "success",
								text: 'Invoice is ready',
								type: "success",
							}).then((result) => {
								location.reload();
							});
						}
					});
				}
			});

            $('.perfoma_invoice_popup').click(function() {
				var id = [];
				var orders_id = [];
				var certi_no = [];
				var qc_review = [];
				var qc_small_status = [];
				var customer_id = $('#customer_id').val();

				$("#render_string :checkbox:checked").each(function() {
					orders_id.push($(this).attr('data-orders_id'));
					certi_no.push($(this).attr('data-certi_no'));
					qc_review = $(this).attr('data-qc_review');
					qc_small_status = $(this).attr('data-qc_small_status');
					id.push($(this).val());
					if((qc_review == 'QC REVIEW OUT' || qc_review == 'QC REVIEW IN') && (qc_small_status == 'N' || qc_small_status == '') ){
						qc_review_status_popup();
						return false;
					}
				});
				var checkpricesave = 0;
				$(".save_active").each(function() {
					checkpricesave += 1;
				});
				if((qc_review == 'QC REVIEW OUT' || qc_review == 'QC REVIEW IN') && (qc_small_status == 'N' || qc_small_status == '')){
					qc_review_status_popup();
				}else{
					if (checkpricesave != 0) {
						Swal.fire("Warning!", "Save diamond price before creating invoice", "warning");
					} else if (id == "" && orders_id == "" && certi_no == "") {
						Swal.fire("Warning!", "Please Select at least one record", "warning");
					} else {
                        blockUI.block();
						request_call("{{ url('admin-perfoma-popup') }}", "orders_id=" + orders_id + "&certi_no=" + certi_no + "&customer_id=" + customer_id);
						xhr.done(function(mydataorder) {
                            blockUI.release();
							if (mydataorder.error == false) {
								Swal.fire("Warning!", "First confirm to supplier", "warning");
							} else {
                                var option = '';
                                var address = '';
                                $.each(mydataorder.associate, function(associates, item) {
                                    option += "<option value='"+ item.id +"'>"+ item.name +"</option>";
                                });
                                $.each(mydataorder.addresses, function(addresses, item) {
                                    var selec = '';
                                    if(item.by_default == 1){
                                        selec = 'selected';
                                    }
                                    address += "<option value='"+ item.add_id +"' "+ selec +">"+ item.state +"</option>";
                                });

								$("#header-modal").html("<div class='modal-dialog modal-xl modal-fullscreen'>" +
									"<div class='modal-content'>" +
									"<div class='modal-header'>" +
										"<h4 class='modal-title'><strong>Create Performa Invoice</strong></h4>" +
										"<div><label class='text-muted fs-7 form-label'>Default input</label><select id='associate' class='form-select mw-150px me-2'>"+ option +"</select></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>address</label><select id='address_id' class='form-select mw-150px me-2'><option value=''>SELECT AN ADDRESS</option>"+ address +"</select></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>Shipping</label><select id='shipping_val' class='form-select mw-150px me-2'><option value='FEDEX'>FEDEX</option><option value='UPS'>UPS</option><option value='MA-Express'>MA-Express</option><option value='JK-MALCA AMIT'>JK-MALCA AMIT</option><option value='B.V.C'>BVC</option><option value='BVC'>BVC FOB</option><option value='Other'>Other</option></select></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>shipping charge</label><input class='form-control mw-90px' id='shipping_charge' type='number' value='0'></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>Extra Discount $</label><input class='form-control mw-90px' id='discount_extra_order' type='number' value='0'></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>Consignee</label><br/><input type='checkbox' id='consignee' value='1'></div>"+
                                        "<div><label class='text-muted fs-7 form-label'>consignee no</label><input class='form-control mw-90px' id='consignee_no' type='text' value='' placeholder='consignee no'></div>"+
										"<button id='create_perfoma' class='btn btn-success me-2'>Perfoma</button>" +
										"<div class='btn btn-icon btn-sm btn-active-light-primary ms-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>" +
									"</div>" +
									"<div class='modal-body'>" +
										"<div class='row grid-block'>" +
                                        "<table class='table center table-striped table-bordered bulk_action'>" +
                                            "<tr>" +
                                                "<td colspan='5'>Diamonds : <b>" + totalstone + "</b> | Carats : <b>" + TotalCarat + "</b> | Sale Per Carat : <b>" + TotalPerCarat + "</b> | Sale Price : <b>" + TotalPrice + "</b></td>"+
                                                "<input class='form-control mw-150px' id='extra_save' type='hidden' value='" + mydataorder.discountamount + "'></td>"+
                                            "</tr>" +
                                        "</table>" +
										"" + mydataorder.render_msg + "" +
										"</div>" +
									"</div>" +
									"</div>" +
									"</div>"
								);
								$('#header-modal').modal('show');
							}
						});
					}

				}
			});

            $('#header-modal').delegate('#create_perfoma', 'click', function(event) {
				var id = [];
				var orders_id = [];
				var certi_no = [];
				var discount = [];
				var net = [];
				var sumrap = 0;
				var netsum = 0;
				var customer_id = $('#customer_id').val();
				var discount_extra_order = $('#discount_extra_order').val();
				var extra_save = $('#extra_save').val();
				if (!extra_save) {
					extra_save = 0;
				}
				var shipping = $('#shipping_val').val();
                var associate = $('#associate').val();
                var shipping_charge = $('#shipping_charge').val();
                let consignee_no = $('#consignee_no').val();
                let address_id = $('#address_id').val();
                let consignee = 0;
                if ($("#consignee").is(':checked')) {
                    consignee = 1;
                }

				$("#render_string :checkbox:checked").each(function() {
					net.push($(this).attr('data-price'));
					orders_id.push($(this).attr('data-orders_id'));
					certi_no.push($(this).attr('data-certi_no'));
					discount.push($(this).attr('data-discount'));
					id.push($(this).val());
					sumrap = sumrap + parseInt($(this).attr('data-onerap'));
					netsum = netsum + parseFloat($(this).attr('data-price'));
				});
				var avg_discount = (netsum / sumrap * 100 - 100).toFixed(2);
				if (id == "" && orders_id == "" && certi_no == "") {
					Checked_Stone();
				} else {
                    $('#header-modal').modal('hide');
                    blockUI.block();
					request_call("{{ url('admin-perfoma-invoice') }}", "orders_id=" + orders_id + "&certi_no=" + certi_no + "&customer_id=" + customer_id + "&discount_extra_order=" + discount_extra_order + "&extra_save=" + extra_save + "&shipping=" + shipping + "&discount=" + avg_discount + "&amt=" + netsum + "&associate="+ associate + "&shipping_charge=" + shipping_charge + "&consignee=" + consignee + "&consignee_no=" + consignee_no + "&address_id=" + address_id );
					xhr.done(function(mydataorder) {
                        blockUI.release();
						if ($.trim(mydataorder.success) != "") {
							$(":checkbox:checked").each(function() {
								$(this).closest('tr').html('');
							});
							Swal.fire({
								title: "success",
								text: 'Perfoma is ready',
								type: "success",
							}).then((result) => {
								location.reload();
							});
						}
					});
				}
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

                blockUI.block();
                request_call("{{ url('admin-view-order-detail')}}", "id="+id);
                xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        blockUI.release();
                        parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('#render_string').delegate('.fa-minus', 'click', function() {
                blockUI.block();
                $(this).removeClass("fa-minus").addClass("fa-plus");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
                blockUI.release();
            });

            $("#render_string").delegate(".edit_price", "click", function () {
				var ids = $(this).attr('data-id');
				var certino = $(this).attr('data-certino');

                var sale_price = $(this).attr('data-sale_price');
                var buy_price = $(this).attr('data-buy_price');
                var carat = $(this).attr('data-carat');
                $("#header-modal").html('<div class="modal-dialog modal-md">'
                            +'<div class="modal-content">'
                                +'<div class="modal-header">'
                                    +'<h4 class="modal-title">Update Price Certi # : '+certino+'</h4>'
                                    +'<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>'
                                +'</div>'
                                +'<div class="modal-body">'
                                    +'<div class="row">'
                                        +'<div class="col-md-6 col-sm-3 col-xs-12">'
                                            +'<input type="hidden" class="form-control" name="order_id" id="order_id_popup" value="'+ids+'" />'
                                            +'<input type="hidden" class="form-control" name="carat" id="order_carat_popup" value="'+carat+'" />'
                                            +'<label for="exampleFormControlInput1" class="required form-label">Sale Price</label>'
                                            +'<input type="text" class="form-control" name="sale_price" id="sale_price" value="'+sale_price+'" required />'
                                        +'</div>'
                                        +'<div class="col-md-6 col-sm-3 col-xs-12">'
                                            +'<label for="exampleFormControlInput1" class="required form-label">Buy Price</label>'
                                            +'<input type="text" class="form-control" name="buy_price" id="buy_price" value="'+buy_price+'" required />'
                                        +'</div>'
                                    +'</div>'
                                +'</div>'
                                +'<div class="modal-footer justify-content-between">'
                                    +'<button type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</button>'
                                    +'<button type="button" class="btn btn-primary save_price">Save</button>'
                                +'</div>'
                            +'</div>'
                        +'</div>'
                );
                $('#header-modal').modal('show');
				// KTApp.blockPage({
				// 	overlayColor: '#000000',
				// 	state: 'primary',
				// 	message: 'Processing...'
				// });
				// request_call("{{ url('admin-check-pickup') }}", "ids=" + ids + "&certino=" + certino);
				// xhr.done(function (mydataorder) {
				// 	// KTApp.unblockPage();
				// 	if (mydataorder != "0")
				// 	{
				// 		$('#hide_edit_' + ids).hide();
				// 		$('#price_hidden_' + ids).hide();
				// 		$('#a_price_hidden_' + ids).hide();
				// 		$('#price_change_' + ids).show();
				// 		$('#a_price_change_' + ids).show();
				// 		$('#hide_save_' + ids).show();
				// 		$('#hide_save_' + ids).addClass("save_active");

				// 	} else
				// 	{
				// 		$('#hide_edit_' + ids).hide();
				// 		$('#price_hidden_' + ids).hide();
				// 		// $('#a_price_hidden_'+ids).hide();
				// 		$('#price_change_' + ids).show();
				// 		// $('#a_price_change_'+ids).show();
				// 		$('#hide_save_' + ids).show();
				// 		$('#hide_save_' + ids).addClass("save_active");
				// 		// alreadypickup();
				// 	}
				// });
			});

			$("#header-modal").delegate(".save_price", "click", function () {
				var orders_id = $('#order_id_popup').val();
                var sale_price = $('#sale_price').val();
                var buy_price = $('#buy_price').val();
                var carat = $('#order_carat_popup').val();

				if (sale_price != "" && buy_price != "")
				{
                    $('#header-modal').modal('hide');
                    blockUI.block();
					request_call("{{ url('admin-update-order-price') }}", "orders_id=" + $.trim(orders_id) + "&sale_price=" + sale_price + "&buy_price=" + buy_price+ "&carat=" + carat);
					xhr.done(function (mydata) {
                        blockUI.release();
                        if(mydata.success == true)
                        {
                            Swal.fire({
                                title: "Success",
                                text: mydata.message,
                                type: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        }
                        else if(mydata.success == false)
                        {
                            Swal.fire({
                                title: "Warning",
                                text: mydata.message,
                                type: "warning",
                            }).then((result) => {
                                location.reload();
                            });
                        }
					});
				}
				else
				{
					Swal.fire({title: "Rejected", text: 'Price Empty...!!', type: "Warning"});
				}
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
