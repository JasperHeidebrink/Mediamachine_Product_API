// phpcs:ignoreFile
/**
 * This file handles the ajax calls for temptation.
 * Ignored this file for phpcs cause we don't yet got a proper JS file configuration.
 *
 * @package   DPG_WP_EventApi
 */

jQuery(document).ready(function ($) {
    $('.ajaxload').click(function () {
        let type = $(this).attr('id');
        jQuery.post(
            dpg_eventapi_admin.ajax_url,
            {
                action: 'dpgeventhtml_' + type,
            },
            function (data) {
                $('.ajaxdata').html(data.data);
            }
        );
    });

    $('select#eventapi_event_id,select#eventapi_edition_id').change( function () {
        let form =$(this).parents('form');
        form.find('input[type=submit]').click();
    });
});