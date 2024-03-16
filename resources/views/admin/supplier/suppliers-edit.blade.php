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
								<form role="form" method="post" action="{{ url('post-supplier-edit') }}" enctype="multipart/form-data" id="supplier_detail">
									{!! csrf_field() !!}
									<input type="hidden" name="sup_id" value="{{ $supplier->sup_id }}" id="sup_id">
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">{{ $supplier->users->companyname }}</span>
											</h3>
                                            <div class="card-toolbar">
                                                <button class="btn btn-sm btn-success me-2">Approved Orders: {!! $approvedorders !!}</button>
                                                <button class="btn btn-sm btn-danger me-2">Rejected Orders: {!! $rejectedorders !!}</button>
                                            </div>
										</div>

										<div class="card-body">
											<div class="row">
												<div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="title">Email</label>
														<input type="text" class="form-control" name="email" value="{{ $supplier->users->email }}" required="" readonly="">
													</div>
												</div>
												<div class="col-md-3 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Purchase Manager</label>
														<select class="form-select form-select-solid fw-bolder form-select-solid fw-bolder" id="sales_person" name="sales_person">
														    <option value="" selected="">Select Purches Manager</option>
                                                            @foreach ($staff as $value)
											                    <option value="{{$value->id}}" {{ ($value->id == $supplier->users->added_by) ? 'selected' : '' }} >{{$value->firstname}}</option>
                                                            @endforeach
														</select>
													</div>
												</div>
												<div class="col-md-3 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Diamond Type</label>
														<select class="form-select form-select-solid fw-bolder form-select-solid fw-bolder" id="diamond_type" name="diamond_type">
														    <option value="" selected="">Select Diamond Type</option>
															<option value="Natural" {{ ($supplier->diamond_type == 'Natural') ? 'selected' : '' }} >Natural</option>
															<option value="Lab Grown" {{ ($supplier->diamond_type == 'Lab Grown') ? 'selected' : '' }} >Lab Grown</option>
														</select>
													</div>
												</div>
												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Hold Allow:</label>
														<label class="switch">
															<input type="checkbox" class="hold_allow" name="hold_allow" value="1" {{ ($supplier->hold_allow == 1)  ? "checked" : ''; }} data-onstyle="success">
															<span class="slider round"></span>
														</label>
                                                        </div>
												</div>
											</div>
                                            <div class="row">
                                                <div class="col-md-4 col-sm-18">
                                                    <label for="">Return Allow</label>
                                                    <select class="form-select form-select-solid fw-bolder form-select-solid fw-bolder" id="return_allow" name="return_allow">
                                                        <option value="1" {{ ($supplier->return_allow == '1') ? 'selected' : '' }}>Yes</option>
                                                        <option value="0" {{ ($supplier->return_allow == '0') ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-sm-18">
                                                    <label for="">Memo Allow</label>
                                                    <select class="form-select form-select-solid fw-bolder form-select-solid fw-bolder" id="memo_allow" name="memo_allow">
                                                        <option value="1" {{ ($supplier->memo_allow == '1') ? 'selected' : '' }}>Yes</option>
                                                        <option value="0" {{ ($supplier->memo_allow == '0') ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-sm-18">
                                                    <label for="">Markup</label>
                                                    <input type="text" class="form-control" name="markup" value="{{ $supplier->markup; }}">
                                                </div>
                                            </div>
										</div>
									</div>
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title">Company </h3>
										</div>
										<!--begin: Datatable-->
										<div class="card-body">
											<div class="row">
												<div class="col-md-6 col-sm-18">
													<div class="form-group">
														<label for="title">Company Name*:</label>
														<input type="text" class="form-control" name="company" value="{{ $supplier->users->companyname; }}">
													</div>
                                                    <div class="form-group">Mobile:</label>
														<input type="text" class="form-control input-phone" name="telphone" id="telphone" value="{{ $supplier->users->mobile; }}">
													</div>
													<div class="form-group">
														<label for="title">Website:</label>
														<input type="text" class="form-control" name="website" value="{{ $supplier->website; }}">
													</div>
													<div class="form-group">
														<label for="title">Address:</label>
														<textarea class="form-control" name="address" id="address">{{ $supplier->address; }}</textarea>
													</div>

                                                <div class="form-group">
                                                    <label class="col-lg-4 col-form-label ">Country :</label>

                                                    <div class="row">
                                                        <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                                            <select name="country" class="form-control"
                                                                id="countySel" size="1">
                                                                <option value="{{ $supplier->country }}" selected="selected">{{ $supplier->country }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-lg-4 col-form-label ">State :</label>

                                                    <div class="row">
                                                        <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                                            <select name="state" class="form-control"
                                                                id="stateSel" size="1">
                                                                <option value="{{ $supplier->state }}" selected="selected">{{ $supplier->state }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-lg-4 col-form-label ">City :</label>
                                                    <div class="row">
                                                        <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                                            <select name="city" class="form-control"
                                                                id="districtSel" size="1">
                                                                <option value="{{ $supplier->city }}" selected="selected">{{ $supplier->city }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


													<div class="form-group">
														<label for="title">Pin code:</label>
														<input type="text" class="form-control" name="pincode" value="{{ $supplier->zipcode; }}">
													</div>

													<div class="form-group">
														<!-- <label for="title">How many diamonds you have?:</label> -->
														<!-- <select class="form-control" placeholder="Select Range" name="drange">
																<option value="" selected="">Select Range </option>
																<option value="1-100">1-100</option>
																<option value="101-1000">101-1000</option>
																<option value="1001-2000">1001-2000</option>
																<option value="2001-4000">2001-4000</option>
																<option value="4000">4000 & above</option>
															</select>-->
														<!-- <input type="text" class="form-control" name="drange" value="< ?php echo $supplier->drange; ?>"> -->
													</div>
												</div>
												<div class="col-md-6 col-sm-18">
													<div class="form-group">
														<label for="title">Business Registration Number* (GST):</label>
														<input type="text" class="form-control" name="compnay_registration_number" value="{{ $supplier->compnay_registration_number; }}">
													</div>
													<div class="form-group">
														<label for="title">Business Registration Document*:</label>
														<input type="file" class="form-control" name="compnay_registration_document" value="{{ $supplier->compnay_registration_document; }}">
														 @if(!empty($supplier->compnay_registration_document))
															<a href="{{asset('uploads/suppliers_doc/'.$supplier->compnay_registration_document)}}" target="_blank">Business Registration Document</a>
                                                        @endif
													</div>
													<div class="form-group">
														<label for="title">Partner Name*:</label>
														<input type="text" class="form-control" name="compnay_partner_name" value="{{ $supplier->compnay_partner_name; }}">
													</div>
													<div class="form-group">
														<label for="title">Partner ID Proof* :</label>
														<input type="file" class="form-control" name="compnay_partner_document" value="{{ $supplier->compnay_partner_document; }}">
														 @if(!empty($supplier->compnay_partner_document))
															<a href="{{asset('uploads/suppliers_doc/'.$supplier->compnay_partner_document)}}" target="_blank">Partner ID Proof</a>
                                                        @endif
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title">Personal Info</h3>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="title">First Name*:</label>
														<input type="text" class="form-control" name="firstname" value="{{ $supplier->users->firstname; }}">
													</div>
													<div class="form-group">
														<label for="title">Last Name*:</label>
														<input type="text" class="form-control" name="lastname" value="{{ $supplier->users->lastname; }}">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="title">Designation*:</label>
														<select class="form-select form-select-solid fw-bolder" placeholder="Select Designation" name="designation" id="designation">
															<option value="" selected="">Select Designation</option>
															<option value="Director" <?php if ($supplier->designation == 'Director') {
																							echo 'selected';
																						} ?>>Director</option>
															<option value="CEO" <?php if ($supplier->designation == 'CEO') {
																					echo 'selected';
																				} ?>>CEO</option>
															<option value="Partner" <?php if ($supplier->designation == 'Partner') {
																						echo 'selected';
																					} ?>>Partner</option>
															<option value="Owner" <?php if ($supplier->designation == 'Owner') {
																						echo 'selected';
																					} ?>>Owner</option>
															<option value="Manager" <?php if ($supplier->designation == 'Manager') {
																						echo 'selected';
																					} ?>>Manager</option>
															<option value="Sales Executive" <?php if ($supplier->designation == 'Sales Executive') {
																								echo 'selected';
																							} ?>>Sales Executive</option>
															<option value="Others" <?php if ($supplier->designation == 'Others') {
																						echo 'selected';
																					} ?>>Others</option>
														</select>
													</div>
													<div class="form-group">
														<label for="title">Mobile Number:</label><br>
														<!--							<input type="text" class="form-control" name="moblie_no" value="<?php //echo $supplier->moblie_no;
																																						?>">-->
														<input id="phoneno" class="form-control input-phone" type="text" value="<?php echo !empty($supplier->users->mobile) ? $supplier->users->mobile : ''; ?>" name="moblie_no">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title">Broker Info</h3>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="title">Name :</label>
														<input type="text" class="form-control" name="brokername" value="{{ !empty($supplier->broker_name) ? $supplier->broker_name : ''; }}">
													</div>
													<div class="form-group">
														<label for="title">Email :</label>
														<input type="email" class="form-control" name="brokeremail" value="{{ !empty($supplier->broker_email) ? $supplier->broker_email : ''; }}">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="title">Mobile :</label>
														<input type="text" class="form-control" name="bokerphone" value="{{ !empty($supplier->boker_phone) ? $supplier->boker_phone : ''; }}">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title">Mode</h3>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6 col-sm-18">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="title">Stock Status:</label>
                                                            <select class="form-select form-select-solid fw-bolder stock_status" id="stock_status" name="stock_status">
                                                                <option value="">Select Stock Status</option>
                                                                <option value="ACTIVE" <?php echo ($supplier->stock_status == 'ACTIVE') ? 'selected="selected"' : ''; ?>>ACTIVE</option>
                                                                <option value="INACTIVE" <?php echo ($supplier->stock_status == 'INACTIVE') ? 'selected="selected"' : ''; ?>>INACTIVE</option>
                                                            </select>
                                                            <div class="form-group">
                                                                <label for="title">Upload Mode:</label>
                                                                <select class="form-select form-select-solid fw-bolder upload_mode" id="upload_mode" name="upload_mode">
                                                                    <option value="">Select Upload Mode</option>
                                                                    <option value="FTP" <?php echo ($supplier->upload_mode == 'FTP') ? 'selected="selected"' : ''; ?>>FTP</option>
                                                                    <option value="File" <?php echo ($supplier->upload_mode == 'File') ? 'selected="selected"' : ''; ?>>FILE</option>
                                                                    <option value="custom FTP" <?php echo ($supplier->upload_mode == 'custom FTP') ? 'selected="selected"' : ''; ?>>custom FTP</option>
                                                                    <option value="custom API" <?php echo ($supplier->upload_mode == 'custom API') ? 'selected="selected"' : ''; ?>>custom API</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
												</div>
												<div class="col-md-6 col-sm-18">
                                                    <input type="hidden" name="active_com" id="active_com" value="{{ ($active != null) ? $active->comment : '' }}">
                                                    <input type="hidden" name="inactive_com" id="inactive_com" value="{{ ($inactive != null) ? $inactive->comment : '' }}">
                                                    <input type="hidden" name="status" id="status" value="{{ $supplier->stock_status }}">
                                                    <label for="title">Reason For Changing Status:</label>
                                                    <textarea name="reason" id="reason" cols="30" rows="5" class="form-control" placeholder="Reason For Changing Status"></textarea>
                                                </div>
                                            </div>
											<div class="row mt-5 ">
												<div class="col-md-6 col-sm-18">
													<div class="form-group api" style="display:<?php if ($supplier->upload_mode == 'API') {
																									echo 'block';
																								} else {
																									echo 'none';
																								} ?>">
														<label for="title">Api Key:</label>
														<input type="text" class="form-control" name="api_key" value="{{ !empty($supplier->api_key) ? $supplier->api_key : ''; }}">
													</div>
													<div class="ftp" style="display:<?php if ($supplier->upload_mode == 'FTP' || $supplier->upload_mode == 'custom FTP') {
																						echo 'block';
																					} else {
																						echo 'none';
																					} ?>">
														<div class="col-md-6 col-sm-18">
															<div class="form-group">
																<label for="title">FTP Hostname:</label>
																<input type="text" class="form-control" name="ftp_host" value="{{ !empty($supplier->ftp_host) ? $supplier->ftp_host : ''; }}">
															</div>
															<div class="form-group">
																<label for="title">FTP Username:</label>
																<input type="text" class="form-control" name="ftp_username" value="{{ !empty($supplier->ftp_username) ? $supplier->ftp_username : ''; }}">
															</div>
														</div>
														<div class="col-md-6 col-sm-18">
															<div class="form-group">
																<label for="title">FTP password:</label>
																<input type="text" class="form-control" name="ftp_password" value="{{ !empty($supplier->ftp_password) ? $supplier->ftp_password : ''; }}">
															</div>
															<div class="form-group">
																<label for="title">FTP Port:</label>
																<input type="text" class="form-control" name="ftp_port" value="{{ !empty($supplier->ftp_port) ? $supplier->ftp_port : ''; }}">
															</div>
														</div>
														<div class="col-md-6 col-sm-18">
															<div class="form-group">
																<label for="title">FTP folder name: <span class="text-danger">(Folder name will be text before @thediamondport.com)</span></label>
																<input type="text" class="form-control" name="folder_name" value="{{ !empty($supplier->folder_name) ? $supplier->folder_name : ''; }}">
															</div>
														</div>
													</div>
													<div class="col-md-6 col-sm-18">
														<div class="form-group uplod_file" style="display:<?php if ($supplier->upload_mode == 'File') {
																												echo 'block';
																											} else {
																												echo 'none';
																											} ?>">
															<!--															<label for="title">Select File:</label>
																<input type="file" class="form-control" name="upload_mode_file" value="" ><a href="< ?php echo base_url() ?>assets/documents/< ?php echo!empty($supplier->file_name) ? $supplier->file_name : ''; ?>" target="_blank"><?php echo !empty($supplier->file_name) ? $supplier->file_name : ''; ?></a>-->
														</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-18">
                                                    <label for="title">FollowUp And Feedback:</label>
                                                    <textarea name="feedback" id="feedback" cols="30" rows="5" class="form-control mt-2" placeholder="Enter the Feedback"></textarea>
                                                </div>
											</div>
                                        </div>
                                        <div class="d-flex justify-content-end py-6 px-9">
                                        <button type="submit" class="ckditor btn btn-sm btn-primary">Update</button>
                                    </div>
                                    </div>
                                    @if (count($status_history) > 0)
                                        <div class="card mb-3 gutter-b">
                                            <div class="card-header">
                                                <h3 class="card-title">Supplier Status Change History</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table id="datatable" class="table table-bordered table-hover dataTable no-footer">
                                                            <thead>
                                                                <tr>
                                                                    <td>Supplier Name</td>
                                                                    <td>Updated By</td>
                                                                    <td>Feedback</td>
                                                                    <td>Comment</td>
                                                                    <td>Status</td>
                                                                    <td>Date</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($status_history as $item)
                                                                    <tr>
                                                                        <td>{!! $supplier->users->companyname !!}</td>
                                                                        <td>{!! $item->updatedBy->firstname !!}</td>
                                                                        <td>{!! $item->feedback !!}</td>
                                                                        <td>{!! $item->comment !!}</td>
                                                                        <td>{!! $item->status !!}</td>
                                                                        <td>{!! $item->date !!}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
    <script src="{{ asset('assets/js/countries.js') }}" type="text/javascript"></script>
	<!--end::Global Javascript Bundle-->

	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->

	<script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");

		// $('#kt_table_users').DataTable({
        //     'processing': true,
		// });

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

            var table = $('#datatable').DataTable({
				// "ordering": false,
				"scrollX": true,
				"pageLength": 50,
                order: [[5, 'desc']],
            });

            // $('#stock_status').on('change',function(){
            //     changable();
            // });

            function changable(){
                var stock_status = $('#stock_status').val();
                var active_com = $('#active_com').val();
                var inactive_com = $('#inactive_com').val();
                var status = $('#status').val();

                if(stock_status == 'INACTIVE'){
                    document.getElementById('reason').innerHTML=inactive_com;
                }
                else if(stock_status == 'ACTIVE'){
                    document.getElementById('reason').innerHTML=active_com;
                }
                else{
                    document.getElementById('reason').innerHTML="";
                }

                if(status != stock_status ){
                    document.getElementById("reason").required = true;
                }
                else{
                    document.getElementById("reason").required = false;
                }
            }

            $('#upload_mode').on('change', function() {
				var option = $("#upload_mode option:selected").text();
				if (option === 'custom API') {
					$(".api").css("display", "block");
					$(".ftp").css("display", "none");
					$(".uplod_file").css("display", "none");
				} else if (option === 'FTP') {
					$(".api").css("display", "none");
					$(".uplod_file").css("display", "none");
					$(".ftp").css("display", "block");
				} else if (option === 'custom FTP') {
					$(".api").css("display", "none");
					$(".uplod_file").css("display", "none");
					$(".ftp").css("display", "block");
				} else if (option === 'FILE') {
					$(".api").css("display", "none");
					$(".ftp").css("display", "none");
					$(".uplod_file").css("display", "block");
				}
			});

		});

		"use strict";
		var KTUsersList = (function () {
			var e,
				t,
				n,
				r,
				o = document.getElementById("kt_table_users"),
				c = () => {
					o.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach((t) => {
						t.addEventListener("click", function (t) {
							t.preventDefault();
							const n = t.target.closest("tr"),
								r = n.querySelectorAll("td")[1].querySelectorAll("a")[1].innerText;
							Swal.fire({
								text: "Are you sure you want to delete " + r + "?",
								icon: "warning",
								showCancelButton: !0,
								buttonsStyling: !1,
								confirmButtonText: "Yes, delete!",
								cancelButtonText: "No, cancel",
								customClass: { confirmButton: "btn fw-bold btn-danger", cancelButton: "btn fw-bold btn-active-light-primary" },
							}).then(function (t) {
								t.value
									? Swal.fire({ text: "You have deleted " + r + "!.", icon: "success", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn fw-bold btn-primary" } })
										.then(function () {
											e.row($(n)).remove().draw();
										})
										.then(function () {
											a();
										})
									: "cancel" === t.dismiss && Swal.fire({ text: customerName + " was not deleted.", icon: "error", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn fw-bold btn-primary" } });
							});
						});
					});
				},
				l = () => {
					const c = o.querySelectorAll('[type="checkbox"]');
					(t = document.querySelector('[data-kt-user-table-toolbar="base"]')), (n = document.querySelector('[data-kt-user-table-toolbar="selected"]')), (r = document.querySelector('[data-kt-user-table-select="selected_count"]'));
					const s = document.querySelector('[data-kt-user-table-select="delete_selected"]');
					c.forEach((e) => {
						e.addEventListener("click", function () {
							setTimeout(function () {
								a();
							}, 50);
						});
					}),
						s.addEventListener("click", function () {
							Swal.fire({
								text: "Are you sure you want to delete selected customers?",
								icon: "warning",
								showCancelButton: !0,
								buttonsStyling: !1,
								confirmButtonText: "Yes, delete!",
								cancelButtonText: "No, cancel",
								customClass: { confirmButton: "btn fw-bold btn-danger", cancelButton: "btn fw-bold btn-active-light-primary" },
							}).then(function (t) {
								t.value
									? Swal.fire({ text: "You have deleted all selected customers!.", icon: "success", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn fw-bold btn-primary" } })
										.then(function () {
											c.forEach((t) => {
												t.checked &&
													e
														.row($(t.closest("tbody tr")))
														.remove()
														.draw();
											});
											o.querySelectorAll('[type="checkbox"]')[0].checked = !1;
										})
										.then(function () {
											a(), l();
										})
									: "cancel" === t.dismiss &&
									Swal.fire({ text: "Selected customers was not deleted.", icon: "error", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn fw-bold btn-primary" } });
							});
						});
				};
			const a = () => {
				const e = o.querySelectorAll('tbody [type="checkbox"]');
				let c = !1,
					l = 0;
				e.forEach((e) => {
					e.checked && ((c = !0), l++);
				}),
					c ? ((r.innerHTML = l), t.classList.add("d-none"), n.classList.remove("d-none")) : (t.classList.remove("d-none"), n.classList.add("d-none"));
			};
			return {
				init: function () {
					o &&
						(o.querySelectorAll("tbody tr").forEach((e) => {
							const t = e.querySelectorAll("td"),
								n = t[3].innerText.toLowerCase();
							let r = 0,
								o = "minutes";
							n.includes("yesterday")
								? ((r = 1), (o = "days"))
								: n.includes("mins")
								? ((r = parseInt(n.replace(/\D/g, ""))), (o = "minutes"))
								: n.includes("hours")
								? ((r = parseInt(n.replace(/\D/g, ""))), (o = "hours"))
								: n.includes("days")
								? ((r = parseInt(n.replace(/\D/g, ""))), (o = "days"))
								: n.includes("weeks") && ((r = parseInt(n.replace(/\D/g, ""))), (o = "weeks"));
							const c = moment().subtract(r, o).format();
							t[3].setAttribute("data-order", c);
							const l = moment(t[5].innerHTML, "DD MMM YYYY, LT").format();
							t[5].setAttribute("data-order", l);
						}),
						(e = $(o).DataTable({
							info: !1,
							order: [],
							pageLength: 10,
							lengthChange: !1,
							columnDefs: [
								{ orderable: !1, targets: 0 },
								{ orderable: !1, targets: 5 },
							],
						})).on("draw", function () {
							l(), c(), a();
						}),
						l(),
						document.querySelector('[data-kt-user-table-filter="search"]').addEventListener("keyup", function (t) {
							e.search(t.target.value).draw();
						}),
						document.querySelector('[data-kt-user-table-filter="reset"]').addEventListener("click", function () {
							document
								.querySelector('[data-kt-user-table-filter="form"]')
								.querySelectorAll("select")
								.forEach((e) => {
									$(e).val("").trigger("change");
								}),
								e.search("").draw();
						}),
						c(),
						(() => {
							const t = document.querySelector('[data-kt-user-table-filter="form"]'),
								n = t.querySelector('[data-kt-user-table-filter="filter"]'),
								r = t.querySelectorAll("select");
							n.addEventListener("click", function () {
								var t = "";
								r.forEach((e, n) => {
									e.value && "" !== e.value && (0 !== n && (t += " "), (t += e.value));
								}),
									e.search(t).draw();
							});
						})());
				},
			};
		})();
		KTUtil.onDOMContentLoaded(function () {
			KTUsersList.init();
		});
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
