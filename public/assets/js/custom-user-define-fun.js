const AllowNumericOnly = (selector) => {
    $(selector).on('input', function () {
        return $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
}

const BlockSpaceForInput = (selector) => {
    $(selector).on('input', function () {
        return $(this).val($(this).val().replace(/\s/g, ''));

    });
}

const AllowTwoDecimalAfterPoint = (selector) => {
    $(selector).on('input', function () {
        return $(this).val($(this).val().replace(/^\d+(\.\d{1,2})?$/g, ''));
    });
}



const OnlyAllowFloatNumber = (selector) => {
    $(selector).on('input', function () {
        let value = $(this).val();
        value = value.replace(/[^0-9.]/g, '');
        value = value.replace(/(\..*)\./g, '$1').replace(/(\.\d{2})\d+/, '$1');
        $(this).val(value);
    });
}



const DeleteConfirmation = ($title) => {
    $('.delete_any_item').on('click', function () {
        status = $(this).attr('status');
        var location = $(this).attr('data-id');
        Swal.fire({
            title: $title ?? "Are you sure you want to delete this?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            confirmed = false;
            if (result.isConfirmed) {
                window.location.href = location;
            }
        });
    });

    return status;
}