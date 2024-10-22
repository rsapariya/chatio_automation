var templates_dttble = $('#templates_automation_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [10, 20, 50, 100],
    "language": {
        "paginate": {
            "first": "<i class='fa fa-angles-left'></i>",
            "previous": "<i class='fa fa-angle-left'></i>",
            "next": "<i class='fa fa-angle-right'></i>",
            "last": "<i class='fa fa-angles-right'></i>"
        },
        "info": "Showing page _PAGE_ of _PAGES_"
    },
    order: [[3, "desc"]],
    ajax: {
        'type': 'GET',
        "url": base_url + 'templates/list_templates/automation',
    },
    columns: [
        {
            data: "sr_no",
            visible: true,
            searchable: false,
            orderable: false,
        },
        {
            data: "name",
            visible: true,
            render: function (data, type, full, meta) {
                var res = '';
                if (full.temp_id != '' && full.temp_id != null) {
                    res = '<a href="Javascript:(0);" onClick="view_templates(' + full.id + ')" class="bs-tooltip" data-bs-placement="top" data-bs-original-title="View Description"><img src="' + base_url + 'assets/img/meta.ico" width="17px" > ';
                    res += data + ' (' + full.temp_language + ')</a>';
                }
                if (full.custom_type != '' && full.custom_type != null) {
                    res = data + ' (' + full.custom_type + ')';
                }

                return res;
            }
        },
        {
            data: "automation_image",
            visible: true,
            orderable: false,
            render: function (data, type, row, meta) {
                var image = '';
                if (data != '' && data != null) {
                    var image_name = data;
                    var iarray = image_name.split('.');
                    if (iarray[1] != undefined && iarray[1] != '') {
                        if (iarray[1] == 'pdf') {
                            image = '<a class="text-primary" href="' + base_url + '' + DEFAULT_IMAGE_UPLOAD_PATH + '' + data + '" target="_blank"/>' + data + '</a>';
                        } else {
                            image = '<img src="' + base_url + '' + DEFAULT_IMAGE_UPLOAD_PATH + '' + data + '"/>';

                        }
                    }
                }
                return image
            },
        },
        {
            data: "created_at",
            visible: true,
        },
        {
            data: "is_deleted",
            visible: true,
            searchable: false,
            orderable: false,
            render: function (data, type, full, meta) {
                var action = '';
                action += '<td><ul class="table-controls">';
                if (full.temp_id != '' && full.temp_id != null) {
                    action += '<li><a href="javascript:void(0)" onclick="view_templates(' + full.id + ')" class="btn btn-outline-success btn-icon bs-tooltip p-1" data-bs-placement="top" data-bs-original-title="View"><i class="fa fa-eye p-1 br-6 mb-1"></i></a></li>';
                } else {
                    var edit_url = 'edit';
                    if (full.custom_type != '' && full.custom_type != null) {
                        edit_url = 'edit_custom';
                    }
                    action += '<li><a href="' + base_url + 'templates/' + edit_url + '/' + btoa(full.id) + '" class="btn btn-outline-primary btn-icon bs-tooltip p-1" data-bs-placement="top" data-bs-original-title="Edit"><i class="fa fa-pencil p-1 br-6 mb-1"></i></a></li>';
                    action += '<li><a href="javascript:void(0)" onclick="delete_templates(' + full.id + ')" class="btn btn-outline-danger btn-icon bs-tooltip p-1" data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash p-1 br-6 mb-1"></i></a></li>';
                }
                action += '</ul></td>';
                return action;
            }
        }
    ], drawCallback: function () {
        //$('.t-dot').tooltip({ template: '<div class="tooltip status" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' })
    }
});
//
//$('.dataTables_length select').select2({
//    minimumResultsForSearch: Infinity,
//    width: 'auto'
//});

$(document).find('.btn-add-template').on('click', function (e) {
    e.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

$(document).find('.btn-add-custom-template').on('click', function (e) {
    e.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

$(document).find('.btn-add-action').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.div_template_action:last').data('seq');
    //console.log(count);
    count = (count == undefined) ? 0 : count;
    //console.log(count);
    var seq = (count + 1);
    if (count < 10) {
        var html = '<div class="div_template_action mt-3" id="div_template_action_' + seq + '" data-seq="' + seq + '">' +
            '<div class="row">' +
            '<div class="col-4">' +
            '<input type="text" class="form-control" id="title_' + seq + '" name="title[' + seq + ']" placeholder="title ' + seq + '" value="" maxlength="20" required><small>Maximum 20 character</small>' +
            '<div class="invalid-feedback">Please fill the Action Title</div>' +
            '</div>' +
            '<div class="col-7">' +
            '<input type="text" class="form-control" id="description_' + seq + '" name="description[' + seq + ']" placeholder="description ' + seq + '" value="" required>' +
            '<div class="invalid-feedback">Please fill the Action Description</div>' +
            '</div>' +
            '<div class="col-1">' +
            '<button type="button" class="btn btn-danger btn-remove-action" id="btn_remove_action_' + seq + '" data-id="' + seq + '"><i class="fa fa-minus"></i></button> ' +
            '</div>' +
            '</div>' +
            '</div>';
        $(document).find('.div_template_action_main').append(html);
        remove_change_event();
    }
});

remove_change_event();
function remove_change_event() {
    $(document).find('.btn-remove-action').on('click', function (event) {
        event.preventDefault();
        var seq = $(this).data('id');
        $(document).find('#div_template_action_' + seq).remove();
    });
}

$(document).find('.btn-add-btn-action').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.div_template_btn_action:last').data('seq');
    count = (count == undefined) ? 0 : count;
    var seq = (count + 1);
    if (count < 10) {
        var html = '<div class="div_template_btn_action mt-3" id="div_template_btn_action_' + seq + '" data-seq="' + seq + '">' +
            '<div class="row">' +
            '<div class="col-4">' +
            '<input type="text" class="form-control" id="title_' + seq + '" name="btn_title[' + seq + ']" placeholder="title ' + seq + '" value="" maxlength="20" required><small>Maximum 20 character</small>' +
            '<div class="invalid-feedback">Please fill the Action Title</div>' +
            '</div>' +
            '<div class="col-7">' +
            '</div>' +
            '<div class="col-1">' +
            '<button type="button" class="btn btn-danger btn-remove-btn-action" id="btn_remove_btn_action_' + seq + '" data-id="' + seq + '"><i class="fa fa-minus"></i></button> ' +
            '</div>' +
            '</div>' +
            '</div>';
        $(document).find('.div_template_btn_action_main').append(html);
        remove_btn_change_event();
    }
});

remove_btn_change_event();
function remove_btn_change_event() {
    $(document).find('.btn-remove-btn-action').on('click', function (event) {
        event.preventDefault();
        var seq = $(this).data('id');
        $(document).find('#div_template_btn_action_' + seq).remove();
    });
}

$(".vcard_contact_number").inputmask({ mask: "9999999999" });
var input = document.querySelector(".vcard_contact_number");
if (input != null) {
    var iti = window.intlTelInput(input, {
        initialCountry: "in",
        //dialCode : "93",
        separateDialCode: true,
        hiddenInput: "phone_number_full",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });

    /*input.addEventListener('input', function () {
        var countryCode = iti.getSelectedCountryData().dialCode;
        console.log(countryCode);
        $(document).find('#country_code').val(countryCode);
    });*/
}


window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('add_template');
    var invalid = $('.add_template .invalid-feedback');

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

window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('add_custom_template');
    var invalid = $('.add_custom_template .invalid-feedback');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {

            var visibleInputs = $(form).find(':input:visible[required]');
            var isValid = true;
            visibleInputs.each(function () {

                var invalidFeedback = $(this).siblings('.invalid-feedback');

                if (!this.validity.valid) {
                    event.preventDefault();
                    event.stopPropagation();
                    isValid = false;
                    invalidFeedback.css('display', 'block');
                    if ($(this).attr('id') == 'contact_number') {
                        var itiElement = document.querySelector('.iti');
                        var invalidElement = itiElement.nextElementSibling.nextElementSibling;

                        if (invalidElement && invalidElement.classList.contains('invalid-feedback')) {
                            invalidElement.style.display = 'block';
                        }
                    }
                } else {
                    invalidFeedback.css('display', 'none');
                    $(this).siblings('.invalid-feedback').css('display', 'none');
                    if ($(this).attr('id') == 'contact_number') {
                        var itiElement = document.querySelector('.iti');
                        var invalidElement = itiElement.nextElementSibling.nextElementSibling;

                        if (invalidElement && invalidElement.classList.contains('invalid-feedback')) {
                            invalidElement.style.display = 'none';
                        }
                    }
                }
            });

            if (isValid) {
                invalid.css('display', 'none');
                form.classList.add('was-validated');
                // If form is valid, you can proceed with form submission or other actions
            }

        }, false);
    });
}, false);


function view_templates_description(id) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: base_url + 'templates/get_template_description/' + btoa(id),
        success: function (result) {
            var response = '';
            if (result.temp_id != '' && result.temp_id != null) {
                var desc_json = (result.description != '') ? $.parseJSON(result.description) : '';
                if (desc_json != undefined && desc_json != '') {
                    $.each(desc_json, function (key, val) {
                        if (val.type == 'BODY') {
                            response += val.text;
                        }
                        if (val.type == 'FOOTER') {
                            response += val.text;
                        }
                    });
                }
            } else if (result.custom_type != '' && result.custom_type != null) {
                var desc_json = (result.description != '') ? $.parseJSON(result.description) : '';
                if (desc_json != undefined && desc_json != '') {
                    if (desc_json.header_text != undefined && desc_json.header_text != '') {
                        response += desc_json.header_text + '<br/>';
                    }
                    if (desc_json.body_text != undefined && desc_json.body_text != '') {
                        response += desc_json.body_text + '<br/>';
                    }
                    if (desc_json.footer_text != undefined && desc_json.footer_text != '') {
                        response += desc_json.footer_text + '<br/>';
                    }
                    if (desc_json.text_details != undefined && desc_json.text_details != '') {
                        response += desc_json.text_details + '<br/>';
                    }
                    var action_text = '<br/><h6><strong>' + result.custom_type + '</strong></h6>';
                    if (desc_json.actions != undefined && desc_json.actions != '') {
                        $.each(desc_json.actions, function (key, val) {
                            if (result.custom_type == 'list') {
                                action_text += key + ', ' + val.title + ', ' + val.description + '<br/>';
                            } else if (result.custom_type == 'button') {
                                action_text += key + ', ' + val.title + '<br/>';
                            } else if (result.custom_type == 'text') {
                                action_text = '';
                            }
                        });
                        response += action_text;
                    }

                }
            } else {
                var parsedResponse = JSON.parse(result);
                var formattedJsonResponse = JSON.stringify(parsedResponse, null, 2);

                response = '<pre>' + formattedJsonResponse + '</pre>';
            }
            //response = response.replace(/\n/g, '<br>');

            //var formattedJson = JSON.stringify(response, undefined, 2);
            $(document).find('.template_details').html('').html('<code>' + response + '</code>');
            setTimeout(function () {
                $(document).find('#modal_view_template').modal('show');
            }, 500);
        }
    });
}

function delete_templates(id) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Template!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "templates/action/delete/" + btoa(id);
        }
    })
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

function set_default_templates(id, type) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to set this Template!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, set as default!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "templates/action/set_default/" + btoa(id) + "/" + type;
        }
    })
}

//tinymce.init({
//    selector: 'div.textarea',
//    height: 500,
//    menubar: false,
//    plugins: [
//        'advlist autolink lists link image charmap print preview anchor',
//        'searchreplace visualblocks code fullscreen',
//        'insertdatetime media table contextmenu paste code'
//    ],
//    toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
//    init_instance_callback: function (editor) {
//        editor.on('change', function (e) {
//            var content = tinyMCE.activeEditor.getContent();
//            $(document).find('#description').val(content);
//        });
//    }
//});

$(document).find('.template-cls').change(function () {
    var value = $(this).val();
    if (value == 'automation') {
        $(document).find('.div_template_name').removeClass('hide');
        $(document).find('.div_template_automation_images').removeClass('hide');
    } else {
        $(document).find('.div_template_name').addClass('hide');
        $(document).find('.div_template_automation_images').addClass('hide');
    }
});

$(document).find('.action-title').on('keyup', function (e) {
    console.log('hi');
});

function clearErrorMessage() {
    var forms = document.getElementsByClassName('add_custom_template');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
        var invalidFeedbacks = $(form).find('.invalid-feedback');
        invalidFeedbacks.css('display', 'none'); // Hide all error message elements
    });
}
$(document).find('.template-type-cls').change(function () {
    var value = $(this).val();
    clearErrorMessage();
    if (value == 'list') {
        $(document).find('.div_template_name').removeClass('hide');
        $(document).find('.div_template_text_name').addClass('hide');
        $(document).find('.div_template_action_main').removeClass('hide');
        $(document).find('.div_template_btn_action_main').addClass('hide');
        $(document).find('.div_template_action_title').removeClass('hide');
        $(document).find('.div_contacts').addClass('hide');

        //$(document).find('#body_text').removeAttr('required');
        $(document).find('#body_text').attr('required', 'required');
        $(document).find('#header_text').attr('required', 'required');
        $(document).find('#action_title').attr('required', 'required');
    } else if (value == 'button') {
        $(document).find('.div_template_name').removeClass('hide');
        $(document).find('.div_template_text_name').addClass('hide');
        $(document).find('.div_template_action_main').addClass('hide');
        $(document).find('.div_template_btn_action_main').removeClass('hide');
        $(document).find('.div_template_action_title').addClass('hide');
        $(document).find('.div_contacts').addClass('hide');

        $(document).find('#body_text').attr('required', 'required');
        $(document).find('#header_text').attr('required', 'required');
        //$(document).find('#header_text').removeAttr('required');
        $(document).find('#action_title').removeAttr('required');
    } else if (value == 'text') {
        $(document).find('.div_template_text_name').removeClass('hide');
        $(document).find('.div_template_name').addClass('hide');
        $(document).find('.div_template_action_main').addClass('hide');
        $(document).find('.div_template_btn_action_main').addClass('hide');
        $(document).find('.div_template_action_title').addClass('hide');
        $(document).find('.div_contacts').addClass('hide');

        $(document).find('#body_text').removeAttr('required');
        $(document).find('#header_text').removeAttr('required');
        $(document).find('#action_title').removeAttr('required');
    } else if (value == 'contacts') {
        $(document).find('.div_contacts').removeClass('hide');
        $(document).find('.div_template_text_name').addClass('hide');
        $(document).find('.div_template_name').addClass('hide');
        $(document).find('.div_template_action_main').addClass('hide');
        $(document).find('.div_template_btn_action_main').addClass('hide');
        $(document).find('.div_template_action_title').addClass('hide');

        $(document).find('#body_text').removeAttr('required');
        $(document).find('#header_text').removeAttr('required');
        $(document).find('#action_title').removeAttr('required');
    }


});

$(document).on('keyup', '#template_name', function (e) {
    e.preventDefault();
    const templateName = document.querySelector('#template_name');
    let value = templateName.value;
    value = value.replace(/\s+/g, '_');
    value = value.toLowerCase();
    value = value.replace(/[^a-z0-9_]/g, '');
    templateName.value = value;
});

$(document).on('keyup', '#template_name', function (e) {
    e.preventDefault();
    const templateName = document.querySelector('#template_name');
    let value = templateName.value;
    value = value.replace(/\s+/g, '_');
    value = value.toLowerCase();
    value = value.replace(/[^a-z0-9_]/g, '');
    templateName.value = value;
});

$(document).on('blur', '#template_name', function (e) {
    e.preventDefault();
    let templateName = document.querySelector('#template_name');
    let secondSibling = templateName.nextElementSibling;
    let thirdSibling = secondSibling.nextElementSibling;
    if(templateName.value != ''){
        thirdSibling.style.display = 'none';
        jQuery.ajax({
            type: "GET",
            dataType: 'json',
            url: base_url + 'templates/check_template_exist/' + templateName.value,
            success: function (response) {
                if(response.status){
                    templateName.focus();
                    templateName.style.borderColor = 'red';
                    thirdSibling.innerHTML = 'Template with this name already exist. use different name.';
                    thirdSibling.style.display = 'block';
                }else{
                    thirdSibling.style.display = 'none';
                    thirdSibling.innerHTML = 'Please fill the template name'
                    templateName.style.borderColor = '#bfc9d4';
                }
            }
        });
    }else{
        templateName.focus();
        templateName.style.borderColor = 'red';
        thirdSibling.innerHTML = 'Please fill the template name'
        thirdSibling.style.display = 'block';
    }
});

/*$('.template_body_button').on('click', function (e) {
    e.preventDefault();

    const btnId = $(this).attr('id');
    const btnBold = $(this).hasClass('bold');
    const btnItalic = $(this).hasClass('italic');

    if (btnBold) {
        let fieldId = btnId.replace(/^bold_/, '');
        const textarea = document.getElementById(fieldId);
        const emojiArea = $('.template_body_text').data('emojioneArea');

        console.log(emojiArea);

    }
    if (btnItalic) {
        let fieldId = btnId.replace(/^italic_/, '');
        const textarea = document.getElementById(fieldId);
    }

});*/


$('#bubble_message').on('keyup', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    bubbleMessageLengthCount();
});

$(document).on('keyup', '.bm_ex', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    bubbleMessageLengthCount();
});
function bubbleMessageLengthCount() {
    let bubble_message = document.querySelector('#bubble_message').value;
    let str = getStringWithoutPlaceholder(bubble_message);
    str += getExampleValue('.bm_ex');
    set_string_length_counter(str);
}

function set_string_length_counter(body_text, cardId = '') {
    let textLength = 0;
    if (cardId) {
        textLength = 160;
        document.querySelector('.content_count_' + cardId).innerHTML = body_text.length + '/' + textLength;
    } else {
        textLength = 1024;
        document.querySelector('.bubble_message_count').innerHTML = body_text.length + '/' + textLength;
    }
    let userMsg = document.querySelector('.userMessage');
    if (body_text.length > textLength) {
        document.activeElement.blur();
        let saveCarousel = document.querySelector('.save_carousel_blk');
        
        
        if (saveCarousel.children.length > 0) {
            saveCarousel.removeChild(saveCarousel.lastElementChild);
        }

        let errorMsg = '';
        if (cardId) {
            document.querySelector('#content_' + cardId).style.borderColor = 'red';
            document.querySelector('#content_' + cardId).focus();
          }else{
            document.querySelector('#bubble_message').style.borderColor = 'red';
            document.querySelector('#bubble_message').focus(); 
          }
        userMsg.innerHTML = '<div class="col-lg-7 col-12"><div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
            + '<button type = "button" class="btn-close" data - bs - dismiss="alert" aria - label="Close" ><i class="fa fa-close text-danger"></i></button>'
            + 'only ' + textLength + ' character is allowed.</div></div>';
        window.scrollTo(0, document.body.scrollHeight);
        
    } else {
        if (cardId) {
            document.querySelector('#content_' + cardId).style.borderColor = '#bfc9d4';
        } else {
            document.querySelector('#bubble_message').style.borderColor = '#bfc9d4';
        }
        userMsg.innerHTML = '';
        addSaveCarouselBtn();
    }
}


$(document).on('keyup', '.card_content', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    let fieldId = $(this).attr('id');
    let cardId = fieldId.replace('content_', '');
    contentLengthCount(cardId);
});

$(document).on('keyup', '.content_example', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    let cardId = $(this).data('id');
    contentLengthCount(cardId);
});



function contentLengthCount(cardId) {
    let content = document.querySelector('#content_' + cardId).value;
    let str = getStringWithoutPlaceholder(content);
    str += getExampleValue('.content_ex_' + cardId);
    set_string_length_counter(str, cardId);
}

function getStringWithoutPlaceholder(str) {
    const placeholderPattern = /{{\d+}}/g;
    const stringWithoutPlaceholders = str.replace(placeholderPattern, '');
    return stringWithoutPlaceholders;
}

function getExampleValue(selector) {
    let example = document.querySelectorAll(selector);
    let exStr = '';
    if (example.length > 0) {
        example.forEach(element => {
            let exValue = element.value;
            if (exValue) {
                exStr += exValue;
            }
        });
    }
    return exStr;
}

$('#bubble_message').on('blur', function (e) {
    let bubble_message = $(this).val();
    checkDynamic(bubble_message, 'bubble_message', 'bm_ex_');
});

$(document).on('blur', '.card_content', function (e) {
    let card_content = $(this).val();
    checkDynamic(card_content, $(this).attr('id'), 'content_ex_');
});

function checkDynamic(str, id, name) {
    const placeholderPattern = /{{\d+}}/g;
    const matches = str.match(placeholderPattern);
    const numberOfPlaceholders = matches ? matches.length : 0;
    let field = document.querySelector('#' + id);

    let exFieldsBlock = document.querySelector('.dynamic_' + id);
    let userMessage = document.querySelector('.' + id + '_error');

    if (numberOfPlaceholders > 0) {
        let exFields = '';
        const seenPlaceholders = new Set();
        const duplicates = new Set();
        const mismathched = new Set();
        let phIndex = 1;
        for (const placeholder of matches) {
            if (seenPlaceholders.has(placeholder)) {
                duplicates.add(placeholder);
            } else {
                seenPlaceholders.add(placeholder);
            }
            if (placeholder !== '{{' + phIndex + '}}') {
                mismathched.add(placeholder);
            }
            phIndex++;
        }
        if (duplicates.size > 0 || mismathched.size > 0) {
            let bodyContenterror = '';
            if (duplicates.size > 0) {
                bodyContenterror += 'Duplicate variable detected:' + [...duplicates].join(', ');
            }
            if (duplicates.size == 0 && mismathched.size > 0) {
                bodyContenterror += 'Mismathched variable detected:' + [...mismathched].join(', ') + ' Not in a sequence.';
            }
            userMessage.innerHTML = bodyContenterror;
            userMessage.style.display = 'block';
            field.style.borderColor = 'red';
            field.focus();
            exFieldsBlock.innerHTML = '';
            exFieldsBlock.classList.add('hide');
        } else {
            field.style.borderColor = '#bfc9d4';
            userMessage.innerHTML = '';
            userMessage.style.display = 'none';
            for (let bi = 1; bi <= numberOfPlaceholders; bi++) {
                let exValue = '';
                if (id == 'bubble_message') {
                    let exField = document.querySelector('#' + name + bi);
                    if (exField) {
                        exValue = exField.value;
                    }
                    exFields += '<div class="row"><div class="col-lg-5 col-12" id="' + name + bi + '_block">'
                        + '<div class="form-group mb-1">'
                        + '<input type="text" class="form-control bm_ex" id="' + name + bi + '" name="' + name + bi + '" placeholder="example value for {{' + bi + '}}" value="' + exValue + '" required>'
                        + '</div></div> </div>';
                } else {
                    let cardId = id.replace('content_', '');
                    let exField = document.querySelector('#' + name + bi + '_' + cardId);
                    if (exField) {
                        exValue = exField.value;
                    }
                    exFields += '<div class="row"><div class="col-lg-5 col-12" id="' + name + bi + '_block">'
                        + '<div class="form-group mb-1">'
                        + '<input type="text" class="form-control content_example ' + name + cardId + '" id="' + name + bi + '_' + cardId + '" data-id="' + cardId + '" name="' + cardId + '[' + name + bi + ']" placeholder="example value for {{' + bi + '}}" value="' + exValue + '" required>'
                        + '</div></div> </div>';
                }

            }
            exFieldsBlock.innerHTML = exFields;
            exFieldsBlock.classList.remove('hide');
        }
    } else {
        exFieldsBlock.innerHTML = '';
        exFieldsBlock.classList.add('hide');
    }
}

$(document).on('click', '#add-carousel-card', function (e) {
    const addBtn = $(this);

    const cardList = document.querySelector('#card-tab-list');
    const tabIndex = cardList.children.length;
    addBtn.attr('disabled', 'disabled');
    addBtn.addClass('disabled');
    const cardId = Math.floor(Date.now() / 1000);

    const buttons = cardList.querySelectorAll('button.nav-link');

    buttons.forEach(button => {
        if (button.classList.contains('active')) button.classList.remove('active');
    });

    var newItem = document.createElement('li');
    newItem.className = 'nav-item';
    newItem.setAttribute('id', 'li-' + cardId);
    newItem.setAttribute('role', 'presentation');
    newItem.innerHTML = '<button class="nav-link active" id="card-' + cardId + '" data-bs-toggle="tab" data-bs-target="#card-pane-' + cardId + '" type="button" role="tab" aria-selected="true">'
        + '<i class="fa fa-pencil"></i>'
        + '</button>';

    var lastChild = cardList.children[cardList.children.length - 1];
    cardList.insertBefore(newItem, lastChild);

    const cardContent = document.querySelector('#card-tab-content');
    const panes = cardContent.querySelectorAll('.tab-pane');

    panes.forEach(pane => {
        if (pane.classList.contains('show')) pane.classList.remove('show');
        if (pane.classList.contains('active')) pane.classList.remove('active');
    });

    var newPane = document.createElement('div');
    newPane.className = 'tab-pane show active';
    newPane.setAttribute('id', 'card-pane-' + cardId);
    newPane.setAttribute('role', 'tabpanel');
    newPane.setAttribute('aria-labelledby', 'card-' + cardId);
    newPane.setAttribute('tabindex', tabIndex);

    newPane.innerHTML = '<div class="card" >'
        + '<input type="hidden" name="content[]" value="' + cardId + '" />'
        + '<div class="card-header d-flex justify-content-between" ><div class="title">Card Content</div>'
        + '<button type="button" class="remove_card" id="remove_card_' + cardId + '"><i class="fa fa-trash"></i></button>'
        + '</div>'
        + '<div class="card-body">'
        + '<div class="col-12 mb-3"><div class="form-group"><h6 class="mb-0"> Media</h6><small>Upload either an image or video</small><br />'
        + '<input type="file" class="form-control-file card_media" name="' + cardId + '" id="media_' + cardId + '" accept="image/png, image/jpeg, image/jpg, video/mp4, video/3gp" />'
        + '<small class="mt-2 card_media_err_' + cardId + '"><b class="text-danger">allowed file size below 5 MB</b></small>'
        + '</div>'
        + '<div class="mt-2 col-4 media_preview_' + cardId + '"></div>'
        + '</div >'
        + '<div class="col-12 media_data_' + cardId + '"></div>'
        + '<div class="col-12 mb-3"><div class="form-group">'
        + '<h6 class="mb-0 mt-2">Content</h6><div class="mb-0 text-end body_content_counter"><small class="content_count_' + cardId + '" > 0 / 160</small></div>'
        + '<textarea class="form-control card_content" id="content_' + cardId + '" name="' + cardId + '[card_content]" ></textarea>'
        + '<div class="valid-feedback"> </div><div class="invalid-feedback content_' + cardId + '_error">Please fill the bubble message </div><small>If you want to add variables in message use curly-brackets like "{" with numbers. eg.Hello {{1}}, Good {{2}}</small> '
        + '</div></div>'
        + '<div class="col-12 mb-3 dynamic_content_' + cardId + ' hide"></div>'
        + '<div class="col-12 mb-3"><div class="form-group"><h6 class="mb-2">Buttons</h6><small class="mb-1">Numbers of button and Button type must be the same across all carousel cards.</small>'
        + '<div class="row card_buttons_' + cardId + '"><div class="col-12 btn_one_' + cardId + '"><div class="row">'
        + '<div class="col-lg-4 col-5"><select class="form-control card_button_type" id="btn_one_type_' + cardId + '"data-btn="btn_one" name="' + cardId + '[btn_one_type]"><option value="QUICK_REPLY"> Quick Reply</option><option value="URL"> URL</option></select></div>'
        + '<div class="col-lg-6 col-5 text-end"><input type="text" class="form-control" name="' + cardId + '[btn_one_type_text]" placeholder="Button Text" maxlength="25" /></div>'
        + '</div></div></div>'
        + '<div class="text-end card_add_btn_' + cardId + '"><button type="button" class="btn btn-outline-success add_btn mt-2" id="add_btn_' + cardId + '">Add Button</button></div>'
        + '</div></div>'
        + '</div></div>';

    var lastContentChild = cardContent.children[cardContent.children.length];
    cardContent.insertBefore(newPane, lastContentChild);

    if ((document.querySelector('#card-tab-list').children.length - 1) == 10) {
        document.querySelector('#card-tab-list').removeChild(document.querySelector('#card-tab-list').lastElementChild);
    }

    addSaveCarouselBtn();
    setTimeout(function () {
        addBtn.removeAttr('disabled');
        addBtn.removeClass('disabled');
    }, 1000)
});


function addSaveCarouselBtn() {
    let saveCarousel = document.querySelector('.save_carousel_blk');
    if (saveCarousel.children.length == 0) {
        var saveBtn = document.createElement('div');
        saveBtn.className = 'col-lg-7 col-12 text-end mt-3';
        saveBtn.innerHTML = '<button type="button" class="btn btn-primary" id="save-carousel">Submit</button>';
        var saveCarouselChild = saveCarousel.children[saveCarousel.children.length];
        saveCarousel.insertBefore(saveBtn, saveCarouselChild);
    }
}

$(document).on('click', '.remove_card', function (e) {
    e.preventDefault();
    let card = $(this).attr('id');
    let cardId = card.replace('remove_card_', '');
    let removePane = document.querySelector('#card-pane-' + cardId);
    let cardPanes = document.querySelectorAll('.tab-pane');
    const cardIndex = Array.from(cardPanes).indexOf(removePane);

    removePane.remove();
    let removeList = document.querySelector('#li-' + cardId);
    removeList.remove();

    cardPanes = document.querySelectorAll('.tab-pane');
    if (cardPanes.length > 0) {
        let nextCardItem = '';
        if (cardIndex < cardPanes.length) {
            nextCardItem = cardPanes[cardIndex];
        } else {
            nextCardItem = cardPanes[cardIndex - 1];
        }
        nextCardItem.classList.add('show');
        nextCardItem.classList.add('active');
        let nextCardItemId = nextCardItem.id;
        let cardId = nextCardItemId.replace('card-pane-', '');

        let nextCardList = document.querySelector('#li-' + cardId);
        let childElement = nextCardList.querySelector('#card-' + cardId);
        childElement.classList.add('active');
    }
    const cardTabList = document.querySelector('#card-tab-list');
    const carouselBtn = cardTabList.querySelector('.add_carousel_btn');
    if (carouselBtn == null) {
        var addCarouselBtn = document.createElement('div');
        addCarouselBtn.className = 'nav-item add_carousel_btn';
        addCarouselBtn.innerHTML = '<button type="button" id="add-carousel-card"><i class="fa fa-plus" ></i></button >';

        var lastListChild = cardTabList.children[cardTabList.children.length];
        cardTabList.insertBefore(addCarouselBtn, lastListChild);

    }
});

$(document).on('click', '.add_btn', function (e) {
    e.preventDefault();
    let addBtn = $(this).attr('id');
    let cardId = addBtn.replace('add_btn_', '');
    let btnBlocks = document.querySelector('.card_buttons_' + cardId);

    var btnTwo = document.createElement('div');
    btnTwo.className = 'col-12 mt-2 btn_two_' + cardId;

    btnTwo.innerHTML = '<div class="row"><div class="col-lg-4 col-5">'
        + '<select class="form-control card_button_type" id="btn_two_type_' + cardId + '" data-btn="btn_two" name="' + cardId + '[btn_two_type]"><option value="QUICK_REPLY"> Quick Reply</option><option value="URL"> URL</option></select></div>'
        + '<div class="col-lg-6 col-5 text-end"><input type="text" class="form-control" name="' + cardId + '[btn_two_type_text]" placeholder="Button Text" maxlength="25"></div>'
        + '<div class="col-lg-2 col-2"><button type="button" class="btn-borderless btn-remove mt-2" id="btn_remove_' + cardId + '"><i class="fa fa-trash"></i></button></div>'
        + '</div >';

    var lastChild = btnBlocks.children[btnBlocks.children.length];
    btnBlocks.insertBefore(btnTwo, lastChild);

    if ((document.querySelector('.card_buttons_' + cardId).children.length) == 2) {
        document.querySelector('.card_add_btn_' + cardId).removeChild(document.querySelector('.card_add_btn_' + cardId).lastElementChild);
    }
});

$(document).on('click', '.btn-remove', function (e) {
    e.preventDefault();
    let removeBtn = $(this).attr('id');
    let cardId = removeBtn.replace('btn_remove_', '');
    let addBtnBlocks = document.querySelector('.card_add_btn_' + cardId);

    document.querySelector('.card_buttons_' + cardId).removeChild(document.querySelector('.card_buttons_' + cardId).lastElementChild);

    var addABtn = document.createElement('div');
    addABtn.className = 'btn btn-outline-success add_btn mt-2';
    addABtn.setAttribute('id', 'add_btn_' + cardId);
    addABtn.setAttribute('type', 'button');
    addABtn.innerHTML = 'Add Button';

    var lastContentChild = addBtnBlocks.children[addBtnBlocks.children.length];
    addBtnBlocks.insertBefore(addABtn, lastContentChild);

});

$(document).on('change', '.card_button_type', function (e) {
    e.preventDefault();
    let btnType = $(this).val();
    let btnIndex = $(this).data('btn');
    let id = $(this).attr('id');
    let cardId = id.replace(btnIndex + '_type_', '');

    let btnBlock = document.querySelector('.' + btnIndex + '_' + cardId);
    if (btnType == 'QUICK_REPLY') {
        let btnBlockChildren = btnBlock.children;
        if (btnBlockChildren.length > 1) {
            btnBlock.removeChild(btnBlockChildren[1]);
        }
    }
    if (btnType == 'URL') {
        let newDiv = document.createElement('div');
        newDiv.className = 'row mt-2';
        newDiv.innerHTML = '<div class="col-lg-4 col-5">'
            + '<select class="form-control button_url_type" id="button_url_type_' + cardId + '" data-btn="' + btnIndex + '" name="' + cardId + '[' + btnIndex + '_url_type]"><option value="static"> Static</option><option value="dynamic"> Dynamic</option></select></div>'
            + '<div class="col-lg-6 col-5 text-end"><input type="text"  name="' + cardId + '[' + btnIndex + '_url]" id="' + btnIndex + '_url_' + cardId + '" class="form-control btn_url" data-btn ="' + btnIndex + '" placeholder="' + base_url + '" maxlength="2000">'
            + '<div class="dynamic_url_' + btnIndex + '_' + cardId + ' mt-2"> </div></div>';
        btnBlock.appendChild(newDiv);
    }
});

$(document).on('change', '.button_url_type', function (e) {
    e.preventDefault();
    let urlType = $(this).val();
    let btnIndex = $(this).data('btn');
    let id = $(this).attr('id');
    let cardId = id.replace('button_url_type_', '');

    let btnUrl = document.querySelector('#' + btnIndex + '_url_' + cardId);
    let dynamicUrlBlk = document.querySelector('.dynamic_url_' + btnIndex + '_' + cardId);
    if (urlType == 'static') {
        btnUrl.placeholder = base_url;
        dynamicUrlBlk.innerHTML = '';
    } else {
        btnUrl.placeholder = base_url + '{{1}}';
        dynamicUrlBlk.innerHTML = '<input type="text"  name="' + cardId + '[' + btnIndex + '_url_example]" id="' + btnIndex + '_url_example_' + cardId + '" class="form-control" placeholder="' + base_url + 'aboutus" maxlength="2000">'
    }
});

$(document).on('keyup', '.btn_url', function (e) {
    e.preventDefault();
    let url = $(this).val();
    let id = $(this).attr('id');
    let btn = $(this).data('btn');
    let cardId = id.replace(btn + '_url_', '');

    let btnUrl = document.querySelector('#' + btn + '_url_' + cardId);
    let userMsg = document.querySelector('.userMessage');
    try {
        new URL(url);
        btnUrl.style.borderColor = '#bfc9d4';
        userMsg.innerHTML = '';
    } catch (e) {
        btnUrl.focus();
        btnUrl.style.borderColor = 'red';

        userMsg.innerHTML = '<div class="col-lg-7 col-12"><div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
            + '<button type = "button" class="btn-close" data - bs - dismiss="alert" aria - label="Close" ><i class="fa fa-close text-danger"></i></button>'
            + ' Please provide valid URL for Button ' + btn.replace('btn_', '') + '</div ></div >';
    }
});

$(document).on('change', '.card_media', function (e) {
    e.preventDefault();
    let saveCarousel = document.querySelector('#save-carousel');
    saveCarousel.disabled = true;
    let id = $(this).attr('id');
    let cardId = id.replace('media_', '');

    var data = new FormData($("#create_carousel_form")[0]);
    data.append('card_id', cardId);
    let userMsg = document.querySelector('.userMessage');
    let media_data = document.querySelector('.media_data_' + cardId);
    media_data.innerHTML = '';
    let media_preview = document.querySelector('.media_preview_' + cardId);
    if (media_preview) {
        media_preview.innerHTML = '';
    }
    const fileInput = e.target;
    const acceptedFile = fileInput.getAttribute('accept');
    const allowedTypes = acceptedFile.split(',').map(type => type.trim());
    
    let mediaSelError = document.querySelector('.card_media_err_' + cardId);
    mediaSelError.innerHTML = '<p class=" mt-2 text-info"><i class="fa fa-spinner fa-pulse"></i> Please wait...</p>';
    
    const file = fileInput.files[0];
    if(file != undefined){
        let fileSize = file.size;
        let fileType = file.type;
        if( fileSize > 0){
            if(allowedTypes.includes(fileType)){
                let fsMB = Math.ceil(parseInt(file.size)/(1024 * 1024));
                if(fsMB < 5){
                
                    jQuery.ajax({
                        type: "POST",
                        url: base_url + "templates/start_upload_session",
                        data: data,
                        async: true,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            let json = $.parseJSON(response);
                            let innerHtml = '';
                            if (json.error) {
                                 mediaSelError.innerHTML = '<b class="text-danger">'+ json.error +'</b>';
                            } else {
                                innerHtml += '<input type="hidden" name="' + cardId + '[header_handle]" value="' + json.header_handle + '" />'
                                innerHtml += '<input type="hidden" name="' + cardId + '[format]" value="' + json.format + '" />'
                                media_data.innerHTML = innerHtml;

                                const url = URL.createObjectURL(file);
                                
                                if (file.type.startsWith('image/')) {
                                    const img = document.createElement('img');
                                    img.src = url;
                                    img.style.maxWidth = '100%'; // Make sure image fits within the container
                                    media_preview.appendChild(img);
                                    mediaSelError.innerHTML = '<b class="text-success">Image uploaded successfully</b>';
                                } else if (file.type.startsWith('video/')) {
                                    const video = document.createElement('video');
                                    video.src = url;
                                    video.controls = true; // Add video controls (play, pause, etc.)
                                    video.style.maxWidth = '100%'; // Make sure video fits within the container
                                    media_preview.appendChild(video);
                                    mediaSelError.innerHTML = '<b class="text-success">Video uploaded successfully</b>';
                                }
                            }
                        }
                    });
                }else{
                    fileInput.value = '';
                    media_preview.innerHTML = '';
                    mediaSelError.innerHTML = '<b class="text-warning">Maximum 5MB file size is allowed</b>';
                }
            }else{
                fileInput.value = '';
                media_preview.innerHTML = '';
                mediaSelError.innerHTML = '<b class="text-warning">Only allowed '+acceptedFile+' type files.</b>';
            }
            setTimeout(function(){
                saveCarousel.disabled = false;
                mediaSelError.innerHTML = '';
            }, 5000);
        }
    }
});

/*function startUpload(data) {
    jQuery.ajax({
        type: "POST",
        url: base_url + "templates/start_upload",
        data: data,
        success: function (response) {

        }
    });
}*/

$(document).on('click', '#save-carousel', function (e) {
    e.preventDefault();
    $(this).attr('disabled', 'disabled');
    let error = '';
    let templateName = document.querySelector('#template_name');
    let templateCategory = document.querySelector('#template_category');
    let templateLanguage = document.querySelector('#template_language');
    let bubbleMessage = document.querySelector('#bubble_message');
    let userMsg = document.querySelector('.userMessage');
    let clickedSaveCarousel = document.querySelector('#save-carousel');
    if(templateName.value == ''){
        error += 'Please provide template name.<br/>';
    }
    if(templateCategory.value == 'Select Category' || templateCategory.value == ''){
        error += 'Please select category.<br/>';
    }
    if(templateLanguage.value == 'Select Language' || templateLanguage.value == ''){
        error += 'Please select language.<br/>';
    }
    if(bubbleMessage.value == ''){
        error += 'Please provide bubble message.<br/>';
    }
    
    let cardTab = document.querySelector('#card-tab-content');
    if(cardTab.children.length == 0){
        error += 'Please add at least one Carousel Card.<br/>';
    }else{
        let firstChild = cardTab.firstElementChild.id;
        let cardId = firstChild.replace('card-pane-', '');
        
        let panes = document.querySelectorAll('.tab-pane');
        let paneI = 0;
        
        let firstPaneMedia = document.querySelector('input[name="'+cardId+'[format]"]');
        let reqMedia = '';
        if(firstPaneMedia == null){
            error += '<p>Please select image or video media file for card-1.</p>';
        }else{
            reqMedia = firstPaneMedia.value;
        }
        if(reqMedia != '' && cardTab.children.length > 1){
            let firstPaneButtons = document.querySelector('.card_buttons_' + cardId);
            let reqLength = firstPaneButtons.children.length;
            let reqFirstButtonType = document.querySelector('#btn_one_type_' + cardId).value;
            let reqSecondButtonType;
            if (reqLength == 2) {
                reqSecondButtonType = document.querySelector('#btn_two_type_' + cardId).value;
            }
            
            for (let pane of panes) {
                paneI = parseInt(paneI)+1;

                let paneCardId = pane.id.replace('card-pane-', '');
                let paneMedia = document.querySelector('input[name="'+paneCardId+'[format]"]');
                if(paneMedia == null){
                    console.log('not sel :'+paneI);
                    error += '<p>Please select '+reqMedia+' media file for card-'+paneI+'.</p>';
                }else{
                    if(paneMedia.value != reqMedia){
                      error += '<p>Media file should be same for all card.</p>';
                    }
                }
                let paneButtonLength = document.querySelector('.card_buttons_' + paneCardId).children.length;
                if (paneButtonLength == reqLength) {
                    let paneFirstButtonType = document.querySelector('#btn_one_type_' + paneCardId).value;
                    if (paneFirstButtonType == reqFirstButtonType) {
                        if (reqSecondButtonType != undefined) {
                            let paneSecondButtonType = document.querySelector('#btn_two_type_' + paneCardId).value;
                            if (paneSecondButtonType != reqSecondButtonType) {
                               error += '<p>Second Button type must be the same across all carousel cards.</p>'
                            }
                        }
                    } else {
                        error += '<p>First Button type must be the same across all carousel cards.</p>';
                    }
                } else {
                    error += '<p>Numbers of button must be the same across all carousel cards.</p>';
                }
            }
        }  
    }
    
    
    if(error != ''){
        userMsg.innerHTML = '<div class="col-lg-7 col-12"><div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                    + '<button type = "button" class="btn-close" data - bs - dismiss="alert" aria - label="Close" ><i class="fa fa-close text-danger"></i></button>'
                                    + error+'</div ></div >';
        
        clickedSaveCarousel.removeAttribute('disabled');
    }else{
        userMsg.innerHTML = '';
        saveCarousel();
    }
});

function validateMedia(reqMedia) {
    
    
    return mediaError;
    
    
    
    
    
    
    
    //return mediaError;
    
    
            /*let reqLength = firstPaneButtons.children.length;
            //console.log('require button length -> '+reqLength);
            
            let reqFirstButtonType = document.querySelector('#btn_one_type_' + cardId).value;
            //console.log('first button type -> '+reqFirstButtonType);
            
            let reqSecondButtonType;
            if (reqLength == 2) {
                reqSecondButtonType = document.querySelector('#btn_two_type_' + cardId).value;
                //console.log('second button type -> '+reqSecondButtonType);
            }

            let panes = document.querySelectorAll('.tab-pane');
            let paneI = 0;
            for (let pane of panes) {
                paneI = parseInt(paneI)+1;
                
                let paneCardId = pane.id.replace('card-pane-', '');
                let paneButtonLength = document.querySelector('.card_buttons_' + paneCardId).children.length;

                if (paneButtonLength == reqLength) {
                    let paneFirstButtonType = document.querySelector('#btn_one_type_' + paneCardId).value;
                    //console.log('pane '+paneI+' button 1 -> '+paneFirstButtonType);
                    if (paneFirstButtonType == reqFirstButtonType) {
                        if (reqSecondButtonType != undefined) {
                            let paneSecondButtonType = document.querySelector('#btn_two_type_' + paneCardId).value;
                            //console.log('pane '+paneI+' button 2 -> '+paneSecondButtonType);
                            
                            if (paneSecondButtonType != reqSecondButtonType) {
                                error = 'Second Button type must be the same across all carousel cards.<br/>'
                            }
                        }

                    } else {
                         error = 'First Button type must be the same across all carousel cards.<br/>';
                    }
                } else {
                    error = 'Numbers of button must be the same across all carousel cards.<br/>';
                }
            }*/
        
}

function checkButtonOrder() {
    let cardTab = document.querySelector('#card-tab-content');
        console.log('total card -> '+cardTab.children.length);
        let error = '';
        let clickedSaveCarousel = document.querySelector('#save-carousel');
        let userMsg = document.querySelector('.userMessage');
        if (cardTab.children.length > 1) {
            let firstChild = cardTab.firstElementChild.id;
            let cardId = firstChild.replace('card-pane-', '');
            let firstPaneButtons = document.querySelector('.card_buttons_' + cardId);
            let reqLength = firstPaneButtons.children.length;
            //console.log('require button length -> '+reqLength);
            
            let reqFirstButtonType = document.querySelector('#btn_one_type_' + cardId).value;
            //console.log('first button type -> '+reqFirstButtonType);
            
            let reqSecondButtonType;
            if (reqLength == 2) {
                reqSecondButtonType = document.querySelector('#btn_two_type_' + cardId).value;
                //console.log('second button type -> '+reqSecondButtonType);
            }

            let panes = document.querySelectorAll('.tab-pane');
            let paneI = 0;
            for (let pane of panes) {
                paneI = parseInt(paneI)+1;
                
                let paneCardId = pane.id.replace('card-pane-', '');
                let paneButtonLength = document.querySelector('.card_buttons_' + paneCardId).children.length;

                if (paneButtonLength == reqLength) {
                    let paneFirstButtonType = document.querySelector('#btn_one_type_' + paneCardId).value;
                    //console.log('pane '+paneI+' button 1 -> '+paneFirstButtonType);
                    if (paneFirstButtonType == reqFirstButtonType) {
                        if (reqSecondButtonType != undefined) {
                            let paneSecondButtonType = document.querySelector('#btn_two_type_' + paneCardId).value;
                            //console.log('pane '+paneI+' button 2 -> '+paneSecondButtonType);
                            
                            if (paneSecondButtonType != reqSecondButtonType) {
                                error = 'Second Button type must be the same across all carousel cards.<br/>'
                            }
                        }

                    } else {
                         error = 'First Button type must be the same across all carousel cards.<br/>';
                    }
                } else {
                    error = 'Numbers of button must be the same across all carousel cards.<br/>';
                }
            }
        }
        if(error == ''){
            //console.log('it\'s ok');
            saveCarousel();
        }else{
            userMsg.innerHTML = '<div class="col-lg-7 col-12"><div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                    + '<button type = "button" class="btn-close" data - bs - dismiss="alert" aria - label="Close" ><i class="fa fa-close text-danger"></i></button>'
                                    + error+'</div ></div >';
            clickedSaveCarousel.removeAttribute('disabled');
        }
}

function saveCarousel() {
    var data = new FormData($("#create_carousel_form")[0]);

    jQuery.ajax({
        type: "POST",
        url: base_url + "templates/save_carousel",
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (response) {
            let json = $.parseJSON(response);
            let userMsg = document.querySelector('.userMessage');
            let panes = document.querySelectorAll('.tab-pane');
            let clickedSaveCarousel = document.querySelector('#save-carousel');
            clickedSaveCarousel.removeAttribute('disabled');
            if (json.error) {
                userMsg.innerHTML = '<div class="col-lg-7 col-12"><div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                    + '<button type = "button" class="btn-close" data - bs - dismiss="alert" aria - label="Close" ><i class="fa fa-close text-danger"></i></button>'
                    + json.error + '</div ></div >';
                if (json.card) {
                    let cardBtn = document.querySelector('#card-' + json.card);
                    if (cardBtn) {
                        cardBtn.click();
                    }

                    panes.forEach(pane => {
                        let cardId = pane.id.replace('card-pane-', '');
                        let paneBlk = document.querySelector('#' + pane.id);
                        if (cardId == json.card) {
                            paneBlk.style.border = '1px solid red';
                            paneBlk.style.borderRadius = '10px';
                        } else {
                            paneBlk.style.border = 'none';
                            paneBlk.style.borderRadius = '0px';
                        }
                    });
                }

                setTimeout(function () {
                    userMsg.innerHTML = '';
                    panes.forEach(pane => {
                        let paneBlk = document.querySelector('#' + pane.id);
                        paneBlk.style.border = 'none';
                        paneBlk.style.borderRadius = '0px';
                    });
                }, 3000);
            } else if (json.success) {
                userMsg.innerHTML = '<div class="col-lg-7 col-12"><div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                    + '<button type = "button" class="btn-close" data - bs - dismiss="alert" aria - label="Close" ><i class="fa fa-close text-success"></i></button>'
                    + json.success + '</div ></div >';
                setTimeout(function () {
                    userMsg.innerHTML = '';
                    window.location.href = base_url + 'templates';
                }, 3000);

            } else {
                userMsg.innerHTML = '<div class="col-lg-7 col-12"><div class="alert alert-light-warning alert-dismissible fade show border-0 mb-4" role="alert">'
                    + '<button type = "button" class="btn-close" data - bs - dismiss="alert" aria - label="Close" ><i class="fa fa-close text-warning"></i></button>'
                    + json.warning + '</div ></div >';
                setTimeout(function () {
                    userMsg.innerHTML = '';
                    window.location.href = base_url + 'templates';
                }, 3000);
            }
        }
    });

}


$(document).find('#description').emojioneArea({
    //    saveEmojisAs: 'shortname'
});

