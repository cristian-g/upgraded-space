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
        $('[data-toggle="tooltip"]').tooltip();
        $('.deleteUploadButton').tooltip({
            title: 'Eliminar',
            dataPlacement: 'top'
        });
    });
    $('.renameUploadButton').click(function () {
        $('#id_upload_rename').val($(this).data('id'));
        $('#name_upload_rename').val($(this).data('name'));
        $('#renameModal').modal('show');
    });
    $(function () {
        $('[data-toggle="popover"]').popover();
    });
    $('.deleteUploadButton').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        placement: "left",
        btnOkLabel: "&nbsp;Eliminar",
        btnOkClass: "btn-danger",
        btnOkIconClass: "fa fa-",
        btnOkIconContent: "trash",
        btnCancelLabel: "&nbsp;Cancelar",
        btnCancelClass: "btn-secondary",
        btnCancelIconClass: "fa fa-",
        btnCancelIconContent: "ban",
        title: "¿Estás seguro?",
        content: "Esta acción no se puede deshacer."
    });
    $('.deleteUploadButton').on('confirmed.bs.confirmation', function () {
        $('#id_upload_delete').val($(this).data('id'));
        $('#deleteForm').submit();
    });
});