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
                                <h3 class="card-title align-items-start flex-column">Diamond Status</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('diamond-status-post') }}" method="post">
                                    @csrf
                                    <div class="row mb-5">
                                        <div class="col-md-5 col-sm-18">
                                            <div class="form-group">
                                                <label for="title"> Certificate Number<span class='text-danger'>*</span></label>
                                                <input type="text" class="form-control"name="certificate" id="certificate" {{ ($certificate) ? 'value= '.$certificate : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-18 offset-md-1">
                                            <label for="title"> </label><br>
                                            <button type="submit"  class="btn btn-warning" id="searchbtn">Search The Diamond</button>
                                        </div>
                                    </div>
                                </form>
                                @if ($details_show != '')
                                    @if($diamond_details != null)
                                        <div class="row">
                                            <div class="col-md-12 table-responsive mb-5" style="margin-top: 10px;">
                                                <h4>Diamond Details: </h4><br/>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Media</th>
                                                            <th>Supplier Name</th>
                                                            <th>Reference No</th>
                                                            <th>Diamond Type</th>
                                                            <th>SKU</th>
                                                            <th>Shape</th>
                                                            <th>Carat</th>
                                                            <th>Color</th>
                                                            <th>Clarity</th>
                                                            <th>Cut</th>
                                                            <th>Polish</th>
                                                            <th>Symmetry</th>
                                                            <th>Flour</th>
                                                            <th>Lab</th>
                                                            <th>Certificate</th>
                                                            <th>Measurement</th>
                                                            <th>Country</th>
                                                            <th>Milky</th>
                                                            <th>eyeclean</th>
                                                            <th>Original Rate</th>
                                                            <th>$/ct</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                @if (!empty($diamond_details->image))
                                                                    <a href="{{ $diamond_details->image }}" target="_blank" class="ms-1"><img height="22" src="{{ asset("assets/images/imagesicon.png") }}" style="cursor:pointer;" title="Image"></a>
                                                                @endif

                                                                @if (!empty($diamond_details->video))
                                                                    <a href="{{ $diamond_details->video }}" target="_blank" class="ms-1"><img height="20" src="{{ asset("assets/images/videoicon.png") }}" style="cursor:pointer;" title="Video"></a>
                                                                @endif
                                                            </td>
                                                            <td>{!! $diamond_details->supplier_name !!}</td>
                                                            <td>{!! $diamond_details->ref_no !!}</td>
                                                            <td>{{ ($diamond_details->diamond_type == 'W') ? 'NATURAL' : 'LAB GROWN' }}</td>
                                                            <td>{!! $diamond_details->id !!}</td>
                                                            <td>{!! $diamond_details->shape !!}</td>
                                                            <td>{!! $diamond_details->carat !!}</td>
                                                            <td>{!! $diamond_details->color !!}</td>
                                                            <td>{!! $diamond_details->clarity !!}</td>
                                                            <td>{!! $diamond_details->cut !!}</td>
                                                            <td>{!! $diamond_details->polish !!}</td>
                                                            <td>{!! $diamond_details->symmetry !!}</td>
                                                            <td>{!! $diamond_details->fluorescence !!}</td>
                                                            <td>{!! $diamond_details->lab !!}</td>
                                                            @if ($diamond_details->lab == 'IGI')
                                                                <td><a href="'https://www.igi.org/viewpdf.php?r= {{ $diamond_details->certificate_no }}" target="_blank">'{!! $diamond_details->certificate_no !!}</a></td>
                                                            @elseif ($diamond_details->lab == 'GIA')
                                                                <td><a href="http://www.gia.edu/report-check?reportno={{ $diamond_details->certificate_no  }}" target="_blank">{!! $diamond_details->certificate_no !!}</a></td>
                                                            @elseif ($diamond_details->lab == 'HRD')
                                                                <td><a href="https://my.hrdantwerp.com/?id=34&record_number{!! $diamond_details->certificate_no  !!}&weight=" target="_blank">{!!$diamond_details->certificate_no !!}</a></td>
                                                            @elseif ($diamond_details->lab == 'GCAL')
                                                                <td><a href="https://www.gcalusa.com/certificate-search.html?certificate_id{!! $diamond_details->certificate_no  !!}&weight=" target="_blank">{!!$diamond_details->certificate_no !!}</a></td>
                                                            @else
                                                                <td>{!!$diamond_details->certificate_no !!}</td>
                                                            @endif
                                                            <td>{!! $diamond_details->length.' x '.$diamond_details->width.' x '.$diamond_details->depth !!}</td>
                                                            <td>{!! $diamond_details->country !!}</td>
                                                            <td>{!! $diamond_details->milky !!}</td>
                                                            <td>{!! $diamond_details->eyeclean !!}</td>
                                                            <td>{!! $diamond_details->orignal_rate !!}</td>
                                                            <td>{!! $diamond_details->rate !!}</td>
                                                            <td>{!! $diamond_details->net_dollar !!}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="timeline mt-5">
                                            <div class="timeline-item">
                                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                    <div class="symbol-label bg-light">
                                                        <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="currentColor"></path>
                                                                <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="timeline-content mb-10 mt-n2">
                                                    <div class="timeline-line w-40px"></div>
                                                    <div class="overflow-auto pe-3">
                                                        <div class="fs-3 fw-semibold mb-2">Availibility</div>
                                                        <div class="d-flex align-items-center mt-1 fs-6">
                                                            <div class="text-muted me-2 fs-7">
                                                                @if($diamond_details->is_delete == 0)
                                                                    <span class="text-success">AVAILABLE</span>
                                                                @else
                                                                    <span class="text-danger">UNAVAILABLE</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($order_details != null)
                                                @foreach($order_details as $order)
                                                    @php
                                                        $color = '';
                                                        if($order->order_status == 'PENDING')
                                                            {$color = 'color:#7239ea!important;';}
                                                        elseif($order->order_status == 'APPROVED')
                                                            {$color = 'color:#42c220fa!important;';}
                                                        elseif($order->order_status == 'REJECT')
                                                            {$color = 'color:#f1416c!important;';}
                                                        elseif($order->order_status == 'RELEASED')
                                                            {$color = 'color:#f1416c!important;';}
                                                        if($order->supplier_status == 'PENDING')
                                                            {$sup_color = 'color:#7239ea!important;';}
                                                        elseif($order->supplier_status == 'APPROVED')
                                                            {$sup_color = 'color:#42c220fa!important;';}
                                                        elseif($order->supplier_status == 'REJECT')
                                                            {$sup_color = 'color:#f1416c!important;';}
                                                        elseif($order->supplier_status == 'RELEASED')
                                                            {$sup_color = 'color:#f1416c!important;';}
                                                    @endphp
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M11.2166 8.50002L10.5166 7.80007C10.1166 7.40007 10.1166 6.80005 10.5166 6.40005L13.4166 3.50002C15.5166 1.40002 18.9166 1.50005 20.8166 3.90005C22.5166 5.90005 22.2166 8.90007 20.3166 10.8001L17.5166 13.6C17.1166 14 16.5166 14 16.1166 13.6L15.4166 12.9C15.0166 12.5 15.0166 11.9 15.4166 11.5L18.3166 8.6C19.2166 7.7 19.1166 6.30002 18.0166 5.50002C17.2166 4.90002 16.0166 5.10007 15.3166 5.80007L12.4166 8.69997C12.2166 8.89997 11.6166 8.90002 11.2166 8.50002ZM11.2166 15.6L8.51659 18.3001C7.81659 19.0001 6.71658 19.2 5.81658 18.6C4.81658 17.9 4.71659 16.4 5.51659 15.5L8.31658 12.7C8.71658 12.3 8.71658 11.7001 8.31658 11.3001L7.6166 10.6C7.2166 10.2 6.6166 10.2 6.2166 10.6L3.6166 13.2C1.7166 15.1 1.4166 18.1 3.1166 20.1C5.0166 22.4 8.51659 22.5 10.5166 20.5L13.3166 17.7C13.7166 17.3 13.7166 16.7001 13.3166 16.3001L12.6166 15.6C12.3166 15.2 11.6166 15.2 11.2166 15.6Z" fill="currentColor"></path>
                                                                        <path opacity="0.3" d="M5.0166 9L2.81659 8.40002C2.31659 8.30002 2.0166 7.79995 2.1166 7.19995L2.31659 5.90002C2.41659 5.20002 3.21659 4.89995 3.81659 5.19995L6.0166 6.40002C6.4166 6.60002 6.6166 7.09998 6.5166 7.59998L6.31659 8.30005C6.11659 8.80005 5.5166 9.1 5.0166 9ZM8.41659 5.69995H8.6166C9.1166 5.69995 9.5166 5.30005 9.5166 4.80005L9.6166 3.09998C9.6166 2.49998 9.2166 2 8.5166 2H7.81659C7.21659 2 6.71659 2.59995 6.91659 3.19995L7.31659 4.90002C7.41659 5.40002 7.91659 5.69995 8.41659 5.69995ZM14.6166 18.2L15.1166 21.3C15.2166 21.8 15.7166 22.2 16.2166 22L17.6166 21.6C18.1166 21.4 18.4166 20.8 18.1166 20.3L16.7166 17.5C16.5166 17.1 16.1166 16.9 15.7166 17L15.2166 17.1C14.8166 17.3 14.5166 17.7 14.6166 18.2ZM18.4166 16.3L19.8166 17.2C20.2166 17.5 20.8166 17.3 21.0166 16.8L21.3166 15.9C21.5166 15.4 21.1166 14.8 20.5166 14.8H18.8166C18.0166 14.8 17.7166 15.9 18.4166 16.3Z" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <div class="timeline-content mb-5 mt-n1">
                                                            <div class="mb-5 pe-3">
                                                                <a class="fs-3 fw-semibold text-gray-800 mb-2">Approvals:</a>
                                                            </div>
                                                            <div class="overflow-auto pb-5">
                                                                <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-5">
                                                                    <div class="d-flex flex-aligns-center pe-10 pe-lg-20 min-w-650px">
                                                                        <div class="ms-1 fw-semibold">
                                                                            <a class="fs-4 fw-bold"><u>Customer</u></a>
                                                                            <div>
                                                                                Order ID : <span style={{ $color }}>{!! $order->orders_id !!}</span><br/>
                                                                                Name : <span style={{ $color }}>{!! $order->user->companyname !!}</span><br/>
                                                                                Status : <span style={{ $color }}>{!! $order->order_status !!}</span><br/>
                                                                                Comment :<span style={{ $color }}>{!! $order->customer_comment !!}</span><br/>
                                                                                Order Place Date : <span style={{ $color }}>{!! $order->created_at !!}</span><br/>
                                                                                Status : <span style={{ $color }}>{!! ($order->hold == 1) ? "Hold" : 'Confirmed' !!}</span><br/>
                                                                                country : <span style={{ $color }}>{!! $order->country !!}</span><br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-aligns-center pe-10 pe-lg-20">
                                                                        <div class="ms-1 fw-semibold">
                                                                            <a class="fs-4 fw-bold"><u>Supplier</u></a>
                                                                            <div>
                                                                                Name : <span style={{ $sup_color }}>{!! $order->orderdetail->supplier_name !!}</span> <br/>
                                                                                Status : <span style={{ $sup_color }}>{!! $order->supplier_status !!}</span> <br/>
                                                                                Comment : <span style={{ $sup_color }}>{!! $order->supplier_comment !!}</span> <br/>
                                                                                Date : <span style={{ $sup_color }}>{!! $order->updated_at !!}</span> <br/>
                                                                                Reject Reason :<span style={{ $sup_color }}>{!! $order->supplier_reject_reson !!}</span> <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if (!empty($pickup_details) && $pickup_details->qc_list != '' && $pickup_details->qc_list != null)
                                                <div class="timeline-item">
                                                    <div class="timeline-line w-40px"></div>
                                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                        <div class="symbol-label bg-light">
                                                            <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                                                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <div class="timeline-content mb-5 mt-n1">
                                                        <div class="pe-3 mb-5">
                                                            <div class="fs-5 fw-semibold mb-2">QC Comment</div>
                                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                                <div class="me-2 fs-7" style="color:#42c220fa!important;">{{ $pickup_details->qc_list->qc_comment }}<br/>{{ $pickup_details->qc_list->created_at }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($pickup_details != null)
                                                <div class="timeline-item">
                                                    <div class="timeline-line w-40px"></div>
                                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                        <div class="symbol-label bg-light">
                                                            <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="currentColor"></path>
                                                                    <path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <div class="timeline-content mb-5 mt-n1">
                                                        <div class="pe-3 mb-5">
                                                            <div class="fs-3 fw-semibold mb-2">Stone status:</div>
                                                            <div class="d-flex align-items-center mt-1 fs-6">
                                            @if($pickup_details->status == "PENDING" && ($pickup_details->export_number != "" || $pickup_details->export_invoice != ""))
                                                                <span class="mb-1 fw-bolder" style="color:#42c220fa!important;">In Transit<br/>{!! $pickup_details->pickup_date !!}</span>
                                            @elseif($pickup_details->status =='PENDING')
                                                                <span style="color:#7239ea!important;">Requested For QC<br/>{!! $pickup_details->pickup_date !!}</span>
                                            @elseif($pickup_details->status == "PICKUP_DONE" && $pickup_details->qc_list == null)
                                                                <span class="mb-1" style="color:#42c220fa!important;">On Hand<br/>{!! $pickup_details->pickup_date !!}</span>
                                            @elseif($pickup_details->status == "PICKUP_DONE" && $pickup_details->qc_list != null)
                                                                <span class="mb-1 fw-bolder" style="color:#42c220fa!important;">Done QC<br/>{!! $pickup_details->pickup_date !!}</span>
                                            @elseif($pickup_details->status == "QCRETURN")
                                                                <span class="mb-1 fw-bolder" style="color:#42c220fa!important;">QC Done & Return<br/>{!! $pickup_details->pickup_date !!}</span>
                                            @elseif($pickup_details->status == "PICKUP_DONE" && $pickup_details->export_number != "")
                                                                <span class="mb-1 fw-bolder" style="color:#42c220fa!important;">Received<br/>{!! $pickup_details->pickup_date !!}</span>
                                            @elseif($pickup_details->status == "REACHED")
                                                                <span class="mb-1" style="color:#42c220fa!important;">Reached<br/>{!! $pickup_details->pickup_date !!}</span>
                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                                <div class="timeline-item">
                                                    <div class="timeline-line w-40px"></div>
                                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                        <div class="symbol-label bg-light">
                                                            <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                                                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <div class="timeline-content mb-5 mt-n1">
                                                        <div class="pe-3 mb-5">
                                                            <div class="fs-5 fw-semibold mb-2">Invoice Number</div>
                                                        <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-5">
                                                            <div class="d-flex flex-aligns-center pe-10 pe-lg-20 min-w-650px">
                                                                <div class="ms-1 fw-semibold">
                                                                    <a class="fs-4 fw-bold"><u>Invoice</u></a><br/>
                                                                    {!! ( !empty($invoice) && !empty($invoice->invoice_number) ) ? '<a href="'.url("assets/invoices/".$invoice->bill_invoice_pdf).'" target="_blank">'.$invoice->invoice_number.'</a> <br/>'.$invoice->created_at .'<br/> Sender :  '. $invoice->associates->name .'<br/> final Destination : '. $invoice->final_destination : '' !!}
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-aligns-center pe-10 pe-lg-20">
                                                                <div class="ms-1 fw-semibold">
                                                                    <a class="fs-4 fw-bold"><u>proforma</u></a><br/>
                                                                    {!! ( !empty($invoices_perfoma) && !empty($invoices_perfoma->invoice_number) ) ? '<a href="'.url("assets/invoices/".$invoices_perfoma->bill_invoice_pdf).'" target="_blank">'.$invoices_perfoma->invoice_number.'</a> <br/>'.$invoices_perfoma->created_at .'<br/> Sender :  '. $invoices_perfoma->name .'<br/> final Destination : '. $invoices_perfoma->final_destination : '' !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                </div>
                                                <div class="timeline-item">
                                                    <div class="timeline-line w-40px"></div>
                                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                        <div class="symbol-label bg-light">
                                                            <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M6 8.725C6 8.125 6.4 7.725 7 7.725H14L18 11.725V12.925L22 9.725L12.6 2.225C12.2 1.925 11.7 1.925 11.4 2.225L2 9.725L6 12.925V8.725Z" fill="currentColor"></path>
                                                                    <path opacity="0.3" d="M22 9.72498V20.725C22 21.325 21.6 21.725 21 21.725H3C2.4 21.725 2 21.325 2 20.725V9.72498L11.4 17.225C11.8 17.525 12.3 17.525 12.6 17.225L22 9.72498ZM15 11.725H18L14 7.72498V10.725C14 11.325 14.4 11.725 15 11.725Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <div class="timeline-content mb-5 mt-n1">
                                                        <div class="pe-3 mb-5">
                                                            <div class="fs-5 fw-semibold mb-2">Export Number</div>
                                                            <div class="overflow-auto pb-5">
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-7"><span class="text-danger">{!! ( !empty($pickup_details) && !empty($pickup_details->export_number) ) ? $pickup_details->export_number .'<br/>'.$pickup_details->export_created_date : '' !!}</span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>

                                        {{-- <div class="cd-horizontal-timeline loaded">
                                            <div class="timeline" style="max-width:100%;">
                                                <div class="events-wrapper">
                                                    <div class="events" style="width: 1800px; transform: translateX(0px);">
                                                        <ol>
                                                            <li>
                                                                {{ date_format('2023-01-17 15:03:57',"d/m/Y"); }}
                                                                <a href="" data-date=""> sdfkj </a>
                                                            </li>
                                                        </ol>

                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    @else
                                        <div class="text-muted me-2 fs-7"><span class="text-danger">No Diamond On Behalf of this certificate Number</span></div>
                                    @endif
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
