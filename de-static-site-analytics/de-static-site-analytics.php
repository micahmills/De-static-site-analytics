<?php
/**
 * Plugin Name: DE Static Site Analytics
 * Plugin URI: https://github.com/micahmills/De-static-site-analytics
 * Description: A simple Google Tag Manager plugin for WordPress multisite that adds GTM tracking code to all pages.
 * Version: 1.0.0
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
        
        // Add GTM code to front-end
        add_action('wp_head', array($this, 'add_gtm_head'), 1);
        add_action('wp_body_open', array($this, 'add_gtm_body'), 1);
        
        // Fallback for themes that don't support wp_body_open
        if (!function_exists('wp_body_open')) {
            add_action('wp_footer', array($this, 'add_gtm_body_fallback'), 1);
        }
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
            'Google Tag Manager Settings',
            array($this, 'render_section_description'),
            'de-static-site-analytics'
        );
        
        add_settings_field(
            'de_gtm_container_id',
            'GTM Container ID',
            array($this, 'render_container_id_field'),
            'de-static-site-analytics',
            'de_static_site_analytics_section'
        );
    }
    
    /**
     * Sanitize GTM Container ID
     */
    public function sanitize_container_id($input) {
        $input = sanitize_text_field($input);
        
        // Validate GTM container ID format (GTM-XXXXXXX)
        if (!empty($input) && !preg_match('/^GTM-[A-Z0-9]+$/i', $input)) {
            add_settings_error(
                $this->option_name,
                'invalid_gtm_id',
                'Invalid GTM Container ID format. It should be in the format GTM-XXXXXXX',
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
        echo '<p>Enter your Google Tag Manager container ID below. The GTM code will be automatically added to all pages on your site.</p>';
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
               placeholder="GTM-XXXXXXX">
        <p class="description">Enter your Google Tag Manager container ID (e.g., GTM-XXXXXXX)</p>
        <?php
    }
    
    /**
     * Get GTM Container ID
     */
    private function get_container_id() {
        return get_option($this->option_name, '');
    }
    
    /**
     * Add GTM code to head
     */
    public function add_gtm_head() {
        $container_id = $this->get_container_id();
        
        if (empty($container_id)) {
            return;
        }
        
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
     * Add GTM noscript code to body
     */
    public function add_gtm_body() {
        $container_id = $this->get_container_id();
        
        if (empty($container_id)) {
            return;
        }
        
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
    public function add_gtm_body_fallback() {
        if (!did_action('wp_body_open')) {
            $this->add_gtm_body();
        }
    }
}

// Initialize the plugin
new DE_Static_Site_Analytics();
