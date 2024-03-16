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

                                @if(Session::has('update'))
                                    <div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                        {{ session()->get('update') }}
                                    </div>
								@endif
								<form role="form" method="post" action="{{ url('post-add-associate')}}"  id="supplier_detail">
									@csrf
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3">Add Associate</span>
											</h3>
										</div>
										<div class="card-body">
											<div class="row mb-5">
												<div class="col-md-4">
													<div class="form-group">
														<label for="title"> Name<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{old('name')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Email<span class='text-danger'>*</span></label>
                                                        <input type="email" class="form-control" name="email" placeholder="Enter Email Id" value="{{old('email')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="title">Phone<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="mobile" placeholder="Enter phone Number" value="{{old('mobile')}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Address<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="address" placeholder="Enter Address" value="{{old('address')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="port_loading">Port Of Loading<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="port_loading" placeholder="port loading" value="{{old('port_loading')}}" required >
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="carrier_place">carrier place<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="carrier_place" placeholder="carrier place" value="{{old('carrier_place')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="gst_no">GST No.</label>
                                                        <input type="text" class="form-control" name="gst_no" placeholder="GST No" value="{{old('gst_no')}}">
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="pan_no">PAN No.</label>
                                                        <input type="text" class="form-control" name="pan_no" placeholder="PAN No" value="{{old('pan_no')}}">
													</div>
												</div>
                                            </div>
										</div>
									</div>
                                    <div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3">Bank Detail</span>
											</h3>
										</div>
										<div class="card-body">
											<div class="row mb-5">
												<div class="col-md-4">
													<div class="form-group">
														<label for="title">Account Number<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="accountnumber" placeholder="Enter Account Number" value="{{old('accountnumber')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Bank Name<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="bankname" placeholder="Enter Bank Name" value="{{old('bankname')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="branch Name">Branch Name<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="branchname" placeholder="Enter Branch Name" value="{{old('branchname')}}" >
													</div>
												</div>
                                            </div>

                                            <div class="row mb-5">
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="title">Bank Address<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="bankaddress" placeholder="Enter Bank Address" value="{{old('bankaddress')}}" required>
													</div>
                                                </div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="title">IFSC Code<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="ifsccode" placeholder="Enter IFSC Code" value="{{old('ifsccode')}}" required>
													</div>
                                                </div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Address Code<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="addresscode" placeholder="Enter Addresse Code" value="{{old('addresscode')}}" required>
													</div>
												</div>
                                            </div>

                                            <div class="row mb-5">
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Swift Code<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="swiftcode" placeholder="Enter Swift Code" value="{{old('swiftcode')}}" required>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="title">Routing Number (Bank Wire)<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="routingbank" placeholder="Enter Routing Number" value="{{old('routingbank')}}" required>
													</div>
												</div>

                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Routig Number(Dir.Deposite & ACH)<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="routingdirect" placeholder="Enter Routing Name" value="{{old('routingdirect')}}" required>
													</div>
												</div>
                                            </div>
                                            <div class="row mb-5">
												<div class="col-md-4">
													<div class="form-group">
														<label for="title">Intermediary Bank<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="intermediarybank" placeholder="Enter Intermediary Number" value="{{old('intermediarybank')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="intermediaryswiftcode">Intermediary Bank Swift Code</label>
                                                        <input type="text" class="form-control" name="intermediaryswiftcode" placeholder="Enter Intermediary Swift Code" value="{{old('intermediaryswiftcode')}}">
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="bsbcode">BSB Code</label>
                                                        <input type="text" class="form-control" name="bsbcode" placeholder="Enter BSB Code" value="{{old('bsbcode')}}">
													</div>
												</div>
                                            </div>

                                            <div class="row mb-5">
												<div class="col-md-4">
													<div class="form-group">
														<label for="title">HSN Code Natural<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="hsncodenatural" placeholder="Enter HSN Code Natural" value="{{old('hsncodenatural')}}" required>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">HSN Code Natural One<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="onehsncode" placeholder="Enter HSN Code Natural" value="{{old('onehsncode')}}" required>
													</div>
												</div>

                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">HSN Code Lab <span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="hsncodelab" placeholder="Enter HSN Code Lab" value="{{old('hsncodelab')}}" required>
													</div>
												</div>
                                            </div>
                                            <div class="form-group mb-3 mt-3">
												<button type="submit" class="ckditor btn btn-sm btn-primary"> Add Associate</button>
											</div>
										</div>
									</div>
								</form>
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

	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->

	<script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");

		$(document).ready(function() {
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
		});
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
