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
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Purchase Report</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button class="btn btn-sm btn-secondary me-2">Pcs : <span id="total_pcs"></span></button>
                                    <button class="btn btn-sm btn-secondary me-2">Total carat:<span id="total_carat">0</span></button>
                                    <button class="btn btn-sm btn-secondary me-2">Total Price:<span id="total_price">0</span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.post-purchase-list') }}" method="POST">
                                    @csrf
                                    <div class="row mb-6">
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label>Supplier:</label>
                                            <select for="supplier" class="form-select" name="supplier">
                                                <option class="form-control" value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option class="form-control" value="{{ $supplier->supplier_id }}"{{ ($supplier->supplier_id == $supplier_id) ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label>Starting Carat:</label>
                                            <input type="text" id="start_carat" class="form-control" name="from_carat" value="{{ $from_carat }}" placeholder = "more than carat">
                                        </div>
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label>Ending Carat:</label>
                                            <input type="text" id="to_carat" class="form-control" name="to_carat" value="{{ $to_carat }}" placeholder = "TO carat">
                                        </div>
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label>From:</label>
                                            <input type="date" id="start_date" class="form-control" name="from_date" value="{{ $from_date }}">
                                        </div>
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label>To:</label>
                                            <input type="date" id="end_date" class="form-control" name="to_date" value="{{ $to_date }}">
                                        </div>
                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                            <label for=""> </label>
                                            <button class="btn btn-sm btn-primary me-2 mt-4" id="kt_search"><i class="la la-search"></i> Search</button>
                                            <a href="{{ route('admin.purchase-list') }}"class="btn btn-sm btn-secondary me-2 mt-4" id="kt_reset" type="reset" ><i class='la la-close'></i>Reset</a>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                        <thead>
                                           <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                <th class="column-title">
                                                    <label class="checkbox justify-content-center mr-2">
                                                    <input type="checkbox" id="checkAll"/><span></span>
                                                </th>
                                                <th class="column-title">Invoice</th>
                                                <th class="column-title">Supplier Name</th>
                                                <th class="column-title">Country</th>
                                                <th class="column-title">Return</th>
                                                <th class="column-title">Shape</th>
                                                <th class="column-title">Ref No</th>
                                                <th class="column-title">Carat</th>
                                                <th class="column-title">Color</th>
                                                <th class="column-title">Clarity</th>
                                                <th class="column-title">Cut</th>
                                                <th class="column-title">Polish</th>
                                                <th class="column-title">Symm</th>
                                                <th class="column-title">Fluor</th>
                                                <th class="column-title">Lab</th>
                                                <th class="column-title">Certificate No</th>
                                                <th class="column-title">Rate</th>
                                                <th class="column-title">Amount</th>
                                                <th class="column-title">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="render_string">
                                        @if (!empty($purchase))
                                            @foreach ($purchase as $value)
                                                @if (!empty($value->orders) && !empty($value->orders_items))
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="check_box" data-stone={!! 1 !!} data-carat={!! $value->orders_items->carat !!} data-price = "{!! $value->Orders->buy_price !!}"/>
                                                        </td>
                                                        <td>{{ $value->invoice_number }}</td>
                                                        <td>{{ $value->orders_items->supplier_name }}</td>
                                                        <td>{{ $value->orders_items->country }}</td>
                                                        <td>{{ ($value->Orders->return_price != 0) ? 'R' : '' }}</td>
                                                        <td>{{ $value->orders_items->shape }}</td>
                                                        <td>{{ $value->ref_no }}</td>
                                                        <td>{{ number_format($value->orders_items->carat,2) }}</td>
                                                        <td>{{ $value->orders_items->color }}</td>
                                                        <td>{{ $value->orders_items->clarity }}</td>
                                                        <td>{{ $value->orders_items->cut }}</td>
                                                        <td>{{ $value->orders_items->polish }}</td>
                                                        <td>{{ $value->orders_items->symmetry }}</td>
                                                        <td>{{ $value->orders_items->fluorescence }}</td>
                                                        <td>{{ $value->orders_items->lab }}</td>
                                                        <td>{{ $value->certificate_no }}</td>
                                                        <td>{{ $value->Orders->buy_rate }}</td>
                                                        <td>{{ $value->Orders->buy_price }}</td>
                                                        <td>{{ $value->created_at  }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr><td colspan="100%">No Record Found!!</td></tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {!! $purchase->links() !!}
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

            $('#kt_table_users').DataTable({
                'processing': true,
                "pageLength": 500
            });

            $("#checkAll").click(function(){
				$('#render_string input:checkbox').not(this).prop('checked', this.checked);
				checkbox_event();
			});

            $('.check_box').on("change", function() {
                checkbox_event();
            });

            function checkbox_event(){
				var total = 0;
				var stone = 0;
				var carat = 0;
				$(this).parents("tr").removeClass("success");
				$('.check_box:checked').each(function() {
                    $(this).parents("tr").addClass("success");
					stone += parseInt($(this).data('stone'));
                    carat += $(this).data('carat');
                    total += $(this).data('price');
				});
				$('#total_pcs').html(stone);
				$('#total_carat').html((carat).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
				$('#total_price').html((total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			};
        });
    </script>
</body>
</html>
