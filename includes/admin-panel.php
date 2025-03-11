<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'wqr_add_woocommerce_submenu');
function wqr_add_woocommerce_submenu() {
    add_submenu_page('woocommerce', 'QR Codes', 'QR Codes', 'manage_options', 'wqr-admin', 'wqr_admin_page');
}

function wqr_admin_page() {
    global $wpdb;
    
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    $products = wc_get_products(['limit' => $per_page, 'offset' => $offset]);
    $total_products = wc_get_products(['limit' => -1]);
    $total_pages = ceil(count($total_products) / $per_page);

    echo "<div class='wrap'><h1>WooCommerce QR Codes</h1>";
    echo "<table class='wp-list-table widefat fixed striped'>";
    echo "<thead><tr><th>Product</th><th>QR Code</th><th>Download</th></tr></thead><tbody>";

    foreach ($products as $product) {
        $qr_url = wqr_generate_qr($product->get_id());
        $download_url = admin_url("admin.php?page=wqr-admin&wqr_download={$product->get_id()}");

        echo "<tr>
                <td>{$product->get_name()}</td>
                <td><img src='{$qr_url}' width='200' height='200' /></td>
                <td><a href='{$download_url}' class='button'>Download</a></td>
              </tr>";
    }

    echo "</tbody></table>";

    echo "<div class='tablenav'><div class='tablenav-pages'>";
    if ($total_pages > 1) {
        if ($current_page > 1) echo '<a class="button" href="?page=wqr-admin&paged=' . ($current_page - 1) . '">« Previous</a> ';
        echo " Page $current_page of $total_pages ";
        if ($current_page < $total_pages) echo '<a class="button" href="?page=wqr-admin&paged=' . ($current_page + 1) . '">Next »</a>';
    }
    echo "</div></div>";

    echo "</div>";
}
?>
