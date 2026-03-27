"use strict";
/**
 * @package   MM_Product_API
 * Admin Panel Main Entry Point
 */

import { initUpdateProducts } from './admin-update.js';
import { initImportProducts } from './admin-import.js';

jQuery(document).ready(function ($) {
    initUpdateProducts();
    initImportProducts();
});

