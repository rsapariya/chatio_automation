var chatlogs_dttble = $('#chatlogs_dttble').DataTable({
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
    "order": [[4, "desc"]],
    "dom": 'lfBrtip',
    "buttons": [
        { extend: 'selectAll', className: 'btn btn-ouline-primary' },
        { extend: 'selectNone', className: 'btn btn-ouline-primary' },
        {
            text: 'Delete Log',
            className: 'btn btn-ouline-danger',
            action: function (e, dt, node, config) {
                var row_selected = dt.rows({ selected: true }).data().length;
                var selected_ids = '';
                if (row_selected > 0) {
                    selected_ids = dt.rows({ selected: true }).data().pluck('id').toArray();
                    var msg = '';
                    if (row_selected == 1) {
                        msg = row_selected + ' record selected.'
                    } else {
                        msg = row_selected + ' records selected.'
                    }
                    Swal.fire({
                        title: "Are you sure?",
                        text: msg + " You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                        width: 600,
                        confirmButtonColor: '#26a69a',
                        cancelButtonColor: '#ff7043',
                        allowOutsideClick: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            var data = { 'logs_ids': JSON.stringify(selected_ids) };
                            jQuery.ajax({
                                type: 'POST',
                                url: base_url + 'chatLogs/delete_logs',
                                data: data,
                                cache: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    if (json.status) {
                                        Swal.fire('Success!', 'Text Log deleted.', 'success');
                                        dt.ajax.reload();
                                        setTimeout(function () {
                                            Swal.close()
                                        }, 3000);
                                    } else {
                                        Swal.fire("Error", "Something went wrong.Please try again.", "error");
                                    }
                                },
                                error: function (xhr, status, error) {
                                    Swal.fire("Error", "Something went wrong.Please try again.", "error");
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire("Warning", "Please select Response.", "warning");
                }
            }
        }
    ],
    "ajax": {
        "url": base_url + 'chatLogs/getlogs',
        "type": "POST",
        "data": function (d) {
            d.query_time = $(document).find('#query_time').val();
        }
    },
    "columns": [
        { data: null, defaultContent: '', sortable: false, className: 'select-checkbox' },
        {
            data: "from_profile_name",
            visible: true
        },
        {
            data: "phone_number",
            visible: true,
        },
        {
            data: "message",
            visible: true,
            render: function (data, type, full, meta) {
                var message = full.message;
                var msgStr = "'" + full.message + "'";
                if (message != '' && message != null) {
                    if (message.length >= 40) {
                        var newMessage = message.substr(0, 25);
                        message = newMessage + '...';
                        message += '<a href="javascript:void(0)" class="text-info" onClick="view_more_message(' + full.id + ')"> View more</a>';
                    }
                }
                return message;
            }
        },
        {
            data: "created",
            visible: true,
        },
    ],
    "select": {
        "style": 'multi',
        "selector": 'td:first-child'
    }
});

$(document).find('.filter-chatlogs-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#chatlogs_dttble').DataTable().ajax.reload(null, false);
});

$(document).find('.clear-chatlogs-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#query_time').val('');
    $(document).find('#chatlogs_dttble').DataTable().ajax.reload(null, false);
});

$(document).find('.generate-chatlogs-excel-response-list').on('click', function (e) {
    e.preventDefault();
    var query_time = $(document).find('#query_time').val();
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: { 'query_time': query_time },
        url: base_url + 'chatLogs/createExcel',
        success: function (result) {
            if (result.status) {
                if (result.link != undefined && result.link != '') {
                    window.open(result.link);
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

var apilogs_dttble = $('#apilogs_dttble').DataTable({
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
    "order": [[4, "desc"]],
    "dom": 'lfBrtip',
    "buttons": [
        { extend: 'selectAll', className: 'btn btn-ouline-primary' },
        { extend: 'selectNone', className: 'btn btn-ouline-primary' },
        {
            text: 'Delete Log',
            className: 'btn btn-ouline-danger',
            action: function (e, dt, node, config) {
                var row_selected = dt.rows({ selected: true }).data().length;
                var selected_ids = '';
                if (row_selected > 0) {
                    selected_ids = dt.rows({ selected: true }).data().pluck('id').toArray();
                    var msg = '';
                    if (row_selected == 1) {
                        msg = row_selected + ' record selected.'
                    } else {
                        msg = row_selected + ' records selected.'
                    }
                    Swal.fire({
                        title: "Are you sure?",
                        text: msg + " You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                        width: 600,
                        confirmButtonColor: '#26a69a',
                        cancelButtonColor: '#ff7043',
                        allowOutsideClick: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            var data = { 'logs_ids': JSON.stringify(selected_ids) };
                            jQuery.ajax({
                                type: 'POST',
                                url: base_url + 'chatLogs/delete_logs',
                                data: data,
                                cache: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    if (json.status) {
                                        Swal.fire('Success!', 'API Log deleted.', 'success');
                                        dt.ajax.reload();
                                        setTimeout(function () {
                                            Swal.close()
                                        }, 3000);
                                    } else {
                                        Swal.fire("Error", "Something went wrong.Please try again.", "error");
                                    }
                                },
                                error: function (xhr, status, error) {
                                    Swal.fire("Error", "Something went wrong.Please try again.", "error");
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire("Warning", "Please select Response.", "warning");
                }
            }
        }
    ],
    "ajax": {
        "url": base_url + 'chatLogs/getApilogs',
        "type": "POST",
        "data": function (d) {
            d.query_time = $(document).find('#query_time').val();
        }
    },
    "columns": [
        { data: null, defaultContent: '', sortable: false, className: 'select-checkbox' },
        {
            data: "phone_number",
            visible: true,
        },
        {
            data: "template_name",
            visible: true,
            render: function (data, type, full, meta) {
                var message = '';
                if (full.template_name != '' && full.template_name != null) {
                    message += '<a href="javascript:void(0)" class="text-info bs-tooltip" data-bs-placement="top" data-bs-original-title="View Message" onClick="view_api_message(' + full.id + ')" >' + full.template_name + '</a>';
                }
                return message;
            }
        },
        {
            data: "message_status",
            visible: true,
            render: function (data, type, full, meta) {
                var span = '';
                if (full.message_status == 'delivered') {
                    span += ' <span class="badge badge-light-info mb-2 me-4">DELIVERED</span>'
                } else if (full.message_status == 'sent') {
                    span += ' <span class="badge badge-light-secondary mb-2 me-4">SENT</span>'
                } else if (full.message_status == 'read') {
                    span += ' <span class="badge badge-light-success mb-2 me-4">READ</span>'
                } else if (full.message_status == 'failed') {
                    span += ' <span class="badge badge-light-danger mb-2 me-4">FAILED</span>'
                } else {
                    span += ' <span class="badge badge-light-warning mb-2 me-4">ACCEPTED</span>'
                }
                return span;
            }
        },
        {
            data: "created",
            visible: true
        },
        {
            data: "deliver_time",
            visible: true
        },
        {
            data: "read_time",
            visible: true
        }
    ],
    "select": {
        "style": 'multi',
        "selector": 'td:first-child'
    }
});

$(document).find('.filter-apilogs-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#apilogs_dttble').DataTable().ajax.reload(null, false);
});

$(document).find('.clear-apilogs-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#query_time').val('');
    $(document).find('#apilogs_dttble').DataTable().ajax.reload(null, false);
});

$(document).find('.generate-apilogs-excel-response-list').on('click', function (e) {
    e.preventDefault();
    var query_time = $(document).find('#query_time').val();
    //console.log(query_time);
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: { 'query_time': query_time },
        url: base_url + 'chatLogs/createApiLogExcel',
        success: function (result) {
            if (result.status) {
                if (result.link != undefined && result.link != '') {
                    window.open(result.link);
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

$(document).find('#query_time').daterangepicker({
    opens: 'left',
    autoUpdateInput: false,
    //startDate: moment().subtract(6, 'days'),
    //endDate: moment(),
    showDropdowns: true,
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
});
function view_more_message(id) {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: base_url + 'chatLogs/view_message?id=' + btoa(id),
        cache: "false",
        success: function (result) {
            if (result) {

                $(document).find('.template_details').html('').html(result);
                setTimeout(function () {
                    $(document).find('#modal_view_template').modal('show');
                }, 500);
            }
        }
    });
}

function view_api_message(id) {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: base_url + 'chatLogs/view_api_message?id=' + btoa(id),
        cache: "false",
        success: function (result) {
            $(document).find('.template_details').html('').html(result);
            setTimeout(function () {
                $(document).find('#modal_view_template').modal('show');
            }, 500);
        }
    });
}

//## live chat
$('.hamburger').on('click', function (e) {
    var userBox = document.querySelector('.user-list-box');
    if (userBox.classList.contains('user-list-box-show')) {
        userBox.classList.remove('user-list-box-show');
    } else {
        userBox.classList.add('user-list-box-show');
    }
});

var offset = 0;
var fetchingChat = false;
$('.chat-conversation-box').scroll(function () {
    if ($('.chat-conversation-box').scrollTop() == 0 && !fetchingChat) {
        var loadChats = document.querySelector('#loading-chat');
        loadChats.classList.remove('hide');

        var contact = $('#sender_number').val();
        fetchingChat = true;
        offset += 10; // increment offset to fetch next set of messages
        let chats = document.querySelector('.chat');
        //let topElement = chats.firstElementChild;
        let topElement = chats.children[1];
        jQuery.ajax({
            type: "POST",
            dataType: 'json',
            data: { 'contact': contact, 'offset': offset },
            url: base_url + 'chatLogs/get_chat',
            success: function (response) {
                loadChats.classList.add('hide');
                
                if (response && response.chat) {
                    var html = response.chat;
                    html += chats.innerHTML;
                }
                chats.innerHTML = html;
                if(topElement != '' && topElement != undefined){
                    scrollToSection(topElement.id);
                }
                
                fetchingChat = false;
            },
            error: function () {
                loadChats.classList.add('hide');
                var noChat = document.querySelector('#no-chat');
                noChat.classList.remove('hide');
                fetchingChat = false; // reset flag
            }
        });
    }
});

/*----------------------------------------------
 ************ START LOAD CONTACTS **************
----------------------------------------------*/
/**/

let contactOffSet = 0;
let contactLimit = 0;
var fetchingContacts = false;
let preContactOffset;
let previousRequest;
//let loadContactsInterval;
let loadChatInterval;
let loadContactsInterval = setInterval(function (e) {
    let selListedContact = document.querySelector('#listed_contact');
    if(selListedContact){
        let listedContact = selListedContact.value;
        let filterData = {'limit' : listedContact};
        loadContacts(filterData);
    }
}, 20000);


$('#search-contact').on('keyup', function (e) {
    e.preventDefault();
    var search = $(this).val();
    let loadMore = document.querySelector('#load-more');
    if (search.length ==0 || (search.length >=3 && e.keyCode != 16 && e.keyCode != 17 && e.keyCode != 18 && e.keyCode != 20 && e.keyCode != 27)) {
        if(!loadMore.classList.contains('hide')){
            loadMore.classList.add('hide');
        }
        var totalContact = document.querySelector('.total-contact');
        var contactList = document.querySelector('#contact-list');
        contactList.innerHTML = '<div class="text-center"><span class="spinner-border theme-text-color "></span></div>';
        totalContact.innerHTML = '';
        let filterData = {'search': search};
        if(search.length ==0){
            let listedContact = document.querySelector('#listed_contact').value;
            filterData.limit = listedContact;
        }
        loadContacts(filterData);
        if(search.length ==0){
            if(loadMore.classList.contains('hide')){
                loadMore.classList.remove('hide');
            }
        }
    }
});

$(document).on('click','#load-more-btn', async function(e){
    e.preventDefault();
    clearInterval(loadContactsInterval);
    if (previousRequest) {
        previousRequest.abort();  // Abort previous request if it exists
    }
    const loadMoreBtn = document.querySelector('#load-more-btn');
    
    // Disable the anchor tag by preventing pointer events
    loadMoreBtn.style.pointerEvents = 'none';
    loadMoreBtn.style.opacity = '0.5';       
    loadMoreBtn.style.cursor = 'not-allowed';
    loadMoreBtn.innerHTML = 'Loading...';
    contactOffSet = 7;
    
    const contactList = document.querySelector('#contact-list');
    
    var totalContact = document.querySelector('.total-contact');
    let listedContact = document.querySelector('#listed_contact').value;
    let fetchingContact = (parseInt(listedContact, 10)+parseInt(contactOffSet, 10));
    console.log(fetchingContact);
    
    if( fetchingContact > parseInt(totalContact.innerHTML, 10)){
        let diffOfContact = fetchingContact-parseInt(totalContact.innerHTML, 10);
        contactOffSet -= diffOfContact;
    }
    
    let filterData = { 'start' : listedContact, 'limit' : contactOffSet};
    
    //new code
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data:filterData,
        url: base_url + 'chatLogs/load_more',
        success: function (response) {
            previousRequest = null;
            let fetchedContacts = response.contacts;
            if(fetchedContacts){
                var activeContact = $('#sender_number').val();
                totalContact.textContent = response.total_contact;
                
                let contactHtml = '';
                fetchedContacts.forEach(function (fC) {
                    let profileName = fC.name !== null ? fC.name : fC.from_profile_name;
                    let phoneNumber = fC.phone_number;
                    let isContact = fC.name !== null ? 'yes' : 'no';
                    let contactId = parseInt(listedContact, 10)+1;
                    
                    contactHtml += `<div id="contact-${contactId}" class="person ${activeContact == phoneNumber ? 'active' : ''}" data-contact="${phoneNumber}">`;
                    contactHtml += `<div class="user-info"><div class="f-head"></div><div class="f-body">`;
                    contactHtml += `<div class="meta-info"><span class="user-name" data-name="${profileName}" data-number="${phoneNumber}" data-is_contact="${isContact}">${profileName || phoneNumber}</span>`;
                    contactHtml += `<span class="user-meta-time">${fC.created}</span></div>`;

                    if (fC.message_type == 'text' || fC.message_type == 'button_reply') {
                        if (fC.message) {
                            let message = fC.message.length > 25 ? fC.message.substring(0, 25) + '...' : fC.message;
                            contactHtml += `<span class="preview">${message}</span>`;
                        }
                    } else if (fC.message_type == 'video') {
                        contactHtml += `<span class="preview"><i class="fa fa-file-video text-muted"></i> Video</span>`;
                    } else if (fC.message_type == 'image') {
                        contactHtml += `<span class="preview"><i class="fa fa-file-image text-muted"></i> Image</span>`;
                    } else if (fC.message_type == 'document') {
                        contactHtml += `<span class="preview"><i class="fa fa-file text-muted"></i> Document</span>`;
                    } else if (fC.message_type == 'template') {
                        contactHtml += `<span class="preview"><i class="fa fa-lines text-muted"></i> Template</span>`;
                    }

                    if (fC.unread_message > 0) {
                        contactHtml += `<span class="counter">${fC.unread_message}</span>`;
                    }

                    contactHtml += `</div></div></div>`;
                });
                contactList.innerHTML += contactHtml;
                listedContact = parseInt(listedContact, 10)+contactOffSet;
                document.querySelector('#listed_contact').value = listedContact;
                if(listedContact == parseInt(totalContact.innerHTML, 10)){
                    loadMoreBtn.parentElement.classList.add('hide');
                }
                
                loadMoreBtn.style.pointerEvents = 'auto';
                loadMoreBtn.style.opacity = '1';         
                loadMoreBtn.style.cursor = 'pointer';    
                loadMoreBtn.innerHTML = 'Load More';
                /*loadContactsInterval = setInterval(function (e) {
                    let search = document.querySelector('#search-contact').value;
                    let listedContact = document.querySelector('#listed_contact').value;
                    let loadFilterData = { 'search':search,'limit' : listedContact};
                    loadContacts(loadFilterData);
                }, 20000);*/
            }
        }
    });
    //new code 
    
    
    
   /* 
    //Old Code 
     try {
        let contacts_json = await fetchContacts(filterData);
        totalContact.textContent = contacts_json.total_contact;
        contactList.innerHTML += contacts_json.contacts;
        listedContact = parseInt(listedContact, 10)+contactOffSet;
        document.querySelector('#listed_contact').value = listedContact;
        if(listedContact == parseInt(totalContact.innerHTML, 10)){
            loadMoreBtn.parentElement.classList.add('hide');
            console.log(listedContact);
        }
    }
    catch (error) {
        console.log('Error fetching contacts');
    }finally {
        fetchingContacts = false;
    }
    //Old Code  
    
    
    // Re-enable the anchor tag
    loadMoreBtn.style.pointerEvents = 'auto';
    loadMoreBtn.style.opacity = '1';         
    loadMoreBtn.style.cursor = 'pointer';    
    loadMoreBtn.innerHTML = 'Load More';
    loadContactsInterval = setInterval(function (e) {
        let search = document.querySelector('#search-contact').value;
        let listedContact = document.querySelector('#listed_contact').value;
        let loadFilterData = { 'search':search,'limit' : listedContact};
        loadContacts(loadFilterData);
    }, 20000);
     **/
});


async function loadContacts(filterData) {
    clearInterval(loadContactsInterval);
    if (previousRequest) {
        previousRequest.abort();  // Abort previous request if it exists
    }
    var totalContact = document.querySelector('.total-contact');
    var contactList = document.querySelector('#contact-list');
    
    try {
        let contacts_json = await fetchContacts(filterData);
        let fetchedContacts = contacts_json.contacts;
        if(fetchedContacts){
                var activeContact = $('#sender_number').val();
                totalContact.textContent = contacts_json.total_contact;
                
                let contactHtml = '';
                let contactId = 1;
                fetchedContacts.forEach(function (fC) {
                    let profileName = fC.name !== null ? fC.name : fC.from_profile_name;
                    let phoneNumber = fC.phone_number;
                    let isContact = fC.name !== null ? 'yes' : 'no';
                    
                    contactHtml += `<div id="contact-${contactId}" class="person ${activeContact == phoneNumber ? 'active' : ''}" data-contact="${phoneNumber}">`;
                    contactHtml += `<div class="user-info"><div class="f-head"></div><div class="f-body">`;
                    contactHtml += `<div class="meta-info"><span class="user-name" data-name="${profileName}" data-number="${phoneNumber}" data-is_contact="${isContact}">${profileName || phoneNumber}</span>`;
                    contactHtml += `<span class="user-meta-time">${fC.created}</span></div>`;

                    if (fC.message_type == 'text' || fC.message_type == 'button_reply') {
                        if (fC.message) {
                            let message = fC.message.length > 25 ? fC.message.substring(0, 25) + '...' : fC.message;
                            contactHtml += `<span class="preview">${message}</span>`;
                        }
                    } else if (fC.message_type == 'video') {
                        contactHtml += `<span class="preview"><i class="fa fa-file-video text-muted"></i> Video</span>`;
                    } else if (fC.message_type == 'audio') {
                        contactHtml += `<span class="preview"><i class="fa fa-file-audio text-muted"></i> Audio</span>`;
                    } else if (fC.message_type == 'image') {
                        contactHtml += `<span class="preview"><i class="fa fa-file-image text-muted"></i> Image</span>`;
                    } else if (fC.message_type == 'document') {
                        contactHtml += `<span class="preview"><i class="fa fa-file text-muted"></i> Document</span>`;
                    } else if (fC.message_type == 'template') {
                        contactHtml += `<span class="preview"><i class="fa fa-lines text-muted"></i> Template</span>`;
                    }

                    if (fC.unread_message > 0) {
                        contactHtml += `<span class="counter">${fC.unread_message}</span>`;
                    }

                    contactHtml += `</div></div></div>`;
                    contactId+=1;
                });
                contactList.innerHTML = contactHtml;
            }
        
        /*totalContact.innerHTML = contacts_json.total_contact;
        contactList.innerHTML = contacts_json.contacts;*/
    } catch (error) {
        console.log('failed to load');
    }
    loadContactsInterval = setInterval(function (e) {
        let search = document.querySelector('#search-contact').value;
        let listedContact = document.querySelector('#listed_contact').value;
        let filterData = {'search': search,'limit' : listedContact};
        loadContacts(filterData);
    }, 20000);
}


function fetchContacts(fData){
    clearInterval(loadContactsInterval);
    if (previousRequest) {
        previousRequest.abort();  // Abort previous request if it exists
    }
    return new Promise((resolve, reject) => {
        
        previousRequest = jQuery.ajax({
            type: "POST",
            dataType: 'json',
            data:fData,
            url: base_url + 'chatLogs/contact_filter',
            success: function (response) {
                previousRequest = null;
                resolve(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                reject(new Error(textStatus || errorThrown));
            }
        });
    });
}


/*$('#contact-list').scroll(async function (){
    const contactList = document.querySelector('#contact-list');
    //alert('scroll-top : '+ contactList.scrollTop+',   list-height: '+contactList.clientHeight+',    scroll-height'+contactList.scrollHeight);
    
    if (contactList.scrollTop + contactList.clientHeight >= contactList.scrollHeight) {
         
        
        contactList.style.overflow = 'hidden';
        var totalContact = document.querySelector('.total-contact');
        fetchingContacts = true;
        contactOffSet += 7;
        let lodingMoreContact = document.querySelector('#loading-more-contact');
        try {
            let contacts_json = await fetchContacts(null, contactOffSet);
            //alert(contacts_json);
            contactList.style.overflow = 'auto';
            if(lodingMoreContact){
                lodingMoreContact.remove();
            }
            totalContact.textContent = contacts_json.total_contact;
            contactList.innerHTML += contacts_json.contacts;
        } catch (error) {
            contactList.style.overflow = 'auto';
            if(lodingMoreContact){
                lodingMoreContact.remove();
            }
            console.error('Error fetching contacts:', error);
        }finally {
            fetchingContacts = false;
        }
    }
});*/


/*--------------------------------------------
 ************ END LOAD CONTACTS **************
--------------------------------------------*/


$(document).on("click",".person", function (e) {
    
    clearInterval(loadContactsInterval);
    clearInterval(loadChatInterval);
    e.preventDefault();
    let thisPerson = $(this);
    
    fetchingChat = false;
    offset = 0;

    var chatSideBlock = document.querySelector('.chat_side_block');
    if(chatSideBlock){
        if (!chatSideBlock.classList.contains('hide')) {
            chatSideBlock.classList.add('hide');
        }
    }

    var chats = document.querySelector('.chat');
    chats.innerHTML = '';

    var userBox = document.querySelector('.user-list-box');
    if (userBox.classList.contains('user-list-box-show')) {
        userBox.classList.remove('user-list-box-show');
    }

    var noChat = document.querySelector('#no-chat');
    noChat.classList.add('hide');

    var contact = this.getAttribute('data-contact');
    document.querySelector("#sender_number").value = contact;
    
    loadChat(contact);
    checkForMember(contact);
    //checkIsSubscribed(contact);
    var persons = document.querySelectorAll('.person');
    persons.forEach(function (person) {
        if (person.classList.contains('active')) {
            person.classList.remove('active');
        }
    });
    //let profile = $(this).find('img').attr('src');
    
    let name = $(this).find('.user-name').data('name');
    let number = $(this).find('.user-name').data('number');
    let is_contact = $(this).find('.user-name').data('is_contact');
    
    /*let siteUrl = base_url.replace(/^https?:\/\//, '');
    let wsUrl = siteUrl.replace(/\/$/, '');
    var conn = new WebSocket("wss://"+wsUrl+":8282");
    var client = {
        user_id: number,
        recipient_id: null,
        message: null
    };

    conn.onopen = function(e) {
        conn.send(JSON.stringify(client));
        $('#messages').append('<font color="green">Successfully connected as user '+ client.user_id +'</font><br>');
    };*/
    
    
    let headName = document.querySelector(".current-chat-user-name .name");
    headName.innerHTML = name !== '' ? name+'<br/><small>'+number+'</small>' : '<small>'+number+'</small>';
    if(document.querySelector('#save-contact')){
        document.querySelector('#save-contact').innerHTML = is_contact == 'yes' ? '<i class="fa fa-pencil fa-xl"></i>' : '<i class="fa fa-save fa-xl text-info"></i>'
    }
    
    $(this).addClass('active');
    $(document).find('.chat-meta-user').addClass('chat-active');
    $(document).find('.chat-conversation-box').addClass('ps');
    $(document).find('.chat-footer').addClass('chat-active');

    $(document).find('.chat-box').css('height', 'calc(100vh - 158px)');
    $(document).find('.chat-not-selected').css('display', 'none');
    $(document).find('.chat-box-inner').css('height', '100%');
    
    let activePerson = document.querySelector('.person.active');
    let counterElement = activePerson.querySelector('.counter');
    if (counterElement) {
        counterElement.remove();
    }
    loadChatInterval =setInterval(function (e) {
        var LoadNewChat = document.querySelector('.active-chat');
        if (LoadNewChat != null){
            loadLatestChats();
        } 
    }, 15000);
    
    loadContactsInterval = setInterval(function (e) {
        let search = document.querySelector('#search-contact').value;
        let listedContact = document.querySelector('#listed_contact').value;
        let filterData = {'search' : search,'limit' : listedContact};
        loadContacts(filterData);
    }, 20000);
    
});

$('#reload-chat').on('click', function (e) {
    $(this).addClass('fa-spin');
    loadLatestChats();
});

function loadChat(contact, offset) {
    var loadChats = document.querySelector('#loading-chat');
    loadChats.classList.remove('hide');
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: { 'contact': contact },
        url: base_url + 'chatLogs/get_chat',
        success: function (response) {
            setTimeout(function (e) {
                loadChats.classList.add('hide');
                var chats = document.querySelector('.chat');
                chats.innerHTML = response.chat;
                chats.classList.add('active-chat');
                
                let chatCarousel = document.querySelectorAll('.carousel');
                chatCarousel.forEach(function (chatCar) {
                    var carousel = new bootstrap.Carousel(chatCar, {
                        interval: 2000,
                        wrap: false
                    });
                });
                
                var freeWindow = document.querySelector('.free-window');
                if (!response.allow_send_msg) {
                    freeWindow.innerHTML = '<div><button type="button" class="btn btn-borderless send-template-btn free-send-template"><i class="fa fa-newspaper"></i>&nbsp;&nbsp;Send Template</button>'
                    + '<h6 class="mb-0"><b>Your free 24-hour window is over.</b></h6></div>';
                    freeWindow.classList.add('justify-content-center');
                } else {
                    if (freeWindow.classList.contains('justify-content-center')) {
                        freeWindow.classList.remove('justify-content-center');
                    }
                    freeWindow.innerHTML = '<label class="custom-file-upload"><input type="file" class="choose-file" name="choose-file" id="choose-file" />'
                        + '<span><i class="fa fa-plus"></i></span></label>'
                        + '<button type="button" class="btn btn-borderless send-template-btn"><i class="fa fa-newspaper"></i></button>'
                        + '<div class="emoji-picker"></div>'
                        + '<input type="text" class="form-control mail-write-box" id ="send-msg" />'
                        + '<button type="button" class="btn btn-dark send-msg-btn"><i class="fa fa-paper-plane theme-text-color"></i></button>';

                    $('.mail-write-box').emojioneArea({
                        search: false,
                        filtersPosition: "bottom",
                        tones: false,
                        shortcuts: false,
                        events: {
                            keyup: function (editor, event) {
                                if (event.which == 13) {
                                    $('.emojionearea-editor').blur();
                                    $('.send-msg-btn').trigger('click');
                                }
                            }
                        }
                    });
                }
                var conBox = document.querySelector('.chat-conversation-box');
                conBox.scroll({
                    top: conBox.scrollHeight,
                    behavior: 'smooth'
                });
            }, 1000);
        }
    });
}

function loadLatestChats() {
    var activeChat = document.querySelector('.active-chat');
    if (activeChat) {
        let chatId = activeChat.lastChild.id;
        let id = chatId.replace('chat-', '');

        var contact = $('#sender_number').val();
        var reloadChats = document.querySelector('#reload-chat');
        var sendButton = document.querySelector('.send-msg-btn');
        if (sendButton) {
            sendButton.disabled = true;
        }

        jQuery.ajax({
            type: "POST",
            dataType: 'json',
            data: { 'contact': contact, 'id': id },
            url: base_url + 'chatLogs/get_latest_chat',
            success: function (response) {
                if (sendButton) {
                    sendButton.disabled = false;
                }
                if (response.chat) {
                    let latestChat = document.querySelector('.latest_chat');
                    if (latestChat) {
                        latestChat.remove();
                    }
                    activeChat.insertAdjacentHTML('beforeend', '<span class="text-center text-muted latest_chat"><b>New messages</b></span>');
                    activeChat.insertAdjacentHTML('beforeend', response.chat);
                    var conBox = document.querySelector('.chat-conversation-box');
                    conBox.scroll({
                        top: conBox.scrollHeight,
                        behavior: 'smooth'
                    });
                }
                setTimeout(function(e){
                     reloadChats.classList.remove('fa-spin');
                },1000);
            }
        });
    }
}

function checkForMember(contact) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: { 'contact': contact },
        url: base_url + 'chatLogs/get_assigned_member',
        success: function (response) {
            var agentName = document.querySelector('.member-name');
            if(agentName){
                if (!response.status) {
                    agentName.innerHTML = '';
                } else {
                    agentName.innerHTML = '<a href="javascript:void(0)" class="btn" id="remove-member">'+response.name+'</a>';
                }
            }
        }
    });
}

$(document).on('keypress', '.emojionearea-editor', function (e) {
    //console.log(e.keyCode);
    if (e.keyCode == 13) {
        send_msg();
    }
});

$(document).on('click', '.send-msg-btn', function (e) {
    send_msg();
});

function send_msg() {
    var msg = document.querySelector('#send-msg').value;
    var contact = $('#sender_number').val();
    if (msg.trim().length > 0) {
        var sendButton = document.querySelector('.send-msg-btn');
        sendButton.disabled = true;
        jQuery.ajax({
            type: "POST",
            dataType: 'json',
            data: { 'contact': contact, 'msg': msg },
            url: base_url + 'chatLogs/send_message',
            success: function (response) {
                sendButton.disabled = false;
                $('.mail-write-box').val('');
                //$('.emojionearea-editor').val();
                let search = document.querySelector('#search-contact').value;
                console.log('send: '+contactOffSet);
                loadContacts(search);
                if (response.status) {
                    loadChat(contact);
                }
            }
        });
    }
}

$(document).on('click','#delete_chat_msg', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    Swal.fire({
        title: "Are you sure?",
        text: "You want to remove it?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        width: 600,
        confirmButtonColor: '#26a69a',
        cancelButtonColor: '#ff7043',
        allowOutsideClick: false
    }).then(function (result) {
        if (result.isConfirmed) {
            var data = { 'logs_ids': JSON.stringify(id) };
            jQuery.ajax({
                type: 'POST',
                url: base_url + 'chatLogs/delete_logs',
                data: data,
                cache: false,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json.status) {
                        var contact = document.querySelector("#sender_number").value;
                        loadChat(contact);
                    } else {
                        Swal.fire("Error", "Something went wrong.Please try again.", "error");
                    }
                }
            });
        }
    });
});

$(document).delegate('#choose-file', 'change', function (e) {
    e.preventDefault();
    var file = e.target.files[0];
    $('#select_document_model').modal('show');

    var fileTitle = $('#file-title');
    fileTitle.text(file.name);

    var previewImage = document.getElementById('preview-image');
    var previewDocument = document.getElementById('preview-document');

    var previewVideo = document.getElementById('preview-video');

    var previewNo = document.getElementById('no-preview');
    var noPreviewInfo = document.getElementById('no-preview-info');

    if (file.type.startsWith('image/')) {
        if (previewImage.classList.contains('hide')) {
            previewImage.classList.remove('hide');
        }
    } else if (file.type.startsWith('video/')) {
        if (previewVideo.classList.contains('hide')) {
            previewVideo.classList.remove('hide');
        }
    } else if (file.type.startsWith('application/vnd')) {
        if (previewNo.classList.contains('hide')) {
            previewNo.classList.remove('hide');
        }
    } else {
        if (previewDocument.classList.contains('hide')) {
            previewDocument.classList.remove('hide');
        }
    }

    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (file.type.startsWith('image/')) {
                previewImage.src = e.target.result;
            } else if (file.type.startsWith('video/')) {
                var videoURL = URL.createObjectURL(file);
                previewVideo.src = videoURL;
                previewVideo.type = file.type;
                previewVideo.style.display = 'block';
            } else if (file.type.startsWith('application/vnd')) {
                var megabytes = file.size / (1024 * 1024);
                noPreviewInfo.innerHTML = Math.ceil(megabytes) + ' XLSX';
            } else {
                previewDocument.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
});

$(document).on('click','.view-image-popup', function(){
    let caption = $(this).data('caption');
    let url = $(this).data('url');
    if(url != ''){
        $('#modal-image').attr('src', url);
        $('#modal-caption').text(caption);
        $('#image-popup-modal').modal('show');
    }
});

$('.btn-close-modal').on('click', function (e) {
    e.preventDefault();
    reset_modal();
});

$(document).on('click', '.send-template-btn', function(e){
    e.preventDefault();
    let contact = document.querySelector("#sender_number").value;
    $(".livechat_modal_block").load(base_url + 'chatLogs/get_templates/'+contact, function () {
        $(document).find('#send_template_modal').modal('show');
    });
});

$(document).on('click','#send-template', function(e){
  e.preventDefault();
  let $this = $(this);
  $this.attr('disabled','disabled');
  let data = jQuery("#send_template_frm").serialize();
  let userMessage = document.querySelector('.user-message');
  userMessage.innerHTML = '';
  let contact = document.querySelector("#sender_number").value;
    
  jQuery.ajax({
        type: "POST",
        url: base_url + "chatLogs/send_template",
        data: data,
        success: function (response) {
            $this.removeAttr('disabled');
            var json = JSON.parse(response); 
            
            if(!json.status){
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
            }else{
                userMessage.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                            +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button> Your send template request has been accepted!</button></div>';
                setTimeout(function(e){
                    userMessage.innerHTML = '';
                    $(document).find('#send_template_modal').modal('hide');
                    loadChat(contact);
                }, 2000);
            }
        }
    });
});

$(document).delegate('#template_id','change', function(e){
    e.preventDefault();
    var template_id = $(this).val();
    var tempPreview = document.querySelector('#template_preview');
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

function reset_modal() {
    var fileTitle = document.getElementsByClassName('file-title');
    fileTitle.innerHTML = '';
    var previewImage = document.getElementById('preview-image');
    previewImage.src = '';
    previewImage.classList.add('hide');

    var previewDocument = document.getElementById('preview-document');
    previewDocument.src = '';
    previewDocument.classList.add('hide');

    var previewVideo = document.getElementById('preview-video');
    previewVideo.src = '';
    previewVideo.type = '';
    previewVideo.classList.add('hide');

    var previewNo = document.getElementById('no-preview');
    var noPreviewInfo = document.getElementById('no-preview-inof');
    previewNo.classList.add('hide');

    var ModalUserMsg = document.querySelector('.modal-user-message');
    ModalUserMsg.innerHTML = '<p></p>';

    $('#select_document_model').modal('hide');
}

$('#send-file').on('click', function (e) {
    e.preventDefault();
    var sendButton = $(this);
    sendButton.attr('disabled', 'disabled');
    var inputFile = document.getElementById('choose-file');
    var contact = $('#sender_number').val();
    var file = inputFile.files.item(0);
    var data = new FormData();
    data.append('contact', contact);
    data.append('file', file);
    var ModalUserMsg = document.querySelector('.modal-user-message');
    ModalUserMsg.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
        + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>Please Wait...!</button></div>';

    jQuery.ajax({
        type: "POST",
        url: base_url + 'chatLogs/send_message',
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (response) {
            var json = JSON.parse(response);
            sendButton.removeAttr('disabled');
            if (json.status) {
                ModalUserMsg.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                    + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>Request Accespted!</button></div>';
                reset_modal();
                loadChat(contact);
            } else {
                ModalUserMsg.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                    + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>' + json.error + '</button></div>';
            }
        }
    });
});

function scrollToSection(id) {
    let chat = document.getElementById(id);
    chat.scrollIntoView({ behavior: 'smooth' });
}

$(document).on('click', '#save-contact',function(e){
    e.preventDefault();
    var contactNumber = $('#sender_number').val();
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data:{'contact' : contactNumber},
        url: base_url + 'chatLogs/get_contact_info',
        success: function (response) {
            let chatSideBlock = document.querySelector('.chat_side_block');
            if (chatSideBlock.classList.contains('hide')) {
                chatSideBlock.classList.remove('hide');
            }
            let chatSideBlockBody = document.querySelector('.chat_side_block_body');
            chatSideBlockBody.innerHTML = response.contact;
            var inputTags = document.querySelector('input[name=tags]');
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
                    whitelist: response.tags
                });
                inputTag.on('dropdown:show dropdown:updated', onDropdownShow);
            }
            
        }
    }); 
});

$(document).on('click','#remove-member', function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "You want to remove assigned member!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        width: 600,
        confirmButtonColor: '#26a69a',
        cancelButtonColor: '#ff7043',
        allowOutsideClick: false
    }).then(function (result) {
        if (result.isConfirmed) {
            var contact = document.querySelector("#sender_number").value;
            jQuery.ajax({
                type: "POST",
                data: { 'contact': contact },
                url: base_url + 'chatLogs/remove_assigned_member',
                success: function (response) {
                    var json = jQuery.parseJSON(response);
                    if (!json.status) {
                        new swal("Error!", json.error, "error");
                    } else {
                        var memberName = document.querySelector('.member-name');
                        memberName.innerHTML = '';
                    }
                }
            });
        }
    });
});

$('.chat_side_block_close').on('click', function (e) {
    e.preventDefault();
    var chatSideBlock = document.querySelector('.chat_side_block');
    if (!chatSideBlock.classList.contains('hide')) {
        chatSideBlock.classList.add('hide');
    }
});

jQuery(document).on('click', '.btn-save-contact', function (e) {
    e.preventDefault();
    let _this = $(this);
    _this.attr('disabled', 'disabled');
    var data = jQuery("#save_contact_frm").serialize();
    jQuery.ajax({
        type: "POST",
        data: data,
        url: base_url + 'chatLogs/save_contact',
        success: function (response) {
            _this.removeAttr('disabled');
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                var msg = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Failed! </strong>' + json.msg + '</button></div>';
                $('#save_contact_frm .user-message').html(msg);
                $('#save_contact_frm .user-message').show();
                setTimeout(function () {
                    $('#save_contact_frm .user-message').html('');
                    $('#save_contact_frm .user-message').hide();
                }, 2000);
            } else{
                var msg = '<div class="alert alert-light-primary alert-dismissible fade show border-0 mb-4" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button><strong>Success!</strong> '+json.msg+'</button></div>';
                $('#save_contact_frm .user-message').html(msg);
                $('#save_contact_frm .user-message').show();
                setTimeout(function () {
                    $('#save_contact_frm .user-message').html('');
                    $('#save_contact_frm .user-message').hide();
                    
                    var chatSideBlock = document.querySelector('.chat_side_block');
                    if (!chatSideBlock.classList.contains('hide')) {
                        chatSideBlock.classList.add('hide');
                    }
                    if(json.member){    
                        var memberName = document.querySelector('.member-name');
                        memberName.innerHTML = '<a href="javascript:void(0)" class="btn" id="remove-member">'+json.member+'</a>';
                    }
                    var chatSideBlock = document.querySelector('#save-contact');
                    chatSideBlock.innerHTML = '<i class="fa fa-pencil fa-xl text-muted" aria-hidden="true"></i>';
                }, 2000);
            }
        }
    });
});

/*function checkIsSubscribed(contact) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: { 'contact': contact },
        url: base_url + 'chatLogs/is_subscribed',
        success: function (response) {
            let isSubscribedBlk = document.querySelector('.is_subscribed_blk');
            if (response.status) {
                if(response.is_subscribed == 1){
                    isSubscribedBlk.innerHTML = '<a href="javascript:void(0);" id="is_subscribed" data-status="subscribed"><span class="badge badge-info"><i class="fa fa-bell"></i> Subscribed</span></a>';
                }else{
                    isSubscribedBlk.innerHTML = '<a href="javascript:void(0);" id="is_subscribed" data-status="unsubscribed"><span class="badge badge-warning"><i class="fa fa-bell-slash"></i> Unsubscribed</span></a>';
                }
            }else{
                isSubscribedBlk.innerHTML = '';
            }
        }
    });
}
jQuery(document).on('click', '#is_subscribed', function (e) {
    e.preventDefault();
    let contact = document.querySelector("#sender_number").value;
    let status = $(this).data('status');
    console.log(contact+' '+status);
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: { 'contact': contact, 'status': status },
        url: base_url + 'chatLogs/update_subscribe_status',
        success: function (response) {
            let isSubscribedBlk = document.querySelector('.is_subscribed_blk');
            if (response.status) {
                new swal("Success!", 'Status has been changed!', "success");
                if(status == 'unsubscribed'){
                    isSubscribedBlk.innerHTML = '<a href="javascript:void(0);" id="is_subscribed" data-status="subscribed"><span class="badge badge-info"><i class="fa fa-bell"></i> Subscribed</span></a>';
                }else{
                    isSubscribedBlk.innerHTML = '<a href="javascript:void(0);" id="is_subscribed" data-status="unsubscribed"><span class="badge badge-warning"><i class="fa fa-bell-slash"></i> Unsubscribed</span></a>';
                }
            } else {   
                new swal("Error!", 'Something went wrong!!', "error");
            }
        }
    });
});*/




/***Tagify*********/

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

function onDropdownShow(e){
    var dropdownContentElm = e.detail.tagify.DOM.dropdown.content;
}



