var automations_dttble = $('#automations_dttble').DataTable({
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
    order: [[5, "desc"]],
    ajax: {
        'type': 'GET',
        "url": base_url + 'automations/list_automations',
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
            visible: true,
            width: '40%'
        },
        {
            data: "trigger_time",
            visible: true,
        },
        {
            data: "automation_id",
            visible: true,
        },
        {
            data: "total_inquiries",
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
                var is_template_default = (full.is_default != undefined && full.is_default != null) ? parseInt(full.is_default) : 0;
                var action = '<td>';
                action += '<ul class="table-controls">';
                action += '<li><a href="' + base_url + 'automations/view/' + btoa(full.id) + '" class="btn btn-outline-success bs-tooltip p-1" data-bs-placement="top" data-bs-original-title="View"><i class="fa fa-eye p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="' + base_url + 'automations/edit/' + btoa(full.id) + '" class="btn btn-outline-primary bs-tooltip p-1" data-bs-placement="top" data-bs-original-title="Edit"><i class="fa fa-pencil p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_automations(' + full.id + ')"  title="Delete" class="btn btn-outline-danger bs-tooltip p-1" data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash p-1 br-6 mb-1"></i></a></li>';
                action += '</ul></td>';
                return action;
            }
        }
    ], drawCallback: function () {
    }
});

var automation_logs_dttble = $('#automation_logs_dttble').DataTable({
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
        "url": base_url + 'automations/get_automation_logs',
    },
    columns: [
        {
            data: "sr_no",
            visible: true,
            searchable: false,
            sortable: false
        },
        {
            data: "name",
            visible: true
        },
        {
            data: "phone_number_full",
            visible: true
        },
        {
            data: "notification_date",
            visible: true
        },
        {
            data: "automation_name",
            visible: true
        },
        {
            data: "template_name",
            visible: true
        },
        {
            data: "message_status",
            visible: true,
            searchable: false,
            sortable: false,
            render: function (data, type, full, meta) {
                let mStatus = '';
                let messageStatus = full.message_status;
                if(full.error_response !== null || messageStatus == 'failed'){
                    mStatus +='<span class="badge badge-light-danger">FAILED</span>';
                }else if(messageStatus == 'delivered' || messageStatus == 'read'){
                    mStatus +='<span class="badge badge-light-success">'+messageStatus.toUpperCase()+'</span>';
                }
                return mStatus;
            }
        },
        {
            data: "sent_at",
            visible: true
        }
    ]
});

$(document).find('.btn-add-automation').on('click', function (e) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('add_automation');
    var invalid = $('.add_automation .invalid-feedback');

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

function delete_automations(id) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Automation!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "automations/action/delete/" + btoa(id);
        }
    })
}

$(document).find('#description').emojioneArea();

$(document).on('click', '.delete_automation', function (event) {
    event.preventDefault();
    var seq = $(this).data('id');
    $(document).find('#automation_details_div_' + seq).remove();
    $(document).find('#automation_template_div_' + seq).remove();
});

$(document).find('.btn-add-message').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.automation_details_div:last').data('seq');
    count = (count == undefined) ? 0 : count;
    var seq = (count + 1);
    var automation_templates = $(document).find('#automation_templates').val();
    var templates = $.parseJSON(automation_templates);
    var options = '';
    options += '<option value="">Select Template</option>';
    $.each(templates, function (key, value) {
        options += '<option value="' + value.id + '">' + value.name + '</option>';
    });
    var html = '<div class="automation_details_div" id="automation_details_div_' + seq + '"  data-seq="' + seq + '">' +
                '<div class="col-12 mt-2">'+
                '<div class="form-group row">' +
                '<label for="template_' + seq + '">Select Template</label>' +
                '<div class="col-xl-8 col-lg-8 col-sm-8 col-10">' +
                '<select class="form-control basic template" id="template_' + seq + '" name= "templates[' + seq + ']">' +
                options +
                '</select>' +
                '</div>' +
                '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' +
                '<div class="actions  align-self-center">' +
                '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_automation" data-id="' + seq + '">' +
                '<i class="fa fa-trash"></i>' +
                '</a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
    html += '<div class="automation_template_div hide" id="automation_template_div_' + seq + '" data-seq="' + seq + '">' +
            '<div class="col-12 mt-2">'+
            '<div class="form-group row mb-4">' +
            '<label class="automation_template_name" id="automation_template_name_' + seq + '"></label>' +
            '<div class="col-xl-12 col-lg-12 col-sm-12 col-12">' +
            '<div class="automation_template_details_div" id="automation_template_details_div_' + seq + '" data-seq="' + seq + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    $(document).find('.automation_details').append(html);
    setTimeout(function () {
        $(document).find(".basic").select2({
            tags: true
        });
    }, 10);

    add_change_event(seq);
//    $(document).find('#template_' + seq).change(function (event) {
//        event.preventDefault();
//        var temp_id = $(this).find(":selected").val();
//        get_template_details(temp_id, seq);
//    });
});

function add_change_event(seq) {
    $(document).find('#template_' + seq).change(function (event) {
        event.preventDefault();
        var temp_id = $(this).find(":selected").val();
        get_template_details(temp_id, seq);
    });
}

$(document).find(".basic").map(function () {
    var template_id = $(this).attr('id');
    var seq = template_id.split("_")[1];
    if (seq != undefined && seq > 0) {
        add_change_event(seq);
    }
});

function get_template_details(temp_id, seq) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        //url: base_url + 'templates/get_template_details/' + btoa(temp_id) + '/' + btoa(seq),
        url: base_url + 'clients/get_single_template_preview/' + btoa(temp_id) + '/' + btoa(seq),
        success: function (result) {
//            console.log(result);
            $(document).find('#automation_template_div_' + seq).removeClass('hide');
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

function readURL(input, seq = 0) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(document).find('.media_preview_div_' + seq).removeClass('hide');
            $(document).find('#template_preview_holder_' + seq).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $(document).find('.media_preview_div_' + seq).addClass('hide');
        $(document).find('#template_preview_holder_' + seq).attr('src', '');
}
}

$(document).find('.btn-add-delay').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.automation_details_div:last').data('seq');
    count = (count == undefined) ? 0 : count;
    var seq = (count + 1);

    var html = '<div class="automation_details_div" id="automation_details_div_' + seq + '" data-seq="' + seq + '">' +
                '<div class="col-12 mt-2">'+
                '<div class="form-group row mb-4 delay_div">' +
                '<label for="delay_' + seq + '">Select Delay</label>' +
                '<div class="col-xl-4 col-lg-4 col-sm-4 col-4">' +
                '<input type="text" class="form-control delay_count" id="delay_count_' + seq + '" name="delay_count[' + seq + ']">' +
                '</div>' +
                '<div class="col-xl-4 col-lg-4 col-sm-4 col-4">' +
                '<select class="form-control delay_duration" name="delay_duration[' + seq + ']" id="delay_duration_' + seq + '">' +
                '<option selected="selected">Minutes</option>' +
                '<option>Hours</option>' +
                '<option>Days</option>' +
                '<option>Weeks</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' +
                '<div class="actions align-self-center">' +
                '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_automation" data-id="' + seq + '">' +
                '<i class="fa fa-trash"></i>' +
                '</a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
    $(document).find('.automation_details').append(html);
});

$(".basic").select2({
    tags: true
});

var hTime = document.getElementById('hTime');
if(hTime){
    flatpickr(hTime, {
        enableTime: true,
        noCalendar: true,   
        dateFormat: "H:i",
        //defaultDate: new Date(),
        minuteIncrement: 1
    });
}
