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
                                <h3 class="card-title align-items-start flex-column">Match Pair</h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon" id="kt_drawer_matchpair_toggle"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 table-responsive mb-5" style="margin-top: 10px;">
                                        <table class="table table-striped dataTable" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th>Supplier Name</th>
                                                    <th>Type</th>
                                                    <th>Shape</th>
                                                    <th>Certificate Number</th>
                                                    <th>Carat</th>
                                                    <th>Color</th>
                                                    <th>Clarity</th>
                                                    <th>Lab</th>
                                                    <th>Polish</th>
                                                    <th>Symmetry</th>
                                                    <th>Fluorescence</th>
                                                    <th>Measurements</th>
                                                </tr>
                                            </thead>
                                            <tbody id="render_string">
                                                @foreach($diamonds as $key=>$result)
                                                    @if($loop->iteration % 2 == 0)
                                                        @php $class='style="border-bottom:2px solid red;"'; @endphp
                                                    @else
                                                        {!! $class='' !!}
                                                    @endif
                                                    <tr {!! $class !!}>
                                                        <td>{{ $result->supplier_name }}</td>
                                                        <td>{{ ($result->shape != 'L') ? 'Natural' : 'Lab Grown' }}</td>
                                                        <td>{{ $result->shape }}</td>
                                                        <td>{{ $result->certificate_no }}</td>
                                                        <td>{{ $result->carat }}</td>
                                                        <td>{{ $result->color }}</td>
                                                        <td>{{ $result->clarity }}</td>
                                                        <td>{{ $result->lab }}</td>
                                                        <td>{{ $result->polish }}</td>
                                                        <td>{{ $result->symmetry }}</td>
                                                        <td>{{ $result->fluorescence }}</td>
                                                        <td>{{ $result->length.' x '.$result->width.' x '.$result->depth }}</td>
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
                <div id="kt_drawer_matchpair" class="bg-body drawer drawer-end" data-kt-drawer="true" data-kt-drawer-name="matchpair" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '80%'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_matchpair_toggle" data-kt-drawer-close="#kt_drawer_matchpair_close">
                    <div class="card w-100 rounded-0 border-0 p-0" id="kt_drawer_matchpair_messenger">
                        <div class="card-header pe-5" id="kt_drawer_matchpair_header">
                            <div class="card-title">
                                <div class="d-flex justify-content-center flex-column me-3">
                                    <a class="fs-1 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">Match Pair Filter</a>
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_matchpair_close">
                                    <span class="svg-icon svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-6" id="kt_drawer_matchpair_messenger_body">
                            <div class="row mb-2">
                                <div class="col-md-1 col-sm-12 col-xs-12 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Diamond Type:</span>
                                </div>
                                <div class="col-md-11 dia_type">
                                    <a class="btn btn-sm diamond_type border border-gray-300 m-sm-2 {{ ($type == 'natural') ? 'btn-primary' : '' ; }}" title="diamond_type" data-val="natural" >Natural</a>
                                    <a class="btn btn-sm diamond_type border border-gray-300 m-sm-2 {{ ($type == 'lab_grown') ? 'btn-primary' : '' ; }}" title="diamond_type" data-val="lab_grown" >Lab Grown</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 col-sm-12 col-xs-12 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Shape:</span>
                                </div>
                                <div class="col-md-11">
                                    <a id="All" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 allshape" data-val="All" title="shape" style="border: 1px solid #dcdcdc; margin: 0px;font-size: 12px;  padding-left:10px; padding-right: 10px;">
                                        <span style="font-size:18px;width: 25px;">All </span><br/><span class="fs-9">Shapes</span>
                                    </a>
                                    <a id="Round" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Round" data-val="Round">
                                        <img class="image_off" src="{{asset('assets/images/shape/round.png')}}" width="25" alt="Round">
                                        <br>Round
                                    </a>
                                    <a id="Princess" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Princess" data-val="Princess">
                                        <img class="image_off" src="{{asset('assets/images/shape/princess.png')}}" width="25" alt="Princess">
                                        <br>Princess
                                    </a>
                                    <a id="Asscher" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Asscher" data-val="Asscher">
                                        <img class="image_off" src="{{asset('assets/images/shape/asscher.png')}}" width="25" alt="Asscher">
                                        <br>Asscher
                                    </a>
                                    <a id="Cushion" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Cushion" data-val="Cushion">
                                        <img class="image_off" src="{{asset('assets/images/shape/cushion.png')}}" width="25" alt="Cushion">
                                        <br>Cushion
                                    </a>
                                    <a id="Emerald" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Emerald" data-val="Emerald">
                                        <img class="image_off" src="{{asset('assets/images/shape/emerald.png')}}" width="25" alt="Emerald">
                                        <br>Emerald
                                    </a>
                                    <a id="Heart" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Heart" data-val="Heart">
                                        <img class="image_off" src="{{asset('assets/images/shape/heart.png')}}" width="25" alt="Heart">
                                        <br>Heart
                                    </a>
                                    <a id="Marquise" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Marquise" data-val="Marquise">
                                        <img class="image_off" src="{{asset('assets/images/shape/marquise.png')}}" width="25" alt="Marquise">
                                        <br>Marquise
                                    </a>
                                    <a id="Oval" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Oval" data-val="Oval">
                                        <img class="image_off" src="{{asset('assets/images/shape/oval.png')}}" width="25" alt="Oval">
                                        <br>Oval
                                    </a>
                                    <a id="Pear" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Radiant" data-val="Pear">
                                        <img class="image_off" src="{{asset('assets/images/shape/pear.png')}}" width="25" alt="Pear">
                                        <br>Pear
                                    </a>
                                    <a id="Radiant" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Radiant" data-val="Radiant">
                                        <img class="image_off" src="{{asset('assets/images/shape/radiant.png')}}" width="25" alt="Radiant"><br>Radiant
                                    </a>
                                    <a id="SQUARE_RADIANT" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="shape" data-val="SQUARE_RADIANT">
                                        <img class="image_off" src="{{asset('assets/images/shape/lradiant.png')}}" width="25" alt="SQUARE Radiant"><br>SQ.Radiant
                                    </a>
                                    <a id="TRILLIANT" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Trilliant" data-val="TRILLIANT">
                                        <img class="image_off" src="{{asset('assets/images/shape/trilliant.png')}}" width="25" alt="Trilliant"><br>Trilliant
                                    </a>
                                    <a id="CUSHION_MODIFIED" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Cushion" data-val="CUSHION_MODIFIED">
                                        <img class="image_off" src="{{asset('assets/images/shape/cushion.png')}}" width="25" alt="Cushion Modify"><br>Cushion mod.
                                    </a>
                                    <a id="Triangle" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="Triangle" data-val="Triangle">
                                        <img class="image_off" src="{{asset('assets/images/shape/triangle.png')}}" width="25" alt="Triangle"><br>Triangle
                                    </a>
                                    <a id="OTHER" class="btn btn-sm togglebtn shape border border-gray-300 p-2 m-sm-2 fs-9" title="other" data-val="OTHER">
                                        <img class="image_off" src="{{asset('assets/images/shape/other.png')}}" width="25" alt="Other"><br>Other
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 col-sm-12 col-xs-12 d-sm-flex justify-content-center align-items-sm-center"><span>Carat</span></div>
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <input id="min_carat" class="form-control stone_count" name="min_carat" placeholder="From" type="text">
                                </div>
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <input id="max_carat" class="form-control stone_count" name="max_carat" placeholder="To" type="text">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Color :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="D" >D</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="E" >E</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="F" >F</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="G" >G</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="H" >H</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="I" >I</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="J" >J</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="K" >K</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="L" >L</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="M" >M</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="N" >N</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="OP" >OP</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="QR" >QR</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="ST" >ST</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="UV" >UV</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="WX" >WX</a>
                                    <a class="btn btn-sm togglebtn colordata border border-gray-300 m-sm-2" title="color" data-val="YZ" >YZ</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Clarity :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="FL">FL</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="IF">IF</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="VVS1">VVS1</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="VVS2">VVS2</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="VS1">VS1</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="VS2">VS2</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="SI1">SI1</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="SI2">SI2</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="I1">I1</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="I2">I2</a>
                                    <a class="btn togglebtn claritydata btn-sm border border-gray-300 m-sm-2" title="clarity" data-val="I3">I3</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Cut :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn btn-sm ID m-sm-2" style="border: 1px solid #dcdcdc;" title="cut" data-val="ID">Ideal</a>
                                    <a class="btn togglebtn btn-sm EX m-sm-2" style="border: 1px solid #dcdcdc;" title="cut" data-val="EX">Excellent</a>
                                    <a class="btn togglebtn btn-sm VG m-sm-2" style="border: 1px solid #dcdcdc;" title="cut" data-val="VG">Very Good</a>
                                    <a class="btn togglebtn btn-sm GD m-sm-2" style="border: 1px solid #dcdcdc;" title="cut" data-val="GD">Good</a>
                                    <a class="btn togglebtn btn-sm FR m-sm-2" style="border: 1px solid #dcdcdc;" title="cut" data-val="FR">Fair</a>
                                    <a class="btn togglebtn btn-sm PR m-sm-2" style="border: 1px solid #dcdcdc;" title="cut" data-val="PR">Poor</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Polish :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn btn-sm m-sm-2 EX" style="border: 1px solid #dcdcdc;" title="polish" data-val="EX">Excellent</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 VG" style="border: 1px solid #dcdcdc;" title="polish" data-val="VG">Very Good</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 GD" style="border: 1px solid #dcdcdc;" title="polish" data-val="GD">Good</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 FR" style="border: 1px solid #dcdcdc;" title="polish" data-val="FR">Fair</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 PR" style="border: 1px solid #dcdcdc;" title="polish" data-val="PR">Poor</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Symmetry :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn btn-sm m-sm-2 EX" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="EX">Excellent</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 VG" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="VG">Very Good</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 GD" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="GD">Good</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 FR" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="FR">Fair</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 PR" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="PR">Poor</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Fluorescence :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn m-sm-2 btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="NON">NONE</a>
                                    <a class="btn togglebtn m-sm-2 btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="FNT">FAINT</a>
                                    <a class="btn togglebtn m-sm-2 btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="MED">MEDIUM</a>
                                    <a class="btn togglebtn m-sm-2 btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="SLIGHT">SLIGHT</a>
                                    <a class="btn togglebtn m-sm-2 btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="STG">STRONG</a>
                                    <a class="btn togglebtn m-sm-2 btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="VST">VERY STRONG</a>
                                    <a class="btn togglebtn m-sm-2 btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="VSLT">VSLT</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Country :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn btn-sm m-sm-2 ng-binding" style="border: 1px solid #dcdcdc;" title="location" data-val="INDIA">INDIA</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 ng-binding" style="border: 1px solid #dcdcdc;" title="location" data-val="HONGKONG">HONG KONG</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 ng-binding" style="border: 1px solid #dcdcdc;" title="location" data-val="ISRAEL">ISRAEL</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 ng-binding" style="border: 1px solid #dcdcdc;" title="location" data-val="USA">USA</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 ng-binding" style="border: 1px solid #dcdcdc;" title="location" data-val="UAE">UAE</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 ng-binding" style="border: 1px solid #dcdcdc;" title="location" data-val="BELGIUM">BELGIUM</a>
                                    <a class="btn togglebtn btn-sm m-sm-2 ng-binding" style="border: 1px solid #dcdcdc;" title="location" data-val="OTHER">OTHER</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Lab :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn btn-sm ng-binding m-sm-2" style="border: 1px solid #dcdcdc;" title="lab" data-val="GIA">GIA</a>
                                    <a class="btn togglebtn btn-sm ng-binding m-sm-2" style="border: 1px solid #dcdcdc;" title="lab" data-val="IGI">IGI</a>
                                    <a class="btn togglebtn btn-sm ng-binding m-sm-2" style="border: 1px solid #dcdcdc;" title="lab" data-val="HRD">HRD</a>
                                    <a class="btn togglebtn btn-sm ng-binding m-sm-2" style="border: 1px solid #dcdcdc;" title="lab" data-val="AGS">AGS</a>
                                    <a class="btn togglebtn btn-sm ng-binding m-sm-2" style="border: 1px solid #dcdcdc;" title="lab" data-val="GCAL">GCAL</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Eye Clean :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn btn-sm eyesyes m-sm-2" style="border: 1px solid #dcdcdc;" title="eyeclean" data-val="YES">YES</a>
                                    <a class="btn togglebtn btn-sm eyesyes m-sm-2" style="border: 1px solid #dcdcdc;" title="eyeclean" data-val="NO">NO</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-1 d-sm-flex justify-content-center align-items-sm-center">
                                    <span> Treatment :</span>
                                </div>
                                <div class="col-md-11">
                                    <a class="btn togglebtn btn-sm c_type m-sm-2" style="border: 1px solid #dcdcdc;" title="c_type" data-val="CVD">CVD</a>
                                    <a class="btn togglebtn btn-sm c_type m-sm-2" style="border: 1px solid #dcdcdc;" title="c_type" data-val="HPHT">HPHT</a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12 py-6">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="footer fixed-bottom bottom-0  p-4" style="left:20%">
                            <button class="btn btn-success btn-sm" id="searchamtch">Search</button>
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

            var drawerEl = document.querySelector("#kt_drawer_matchpair");
            var drawer = KTDrawer.getInstance(drawerEl);
            drawer.show();

            $('.diamond_type').click(function(){
                var val = $(this).attr('data-val');
                var preselected = $(".dia_type .btn-primary").attr('data-val');
                if(preselected != val){
                    $(".diamond_type").removeClass("btn-primary");
                    $(this).addClass('btn-primary');
                }
            });

            $('.togglebtn').click(function (event) {
				event.preventDefault();
				event.stopPropagation();
				$(this).toggleClass("btn-primary", "");

				var act = $('.togglebtn').hasClass("btn-primary");

			});



            $('.allshape').click(function (event) {
				$("#allshape").toggleClass("btn-primary", "");
				if ($(this).hasClass("btn-primary"))
				{
					$(".shape").each(function () {
						$(this).addClass("btn-primary");
					});
				} else
				{
					$(".shape").each(function () {
						$(this).removeClass("btn-primary");
					});
				}
			});

            var page=0;

            $('#searchamtch').click(function (event) {
                $('#render_string').empty();
                page = 0;
                search_pair(page);
            })

            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= ($(document).height() - 1)) {
                    page++;
                    search_pair(page);
                }
            });

            function search_pair(page){
                event.preventDefault();
                var render_string = '' , record_html = '', type = '', shape = '',color = '',clarity = '',cut = '',polish = '',symmetry = '',fluorescence = '',location = '',lab = '',eyeclean = '',c_type = '';
                $("#kt_drawer_matchpair .btn-primary").each(function (index, value) {
                    if ($(this).hasClass('diamond_type'))
					{
						if ($.trim(type) != "")
							type += ",";

						if ($.trim($(this).attr("data-val")) != "")
							type += $(this).attr("data-val");
					} else if ($(this).hasClass('shape'))
					{
						if ($.trim(shape) != "")
							shape += ",";

						if ($.trim($(this).attr("data-val")) != "")
							shape += $(this).attr("data-val");
					} else if ($(this).attr('title') == "color")
					{
						if ($.trim(color) != "")
							color += ",";

						if ($.trim($(this).attr("data-val")) != "")
							color += $(this).attr("data-val");
					} else if ($(this).attr('title') == "clarity")
					{
						if ($.trim(clarity) != "")
							clarity += ",";

						if ($.trim($(this).attr("data-val")) != "")
							clarity += $(this).attr("data-val");
					} else if ($(this).attr('title') == "cut")
					{
						if ($.trim(cut) != "")
							cut += ",";

						if ($.trim($(this).attr("data-val")) != "")
							cut += $(this).attr("data-val");
					} else if ($(this).attr('title') == "polish")
					{
						if ($.trim(polish) != "")
							polish += ",";

						if ($.trim($(this).attr("data-val")) != "")
							polish += $(this).attr("data-val");
					} else if ($(this).attr('title') == "symmetry")
					{
						if ($.trim(symmetry) != "")
							symmetry += ",";

						if ($.trim($(this).attr("data-val")) != "")
							symmetry += $(this).attr("data-val");
					} else if ($(this).attr('title') == "fluorescence")
					{
						if ($.trim(fluorescence) != "")
							fluorescence += ",";

						if ($.trim($(this).attr("data-val")) != "")
						{
							fluorescence += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "location")
					{
						if ($.trim(location) != "")
							location += ",";
						if ($.trim($(this).attr("data-val")) != "")
						{
							location += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "lab")
					{
						if ($.trim(lab) != "")
							lab += ",";

						if ($.trim($(this).attr("data-val")) != "")
							lab += $(this).attr("data-val");
					} else if ($(this).attr('title') == "eyeclean")
					{
						if ($.trim(eyeclean) != "")
							eyeclean += ",";
						if ($.trim($(this).attr("data-val")) != "")
						{
							eyeclean += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "c_type")
					{
						if ($.trim(c_type) != "")
                            c_type += ",";
						if ($.trim($(this).attr("data-val")) != "")
						{
							c_type += $(this).attr("data-val");
						}
					}
                });

                render_string += set_range_val('min_carat',$('#min_carat').val(),'max_carat',$('#max_carat').val());
                render_string += set_single_val('type',type);
                render_string += set_single_val('shape',shape);
                render_string += set_single_val('color',color);
                render_string += set_single_val('clarity',clarity);
                render_string += set_single_val('cut',cut);
                render_string += set_single_val('polish',polish);
                render_string += set_single_val('symmetry',symmetry);
                render_string += set_single_val('fluorescence',fluorescence);
                render_string += set_single_val('location',location);
                render_string += set_single_val('lab',lab);
                render_string += set_single_val('eyeclean',eyeclean);
                render_string += set_single_val('c_type',c_type);

                request_call("{{ url('match-pair-search') }}",'page='+ page + render_string);
                xhr.done(function(mydata) {
                    $.each(mydata.diamonds, function(index, item) {
                        var style="";
                        if(index % 2 != 0){
                            style='style="border-bottom:2px solid red;"';
                        }
                        if(item != null){
                            record_html += '<tr '+ style +'>'+
                                                '<td>'+ item.supplier_name +'</td>'+
                                                '<td>'+ type +'</td>'+
                                                '<td>'+ item.shape +'</td>'+
                                                '<td>'+ item.certificate_no +'</td>'+
                                                '<td>'+ item.carat +'</td>'+
                                                '<td>'+ item.color +'</td>'+
                                                '<td>'+ item.clarity +'</td>'+
                                                '<td>'+ item.lab +'</td>'+
                                                '<td>'+ item.polish +'</td>'+
                                                '<td>'+ item.symmetry +'</td>'+
                                                '<td>'+ item.fluorescence +'</td>'+
                                                '<td>'+ item.length + ' x ' + item.width + ' x ' + item.depth +'</td>'+
                                            '</tr>';
                        }
                        else{
                            record_html += '<tr '+ style +'>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                                '<td>&nbsp</td>'+
                                            '</tr>';
                        }
                    });
                    drawer.hide();

                    if(page == 0){
                        $('#render_string').empty();
                    }

                    $('#render_string').append(record_html);
                });


            };
            function set_single_val(field_name,field_value){
                if($.trim(field_name) != '' && $.trim(field_value) != ''){
                    return ("&" + $.trim(field_name) + '=' + $.trim(field_value));
                }
                else{
                    return "";
                }
            }
            function set_range_val(min_name,min_value,max_name,max_value){
                if($.trim(min_name) != '' && $.trim(max_name) != '' && $.isNumeric($.trim(min_value)) && $.isNumeric($.trim(max_value))){
                    return ("&" + $.trim(min_name) + '=' + $.trim(min_value) + "&" + $.trim(max_name) + '=' + $.trim(max_value));
                }
                else{
                    return '';
                }
            }
        });
    </script>
</body>
</html>
