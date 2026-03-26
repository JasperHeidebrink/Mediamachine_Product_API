<table class="form-table" role="presentation">

    <tbody>
    <tr>
        <th scope="row"><label for="blogname"><?= __('Reload data', MM_API_DOMAIN) ?></label></th>
        <td>
            <form class="add_attribute_form">
                This will check all the products in on the website and update the price and stock, based on the SKU.
                <br>
                <br>
                <button type="button" class="button mm_api_reload"><?php _e('Reload', MM_API_DOMAIN) ?></button>
                <button type="button" class="button mm_api_stop hidden"><?php _e('stop', MM_API_DOMAIN) ?></button>
            </form>
            <div class="mm-api-status"></div>
        </td>
    </tr>
    </tbody>
</table>
