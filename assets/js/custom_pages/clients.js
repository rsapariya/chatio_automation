var clients_dttble = $('#clients_dttble').DataTable({
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
        "url": base_url + 'clients/list_clients',
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
            data: "email",
            visible: true
        },
        {
            data: "phone_number_full",
            visible: true,
        },
        {
            data: "cust_birth_date",
            visible: true,
        },
        {
            data: "cust_anniversary_date",
            visible: true,
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

                var action = '<td><ul class="table-controls">';
                action += '<li><a href="' + base_url + 'contacts/edit/' + btoa(full.id) + '" class="btn btn-outline-primary bs-tooltip"  data-bs-placement="top" data-bs-original-title="Edit"><i class="fa fa-pencil-alt p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_users(' + full.id + ')"  class="btn btn-outline-danger bs-tooltip"  data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash p-1 br-6 mb-1"></i></a></li>';
                action += '</ul></td>';
                return action;
            }
        }
    ]
});

//$('.dataTables_length select').select2({
//    minimumResultsForSearch: Infinity,
//    width: 'auto'
//});

$(document).find('.btn-add-client').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});
$(document).find('.btn-add-multiple-client').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});
$(document).find('.btn-file-download').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('target');
    location.replace(url);
});

$("#hEmail").inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: !1, onBeforePaste: function (m, a) {
        return(m = m.toLowerCase()).replace("mailto:", "")
    },
    definitions: {"*": {
            validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~-]",
            cardinality: 1,
            casing: "lower"
        }
    }
});
$("#hPhoneNo").inputmask({mask: "9999999999"});

var input = document.querySelector("#hPhoneNo");
if(input != null){
    var iti = window.intlTelInput(input, {
        initialCountry: "in",
        separateDialCode: true,
        hiddenInput: "phone_number_full",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });
    
    input.addEventListener('input', function () {
        var countryCode = iti.getSelectedCountryData().dialCode;
        console.log(countryCode);
        $(document).find('#country_code').val(countryCode);
    });
}



window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('add_client');
    var invalid = $('.add_client .invalid-feedback');

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

function delete_users(id) {
    new swal({
        title: "Are you sure?",
        text: "You won't be able to revert this Contact!",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            window.location.href = base_url + "contacts/action/delete/" + id;
        }
    })
}

function addDate(date, id) {
    $(document).find('#' + id).val(date);
}

flatpickr(document.getElementsByClassName('flatpickr'), {
    enableTime: false,
    dateFormat: "M d, Y",
    //defaultDate: new Date()
});


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
    console.log('length: '+inputTag.suggestedListItems.length);
    
    if( inputTag.suggestedListItems.length > 1 ){
        addAllSuggestionsElm = getAddAllSuggestionsElm();

        // insert "addAllSuggestionsElm" as the first element in the suggestions list
        dropdownContentElm.insertBefore(addAllSuggestionsElm, dropdownContentElm.firstChild)
    }
}

function onSelectSuggestion(e){
    if( e.detail.elm == addAllSuggestionsElm )
        inputTag.dropdown.selectAll();
}

// create a "add all" custom suggestion element every time the dropdown changes
function getAddAllSuggestionsElm(){
    // suggestions items should be based on "dropdownItem" template
    return inputTag.parseTemplate('dropdownItem', [{
            class: "addAll",
            //name: "Add all",
            name: inputTag.whitelist.reduce(function(remainingSuggestions, item){
                return inputTag.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1
            }, 0) + " tags"
        }]
        )
}

$('#add_new_column').on('click', function(e){
    e.preventDefault();
    var column = $(this).data('column');
    var count = column+1;
    if(column <= 10){
        var html ='<div class="col-12 mt-2 new_column_'+count+'"><label for="hgroupNo">New Column</label><div class="row">'
                    +'<div class="col-10"><div class="form-group">'
                    +'<input type="text" class="form-control" name="column[]" placeholder="" value=""></div></div>'
                    +'<div class="col-2 d-flex justify-content-end"><button type="button" class="btn btn-danger remove_column" data-column="'+count+'"><i class="fa fa-minus"></i></button></div>'
                    +'</div></div>';
        $(document).find('.dynamic_column').append(html);
        $(this).data('column', count);
    }
    if(count == 10){
        $('#add_new_column').addClass('hide');
    }
});
$(document).delegate(".remove_column", "click", function (e) {
    e.preventDefault();
    var column = $(this).data('column');
    var count = $('#add_new_column').data('column');
    $(document).find('.new_column_' + column).remove();
    count--;
    if(count < 10){
        $('#add_new_column').removeClass('hide');
    }
    $('#add_new_column').data('column', count);
});

$('#client_file').on('change', function(e){
    e.preventDefault();
    var data = new FormData($(".add_mutliple_client")[0]);
    jQuery.ajax({
        type: "POST",
        url: base_url + "clients/map_columns",
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (result) {
            var json = JSON.parse(result);
            if(!json.status){
                document.querySelector('.userMessage').innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
            }else{
                localStorage.clear();
                document.querySelector('.map_columns').innerHTML  = json.success;
            }
            
            
            //console.log($('.map_columns').innerHTML);
        }
    });
});


$(document).on('change', '.mapping', function(e) {
    e.preventDefault();
    var $this = $(this);
    var thisField = $this.attr('id');
    var thisValue = $this.val();
    var curValue = localStorage.getItem(thisField);
    
    $('.mapping').each(function() {
        var $select = $(this);
        if ($select.attr('id') !== thisField) {
            $select.find('option[value="' + thisValue + '"]').remove();
            if (curValue && thisValue !== curValue) {
                var optionToAdd = $('<option>').val(curValue).text(curValue);
                $select.append(optionToAdd);
            }
        }
    });
    
    $this.val(thisValue); // Update the current select with the selected value
    
    localStorage.setItem(thisField, thisValue);
});

$(document).on('click', '.btn-import-data', function(e){
    e.preventDefault();
    $(this).attr('disabled','disabled');
    var data = jQuery(".add_mutliple_client").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "clients/save_multiple",
        data: data,
        success: function (result) {
            var json = JSON.parse(result);
            $(this).removeAttr('disabled');
            if(!json.status){
                document.querySelector('.userMessage').innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
            }else{
                
            }
        }
    });
    
});
