var clients_dttble = $('#clients_dttble').DataTable({
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
            text: 'Delete Contacts',
            className: 'btn btn-ouline-danger',
            action: function (e, dt, node, config) {
                var row_selected = dt.rows({selected: true}).data().length;
                var selected_ids = '';
                if (row_selected > 0) {
                    var msg = '';
                    if(row_selected == 1){
                        msg = row_selected+' Contact selected.'
                    }else{
                        msg = row_selected+' Contacts selected.'
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
                                url: base_url + 'clients/delete_contacts',
                                data: data,
                                cache: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    if (json.status) {
                                        Swal.fire('Success!', 'Contacts deleted.', 'success');
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
        'type': 'GET',
        "url": base_url + 'clients/list_clients',
    },
    "columns": [
        { data: null, defaultContent: '', sortable: false, className: 'select-checkbox' },
        {
            data: "name",
            visible: true
        },
        {
            data: "phone_number",
            visible: true,
        },
        {
            data: "group_ids",
            visible: true,
        },
        {
            data: "created_at",
            visible: true,
        },
        {
            data: "is_subscribed",
            visible: true,
            render: function (data, type, full, meta) {
                var status;
                if(full.is_subscribed == 1){
                    status = '<a href="javascript:void(0)" class="is_subscribed" data-id="'+btoa(full.id)+'" data-status="subscribed"><span class="badge badge-info mb-2 me-4"><i class="fa fa-bell"></i> Subscribed</span></a>';
                }else{
                    status = '<a href="javascript:void(0)" class="is_subscribed" data-id="'+btoa(full.id)+'" data-status="unsubscribed"><span class="badge badge-warning mb-2 me-4"><i class="fa fa-bell-slash"></i> Unsubscribed</span></a>';
                }
                return status;
            }
        },
        {
            data: "is_deleted",
            visible: true,
            searchable: false,
            sortable: false,
            render: function (data, type, full, meta) {
                var action = '<td>';
                
                action += '<ul class="table-controls">';
                action += '<li><a href="' + base_url + 'contacts/edit/' + btoa(full.id) + '" class="btn btn-outline-primary bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Edit"><i class="fa fa-pencil-alt p-1 br-6 mb-1"></i></a></li>';
                action += '<li><a href="javascript:void(0)" onclick="delete_users(' + full.id + ')"  class="btn btn-outline-danger bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash p-1 br-6 mb-1"></i></a></li>';
                if(full.is_subscribed == 1){
                    action += '<li><a href="javascript:void(0)" onclick="get_templates(' + full.id + ')"  class="btn btn-outline-info bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Send Meta Template"><i class="fa fa-newspaper p-1 br-6 mb-1"></i></a></li>';
                }
                
                if(full.automation_name != null && full.automation_name != ''){
                    action += '<li><a href="javascript:void(0)" onclick="remove_automation(' + full.id + ')"  class="btn btn-outline-danger bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Remove Automation"><i class="fa fa-microchip p-1 br-6 mb-1"></i></a></li>';
                }else{
                    action += '<li><a href="javascript:void(0)" onclick="add_automation(' + full.id + ')"  class="btn btn-outline-primary bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Add Automation"><i class="fa fa-microchip p-1 br-6 mb-1"></i></a></li>';
                }
                
                action += '</ul>'; 
                action += '</td>';
                return action;
            }
        }
    ],
    "select": {
        "style": 'multi',
        "selector": 'td:first-child'
    }
});


/*========================
    Start Send Template
==========================*/
function get_templates(id){
    $(".contact_modal_block").load(base_url + 'clients/get_templates/'+btoa(id), function () {
        $(document).find('#send_template_modal').modal('show');
    });
}

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

$(document).on('change','#template_preview .default_select_value', function(e){
    var $this = $(this);
    var field = $this.val();
    var contact_id = document.querySelector('#contact_id').value;
    var userMessage = document.querySelector('.user-message');
    userMessage.innerHTML = '';
    jQuery.ajax({
        type: "POST",
        url: base_url + "clients/check_field_value",
        data: {"field": field, "contact_id" : contact_id},
        success: function (response) {
            var json = JSON.parse(response); 
            if(!json.status){
                $this.val($this.find('option:first').val());
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
                setTimeout(function(e){
                    userMessage.innerHTML = '';
                }, 2500);
            }
        }
    });
});

$(document).on('click','#send-template', function(e){
  e.preventDefault();
  var $this = $(this);
  $this.attr('disabled','disabled');
  var data = jQuery("#send_template_frm").serialize();
  jQuery.ajax({
        type: "POST",
        url: base_url + "clients/send_template",
        data: data,
        success: function (response) {
            $this.removeAttr('disabled');
            var json = JSON.parse(response); 
            var userMessage = document.querySelector('.user-message');
            if(!json.status){
                var errorMsg = 'Please enter value for empty fields';
                if(json.error){
                    errorMsg = json.error;
                }
                
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+errorMsg+'</button></div>';
                if (json.fields) {
                    var fieldsArray = JSON.parse(json.fields); 
                    console.log(fieldsArray);
                    fieldsArray.forEach(function(fieldId) {
                        var inputField = $(fieldId); 
                        console.log(inputField);
                        inputField.css('border-color', 'red'); 
                    });
                    setTimeout(function(e){
                        fieldsArray.forEach(function(fieldId) {
                            var inputField = $(fieldId); 
                            inputField.css('border-color', 'black'); 
                        });
                    }, 5000);
                }
                setTimeout(function(e){
                    userMessage.innerHTML = '';
                }, 3000);
            }else{
                userMessage.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                            +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button> Your send template request has been accepted!</button></div>';
                setTimeout(function(e){
                    userMessage.innerHTML = '';
                    $(document).find('#send_template_modal').modal('hide');
                }, 2000);
            }
        }
    });
});

$(document).on('click', '.is_subscribed', function(e){
    e.preventDefault();
    let status = $(this).data('status');
    let id = $(this).data('id');
    jQuery.ajax({
        type: "POST",
        url: base_url + "clients/change_status",
        data: {"status": status, "id" : id},
        success: function (response) {
            var json = JSON.parse(response); 
            if (json.status) {
                Swal.fire('Success!', 'Status has been changed.', 'success');
                clients_dttble.ajax.reload();
            } else {
                Swal.fire("Error", "Something went wrong.", "error");
            }
            setTimeout(function () {
                Swal.close()
            }, 3000);
        }
    });
});
/*========================
    End Send Template
==========================*/


function add_automation(id){
    $(".contact_modal_block").load(base_url + 'clients/automation/'+id, function () {
        $(document).find('#automation_modal').modal('show');
    });
}

function remove_automation(id){
    new swal({
        title: "Are you sure?",
        text: "You want to remove automation for this contact!?",
        type: "warning",
        padding: '2em',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plz!",
        cancelButtonColor: '#d33',
    }).then(function (result) {
        if (result.value) {
            jQuery.ajax({
                type: "POST",
                url: base_url + "clients/remove_automation/"+btoa(id),
                success: function (response) {
                    var json = JSON.parse(response); 
                    var userMessage = document.querySelector('.userMessage');
                    if(json.status){
                        clients_dttble.ajax.reload(null, false);
                        userMessage.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>Automation has been removed!</button></div>';
                        setTimeout(function(e){
                            userMessage.innerHTML = '';
                        }, 1500);
                    }else{
                        userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
                        setTimeout(function(e){
                            userMessage.innerHTML = '';
                        }, 1500);
                    }
                }
            });
        }
    })
}

$(document).delegate('.save-automation','click', function(e){
    e.preventDefault();
    $(this).attr('disabled','disabled');
    var data = jQuery("#automation_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "clients/add_automation",
        data: data,
        success: function (response) {
            var json = JSON.parse(response);
            var userMessage = document.querySelector('#automation_frm .user-message');
            if(!json.status){
                userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
                setTimeout(function(e){
                    userMessage.innerHTML = '';
                }, 1500);
            }else{
                userMessage.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>Automation has been added!</button></div>';
                setTimeout(function(e){
                    userMessage.innerHTML = '';
                    $(document).find('#automation_modal').modal('hide');
                    clients_dttble.ajax.reload(null, false);
                }, 1500);
            }
        }
    });
});

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
    let initialCountry = "in";
    var iti = window.intlTelInput(input, {
        separateDialCode: true,
        hiddenInput: "phone_number_full",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });
    
    let countryCode = document.querySelector("#country_code");
    if(countryCode.value != null){
        let countries = iti.p;
        countries.forEach(function(country) {
            if(country.dialCode == countryCode.value){
                initialCountry = country.iso2
            }
        });
    }
    iti.setCountry(initialCountry); 
    
    input.addEventListener('input', function () {
        var countryCode = iti.getSelectedCountryData().dialCode;
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
            jQuery.ajax({
                type: "POST",
                url: base_url + "clients/delete_contact/"+id,
                success: function (response) {
                    var json = JSON.parse(response); 
                    var userMessage = document.querySelector('.userMessage');
                    if(json.status){
                        clients_dttble.ajax.reload(null, false);
                        userMessage.innerHTML = '<div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>Contact has been removed!</button></div>';
                        setTimeout(function(e){
                            userMessage.innerHTML = '';
                        }, 1500);
                    }else{
                        userMessage.innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
                        setTimeout(function(e){
                            userMessage.innerHTML = '';
                        }, 1500);
                    }
                }
            });
            
            //window.location.href = base_url + "contacts/action/delete/" + id;
        }
    });
}

function addDate(date, id) {
    $(document).find('#' + id).val(date);
}
let birth_date = document.getElementById('anniversary_date');
if(birth_date){
    flatpickr(birth_date, {
        enableTime: false,
        dateFormat: "M d, Y",
        defaultDate: birthDate
    });
}
let anniversary_date = document.getElementById('anniversary_date');
if(anniversary_date){
    flatpickr(anniversary_date, {
        enableTime: false,
        dateFormat: "M d, Y",
        defaultDate: anniversaryDate
    });
}
var inputTags = document.querySelector('input[name=group_ids]');
if(inputTags){
    var inputTag = new Tagify(inputTags, {
        enforceWhitelist: true,
        dropdown: {
            closeOnSelect: false,
            maxItems: Infinity,
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
                setTimeout(function(e){
                    document.querySelector('.userMessage').innerHTML = '';
                }, 3000);
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
    var totalColumn = $('#total_columns').val();
    
    if(curValue == null){
        $('#total_columns').val(totalColumn-1);
    }
    
    $('.mapping').each(function() {
        var $select = $(this);
        if ($select.attr('id') !== thisField) {
            $select.find('option[value="' + thisValue + '"]').remove();
            if (curValue && thisValue !== curValue) {
                var normVal = curValue.replace("_", " ");
                var optionToAdd = $('<option>').val(curValue).text(normVal.toUpperCase());
                $select.append(optionToAdd);
            }
        }
    });
    
    $this.val(thisValue); // Update the current select with the selected value
    
    localStorage.setItem(thisField, thisValue);
});

/*var countryCode = document.querySelector("#country_code");
if (countryCode != null) {
    var itiCC = window.intlTelInput(countryCode, {
        initialCountry: "in",
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });
    
    var code = itiCC.getSelectedCountryData().dialCode;
    countryCode.value = code;
    
    itiCC.input.addEventListener("countrychange", function() {
        var code = itiCC.getSelectedCountryData().dialCode;
        countryCode.value = code;
    });
}*/


$(document).on('click', '.btn-import-data', function(e){
    e.preventDefault();
    var _This = $(this);
    _This.attr('disabled','disabled');
    var totalColumn = $('#total_columns').val();
    if(totalColumn != undefined){
        var selects = document.querySelectorAll('.mapping');
        var nameIsSelected = false;
        var phonenumberIsSelected = false;
        selects.forEach(function(select) {
            var selectedValue = select.value;
            if (selectedValue === 'name') {
                nameIsSelected = true;
            }
            if (selectedValue === 'phone_number') {
                phonenumberIsSelected = true;
            }
        });
        
        if (nameIsSelected && phonenumberIsSelected) {
            if(totalColumn > 0){
                new swal({
                    title: "Are you sure?",
                    text: totalColumn+" fields are unmapped! do you want to continue?",
                    type: "warning",
                    padding: '2em',
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    cancelButtonColor: '#d33',
                }).then(function (result) {
                    if (result.value) {
                        //importContacts($(this));
                        country_dropdown();
                    }else{
                        _This.removeAttr('disabled');
                    }
                });
            }else{
                country_dropdown();
            }
        }else{
           window.scrollTo(0, 0);
           
           $(this).removeAttr('disabled');
           var isRequiredError = '';
            if(!nameIsSelected){
                isRequiredError += '<p>Name value must be assign to any related field.</p>';
            }
            if(!phonenumberIsSelected){
                isRequiredError += '<p>Phone Number value must be assign to any related field.</p>';
            }
            
            var userMessage = document.querySelector('.userMessage');
            userMessage.innerHTML = '<div class="alert alert-light-warning alert-dismissible fade show border-0 mb-4" role="alert">'
                                    +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+isRequiredError+'</button></div>';
            var selects = document.querySelectorAll('.mapping');
            selects.forEach(function(select) {
                select.style.border = '1px solid red';
            });
            
            setTimeout(function(e){
                userMessage.innerHTML = '';
                selects.forEach(function(select) {
                    select.style.border = '1px solid #bfc9d4';
                });
            }, 3000);
        }
    }else{
        var userMessage = document.querySelector('.userMessage');
        userMessage.innerHTML = '<div class="alert alert-light-warning alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>Please select file</button></div>';
        setTimeout(function(e){
            userMessage.innerHTML = '';
        }, 3000);
    }
});

function country_dropdown(){
    $(".contact_modal_block").load(base_url + 'clients/manage_country_code', function () {
        var country_select = document.querySelector("#country_select");
        var iti = window.intlTelInput(country_select, {
            initialCountry: "in",
            separateDialCode: true,
            hiddenInput: "countrycode",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
        });
        country_select.style.paddingLeft = "0";
        $(document).find('#manage_country_code').modal('show');
    }); 
}
$(document).on('click','.close_country_code_modal',function(e) {
    e.preventDefault();
    $(document).find('#manage_country_code').modal('hide');
    document.querySelector('.btn-import-data').removeAttribute('disabled');
});

$(document).on('change','.is_contry_code_added',function(e) {
    e.preventDefault();
    var countryDropdown = document.querySelector("#country_dropdown_block");
    if ($(this).val() === "no") {
        countryDropdown.classList.remove('hide');
    }else{
        countryDropdown.classList.add('hide');
    }
});
$(document).on('click','.import-contacts',function(e) {
   e.preventDefault();
   $(this).attr('disabled','disabled');
   var data = jQuery("#country_code_frm").serialize();
   var countrycode = document.querySelector('.iti__selected-dial-code').innerHTML.replace(/\+/g, '');
   data += '&countrycode=' + encodeURIComponent(countrycode);
   importContacts(data)
});



function importContacts(data){
    $(document).find('#manage_country_code').modal('hide');
    var fdata = jQuery(".add_mutliple_client").serialize();
    fdata += '&' + data;
    let importData = document.querySelector('.btn-import-data');
    importData.innerHTML = '<span><i class="fa fa-pulse fa-spinner"></i> importing..</span>';
    
    jQuery.ajax({
        type: "POST",
        url: base_url + "clients/save_multiple",
        data: fdata,
        success: function (result) {
            window.scrollTo(0, 0);
            var json = JSON.parse(result);
            document.querySelector('.btn-import-data').removeAttribute('disabled');
            if(!json.status){
                importData.innerHTML = '<span>contact imported</span>';
                document.querySelector('.userMessage').innerHTML = '<div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">'
                                +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>'+json.error+'</button></div>';
                setTimeout(function(e){
                    document.querySelector('.userMessage').innerHTML = '';
                }, 3000);
            }else{
                let reportHTML ='<div class="justify-content-start">'
                +'<h6 class="text-start mb-0"><b>Total contacts found in excel : '+json.total_records+'</b></h6>'
                +'<hr/>'
                +'<h6 class="text-start mb-0"><b>Duplicate contacts in excel : '+json.duplicate_records+'</b></h6>'
                +'<hr/>'
                +'<h6 class="text-start mb-0"><b>Already existing contacts in database : '+json.exist_records+'</b></h6>'
                +'<hr/>'
                +'<h6 class="text-start mb-0"><b>Invalid contacts : '+json.invalid_records+'</b></h6>'
                +'<hr/>'
                +'<h6 class="text-start mb-0"><b>Total succesfully imported contacts : '+json.saved_records+'</b></h6>'
                +'<hr/>'
                +'</div>';
                Swal.fire({
                    title: "<h5><b>Excel Import Report</b></h5>",
                    html: reportHTML,
                }).then(function (result) {
                    window.location.href = base_url+'contacts';
                });
            }
        }
    });
}




