var clients_dttble = $('#recurrings_dttble').DataTable({
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
        "url": base_url + 'recurrings/list_recurrings',
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
            data: "description",
            visible: true,
            render: function (data, type, full, meta) {
                var description = data;
                if (full.template_id != '' && full.template_id != null && full.template_id != 'other') {
                    description = '<a href="javascript:void(0)" class="text-success" onclick="view_templates(' + full.template_id + ')"  title="View"><b>' + full.template_name + '</b></a>';
                }
                return description;
            }
        },
        {
            data: "trigger_time",
            visible: true,
            render: function (data, type, full, meta) {
                var text = '';
                if (full.trigger_type == 'weekly') {
                    text += '<br/> Week Day : ' + full.weekly_day;
                } else if (full.trigger_type == 'monthly') {
                    text += '<br/> Month Date : ' + full.monthly_date;
                } else if (full.trigger_type == 'yearly') {
                    text += '<br/> Yearly Date : ' + full.yearly_date;
                } else {
                    text += 'Daily';
                }
                return full.trigger_time + ' ' + text;
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
                action += '<li><a href="' + base_url + 'recurrings/edit/' + btoa(full.id) + '" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Edit" title=""><i class="flaticon-edit p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_recurring(' + full.id + ')"  title="" class="bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="flaticon-delete p-1 br-6 mb-1"></i></a></li>';
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

function delete_recurring(id) {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Recurring!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "recurrings/action/delete/" + btoa(id);
        }
    })
}

function addDate(date, id) {
    $(document).find('#' + id).val(date);
}

$(".basic").select2({
    tags: true
});
$(".weekly_day").select2({
    tags: true
});
$(".monthly_date").select2({
    tags: true
});



$(document).ready(function () {
    $(document).find('#description').emojioneArea({
//    saveEmojisAs: 'shortname'
    });

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
    $(document).find('.recurring-cls').change(function () {
        var value = $(this).val();
        if (value == 'weekly') {
            $(document).find('.trigger_yearly_div').addClass('hide');
            $(document).find('.trigger_weekly_div').removeClass('hide');
            $(document).find('.trigger_monthly_div').addClass('hide');
        } else if (value == 'monthly') {
            $(document).find('.trigger_yearly_div').addClass('hide');
            $(document).find('.trigger_weekly_div').addClass('hide');
            $(document).find('.trigger_monthly_div').removeClass('hide');
        } else if (value == 'yearly') {
            $(document).find('.trigger_yearly_div').removeClass('hide');
            $(document).find('.trigger_weekly_div').addClass('hide');
            $(document).find('.trigger_monthly_div').addClass('hide');
        } else {
            $(document).find('.trigger_yearly_div').addClass('hide');
            $(document).find('.trigger_weekly_div').addClass('hide');
            $(document).find('.trigger_monthly_div').addClass('hide');
        }
    });
})

$(document).find('#template_id').change(function (event) {
    event.preventDefault();
    $(document).find('.other_template_div').addClass('hide');
    $(document).find('.automation_details').addClass('hide');
    var temp_id = $(this).find(":selected").val();
    if (temp_id == 'other') {
        $(document).find('.other_template_div').removeClass('hide');
    } else {
        get_template_details(temp_id, 1);
    }
});

function get_template_details(temp_id, seq) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: base_url + 'templates/get_template_details/' + btoa(temp_id) + '/' + btoa(seq),
        success: function (result) {
            console.log(result);
            $(document).find('.automation_details').removeClass('hide');
            $(document).find('#automation_template_name_' + seq).html('').html(result.name);
            $(document).find('#automation_template_details_div_' + seq).html('').html(result.response);
            setTimeout(function () {
                $(document).find("#temp_image_media_" + seq).change(function () {
                    readURL(this, seq);
                });
            }, 10);
        }
    });
}

function view_templates(id) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: base_url + 'templates/get_template_details/' + btoa(id),
        success: function (result) {
            $(document).find('.template_name').html('').html(result.name);
            $(document).find('.template_details').html('').html(result.response);
            setTimeout(function () {
                $(document).find('#modal_view_template').modal('show');
            }, 500);
        }
    });
}