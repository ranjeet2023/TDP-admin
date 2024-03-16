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
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                                <div class="card">
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title" style="display: inline">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bolder fs-3 mb-1">Leads Report</span>
                                            </h3>
                                        </div>
                                        <div class="card-title">
                                            <h3 class="card-title align-items-start flex-column">
                                                <a href="{{ url('/') }}/leads-list" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i></a>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="row" >
                                        <div class="col-8" style="margin-left:0px">
                                            <div id="chart" >
                                            </div>
                                        </div>
                                        <div class="col-4" >
                                            <div class="form-group mt-4 p-4 ">
                                              <label for="" class="h3">Date:-</label>
                                                <div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar" ></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 p-4">
                                                <label for="" class="h3">Country:-</label>
                                                <select name="country" class="form-control"  id="countySel" size="1">
                                                    <option value="">Select Country </option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-4 p-4">
                                                <label for="" class="h3">Customer:-</label>
                                                <select class="form-control" name="data" id="user">
                                                    <option value="">Select Customer </option>
                                                    @foreach ($leads as  $value)
                                                     <option value="{{$value->firstname}}" > {{$value->firstname}}</option>
                                                   @endforeach
                                                </select>
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

    <script>
       $(document).ready(function() {
                var datefilter="";
                var countryfilter="";
                var userfilter="";
                var startdate="";
                var enddate="";
                var starttime="";
                var endtime="";

                $(function() {
                var start = moment().subtract('days');
                var end = moment();
                function cb(start, end) {
                    startdate=start.format('YYYY-MM-DD');
                    enddate=end.format('YYYY-MM-DD');
                    $('#chart').empty();
                    search(startdate,enddate,countryfilter,userfilter);
                    $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                }
                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                    'All': [moment().subtract(10, 'year').endOf('year'),moment()],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'Last 3 months': [moment().subtract(3, 'months'), moment()],
                    'Last 6 months': [moment().subtract(6, 'months'), moment()],
                    'Last 1 year': [moment().subtract(1, 'years'), moment()],
                    }
                }, cb);
                cb(start, end);
                });

            $('#countySel').change(function() {
                var countrySelected = $(this).find("option:selected");
                countryfilter = countrySelected.val();
                $('#chart').empty();
                search(startdate,enddate,countryfilter,userfilter);
            });
            $('#user').change(function() {
                var userSelected = $(this).find("option:selected");
                userfilter = userSelected.val();
                $('#chart').empty();
                search(startdate,enddate,countryfilter,userfilter);
            });

           function search(startdate,enddate,countryfilter,userfilter){
               data = {};
                if (startdate) {
                    data.startdate =startdate ;
                 }
                 if (enddate) {
                    data.enddate = enddate;
                 }
                 if (countryfilter) {
                    data.countryfilter =countryfilter
                 }
                 if (userfilter) {
                    data.user =userfilter
                 }
            $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ url( '/leads-report')}}',
                    data:data,
                    success: function(data) {
                    var userid=[];
                    const counts = {};
                    data.records.forEach(record => {
                    if (record.type === 1) {
                        if (!counts[record.created_by.firstname]) {
                        userid.push(record.created_by.id);
                        counts[record.created_by.firstname] = { type1: record.count, type2: 0 };
                        } else {
                        counts[record.created_by.firstname].type1 += record.count;
                        }
                    } else if (record.type === 2) {
                        if (!counts[record.created_by.firstname]) {
                        userid.push(record.created_by.id);
                        counts[record.created_by.firstname] = { type1: 0, type2: record.count };
                        } else {
                        counts[record.created_by.firstname].type2 += record.count;
                        }
                    }
                    });
                    var username=[];
                    var callcount=[];
                    var emailcount=[];

                    for (const [name, count] of Object.entries(counts)) {
                    username.push(`${name}`);
                    callcount.push(`${count.type1}`);
                    emailcount.push(`${count.type2}`);
                    }
                    var options = {
                    series: [
                            {
                                name: 'Call',
                                data: callcount,
                                dataurl:1,
                            }, {
                                name: 'Email',
                                data: emailcount,
                                dataurl:2,
                            }
                            ],
                    chart: {
                    type: 'bar',
                    events: {
                            dataPointSelection: function(event, chartContext, obj) {
                                var type  = obj.w.config.series[obj.seriesIndex].dataurl;
                                var  id  =userid[obj.dataPointIndex];
                                var data = `/${'leads-report-detail?detail=' + type}&startdate=${startdate}&enddate=${enddate}&id=${id}&country=${countryfilter}&user=${userfilter}`;
                                window.open(data, '_blank');
                            }
                        },
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                    },
                    responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                        }
                    }
                    }],
                    plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10,
                        columnWidth: '10%',
                        dataLabels: {
                        total: {
                            enabled: true,
                            style: {
                            fontSize: '13px',
                            fontWeight: 900
                            }
                        }
                        }
                    },
                    },
                    xaxis: {
                    categories:username,
                    },

                    legend: {
                    position: 'right',
                    offsetY: 40
                    },
                    fill: {
                    opacity: 1
                    }
                    };
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                    },
                    error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    }
            });
        }
        // search(startdate,enddate,countryfilter,userfilter);
        });

    </script>
    {{-- // <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <script src="{{asset('assets/js/onlycountry.js')}}" type="text/javascript"></script>
    {{-- custome date time  --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

</body>
<!--end::Body-->

</html>
