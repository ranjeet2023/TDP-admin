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
    <script src="https://cdn.tiny.cloud/1/a98rnje984ktgtk8tn1hhoqzrnsiiltw0dqgrmsdywxx7stm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
     tinymce.init({
     selector: 'textarea',
     plugins: 'anchor autolink charmap code emoticons image link lists media searchreplace table visualblocks wordcount',
     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | code',
     });
    </script>
    <style>
        .card-body {
            padding: 3rem 2.25rem;
            color: var(--bs-card-color);
            background-color:#fff;
        }
        .table tr{
            white-space: nowrap; overflow: hidden; text-overflow:ellipsis;
        }
        .tox-statusbar__branding{
            display: none;
        }
        </style>
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
                        <a href="http://127.0.0.1:8000/leads-list" class="btn btn-primary btn-sm" style="float:right;height:30px"><i class="fa fa-arrow-left"></i></a>
                        <div class="row gy-5 g-xl-8">
                            <div class="col-xl-8 mx-auto">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-icon" role="alert"><i
                                        class="uil uil-times-circle"></i>
                                       {{ session()->get('success') }}
                                     </div>
                                 @endif
                                 <div class="card-body snipcss-OkoQo">
                                    <h1>Template</h1>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-secondary" id="create" data-toggle="modal" data-target="#exampleModal">Create </button>
                                            <button type="button" class="btn btn-secondary" id="show">Show </button>
                                        </div>
                                    <div class="datatable" >
                                        <table class="table" id="myTable" >
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Subject</th>
                                                    <th>Detail</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                            </tbody>
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
   {{--  bootstrap model  --}}
   <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Template<span id="recipient-email"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_id">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Template name</label>
                    <input type="text" class="form-control"  id="name"  required>
                    <input type="text" class="form-control"  id="id" hidden required>
                </div>
                <div class="form-group">
                    <label for="subject" class="col-form-label">Subject:</label>
                    <input type="text" class="form-control" id="subject" required>
                </div>
                <div class="form-group">
                    <label for="message" class="col-form-label">Message:</label>
                    <textarea class="form-control" id="default-editor" required></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" id="temp-edit">Save</button>
        </div>
      </div>
    </div>
  </div>
	<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/scripts.bundle.js')}}"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Page Custom Javascript(used by this page)-->
    <script>
        $(document).ready(function() {
            var xhr;
            var total_selected = 0;
            var page_record_from = 0;
            var selected_ids = "";
            var id="";

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

            function template() {
                    blockUI.block();
                    request_call('leads-template-show');
                    xhr.done(function(mydata) {
                    blockUI.release();
                    if(mydata.result == null){
                            $('#tbody').html('<tr><td colspan="100%" align="center">No Record Found!</td></tr>');
                        }
                        else{
                            var row = '';
                            var id=1;
                            mydata.result.forEach(function(item,result) {
                            row += '<tr class="test">' +
                                        '<td scope="row">' + id + '</td>' +
                                        '<td>' + item.name + '</td>' +
                                        '<td>' + item.subject + '</td>' +
                                        '<td><button class="btn btn-light btn-sm me-1 details" item-id="' + item.id + '" data-toggle="modal" data-target="#exampleModal" >Details</a></td>' +
                                        '<td><button class="btn btn-success btn-sm me-1" item-id ="' + item.id + '" id="delete" >Delete</button>'+
                                '</tr>';
                                id++;
                        });
                        }
                        $('#tbody').html(row);
                    });
                }
            template();
            $('#tbody').delegate('.details','click',function(){
                    var data=$(this).attr('item-id');
                    request_call('leads-template-show',"data=" + data);
                    xhr.done(function(response) {
                        $("#name").val(response.result.name);
                        $("#id").val(response.result.id);
                        $("#subject").val(response.result.subject);
                        var editor = tinymce.activeEditor;
                        editor.setContent(response.result.message, { write: false });
                    });
            });
            $('#temp-edit').on('click', function(){
                   id= $('#id').val();
                   var name =$('#name').val();
                   var subject =$('#subject').val();
                   var content = tinymce.get("default-editor").getContent();
                   if (!name) {
                        Swal.fire({title: "Success", icon: "warning", text: "name field required!"});
                    }
                    if (!subject) {
                        Swal.fire({title: "Success", icon: "warning", text: "subject field required!"});
                    }
                request_call('create-email-template',"lead_id=" + id + "&name=" + name + "&subject=" + subject + "&content=" + content);
                    xhr.done(function(response) {
                        $('.modal').removeClass('in').attr("aria-hidden","true").css("display", "none");
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $("#form_id").trigger("reset");
                        if(response.empty==true){
                            Swal.fire({
                                title: "Success",
                                icon:'success',
                                text: "Create  Successfully!",
                            });
                        }else{
                            Swal.fire({
                                title: "fail",
                                icon:'error',
                                text: "fail",
                            });
                        }
                        template();
                    });
                });

            $('#create').on('click',function(){
                $("#form_id").trigger("reset");
            });
            $('#tbody').delegate('#delete','click',function(){
                var data=$(this).attr('item-id');
                request_call('leads-template-delete',"data=" + data);
                xhr.done(function(response) {
                if(response.empty==true){
                        Swal.fire({
                            title: "Success",
                            icon:'success',
                            text: "Delete  Successfully!",
                        });
                    }else{
                        Swal.fire({
                            title: "fail",
                            icon:'error',
                            text: "fail",
                        });
                    }
                });
                template()
            });
            // var table = $('#myTable').DataTable();
        });
        </script>
	<script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{asset('assets/admin/js/custom/intro.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
<!--end::Body-->
</html>
