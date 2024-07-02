/*

var templates_dttble = $('#templates_dttble').DataTable({
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
        "url": base_url + 'templates/list_templates/birthday',
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
            data: "description",
            visible: true,
            width: '60%'
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
                var action = '<td><ul class="table-controls">';
                action += '<li><a href="' + base_url + 'templates/edit/' + btoa(full.id) + '" title="Edit"><i class="fa fa-pencil"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_templates(' + full.id + ')"  title="Delete"><i class="fa fa-trash"></i></a></li>';
                if (is_template_default != full.id) {
                    action += '<li><a href="javascript:void(0)" onclick="set_default_templates(' + full.id + ',\'birthday\')"  title="Set Default" ><i class="fa fa-star"></i></a></li>';
                } else {
                    action += '<li><span title="Default" class="btn-gradient-danger btn-template-default"><i class="fa fa-star"></i></span></li>';
                }
                action += '</ul></td>';
                return action;
            }
        }
    ], drawCallback: function () {
        $('.t-dot').tooltip({template: '<div class="tooltip status" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'})
    }
});
var templates_dttble = $('#templates_anniversary_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [5, 10, 20, 50, 100],
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
        "url": base_url + 'templates/list_templates/anniversary',
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
            data: "description",
            visible: true,
            width: '60%'
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
                var action = '<td><ul class="table-controls">';
                action += '<li><a href="' + base_url + 'templates/edit/' + btoa(full.id) + '" title="Edit"><i class="fa fa-pencil"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_templates(' + full.id + ')"  title="Delete"><i class="fa fa-trash"></i></a></li>';
                if (is_template_default != full.id) {
                    action += '<li><a href="javascript:void(0)" onclick="set_default_templates(' + full.id + ',\'anniversary\')"  title="Set Default"><i class="fa fa-star"></i></a></li>';
                } else {
                    action += '<li><span title="Default" class="btn-gradient-danger btn-template-default"><i class="fa fa-star"></i></span></li>';
                }
                action += '</ul></td>';
                return action;
            }
        }
    ], drawCallback: function () {
        $('.t-dot').tooltip({template: '<div class="tooltip status" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'})
    }
});
*/
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
                    res ='<a href="Javascript:(0);" onClick="view_templates_description('+full.id+')"><img src="../../assets/img/meta.ico" width="17px" > ';
                    res += data + ' (' + full.temp_language + ')</a>';
                }
                if (full.custom_type != '' && full.custom_type != null) {
                    res = '<a href="Javascript:(0);" onClick="view_templates_description('+full.id+')">'+data + ' (' + full.custom_type + ')</a>';
                }
                
                return res;
            }
        },
        /*{
            data: "description",
            visible: true,
            width: '3%',
            render: function (data, type, full, meta) {
                var response = '';
                if (full.temp_id != '' && full.temp_id != null) {
                    var desc_json = (full.description != '') ? $.parseJSON(full.description) : '';
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
                } else if (full.custom_type != '' && full.custom_type != null) {
                    var desc_json = (full.description != '') ? $.parseJSON(full.description) : '';
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
                        var action_text = '<br/><h6><strong>' + full.custom_type + '</strong></h6>';
                        if (desc_json.actions != undefined && desc_json.actions != '') {
                            $.each(desc_json.actions, function (key, val) {
                                if (full.custom_type == 'list') {
                                    action_text += key + ', ' + val.title + ', ' + val.description + '<br/>';
                                } else if (full.custom_type == 'button') {
                                    action_text += key + ', ' + val.title + '<br/>';
                                } else if (full.custom_type == 'text') {
                                    action_text = '';
                                }
                            });
                            response += action_text;
                        }

                    }
                } else {
                    response = data;
                }
                response = response.replace(/\n/g, '<br>');
                return response;
            }
        },*/
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
                    action += '<li><a href="javascript:void(0)" onclick="view_templates(' + full.id + ')" class="btn btn-outline-success btn-icon" title="View"><i class="fa fa-eye"></i></a></li>';
                } else {
                    var edit_url = 'edit';
                    if (full.custom_type != '' && full.custom_type != null) {
                        edit_url = 'edit_custom';
                    }
                    action += '<li><a href="' + base_url + 'templates/' + edit_url + '/' + btoa(full.id) + '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="fa fa-pencil"></i></a></li>';
                    action += '<li><a href="javascript:void(0)" onclick="delete_templates(' + full.id + ')" class="btn btn-outline-danger btn-icon"  title="Delete"><i class="fa fa-trash"></i></a></li>';
                }
                action += '</ul></td>';
                return action;
            }
        }
    ], drawCallback: function () {
        $('.t-dot').tooltip({template: '<div class="tooltip status" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'})
    }
});
//
//$('.dataTables_length select').select2({
//    minimumResultsForSearch: Infinity,
//    width: 'auto'
//});

$(document).find('.btn-add-template').on('click', function (e) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

$(document).find('.btn-add-custom-template').on('click', function (e) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

$(document).find('.btn-add-action').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.div_template_action:last').data('seq');
    console.log(count);
    count = (count == undefined) ? 0 : count;
    console.log(count);
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
                } else {
                    invalidFeedback.css('display', 'none');
                    $(this).siblings('.invalid-feedback').css('display', 'none');
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
                    response = result;
                }
                response = response.replace(/\n/g, '<br>');
            
            //var formattedJson = JSON.stringify(response, undefined, 2);
            $(document).find('.template_details').html('').html('<code>'+response+'</code>');
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

$(document).find('.action-title').on('keyup', function(e){
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

        $(document).find('#body_text').removeAttr('required');
        $(document).find('#header_text').removeAttr('required');
        $(document).find('#action_title').removeAttr('required');
    }

});
$(document).find('#description').emojioneArea({
//    saveEmojisAs: 'shortname'
});