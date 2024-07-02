var indiamart_list_dttble = $('#indiamart_list_dttble').DataTable({
    processing: true,
    serverSide: true,
    "lengthMenu": [10, 20, 50, 100, 200],
    "language": {
        "paginate": {
            "previous": "<i class='flaticon-arrow-left-1'></i>",
            "next": "<i class='flaticon-arrow-right'></i>"
        },
        "info": "Showing page _PAGE_ of _PAGES_"
    },
    dom: 'lfBrtip',
    buttons: {
        dom: {
            button: {
                className: 'btn btn-light'
            },
        },
        buttons: [
            {extend: 'excelHtml5', text: 'To Excel'},
            {extend: 'selectAll', className: 'btn bg-primary'},
            {extend: 'selectNone', className: 'btn bg-danger'},
            {text: 'Send Message', action: function (e, dt, node, config) {
                    var row_seleted = indiamart_list_dttble.rows('.selected').data().length;
                    var selected_ids = '';
                    if (row_seleted != undefined && row_seleted > 0) {
                        selected_ids = $.map(indiamart_list_dttble.rows('.selected').data(), function (item) {
                            return item.id;
                        });
                        swal.fire({
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
                        Swal.fire("Warning", "Please select Lead.", "warning");
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
});
$(document).find('.filter-indiamart-list').on('click', function (e) {
    e.preventDefault();
    $(document).find('#indiamart_list_dttble').DataTable().ajax.reload(null, false);
});