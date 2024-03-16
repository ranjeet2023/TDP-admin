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

                                @foreach ($associate_info as $associate)
                                    <form role="form" method="post" action="{{ url('update-associate-detail')}}">
									@csrf
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3">Edit Associate Detail</span>
											</h3>
										</div>
										<div class="card-body">
											<div class="row mb-5">
												<div class="col-md-4">
													<div class="form-group">
														<label for="name">Name<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="name" value="{{$associate->name}}">
													</div>
												</div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
														<label for="email">Email<span class='text-danger'>*</span></label>
                                                        <input type="email" class="form-control" name="email" value="{{$associate->email}}">
													</div>
                                                </div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="mobile">Phone<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="mobile" value="{{$associate->mobile}}">
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="port_loading">Country<span class='text-danger'>*</span></label>
                                                        <select class="form-select" name="country">
                                                            <option value="">Select a country</option>
                                                            <option value="Mumbai" {{ ($associate->country ==  'Mumbai' ) ? 'selected' : '' }}>Mumbai</option>
                                                            <option value="USA"{{ ($associate->country ==  'USA' ) ? 'selected' : '' }}>USA</option>
                                                            <option value="Hongkong"{{ ($associate->country ==  'Hongkong' ) ? 'selected' : '' }}>Hong Kong</option>
                                                            <option value="Australia"{{ ($associate->country ==  'Australia ' ) ? 'selected' : '' }}>Australia</option>
                                                        </select>
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="port_loading">Port Of Loading<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="port_loading" value="{{$associate->port_loading}}" >
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="carrier_place">carrier place<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="carrier_place" value="{{$associate->carrier_place}}" >
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="address">Address<span class='text-danger'>*</span></label>
                                                        <textarea class="form-control" name="address" >{{$associate->address}}</textarea>
													</div>
												</div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="carrier_place">GST No.</label>
                                                        <input type="text" class="form-control" name="gst_no" value="{{$associate->gst_no}}" >
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="carrier_place">PAN No.</label>
                                                        <input type="text" class="form-control" name="pan_no" value="{{$associate->pan_no}}" >
													</div>
												</div>
                                            </div>
										</div>
									</div>
                                    <input type="hidden" name="id" value="{{$associate->id}}">
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
														<input type="text" class="form-control" name="accountnumber" value="{{$associate->account_number}}">
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Bank Name<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="bankname" value="{{$associate->bank_name}}" >
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="branch Name">Branch Name<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="branchname" value="{{$associate->branch_name}}" >
													</div>
												</div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="title">Bank Address<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="bankaddress" value="{{$associate->bank_address}}">
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="title">IFSC Code<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="ifsccode" value="{{$associate->ifsc_code}}">
													</div>
												</div>
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Address Code<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="addresscode" value="{{$associate->address_code}}" >
													</div>
												</div>
                                            </div>

                                            <div class="row mb-5">
                                                <div class="col-md-4">
													<div class="form-group">
														<label for="salesperson">Swift Code<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="swiftcode" value="{{$associate->swift_code}}">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="title">Routing Number (Bank Wire)<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="routingbank" value="{{$associate->routing_bank_number}}">
													</div>
												</div>
                                                <div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Routig Number(Dir.Deposite & ACH)<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="routingdirect" value="{{$associate->routig_number_directs_deposite}}" >
													</div>
												</div>
                                            </div>
                                            <div class="row mb-5">
												<div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="title">Intermediary Bank<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="intermediarybank" value="{{$associate->intermediary_bank}}">
													</div>
												</div>
                                                <div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Intermediary Bank Swift Code</label>
                                                        <input type="text" class="form-control" name="intermediaryswiftcode" value="{{$associate->intermediary_swift_code}}" >
													</div>
												</div>
                                                <div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="bsbcode">BSB Code</label>
                                                        <input type="text" class="form-control" name="bsbcode" value="{{$associate->bsb_code}}" >
													</div>
												</div>
                                            </div>
                                            <div class="row mb-5">
												<div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="title">HSN Code Natural<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="hsncodenatural" value="{{$associate->hsn_code_natural}}">
													</div>
												</div>
                                                <div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="salesperson">HSN Code Natural above One<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="onehsncode" value="{{$associate->hsn_code_natural_one}}">
													</div>
												</div>
                                                <div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="salesperson">HSN Code Lab <span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="hsncodelab" value="{{$associate->hsn_code_lab}}">
													</div>
												</div>
                                            </div>
                                            <div class="form-group mb-3 mt-3">
												<button type="submit" class="ckditor btn btn-sm btn-primary"> Update</button>
											</div>
										</div>
									</div>
                                    </form>
                                @endforeach
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
