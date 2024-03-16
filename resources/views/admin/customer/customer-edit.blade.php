<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="{{ config('app.website') }}" />
    <meta name="keywords" content="{{ config('app.website') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    {{-- <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" /> --}}
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

                            <form id="kt_account_profile_details_form"  class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ url('update-customer-profile') }}" enctype='multipart/form-data'
                                            method="post">
                                            @csrf
                                    <div class="card">
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bolder fs-3 mb-1">Company Info</span>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-body py-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label fs-6 fw-bolder text-black-700 mb-3">Personal Detail</label>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">First Name</label>
                                                    <div class="col-lg-8">
                                                        <div class="row">
                                                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="firstname"
                                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                                    placeholder="First name"
                                                                    value="{{ $customer_detail->user->firstname }}">
                                                                <span class="text-danger">
                                                                    @error('firstname')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                                <div
                                                                    class="fv-plugins-message-container invalid-feedback">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Last Name</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Phone number must be active"
                                                            aria-label="Phone number must be active"></i>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="text" name="lastname"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Last name"
                                                            value="{{ $customer_detail->user->lastname }}">
                                                        <span class="text-danger">
                                                            @error('lastname')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div
                                                            class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Contact Phone</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Phone number must be active"
                                                            aria-label="Phone number must be active"></i>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="tel" name="mobile"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Phone number"
                                                            value="{{ $customer_detail->user->mobile }}">
                                                        <span class="text-danger">
                                                            @error('mobile')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div
                                                            class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-6">
                                                    <label   class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input disabled type="email" name="email"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Company Email"
                                                            value="{{ $customer_detail->user->email }}">
                                                        <span class="text-danger">
                                                            @error('email')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div
                                                            class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label   class="col-lg-4 col-form-label required fw-bold fs-6">Additional Email</label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="email" name="additionalemail" value="{{ $customer_detail->addemail }}"  class="form-control form-control-lg form-control-solid"  placeholder="add Email" value="">
                                                        <div  class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                        <span class="text-danger">
                                                            @error('additionalemail')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row mb-6">
                                                    <label
                                                        class="col-lg-4 col-form-label required fw-bold fs-6">PassPort No</label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input  type="text" name="passportno"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Passport No"
                                                            value="{{$customer_detail->passport_id}}">
                                                        <span class="text-danger">
                                                            @error('passportno')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                            @if(!empty($customer_detail->passport_id))
                                                                <p class="text-primary">Passport Number Verified {{$customer_detail->passport_id}}</p>
                                                            @else
                                                                <p class="text-danger">File Not Uploaded</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-6">
                                                    <label
                                                        class="col-lg-4 col-form-label required fw-bold fs-6">PassPort </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input  type="file" name="possportfile"
                                                            class="form-control form-control-lg form-control-solid"
                                                            value="">
                                                        <span class="text-danger">
                                                            @error('possportfile')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div
                                                            class="fv-plugins-message-container invalid-feedback">
                                                            @if(!empty($customer_detail->passport_file))
                                                                <p class="text-primary">File Uploaded {{$customer_detail->passport_file}}</p>
                                                            @else
                                                                <p class="text-danger">File Not Uploaded</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fs-6 fw-bolder text-black-700 mb-3">Company Detail</label>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Company Name</label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="text" name="companyname"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Company name"
                                                            value="{{ $customer_detail->user->companyname }}">
                                                        <span class="text-danger">
                                                            @error('companyname')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div
                                                            class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Website</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="text" name="website" class="form-control form-control-lg form-control-solid"
                                                            placeholder="website" value="{{ $customer_detail->website }}">
                                                        <span class="text-danger">
                                                            @error('website')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Address</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="text" name="address" class="form-control form-control-lg form-control-solid"
                                                            placeholder="Address" value="{{ $customer_detail->address }}">
                                                        <span class="text-danger">
                                                            @error('address')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Company No</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="text" name="companyno" class="form-control form-control-lg form-control-solid"
                                                            placeholder="Company No" value="{{ $customer_detail->com_reg_no }}">
                                                        <span class="text-danger">
                                                            @error('com_reg_no')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Compnay Document</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="file" name="compnydoc" class="form-control form-control-lg form-control-solid"
                                                            placeholder="Company Document" value="{{ $customer_detail->com_reg_doc }}">
                                                        <span class="text-danger">
                                                            @error('compnydoc')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        @if(!empty($customer_detail->com_reg_doc))
                                                                <p class="text-primary">File Uploaded {{$customer_detail->com_reg_doc}}</p>
                                                            @else
                                                                <p class="text-danger">File Not Uploaded</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Country</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Country of origination"
                                                            aria-label="Country of origination"></i>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <select name="country" class="form-control countrySel"
                                                            id="countySel" size="1">
                                                            <option value="{{ $customer_detail->country }}" selected="selected">{{ $customer_detail->country }}</option>
                                                        </select>
                                                        <div
                                                            class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">State</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Country of origination"
                                                            aria-label="Country of origination"></i>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <select name="state" class="form-control stateSel"
                                                            id="stateSel" size="1">
                                                            <option value="{{ $customer_detail->state }}" selected="selected">{{ $customer_detail->state }}</option>
                                                        </select>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">City</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Country of origination"
                                                            aria-label="Country of origination"></i>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <select name="city" class="form-control districtSel"
                                                            id="districtSel" size="1">
                                                            <option value="{{ $customer_detail->city }}" selected="selected">{{ $customer_detail->city }}</option>
                                                        </select>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                <div class="card mt-5">
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bolder fs-3 mb-1">Setting</span>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-body py-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Discount</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="text" name="discount" class="form-control form-control-lg form-control-solid"
                                                            placeholder="Discount" value="{{ $customer_detail->discount }}">
                                                        <span class="text-danger">
                                                            @error('discount')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Lab discount</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <input type="text" name="lab_discount"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="lab discount"
                                                            value="{{ $customer_detail->lab_discount }}">
                                                        <span class="text-danger">
                                                            @error('customer_type')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Customer Type</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <select class="form-select companycount selectpicker" id="customer_type" name="customer_type" autocomplete="off" required>
                                                            <option value="">Select Customer Type</option>
                                                            <option value="1" {{ ($customer_detail->customer_type == 1) ? 'selected=selected' : ''; }}>Customer</option>
															<option value="2" {{ ($customer_detail->customer_type == 2) ? 'selected=selected' : ''; }}>Gold Customer</option>
															<option value="3" {{ ($customer_detail->customer_type == 3) ? 'selected=selected' : ''; }}>Silver Customer</option>
															<option value="4" {{ ($customer_detail->customer_type == 4) ? 'selected=selected' : ''; }}>Pending</option>
                                                        </select>
                                                        <span class="text-danger">
                                                            @error('customer_type')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                        <span class="required">Sales Manager</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                        <select class="form-select companycount selectpicker" id="customer_type" name="sales_manager_id" autocomplete="off" required>
                                                            <option value="">Select Seles Manager</option>
                                                            @foreach($sales as $value)
                                                            <option value="{{$value->id}}" {{ ($customer_detail->user->added_by == $value->id) ? 'selected' :''}} >{{$value->firstname}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger">
                                                            @error('customer_type')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                              </div>
                                              <div class="row mb-6 mt-4" >
                                                        <label class="p-3 form-check form-switch form-check-custom form-check-solid">
                                                            <span class="required me-6">Supplier name/ price</span>
                                                            <input class="me-2 form-check-input" type="checkbox" name="showsupplier" value="1" {{ ($customer_detail->showsupplier == 1)  ? "checked" : ''; }}>
                                                </label>
                                            </div>
                                         </div>
                                      </div>
                                    </div>
                                </div>

                                <div class="card mt-5" >
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title">
                                            <h3 class="card-title align-items-start flex-column">
                                                    <span class="card-label fw-bolder fs-3 mb-1">API-CFM Info</span>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="card-body py-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                    {{-- <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                            <span class="required">consignee buyer name</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                            <textarea class="form-control form-control-lg form-control-solid" name="consignee_buyer_name" id="consignee_buyer_name" cols="20" rows="2">{{$customer_detail->consignee_buyer_name}}</textarea>
                                                        <span class="text-danger">
                                                                @error('consignee_buyer_name')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                        <div class="fv-plugins-message-container invalid-feedback">
                                                        </div>
                                                    </div>
                                                    </div> --}}
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                            <span class="required">API KEY</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                            <input type="text" data-bs-target="license" name="api_key" id="kt_clipboard_4" class="form-control form-control-lg form-control-solid" placeholder="api key" value="{{ $customer_detail->api_key }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                            <span class="required">API ON OFF</span>
                                                    </label>
                                                        <div class="col-lg-4 fv-row fv-plugins-icon-container">
                                                            <button type="button" id="regenerate" class="btn btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                                <span class="svg-icon svg-icon-2">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"/>
                                                                    <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"/>
                                                                    </svg>
                                                        </span>
                                                            </button>
                                                            <button type="button" data-clipboard-target="#kt_clipboard_4" class="btn btn-active-color-primary btn-icon btn-sm btn-outline-light">
                                                                <span class="svg-icon svg-icon-2">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path opacity="0.5" d="M18 2H9C7.34315 2 6 3.34315 6 5H8C8 4.44772 8.44772 4 9 4H18C18.5523 4 19 4.44772 19 5V16C19 16.5523 18.5523 17 18 17V19C19.6569 19 21 17.6569 21 16V5C21 3.34315 19.6569 2 18 2Z" fill="currentColor"></path>
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7857 7.125H6.21429C5.62255 7.125 5.14286 7.6007 5.14286 8.1875V18.8125C5.14286 19.3993 5.62255 19.875 6.21429 19.875H14.7857C15.3774 19.875 15.8571 19.3993 15.8571 18.8125V8.1875C15.8571 7.6007 15.3774 7.125 14.7857 7.125ZM6.21429 5C4.43908 5 3 6.42709 3 8.1875V18.8125C3 20.5729 4.43909 22 6.21429 22H14.7857C16.5609 22 18 20.5729 18 18.8125V8.1875C18 6.42709 16.5609 5 14.7857 5H6.21429Z" fill="currentColor"></path>
                                                                    </svg>
                                                        </span>
                                                            </button>
                                            </div>
                                                        <div class="col-lg-4 fv-row fv-plugins-icon-container">
                                                            <label class="p-3 form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="checkbox" name="api_enable" value="1" {{ ($customer_detail->api_enable == 1)  ? "checked" : ''; }}>
                                                                <span class="form-check-label">API On Off</span>
                                                    </label>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                            <span class="required">CFM KEY</span>
                                                    </label>
                                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                            <input type="text" class="form-control form-control-lg form-control-solid" readonly placeholder="api key" value="{{ optional($customermode)->token_key }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                                    <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
                                </div>
                                        <input type="hidden" name='id' id='customerid' value="{{ $customer_detail->user->id }}">
                            </div>
                                </form>

                                <div class="card mt-2">
                                    <div class="card-header border-0 pt-6 justify-content-end ">
                                        <div class="card-title">
                                            <button type="button" class="btn btn-success btn-active-light-primary me-2 float-right  add-shipping-destination " width="100px">Add Shipping Destination</button>
                            </div>
                                    </div>
                                    <div class="card-body py-4">
                            <table class="table">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th>Company name</th>
                                        <th>Address</th>
                                        <th>Zip code</th>
                                        <th>Phone number</th>
                                        <th>Company Tax</th>
                                        <th>Port of Discharge</th>
                                        <th>consignee buyer name</th>
                                        <th>Gst number</th>
                                        <th>Place of supply</th>
                                        <th>Set As default</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                            <tbody id="tabledata"></tbody>
                             </table>
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

    <div class="modal fade" id="header-modal" tabindex="-1" role="dialog" aria-labelledby="header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" >
            <div class="modal-content" >
                <div class="card mt-5" >
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="header-modalLabel">Shipping Details</h5>
                        <div class='btn btn-icon btn-sm btn-active-light-primary ms-2' data-bs-dismiss='modal' aria-label='Close'><i class='fa fa-times'></i></div>
                    </div>
                    <form id="form">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required"> Company name</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <input class="form-control form-control-lg form-control-solid" name="company_name" id ="company_name" required>
                                        <input class="form-control form-control-lg form-control-solid"  id ="customer_name"  value="{{$customer_detail->user->firstname}}" hidden>
                                        <input class="form-control form-control-lg form-control-solid"  id ="customer_id" value="{{$customer_detail->user->id}}"  hidden>
                                        <input class="form-control form-control-lg form-control-solid"  id ="id" name="id"  hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">Country</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <select class="form-control form-control-lg form-control-solid countrySel" name=""  size="1" id="country" >
                                            <option value="" selected="selected">Select Country </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">Address</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <textarea class="form-control form-control-lg form-control-solid" name="address" id="address" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">State</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <select class="form-control form-control-lg form-control-solid stateSel" name="" id="state" >
                                            <option value="" selected="selected">Select State </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                <span class="required">Zip code</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <input type="number" class="form-control form-control-lg form-control-solid" name="pincode" id="pincode" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                        <span class="required">City</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <select class="form-control form-control-lg form-control-solid districtSel" name="" id="district" >
                                            <option value="" selected="selected">Select City </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                <span class="required">Phone number</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                <input type="number" class="form-control form-control-lg form-control-solid" name="phone_number" id="phone_number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                <span class="required">Company Tax</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                <input type="text"class="form-control form-control-lg form-control-solid" name="company_tax" id="company_tax" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                <span class="required">Port of Discharge</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                <input class="form-control form-control-lg form-control-solid" name="p-o-d" id="port_of_discharge">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                <span class="required">Cnsignee Buyer name</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                <input class="form-control form-control-lg form-control-solid" name="attend_name"  id="attend_name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                <span class="required">Gst number</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                <input type="text" class="form-control form-control-lg form-control-solid" name="gst_number" id="gst_number" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">
                                                <span class="required">Place of supply</span>
                                    </label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                                <input class="form-control form-control-lg form-control-solid" name="supply_place"  id="supply_place">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss='modal' aria-label='Close'>Close</button>
                                <button type="button" class="btn btn-primary" id="save">Save</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Shopping details  --}}
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)"
                    fill="black" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="black" />
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
    <script src="{{ asset('assets/js/countries.js') }}" type="text/javascript"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Page Custom Javascript(used by this page)-->
    {{-- <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script> --}}
    <script src="{{ asset('assets/admin/js/custom/intro.js') }}"></script>
    <script src="{{asset('assets/js/countryclass.js')}}" type="text/javascript"></script>
    <!--end::Page Custom Javascript-->

    <script type="text/javascript">
        $(document).ready(function() {
            var xhr;

            function request_call(url, mydata) {
            var base_url = window.location.origin;
                if (xhr && xhr.readyState != 4) {
                    xhr.abort();
                }
                xhr = $.ajax({
                    url:base_url+"/"+url,
                    type: 'post',
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: mydata,
                });
            }

        function fetch_record(){
            var customer_id = $('#customerid').val();
            request_call('show-shipping-details', "customerId=" + customer_id);
            xhr.done(function(response) {
                $('#tabledata').html(response.result);
            });
        }
        fetch_record();

           $('.add-shipping-destination').on('click', function(){
                $('#header-modal').modal('show');
                $("#form").trigger("reset");
           });

            $('#save').on('click', function(){
                var company_name=$('#company_name').val();
                var customer_name=$('#customer_name').val();
                var customer_id=$('#customer_id').val();
                var id =$('#id').val();
                var country=$('#country').val();
                var state=$('#state').val();
                var district=$('#district').val();
                var address=$('#address').val();
                var pincode =$('#pincode').val();
                var gst_number =$('#gst_number').val();
                var supply_place =$('#supply_place').val();
                var phonenumber =$('#phone_number').val();
                var company_tax =$('#company_tax').val();
                var port_of_discharge =$('#port_of_discharge').val();
                var attend_name =$('#attend_name').val();
                if (!company_name) {
                Swal.fire({title: "Success", icon: "warning", text: "Company field required!"});
                }else if (!address) {
                Swal.fire({title: "Success", icon: "warning", text: "Address field required!"});
                }else if(!pincode){
                Swal.fire({title: "Success", icon: "warning", text: "Zip code field required!"});
                }else if(!phonenumber){
                Swal.fire({title: "Success", icon: "warning", text: "Phone number field required!"});
                }else if(!port_of_discharge){
                Swal.fire({title: "Success", icon: "warning", text: "Port of discharge field required!"});
                }else if(!attend_name){
                    Swal.fire({title: "Success", icon: "warning", text: "Attend name field required!"});
                }else{
            request_call('add-edit-shipping-details',"comany_name=" + company_name + "&id=" + id + "&customer_name=" + customer_name + " &customer_id=" +customer_id + "&country=" + country + "&state=" + state + "&district=" + district + "&address=" + address + "&pincode=" + pincode + "&gst_number="+ gst_number +"&supply_place=" +supply_place + "&phonenumber=" + phonenumber + "&company_tax="+ company_tax + "&port_of_discharge=" + port_of_discharge + "&attend_name=" + attend_name);
                    xhr.done(function(response) {
                        $('#header-modal').modal('hide');
                        $("#form").trigger("reset");
                        if(response.message){
                        Swal.fire('success','Shipping Details Updated Successfully!','success');
                        } else {
                            Swal.fire('warning','Something Error!','warning');
                        }
                        fetch_record();
                    });
                }
            });

            $('#tabledata').delegate('.shipping_details_edit', 'click', function() {
                $("#form").trigger("reset");
                $('#header-modal').modal('show');
                var id=$(this).attr('data-val');
                request_call('edit-shipping-details',"id=" + id);
                    xhr.done(function(response) {
                        $('#id').val(response.add_id);
                        $('#company_name').val(response.company_name);
                        $('#customer_name').val(response.customer_name);
                        $('#customer_id').val(response.customer_id);
                        $("#country").val(response.country).change();
                        $("#state").val(response.state).change();
                        $("#district").val(response.city).change();
                        $('#address').val(response.address);
                        $('#pincode').val(response.pincode);
                        $('#gst_number').val(response.gst_no);
                        $('#supply_place').val(response.place_of_supply);
                        $('#phone_number').val(response.phone_no);
                        $('#company_tax').val(response.company_tax);
                        $('#port_of_discharge').val(response.port_of_discharge);
                        $('#attend_name').val(response.attend_name);
                    });
            });

            $('#tabledata').delegate('.shipping_details_delete', 'click', function() {
                var id = $(this).attr('data-val');
                var by_default = $(this).attr('data-default');
                console.log(by_default);
                if(by_default == 1){
                    Swal.fire('warning','You Can Not Delete Record Which Is By Default!','warning');
                }
                else{
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Are you sure you want to Delete?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Delete!"
                    }).then(function(result) {
                        if (result.value) {
                        request_call('delete-shipping-details',"id=" + id);
                        xhr.done(function(response) {
                            if(response){
                                    Swal.fire('success','Deleted Successfully!','success');
                            } else {
                                Swal.fire('warning','Something Error!','warning');
                            }
                            });
                            setTimeout(function(){
                                fetch_record();
                            }, 100);
                        }
                    })
                }
            });

            $('#tabledata').delegate('#flexSwitchDefault','change', '.turnonoff', function() {
                var id = $(this).data('val');
                var customerId = $(this).data('customer-id');
                request_call('shipping-address-default',"id=" + id+"&customerId=" + customerId);
                xhr.done(function(response) {
                    console.log('done');
                    if(response){
                            Swal.fire('success','Set default  Successfully!','success');
                    } else {
                        Swal.fire('warning','Something Error!','warning');
                    }
                    fetch_record();
                   });
                });
            });

        localStorage.setItem("ak_search", "");
        localStorage.setItem("lg_search", "");

        // const target = document.getElementById('kt_clipboard_4');
        // const button = target.nextElementSibling;

        // // // Init clipboard -- for more info, please read the offical documentation: https://clipboardjs.com/
        // clipboard = new ClipboardJS(button, {
        //     target: target,
        //     text: function () {
        //         return target.value;
        //     }
        // });

        function randomString() {
            //define a variable consisting alphabets in small and capital letter
            var characters = "ABCDEFGHJKLMNOPQRSTUVWXTZabcdefghikmnopqrstuvwxyz";

            //specify the length for the new string
            var lenString = 12;
            var randomstring = 'TDP';

            //loop to select a new character in each iteration
            for (var i=0; i<lenString; i++) {
                var rnum = Math.floor(Math.random() * characters.length);
                randomstring +=  Math.floor(Math.random() * characters.length);
                randomstring += characters.substring(rnum, rnum+1);
            }

            //display the generated string
            document.getElementById("kt_clipboard_4").value = randomstring;
        }

        $('#regenerate').click(function() {
            randomString();
        });


        // $('#kt_table_users').DataTable({
        //     'processing': true,
        // });

        "use strict";
        var KTUsersList = (function() {
            var e,
                t,
                n,
                r,
                o = document.getElementById("kt_table_users"),
                c = () => {
                    o.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach((t) => {
                        t.addEventListener("click", function(t) {
                            t.preventDefault();
                            const n = t.target.closest("tr"),
                                r = n.querySelectorAll("td")[1].querySelectorAll("a")[1].innerText;
                            Swal.fire({
                                text: "Are you sure you want to delete " + r + "?",
                                icon: "warning",
                                showCancelButton: !0,
                                buttonsStyling: !1,
                                confirmButtonText: "Yes, delete!",
                                cancelButtonText: "No, cancel",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-danger",
                                    cancelButton: "btn fw-bold btn-active-light-primary"
                                },
                            }).then(function(t) {
                                t.value ?
                                    Swal.fire({
                                        text: "You have deleted " + r + "!.",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary"
                                        }
                                    })
                                    .then(function() {
                                        e.row($(n)).remove().draw();
                                    })
                                    .then(function() {
                                        a();
                                    }) :
                                    "cancel" === t.dismiss && Swal.fire({
                                        text: customerName + " was not deleted.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary"
                                        }
                                    });
                            });
                        });
                    });
                },
                l = () => {
                    const c = o.querySelectorAll('[type="checkbox"]');
                    (t = document.querySelector('[data-kt-user-table-toolbar="base"]')), (n = document
                        .querySelector('[data-kt-user-table-toolbar="selected"]')), (r = document.querySelector(
                        '[data-kt-user-table-select="selected_count"]'));
                    const s = document.querySelector('[data-kt-user-table-select="delete_selected"]');
                    c.forEach((e) => {
                            e.addEventListener("click", function() {
                                setTimeout(function() {
                                    a();
                                }, 50);
                            });
                        }),
                        s.addEventListener("click", function() {
                            Swal.fire({
                                text: "Are you sure you want to delete selected customers?",
                                icon: "warning",
                                showCancelButton: !0,
                                buttonsStyling: !1,
                                confirmButtonText: "Yes, delete!",
                                cancelButtonText: "No, cancel",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-danger",
                                    cancelButton: "btn fw-bold btn-active-light-primary"
                                },
                            }).then(function(t) {
                                t.value ?
                                    Swal.fire({
                                        text: "You have deleted all selected customers!.",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary"
                                        }
                                    })
                                    .then(function() {
                                        c.forEach((t) => {
                                            t.checked &&
                                                e
                                                .row($(t.closest("tbody tr")))
                                                .remove()
                                                .draw();
                                        });
                                        o.querySelectorAll('[type="checkbox"]')[0].checked = !1;
                                    })
                                    .then(function() {
                                        a(), l();
                                    }) :
                                    "cancel" === t.dismiss &&
                                    Swal.fire({
                                        text: "Selected customers was not deleted.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary"
                                        }
                                    });
                            });
                        });
                };
            const a = () => {
                const e = o.querySelectorAll('tbody [type="checkbox"]');
                let c = !1,
                    l = 0;
                e.forEach((e) => {
                        e.checked && ((c = !0), l++);
                    }),
                    c ? ((r.innerHTML = l), t.classList.add("d-none"), n.classList.remove("d-none")) : (t
                        .classList.remove("d-none"), n.classList.add("d-none"));
            };
            return {
                init: function() {
                    o &&
                        (o.querySelectorAll("tbody tr").forEach((e) => {
                                const t = e.querySelectorAll("td"),
                                    n = t[3].innerText.toLowerCase();
                                let r = 0,
                                    o = "minutes";
                                n.includes("yesterday") ?
                                    ((r = 1), (o = "days")) :
                                    n.includes("mins") ?
                                    ((r = parseInt(n.replace(/\D/g, ""))), (o = "minutes")) :
                                    n.includes("hours") ?
                                    ((r = parseInt(n.replace(/\D/g, ""))), (o = "hours")) :
                                    n.includes("days") ?
                                    ((r = parseInt(n.replace(/\D/g, ""))), (o = "days")) :
                                    n.includes("weeks") && ((r = parseInt(n.replace(/\D/g, ""))), (o =
                                        "weeks"));
                                const c = moment().subtract(r, o).format();
                                t[3].setAttribute("data-order", c);
                                const l = moment(t[5].innerHTML, "DD MMM YYYY, LT").format();
                                t[5].setAttribute("data-order", l);
                            }),
                            (e = $(o).DataTable({
                                info: !1,
                                order: [],
                                pageLength: 10,
                                lengthChange: !1,
                                columnDefs: [{
                                        orderable: !1,
                                        targets: 0
                                    },
                                    {
                                        orderable: !1,
                                        targets: 5
                                    },
                                ],
                            })).on("draw", function() {
                                l(), c(), a();
                            }),
                            l(),
                            document.querySelector('[data-kt-user-table-filter="search"]').addEventListener(
                                "keyup",
                                function(t) {
                                    e.search(t.target.value).draw();
                                }),
                            document.querySelector('[data-kt-user-table-filter="reset"]').addEventListener(
                                "click",
                                function() {
                                    document
                                        .querySelector('[data-kt-user-table-filter="form"]')
                                        .querySelectorAll("select")
                                        .forEach((e) => {
                                            $(e).val("").trigger("change");
                                        }),
                                        e.search("").draw();
                                }),
                            c(),
                            (() => {
                                const t = document.querySelector('[data-kt-user-table-filter="form"]'),
                                    n = t.querySelector('[data-kt-user-table-filter="filter"]'),
                                    r = t.querySelectorAll("select");
                                n.addEventListener("click", function() {
                                    var t = "";
                                    r.forEach((e, n) => {
                                            e.value && "" !== e.value && (0 !== n && (t += " "),
                                                (t += e.value));
                                        }),
                                        e.search(t).draw();
                                });
                            })());
                },
            };
        })();
        KTUtil.onDOMContentLoaded(function() {
            KTUsersList.init();
        });
    </script>
</body>
<!--end::Body-->

</html>
