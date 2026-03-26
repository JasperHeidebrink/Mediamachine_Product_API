<?php
/**
 * Plugin functionality -  WordPress MMProductApi
 * Basic plugin setup
 *
 * @package   MMProductApi
 */

namespace MMProductApi;

use MMProductApi\Admin\Admin;
use MMProductApi\Admin\Ajax;

/**
 * Class Plugin
 */
class Plugin
{
    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (!is_admin()) {
            return;
        }

        new Admin();
        new Ajax();
    }

    /**
     * Load the text domain for this plugin.
     */
    public function load_plugin_textdomain(): void
    {
        // Set language domain.
        load_plugin_textdomain(
            'mm-product-api',
            false,
            '/mm-product-api/languages'
        );
    }
}
