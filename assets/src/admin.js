"use strict";
/**
 * @package   MM_Product_API
 * Admin Panel Main Entry Point
 */

import { initUpdateProducts } from './admin-update.js';
import { initImportProducts } from './admin-import.js';

jQuery(document).ready(function ($) {
    const status_element = jQuery('.mm-api-status');

    function addStatusMessage (message, type = 'primary') {
        status_element.append('<br><span class="wp-ui-text-' + type + '">' + message + '</span>');
        status_element.animate({scrollTop: status_element.prop("scrollHeight")}, 1000);
    }

    // Expose helper for imported admin modules.
    window.addStatusMessage = addStatusMessage;

    initUpdateProducts();
    initImportProducts();
});

