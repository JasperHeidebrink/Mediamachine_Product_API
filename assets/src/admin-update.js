"use strict";

/**
 * Update Products Module
 * @package MM_Product_API
 */

export function initUpdateProducts() {
    // UPDATE PRODUCTS
    let loading        = true,
        reload_button  = jQuery('button.mm_api_reload'),
        stop_button    = jQuery('button.mm_api_stop');

    reload_button.click(
        function () {
            jQuery('.mm-api-status').html('');
            addStatusMessage('Loading data...');
            stop_button.removeClass('hidden');
            reload_button.addClass('hidden');

            loading = true;
            api_load_data(1);
        }
    );
    stop_button.click(
        function () {
            stop_button.addClass('hidden');
            reload_button.removeClass('hidden');
            loading = false;
        }
    );

    function api_load_data(current_page) {
        if (false === loading) {
            return;
        }

        jQuery.ajax({
            url    : mm_api_admin.ajax_url,
            type   : 'POST',
            data   : {
                'action': 'mmloadapidata',
                'page'  : current_page,
            },
            success: function (response) {
                if (false === response.success) {
                    console.log(response);
                    addStatusMessage(response.data.message, 'notification');
                    stop_button.trigger('click');
                    return;
                }
                addStatusMessage(response.data);
                api_load_data(++current_page);
            },
            error  : function () {
                console.log('MM Error on page ' + current_page + '. Stopping updates.');
                addStatusMessage('Error on page ' + current_page + '. Stopping updates.', 'notification');
                stop_button.trigger('click');
            }
        });
    }
}

