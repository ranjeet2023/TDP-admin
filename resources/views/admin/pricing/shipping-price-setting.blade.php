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
                                        <h3 class="card-title">Shipping Price</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-hover" id="datatable">
                                            <thead>
                                                <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                    <th class="column-title">Location</th>
                                                    <th class="column-title">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="render_string">
                                                <?php
                                                if (!empty($country_lists)) {
                                                    foreach ($country_lists as $value) { ?>
                                                        <tr>
                                                            <td><?php echo $value->location; ?></td>
                                                            <td><button data-id="<?= $value->location; ?>" class="btn btn-sm btn-primary showpricing"><i class="fas fa-external-link-alt pl-2"></i></button></td>
                                                        </tr>
                                                    <?php }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="100%">No Record Found!!</td>
                                                    </tr>
                                                <?php } ?>
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
		$(document).ready(function () {
			$('#datatable').DataTable({
				"bDestroy": true,
				"processing": true,
				//scrollY: true,
				//scrollX: true,
				//scrollCollapse: true,
				columnDefs: [
					{ orderable: false, targets: [-1] }
				]
			});
			$(".dataTable").wrap('<div class="dataTables_scroll" />');
			var xhr;
			function request_call(url, mydata)
			{
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

			$("#render_string").delegate('.showpricing', 'click', function () {
				var location = $(this).attr('data-id');
				var html = '';
				request_call("{{ url('shippingpricelist') }}", "id=" + location);
				xhr.done(function (mydata) {
					if (mydata.pricelist != "")
					{
						for(var i=0; i < mydata.pricelist.length; i++)
						{
							html += "<tr><td>"+mydata.pricelist[i].min_range+"-"+mydata.pricelist[i].max_range+"</td>";
							html += "<td><input type='input' value='"+mydata.pricelist[i].pricechange+"' name="+mydata.pricelist[i].id+" class='form-control col-md-3' /></td></tr>";
						}

						$("#header-modal").html("<div class='modal-dialog modal-lg'><div class='modal-content'>"
								+'<div class="modal-header">'
									+'<h4 class="modal-title">Price Setting</h4>'
									+'<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>'
								+'</div>'
								+ "<form name='save_shippingprice'id='save_shippingprice' >"
								+ "<div class='modal-body'>"
								+ '<table class="table table-striped jambo_table bulk_action"><thead><tr class="fw-bolder fs-6 text-gray-800 px-7"><th class="column-title">Range</th><th class="column-title">Value</th></tr></thead><tbody id="render_string">'
								+ html
								+ '</tbody></table>'
								+ "</div>"
								+ "<div class='modal-footer text-center'>"
								+ "<button type='button' class='btn btn-primary btn-embossed bnt-square' id='save'><i class='fa fa-check'></i> Save</button>"
								+ "</div>"
								+ "</from>"
								+ "</div>"
								+ "</div>");
						$('#header-modal').modal('show');
					}
				});
			});

			$("body").delegate('#save', 'click', function () {
				var str = $("#save_shippingprice").serialize();
                blockUI.block();
				request_call("{{ url('save-shippingpricelist') }}", str);
				xhr.done(function (mydata) {
                    blockUI.release();
					$('#header-modal').modal('hide');
				});
			});
		});
	</script>
</body>
</html>
