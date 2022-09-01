"use strict";
/**
 * @package   DPG_WP_EventApi
 */
const EVENTAPI_CHANGE_SPEED = 100;

jQuery(document).ready(function ($) {

    let text_search = $('input[name="event_filter_text"]'),
        category_search = $('select[name="event_filter_activity"]'),
        date_search = $('select[name="event_filter_date"]'),
        empty_container = $('.c-event-no-result');

    empty_container.hide();

    text_search.on('keyup', function () {
        dpg_event_filter()
    });
    category_search.on('change', function () {
        dpg_event_filter()
    });
    date_search.on('change', function () {
        dpg_event_filter()
    });

    function dpg_event_filter() {
        let text_value = text_search.val(),
            category_value = category_search.val(),
            date_value = date_search.val(),
            block_visible = false;

        $('.event__block').each(function () {
            let blockText = $(this).text().toLowerCase(),
                item_shown = true;


            if (text_value && ! blockText.includes(text_value.toLowerCase())) {
                item_shown = false;
            }

            if ( item_shown && '0' !== category_value && category_value !== $(this).attr('data-category')) {
                item_shown = false;
            }

            if ( item_shown && ! $(this).attr('data-days').includes(date_value) ) {
                item_shown = false;
            }

            if (item_shown) {
                block_visible = true;
                $(this).show(EVENTAPI_CHANGE_SPEED);
            } else {
                $(this).hide(EVENTAPI_CHANGE_SPEED);
            }
        });

        if ( block_visible ) {
            empty_container.hide();
        } else {
            empty_container.show();
        }
    }
});
