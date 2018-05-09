//registerForm
$(document).ready(function () {

    $('#registerSubmit').click(function() {
        /* Validations go here */

        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirm_password = $('#confirm_password').val();

        if (username.length > 20 || username.length < 0){
            return false;
        }

        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!mailformat.test(email)){
            return false
        }

        var passwordformat = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])");
        if (password.length < 6 || password.length > 12 || !passwordformat.test(password)){
            return false;
        }

        if (confirm_password.localeCompare(password) != 0){
            return false;
        }

        $('#registerForm').submit(); // If all the validations succeeded
    });

    $('#loginSubmit').click(function () {
        var loginId= $('#loginId').val();
        var password = $('#password').val();
        
        if (loginId.includes("@")){
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (!mailformat.test(loginId)){
                alert("email not valid");
                return false
            }
        }else {
            if (loginId.length > 20 || loginId.length < 0){
                alert("username not valid");
                return false;
            }
        }

        var passwordformat = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])");
        if (password.length < 6 || password.length > 12 || !passwordformat.test(password)){
            alert("password not valid");
            return false;
        }

        $('#loginForm').submit(); // If all the validations succeeded
    })

    $('#deleteUserButton').click(function () {
        swal({
            title: "¿Eliminar cuenta?",
            icon: "warning",
            text: "Si eliminas tu cuenta perderás todos tus documentos! Si deseas continuar haz click en 'Eliminar'.",
            buttons: {
                cancel: "Cancelar",
                confirm: "Eliminar"
            },
            dangerMode: true,
        }).then(function(confirm){
            if(confirm){
                $("#deleteUserForm").submit();
            }
        });
    })
});

function checkForm(name, value){
    switch(name){
        case 'username':
            if (value.length > 20 || value.length < 0){
                $('#feedback-username').css('display', 'block');
                $('input[name="username"]').css('border-color', '#dc3545');
            }else{
                $('#feedback-username').css('display', 'none');
                $('input[name="username"]').css('border', '1px solid #ced4da');
            }
            break;
        case 'email':
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (!mailformat.test(value)){
                $('#feedback-email').css('display', 'block');
                $('input[name="email"]').css('border-color', '#dc3545');
            }else{
                $('#feedback-email').css('display', 'none');
                $('input[name="email"]').css('border', '1px solid #ced4da');
            }
            break;
        case 'birthdate':
            var dateformat = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;
            if(!dateformat.test(value) || value == ""){
                $('#feedback-birthdate').css('display', 'block');
                $('input[name="birthdate"]').css('border-color', '#dc3545');
            }else{
                $('#feedback-birthdate').css('display', 'none');
                $('input[name="birthdate"]').css('border', '1px solid #ced4da');
            }
            break;
        case 'password':
            var passwordformat = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])");
            if (value.length < 6 || value.length > 12 || !passwordformat.test(value)){
                $('#feedback-password').css('display', 'block');
                $('input[name="password"]').css('border-color', '#dc3545');
            }else{
                $('#feedback-password').css('display', 'none');
                $('input[name="password"]').css('border', '1px solid #ced4da');
            }
            break;
        case 'confirm_password':
            if(value != $('input[name="password"]').val()){
                $('#feedback-confirm_password').css('display', 'block');
                $('input[name="confirm_password"]').css('border-color', '#dc3545');
            }else{
                $('#feedback-confirm_password').css('display', 'none');
                $('input[name="confirm_password"]').css('border', '1px solid #ced4da');
            }
            break;
    }
}