
    $(document).ready(function() {
        let page = 1;
        function loadMoreData(page) {
            $.ajax({
                    url:`${getImageBaseUrl}?page=${page}`,
                    type: 'get',
                    beforeSend: function() {
                        $('.ajax-load').show();
                    }
                })
                .done(function(data) {
                    if (data == "") {
                        $('.ajax-load').html("No more records found");
                        return;
                    }
                    $('.ajax-load').hide();
                    $('#media-data').append(data);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    // alert('Server not responding...');
                });
        }

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 1) {
                page++;
                loadMoreData(page);
            }
        });

        loadMoreData(page);

        $('#select-all').click(function(event) {
            if (this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    });
 