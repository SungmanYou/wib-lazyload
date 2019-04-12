<?php
    $metadata = $_REQUEST['metadata'];
    $size = $_REQUEST['size'];
    $id = $_REQUEST['id'];
    $height = isset($metadata['sizes'][$size]) ? intval($metadata['sizes'][$size]['height']) : intval($metadata['height']);
    $width = isset($metadata['sizes'][$size]) ? intval($metadata['sizes'][$size]['width']) : intval($metadata['width']);
    if ($height === $width) {
        $lqip = wp_get_attachment_image_src($id, 'lqip_square', false);
    } else {
        $lqip = wp_get_attachment_image_src($id, 'lqip', false);
    }
    $lowsrc = $lqip[0];
    $padding_bottom = $lqip[2] / $lqip[1] * 100;
?>
<div class="ls-mediabox"
     style="padding-bottom: <?=$padding_bottom?>%;">
    <img class="ls-mediabox-img lazyload blur-up"
         data-sizes="auto"
         data-srcset="<?=esc_attr(wp_get_attachment_image_srcset($id, $size, $metadata))?>"
         data-lowsrc="<?=esc_attr($lowsrc)?>" />
</div>