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
    <style>
        .kycdropdown{
            inset:-25px -85px auto auto !important;
        }
    </style>

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


								@if ($errors->any())
									<div class="alert alert-danger alert-icon" role="alert"><i class="uil uil-times-circle"></i>
										@foreach ($errors->all() as $error)
											{{ $error }}
										@endforeach
									</div>
								@endif
								<div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Supplier List</span>
											</h3>
										</div>
									</div>
									<div class="card-body py-4">
                                        <form action="{{ route('supplier.list-post') }}" method="POST" id="supplier_form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <select class="form-select" id="sales_person" name="sales_person">
                                                        <option value="">Supplier Manager</option>
                                                        @foreach ($managers as $manager)
                                                            <option value="{{ $manager->id }}">{{ $manager->firstname }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                <select class="selectpicker form-select" data-show-subtext="true" data-live-search="true" id="supplier_ids" name="supplier_ids">
                                                <option value="">Supplier Name</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->sup_id }}">{{ $supplier->supplier_name }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-select" id="diamond_type" name="diamond_type">
                                                        <option value="">Diamond Type</option>
                                                        <option value="Lab Grown">Lab Grown</option>
                                                        <option value="Natural">Natural</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-select" id="active_inactive" name="active_inactive">
                                                        <option value="">status Type</option>
                                                        <option value="0">Active</option>
                                                        <option value="1">Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-select" id="kyc_done_not" name="kyc_done_not">
                                                        <option value="">KYC Status</option>
                                                        <option value="0">Done</option>
                                                        <option value="1">Not Done</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select class="form-select" id="country" name="country">
                                                        <option value="">Select Country</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->country }}"class="form-control">{{ $country->country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-md-2">
                                                    <button class="btn btn-success btn-icon" type="button" id="btnSearch"><i class="fa fa-search"></i></button>
                                                    <button class="btn btn-danger btn-icon ms-2" type="reset" id="btnSearch"><i class='fa fa-times'></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        <input type="hidden" edit="editpermission" value="{{ $permission->edit }}">
                                        <input type="hidden" edit="deletepermission" value="{{ $permission->delete }}">

										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
															<th class="w-10px pe-2 sorting_disabled"></th>
															<th class="column-title">Actions</th>
                                                            <th class="column-title">ID</th>
                                                            <th class="column-title">KYC</th>
															<th class="column-title">Supplier</th>
															<th class="column-title">Type</th>
															<th class="column-title">Upload</th>
															<th class="column-title">Status</th>
															<th class="column-title">Active</th>
															<th class="column-title">Invalid</th>
															<th class="column-title">Conflicted</th>
                                                            <th class="column-title">R Policy</th>
                                                            <th class="column-title">markup</th>
                                                            <th class="column-title">Supplier Manager</th>
															<th class="column-title">Created At</th>
															<th class="column-title">Search</th>
															<th class="column-title">File</th>
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold" id="render_string">
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

	<script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");

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

            $('#render_string').delegate('.validcount', 'click', function() {
                var table = $(this).data('table');
                var supplier_id = $(this).data('supplier_id');
                blockUI.block();
                request_call("{{ url('valid-diamond-count')}}", "table=" + table+"&supplier_id=" +supplier_id);
                xhr.done(function(mydata) {
                    blockUI.release();
                    $('#sup'+supplier_id).html(mydata.diamond);
                });
            });

            $('#render_string').delegate('.fa-plus', 'click', function() {
                $(".detail_view").each(function(e) {
                    $(this).remove();
                });

                $(".fa-minus").each(function(e) {
                    $(this).removeClass("fa-minus").addClass("fa-plus");
                });

                $(this).removeClass("fa-plus").addClass("fa-minus");

                var parent_tr = $(this).parents('tr');
                var id = $(this).data('id');
                blockUI.block();
                request_call("{{ url('supplier-upload-report')}}", "id="+id);
                xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        blockUI.release();
                        parent_tr.after("<tr class='detail_view'> <td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('#render_string').delegate('.fa-minus', 'click', function() {
                $(this).removeClass("fa-minus").addClass("fa-plus");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
            });

            var dt = $('#kt_table_users').DataTable({
                processing: true,
                pageLength: 3,
                serverSide: true,
                orderable: false,
                ajax: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    "url": "{{route('supplier.list-post')}}",
                    "type": "POST",
                },
                columns: [
                        { data: 'plush' },
                        { data: null },
                        { data: 'id' },
                        { data: 'kyc' },
                        { data: 'suppliers' },
                        { data: 'diamond_type' },
                        { data: 'upload_mode' },
                        { data: 'stock_status' },
                        { data: 'valid' },
                        { data: 'invalid' },
                        { data: 'conflicted' },
                        { data: 'return_allow' },
                        { data: 'markup' },
                        { data: 'staffname' },
                        { data: 'created_at' },
                        { data: 'stonesshow' },
                        { data: 'file' },
                    ],
                    order: [[ 2, "asc" ]],
                    columnDefs: [
                    {
                        targets: 1,
                        data: null,
                        orderable: true,
                        className: 'text-end',
                        render: function (data, type, row) {
                            var sup_id = row.id;
                            var editpermission = row.editpermission;
                            var deletepermission = row.deletepermission;
                            var test = '<a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions'
                                             +'<span class="svg-icon svg-icon-5 m-0">'
                                                 +'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">'
                                                    +'<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"></path>'
                                                +'</svg>'
                                            +'</span>'
                                        +'</a>'
                                        +'<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true" style="min-width: 9%;">';
                                            if(editpermission == 1){
                                                test += '<div class="menu-item px-3">'
                                                            +'<a href="{{ url("suppliers-edit") }}/'+sup_id+'" class="menu-link px-3">Edit</a>'
                                                        +'</div>'
                                                        +'<div class="menu-item px-3">'
                                                            +'<a href="{{ url("invoice-list") }}/'+row.id+'" class="menu-link px-3">Invoice</a>'
                                                        +'</div>'
                                                        +'<div class="menu-item px-3">'
                                                            +'<a href="{{ url("suppliers-password") }}/'+row.id+'" class="menu-link px-3">Change Password</a>'
                                                        +'</div>';
                                            }
                                            if(deletepermission == 1){
                                                test += '<div class="menu-item px-3">'
                                                            +'<a href="{{ url("suppliers-delete") }}/'+row.id+'" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>'
                                                        +'</div>';
                                            }
                                            if(editpermission == 1){
                                                test+= '<div class="menu-item px-3">'
                                                            +'<a href="{{ url("supplier-markup") }}/'+row.id+'" class="menu-link px-3" data-kt-users-table-filter="price_row">Markup setting</a>'
                                                        +'</div>';
                                            }
                                        test+= '</div>';
                                        return test;
                        },
                    },
                ],
            });
            table = dt.$;
            dt.on('draw', function () {
                KTMenu.createInstances();
            });
        });

        $('#btnSearch').click(function(){
            var sales_person = $("#sales_person").val();
            var supplier_ids = $("#supplier_ids").val();
            var diamond_type = $("#diamond_type").val();
            var active_inactive = $("#active_inactive").val();
            var kyc_done_not = $("#kyc_done_not").val();
            var country = $('#country').val();

            if ($.fn.dataTable.isDataTable('#kt_table_users')) {
                $('#kt_table_users').DataTable().destroy();
            }

            $('#kt_table_users').DataTable({
                processing: true,
                pageLength: 3,
                serverSide: true,
                Sortable: true,
                ajax: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{route('supplier.list-post')}}",
                    data:{sales_person_name:sales_person,supplier_id:supplier_ids,type:diamond_type,status:active_inactive,kyc_status:kyc_done_not,country:country},
                    type: "POST"
                },
                columns: [
                    { data: 'plush' },
                    { data: null },
                    { data: 'id' },
                    { data: 'kyc' },
                    { data: 'suppliers' },
                    { data: 'diamond_type' },
                    { data: 'upload_mode' },
                    { data: 'stock_status' },
                    { data: 'valid' },
                    { data: 'invalid' },
                    { data: 'conflicted' },
                    { data: 'return_allow' },
                    { data: 'markup' },
                    { data: 'staffname' },
                    { data: 'created_at' },
                    { data: 'stonesshow' },
                    { data: 'file' },
                ],
                order: [[ 2, "asc" ]],
                columnDefs: [
                {
                    targets: 1,
                    data: null,
                    orderable: true,
                    className: 'text-end',
                    render: function (data, type, row) {
                        var sup_id = row.id;
                        var editpermission = row.editpermission;
                        var deletepermission = row.deletepermission;
                        var test = '<a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions'
                                        +'<span class="svg-icon svg-icon-5 m-0">'
                                            +'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">'
                                                +'<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />'
                                            +'</svg>'
                                        +'</span>'
                                    +'</a>'
                                    +'<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true" style="min-width: 9%;">';
                                        if(editpermission == 1){
                                            test += '<div class="menu-item px-3">'
                                                        +'<a href="{{ url("suppliers-edit") }}/'+sup_id+'" class="menu-link px-3">Edit</a>'
                                                    +'</div>'
                                                    +'<div class="menu-item px-3">'
                                                        +'<a href="{{ url("invoice-list") }}/'+row.id+'" class="menu-link px-3">Invoice</a>'
                                                    +'</div>'
                                                    +'<div class="menu-item px-3">'
                                                        +'<a href="{{ url("suppliers-password") }}/'+row.id+'" class="menu-link px-3">Change Password</a>'
                                                    +'</div>';
                                        }
                                        if(deletepermission == 1){
                                            test += '<div class="menu-item px-3">'
                                                        +'<a href="{{ url("suppliers-delete") }}/'+row.id+'" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>'
                                                    +'</div>';
                                        }
                                        if(editpermission == 1){
                                            test+= '<div class="menu-item px-3">'
                                                        +'<a href="{{ url("supplier-markup") }}/'+row.id+'" class="menu-link px-3" data-kt-users-table-filter="price_row">Markup setting</a>'
                                                    +'</div>';
                                        }
                                    test+= '</div>';
                                    return test;
                    },
                },
                ],
            });
        });

	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
