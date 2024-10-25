$(document).find(".basic").select2({
    tags: true
});
var automations_dttble = $('#reply_messages_dttble').DataTable({
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
        "url": base_url + 'replyMessage/list_reply_messages',
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
            data: "reply_text",
            visible: true,
        },
        {
            data: "attachments",
            visible: true,
            render: function (data, type, full, meta) {
                var attachments = (full.attachments != undefined && full.attachments != '') ? JSON.parse(full.attachments) : '';
                var list_templates = (full.list_templates != undefined && full.list_templates != '') ? full.list_templates : '';
                var attachments_text = '';
                if (attachments != '') {
                    $.each(attachments, function (i, item) {
                        var template_details = list_templates[i];
                        var br_text = '<br/><br/>';
                        var attach_text = '';
                        if (isNumeric(item)) {
                            if (template_details != undefined && template_details != '') {
                                if (template_details.name != undefined && template_details.name != '') {
                                    var custom_type = '';
                                    if (template_details.custom_type != undefined && template_details.custom_type != null && template_details.custom_type != '') {
                                        custom_type += ' (' + template_details.custom_type + ')';
                                    }
                                    attach_text = '<span class="text-success">' + template_details.name + custom_type + '</span>';
                                }
                            }
                        } else {
                            attach_text = '<a class="text-success" target="_blank" href="' + base_url + '' + ATTACHMENT_IMAGE_UPLOAD_PATH + '' + item + '">' + item + '</a>';
                        }
                        if (attach_text != '' && attach_text != null) {
                            attachments_text += (i == 1) ? i + ') ' + attach_text : br_text + '' + i + ') ' + attach_text;
                        }
                    });
                }
                return attachments_text;
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
                var is_template_default = (full.is_default != undefined && full.is_default != null) ? parseInt(full.is_default) : 0;
                var action = '<td><ul class="table-controls">';
                action += '<li><a href="' + base_url + 'replyMessage/edit/' + btoa(full.id) + '" class="btn btn-outline-primary bs-tooltip p-1" data-bs-placement="top" data-bs-original-title="Edit"><i class="fa fa-pencil p-1 br-6 mb-1"></i></a></li>';
                //rewrite: RR
                //action += '<li><a href="javascript:void(0)" onclick="delete_reply_message(' + full.id + ')"  title="Delete"><i class="flaticon-delete p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0)" class="delete_reply_message btn btn-outline-danger bs-tooltip p-1" data-reply_id="'+ full.reply_id +'" data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash p-1 br-6 mb-1"></i></a></li>';
                action += '</ul></td>';
                return action;
            }
        }
    ], drawCallback: function () {
    }
});

function isNumeric(value) {
    return !isNaN(parseFloat(value)) && isFinite(value);
}

$(document).find('.btn-add-reply-message').on('click', function (e) {
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

//rewrite by: RR
$(document).on('click','.delete_reply_message', function(event){
    event.preventDefault();
    let reply_id = $(this).data('reply_id');

    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Reply Message!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "replyMessage/action/delete/" + btoa(reply_id);
        }
    })
});
/*function delete_reply_message(id) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Reply Message!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "replyMessage/action/delete/" + btoa(id);
        }
    })
}*/


/*
@uses : add close button to trigger reply text while adding
@author : RR
*/
$(document).on('keydown', '.bootstrap-tagsinput', function(e){
    if (e.key === "Enter" || e.keyCode === 9 || e.keyCode === 188) {    
        const btags = document.querySelectorAll('.bootstrap-tagsinput .tag');
        const tagsArray = Array.from(btags).map(btag => btag.textContent.replace('x', '').trim());  
        setTimeout(function(){
            const tags = getTagsInput()
            tags.forEach(tag => {
                if(tag.children.length == 0){
                    addRemoveButton(tag)
                }
            });
            updateHiddenField();
        },10)
    }
});
/*
@uses : add close button to trigger reply text
@author : RR
*/
//initializeTags();
function  initializeTags(){
    const tagsInput = document.querySelector('.tagsinput');
    if(tagsInput != undefined){
        const tags = getTagsInput()  
        tags.forEach(tag => {
            addRemoveButton(tag)
        });

        const container = tagsInput.parentElement
        const tagsInfo = document.createElement('div');
        tagsInfo.setAttribute('class', 'tagsinput-container');
    }
}

/*$(this).click(function() {
    var activeElement = document.activeElement;
    if(activeElement.tagName == 'BODY'){
        const tags = getTagsInput()    
        tags.forEach(tag => {
            if(tag.children.length == 0){
                addRemoveButton(tag)
            }
        });
    }
});*/


function getTagsInput(){
    const container = document.querySelector('.bootstrap-tagsinput');
    const tags = container.querySelectorAll('.tag');
    return tags;
}
function addRemoveButton(tag){
    const removeSpan = document.createElement('span');
    removeSpan.setAttribute('data-role', 'remove-button');
    removeSpan.setAttribute('class', 'remove-button');
    removeSpan.innerHTML = 'x'; 
    tag.appendChild(removeSpan);
}

function updateHiddenField(){
    const replyText = document.getElementById('hReplyText')
    const btags = document.querySelectorAll('.bootstrap-tagsinput .tag');
    const tagsArray = Array.from(btags).map(btag => btag.textContent.replace('x', '').trim());
    replyText.value = tagsArray.join(',');
}

$(document).on('click', '.remove-button', function(e){
    e.preventDefault();
    const tag = e.target.closest('.tag');
    if (tag) {
        tag.remove();
        updateHiddenField();
    }
    setTimeout(function(){
        updateHiddenField();
    },20);
});


$(document).find('#description').emojioneArea();

$(document).on('click', '.delete_message', function (event) {
    event.preventDefault();
    var seq = $(this).data('id');
    $(document).find('#reply_message_details_div_' + seq).remove();
    $(document).find('#reply_attachment_details_div_' + seq).remove();
    $(document).find('#reply_template_details_div_' + seq).remove();
    $(document).find('#reply_meta_template_details_div_' + seq).remove();
    $(document).find('#reply_meta_template_div_' + seq).remove();
});

$(document).find('.btn-add-message').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.reply_message_details_div:last').data('seq');
    count = (count == undefined) ? 0 : count;
    var seq = (count + 1);
    var html = '<div class="reply_message_details_div" id="reply_message_details_div_' + seq + '"  data-seq="' + seq + '">' +
            '<div class="form-group row mb-4">' +
            '<label >Enter Message</label>' +
            '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">' +
            '<input type="text" class="form-control" id="message_' + seq + '" name= "messages[' + seq + ']" placeholder="Message" Required/>' +
            '</div>' +
            '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' +
            '<div class="actions  align-self-center">' +
            '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_message" data-id="' + seq + '">' +
            '<i class="fa fa-trash"></i>' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    $(document).find('.reply_message_attachment_details').append(html);
});

$(document).find('.btn-add-attachment').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.reply_attachment_details_div:last').data('seq');
    count = (count == undefined) ? 0 : count;
    var seq = (count + 1);
    var html = '<div class="reply_attachment_details_div" id="reply_attachment_details_div_' + seq + '" data-seq="' + seq + '">' +
            '<div class="form-group row mb-3">' +
            '<label >Add Attachment</label>' +
            '<div class="col-xl-11 col-lg-11 col-sm-11 col-11"> <div class="row">' +
            '<div class="col-12">' +
            '<input type="file" class="form-control-file" id="attachment_' + seq + '" name="attachment[' + seq + ']" placeholder="" accept=".jpg,.jpeg, .png, .pdf, .mp4">' +
            '<small id="passwordHelpInline" class="text-muted">' +
            'Allowed file type: JPG, JPEG, PNG (Max size: 5MB), PDF (Max size: 100MB) AND MP4 (Max size: 100MB)' +
            '</small>' +
            '</div>' +
            '<div class="col-12">' +
            '<input type="text" class="form-control" id="attachment_caption' + seq + '" name="attachment_caption[' + seq + ']" placeholder="enter caption for attachment" maxlength="1000" />' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' +
            '<div class="actions align-self-center">' +
            '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_message" data-id="' + seq + '">' +
            '<i class="fa fa-trash"></i>' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    $(document).find('.reply_message_attachment_details').append(html);
});


$(document).find('.btn-add-meta-template').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.reply_attachment_details_div:last').data('seq');
    count = (count == undefined) ? 0 : count;
    var seq = (count + 1);
    var meta_templates = $(document).find('#meta_templates').val();
    var metatemplates = $.parseJSON(meta_templates);
    var options = '';
    options += '<option value="">Select Meta Template</option>';
    $.each(metatemplates, function (key, value) {
        options += '<option value="' + value.id + '">' + value.name + '</option>';
    });
    var html = '<div class="reply_meta_template_details_div reply_attachment_details_div" id="reply_template_details_div_' + seq + '"  data-seq="' + seq + '">' +
            '<div class="form-group row mb-4">' +
            '<label>Select Meta Template</label>' +
            '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">' +
            '<select class="form-control basic reply_meta_template" required id="reply_meta_template_' + seq + '" name= "reply_meta_templates[' + seq + ']">' +
            options +
            '</select>' +
            '</div>' +
            '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' +
            '<div class="actions  align-self-center">' +
            '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_message" data-id="' + seq + '">' +
            '<i class="fa fa-trash"></i>' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    html += '<div class="reply_meta_template_div hide automation_template_div" id="reply_meta_template_div_' + seq + '" data-seq="' + seq + '">' +
            '<div class="form-group row mb-4">' +
            '<label class="reply_meta_template_name" id="reply_meta_template_name_' + seq + '"></label>' +
            '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">' +
            '<div class="reply_meta_template_desc_div" id="reply_meta_template_desc_div_' + seq + '" data-seq="' + seq + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    $(document).find('.reply_message_attachment_details').append(html);
    setTimeout(function () {
        $(document).find(".basic").select2({
            tags: true
        });
    }, 10);

    add_meta_change_event(seq);
});

function add_meta_change_event(seq) {
    $(document).find('#reply_meta_template_' + seq).change(function (event) {
        event.preventDefault();
        var temp_id = $(this).find(":selected").val();
        get_template_details(temp_id, seq);
    });
}

function get_template_details(temp_id, seq) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: base_url + 'templates/get_template_details/' + btoa(temp_id) + '/' + btoa(seq),
        success: function (result) {
//            console.log(result);
            $(document).find('#reply_meta_template_div_' + seq).removeClass('hide');
            $(document).find('#reply_meta_template_name_' + seq).html('').html(result.name);
            $(document).find('#reply_meta_template_desc_div_' + seq).html('').html(result.response);
            setTimeout(function () {
                $(document).find("#temp_image_media_" + seq).change(function () {
                    readURL(this, seq);
                });
            }, 10);
        }
    });
}

$(document).find('.btn-add-template').on('click', function (event) {
    event.preventDefault();
    var count = $(document).find('.reply_attachment_details_div:last').data('seq');
    count = (count == undefined) ? 0 : count;
    var seq = (count + 1);
    var list_templates = $(document).find('#list_templates').val();
    var templates = $.parseJSON(list_templates);
    var options = '';
    options += '<option value="">Select Custom Template</option>';
    $.each(templates, function (key, value) {
        options += '<option value="' + value.id + '">' + value.name + ' (' + value.custom_type + ')</option>';
    });
    var html = '<div class="reply_template_details_div reply_attachment_details_div" id="reply_template_details_div_' + seq + '"  data-seq="' + seq + '">' +
            '<div class="form-group row mb-4">' +
            '<label >Select Custom Template</label>' +
            '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">' +
            '<select class="form-control basic reply_template" required id="reply_template_' + seq + '" name= "reply_templates[' + seq + ']">' +
            options +
            '</select>' +
            '</div>' +
            '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' +
            '<div class="actions  align-self-center">' +
            '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_message" data-id="' + seq + '">' +
            '<i class="fa fa-trash"></i>' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    
    $(document).find('.reply_message_attachment_details').append(html);
    //$().select2();
    new TomSelect('#reply_template_' + seq,{
        create: false,
    });
    
    add_change_event(seq);
});

function add_change_event(seq) {
    $(document).find('#template_' + seq).change(function (event) {
        event.preventDefault();
    });
}
$(document).find(".basic").map(function () {
    var template_id = $(this).attr('id');
    var seq = template_id.split("_")[1];
    if (seq != undefined && seq > 0) {
        add_change_event(seq);
    }
});
