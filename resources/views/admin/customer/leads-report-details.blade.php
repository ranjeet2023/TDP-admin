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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    @include('admin/css')
    <style>
        .modal-content{
            width: 142% !important;
        }
        .modal{
            width:85%;
        }
    </style>
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
								<div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bolder fs-3 mb-1">Leads Report Details</span>
											</h3>
										</div>
                                        <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm" style="float:right;height:30px"><i class="fa fa-arrow-left"></i></a>
									</div>
									<div class="card-body py-4">
										<div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
											<div class="table-responsive">
												<table class="table align-middle table-row-dashed fs-6 gy-5" id="myTable" >
													<thead>
														<tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                            <th>ID</th>
                                                            <th>FirstName</th>
                                                            <th>LastName</th>
                                                            <th>Mobile number</th>
                                                            <th>Country</th>
                                                            <th>Company</th>
                                                            <th>Comment</th>
                                                            <th>Details</th>
                                                            <th>Date</th>
														</tr>
													</thead>
													<tbody class="text-gray-600 fw-bold">
                                                        @php
                                                            $leads=1;
                                                        @endphp
                                                        @foreach ($data as $details )
                                                        <tr>
                                                            <td> {{ $leads }}</td>
                                                            <td> @if ($details->leads) {{ $details->leads->firstname }} @endif</td>
                                                            <td> @if ($details->leads) {{ $details->leads->lastname }} @endif</td>
                                                            <td> @if ($details->leads) {{ $details->leads->mobile_number }} @endif</td>
                                                            <td> @if ($details->leads) {{ $details->leads->country }} @endif</td>
                                                            <td> @if ($details->leads) {{ $details->leads->company_name }} @endif</td>
                                                            <td> {{ $details->comment }}</td>
                                                            <td><a href="{{url('leads-report-user-detail')}}/{{ $details->leads_id }}"><button type="button" class="btn btn-secondary btn-sm details" data-toggle="modal" data-target=".bd-example-modal-lg" >details</button></td>
                                                            <td>
                                                            @php
                                                            $date = date('Y-m-d H:i:s');
                                                            @endphp
                                                            {{  date('Y-m-d H:i:s A', strtotime($details->follow_up_date)) }}@if($details->follow_up_date>$date) <span class="badge badge-warning">P</span>@endif</td>
                                                        </tr>
                                                        @php
                                                        $leads++;
                                                        @endphp
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
                @include('admin/footer')
            </div>
        </div>
    </div>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <Script>
        $(document).ready( function () {
            $('#myTable').DataTable();
    });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
