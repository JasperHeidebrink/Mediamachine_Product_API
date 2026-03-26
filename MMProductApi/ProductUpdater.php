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
    private $api_client;

    public function __construct()
    {
        $this->api_client = new StockApi();
    }

    public function update_prices_from_api($page = 0, $posts_per_page = 20)
    {
        $api_data = $this->get_api_data();
        if (is_wp_error($api_data)) {
            return ['error' => 'Failed to fetch data from API'];
        }

        $products = $this->get_products($page, $posts_per_page);
        if (empty($products)) {
            return ['error' => 'No new products found'];
        }

        $output = '';
        /** @var \WC_Product $product */
        foreach ($products as $product) {
            $sku       = $product->get_sku();
            $productId = $product->get_id();
            if (!$sku) {
                $output .= "<div class=\"log-error\">No SKU number found for product ID {$productId}";
                $output .= ' <a href="/wp-admin/post.php?action=edit&post='.$productId.'" class="italic" target="product">'. $product->get_title() . '</a></div>';
                continue;
            }

            if (!isset($api_data['products'][$sku])) {
                $output .= "<div class=\"log-alert\">No API data found for SKU {$sku}";
                $output .= ' <a href="/wp-admin/post.php?action=edit&post='.$productId.'" class="italic" target="product">'. $product->get_title() . '</a></div>';
                continue;
            }

            update_post_meta($productId, 'reseller_price', $api_data['products'][$sku]['price_reseller']);
            update_post_meta($productId, 'resellerbasic_price', $api_data['products'][$sku]['price_end_user']);
            update_post_meta($productId, 'stock_quantity', $api_data['products'][$sku]['available_stock']);

            $output .= "<div class=\"log-debug\">Updated: Product ID {$productId} (SKU: $sku) ";
            $output .= ' <a href="/wp-admin/post.php?action=edit&post='.$productId.'" class="italic" target="product">'. $product->get_title() . '</a></div>';
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

    public function schedule_price_updates($interval = 'hourly')
    {
        if (!wp_next_scheduled('mm_product_api_update_prices')) {
            wp_schedule_event(time(), $interval, 'mm_product_api_update_prices');
        }
    }
}
