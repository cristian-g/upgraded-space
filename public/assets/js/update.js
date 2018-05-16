$(document).ready(function () {

    $(document).on('submit', '#updateForm', function (e) {
        e.preventDefault();

        var data = new FormData($('#updateForm')[0]);
        $.ajax({
            type: 'post',
            url: '/edit',
            processData: false,
            contentType: false,
            cache: false,
            data: data,
            success: function (json) {
                swal({
                    title: "Datos actualizados",
                    icon: "success",
                    text: "La información de tu cuenta se ha actualizado correctamente.",
                    buttons: {
                        confirm: "Aceptar"
                    }
                });
                var profileimageSrc = $('#profileimage').attr('src');
                $('#profileimage').removeAttr('src').attr('src', profileimageSrc+'?timestamp=' + new Date().getTime());
            },
            error: function (responseText) {
                swal({
                    title: "Error",
                    icon: "error",
                    text: "Ha habido un error con el servidor, inténtalo de nuevo.",
                    buttons: {
                        confirm: "Aceptar"
                    },
                    dangerMode: true
                });
            }
        });
    });
});

