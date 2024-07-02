$(document).find(".basic").select2({
    tags: true
});

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
        {extend: 'selectAll', className: 'btn btn-ouline-primary'},
        {extend: 'selectNone', className: 'btn btn-ouline-primary'},
        {
            text: 'Delete Log',
            className: 'btn btn-ouline-danger',
            action: function (e, dt, node, config) {
                var row_selected = dt.rows({selected: true}).data().length;
                var selected_ids = '';
                if (row_selected > 0) {
                    selected_ids = dt.rows({selected: true}).data().pluck('id').toArray();
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
                            var data = {'logs_ids': JSON.stringify(selected_ids)};
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
        {data: null, defaultContent: '', sortable: false, className: 'select-checkbox'},
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
                if (message.length >= 40) {
                    var newMessage = message.substr(0, 25);
                    message = newMessage + '...';
                    message += '<a href="javascript:void(0)" class="text-info" onClick="view_more_message(' + full.id + ')"> View more</a>';
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

$(document).find('#query_time').daterangepicker({
    opens: 'left',
    startDate: moment().subtract(6, 'days'),
    endDate: moment(),
    showDropdowns: true,
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
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

function view_more_message(id) {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: base_url + 'chatLogs/view_message?id=' + btoa(id),
        cache: "false",
        success: function (result) {
            $(document).find('.template_details').html('').html(result);
            setTimeout(function () {
                $(document).find('#modal_view_template').modal('show');
            }, 500);
        }
    });
}

$(document).find('.generate-chatlogs-excel-response-list').on('click', function (e) {
    e.preventDefault();
    var query_time = $(document).find('#query_time').val();
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: {'query_time': query_time},
        url: base_url + 'chatLogs/createExcel',
        success: function (result) {
            console.log(result);
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

//## live chat

$('#search-contact').on('keyup', function (e) {
    e.preventDefault();
    var search = $(this).val();
    if (e.keyCode != 16 && e.keyCode != 17 && e.keyCode != 18 && e.keyCode != 20 && e.keyCode != 27) {
        var totalContact = document.querySelector('.total-contact');
        var contactList = document.querySelector('#contact-list');
        contactList.innerHTML = '<div class="text-center"><span class="spinner-border theme-text-color "></span></div>';
        totalContact.innerHTML = '';
        loadContacts(search);
    }
});

function loadContacts(search) {
    var totalContact = document.querySelector('.total-contact');
    var contactList = document.querySelector('#contact-list');
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: {'search': search},
        url: base_url + 'chatLogs/contact_filter',
        success: function (response) {
            var contacts = response.customers;
            var list = '';
            if (response.count > 0) {
                $.each(contacts, function (key, value) {
                    list += '<div class="person" data-contact="' + value.phone_number + '"><div class="user-info"><div class="f-head"></div><div class="f-body"><div class="meta-info">';
                    list += '<span class="user-name" data-name="' + value.phone_number + '">+' + value.phone_number + '</span>';
                    list += '<span class="user-meta-time">' + value.created + '</span></div>';
                    list += '<span class="preview">' + value.from_profile_name + '</span></div></div></div>';
                });
            } else {
                list += '<div class="person" data-chat="person6"><div class="user-info"><div class="f-body"><div class="meta-info text-center"><span>Data not fount!</span></div></div></div></div>';
            }

            setTimeout(function (e) {
                contactList.innerHTML = '';
                totalContact.innerHTML = response.count;
                contactList.innerHTML = list;
            }, 500);
        }
    });
}

$(document).delegate(".person", "click", function (e) {
    e.preventDefault();

    var contact = this.getAttribute('data-contact');
    document.querySelector("#sender_number").value = contact;
    loadChat(contact);

    var persons = document.querySelectorAll('.person');
    persons.forEach(function (person) {
        if (person.classList.contains('active')) {
            person.classList.remove('active');
        }
    });
    //let profile = $(this).find('img').attr('src');

    var name = $(this).find('.user-name').data('name');
    var headName = document.querySelector(".current-chat-user-name .name");
    headName.textContent = name;

    $(this).addClass('active');
    $(document).find('.chat-meta-user').addClass('chat-active');
    $(document).find('.chat-conversation-box').addClass('ps');
    $(document).find('.chat-footer').addClass('chat-active');

    $(document).find('.chat-box').css('height', 'calc(100vh - 158px)');
    $(document).find('.chat-not-selected').css('display', 'none');
    $(document).find('.chat-box-inner').css('height', '100%');
    //checkForActiveContact();
});

function loadChat(contact) {
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: {'contact': contact},
        url: base_url + 'chatLogs/get_chat',
        success: function (response) {
            var chats = document.querySelector('.chat');
            chats.innerHTML = response;
            chats.classList.add('active-chat');

            var bottomElement = chats.lastElementChild;
            bottomElement.scrollIntoView({behavior: 'auto', block: 'end', inline: 'end'});

        }
    });
}

$('#send-msg').on('keyup', function (e) {
    if (e.keyCode == 13) {
        send_msg();
    }
});

$('.send-msg-btn').on('click', function (e) {
    send_msg();
});

function send_msg() {

    var msg = $('#send-msg').val();
    var contact = $('#sender_number').val();
    if (msg.length > 1) {
        jQuery.ajax({
            type: "POST",
            dataType: 'json',
            data: {'contact': contact, 'msg': msg},
            url: base_url + 'chatLogs/send_message',
            success: function (response) {
                loadContacts();
                if (response.status) {
                    $('#send-msg').val('');
                    loadChat(contact);
                }
            }
        });
    }
}

/*$('.download-file').on('click', function(e){
 var url = $(this).data('link');
 var filename = $(this).data('filename');
 
 });*/


/*$('.emoji-picker').emojioneArea({
 //search: false,
 //filtersPosition: "bottom",
 //tones: false,
 //shortcuts: false,
 
 });*/
