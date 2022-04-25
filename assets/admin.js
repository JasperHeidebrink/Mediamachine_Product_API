// phpcs:ignoreFile
/**
 * This file handles the ajax calls for temptation.
 * Ignored this file for phpcs cause we don't yet got a proper JS file configuration.
 *
 * @package   DPG_WP_EventApi
 */

jQuery(document).ready(function ($) {
    $('.ajaxload_activities').click(function () {
        jQuery.post(
            dpg_eventapi_admin.ajax_url,
            {
                action: 'dpgeventhtml_activities',
            },
            function (data) {
                $('.ajaxdata').html(data.data);
            }
        );
    });

    $('.ajaxload_exhibitors').click(function () {
        jQuery.post(
            dpg_eventapi_admin.ajax_url,
            {
                action: 'dpgeventhtml_exhibitors',
            },
            function (data) {
                console.log(data.data);
                $('.ajaxdata').html(data.data);
            }
        );
    });
});