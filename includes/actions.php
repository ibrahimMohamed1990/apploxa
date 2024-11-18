<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
function apploxa_handle_message($order_id=null,$message=null,$user_id=null){
	if(!$message){return;}
  $order = new WC_Order( $order_id );
	if($order_id){
		$vars = array(
			'{first_name}'    => $order->get_billing_first_name(),
			'{last_name}'     => $order->get_billing_last_name(),
			'{total}'         => $order->get_total(),
			'{order_id}'      => $order->get_id(),
			'{stat}'          => $order->get_status()
		);
	}
	if($user_id){
		$user=get_userdata($user_id);
		$vars = array(
			'{first_name}'    => $user->first_name,
			'{last_name}'     => $user->last_name
		);
	}
  $final_message = strtr($message, $vars);
  return $final_message;
}

function apploxa_send_message($message,$phone=null,$order_id=null){
	if(!$message){return;}
   $token = get_option( 'apploxa_token' );
  if($phone){
    $phoneNumber= $phone;
  }else {
    $order = new WC_Order( $order_id );
    $user_id = $order->get_user_id();
    $whatsnumber = get_user_meta($user_id, 'apploxa_whatsapp', 1);
		if(!$order){return;}
    $phoneNumber = ltrim($order->get_billing_phone(), '+');
    if ( empty($order->get_billing_phone()) || empty($phoneNumber)  ) return;
    $phoneNumber = $order->get_billing_phone();
    if ( ! str_starts_with($phoneNumber, '+')) {
      $phoneNumber = '+' . $order->get_billing_phone();
    }
  }

  $args = array(
      'body' => wp_json_encode(array(
          'phone' => $whatsnumber?$whatsnumber:$phoneNumber,
          'message' => $message,
      )),
      'headers' => array(
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $token ,
      ),
  );

	$response = wp_remote_post('https://api.apploxa.com/hook/whatsapp/v2/message/send-plain-text', $args);
 	$code  = wp_remote_retrieve_response_code($response);
  $body = json_decode( wp_remote_retrieve_body($response) , 1 );

	if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			return $error_message;
	}
			if($code == 200){
				return true;
			}else {
				return $body['message'];
			}

}


function apploxa_admin_message($message=null,$order_id=null){
	if(!$message){return;}
   $token = get_option( 'apploxa_token' );
   $phoneNumber=get_option( 'apploxa_admin_whatsapp' );

  $args = array(
      'body' => wp_json_encode(array(
          'phone' => $phoneNumber,
          'message' => $message,
      )),
      'headers' => array(
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $token ,
      ),
  );

	$response = wp_remote_post('https://api.apploxa.com/hook/whatsapp/v2/message/send-plain-text', $args);
 	$code  = wp_remote_retrieve_response_code($response);
	// $body = json_decode( wp_remote_retrieve_body($response) , 1 );
	if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			return false;
	}
			if($code == 200){
				return true;
			}else {
				return false;
			}

}


function apploxa_save_options() {
		$order_statuses = wc_get_order_statuses();
    if ( isset($_POST['apploxa_token']) && isset($_POST['apploxa_admin_whatsapp']) ) {
		if (isset($_POST['apploxa_admin_nonce'])) {
			$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
			if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
        $option_value = sanitize_text_field(wp_unslash($_POST['apploxa_token']));
        $apploxa_admin_whatsapp = sanitize_text_field(wp_unslash($_POST['apploxa_admin_whatsapp']));
        update_option('apploxa_admin_whatsapp', $apploxa_admin_whatsapp);
        update_option('apploxa_token', $option_value);
		add_action('admin_print_scripts', 'apploxa_custom_admin_js');
   	 }
	}
 }
 


    if ( isset($_POST['save_cust_nots']) ) {
		if (isset($_POST['apploxa_admin_nonce'])) {
		$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
		if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
			foreach ( $order_statuses as $status_slug => $status_name ) {
			    $status_slug_with_underscores =  str_replace( 'wc-', '', $status_slug );
			    $slug = 'apploxa_'.$status_slug_with_underscores ;
				if(isset($_POST[$slug])){
					update_option( $slug, sanitize_text_field(wp_unslash($_POST[$slug])));
				}
					

			}
				if(isset($_POST['apploxa_abandoned_cart']) && isset($_POST['apploxa_custom_interval'])){
					update_option('apploxa_abandoned_cart',sanitize_text_field(wp_unslash($_POST['apploxa_abandoned_cart'])));
					update_option('apploxa_custom_interval',sanitize_text_field(wp_unslash($_POST['apploxa_custom_interval'])));
				}

				if(isset($_POST['apploxa_order_note_msg'])){
					update_option('apploxa_order_note_msg',1);
				}else {
					update_option('apploxa_order_note_msg',0);
				}
				add_action('admin_print_scripts', 'apploxa_custom_admin_js');
			}
    }
}
		if ( isset($_POST['save_otp_nots']) ) {
			if (isset($_POST['apploxa_admin_nonce'])) {
				$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
				if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
					if(isset($_POST['apploxa_enable_otp'])){
								update_option('apploxa_enable_otp',1);
					}else {
							update_option('apploxa_enable_otp', 0);
					}
						if(isset($_POST['apploxa_otp_txt'])){ update_option('apploxa_otp_txt',sanitize_text_field(wp_unslash($_POST['apploxa_otp_txt'])));
						}
					if(isset($_POST['apploxa_login_url'])){ 
					update_option('apploxa_login_url',sanitize_text_field(wp_unslash($_POST['apploxa_login_url'])));
						}
		  		add_action('admin_print_scripts', 'apploxa_custom_admin_js');
		}
	}
}
    if ( isset($_POST['save_admin_nots']) ) {
		if (isset($_POST['apploxa_admin_nonce'])) {
			$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
			if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
			foreach ( $order_statuses as $status_slug => $status_name ) {
			    $status_slug_with_underscores =  str_replace( 'wc-', '', $status_slug );
			    $slug = 'apploxa_adm_'.$status_slug_with_underscores ;
					update_option($slug,sanitize_text_field(wp_unslash($_POST[$slug])));
			}
				add_action('admin_print_scripts', 'apploxa_custom_admin_js');
    }
	}
}

		if ( isset($_POST['save_tmp_nots']) ) {
			if (isset($_POST['apploxa_admin_nonce'])) {
				$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
				if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
			foreach ( $order_statuses as $status_slug => $status_name ) {
					$status_slug_with_underscores =  str_replace( 'wc-', '', $status_slug );
					$slug = 'apploxa_tmp_'.$status_slug_with_underscores ;
				if(isset($_POST[$slug])){ 
					update_option($slug,sanitize_text_field(wp_unslash($_POST[$slug])));
				}
			}
				add_action('admin_print_scripts', 'apploxa_custom_admin_js');
		}
	}
}
}
add_action('admin_init', 'apploxa_save_options');


function apploxa_add_log($order_id=null,$status=null,$message=null,$user_id=null,$phone=null){
	global $wpdb;
	$table_name =  $wpdb->prefix .'apploxa_logs';
	$order = new WC_Order( $order_id );
    $whatsnumber = get_user_meta($order->get_user_id(), 'apploxa_whatsapp', 1);
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->insert($table_name, array(
		    'user_id' => $user_id?$user_id:$order->get_user_id(),
		    'action' =>  $user_id?'OTP':$order->get_status(),
		    'message' => $message,
				'status' => ($status===true)?1:0,
				'whats_app_number' =>  $whatsnumber?$whatsnumber:$phone
		));
}


function apploxa_send_order_notes( $email_args ) {
    if( get_option( 'apploxa_order_note_msg' )) {
        $order = wc_get_order( $email_args['order_id'] );
        $note  = $email_args['customer_note'];
        if ( 'NULL' === $order->get_billing_phone()) {
            return;
         }

				 $stats = apploxa_send_message($note, null,$email_args['order_id']);
				 apploxa_add_log(null,$status,$final_message);
      }
}
add_action( 'woocommerce_new_customer_note_notification', 'apploxa_send_order_notes', 10, 1 );

// woocommerce_order_status_changed //
add_action("woocommerce_order_status_changed","apploxa_handle_orders",10,3);
function apploxa_handle_orders($order_id, $old_status, $new_status) {
					$new_status_edit =  str_replace( 'wc-', '', $new_status );
	        $user_message =  get_option( 'apploxa_'.$new_status_edit );
					$admin_message = get_option( 'apploxa_adm_'.$new_status_edit );

				if($user_message){
						$final_msg = apploxa_handle_message($order_id,$user_message);
						apploxa_admin_message(get_option( 'apploxa_adm_order_processing' ),$order_id);
						$status = apploxa_send_message($final_msg,null,$order_id);
						apploxa_add_log($order_id,$status,$final_msg);
				}
					if($admin_message){
							$final_msg = apploxa_handle_message($order_id,$admin_message);
							apploxa_admin_message($final_msg ,$order_id);
					}
}
 


function apploxa_p_login_register_popup() { ?>
		<div id="apploxa_p_popup" class="apploxa_p_popup">
		    <div class="apploxa_p_popup_content">
		        <span class="apploxa_p_close">&times;</span>
		        <div class="apploxa_p_tab">
		            <button class="apploxa_p_tablinks active" onclick="openTab(event, 'apploxa_p_login')"><?php esc_html_e('Log in', 'apploxa');?></button>
		            <button class="apploxa_p_tablinks" onclick="openTab(event, 'apploxa_p_signup')"><?php esc_html_e('Sign up', 'apploxa');?></button>
		        </div>
		        <div id="apploxa_p_login" class="apploxa_p_tabcontent" style="display:block;">

		                <input type="text" class="apploxa_phone" placeholder="<?php esc_html_e('Phone Number', 'apploxa');?>" name="phone" required>
										<br>
									 
		                <button type="button" login="1" class="apploxa_logn_btn"><?php esc_html_e('Log in', 'apploxa');?></button>
		        </div>

		        <div id="apploxa_p_signup" class="apploxa_p_tabcontent">

		                <input type="text" class="apploxa_fullname" placeholder="<?php esc_html_e('Full Name', 'apploxa');?>" name="fullname" required>
		                <input type="text" class="apploxa_phone" placeholder="<?php esc_html_e('Phone Number', 'apploxa');?>" name="phone" required>
										<br>
		                <button type="button" class="apploxa_logn_btn"><?php esc_html_e('Sign up', 'apploxa');?></button>
		        </div>

		        <!-- OTP Verification Tab Content -->
		        <div id="apploxa_p_otp" class="apploxa_p_tabcontent">
		            <h2><?php esc_html_e('Verify OTP', 'apploxa');?></h2>
		            <p><?php esc_html_e('OTP has been sent', 'apploxa');?></p>
		            <p><?php esc_html_e('Enter verification code', 'apploxa');?></p>
		                <div class="apploxa_p_otp_input">
		                    <input type="text" maxlength="1" class="first_inp" required>
		                    <input type="text" maxlength="1" required>
		                    <input type="text" maxlength="1" required>
		                    <input type="text" maxlength="1" required>
		                </div>
										<input type="hidden" class="apploxa_hidn_phone" value="">
										<br>
		                <button type="button" class="apploxa_confirm_code"><?php esc_html_e('Confirm', 'apploxa');?></button>
		        </div>
		    </div>
			<?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
		</div> 
	 <?php  
 
}



function apploxa_check_user() {
	if (isset($_POST['apploxa_admin_nonce'])) {
		$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
		if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
		if(empty($_POST['phone'])){
			 wp_send_json_error('Sorry, add phone number first');
		}
 	
	    $phone=sanitize_text_field(wp_unslash($_POST['phone']));
	    $site_url = get_site_url(); 
    	$parsed_url = wp_parse_url($site_url, PHP_URL_HOST);
	    $username= trim($phone,"+").'@'.$parsed_url;
    	$user_id=username_exists($username); 
    	
    	
    	if(isset($_POST['login'])){
        	if(!$user_id){
        	    wp_send_json_error('Sorry, phone number does not exist');
        	}
    	}
    	
            $user=get_userdata($user_id);
			$rand = wp_rand(1111,9999); 
			if(get_option('apploxa_otp_txt')){
				$vars = array(
					'{username}'    => $user->display_name,
					'{otp}'     => $rand
				);
				$message = strtr(get_option('apploxa_otp_txt'), $vars);
			}else {
				$message = 'Apploxa OTP: '.$rand;
			} 
			$status=apploxa_send_message($message,$phone);
			apploxa_add_log(null,$status,$message,$user_id,$phone);
			if($status === true){
					set_transient( 'apploxa_otp_'.trim($phone,"+"), $rand , 300 );
					wp_send_json_success("OTP has been sent to your phone.");
			}else {
				wp_send_json_error($status??'No connection');
			} 
    wp_die();
}
}
}
function apploxa_check_otp() {
	if (isset($_POST['apploxa_admin_nonce'])) {
		$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
		if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
		if(!empty($_POST['phone'])){
			 wp_send_json_error('Sorry, phone number does not exist');
		}
		 if(!empty($_POST['otp'])){
			 wp_send_json_error('no otp provided');
		}
		$site_url = get_site_url();
		$parsed_url = wp_parse_url($site_url, PHP_URL_HOST);
		$phone=sanitize_text_field(wp_unslash($_POST['phone']));
		$username= trim($phone,"+").'@'.$parsed_url;
		$otp=sanitize_text_field(wp_unslash($_POST['otp']));
		$user_id=username_exists($username);
		if( !$user_id ){
            		$user_id = wp_create_user($username, wp_generate_password(), $username);
            		 if( !empty($_POST['fullname'])){
            		     wp_update_user(['ID' => $user_id,'first_name' => sanitize_text_field(wp_unslash($_POST['fullname']))]);
            		 }  
            		if (is_wp_error($user_id)) {
            				wp_send_json_error('User registration failed: ' . $user_id->get_error_message());
            		}
             
		}
		$db_otp = get_transient( 'apploxa_otp_'.trim($phone,"+") );
		if($otp == $db_otp){
			update_user_meta( $user_id, 'billing_phone', $phone );
		    update_user_meta($user_id, 'apploxa_whatsapp', $phone);
			wp_set_current_user($user_id);
	 		wp_set_auth_cookie($user_id);
			if(get_option('apploxa_login_url')){
				wp_send_json_success(get_option('apploxa_login_url'));
			}else {
				wp_send_json_success(home_url());
			}
		}else {
			wp_send_json_error('Invalid OTP.');
		}
    wp_die();
}
}
}


function apploxa_add_popup_nav_menu_meta_box() {
    add_meta_box(
        'apploxa-popup-nav-menu-item',
        'Apploxa Login Popup',
        'apploxa_popup_nav_menu_item',
        'nav-menus',
        'side',
        'default'
    );
}

function apploxa_popup_nav_menu_item() {
    global $nav_menu_selected_id;

    ?>
    <div id="apploxa-popup-menu-item" class="posttypediv">
        <div id="tabs-panel-apploxa-popup" class="tabs-panel tabs-panel-active">
            <ul id="apploxa-popup-checklist" class="categorychecklist form-no-clear">
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1" /> Login
                    </label>
										<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom" />
					 				<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Login" />
					 			<input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="#apploxa-popup" />
                </li>
            </ul>
        </div>
        <p class="button-controls">
            <span class="add-to-menu">
                <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attresc_html_e('Add to Menu'); ?>" name="add-apploxa-popup-menu-item" id="submit-apploxa-popup-menu-item" />
                <span class="spinner"></span>
            </span>
        </p>
    </div>
    <?php
}

 

function apploxa_custom_admin_js(){
	echo "<script type='text/javascript'>\n";
	echo 'toastr.options = {"positionClass": "toast-bottom-right","rtl":false};toastr.success("Updated Successfully.");'; 
	echo "\n</script>"; 
 } 

function apploxa_load_front_assets() { 
	wp_enqueue_style('toastr_css', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/css/toaster.css',null,APPLOXA_VERISON);
	wp_enqueue_script('toastr_js', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/js/toaster.js',null,APPLOXA_VERISON,true);
	wp_enqueue_style('apploxa_front_css', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/css/front.css',null,APPLOXA_VERISON);
	wp_enqueue_script('apploxa_front_js', plugin_dir_url(dirname(__FILE__)) . 'admin/assets/js/front.js',null,APPLOXA_VERISON,true);
}


function apploxa_lazy_ajax()
{
    ?>
    <script type="text/javascript"> 
    var apploxa_ajaxurl = "<?php echo esc_js(admin_url('admin-ajax.php')); ?>"; 
    </script>
    <?php
}


if(get_option('apploxa_enable_otp')){
	add_action( 'admin_init', 'apploxa_add_popup_nav_menu_meta_box');
	add_action( 'wp_footer', 'apploxa_load_front_assets'); 
	add_action( 'wp_footer', 'apploxa_p_login_register_popup');
	add_action('wp_head', 'apploxa_lazy_ajax', 0, 0);
	add_action( 'wp_ajax_apploxa_check_user', 'apploxa_check_user' );
	add_action( 'wp_ajax_nopriv_apploxa_check_user', 'apploxa_check_user' );
	add_action( 'wp_ajax_apploxa_check_otp', 'apploxa_check_otp' );
	add_action( 'wp_ajax_nopriv_apploxa_check_otp', 'apploxa_check_otp' );
	add_theme_support( 'menus' );
}


add_action('woocommerce_add_to_cart', 'apploxa_record_cart_timestamp');
function apploxa_record_cart_timestamp() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, '_cart_timestamp', time());
    }
}

function apploxa_check_abandoned_carts() {
      
    $cache_key = 'users_with_cart_timestamp';
	$users = wp_cache_get($cache_key);

	if ($users === false) {
		global $wpdb; 
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$users = $wpdb->get_col($wpdb->prepare(
			"SELECT DISTINCT user_id 
			 FROM {$wpdb->usermeta} 
			 WHERE meta_key = %s",
			'_cart_timestamp'
		));
		$users = array_map('get_user_by', array_fill(0, count($users), 'id'), $users);
		wp_cache_set($cache_key, $users, '', 3600); // Cache for 1 hour
	}


	
    $int = get_option('apploxa_custom_interval');
    $time=$int?(int)$int:1; 
    $abandon_time = 60 * 60 * $time;//24 hours
    $current_time = time();

    foreach ($users as $user) {
        $user_id = $user->ID;
        $cart_timestamp = get_user_meta($user_id, '_cart_timestamp', true);
        $cart_count = apploxa_user_has_cart_items($user_id);
        apploxa_add_log(null,$cart_count,'test',$user_id);
        if ($cart_count && ($current_time - $cart_timestamp) > $abandon_time) {
            apploxa_send_abandoned_cart_msg($user);
            delete_user_meta($user_id, '_cart_timestamp'); // Remove the timestamp after sending the email
        }
    }
}

function apploxa_send_abandoned_cart_msg($user) {
	 $user_message =  get_option('apploxa_abandoned_cart');
	 $apploxa_whatsapp = get_user_meta( $user->ID, 'apploxa_whatsapp', 1 );
	 $phone = $apploxa_whatsapp?$apploxa_whatsapp:get_user_meta( $user->ID, 'billing_phone', 1 );
	 if(!$user_message){return;}
	 if(!$phone){return;}
	 $finalmsg  = apploxa_handle_message(null,$user_message,$user->ID);
 	 $status = apploxa_send_message($finalmsg,$phone,null);
	 apploxa_add_log(null,$status,$finalmsg,$user->ID,$phone);
}
// Schedule the event

function apploxa_user_has_cart_items($user_id) {
$session_handler = new WC_Session_Handler(); 
$session = $session_handler->get_session($user_id); 
$cart_items = maybe_unserialize($session['cart']);
    return !empty($cart_items);
}
  

add_action('init', 'apploxa_schedule_abandoned_cart_check');
function apploxa_schedule_abandoned_cart_check() {
     $user_message = trim(get_option('apploxa_abandoned_cart'));
     if(!$user_message){return;}
    if (!wp_next_scheduled('apploxa_check_abandoned_carts_event')) {
        wp_schedule_event(time(), 'hourly', 'apploxa_check_abandoned_carts_event');
    }
}
add_action('apploxa_check_abandoned_carts_event', 'apploxa_check_abandoned_carts');

register_deactivation_hook(__FILE__, 'apploxa_clear_abandoned_cart_check');

function apploxa_clear_abandoned_cart_check() {
    $timestamp = wp_next_scheduled('apploxa_clear_abandoned_cart_check');
    wp_unschedule_event($timestamp, 'apploxa_clear_abandoned_cart_check');
}


// Add custom field to user profile
function apploxa_add_whatsapp_field($user) {
    ?>
    <h3>WhatsApp Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="apploxa_whatsapp">WhatsApp Number</label></th>
            <td>
                <input type="text" name="apploxa_whatsapp" id="apploxa_whatsapp" value="<?php echo esc_attr(get_user_meta($user->ID, 'apploxa_whatsapp', true)); ?>" class="regular-text" /><br />
                <span class="description">Please enter your WhatsApp number.</span>
            </td>
        </tr>
    </table>
    <?php
}

// Hook the function to the appropriate action
add_action('show_user_profile', 'apploxa_add_whatsapp_field');
add_action('edit_user_profile', 'apploxa_add_whatsapp_field');

// Save the custom field value
function apploxa_save_whatsapp_field($user_id) {
	if (isset($_POST['apploxa_admin_nonce'])) {
		$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
		if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
    if (current_user_can('edit_user', $user_id)) {
		if(isset($_POST['apploxa_whatsapp'])){
	 update_user_meta($user_id, 'apploxa_whatsapp', sanitize_text_field(wp_unslash($_POST['apploxa_whatsapp'])));
		} 
    }
}
}
}
// Hook the save function to the appropriate action
add_action('personal_options_update', 'apploxa_save_whatsapp_field');
add_action('edit_user_profile_update', 'apploxa_save_whatsapp_field');
