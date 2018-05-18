$(document).ready(function () {

    $(document).on('submit', '#updateForm', function (e) {
        e.preventDefault();

        var data = new FormData($('#updateForm')[0]);
        var filesize = $('#profile_image')[0].files[0].size;
        var filename = $('#profile_image').val();
        var extension = filename.split('.').pop();
        $.ajax({
            type: 'post',
            url: '/edit',
            processData: false,
            contentType: false,
            cache: false,
            data: data,
            success: function (json) {

                //we check the file extension
                var validExtensions = ['jpg', 'png', 'gif'];
                if (validExtensions.indexOf(extension) && extension.localeCompare('') != 0){
                    swal({
                        title: "Error",
                        icon: "error",
                        text: "El formato de la imagen de perfil es incorrecto. Por favor, utiliza .jpg, .png o .gif",
                        buttons: {
                            confirm: "Aceptar"
                        },
                        dangerMode: true
                    });
                    return;
                }

                if (filesize > 500000){
                    swal({
                        title: "Error",
                        icon: "error",
                        text: "Por favor seleccione una imagen con un tamaño inferior a 500KB",
                        buttons: {
                            confirm: "Aceptar"
                        },
                        dangerMode: true
                    });
                    return;
                }

                swal({
                    title: "Datos actualizados",
                    icon: "success",
                    text: "La información de tu cuenta se ha actualizado correctamente.",
                    buttons: {
                        confirm: "Aceptar"
                    }
                });

                if (extension.localeCompare('') != 0){
                    var profileimageSrc = $('#profileimage').attr('src');
                    var newPath =  profileimageSrc.replace(/\.[^/.]+$/, "") + '.' + extension;
                    $('#profileimage').removeAttr('src').attr('src', newPath+'?timestamp=' + new Date().getTime());
                    $('#bigProfileImage').removeAttr('src').attr('src', newPath+'?timestamp=' + new Date().getTime());
                }
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

