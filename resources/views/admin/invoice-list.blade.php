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
                            @if(Session::has('failed'))
                            <div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                {{ session()->get('failed') }}
                            </div>
                            @endif
                            <div class="card card-custom gutter-b">
                                <div class="card-header border-0">
                                    <h3 class="card-title align-items-start flex-column">Invoice</h3>
                                    <div class="card-toolbar">
                                        <button class="btn btn-sm btn-secondary me-2">Total Pcs : <span id="total_pcs">0</span></button>
                                        <button class="btn btn-sm btn-secondary me-2">Total $ : <span id="totalamount">0.00</span></button>
                                        <button class="btn btn-sm btn-warning me-2" id="sales-invoice">Sales Invoice</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form class="m-2" method="post" action="{{ url('invoice-list') }}" id="customer_filter">
                                        @csrf
                                        <div class="row mb-6">
                                            <div class="col-lg-2 mb-lg-0 mb-6">
                                                <label>Sender:</label>
                                                <select name="sender" id="sender" class="form-control ml-2 mr-2" style="padding:0px 10px;">
                                                    <option value="">Select A Sender</option>
                                                    @foreach ($senders as $sender)
                                                    <option value="{{ $sender->id }}" {{ ($send ==  $sender->id ) ? 'selected' : '' }}>{{ $sender->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 mb-lg-0 mb-6">
                                                <label>Customer:</label>
                                                <select name="customer" id="customer" class="form-control ml-2 mr-2" style="padding:0px 10px;">
                                                    <option value="">Select A Customer</option>
                                                    @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ ($cus ==  $customer->id ) ? 'selected' : '' }}>{{ $customer->companyname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 mb-lg-0 mb-6">
                                                <label>Invoice Status:</label>
                                                <select name="status" id="status" class="form-control ml-2 mr-2" style="padding:0px 10px;">
                                                    <option value="">invoice Status</option>
                                                    <option value="all" {{ ($status ==  'all' ) ? 'selected' : '' }}>all</option>
                                                    <option value="deleted" {{ ($status ==  'deleted' ) ? 'selected' : '' }}>Delete</option>
                                                    <option value="not deleted" {{ ($status ==  'not deleted' ) ? 'selected' : '' }}>Not deleted</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2 mb-lg-0 mb-6">
                                                <label>From Date:</label>
                                                <input type="date" id="start_date" class="form-control ml-2 mr-2" style="padding:0px 10px;" name="from_date" value="{{ $from_date }}">
                                            </div>
                                            <div class="col-lg-2 mb-lg-0 mb-6">
                                                <label>To Date:</label>
                                                <input type="date" id="end_date" class="form-control ml-2 mr-2" style="padding:0px 10px;" name="to_date" value="{{ $to_date }}" >
                                            </div>
                                            <div class="col-lg-2 mb-lg-0 mb-6">
                                                <label for=""> </label>
                                                <input  class="btn btn-success btn-sm mr-2 mt-8" type="submit" id="btnSearch" value="Search"style="border-radius: 0.45rem;">
                                                <button class="btn btn-danger btn-sm mt-8" type="button" onClick="window.location.href='invoice-list'"style="border-radius: 0.45rem;">clear</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                            <thead>
                                                <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                    <th><input class="check_box check_all" name="multiaction" type="checkbox"></th>
                                                    <th class="column-title">Invoice Number</th>
                                                    <th class="column-title">Sender Name</th>
                                                    <th class="column-title">Company Name</th>
                                                    <th class="column-title">Date</th>
                                                    <th class="column-title">Pcs Of Stone</th>
                                                    <th class="column-title">Carriage</th>
                                                    <th class="column-title">Track</th>
                                                    <th class="column-title">Payment Status</th>
                                                    <th class="column-title">Difference</th>
                                                    <th class="column-title">Amount</th>
                                                    <th class="column-title">Mail</th>
                                                    <th class="column-title">Download</th>
                                                    <th class="column-title">Sales Invoice</th>
                                                </tr>
                                            </thead>
                                            <tbody id="render_string">
                                                @if (!empty($invoice))
                                                @foreach ($invoice as $value)
                                                <tr>
                                                    <td>
                                                        <i class="fa fa-plus-square" id="stones_detail" data-invoice_id={{ $value->invoice_id }}></i>
                                                        <label class="checkbox  justify-content-center">
                                                            <input class="check_box" name="multiaction" type="checkbox" data-stones="{{ count(explode(",",$value->orders_id)) }}" data-price={{ $value->total_amount }}>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>{{ $value->invoice_number }}</td>
                                                    <td>{{ optional($value->associates)->name }}</td>
                                                    <td>{{ optional($value->customers)->companyname }}</td>
                                                    <td>{{ $value->created_at  }}</td>
                                                    <td>{{count(explode(",",$value->orders_id))}}</td>
                                                    <td>{{ $value->pre_carriage  }}</td>
                                                    <td>
                                                        @if ($value->is_deleted == 0)
                                                            @if(empty($value->tracking_no))
                                                                <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 invoice" data-invoice_id="{{ $value->invoice_id }}">
                                                                    <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                    <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z" fill="currentColor"/>
                                                                                    <path opacity="0.3" d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                            @else
                                                                @if($value->pre_carriage == 'FEDEX')
                                                                    <a target="_blank" href="https://www.fedex.com/fedextrack/?trknbr={{ str_replace(' ','', $value->tracking_no) }}" class="text-primary">{{$value->tracking_no}}</p>
                                                                @elseif($value->pre_carriage == 'UPS')
                                                                    <a target="_blank" href="https://www.ups.com/track?loc=en_US&requester=ST/?{{ str_replace(' ','', $value->tracking_no) }}" class="text-primary">{{$value->tracking_no}}</p>
                                                                @elseif($value->pre_carriage == 'BVC')
                                                                    <a target="_blank" href="https://bgs.brinksglobal.com/login?{{ str_replace(' ','', $value->tracking_no) }}" class="text-primary">{{$value->tracking_no}}</p>
                                                                @else
                                                                    <a class="text-primary">{{$value->tracking_no}}</p>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($value->is_deleted == 0)

                                                            @if($value->total_amount > ($value->payment+$value->payment2) && ($value->payment2 == 0))
                                                                <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 payment-status" data-invoice_id="{{ $value->invoice_id }}" data-payment="{{ $value->payment }}" >
                                                                    <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <path opacity="0.3" d="M12.5 22C11.9 22 11.5 21.6 11.5 21V3C11.5 2.4 11.9 2 12.5 2C13.1 2 13.5 2.4 13.5 3V21C13.5 21.6 13.1 22 12.5 22Z" fill="currentColor"/>
                                                                            <path d="M17.8 14.7C17.8 15.5 17.6 16.3 17.2 16.9C16.8 17.6 16.2 18.1 15.3 18.4C14.5 18.8 13.5 19 12.4 19C11.1 19 10 18.7 9.10001 18.2C8.50001 17.8 8.00001 17.4 7.60001 16.7C7.20001 16.1 7 15.5 7 14.9C7 14.6 7.09999 14.3 7.29999 14C7.49999 13.8 7.80001 13.6 8.20001 13.6C8.50001 13.6 8.69999 13.7 8.89999 13.9C9.09999 14.1 9.29999 14.4 9.39999 14.7C9.59999 15.1 9.8 15.5 10 15.8C10.2 16.1 10.5 16.3 10.8 16.5C11.2 16.7 11.6 16.8 12.2 16.8C13 16.8 13.7 16.6 14.2 16.2C14.7 15.8 15 15.3 15 14.8C15 14.4 14.9 14 14.6 13.7C14.3 13.4 14 13.2 13.5 13.1C13.1 13 12.5 12.8 11.8 12.6C10.8 12.4 9.99999 12.1 9.39999 11.8C8.69999 11.5 8.19999 11.1 7.79999 10.6C7.39999 10.1 7.20001 9.39998 7.20001 8.59998C7.20001 7.89998 7.39999 7.19998 7.79999 6.59998C8.19999 5.99998 8.80001 5.60005 9.60001 5.30005C10.4 5.00005 11.3 4.80005 12.3 4.80005C13.1 4.80005 13.8 4.89998 14.5 5.09998C15.1 5.29998 15.6 5.60002 16 5.90002C16.4 6.20002 16.7 6.6 16.9 7C17.1 7.4 17.2 7.69998 17.2 8.09998C17.2 8.39998 17.1 8.7 16.9 9C16.7 9.3 16.4 9.40002 16 9.40002C15.7 9.40002 15.4 9.29995 15.3 9.19995C15.2 9.09995 15 8.80002 14.8 8.40002C14.6 7.90002 14.3 7.49995 13.9 7.19995C13.5 6.89995 13 6.80005 12.2 6.80005C11.5 6.80005 10.9 7.00005 10.5 7.30005C10.1 7.60005 9.79999 8.00002 9.79999 8.40002C9.79999 8.70002 9.9 8.89998 10 9.09998C10.1 9.29998 10.4 9.49998 10.6 9.59998C10.8 9.69998 11.1 9.90002 11.4 9.90002C11.7 10 12.1 10.1 12.7 10.3C13.5 10.5 14.2 10.7 14.8 10.9C15.4 11.1 15.9 11.4 16.4 11.7C16.8 12 17.2 12.4 17.4 12.9C17.6 13.4 17.8 14 17.8 14.7Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                            @endif
                                                            @if(!empty($value->payment))
                                                                @if($value->payment+$value->payment2 > $value->total_amount )
                                                                    <span class="text-success">{{$value->payment+$value->payment2}}</span>
                                                                    <span>{{$value->payment_date}}</span>
                                                                @elseif($value->payment+$value->payment2 == $value->total_amount )
                                                                    <span class="text-info">{{$value->payment+$value->payment2}}</span>
                                                                    <span>{{$value->payment_date}}</span>
                                                                @else
                                                                    <span class="text-danger">{{$value->payment+$value->payment2}}</span>
                                                                    <span>{{$value->payment_date}}</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($value->payment && $value->total_amount))
                                                            @if (($value->payment+$value->payment2) < $value->total_amount)
                                                                <span class="text-danger">{{number_format(($value->payment+$value->payment2) - $value->total_amount , 2)}}</span>
                                                            @elseif(($value->payment+$value->payment2) > $value->total_amount)
                                                                <span class="text-success"> {{number_format(($value->payment+$value->payment2) - $value->total_amount , 2)}}</span>
                                                            @else

                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $value->total_amount }}
                                                        @if($value->ex_rate > 0)
                                                            <br/>
                                                            <span class="text-muted">{!! round($value->total_amount*$value->ex_rate,2)  !!}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($value->resend_mail == 0 && $value->is_deleted == 0)
                                                        <button class="btn btn-icon btn-warning btn-sm me-3 sendmailtocustomer" data-invoice_id="{{ $value->invoice_id }}" data-customer_id={{ optional($value->customers)->id }}>
                                                            <span class="svg-icon svg-icon-muted svg-icon-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor"></path>
                                                                    <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        @endif
                                                    </td>
                                                    <td nowrap>
                                                        <a href="{{ asset('assets/invoices/'.$value->bill_invoice_pdf) }}" target="_bank" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">
                                                            <span class="svg-icon svg-icon-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"></path>
                                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                        @if(!empty($permission) && $permission->delete == 1)
                                                        @if ($value->is_deleted == 0)
                                                        <a class="btn btn-icon btn-danger btn-sm me-3 cancelinvoice" data-invoice_id="{{ $value->invoice_id }}">
                                                            <span class="svg-icon svg-icon-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <path opacity="0.3" d="M12 10.6L14.8 7.8C15.2 7.4 15.8 7.4 16.2 7.8C16.6 8.2 16.6 8.80002 16.2 9.20002L13.4 12L12 10.6ZM10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.30001 16.5 8.50001 16.5C8.70001 16.5 9.00002 16.4 9.20002 16.2L12 13.4L10.6 12Z" fill="currentColor"/>
                                                                            <path d="M21 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H21C21.6 2 22 2.4 22 3V21C22 21.6 21.6 22 21 22ZM13.4 12L16.2 9.20001C16.6 8.80001 16.6 8.19999 16.2 7.79999C15.8 7.39999 15.2 7.39999 14.8 7.79999L12 10.6L9.20001 7.79999C8.80001 7.39999 8.19999 7.39999 7.79999 7.79999C7.39999 8.19999 7.39999 8.80001 7.79999 9.20001L10.6 12L7.79999 14.8C7.39999 15.2 7.39999 15.8 7.79999 16.2C7.99999 16.4 8.3 16.5 8.5 16.5C8.7 16.5 9.00001 16.4 9.20001 16.2L12 13.4L14.8 16.2C15 16.4 15.3 16.5 15.5 16.5C15.7 16.5 16 16.4 16.2 16.2C16.6 15.8 16.6 15.2 16.2 14.8L13.4 12Z" fill="currentColor"/>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                        @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($value->sales_invoice_pdf != '')
                                                        <a href="{{ url('assets/sales_invoices/'.$value->sales_invoice_pdf) }}" target="_blank" class="btn btn-primary btn-icon btn-sm"><i class="fa fa-download"></i></a>
                                                        @endif
                                                    </td>
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
                    'ordering':false,
                    'processing': true,
                    "pageLength": 100
                });

                $('#render_string').delegate('.sendmailtocustomer', 'click', function() {
                    var invoice_no = $(this).data('invoice_id');
                    var customer_id = $(this).data('customer_id');
                    Swal.fire({
                        title: 'Are You Sure You Want To send Mail To Customer',
                        icon: 'question',
                        confirmButtonColor: '#3085d6',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Send Mail!',
                        cancelButtonText: "No, Don't Send!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            blockUI.block();
                            request_call("{{ url('send-mail-to-customer')}}", "invoice_no= " + invoice_no + "&customer_id=" + customer_id);
                            xhr.done(function(mydata) {
                                if(mydata.success){
                                    blockUI.release();
                                    Swal.fire("Mail Sent!", mydata.success,'success').then((result) => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    });
                });

                $('#render_string').delegate('.fa-plus-square', 'click', function() {
                    $(".detail_view").each(function(e) {
                        $(this).remove();
                    });

                    $(".fa-minus-square").each(function(e) {
                        $(this).removeClass("fa-minus-square").addClass("fa-plus-square");
                    });

                    $(this).removeClass("fa-plus-square").addClass("fa-minus-square");
                    var parent_tr = $(this).parents('tr');
                    var invoice_id = $(this).data('invoice_id');
                    blockUI.block();
                    request_call("{{ url('invoice-list-diamonds')}}", "invoice_id="+invoice_id);
                    xhr.done(function(mydata) {
                        if ($.trim(mydata.detail) != "") {
                            blockUI.release();
                            parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                        }
                    });
                });

                $('#render_string').delegate('.fa-minus-square', 'click', function() {
                    $(this).removeClass("fa-minus-square").addClass("fa-plus-square");
                    var parent_tr = $(this).parents('tr');
                    parent_tr.next("tr.detail_view").remove();
                });

                $('.table').delegate('.cancelinvoice', 'click', function(event) {
                    let invoice_id = $(this).attr('data-invoice_id');
                    Swal.fire({
                        width:'30%',
                        title: 'Would You want to cancel this Invoice?',
                        html: `<div class="container">
                                    <div class="row">
                                        Reason:<br/>
                                        <textarea type="textarea" class="form-control" id="reason" rows="5" cols="40" placeholder="Provide Reason For Cancelling Invoice"></textarea>
                                    </div>
                                </div>`,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Cancel it!',
                        cancelButtonText: 'No!',
                        preConfirm: () => {
                            const reason = Swal.getPopup().querySelector('#reason').value

                            if(!reason){
                                Swal.showValidationMessage(`Please Enter Reason !`)
                            }
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let reason = Swal.getPopup().querySelector('#reason').value;
                            blockUI.block();
                            request_call("{{ url('admin-cancel-invoice')}}", "invoice_id=" + invoice_id + "&reason=" + reason);
                            xhr.done(function(mydata) {
                                blockUI.release();
                                Swal.fire({
                                    title: "Cancelled",
                                    text: 'Cancelled successfully...!!',
                                    type: "success",
                                }).then((result) => {
                                    location.reload();
                                });
                            });
                        }
                    });
                });

                $('#sales-invoice').click(function() {
                    var total_val = 0;
                    orderid = [];
                    invoiceno = [];

                    $('.orderscheck:checked').each(function() {
                        total_val += 1;
                        orderid.push($(this).attr('data-orderid'));
                        invoiceno = ($(this).attr('data-invoiceno'));
                    });

                    if (total_val == 0) {
                        Swal.fire("Warning!", "Please Select at least One Record !", "warning");
                    }else{
                        blockUI.block();
                        request_call("{{ url('admin-sales-invoice-popup') }}", "orderid=" + orderid + "&invoiceno=" + invoiceno);
                        xhr.done(function(mydataorder) {
                            blockUI.release();
                            if(mydataorder.error == false){
                                Swal.fire("Warning!", "Please Select Only One Record !", "warning");
                            }
                            else{
                                var option = '';
                                $.each(mydataorder.customers, function(customers, item) {
                                        option += "<option value='"+ item.user.id +"'>"+ item.user.companyname +"</option>";
                                });
                                $("#header-modal").html("<div class='modal-dialog modal-xl modal-dialog-centered'>" +
                                    "<div class='modal-content'>" +
                                    "<div class='modal-header'>" +
                                    "<h4 class='modal-title'><strong>Create Sales Invoice</strong></h4>" +
                                                    "<div class='ml-2'><label class='text-muted fs-7 form-label'>Assignee : </label><input type='text' class='form-control mw-150px' id='associate' value='" + mydataorder.theassociate + "' disabled ></div>"+
                                                    "<div class='ml-2'><label class='text-muted fs-7 form-label'>Customer</label><select id='customerid' class='form-select mw-150px me-2'>"+ option +"</select></div>"+
                                                    "<div class='ml-2'><label class='text-muted fs-7 form-label'>Shipping</label><select id='shipping_val' name='shipping' class='form-select mw-150px me-2'><option value='FEDEX'>FEDEX</option><option value='UPS'>UPS</option><option value='MA-Express'>MA-Express</option><option value='JK-MALCA AMIT'>JK-MALCA AMIT</option><option value='B.V.C'>BVC</option><option value='BVC'>BVC FOB</option><option value='Other'>Other</option></select></div>"+
                                                    "<div class='ml-2'><label class='text-muted fs-7 form-label'>shipping charge</label><input class='form-control mw-90px' id='shipping_charge' type='number' value='0'></div>"+
                                                    "<div class='ml-2'><label class='text-muted fs-7 form-label'>Extra Discount $</label><input class='form-control mw-90px' id='discount_extra_order' type='number' value='0'></div>"+
                                    "<button class='btn btn-success me-2 create_sales_invoice ml-2'>Sales Invoice</button>" +
                                    "<div class='btn btn-icon btn-sm btn-active-light-primary ms-2 ml-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>" +
                                    "</div>" +
                                    "<div class='modal-body'>" +
                                    "<div class='row grid-block'>" +
                                    "<table class='table center table-striped table-bordered bulk_action'>" +
                                    "<tr>" +
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
                })

                $('#header-modal').delegate('.create_sales_invoice', 'click', function() {
                    blockUI.block();
                    var associate = $('#associate').val();
                    var customer = $('#customerid').val();
                    var shipping = $('#shipping_val').val();
                    var discount_extra_order = $('#discount_extra_order').val();
                    var shipping_charge = $('#shipping_charge').val();
                    var extra_save = $('#extra_save').val();
                    if (!extra_save) {
                        extra_save = 0;
                    }
                    orderid = [];
                    invoiceno = 0;

                    $('.orderscheck:checked').each(function() {

                        orderid.push($(this).attr('data-orderid'));
                        invoiceno = $(this).attr('data-invoiceno');
                    });

                    $('#header-modal').modal('hide');
                    blockUI.block();
                    request_call("{{ url('admin-sales-invoice') }}", "orderid=" + orderid + "&customer=" + customer + "&invoiceno=" + invoiceno + "&associate=" + associate + "&shipping=" + shipping  + "&discount_extra_order=" + discount_extra_order + "&shipping_charge=" + shipping_charge);
                    xhr.done(function(mydataorder) {
                        if(mydataorder.error){
                            blockUI.release();
                            Swal.fire("Warning!", mydata.error, "warning").then(location.reload());
                        }
                        else if(mydataorder.success){
                            if ($.trim(mydataorder.success) != "") {
                                blockUI.release();
                                Swal.fire({
                                    title: "Success",
                                    icon:"success",
                                    text: 'Invoice is ready',
                                    type: "success",
                                }).then((result) => {
                                    location.reload();
                                });
                            }
                        }
                    })

                });

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
                    var Price = 0;
                    $('#render_string .check_box:checked').each(function() {

                        stone += parseFloat($(this).data('stones'));
                        Price += parseFloat($(this).data('price'));
                    });
                    $('#total_pcs').text(stone);
                    $('#totalamount').html(Price.toFixed(2));
                });
                $('#render_string').delegate('.check_box', 'change', function() {
                    var stone = 0;
                    var Price = 0;
                    $('#render_string .check_box:checked').each(function() {

                        stone += parseFloat($(this).data('stones'));
                        Price += parseFloat($(this).data('price'));
                    });
                    $('#total_pcs').text(stone);
                    $('#totalamount').html(Price.toFixed(2));
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
                                    icon:"success",
                                    title:"Tracking ID Update And Email Sent!",
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
                    let amount = $(this).attr('data-payment');
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
                            request_call("{{ url('payment-status')}}","invoice_id=" + invoice_id +"&amount=" + amount +"&payment=" + payment + "&payment_date=" + payment_date);
                            xhr.done(function(mydata) {
                                blockUI.release();
                                Swal.fire({
                                        text: 'Payment Status Update',
                                        type: "success",
                                        title: 'Payment Status Updated!',
                                        icon:'success'
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
