var users_dttble = $('#users_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [5, 10, 20, 50, 100],
    "language": {
        "paginate": {
            "previous": "<i class='flaticon-arrow-left-1'></i>",
            "next": "<i class='flaticon-arrow-right'></i>"
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
                action += '<li><a href="' + base_url + 'users/edit/' + btoa(full.id) + '" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Edit" title=""><i class="flaticon-edit p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_users(' + full.id + ')"  title="" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="flaticon-delete p-1 br-6 mb-1"></i></a></li>';
                if (full.type == 'user') {
                    action += '<li><a href="' + base_url + 'users/clients/' + btoa(full.id) + '" title="" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Clients"><i class="flaticon-user-group-1 p-1 br-6 mb-1"></i></a></li>';
                    action += '<li><a href="' + base_url + 'users/settings/' + btoa(full.id) + '" title="" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Settings"><i class="flaticon-gear-4 p-1 br-6 mb-1"></i></a></li>';
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
    swal({
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
    swal({
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
                    swal("Cancelled", "Your User is safe :)", "error");
                }
            });
}

var client_user_id = (client_user_id != undefined) ? client_user_id : '';
var user_clients_dttble = $('#user_clients_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [5, 10, 20, 50, 100],
    "language": {
        "paginate": {
            "previous": "<i class='flaticon-arrow-left-1'></i>",
            "next": "<i class='flaticon-arrow-right'></i>"
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
            data: "phone_number_full",
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

const input = document.querySelector("#hPhoneNo");
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