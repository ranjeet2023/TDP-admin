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
                                <div class="card card-custom gutter-b">
                                    <div class="card-header">
                                        <h3 class="card-title">Daily Reporting</h3>
                                    </div>
                                    @if (Auth::user()->user_type != 1)
                                        <div class="card-body">
                                            <form action="{{ url('daily-report-post') }}" class="form-horizontal form-label-left" method="post">
                                            @csrf
                                                <div class="row">
                                                    <div class="col-md-3 align-self-end ">
                                                        <div class="row">
                                                            <div class="col">
                                                                <h2> Task: </h2>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="card">
                                                                    <div class="card-header" style="border-bottom: 0px">`
                                                                        <div class="card-title"> </div>
                                                                        <div class="card-toolbar">
                                                                            <input type="submit" class="btn btn-success" value = "Submit">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <textarea name="task" id="task" cols="30" rows="3" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <div class="card card-custom gutter-b mt-5">
                                    <div class="card-header" style="border-bottom:0px;">
                                        <h3 class="card-title">Reports : </h3>
                                    </div>
                                    <div class="card-body">
                                        @if (Auth::user()->user_type == 1)
                                            <form action="{!! url('daily-reporting') !!}" method="POST">
                                            @csrf
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                                        <label>User :</label>
                                                        <select class="form-select"name="username">
                                                            <option value="" selected>Select user</option>
                                                            @foreach ($users as $user)
                                                                <option value="{!! $user->user_id !!}" {{ ($username == $user->user_id) ? 'selected' : '' }}>{!! $user->users->firstname !!}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label></label>
                                                        <button class="form-control btn btn-primary btn-primary--icon" type="submit">
                                                            <span>
                                                                <i class="la la-search"></i>
                                                                <span>Search</span>
                                                            </span>
                                                        </button>&#160;&#160;
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label></label>
                                                        <button class="form-control btn btn-secondary btn-secondary--icon" type="button" onClick="window.location.href='daily-reporting'">
                                                            <span>
                                                                <i class="la la-close"></i>
                                                                <span>Reset</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                        @endif
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="10%">User Name</th>
                                                    <th width="70%">Task </th>
                                                    <th>Added On</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="render_string">
                                                @foreach ($reports as $report)
                                                    <tr>
                                                        <td>{!! optional($report->users)->firstname !!}</td>
                                                        <td>{!! $report->task !!}</td>
                                                        <td>{!! $report->created_at !!}</td>
                                                        <td>
                                                            <button class="btn btn-icon btn-sm btn-danger" id="deletereport" data-id="{!! $report->report_id !!}"><i class="fa fa-times"></i></button>
                                                        </td>
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
				@include('admin/footer')
			</div>
		</div>
	</div>
	<div class="modal fade" id="header-modal" aria-hidden="true"></div>

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
                    headers:{"Content-Type":"multipart/form-data"},
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: mydata,
                });
            };

            $('#render_string').delegate('#deletereport', 'click', function() {
                var id=$(this).data('id');
                blockUI.block();
                request_call("{{ url('update-daily-reporting')}}", "id=" + id);
                xhr.done(function(mydata) {
                    blockUI.release();
                    Swal.fire({
                        icon:'success',
                        title:"Success!",
                        text:mydata.success,
                        type:'success'
                    }).then((result) => {
                        window.location.reload();
                    });
                });
            });
        });
	</script>
	</body>
</html>
