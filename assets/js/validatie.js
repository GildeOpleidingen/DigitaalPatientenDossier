(() => {
    'use strict'

    const forms = document.querySelectorAll('.needs-validation')

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})()

$(document).ready(function () {
    const btn = $("#btn");
    const form = $("#formulier");

    if (btn.length && form.length) {
        btn.on("click", function () {
            if (form[0].checkValidity()) {
                form.append('<input type="hidden" name="form" value="1">');

                $(this).prop("disabled", true);
                $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                setTimeout(function () {
                    form[0].submit();
                }, 500);
            } else {
                form[0].reportValidity();
            }
        });
    }
});