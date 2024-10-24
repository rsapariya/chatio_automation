var member_dttble = $('#member_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [50, 100, 150, 200, 500],
    "language": {
        "paginate": {
            "first": "<i class='fa fa-angles-left'></i>",
            "previous": "<i class='fa fa-angle-left'></i>",
            "next": "<i class='fa fa-angle-right'></i>",
            "last": "<i class='fa fa-angles-right'></i>"
        },
        "info": "Showing page _PAGE_ of _PAGES_"
    },
    order: [[0, "desc"]],
    ajax: {
        'type': 'GET',
        "url": base_url + 'team/get_team'
    },
    columns: [
        {
            data: "id",
            visible: true,
            searchable: false,
            sortable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
        },
        {
            data: "name",
            visible: true
        },
        {
            data: "email",
            visible: true
        },
        {
            data: "phone_number",
            visible: true
        },
        {
            data: "is_blocked",
            visible: true,
            render: function (data, type, full, meta) {
                var status = '';
                if(full.is_blocked == 0){
                    status = '<a href="javascript:void(0);"  data-id = "' + btoa(full.id) + '" data-status = "0"  class="badge badge-success change_status">ACTIVE</a>';
                }else{
                    status = '<a href="javascript:void(0);" data-id = "' + btoa(full.id) + '" data-status = "1"  class="badge badge-danger change_status">BLOCKED</a>';
                }
                return status;
            }
        },
        {
            data: "last_login",
            visible: true,
            render: function (data, type, full, meta) {
                var date = '';
                if(full.last_login !== '0000-00-00 00:00:00'){
                    date = full.last_login;
                }
                return date;
            }
        },
        {
            data: "last_ip",
            visible: true
        },
        {
            data: "action",
            visible: true,
            width: '5%',
            searchable: false,
            sortable: false,
            render: function (data, type, full, meta) {

                var action = '<td><ul class="table-controls">';
                action += '<li><a href="javascript:void(0);" data-id="'+ btoa(full.id) +'" id="edit_member" class="btn btn-outline-primary bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Edit"><i class="fa fa-pencil-alt p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0);" data-id="'+ btoa(full.id) + '"  class="btn btn-outline-danger bs-tooltip p-1 delete_team_member"  data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0);" data-id="'+ btoa(full.id) + '"  class="btn btn-outline-secondary bs-tooltip p-1 view_member"  data-bs-placement="top" data-bs-original-title="View"><i class="fa fa-eye p-1 br-6 mb-1"></i></a></li>';
                action += '</ul></td>';
                return action;
            }
        }
    ]
});

jQuery(document).on('click', "#add_member", function (event) {
    $(".team_model_block").load(base_url + 'team/manage', function () {
        $(document).find('#team_model').modal('show');

    });
});

jQuery(document).on('click', "#edit_member", function (event) {
    var id = $(this).data('id');
    $(".team_model_block").load(base_url + 'team/manage/'+id, function () {
        $(document).find('#team_model').modal('show');

    });
});

jQuery(document).on('click', '.save-team', function (e) {
    e.preventDefault();
    var data = jQuery("#team_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "team/save",
        data: data,
        success: function (response) {
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                var msg = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Failed! </strong>'+json.msg+'</button></div>';
                $('#team_frm .user-message').html(msg);
                $('#team_frm .user-message').show();
                setTimeout(function () {
                    $('#team_frm .user-message').html('');
                    $('#team_frm .user-message').hide();
                }, 2000);
            } else {
                var msg = '<div class="alert alert-light-primary alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Success!</strong> Member saved successfully.</button></div>';
                $('#team_frm .user-message').html(msg);
                $('#team_frm .user-message').show();
                setTimeout(function () {
                    $('#team_frm .user-message').html('');
                    $('#team_frm .user-message').hide();
                    $(document).find('#team_model').modal('hide');
                    member_dttble.ajax.reload(null, false);
                }, 2000);
            }
        }
    });
});

jQuery(document).on('click','.change_status' , function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var status = $(this).data('status');
    
    jQuery.ajax({
        type: "POST",
        url: base_url + "team/change_status",
        data: {'id' : id,'status' : status},
        success: function (response) {
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                var msg = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Failed! </strong>'+json.msg+'</button></div>';
                $('.userMessage').html(msg);
                $('.userMessage').show();
            }else{
                var msg = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Success! </strong> Status has been changed!</button></div>';
                $('.userMessage').html(msg);
                $('.userMessage').show();
                member_dttble.ajax.reload(null, false);
            }
            setTimeout(function () {
                    $('.userMessage').html();
                    $('.userMessage').hide();
            }, 2000);
        }
    });
    
});

jQuery(document).on('click', ".view_member", function (event) {
    var id = $(this).data('id');
    $(".team_model_block").load(base_url + 'team/view/'+id, function () {
        $(document).find('#member_info_model').modal('show');
    });
});

jQuery(document).on('click', '.hide_show_password', function(e){
    e.preventDefault();
    const input = this.previousElementSibling;
    const button = this;
    if (input.type === 'password') {
      input.type = 'text'; 
      button.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
      input.type = 'password';
      button.innerHTML = '<i class="fa fa-eye"></i>';
    }
});
jQuery(document).on('click', '.unassign_member', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    Swal.fire({
        title: "Are you sure?",
        text: "You want to remove assigned member!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        width: 600,
        confirmButtonColor: '#26a69a',
        cancelButtonColor: '#ff7043',
        allowOutsideClick: false,
    }).then(function (result) {
        if (result.isConfirmed) {
            jQuery.ajax({
                type: "POST",
                data: { 'id': id },
                url: base_url + 'team/remove_assigned_member',
                success: function (response) {
                    var json = jQuery.parseJSON(response);
                    if (!json.status) {
                        Swal.fire({
                            title: "Error!",
                            text: json.error,
                            icon: "error",
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            title: "Success!",
                            text: "Member unassigned!",
                            icon: "success",
                            timer: 2000
                        });
                        let tr = document.querySelector('#tbl-'+atob(id));
                        tr.parentNode.removeChild(tr);
                    }
                }
            });
        }
    });
});

jQuery(document).on('click', '.delete_team_member', function(e){
    e.preventDefault();
    let tid = $(this).data('id');
    jQuery.ajax({
        type: "POST",
        url: base_url + "team/is_assigned",
        data: {'id' : tid},
        success: function (response) {
            let json = jQuery.parseJSON(response);
            if(json.status){
                Swal.fire({
                    title: '<i class="fa fa-warning fa-2xl text-warning"></i>',
                    text: 'This Member is assigned to Live Chat. Please unassigned from Live chat to remove',
                    showConfirmButton: false,
                    timer: 3000
                });
            }else{
                delete_member(tid);
            }
        }
    });
});


function delete_member(id) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this member!",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
             jQuery.ajax({
                type: "POST",
                url: base_url + "team/delete",
                data: {'id' : id},
                success: function (response) {
                    let json = jQuery.parseJSON(response);
                    let userMessage = document.querySelector('.userMessage');
                    if(json.status){
                        
                        var msg = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Success! </strong> Member successfully deleted!</button></div>';
                        userMessage.innerHTML = msg;
                         member_dttble.ajax.reload(null, false);
                    }else{
                        var msg = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Failed! </strong>'+json.msg+'</button></div>';
                        userMessage.innerHTML = msg;
                    }
                    setTimeout(function () {
                        userMessage.innerHTML = '';
                    }, 2000);
                }
            });
        }
    });
}