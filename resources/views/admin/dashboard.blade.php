<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>Dashboard {{config('app.name')}}</title>
	<meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

	<meta name="description" content="{{config('app.website')}}" />
	<meta name="keywords" content="{{config('app.website')}}" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" />

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
								<div class="card card-xl-stretch mb-5" style="height:auto;">
									<div class="card-body p-0">
										<div class="card-p">
											<div class="row g-0">
												<div class="col col-lg-2 bg-primary px-6 py-8 rounded-2 me-7 mb-7" style="width:18.65%!important;">
													<span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
															<rect x="8" y="9" width="3" height="10" rx="1.5" fill="black" />
															<rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="black" />
															<rect x="18" y="11" width="3" height="8" rx="1.5" fill="black" />
															<rect x="3" y="13" width="3" height="6" rx="1.5" fill="black" />
														</svg>
													</span>
													<!--end::Svg Icon-->
													<div class="text-white fw-bold fs-4">{{ $Natualnumberofdiamond }}</div>
													<a class="text-white fw-bold fs-4">Natural diamond</a>
												</div>
												<div class="col col-lg-2 bg-success px-6 py-8 rounded-2 me-7 mb-7" style="width:18.65%!important;">
													<!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
													<span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
															<rect x="8" y="9" width="3" height="10" rx="1.5" fill="black" />
															<rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="black" />
															<rect x="18" y="11" width="3" height="8" rx="1.5" fill="black" />
															<rect x="3" y="13" width="3" height="6" rx="1.5" fill="black" />
														</svg>
													</span>
													<!--end::Svg Icon-->
													<div class="text-white fw-bold fs-4">{{ $Labnumberofdiamond }}</div>
													<a class="text-white fw-bold fs-4">Lab diamond</a>
												</div>
											</div>
                                            <div class="row g-0">
                                                <div class="col bg-info px-5 py-8 rounded-2 me-7 mb-7">
													<span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
                                                            <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                                                          </svg>
													</span>
													<!--end::Svg Icon-->
													<div class="text-white fw-bold fs-4">{{ $loginHistoryData }}</div>
													<a class="text-white fw-bold fs-4">Today Login</a>
												</div>
                                                @if (Auth::user()->user_type == 1 || Auth::user()->user_type == 4 )
                                                    <div class="col bg-success px-5 py-8 rounded-2 me-7 mb-7">
                                                        <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="white"/>
                                                                <rect opacity="1" x="14" y="4" width="4" height="4" rx="2" fill="black"/>
                                                                <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="white"/>
                                                                <rect opacity="1" x="6" y="5" width="6" height="6" rx="3" fill="black"/>
                                                                </svg>
                                                            </span>
                                                        <!--end::Svg Icon-->
                                                        <div class="text-white fw-bold fs-4">{{ $pending_customer }}</div>
                                                        <a class="text-white fw-bold fs-4">Pending Customer</a>
                                                    </div>
                                                @endif
                                                @if (Auth::user()->user_type == 1 || Auth::user()->user_type ==5 )
                                                    <div class="col bg-info px-5 py-8 rounded-2 me-7 mb-7">
                                                        <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z" fill="currentColor"/>
                                                                <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                        <div class="text-white fw-bold fs-4">{{ $pending_suppliers }}</div>
                                                        <a class="text-white fw-bold fs-4">Pending Supplier</a>
                                                    </div>
                                                @endif
                                                <div class="col bg-success px-5 py-8 rounded-2 me-7 mb-7">
													<span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z" fill="currentColor"/>
														</svg>
													</span>
													<!--end::Svg Icon-->
													<div class="text-white fw-bold fs-4">{{ $order_count }}</div>
													<a class="text-white fw-bold fs-4">Today Order</a>
												</div>
                                                <div class="col bg-info px-5 py-8 rounded-2 me-7 mb-7">
													<span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"/>
															<path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"/>
														</svg>
													</span>
													<!--end::Svg Icon-->
													<div class="text-white fw-bold fs-4">{{ $hold }}</div>
													<a class="text-white fw-bold fs-4">Hold</a>
												</div>

                                            </div>
                                            @if (Auth::user()->user_type == 1)
											<div class="row g-0">
												<div class="col-md-2 mb-7">
												<a class="btn btn-warning m-1" href="{{ url('update-raprate') }}">Update R List</a>
												</div>
											</div>
                                            @endif
                                            @if (in_array(Auth::user()->role_id, [1]))
                                            <div class="row g-0">
												<div class="col bg-gray px-6 py-8 rounded-2 me-7 mb-7">
                                                    <form role="form" method="post" action="{{ url('customer-upload') }}" enctype="multipart/form-data">
                                                        {!! csrf_field() !!}
                                                        <input type="file" class="form-control" name="upload-file" required />
                                                        <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                                    </form>
												</div>
												<div class="col bg-gray px-6 py-8 rounded-2 me-7 mb-7">
                                                    <form role="form" method="post" action="{{ url('supplier-upload') }}" enctype="multipart/form-data">
                                                        {!! csrf_field() !!}
                                                        <input type="file" class="form-control" name="upload-file" required />
                                                        <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                                    </form>
												</div>
											</div>
                                            @endif
										</div>
									</div>
                                </div>

                                <div class="card card-xl-stretch mb-5" style="height:auto;">
                                    <div class="card-header border-0">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder text-dark">Recent Orders</span>
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding-top:0px;">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade active show in" >
                                                <div class="table-responsive">
                                                    <table class="table table-striped jambo_table bulk_action" id="red_alert_datatable">
                                                        <thead>
                                                            <th>Certificate Number</th>
                                                            <th>Supplier Name</th>
                                                            <th>Supplier Status</th>
                                                            <th>Customer Name</th>
                                                            <th>Order Status</th>
                                                            <th>DIamond Type</th>
                                                            <th>Lab</th>
                                                            <th>Carat</th>
                                                            <th>Color</th>
                                                            <th>Clarity</th>
                                                            <th>Cut</th>
                                                            <th>Polish</th>
                                                            <th>Symmetry</th>
                                                            <th>Fluorescence</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($orders as $order)
                                                                <tr>
                                                                    <td>{{  $order->certificate_no }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->supplier_name : '' }}</td>
                                                                    <td>{{  $order->supplier_status }}</td>
                                                                    <td>{{  $order->user->companyname }}</td>
                                                                    <td>{{  $order->order_status }}</td>
                                                                    <td>{{ ($order->diamond_type == 'L' ? 'Lab Grown' : 'Natural') }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->lab : '' }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->carat : '' }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->color : '' }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->clarity : '' }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->cut : '' }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->polish : '' }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->symmetry : '' }}</td>
                                                                    <td>{{  ($order->orderdetail != null) ? $order->orderdetail->fluorescence : '' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
                                <div class="card card-xl-stretch mb-5" style="height:auto;">
                                    <div class="card-header border-0">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder text-dark">Chart of Orders And Value:</span>
                                        </h3>
                                    </div>
                                    <div style="display:none" id="count_orders" >{{ "$count_orders" }}</div>
                                    <div style="display:none" id="total_price" >{{ $total_price }}</div>
                                    <div style="display:none" id="reject_count" >{{ "$reject_count" }}</div>
                                    <div style="display:none" id="reject_total_price" >{{ "$reject_total_price" }}</div>
                                        <div style="display:none" id="date" >{{ $date }}</div>

                                        <div class="card-body">
                                            <div id="kt_apexcharts_5" style="height: 350px;"></div>
                                        </div>

                                </div>
                                <div class="card card-xl-stretch mb-5" style="height:auto;">
                                    <div class="card-header border-0">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder text-dark">Client Registration Duration Wise Report :</span>
                                        </h3>
                                    </div>
                                        <div style="display:none" id="customers" >{{ "$customers" }}</div>
                                        <div class="card-body">
                                            <div id="Chart2" style="height: 350px;"></div>
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
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

	<script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");

        $(document).ready(function() {

            var element = document.getElementById('kt_apexcharts_5');

            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--kt-warning');

            if (!element) {
                return ;
            }

            var date = JSON.parse(document.getElementById("date").innerHTML);
            var count_orders = JSON.parse(document.getElementById("count_orders").innerHTML);
            var total_price = JSON.parse(document.getElementById("total_price").innerHTML);
            var reject_count = JSON.parse(document.getElementById("reject_count").innerHTML);
            var reject_total_price = JSON.parse(document.getElementById("reject_total_price").innerHTML);

            var options = {
                series: [{
                    name: 'No Of Orders Per Day :',
                    type: 'bar',
                    data: count_orders,
                },{
                    name: 'Orders Value Per Day :',
                    type: 'bar',
                    data: total_price,
                },
                {
                    name: 'No of Rejected Orders Per Day :',
                    type: 'bar',
                    data: reject_count,
                },{
                    name: 'Rejected Orders Value Per Day :',
                    type: 'bar',
                    data: reject_total_price,
                }],
                chart: {
                    fontFamily: 'inherit',
                    stacked: true,
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        endingShape: 'rounded',
                        columnWidth: ['35%'],
                        borderRadius: [6]
                    },
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    show: true,
                    width: 5,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: date,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        },
                        formatter: function (val) {
                            return val.toFixed(2)
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: [{
                        formatter: function (val) {
                            return val;
                        }
                    },{
                        formatter: function (val) {
                            return '$ ' + val.toFixed(2);
                        }
                    },{
                        formatter: function (val) {
                            return val;
                        }
                    },{
                        formatter: function (val) {
                            return '$ ' + val.toFixed(2);
                        }
                    }
                ]
                },
                colors: ['#50CD89','#50CD89','#7239EA','#7239EA'],
                grid: {
                    borderColor: '#000',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                }
            };


            var chart = new ApexCharts(element, options);
            chart.render();

            var customers = JSON.parse(document.getElementById("customers").innerHTML);

            // Chart labels
            var option = {
                series: customers,
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
                labels: ['0 To 1 Month', '1 To 3 Month', '3 To 6 Month', '6 To 12 Month', 'More than 12 Months'],
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

            var chart = new ApexCharts(document.querySelector("#Chart2"), option);
            chart.render();

        });
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
