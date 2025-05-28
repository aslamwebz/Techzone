<?php
header('Content-Type: image/jpeg');
$width = 600;
$height = 400;
$image = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($image, 204, 204, 204);
$text_color = imagecolorallocate($image, 150, 150, 150);
imagefill($image, 0, 0, $bg_color);
$text = 'No Image Available';
$font_size = 5;
$text_width = imagefontwidth($font_size) * strlen($text);
$text_x = ($width - $text_width) / 2;
$text_y = ($height - imagefontheight($font_size)) / 2;
imagestring($image, $font_size, $text_x, $text_y, $text, $text_color);
imagejpeg($image);
imagedestroy($image);
?>
