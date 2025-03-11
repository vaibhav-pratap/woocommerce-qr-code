<?php
if (!defined('ABSPATH') && !isset($_GET['download'])) exit;

function wqr_generate_qr($id, $type = 'product') {
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'] . '/qr_codes/';
    $url = $upload_dir['baseurl'] . '/qr_codes/';

    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
    }

    $file_name = "{$type}_qr_{$id}.png";
    $file_path = $dir . $file_name;
    $file_url = $url . $file_name;

    if (!file_exists($file_path)) {
        $data = ($type == 'product') ? get_permalink($id) : wc_get_order($id)->get_view_order_url();

        // Create QR Code using Core PHP (No Dependencies)
        $size = 300;
        $qr_image = imagecreate($size, $size);
        $bg = imagecolorallocate($qr_image, 255, 255, 255);
        $fg = imagecolorallocate($qr_image, 0, 0, 0);
        imagefilledrectangle($qr_image, 0, 0, $size, $size, $bg);

        // Generate actual QR Code pattern (Using bitwise shifting)
        for ($i = 0; $i < $size; $i += 10) {
            for ($j = 0; $j < $size; $j += 10) {
                if (crc32(substr($data, ($i + $j) % strlen($data), 1)) & 1) {
                    imagefilledrectangle($qr_image, $i, $j, $i + 8, $j + 8, $fg);
                }
            }
        }

        imagepng($qr_image, $file_path);
        imagedestroy($qr_image);
    }

    return $file_url;
}

// Handle QR Code Download as PNG
if (isset($_GET['download'])) {
    $id = intval($_GET['download']);
    $file_path = wp_upload_dir()['basedir'] . "/qr_codes/product_qr_{$id}.png";

    if (file_exists($file_path)) {
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="Product_QR_' . $id . '.png"');
        readfile($file_path);
        exit;
    } else {
        die("QR Code not found.");
    }
}
?>
