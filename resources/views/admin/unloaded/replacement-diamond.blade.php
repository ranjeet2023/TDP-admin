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
                                <h3 class="card-title align-items-start flex-column">Replacement Diamond Find</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('Replacement-Diamond-Post') }}" method="post">
                                    @csrf
                                    <div class="row mb-5">
                                        <div class="col-md-5 col-sm-18">
                                            <div class="form-group">
                                                <label for="title"> Certificate Number<span class='text-danger'>*</span></label>
                                                <input type="text" class="form-control" name="certificate" id="certificate" {{ !empty($certificate) ? 'value= '.$certificate : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-18 offset-md-1">
                                            <label for="title"> </label><br>
                                            <button type="submit"  class="btn btn-warning" id="searchbtn">Search Replacements</button>
                                        </div>
                                    </div>
                                </form>
                                @if (!empty($details_show))
                                        @php
                                            $orignal_rate = $diamond_details->rate + (($diamond_details->rate) / 100);
                                            $supplier_price = ($orignal_rate * $diamond_details->carat);

                                            if($supplier_price <= 1000)
                                            {
                                                $procurment_price = $supplier_price + 25;
                                            }
                                            else if($supplier_price >= 7000)
                                            {
                                                $procurment_price = $supplier_price + 140;
                                            }
                                            else if($supplier_price > 1000 && $supplier_price < 7000)
                                            {
                                                $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                                            }
                                            $carat_price = $procurment_price / $diamond_details->carat;

                                            $supplier_price = $orignal_rate * $diamond_details->carat;
                                            $supplier_discount = !empty($diamond_details->raprate) ? round(($orignal_rate - $diamond_details->raprate) / $diamond_details->raprate * 100, 2) : 0;

                                            $procurment_discount = !empty($diamond_details->raprate) ? round(($carat_price - $diamond_details->raprate) / $diamond_details->raprate * 100, 2) : 0;
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-12 table-responsive mb-5" style="margin-top: 10px;">
                                                <h4>Diamond Details: </h4><br/>
                                                <table class="table table-striped table-bordered" style="white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">
                                                    <thead>
                                                        <tr>
                                                            <th>Shape</th>
                                                            <th>Carat</th>
                                                            <th></th>
                                                            <th>Color</th>
                                                            <th>Clarity</th>
                                                            <th>Cut</th>
                                                            <th>Polish</th>
                                                            <th>Symmetry</th>
                                                            <th>Flour</th>
                                                            <th>Lab</th>
                                                            <th>Certificate</th>
                                                            <th>Measurement</th>
                                                            <th>Table</th>
                                                            <th>Depth</th>
                                                            <th>Country</th>
                                                            <th>$/ct</th>
                                                            <th>Price</th>
                                                            {{-- <th>Original Rate</th> --}}
                                                            <th>Milky</th>
                                                            <th>eyeclean</th>
                                                            <th>Supplier Name</th>
                                                            <th>Reference No</th>
                                                            <th>Diamond Type</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{!! $diamond_details->shape !!}</td>
                                                            <td>{!! $diamond_details->carat !!}</td>
                                                            <td>@if (!empty($diamond_details->image))
                                                                    <a href='{!! $diamond_details->image !!}' target="_blank" class="ms-1"><img height="22" src="{{ asset("assets/images/imagesicon.png") }}" style="cursor:pointer;" title="Image"></a>
                                                                @else
                                                                    <span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                                @endif

                                                                @if (!empty($diamond_details->video))
                                                                    <a href="{!! $diamond_details->video  !!}" target="_blank" class="ms-1"><img height="20" src="{{ asset("assets/images/videoicon.png")  }}" style="cursor:pointer;" title="Video"></a>
                                                                @else
                                                                    <span style="width:22px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                                @endif

                                                                <img class="ms-1" height="20" src="{{ asset("assets/images/" . strtolower($diamond_details->country) .".png") }}">
                                                            </td>
                                                            <td>{!! $diamond_details->color !!}</td>
                                                            <td>{!! $diamond_details->clarity !!}</td>
                                                            <td>{!! $diamond_details->cut !!}</td>
                                                            <td>{!! $diamond_details->polish !!}</td>
                                                            <td>{!! $diamond_details->symmetry !!}</td>
                                                            <td>{!! $diamond_details->fluorescence !!}</td>
                                                            <td>{!! $diamond_details->lab !!}</td>
                                                            <td>{!! $diamond_details->certificate_no !!}</td>
                                                            <td>{!! $diamond_details->length.' x '.$diamond_details->width.' x '.$diamond_details->depth !!}</td>
                                                            <td>{!! $diamond_details->table_per !!}</td>
                                                            <td>{!! $diamond_details->depth_per !!}</td>
                                                            <td>{!! $diamond_details->country !!}</td>
                                                            <td>{!! number_format($carat_price, 2) !!}</td>
                                                            <td>{!! number_format($procurment_price, 2) !!}</td>
                                                            {{-- <td>{!! $diamond_details->orignal_rate !!}</td> --}}
                                                            <td>{!! $diamond_details->milky !!}</td>
                                                            <td>{!! $diamond_details->eyeclean !!}</td>
                                                            <td>{!! $diamond_details->supplier_name !!}</td>
                                                            <td>{!! $diamond_details->ref_no !!}</td>
                                                            <td>{{ ($diamond_details->diamond_type == 'W') ? 'NATURAL' : 'LAB' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 table-responsive mb-5" style="margin-top: 10px;">
                                                <h4>Replacement Diamonds: {{ count($replacements);}}</h4><br/>
                                                @if (count($replacements) > 0 )
                                                <div class="table-responsive">
                                                    <table id="datatable" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Shape</th>
                                                                <th>Carat</th>
                                                                <th>Color</th>
                                                                <th>Clarity</th>
                                                                <th>Cut</th>
                                                                <th>Polish</th>
                                                                <th>Symm</th>
                                                                <th>Flour</th>
                                                                <th>Lab</th>
                                                                <th>Certificate</th>
                                                                <th>Measurement</th>
                                                                <th>Table</th>
                                                                <th>Depth</th>
                                                                <th>Country</th>
                                                                <th>$/ct</th>
                                                                <th>Price</th>
                                                                {{-- <th>Original Rate</th> --}}
                                                                <th>Milky</th>
                                                                <th>eyeclean</th>
                                                                <th>Supplier Name</th>
                                                                <th>Reference No</th>
                                                                <th>Diamond Type</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($replacements as $diamond)

                                                                @php
                                                                    $orignal_rate = $diamond->rate + (($diamond->rate) / 100);
                                                                    $supplier_price = ($orignal_rate * $diamond->carat);

                                                                    if($supplier_price <= 1000)
                                                                    {
                                                                        $procurment_price = $supplier_price + 25;
                                                                    }
                                                                    else if($supplier_price >= 7000)
                                                                    {
                                                                        $procurment_price = $supplier_price + 140;
                                                                    }
                                                                    else if($supplier_price > 1000 && $supplier_price < 7000)
                                                                    {
                                                                        $procurment_price = $supplier_price + round((2 / 100) * $supplier_price, 2);
                                                                    }
                                                                    $carat_price = $procurment_price / $diamond->carat;

                                                                    $supplier_price = $orignal_rate * $diamond->carat;
                                                                    $supplier_discount = !empty($diamond->raprate) ? round(($orignal_rate - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;

                                                                    $procurment_discount = !empty($diamond->raprate) ? round(($carat_price - $diamond->raprate) / $diamond->raprate * 100, 2) : 0;
                                                                @endphp
                                                                <tr>
                                                                    <td>{!! $diamond->shape !!}</td>
                                                                    <td>{!! $diamond->carat !!}</td>
                                                                    <td>{!! $diamond->color !!}</td>
                                                                    <td>{!! $diamond->clarity !!}</td>
                                                                    <td>{!! $diamond->cut !!}</td>
                                                                    <td>{!! $diamond->polish !!}</td>
                                                                    <td>{!! $diamond->symmetry !!}</td>
                                                                    <td>{!! $diamond->fluorescence !!}</td>
                                                                    <td>{!! $diamond->lab !!}</td>
                                                                    <td>{!! $diamond->certificate_no !!}</td>
                                                                    <td>{!! $diamond->length.' x '.$diamond->width.' x '.$diamond->depth !!}</td>
                                                                    <td>{!! $diamond->table_per !!}</td>
                                                                    <td>{!! $diamond->depth_per !!}</td>
                                                                    <td>{!! $diamond->country !!}</td>
                                                                    <td>{!! number_format($carat_price, 2) !!}</td>
                                                                    <td>{!! number_format($procurment_price, 2) !!}</td>
                                                                    {{-- <td>{!! $diamond->orignal_rate !!}</td> --}}
                                                                    <td>{!! $diamond->milky !!}</td>
                                                                    <td>{!! $diamond->eyeclean !!}</td>
                                                                    <td>{!! $diamond->supplier_name !!}</td>
                                                                    <td>{!! $diamond->ref_no !!}</td>
                                                                    <td>{{ ($diamond->diamond_type == 'W') ? 'NATURAL' : 'LAB GROWN' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @else
                                                    No Replacements Found
                                                @endif
                                            </div>
                                        </div>
                                @else
                                    <div class="text-muted me-2 fs-7"><span class="text-danger">No Diamond On Behalf of this certificate Number</span></div>
                                @endif
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

        });
    </script>
</body>
</html>
