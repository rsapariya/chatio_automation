var reply_responses_dttble = $('#reply_responses_dttble').DataTable({
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
    "order": [[0, "desc"]],
    "dom": 'lfBrtip',
    "buttons": [
        {extend: 'selectAll', className: 'btn btn-ouline-primary'},
        {extend: 'selectNone', className: 'btn btn-ouline-primary'},
        {
            text: 'Delete Responses',
            className: 'btn btn-ouline-danger',
            action: function (e, dt, node, config) {
                var row_selected = dt.rows({selected: true}).data().length;
                var selected_ids = '';
                if (row_selected > 0) {
                    var msg = '';
                    if(row_selected == 1){
                        msg = row_selected+' record selected.'
                    }else{
                        msg = row_selected+' records selected.'
                    }
                    selected_ids = dt.rows({selected: true}).data().pluck('id').toArray();
                    Swal.fire({
                        title: "Are you sure?",
                        text: msg+" You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                        width: 600,
                        confirmButtonColor: '#26a69a',
                        cancelButtonColor: '#ff7043',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var data = {'response_ids': JSON.stringify(selected_ids)};
                            jQuery.ajax({
                                type: 'POST',
                                url: base_url + 'replyResponse/delete_responses',
                                data: data,
                                cache: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    if (json.status) {
                                        Swal.fire('Success!', 'Responses deleted.', 'success');
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
        "url": base_url + 'replyResponse/list_reply_responses',
        "type": "POST",
        "data": function (d) {
            d.trigger_text = $(document).find('select#trigger_text :selected').val();
            d.query_time = $(document).find('#query_time').val();
        }
    },
    "columns": [
        {data: null, defaultContent: '', sortable: false, className: 'select-checkbox'},
        {data: "from_profile_name", visible: true},
        {data: "phone_number", visible: true},
        {data: "message", visible: true},
        {data: "created_at", visible: true}
    ],
    "select": {
        "style": 'multi',
        "selector": 'td:first-child'
    }
});

function isNumeric(value) {
    return !isNaN(parseFloat(value)) && isFinite(value);
}

function delete_reply_response(id) {
    new swal({
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

/*$(document).find(".basic").select2({
    tags: true
});*/
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