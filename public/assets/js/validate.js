//registerForm
$(document).ready(function () {

    $('#registerSubmit').click(function() {
        /* Validations go here */

        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirm_password = $('#confirm_password').val();

        /*if (username.length > 20 || username.length < 0){
            alert("username not valid");
            return false;
        }

        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!mailformat.test(email)){
            alert("email not valid");
            return false
        }

        var passwordformat = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])");
        if (password.length < 6 || password.length > 12 || !passwordformat.test(password)){
            alert("password not valid");
            return false;
        }

        if (confirm_password.localeCompare(password) != 0){
            alert("passwords not identical");
            return false;
        }*/

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