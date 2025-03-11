<?php
if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', 'wqr_add_product_qr_metabox');
function wqr_add_product_qr_metabox() {
    add_meta_box('wqr_qr_code', 'Product QR Code', 'wqr_display_product_qr', 'product', 'side', 'default');
}

function wqr_display_product_qr($post) {
    $qr_url = wqr_generate_qr($post->ID);
    $download_url = admin_url("admin.php?page=wqr-admin&wqr_download={$post->ID}");

    echo "<div style='text-align: center;'>
            <img src='{$qr_url}' width='200' height='200' style='border: 1px solid #ccc; padding: 10px;' />
            <p><a href='{$download_url}' class='button'>Download QR Code</a></p>
          </div>";
}
?>
