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
                                    <span class="card-label fw-bolder text-dark">Parcel Goods List</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action" id="kt_table_users">
                                        <thead>
                                           <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                <th class="column-title">Customer Name</th>
                                                <th class="column-title">Diamond Type</th>
                                                <th class="column-title">Shape</th>
                                                <th class="column-title">Color</th>
                                                <th class="column-title">Clarity</th>
                                                <th class="column-title">Cut</th>
                                                <th class="column-title">MM</th>
                                                <th class="column-title">Carat</th>
                                                <th class="column-title">Pieces</th>
                                                <th class="column-title">Order Number</th>
                                                {{-- <th class="column-title">Price</th> --}}
                                                <th class="column-title">Comment</th>
                                                <th class="column-title">Send Mail</th>
                                                <th class="column-title">Comment</th>
                                                <th class="column-title">Fill Price</th>
                                                <th class="column-title">Action</th>
                                                <th class="column-title">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody id="render_string">
                                        @if (!empty($parcels))
                                            @foreach ($parcels as $parcel)
                                                <tr>
                                                    <td>{{ optional($parcel->customers)->companyname }}</td>
                                                    <td>{{ ($parcel->diamond_type == 'PW') ? 'Natural' : 'Lab Grown' }}</td>
                                                    <td>{{ $parcel->shape }}</td>
                                                    <td>{{ $parcel->color }}</td>
                                                    <td>{{ $parcel->clarity }}</td>
                                                    <td>{{ $parcel->cut }}</td>
                                                    <td>{{ $parcel->size }}MM</td>
                                                    <td>{{ $parcel->carat }}</td>
                                                    <td>{{ $parcel->pcs }}</td>
                                                    <td>{{ $parcel->order_no }}</td>
                                                    {{-- <td>{{ $parcel->price }}</td> --}}
                                                    <td>{{ $parcel->comments }}</td>
                                                    <td>
                                                        <button class="btn btn-icon btn-warning btn-sm me-3 sendmailparcelgoods" data-id="{{ $parcel->id }}" data-comment="{{ $parcel->comments }}" data-price="{{ $parcel->price }}">
                                                            <span class="svg-icon svg-icon-muted svg-icon-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor"></path>
                                                                    <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-icon btn-primary btn-sm me-3 parcel_comment" data-id="{{ $parcel->id }}">
                                                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                        <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <input class="form-control price" id="exchange_{{  $parcel->id }}" data-id="{{  $parcel->id }}" value="{{ $parcel->price }}" type="number" size="4" style="min-width:100px;">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm commentparcel" data-id ="{{ $parcel->id }}" data-sku="{{ $parcel->sku }}" data-comment = "{{ $parcel->comments }}">Comment</button>
                                                    </td>
                                                    <td>{{ $parcel->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="100%">No Record Found!!</td></tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {!! $parcels->links() !!}
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
        <div class="modal fade " id="header-modals" tabindex="-1" role="dialog" aria-labelledby="header-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl " role="document"  >
                <div class="modal-content"   >
                    <div class="card mt-5" >
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="header-modalLabel">Paracel Comment Details</h5>
                            <div class='btn btn-icon btn-sm btn-active-light-primary ms-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>
                        </div>

                        <div class="card-body py-4 " >
                            <div class="table-wrapper" style="max-height: 700px;; overflow-y: scroll;"   >
                                <table class="table mx-auto " data-spy="scroll"  >
                                    <thead>
                                        <tr>
                                            <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>id</b></th>
                                            <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>User Type</b></th>
                                            <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>Comment</b></th>
                                            <th  class="min-w-125px sorting snipcss0-8-186-190 font-weight-bold"><b>Date</b></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                'ordering':false,
                "pageLength": 100,
            });
            $(document).on("click",".parcel_comment",function(e){
                $('#header-modals').modal('show');
            });

            $('#render_string').delegate('.price', 'blur', function (e) {
                var id = $(this).attr('data-id');
				var price = $(this).val();
				if (price != "")
				{
                    if(price > 0){
                        blockUI.block();
                        request_call("{{ url('update-parcel-goods') }}", "id=" + $.trim(id) + "&status=" + 'price_change' + "&price=" + price);
                        blockUI.release();
                    }
                    else{
                        Swal.fire({
                                    title: "error",
                                    icon: "error",
                                    text: 'enter value more than 0',
                                    type: "error",
                                })
                    }
				}
			});

            $('#render_string').delegate('.sendmailparcelgoods','click',function(){
                var id = $(this).data('id');
                var comment = $(this).data('comment');
                var price = $(this).data('price');
                if(price == null || price == 0){
                    Swal.fire("Warning!", "Please Enter Price Before You Send Mail!", "warning");
                }
                else{
                    if(comment == null || comment == ''){
                        Swal.fire({
                            icon:'warning',
                            title: "Send mail?",
                            text: 'You Have Not Entered Comment Are You sure you want to send mail to customer ?',
                            type: "Warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes, Send!"
                        }).then((result) => {
                            if(result.value){
                                blockUI.block();
                                request_call("{{ url('admin-send-mail-parcel') }}", "id=" + id);
                                xhr.done(function(returnmail) {
                                    blockUI.release();
                                    if(returnmail.success){
                                            Swal.fire({
                                                title: "Success",
                                                icon:"success",
                                                text: returnmail.success,
                                                type: "success",
                                            }).then((result) => {
                                                location.reload();
                                            });
                                    }
                                    else{
                                        Swal.fire({
                                                title: "error",
                                                icon:"error",
                                                text: returnmail.error,
                                                type: "error",
                                            })
                                    }
                                });
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            icon:'question',
                            title: "Send mail?",
                            text: 'Do you want to send Mail to Customer ?',
                            type: "question",
                            showCancelButton: true,
                            confirmButtonText: "Yes, Send!"
                        }).then((result) => {
                            if(result.value){
                                blockUI.block();
                                request_call("{{ url('admin-send-mail-parcel') }}", "id=" + id);
                                xhr.done(function(returnmail) {
                                    blockUI.release();
                                    if(returnmail.success){
                                            Swal.fire({
                                                title: "Success",
                                                icon:"success",
                                                text: returnmail.success,
                                                type: "success",
                                            }).then((result) => {
                                                location.reload();
                                            });
                                    }
                                    else{
                                        Swal.fire({
                                                title: "error",
                                                icon:"error",
                                                text: returnmail.error,
                                                type: "error",
                                            })
                                    }
                                });
                            }
                        });
                    }
                }
            });

            $('#render_string').delegate('.commentparcel','click',function(){
                var id = $(this).data('id');
                var sku=$(this).data('sku');
                $("#header-modal").html('<div class="modal-dialog ">'
                                +'<div class="modal-content">'
                                    +'<div class="modal-header">'
                                        +'<h4 class="modal-title">Add A Comment On This Parcel </h4>'
                                        +'<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>'
                                    +'</div>'
                                    +'<div class="modal-body">'
                                        +'<div class="row">'
                                            +'<div class="col-md-12 col-sm-12 col-xs-12">'
                                                +'<div class="row " style="width: 100%;">'
                                                    +'<textarea class="form-control comment" rows="3" placeholder="Comments" required name="comment" maxlength="100" id="comment"> </textarea>'
                                                    +'<h3 class="mt-3"><span class="com text-danger"></span></h3>'
                                                +'</div>'
                                            +'</div>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="modal-footer justify-content-between">'
                                        +'<button type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</button>'
                                        +'<button type="button" class="btn btn-primary comment_add">Yes</button>'
                                    +'</div>'
                                +'</div>'
                            +'</div>'
                );
                $('#header-modal').modal('show');

                $('#header-modal').delegate('.comment_add', 'click', function() {
                    $('.com').html('');
                    var comment = $("#comment").val();
                    if (comment==" ") {
                        Swal.fire({
                                    title: "error",
                                    icon:"error",
                                    text: 'Comment are required!',
                                    type: "error",
                            })
                    }else{
                        $('#header-modal').modal('hide');
                        blockUI.block();
                        request_call("{{ url('update-parcel-goods')}}",  "status=" + "comment_add" + "&id=" + id + "&sku=" + sku + "&comment=" + encodeURIComponent(comment));
                        xhr.done(function(updategoods) {
                        if(updategoods.success){
                            if ($.trim(updategoods.success) != "") {
                                blockUI.release();
                                Swal.fire({
                                    title: "Success",
                                    icon:"success",
                                    text: updategoods.success,
                                    type: "success",
                                }).then((result) => {
                                    location.reload();
                                });
                            }
                        }
                        else{
                            Swal.fire({
                                    title: "error",
                                    icon:"error",
                                    text: 'Something Went Wrong!',
                                    type: "error",
                                })
                        }
                    })
                    }
                });
            })
            $(document).on("click",".parcel_comment",function(e){
                var parcel_id=$(this).data('id');
                blockUI.block();
                request_call("{{ url('parcel_comment')}}", "parcel_id=" + parcel_id);
                    xhr.done(function(mydata) {
                     blockUI.release();
                     $('.tbody').html(mydata);
                });
            });
        });
    </script>
</body>
</html>
