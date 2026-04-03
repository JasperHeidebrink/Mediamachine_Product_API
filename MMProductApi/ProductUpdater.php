<?php
/**
 * Class for handling admin
 *
 * @package MM_Product_API
 */

namespace MMProductApi;

use MMProductApi\Api\StockApi;

/**
 * Class Plugin
 */
class ProductUpdater
{
    public function __construct()
    {
    }

    public function update_prices_from_api($page = 0, $posts_per_page = 20)
    {
        $api_data = $this->get_api_data(0);
        if (is_wp_error($api_data) || array_key_exists('error', $api_data)) {
            return [
                'log'     => __LINE__,
                'error'   => 'API error',
                'message' => $api_data['message'] ?? 'Failed to fetch data from API',
                'data'    => $api_data['data'] ?? null,
            ];
        }

        $products = $this->get_products($page, $posts_per_page);
        if (empty($products)) {
            return [
                'log'     => __LINE__,
                'error'   => 'No products',
                'message' => 'No products found in API',
                'data'    => $api_data['data'] ?? null,
            ];
        }

        $output = '';
        /** @var \WC_Product $product */
        foreach ($products as $product) {
            $sku       = $product->get_sku();
            $productId = $product->get_id();
            if (!$sku) {
                $output .= "<div class=\"log-error\">No SKU number found for product ID: {$productId}";
                $output .= ' <a href="/wp-admin/post.php?action=edit&post=' . $productId . '" class="italic" target="product">' .
                           $product->get_title() . '</a></div>';
                continue;
            }

            if (!isset($api_data[$sku])) {
                $output .= "<div class=\"log-alert\">No API data found for SKU: {$sku}";
                $output .= ' <a href="/wp-admin/post.php?action=edit&post=' . $productId . '" class="italic" target="product">' .
                           $product->get_title() . '</a></div>';
                continue;
            }

            update_post_meta($productId, 'reseller_price', $api_data[$sku]['price_reseller']);
            update_post_meta($productId, 'resellerbasic_price', $api_data[$sku]['price_end_user']);
            update_post_meta($productId, 'stock_quantity', $api_data[$sku]['available_stock']??0);
            update_post_meta($productId, 'api_data', json_encode($api_data[$sku]));

            $output .= "<div class=\"log-debug\">Updated product ID: {$productId} (SKU: $sku) ";
            $output .= ' <a href="/wp-admin/post.php?action=edit&post=' . $productId . '" class="italic" target="product">' .
                       $product->get_title() . '</a></div>';
        }

        return $output;
    }

    public function get_api_data($page = 0)
    {
        return StockApi::get_products($page);
    }

    /**
     * Get products with pagination
     *
     * @param int $page
     * @param int $posts_per_page
     *
     * @return array
     */
    public function get_products($page = 0, $posts_per_page = 20): array
    {
        return wc_get_products(
            [
                'page'    => $page,
                'limit'   => $posts_per_page,
                'orderby' => 'date',
                'order'   => 'DESC',
            ]
        );
    }

    /**
     * Count new products from API that don't exist on the website
     *
     * @return array
     */
    public function count_new_products(): array
    {
        $api_data = $this->get_api_data();

        if (is_wp_error($api_data) || isset($api_data['error'])) {
            return [
                'log'     => __LINE__,
                'error'   => $api_data['error'],
                'message' => $api_data['message'] ?? 'Failed to fetch data from API',
                'data'    => $api_data['data'] ?? null,
            ];
        }

        $existing_skus = $this->get_existing_skus();

        $data = [
            'new_skus'       => [],
            'new'            => 0,
            'total_api'      => count($api_data),
            'total_existing' => count($existing_skus),
        ];

        if (empty($data)) {
            return $data;
        }

        foreach ($api_data as $product) {
            $sku = $product['sku'] ?? 0;
            if (empty($sku)) {
                continue;
            }
            if (!in_array($sku, $existing_skus, true)) {
                $data['new']++;
                $data['new_skus'][] = $sku;
            }
        }

        return $data;
    }

    /**
     * Get all existing SKUs from WooCommerce products
     *
     * @return array
     */
    private function get_existing_skus(): array
    {
        $args     = [
            'limit'   => -1,
            'return ' => 'ids',
        ];
        $products = wc_get_products($args);
        $skus     = [];

        foreach ($products as $product_id) {
            $product = wc_get_product($product_id);
            if ($product && $product->get_sku()) {
                $skus[] = $product->get_sku();
            }
        }

        return $skus;
    }

    /**
     * Import new products from API
     *
     * @param int $page
     * @param int $posts_per_page
     * @return array|string
     */
    public function import_new_products_from_api($page = 0, $posts_per_page = 20): array|string
    {
        $api_data = $this->get_api_data($page);
        if (is_wp_error($api_data)) {
            return [
                'log'     => __LINE__,
                'error'   => 'API error',
                'message' => 'Failed to fetch data from API',
                'data'    => $api_data['data'] ?? null,
            ];
        }

        if (empty($api_data)) {
            return [
                'log'     => __LINE__,
                'error'   => 'No products',
                'message' => 'No products found in API',
            ];
        }

        $output        = [];
        $create_result = [];
        $counter       = 0;
        $existing_skus = $this->get_existing_skus();

        foreach ($api_data as $api_product) {
            $sku   = $api_product['sku'] ?? 0;
            $title = $api_product['title'] ?? 'no name';
            if (empty($sku) || in_array($sku, $existing_skus, true)) {
                continue;
            }

            $result          = $this->create_product_from_api($api_product, $sku);
            $create_result[] = $result;

            if (is_wp_error($result)) {
                $output[] = '<span class="log-error">Error creating product SKU ' .
                            $sku . ': ' . $result->get_error_message() .
                            '</span>';
                continue;
            }

            $url      = get_admin_url() . 'post.php?action=edit&post=' . $result;
            $output[] = '<span class="log-debug">Created: <a href="' . $url . '" target="edit">' .
                        'ID: ' . $result . 'SKU: ' . $sku . ' : ' . $title .
                        '</a></span>';
            $counter++;
        }

        $new = 0;
        foreach ($api_data as $product) {
            $sku = $product['sku'] ?? 0;
            if (empty($sku)) {
                continue;
            }
            if (!in_array($sku, $this->get_existing_skus(), true)) {
                $new++;
            }
        }

        return [
            'counter'       => $counter,
            'page'          => $page,
            'result'        => implode('<br>', $output),
            'create_result' => $create_result,
            'new'           => $new,
        ];
    }

    /**
     * Create a new WooCommerce product from API data
     *
     * @param array $api_product
     * @param string $sku
     * @return int|\WP_Error
     */
    private
    function create_product_from_api(
        $api_product,
        $sku
    ) {
        if (empty($sku) || $sku === 0) {
            return new \WP_Error('invalid_sku', 'SKU is required to create product');
        }

        $product = new \WC_Product_Simple();

        $product_title   = $api_product['title'] ?? 'Untitled Product';
        $price_end_user  = $api_product['price_end_user'] ?? 0;
        $available_stock = $api_product['available_stock'] ?? 0;
        $price_reseller  = $api_product['price_reseller'] ?? 0;

        $product->set_name($product_title);
        $product->set_sku($sku);
        $product->set_status('draft');
        $product->set_catalog_visibility('hidden');

        // Set prices
        $product->set_regular_price($price_end_user);

        // Set stock
        $product->set_manage_stock(true);
        $product->set_stock_quantity($available_stock);

        // Save product
        $product_id = $product->save();
        add_post_meta($product_id, 'api_data', json_encode($api_product));

        if (!$product_id) {
            return new \WP_Error('product_creation_failed', 'Could not create product');
        }

        // Set ACF fields for reseller prices
        update_post_meta($product_id, 'reseller_price', $price_reseller);
        update_post_meta($product_id, 'resellerbasic_price', $price_end_user);
        update_post_meta($product_id, 'stock_quantity', $available_stock);

        return $product_id;
    }

    public
    function schedule_price_updates(
        $interval = 'hourly'
    ) {
        if (!wp_next_scheduled('mm_product_api_update_prices')) {
            wp_schedule_event(time(), $interval, 'mm_product_api_update_prices');
        }
    }
}
