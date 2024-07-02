var clients_dttble = $('#clients_dttble').DataTable({
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
        "url": base_url + 'clients/list_clients',
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
        },
        {
            data: "is_deleted",
            visible: true,
            searchable: false,
            sortable: false,
            render: function (data, type, full, meta) {

                var action = '<td><ul class="table-controls">';
                action += '<li><a href="' + base_url + 'clients/edit/' + btoa(full.id) + '" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Edit" title=""><i class="flaticon-edit p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_users(' + full.id + ')"  title="" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="flaticon-delete p-1 br-6 mb-1"></i></a></li>';
                action += '</ul></td>';
                return action;
            }
        }
    ]
});

//$('.dataTables_length select').select2({
//    minimumResultsForSearch: Infinity,
//    width: 'auto'
//});

$(document).find('.btn-add-client').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});
$(document).find('.btn-add-multiple-client').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});
$(document).find('.btn-file-download').on('click', function (event) {
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

window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('add_client');
    var invalid = $('.add_client .invalid-feedback');

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
        text: "You won't be able to revert this Client!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "clients/action/delete/" + id;
        }
    })
}

function addDate(date, id) {
    $(document).find('#' + id).val(date);
}

