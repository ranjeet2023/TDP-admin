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
        .tabs-link{
            cursor: pointer;
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
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Export List</span>
                                </h3>
                                    <div class="card-toolbar">
                                        <button class="btn btn-sm btn-secondary me-2">Total Pcs : <span id="total_pcs">0</span></button>
                                        <button class="btn btn-sm btn-secondary me-2">Total Price : <span id="total_price">0</button>
                                    </div>
                                </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                            <li class="nav-item">
                                                <a class="nav-link active tabs-link" id="exports-tab" data-tab="exports">EXPORTS</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link tabs-link" id="exports-invoice-tab" data-tab="export-invoice">EXPORT INVOICE</a>
                                            </li>
                                        </ul>
                                        <div class="table-responsive" id="exports_table">
                                            <table id="datatable" class="table table-striped jambo_table bulk_action">
                                                <thead>
                                                    <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th class="column-title"><input type="checkbox" id="checkAll"/></th>
                                                        <th class="column-title">Export Number</th>
                                                        <th class="column-title">Created At</th>
                                                        <th class="column-title">No Of Stone</th>
                                                        <th class="column-title">Export Amount</th>
                                                        <th class="column-title">Refference Number</th>
                                                        <th class="column-title">Invoice</th>
                                                        <th class="column-title">packaging list</th>
                                                        <th class="column-title">Address Rap</th>
                                                        <th class="column-title">Declaration</th>
                                                        <th class="column-title">Annexure A</th>
                                                        <th class="column-title">Annexure</th>
                                                        <th class="column-title">EDI Data</th>
                                                        <th class="column-title">Cancel Export</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">
                                                    @foreach($exports as $export)
                                                        <tr nowrap>
                                                            <td><input type="checkbox" class="check_box mr-4"  data-stone="{!! $export->stone_count !!}" data-price="{!! $export->total_amount !!}" data-export="{{ $export->export_number }}"/> &nbsp;<i class="fa fa-plus-square" data-exp_number="{{ $export->export_number }}" data-stone_count="{{ $export->stone_count }}"></i></td>
                                                            <td>{{ $export->export_number }}</td>
                                                            <td>{{ $export->created_at }}</td>

                                                            <td>{{ $export->stone_count }}</td>
                                                            <td>{{ $export->total_amount }}</td>
                                                            <td align="center">
                                                                @if ($export->reff_no == null)
                                                                    <button type="button" id="track_no" data-export_no="{{ $export->export_number }}"class="btn btn-success btn-icon btn-sm"><i class="fa fa-truck"></i></button>&nbsp;
                                                                @else
                                                                    {{ $export->reff_no }}<br/>

                                                                @endif
                                                                @if ($export->ex_status == 'receive')
                                                                    <span class="badge badge-warning badge-lg">{{ $export->receive_date }}</span>
                                                                @else
                                                                    <span class="badge badge-success badge-lg unreceive" data-export_no="{{ $export->export_number }}" style="cursor:pointer">Unreceive</span>
                                                                @endif
                                                            </td>
                                                            <td><a href= "{{ route('download-export',$export->invoice) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></a></td>
                                                            <td><a href= "{{ route('download-export',$export->packaging_list) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></a></td>
                                                            <td><a href= "{{ route('download-export',$export->address_rap) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></a></td>
                                                            <td><a href= "{{ route('download-export',$export->declaration) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></a></td>
                                                            <td><a href= "{{ route('download-export',$export->annexure_A) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></a></td>
                                                            <td><a href= "{{ route('download-export',$export->annexure) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></a></td>
                                                            <td><a href= "{{ url('/assets/export/',$export->edi_excel) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></a></td>
                                                        @if(!empty($permission) && $permission->delete == 1)
                                                            <td>
                                                                <a id="cancel_export" data-export_no="{{ $export->export_number }}">
                                                                <button type="button" class="btn btn-danger btn-icon btn-sm" title="Cancel" ><i class="fa fa-times"></i></button></a>
                                                            </td>
                                                        @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive" id="exports_invoice_table" style="display: none;">
                                            <table id="datatable2" class="table table-striped jambo_table bulk_action">
                                                {{-- <thead> --}}
                                                    <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th class="column-title">Export Invoice Number</th>
                                                        <th class="column-title">Number Of Stones</th>
                                                        <th class="column-title">Sender</th>
                                                        <th class="column-title">Receiver</th>
                                                        <th class="column-title">Created By</th>
                                                        <th class="column-title">Created At</th>
                                                        <th class="column-title">Export Invoice</th>
                                                        <th class="column-title">Cancel Export Invoice</th>
                                                    </tr>
                                                {{-- </thead> --}}
                                                <tbody id="render_string_2">
                                                    @foreach ($invoices as $item)
                                                        <tr>
                                                            <td>{!! $item->export_invoice_no !!}</td>
                                                            <td>{!! $item->no_of_stones !!}</td>
                                                            <td>{!! $item->from_associate_name !!}</td>
                                                            <td>{!! $item->to_associate_name !!}</td>
                                                            <td>{!! $item->updated_by_user !!}</td>
                                                            <td>{!! $item->created_at !!}</td>
                                                            <td><a href="{{ url('/assets/export_invoice',$item->export_invoice_no.'.pdf') }}"class="btn btn-sm btn-icon btn-success" target="_blank"><i class="fa fa-download"></i></a></td>
                                                            <td><button type="button" class="btn btn-danger btn-icon btn-sm cancel_export_invoice" title="Cancel" data-status="cancel-export-invoice" data-expinvoiceid="{!! $item->exoprt_invoice_id !!}"data-order_id="{!! $item->orders_id !!}" data-exportinvoiceno="{!! $item->export_invoice_no !!}"><i class="fa fa-times"></i></button></a></td>
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

            $("#checkAll").click(function(){
				$('#render_string input:checkbox').not(this).prop('checked', this.checked);
				checkbox_event();
			});

            $('.check_box').on("change", function() {
                checkbox_event();
            });

            function checkbox_event(){
				var total = 0;
				var stone = 0;
				$('.check_box:checked').each(function() {
					stone += parseInt($(this).data('stone'));
                    total += (parseInt($(this).data('price')));
				});
				$('#total_pcs').html(stone);
				$('#total_price').html((total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			};

            // $('#render_string').delegate('#checkallorder', 'click', function(e) {
            //     var table= $(e.target).closest('tr');
            //     console.log(table);
			// 	$('#render_string .orderscheck').not(this).prop('checked', this.checked);
			// });

            $('.tabs-link').click(function(e){
                var tab=$(this).attr('data-tab');
                if(tab == 'export-invoice'){
                    document.getElementById('exports_table').style.display = "none";
                    document.getElementById('exports_invoice_table').style.display = "block";
                    $('#exports-tab').removeClass("active");
                    $('#exports-invoice-tab').addClass("active");
                }
                else if(tab == 'exports'){
                    document.getElementById('exports_table').style.display = "block";
                    document.getElementById('exports_invoice_table').style.display = "none";
                    $('#exports-tab').addClass("active");
                    $('#exports-invoice-tab').removeClass("active");
                }
			})

            $('#render_string').delegate('.unreceive', 'click', function() {
                var export_no = $(this).attr('data-export_no');
                Swal.fire({
                    title: 'Please Enter Details!',
                    width:'40%',
                    html: ` <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        Export Status:<br/>
                                        <select class="form-select" for="export_status" id="ex_status">
                                            <option class="form-control" value="">Choose An Option</option>
                                            <option class="form-control" value="receive">Receive</option>
                                            <option class="form-control" value="unreceive">Unreceive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        Recieve Date:<br/>
                                        <input id="datetimepicker" class="form-control" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!',
                    willOpen: function(){
                        flatpickrInstance = flatpickr(
                            Swal.getPopup().querySelector('#datetimepicker')
                        )
                    },
                    preConfirm: () => {
                        const ex_status = Swal.getPopup().querySelector('#ex_status').value
                        const receive_date = Swal.getPopup().querySelector('#datetimepicker').value

                        if(!ex_status){
                            Swal.showValidationMessage(`Please Select Export Status`)
                        }
                        if(!receive_date){
                            Swal.showValidationMessage(`Please Enter Recieve Date`)
                        }
                    },
                }).then((result) => {
                    if (result.isConfirmed){
                        let ex_status = Swal.getPopup().querySelector('#ex_status').value;
                        let receive_date = Swal.getPopup().querySelector('#datetimepicker').value;
                        blockUI.block();
                        request_call("{{ url('update-export-status') }}", "export_no=" + export_no + "&ex_status=" + ex_status + "&receive_date=" + receive_date );
                        xhr.done(function(mydata) {
                            blockUI.release();
                            if(mydata.success){
                                Swal.fire("Success!", mydata.success,'success').then((result) => {
                                        window.location.reload();
                                });
                            }
                        });
                    }
                });
            })

            $('#render_string').delegate('#track_no', 'click', function() {
                var export_no = $(this).attr('data-export_no');
                Swal.fire({
                    title: 'Enter Order Refference Number',
                    input: 'text',
                    inputPlaceholder: 'Enter your Refference Number',
                    icon: 'question',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit ',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            blockUI.block();
                            request_call("{{ url('reff-order-export')}}","export_no=" + export_no +"&reff_no=" + result.value);
                            xhr.done(function(mydata) {
                                blockUI.release();
                                if(mydata.success){
                                    Swal.fire({
                                        text: mydata.success,
                                        type: "success",
                                        icon: 'success',
                                    }).then((result) => { location.reload(); });
                                }
                                else{
                                    Swal.fire({
                                        text: mydata.error,
                                        type: 'error',
                                        icon: 'error',
                                    }).then((result) => { location.reload(); });
                                }
                            })
                        };
                });
            });

            $('#render_string_2').delegate('.cancel_export_invoice', 'click', function() {
                var status = $(this).data('status');
                var order_id = $(this).data('order_id');
                var exportinvoiceno = $(this).data('exportinvoiceno');
                var expinvoiceid = $(this).data('expinvoiceid');
                Swal.fire({
					title: "Are you sure? #"+exportinvoiceno,
					text: "Are you sure you want to cancel?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, cancel Export Invoice!"
                }).then(function(result) {
					if (result.value) {
                        blockUI.block();
                        request_call("{{ url('admin-cancel-export-invoice') }}","status=" + status + "&order_id=" + order_id + "&exportinvoiceno=" + exportinvoiceno + "&expinvoiceid=" + expinvoiceid);
                        xhr.done(function(mydata){
                            blockUI.release();
                            if(mydata.success){
                                Swal.fire("Done", mydata.success, "success").then((result) => {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });

            });

            $('#render_string').delegate('#cancel_export', 'click', function() {
                var export_no = $(this).attr('data-export_no');
                Swal.fire({
					title: "Are you sure? #"+export_no,
					text: "Are you sure you want to cancel?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, cancel Export!"
                }).then(function(result) {
					if (result.value) {
                        blockUI.block();
                        request_call("{{ url('cancel-export') }}","export_no=" +export_no);
                        xhr.done(function(mydata){
                            if(mydata.success){
                                blockUI.release();
                                Swal.fire("Done", mydata.success, "success").then((result) => {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            });

            $('#render_string').delegate('.fa-plus-square', 'click', function() {
                // $(".detail_view").each(function(e) {
                //     $(this).remove();
                // });

                // $(".fa-minus-square").each(function(e) {
                //     $(this).removeClass("fa-minus-square").addClass("fa-plus-square");
                // });

                $(this).removeClass("fa-plus-square").addClass("fa-minus-square");

                var parent_tr = $(this).parents('tr');
                var exp_no = $(this).data('exp_number');
                blockUI.block();
                request_call("{{ url('export-list-diamonds')}}", "exp_no="+exp_no);
                xhr.done(function(mydata) {
                    blockUI.release();
                    if ($.trim(mydata.detail) != "") {
                        parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('#render_string').delegate('.fa-minus-square', 'click', function() {
                $(this).removeClass("fa-minus-square").addClass("fa-plus-square");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
            });

            var table = $('#datatable').DataTable({
				// "ordering": false,
				"scrollX": true,
				"pageLength": 100,
                order: [[2, 'desc']],
            });
            var table2 = $('#datatable2').DataTable({
				// "ordering": false,
				"scrollX": true,
				"pageLength": 100,
                order: [[2, 'desc']],
            });
        });
    </script>
</body>
</html>
