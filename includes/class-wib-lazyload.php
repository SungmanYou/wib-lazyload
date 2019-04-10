<?php
/**
 * @link       https://github.com/SungmanYou
 * @since      1.0.0
 * @package    Wib_Lazyload
 * @subpackage Wib_Lazyload/includes
 * @author     Sungman You <sungman.you@gmail.com>
 */
class Wib_Lazyload
{

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct()
    {
        if (defined('WIB_LAZYLOAD_VERSION')) {$this->version = WIB_LAZYLOAD_VERSION;} else { $this->version = '1.0.0';}
        $this->plugin_name = 'wib-lazyload';
        $this->load_dependencies();
        $this->define_public_hooks();
    }

    private function load_dependencies()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wib-lazyload-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wib-lazyload-public.php';
        $this->loader = new Wib_Lazyload_Loader();
    }

    private function define_public_hooks()
    {
        $plugin_public = new Wib_Lazyload_Public($this->get_plugin_name(), $this->get_version());
        $plugin_public->define_global_functions();
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 0);
        $this->loader->add_action('wp_footer', $plugin_public, 'enqueue_lazysizes', 0); // MUST be hooked on 'wp_footer' for top priority.
        $this->loader->add_filter('post_thumbnail_html', $plugin_public, 'filter_post_thumbnail_html', 10, 5);
    }

    // Runner
    public function run()
    {$this->loader->run();}

    // Getters
    public function get_plugin_name()
    {return $this->plugin_name;}
    public function get_loader()
    {return $this->loader;}
    public function get_version()
    {return $this->version;}

}