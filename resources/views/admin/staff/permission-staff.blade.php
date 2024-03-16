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


								@if ($errors->any())
									<div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
										@foreach ($errors->all() as $error)
											{{ $error }}
										@endforeach
									</div>
								@endif

                                <form action="{{ url('assign-permission')}}/{{$user_id}}"  method="post">
                                @csrf
                                <div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Permission List</span>
												<span class="text-muted fw-bold fs-7">{{ $user_name->firstname.' '.$user_name->lastname }}</span>
											</h3>
										</div>
                                        <div class="form-group mb-3 mt-3">
											<button type="submit" class="btn btn-sm btn-primary">Submit</button>
										</div>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5 data-table" id="myTable">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                            <th class="">Title</th>
                                                            <th class="">Menu</th>
                                                            <th class="">Full</th>
                                                            <th class="">Edit</th>
                                                            <th class="">Delete</th>
                                                            <!-- <th class="">Status</th>  -->
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold">
														@php
                                                        $user_has_permission = json_decode(json_encode($user_has_permission), true);
														$u_p =array();
														array_walk($user_has_permission, function ($v, $k) use (&$u_p) {$u_p[$v['permission_id']] = $v;});

														// echo "<pre>";
														// print_r($u_p);die;
                                                        @endphp

                                                        @foreach($permission as $data)
                                                            @php
                                                            $checked_menu = $checked_full = $checked_edit = $checked_delete = '';
                                                            if(in_array($data->permission_id, array_column($u_p, 'permission_id')))
                                                            {
                                                                $checked_menu = ($u_p[$data->permission_id]['menu'] == 1) ? 'checked' : '';
                                                                $checked_full = ($u_p[$data->permission_id]['full'] == 1) ? 'checked' : '';
                                                                $checked_edit = ($u_p[$data->permission_id]['edit'] == 1) ? 'checked' : '';
                                                                $checked_delete = ($u_p[$data->permission_id]['delete'] == 1) ? 'checked' : '';
                                                            }
                                                            @endphp
                                                            <tr>
                                                                <input type='hidden' name="permission[{{$data->permission_id}}][menu]" value="0">
                                                                <input type='hidden' name="permission[{{$data->permission_id}}][full]" value="0">
                                                                <input type='hidden' name="permission[{{$data->permission_id}}][edit]" value="0">
                                                                <input type='hidden' name="permission[{{$data->permission_id}}][delete]" value="0">

                                                                <td>{{$data->title}}</td>
                                                                <td><input type="checkbox" name="permission[{{$data->permission_id}}][menu]" value="1" {{$checked_menu}}></td>
                                                                <td><input type="checkbox" name="permission[{{$data->permission_id}}][full]" value="1" {{$checked_full}}></td>
                                                                <td><input type="checkbox" name="permission[{{$data->permission_id}}][edit]" value="1" {{$checked_edit}}></td>
                                                                <td><input type="checkbox" name="permission[{{$data->permission_id}}][delete]" value="1" {{$checked_delete}}></td>
                                                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                                            </tr>
                                                        @endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
                            </form>
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
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->

	<script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");
			var xhr;
			var total_selected = 0;
			var page_record_from = 0;
			var selected_ids = "";

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
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
