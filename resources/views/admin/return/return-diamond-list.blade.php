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
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"> --}}
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
                                    <span class="card-label fw-bolder text-dark">Return Diamond List</span>
                                </h3>
								<div class="card-toolbar">
									<a class="btn btn-success btn-sm me-3" href="{{ route('admin.add-return-diamond') }}">Add a Return Diamond</a>
                                </div>
                            </div>
                            <div class="card-body">
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade active show in" >
										<div class="table-responsive" style="margin-top: 10px;">
											<table class="table table-striped jambo_table bulk_action">
												<thead>
													<tr class="headings">
														<th class="column-title">Customer Name</th>
														<th class="column-title">Supplier Name</th>
														<th class="column-title">Certificate Number</th>
														<th class="column-title">Invoice Number</th>
														<th class="column-title">Export Number</th>
														<th class="column-title">Reference Number</th>
														<th class="column-title">Shape</th>
														<th class="column-title">Color</th>
														<th class="column-title">Clarity</th>
														<th class="column-title">Return Date</th>
														<th class="column-title">Days After The Stone Returned</th>
														<th class="column-title">Buy Price</th>
														<th class="column-title">Return Paid Amount</th>
														<th class="column-title">Buy Date</th>
													</tr>
												</thead>
												<tbody id="render_string">
                                                    @foreach ($returns as $return)
                                                        <tr>
                                                            <td>{!! $return->orderItems->customer->companyname !!}</td>
                                                            <td>{!! $return->orderItems->Supplier->companyname !!}</td>
                                                            <td>{!! $return->certificate_no !!}</td>
                                                            <td>{!! $return->orderItems->invoiceNo->invoice_number !!}</td>
                                                            <td>{!! $return->orderItems->exportNo->export_number !!}</td>
                                                            <td>{!! $return->orderItems->ref_no !!}</td>
                                                            <td>{!! $return->orderItems->shape !!}</td>
                                                            <td>{!! $return->orderItems->color !!}</td>
                                                            <td>{!! $return->orderItems->clarity !!}</td>
                                                            <td>{!! $return->return_date !!}</td>
                                                            <td>{!! intval((time() - strtotime($return->return_date))/(60*60*24)) !!} Days Ago</td>
                                                            <td>{!! $return->orderItems->orders->buy_price !!}</td>
                                                            <td>{!! $return->return_paid_amount !!}</td>
                                                            <td>{!! $return->orderItems->created_at !!}</td>
                                                        </tr>
                                                    @endforeach
												</tbody>
											</table>
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

	<script>var hostUrl = "/assets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->
	{{-- <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script> --}}

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
            };
        });

    </script>
</body>
</html>
