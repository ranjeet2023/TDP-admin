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
        .tox-statusbar__branding{
            display: none;
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/a98rnje984ktgtk8tn1hhoqzrnsiiltw0dqgrmsdywxx7stm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
       <script>
        tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap code emoticoncodes image link lists media wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
       </script>
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
                                                <span class="card-label fw-bolder fs-3 mb-1">Leads List</span>
                                            </h3>
                                        </div>
                                        <div class="card-toolbar">
                                            <a class="btn btn-lg btn-primary " style="margin-right:2px" href="{{ url("leads-report") }}">Leads Report</a>
                                            <a class="btn btn-lg btn-success " href="{{ url("add-new-leads") }}">Add New Leads</a>
                                            <a href="{{url('create-email-template')}}"class="btn btn-success mx-1">Template</a>
                                        </div>
                                    </div>
                                    <div class="card-body py-4">
                                        <form class="form-horizontal form-label-left" method="post">
                                            <div class="row">

                                                <div class="col-md-2">
                                                    <label for="First name"><h5>First name:</h5></label><br/>
                                                    <input type="text" id="firstname" class="form-control" placeholder="First Name">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="Last name"><h5>Last name:</h5></label><br/>
                                                    <input type="text" id="lastname" class="form-control" placeholder="Last Name">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="Last name"><h5>Country:</h5></label><br/>
                                                    <input type="text" id="country" class="form-control" placeholder="Country">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="PhoneNo"><h5>Phone Number:</h5></label><br/>
                                                    <input type="text" id="phoneno" class="form-control" placeholder="Phone Number">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="E-mail"><h5>E-mail:</h5></label><br/>
                                                    <input type="text" id="email" class="form-control" placeholder="E-Mail"></div>
                                                <div class="col-md-2">
                                                    <br />
                                                    <button type="button" class="btn btn-primary searching ">Search</button>
                                                    <a href="{{ url('/') }}/leads-list"> <button type="button" class="btn btn-success">Reset</button></a>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer mt-10">
                                            <div class="table-responsive">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                                    <thead>
                                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                            <th></th>
                                                            <th>Actions</th>
                                                            <th>Comment</th>
                                                            <th>Convert</th>
                                                            <th>Send email</th>
                                                            <th>Details</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Date Of Birth</th>
                                                            <th>Email</th>
                                                            <th>Mobile Number</th>
                                                            <th>Phone Number</th>
                                                            <th>Fax Number</th>
                                                            <th>City</th>
                                                            <th>Country</th>
                                                            <th>Created By</th>
                                                            <th>Assign To</th>
                                                            <th>Lead Status</th>
                                                            <th>Last Contacted</th>
                                                            <th>Company Name</th>
                                                            <th>Website URL</th>
                                                            <th>Associated Company</th>
                                                            <th>Created At</th>
                                                            <th>Updated At</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-bold" id="render_string">
														@if(count($leads) > 0)
															@foreach($leads as $lead)
                                                                <tr>
                                                                    <td>
                                                                        <button class="btn btn-warning btn-icon btn-sm comments" id="comments-{{ $lead->id }}" data-lead_id = "{{ $lead->id }}"><i class="fa fa-plus"></i></button>
                                                                        <button class="btn btn-danger btn-icon btn-sm cancelbtn" id="hide_comments-{{ $lead->id }}" data-lead_id = "{{ $lead->id }}" style="display: none;"><i class="fa fa-minus"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        <a class="btn btn-info btn-sm me-1" href="{{ route('leads-edit', $lead->id) }}">Edit</a>
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-success btn-sm me-1 commentAdd"  data-lead_id="{{ $lead->id }}">Comment</button>
                                                                    </td>
                                                                    <td>
                                                                        <a class="btn btn-danger btn-sm me-1 convert" data-lead_id="{{ $lead->id }}">Convert</a>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-primary btn-sm send-email" data-toggle="modal" data-target="#exampleModal" data-lead_id="{{ $lead->id }}" data-email="{{ $lead->email }}">Send email </button>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-success btn-sm " ><a class="text-white" href="{{url('/leads-report-user-detail')}}/{{ $lead->id }}">Details</a></button>
                                                                    </td>
                                                                    <td>{!! $lead->firstname !!}</td>
                                                                    <td>{!! $lead->lastname !!}</td>
                                                                    <td>{!! $lead->date_of_birth !!}</td>
                                                                    <td>{!! $lead->email !!}</td>
                                                                    <td>{!! $lead->mobile_number !!}
                                                                    @if(!empty($lead->mobile_number))
                                                                    <button class="mobile border-0" data-mobile="{!! $lead->mobile_number !!}"><i class="far fa-copy"  data-toggle="tooltip" data-placement="top" title="copy"></i> </button>
                                                                    @endif

                                                                   </td>
                                                                    <td>{!! $lead->phone_number !!}</td>
                                                                    <td>{!! $lead->fax_number !!}</td>
                                                                    <td>{!! $lead->city !!}</td>
                                                                    <td>{!! $lead->country !!}</td>
                                                                    <td>{{  optional($lead->createdbyuser)->firstname.' '.optional($lead->createdbyuser)->lastname  }}</td>
                                                                    <td>{{ optional($lead->assigntouser)->firstname.' '.optional($lead->assigntouser)->lastname }}</td>
                                                                    <td>{!! $lead->lead_status !!}</td>
                                                                    <td>{!! $lead->last_contacted !!}</td>
                                                                    <td>{!! $lead->company_name !!}</td>
                                                                    <td>{!! $lead->website_url !!}</td>
                                                                    <td>{!! $lead->associated_company !!}</td>
                                                                    <td>{!! $lead->created_at !!}</td>
                                                                    <td>{!! $lead->updated_at !!}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
															<tr><td colspan="100%" align="center">No Record Found</td></tr>
                                                        @endif
                                                </table>
                                                {!! $leads->appends(\Request::except('page'))->render() !!}
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
                    {{-- <h5 class="float-right" style="float:right"> --}}
                      <select name="template" class="border-0" id="template" style="float:right;margin-top:12px">
                        <option>template</option>
                      </select>
                    {{-- </h5> --}}
                    <textarea class="form-control" id="default-editor" required></textarea>
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
	<script src="{{asset('assets/admin/js/comment.js')}}"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>
<!--end::Body-->
</html>
