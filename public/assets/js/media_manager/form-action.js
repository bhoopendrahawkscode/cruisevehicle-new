$(document).ready(function () {
    $(".EditMediaBtn").on('click', function () {
        const parent = $(this).parent().parent();
        const ImgeLink = $(this).attr('data-img');
        const ImageName = parent.find('.ImageName').text();
        const updateMediaNameUrl = $(this).attr('data-url');
        selected_media_image_id = $(this).attr('data-id');

        $("#imageURL").attr('value', ImgeLink);
        $('#modalImageLink').attr('src', ImgeLink);
        $("#modalImageForm input[name='name']").val(ImageName.trim());

        $("#modalImageForm").attr('action', updateMediaNameUrl);
        $("#modalImageForm").find(".help-inline").text('');
        $('#imageModal').modal('show');
    }).parent().hover(function () {
        $(this).parent().find('.EditMediaBtn').removeClass('d-none');
    }, function () {
        $(this).parent().find('.EditMediaBtn').addClass('d-none');
    });

    //Click to copy
    $("#CopyURLBtn").on('click', function (e) {
        e.preventDefault();
        const textToCopy = $("#imageURL").attr('value');
        navigator.clipboard.writeText(textToCopy).then(function () {
            $("#copiedMsg").removeClass('d-none');
            setTimeout(function () {
                $("#copiedMsg").addClass('d-none');
            }, 2000);
        })
    });

    DeleteConfirmation();
});