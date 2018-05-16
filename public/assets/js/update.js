$(document).ready(function () {

    $(document).on('submit', '#registerForm', function (e) {
        e.preventDefault();

        var data = new FormData($('#registerForm')[0]);
        $.ajax({
            type: 'post',
            url: '/edit',
            processData: false,
            contentType: false,
            cache: false,
            data: data,
            success: function (json) {

            },
            error: function (responseText) {

            }
        });
    });
});

