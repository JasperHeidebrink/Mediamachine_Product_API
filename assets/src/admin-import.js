"use strict";

/**
 * Import New Products Module
 * @package MM_Product_API
 */

export function initImportProducts() {
    // IMPORT NEW PRODUCTS
    let importing          = false,
        new_product_count  = 0,
        count_button       = jQuery('button.mm_api_count_new_products'),
        import_info        = jQuery('.mm-api-new-products-info'),
        import_status      = jQuery('.mm-api-import-status'),
        confirm_button     = jQuery('button.mm_api_confirm_import'),
        import_stop_button = jQuery('button.mm_api_import_stop'),
        progress_bar       = jQuery('#mm-import-progress'),
        progress_counter   = jQuery('#mm-import-counter');

    count_button.click(
        function () {
            count_button.prop('disabled', true);
            count_button.text('Checking...');

            jQuery.ajax({
                url    : mm_api_admin.ajax_url,
                type   : 'POST',
                data   : {
                    'action': 'mmcountnewproducts',
                },
                success: function (response) {
                    count_button.prop('disabled', false);
                    count_button.text("Check for new products");

                    import_info.toggleClass('hidden', false);

                    if (false === response.success) {
                        jQuery('#mm-new-products-count').html('<span class="error">' + response.data + '</span>');
                        return;
                    }

                    new_product_count = response.data.new;

                    if (0 === new_product_count) {
                        jQuery('#mm-new-products-count').html('<span class="notice-info">' + 'No new products found.' + '</span>');
                    } else {
                        jQuery('#mm-new-products-count').html('<strong>' + new_product_count + ' new products found!</strong> Click "Let\'s go" to start importing.');
                        confirm_button.toggleClass('hidden', false);
                    }
                },
                error  : function () {
                    count_button.prop('disabled', false);
                    count_button.text("Check for new products");
                    import_info.toggleClass('hidden', false);
                    jQuery('#mm-new-products-count').html('<span class="error">' + 'Error checking for new products. Please try again.' + '</span>');
                }
            });
        }
    );

    confirm_button.click(
        function () {
            confirm_button.toggleClass('hidden', true);
            import_stop_button.toggleClass('hidden', false);
            import_status.toggleClass('hidden', false);
            import_status.html('');
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
            progress_counter.toggleClass('hidden', true);
            import_status.append('<br/><span class="notice-warning">Import stopped.</span>');
        }
    );

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
                    importing = false;
                    import_stop_button.trigger('click');
                    import_status.append('<br/><span class="error">' + response.data + '</span>');
                    return;
                }

                import_status.append(response.data + '<br/>');
                import_status.animate({scrollTop: import_status.prop("scrollHeight")}, 500);

                // Update progress
                var progress_percent = Math.min((current_page / 5) * 100, 100); // Assuming ~5 pages
                progress_bar.val(progress_percent);
                progress_counter.text(current_page + ' pages processed');

                // Continue with next page
                import_new_products(++current_page);
            },
            error  : function () {
                console.log('MM Error on import page ' + current_page + ' Lets retry');
                import_new_products(current_page);
            }
        });
    }
}

