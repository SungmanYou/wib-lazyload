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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wib-lazyload-public.css', [], $this->version, 'all');
    }

    public function enqueue_lazysizes()
    {
        printf('<script type="text/javascript" src="%s"></script>', plugin_dir_url(__FILE__) . 'js/lazysizes.min.js');
        printf('<script type="text/javascript" src="%s"></script>', plugin_dir_url(__FILE__) . 'js/lazysizes.blur-up.min.js');
    }

    public function filter_post_thumbnail_html()
    {}

}
