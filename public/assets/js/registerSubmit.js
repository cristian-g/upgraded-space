$(document).ready(function() {
    $("#registerSubmit").click(function(event) {

        // Fetch form to apply custom Bootstrap validation
        var form = $("#registerForm");

        if (form[0].checkValidity() === false) {
            // Validation error
            event.preventDefault();
            event.stopPropagation();
        }
        else {
            // Successful validation
            //if $()
        }

        // Validate a form in Bootstrap 4 in the browser using html5 and checkValidity function. Once the form has "passed" validation, add the "was-validated" class to the form, then the inputs will show valid-feedback or invalid-feedback messages
        form.addClass('was-validated');
    });
});