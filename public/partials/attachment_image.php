<?php
    $height = isset($_REQUEST['metadata']['sizes'][$_REQUEST['size']])
    ? $_REQUEST['metadata']['sizes'][$_REQUEST['size']]['height']
    : $_REQUEST['metadata']['height'];
    $width = isset($_REQUEST['metadata']['sizes'][$_REQUEST['size']])
    ? $_REQUEST['metadata']['sizes'][$_REQUEST['size']]['width']
    : $_REQUEST['metadata']['width'];
    $srcset = wp_get_attachment_image_srcset($_REQUEST['id'], $_REQUEST['size'], $_REQUEST['metadata']);
    $lowsrc = $height === $width
    ? wp_get_attachment_image_src($_REQUEST['id'], 'lqip_square', false)[0] // sqaure
    : wp_get_attachment_image_src($_REQUEST['id'], 'lqip', false)[0]; // original ratio
?>

<div class="ls-mediabox"
     style="padding-bottom: <?=(int) $height / (int) $width * 100?>%;">
    <img class="ls-mediabox-img lazyload blur-up"
         width="<?=(int) $height?>"
         height="<?=(int) $width?>"
         data-sizes="auto"
         data-srcset="<?=esc_attr($srcset)?>"
         data-lowsrc="<?=esc_attr($lowsrc)?>" />
</div>