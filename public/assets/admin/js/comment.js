$(document).ready(function() {
    var xhr;
    var total_selected = 0;
    var page_record_from = 0;
    var selected_ids = "";

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
    $('.comments').on('click', function() {
        var lead_id = $(this).data('lead_id');
            $('.comments').show();
            $('.cancelbtn').hide();
        var parent_tr = $(this).parents('tr');
        blockUI.block();
        request_call('leads-comment-show', 'lead_id=' + lead_id);
        xhr.done(function(mydata) {
            blockUI.release();
            if ($.trim(mydata.detail) != "") {
                $('#comments-'+lead_id).hide();
                $('#hide_comments-'+lead_id).show();
                $('.detail_view').empty();
                parent_tr.after("<tr class='detail_view'><td colspan='100%'> " + $.trim(mydata.detail) + " </td></tr>");
            }
        });
    });

    $('#render_string').delegate('.cancelbtn', 'click', function() {
        var lead_id = $(this).data('lead_id');
        $('.comments').show();
        $('.cancelbtn').hide();
        $('.detail_view').empty();
    });
        $('.commentAdd').on('click', function(){
        let lead_id = $(this).data('lead_id');
        Swal.fire({
            html:   '<lable class="fs-5 float-left">Leads Comment : </lable><br>'+
                '<textarea class="form-control" placeholder="comment" id="comment" rows="4"></textarea>' +
                    '<lable class="fs-5 float-left">Follow Up Date : </lable><br>'+
                    '<input type="datetime-local"  class="form-control" id="followup_date" >',
            icon: 'question',
            confirmButtonColor: '#3085d6',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit ',
            preConfirm: () => {
                const comment = Swal.getPopup().querySelector('#comment').value
                const followup_date = Swal.getPopup().querySelector('#followup_date').value
                if (!comment) {
                    Swal.showValidationMessage(`Please Enter comment`)
                }
                // if (!followup_date) {
                //     Swal.showValidationMessage(`Please Enter Follow Up Date`)
                // }
            }
        }).then((result) => {
            if (result.isConfirmed) {
            let comment =  document.getElementById('comment').value;
            let followup_date =  document.getElementById('followup_date').value;
                blockUI.block();
                request_call('leads-comment-add',"lead_id=" + lead_id +"&comment=" + comment + "&followup_date=" + followup_date);
                xhr.done(function(mydata) {
                    blockUI.release();
                        if(mydata.flag == true){
                            Swal.fire('success','Comment Added Successfully!!','success');
                            }
                        else{
                            Swal.fire('warning','Can Not Add Comment!','warning').then((result) => { if (result.isConfirmed) {location.reload()} });
                    }
                })
            }
        });
    });
    $('#kt_table_users').DataTable({
        'processing': true,
        "pageLength": 100,
        'ordering': false,
        'searching': false,
        'bLengthChange': false
    });
    $('.searching').on("click", function() {
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var phoneno = $('#phoneno').val();
        var email = $('#email').val();
        var country = $('#country').val();
        blockUI.block();
        request_call('leads-list', "firstname=" + firstname + "&lastname=" + lastname + "&phoneno=" + phoneno + "&email=" + email + "&country=" + country );
        $('#render_string').empty();
        xhr.done(function(mydata) {
            blockUI.release();
            if(mydata.leads.data.length == 0){
                document.getElementById('render_string').innerHTML ='<tr><td colspan="100%" align="center">No Record Found!</td></tr>';
            }
            else{
                var row = '';
                mydata.leads.data.forEach(function(item,data) {
                    row += '<tr>'+
                                '<td><a class="btn btn-info btn-sm me-1" href="leads-edit/' + item.id +'">Edit</a></td>' +
                                '<td><button class="btn btn-success btn-sm me-1 commentAdd" data-lead_id="' + item.id + '">Comment</button>'+
                                    '<button class="btn btn-warning btn-icon btn-sm comments" data-lead_id = "' + item.id + '"><i class="fa fa-eye"></i></button></td>' +
                                    '<td><a class="btn btn-danger btn-sm me-1 convert" data-lead_id="'+ item.id +'">Convert</a></td>' +
                        '<td>' + item.firstname + '</td>' +
                        '<td>' + item.lastname + '</td>' +
                        '<td>' + item.date_of_birth + '</td>' +
                        '<td>' + item.email + '</td>' +
                        '<td>' + item.mobile_number + '</td>' +
                        '<td>' + item.phone_number + '</td>' +
                        '<td>' + item.fax_number + '</td>' +
                        '<td>' + item.city + '</td>' +
                        '<td>' + item.country + '</td>';
            if (item.createdbyuser != null) {
                row +=  '<td>' + item.createdbyuser.companyname + '</td>';
            } else {
                row +=  '<td> </td>';
            }
            if (item.assigntouser != null) {
                row +=  '<td>' + item.assigntouser.companyname + '</td>';
            } else {
                row +=  '<td> </td>';
            }
                row +=  '<td>' + item.lead_status + '</td>' +
                        '<td>' + item.last_contacted + '</td>' +
                        '<td>' + item.company_name + '</td>' +
                        '<td>' + item.website_url + '</td>' +
                        '<td>' + item.associated_company + '</td>' +
                        '<td>' + item.created_at + '</td>' +
                        '<td>' + item.updated_at + '</td>' +
                        '</tr>';
                });
                document.getElementById('render_string').innerHTML = row;
            }
        });
    });
    // convert
    $('#render_string').delegate('.convert', 'click', function() {
        var lead_id = $(this).data('lead_id');
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to Convert leads?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Convert!"
            }).then(function(result) {
                if (result.value) {
                    blockUI.block();
                    request_call('leads-convert', 'lead_id=' + lead_id );
                    xhr.done(function(mydata) {
                        blockUI.release();
                        Swal.fire({
                            title: "Success",
                            icon:'success',
                            text: "Leads Convert  Successfully!",
                        })
                    });
                }
            })
        // }
    });
    $('.send-email').on('click', function(){
      var email =$(this).attr('data-email');
                 $("#recipient-name").val(email);
      var lead_id =$(this).attr('data-lead_id');
                 $("#lead_id").val(lead_id);
    });
    $('#send-email').on('click', function() {
        var lead_id = $("#lead_id").val(),
            r_name = $("#recipient-name").val(),
            subject = $("#subject").val(),
           content = tinymce.get('default-editor').getContent({format: 'html'});
           console.log(content);
        if (!r_name) {
            Swal.fire({title: "Success", icon: "warning", text: "Receipient field required!"});
        } else if (!subject) {
            Swal.fire({title: "error", icon: "warning", text: "Subject field required!"});
        } else if (!content) {
            Swal.fire({title: "error", icon: "warning", text: "Content field required!"});
        } else {
            blockUI.block();
            request_call('leads-send-email',"lead_id=" + lead_id + "&r_name=" + r_name + "&subject=" + subject + "&content=" + content)
            xhr.done(function(mydata) {
                    blockUI.release();
                    $('.modal').removeClass('in').attr("aria-hidden","true").css("display", "none");
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $("#form_id").trigger("reset");
                    if(mydata){
                        Swal.fire('success','Email Send Successfully!!','success');
                    } else {
                        Swal.fire('warning','Can Not Send email!','warning');
                    }
                });
           }
    });
    $('.mobile').on('click', function(){
      var copyText =$(this).attr('data-mobile');
      navigator.clipboard.writeText(copyText);
    });
    $(function () {
     $('[data-toggle="tooltip"]').tooltip()
    })

    // leads template
    request_call('leads-email-template');
    // console.log('ew');
    xhr.done(function(response) {
        $('#template').html(response.result);
    });
    $('#template').on('change', function(){
        var data=$(this).val();
        if(data){
        request_call('leads-template-show',"data=" + data)
            xhr.done(function(response) {
            $("#subject").val(response.result.subject);
            var editor = tinymce.activeEditor;
            editor.setContent(response.result.message, { write: false });
            });
        }
      });
});
