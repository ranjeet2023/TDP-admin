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
                                    <span class="card-label fw-bolder text-dark">Purchase Bills</span>
                                </h3>
								<div class="card-toolbar">
                                    <button class="btn btn-sm btn-secondary me-2">No of Pcs : <span id="total_pcs"></span></button>
                                    <button class="btn btn-sm btn-secondary me-2">Amount : <span id="total_price">0</span></button>
									<a class="btn btn-success btn-sm me-3" href="{{ route('admin.purchase-bill-form') }}">Add a New Bill</a>
                                    <button class="btn btn-sm btn-secondary me-2">Total Bills : <span>{{ $count }}</span></button>
								</div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.post-purchase-bill') }}" method="POST">
                                    @csrf
                                    <div class="row mb-6">
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Supplier:</label>
                                            <select class="form-select" id="supplier" name="supplier">
                                                <option value="" selected>Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->users->id }}" {{ ($supplier->users->id == $supplier_id) ? 'selected' : '' }}>{{ $supplier->users->companyname }}</option>
                                                @endforeach
                                            </select>
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
                                            <label>Payment Status:</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="">Payment Status</option>
                                                <option value="paid" {{ ($status == 'paid') ? 'selected' : '' }}>PAID</option>
                                                <option value="unpaid" {{ ($status == 'unpaid') ? 'selected' : '' }}>UNPAID</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 d-flex align-items-center gap-2 gap-lg-3">
                                            <label></label>
                                            <button class="btn btn-sm btn-primary me-2 mt-4" id="kt_search"><i class="la la-search"></i> Search</button>
                                            <a href="{{ route('admin.purchase-bill') }}"class="btn btn-sm btn-secondary me-2 mt-4" id="kt_reset" type="reset" ><i class='la la-close'></i>Reset</a>
                                        </div>
                                    </div>
                                </form>
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade active show in" >
										<div class="table-responsive" style="margin-top: 10px;">
											<table class="table table-striped jambo_table bulk_action">
												<thead>
													<tr class="headings">
														<th class="column-title">
                                                            <label class="checkbox justify-content-center mr-2">
                                                            <input type="checkbox" id="checkAll"/><span></span>
                                                        </th>
														<th class="column-title">index</th>
														<th class="column-title">Supplier name</th>
														<th class="column-title">Image</th>
														<th class="column-title">Amount</th>
														<th class="column-title">Date</th>
														<th class="column-title">Due Date</th>
														<th class="column-title">Paid Amount</th>
														<th class="column-title">Paid Date</th>
														<th class="column-title">Amount Status</th>
														<th class="column-title">Action</th>
													</tr>
												</thead>
												<tbody id="render_string">

                                                    @foreach ($bills as $bill)
                                                    @php
                                                        $paid_amount = $bill->paid_amount;
                                                        if($paid_amount != null || $paid_amount != 0){
                                                            $difference = $paid_amount - $bill->amount;
                                                        }
                                                    @endphp
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="check_box" data-stone={!! 1 !!} data-price = {!! $bill->amount !!}/>
                                                            </td>

                                                            <td>{{ $bill->bill_id }}</td>
                                                            <td>{{ $bill->users->companyname }}</td>
                                                            <td><a href= "{{ url('/uploads/purchase_bill/'.$bill->image) }}" target="_blank"><button type="button" class="btn btn-info btn-icon btn-sm"><i class="fa fa-download"></i></button></td>
                                                            <td>{{ number_format($bill->amount,2) }}
                                                                @if ($bill->paid_amount != null || $bill->paid_amount != 0)
                                                                   @if ($difference > 0)
                                                                        (<span style="color:green;">{{ $difference }}</span>)
                                                                   @else
                                                                        (<span style="color:red;">{{ $difference }}</span>)
                                                                   @endif
                                                                @endif
                                                            </td>
                                                            <td>{{ $bill->date }}</td>
                                                            <td>
                                                                @if ( $bill->status == 'paid')
                                                                    <i class="fa fa-check" title="amount paid" style="color: green;"></i>
                                                                @else
                                                                    @php
                                                                        $due_date = intval(((strtotime($bill->date) + 1296000) - time())/(60*60*24));
                                                                    @endphp
                                                                    @if ($due_date > 0)
                                                                        {{ $due_date }} days Left
                                                                    @else
                                                                        {{ abs($due_date) }} days over due
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>{{ $bill->paid_amount }}</td>
                                                            <td>{{ $bill->paid_date }}</td>
                                                            <td>
                                                                @if ( $bill->status == 'paid')
                                                                    <span class="badge badge-primary">PAID</span>
                                                                @elseif($bill->status == 'unpaid')
                                                                    <span class="badge badge-danger">UNPAID</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($bill->status != 'paid')
                                                                    <button type="button" class="btn btn-warning btn-icon btn-sm me-1" id="actionbtn" data-bill_id ="{{ $bill->bill_id }}"><i class="fa fa-home"></i></button>
                                                                @endif
                                                                @if (Auth::user()->user_type == 1)
                                                                    <button class="btn btn-sm btn-icon btn-danger me-1 delete" title="Delete" data-bill="{{ $bill->bill_id }}"><i class="fa fa-trash"></i></button>
                                                                @endif
                                                                @if ($bill->comment == null)
                                                                    <button type="button" class="btn btn-success btn-icon btn-sm" id="commentbtn" data-bill_id ="{{ $bill->bill_id }}"data-comment="{{ $bill->comment }}" title = "Comment"><i class="fa fa-comment"></i></button>
                                                                @else
                                                                    <button type="button" class="btn btn-success btn-icon btn-sm" id="commentbtn" data-bill_id ="{{ $bill->bill_id }}"data-comment="{{ $bill->comment }}" title="{{ $bill->comment }}"><i class="fa fa-comment"></i></button>
                                                                @endif
                                                            </td>
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
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: mydata,
                });
            };

            $('#render_string').delegate('#commentbtn', 'click', function() {
                var bill_id = $(this).attr('data-bill_id');
                var comment = $(this).attr('data-comment');
                Swal.fire({
                    width:'30%',
					title: 'Enter A Comment On this Purchase bill !',
                    html: `<div class="container">
								<div class="row">
                                    Comment:<br/>
                                    <textarea type="textarea" class="form-control" id="comment" rows="5" cols="40" placeholder="Please Enter A Comment!">` + comment + `</textarea>
                                </div>
                            </div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Comment it!',
                    cancelButtonText: 'No!',
                    preConfirm: () => {
                        const comment = Swal.getPopup().querySelector('#comment').value

                        if(!comment){
                            Swal.showValidationMessage(`Please Enter comment !`)
                        }
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        let status = 'comment';
                        let comment = Swal.getPopup().querySelector('#comment').value;
                        blockUI.block();
                        request_call("{{ url('update-purchase-bill')}}", "status=" + status + "&comment=" + comment + "&bill_id=" + bill_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire("Success!", mydata.success,'success').then((result) => {
                                window.location.reload();
                            });
                        });
                    }
                });;

            });

            $('#render_string').delegate('.delete', 'click', function() {
                Swal.fire({
					title: 'Are you sure you want to Delete this Purchase Bill ?',
					text: "Delete This Purchase Bill ?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, Delete It!',
                }).then((result) => {
                    var bill = $(this).data('bill');
					if (result.isConfirmed) {
                        blockUI.block();
						request_call("{{ route('admin.delete-purchase-bill')}}", "bill=" + bill);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire("Deleted!", mydata.success,'error').then((result) => {
                                window.location.reload();
                            });
                        });
                    }
                })
            })

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
				$(this).parents("tr").removeClass("success");
				$('.check_box:checked').each(function() {
					$(this).parents("tr").addClass("success");
					stone += parseInt($(this).data('stone'));
                    total += (parseInt($(this).data('price')));
				});
				$('#total_pcs').html(stone);
				$('#total_price').html((total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			};

            $('#render_string').delegate('#actionbtn', 'click', function() {
                var bill_id = $(this).attr('data-bill_id');
                Swal.fire({
                    title:  '<u>Bill Details</u>',
                    width:'50%',
                    html: ` <div class="container">
                                <div class='row'>
                                    <div class='col-md-4'>
                                        Paid Amount :<br/>
                                        <input type="text" class="form-control" name="paid_amount" id="paid_amount" placeholder="Paid Amount">
                                    </div>
                                    <div class='col-md-4'>
                                        Paid Date :<br/>
                                        <input id="datetimepicker" class="form-control" placeholder="YYYY-MM-DD" >
                                    </div>
                                    <div class='col-md-4'>
                                        Amount Status:<br/>
                                        <Select class="form-select" name="status" id="paid_status">
                                            <option value="">Select amount Status</option>
                                            <option value="paid">paid</option>
                                            <option value="unpaid">unpaid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>`,
                    willOpen: function(){
                        flatpickrInstance = flatpickr(
                            Swal.getPopup().querySelector('#datetimepicker')
                        )
                    },
                    preConfirm: () => {
                        const paid_amount = Swal.getPopup().querySelector('#paid_amount').value
                        const datetimepicker = Swal.getPopup().querySelector('#datetimepicker').value
                        const paid_status = Swal.getPopup().querySelector('#paid_status').value

                        if(isNaN(paid_amount)){
                                Swal.showValidationMessage(`Please Input Number in Paid Amount`)
                            }
                        if(!paid_amount){
                            Swal.showValidationMessage(`Please Enter Paid Amount`)
                        }

                        if(!datetimepicker){
                            Swal.showValidationMessage(`Please Pick a Date`)
                        }

                        if(!paid_status){
                            Swal.showValidationMessage(`Please Select Amount Satus`)
                        }
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Confirm it!',
                }).then((result) => {
                    if (result.isConfirmed){
                        let status = 'update';
                        let paid_amount = Swal.getPopup().querySelector('#paid_amount').value;
                        let datetimepicker = Swal.getPopup().querySelector('#datetimepicker').value;
                        let paid_status = Swal.getPopup().querySelector('#paid_status').value;

                        blockUI.block();
                        request_call("{{ url('update-purchase-bill') }}", "status=" + status + "&paid_amount=" + paid_amount + "&datetimepicker=" + datetimepicker + "&paid_status=" + paid_status + "&bill_id=" + bill_id);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire("Success!", mydata.success,'success').then((result) => {
                                window.location.reload();
                            });
                        });
                    }
                });
        });
        });

    </script>
</body>
</html>
