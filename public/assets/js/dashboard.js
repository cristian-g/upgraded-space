$(document).ready(function () {
    $('#submitNewFolder').on('click', function() {
        $('#newFolderForm').submit();
    });
    $('#submitUploadFile').on('click', function() {
        $('#uploadFileForm').submit();
    });
});