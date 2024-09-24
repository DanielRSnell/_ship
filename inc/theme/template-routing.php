<?php
/**
 * Custom Template Loader
 *
 * This class implements a custom template loading system for WordPress themes.
 * It allows for a more organized and flexible template structure.
 */
class Custom_Template_Loader
{
    /** @var string The current post type */
    private $post_type;

    /** @var string Base path for all template files */
    private $base_path = 'src/routes/';

    /** @var string Primary template category (e.g., 'core', 'single', 'archives') */
    private $base_type = '';

    /** @var string Secondary template category (e.g., 'post', 'page', 'product') */
    private $sub_type = '';

    /** @var string Specific template type (e.g., 'single', 'archive') */
    private $template_type = '';

    /**
     * Constructor: Sets up the template loader
     */
    public function __construct()
    {
        $this->post_type = get_post_type();
        add_action('template_redirect', array($this, 'load_template'), 1);
    }

    /**
     * Main method to load the appropriate template
     */
    public function load_template()
    {
        $this->determine_template_path();
        $template_path = $this->build_template_path();
        $custom_template = locate_template($template_path);

        if ($custom_template) {
            include $custom_template;
            exit;
        }

        // For debugging: uncomment to see the template path
        echo "Template path: " . $template_path;
    }

    /**
     * Determines the appropriate template path based on the current WordPress query
     */
    private function determine_template_path()
    {
        if (is_front_page() && is_home()) {
            $this->set_core_template('index');
        } elseif (is_front_page()) {
            $this->set_core_template('front-page');
        } elseif (is_home()) {
            $this->set_core_template('blog-index');
        } elseif (is_single()) {
            $this->set_single_template();
        } elseif (is_page()) {
            $this->set_page_template();
        } elseif (is_archive()) {
            $this->set_archive_template();
        } elseif (is_search()) {
            $this->set_core_template('search');
        } elseif (is_404()) {
            $this->set_core_template('404');
        }

        // Check for WooCommerce templates if WooCommerce is active
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            $this->set_woocommerce_template();
        }
    }

    /**
     * Sets the template path for core templates
     *
     * @param string $template_name The name of the core template
     */
    private function set_core_template($template_name)
    {
        $this->base_type = 'core';
        $this->sub_type = $template_name;
        $this->template_type = '';
    }

    /**
     * Sets the template path for single post types
     */
    private function set_single_template()
    {
        $this->base_type = 'single';
        $this->sub_type = $this->post_type;
        $this->template_type = 'single';
    }

    /**
     * Sets the template path for pages
     */
    private function set_page_template()
    {
        $this->base_type = 'pages';
        $this->sub_type = 'page';
        $this->template_type = 'single';
    }

    /**
     * Sets the template path for archive pages
     */
    private function set_archive_template()
    {
        if (is_post_type_archive()) {
            $this->base_type = 'archives';
            $this->sub_type = 'post-type';
            $this->template_type = $this->post_type;
        } elseif (is_category() || is_tag() || is_tax()) {
            $this->set_taxonomy_template();
        } elseif (is_author()) {
            $this->set_author_template();
        } elseif (is_date()) {
            $this->set_date_template();
        }
    }

    /**
     * Sets the template path for taxonomy archives
     */
    private function set_taxonomy_template()
    {
        $this->base_type = 'taxonomy';
        $this->sub_type = get_queried_object()->taxonomy;
        $this->template_type = 'archive';
    }

    /**
     * Sets the template path for author archives
     */
    private function set_author_template()
    {
        $this->base_type = 'archives';
        $this->sub_type = 'author';
        $this->template_type = get_the_author_meta('user_nicename');
    }

    /**
     * Sets the template path for date archives
     */
    private function set_date_template()
    {
        $this->base_type = 'archives';
        $this->sub_type = 'date';
        $this->template_type = 'archive';
    }

    /**
     * Sets the template path for WooCommerce pages
     */
    private function set_woocommerce_template()
    {
        if (is_shop()) {
            $this->set_core_template('shop');
        } elseif (is_product_category()) {
            $this->base_type = 'taxonomy';
            $this->sub_type = 'product_cat';
            $this->template_type = 'archive';
        } elseif (is_product_tag()) {
            $this->base_type = 'taxonomy';
            $this->sub_type = 'product_tag';
            $this->template_type = 'archive';
        } elseif (is_product()) {
            $this->base_type = 'woocommerce';
            $this->sub_type = 'product';
            $this->template_type = 'single';
        } elseif (is_cart()) {
            $this->set_core_template('cart');
        } elseif (is_checkout()) {
            $this->set_core_template('checkout');
        } elseif (is_account_page()) {
            $this->set_core_template('account');
        }
    }

    /**
     * Builds the final template path
     *
     * @return string The complete template path
     */
    private function build_template_path()
    {
        $template_path = $this->base_path . $this->base_type;
        if ($this->sub_type) {
            $template_path .= '/' . $this->sub_type;
        }
        if ($this->template_type) {
            $template_path .= '/' . $this->template_type;
        }
        return $template_path . '.php';
    }
}

// Instantiate the class
new Custom_Template_Loader();
