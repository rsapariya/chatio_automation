var tags_dttble = $('#tags_dttble').DataTable({
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
        "url": base_url + 'tag/get_tags'
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
            data: "tag",
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
                action += '<li><a href="javascript:void(0);" data-id="'+ btoa(full.id) +'" id="edit_tag" class="btn btn-outline-primary bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Edit"><i class="fa fa-pencil-alt p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0);" onclick="is_assign(' + full.id + ')"  class="btn btn-outline-danger bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash p-1 br-6 mb-1"></i></a></li>';
                action += '</ul></td>';
                return action;
            }
        }
    ]
});

jQuery(document).on('click', "#add_tag", function (event) {
    $(".tags_model_block").load(base_url + 'tag/manage', function () {
        $(document).find('#tags_model').modal('show');

    });
});

jQuery(document).on('click', "#edit_tag", function (event) {
    var id = $(this).data('id');
    $(".tags_model_block").load(base_url + 'tag/manage/'+id, function () {
        $(document).find('#tags_model').modal('show');

    });
});


jQuery(document).on('click', '.save-tag', function (e) {
    e.preventDefault();
    var data = jQuery("#tag_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "tag/save",
        data: data,
        success: function (response) {
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                var msg = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Failed! </strong>'+json.msg+'</button></div>';
                $('#tag_frm .user-message').html(msg);
                $('#tag_frm .user-message').show();
                setTimeout(function () {
                    $('#tag_frm .user-message').html('');
                    $('#tag_frm .user-message').hide();
                }, 2000);
            } else {
                var msg = '<div class="alert alert-light-primary alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Success!</strong> Tag saved successfully.</button></div>';
                $('#tag_frm .user-message').html(msg);
                $('#tag_frm .user-message').show();
                setTimeout(function () {
                    $('#tag_frm .user-message').html('');
                    $('#tag_frm .user-message').hide();
                    $(document).find('#tags_model').modal('hide');
                    tags_dttble.ajax.reload(null, false);
                }, 2000);
            }
        }
    });
});

function is_assign(id){
    jQuery.ajax({
        type: "POST",
        url: base_url + "tag/is_assign",
        data: {'id' : id},
        success: function (response) {
            var json = jQuery.parseJSON(response);
            delete_tag(json.status, id)
        }
    });
}
function delete_tag(status, id) {
    let swalHTML = '';
    if(status === true){
        swalHTML += `<p class="mb-2">This tag is assigned to contacts. You won't be able to revert this Tag!<p>
                <div class="text-start p-3 bg-light-info"><small class="mt-2 mb-1 text-danger">select any option!</small><br/><label><input type="radio" name="remove_contact" value="tag" /> Delete tag only. </label><br>
                <label><input type="radio" name="remove_contact" value="contact" /> Delete all contacts with this tag also.</label></div>
                <p clas="mt-2"><b>Note:</b> Some contacts may have multiple tags.</P>`;
    
    }else{
        swalHTML += `<p class="mb-0">You won't be able to revert this Tag!<p>`;
    }
    new swal({
        title: "Are you sure?",
        html: swalHTML,
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
        didOpen: function () {
            if (status === true) {
                document.querySelector('.swal2-confirm').setAttribute('disabled', 'true');
                document.querySelectorAll('input[name="remove_contact"]').forEach(input => {
                    input.addEventListener('change', function () {
                        document.querySelector('.swal2-confirm').removeAttribute('disabled');
                    });
                });
            }
        }
    }).then(function (result) {
        if (result.value) {
            let data;
            if(status === true){
                const selectedOption = document.querySelector('input[name="remove_contact"]:checked');
                if (selectedOption.value === 'contact') {
                    data = {'id': id, 'delete': 'contact'};
                } else {
                    data = {'id': id, 'delete': 'tag'};
                }
            }else{
                data = {'id': id, 'delete': 'tag'};
            }
            if(data){
                jQuery.ajax({
                    type: "POST",
                    url: base_url + "tag/delete_tag",
                    data: data,
                    success: function (response) {
                        var json = JSON.parse(response);
                        if (!json.status) {
                            swal.fire({
                                title: "Error!",
                                text: json.error,
                                icon: "error",
                                confirmButtonText: "Ok"
                            }).then(function() {
                                setTimeout(function() {
                                    Swal.close();
                                }, 2000);
                            });
                        } else {
                            tags_dttble.ajax.reload(null, false);
                            swal.fire({
                                title: "Success!",
                                text: json.success,
                                icon: "successs",
                                confirmButtonText: "Ok"
                            }).then(function() {
                                setTimeout(function() {
                                    Swal.close();
                                }, 2000);
                            });
                        }
                        
                    }
                });
            }
        }
    });
}