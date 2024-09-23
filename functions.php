<?php

// Require Once
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/setup.php';

Timber\Timber::init();
Timber::$dirname = ['src/views'];
Timber::$autoescape = false;

add_action('after_setup_theme', 'woocommerce_support');
function woocommerce_support()
{
    add_theme_support('woocommerce');
}

add_filter('woocommerce_template_loader_files', 'custom_woocommerce_template_loader_files', 10, 2);

function custom_woocommerce_template_loader_files($templates, $template_name)
{
    if ($template_name === 'archive-product.php') {
        array_unshift($templates, 'archive-product.php');
    }
    return $templates;
}

class ThemeManager
{
    private $timber_context;
    private $is_development;

    public function __construct()
    {
        $this->is_development = defined('WP_DEBUG') && WP_DEBUG;
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_filter('timber/context', [$this, 'capture_timber_context']);
        add_action('wp_footer', [$this, 'render_context_manager']);
    }

    public function enqueue_assets()
    {
        if ($this->is_development) {
            wp_enqueue_script('hmr', 'http://localhost:8080/js/app.js', [], null, true);
        } else {
            wp_enqueue_script('app', get_template_directory_uri() . '/dist/js/app.js', [], null, true);
            wp_enqueue_style('app', get_template_directory_uri() . '/dist/css/app.css');
        }
    }

    public function capture_timber_context($context)
    {
        $this->timber_context = $context;
        return $context;
    }

    public function render_context_manager()
    {
        if (!isset($this->timber_context['theme']->uri)) {
            error_log('Theme URI is not set.');
            return;
        }

        $the_uri = $this->timber_context['theme']->uri;
        error_log('Theme URI: ' . $the_uri);

        $is_browser_sync = is_string($the_uri) && strpos($the_uri, 'local') !== false;
        $is_debugger_enabled = isset($_GET['debugger']) && $_GET['debugger'] === 'true';

        if (($is_browser_sync || $is_debugger_enabled) && $this->timber_context) {
            try {
                Timber::render('debugger/index.twig', [
                    'context' => $this->timber_context,
                ]);
                error_log('Debugger rendered successfully.');
            } catch (Exception $e) {
                error_log('Error rendering debugger: ' . $e->getMessage());
            }
        }
    }
}

// Initialize the ThemeManager
new ThemeManager();
