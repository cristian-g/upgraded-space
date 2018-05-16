
function ejecutarAjax(){
    $.ajax({
        type: 'post',
        url: '/edit',
        data: $("#registerForm").serialize(),
        success: function(json) {

        },
        error: function(responseText) {

        }
    });
}
