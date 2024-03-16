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

	@include('admin/css')
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
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
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">Lab Grown Diamond</span>
                                    <button class="btn btn-primary btn-sm btn-icon" onclick="window.location.href='diamond_labgrown'" id="backbtn"><i class="fa fa-arrow-left"></i></button>
                                </h3>
                                <div class="card-toolbar">
                                    <input type="checkbox" name="supplier_name" id="supplier_name" value="" class="me-1"><label for="supplier_name" class="me-2">Supplier Name</label>
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
                                                            <input type="hidden" id="toorder" data-field="" data-order="">
                                                        </th>
                                                        <th class="column-title">Party </th>
                                                        <th></th>
                                                        <th class="column-title">Stone No</th>
                                                        <th class="column-title">Ref Stone</th>
                                                        <th class="column-title">Avail</th>
                                                        <th class="column-title">Shape</th>
                                                        <th class="column-title orderable" id="carat" style="cursor: pointer;">Carat</th>
                                                        <th class="column-title">Col</th>
                                                        <th class="column-title">Clarity</th>
                                                        <th class="column-title">Cut</th>
                                                        <th class="column-title">Pol</th>
                                                        <th class="column-title">Sym</th>
                                                        <th class="column-title">Flo</th>
                                                        <th class="column-title">Lab</th>
                                                        <th class="column-title">Certificate</th>
                                                        <th class="column-title orderable" id="discount" style="cursor: pointer;">Discount</th>
                                                        @if(in_array(Auth::user()->user_type, array(1,4)))
                                                        <th class="column-title">Sell Price</th>
                                                        @endif
                                                        @if(in_array(Auth::user()->user_type, array(1,5)))
                                                        <th class="column-title">Price</th>
                                                        <th class="column-title orderable" id="net_dollar" style="cursor: pointer;">Orignal Price</th>
                                                        @endif
                                                        <th class="column-title">Table</th>
                                                        <th class="column-title">Depth</th>
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
                                                    <a class="page-link"><span id="pagecount">1</span> &nbsp;to &nbsp; <span id="totalrecord"></span> &nbsp; Total Pages</a>
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

	<script>var hostUrl = "assets/";</script>
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

            $('.orderable').click(function(){
                var id = $(this).attr('id');
                var order = $('#toorder').attr('data-order');
                if(order == 'desc' || order == ''){
                    $('#toorder').attr('data-field',id);
                    $('#toorder').attr('data-order','asc');
                }
                else{
                    $('#toorder').attr('data-field',id);
                    $('#toorder').attr('data-order','desc');
                }
                go_my_search(id);

            })
            $('#download_excel').click(function() {
                var selected_stone = [];
                $(":checkbox:checked").each(function() {
                    selected_stone.push($(this).attr('data-id'));
                });
                var searchdata = localStorage.getItem("search");
                var supplier_name = false;
                if ($("#supplier_name").prop('checked') == true) {
                    var supplier_name = true;
                }
                blockUI.block();
                request_call("{{ url('allStockDownload-labgrown')}}", searchdata + "&selected_stone=" + selected_stone+ "&supplier_name=" + supplier_name);
                xhr.done(function(mydata) {
                    blockUI.release();
                    document.location.href = ("uploads/" + mydata.file_name);
                });
            });

            function go_my_search(s) {
                var sorting = s;
                var order = $('#toorder').attr('data-order');
                var searchdata = localStorage.getItem("search");
                blockUI.block();
                request_call("{{ url('diamond_labgrown_list')}}", "page=" + page_record_from + searchdata + "&sorting=" + sorting +"&order=" + order);
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
            go_my_search();

            var target = document.querySelector("#render_string");
            // var blockUI = new KTBlockUI(target, {
            //     message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            // });

            var page = 0;
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {

                    page++;
                    loadMoreData(page);
                }
            });
            function loadMoreData(page){
                var sorting = $('#toorder').attr('data-field');
                var order = $('#toorder').attr('data-order');
                var searchdata = localStorage.getItem("search");
                blockUI.block();
                request_call("{{ url('diamond_labgrown_list') }}", "page=" + page + searchdata + "&sorting=" + sorting +"&order=" + order);
                xhr.done(function(mydata) {
                    blockUI.release();
                    $('#render_string').append(mydata.result);
                });
            }


            $("#next_page").click(function(event) {
                event.preventDefault();
                var total_record = $("#totalrecord").html();
                if (page_record_from + 1 < total_record) {
                    page_record_from += 1;
                    $('#pagecount').html(page_record_from + 1);
                    var searchdata = localStorage.getItem("search");
                    var sorting = $('#toorder').attr('data-field');
                    var order = $('#toorder').attr('data-order');
                    blockUI.block();
                    request_call("{{ url('diamond_labgrown_list') }}", "page=" + page_record_from + searchdata + "&sorting=" + sorting +"&order=" + order);
                    xhr.done(function(mydata) {
                        blockUI.release();
                        $('#render_string').html(mydata.result);
                    });
                }
                if (page_record_from + 1 == total_record) {
                    $(this).addClass("disabled");
                }

                if (page_record_from > 1) {
                    $("#previous_page").removeClass("disabled");
                } else {
                    $("#previous_page").addClass("disabled");
                }
            });

            $("#previous_page").click(function(event) {
                event.preventDefault();
                var sorting = $('#toorder').attr('data-field');
                var order = $('#toorder').attr('data-order');
                var total_record = $("#totalrecord").html();
                if (page_record_from > 0) {
                    page_record_from -= 1;
                    $('#pagecount').html(page_record_from + 1);
                    var searchdata = localStorage.getItem("search");

                    blockUI.block();
                    request_call("{{ url('diamond_labgrown_list')}}", "page=" + page_record_from + searchdata + "&sorting=" + sorting +"&order=" + order);
                    xhr.done(function(mydata) {
                        blockUI.release();
                        $('#render_string').html(mydata.result);
                    });
                }
                if (page_record_from < total_record) {
                    $("#next_page").removeClass("disabled");
                }
                if (page_record_from > 0) {
                    $("#previous_page").removeClass("disabled");
                } else {
                    $("#previous_page").addClass("disabled");
                }

            });

            //detail
            $('#render_string').delegate('.diamond_detail', 'click', function() {
                var loatno = this.id;
                blockUI.block();
                request_call("{{ url('diamond-view-detail')}}", "certificate_no=" + $.trim(loatno)+"&diamond_type=L");
                xhr.done(function(mydata) {
                    blockUI.release();
                    $("#header-modal").html(mydata.success);
                    $('#header-modal').modal('show');
                });
            });




            $('#render_string').delegate('.view_image', 'click', function() {
                var certinumber = this.id;
                certinumber = certinumber.replace("I", "");

                blockUI.block();
                request_call("Labdiamond/CheckImageSet", "certno=" + certinumber);
                xhr.done(function(mydata) {
                    blockUI.release();
                    if (mydata.success == true) {
                        $("#header-modal").html("<div class='modal-content' style='width: 513px;margin-left: 30%;margin-top:2%;'>" +
                            "<div class='modal-header' style='padding: 7px'>" +
                            "<button type='' class='close modalclose' data-dismiss='modal' aria-hidden='true' style='color:#000;'><i class='fa fa-close'></i></button>" +
                            "<h4 class='modal-title' style='text-align: center;'><strong>Diamond : " + certinumber + "</strong></h4>" +
                            "</div>" +
                            "<div class='modal-body tiles' style='border-top: 1px solid #fff;'>" +
                            "<h5 id='video_wait' align='center'><i class='fa fa-spinner fa-spin' style='font-size:24px'></i></h5>" +
                            "<div class='col-lg-12 changeimage' " + mydata.displaynoneimage + "><a target='_blank' href='" + mydata.defaul_imgurl + "' ><img class='tile'  src='" + mydata.defaul_imgurl + "' width='450px'></a></div>" +
                            "<div class='col-lg-12 changevideo' " + mydata.displaynonevideo + " ><iframe style='border:none;'scrolling='no' src='" + mydata.defaul_url + "' width='450px' height='440px'></iframe></div>" +
                            "<div class='col-lg-12' style='padding-left: 2px;cursor:pointer;'>" +
                            mydata.video + "" +
                            mydata.image + "" +
                            mydata.heart + "" +
                            mydata.arrows + "" +
                            mydata.asset + "" +
                            "</div>" +
                            "&nbsp;" +
                            "</div>" +
                            "</div>");
                        $('#header-modal').modal('show');
                        document.onload = setTimeout(function() {
                            $('#video_wait').hide()
                        }, 6000);
                    } else {
                        $("#header-modal").html("<div class='modal-content' style='width: 513px;margin-left: 30%;margin-top:2%;'>" +
                            "<div class='modal-header' style='padding: 7px'>" +
                            "<button type='' class='close modalclose' data-dismiss='modal' aria-hidden='true' style='color:#000;'><i class='fa fa-close'></i></button>" +
                            "<h4 class='modal-title'style='text-align: center;'><strong>Stone Id : " + certinumber + "</strong></h4>" +
                            "</div>" +
                            "<div class='modal-body tiles'>" +
                            "<h4>No Image Found</h4>" +
                            "</div>" +
                            "</div>");
                        $('#header-modal').modal('show');
                    }
                });
            });

            $('#header-modal').delegate('.clickimage', 'click', function() {
                if (this.id == "video_check") {
                    $('#video_wait').show()
                    $('.changevideo').show();
                    var src = $(this).attr('data-videosrc');
                    $('.changevideo iframe').attr('src', src);
                    $('.changeimage').hide();
                    document.onload = setTimeout(function() {
                        $('#video_wait').hide()
                    }, 4000);
                } else {
                    $('#video_wait').show()
                    $('.changeimage').show();
                    var src = $(this).attr('src');
                    $('.changeimage img').attr('src', src);
                    $('.changeimage a').attr('href', src);
                    $('.changevideo').hide();
                    document.onload = setTimeout(function() {
                        $('#video_wait').hide()
                    }, 800);
                }
            });


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
                    $(this).parents("tr").removeClass("success");
                    $('#totalcarate').html(carat.toFixed(2));
                    $('#totalpercarat').html(cprice.toFixed(2));
                    $('#totalamount').html(price.toFixed(2));
                }
            }
        });
    </script>
</body>
</html>
