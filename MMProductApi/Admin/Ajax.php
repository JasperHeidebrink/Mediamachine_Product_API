<?php
/**
 * Class for handling admin
 *
 * @package MM_Product_API
 */

namespace MMProductApi\Admin;

use MMProductApi\ProductUpdater;

class Ajax
{

    /**
     * @var int
     */
    private $posts_per_page = 5;

    public function __construct()
    {
        add_action('wp_ajax_mmloadapidata', [$this, 'load_api_data']);
        add_action('wp_ajax_nopriv_mmloadapidata', [$this, 'load_api_data']);
        add_action('wp_ajax_mmcountnewproducts', [$this, 'count_new_products']);
        add_action('wp_ajax_nopriv_mmcountnewproducts', [$this, 'count_new_products']);
        add_action('wp_ajax_mmimportnewproducts', [$this, 'import_new_products']);
        add_action('wp_ajax_nopriv_mmimportnewproducts', [$this, 'import_new_products']);
    }

    /**
     * Load the API data
     *
     * @return void
     */
    public function load_api_data(): void
    {
        $page = absint($_POST['page'] ?? 0);

        $data = (new ProductUpdater())->update_prices_from_api($page);
        if (isset($data['error'])) {
            wp_send_json_error(
                [
                    'call'    => __FUNCTION__,
                    'error'   => esc_html($data['error']),
                    'message' => esc_html($data['message'] ?? 'Failed to load API data'),
                ]
            );
        }
        wp_send_json_success($data);
    }

    /**
     * Count new products from API
     *
     * @return void
     */
    public function count_new_products(): void
    {
        $data = (new ProductUpdater())->count_new_products();

        if (is_array($data) && isset($data['error'])) {
            wp_send_json_error(
                [
                    'call'    => __FUNCTION__,
                    'error'   => esc_html($data['error']),
                    'message' => esc_html($data['message'] ?? 'Failed to count new products'),
                    'data'    => $data['data'] ?? [],
                ]
            );
        }

        wp_send_json_success($data);
    }

    /**
     * Import new products from API
     *
     * @return void
     */
    public function import_new_products(): void
    {
        $page = absint($_POST['page'] ?? 0);

        $data = (new ProductUpdater())->import_new_products_from_api($page);

        if (isset($data['error'])) {
            wp_send_json_error(
                [
                    'call'    => __FUNCTION__,
                    'error'   => esc_html($data['error']),
                    'message' => esc_html($data['message'] ?? 'Failed to import new products'),
                ]
            );
        }

        wp_send_json_success($data);
    }
}
