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
								<div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Pending Supplier List</span>
											</h3>
										</div>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
															<th class="w-10px pe-2 sorting_disabled"></th>
															<th class="min-w-80px">Actions</th>
															<th class="min-w-105px">Supplier</th>
															<th class="min-w-105px">Email</th>
															<th class="min-w-105px">Type</th>
															<th class="min-w-105px">Mobile</th>
															<th class="min-w-105px">City</th>
															<th class="min-w-105px">Country</th>
															<th class="min-w-80px">Created At</th>
                                                            <th class="min-w-80px">Action</th>
                                                            <th class="min-w-80px">Action</th>
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold">
														@if(!empty($suppliers))
															@foreach($suppliers as $supplier)
															<tr>
																<td><i class="fa fa-plus" data-id="171" data-sup_id="{{ $supplier->users->id }}"></i></td>
																<td>
																	<a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
																		<span class="svg-icon svg-icon-5 m-0">
																			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																				<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />
																			</svg>
																		</span>
																	</a>
																	<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
																		<div class="menu-item px-3">
																			<a href="{{url('pending-supplier-delete')}}/{{ $supplier->users->id}}" class="menu-link px-3" data-kt-users-table-filter="delete_row">Delete</a>
																		</div>
                                                                        <div class="menu-item px-3">
                                                                            <a href="{{ url('supplier-move/'.$supplier->users->id) }}" class="menu-link px-3">Move to Customer</a>
                                                                        </div>
																	</div>
																</td>
																<td>
																	<div class="d-flex flex-column">
																		<a class="text-gray-800 text-hover-primary mb-1">{{ $supplier->users->companyname }}</a>
																		<span>{{ $supplier->users->firstname }} {{ $supplier->users->lastname }}</span>
																	</div>
																</td>
																<td>{{ $supplier->users->email }}{!! ($supplier->users->email_verified_at != null) ? '<i class="fa fa-check" aria-hidden="true" style="color: green;"></i>' : ''; !!}</td>
																<td>{{ $supplier->diamond_type }}</td>
																<td>{{ $supplier->users->mobile }}</td>
																<td>{{ $supplier->city }}</td>
																<td>{{ $supplier->country }}</td>
																<td>{{ $supplier->users->created_at }}</td>
																<td nowrap>
                                                                    <a class="btn btn-icon btn-sm btn-success activate" id="{{ $supplier->users->id }}" data-companyname="{{ $supplier->users->companyname }}" data-added_by="{{ $supplier->users->added_by }}"  title="Approve">
                                                                        <span class="svg-icon svg-icon-muted svg-icon-1">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                                                            <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                    <a class="btn btn-icon btn-sm btn-warning resendemail" id="{{ $supplier->users->id }}" title="Resend verifiction email">
                                                                        <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor"/>
                                                                            <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor"/>
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-primary btn-sm followup" data-sup_id={{ $supplier->users->id }}>Follow Up</button>
                                                                </td>
															</tr>
															@endforeach
														@else
															<!-- <tr>
																<td colspan="100%"> No record Found</td>
															</tr> -->
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

	<script type="text/javascript">
		localStorage.setItem("ak_search", "");
		localStorage.setItem("lg_search", "");

		// $('#kt_table_users').DataTable({
        //     'processing': true,
		// });

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

            $('#kt_table_users').DataTable({
                'processing': true,
                "pageLength": 100
            });

            $('.table').delegate('.fa-plus', 'click', function() {
                $(".detail_view").each(function(e) {
                    $(this).remove();
                });

                $(".fa-minus").each(function(e) {
                    $(this).removeClass("fa-minus").addClass("fa-plus");
                });

                $(this).removeClass("fa-plus").addClass("fa-minus");

                var parent_tr = $(this).parents('tr');
                var sup_id = $(this).data('sup_id');
                blockUI.block();
                request_call("{{ url('followup-details')}}", "sup_id=" + sup_id);
                xhr.done(function(mydata) {
                    if ($.trim(mydata.detail) != "") {
                        blockUI.release();
                        parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
                    }
                });
            });

            $('.table').delegate('.fa-minus', 'click', function() {
                $(this).removeClass("fa-minus").addClass("fa-plus");
                var parent_tr = $(this).parents('tr');
                parent_tr.next("tr.detail_view").remove();
            });

            $('.table').delegate('.followup', 'click', function() {
				var sup_id = $(this).data('sup_id');
                Swal.fire({
                    width: '25%',
                    icon:'question',
                    title:'Follow Up Details',
                    html:`<div class="container">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="float-start">Comment:</label>
                                <textarea class="form-control" id="comment"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="float-start">Followed Up By :</label>
                                <select for="follow up by" class="form-select" id="followed_up_by">
                                    <option value="">Select A Manager</option>
                                    <?php foreach($users as $user){
                                        print_r('<option value="'.$user->id.'" >'.$user->firstname.' '.$user->lastname.'</option>');
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    preConfirm: () => {
						const comment = Swal.getPopup().querySelector('#comment').value
						const followed_up_by = Swal.getPopup().querySelector('#followed_up_by').value
                        if(!comment)
                        {
                            Swal.showValidationMessage(`Please Comment what You Followed Up!`)
                        }
                        if(!followed_up_by)
                        {
                            Swal.showValidationMessage(`Please Select A User Who Followed Up!`)
                        }
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        let comment =$('#comment').val();
                        let followed_up_by =$('#followed_up_by').val();
                        blockUI.block();
                        request_call("{{ url('pending-supplier-followup')}}",'sup_id=' + sup_id + '&comment=' + comment + '&followed_up_by=' + followed_up_by);
                        xhr.done(function(mydata) {
                            blockUI.release();
                            Swal.fire({
                                title: "Success",
                                text: 'Follow Up Details Added SuccessFully....!!',
                                icon: "success",
                                type: "Success",
                            }).then((result) => {
                                location.reload();
                            });
                        });
                    }
                });
            })

			$('.table').delegate('.activate', 'click', function() {
				var ids = this.id;
				var companynames = $(this).data('companyname');
				var added_by = $(this).data('added_by');
				// if(added_by == ''){
                blockUI.block();
				request_call("{{ route('supplier.pending.popup') }}", "id=" + ids );
				xhr.done(function(data) {
                    blockUI.release();
					var type = '';
					type += "<option value='Natural' "+( (data.pending_supplier.diamond_type  == 'Natural' ) ? 'selected' : '') +" > Natural </option>";
					type += "<option value='Lab Grown' "+( (data.pending_supplier.diamond_type  == 'Lab Grown' ) ? 'selected' : '') +" > Lab Grown</option>";
					var staff = '';
					$.each(data.staff, function(associates, item) {
						staff += "<option value='"+ item.id +"'>"+ item.firstname +"</option>";
					});
					$("#header-modal").html('<div class="modal-dialog modal-lg">'
									+'<div class="modal-content">'
										+'<div class="modal-header">'
											+'<h4 class="modal-title">Are you sure you want to Approve Supplier request? Company Name = '+companynames+'</h4>'
											+'<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>'
										+'</div>'
										+'<div class="modal-body">'
											+'<div class="row mb-4">'
												+'<div class="col-md-4 col-sm-12 col-xs-12">'
													+'<span>Diamond Type</span>'
												+'</div>'
												+'<div class="col-md-8 col-sm-12 col-xs-12">'
													+'<input type="hidden" id="psupplier_id" value='+ids+'>'
													+'<select for="diamond Type" id="type" class="form-control">'
														+type
													+'</select>'
												+'</div>'
											+'</div>'
											+'<div class="row">'
												+'<div class="col-md-4 col-sm-12 col-xs-12">'
														+'<span >Supplier Manager</span>'
												+'</div>'
												+'<div class="col-md-8 col-sm-12 col-xs-12">'
													+'<select for="diamond Type" id="staff" class="form-control">'
														+staff
													+'</select>'
												+'</div>'
											+'</div>'
										+'</div>'
										+'<div class="modal-footer justify-content-between">'
											+'<button type="button" class="btn btn-primary Submit-Popup-Pending-Suppliers">Submit</button>'
										+'</div>'
									+'</div>'
								+'</div>'
								);
					$('#header-modal').modal('show');
				});
				// }
				// else{
				// 	console.log('a');
					// blockUI.block();
					// request_call("{{url('activate-suppliers')}}", "id=" + $.trim(ids));
					// xhr.done(function(mydata) {
					// 	blockUI.release();
					// 	if(mydata.success)
					// 	{
					// 		Swal.fire({title: "Success", text: 'Supplier Account Approved.', type: "success"}).then((result) => { location.reload(); });
					// 	}
					// 	else
					// 	{
					// 		Swal.fire({title: "Warning", text: mydata.error, type: "warning"}).then((result) => { location.reload(); });
					// 	}
					// });
				// }

			});

			$('#header-modal').delegate('.Submit-Popup-Pending-Suppliers', 'click', function() {
                $('#header-modal').modal('hide');
				var type = $("#type").val();
				var staff = $("#staff").val();
				var id = $('#psupplier_id').val();
                blockUI.block();
				request_call("{{ url('post-popup-pending-Suppliers')}}", "type=" + type + "&staff=" + staff + "&id=" +id);
				xhr.done(function(mydata) {
                    blockUI.release();
					Swal.fire({
						title: "Success",
						text: 'Supplier Added successfully...!!',
						type: "Success",
					}).then((result) => {
						location.reload();
					});
				});
			});


            $('.table').delegate('.resendemail', 'click', function() {
				var ids = this.id;
                Swal.fire({
                    title: 'Are You Sure You Want To send Mail To Supplier',
                    icon: 'question',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Send Mail!',
                    cancelButtonText: "No, Don't Send!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        blockUI.block();
                        request_call("{{url('resend-email-supplier')}}", "id=" + $.trim(ids));
                        xhr.done(function(mydata) {
                            if(mydata.success)
                            {
                                blockUI.release();
                                Swal.fire({title: "Success", icon:"success", text: 'Email sent To Supplier.', type: "success"}).then((result) => { location.reload(); });
                            }
                            else
                            {
                                blockUI.release();
                                Swal.fire({title: "Warning", icon:"warning", text: mydata.error, type: "warning"}).then((result) => { location.reload(); });
                            }
                        });
                    }
                });
			});
		});
	</script>
	<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
