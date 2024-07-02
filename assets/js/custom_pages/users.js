var users_dttble = $('#users_dttble').DataTable({
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
        "url": base_url + 'users/list_users',
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
            data: "phone_number_full",
            visible: true,
        },
        {
            data: "type",
            visible: true,
            render: function (data, type, full, meta) {
                var status = '';
                if (full.type == 'user') {
                    status = '<td><span class="badge outline-badge-primary"">User</span></td>';
                } else {
                    status = '<td><span class="badge outline-badge-success"">Admin</span></td>';
                }
                return status;
            }
        },
        {
            data: "created_at",
            visible: true,
            searchable: false,
        },
        {
            data: "is_deleted",
            visible: true,
            searchable: false,
            sortable: false,
            render: function (data, type, full, meta) {

                var action = '<td><ul class="table-controls">';
                action += '<li><a href="' + base_url + 'users/edit/' + btoa(full.id) + '" class="btn btn-outline-primary btn-icon " data-toggle="tooltip" data-placement="top" data-original-title="Edit" title=""><i class="fa fa-pencil-alt"></i></a></li>';
                action += '<li class="ml-3"><a href="javascript:void(0)" onclick="delete_users(' + full.id + ')"  title="" class="btn btn-outline-danger btn-icon " data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash"></i></a></li>';
                if (full.type == 'user') {
                    action += '<li class="ml-3"><a href="' + base_url + 'users/clients/' + btoa(full.id) + '" title="" class="btn btn-outline-warning btn-icon " data-toggle="tooltip" data-placement="top" data-original-title="Clients"><i class="fa fa-users"></i></a></li>';
                    action += '<li class="ml-3"><a href="' + base_url + 'users/settings/' + btoa(full.id) + '" title="" class="btn btn-outline-secondary btn-icon " data-toggle="tooltip" data-placement="top" data-original-title="Settings"><i class="fa fa-gear"></i></a></li>';
                }
                action += '</ul></td>';
                return action;
            }
        }
    ]
});

$(document).find('.btn-add-user').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

$("#hEmail").inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: !1, onBeforePaste: function (m, a) {
        return(m = m.toLowerCase()).replace("mailto:", "")
    },
    definitions: {"*": {
            validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~-]",
            cardinality: 1,
            casing: "lower"
        }
    }
});
$("#hPhoneNo").inputmask({mask: "9999999999"});



window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('add_user');
    var invalid = $('.add_user .invalid-feedback');

    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                invalid.css('display', 'block');
            } else {

                invalid.css('display', 'none');
                form.classList.add('was-validated');
            }
        }, false);
    });

}, false);

function delete_users(id) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this User!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "users/action/delete/" + id;
        }
    })
}

function block_users(id, type) {
    new swal({
        title: "Are you sure?",
        text: "You would like to " + type + " this User!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, " + type + " it!",
        cancelButtonText: "No, cancel plz!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
            function (isConfirm) {
                if (isConfirm) {
                    window.location.href = base_url + "users/action/" + type + "/" + id;
                } else {
                    new swal("Cancelled", "Your User is safe :)", "error");
                }
            });
}

var client_user_id = (client_user_id != undefined) ? client_user_id : '';
var user_clients_dttble = $('#user_clients_dttble').DataTable({
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
        "url": base_url + 'users/list_user_clients/' + client_user_id,
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
            visible: true,
        },
        {
            data: "cust_birth_date",
            visible: true,
        },
        {
            data: "cust_anniversary_date",
            visible: true,
        },
        {
            data: "created_at",
            visible: true,
        }
    ]
});

//$('.dataTables_length select').select2({
//    minimumResultsForSearch: Infinity,
//    width: 'auto'
//});
const input = document.querySelector("#hPhoneNo");
if(input !== null){
    const iti = window.intlTelInput(input, {
        initialCountry: "in",
        separateDialCode: true,
        hiddenInput: "phone_number_full",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });

    input.addEventListener('input', function () {
        var countryCode = iti.getSelectedCountryData().dialCode;
        console.log(countryCode);
        $(document).find('#country_code').val(countryCode);
    });
}


/*$(function () {
    $('.timepicker').datetimepicker({
        format: 'h:mm A', //use this format if you want the 12hours timpiecker with AM/PM toggle
        icons: {
            time: "now-ui-icons tech_watch-time",
            date: "now-ui-icons ui-1_calendar-60",
            up: "flaticon-arrows-1",
            down: "flaticon-down-arrow",
            previous: 'now-ui-icons arrows-1_minimal-left',
            next: 'now-ui-icons arrows-1_minimal-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
        },
    });
});*/

$('#message_on_inquiry').on('change', function(e){
    e.preventDefault();
    var checkbox = document.getElementById("message_on_inquiry");
    if (checkbox.checked) {
        $('.message_on_inquiry_block').removeClass('hide'); 
    } else {
        $(document).find('.inquiry_template_block').html('');
        $('.message_on_inquiry_block').addClass('hide');
    }
});

$('#inquiry_template').on('change', function(e){
    e.preventDefault();
    var temp_id = $(this).val();
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: base_url + 'templates/get_template_details/' + btoa(temp_id) + '/'+ btoa(1),
        success: function (result) {
            $(document).find('.inquiry_template_block').removeClass('hide');
            $(document).find('.inquiry_template_block').html('').html(result.response)
        }
    });
});

$('#forward_inquiry').on('change', function(e){
    e.preventDefault();
    var checkbox = document.getElementById("forward_inquiry");
    if (checkbox.checked) {
        $('.forward_inquiry_block').removeClass('hide'); 
    } else {
        $(document).find('.forward_inquiry_template_block').html('');
        $('.forward_inquiry_block').addClass('hide');
    }
});

$('#forward_inquiry_template').on('change', function(e){
    e.preventDefault();
    var temp_id = $(this).val();
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: base_url + 'templates/get_template_details/' + btoa(temp_id),
        success: function (result) {
            $(document).find('.forward_inquiry_template_block').removeClass('hide');
            $(document).find('.forward_inquiry_template_block').html('').html(result.response)
        }
    });
});

function get_template(){
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: base_url + 'templates/get_templates',
        cache: "false",
        success: function (result) {
            $('.templates_list').html(result);
        }
    });
}



