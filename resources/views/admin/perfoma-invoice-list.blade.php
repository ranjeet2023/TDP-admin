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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
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
                        @if(Session::has('mes'))
                            <div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                {{ session()->get('mes') }}
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
                                    <span class="card-label fw-bolder text-dark">Perfoma</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                        <thead>
                                           <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                <th class="column-title">Invoice Number</th>
                                                <th class="column-title">Company Name</th>
                                                <th class="column-title">Amount</th>
                                                <th class="column-title">Date</th>
                                                <th class="column-title">Pcs Of Stone</th>
                                                <th>Download</th>
                                                <th>Invoice generated</th>
                                                @if(Auth::user()->user_type == 1)
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="render_string">
                                        @if (!empty($invoice))
                                            @foreach ($invoice as $value)
                                                <tr>
                                                    <td>{{ $value->invoice_number }}</td>
                                                    <td>{{ $value->companyname }}</td>
                                                    <td>{{ $value->total_amount }}</td>
                                                    <td>{{ $value->created_at  }}</td>
                                                    <td>{{count(explode(",",$value->orders_id))}}</td>
                                                    <td nowrap>
                                                        <a href="{{ asset('assets/invoices/'.$value->bill_invoice_pdf) }}" target="_blank" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">
                                                            <span class="svg-icon svg-icon-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"></path>
                                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                    </td>
                                                    <td nowrap>
                                                        @if($value->invoice_generate_status == 0)
                                                            <a class="btn btn-icon btn-sm btn-success invoice_status" title="Approve" data-invoice_id= "{{ $value->invoice_id }}">
                                                                <span class="svg-icon svg-icon-muted svg-icon-1">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                                                    <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                                                    </svg>
                                                                </span>
                                                            </a>
                                                        @else
                                                            <span class="badge badge-warning">GENERATED</span>
                                                        @endif
                                                    </td>
                                                    @if(Auth::user()->user_type == 1)
                                                        <td><a class="btn btn-icon btn-danger btn-sm cancelinvoice" data-invoice_number = "{{ $value->invoice_number }}"><i class="fa fa-times"></i></a></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="100%">No Record Found!!</td></tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {!! $invoice->links() !!}
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


	<script>var hostUrl = "/assets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<!--begin::Page Custom Javascript(used by this page)-->
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

            $('#kt_table_users').DataTable({
                'processing': true,
                "pageLength": 100,
                "ordering": false
            });

            $('.table').delegate('.invoice_status', 'click', function() {
				let invoice_id = $(this).attr('data-invoice_id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are You sure That This Invoice Is Generated?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Invoice Generated!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{ url('admin-invoice_generated_status')}}", "invoice_id=" + invoice_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Success",
                                text: mydata.success,
                                type: "success",
                                icon: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            });

            $('.table').delegate('.cancelinvoice', 'click', function(event) {
				let invoice_number = $(this).attr('data-invoice_number');
                Swal.fire({
                    title: 'Are you sure you want to Cancel Invoice?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Cancel it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'delete-invoice/'+ invoice_number;
                    }
                });
			});


            $('.table').delegate('.invoice', 'click', function(event) {
				let invoice_id = $(this).attr('data-invoice_id');
                 Swal.fire({
                    title: 'Enter Order Tracking Number',
                    input: 'text',
                    inputPlaceholder: 'Enter your Tracking Number',
                    icon: 'question',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit ',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{ url('track-order')}}","invoice_id=" + invoice_id +"&track_no=" + result.value);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                text: 'Tracking ID Update',
                                type: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });

			});


            $('.table').delegate('.payment-status', 'click', function(event) {
				let invoice_id = $(this).attr('data-invoice_id');
                 Swal.fire({
                    html: '<lable class="fs-5 float-left">Payment Recive Amount</lable><br>'+
                    '<input class="form-control payment"  type="number" placeholder="Final Amount" id="payment" >' +
                        '<lable class="fs-5 float-left">Pick  Delivery Date</lable><br>'+
                        '<input type="date"  class="form-control" id="payment_date" >',
                    // title:'Payment Recive Amount',
                    input:'date',
                    icon: 'question',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit ',
                    preConfirm: () => {
                        const payment = Swal.getPopup().querySelector('#payment').value
                        const payment_date = Swal.getPopup().querySelector('#payment_date').value

                        if (!payment) {
                        Swal.showValidationMessage(`Please Enter Amount `)
                        }
                        if (!payment_date) {
                        Swal.showValidationMessage(`Please Enter Date`)
                        }
                    }

                   }).then((result) => {
                    if (result.isConfirmed) {
                        let payment =  document.getElementById('payment').value;
                        let payment_date =  document.getElementById('payment_date').value;
                        blockUI.block();
                        request_call("{{ url('payment-status')}}","invoice_id=" + invoice_id +"&payment=" + payment + "&payment_date=" + payment_date);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                text: 'Payment Status Update',
                                type: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });

			});
        });
    </script>
</body>
</html>
