var clients_dttble = $('#inquiries_dttble').DataTable({
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
        "url": base_url + 'inquiries/list_inquiries',
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
            data: "phone_number_full",
            visible: true,
        },
        {
            data: "inquiry_type_name",
            visible: true,
        },
        {
            data: "automation_name",
            visible: true,
            render: function (data, type, full, meta) {
                var str = '';
                str += '<a class="text-info" href="' + base_url + 'automations/view/' + btoa(full.automation_id) + '">' + data + '</a>'
                return str;
            }
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
                action += '<li><a href="' + base_url + 'inquiries/edit/' + btoa(full.id) + '" class="btn btn-outline-primary bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Edit" title=""><i class="fa fa-pencil"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_inquiry(' + full.id + ')"  title="" class="btn btn-outline-danger bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash"></i></a></li>';
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

$(document).find('.btn-add-inquiry').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});
$(document).find('.btn-add-multiple-inquiry').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});
$(document).find('.btn-inquiry-file-download').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
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
    var forms = document.getElementsByClassName('add_inquiry');
    var invalid = $('.add_inquiry .invalid-feedback');

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

function delete_inquiry(id) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Inquiry!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "inquiries/action/delete/" + btoa(id);
        }
    })
}

function addDate(date, id) {
    $(document).find('#' + id).val(date);
}

$(".basic").select2({
    tags: true
});