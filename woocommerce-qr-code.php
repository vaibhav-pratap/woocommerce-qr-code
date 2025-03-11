<?php
/**
 * Plugin Name: WooCommerce QR Code Generator
 * Plugin URI: https://exiverlabs.co.in/
 * Description: Automatically generates and embeds QR codes for WooCommerce products and orders, with admin features.
 * Version: 1.4
 * Author: Vaibhav Singh
 * Author URI: https://exiverlabs.co.in/
 * License: GPL2
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('WQR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WQR_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WQR_PLUGIN_DIR . 'includes/qr-generator.php';
require_once WQR_PLUGIN_DIR . 'includes/admin-panel.php';
include_once plugin_dir_path(__FILE__) . 'includes/product-edit-qr.php';

// Add QR code to product description
add_filter('the_content', 'wqr_add_product_qr_code');
function wqr_add_product_qr_code($content) {
    if (is_singular('product')) {
        global $post;
        $qr_code_url = wqr_generate_qr($post->ID);
        $download_url = WQR_PLUGIN_URL . "includes/qr-generator.php?download=" . $post->ID;
        $content .= "<p><strong>Scan to View Product:</strong><br>
                     <img src='{$qr_code_url}' alt='Product QR Code' />
                     <br><a href='{$download_url}' download>Download QR Code</a></p>";
    }
    return $content;
}

// Generate QR Code for Orders on Thank You Page
add_action('woocommerce_thankyou', 'wqr_generate_order_qr');
function wqr_generate_order_qr($order_id) {
    $order = wc_get_order($order_id);
    if ($order) {
        $qr_code_url = wqr_generate_qr($order_id, 'order');
        echo "<p><strong>Scan to View Order:</strong><br>
              <img src='{$qr_code_url}' alt='Order QR Code' /></p>";
    }
}
?>
