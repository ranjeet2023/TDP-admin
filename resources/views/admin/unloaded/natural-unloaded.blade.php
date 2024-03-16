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
                            <div class="card-header collapsible">
                                <h3 class="card-title">Unloaded Natural</h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-sm btn-primary go_my_search cursor-pointer rotate  me-2" id="go_my_search" value="Search" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">Search</button>
                                    <button type="button" class="btn btn-sm btn-secondary cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">Modify</button>
                                </div>
                            </div>
							<div class="card-body card-scroll h-500px collapse show" id="kt_docs_card_collapsible">
								<div class="row mb-2">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<a class="btn btn-sm allshape" data-val="All" title="shape" style="border: 1px solid #dcdcdc; margin: 0px;font-size: 12px;  padding-left:10px; padding-right: 10px;">
											<span style="font-size:18px;width: 25px;">All </span><br/>Shapes
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-3" title="Round" data-val="Round">
											<img class="image_off" src="{{asset('assets/images/shape/round.png')}}" width="25" alt="Round">
											<br>Round
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-3" title="Princess" data-val="Princess">
											<img class="image_off" src="{{asset('assets/images/shape/princess.png')}}" width="25" alt="Princess">
											<br>Princess
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Asscher" data-val="Asscher">
											<img class="image_off" src="{{asset('assets/images/shape/asscher.png')}}" width="25" alt="Asscher">
											<br>Asscher
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Cushion" data-val="Cushion">
											<img class="image_off" src="{{asset('assets/images/shape/cushion.png')}}" width="25" alt="Cushion">
											<br>Cushion
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Emerald" data-val="Emerald">
											<img class="image_off" src="{{asset('assets/images/shape/emerald.png')}}" width="25" alt="Emerald">
											<br>Emerald
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Heart" data-val="Heart">
											<img class="image_off" src="{{asset('assets/images/shape/heart.png')}}" width="25" alt="Heart">
											<br>Heart
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Marquise" data-val="Marquise">
											<img class="image_off" src="{{asset('assets/images/shape/marquise.png')}}" width="25" alt="Marquise">
											<br>Marquise
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Oval" data-val="Oval">
											<img class="image_off" src="{{asset('assets/images/shape/oval.png')}}" width="25" alt="Oval">
											<br>Oval
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Radiant" data-val="Pear">
											<img class="image_off" src="{{asset('assets/images/shape/pear.png')}}" width="25" alt="Pear">
											<br>Pear
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Radiant" data-val="Radiant">
											<img class="image_off" src="{{asset('assets/images/shape/radiant.png')}}" width="25" alt="Radiant"><br>Radiant
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="shape" data-val="SQUARE RADIANT">
											<img class="image_off" src="{{asset('assets/images/shape/lradiant.png')}}" width="25" alt="SQUARE Radiant"><br>SQ.Radiant
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Trilliant" data-val="TRILLIANT">
											<img class="image_off" src="{{asset('assets/images/shape/trilliant.png')}}" width="25" alt="Trilliant"><br>Trilliant
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Cushion" data-val="CUSHION MODIFIED">
											<img class="image_off" src="{{asset('assets/images/shape/cushion.png')}}" width="25" alt="Cushion Modify"><br>Cushion mod.
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="Triangle" data-val="Triangle">
											<img class="image_off" src="{{asset('assets/images/shape/triangle.png')}}" width="25" alt="Triangle"><br>Triangle
										</a>
										<a class="btn btn-sm togglebtn shape border border-gray-300 p-2" title="other" data-val="OTHER">
											<img class="image_off" src="{{asset('assets/images/shape/other.png')}}" width="25" alt="Other"><br>Other
										</a>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-md-1 col-sm-12 col-xs-12"><b>Carat</b></div>
									<div class="col-md-2 col-sm-12 col-xs-12">
										<input id="min_weight" class="form-control stone_count" name="min_weight" placeholder="From" type="text">
									</div>
									<div class="col-md-2 col-sm-12 col-xs-12">
										<input id="max_weight" class="form-control stone_count" name="max_weight" placeholder="To" type="text">
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
									<div class="col-md-4 col-sm-12 col-xs-12">
										<input id="stoneid" class="form-control stone_count" name="stoneid" ng-model="stoneid" placeholder="stone ID or Certificate" type="text">
									</div>
									<!-- <div class="col-md-2 col-sm-12 col-xs-12">
										<input type="text" class="form-control stone_count" id="stoneid_encrtypt" name="stoneid_encrtypt" ng-model="stoneid_encrtypt" placeholder="Enccrypt stone ID">
									</div> -->
								</div>
								<div class="row mb-2">
									<div class="col-md-1 col-sm-12 col-xs-12"><b>Color</b></div>
									<div class=" col-md-10 col-sm-12 col-xs-12" style="vertical-align:middle;padding:5px">
										<div class="nav nav-tabs navcolor" id="" role="tablist">
											<a class="nav-link active" id="white" data-toggle="pill" href="#tabwhite" role="tab" aria-controls="tabwhite" aria-selected="true">White</a>
											<a class="nav-link" id="fancy" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Fancy</a>
										</div>
										<div class="tab-content" >
											<div class="tab-pane active" id="tabwhite" role="tabpanel" aria-labelledby="tabwhite">
												<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10px; padding-left: 0px; " >
													<a id="D" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="D" >D</a>
													<a id="E" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="E" >E</a>
													<a id="F" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="F" >F</a>
													<a id="G" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="G" >G</a>
													<a id="H" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="H" >H</a>
													<a id="I" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="I" >I</a>
													<a id="J" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="J" >J</a>
													<a id="K" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="K" >K</a>
													<a id="L" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="L" >L</a>
													<a id="M" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="M" >M</a>
													<a id="N" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="N" >N</a>
													<a id="OP" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="OP" >OP</a>
													<a id="QR" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="QR" >QR</a>
													<a id="ST" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="ST" >ST</a>
													<a id="UV" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="UV" >UV</a>
													<a id="WX" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="WX" >WX</a>
													<a id="YZ" class="btn btn-sm togglebtn colordata border border-gray-300" title="color" data-val="YZ" >YZ</a>
												</div>
											</div>
											<div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
												<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px ; padding-left: 0px;" >
													<div class="row">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Intensity</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="Faint" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Faint" >Faint</a>
															<a id="VeryLight" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Very Light" >Very Light</a>
															<a id="Light" class="btn btn-sm togglebtn clarintensitydata border border-gray-300" title="Intensity" data-val="Light" >Light</a>
															<a id="FancyLight" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Fancy Light" >Fancy Light</a>
															<a id="Fancy" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Fancy" >Fancy</a>
															<a id="FancyDark" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Fancy Dark" >Fancy Dark</a>
															<a id="FancyIntense" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Fancy Intense" >Fancy Intense</a>
															<a id="FancyVivid" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Fancy Vivid" >Fancy Vivid</a>
															<a id="FancyDeep" class="btn btn-sm togglebtn intensitydata border border-gray-300" title="Intensity" data-val="Fancy Deep" >Fancy Deep</a>
														</div>
													</div>
													<div class="row" style="padding-top: 10px;">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Overtone</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">
															<a id="foNone" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="None" >None</a>
															<a id="Yellow" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="Yellow" >Yellow</a>
															<a id="Yellowish" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="Yellowish" >Yellowish</a>
															<a id="Pink" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Pink" >Pink</a>
															<a id="Pinkish" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Pinkish" >Pinkish</a>
															<a id="Blue" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Blue" >Blue</a>
															<a id="Blueish" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Blueish" >Blueish</a>
															<a id="Red" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="Red" >Red</a>
															<a id="Reddish" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="Reddish" >Reddish</a>
															<a id="Green" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Green" >Green</a>
															<a id="Greenish" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Greenish" >Greenish</a>
															<a id="Purple" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Purple" >Purple</a>
															<a id="Purplish" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Purplish" >Purplish</a>
															<a id="Orange" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="Orange" >Orange</a>
															<a id="Orangey" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="Orangey" >Orangy</a>
															<a id="VIOLET" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="VIOLET" >Violet</a>
															<a id="VIOLETISH" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Violetish" >Violetish</a>
															<a id="Gray" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Gray" >Gray</a>
															<a id="Grayish" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Grayish" >Grayish</a>
															<a id="Black" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Black" >Black</a>
															<a id="Brown" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Brown" >Brown</a>
															<a id="Brownish" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Brownish" >Brownish</a>
															<a id="CHAMPANGE" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Champagne" >Champagne</a>
															<a id="COGNAC" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Cognac" >Cognac</a>
															<a id="CHAMELEON" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="Chameleon" >Chameleon</a>
															<a id="White" class="btn btn-sm togglebtn overtonedata border border-gray-300" title="Overtone" data-val="White" >White</a>
															<a id="Other" class="btn btn-sm togglebtn covertonedata border border-gray-300" title="Overtone" data-val="Other" >Other</a>
														</div>
													</div>
													<div class="row" style="padding-top: 10px;">
														<div class="col-md-2 col-sm-12 col-xs-12 "style="vertical-align:middle;padding:5px">
															<b>Color</b>
														</div>
														<div class="col-md-10 col-sm-12 col-xs-12">

															<a id="Yellow" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Yellow" >Yellow</a>
															<a id="Pink" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Pink" >Pink</a>
															<a id="Blue" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Blue" >Blue</a>
															<a id="Red" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Red" >Red</a>
															<a id="Green" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Green" >Green</a>
															<a id="Purple" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Purple" >Purple</a>
															<a id="Orange" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Orange" >Orange</a>
															<a id="Violet" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Violet" >Violet</a>
															<a id="Grey" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Grey" >Grey</a>
															<a id="Black" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Black" >Black</a>
															<a id="brown" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="brown" >Brown</a>
															<a id="White" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="White" >White</a>
															<a id="Champagne" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Champagne" >Champagne</a>
															<a id="Cognac" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Cognac" >Cognac</a>
															<a id="Chameleon" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Chameleon" >Chameleon</a>
															<a id="others" class="btn btn-sm togglebtn FancyColordata border border-gray-300" title="FancyColor" data-val="Other" >Others</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-md-1 col-sm-12 col-xs-12"><b >Clarity</b></div>
									<div class="col-md-10 col-sm-10 col-xs-10">
										<a id="FL" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="FL" >FL</a>
										<a id="IF" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="IF" >IF</a>
										<a id="VVS1" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="VVS1" >VVS1</a>
										<a id="VVS2" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="VVS2" >VVS2</a>
										<a id="VS1" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="VS1" >VS1</a>
										<a id="VS2" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="VS2" >VS2</a>
										<a id="SI1" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="SI1" >SI1</a>
										<a id="SI2" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="SI2" >SI2</a>
										<a id="I1" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="I1" >I1</a>
										<a id="I2" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="I1" >I2</a>
										<a id="I3" class="btn btn-sm togglebtn claritydata border border-gray-300" title="clarity" data-val="I1" >I3</a>
									</div>
								</div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Cut</b></div>
                                    <div class="col-md-6">
                                        <a class="btn btn-sm togglebtn EX border border-gray-300" title="EX" data-val="EX">Excellent</a>
                                        <a class="btn btn-sm togglebtn VG border border-gray-300" title="VG" data-val="VG">Very Good</a>
                                        <a class="btn btn-sm togglebtn GD border border-gray-300" title="GD" data-val="GD">Good</a>
                                        <a class="btn btn-sm togglebtn FR border border-gray-300" title="FR" data-val="FR">Fair</a>
                                        <a class="btn btn-sm togglebtn PR border border-gray-300" title="PR" data-val="PR">Poor</a>
                                        <a class="btn btn-sm togglebtn ID border border-gray-300" title="ID" data-val="ID">Ideal</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-sm togglebtn threeex border border-gray-300" data-val="3EX" >3EX</a>
                                        <a class="btn btn-sm togglebtn threevg border border-gray-300" data-val="3EX" >3VG+</a>
                                        <a class="btn btn-sm togglebtn nobgm border border-gray-300" data-val="nobgm" >NO BGM</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Polish</b></div>
                                    <div class="col-md-10">
                                        <a class="btn btn-sm togglebtn EX border border-gray-300" title="polish" data-val="EX">Excellent</a>
                                        <a class="btn btn-sm togglebtn VG border border-gray-300" title="polish" data-val="VG">Very Good</a>
                                        <a class="btn btn-sm togglebtn GD border border-gray-300" title="polish" data-val="GD">Good</a>
                                        <a class="btn btn-sm togglebtn FR border border-gray-300" title="polish" data-val="FR">Fair</a>
                                        <a class="btn btn-sm togglebtn PR border border-gray-300" title="polish" data-val="PR">Poor</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Symmetry</b></div>
                                    <div class="col-md-10">
                                        <a class="btn btn-sm togglebtn EX border border-gray-300"  title="symmetry" data-val="EX">Excellent</a>
                                        <a class="btn btn-sm togglebtn VG border border-gray-300"  title="symmetry" data-val="VG">Very Good</a>
                                        <a class="btn btn-sm togglebtn GD border border-gray-300"  title="symmetry" data-val="GD">Good</a>
                                        <a class="btn btn-sm togglebtn FR border border-gray-300"  title="symmetry" data-val="FR">Fair</a>
                                        <a class="btn btn-sm togglebtn PR border border-gray-300"  title="symmetry" data-val="PR">Poor</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Lab</b></div>
                                    <div class="col-md-10">
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="lab" data-val="GIA">GIA</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="lab" data-val="IGI">IGI</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="lab" data-val="HRD">HRD</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="lab" data-val="AGS">AGS</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="lab" data-val="GCAL">GCAL</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Fluorescence</b></div>
                                    <div class="col-md-10">
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="fluorescence" data-val="NON">NONE</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="fluorescence" data-val="FNT">FAINT</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="fluorescence" data-val="MED">MEDIUM</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="fluorescence" data-val="SLIGHT">SLIGHT</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="fluorescence" data-val="STG">STRONG</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="fluorescence" data-val="VST">VERY STRONG</a>
                                        <a class="btn btn-sm togglebtn  border border-gray-300"  title="fluorescence" data-val="VSLT">VSLT</a>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Country</b></div>
                                    <div class="col-md-10">
                                        <a class="btn btn-sm togglebtn border border-gray-300"  title="location" data-val="INDIA">INDIA</a>
                                        <a class="btn btn-sm togglebtn border border-gray-300"  title="location" data-val="HONGKONG">HONG KONG</a>
                                        <a class="btn btn-sm togglebtn border border-gray-300"  title="location" data-val="ISRAEL">ISRAEL</a>
                                        <a class="btn btn-sm togglebtn border border-gray-300"  title="location" data-val="USA">USA</a>
                                        <a class="btn btn-sm togglebtn border border-gray-300"  title="location" data-val="UAE">UAE</a>
                                        <a class="btn btn-sm togglebtn border border-gray-300"  title="location" data-val="BELGIUM">BELGIUM</a>
                                        <a class="btn btn-sm togglebtn border border-gray-300"  title="location" data-val="OTHER">OTHER</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><b>Eye Clean</b></div>
                                    <div class="col-md-10">
                                        <a class="btn btn-sm togglebtn eyesyes border border-gray-300"  title="eyeclean" data-val="YES">YES</a>
                                        <a class="btn btn-sm togglebtn eyesyes border border-gray-300"  title="eyeclean" data-val="NO">NO</a>
                                    </div>
                                </div>
							</div>
						</div>
                        <div class="card card-custom gutter-b mt-5">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">

                                </h3>
                                <div class="card-toolbar">
                                    <button class="btn btn-secondary btn-sm me-4" title="Move to search" id="movetosearch" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Move to search"><i class="fa fa-reply fa-6"></i></button>
                                    <button class="btn btn-secondary btn-sm me-4" title="Download Excel" id="download_excel" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Download Excel"><i class="fa fa-file-excel fa-6"></i></button>
                                    <button class="btn btn-primary btn-sm me-4 total_record" title="Total Stone" data-placement="top" data-toggle="tooltip" data-original-title="Total Stone">Total Stone = <span id="total_stone_record">0</span></button>
                                    @if (Auth::user()->user_type == 1)
                                    @endif
                                    <button class="btn btn-sm btn-secondary me-4">Diamonds : <span id="total_pcs">0</span></button>
                                    <button class="btn btn-sm btn-secondary me-4">Carat : <span id="totalcarate">0.00</span></button>
                                    <button class="btn btn-sm btn-secondary me-4">$/ct $<span id="totalpercarat">0.00</span></button>
                                    <button class="btn btn-sm btn-secondary">Net Payable $<span id="totalamount">0.00</span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped jambo_table bulk_action">
                                                <thead>
                                                    <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                        <th></th>
                                                        <th>
                                                            <input type="checkbox" class="check_box_all" id="select_box_all">
                                                        </th>
                                                        <th class="column-title">Party </th>
                                                        <th></th>
                                                        <th class="column-title">Stone No</th>
                                                        <th class="column-title">ref Stone</th>
                                                        <th class="column-title">Avail</th>
                                                        <th class="column-title">Shape</th>
                                                        <th class="column-title">Carat</th>
                                                        <th class="column-title">Col</th>
                                                        <th class="column-title">Clarity</th>
                                                        <th class="column-title">Cut</th>
                                                        <th class="column-title">Pol</th>
                                                        <th class="column-title">Sym</th>
                                                        <th class="column-title">Flo</th>
                                                        <th class="column-title">Lab</th>
                                                        <th class="column-title">Certificate</th>
                                                        <th class="column-title">Discount</th>
                                                        <th class="column-title">Price</th>
                                                        <th class="column-title">A Price</th>
                                                        <th class="column-title">Orignal Price</th>
                                                        <th class="column-title">Table %</th>
                                                        <th class="column-title">Depth %</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="render_string"></tbody>
                                            </table>
                                            <ul class="pagination">
                                                <li class="page-item previous disabled" id="previous_page">
                                                    <a href="javascript:void(0)"><span class="page-link">Previous</span></a>
                                                </li>
                                                <li class="page-item next" id="next_page">
                                                    <a href="javascript:void(0)" class="page-link">Next</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link"><span id="pagecount">1</span> to <span id="totalrecord"></span> Total Pages</a>
                                                </li>
                                            </ul>
                                            <!--<div id="add_to_cart">add_to_cart   </div>-->
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

	<script type="text/javascript">
		$(document).ready(function () {
			let xhr;
            let selected_ids = "";
            let total_selected = 0;
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
            let page_record_from = 0;


			$('#render_string').delegate('.diamond_detail', 'click', function() {
                var loatno = this.id;
                blockUI.block();
                request_call("{{ url('diamond-view-detail')}}", "certificate_no=" + $.trim(loatno)+"&diamond_type=W");
                xhr.done(function(mydata) {
                    blockUI.release();
                    $("#header-modal").html(mydata.success);
                    $('#header-modal').modal('show');
                });
            });


			function preparesearch()
			{
				var search_string = "", shape = "", color = "", clarity = "", cut = "", polish = "", symmetry = "", flourescence = "", brown_milky = "", lab = "", navigates = "", natts = "", show_hold_by_user = "", available = "", location = "", eyeclean = "", BROWN = "", GREEN = "", MILKY = "", HA = "", ktsdata = "", fcolor = "", intesites = "", overtones = "";
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
					} else if ($(this).attr('title') == "HA")
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
				// search_string += set_search_single("stoneid_encrtypt", encodeURIComponent($("#stoneid_encrtypt").val()));
				search_string += set_search_single("stoneid", encodeURIComponent($("#stoneid").val()));
				search_string += set_search_single("certificate_id", $("#certificate_id").val());
				search_string += set_search_single("fancyorwhite", fancyorwhite);
				search_string += set_search_single("fcolor", fcolor);
				search_string += set_search_single("intesites", intesites);
				search_string += set_search_single("overtones", overtones);

				// search_string += set_search_numeric_range("min_weight", smallmulti, "max_weight", maxmulti);
				search_string += set_search_numeric_range("min_weight", $("#min_weight").val(), "max_weight", $("#max_weight").val());
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
					localStorage.setItem("notsearch", search_string);
				} else {
					alert("You can't see search stone");
				}
				return search_string;
			}

			$('#fancy').click(function (event) {
				$('#fancy').addClass("active");
				$('#white').removeClass("active");

			});

			$('#white').click(function (event) {
				$('#white').addClass("active");
				$('#fancy').removeClass("active");

			});

			$('.togglebtn').click(function (event) {
				event.preventDefault();
				event.stopPropagation();
				$(this).toggleClass("btn-primary", "");

				var act = $('.togglebtn').hasClass("btn-primary");
				if(act){

				}
				else
				{
					$('#no_of_stone').html('0');
				}
			});
			// $('.eyesyes').click(function (event) {
			// 	event.preventDefault();
			// 	event.stopPropagation();
			// 	$('.eyesyes').each(function(){
			// 			$(this).removeClass("btn-primary");
			// 	})
			// 	$(this).addClass("btn-primary");
			//
			// });

			$('.go_my_search').click(function (event) {
				event.preventDefault();
				preparesearch();
				var count_stone = $('#no_of_stone').html();
				if (count_stone == 0)
				{
					Swal.fire("Warning!", "Diamonds you search not found...!", "warning");
				} else
				{
                    var searchdata = localStorage.getItem("notsearch");
                    blockUI.block();
                    request_call("{{ url('unloaded-natural-diamond')}}", searchdata);
                    xhr.done(function(mydata) {
                        blockUI.release();
                        $('#render_string').html(mydata.result);
                        $('#total_stone_record').html(mydata.count);
                        var number = mydata.number;
                        if (number == 0) {
                            $("#totalrecord").html(mydata.number);
                        } else {
                            $("#totalrecord").html(mydata.number - 1);
                        }
                        if (page_record_from == mydata.number) {
                            $('#next_page').addClass("disabled");
                        }
                    });
				}
			});

            $('.allshape').click(function (event) {
				$(this).toggleClass("btn-primary", "");
				if ($(this).hasClass("btn-primary"))
				{
					$(".shape").each(function () {
						$(this).addClass("btn-primary");
					});
				}
                else
				{
					$(".shape").each(function () {
						$(this).removeClass("btn-primary");
					});
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
					}
                    else
					{
						if (colorvalue2 >= id)
						{
							for (i = colorvalue; i <= id; i++)
							{
								$(this).addClass("btn-primary");
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
						}
					}
                    else
					{
						$(this).removeClass("btn-primary");
						$('#no_of_stone').html('0');
					}
				});
			});
			//clerity select

			$("#targetclarity").click(function () {
				var clarityvalue = $(this).val();
				console.log(clarityvalue);
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


					} else
					{
						if (claritystart >= id)
						{
							for (i = claritycurrent; i <= id; i++)
							{
								$(this).addClass("btn-primary");

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

            $('#render_string').delegate('.checkbox', 'click', function() {
                if ($(this).prop('checked') == true) {
                    $(this).parents("tr").addClass("success");
                    manage_selected_ids("add_only", $(this).attr('id'));
                } else {
                    $('.check_box_all').each(function() {
                        this.checked = false;
                    });
                    $(this).parents("tr").removeClass("success");
                    manage_selected_ids("remove_only", $(this).attr('id'));
                }
                if ($('.checkbox:checked').length == $('.checkbox').length) {
                    $('.check_box_all').each(function() {
                        this.checked = true;
                    });
                }
            });

            $('#select_box_all').click(function(event) {
                if ($(this).prop('checked') == true) {
                    checkbox_check('select');
                } else {
                    checkbox_check('deselect');
                }
            });

            function checkbox_check(chech_option) {
                if (chech_option == "select") {
                    total_selected = 0;
                    $("#render_string .checkbox").each(function() {
                        $(this).prop('checked', true);
                        $(this).parents("tr").addClass("success");
                        manage_selected_ids("add_only", $(this).attr('id'));
                    });
                } else if (chech_option == "deselect") {
                    $("#render_string .checkbox").each(function() {
                        $(this).prop('checked', false);
                        $(this).parents("tr").removeClass("success");
                        manage_selected_ids("remove_only", $(this).attr('id'));
                    });
                    total_selected = 0;
                }
            }

            function manage_selected_ids(option_tag, real_value) {
                if (option_tag == "add_only") {
                    var real_value = $.trim(real_value).toUpperCase() + ",";
                    if (selected_ids.indexOf(real_value) < 0) {
                        selected_ids += real_value;
                    }
                    total_selected++;
                    $('#total_pcs').text(total_selected);

                    var carat = 0;
                    var cprice = 0;
                    var price = 0;
                    $(this).parents("tr").removeClass("success");
                    $('#render_string .checkbox:checked').each(function() {
                        $(this).parents("tr").addClass("success");
                        carat += parseFloat($(this).data('carat'));
                        cprice += parseFloat($(this).data('cprice'));
                        price += parseFloat($(this).data('price'));
                    });


                    $('#totalcarate').html(carat.toFixed(2));
                    $('#totalpercarat').html(cprice.toFixed(2));
                    $('#totalamount').html(price.toFixed(2));
                } else if (option_tag == "remove_only") {
                    var real_value = $.trim(real_value).toUpperCase() + ",";
                    while (selected_ids.indexOf(real_value) >= 0) {
                        selected_ids = selected_ids.replace(real_value, "");
                    }
                    total_selected--;
                    $('#total_pcs').text(total_selected);
                    var carat = 0;
                    var cprice = 0;
                    var price = 0;
                    $('#render_string .checkbox:checked').each(function() {
                        $(this).parents("tr").addClass("success");
                        carat += parseFloat($(this).data('carat'));
                        cprice += parseFloat($(this).data('cprice'));
                        price += parseFloat($(this).data('price'));
                    });
                    $(this).parents("tr").removeClass("success");
                    $('#totalcarate').html(carat.toFixed(2));
                    $('#totalpercarat').html(cprice.toFixed(2));
                    $('#totalamount').html(price.toFixed(2));
                }
            }

            $("#movetosearch").click(function (event) {
                var selected_stone = [];
                $(":checkbox:checked").each(function() {
                    selected_stone.push($(this).attr('data-id'));
                });

                if(selected_stone.length == 0)
                {
                    Swal.fire("Warning!", "Please Select at least one record", "warning");
                }
                else
                {
                    blockUI.block();
                    request_call("{{ url('movetosearch-natural')}}", "selected_stone=" + selected_stone);
                    xhr.done(function (mydata) {
                        blockUI.release();
                        swal({
                            title: "Success",
                            text: mydata.success,
                            type: "success"
                        },
                        function(){
                            location.reload();
                        });
                    });
                }
			});
		});
	</script>
	</body>
</html>
