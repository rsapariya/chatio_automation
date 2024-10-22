var campaign_dttble = $('#campaign_dttble').DataTable({
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
    order: [[3, "desc"]],
    ajax: {
        'type': 'GET',
        "url": base_url + 'campaigns/list_campaigns',
    },
    columns: [
        {
            data: "sr_no",
            visible: true,
            sortable: false,
        },
        {
            data: "campaign_name",
            visible: true
        },
        {
            data: "name",
            visible: true,
        },
        {
            data: "created",
            visible: true,
        },
{
            data: "status",
            visible: true,
            render: function (data, type, full, meta) {
                let renderStatus = full.status;
                
                if(full.status == 'schedule'){
                    renderStatus = '<span class="badge badge-light-info mb-2 me-4">Schedule</span>';
                }
                if(full.status == 'in_progress'){
                     renderStatus = '<span class="badge badge-light-warning mb-2 me-4">In Progress</span>';
                }
                if(full.status == 'delivered'){
                     renderStatus = '<span class="badge badge-light-success mb-2 me-4">Delivered</span>';
                }
                if(full.status == 'failed'){
                     renderStatus = '<span class="badge badge-light-danger mb-2 me-4">Failed</span>';
                }
                return renderStatus;
            }
        },
        {
            data: "contacts",
            visible: true,
            sortable: false,
        },
        {
            data: "accepted_messages",
            visible: true,
            sortable: false,
            render: function (data, type, full, meta) {
                return (parseInt(full.accepted_messages)+parseInt(full.delivered_messages)+parseInt(full.read_messages));
            }
        },
        {
            data: "failed_messages",
            visible: true,
            sortable: false,
        },
        {
            data: "delivered_messages",
            visible: true,
            sortable: false,
            render: function (data, type, full, meta) {
                return (parseInt(full.delivered_messages)+parseInt(full.read_messages));
            }
        },
        {
            data: "read_messages",
            visible: true,
            sortable: false,
        },
        {
            data: "created",
            visible: true,
            sortable: false,
            render: function (data, type, full, meta) {
                let action = '';
                if(full.status == 'delivered'){
                    action += '<a href="'+base_url+'campaigns/view_campaign_info/'+btoa(full.id)+'" class="text-primary mr-2" ><i class="fa fa-eye"></i></a>'
                    
                }
                if(parseInt(full.failed_messages) > 0){
                    action += '<a href="'+base_url+'campaigns/create_campaign_for_failed_contact/'+btoa(full.id)+'" class="text-warning mr-2" ><i class="fa fa-repeat"></i></a>'
                }
                return action;
            }
        }
    ]
});

let dtCampaignRefreshInterval;
if($('#campaign_dttble').length){
    dtCampaignRefreshInterval = setInterval(function(){
        refresh_campaign_dt();
    },5000);
}

function refresh_campaign_dt(){
    jQuery.ajax({
        type: "get",
        url: base_url + "campaigns/check_in_progress_campaign",
        success: function (res) {
            let json = JSON.parse(res); 
            if(json.status){
                let dtRefreshInterval;
                let dtI = 5;
                dtRefreshInterval = setInterval(function(){
                    if(dtI >0){
                        campaign_dttble.ajax.reload(null, false);
                        --dtI;
                    }else{
                        if (dtRefreshInterval) {
                            campaign_dttble.ajax.reload(null, false);
                            clearInterval(dtRefreshInterval);
                            dtRefreshInterval = null;
                        }
                        refresh_campaign_dt();
                    }                    
                },7000);
            }
            if (dtCampaignRefreshInterval) {
                campaign_dttble.ajax.reload(null, false);
                clearInterval(dtCampaignRefreshInterval);
                dtCampaignRefreshInterval = null;
            }
        }
    });
}

var inputTags = document.querySelector('input[name=group_ids]');
if(inputTags){
    var inputTag = new Tagify(inputTags, {
        enforceWhitelist: true,
        dropdown: {
            closeOnSelect: false,
            enabled: 0,
            classname: 'users-list',
            searchKeys: ['name']
        },
        templates: {
            tag: tagTemplate,
            dropdownItem: suggestionItemTemplate
        },
        whitelist: (userTags != '') ? JSON.parse(userTags) : ''
    });
    
    inputTag.on('dropdown:show dropdown:updated', onDropdownShow)
    inputTag.on('dropdown:select', onSelectSuggestion)
    
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
            <strong>${tagData.name}</strong>
        </div>`;
}

var addAllSuggestionsElm;

function onDropdownShow(e){
    var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;
    if( inputTag.suggestedListItems.length > 1 ){
        addAllSuggestionsElm = getAddAllSuggestionsElm();
        dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild)
    }
}

function onSelectSuggestion(e){
    if( e.detail.elm == addAllSuggestionsElm )
        inputTag.dropdown.selectAll();
}

function getAddAllSuggestionsElm(){
    return inputTag.parseTemplate('dropdownItem', [{
            class: "addAll",
            name: inputTag.whitelist.reduce(function(remainingSuggestions, item){
                return inputTag.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1
            }, 0) + " tags"
        }]
        )
}

$(document).on('change', '#campaign_template', function(e){
    e.preventDefault();
    var template_id = $(this).val();
    var tempPreview = document.querySelector('#campaign_template_preview');
    tempPreview.innerHTML = '';
    jQuery.ajax({
        type: "POST",
        url: base_url + "clients/get_single_template_preview/"+btoa(template_id),
        success: function (res) {
            var json = JSON.parse(res); 
            tempPreview.innerHTML = json.response;
            tempPreview.classList.add('automation_template_div');
        }
    });
});

/*$(document).on('change', '.default_select_value', function(e){
    e.preventDefault();
    let columnName = $(this).val();
});*/

$(document).on('change', '#campaing_tags', function(e){
    e.preventDefault();
    let tags = document.getElementById('campaing_tags');
    let tag_json = (tags.value != '') ? $.parseJSON(tags.value) : '';
    let tag_names = [];
    if (tag_json != undefined && tag_json != '') {
        $.each(tag_json, function (key, val) {
            tag_names.push(val.name)
        });
    }
    let contactCounter = document.querySelector('#contact-counter');
    jQuery.ajax({
        type: "POST",
        url: base_url + "campaigns/load_contacts",
        data: {'tags' : tag_names},
        success: function (response) {
            let json_res = JSON.parse(response);
            contactCounter.innerHTML = "<h6>Total Contacts :"+ json_res.count+"</h6>";
            
        }
    });
});

$(document).on('blur', '#campaign_name', function(e){
    let campaignName = $(this).val();
    jQuery.ajax({
        type: "POST",
        url: base_url + "campaigns/check_campaign_name",
        data: {'campaign_name' : campaignName},
        success: function (response) {
            let campaignNameField = document.querySelector('#campaign_name');
            let userMessage = document.querySelector('.user-message');
            if(response){
                campaignNameField.focus();
                campaignNameField.style.borderColor = 'red';
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close">x</button> Try diffrent name. Campaing name already exist! </button></div>';
            }else{
                userMessage.innerHTML = '';
                campaignNameField.style.borderColor = '#bfc9d4';
            }
        }
    });
});

$(document).on('change', '.notification_campaign', function(e){
    e.preventDefault();
    let notificationType = $(this).val();
    let notificationBlock = document.querySelector('#notification_date_block');
    if(notificationType == 'schedule_campaign'){
        let notification_date = document.createElement('div');
        notification_date.className = 'form-group';
        notification_date.innerHTML = '<label for="hTime">Notification Date</label>'
            + '<input id="notification_date" name="notification_date" value="" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Notification Date">';
        let notificationBlockChild = notificationBlock.children[notificationBlock.children.length];
        notificationBlock.insertBefore(notification_date, notificationBlockChild); 
        
        flatpickr(document.getElementById('notification_date'), {
            enableTime: true,
            dateFormat: "M d, Y H:i",
            minDate: "today",
            maxDate: new Date().fp_incr(7)
        });
    }else{
       notificationBlock.innerHTML = ''; 
    }
});

$(document).on('click', '#btn-create-campaign', function(e){
    let campaignBtn = document.querySelector('#btn-create-campaign');
    campaignBtn.innerHTML = '<span class="loading-spinner"><i clas="fa fa-spinner fa-pulse"></i></span> Create Campaign';
    campaignBtn.disabled = true;
    let data = jQuery("#create_campaign_form").serialize();
    let userMessage = document.querySelector('.user-message');
    userMessage.innerHTML = '';
    /*jQuery.ajax({
        type: "POST",
        url: base_url + "campaigns/save",
        data: data,
        success: function (response) {
            let json = JSON.parse(response);
            campaignBtn.disabled = false;
            if(json.error){
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close">x</button> '+json.error+' </button></div>';
            }else{
                userMessage.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close text-success" data-bs-dismiss="alert" aria-label="Close">x</button> '+json.success+' </button></div>';
                
                setTimeout(function(){
                     window.location.href = base_url + "campaigns";
                }, 2000);
            }
        }
    });*/
});

$(document).on('click', '#btn-create-failed-campaign', function(e){
    let campaignBtn = document.querySelector('#btn-create-failed-campaign');
    
    campaignBtn.disabled = true;
    let data = jQuery("#create_failed_campaign_form").serialize();
    let userMessage = document.querySelector('.user-message');
    userMessage.innerHTML = '';
    jQuery.ajax({
        type: "POST",
        url: base_url + "campaigns/save_failed_campaign",
        data: data,
        success: function (response) {
            let json = JSON.parse(response);
            campaignBtn.disabled = false;
            if(json.error){
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close">x</button> '+json.error+' </button></div>';
            }else{
                userMessage.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close text-success" data-bs-dismiss="alert" aria-label="Close">x</button> '+json.success+' </button></div>';
                
                setTimeout(function(){
                     window.location.href = base_url + "campaigns";
                }, 1000);
            }
        }
    });
});

var campaign_details_dttble = $('#campaign_details_dttble').DataTable({
    "processing": true,
    "serverSide": true,
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
    "order": [[2, "desc"]],
    "dom": 'lftrip',
    "buttons": [
        {extend: 'excel', className: 'btn btn-ouline-success'}
    ],
    "ajax": {
        "url": base_url + 'campaigns/list_campaign_info',
        "type": "POST",
        "data": function (d) {
            d.campaign_id = $(document).find('#campaign_id').val();
        }
    },
    "columns": [
        {
            data: "sr_no",
            visible: true,
            searchable: false,
            sortable: false,
        },
        {
            data: "created",
            visible: true
        },
        {
            data: "name",
            visible: true
        },
        {
            data: "contact_number",
            visible: true,
        },
        {
            data: "is_sent",
            visible: true,
            render: function (data, type, full, meta) {
                if(full.is_sent == 1){
                    return '<span class="badge badge-light-success mb-2 me-4">Sent</span>';
                }
            }
        },
        {
            data: "sent_time",
            visible: true,
        },
        {
            data: "message_status",
            visible: true,
            render: function (data, type, full, meta) {
                let renderStatus = '';
                if(full.message_status != ''){
                    if(full.message_status == 'delivered'){
                         renderStatus = '<span class="badge badge-light-info mb-2 me-4">Delivered</span>';
                    }else if(full.message_status == 'failed'){
                         renderStatus = '<span class="badge badge-light-danger mb-2 me-4">Failed</span>';
                    }else if(full.message_status == 'read'){
                         renderStatus = '<span class="badge badge-light-success mb-2 me-4">Read</span>';
                    }
                }
                return renderStatus
            }
        },
        {
            data: "deliver_time",
            visible: true,
            render: function (data, type, full, meta) {
                let renderStatus = '';
                if(full.message_status != ''){
                    if(full.message_status == 'delivered'){
                         renderStatus = full.deliver_time;
                    }
                    if(full.message_status == 'read'){
                         renderStatus = full.read_time;
                    }
                }
                return renderStatus
            }
        }
    ],
});

$(document).find('.generate-campaign-contact-list').on('click', function (e) {
    e.preventDefault();
    let excelButton = document.querySelector('.generate-campaign-contact-list');
    if(excelButton){
        excelButton.innerHTML = '<i class="fa fa-pulse fa-spinner"></i> wait..';
        excelButton.classList.add('btn-info');
        if(excelButton.classList.contains('btn-success')){
           excelButton.classList.remove('btn-success'); 
        }
    }
    
    var campaign_id = document.querySelector('#campaign_id').value;
    jQuery.ajax({
        type: "GET",
        dataType: 'json',
        url: base_url + 'campaigns/createExcel/'+campaign_id,
        success: function (result) {
            console.log(result);
            if (result.status) {
                if (result.link != undefined && result.link != '') {
                    //window.open(result.link);
                    if (excelButton) {
                        let downloadLink = document.createElement('a');
                        downloadLink.href = result.link;
                        downloadLink.download = '';
                        downloadLink.classList.add('btn', 'btn-success');
                        downloadLink.innerHTML='<i class="fa fa-download"></i> Download';
                        excelButton.replaceWith(downloadLink);
                    }
                    
                } else {
                    new swal("Error!", 'Invalid request. Please try again!', "error");
                }
            } else {
                var message = 'Invalid request. Please try again!';
                if (result.msg != undefined && result.msg != '') {
                    message = result.msg;
                }
                new swal("Error!", message, "error");
            }
            return false;

        }
    });
});



