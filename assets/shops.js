// Shops search function
const EVENT_API_CHANGE_SPEED = 100;

jQuery(document).ready(function ($) {

    let text_search = $('input[name="event_shop_search"]');
    let category_select = $('select[name="event_shop_category"]');
    const empty_container = $(".c-event-no-result");

    empty_container.hide();

    text_search.on('keyup', filterShops );
    category_select.on('change', filterShops );

    function filterShops() {
        let text_value = text_search.val();
        let category = category_select.val();
        let all_hidden = true;

        $('.c-event-shop').each(function () {
            let show = true;
            let blockText = $(this).text().toLowerCase();
            let blockCategories = $(this).data('categories');

            if ( category && '#' !== category && !blockCategories.includes(category.toLowerCase()) ) {
                show = false;
            }

            if (show && text_value && !blockText.includes(text_value.toLowerCase()) ) {
               show = false;
            }

            if ( show ) {
                all_hidden = false;
                $(this).show(EVENT_API_CHANGE_SPEED);
            } else {
                $(this).hide(EVENT_API_CHANGE_SPEED);
            }
        });

        if ( all_hidden ) {
            empty_container.show(EVENT_API_CHANGE_SPEED);
        } else {
            empty_container.hide(EVENT_API_CHANGE_SPEED);
        }
    }

    filterShops();
});