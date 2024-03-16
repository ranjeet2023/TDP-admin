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
                                <h3 class="card-title align-items-start flex-column">Daily Check List</h3>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                        <thead>
                                        <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                <th><input class="check_box check_all" name="multiaction" type="checkbox"></th>
                                                <th class="column-title">Invoice Number</th>
                                                <th class="column-title">Sender Name</th>
                                                <th class="column-title">Customer Name</th>
                                                <th class="column-title">Date</th>
                                                <th class="column-title">Pcs Of Stone</th>
                                                <th class="column-title">Carriage</th>
                                                <th class="column-title">Track</th>
                                                <th class="column-title">Payment Status</th>
                                                <th class="column-title">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="render_string">
                                        @if (!empty($invoices))
                                            @foreach ($invoices as $value)
                                                <tr>
                                                    <td>
                                                        <i class="fa fa-plus-square" id="stones_detail" data-invoice_id={{ $value->invoice_id }}></i>
                                                    </td>
                                                    <td>{{ $value->invoice_number }}</td>
                                                    <td>{{ optional($value->associates)->name}}</td>
                                                    <td>{{ optional($value->customers)->companyname }}</td>
                                                    <td>{{ $value->created_at  }}</td>
                                                    <td>{{count(explode(",",$value->orders_id))}}</td>
                                                    <td>{{ $value->pre_carriage  }}</td>
                                                    <td>{{ $value->tracking_no }}</td>
                                                    <td>{{ $value->payment }}</td>
                                                    <td>{{ $value->total_amount }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="100%">No Record Found!!</td></tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {!! $invoices->links() !!}
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

        });
    </script>
</body>
</html>
