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
                        <div class="card card-custom gutter-b mb-5">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Sales Report</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button class="btn btn-sm btn-secondary me-2">Pcs : <span id="total_pcs">{{ $total_pcs }}</span></button>
                                    <button class="btn btn-sm btn-secondary me-2">Total carat : <span id="total_carat">0</span></button>
                                    <button class="btn btn-sm btn-secondary me-2">Total Buy Price : <span id="total_buy_price">0</span></button>
                                    <button class="btn btn-sm btn-secondary me-2">Total Sale Price : <span id="total_sale_price">0</span></button>
                                    <button class="btn btn-sm btn-secondary me-2">Total % : <span id="total_percent">0</span></button>

                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.Post-sales-report') }}" method="POST">
                                    @csrf
                                    <div class="row mb-6">
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label>From:</label>
                                            <input type="date" id="start_date" class="form-control" name="from_date" value="{{ $from_date }}">
                                        </div>
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label>To:</label>
                                            <input type="date" id="end_date" class="form-control" name="to_date" value="{{ $to_date }}">
                                        </div>
                                        <div class="col-lg-4 mb-lg-0 mb-6">
                                            <label></label>
                                            <button class="btn btn-sm btn-primary me-2 mt-4" id="kt_search"><i class="la la-search"></i> Search</button>
                                            <button onClick="window.location.href='sales-report'" class="btn btn-sm btn-secondary me-2 mt-4" id="kt_reset" type="reset" ><i class='la la-close'></i>Reset</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                        <thead>
                                           <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                <th class="column-title">
                                                    <label class="checkbox justify-content-center mr-2">
                                                    <input type="checkbox" id="checkAll"/><span></span>
                                                </th>
                                                <th class="column-title">Order No</th>
                                                <th class="column-title">Carat</th>
                                                <th class="column-title">Stone Details</th>
                                                <th class="column-title">Buying Taxable</th>
                                                <th class="column-title">Buying Port</th>
                                                <th class="column-title">Supplier Name</th>
                                                <th class="column-title">P Invoice No</th>
                                                <th class="column-title">P Date</th>
                                                <th class="column-title">Sell Port</th>
                                                <th class="column-title">Sell Invoice No</th>
                                                <th class="column-title">Sell Date</th>
                                                <th class="column-title">Selling Taxable Value</th>
                                                <th class="column-title">Shipping Charge</th>
                                                <th class="column-title">Gross Margin</th>
                                            </tr>
                                        </thead>
                                        <tbody id="render_string">
                                        @if (!empty($sales))
                                            @foreach ($sales as $value)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="check_box" data-stone={!! 1 !!} data-carat={!! $value->orders_items->carat !!} data-buy_price = {!! $value->Orders->buy_price !!} data-sale_price = {!! $value->Orders->sale_price !!}/>
                                                    </td>
                                                    <td>{{ $value->Orders->orders_id }}</td>
                                                    <td>{{ $value->orders_items->carat }}</td>
                                                    <td>{{ $value->orders_items->shape .'-'. $value->orders_items->color.'-'. $value->orders_items->clarity.'-'. $value->orders_items->lab.'-'. $value->orders_items->certificate_no }}</td>
                                                    <td>{{ $value->Orders->buy_price }}</td>
                                                    <td>{{ $value->pickups->location }}</td>
                                                    <td>{{ substr($value->orders_items->supplier_name, 0, 20)  }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $value->customers->port_of_discharge }}</td>
                                                    <td>{{ $value->invoices->invoice_number }}</td>
                                                    <td>{{ date("d-M-Y", strtotime($value->invoices->created_at)) }}</td>
                                                    <td>{{ $value->Orders->sale_price }}</td>
                                                    <td>{{ $value->invoices->shipping_charge }}</td>
                                                    <td>{{ number_format($value->Orders->sale_price - $value->Orders->buy_price)}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="100%">No Record Found!!</td></tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {!! $sales->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-xl-stretch mb-5" style="height:auto;">
                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class="col-md-6">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder text-dark">Shape Wise Sales Report :</span>
                                        </h3>
                                    </div>
                                    <div class="col-md-6">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder text-dark">Country Wise Sales Report :</span>
                                        </h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div id="ShapeWiseChart" style="height: 350px;"></div>
                                    </div>
                                    <div class="col">
                                        <div id="CountryWiseChart" style="height: 350px;"></div>
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
                "pageLength": 500,
                "ordering": false
            });

            $("#checkAll").click(function(){
				$('#render_string input:checkbox').not(this).prop('checked', this.checked);
				checkbox_event();
			});

            $('.check_box').on("change", function() {
                checkbox_event();
            });

            function checkbox_event(){
				var buy_price = 0;
				var sale_price = 0;
				var stone = 0;
				var carat = 0;
                var percent = 0;
				$(this).parents("tr").removeClass("success");
				$('.check_box:checked').each(function() {
                    $(this).parents("tr").addClass("success");
					stone += parseInt($(this).data('stone'));
                    carat += $(this).data('carat');
                    buy_price += (parseFloat($(this).data('buy_price')));
                    sale_price += (parseFloat($(this).data('sale_price')));
				});

                if(stone == 0){
                    stone = {{ $total_pcs }};
                }
                var percent = ((sale_price - buy_price)/sale_price) * 100;

				$('#total_pcs').html(stone);
				$('#total_carat').html((carat).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
				$('#total_buy_price').html((buy_price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
				$('#total_sale_price').html((sale_price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

                $('#total_percent').html((percent).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			};

            var shapeall = JSON.parse({!! json_encode($shapeall) !!});
            var shapedata = JSON.parse({!! json_encode($shapedata) !!});

            // Chart labels
            var option = {
                series: shapedata,
                chart: {
                    width: 500,
                    type: 'pie',
                },
                tooltip: {
                    style: {
                        fontSize: '12px',
                    },
                    y: {
                        formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
                            return value
                        }
                    }
                },
                labels: shapeall,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#ShapeWiseChart"), option);
            chart.render();

            var countryall = JSON.parse({!! json_encode($countryall) !!});
            var countrydata = JSON.parse({!! json_encode($countrydata) !!});

            // Chart labels
            var option = {
                series: countrydata,
                chart: {
                    width: 500,
                    type: 'pie',
                },
                tooltip: {
                    style: {
                        fontSize: '12px',
                    },
                    y: {
                        formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
                            return value
                        }
                    }
                },
                labels: countryall,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#CountryWiseChart"), option);
            chart.render();

        });
    </script>
</body>
</html>

