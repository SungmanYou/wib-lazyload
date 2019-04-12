<?php
/**
 * @link       https://github.com/SungmanYou
 * @since      1.0.0
 * @package    Wib_Lazyload
 * @subpackage Wib_Lazyload/public
 * @author     Sungman You <sungman.you@gmail.com>
 */
class Wib_Lazyload_Public
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/wib-lazyload-public.css',
            [],
            $this->version,
            'all'
        );
    }

    public function enqueue_lazysizes()
    {
        printf(
            '<script src="%s" async=""></script>',
            plugin_dir_url(__FILE__) . 'js/lazysizes.min.js'
        );
        printf(
            '<script src="%s" async=""></script>',
            plugin_dir_url(__FILE__) . 'js/lazysizes.blur-up.min.js'
        );
    }

    public function filter_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr)
    {
        if (is_feed() || intval(get_query_var('print')) == 1 || intval(get_query_var('printpage')) == 1) {return $html;}
        if (!$html || empty($html) || $html == '') {return $html;}
        if (!isset($attr['class']) || !preg_match("~\b" . 'lazyload' . "\b~", $attr['class'])) {return $html;}
        $metadata = wp_get_attachment_metadata($post_thumbnail_id);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);

        $imgs = $dom->getElementsByTagName('img');
        if (empty($imgs)) {return $html;}
        foreach ($imgs as $img) {
            if ($img->hasAttribute('width')) {$width = $img->getAttribute('width');} elseif (isset($metadata['sizes'][$size])) {$width = $metadata['sizes'][$size]['width'];} else { $width = $metadata['width'];}
            if ($img->hasAttribute('height')) {$height = $img->getAttribute('height');} elseif (isset($metadata['sizes'][$size])) {$height = $metadata['sizes'][$size]['height'];} else { $height = $metadata['height'];}
            $lowsrc = $height === $width
            ? wp_get_attachment_image_src($post_thumbnail_id, 'lqip_square', false)[0]// sqaure
             : wp_get_attachment_image_src($post_thumbnail_id, 'lqip', false)[0]; // original ratio

            $mediabox = $dom->createElement('div');
            $mediabox->setAttribute('class', 'ls-mediabox');
            $mediabox->setAttribute('style', 'padding-bottom: ' . intval($height) / intval($width) * 100 . '%;');

            $mediabox_clone = $mediabox->cloneNode();
            $img->parentNode->replaceChild($mediabox_clone, $img);
            $mediabox_clone->appendChild($img);
            $img->setAttribute('data-sizes', 'auto');
            $img->removeAttribute('sizes');
            $img->setAttribute('data-srcset', $img->getAttribute('srcset'));
            $img->removeAttribute('srcset');
            $img->setAttribute('data-src', $img->getAttribute('src'));
            $img->removeAttribute('src');
            $img->setAttribute('class', implode(' ', [$img->getAttribute('class'), 'ls-mediabox-img blur-up']));
            $img->setAttribute('data-lowsrc', $lowsrc);
        }
        $html = $dom->saveHTML();
        return $html;
    }

    public function define_global_functions()
    {
        if (!function_exists('wib_ll_wp_get_attachment_image')) {
            function wib_ll_wp_get_attachment_image($attachment_id = null, $size = 'full')
            {
                if (!isset($attachment_id)) {return;}
                $_REQUEST['metadata'] = wp_get_attachment_metadata($attachment_id);
                $_REQUEST['id'] = $attachment_id;
                $_REQUEST['size'] = $size;

                ob_start();
                include 'partials/attachment-image.php';
                $html = ob_get_clean();
                echo $html;
            }
        }
    }
}