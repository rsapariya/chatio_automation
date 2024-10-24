var indiamart_list_dttble = $('#indiamart_list_dttble').DataTable({
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
    "order": [[10, "desc"]],
    "dom": 'lfBrtip',
    "buttons": [
        {extend: 'selectAll', className: 'btn btn-ouline-primary'},
        {extend: 'selectNone', className: 'btn btn-ouline-primary'},
        {
            text: 'Send Message', action: function (e, dt, node, config) {
            var row_seleted = indiamart_list_dttble.rows('.selected').data().length;
            var selected_ids = '';
                if (row_seleted != undefined && row_seleted > 0) {
                    selected_ids = $.map(indiamart_list_dttble.rows('.selected').data(), function (item) {
                        return item.id;
                    });
                    new swal.fire({
                        title: "Send Message to Leads!",
    //                            text: "Write Your Message",
                        input: 'textarea',
                        inputLabel: "Add ||name|| for replace sender name",
    //                            inputValue: 'Hello, ||name||',
                        inputAttributes: {
                            required: 'true'
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return "You need to write something!";
                            }
                        },
                        width: 600,
                        showCancelButton: true,
                        confirmButtonColor: '#26a69a',
                        cancelButtonColor: '#ff7043',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.value) {
                            var data = {'lead_ids': JSON.stringify(selected_ids), 'message': result.value};
                            console.log("data: " + data);
                            $.ajax({
                                type: 'POST',
                                url: base_url + 'indiamart_leads/send_leads_message',
                                data: data,
                                cache: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    if (json.status) {
                                        Swal.fire('Success!', 'Message will be sent to selected leads in while', 'success');
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 4000);
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
                    new Swal.fire("Warning", "Please select Lead.", "warning");
                }
            },
        },
    ],
    "ajax": {
        "url": base_url + 'indiamart_leads/list_indiamart_leads',
        "type": "POST",
        "data": function (d) {
            d.city = $(document).find('select#city :selected').val();
            d.mcat_name = $(document).find('select#mcat_name :selected').val();
            d.query_time = $(document).find('#query_time').val();
        }
    },
    "columns": [
        {data: null, defaultContent: '', orderable: false, className: 'select-checkbox'},
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
            data: "mobile",
            visible: true,
        },
        {
            data: "subject",
            width: "2%",
            visible: true,
            render: function (data, type, full, meta) {
                var subject = full.subject 
                if(subject.length > 25){
                    let newSubject = subject.substr(0, 25);
                    subject = newSubject += '...'
                    subject += ' <a href="javascript:(0);" class="text-info" onclick="view_subject('+full.id+')">view more</a>'
                }
                return subject;
            }
        },
        {
            data: "company",
            visible: true,
        },
        {
            data: "city",
            visible: true,
            render: function (data, type, full, meta) {
                return full.city + ' - ' + full.state;
            }
        },
        {
            data: "product_name",
            visible: true,
        },
        {
            data: "message",
            width: "5%",
            visible: true,
            render: function (data, type, full, meta) {
                var message = full.message 
                if(message.length > 35){
                    let newMessage = message.substr(0, 35);
                    message = newMessage += '...'
                    message += ' <a href="javascript:(0);" class="text-info" onclick="view_message('+full.id+')">view more</a>'
                }
                return message;
            }
        },
        {
            data: "mcat_name",
            visible: true,
        },
        {
            data: "query_time",
            visible: true,
        }
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
$(document).find('.filter-indiamart-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#indiamart_list_dttble').DataTable().ajax.reload(null, false);
});
$(document).find('.clear-indiamart-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('select#city').val('').trigger('change');
    $(document).find('select#mcat_name').val('').trigger('change');
    $(document).find('#query_time').val('');
    $(document).find('#indiamart_list_dttble').DataTable().ajax.reload(null, false);
});
$(document).find('.generate-indiamart-excel-response-list').on('click', function (e) {
    e.preventDefault();
    var city = $(document).find('select#city :selected').val();
    var mcat_name = $(document).find('select#mcat_name :selected').val();
    var query_time = $(document).find('#query_time').val();

    let data = {
        'source' : 'indiamart',
        'city' : city,
        'mcat_name' : mcat_name, 
        'query_time' : query_time
    }
    generate_lead_excel(data);
});

function generate_lead_excel(data){
   jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: data,
        url: base_url + 'indiamart_leads/createExcel',
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
}

var tradeindia_list_dttble = $('#tradeindia_list_dttble').DataTable({
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
    "order": [[9, "desc"]],
    "dom": 'lfBrtip',
    "buttons": [
        {extend: 'selectAll', className: 'btn btn-ouline-primary'},
        {extend: 'selectNone', className: 'btn btn-ouline-primary'},
        {
            text: 'Send Message', action: function (e, dt, node, config) {
            var row_seleted = tradeindia_list_dttble.rows('.selected').data().length;
            var selected_ids = '';
                if (row_seleted != undefined && row_seleted > 0) {
                    selected_ids = $.map(tradeindia_list_dttble.rows('.selected').data(), function (item) {
                        return item.id;
                    });
                    new swal.fire({
                        title: "Send Message to Leads!",
    //                            text: "Write Your Message",
                        input: 'textarea',
                        inputLabel: "Add ||name|| for replace sender name",
    //                            inputValue: 'Hello, ||name||',
                        inputAttributes: {
                            required: 'true'
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return "You need to write something!";
                            }
                        },
                        width: 600,
                        showCancelButton: true,
                        confirmButtonColor: '#26a69a',
                        cancelButtonColor: '#ff7043',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.value) {
                            var data = {'lead_ids': JSON.stringify(selected_ids), 'message': result.value};
                            console.log("data: " + data);
                            $.ajax({
                                type: 'POST',
                                url: base_url + 'indiamart_leads/send_leads_message',
                                data: data,
                                cache: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    if (json.status) {
                                        Swal.fire('Success!', 'Message will be sent to selected leads in while', 'success');
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 4000);
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
                    new Swal.fire("Warning", "Please select Lead.", "warning");
                }
            },
        },
    ],
    "ajax": {
        "url": base_url + 'indiamart_leads/list_tradeindia_leads',
        "type": "POST",
        "data": function (d) {
            d.city = $(document).find('select#ticity :selected').val();
            d.query_time = $(document).find('#tiquery_time').val();
        }
    },
    "columns": [
        {data: null, defaultContent: '', orderable: false, className: 'select-checkbox'},
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
            data: "mobile",
            visible: true,
        },
        {
            data: "subject",
            visible: true,
        },
        {
            data: "company",
            visible: true,
        },
        {
            data: "city",
            visible: true,
            render: function (data, type, full, meta) {
                return full.city + ' - ' + full.state;
            }
        },
        {
            data: "product_name",
            visible: true,
        },
        {
            data: "message",
            visible: true,
            render: function (data, type, full, meta) {
                var message = full.message 
                if(message.length > 35){
                    let newMessage = message.substr(0, 35);
                    message = newMessage += '...'
                    message += ' <a href="javascript:(0);" class="text-info" onclick="view_message('+full.id+')">view more</a>'
                }
                return message;
            }
        },
        {
            data: "query_time",
            visible: true,
        }
    ],
    "select": {
        "style": 'multi',
        "selector": 'td:first-child'
    }
});
$(document).find('#tiquery_time').daterangepicker({
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
$(document).find('.filter-tradeindia-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#tradeindia_list_dttble').DataTable().ajax.reload(null, false);
});
$(document).find('.clear-tradeindia-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('select#ticity').val('').trigger('change');
    $(document).find('#tiquery_time').val('');
    $(document).find('#tradeindia_list_dttble').DataTable().ajax.reload(null, false);
});
$(document).find('.generate-tradeindia-excel-response-list').on('click', function (e) {
    e.preventDefault();
    var city = $(document).find('select#ticity :selected').val();
    var query_time = $(document).find('#tiquery_time').val();

    let data = {
        'source' : 'tradeindia',
        'city' : city,
        'query_time' : query_time
    }
    generate_lead_excel(data);
});


var exportersindia_list_dttble = $('#exportersindia_list_dttble').DataTable({
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
    "order": [[9, "desc"]],
    "dom": 'lfBrtip',
    "buttons": [
        {extend: 'selectAll', className: 'btn btn-ouline-primary'},
        {extend: 'selectNone', className: 'btn btn-ouline-primary'},
        {
            text: 'Send Message', action: function (e, dt, node, config) {
            var row_seleted = exportersindia_list_dttble.rows('.selected').data().length;
            var selected_ids = '';
                if (row_seleted != undefined && row_seleted > 0) {
                    selected_ids = $.map(exportersindia_list_dttble.rows('.selected').data(), function (item) {
                        return item.id;
                    });
                    new swal.fire({
                        title: "Send Message to Leads!",
    //                            text: "Write Your Message",
                        input: 'textarea',
                        inputLabel: "Add ||name|| for replace sender name",
    //                            inputValue: 'Hello, ||name||',
                        inputAttributes: {
                            required: 'true'
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return "You need to write something!";
                            }
                        },
                        width: 600,
                        showCancelButton: true,
                        confirmButtonColor: '#26a69a',
                        cancelButtonColor: '#ff7043',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.value) {
                            var data = {'lead_ids': JSON.stringify(selected_ids), 'message': result.value};
                            console.log("data: " + data);
                            $.ajax({
                                type: 'POST',
                                url: base_url + 'indiamart_leads/send_leads_message',
                                data: data,
                                cache: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    if (json.status) {
                                        Swal.fire('Success!', 'Message will be sent to selected leads in while', 'success');
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 4000);
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
                    new Swal.fire("Warning", "Please select Lead.", "warning");
                }
            },
        },
    ],
    "ajax": {
        "url": base_url + 'indiamart_leads/list_exportersindia_leads',
        "type": "POST",
        "data": function (d) {
            d.city = $(document).find('select#eicity :selected').val();
            d.query_time = $(document).find('#eiquery_time').val();
        }
    },
    "columns": [
        {data: null, defaultContent: '', orderable: false, className: 'select-checkbox'},
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
            data: "mobile",
            visible: true,
        },
        {
            data: "subject",
            visible: true,
        },
        {
            data: "company",
            visible: true,
        },
        {
            data: "city",
            visible: true,
            render: function (data, type, full, meta) {
                return full.city + ' - ' + full.state;
            }
        },
        {
            data: "product_name",
            visible: true,
        },
        {
            data: "message",
            visible: true,
            render: function (data, type, full, meta) {
                var message = full.message 
                if(message.length > 35){
                    let newMessage = message.substr(0, 35);
                    message = newMessage += '...'
                    message += ' <a href="javascript:(0);" class="text-info" onclick="view_message('+full.id+')">view more</a>'
                }
                return message;
            }
        },
        {
            data: "query_time",
            visible: true,
        }
    ],
    "select": {
        "style": 'multi',
        "selector": 'td:first-child'
    }
});
$(document).find('#eiquery_time').daterangepicker({
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
$('#filter-exportersindia-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#exportersindia_list_dttble').DataTable().ajax.reload(null, false);
});
$(document).find('.clear-exportersindia-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('select#eicity').val('').trigger('change');
    $(document).find('#eiquery_time').val('');
    $(document).find('#exportersindia_list_dttble').DataTable().ajax.reload(null, false);
});
$(document).find('.generate-exportersindia-excel-response-list').on('click', function (e) {
    e.preventDefault();
    var city = $(document).find('select#eicity :selected').val();
    var query_time = $(document).find('#eiquery_time').val();

    let data = {
        'source' : 'exportersindia',
        'city' : city,
        'query_time' : query_time
    }
    generate_lead_excel(data);
});



var crm_message_logs_dttble = $('#crm_message_logs_dttble').DataTable({
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
    order: [[6, "desc"]],
    ajax: {
        "url": base_url + 'indiamart_leads/get_message_logs',
        "type": "GET"
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
            data: "customer_name",
            visible: true
        },
        {
            data: "customer_mobile",
            visible: true,
        },
        {
            data: "leads_source",
            visible: true,
            render: function (data, type, full, meta) {
                return full.leads_source.toUpperCase();
            }
        },
        {
            data: "template_name",
            visible: true,
        },
        {
            data: "message_status",
            visible: true,
            render: function (data, type, full, meta) {
                var span = '';
                if(full.message_status == 'delivered'){
                    span += ' <span class="badge badge-light-info mb-2 me-4">DELIVERED</span>'
                }else if(full.message_status == 'sent'){
                    span += ' <span class="badge badge-light-secondary mb-2 me-4">SENT</span>'
                }else if(full.message_status == 'read'){
                    span += ' <span class="badge badge-light-success mb-2 me-4">READ</span>'
                }else if(full.message_status == 'failed'){
                    span += ' <span class="badge badge-light-danger mb-2 me-4">FAILED</span>'
                }else{
                    span += ' <span class="badge badge-light-warning mb-2 me-4">ACCEPTED</span>'
                }
                return span;
            }
        },
        {
            data: "created",
            visible: true,
            render: function (data, type, full, meta) {
                var span = '';
                if(full.created !== '0000-00-00 00:00:00'){
                    span = full.created
                }
                return span;
            }
        },
        /*{
            data: "sent_time",
            visible: true,
        },*/
        {
            data: "deliver_time",
            visible: true,
            render: function (data, type, full, meta) {
                var span = '';
                if(full.deliver_time !== '0000-00-00 00:00:00'){
                    span = full.deliver_time
                }
                return span;
            }
        },
        
        {
            data: "read_time",
            visible: true,
            render: function (data, type, full, meta) {
                var span = '';
                if(full.read_time !== '0000-00-00 00:00:00'){
                    span = full.read_time
                }
                return span;
            }
        },
        /*{
            data: "action",
            visible: true,
            searchable: false,
            sortable: false,
            render: function (data, type, full, meta) {
                var is_template_default = (full.is_default != undefined && full.is_default != null) ? parseInt(full.is_default) : 0;
                var action = '<td><ul class="table-controls">';
                action += '<li><a href="' + base_url + 'automations/view/' + btoa(full.id) + '" title="View" class="btn btn-outline-success"><i class="fa fa-eye"></i></a></li>';
                action += '<li><a href="' + base_url + 'automations/edit/' + btoa(full.id) + '" title="Edit" class="btn btn-outline-primary"><i class="fa fa-pencil"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_automations(' + full.id + ')"  title="Delete" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a></li>';
                
                action += '</ul></td>';
                return action;
            }
        }*/
    ],
    drawCallback: function () {}
});
function view_message(id){
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: base_url + 'indiamart_leads/get_view_data?id='+btoa(id)+'&type=message',
        cache: "false",
        success: function (result) {
            //$('.templates_list').html(result);
            $(document).find('.template_details').html('').html(result);
            setTimeout(function () {
                $(document).find('#modal_view_template').modal('show');
            }, 500);
        }
    });
}
function view_subject(id){
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: base_url + 'indiamart_leads/get_view_data?id='+btoa(id)+'&type=subject',
        cache: "false",
        success: function (result) {
            //$('.templates_list').html(result);
           $(document).find('.template_details').html('').html(result);
            setTimeout(function () {
                $(document).find('#modal_view_template').modal('show');
            }, 500);
        }
    });
}





/*var indiamart_list_dttble = $('#indiamart_list_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [10, 20, 50, 100, 200],
    "language": {
        "paginate": {
            "first": "<i class='fa fa-angles-left'></i>",
            "previous": "<i class='fa fa-angle-left'></i>",
            "next": "<i class='fa fa-angle-right'></i>",
            "last": "<i class='fa fa-angles-right'></i>"
        },
        "info": "Showing page _PAGE_ of _PAGES_"
    },
    dom: 'Bfrtip',
    buttons: {
        dom: {
            button: {
                className: 'btn btn-light'
            },
        },
        buttons: [
            {extend: 'selectAll', className: 'btn btn-primary'},
            {extend: 'excelHtml5', text: 'To Excel'},
            {extend: 'selectNone', className: 'btn btn-danger'},
            {text: 'Send Message', action: function (e, dt, node, config) {
                    var row_seleted = indiamart_list_dttble.rows('.selected').data().length;
                    var selected_ids = '';
                    if (row_seleted != undefined && row_seleted > 0) {
                        selected_ids = $.map(indiamart_list_dttble.rows('.selected').data(), function (item) {
                            return item.id;
                        });
                        new swal.fire({
                            title: "Send Message to Leads!",
//                            text: "Write Your Message",
                            input: 'textarea',
                            inputLabel: "Add ||name|| for replace sender name",
//                            inputValue: 'Hello, ||name||',
                            inputAttributes: {
                                required: 'true'
                            },
                            inputValidator: (value) => {
                                if (!value) {
                                    return "You need to write something!";
                                }
                            },
                            width: 600,
                            showCancelButton: true,
                            confirmButtonColor: '#26a69a',
                            cancelButtonColor: '#ff7043',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.value) {
                                var data = {'lead_ids': JSON.stringify(selected_ids), 'message': result.value};
                                console.log("data: " + data);
                                $.ajax({
                                    type: 'POST',
                                    url: base_url + 'indiamart_leads/send_leads_message',
                                    data: data,
                                    cache: false,
                                    success: function (data) {
                                        var json = $.parseJSON(data);
                                        if (json.status) {
                                            Swal.fire('Success!', 'Message will be sent to selected leads in while', 'success');
                                            setTimeout(function () {
                                                window.location.reload();
                                            }, 4000);
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
                        new Swal.fire("Warning", "Please select Lead.", "warning");
                    }
                }},
        ],
    },
    order: [[9, "desc"]],
    ajax: {
        'type': 'GET',
        "url": base_url + 'indiamart_leads/list_indiamart_leads',
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
            data: "mobile",
            visible: true,
        },
        {
            data: "subject",
            visible: true,
        },
        {
            data: "company",
            visible: true,
        },
        {
            data: "city",
            visible: true,
            render: function (data, type, full, meta) {
                return full.city + ' - ' + full.state;
            }
        },
        {
            data: "product_name",
            visible: true,
        },
        {
            data: "message",
            visible: true,
        },
        {
            data: "mcat_name",
            visible: true,
        },
        {
            data: "query_time",
            visible: true,
        },
//        {
//            data: "query_id",
//            visible: true,
//            searchable: false,
//            sortable: false,
//            render: function (data, type, full, meta) {
//
//                var action = '<td><ul class="table-controls">';
//                action += '</ul></td>';
//                return action;
//            }
//        }
    ],
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        searchable: false,
        targets: 0,
    }],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    fnServerData: function (sSource, aoData, fnCallback) {
        aoData.push({"name": "city", "value": $(document).find('select#city :selected').val()},
                {"name": "mcat_name", "value": $(document).find('select#mcat_name :selected').val()},
                {"name": "query_time", "value": $(document).find('#query_time').val()},
                );
        $.ajax
                ({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
    },
});*/


