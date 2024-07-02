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

window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('edit_profile');
    var invalid = $('.edit_profile .invalid-feedback');

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

$(document).find('.div-dashboard-wrapper').on('click', function (event) {
    event.preventDefault();
    var url = $(this).data('url');
    location.replace(url);
});