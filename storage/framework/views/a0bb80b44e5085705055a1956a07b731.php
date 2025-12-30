<?php
$localeKey  = array_key_first($errors->getMessages());

if ($localeKey != '') {
    $localeKey = explode(".", $localeKey);
    if (is_array($localeKey) && isset($localeKey[1])) {
?>
        <script>
            $(document).ready(function() {
                $("#tabId<?php echo $localeKey[1]; ?>").tab('show');
                $("#tabId<?php echo $localeKey[1]; ?>").addClass('error');
            });
        </script>
<?php
    }
}
?>

<script>
    function removeTabError() {
        if ($('#tab-parent').find('.error').length !== 0) {
            $('.language_tab').each(function() {
                $(this).removeClass('error');
                $('#tabId' + languageID).tab('hide');
            });
            var ele = $("#tab-parent .error:first");
            var languageID = ele.closest('.tab-pane').attr('language');
            $('#tabId' + languageID).tab('show');
            $('#tabId' + languageID).addClass('error');
        } else {
            $('.language_tab').removeClass('error');
        }
    }

    function removeTabError2(lang) {
        if ($('#custom-tabs-' + lang).find('.error').length == 0) {
            $('#tabId' + lang).removeClass('error');

        }
    }
</script><?php /**PATH /var/www/html/resources/views/admin/tabSelected.blade.php ENDPATH**/ ?>