<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ config('app.website') }}" />
    <meta name="keywords" content="{{ config('app.website') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .card{
            /* box-shadow: 2px 3px 4px 5px !important; */
        }
        .dataTables_filter{
            text-align:right;
        }
        .pagination{
            justify-content:right !important;
        }
        .btn-light-info:hover{
        background-color: rgb(12, 4, 4) ;
        }
        .content{
            padding: 0px !important;
        }
        .table tr{
            white-space: nowrap; overflow: hidden; text-overflow:ellipsis;
        }
        .tox-statusbar__branding{
            display: none;
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/a98rnje984ktgtk8tn1hhoqzrnsiiltw0dqgrmsdywxx7stm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
       <script>
        tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
       </script>
    @include('admin/css')
</head>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed"
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
								<div class="card"style="background-color:rgb(245 245 245)">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Leads User Report Details</span>
											</h3>
										</div>
									</div>
									<div class="card-body py-4"  >
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            <div class="d-flex flex-column flex-xl-row snipcss-kxjVn">
                                                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                                                  <div class="card mb-5 mb-xl-8" >
                                                    <div class="card-body pt-15">
                                                      <div class="d-flex flex-center flex-column mb-5">
                                                        <div class="symbol symbol-150px symbol-circle mb-7">
                                                          <img src="{{ asset('assets/images/user.png')}}" alt="image"   >
                                                        </div>
                                                        <h1>{{$result['record']->leads->firstname}} {{$result['record']->leads->lastname}}</h1>
                                                        <a href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">
                                                            {{$result['record']->leads->email}}
                                                        </a>
                                                      </div>
                                                      <div class="d-flex flex-stack fs-4 py-3">
                                                        <div class="fw-bold">
                                                          Details
                                                        </div>
                                                        <div>
                                                            <span class="svg-icon svg-icon-primary svg-icon-3x commentAdd" data-lead_id="{{$result['record']->leads->id}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L5,18 C3.34314575,18 2,16.6568542 2,15 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 Z M6.16794971,10.5547002 C7.67758127,12.8191475 9.64566871,14 12,14 C14.3543313,14 16.3224187,12.8191475 17.8320503,10.5547002 C18.1384028,10.0951715 18.0142289,9.47430216 17.5547002,9.16794971 C17.0951715,8.86159725 16.4743022,8.98577112 16.1679497,9.4452998 C15.0109146,11.1808525 13.6456687,12 12,12 C10.3543313,12 8.9890854,11.1808525 7.83205029,9.4452998 C7.52569784,8.98577112 6.90482849,8.86159725 6.4452998,9.16794971 C5.98577112,9.47430216 5.86159725,10.0951715 6.16794971,10.5547002 Z" fill="#000000"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                            <span class="svg-icon svg-icon-success svg-icon-3x  send-email" data-toggle="modal" data-target="#exampleModal" data-email="{{$result['record']->leads->email}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z" fill="#000000" opacity="0.3"/>
                                                                        <path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000"/>
                                                                    </g>
                                                                 </svg>
                                                          </span>
                                                        </div>
                                                      </div>
                                                      <div class="separator separator-dashed my-3">
                                                      </div>
                                                      <div class="pb-5 fs-6">
                                                        <div class="fw-bold mt-5">
                                                          Account ID
                                                        </div>
                                                        <div class="text-gray-600">
                                                            {{$result['record']->leads->id}}
                                                        </div>
                                                        <div class="fw-bold mt-5">
                                                           Email
                                                        </div>
                                                        <div class="text-gray-600">
                                                          <a href="#" class="text-gray-600 text-hover-primary">
                                                            {{$result['record']->leads->email}}
                                                          </a>
                                                        </div>
                                                        <div class="fw-bold mt-5">
                                                           Address
                                                        </div>
                                                        <div class="text-gray-600">
                                                            {{$result['record']->leads->country}}
                                                            {{$result['record']->leads->state}}
                                                          <br>
                                                          {{$result['record']->leads->city}}
                                                        </div>
                                                        <div class="fw-bold mt-5">
                                                            Fax  number
                                                         </div>
                                                         <div class="text-gray-600">
                                                             {{$result['record']->leads->fax_number}}
                                                                   </div>
                                                         <div class="fw-bold mt-5">
                                                            Website url
                                                         </div>
                                                         <div class="text-gray-600">
                                                             {{$result['record']->leads->website_url}}
                                                         </div>
                                                         <div class="fw-bold mt-5">
                                                            Company name
                                                         </div>
                                                         <div class="text-gray-600">
                                                             {{$result['record']->leads->company_name}}
                                                         </div>
                                                         <div class="fw-bold mt-5">
                                                            Associated_ Company
                                                         </div>
                                                         <div class="text-gray-600">
                                                             {{$result['record']->leads->associated_company}}
                                                         </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="flex-lg-row-fluid ms-lg-15" >
                                                  <div class="tab-content" id="myTabContent" >
                                                    <div class="tab-pane fade show active" id="kt_ecommerce_customer_overview" role="tabpanel" >
                                                      <div class="card pt-4 mb-6 mb-xl-9" >
                                                        <div class="card-header border-0">
                                                          <div class="card-title">
                                                            <h2>
                                                              Follow Up Date
                                                            </h2>
                                                        </div>
                                                        <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm" style="float:right;height:30px"><i class="fa fa-arrow-left"></i></a>
                                                        </div>
                                                        <div class="card-body pt-0 pb-5">
                                                            <div class="col-2 mt-2" style="float:right;padding:0%;">
                                                                  <label for="" style="float:right">Type:</label>
                                                                  <select class="form-control px-2 h-25 fs-8" name="" id="type-filter" >
                                                                    <option value="call" class="">Call</option>
                                                                    <option value="email">Email</option>
                                                                  </select>
                                                            </div>
                                                            <table class="table align-middle table-row-dashed gy-5 dataTable no-footer"  id="myTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Type</th>
                                                                        <th>Comment</th>
                                                                        <th>Follow date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($result['leads_comment'] as $comment)
                                                                    <tr>
                                                                        <td scope="row">
                                                                            @if($comment->type ==1)
                                                                            <span class="badge badge-secondary">Call</span>
                                                                            @else
                                                                            <span class="badge badge-secondary">Email</span>
                                                                            @endif
                                                                        <td>{{ $comment->comment }}</td>
                                                                        @php
                                                                            $date = date('Y-m-d H:i:s');
                                                                        @endphp
                                                                        <td>{{ date('Y-m-d h:i:s A', strtotime($comment->follow_up_date))  }} @if($comment->follow_up_date>$date) <span class="badge badge-warning">P</span>@endif </td>
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
                                                </div>
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
   {{-- send email bootstrap model  --}}
   <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send email to <span id="recipient-email"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_id">
                <div class="form-group">
                <label for="recipient-name" class="col-form-label">Recipient:</label>
                <input type="email" class="form-control" id="recipient-name" required>
                <input type="text" class="form-control" id="lead_id"  hidden required>
                </div>
                <div class="form-group">
                <label for="subject" class="col-form-label">Subject:</label>
                <input type="text" class="form-control" id="subject" required>
                </div>
                <div class="form-group">
                <label for="message" class="col-form-label">Message:</label>
                <textarea class="form-control"  id="default-editor" required></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary btn-sm" id="send-email">Send email</button>
        </div>
      </div>
    </div>
  </div>
    <script>
    var hostUrl = "assets/";
   </script>
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
    <script src="{{asset('assets/admin/js/comment.js')}}"></script>
        {{-- <Script>
        $('#myTable').DataTable();
        </script> --}}
        <script>
            $(document).ready(function() {
              var table = $('#myTable').DataTable();
              $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                  var typeFilter = $('#type-filter').val().toLowerCase();
                  var typeColumn = data[0].toLowerCase();
                  if (typeFilter === '' || typeFilter === typeColumn) {
                    return true;
                  }
                  return false;
                }
              );
              $('#type-filter').on('change', function() {
                table.draw();
              });
            });
            </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </body>
