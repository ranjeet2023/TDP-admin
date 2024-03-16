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
                                    <span class="card-label fw-bolder text-dark">Search History Log</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group w-auto float-right" >
                                            <label for="">Customer</label>
                                            <select class="form-control " name="" id="select_customer" style="height:40px">
                                                <option value="">Select Customer</option>
                                                @foreach ($logData as $record)
                                                    @if(!empty($record->users->firstname))
                                                    <option value="{{$record->users->id }}"> {{$record->users->firstname}} </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                          </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped jambo_table bulk_action">
                                                <thead>
                                                   <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th class="column-title">Customer Name</th>
                                                        <th class="column-title">Date</th>
                                                        <th class="column-title">Ip</th>
                                                        <th class="column-title">Diamond Type</th>
                                                        <th class="column-title">Certificate Number</th>
                                                        <th class="column-title">Carat</th>
                                                        <th class="column-title">Shape</th>
                                                        <th class="column-title">Colour</th>
                                                        <th class="column-title">Clarity</th>
                                                        <th class="column-title">Cut</th>
                                                        <th class="column-title">Polish</th>
                                                        <th class="column-title">Summary</th>
                                                        <th class="column-title">Flow</th>
                                                        <th class="column-title">Lab</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">

                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-center">

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
            var user_id='';
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
            request_call('search-history',"userid=" + user_id);
                blockUI.block();
                xhr.done(function(mydata) {
                    blockUI.release();
                    $('#render_string').html(mydata);
                });

            $('#select_customer').on('click', function(){
                 var user_id=$('#select_customer').val();
                 request_call('search-history',"userid=" + user_id);
                 xhr.done(function(mydata) {
                      $('#render_string').html(mydata);
                 });
            });
        });
    </script>
</body>
</html>
