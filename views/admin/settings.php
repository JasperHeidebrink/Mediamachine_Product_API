<table class="form-table" role="presentation">

    <tbody>
    <tr>
        <th scope="row"><?= __('Reload data', MM_API_DOMAIN) ?></th>
        <td>
            <p>This will check all the products in on the website and update the price and stock, based on the SKU.</p>
            <button type="button" class="button mm_api_reload"><?php _e('Reload', MM_API_DOMAIN) ?></button>
            <button type="button" class="button mm_api_stop hidden"><?php _e('stop', MM_API_DOMAIN) ?></button>
            <div class="mm-api-status"></div>
        </td>
    </tr>
    <tr>
        <th scope="row"><?= __('Import new products', MM_API_DOMAIN) ?></th>
        <td>
            <p>This will import new products from the API that don't exist on the website yet. New products will be created with draft status.</p>
            <button type="button" class="button mm_api_count_new_products"><?php _e('Check for new products', MM_API_DOMAIN) ?></button>
            
            <div class="hidden mm-api-new-products-info">
                <p id="mm-new-products-count"></p>
                <button type="button" class="button button-primary mm_api_confirm_import hidden"><?php _e("Let's go", MM_API_DOMAIN) ?></button>
                <button type="button" class="button mm_api_import_stop hidden"><?php _e('Stop', MM_API_DOMAIN) ?></button>
            </div>

            <progress id="mm-import-progress" value="0" max="100" class="hidden" style="width: 100%;"></progress>
            <span id="mm-import-counter" class="hidden" style="display: inline-block; margin-left: 10px;"></span>
            
            <div class="mm-api-import-status"></div>
        </td>
    </tr>
    </tbody>
</table>
