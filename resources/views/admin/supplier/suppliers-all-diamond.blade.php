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
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			@include('admin/sidebar')
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				@include('admin/header')
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<div id="kt_content_container" class="container-xxl">
                        <div class="card card-custom gutter-b">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Suppliers All Diamond</span>
                                    <span class="text-muted fw-bold fs-7">{{ !empty($supplier) ? $supplier->companyname : ''; }}</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('download-images', request()->route('id')) }}"class="btn btn-info btn-sm btn-icon me-4" data-placement="top" data-toggle="tooltip"><i class="fa fa-download"></i></a>
                                    <button class="btn btn-primary btn-sm me-4 images_count" title="Number Of Images" data-placement="top" data-toggle="tooltip" data-original-title="Images Count">Images ={{ $imagescount }}</button>
                                    <button class="btn btn-primary btn-sm me-4 videos_count" title="Number Of Videos" data-placement="top" data-toggle="tooltip" data-original-title="Videos Stone">Videos ={{ $videoscount }}</button>
                                    <button class="btn btn-primary btn-sm me-4 total_record" title="Total Stone" data-placement="top" data-toggle="tooltip" data-original-title="Total Stone">Total Stone ={{ $stonecount }}</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped jambo_table bulk_action">
                                                <thead>
                                                    <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <td></td>
                                                        <th class="column-title">Stone No</th>
                                                        <th class="column-title">Ref Stone</th>
                                                        <th class="column-title">Avail</th>
                                                        <th class="column-title">Shape</th>
                                                        <th class="column-title">@sortablelink('carat')</th>
                                                        <th class="column-title">Col</th>
                                                        <th class="column-title">Clarity</th>
                                                        <th class="column-title">Cut</th>
                                                        <th class="column-title">Pol</th>
                                                        <th class="column-title">Sym</th>
                                                        <th class="column-title">Flo</th>
                                                        <th class="column-title">Lab</th>
                                                        <th class="column-title">Certificate</th>
                                                        <th class="column-title">@sortablelink('net_dollar','Orignal Price')</th>
                                                        <th class="column-title">@sortablelink('orignal_rate','$ per carat')</th>
                                                        <th class="column-title">discount</th>
                                                        <th class="column-title">Table</th>
                                                        <th class="column-title">Depth</th>
                                                        <th class="column-title">Griddle</th>
                                                        <th class="column-title">Griddle Per</th>
                                                        <th class="column-title">Griddle Thin</th>
                                                        <th class="column-title">Griddle Thick</th>
                                                        <th class="column-title">Eyeclean</th>
                                                        <th class="column-title">Shade</th>
                                                        <th class="column-title">Culate</th>
                                                        <th class="column-title">Luster</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string">
                                                    @foreach ($diamonds as $diamond)
                                                    <tr>
                                                        <td nowrap="nowrap">
                                                            <i class="fa fa-eye diamond_detail" id="{{ $diamond->certificate_no }}"></i>
                                                        @if(!empty($diamond->image))
                                                            <a href="{{ $diamond->image }}" target="_blank" class="ms-1"><img height="20" src="{{ asset("assets/images/imagesicon.png") }}" style="cursor:pointer;" title="Image"></a>
                                                        @else
                                                            <span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                        @endif
                                                        @if(!empty($diamond->video))
                                                            <a href="{{ $diamond->video }}" target="_blank" class="ms-1"><img height="20" src="{{ asset("assets/images/videoicon.png") }}" style="cursor:pointer;" title="Video"></a>
                                                        @else
                                                            <span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                        @endif
                                                        <img height="22" src="{{ asset("assets/images/". strtolower($diamond->country) .".png") }}" style="cursor:pointer;" title="{{ $diamond->country }}">
                                                        </td>
                                                        <td>{{ $diamond->id }}</td>
                                                        <td>{{ $diamond->ref_no }}</td>
                                                        <td>{{ $diamond->availability }}</td>
                                                        <td>{{ $diamond->shape }}</td>
                                                        <td>{{ number_format($diamond->carat , 2) }}</td>
                                                        <td>{{ $diamond->color }}</td>
                                                        <td>{{ $diamond->clarity }}</td>
                                                        <td>{{ $diamond->cut }}</td>
                                                        <td>{{ $diamond->polish }}</td>
                                                        <td>{{ $diamond->symmetry }}</td>
                                                        <td>{{ $diamond->fluorescence }}</td>
                                                        <td>{{ $diamond->lab }}</td>
                                                        @if ($diamond->lab == 'IGI')
                                                            <td><a href="https://www.igi.org/viewpdf.php?r={{ $diamond->certificate_no }}" target="_blank">{{ $diamond->certificate_no }}</a></td>
                                                        @elseif($diamond->lab == 'GIA')
                                                            <td><a href="http://www.gia.edu/report-check?reportno={{ $diamond->certificate_no }}" target="_blank">{{ $diamond->certificate_no }}</a></td>
                                                        @elseif($diamond->lab == 'HRD')
                                                            <td><a href="https://my.hrdantwerp.com/?id=34&record_number={{ $diamond->certificate_no }}&weight=" target="_blank">{{ $diamond->certificate_no }}</a></td>
                                                        @elseif($diamond->lab == 'GCAL')
                                                            <td><a href="https://www.gcalusa.com/certificate-search.html?certificate_id={{ $diamond->certificate_no }}&weight=" target="_blank">{{ $diamond->certificate_no }}</a></td>
                                                        @else
                                                        <td>{{ $diamond->certificate_no }}</td>
                                                        @endif
                                                        <td>{{ number_format($diamond->orignal_rate * $diamond->carat , 2) }}</td>
                                                        <td>{{ number_format($diamond->orignal_rate , 2) }}</td>
                                                        <td>{{ number_format(!empty($diamond->raprate) ? round(($diamond->orignal_rate - $diamond->raprate) / $diamond->raprate * 100, 2) : 0 , 2) }}</td>
                                                        <td>{{ number_format($diamond->table_per , 2) }}</td>
                                                        <td>{{ number_format($diamond->depth_per , 2) }}</td>
                                                        <td>{{ $diamond->gridle }}</td>
                                                        <td>{{ $diamond->gridle_per }}</td>
                                                        <td>{{ $diamond->girdle_thin }}</td>
                                                        <td>{{ $diamond->girdle_thick }}</td>
                                                        <td>{{ $diamond->eyeclean }}</td>
                                                        <td>{{ $diamond->shade }}</td>
                                                        <td>{{ $diamond->cutlet }}</td>
                                                        <td>{{ $diamond->luster }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {!! $diamonds->appends(\Request::except('page'))->render() !!}
                                            {{-- <ul class="pagination">
                                                <li class="page-item previous disabled" id="previous_page">
                                                    <a href="javascript:void(0)"><span class="page-link">Previous</span></a>
                                                </li>
                                                <li class="page-item next" id="next_page">
                                                    <a href="javascript:void(0)" class="page-link">Next</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link"><span id="pagecount">1</span> &nbsp;to &nbsp; <span id="totalrecord"></span> &nbsp; Total Pages</a>
                                                </li>
                                            </ul> --}}
                                            <!--<div id="add_to_cart">add_to_cart   </div>-->
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

	<script>var hostUrl = "assets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
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
            }

            $('#render_string').delegate('.diamond_detail', 'click', function(){
                var loatno = this.id;
                blockUI.block();
                request_call("{{ url('diamond-view-detail')}}", "certificate_no=" + $.trim(loatno)+"&diamond_type=W");
                xhr.done(function(mydata) {
                    blockUI.release();
                    $("#header-modal").html(mydata.success);
                    $('#header-modal').modal('show');
                });
            });
        });

    </script>
</body>
</html>
