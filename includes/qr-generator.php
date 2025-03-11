<?php
if (!defined('ABSPATH')) exit;

function wqr_generate_qr($id, $type = 'product') {
    $data = ($type === 'product') ? get_permalink($id) : wc_get_order($id)->get_view_order_url();
    return "https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=" . urlencode($data) . "&choe=UTF-8";
}

// QR Code Download as PNG
if (isset($_GET['wqr_download'])) {
    $id = intval($_GET['wqr_download']);
    $type = ($_GET['type'] === 'order') ? 'order' : 'product';
    $qr_url = wqr_generate_qr($id, $type);

    $image = file_get_contents($qr_url);
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="QR_' . $type . '_' . $id . '.png"');
    echo $image;
    exit;
}
?>
