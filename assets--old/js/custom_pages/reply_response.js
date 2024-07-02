var reply_responses_dttble = $('#reply_responses_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [50, 100, 200, 500],
    "language": {
        "paginate": {
            "previous": "<i class='flaticon-arrow-left-1'></i>",
            "next": "<i class='flaticon-arrow-right'></i>"
        },
        "info": "Showing page _PAGE_ of _PAGES_"
    },
    order: [[0, "desc"]],
    dom: 'lfBrtip',
    buttons: {
        dom: {
            button: {
                className: 'btn btn-light'
            },
        },
        buttons: [
            {extend: 'selectAll', className: 'btn btn-c-gradient-3'},
            {extend: 'selectNone', className: 'btn btn-c-gradient-4'},
            {text: 'Delete Responses', className: 'btn btn-c-gradient-1', action: function (e, dt, node, config) {
                    var row_seleted = reply_responses_dttble.rows('.selected').data().length;
                    var selected_ids = '';
                    if (row_seleted != undefined && row_seleted > 0) {
                        selected_ids = $.map(reply_responses_dttble.rows('.selected').data(), function (item) {
                            return item.id;
                        });
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won't be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes, delete it!",
                            width: 600,
                            confirmButtonColor: '#26a69a',
                            cancelButtonColor: '#ff7043',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.value) {
                                var data = {'response_ids': JSON.stringify(selected_ids)};
                                /*console.log(data);
                                return false;*/
                                $.ajax({
                                    type: 'POST',
                                    url: base_url + 'replyResponse/delete_responses',
                                    data: data,
                                    cache: false,
                                    success: function (data) {
                                        var json = $.parseJSON(data);
                                        if (json.status) {
                                            Swal.fire('Success!', 'Responses deleted.', 'success');
                                            $('#reply_responses_dttble').DataTable().ajax.reload(null, false);
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
                        Swal.fire("Warning", "Please select Lead.", "warning");
                    }
                }},
        ],
    },
    "sAjaxSource": base_url + 'replyResponse/list_reply_responses',
//    ajax: {
//        'type': 'GET',
//        "url": base_url + 'replyResponse/list_reply_responses',
//    },
    columns: [
        {"data": null, defaultContent: ''},
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
            data: "response",
            visible: true,
        },
        {
            data: "name",
            visible: true,
        },
        {
            data: "mobile_number",
            visible: true,
        },
        {
            data: "created_at",
            visible: true,
        },
//        {
//            data: "is_deleted",
//            visible: true,
//            searchable: false,
//            sortable: false,
//            render: function (data, type, full, meta) {
//                var action = '<td><ul class="table-controls">';
////                action += '<li><a href="javascript:void(0)" onclick="delete_reply_response(' + full.id + ')"  title="Delete"><i class="flaticon-delete p-1 br-6 mb-1"></i></a></li>';
//                action += '</ul></td>';
//                return action;
//            }
//        }
    ], drawCallback: function () {
    },
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
        aoData.push({"name": "trigger_text", "value": $(document).find('select#trigger_text :selected').val()},
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
});

function isNumeric(value) {
    return !isNaN(parseFloat(value)) && isFinite(value);
}


function delete_reply_response(id) {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Reply Response!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "replyResponse/action/delete/" + btoa(id);
        }
    })
}


$(document).find(".basic").select2({
    tags: true
});

$(document).find('#query_time').daterangepicker({
    opens: 'left',
    startDate: moment().subtract(6, 'days'),
    endDate: moment(),
    showDropdowns: true,
    dateLimit: {days: 60},
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
});

//$(document).find('#query_time').val('');

$(document).find('.filter-response-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#reply_responses_dttble').DataTable().ajax.reload(null, false);
});
$(document).find('.clear-response-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('select#trigger_text').val('').trigger('change');
    $(document).find('#query_time').val('');
    $(document).find('#reply_responses_dttble').DataTable().ajax.reload(null, false);
});

$(document).find('.generate-excel-response-list').on('click', function (e) {
    e.preventDefault();
    var trigger_text = $(document).find('select#trigger_text :selected').val();
    var query_time = $(document).find('#query_time').val();
    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        data: {'trigger_text': trigger_text, 'query_time': query_time},
        url: base_url + 'replyResponse/createExcel',
        success: function (result) {
            console.log(result);
            if (result.status) {
                if (result.link != undefined && result.link != '') {
                    window.open(result.link);
                } else {
                    swal("Error!", 'Invalid request. Please try again!', "error");
                }
            } else {
                var message = 'Invalid request. Please try again!';
                if (result.msg != undefined && result.msg != '') {
                    message = result.msg;
                }
                swal("Error!", message, "error");
            }
            return false;

        }
    });
});