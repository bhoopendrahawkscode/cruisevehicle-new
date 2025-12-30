$(document).ready(function() {
        $("#modalImageForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                    notNumber: true,
                    remote: {
                        url: validateMediaNameUrl,
                        type: "POST",
                        data: {
                            id: function() {
                                return selected_media_image_id;
                            }
                        }
                    }
                },

            },
            messages: {
                name: {
                    minlength: minlength_,
                    maxlength: maxlength,
                    remote: remote_,
                },

            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').addClass('error');

            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('error');
                $(element).parents('.form-group').addClass('success');
            },

        });
});