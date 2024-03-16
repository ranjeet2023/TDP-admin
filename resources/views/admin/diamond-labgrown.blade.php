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
						<div class="card card-custom gutter-b">
							<div class="card-body">
								<div class="row mb-2">
									<div class="col-md-1 col-sm-12 col-xs-12"><b>Company</b></div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										<select class="form-select company" multiple="multiple" id="companycount" data-control="select2" data-placeholder="Select Supplier" data-allow-clear="true">
										@foreach ($suppliers as $supplier)
											<option value="{{ $supplier->sup_id }}" id="company_{{ $supplier->sup_id }}">{{$supplier->users->companyname}}</option>
										@endforeach
										</select>
									</div>
									<div class="col-md-3 col-sm-12 col-xs-12"></div>
									@if (Auth::user()->user_type == 1)
										<div class="col-md-2 col-sm-12 col-xs-12">
											<button  class="btn btn-primary btn-sm delete_stock_all" ng-click="clicked()">Delete All Stock</button>
										</div>
									@endif
									<div class="col-md-3 col-sm-12 col-xs-12">
										<span class="badge badge-danger">No of stone : <span id="no_of_stone">0</span></span>
									</div>
                                    <div class="col-md-2 col-sm-12 col-xs-12" style="display:flex;justify-content: end;">
                                        <button type="button" class="btn btn-danger py-3 resetbtn" > RESET </button>
                                    </div>
								</div>
								<div class="row mb-2">
									<div class="col-md-12 col-sm-12 col-xs-12">
									<a id="All" class="btn btn-sm togglebtn shape border border-gray-300 p-2 allshape" data-val="All" title="shape" style="border: 1px solid #dcdcdc; margin: 0px;font-size: 12px;  padding-left:10px; padding-right: 10px;">
											<span style="font-size:18px;width: 25px;">All </span><br/>Shapes
										</a>
										<a id="Round" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Round" data-val="Round">
											<img class="image_off" src="{{asset('assets/images/shape/round.png')}}" width="25" alt="Round">
											<br>Round
										</a>
										<a id="Princess" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Princess" data-val="Princess">
											<img class="image_off" src="{{asset('assets/images/shape/princess.png')}}" width="25" alt="Princess">
											<br>Princess
										</a>
										<a id="Asscher" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Asscher" data-val="Asscher">
											<img class="image_off" src="{{asset('assets/images/shape/asscher.png')}}" width="25" alt="Asscher">
											<br>Asscher
										</a>
										<a id="Cushion" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Cushion" data-val="Cushion">
											<img class="image_off" src="{{asset('assets/images/shape/cushion.png')}}" width="25" alt="Cushion">
											<br>Cushion
										</a>
										<a id="Emerald" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Emerald" data-val="Emerald">
											<img class="image_off" src="{{asset('assets/images/shape/emerald.png')}}" width="25" alt="Emerald">
											<br>Emerald
										</a>
										<a id="Heart" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Heart" data-val="Heart">
											<img class="image_off" src="{{asset('assets/images/shape/heart.png')}}" width="25" alt="Heart">
											<br>Heart
										</a>
										<a id="Marquise" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Marquise" data-val="Marquise">
											<img class="image_off" src="{{asset('assets/images/shape/marquise.png')}}" width="25" alt="Marquise">
											<br>Marquise
										</a>
										<a id="Oval" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Oval" data-val="Oval">
											<img class="image_off" src="{{asset('assets/images/shape/oval.png')}}" width="25" alt="Oval">
											<br>Oval
										</a>
										<a id="Pear" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Radiant" data-val="Pear">
											<img class="image_off" src="{{asset('assets/images/shape/pear.png')}}" width="25" alt="Pear">
											<br>Pear
										</a>
										<a id="Radiant" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Radiant" data-val="Radiant">
											<img class="image_off" src="{{asset('assets/images/shape/radiant.png')}}" width="25" alt="Radiant"><br>Radiant
										</a>
										<a id="SQUARE_RADIANT" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="shape" data-val="SQUARE_RADIANT">
											<img class="image_off" src="{{asset('assets/images/shape/lradiant.png')}}" width="25" alt="SQUARE Radiant"><br>SQ.Radiant
										</a>
										<a id="TRILLIANT" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Trilliant" data-val="TRILLIANT">
											<img class="image_off" src="{{asset('assets/images/shape/trilliant.png')}}" width="25" alt="Trilliant"><br>Trilliant
										</a>
										<a id="CUSHION_MODIFIED" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Cushion" data-val="CUSHION_MODIFIED">
											<img class="image_off" src="{{asset('assets/images/shape/cushion.png')}}" width="25" alt="Cushion Modify"><br>Cushion mod.
										</a>
										<a id="Triangle" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Triangle" data-val="Triangle">
											<img class="image_off" src="{{asset('assets/images/shape/triangle.png')}}" width="25" alt="Triangle"><br>Triangle
										</a>
										<a id="OTHER" class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="other" data-val="OTHER">
											<img class="image_off" src="{{asset('assets/images/shape/other.png')}}" width="25" alt="Other"><br>Other
										</a>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-md-1 col-sm-12 col-xs-12"><b>Carat</b></div>
									<div class="col-md-2 col-sm-12 col-xs-12">
										<input id="min_carat" class="form-control stone_count" name="min_carat" placeholder="From" type="text">
									</div>
									<div class="col-md-2 col-sm-12 col-xs-12">
										<input id="max_carat" class="form-control stone_count" name="max_carat" placeholder="To" type="text">
									</div>
									<div class="col-md-2 col-sm-12 col-xs-12">
										<!-- <input id="multisize" class="form-control" name="multisize" value="Multiple size" style="width: 100%; color: gray;" type="button">
										<div id="multishow" style="display: none;position: absolute; z-index: 99; background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 10px;">
											<div class="row" id="muliple_value">
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size1f" id="multi_size1f" class="form-control multi stone_count" placeholder="From"/>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size1t" id="multi_size1t" class="form-control multi stone_count" placeholder="To"/>
													</div>
												</div>
											</div>
											<div class="row" id="muliple_value">
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size2f" id="multi_size2f" class="form-control multi stone_count" placeholder="From"/>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size2t" id="multi_size2t" class="form-control multi stone_count" placeholder="To"/>
													</div>
												</div>
											</div>
											<div class="row" id="muliple_value">
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size3f" id="multi_size3f" class="form-control multi stone_count" placeholder="From"/>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size3t" id="multi_size3t" class="form-control multi stone_count" placeholder="To"/>
													</div>
												</div>
											</div>
											<div class="row" id="muliple_value">
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size4f" id="multi_size4f" class="form-control multi stone_count" placeholder="From"/>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="append-icon">
														<input type="text" name="multi_size4t" id="multi_size4t" class="form-control multi stone_count" placeholder="To"/>
													</div>
												</div>
											</div>
										</div> -->
									</div>
									<!-- <div class="col-md-2 col-sm-12 col-xs-12">
										<input id="multi_size" class="form-control stone_count " name="multi_size" placeholder="Your Size Area" type="text">
									</div> -->
									<div class="col-md-2 col-sm-12 col-xs-12">
										<input id="stone_id" class="form-control stone_count" name="stone_id" ng-model="stone_id" placeholder="stone ID or Certificate" type="text">
									</div>
									<!-- <div class="col-md-2 col-sm-12 col-xs-12">
										<input type="text" class="form-control stone_count" id="stone_id_encrtypt" name="stone_id_encrtypt" ng-model="stone_id_encrtypt" placeholder="Enccrypt stone ID">
									</div> -->
								</div>
								<div class="row mb-2">
									<div class="col-md-1 col-sm-12 col-xs-12"><b>Color</b></div>
									<div class=" col-md-10 col-sm-12 col-xs-12" style="vertical-align:middle;padding:5px">
                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" id="white" href="#tabwhite">White</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" id="fancy" href="#tabfancy">Fancy</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tabwhite" role="tabpanel">
                                                <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10px; padding-left: 0px; " >
													<a id="color_D" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="D">D</a>
													<a id="color_E" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="E">E</a>
													<a id="color_F" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="F">F</a>
													<a id="color_G" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="G">G</a>
													<a id="color_H" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="H">H</a>
													<a id="color_I" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="I">I</a>
													<a id="color_J" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="J">J</a>
													<a id="color_K" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="K">K</a>
													<a id="color_L" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="L">L</a>
													<a id="color_M" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="M">M</a>
													<a id="color_N" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="N">N</a>
													<a id="color_OP" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="OP">OP</a>
													<a id="color_QR" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="QR">QR</a>
													<a id="color_ST" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="ST">ST</a>
													<a id="color_UV" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="UV">UV</a>
													<a id="color_WX" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="WX">WX</a>
													<a id="color_YZ" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="YZ">YZ</a>
												</div>
                                            </div>
                                            <div class="tab-pane fade" id="tabfancy" role="tabpanel">
                                                <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px ; padding-left: 0px;" >
													<div class="row">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Intensity</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="intesites_Faint" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="Faint" style="border: 1px solid #dcdcdc;">Faint</a>
															<a id="intesites_VeryLight" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="VeryLight" style="border: 1px solid #dcdcdc;">Very Light</a>
															<a id="intesites_Light" class="btn btn-sm togglebtn clarintensitydata ng-binding" title="Intensity" data-val="Light" style="border: 1px solid #dcdcdc;">Light</a>
															<a id="intesites_FancyLight" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="FancyLight" style="border: 1px solid #dcdcdc;">Fancy Light</a>
															<a id="intesites_Fancy" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="Fancy" style="border: 1px solid #dcdcdc;">Fancy</a>
															<a id="intesites_FancyDark" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="FancyDark" style="border: 1px solid #dcdcdc;">Fancy Dark</a>
															<a id="intesites_FancyIntense" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="FancyIntense" style="border: 1px solid #dcdcdc;">Fancy Intense</a>
															<a id="intesites_FancyVivid" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="FancyVivid" style="border: 1px solid #dcdcdc;">Fancy Vivid</a>
															<a id="intesites_FancyDeep" class="btn btn-sm togglebtn intensitydata ng-binding" title="Intensity" data-val="FancyDeep" style="border: 1px solid #dcdcdc;">Fancy Deep</a>
														</div>
													</div>
													<div class="row" style="padding-top: 10px;">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Overtone</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="overtones_None" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="None" style="border: 1px solid #dcdcdc;">None</a>
															<a id="overtones_Yellow" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="Yellow" style="border: 1px solid #dcdcdc;">Yellow</a>
															<a id="overtones_Yellowish" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="Yellowish" style="border: 1px solid #dcdcdc;">Yellowish</a>
															<a id="overtones_Pink" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Pink" style="border: 1px solid #dcdcdc;">Pink</a>
															<a id="overtones_Pinkish" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Pinkish" style="border: 1px solid #dcdcdc;">Pinkish</a>
															<a id="overtones_Blue" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Blue" style="border: 1px solid #dcdcdc;">Blue</a>
															<a id="overtones_Blueish" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Blueish" style="border: 1px solid #dcdcdc;">Blueish</a>
															<a id="overtones_Red" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="Red" style="border: 1px solid #dcdcdc;">Red</a>
															<a id="overtones_Reddish" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="Reddish" style="border: 1px solid #dcdcdc;">Reddish</a>
															<a id="overtones_Green" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Green" style="border: 1px solid #dcdcdc;">Green</a>
															<a id="overtones_Greenish" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Greenish" style="border: 1px solid #dcdcdc;">Greenish</a>
															<a id="overtones_Purple" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Purple" style="border: 1px solid #dcdcdc;">Purple</a>
															<a id="overtones_Purplish" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Purplish" style="border: 1px solid #dcdcdc;">Purplish</a>
															<a id="overtones_Orange" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="Orange" style="border: 1px solid #dcdcdc;">Orange</a>
															<a id="overtones_Orangey" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="Orangey" style="border: 1px solid #dcdcdc;">Orangy</a>
															<a id="overtones_VIOLET" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="VIOLET" style="border: 1px solid #dcdcdc;">Violet</a>
															<a id="overtones_Violetish" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Violetish" style="border: 1px solid #dcdcdc;">Violetish</a>
															<a id="overtones_Gray" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Gray" style="border: 1px solid #dcdcdc;">Gray</a>
															<a id="overtones_Grayish" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Grayish" style="border: 1px solid #dcdcdc;">Grayish</a>
															<a id="overtones_Black" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Black" style="border: 1px solid #dcdcdc;">Black</a>
															<a id="overtones_Brown" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Brown" style="border: 1px solid #dcdcdc;">Brown</a>
															<a id="overtones_Brownish" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Brownish" style="border: 1px solid #dcdcdc;">Brownish</a>
															<a id="overtones_Champagne" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Champagne" style="border: 1px solid #dcdcdc;">Champagne</a>
															<a id="overtones_Cognac" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Cognac" style="border: 1px solid #dcdcdc;">Cognac</a>
															<a id="overtones_Chameleon" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="Chameleon" style="border: 1px solid #dcdcdc;">Chameleon</a>
															<a id="overtones_White" class="btn btn-sm togglebtn overtonedata ng-binding" title="Overtone" data-val="White" style="border: 1px solid #dcdcdc;">White</a>
															<a id="overtones_Other" class="btn btn-sm togglebtn covertonedata ng-binding" title="Overtone" data-val="Other" style="border: 1px solid #dcdcdc;">Other</a>
														</div>
													</div>
													<div class="row" style="padding-top: 10px;">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Color</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="fcolor_Yellow" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Yellow" style="border: 1px solid #dcdcdc;">Yellow</a>
															<a id="fcolor_Pink" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Pink" style="border: 1px solid #dcdcdc;">Pink</a>
															<a id="fcolor_Blue" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Blue" style="border: 1px solid #dcdcdc;">Blue</a>
															<a id="fcolor_Red" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Red" style="border: 1px solid #dcdcdc;">Red</a>
															<a id="fcolor_Green" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Green" style="border: 1px solid #dcdcdc;">Green</a>
															<a id="fcolor_Purple" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Purple" style="border: 1px solid #dcdcdc;">Purple</a>
															<a id="fcolor_Orange" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Orange" style="border: 1px solid #dcdcdc;">Orange</a>
															<a id="fcolor_Violet" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Violet" style="border: 1px solid #dcdcdc;">Violet</a>
															<a id="fcolor_Grey" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Grey" style="border: 1px solid #dcdcdc;">Grey</a>
															<a id="fcolor_Black" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Black" style="border: 1px solid #dcdcdc;">Black</a>
															<a id="fcolor_brown" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="brown" style="border: 1px solid #dcdcdc;">Brown</a>
															<a id="fcolor_White" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="White" style="border: 1px solid #dcdcdc;">White</a>
															<a id="fcolor_Champagne" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Champagne" style="border: 1px solid #dcdcdc;">Champagne</a>
															<a id="fcolor_Cognac" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Cognac" style="border: 1px solid #dcdcdc;">Cognac</a>
															<a id="fcolor_Chameleon" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Chameleon" style="border: 1px solid #dcdcdc;">Chameleon</a>
															<a id="fcolor_Other" class="btn btn-sm togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Other" style="border: 1px solid #dcdcdc;">Others</a>
														</div>
													</div>
												</div>
                                            </div>
                                        </div>

										{{-- <div class="nav nav-tabs navcolor" id="" role="tablist">
											<a class="nav-link active" id="white" data-toggle="pill" href="#tabwhite" role="tab" aria-controls="tabwhite" aria-selected="true">White</a>
											<a class="nav-link" id="fancy" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Fancy</a>
										</div>
										<div class="tab-content">
											<div class="tab-pane active" id="tabwhite" role="tabpanel" aria-labelledby="tabwhite">

											</div>
											<div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
												<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px ; padding-left: 0px;" >
													<div class="row">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Intensity</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="Faint" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Faint" style="border: 1px solid #dcdcdc;">Faint</a>
															<a id="VeryLight" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Very Light" style="border: 1px solid #dcdcdc;">Very Light</a>
															<a id="Light" class="btn togglebtn clarintensitydata ng-binding" title="Intensity" data-val="Light" style="border: 1px solid #dcdcdc;">Light</a>
															<a id="FancyLight" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Fancy Light" style="border: 1px solid #dcdcdc;">Fancy Light</a>
															<a id="Fancy" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Fancy" style="border: 1px solid #dcdcdc;">Fancy</a>
															<a id="FancyDark" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Fancy Dark" style="border: 1px solid #dcdcdc;">Fancy Dark</a>
															<a id="FancyIntense" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Fancy Intense" style="border: 1px solid #dcdcdc;">Fancy Intense</a>
															<a id="FancyVivid" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Fancy Vivid" style="border: 1px solid #dcdcdc;">Fancy Vivid</a>
															<a id="FancyDeep" class="btn togglebtn intensitydata ng-binding" title="Intensity" data-val="Fancy Deep" style="border: 1px solid #dcdcdc;">Fancy Deep</a>
														</div>
													</div>
													<div class="row" style="padding-top: 10px;">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Overtone</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="foNone" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="None" style="border: 1px solid #dcdcdc;">None</a>
															<a id="Yellow" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="Yellow" style="border: 1px solid #dcdcdc;">Yellow</a>
															<a id="Yellowish" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="Yellowish" style="border: 1px solid #dcdcdc;">Yellowish</a>
															<a id="Pink" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Pink" style="border: 1px solid #dcdcdc;">Pink</a>
															<a id="Pinkish" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Pinkish" style="border: 1px solid #dcdcdc;">Pinkish</a>
															<a id="Blue" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Blue" style="border: 1px solid #dcdcdc;">Blue</a>
															<a id="Blueish" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Blueish" style="border: 1px solid #dcdcdc;">Blueish</a>
															<a id="Red" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="Red" style="border: 1px solid #dcdcdc;">Red</a>
															<a id="Reddish" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="Reddish" style="border: 1px solid #dcdcdc;">Reddish</a>
															<a id="Green" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Green" style="border: 1px solid #dcdcdc;">Green</a>
															<a id="Greenish" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Greenish" style="border: 1px solid #dcdcdc;">Greenish</a>
															<a id="Purple" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Purple" style="border: 1px solid #dcdcdc;">Purple</a>
															<a id="Purplish" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Purplish" style="border: 1px solid #dcdcdc;">Purplish</a>
															<a id="Orange" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="Orange" style="border: 1px solid #dcdcdc;">Orange</a>
															<a id="Orangey" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="Orangey" style="border: 1px solid #dcdcdc;">Orangy</a>
															<a id="VIOLET" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="VIOLET" style="border: 1px solid #dcdcdc;">Violet</a>
															<a id="VIOLETISH" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Violetish" style="border: 1px solid #dcdcdc;">Violetish</a>
															<a id="Gray" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Gray" style="border: 1px solid #dcdcdc;">Gray</a>
															<a id="Grayish" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Grayish" style="border: 1px solid #dcdcdc;">Grayish</a>
															<a id="Black" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Black" style="border: 1px solid #dcdcdc;">Black</a>
															<a id="Brown" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Brown" style="border: 1px solid #dcdcdc;">Brown</a>
															<a id="Brownish" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Brownish" style="border: 1px solid #dcdcdc;">Brownish</a>
															<a id="CHAMPANGE" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Champagne" style="border: 1px solid #dcdcdc;">Champagne</a>
															<a id="COGNAC" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Cognac" style="border: 1px solid #dcdcdc;">Cognac</a>
															<a id="CHAMELEON" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="Chameleon" style="border: 1px solid #dcdcdc;">Chameleon</a>
															<a id="White" class="btn togglebtn overtonedata ng-binding" title="Overtone" data-val="White" style="border: 1px solid #dcdcdc;">White</a>
															<a id="Other" class="btn togglebtn covertonedata ng-binding" title="Overtone" data-val="Other" style="border: 1px solid #dcdcdc;">Other</a>
														</div>
													</div>
													<div class="row" style="padding-top: 10px;">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Color</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="Yellow" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Yellow" style="border: 1px solid #dcdcdc;">Yellow</a>
															<a id="Pink" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Pink" style="border: 1px solid #dcdcdc;">Pink</a>
															<a id="Blue" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Blue" style="border: 1px solid #dcdcdc;">Blue</a>
															<a id="Red" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Red" style="border: 1px solid #dcdcdc;">Red</a>
															<a id="Green" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Green" style="border: 1px solid #dcdcdc;">Green</a>
															<a id="Purple" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Purple" style="border: 1px solid #dcdcdc;">Purple</a>
															<a id="Orange" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Orange" style="border: 1px solid #dcdcdc;">Orange</a>
															<a id="Violet" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Violet" style="border: 1px solid #dcdcdc;">Violet</a>
															<a id="Grey" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Grey" style="border: 1px solid #dcdcdc;">Grey</a>
															<a id="Black" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Black" style="border: 1px solid #dcdcdc;">Black</a>
															<a id="brown" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="brown" style="border: 1px solid #dcdcdc;">Brown</a>
															<a id="White" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="White" style="border: 1px solid #dcdcdc;">White</a>
															<a id="Champagne" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Champagne" style="border: 1px solid #dcdcdc;">Champagne</a>
															<a id="Cognac" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Cognac" style="border: 1px solid #dcdcdc;">Cognac</a>
															<a id="Chameleon" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Chameleon" style="border: 1px solid #dcdcdc;">Chameleon</a>
															<a id="others" class="btn togglebtn FancyColordata ng-binding" title="FancyColor" data-val="Other" style="border: 1px solid #dcdcdc;">Others</a>
														</div>
													</div>
												</div>
											</div>
										</div> --}}
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-md-1 col-sm-12 col-xs-12"><b >Clarity</b></div>
									<div class="col-md-10 col-sm-10 col-xs-10">
										<a id="clarity_FL" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="FL">FL</a>
										<a id="clarity_IF" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="IF">IF</a>
										<a id="clarity_VVS1" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="VVS1">VVS1</a>
										<a id="clarity_VVS2" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="VVS2">VVS2</a>
										<a id="clarity_VS1" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="VS1">VS1</a>
										<a id="clarity_VS2" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="VS2">VS2</a>
										<a id="clarity_SI1" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="SI1">SI1</a>
										<a id="clarity_SI2" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="SI2">SI2</a>
										<a id="clarity_I1" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="I1">I1</a>
										<a id="clarity_I2" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="I2">I2</a>
										<a id="clarity_I3" class="btn togglebtn claritydata btn-sm border border-gray-300" title="clarity" data-val="I3">I3</a>
									</div>
								</div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Cut</b></div>
                                    <div class="col-md-6">
                                        <a id="cut_ID" class="btn togglebtn cutdata btn-sm ID" style="border: 1px solid #dcdcdc;" title="cut" data-val="ID">Ideal</a>
                                        <a id="cut_EX" class="btn togglebtn cutdata btn-sm EX" style="border: 1px solid #dcdcdc;" title="cut" data-val="EX">Excellent</a>
                                        <a id="cut_VG" class="btn togglebtn cutdata btn-sm VG" style="border: 1px solid #dcdcdc;" title="cut" data-val="VG">Very Good</a>
                                        <a id="cut_GD" class="btn togglebtn cutdata btn-sm GD" style="border: 1px solid #dcdcdc;" title="cut" data-val="GD">Good</a>
                                        <a id="cut_FR" class="btn togglebtn cutdata btn-sm FR" style="border: 1px solid #dcdcdc;" title="cut" data-val="FR">Fair</a>
                                        <a id="cut_PR" class="btn togglebtn cutdata btn-sm PR" style="border: 1px solid #dcdcdc;" title="cut" data-val="PR">Poor</a>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-3">
                                        <a class="btn togglebtn btn-sm threeex" data-val="3EX" style="border: 1px solid #dcdcdc;">3EX</a>
                                        <a class="btn togglebtn btn-sm threevg" data-val="3EX" style="border: 1px solid #dcdcdc;">3VG+</a>
                                        <a class="btn togglebtn btn-sm nobgm" data-val="nobgm" style="border: 1px solid #dcdcdc;">NO BGM</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Polish</b></div>
                                    <div class="col-md-10">
                                        <a id="polish_EX" class="btn togglebtn polishdata btn-sm EX" style="border: 1px solid #dcdcdc;" title="polish" data-val="EX">Excellent</a>
                                        <a id="polish_VG" class="btn togglebtn polishdata btn-sm VG" style="border: 1px solid #dcdcdc;" title="polish" data-val="VG">Very Good</a>
                                        <a id="polish_GD" class="btn togglebtn polishdata btn-sm GD" style="border: 1px solid #dcdcdc;" title="polish" data-val="GD">Good</a>
                                        <a id="polish_FR" class="btn togglebtn polishdata btn-sm FR" style="border: 1px solid #dcdcdc;" title="polish" data-val="FR">Fair</a>
                                        <a id="polish_PR" class="btn togglebtn polishdata btn-sm PR" style="border: 1px solid #dcdcdc;" title="polish" data-val="PR">Poor</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Symmetry</b></div>
                                    <div class="col-md-10">
                                        <a id="symmetry_EX" class="btn togglebtn symmetrydata btn-sm EX" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="EX">Excellent</a>
                                        <a id="symmetry_VG" class="btn togglebtn symmetrydata btn-sm VG" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="VG">Very Good</a>
                                        <a id="symmetry_GD" class="btn togglebtn symmetrydata btn-sm GD" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="GD">Good</a>
                                        <a id="symmetry_FR" class="btn togglebtn symmetrydata btn-sm FR" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="FR">Fair</a>
                                        <a id="symmetry_PR" class="btn togglebtn symmetrydata btn-sm PR" style="border: 1px solid #dcdcdc;" title="symmetry" data-val="PR">Poor</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Fluorescence</b></div>
                                    <div class="col-md-10">
                                        <a id="flourescence_NON" class="btn togglebtn flourescencedata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="NON">NONE</a>
                                        <a id="flourescence_FNT" class="btn togglebtn flourescencedata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="FNT">FAINT</a>
                                        <a id="flourescence_MED" class="btn togglebtn flourescencedata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="MED">MEDIUM</a>
                                        <a id="flourescence_SLIGHT" class="btn togglebtn flourescencedata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="SLIGHT">SLIGHT</a>
                                        <a id="flourescence_STG" class="btn togglebtn flourescencedata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="STG">STRONG</a>
                                        <a id="flourescence_VST" class="btn togglebtn flourescencedata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="VST">VERY STRONG</a>
                                        <a id="flourescence_VSLT" class="btn togglebtn flourescencedata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="fluorescence" data-val="VSLT">VSLT</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Country</b></div>
                                    <div class="col-md-10">
                                        <a id="location_INDIA" class="btn togglebtn locationdata btn-sm" style="border: 1px solid #dcdcdc;" title="location" data-val="INDIA">INDIA</a>
                                        <a id="location_HONGKONG" class="btn togglebtn locationdata btn-sm" style="border: 1px solid #dcdcdc;" title="location" data-val="HONGKONG">HONG KONG</a>
                                        <a id="location_ISRAEL" class="btn togglebtn locationdata btn-sm" style="border: 1px solid #dcdcdc;" title="location" data-val="ISRAEL">ISRAEL</a>
                                        <a id="location_USA" class="btn togglebtn locationdata btn-sm" style="border: 1px solid #dcdcdc;" title="location" data-val="USA">USA</a>
                                        <a id="location_UAE" class="btn togglebtn locationdata btn-sm" style="border: 1px solid #dcdcdc;" title="location" data-val="UAE">UAE</a>
                                        <a id="location_BELGIUM" class="btn togglebtn locationdata btn-sm" style="border: 1px solid #dcdcdc;" title="location" data-val="BELGIUM">BELGIUM</a>
                                        <a id="location_OTHER" class="btn togglebtn locationdata btn-sm" style="border: 1px solid #dcdcdc;" title="location" data-val="OTHER">OTHER</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Lab</b></div>
                                    <div class="col-md-10">
                                        <a id="lab_GIA" class="btn togglebtn labdata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="lab" data-val="GIA">GIA</a>
                                        <a id="lab_IGI" class="btn togglebtn labdata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="lab" data-val="IGI">IGI</a>
                                        <a id="lab_HRD" class="btn togglebtn labdata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="lab" data-val="HRD">HRD</a>
                                        <a id="lab_AGS" class="btn togglebtn labdata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="lab" data-val="AGS">AGS</a>
                                        <a id="lab_GCAL" class="btn togglebtn labdata btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="lab" data-val="GCAL">GCAL</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Eye Clean</b></div>
                                    <div class="col-md-10">
                                        <a id="eyeclean_YES" class="btn togglebtn eyecleandata btn-sm eyesyes" style="border: 1px solid #dcdcdc;" title="eyeclean" data-val="YES">YES</a>
                                        <a id="eyeclean_NO" class="btn togglebtn eyecleandata btn-sm eyesyes" style="border: 1px solid #dcdcdc;" title="eyeclean" data-val="NO">NO</a>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Treatment</b></div>
                                    <div class="col-md-10">
                                        <a id="c_type_CVD" class="btn togglebtn btn-sm c_type" style="border: 1px solid #dcdcdc;" title="c_type" data-val="CVD">CVD</a>
                                        <a id="c_type_HPHT" class="btn togglebtn btn-sm c_type" style="border: 1px solid #dcdcdc;" title="c_type" data-val="HPHT">HPHT</a>
                                    </div>
                                </div>


								<div class="row mb-2">
									<div class="col-md-7">
										<!-- <div class="row mb-2">
											<div class="col-md-1"><b>Brown</b></div>
											<div class="col-md-10">
												<a class="btn togglebtn btn-sm ng-binding NOBROWN" style="border: 1px solid #dcdcdc;" title="BROWN" data-val="NO BROWN">NO BROWN</a>
												<a class="btn togglebtn btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="BROWN" data-val="LIGHT BROWN">L. BROWN</a>
												<a class="btn togglebtn btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="BROWN" data-val="BROWN">BROWN</a>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-md-1"><b>Green</b></div>
											<div class="col-md-10">
												<a class="btn togglebtn btn-sm ng-binding NOGREEN" style="border: 1px solid #dcdcdc;" title="GREEN" data-val="NO GREEN">NO GREEN</a>
												<a class="btn togglebtn btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="GREEN" data-val="LIGHT GREEN">L. GREEN</a>
												<a class="btn togglebtn btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="GREEN" data-val="GREEN">GREEN</a>
												<a class="btn togglebtn btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="GREEN" data-val="MIX-TINGE">MIX T</a>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-md-1"><b>Milky</b></div>
											<div class="col-md-10">
												<a class="btn togglebtn btn-sm ng-binding NOMILKY" style="border: 1px solid #dcdcdc;" title="MILKY" data-val="NO MILKY">NO MILKY</a>
												<a class="btn togglebtn btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="MILKY" data-val="LIGHT MILKY">L. MILKY</a>
												<a class="btn togglebtn btn-sm ng-binding" style="border: 1px solid #dcdcdc;" title="MILKY" data-val="MILKY">MILKY</a>
											</div>
										</div> -->
									</div>
								</div>
								<!-- <div class="row mb-2">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div class="x_panel">
											<div class="x_content">
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-2"><b>Diameter</b></div>
														<div class="col-md-5">
															<input id="min_dia" class="form-control stone_count " name="min_dia" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_dia" class="form-control stone_count " name="max_dia" placeholder="To" type="text">
														</div>
													</div>

													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Depth</b></div>
														<div class="col-md-5">
															<input id="min_depth" class="form-control stone_count " name="min_depth" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_depth" class="form-control stone_count" name="max_depth" placeholder="To" type="text">
														</div>
													</div>
													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Table</b></div>
														<div class="col-md-5">
															<input id="min_table" class="form-control stone_count " name="min_table" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_table" class="form-control stone_count " name="max_table" placeholder="To" type="text">
														</div>
													</div>

													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Length</b></div>
														<div class="col-md-5">
															<input id="min_length" class="form-control stone_count " name="min_length" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_length" class="form-control stone_count" name="max_length" placeholder="To" type="text">
														</div>
													</div>

													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Width</b></div>
														<div class="col-md-5">
															<input id="min_width" class="form-control stone_count " name="min_width" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_width" class="form-control stone_count" name="max_width" placeholder="To" type="text">
														</div>
													</div>
												</div>

												<div class="col-md-6">
													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Pavilion Angle</b></div>
														<div class="col-md-5">
															<input id="min_pavilion_angle" class="form-control stone_count" name="min_pavilion_angle" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_pavilion_angle" class="form-control stone_count " name="max_pavilion_angle" placeholder="To" type="text">
														</div>
													</div>

													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Pavilion Height</b></div>
														<div class="col-md-5">
															<input id="min_pavilion_height" class="form-control stone_count" name="min_pavilion_height" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_pavilion_height" class="form-control stone_count " name="max_pavilion_height" placeholder="To" type="text">
														</div>
													</div>

													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Crown Angle</b></div>
														<div class="col-md-5">
															<input id="min_crown_angle" class="form-control stone_count " name="min_crown_angle" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_crown_angle" class="form-control stone_count " name="max_crown_angle" placeholder="To" type="text">
														</div>
													</div>

													<div class="row" style="margin-top: 5px;">
														<div class="col-md-2"><b>Crown Height</b></div>
														<div class="col-md-5">
															<input id="min_crown_height" class="form-control stone_count " name="min_crown_height" placeholder="From" type="text">
														</div>
														<div class="col-md-5">
															<input id="max_crown_height" class="form-control stone_count" name="max_crown_height" placeholder="To" type="text">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div> -->
							</div>
						</div>
						<footer style="position:fixed;bottom:0;width:100%;background: #2a3f54 none repeat scroll 0 0; color: #e7e7e7; padding-top: 5px;padding-bottom: 0px;">
							<div class="pull-right">
								<div id="go_my_search" class="btn btn-primary go_my_search" type="button" value="Search" style="">
									<i class="glyphicon glyphicon-search"></i> Search
								</div>
							</div>
							<div class="clearfix"></div>
						</footer>
					</div>
				</div>
				@include('admin/footer')
			</div>
		</div>
	</div>

	<script>var hostUrl = "/assets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
	<!--end::Global Javascript Bundle-->

	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
	<!--end::Page Custom Javascript-->

	<script type="text/javascript">
		$(document).ready(function () {
			var xhr;
			function request_call(url, mydata)
			{
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

            $('.resetbtn').click(function() {
                var str = (localStorage.getItem("search"));
                searcharr = str.split('&');
                searcharr.forEach(function(item,data) {
                    if(data != 0){
                        value = item.split('=');
                        singleval = value[1].split(',');
                        if(value[0] == 'min_carat'){
                            $('#min_carat').val('');
                        }
                        else if(value[0] == 'max_carat'){
                            $('#max_carat').val('');
                        }
                        else if(value[0] == 'stoneid'){
                            $('#stone_id').val('');
                        }
                        else if(value[0] == 'company'){
                            comval = value[1].split('%2C');
                            comval.forEach(function(companyitem,companydata) {
                                $("#company_"+companyitem).prop("selected", false);
                            });
                        }
                        else{
                            singleval.forEach(function(singleitem,singledata) {
                                if(value[0] == 'shape'){
                                    $("#"+ singleitem+"").removeClass("btn-primary");
                                }
                                else{
                                    $("#"+value[0]+"_"+singleitem+"").removeClass("btn-primary");
                                }
                            });
                        }
                    }
                });
                localStorage.setItem("search",'');
            })

            propselected();

            function propselected(){
                var str = (localStorage.getItem("search"));
                searcharr = str.split('&');
                searcharr.forEach(function(item,data) {
                    if(data != 0){
                        value = item.split('=');
                        singleval = value[1].split(',');
                        if(value[0] == 'min_carat'){
                            $('#min_carat').val(value[1]);
                        }
                        else if(value[0] == 'max_carat'){
                            $('#max_carat').val(value[1]);
                        }
                        else if(value[0] == 'stoneid'){
                            $('#stone_id').val(value[1]);
                        }
                        else if(value[0] == 'company'){
                            comval = value[1].split('%2C');
                            comval.forEach(function(companyitem,companydata) {
                                $("#company_"+companyitem).prop("selected", true);
                            });
                        }
                        else{
                            singleval.forEach(function(singleitem,singledata) {
                                if(value[0] == 'shape'){
                                    $("#"+ singleitem+"").addClass("btn-primary");
                                }
                                else{
                                    $("#"+value[0]+"_"+singleitem+"").addClass("btn-primary");
                                }
                            });
                        }
                    }
                });
            }


			function preparesearch()
			{
				var search_string = "", shape = "", color = "", clarity = "", cut = "", polish = "", symmetry = "", flourescence = "", brown_milky = "", lab = "", navigates = "", natts = "", show_hold_by_user = "", available = "", location = "", eyeclean = "", c_type = "", BROWN = "", GREEN = "", MILKY = "", HA = "", ktsdata = "", fcolor = "", intesites = "", overtones = "";
				var fancyorwhite = "";
				if ($('#fancy').hasClass("active"))
				{
					fancyorwhite = "fancy";
				}

				$(".btn-primary").each(function (index, value) {
					if ($(this).hasClass('shape'))
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
						if ($.trim(flourescence) != "")
							flourescence += ",";

						if ($.trim($(this).attr("data-val")) != "")
						{
							flourescence += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "BROWN")
					{
						if ($.trim(BROWN) != "")
							BROWN += ",";

						if ($.trim($(this).attr("data-val")) != "")
						{
							BROWN += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "GREEN")
					{
						if ($.trim(GREEN) != "")
							GREEN += ",";

						if ($.trim($(this).attr("data-val")) != "")
						{
							GREEN += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "MILKY")
					{
						if ($.trim(MILKY) != "")
							MILKY += ",";
						if ($.trim($(this).attr("data-val")) != "")
						{
							MILKY += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "location")
					{
						if ($.trim(location) != "")
							location += ",";
						if ($.trim($(this).attr("data-val")) != "")
						{
							location += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "eyeclean")
					{
						if ($.trim(eyeclean) != "")
							eyeclean += ",";
						if ($.trim($(this).attr("data-val")) != "")
						{
							eyeclean += $(this).attr("data-val");
						}
					}
                    else if ($(this).attr('title') == "c_type")
					{
						if ($.trim(c_type) != "")
                        c_type += ",";
						if ($.trim($(this).attr("data-val")) != "")
						{
							c_type += $(this).attr("data-val");
						}
					}
                    else if ($(this).attr('title') == "HA")
					{
						if ($.trim(HA) != "")
							HA += ",";

						if ($.trim($(this).attr("data-val")) != "")
						{
							HA += $(this).attr("data-val");
						}
					} else if ($(this).attr('title') == "natts")
					{
						if ($.trim(natts) != "")
							natts += ",";

						if ($.trim($(this).attr("data-val")) != "")
							natts += $(this).attr("data-val");
					} else if ($(this).attr('title') == "lab")
					{
						if ($.trim(lab) != "")
							lab += ",";

						if ($.trim($(this).attr("data-val")) != "")
							lab += $(this).attr("data-val");
					} else if ($(this).attr('title') == "navigates")
					{
						if ($.trim(navigates) != "")
							navigates += ",";

						if ($.trim($(this).attr("data-val")) != "")
							navigates += $(this).attr("data-val");
					}
					else if ($(this).attr('title') == "FancyColor")
					{
						if ($.trim(fcolor) != "")
							fcolor += ",";

						if ($.trim($(this).attr("data-val")) != "")
							fcolor += $(this).attr("data-val");
					}
					else if ($(this).attr('title') == "Intensity")
					{
						if ($.trim(intesites) != "")
							intesites += ",";

						if ($.trim($(this).attr("data-val")) != "")
							intesites += $(this).attr("data-val");
					}
					else if ($(this).attr('title') == "Overtone")
					{
						if ($.trim(overtones) != "")
							overtones += ",";

						if ($.trim($(this).attr("data-val")) != "")
							overtones += $(this).attr("data-val");
					}
				});
				var ktsdata = "";
				$(".check").each(function (event) {
					if (this.checked) {
						if ($.trim(ktsdata) != "")
							ktsdata += ",";

						if ($.trim($(this).attr("data-val")) != "")
							ktsdata += $(this).attr("data-val");
					}
				});

				// multi = $("#multi_size").val();
				// var multivalue = multi.split(/[ -]+/);
				// var filteredArr = multivalue.filter(e => e);
				// var smallmulti = Math.min.apply(null, filteredArr);
				// var maxmulti = Math.max.apply(null, filteredArr);

				search_string += set_search_single("shape", shape);
				search_string += set_search_single("company", encodeURIComponent($("#companycount").val()));
				search_string += set_search_single("ktsdata", ktsdata);
				search_string += set_search_single("color", color);
				search_string += set_search_single("clarity", clarity);
				search_string += set_search_single("cut", cut);
				search_string += set_search_single("polish", polish);
				search_string += set_search_single("symmetry", symmetry);
				search_string += set_search_single("flourescence", flourescence);
				search_string += set_search_single("lab", lab);
				search_string += set_search_single("HA", HA);
				search_string += set_search_single("BROWN", BROWN);
				search_string += set_search_single("GREEN", GREEN);
				search_string += set_search_single("MILKY", MILKY);
				search_string += set_search_single("location", location);
				search_string += set_search_single("eyeclean", eyeclean);
                search_string += set_search_single("c_type", c_type);
				// search_string += set_search_single("stone_id_encrtypt", encodeURIComponent($("#stone_id_encrtypt").val()));
				search_string += set_search_single("stoneid", encodeURIComponent($("#stone_id").val()));
				search_string += set_search_single("certificateid", encodeURIComponent($("#certificate_id").val()));
				search_string += set_search_single("fancyorwhite", fancyorwhite);
				search_string += set_search_single("fcolor", fcolor);
				search_string += set_search_single("intesites", intesites);
				search_string += set_search_single("overtones", overtones);

				// search_string += set_search_numeric_range("min_carat", smallmulti, "max_carat", maxmulti);
				search_string += set_search_numeric_range("min_carat", $("#min_carat").val(), "max_carat", $("#max_carat").val());
				search_string += set_search_numeric_range("min_dia", $("#min_dia").val(), "max_dia", $("#max_dia").val());
				search_string += set_search_numeric_range("min_depth", $("#min_depth").val(), "max_depth", $("#max_depth").val());
				search_string += set_search_numeric_range("min_table", $("#min_table").val(), "max_table", $("#max_table").val());
				search_string += set_search_numeric_range("min_length", $("#min_length").val(), "max_length", $("#max_length").val());
				search_string += set_search_numeric_range("min_width", $("#min_width").val(), "max_width", $("#max_width").val());
				search_string += set_search_numeric_range("min_girdle_per", $("#min_girdle_per").val(), "max_girdle_per", $("#max_girdle_per").val());
				search_string += set_search_numeric_range("min_crown_angle", $("#min_crown_angle").val(), "max_crown_angle", $("#max_crown_angle").val());
				search_string += set_search_numeric_range("min_crown_height", $("#min_crown_height").val(), "max_crown_height", $("#max_crown_height").val());
				search_string += set_search_numeric_range("min_pavilion_angle", $("#min_pavilion_angle").val(), "max_pavilion_angle", $("#max_pavilion_angle").val());
				search_string += set_search_numeric_range("min_pavilion_height", $("#min_pavilion_height").val(), "max_pavilion_height", $("#max_pavilion_height").val());

				if (typeof (Storage) !== "undefined") {
					localStorage.setItem("search", search_string);
				} else {
					alert("You can't see search stone");
				}
				return search_string;
			}

			$('#fancy').click(function (event) {
				$('#fancy').addClass("active");
				$('#white').removeClass("active");
				count_stone();
			});

			$('#white').click(function (event) {
				$('#white').addClass("active");
				$('#fancy').removeClass("active");
				count_stone();
			});

			$('.togglebtn').click(function (event) {
				event.preventDefault();
				event.stopPropagation();
				$(this).toggleClass("btn-primary", "");
				var act = $('.togglebtn').hasClass("btn-primary");
				if(act){
					count_stone();
				}
				else
				{
					$('#no_of_stone').html('0');
				}
			});
			// $('.eyesyes').click(function (event) {
			// 	event.preventDefault();
			// 	event.stopPropagation();
			// 		$('.eyesyes').each(function(){
			// 			$(this).removeClass("btn-primary");
			// 		})
			// 		$(this).addClass("btn-primary");
			// 	count_stone();
			// });

			$('.stone_count').blur(function (event) {
				event.preventDefault();
				event.stopPropagation();
				$(this).toggleClass("btn-default", "");
				count_stone();
			});

			$('#companycount').change(function (event) {
				event.preventDefault();
				event.stopPropagation();
				count_stone();
			});

			$('.allshape').click(function (event) {
				$("#allshape").toggleClass("btn-primary", "");
                if ($(this).hasClass("btn-primary"))
				{
                	$(".shape").each(function () {
						$(this).addClass("btn-primary");
					});
					count_stone();
				} else
				{
					$(".shape").each(function () {
						$(this).removeClass("btn-primary");
					});
					setTimeout(function(){ count_stone(); }, 200);
				}
			});

			function count_stone() {
				var ptext = preparesearch();
				if (ptext != "")
				{
					request_call("{{ url('diamondcountlabgrown') }}", ptext);
					xhr.done(function (mydata) {
						var count_stone = mydata.count;
						var display = $("#no_of_stone").html(count_stone);
					});
				} else
				{
					$('#no_of_stone').html('0');
				}
			}

			$("body").delegate(".delete_stock_all", "click", function () {
				var company_name = $("#companycount").val();
				var stone_id = $("#stone_id").val();
				$.confirm({
					title: 'Confirm!',
					content: 'Are you sure all delete stock !',
					buttons: {
						confirm: function () {

                            blockUI.block();
							request_call("{{ url('delete-stock-labgrown') }}", "company_name=" + company_name + "&stone_id=" + stone_id);
							xhr.done(function (mydata) {
                                blockUI.release();
								$.dialog({
									title: 'Successfully',
									content: 'All Stock Remove Successfully!!',
								});
							});
						},
						cancel: function () {
							$.alert('Canceled!');
						},
					}
				});
			});





			$('.go_my_search').click(function (event) {
				event.preventDefault();
				preparesearch();
				var count_stone = $('#no_of_stone').html();
				var search1 = localStorage.getItem("search");
				if (count_stone == 0)
				{
					Swal.fire("Warning!", "Diamonds you search not found...!", "warning");
				} else
				{
					window.location.href = "diamond_labgrown_list";
				}
			});


			// color slelect
			$("#targetcolor").click(function () {
				var colorvalue = $(this).val();
				var colorvalue2 = $("#stopcolor").val();
				$(".colordata").each(function () {
					var id = $(this).attr("id");
					if (id == colorvalue)
					{
						$(this).addClass("btn-primary");
						count_stone();
					} else
					{
						if (colorvalue2 >= id)
						{
							for (i = colorvalue; i <= id; i++)
							{
								$(this).addClass("btn-primary");
								count_stone();
							}
						}
					}
				});
			});

			$("#stopcolor").click(function () {

				var colorvalue = $(this).val();
				var colorvalue2 = $("#targetcolor").val();

				$(".colordata").each(function () {
					var id = $(this).attr("id");
					var i = '';

					if (colorvalue >= id)
					{
						for (i = colorvalue2; i <= id; i++)
						{
							$(this).addClass("btn-primary");
							count_stone();
						}
					} else
					{
						$(this).removeClass("btn-primary");
						$('#no_of_stone').html('0');
					}
				});
			});
			//clerity select

			$("#targetclarity").click(function () {
				var clarityvalue = $(this).val();
				var clarityvalue2 = $("#stopoclarity").val();
				var maxclarity = ['FL', 'IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'SI3', 'I1'];
				var claritycurrent = maxclarity.indexOf(clarityvalue);
				var claritystart = maxclarity.indexOf(clarityvalue2);

				$(".claritydata").each(function () {
					var idd = $(this).attr("id");
					var id = maxclarity.indexOf(idd);
					if (id == claritycurrent)
					{
						$(this).addClass("btn-primary");
						count_stone();

					} else
					{
						if (claritystart >= id)
						{
							for (i = claritycurrent; i <= id; i++)
							{
								$(this).addClass("btn-primary");
								count_stone();
							}
						}
					}
				});
			});

			$("#stopoclarity").click(function () {
				var clarityvalue = $(this).val();
				var clarityvalue2 = $("#targetclarity").val();
				var maxclarity = ['FL', 'IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'SI3', 'I1'];
				var claritycurrent = maxclarity.indexOf(clarityvalue);
				var claritystart = maxclarity.indexOf(clarityvalue2);
				$(".claritydata").each(function () {
					var idd = $(this).attr("id");
					var id = maxclarity.indexOf(idd);
					var i = '';
					if (claritycurrent >= id)
					{
						for (i = claritystart; i <= id; i++)
						{
							$(this).addClass("btn-primary");
							count_stone();
						}
					} else
					{
						$(this).removeClass("btn-primary");
						$('#no_of_stone').html('0');
					}

				});
			});

			// $("#multisize").click(function (e) {
			// 	e.preventDefault();
			// 	e.stopPropagation();
			// 	$("#multishow").toggle();
			// });

			// $(document).on('click', function (e) {
			// 	if (!$.contains($('#multishow').get(0), e.target)) {
			// 		$('#multishow').hide();
			// 	}
			// });


			$(".nobgm").click(function (event) {
				if ($(this).hasClass("btn-primary"))
				{
					$(".NOBROWN").addClass("btn-primary");
					$(".NOGREEN").addClass("btn-primary");
					$(".NOMILKY").addClass("btn-primary");
				} else
				{
					$(".NOBROWN").removeClass("btn-primary");
					$(".NOGREEN").removeClass("btn-primary");
					$(".NOMILKY").removeClass("btn-primary");
				}
			});

			$(".threevg").click(function (event) {
				if ($(this).hasClass("btn-primary"))
				{
					$(".VG").each(function () {
						$(this).addClass("btn-primary");
					});
					$(".EX").each(function () {
						$(this).addClass("btn-primary");
					});
				} else
				{
					$(".VG").each(function () {
						$(this).removeClass("btn-primary");
					});
					$(".EX").each(function () {
						$(this).removeClass("btn-primary");
					});

				}
			});

			$(".threeex").click(function (event) {
				if ($(this).hasClass("btn-primary"))
				{

					$(".EX").each(function () {
						$(this).addClass("btn-primary");
					});

				} else
				{
					$(".EX").each(function () {
						$(this).removeClass("btn-primary");
					});
				}
			});


			$('#multishow').delegate('.multi', 'blur', function (event) {

				var i = 1;
				var from = '';
				var to = '';
				var twovalue = '';
				for (i = 1; i <= 4; i++)
				{
					from = $("#multi_size" + i + "f").val();
					to = $("#multi_size" + i + "t").val();
					if (from != '' && to != '')
					{
						twovalue += from + "-" + to + " ";
					}
				}
				$("#multi_size").val(twovalue);
			});


			function set_search_single(field_name, field_val)
			{
				if ($.trim(field_name) != "" && $.trim(field_val) != "")
				{
					return ("&" + $.trim(field_name) + "=" + $.trim(field_val));
				} else
				{
					return "";
				}
			}

			function set_search_numeric_range(min_name, min_val, max_name, max_val)
			{
				if ($.trim(min_name) != "" && $.trim(max_name) != "" && $.isNumeric($.trim(min_val)) && $.isNumeric($.trim(max_val)))
				{
					return ("&" + $.trim(min_name) + "=" + $.trim(min_val) + "&" + $.trim(max_name) + "=" + $.trim(max_val));
				} else
				{
					return "";
				}
			}
		});
	</script>
	</body>
</html>
