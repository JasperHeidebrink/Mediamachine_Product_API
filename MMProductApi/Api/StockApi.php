<?php

namespace MMProductApi\Api;


class StockApi
{
    private $api_url = MM_API_URL;
    private $api_key;

    /**
     * @var array
     */
    private $routeEndpoints
        = [
            'products' => 'products',
            'product'  => 'product/{id}',
        ];


    public function __construct()
    {
        $this->api_key = MM_API_KEY;
    }

    /**
     * @param string $endpoint
     * @param array $params
     *
     * @return array
     */
    public function post(string $endpoint, array $params): array
    {
        $route        = $this->get_route($endpoint);
        $url          = $this->api_url . $route;
        $query_string = http_build_query($params);
        if (!empty($query_string)) {
            $url .= '?' . $query_string;
        }

        $response = wp_remote_post(
            $url,
            [
                'sslverify' => false,
                'body'      =>
                    [
                        'token' => $this->api_key,
                    ],
            ]
        );

        if (is_wp_error($response)) {
            return [
                'error'   => $response->get_error_code(),
                'message' => $response->get_error_message(),
                'data'    => ['url' => $url],
            ];
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    /**
     * @param $endpoint
     * @return mixed|string
     */
    private function get_route($endpoint)
    {
        if (!array_key_exists($endpoint, $this->routeEndpoints)) {
            throw new \InvalidArgumentException("Invalid endpoint: {$endpoint}");
        }
        return $this->routeEndpoints[$endpoint];
    }

    /**
     * Fetch product price by SKU
     *
     * @param string $sku
     * @return float|false
     */
    public static function get_products($page = 1): array
    {
        $products = (new self())->post('products', ['page' => $page, 'limit' => -1], []);

        if (isset($products['error'])) {
            return [
                'error'   => $products['error'],
                'message' => $products['message'],
                'data'    => $products['data'] ?? null,
            ];
        }

        $output = [
            'meta' => [
                'per_page'     => $products['meta']['per_page'],
                'to'           => $products['meta']['to'],
                'total'        => $products['meta']['total'],
                'current_page' => $products['meta']['current_page'],
                'from'         => $products['meta']['from'],
                'last_page'    => $products['meta']['last_page'],
            ],
        ];

        foreach ($products['data'] as $product) {
            $output[$product['sku']] = [
                'id'             => $product['id'],
                'sku'            => $product['sku'],
                'price_reseller' => $product['price_reseller'],
                'price_end_user' => $product['price_end_user'],
                'title'          => $product['title'],
                'brand_name'     => $product['brand_name'],
            ];
        }

        return $output;
    }
}
