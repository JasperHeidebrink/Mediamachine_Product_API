"use strict";
/**
 * @package   DPG_WP_EventApi
 */
jQuery(document).ready(function ($) {

    $('select.hasCustomSelect').change(function () {
        DpgEventApi_apply_filter();
    });
    $('input[name="search_participants_query"]').keyup(function () {
        DpgEventApi_apply_filter();
    });

    function DpgEventApi_apply_filter() {
        let category = $('select[name="event__selectfilter_category"]').val();
        let day = $('select[name="event__selectfilter_day"]').val();
        let text = $('input[name="search_participants_query"]').val();

        $('.event__block').each(function () {
            let item = $(this);
            if ('0' === day && '0' === category) {
                item.show();
            } else if ('0' !== day && '0' !== category &&
                category === item.attr('data-category') && day === item.attr('data-day')) {
                item.show();
            } else if ('0' === day && category === item.attr('data-category')) {
                item.show();
            } else if ('0' === category && day === item.attr('data-day')) {
                item.show();
            } else {
                item.hide();
                return;
            }

            if ('' === text) {
                return;
            }

            if ($('.event__title:contains(' + text + ')', item).length === 0) {
                item.hide();
            }
        });
    }
});
