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
								@if(Session::has('update'))
								<div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
									{{ session()->get('update') }}
								</div>
								@endif

								<!-- @if ($errors->any())
									<div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
										@foreach ($errors->all() as $error)
											{{ $error }}
										@endforeach
									</div>
								@endif -->


								<form role="form" method="post" action="{{ url('post-diamond')}}" enctype="multipart/form-data" id="add-diamond">
									@csrf
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3">Add Diamond</span>
											</h3>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-3 col-sm-18">
													<div class="form-group">
														<label for="title">Supplier Name<span class='text-danger'>*</span></label>
                                                        <select id="supplier" name="supplier" class="form-select">
                                                            @foreach ($suppliers as $supplier)
                                                            <option  value="{{$supplier->sup_id}}" id="color">{{$supplier->users->companyname}}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('supplier')
                                                                <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Lot no<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="lotno" value="{{ old('lotno') }}">
                                                        @error('lotno')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Shape<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="shape" value="{{ old('shape') }}">
                                                        @error('shape')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Carat<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="carat" value="{{ old('carat') }}">
                                                        @error('carat')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Color<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="color" value="{{ old('color') }}">
                                                        @error('color')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>
                                            </div>

                                            <div class="row">
												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Clarity<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="clearity" value="{{ old('clearity') }}">
                                                        @error('clearity')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Cut<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="cut" value="{{ old('cut') }}">
                                                        @error('cut')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Polish<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="polish" value="{{ old('polish') }}">
                                                        @error('polish')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Symmetry<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="sym" value="{{ old('sym') }}">
                                                        @error('sym')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Fluorescence<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="fluorescence" value="{{ old('fluorescence') }}">
                                                        @error('fluorescence')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Lab<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="lab" value="{{ old('lab') }}">
                                                        @error('lab')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>
                                            </div>

                                            <div class="row">
												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Certificate<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="certificate" value="{{ old('certificate') }}">
                                                        @error('certificate')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Actual $/CT<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="ct" value="{{ old('ct') }}">
                                                        @error('ct')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="originalrate">Orignal $/CT<span class='text-danger'>*</span>($)</label>
                                                        <input type="number" class="form-control" name="orignalrate" value="{{ old('orignalrate') }}">
                                                        @error('orignalrate')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="netdollar">Buy Pricer<span class='text-danger'>*</span>($)</label>
                                                        <input type="number" class="form-control" name="netdollar" value="{{ old('netdollar') }}">
                                                        @error('netdollar')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Depth %<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="depthp" value="{{ old('depthp') }}">
                                                        @error('depthp')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Table %<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="tablep" value="{{ old('tablep') }}">
                                                        @error('tablep')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Length<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="length" value="{{ old('length') }}">
                                                        @error('length')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Width<span class='text-danger'>*</span></label>
                                                        <input type="text" class="form-control" name="width" value="{{ old('width') }}">
                                                        @error('width')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>
												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Depth<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="depth" value="{{ old('depth') }}">
                                                        @error('depth')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">C.Height</label>
														<input type="text" class="form-control" name="cheight" value="{{ old('cheight') }}">
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">C.Angle</label>
														<input type="text" class="form-control" name="cangle" value="{{ old('cangle') }}">
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">P.Height</label>
														<input type="text" class="form-control" name="pheight" value="{{ old('pheight') }}">
													</div>
												</div>

												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">P.Angle</label>
                                                        <input type="text" class="form-control" name="pangle" value="{{ old('pangle') }}">
													</div>
												</div>

												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Shade</label>
														<input type="text" class="form-control" name="shade" value="{{ old('shade') }}">
													</div>
												</div>


                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Milky</label>
														<input type="text" class="form-control" name="milky" value="{{ old('milky') }}">
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Eye Clean</label>
														<input type="text" class="form-control" name="eyeclean" value="{{ old('eyeclean') }}">
													</div>
												</div>

												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Key to symbol</label>
                                                        <input type="text" class="form-control" name="keysymbol" value="{{ old('keysymbol') }}">
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Country<span class='text-danger'>*</span></label>
                                                        <select name="country"  class="form-control" id="countySel" size="1">
                                                            <option value="" selected="selected">Select Country</option>
                                                        </select>
                                                        @error('country')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
													</div>
												</div>

                                            </div>

                                            <div class="row">
												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Image</label>
														<input type="text" class="form-control" name="image" value="{{ old('image') }}">
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Video</label>
														<input type="text" class="form-control" name="video" value="{{ old('video') }}">
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Heart</label>
														<input type="text" class="form-control" name="heart" value="{{ old('heart') }}">
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="title">Arrow</label>
														<input type="text" class="form-control" name="arrow" value="{{ old('arrow') }}">
													</div>
												</div>

												<div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Asset</label>
                                                        <input type="text" class="form-control" name="asset"value="{{ old('asset') }}" >
													</div>
												</div>

                                                <div class="col-md-2 col-sm-18">
													<div class="form-group">
														<label for="salesperson">Diamond Type<span class='text-danger'>*</span></label>
                                                        <select name="diamond_type" class="form-select form-select-solid fw-bolder form-select-solid fw-bolder"  >
                                                        <option value="">Select </option>
                                                        <option value="W" >Natural</option>
                                                        <option value="L" >Lab Grown</option>
                                                        </select>
                                                        @error('diamond_type')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                </div>

                                            <div class="form-group mb-3 mt-3">
												<button type="submit" class="ckditor btn btn-sm btn-primary"> Add Diamond</button>
											</div>
                                        </div>
									</div>
								</form>
							<div class="card mb-3 gutter-b">
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped jambo_table bulk_action">
                                                <thead>
                                                    <tr class="fw-bolder fs-6 text-gray-800 px-7">

                                                        <th>
                                                            Delete
                                                        </th>
                                                        <th class="column-title">
                                                            Supplier Name
                                                        </th>
                                                        <th class="column-title">lotno</th>
                                                        <th class="column-title">Shape</th>
                                                        <th class="column-title">Carat</th>
                                                        <th class="column-title">Color</th>
                                                        <th class="column-title">Clearity</th>
                                                        <th class="column-title">Cut</th>
                                                        <th class="column-title">Polish</th>
                                                        <th class="column-title">Symmetry</th>
                                                        <th class="column-title">Fluorescence</th>
                                                        <th class="column-title">Lab</th>
                                                        <th class="column-title">Certificate</th>
                                                        <th class="column-title">$/CT</th>
                                                        <th class="column-title">Depth %</th>
                                                        <th class="column-title">Table %</th>
                                                        <th class="column-title">Length</th>
                                                        <th class="column-title">Width</th>
                                                        <th class="column-title">Depth</th>
                                                        <th class="column-title">C.Height</th>
                                                        <th class="column-title">C.Angle</th>
                                                        <th class="column-title">P.Height</th>
                                                        <th class="column-title">P.Angle</th>
                                                        <th class="column-title">Shade</th>
                                                        <th class="column-title">Milky</th>
                                                        <th class="column-title">Eye Clean</th>
                                                        <th class="column-title">Key to symbol</th>
                                                        <th class="column-title">Country</th>
                                                        <th class="column-title">Orignal Rate </th>
                                                        <th class="column-title">Net Dollar</th>
                                                        <th class="column-title">Image</th>
                                                        <th class="column-title">Video</th>
                                                        <th class="column-title">Heart</th>
                                                        <th class="column-title">Arrow</th>
                                                        <th class="column-title">Asset</th>
                                                        <th class="column-title">Diamond Type</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($diamond_list as $diamond)
                                                        <tr>

                                                        <td>
                                                            <a href="{{url('delete-diamond')}}/{{$diamond->id}}/{{$diamond->diamond_type}}">Delete</a>
                                                        </td>
                                                        <td>{{$diamond->supplier_name}} </td>
                                                        <td>{{$diamond->ref_no}}</td>
                                                        <td>{{$diamond->shape}}</td>
                                                        <td>{{$diamond->carat}}</td>
                                                        <td>{{$diamond->color}}</td>
                                                        <td>{{$diamond->clarity}}</td>
                                                        <td>{{$diamond->cut}}</td>
                                                        <td>{{$diamond->polish}}</td>
                                                        <td>{{$diamond->symmetry}}</td>
                                                        <td>{{$diamond->fluorescence}}</td>
                                                        <td>{{$diamond->lab}}</td>
                                                        <td>{{$diamond->certificate_no}}</td>
                                                        <td>{{$diamond->rate}}</td>
                                                        <td>{{$diamond->depth_per}}</td>
                                                        <td>{{$diamond->table_per}}</td>
                                                        <td>{{$diamond->length}}</td>
                                                        <td>{{$diamond->width}}</td>
                                                        <td>{{$diamond->depth}}</td>
                                                        <td>{{$diamond->crown_height}}</td>
                                                        <td>{{$diamond->crown_angle}}</td>

                                                        <td>{{$diamond->pavilion_depth}}</td>
                                                        <td>{{$diamond->pavilion_angle}}</td>
                                                        <td>{{$diamond->shade}}</td>
                                                        <td>{{$diamond->milky}}</td>
                                                        <td>{{$diamond->eyeclean}}</td>
                                                        <td>{{$diamond->key_symbols}}</td>
                                                        <td>{{$diamond->country}}</td>
                                                        <td>{{$diamond->orignal_rate}}</td>
                                                        <td>{{$diamond->net_dollar}}</td>
                                                        <td>{{$diamond->image}}</td>
                                                        <td>{{$diamond->video}}</td>
                                                        <td>{{$diamond->heart}}</td>
                                                        <td>{{$diamond->arrow}}</td>
                                                        <td>{{$diamond->asset}}</td>
                                                        <td>{{$diamond->diamond_type}}</td>
                                                        </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>
                                            <ul class="pagination">
                                                <li class="page-item previous disabled" id="previous_page">
                                                    <a href="javascript:void(0)"><span class="page-link">Previous</span></a>
                                                </li>
                                                <li class="page-item next" id="next_page">
                                                    <a href="javascript:void(0)" class="page-link">Next</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link"><span id="pagecount">1</span> to <span id="totalrecord"></span> Total Pages</a>
                                                </li>
                                            </ul>
                                            <!--<div id="add_to_cart">add_to_cart   </div>-->
                                        </div>
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
    <script src="{{asset('assets/js/countries.js')}}" type="text/javascript"></script>
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
