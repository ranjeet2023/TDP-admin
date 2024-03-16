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

    <style>
        .table tr{
            white-space: nowrap; overflow: hidden; text-overflow:ellipsis;
        }
    </style>

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
                        @if(Session::has('Success'))
                            <div class="alert alert-success alert-icon" role="alert"><i class="uil uil-times-circle"></i>
                                {{ session()->get('Success') }}
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
                                    <span class="card-label fw-bolder text-dark">Image Video Request</span>
                                </h3>
                            </div>
                            <div class="card-body">
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade active show in" >
										<div class="table-responsive" style="margin-top: 10px;">
											<table class="table table-striped jambo_table bulk_action">
												<thead>
													<tr class="headings">
														<th class="column-title">Actions</th>
														<th class="column-title">Customer Name</th>
														<th class="column-title">Supplier Name</th>
														<th class="column-title">Certificate Number</th>
														<th class="column-title">Diamond Type</th>
														<th class="column-title">Carat</th>
														<th class="column-title">Shape</th>
														<th class="column-title">Color</th>
														<th class="column-title">Clarity</th>
														<th class="column-title">Cut</th>
														<th class="column-title">Polish</th>
														<th class="column-title">Symmetry</th>
														<th class="column-title">Florance</th>
														<th class="column-title">Lab</th>
														<th class="column-title">Image</th>
														<th class="column-title">Video</th>
														<th class="column-title">Created On</th>
													</tr>
												</thead>
												<tbody id="render_string">
                                                    @foreach ($records as $record)
                                                        <tr>
                                                            <td>
                                                                <button class="btn btn-sm btn-icon btn-info imageupload" data-id="{{ $record['id'] }}"><i class="fa fa-image"></i></button>
                                                                <button class="btn btn-sm btn-icon btn-warning videoupload" data-id="{{ $record['id'] }}" ><i class="fa fa-video"></i></button>
                                                            </td>
                                                            <td>{{ $record['customer'] }}</td>
                                                            <td>{{ $record['supplier'] }}</td>
                                                            <td>{{ $record['certificate_no'] }}</td>
                                                            <td>{{ ($record['diamond_type'] == 'L' ? 'Lab Grown' : 'Natural') }}</td>
                                                            <td>{{ number_format($record['carat'],2) }}</td>
                                                            <td>{{ $record['shape'] }}</td>
                                                            <td>{{ $record['color'] }}</td>
                                                            <td>{{ $record['clarity'] }}</td>
                                                            <td>{{ $record['cut'] }}</td>
                                                            <td>{{ $record['polish'] }}</td>
                                                            <td>{{ $record['symmetry'] }}</td>
                                                            <td>{{ $record['fluorescence'] }}</td>
                                                            <td>{{ $record['lab'] }}</td>
                                                            <td>
                                                                @if(!empty($record['image']))
                                                                    <input type="text" value="{{ $record['image'] }}" id="thelinkimg" style="display:none;">
                                                                    <button type="button" class="btn btn-sm btn-success btn-icon linktocopy" data-type="image"><i class="fa fa-copy"></i></button>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(!empty($record['video']))
                                                                    <input type="text" value="{{ $record['video'] }}" id="thelinkvid" style="display:none;">
                                                                    <button type="button" class="btn btn-sm btn-success btn-icon linktocopy" data-type="video"><i class="fa fa-copy"></i></button>
                                                                @endif
                                                            </td>
                                                            <td>{{ $record['created_at'] }}</td>
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
                    headers:{"Content-Type":"multipart/form-data"},
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: mydata,
                });
            };

            $('#render_string').delegate('.linktocopy', 'click', function() {
                var type = $(this).attr('data-type');
                if(type == 'image'){
                    var copyText = document.getElementById("thelinkimg");
                }
                else{
                    var copyText = document.getElementById("thelinkvid");
                }
                var test = '';

                copyText.select();
                copyText.setSelectionRange(0, 99999);

                navigator.clipboard.writeText(copyText.value);
                Swal.fire( type +'link Copied!');
            })

            $('#render_string').delegate('.imageupload', 'click', function() {
				var id = $(this).attr('data-id');
                Swal.fire({
                    width:'30%',
                    title : 'Upload Image!',
                html: ` <form action="{{ url('image-video-request-post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                                <div class="container">
                                    <input type="file"  class="form-control" name="image" id="image" style="min-height:40px;" Required accept="image/png, image/jpeg">
                                    <input type="hidden" value="`+ id +`" name="id">
                                    <input type="hidden" value="image" name="type">
                                    <br/>
                                    <input type="submit" class="btn btn-sm btn-warning text-dark" value="Submit" style="width:100px;">
                                </div>
                        </form> `,
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                })
            });

            $('#render_string').delegate('.videoupload', 'click', function() {
				var id = $(this).attr('data-id');
                Swal.fire({
                    width:'30%',
                    showCloseButton: true,
                    title : 'Upload Video!',
                html: ` <form action="{{ url('image-video-request-post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="container">
                                <input type="file"  class="form-control" name="video" id="video" style="min-height:40px;" Required accept="video/*">
                                <input type="hidden" value="`+ id +`" name="id">
                                <input type="hidden" value="Video" name="type">
                                <br/>
                                <input type="submit" class="btn btn-sm btn-warning text-dark" value="Submit" style="width:100px;">
                            </div>
                        </form> `,
                    showCancelButton: false,
                    showConfirmButton: false,
                })
            })

        });

    </script>
</body>
</html>
