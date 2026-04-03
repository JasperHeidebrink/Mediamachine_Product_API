"use strict";

/**
 * Import New Products Module
 * @package MM_Product_API
 */

/**
 * Initialize Import Products functionality
 */
export function initImportProducts() {
    let importing          = false,
        new_product_count  = 0,
        import_info        = jQuery('.mm-api-new-products-info'),
        products_count     = jQuery('.mm-new-products-count'),
        count_button       = jQuery('button.mm_api_count_new_products'),
        confirm_button     = jQuery('button.mm_api_confirm_import'),
        import_stop_button = jQuery('button.mm_api_import_stop'),

        progress_bar       = jQuery('#mm-import-progress'),
        progress_counter   = jQuery('#mm-import-counter');

    count_button.click(
        function () {
            count_button.toggleClass('hidden', true);
            import_info.toggleClass('hidden', true);
            jQuery('.mm-api-status').html('<span class="notice-info">Loading data...</span>');
            addStatusMessage('loading', 'info');

            api_count_new_products();
        });

    confirm_button.click(
        function () {
            confirm_button.toggleClass('hidden', true);
            import_info.toggleClass('hidden', false);
            import_stop_button.toggleClass('hidden', false);

            progress_counter.text('0 of ' + new_product_count + ' products imported.');
            progress_bar.toggleClass('hidden', false);
            progress_counter.toggleClass('hidden', false);

            importing = true;

            import_new_products(1);
        }
    );

    import_stop_button.click(
        function () {
            importing = false;
            import_stop_button.toggleClass('hidden', true);
            confirm_button.toggleClass('hidden', true);
            progress_bar.toggleClass('hidden', true);
            products_count.toggleClass('hidden', true);
            progress_counter.toggleClass('hidden', true);

            count_button.toggleClass('hidden', false);
            addStatusMessage('Import stopped.', 'info');
        }
    );

    /**
     * Check for new products and update UI accordingly
     */
    function api_count_new_products() {

        jQuery.ajax({
            url    : mm_api_admin.ajax_url,
            type   : 'POST',
            data   : {
                'action': 'mmcountnewproducts',
            },
            success: function (response) {

                if (false === response.success) {
                    console.log(response);
                    addStatusMessage(response.data.message, 'notification');
                    import_stop_button.trigger('click');
                    return;
                }

                new_product_count = response.data.new;

                if (0 === new_product_count) {
                    addStatusMessage('No new products found.', 'notification');
                    import_stop_button.trigger('click');
                    return;
                }

                addStatusMessage('New products: ' + new_product_count + '.');
                products_count.html('<b>' + new_product_count + '</b> new products found.');
                import_info.toggleClass('hidden', false);
                confirm_button.toggleClass('hidden', false);
            },
            error  : function () {
                console.log('MM Error checking for products. Stopping updates.');
                addStatusMessage('Error checking for new products. Please try again.', 'notification');
                import_stop_button.trigger('click');
            }
        });
    }

    /**
     * Import new products page by page, updating progress and handling errors
     * @param current_page
     */
    function import_new_products(current_page) {
        if (false === importing) {
            return;
        }

        jQuery.ajax({
            url    : mm_api_admin.ajax_url,
            type   : 'POST',
            data   : {
                'action': 'mmimportnewproducts',
                'page'  : current_page,
            },
            success: function (response) {
                if (false === response.success) {
                    console.log(response);
                    addStatusMessage(response.data.message, 'notification');
                    import_stop_button.trigger('click');
                    return;
                }

                addStatusMessage(response.data.result);

                // Update progress
                let progress_percent = Math.min((current_page / 5) * 100, 100);
                progress_bar.val(progress_percent);
                progress_counter.text(current_page + ' pages processed');

                if (response.data.new < 1) {
                    addStatusMessage('All products are processed.', 'highlight');
                    import_stop_button.trigger('click');
                    return;
                }

                // Continue with next page
                import_new_products(++current_page);
            },
            error  : function () {
                addStatusMessage('Error on import page ' + current_page, 'notification');
                import_new_products(current_page);
            }
        });
    }
}

