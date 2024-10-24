var clients_dttble = $('#recurrings_dttble').DataTable({
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
                action += '<li><a href="' + base_url + 'recurrings/edit/' + btoa(full.id) + '" class="btn btn-outline-primary bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Edit" title=""><i class="fa fa-pencil"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_recurring(' + full.id + ')"  title="" class="btn btn-outline-danger bs-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash mb-1"></i></a></li>';
                action += '</ul></td>';
                return action;
            }
        }
    ]
});

var recurring_logs_dttble = $('#recurring_logs_dttble').DataTable({
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
        "url": base_url + 'recurrings/list_recurring_logs',
    },
    columns: [
        {
            data: "sr_no",
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
            data: "trigger_type",
            visible: true,
            render: function (data, type, full, meta) {
                let tType =full.trigger_type;
                if (!tType) return tType;
                return tType.charAt(0).toUpperCase() + tType.slice(1).toLowerCase();
            }
        },
        {
            data: "trigger_time",
            visible: true
        },
        {
            data: "created",
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
                if(messageStatus == 'failed'){
                    mStatus +='<span class="badge badge-light-danger">'+messageStatus.toUpperCase()+'</span>';
                }else if(messageStatus == 'delivered' || messageStatus == 'read'){
                    mStatus +='<span class="badge badge-light-success">'+messageStatus.toUpperCase()+'</span>';
                }else if(full.message_status == 'accepted'){
                    mStatus +='<span class="badge badge-light-info">'+messageStatus.toUpperCase()+'</span>';
                }
                return mStatus;
            }
        }
    ]
});


$(document).find('.btn-add-inquiry').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

var inputTags = document.querySelector('input[name=name]');
if(inputTags){
    var inputTag = new Tagify(inputTags, {
        maxTags : 1,
        enforceWhitelist: true,
        dropdown: {
            closeOnSelect: false,
            maxItems: Infinity,
            enabled: 0,
            classname: 'users-list',
            searchKeys: ['name','phone_number_full']
        },
        templates: {
            tag: tagTemplate,
            dropdownItem: suggestionItemTemplate
        },
        whitelist: (contactsArr != '') ? JSON.parse(contactsArr) : ''
    });
    
    inputTag.on('dropdown:show dropdown:updated', onDropdownShow);
    
}

function tagTemplate(tagData){
    return `<tag title="${tagData.name}"
                contenteditable='false'
                spellcheck='false'
                tabIndex="-1"
                class="tagify__tag ${tagData.class ? tagData.class : ""}"
                ${this.getAttributes(tagData)}>
            <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
            <div>
                <span class='tagify__tag-text'>${tagData.name}</span>
            </div>
        </tag>`;
}

function suggestionItemTemplate(tagData){
    return `<div ${this.getAttributes(tagData)}
            class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'
            tabindex="0"
            role="option">
            <strong>${tagData.name}</strong><br/>
            <small>${tagData.phone_number_full}</small>
            <hr class="mb-1 mt-1"/>
        </div>`;
}

function onDropdownShow(e){
    e.detail.tagify.DOM.dropdown.content;
}


$("#hPhoneNo").inputmask({mask: "9999999999"});

const input = document.querySelector("#hPhoneNo");
if(input){
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
}

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
    new swal({
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

new TomSelect("#template_id",{
    create: false
});

new TomSelect("#weekly_day",{
    create: false
});

new TomSelect("#monthly_date",{
    create: false
});

$(document).ready(function () {
    $(document).find('#description').emojioneArea({
//    saveEmojisAs: 'shortname'
    });

    flatpickr(document.getElementById('hTime'), {
        enableTime: true,
        noCalendar: true,   
        dateFormat: "H:i",
        defaultDate: flatTriggerTime
    });
    flatpickr(document.getElementById('yearly_date'), {
        enableTime: false,   
        dateFormat: "d M, Y",
        defaultDate: new Date()
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
    let tempPreview = document.querySelector('#template_preview');
    let tempPreviewOther = document.querySelector('.other_template_div');
    //$(document).find('.other_template_div').addClass('hide');
    //$(document).find('.automation_details').addClass('hide');
    var temp_id = $(this).find(":selected").val();
    if (temp_id == 'other') {
        if(tempPreviewOther.classList.contains('hide')){
           tempPreviewOther.classList.remove('hide') ;
        }
        tempPreview.classList.add('hide');
        //$(document).find('.other_template_div').removeClass('hide');
    } else {
        if(tempPreview.classList.contains('hide')){
           tempPreview.classList.remove('hide') ;
        }
        if(tempPreviewOther){
            if(tempPreviewOther.classList.contains('hide')){
                tempPreviewOther.classList.add('hide');
            }
        }
        get_template_details(temp_id);
    }
});

function get_template_details(temp_id) {
    var tempPreview = document.querySelector('#template_preview');
    tempPreview.innerHTML = '';
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        //url: base_url + 'templates/get_template_details/' + btoa(temp_id) + '/' + btoa(seq),
        url: base_url + 'clients/get_single_template_preview/' + btoa(temp_id),
        success: function (result) {
            tempPreview.innerHTML = result.response;
            tempPreview.classList.add('automation_template_div');
            
            /*$(document).find('.automation_details').removeClass('hide');
            $(document).find('#automation_template_name_' + seq).html('').html(result.name);
            $(document).find('#automation_template_details_div_' + seq).html('').html(result.response);
            setTimeout(function () {
                $(document).find("#temp_image_media_" + seq).change(function () {
                    readURL(this, seq);
                });
            }, 10);*/
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

$(document).on('click', '#btn-save-recurring', function(e){
    e.preventDefault();
    var data = jQuery("#add_recurring_form").serialize();
    let userMessage = document.querySelector('.user-message');
    userMessage.innerHTML = '';
    jQuery.ajax({
        type: "POST",
        url: base_url + "recurrings/save",
        data: data,
        success: function (result) {
            let json = JSON.parse(result);
            if(!json.status){
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
            }else{
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>Recurring has been saved.</button></div>';
                setTimeout(function(){
                    window.location.href = base_url+'recurrings';
                },2000);
            }
            
        }
    });
});