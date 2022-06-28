// Shops search function
const EVENT_API_CHANGE_SPEED = 100;

jQuery(document).ready(function ($) {

    let text_search = $('input[name="event_shop_search"]');

    text_search.on('keyup', function () {
        let text_value = text_search.val();

        $('.c-event-shop').each(function () {
            let blockText = $(this).text().toLowerCase();

            if (text_value && !blockText.includes(text_value.toLowerCase())) {
                $(this).hide(EVENT_API_CHANGE_SPEED);
            } else {
                $(this).show(EVENT_API_CHANGE_SPEED);
            }
        });
    });

});
