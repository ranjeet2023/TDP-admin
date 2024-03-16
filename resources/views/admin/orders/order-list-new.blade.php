<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="{{ config('app.website') }}" />
    <meta name="keywords" content="{{ config('app.website') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .tooltip-inner {
            background-color: #000 !important;
            color: #fff !important;
        }
        .dataTables_filter input {
            border-radius: 22px;
            padding: 5px 10px;
            font-size: 14px;
            color: #333;
            background-color: var(--bs-gray-100);
            border-color: var(--bs-gray-100);
            color: var(--bs-gray-700);
            transition: color 0.2s ease;
        }

    </style>
    @include('admin/css')

</head>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed "
    style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            @include('admin/sidebar')
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('admin/header')
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div id="kt_content_container" class="container-xxl">
                        <div class="row gy-5 g-xl-8">
                            <div class="col-xl-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-icon" role="alert"><i
                                            class="uil uil-times-circle"></i>
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-icon" role="alert"><i
                                            class="uil uil-times-circle"></i>
                                        @foreach ($errors->all() as $error)
                                            {{ $error }}
                                        @endforeach
                                    </div>
                                @endif
                                <div class="card  mb-6 bg-light">
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bolder fs-3 mb-1">Order List -
                                                    Supplier</span>
                                            </h3>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="me-4">
                                                <select class="form-select form-select-sm" data-show-subtext="true"
                                                    data-live-search="true" id="holdfilter" name="holdfilter">
                                                    <option value="">Hold Filter</option>
                                                    <option value="1"
                                                        {{ 1 == request()->get('hold') ? 'selected' : '' }}>Hold
                                                    </option>
                                                </select>
                                            </div>
                                            <div>
                                                <select class="form-select form-select-sm" data-show-subtext="true"
                                                    data-live-search="true" id="countryddl" name="countryddl">
                                                    <option value="">Country Name</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->country }}"
                                                            {{ $country->country == request()->get('country') ? 'selected' : '' }}>
                                                            {{ $country->country }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button class="btn btn-danger btn-sm" type="button"
                                                onClick="window.location.href='order-list-new'"
                                                style="margin:0 5px 0 5px;">clear</button>
                                            <a href="#"
                                                class="btn btn-light btn-primary btn-sm d-flex align-items-center gap-2 gap-lg-3 me-2"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <span class="svg-icon svg-icon-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-success text-hover-inverse-success supplier_confirm_popup">Qc  Request</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-info text-hover-inverse-info excel_download" data-orders="{{ $orders }}">Excel Download</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-danger text-hover-inverse-danger reverse_diamond">Reverse</span></a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3 bg-hover-danger text-hover-inverse-danger release_diamond">Release</span></a>
                                                </div>
                                                @if (Auth::user()->user_type == 1 || Auth::user()->id == 721 || Auth::user()->user_type == 496)
                                                    <a href="{{ url('all-order-list') }}"
                                                        class="btn btn-sm btn-warning">All Order List</a>
                                                @endif
                                            </div>
                                            <button class="btn btn-sm btn-secondary me-2"><span
                                                    id="total_pcs">0</span></button>
                                            <button class="btn btn-sm btn-secondary me-2">CT : <span
                                                    id="totalcarat">0.00</span></button>
                                            <button class="btn btn-sm btn-secondary me-2">$/ct $ : <span
                                                    id="totalpercarat">0.00</span></button>
                                            <button class="btn btn-sm btn-secondary me-2">$ : <span
                                                    id="totalamount">0.00</span></button>
                                            <button class="btn btn-sm btn-secondary me-2">$/ct A $ : <span
                                                    id="totalApercarat">0</button>
                                            <button class="btn btn-sm btn-secondary me-2">Price A $ : <span
                                                    id="totalAamount">0</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card snipcss0-1-1-2">
                                    <div   class="card-header border-0 pt-6 snipcss0-2-2-3 tether-target-attached-top tether-element-attached-top tether-element-attached-center tether-target-attached-center">
                                    </div>
                                    <div class="card-body py-4 snipcss0-2-2-181">
                                        <div id="kt_table_users_wrapper"
                                            class="dataTables_wrapper dt-bootstrap4 no-footer snipcss0-3-181-182">
                                            <div class="table-responsive snipcss0-4-182-183">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer snipcss0-5-183-184 table-hover"  id="myTable" >
                                                    <thead class="snipcss0-6-184-185">
                                                        <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                            <th class="min-w-125px sorting snipcss0-8-186-190">Details</th>
                                                            <th class="min-w-125px sorting snipcss0-8-186-190">Status</th>
                                                            <th class="min-w-125px sorting snipcss0-8-186-190">Order</th>
                                                            <th class="min-w-125px sorting snipcss0-8-186-190">Date</th>
                                                            <th class="min-w-125px sorting snipcss0-8-186-190">Buy</th>
                                                            <th class="min-w-125px sorting snipcss0-8-186-190">Follow up</th>
                                                            <th class="min-w-125px sorting snipcss0-8-186-190">Ex Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-semibold snipcss0-6-184-196 render_string" id="render_string" >
                                                    @if(!empty($orders))
                                                        @foreach($orders as $value)
                                                            @php
                                                                $color = '';
                                                                if (in_array($value->orders_id, $getrowcheck)) {
                                                                    $color = "text-success";
                                                                }
                                                            @endphp
                                                        <tr class="odd snipcss0-7-196-197">
                                                            <td >
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input check_box" data-stone={!! 1 !!}  data-orders_id="<?= $value->orders_id ?>" data-customer_id="<?= $value->customer_id ?>"
                                                                    data-ref_no='<?= $value->orderdetail->ref_no ?>'
                                                                    data-certi_no='<?= $value->orderdetail->certificate_no ?>'
                                                                    data-carat='<?= $value->orderdetail->carat ?>'  data-price='<?= $value->sale_price ?>' data-discount="<?= $value->sale_discount ?>"  data-aprice='<?= $value->buy_price ?>' name="multiaction" value="<?= $value->orders_id ?>" type="checkbox">
                                                                   <span></span>
                                                                   <div class="d-flex flex-column text-dark p-2 text-nowrap">
                                                                       <span >{{ $value->orderdetail->lab }}- {{ $value->orderdetail->fluorescence }}-{{ $value->certificate_no }}-{{ $value->orderdetail->shape }}-{{ $value->orderdetail->carat }}-{{ $value->orderdetail->clarity }}-{{ $value->orderdetail->cut }}-{{ $value->orderdetail->polish }}-{{ $value->orderdetail->symmetry }}-{{ $value->ref_no }}</span>
                                                                    </div>
                                                                </div>
                                                                <i class="fa fa-plus" data-id="{{ $value->orders_id }}" data-customer_id="{{ $value->customer_id }}"></i>
                                                                <span class="text-center" >
                                                                    <div class="d-inline p-2 text-dark  ml-2">Customer:<b>{{ $value->user->firstname }} </b></div>|
                                                                    <div class="d-inline p-2 text-dark ml-3">Supplier:<b>{{ $value->orderdetail->supplier_name }}</b></div>
                                                                </span>
                                                            </td>
                                                            <td class="odd snipcss0-7-196-197 ">
                                                                <button class="btn float-left m-0 p-0">
                                                                    <span class="badge badge-light"><img src="{{ asset('assets/images/') }}/{{$value->orderdetail->country}}.png" width="25px"></span>
                                                               </button>
                                                                <div class="d-flex flex-column text-nowrap" >
                                                                    <span >Customer:
                                                                        @if($value->order_status == "PENDING")
                                                                        <button class="btn btn-sm btn-icon btn-primary me-1 w-25px h-25px customer_approve m-auto" data-order="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="APPROVED" ><i class="fa fa-check"></i></button>
                                                                        <button class="btn btn-sm btn-icon btn-danger w-25px h-25px customer_approve m-auto" data-order="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="REJECT" ><i class="fa fa-times"></i></button>
                                                                        @elseif($value->order_status == "APPROVED")
                                                                        <span class="mb-1 text-success fw-bolder">Approved</span>
                                                                        @elseif($value->order_status == "REJECT")
                                                                        <span class="mb-1 text-danger fw-bolder">Rejected</span>
                                                                        @endif
                                                                    </span>
                                                                    <span >Supplier:
                                                                        @if($value->supplier_status == "PENDING")
                                                                        <button class="btn btn-sm btn-icon btn-primary me-1 w-25px h-25px order_approve_supplier m-auto" data-order="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="APPROVED" ><i class="fa fa-check"></i></button>
                                                                        <button class="btn btn-sm btn-icon btn-danger w-25px h-25px order_reject_supplier m-auto" data-reject="{{ $value->orders_id }}" data-certino="{{ $value->certificate_no }}" data-status="REJECT" ><i class="fa fa-times"></i></button>
                                                                        @elseif($value->supplier_status == "APPROVED")
                                                                         <span class="mb-1 text-success fw-bolder">Approved</span>
                                                                        @elseif($value->supplier_status == "REJECT")
                                                                         <span class="mb-1 text-danger fw-bolder">Rejected</span>
                                                                        @endif
                                                                    </span>
                                                                </div>

                                                            </td>
                                                            <td class="align-items-center snipcss0-8-197-201">
                                                                @if ($value->return_price > 0.00)
                                                                    <span class="badge badge-light-danger">
                                                                       {{ ($value->return_price > 0.00) ? ( (Auth::user()->user_type == 1) ? 'R-order' : 'R-order') : ''; }}
                                                                    </span>
                                                                @endif
                                                                <br>
                                                                    @if($value->hold == 1)
                                                                        <span class="badge badge-light-warning">{{ $value->hold == 1 ? 'Hold ' : '' }}</span>
                                                                    @elseif($value->hold == 0)
                                                                        <span class="badge badge-light-primary">{{ $value->hold == 0 ? 'Confirm ' : '' }}</span>
                                                                    @endif
                                                            </td>
                                                            <td class="align-items-center snipcss0-8-197-201">
                                                                {{ date('d M Y, h:i a', strtotime($value->orderdetail->created_at)) }}

                                                                @if($value->hold_at != null || $value->approved_at != null)
                                                                @if($value->hold_at != null)
                                                                    <span class="badge badge-circle badge-primary badge-lg">{{ intval((time() - strtotime($value->hold_at))/(60*60*24)) }}</span><br/>
                                                                @endif
                                                                @if($value->approved_at != null)
                                                                    <span class="badge badge-circle badge-success badge-lg mt-3">{{ intval((time() - strtotime($value->approved_at))/(60*60*24)) }}</span>
                                                                @endif
                                                                @else
                                                                    <span class="badge badge-circle badge-success badge-lg mt-3">{{ intval((time() - strtotime($value->created_at))/(60*60*24)) }}</span>
                                                                @endif
                                                            </td>


                                                            <td class="align-items-center snipcss0-8-197-201">
                                                                <div class="d-flex flex-column " >
                                                                    @if(!empty($permission) && ($permission->full == 1 || in_array(Auth::user()->user_type, array(1))) )
                                                                        <span >Sell:<span class="badge badge-light-dark">{{ $value->sale_discount }}</span>{{ $value->sale_price }}</span>
                                                                    @endif
                                                                    @if(!empty($permission) && ($permission->full == 1 || in_array(Auth::user()->user_type, array(1))) )
                                                                        <span >Buy:<span class="badge badge-light-dark">{{ $value->buy_discount }}</span>{{ $value->buy_price }}</p></span>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="align-items-center snipcss0-8-197-201">
                                                                <div>
                                                                    <i class="fas fa-comment-dots text-primary fs-2 commentAdd" data-val="{{$value->orders_id}}"></i>
                                                                    <span>
                                                                        <button class="btn">
                                                                            <span class="badge badge-primary order_comment" data-val="{{$value->orders_id}}" data-view="{{ $value->orderdetail->lab }}- {{ $value->orderdetail->fluorescence }}-{{ $value->certificate_no }}-{{ $value->orderdetail->shape }}-{{ $value->orderdetail->carat }}-{{ $value->orderdetail->clarity }}-{{ $value->orderdetail->cut }}-{{ $value->orderdetail->polish }}-{{ $value->orderdetail->symmetry }}-{{ $value->ref_no }}">view</span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    @foreach ($value->order_comment->sortByDesc('created_at')->take(1) as $comment)
                                                                        <span class="text-muted">{{ date('d M Y, h:i a', strtotime($comment->created_at)) }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                            <td><input class="form-control exchange_rate w-25px" id="exchange_<?= $value->orders_id ?>" data-id="<?= $value->orders_id ?>" value="<?= $value->exchange_rate ?>" type="number" size="4" style="min-width:100px;"></td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
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
    {{-- <div class="modal fade" id="header-modal" aria-hidden="true"></div> --}}
    <!--begin::Scrolltop-->
    <div class="modal fade" id="header-modal" tabindex="-1" role="dialog" aria-labelledby="header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" >
            <div class="modal-content" >
                <div class="card mt-5" >
                    <div class="modal-content">
                        <div class="modal-header">
                        <h3 class="modal-title" id="header-modalLabel"><span class="badge badge-light text-dark" id="order_details"></span><br><p class="mx-2">Order Comment</p></h3>
                        <div class='btn btn-icon btn-sm btn-active-light-primary ms-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>
                    </div>
                    <div class="card-body py-4"  class="table-wrapper" style="max-height: 700px;; overflow-y: scroll;" >
                        <table class="table mx-auto" >
                            <thead>
                                <tr>
                                    <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>User name</b></th>
                                    <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>Comment</b></th>
                                    <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>Status</b></th>
                                    <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>Date</b></th>
                                </tr>
                            </thead>
                            <tbody class="tbody text-gray-600 fw-semibold snipcss0-6-184-196 render_string">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none">
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

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom/intro.js') }}"></script>

    <script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");

		$(document).ready(function() {
			var xhr;
			var total_selected = 0;
			var page_record_from = 0;
			var selected_ids = "";

            $('#myTable').DataTable();

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
			$('#countryddl').change(function(e){
				var country = $('#countryddl').val();
				location.href = "{{ url('order-list-new') }}?country="+country;
			});
            $('#holdfilter').change(function(e){
				var hold = $('#holdfilter').val();
				location.href = "{{ url('order-list-new') }}?hold="+hold;
			});


            $('.check_box').change(function(e){
				var stone = 0;
				var Carat = 0;
				var PerCarat = 0;
				var Price = 0;
				var APerCarat = 0;
				var APrice = 0;
				$('.check_box:checked').each(function() {
					stone += 1;
					Carat += parseFloat($(this).data('carat'));
					Price += parseFloat($(this).data('price'));
					APrice += parseFloat($(this).data('aprice'));
					PerCarat = Price / Carat;
					APerCarat = APrice / Carat;
				});

				$('#total_pcs').text(stone);
				$('#totalcarat').html(Carat.toFixed(2));
				$('#totalpercarat').html(PerCarat.toFixed(2));
				$('#totalamount').html(Price.toFixed(2));
                $('#totalApercarat').html(APerCarat.toFixed(2));
                $('#totalAamount').html(APrice.toFixed(2));

				totalstone = stone;
				TotalCarat = Carat.toFixed(2);
				TotalPerCarat = PerCarat.toFixed(2);
				TotalPrice = Price.toFixed(2);
				TotalAPerCarat = APerCarat.toFixed(2);
				TotalAPrice = APrice.toFixed(2);
			});

            $(document).on("click",".order_comment",function(e){
                $('#header-modal').modal('show');
            });
            $(document).on("click",".order_comment",function(e){
                var order_id=$(this).attr('data-val');
                let order_data=$(this).data('view');
                blockUI.block();
                request_call("{{ url('order_comment')}}", "order_id=" + order_id);
                    xhr.done(function(mydata) {
                     blockUI.release();
                     $('.tbody').html(mydata);
                     $('#order_details').html(order_data);
                });
            });

            $('#kt_content_container').delegate('.excel_download', 'click', function() {
                var id = [];
                $(":checkbox").each(function() {
					id.push($(this).val());
				});
                blockUI.block();
                request_call("{{ url('all-order-excel-download')}}", "id=" + id );
                xhr.done(function(mydata) {
                    blockUI.release();
                    document.location.href = ("uploads/" + mydata.file_name);
                });
            })

            $('#render_string').delegate('.status_internal_confirmation', 'click', function() {
                let order_id = $(this).attr('data-order');
                let status = $(this).attr('data-status');
                if(status == 'APPROVED'){
                    Swal.fire({
                        title: 'Are you sure you want to Approve Confirmation?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Approve it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            request_call("{{ url('admin-internal-confirmation')}}", "order_id=" + order_id + "&status=" + status);
                            xhr.done(function(mydata) {
                                Swal.fire({
                                    title: "Approved",
                                    text: 'Approved successfully...!!',
                                    type: "success",
                                    icon: "success",
                                }).then((result) => {
                                    location.reload();
                                });
                            });
                        }
                    });
                }
                else{
                    Swal.fire({
                        title: 'Are you sure you want to Reject Confirmation?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Reject it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            request_call("{{ url('admin-internal-confirmation')}}", "order_id=" + order_id + "&status=" + status);
                            xhr.done(function(mydata) {
                                Swal.fire({
                                    title: "Rejected",
                                    text: 'Rejected successfully...!!',
                                    type: "error",
                                    icon: "error",
                                }).then((result) => {
                                    location.reload();
                                });
                            });
                        }
                    });
                }
            })
            // customer approve
            $('#render_string').delegate('.customer_approve', 'click', function() {
                let order_id = $(this).attr('data-order');
                let status = $(this).attr('data-status');
                if(status == 'APPROVED'){
                    Swal.fire({
                        title: 'Are you sure you want to Approve Confirmation?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Approve it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            request_call("{{ url('customer_order_approve')}}", "order_id=" + order_id + "&status=" + status);
                            xhr.done(function(mydata) {
                                Swal.fire({
                                    title: "Approved",
                                    text: 'Approved successfully...!!',
                                    type: "success",
                                    icon: "success",
                                }).then((result) => {
                                    location.reload();
                                });
                            });
                        }
                    });
                }
                else{
                    Swal.fire({
                        title: 'Are you sure you want to Reject Confirmation?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        html:`  <div class="row mt-3 ">
                                <div class="form-floating">
                                    <textarea class="form-control h-100px" placeholder="Leave a comment here" id="comments"></textarea>
                                    <label for="floatingTextarea">Comments</label>
                                </div>
                        </div>`,
                        preConfirm: () => {
                            const comments = Swal.getPopup().querySelector('#comments').value
                            if(!comments)
                            {
                                Swal.showValidationMessage(`Please Comment what You want`)
                            }
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Reject it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let comments = $('#comments').val();
                            request_call("{{ url('customer_order_approve')}}", "order_id=" + order_id + "&status=" + status + "&comments=" + comments);
                            xhr.done(function(mydata) {
                                Swal.fire({
                                    title: "Rejected",
                                    text: 'Rejected successfully...!!',
                                    type: "error",
                                    icon: "error",
                                }).then((result) => {
                                    location.reload();
                                });
                            });
                        }
                    });
                }
            })

            $('#kt_content_container').delegate('.order_approve_supplier', 'click', function(event) {
                let order_id = $(this).attr('data-order');
                let certi_no = $(this).attr('data-certino');
                // let customer_id = $(this).attr('data-userid');
                let status = $(this).attr('data-status');
                Swal.fire({
                    heightAuto: false,
                    width: '50%' ,
                    html:`<div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="float-start">Stone Location</label>
                                <select class="form-select" id="stonelocation" >
                                    <option value="">Stone Location</option>
                                    <option value="surat">SURAT</option>
                                    <option value="mumbai">MUMBAI</option>
                                    <option value="hongkong">HONG KONG</option>
                                    <option value="usa">USA</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="float-start">Certificate Location</label>
                                <select class="form-select" id="certificatelocation">
                                    <option value="">Certificate Location</option>
                                    <option value="surat">SURAT</option>
                                    <option value="mumbai">MUMBAI</option>
                                    <option value="hongkong">HONG KONG</option>
                                    <option value="usa">USA</option>
                                </select>
                            </div>
                        </div>
                        <div class="row m-2">
                            <div class="col-md-6">
                                <label class="float-start">No BGM :</label>
                                <label for="yes">YES</label>
                                <input type="radio" name="bgm" id="yes-bgm" value="YES">
                                <label for="no">NO </label>
                                <input type="radio" name="bgm" id="no-bgm" value="NO" checked>
                            </div>
                            <div class="col-md-6">
                                <label class="float-start">Eye Clean :</label>
                                <label for="YES">YES</label>
                                <input type="radio" name="eyeclean" id="yes-eye-clean" value="YES" checked>
                                <label for="NO">NO</label>
                                <input type="radio" name="eyeclean" id="no-eye-clean" value="NO" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="float-start" >Brown</label>
                                <select class="form-select" id="brown">
                                    <option value="">Please Select</option>
                                    <option value="none" >None</option>
                                    <option value="light">Light</option>
                                    <option value="medimu">Medium</option>
                                    <option value="heavy">Heavy</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="float-start" id>Green</label>
                                <select class="form-select" id="green">
                                <option value="">Please Select</option>
                                    <option value="none">None</option>
                                    <option value="light">Light</option>
                                    <option value="medimum">Medium</option>
                                    <option value="heavy">Heavy</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="float-start">Milky</label>
                                <select class="form-select" id="milky">
                                <option value="">Please Select</option>
                                    <option value="none" >None</option>
                                    <option value="light">Light</option>
                                    <option value="medium">Medium</option>
                                    <option value="heavy">Heavy</option>
                                </select>
                            </div>
                        <div>
                        <div class="row mt-3">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="comment"></textarea>
                                    <label for="floatingTextarea">Comments</label>
                                </div>
                        </div>
                    </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Approve',
                    preConfirm: () => {
                        const stonelocation = Swal.getPopup().querySelector('#stonelocation').value
                        const certificatelocation = Swal.getPopup().querySelector('#certificatelocation').value
                        // const bgm = Swal.getPopup().querySelector('#bgm').checked
                        // const eyeclean = Swal.getPopup().querySelector('#eyeclean').checked
                        const milky = Swal.getPopup().querySelector('#milky').value
                        const brown = Swal.getPopup().querySelector('#brown').value
                        const green = Swal.getPopup().querySelector('#green').value
                        const comment = Swal.getPopup().querySelector('#comment').value

                        if(!comment)
                        {
                            Swal.showValidationMessage(`Please Comment what You want`)
                        }
                        if(!green)
                        {
                            Swal.showValidationMessage(`Please Select Green`)
                        }
                        if(!brown)
                        {
                            Swal.showValidationMessage(`Please Select Brown`)
                        }
                        if(!milky)
                        {
                            Swal.showValidationMessage(`Please Select Milky`)
                        }
                        if (!certificatelocation) {
                        Swal.showValidationMessage(`Please Select Certificate Location`)
                        }
                        if (!stonelocation) {
                        Swal.showValidationMessage(`Please Select Stone Location `)
                        }
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        let stonelocation = $('#stonelocation').val();
                        let certificatelocation = $('#certificatelocation').val();
                        let bgm = $("input[name='bgm']:checked").val();
                        let eyeclean = $("input[name='eyeclean']:checked").val();
                        let milky = $('#milky').val();
                        let brown = $('#brown').val();
                        let green = $('#green').val();
                        let comment = $('#comment').val();
                        blockUI.block();
                        request_call("{{ url('update-enquiry-list-new')}}", "order_id=" + order_id +"&certi_no=" + certi_no + "&status=" + status +"&comment="+comment +"&milky="+milky +"&brown="+brown+"&green="+green+"&stonelocation="+stonelocation+"&certificatelocation="+certificatelocation+"&bgm="+bgm+"&eyeclean="+eyeclean);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                text: 'Approve!',
                                type: "success",
                                icon: 'success',
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });
            });

            $('#kt_content_container').delegate('.order_reject_supplier', 'click', function(event) {
                let order_id = $(this).attr('data-reject');
                let certi_no = $(this).attr('data-certino');
                // let customer_id = $(this).attr('data-userid');
                let status = $(this).attr('data-status');

                Swal.fire({
                    width: 700,
                    icon:'question',
                    html:`<div class="container">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="float-start">Sold :</label>
                                <label for="yes">YES</label>
                                <input type="radio" name="sold" id="sold-yes" value="YES" >
                                <label for="no">NO </label>
                                <input type="radio" name="sold" id="sold-no" value="NO" checked>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="float-start">Hold For Other :</label>
                                <label for="YES">YES</label>
                                <input type="radio" name="hold" id="hold-yes" value="YES" >
                                <label for="NO">NO</label>
                                <input type="radio" name="hold" id="hold-no" value="NO" checked>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="comment"></textarea>
                                <label for="floatingTextarea">Comments</label>
                            </div>
                        </div>
                    </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    preConfirm: () => {
                        let hold = $("input[name='hold']:checked").val();
                        let sold = $("input[name='sold']:checked").val();
                        const comment = Swal.getPopup().querySelector('#comment').value
                        if(!comment)
                        {
                            Swal.showValidationMessage(`Please Comment what You want`)
                        }
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        let comment =$('#comment').val();
                        let hold = $("input[name='hold']:checked").val();
                        let sold = $("input[name='sold']:checked").val();
                        blockUI.block();
                        request_call("{{ url('update-enquiry-list-new')}}","order_id=" + order_id +"&certi_no=" + certi_no + "&status=" + status +"&comment="+comment+"&hold="+hold+"&sold="+sold);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                text: 'Rejected!',
                                type: "warning",
                                icon: "error"
                            }).then((result) => {
                                location.reload();
                            });
                        })
                    }
                });
            });

			$('#kt_content_container').delegate('.exchange_rate', 'blur', function (e) {
				if (e.keyCode === 109 || e.keyCode === 189) {
					alert("Not allowed (-)minus sign");
					$(this).val('');
				}
				var id = $(this).attr('data-id');
				var exchange_rate	= $(this).val();
				if (exchange_rate != "")
				{
                    blockUI.block();
					request_call("{{ url('admin-update-exchange-rate-new') }}", "id=" + $.trim(id) + "&exchange_rate=" + exchange_rate);
					xhr.done(function (mydata) {
                        blockUI.release();
					});
				}
			});

			$('#kt_content_container').delegate('.supplier_confirm_popup', 'click', function() {
				var id = [];
				var certi_no = [];
				var customer_id = '';
				$(":checkbox:checked").each(function() {
					certi_no.push($(this).attr('data-certi_no'));
					id.push($(this).val());
					customer_id = $(this).attr('data-customer_id');
				});
				var checkpricesave = 0;
				$(".save_active").each(function() {
					checkpricesave += 1;
				});

				if (checkpricesave != 0) {
					Swal.fire("Warning!", "save diamond price before pickup.", "warning");
				} else if (id == "" && certi_no == "") {
					Swal.fire("Warning!", "Please Select at least one record.", "warning");
				} else {
                    blockUI.block();
					request_call("{{ url('admin-confirm-to-supplier') }}", "certi_no=" + certi_no + "&orders_id=" + id + "&customer_id=" + customer_id);
					xhr.done(function(mydataorder) {
                        blockUI.release();
						if (mydataorder.error == false) {
							Swal.fire("Warning!", "diamond already Confirm to supplier.", "warning");
						} else {
							$("#header-modal").html("<div class='modal-dialog modal-dialog-centered modal-fullscreen' role='document'>"
								+ "<div class='modal-content'>"
									+ "<div class='modal-header'>"
										+ "<h4 class='modal-title'>Are you sure you want to send diamond to qc?</h4>"
										+ "<div class='card-toolbar'><a id='save_confirm_data' class='btn btn-sm btn-success me-2'>Confirm</a>"
										+ "<button class='btn btn-sm btn-danger' data-bs-dismiss='modal'>Cancel</button></div>"
										+ "<div class='btn btn-icon btn-sm btn-active-light-primary ms-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>"
									+ "</div>" +
									"<div class='modal-body'>" +
										"<div class='row grid-block' style='margin:0px;'>" +
										"<div id='discont_message_popup'></div>" +
										"<table class='table center table-striped table-bordered bulk_action'>" +
										"<tr><td>Total Stone : <b>" + mydataorder.totalstone + "</b> | Total Carat : <b>" + mydataorder.TotalCarat + "</b> | Total A Per Carat : <b>" + mydataorder.TotalAPerCarat + "</b> | Total A Price : <b>" + mydataorder.TotalAPrice + "</b></td></tr>" +
										"</table>" +
										"" + mydataorder.render_msg + "" +
									"</div>" +
								"</div>" +
								"</div>"
							);
							$('#header-modal').modal('show');
							$('.date-picker').daterangepicker({
								singleDatePicker: true,
								parentEl: "#header-modal .modal-body",
								autoApply: true,
							});
						}
					});
				}
			});

			$('#header-modal').delegate('#save_confirm_data', 'click', function(event) {
				var flag = true;
				$(".city").removeClass("border-danger");
				$(".city").each(function() {
					if ($(this).val() == "") {
						$(this).addClass("border-danger");
						flag = false;
					}
				});
				if (flag == true) {
					var array_value = [];
					$('.pickup_row').each(function() {
						array_value.push({
							dateval: $(this).find(".pickup_date").val(),
							id: $(this).find(".pickup_date").attr('id'),
							city: $(this).find(".city").val()
						});
					});
					var temp_data = JSON.stringify(array_value);
					$('#header-modal').modal('hide');
                    blockUI.block();
					request_call("{{ url('admin-confirmToSupplier') }}", "data=" + temp_data);
					xhr.done(function(mydata) {
                        blockUI.release();
						if (mydata.success) {
							Swal.fire("Success!", mydata.success, "success");
							$('.check_box:checked').each(function() {
								$(this).parents("tr").addClass("text-success");
								this.checked = false;
							});
							selected_ids = "";
							total_selected = totalstone = 0;
							$('#total_pcs').text(0);
							$('#totalcarat').html(0);
							$('#totalrap').html(0);
							$('#totaldiscount').html(0);
							$('#totalpercarat').html(0);
							$('#totalamount').html(0);
							$('#totalApercarat').html(0);
							$('#totalAamount').html(0);
						}
						else
                        {
							Swal.fire("Warning!", mydata.error, "warning");
						}
					});
				}
			});

            $('#kt_content_container').delegate('.fa-plus', 'click', function() {
                var parent_tr = $(this).parents('tr');
                var id = $(this).data('id');
                $(".detail_view").each(function(e) {
                    $(this).remove();
                });

                $(".fa-minus").each(function(e) {
                    $(this).removeClass("fa-minus").addClass("fa-plus");
                });

                $(this).removeClass("fa-plus").addClass("fa-minus");

                blockUI.block();
                request_call("{{ url('admin-view-order-detail')}}", "id="+ id);
                xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        blockUI.release();
                        parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('#kt_content_container').delegate('.fa-minus', 'click', function() {
                $(this).removeClass("fa-minus").addClass("fa-plus");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
            });

            $('.reverse_diamond').click(function() {
                var orders_id = [];
                $("#render_string :checkbox:checked").each(function() {
					orders_id.push($(this).attr('data-orders_id'));
				});

                Swal.fire({
                    title: 'Are you sure you want to reverse Diamond?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reverse it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{ url('admin-order-reverse')}}", "customer_id=" + customer_id + "&orders_id=" + orders_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Approved",
                                text: 'reverse successfully...!!',
                                type: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            });

            $('.release_diamond').click(function() {
                var orders_id = [];
                let customer_id = '';
                $("#render_string :checkbox:checked").each(function() {
					orders_id.push($(this).attr('data-orders_id'));
                    customer_id = $(this).attr('data-customer_id')
				});

                Swal.fire({
                    title: 'Are you sure you want to release Diamond?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, release it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{ url('admin-order-release')}}", "customer_id=" + customer_id + "&orders_id=" + orders_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Approved",
                                text: 'release successfully...!!',
                                type: "success",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            });
            // comment add
            $(document).on("click",".commentAdd",function(e){
                let order_id = $(this).attr('data-val');
                               Swal.fire({
                    html:   `<label class="fs-5 float-left" style="float:left">Status:</label>
                                <select class="form-control" id="status">
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            <lable class="fs-5 float-left" style="float:left;margin-top:5px">Add  Comment: </lable><br>
                            <textarea class="form-control" placeholder="comment" id="comment" rows="4"></textarea>`,
                    icon: 'question',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit ',
                    preConfirm: () => {
                        const comment = Swal.getPopup().querySelector('#comment').value
                        if (!comment) {
                            Swal.showValidationMessage(`Please Enter comment`)
                        }

                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                    let comment =  document.getElementById('comment').value;
                    let status =  document.getElementById('status').value;
                        blockUI.block();
                        request_call('order-list-comment-add',"order_id=" + order_id +"&comment=" + comment +"&status=" + status);
                        xhr.done(function(mydata) {
                            blockUI.release();
                                if(mydata.success== true){
                                    Swal.fire('success','Comment Added Successfully!!','success');
                                    }
                                else{
                                    Swal.fire('warning','Can Not Add Comment!','warning');
                            }
                        })
                    }
                });
            });
		});
	</script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
