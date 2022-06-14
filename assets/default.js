"use strict";
/**
 * @package   DPG_WP_EventApi
 */
const EVENTAPI_SHOW_SPEED = 100;

jQuery(document).ready(function ($) {

    $('select[name^="event__selectfilter"]').change(function () {
        DpgEventApi_apply_filter();
    });
    $('input[name="search_participants_query"]').keyup(function () {
        DpgEventApi_apply_filter();
    });

    function DpgEventApi_apply_filter() {
        let category = $('select[name="event__selectfilter_category"]').val(),
            day = $('select[name="event__selectfilter_day"]').val(),
            text = $('input[name="search_participants_query"]').val(),
            empty_result = $('.event_empty_result');

        empty_result.hide();

        $('.event__block').each(function () {
            let item = $(this);
            if ('0' === day && '0' === category) {
                item.show(EVENTAPI_SHOW_SPEED);
            } else if ('0' !== day && '0' !== category &&
                category === item.attr('data-category') && day === item.attr('data-day')) {
                item.show(EVENTAPI_SHOW_SPEED);
            } else if ('0' === day && category === item.attr('data-category')) {
                item.show(EVENTAPI_SHOW_SPEED);
            } else if ('0' === category && day === item.attr('data-day')) {
                item.show(EVENTAPI_SHOW_SPEED);
            } else {
                item.hide();
                return;
            }

            if ('' === text) {
                return;
            }

            if ($('.event__title:contains(' + text + ')', item).length === 0) {
                item.show(EVENTAPI_SHOW_SPEED);
            }
        });

        if ($('.event__block:visible').length === 0) {
            empty_result.show(EVENTAPI_SHOW_SPEED * 2);
        }
    }
});
