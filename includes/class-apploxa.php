<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
} 
class Apploxa {
    public function run() {
        add_action('admin_menu', array($this, 'add_plugin_pages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles')); 
        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action('admin_notices', array($this, 'apploxa_check_woocommerce_active'));
    }
    }
   
   
 


public function apploxa_check_woocommerce_active() { ?>
 
        <div class="notice notice-error">
            <p><?php esc_html_e('WooCommerce is not active. Please activate WooCommerce to use Apploxa plugin.', 'apploxa'); ?></p>
        </div>
      
    <?php  
}

    public function add_plugin_pages() {
        add_menu_page(
            'Apploxa',
            'Apploxa',
            'manage_options',
            'apploxa',
            array($this, 'display_main_page'),
            'dashicons-whatsapp'
        );

        add_submenu_page(
            'apploxa',
            __('Customer notifications', 'apploxa'),
            __('Customer notifications', 'apploxa'),
            'manage_options',
            'apploxa-customer-notifications',
            array($this, 'display_customer_notifications')
        );

        add_submenu_page(
            'apploxa',
            __('Admin notifications', 'apploxa'),
            __('Admin notifications', 'apploxa'),
            'manage_options',
            'apploxa-admin-notifications',
            array($this, 'display_admin_notifications')
        );

        add_submenu_page(
            'apploxa',
            __('Login & Signup OTP', 'apploxa'),
            __('Login & Signup OTP', 'apploxa'),
            'manage_options',
            'apploxa-login-signup-otp',
            array($this, 'display_login_signup_otp')
        );

        add_submenu_page(
            'apploxa',
            __('Quick Message', 'apploxa'),
            __('Quick Message', 'apploxa'),
            'manage_options',
            'apploxa-quick-message',
            array($this, 'display_quick_message')
        );
				add_submenu_page(
						'apploxa',
						__('Templates', 'apploxa'),
						__('Templates', 'apploxa'),
						'manage_options',
						'apploxa-templates',
						array($this, 'display_templates')
				);
        add_submenu_page(
            'apploxa',
            __('Notification Logs', 'apploxa'),
            __('Notification Logs', 'apploxa'),
            'manage_options',
            'apploxa-logs',
            array($this, 'display_logs')
        );
    }


    public function display_main_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/main-page.php';
    }

    public function display_customer_notifications() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/customer-notifications.php';
    }

    public function display_admin_notifications() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/admin-notifications.php';
    }

    public function display_login_signup_otp() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/login-signup-otp.php';
    }

    public function display_quick_message() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/quick-message.php';
    }

    public function display_logs() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/logs.php';
    }
		public function display_templates() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/templates.php';
    }
    public function enqueue_styles($hook) {
      $plugin_pages = array(
          'toplevel_page_apploxa',
          'apploxa_page_apploxa-admin-notifications',
          'apploxa_page_apploxa-customer-notifications',
          'apploxa_page_apploxa-quick-message',
          'apploxa_page_apploxa-login-signup-otp',
          'apploxa_page_apploxa-logs',
					'apploxa_page_apploxa-templates'
      );

      if (!in_array($hook, $plugin_pages)) {
          return;
      } 
	  wp_enqueue_style('bootstrap_apploxa', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/css/bootstrap.min.css',null,APPLOXA_VERISON);
      wp_enqueue_script('bootstrap_apploxa', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/js/bootstrap.bundle.min.js', array('jquery'), APPLOXA_VERISON, true);




      wp_enqueue_style('toastr_css', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/css/toaster.css',null,APPLOXA_VERISON);
      wp_enqueue_script('toastr_js', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/js/toaster.js',null,APPLOXA_VERISON, true);


        function enqueue_custom_admin_js() {
            // Enqueue a dummy script (you can use an actual JS file path if needed)
            wp_enqueue_script( 'custom-admin-script', '' ,null,APPLOXA_VERISON, true);

            // Localize the Ajax URL for use in JavaScript
            wp_localize_script( 'custom-admin-script', 'apploxa', array(
                'ajaxurl' => esc_url( admin_url('admin-ajax.php') )
            ));
        }
        add_action( 'admin_enqueue_scripts', 'enqueue_custom_admin_js' );

	  

      // Enqueue custom plugin styles and scripts
      wp_enqueue_style('apploxa-admin-style', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/css/apploxa-admin.css',null,APPLOXA_VERISON);
      wp_enqueue_script('apploxa-admin-script', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/js/apploxa-admin.js', null,APPLOXA_VERISON, true);


			// Enqueue DataTables
 
	   wp_enqueue_style('datatables_apploxa_css', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/css/jquery.dataTables.min.css',null,APPLOXA_VERISON); 
	  
	   wp_enqueue_script('datatables_apploxa_js', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/js/jquery.dataTables.min.js', array('jquery'), APPLOXA_VERISON, true);


}






}
