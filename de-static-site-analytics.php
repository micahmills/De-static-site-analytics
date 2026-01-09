<?php
/**
 * Plugin Name: DE Static Site Analytics
 * Plugin URI: https://github.com/micahmills/De-static-site-analytics
 * Description: A simple plugin for WordPress multisite that adds Google Tag Manager or Google Analytics tracking code to all pages.
 * Version: 1.1.0
 * Author: Micah Mills
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network: true
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class DE_Static_Site_Analytics {
    private $option_name = 'de_gtm_container_id';
    
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('network_admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add tracking code to front-end
        add_action('wp_head', array($this, 'add_tracking_head'), 1);
        add_action('wp_body_open', array($this, 'add_tracking_body'), 1);
        add_action('wp_footer', array($this, 'add_tracking_body_fallback'), 1);
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_options_page(
            'DE Static Site Analytics',
            'DE Analytics',
            'manage_options',
            'de-static-site-analytics',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'de_static_site_analytics_settings',
            $this->option_name,
            array(
                'type' => 'string',
                'sanitize_callback' => array($this, 'sanitize_container_id'),
                'default' => ''
            )
        );
        
        add_settings_section(
            'de_static_site_analytics_section',
            'Analytics Settings',
            array($this, 'render_section_description'),
            'de-static-site-analytics'
        );
        
        add_settings_field(
            'de_gtm_container_id',
            'Tag ID',
            array($this, 'render_container_id_field'),
            'de-static-site-analytics',
            'de_static_site_analytics_section'
        );
    }
    
    /**
     * Sanitize Tag ID (GTM or GA4)
     */
    public function sanitize_container_id($input) {
        $input = sanitize_text_field($input);
        
        // Validate GTM container ID format (GTM-XXXXXXX) or GA4 format (G-XXXXXXXXXX)
        if (!empty($input) && !preg_match('/^(GTM-[A-Z0-9]+|G-[A-Z0-9]+)$/i', $input)) {
            add_settings_error(
                $this->option_name,
                'invalid_tag_id',
                'Invalid Tag ID format. It should be in the format GTM-XXXXXXX for Google Tag Manager or G-XXXXXXXXXX for Google Analytics 4',
                'error'
            );
            return get_option($this->option_name);
        }
        
        return strtoupper($input);
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Show error/update messages
        settings_errors('de_static_site_analytics_messages');
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('de_static_site_analytics_settings');
                do_settings_sections('de-static-site-analytics');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Render section description
     */
    public function render_section_description() {
        echo '<p>Enter your Google Tag Manager container ID or Google Analytics 4 measurement ID below. The tracking code will be automatically added to all pages on your site.</p>';
    }
    
    /**
     * Render container ID field
     */
    public function render_container_id_field() {
        $value = get_option($this->option_name, '');
        ?>
        <input type="text" 
               name="<?php echo esc_attr($this->option_name); ?>" 
               id="<?php echo esc_attr($this->option_name); ?>"
               value="<?php echo esc_attr($value); ?>" 
               class="regular-text"
               placeholder="GTM-XXXXXXX or G-XXXXXXXXXX">
        <p class="description">Enter your Google Tag Manager container ID (e.g., GTM-XXXXXXX) or Google Analytics 4 measurement ID (e.g., G-XXXXXXXXXX)</p>
        <?php
    }
    
    /**
     * Get Tag ID
     */
    private function get_container_id() {
        return get_option($this->option_name, '');
    }
    
    /**
     * Determine if tag is GTM or GA4
     */
    private function is_gtm_tag($tag_id) {
        return stripos($tag_id, 'GTM-') === 0;
    }
    
    /**
     * Add tracking code to head
     */
    public function add_tracking_head() {
        $container_id = $this->get_container_id();
        
        if (empty($container_id)) {
            return;
        }
        
        if ($this->is_gtm_tag($container_id)) {
            $this->add_gtm_head($container_id);
        } else {
            $this->add_ga4_head($container_id);
        }
    }
    
    /**
     * Add GTM code to head
     */
    private function add_gtm_head($container_id) {
        ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo esc_js($container_id); ?>');</script>
        <!-- End Google Tag Manager -->
        <?php
    }
    
    /**
     * Add GA4 code to head
     */
    private function add_ga4_head($measurement_id) {
        ?>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($measurement_id); ?>"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '<?php echo esc_js($measurement_id); ?>');
        </script>
        <!-- End Google tag (gtag.js) -->
        <?php
    }
    
    /**
     * Add tracking code to body
     */
    public function add_tracking_body() {
        $container_id = $this->get_container_id();
        
        if (empty($container_id)) {
            return;
        }
        
        // Only GTM requires noscript tag in body
        if ($this->is_gtm_tag($container_id)) {
            $this->add_gtm_body($container_id);
        }
    }
    
    /**
     * Add GTM noscript code to body
     */
    private function add_gtm_body($container_id) {
        ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($container_id); ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php
    }
    
    /**
     * Fallback for themes that don't support wp_body_open
     */
    public function add_tracking_body_fallback() {
        if (!did_action('wp_body_open')) {
            $this->add_tracking_body();
        }
    }
}

// Initialize the plugin
new DE_Static_Site_Analytics();
