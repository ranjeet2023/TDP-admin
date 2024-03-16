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
                        <div class="card card-custom gutter-b">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">In Out List</h3>
                                <div class="card-toolbar">
                                    <form class="m-2" method="post" action="{{ url('inout-list') }}" id="qr_code_form">
                                        @csrf
                                        <div class="input-group">
                                            <div class="input-group-append me-2">
                                                <input type="text" placeholder="Scan QR code" name="search" class="form-control form-control-sm me-2 qr_search" value="{{ $search }}" />
                                            </div>
                                            <div class="input-group-append me-2">
                                            <button class="btn btn-icon btn-success btn-sm me-2" type="submit" id="btnSearch" value="Search" name="submit"><i class="fa fa-search"></i></button>
                                            <button class="btn btn-icon btn-danger btn-sm me-2" type="button" onClick="window.location.href='inout-list'">clear</button>
                                            </div>
                                        </div>
                                    </form>
                                    <a class="btn btn-sm btn-primary me-2" id="pickupdoneprint"><span>Print Lable</span></a>
                                    <a class="btn btn-sm btn-primary me-2 receive-bulk"><span>Take Receive</span></a>
                                    <a class="btn btn-sm btn-warning me-2 generate-pdf" data-generated_by="{{ auth::user()->id }}"><span>Generate Export</span></a>
                                    <button class="btn btn-sm btn-warning me-2" id="export-invoice">Export Invoice</button>
                                </div>
                            </div>
                            <div class="card-header border-0">
                                <div class="card-title "></div>
                                <div class="card-toolbar">
                                    <button class="btn btn-sm btn-secondary me-2">Total : <span id="total_pcs">0</span></button>
                                    <button class="btn btn-sm btn-secondary me-2">CT : <span id="totalcarat">0.00</span></button>
                                    <button class="btn btn-sm btn-secondary me-2">$/ct : $<span id="totalpercarat">0.00</span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{ url('inout-list') }}" id="in_out_form">
                                    @csrf
                                    <div class="row mb-6">
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Status:</label>
                                            <select class="form-select datatable-input" name="status">
                                                <option value="" selected>Status</option>
                                                <option value="PENDING" {{ ($status == 'PENDING') ? 'selected' : '' }}>PENDING</option>
                                                <option value="PICKUP_DONE" {{ ($status == 'PICKUP_DONE') ? 'selected' : '' }}>RECEIVED</option>
                                                <option value="IN_TRANSIT" {{ ($status == 'IN_TRANSIT') ? 'selected' : '' }}>IN TRANSIT</option>
                                                <option value="REACHED" {{ ($status == 'REACHED') ? 'selected' : '' }}>REACHED</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Destination:</label>
                                            <select class="form-select datatable-input" name="destination">
                                                <option value="" selected> Select Location</option>
                                                <option value="Mumbai" {{ ($desti == 'Mumbai') ? 'selected' : '' }}>Mumbai</option>
                                                <option value="Hongkong" {{ ($desti == 'Hongkong') ? 'selected' : '' }}>Hongkong</option>
                                                <option value="USA" {{ ($desti == 'USA') ? 'selected' : '' }}>USA</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <label></label>
                                            <button class="btn btn-icon btn-primary mt-6" type="submit" value="Filter" name="filter"><i class="fa fa-search"></i></button>
                                            <label></label>
                                            <a class="btn btn-icon btn-secondary btn-secondary mt-6" onClick="window.location.href='inout-list'"><i class="fa fa-times"></i></a>
                                        </div>
                                        <div class="col-lg-3">
                                            <label></label>
                                            <a class="btn btn-info btn-info btnreached  mt-6">Send To Customer</a>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                <th><input type="checkbox" class="checkAll"/></th>
                                                <th>Edit</th>
                                                <th>Actions</th>
                                                <th>Certificate</th>
                                                <th>Status</th>
                                                <th>Days</th>
                                                <th>Location</th>
                                                <th>Export Num</th>
                                                <th>Export Invoice</th>
                                                <th>Invoice</th>
                                                <th>Shipping Port</th>
                                                <th>Destination</th>
                                                {{-- <th>Previous Location</th>
                                                <th>Final Location</th> --}}
                                                <th>Supplier</th>
                                                <th>Customer Name</th>
                                                <th>Return</th>
                                                <th>Shape</th>
                                                <th>SKU</th>
                                                <th>Ref No.</th>
                                                <th>Carat</th>
                                                <th>Color</th>
                                                <th>Clarity</th>
                                                <th>Lab</th>
                                                <th>Rap</th>
                                                <th>buy Dis(%)</th>
                                                <th>buy $/ct</th>
                                                <th>buy Price</th>
                                                <th>Created On</th>
                                            </tr>
                                        </thead>
                                        <tbody id="render_string">
                                            @foreach ($trackings as $tracking)
                                                @php
                                                    $color = '';
                                                    if ($tracking->orders->order_status == 'REJECT' || $tracking->orders->order_status == 'RELEASED') {
                                                        $color = 'color:#FF0000 !important';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {!! QrCode::generate($tracking->orderitems->certificate_no, 'assets/qrcodes/' . $tracking->orderitems->certificate_no . '.svg'); !!}
                                                        <input type="checkbox" class="check_box" class="form-control"
                                                                data-destination="{{ $tracking->destination }}"
                                                                data-pickup_status="{{ $tracking->status }}"
                                                                data-invoice_number="{{ $tracking->invoice_number }}"
                                                                data-order_id="{{ $tracking->orders_id }}"
                                                                data-location=" {{ $tracking->location }}"
                                                                data-qc_com="{{ ($tracking->qc_list != null) ? $tracking->qc_list->qc_comment : ''}}"
                                                                data-stock_id="{{  $tracking->orderitems->id; }}"
                                                                data-final_destination="{{  optional($tracking->invoices)->final_destination; }}"
                                                                data-diamond_type="{{  $tracking->orderitems->diamond_type; }}"
                                                                data-shape="{{  $tracking->orderitems->shape; }}"
                                                                data-carat="{{  $tracking->orderitems->carat; }}"
                                                                data-irm_no="{{  $tracking->orders->irm_no; }}"
                                                                data-color="{{  $tracking->orderitems->color; }}"
                                                                data-clarity="{{  $tracking->orderitems->clarity; }}"
                                                                data-cut="{{  $tracking->orderitems->cut; }}"
                                                                data-pol="{{  $tracking->orderitems->polish; }}"
                                                                data-sym="{{  $tracking->orderitems->symmetry; }}"
                                                                data-fl="{{  $tracking->orderitems->fluorescence; }}"
                                                                data-lab="{{  $tracking->orderitems->lab; }}"
                                                                data-certi="{{  $tracking->orderitems->certificate_no; }}"
                                                                data-mea="{{  $tracking->orderitems->length.'*'.$tracking->orderitems->width.'*'.$tracking->orderitems->depth;}}"
                                                                <?php if(!empty($tracking->orderitems->length && $tracking->orderitems->length != 0) && !empty($tracking->orderitems->width && $tracking->orderitems->width != 0)){ ?> data-ratio="<?= round($tracking->orderitems->length / $tracking->orderitems->width, 2); ?>" <?php } ?>
                                                                data-tb="{{  $tracking->orderitems->table_per; }}"
                                                                data-dp="{{  $tracking->orderitems->depth_per; }}"
                                                                data-port="{{  $tracking->orders->port; }}"
                                                                data-price="{{  round($tracking->orders->sale_price, 2); }}"
                                                        >
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-icon btn-primary edit_price" id="hide_edit_{!! $tracking->pickup_id !!}" data-order_id="{!! $tracking->orders_id; !!}" data-pickupid="{!! $tracking->pickup_id !!}" data-location="{!! $tracking->pickup_city !!}"><i class="fas fa-edit"></i></button>
                                                        <button class="btn btn-sm btn-icon btn-success save_price" id="hide_save_{!! $tracking->pickup_id !!}" data-order_id="{!! $tracking->orders_id; !!}" data-pickupid="{{ $tracking->pickup_id; }}" style="display:none;"><i class="fas fa-save"></i></button>
                                                    </td>
                                                    <td>
                                                        @if ($tracking->status != 'PENDING' && $tracking->status != 'IN_TRANSIT' && $tracking->status != 'REACHED' && $tracking->status != 'QCRETURN' )
                                                            @if($tracking->qc_list != null)
                                                                <button class="btn btn-sm btn-icon btn-success qc_comment" data-order_id ="{{ $tracking->orders_id }}"  data-comment="{{ $tracking->qc_list->qc_comment }}" title="" data-status="QCREVIEW" ><i class="fa fa-check" title="{{$tracking->qc_list->qc_comment}}"></i></button>
                                                            @else
                                                                <button class="btn btn-sm btn-icon btn-warning qc_comment" data-order_id ="{{ $tracking->orders_id }}"data-comment="" data-status="QCREVIEW"><i class="fa fa-comment"  title="QC Review"></i></button>
                                                            @endif
                                                            <a class="btn btn-sm btn-clean btn-icon btn-danger pickup_done me-2" data-order_id ="{{ $tracking->orders_id }}" data-status="QCRETURN" title="Return"><i class="fas fa-undo"></i></a>
                                                        @elseif ($tracking->orders->order_status == 'REJECT' || $tracking->orders->order_status == 'RELEASED')
                                                            <a class="btn btn-sm btn-clean btn-icon btn-danger pickup_done me-2" data-order_id ="{{ $tracking->orders_id }}" data-status="QCRETURN" title="Return"><i class="fas fa-undo"></i></a>
                                                        @else
                                                            <a class="btn btn-sm btn-clean btn-icon btn-primary pickup_done me-2" data-order_id ="{{ $tracking->orders_id }}" data-status="PICKUP_DONE" title="Receive"><i class="fas fa-truck"></i></a>
                                                        @endif
                                                    </td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->certificate_no }}</td>
                                                    <td style="{{ $color; }}">
                                                        @if($tracking->status == 'PICKUP_DONE')
                                                            <span class="badge badge-success badge-sm">RECEIVED</span>
                                                        @elseif($tracking->status == 'IN_TRANSIT')
                                                            <span class="badge badge-warning badge-sm">IN TRANSIT</span>
                                                        @elseif($tracking->status == 'REACHED')
                                                            <span class="badge badge-info badge-sm">REACHED</span>
                                                        @elseif($tracking->status == 'PENDING')
                                                            <span class="badge badge-danger badge-sm">PENDING</span>
                                                        @else
                                                            <span class="badge badge-secondary badge-sm">{{ $tracking->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td style="{{ $color; }}"><span class="badge badge-circle badge-primary badge-lg">{{ round((time()- strtotime($tracking->updated_at)) / (60*60*24)) }}</span></td>
                                                    <td style="{{ $color; }}">{{ $tracking->location }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->export_number }}</td>
                                                    <td class="text-center" style="{{ $color; }}">
                                                        @if($tracking->export_invoice != '')
                                                            <a href="{{ url('/assets/export_invoice',$tracking->export_invoice.'.pdf') }}"class="btn btn-sm btn-icon btn-success"><i class="fa fa-download"></i></a>
                                                        @endif
                                                    </td>
                                                    <td style="{{ $color; }}">{{ $tracking->invoice_number }}</td>
                                                    <td style="{{ $color; }}">{{ optional($tracking->orders)->port }}</td>
                                                    <td style="{{ $color; }}">
                                                        <span id="p_destination_hidden_{{ $tracking->pickup_id }}">{{ $tracking->destination; }}</span>
                                                        <select id="p_destination_change_{{ $tracking->pickup_id }}" name="destination" class="destination form-select" style="display:none;">
                                                            <option value="" {{ ($tracking->destination == '') ? 'selected' : '' }}>Please select city</option>
                                                        </select>
                                                    </td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->supplier_name }}</td>
                                                    <td style="{{ $color; }}">{{ ($tracking->orders->user != null) ? $tracking->orders->user->companyname : '' }}</td>
                                                    <td style="{{ $color; }}">{{ (optional($tracking->orders)->return_price != 0) ? 'R' : '' }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->shape }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->id }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->ref_no }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->carat }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->color }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->clarity }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->orderitems->lab }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->raprate }}</td>
                                                    <td style="{{ $color; }}">{{ round($tracking->orders->sale_discount, 2) }}</td>
                                                    <td style="{{ $color; }}">{{ number_format(($tracking->orders->buy_price/$tracking->orderitems->carat),2) }}</td>
                                                    <td style="{{ $color; }}">{{ round($tracking->buy_price, 2); }}</td>
                                                    <td style="{{ $color; }}">{{ $tracking->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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

	<script>var hostUrl = "/assets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->

	<!--begin::Page Custom Javascript(used by this page)-->

	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->

    <script>
        $(document).ready(function() {
            var xhr;
            var total_selected = 0;
            var selected_ids = "";
            var page_record_from = 0;
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
				"pageLength": 100,
                order: [[26, 'desc']],
            });

            $('.btnreached').click(function() {
                var total_val = 0;
				var order_id = [];
                var destination_validation = 0;
                var invoice_validation = 0;
                var receive_validation = 0;

                $('#render_string .check_box:checked').each(function() {
					total_val += 1;
					order_id.push($(this).attr('data-order_id'));

                    if($(this).attr('data-pickup_status') == "PENDING" || $(this).attr('data-pickup_status') == "IN_TRANSIT" || $(this).attr('data-pickup_status') == "QCRETURN" ){
                        receive_validation += 1;

                    }
                    if($(this).attr('data-destination') == "Mumbai")
                    {
                        if($(this).attr('data-destination') == "Mumbai" && $(this).attr('data-final_destination') != "Mumbai"){
                            destination_validation += 1;
                        }
                    }
                    if($(this).attr('data-invoice_number') == ""){
                        invoice_validation += 1;
                    }

				})
				if (total_val == 0) {
					Swal.fire("Warning!", "Please Select at least One Record !", "warning");
				}
                else if(receive_validation > 0){
                    Swal.fire("Warning!", "Receive Stones First!", "warning");
                }
                else if(invoice_validation > 0){
                    Swal.fire("Warning!", "Please Generate Invoice First!", "warning");
                }
                else if(destination_validation > 0){
                    Swal.fire("Warning!", "Please Generate Local Invoice First!", "warning");
                }
                else{
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Are you sure you want to Send The Stones to Customer?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Send!"
                    }).then(function(result) {
                        if (result.value) {
                            blockUI.block();
                            request_call("{{ url('tracking-status-update') }}", "order_id=" + order_id + "&status=" + 'REACHED' );
                            xhr.done(function(mydata) {
                                if(mydata.success){
                                    blockUI.release();
                                    Swal.fire({
                                        title: "Success",
                                        icon:'success',
                                        text: "Send To Customer Successfully!",
                                    }).then((result) => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    });
                }
            })

            $('.receive-bulk').click(function() {
                var total_val = 0;
                var receive_validation = 0;
                var order_id = [];

                $('#render_string .check_box:checked').each(function() {
					total_val += 1;
					order_id.push($(this).attr('data-order_id'));

                    if($(this).attr('data-pickup_status') != "PENDING" && $(this).attr('data-pickup_status') != "IN_TRANSIT" && $(this).attr('data-pickup_status') != "QCRETURN" ){
                        receive_validation += 1;
                    }
				})
                if(receive_validation > 0){
                    Swal.fire({
                        type:"Warning!",
                        text:"Stone already in Destination!",
                        icon:"warning"});
                }else{
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Are you sure you want to Receieve?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Receive!"
                    }).then(function(result) {
                        if (result.value) {
                            blockUI.block();
                            request_call("{{ url('tracking-status-update') }}", "order_id=" + order_id + "&status=" + 'bulk_receive' );
                            xhr.done(function(mydata) {
                                if(mydata.success){
                                    blockUI.release();
                                    Swal.fire({
                                        title: "Success",
                                        icon:'success',
                                        text: "Diamond Receieved Successfully!",
                                    }).then((result) => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    });
                }
            });

            $("#render_string").delegate('.edit_price', 'click', function() {
				var ids = $(this).attr('data-pickupid');
				var location = $(this).attr('data-location');

				$('#p_city_hidden_' + ids).hide();
				$('#p_city_change_' + ids).append('<option value="Surat">Surat</option><option value="Mumbai">Mumbai</option><option value="Hongkong">Hongkong</option><option value="USA">USA</option>');
				$('#p_city_change_' + ids).val(location);
				$('#p_city_change_' + ids).show();

                $('#p_destination_hidden_' + ids).hide();
				$('#p_destination_change_' + ids).append('<option value="Surat">Surat</option><option value="Mumbai">Mumbai</option><option value="Hongkong">Hongkong</option><option value="USA">USA</option>');
				$('#p_destination_change_' + ids).val(location);
				$('#p_destination_change_' + ids).show();

				$('#hide_edit_' + ids).hide();
				$('#hide_save_' + ids).show();
			});

            $("#render_string").delegate('.save_price', 'click', function() {
				var ids = $(this).attr('data-pickupid');
				var order_id = $(this).attr('data-order_id');
				var location = $('#p_city_change_' + ids).val();
				var destination = $('#p_destination_change_' + ids).val();

				if (location != "" && destination != "") {
                    blockUI.block();
					request_call("{{ url('tracking-status-update') }}", "order_id=" + order_id +"&location=" + location + "&destination=" + destination + '&status=' + 'EDIT_INOUT' );
					xhr.done(function(mydataorder) {
                        blockUI.release();
						$('#hide_save_' + ids).hide();
						$('#hide_edit_' + ids).show();
						Swal.fire("Success!", mydataorder.success,'success').then((result) => {
                            window.location.reload();
                        });
					});
				} else {
					alert("Location can't be empty..!");
				}
			});


            $('.generate-pdf').click(function() {
				var total_val = 0;
				var order_id = [];
				var qc_not = 0;
                var receive_validation = 0;
                var destination_validation = 0;
                var port_validation = 0;
				var port_val_certi_no = [];
				var generated_by = $(this).data('generated_by');

				$('#render_string .check_box:checked').each(function() {
					total_val += 1;
					order_id.push($(this).attr('data-order_id'));
                    if($(this).attr('data-qc_com') == "")
                    {
                        qc_not += 1;
                    }
                    if($(this).attr('data-port') == '')
                    {
					    port_val_certi_no.push($(this).attr('data-certi'));
                        port_validation += 1;
                    }
                    if($(this).attr('data-pickup_status') != "PICKUP_DONE"){
                        receive_validation += 1;
                    }
                    if($(this).attr('data-destination') != "Mumbai"){
                        destination_validation += 1;
                    }
				})
				if (total_val == 0) {
					Swal.fire("Warning!", "Please Select at least One Record !", "warning");
				}
                else if(port_validation > 0){
                    Swal.fire("Warning!", "Please Add Port location For this Stones :" + port_val_certi_no  , "warning");
                }
                else if(receive_validation > 0){
                    Swal.fire("Warning!", "Please Receive stones first!", "warning");
                }
                else if(qc_not > 0) {
                    Swal.fire("Warning!", "Please Do QC Of all Stones!", "warning");
                }
                else if(destination_validation > 0){
                    Swal.fire("Warning!", "Please Export Only mumbai location stones!", "warning");
                }
                else{
                    Swal.fire({
                        	width:'70%',
                            title: "Generate Export",
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
                                                <option value="FEDEX">FEDEX</option>
                                                <option value="B.V.C-FEDEX">B.V.C-FEDEX</option>
                                                <option value="UPS">UPS</option>
                                                <<option value="BVC">BVC FOB</option>
                                                <option value="Other">Other</option></select>
                        					</select>
                        				</div>
                        				<div class="col-md-3">
                        					Pre - Carriage No:- <br/>
                        					<select for="pre-carriage" id="pre_carriage" class="form-select">
                        						<option value="CF"> C & F</option>
                        						<option value="FOB"> F.O.B</option>
                                                <option value="CIF"> CIF</option>
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
                                blockUI.block();
                        		request_call("{{ url('export-tracking') }}", "order_id=" + order_id + "&consignee=" + consignee + "&exportno=" + exportno + "&generated_by=" + generated_by + "&brokername=" + broker_name+ "&associate=" + associate + "&customer=" + customer + "&shipping_charge=" + shipping_charge + "&pre_carriage=" + pre_carriage + "&weight_box=" + weight_box );
                        		xhr.done(function(mydata) {
                                    blockUI.release();
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

            $(".checkAll").click(function(){
				$('#render_string input:checkbox').not(this).prop('checked', this.checked);
                checkbox_event();
            });

            $('.check_box').on("change", function() {
                checkbox_event();
			});

            function checkbox_event()
			{
				var carat = 0;
				var stone = 0;
				var cpricetotal = 0;
				var price = 0;
				$('#render_string .check_box:checked').each(function() {
					stone += 1;
					carat += parseFloat($(this).data('carat'));
                    price +=parseFloat($(this).data('price'));
					cpricetotal = price / carat;
				});
				$('#totalcarat').html(carat.toFixed(2));
				$('#total_pcs').html(stone);
				$('#totalpercarat').html(cpricetotal.toFixed(2));
			}

            $("#pickupdoneprint").click(function() {
                $("#header-modal").html('');
                var receive_validation = 0;
                if ($('.check_box:checked').length > 0 && $('.check_box:checked').length <= 20) {
					var checkbox=$('.check_box:checked');
					$('.check_box:checked').each(function(v) {
                        if($(this).attr('data-pickup_status') != "PICKUP_DONE"){
                            receive_validation += 1;
                        }
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
							var padding = '105px';
						}
                        if(receive_validation > 0){
                            Swal.fire("Warning!", "Please Receive stones first!", "warning");
                        }else{
                            $("#header-modal").append(
                                '<div class="main" style="height:190px!important;width: 400px;margin: 5 0 8px 0;box-sizing: border-box;margin-top:'+ padding +'" >' +
                                    '<table cellspacing="0" style="border-top-left-radius:10px;	border-top-right-radius:10px;	border-top:1px solid #000;	border-right:1px solid #000;	border-left:1px solid #000;	width:95%;	height:25%;	font-size: 16px; margin-top:0px;	margin-left:4px;	line-height: 14px;">' +
                                        '<tbody>' +
                                            '<tr style="height: 16px;">'+
                                                '<td align="top" rowspan="4" colspan ="2" rowspan="2" style="width:20%;">' +
                                                    '<img src="./assets/frontend/images/logo-dark.svg" height="25" style="padding:5px 15px;">' +
                                                '</td>' +
                                                '<td align="center" >'+
                                                    '<b>The Diamond Port</b>'+
                                                '</td>'+
                                                '<td rowspan="4" style="text-align:	right;padding-left: 5px;"></td>' +
                                            '</tr>'+

                                        '</tbody>' +
                                    '</table>' +
                                    '<table cellspacing="0" style="border-bottom-left-radius:10px;	border-bottom-right-radius:10px; border-right:1px solid #000; border-left:1px solid #000; border-bottom:1px solid #000; width:95%; height:75%; font-size: 14px;margin-bottom:4px;margin-left:4px;line-height: 14px;">'+
                                        '<tbody>' +
                                            '<tr style="vertical-align:top; padding-top:3px;height:16px;">'+
                                                '<td style="padding-left: 5px;white-space: nowrap;width:33%;"><b>' + shape + '</b></td>' +
                                                '<td style="padding-left: 5px;white-space: nowrap;width:50%;">Ratio:<b>' + ratio + '</b></td>' +
                                                '<td  style="white-space: nowrap;width:33%;padding-left: 5px;" rowspan="7"><img src="./assets/qrcodes/' + certi + '.svg" style="height:90px;padding-right:10px; margin-top:2px;"></td>'+
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
                        }
					});
                    if(receive_validation > 0){
                        Swal.fire("Warning!", "Please Receive stones first!", "warning");
                    }
                    else{
                        var newWin = window.open("");
                        newWin.document.write('<html><style type="text/css">@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");body{font-family: Nunito Sans, Helvetica, Arial, sans-serif;font-size: 10px;}</style><body style="">');
                        newWin.document.write(document.getElementById('header-modal').innerHTML);
                        newWin.document.write('</body></html>');
                        newWin.document.close();
                        setTimeout(function() {
                            newWin.print();
                        }, 500);

                    }
				} else {
                    Swal.fire("Warning!", "Please Select more then One Record and less then 7 !", "warning");
				}
			});

            $('#export-invoice').click(function() {
                var total_val = 0;
                orderid = [];
                exportno = [];
                var qc_not = 0;
                var destination_validation = 0;

                $('.check_box:checked').each(function() {
                    total_val += 1;
                    orderid.push($(this).attr('data-order_id'));
                    exportno.push($(this).attr('data-exportno'));
                    if($(this).attr('data-qc_com') == "")
                    {
                        qc_not += 1;
                    }
                    if($(this).attr('data-destination') == "Mumbai"){
                        destination_validation += 1;
                    }
                });

                if (total_val == 0) {
                    Swal.fire("Warning!", "Please Select at least One Record !", "warning");
                }
                else if(qc_not > 0) {
                    Swal.fire("Warning!", "Please Do QC Of all Stones!", "warning");
                }
                else if(destination_validation > 0){
                    Swal.fire("Warning!", "Please Remove mumbai location stones!", "warning");
                }
                else{
                    blockUI.block();
                    request_call("{{ url('admin-export-invoice-popup') }}", "orderid=" + orderid + "&exportno=" + exportno);
                    xhr.done(function(mydataorder) {
                        blockUI.release();
                        if(mydataorder.error == false){
                            Swal.fire("Warning!", "Please Select Only One Record !", "warning");
                        }
                        else{
                            var option = '';
                            $.each(mydataorder.associate, function(associates, item) {
                                option += "<option value='"+ item.id +"'>"+ item.name +"</option>";
                            });
                            $("#header-modal").html("<div class='modal-dialog modal-xl modal-dialog-centered'>" +
                                            "<div class='modal-content'>" +
                                            "<div class='modal-header'>" +
                                                "<h4 class='modal-title'><strong>Create Export Invoice</strong></h4>" +
                                                "<div class='ml-2'><label class='text-muted fs-7 form-label'>Customer : </label><select class='form-control mw-90px' id='customer'>" + option + "</select><input type='hidden' id='orderid' value='"+ mydataorder.orderid +"'></div>" +
                                                "<div class='ml-2'><label class='text-muted fs-7 form-label'>Assignee : </label><select id='associate' class='form-select mw-150px me-2'>"+ option +"</select></div>"+
                                                "<div class='ml-2'><label class='text-muted fs-7 form-label'>Shipping</label><select id='shipping_val' name='shipping' class='form-select mw-150px me-2'><option value='FEDEX'>FEDEX</option><option value='UPS'>UPS</option><option value='MA-Express'>MA-Express</option><option value='JK-MALCA AMIT'>JK-MALCA AMIT</option><option value='B.V.C'>BVC</option><option value='BVC'>BVC FOB</option><option value='Other'>Other</option></select></div>"+
                                                "<div class='ml-2'><label class='text-muted fs-7 form-label'>shipping charge</label><input class='form-control mw-90px' id='shipping_charge' type='number' value='0'></div>"+
                                                "<div class='ml-2'><label class='text-muted fs-7 form-label'>Extra Discount $</label><input class='form-control mw-90px' id='discount_extra_order' type='number' value='0'></div>"+
                                                "<button class='btn btn-success me-2 create_export_invoice ml-2'>Invoice</button>" +
                                                "<div class='btn btn-icon btn-sm btn-active-light-primary ms-2 ml-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>" +
                                            "</div>" +
                                            "<div class='modal-body'>" +
                                                "<div class='row grid-block'>" +
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
            })

            $('#header-modal').delegate('.create_export_invoice', 'click', function() {

                $('#header-modal').modal('hide');
                blockUI.block();
                var orderid = $('#orderid').val();
                var shipping = $('#shipping_val').val();
                var associate = $('#associate').val();
                var customer = $('#customer').val();
                var discount_extra_order = $('#discount_extra_order').val();
                var shipping_charge = $('#shipping_charge').val();
                request_call("{{ url('admin-export-invoice') }}", "orderid=" + orderid + "&associate=" + associate + "&shipping=" + shipping + "&customer=" + customer + "&discount_extra_order=" + discount_extra_order + "&shipping_charge=" + shipping_charge);
                xhr.done(function(mydata) {
                    blockUI.release();
                    if(mydata.error){
                        Swal.fire("Warning!", mydata.error, "warning").then(location.reload());

                    }
                    else if(mydata.success){
                        Swal.fire({
                                icon:'success',
								title: "success",
								text: mydata.success,
								type: "success",
							}).then((result) => {
								location.reload();
							});

                    }
                })

            });


            $('#render_string').delegate('.qc_comment', 'click', function() {
				var comment = $(this).data('comment');
				var status = $(this).data('status');
				var order_id = $(this).data('order_id');

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
                                                    +'<textarea class="form-control comment" rows="3" placeholder="Comments" required name="comment" maxlength="100" id="comment">' + comment + '</textarea>'
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
                        $('#header-modal').modal('hide');
                        blockUI.block();
                        request_call("{{ url('tracking-status-update')}}",  "status=" + status + "&order_id=" + order_id + "&comment=" + encodeURIComponent(comment));
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Review !",
                                text: 'QC Review successfully...!!',
                                type: "success",
                                icon: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            })

            $('#render_string').delegate('.pickup_done', 'click', function() {
				var status = $(this).data('status');
				var order_id = $(this).data('order_id');

                if(status == 'PICKUP_DONE'){
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Are you sure you want to Receieve?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Receive!"
                    }).then(function(result) {
                        if (result.value) {
                            blockUI.block();
                            request_call("{{ url('tracking-status-update') }}", "status=" + status + "&order_id=" + order_id );
                            xhr.done(function(mydata) {
                                blockUI.release();
                                Swal.fire({
                                    title: "Success",
                                    icon:'success',
                                    text: "Diamond Receieved Successfully!",
                                }).then((result) => {
                                    window.location.reload();
                                });
                            });
                        }
                    })
                }
                else{
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Return This stone to Supplier?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Return!"
                    }).then(function(result) {
                        if (result.value) {
                            blockUI.block();
                            request_call("{{ url('tracking-status-update') }}", "status=" + status + "&order_id=" + order_id );
                            xhr.done(function(mydata) {
                                blockUI.release();
                                Swal.fire({
                                    title: "Success",
                                    icon:'success',
                                    text: "Diamond Return Successfully!",
                                }).then((result) => {
                                    window.location.reload();
                                });
                            });
                        }
                    })
                }
            });

        });
    </script>
</body>
</html>

