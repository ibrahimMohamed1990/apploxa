<?php
/*
Plugin Name: Apploxa
Plugin URI: https://github.com/ibrahimMohamed1990/apploxa
Description: Whatsapploxa - whatsapp notifications to your customers
Version: 1.0.0
Author: Apploxa
Author URI: https://www.linkedin.com/in/ibrahim-mohamed-b805b5151/
Requires at least: 4.9
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: apploxa
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/ 
// Prevent direct access to the plugin file
if (!defined('ABSPATH')) {
    exit;
}
define("APPLOXA_VERISON","1.0.0");
function apploxa_activation_check() {
   // Check if WooCommerce is active
   if (!is_plugin_active('woocommerce/woocommerce.php') && !class_exists('WooCommerce')) {
       // Deactivate the plugin immediately
       //deactivate_plugins(plugin_basename(__FILE__));
       // Display an error message and stop activation
       wp_die(
           esc_html( __('Apploxa Plugin requires WooCommerce to be installed and activated. The plugin has been deactivated.', 'apploxa')),
           esc_html( __('Plugin Activation Error', 'apploxa')),
           array('back_link' => true)
       );
   }
}
register_activation_hook(__FILE__, 'apploxa_activation_check');
register_activation_hook(__FILE__, 'apploxa_activate');
// Include the main plugin class

 
function apploxa_activate() {
  global $wpdb;
  $table_name =  $wpdb->prefix .'apploxa_logs';
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NULL,
    action varchar(50) NULL,
    message text NOT NULL,
    status boolean null default 0,
    whats_app_number varchar(20) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY id (id)
    ) $charset_collate;";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);

}


function apploxa_admin_nav($current_page = '') {
  $tabs = array(
      'apploxa' => __('Apploxa Settings', 'apploxa'),
      'apploxa-customer-notifications' => esc_html(__('Customer notifications', 'apploxa')),
      'apploxa-admin-notifications' => esc_html(__('Admin notifications', 'apploxa')),
      'apploxa-login-signup-otp' => esc_html(__('Login & Signup OTP', 'apploxa')),
      'apploxa-quick-message' => esc_html(__('Quick Message', 'apploxa')),
      'apploxa-templates' => esc_html(__('Templates', 'apploxa')),
      'apploxa-logs' => esc_html(__('Notification Logs', 'apploxa'))
  );


    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ($tab === $current_page) ? ' nav-tab-active' : '';
        echo '<a href="?page=' . esc_attr($tab) . '" class="nav-tab' . esc_html($class) . '">' . esc_html($name) . '</a>';
    }
    echo '</h2>';
}
 
// Initialize the plugin
function apploxa_run_apploxa() {
require_once plugin_dir_path(__FILE__) . 'includes/class-apploxa.php';
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    require_once plugin_dir_path(__FILE__) . 'includes/actions.php';
    require_once plugin_dir_path(__FILE__) . 'includes/ajax.php';
}

    load_plugin_textdomain('apploxa', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    $plugin = new Apploxa();
    $plugin->run();

}
apploxa_run_apploxa();

