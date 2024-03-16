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
                                    <span class="card-label fw-bolder text-dark">Invalid Diamond</span>
                                </h3>
								<div class="card-toolbar">
									<select name="diamond_type" class="form-control" style="padding-top:5px;" id="diamond_type">
										<option class="form-control dropdown" value="diamond_natural">Natural</option>
										<option class="form-control dropdown" value="diamond_labgrown">Lab Grown</option>
									</select>
								</div>
							</div>
                            <div class="card-body">

								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade active show in" >
										<div class="table-responsive" style="margin-top: 10px;">
											<table class="table table-striped jambo_table bulk_action" id="red_alert_datatable">
												<thead>
													<tr class="headings">
														<th class="column-title">PARTY</th>
														<th class="column-title">STONE NUMBER</th>
														<th class="column-title">REF NUMBER</th>
														<th class="column-title">SHAPE</th>
														<th class="column-title">CARAT</th>
														<th class="column-title">COLOR</th>
														<th class="column-title">CLARITY</th>
														<th class="column-title">CUT</th>
														<th class="column-title">LAB</th>
														<th class="column-title">CERTIFICATE</th>
														<th class="column-title">DISCOUNT</th>
														<th class="column-title">ORIGINAL PRICE</th>
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
            };

			invalid_diamond();

            $('#diamond_type').change(function(e){
				invalid_diamond();
			});

			function invalid_diamond(){
				var type=$("#diamond_type").val();
                blockUI.block();
				request_call("{{ route('admin.invalid-discount-post')}}", "diamond_type="+type );
				xhr.done(function(mydata) {
					var html_d = '';
                    blockUI.release();
					$(mydata.detail).each(function(key,value){
                        var og_price = value.orignal_rate*value.carat ;
						html_d += '<tr>'
							+'<td>'+value.supplier_name+'</td>'
							+'<td>'+value.id+'</td>'
							+'<td>'+value.ref_no+'</td>'
							+'<td>'+value.shape+'</td>'
							+'<td>'+value.carat.toFixed(2)+'</td>'
							+'<td>'+value.color+'</td>'
							+'<td>'+value.clarity+'</td>'
							+'<td>'+value.cut+'</td>'
							+'<td>'+value.lab+'</td>'
							+'<td>'+value.certificate_no+'</td>'
							+'<td>'+value.discount.toFixed(2)+'</td>'
							+'<td>'+og_price.toFixed(2)+'</td>'
						+'</tr>';
					});

					$('#render_string').html(html_d);
                });
			}

        });

    </script>
</body>
</html>
