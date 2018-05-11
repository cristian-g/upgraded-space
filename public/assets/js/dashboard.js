$(document).ready(function () {
    $('#submitNewFolder').on('click', function() {
        $('#newFolderForm').submit();
    });
    $('#submitUploadFile').on('click', function() {
        $('#uploadFileForm').submit();
    });
    $('#submitRename').on('click', function() {
        $('#renameForm').submit();
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $('.renameUploadButton').click(function () {
        $('#id_upload_rename').val($(this).data('id'));
        $('#name_upload_rename').val($(this).data('name'));
        $('#renameModal').modal('show');
    });
});