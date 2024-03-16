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

	{{-- <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/> --}}
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
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">API Log Detail</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped jambo_table bulk_action">
                                                <thead>
                                                    <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th style="display: none;">ID</th>
                                                        <th class="column-title">Search Date</th>
                                                        <th class="column-title">Diamond Type</th>
                                                        <th class="column-title">Page</th>
                                                        <th class="column-title">SKU</th>
                                                        <th class="column-title">Shape</th>
                                                        <th class="column-title">Carat</th>
                                                        <th class="column-title">Color</th>
                                                        <th class="column-title">Clarity</th>
                                                        <th class="column-title">Cut</th>
                                                        <th class="column-title">Polish</th>
                                                        <th class="column-title">Symm</th>
                                                        <th class="column-title">Flo</th>
                                                        <th class="column-title">Lab</th>
                                                        <th class="column-title">Certificate</th>
                                                        <th class="column-title">Extra</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">
                                                    @if (!empty($logData))
														@foreach ($logData as $value)
                                                            <tr>
                                                                <td style="display: none;">{{ $value->api_log_id }}</td>
                                                                <td>{{ $value->search_date }}</td>
                                                                <td>{{ $value->diamond_type }}</td>
                                                                <td>{{ $value->start_index }}</td>
                                                                <td>{{ $value->stock_id }}</td>
                                                                <td>{{ $value->shape }}</td>
                                                                <td>{{ $value->carat }}</td>
                                                                <td>{{ $value->color }}</td>
                                                                <td>{{ $value->clarity }}</td>
                                                                <td>{{ $value->cut }}</td>
                                                                <td>{{ $value->polish }}</td>
                                                                <td>{{ $value->symmetry }}</td>
                                                                <td>{{ $value->fluorescence }}</td>
                                                                <td>{{ $value->lab }}</td>
                                                                <td>{{ $value->certificate_no }}</td>
                                                                <td class="capital_user">
                                                                    <b>TABLE:</b> {{ $value->table_per_from . ' : '.$value->table_per_to }}<br />
                                                                    <b>DEPTH:</b> {{ $value->depth_per_from. ' : '.$value->depth_per_to }}<br />
                                                                    <b>CARAT-PRICE:</b> {{ $value->pricepercts_min. ' : '.$value->pricepercts_max }}<br />
                                                                    <b>PRICE:</b> {{ $value->totalprice_min. ' : '.$value->totalprice_max }}
                                                                </td>
                                                        @endforeach
													@else
														<tr><td colspan="100%">No Record Found!!</td></tr>
													@endif
                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-center">
                                                {!! $logData->links() !!}
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
            }
        });
    </script>
</body>
</html>
