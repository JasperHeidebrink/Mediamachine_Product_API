"use strict";
/**
 * Update Products Module
 * @package MM_Product_API
 */

export function initUpdateProducts() {
    // UPDATE PRODUCTS
    let loading        = true,
        status_element = jQuery('.mm-api-status'),
        reload_button  = jQuery('button.mm_api_reload'),
        stop_button    = jQuery('button.mm_api_stop');

    reload_button.click(
        function () {
            status_element.html('');
            stop_button.removeClass('hidden');
            reload_button.addClass('hidden');
            loading = true;
            load_api_data(1);
        }
    );
    stop_button.click(
        function () {
            stop_button.addClass('hidden');
            reload_button.removeClass('hidden');
            loading = false;
        }
    );

    function load_api_data(current_page) {
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
                    stop_button.trigger('click');
                    loading = false;
                }
                status_element.append(response.data + '<br/>');
                status_element.animate({scrollTop: status_element.prop("scrollHeight")}, 1000);
                load_api_data(++current_page);
            },
            error  : function () {
                console.log('MM Error on page ' + current_page + ' Lets retry');
                load_api_data(current_page);
            }
        });
    }
}

