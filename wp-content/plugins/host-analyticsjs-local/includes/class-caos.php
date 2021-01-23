<?php
/* * * * * * * * * * * * * * * * * * * *
 *  ██████╗ █████╗  ██████╗ ███████╗
 * ██╔════╝██╔══██╗██╔═══██╗██╔════╝
 * ██║     ███████║██║   ██║███████╗
 * ██║     ██╔══██║██║   ██║╚════██║
 * ╚██████╗██║  ██║╚██████╔╝███████║
 *  ╚═════╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝
 *
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev/wordpress-plugins/caos/
 * @copyright: (c) 2020 Daan van den Bergh
 * @license  : GPL2v2 or later
 * * * * * * * * * * * * * * * * * * * */

defined('ABSPATH') || exit;

class CAOS
{
    /**
     * CAOS constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->do_ajax();
        $this->do_setup();

        if(is_admin()) {
            $this->do_settings();
        }

        if(!is_admin()) {
            $this->do_frontend();
            $this->do_tracking_code();
        }

        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Define constants
     */
    public function define_constants()
    {
        define('CAOS_SITE_URL', 'https://daan.dev');
        define('CAOS_BLOG_ID', get_current_blog_id());
        define('CAOS_OPT_TRACKING_ID', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_TRACKING_ID)));
        define('CAOS_OPT_ALLOW_TRACKING', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_ALLOW_TRACKING)));
        define('CAOS_OPT_COOKIE_NAME', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_COOKIE_NOTICE_NAME)));
        define('CAOS_OPT_COOKIE_VALUE', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_COOKIE_VALUE)));
        define('CAOS_OPT_SNIPPET_TYPE', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_SNIPPET_TYPE)));
        define('CAOS_OPT_SCRIPT_POSITION', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_SCRIPT_POSITION)) ?: 'header');
        define('CAOS_OPT_COMPATIBILITY_MODE', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_COMPATIBILITY_MODE)) ?: null);
        define('CAOS_OPT_COOKIE_EXPIRY_DAYS', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_GA_COOKIE_EXPIRY_DAYS, 30)));
        define('CAOS_OPT_ADJUSTED_BOUNCE_RATE', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_ADJUSTED_BOUNCE_RATE)));
        define('CAOS_OPT_ENQUEUE_ORDER', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_ENQUEUE_ORDER)) ?: 10);
        define('CAOS_OPT_ANONYMIZE_IP', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_ANONYMIZE_IP)));
        define('CAOS_OPT_TRACK_ADMIN', esc_attr(get_option(CAOS_Admin_Settings::CAOS_BASIC_SETTING_TRACK_ADMIN)));
        define('CAOS_OPT_DISABLE_DISPLAY_FEAT', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_DISABLE_DISPLAY_FEATURES)));
        define('CAOS_OPT_REMOTE_JS_FILE', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_JS_FILE)) ?: 'analytics.js');
        define('CAOS_OPT_CACHE_DIR', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_CACHE_DIR)) ?: '/uploads/caos/');
        define('CAOS_OPT_CDN_URL', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_CDN_URL)));
        define('CAOS_OPT_EXT_CAPTURE_OUTBOUND_LINKS', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_CAPTURE_OUTBOUND_LINKS)));
        define('CAOS_OPT_UNINSTALL_SETTINGS', esc_attr(get_option(CAOS_Admin_Settings::CAOS_ADV_SETTING_UNINSTALL_SETTINGS)));
        define('CAOS_OPT_EXT_PLUGIN_HANDLING', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_PLUGIN_HANDLING)) ?: 'set_redirect');
        define('CAOS_OPT_EXT_STEALTH_MODE', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_STEALTH_MODE)));
        define('CAOS_OPT_EXT_TRACK_AD_BLOCKERS', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_TRACK_AD_BLOCKERS)));
        define('CAOS_OPT_EXT_LINKID', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_LINKID)));
        define('CAOS_OPT_EXT_OPTIMIZE', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_OPTIMIZE)));
        define('CAOS_OPT_EXT_OPTIMIZE_ID', esc_attr(get_option(CAOS_Admin_Settings::CAOS_EXT_SETTING_OPTIMIZE_ID)));
        define('CAOS_COOKIE_EXPIRY_SECONDS', CAOS_OPT_COOKIE_EXPIRY_DAYS ? CAOS_OPT_COOKIE_EXPIRY_DAYS * 86400 : 2592000);
        define('CAOS_CRON', 'caos_update_analytics_js');
        define('CAOS_GA_URL', 'https://www.google-analytics.com');
        define('CAOS_GTM_URL', 'https://www.googletagmanager.com');
        define('CAOS_REMOTE_URL', CAOS_OPT_REMOTE_JS_FILE == 'gtag.js' ? CAOS_GTM_URL : CAOS_GA_URL);
        define('CAOS_LOCAL_DIR', WP_CONTENT_DIR . CAOS_OPT_CACHE_DIR);
        define('CAOS_LOCAL_FILE_DIR', CAOS_LOCAL_DIR . CAOS_OPT_REMOTE_JS_FILE);
        define('CAOS_LOCAL_FILE_URL', self::get_url());
        define('CAOS_PROXY_URI', '/wp-json/caos/v1/proxy');
    }

    /**
     * @return CAOS_AJAX
     */
    private function do_ajax()
    {
        return new CAOS_AJAX();
    }

    /**
     * @return CAOS_Setup
     */
    private function do_setup()
    {
        register_uninstall_hook(CAOS_PLUGIN_FILE, 'CAOS::do_uninstall');

        return new CAOS_Setup();
    }

    /**
     * @return CAOS_Admin_Settings
     */
    private function do_settings()
    {
        return new CAOS_Admin_Settings();
    }

    /**
     * @return CAOS_Frontend_Functions
     */
    private function do_frontend()
    {
        return new CAOS_Frontend_Functions();
    }

    /**
     * @return CAOS_Frontend_Tracking
     */
    private function do_tracking_code()
    {
        return new CAOS_Frontend_Tracking();
    }

    /**
     * Register CAOS Proxy so endpoint can be used.
     * For using Stealth mode, SSL is required.
     */
    public function register_routes()
    {
        if (CAOS_OPT_EXT_STEALTH_MODE) {
            $proxy = new CAOS_API_Proxy();
            $proxy->register_routes();
        }

        if (CAOS_OPT_EXT_TRACK_AD_BLOCKERS) {
            $proxy = new CAOS_API_AdBlockDetect();
            $proxy->register_routes();
        }
    }

    /**
     * @return string
     */
    public static function get_url()
    {
        $url = content_url() . CAOS_OPT_CACHE_DIR . CAOS_OPT_REMOTE_JS_FILE;

        if (CAOS_OPT_CDN_URL) {
            $url = str_replace(get_site_url(CAOS_BLOG_ID), '//' . CAOS_OPT_CDN_URL, $url);
        }

        return $url;
    }

    /**
     * @return CAOS_Uninstall
     * @throws ReflectionException
     */
    public static function do_uninstall()
    {
        return new CAOS_Uninstall();
    }

    /**
     * Helper to return WordPress filesystem subclass.
     *
     * @return WP_Filesystem_Base $wp_filesystem
     */
    public static function filesystem()
    {
        global $wp_filesystem;

        if ( is_null( $wp_filesystem ) ) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem();
        }

        return $wp_filesystem;
    }
}
