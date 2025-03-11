<?php
if (!defined('ABSPATH')) exit;

// Add Admin Menu
add_action('admin_menu', 'wqr_add_admin_menu');
function wqr_add_admin_menu() {
    add_menu_page('QR Code Manager', 'QR Codes', 'manage_options', 'wqr-admin', 'wqr_admin_page', 'dashicons-qrcode', 56);
}

// Admin Page Content
function wqr_admin_page() {
    global $wpdb;
    $products = wc_get_products(['limit' => -1]);
    echo "<div class='wrap'><h1>WooCommerce QR Code Manager</h1>";
    
    echo "<h2>Products QR Codes</h2><table class='wp-list-table widefat fixed striped'>";
    echo "<thead><tr><th>Product</th><th>QR Code</th><th>Download</th></tr></thead><tbody>";
    
    foreach ($products as $product) {
        $qr_url = wqr_generate_qr($product->get_id());
        $download_url = WQR_PLUGIN_URL . "includes/qr-generator.php?download=" . $product->get_id();
        echo "<tr>
                <td>{$product->get_name()}</td>
                <td><img src='{$qr_url}' width='50' /></td>
                <td><a href='{$download_url}' download>Download</a></td>
              </tr>";
    }
    
    echo "</tbody></table></div>";
}
?>
