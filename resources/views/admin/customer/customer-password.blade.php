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

                                <form role="form" method="post" action="{{ url('update-customer-password')}}"  id="supplier_detail">
									@csrf
									<div class="card mb-3 gutter-b">
										<div class="card-header">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3">Change Password</span>
											</h3>
										</div>
										<div class="card-body">
										    <div class="row mb-5">
												<div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="title">New Password<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="password" >
													</div>
												</div>
                                            </div>
                                            <div class="row mb-5">
												<div class="col-md-4 col-sm-18">
													<div class="form-group">
														<label for="title">Confirm Password<span class='text-danger'>*</span></label>
														<input type="text" class="form-control" name="password_confirmation" >
													</div>
												</div>
                                            </div>

                                                <div class="form-group mb-3 mt-3">
														<button type="submit" class="ckditor btn btn-sm btn-primary"> Change Password</button>
												</div>
										</div>
									</div>
                                    <input type="hidden" name="id" value="{{$customer_detail->id}}">
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
    <script src="{{asset('assets/js/countries.js')}}" t1LBf6zWdVYz9oN1Puctivrt8CLk6kburAP Javascript Bundle-->

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
