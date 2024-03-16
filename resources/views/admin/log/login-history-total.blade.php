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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                                    <span class="card-label fw-bolder text-dark">Login History Total</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm" style="float:right;height:30px"><i class="fa fa-arrow-left"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class= m-0 w-auto" style="float:right">
                                            <label for="" class="h3">Date:-</label>
                                              <div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; border: 1px solid #ccc; width: 100%">
                                                  <i class="fa fa-calendar" ></i>&nbsp;
                                                  <span></span> <i class="fa fa-caret-down"></i>
                                              </div>
                                          </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                                <thead>
                                                   <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th class="column-title">Company Name</th>
                                                        <th class="column-title">Last Login</th>
                                                        <th class="column-title">Ip Address</th>
                                                        <th class="column-title">City</th>
                                                        <th class="column-title">Country</th>
                                                        <th class="column-title">User Type</th>
                                                        <th class="column-title">Total Login</th>
                                                        <th class="column-title">Browser</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">

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
            var startdate="";
            var enddate="";
            var starttime="";
            var endtime="";

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


            $(function() {
            var start = moment().subtract('days');
            var end = moment();
            function cb(start, end) {
                startdate=start.format('YYYY-MM-DD');
                enddate=end.format('YYYY-MM-DD');
                $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                request_call('login-history-total',"startdate=" + startdate + "&enddate=" + enddate );
                xhr.done(function(mydata) {
                    $('#render_string').html(mydata);
                });
            }
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                'All': [moment().subtract(10, 'year').endOf('year'),moment()],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'Last 3 months': [moment().subtract(3, 'months'), moment()],
                'Last 6 months': [moment().subtract(6, 'months'), moment()],
                'Last 1 year': [moment().subtract(1, 'years'), moment()],
                }
            }, cb);
            cb(start, end);
            });
        });
    </script>

       <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</body>
</html>
