<?php
if (!defined('ABSPATH')) exit;

// Add Admin Menu
add_action('admin_menu', 'wqr_add_admin_menu');
function wqr_add_admin_menu() {
    add_menu_page('QR Code Manager', 'QR Codes', 'manage_options', 'wqr-admin', 'wqr_admin_page', 'dashicons-qrcode', 56);
}

// Admin Page Content with Pagination
function wqr_admin_page() {
    global $wpdb;

    // Pagination Setup
    $per_page = 20; // Show 20 products per page
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    $products = wc_get_products([
        'limit' => $per_page,
        'offset' => $offset
    ]);
    
    $total_products = wc_get_products(['limit' => -1]); // Get total count
    $total_pages = ceil(count($total_products) / $per_page);
    
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

    echo "</tbody></table>";

    // Pagination Controls
    echo "<div class='tablenav'><div class='tablenav-pages'>";
    if ($total_pages > 1) {
        if ($current_page > 1) {
            echo '<a class="button" href="?page=wqr-admin&paged=' . ($current_page - 1) . '">« Previous</a> ';
        }
        echo " Page $current_page of $total_pages ";
        if ($current_page < $total_pages) {
            echo '<a class="button" href="?page=wqr-admin&paged=' . ($current_page + 1) . '">Next »</a>';
        }
    }
    echo "</div></div>";

    echo "</div>";
}
?>
