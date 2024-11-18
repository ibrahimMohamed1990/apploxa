<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function apploxa_send_quick_msg(){
	$token = get_option( 'apploxa_token' );
	if (isset($_POST['apploxa_admin_nonce'])) {
		$nonce = sanitize_text_field(wp_unslash($_POST['apploxa_admin_nonce']));
		if ( wp_verify_nonce($nonce, 'apploxa_admin_action')) {
	if(!empty($_POST['phone']) || !empty($_POST['message']) ){
		wp_send_json_error( 'اضف الحقول لتتمكن من الارسال' );
	}
	$args = array(
			'body' => wp_json_encode(array(
					'phone' => sanitize_text_field(wp_unslash( $_POST['phone'] )),
					'message' =>sanitize_text_field(wp_unslash($_POST['message'])),
			)),
			'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $token ,
			),
	);
	$response = wp_remote_post('https://api.apploxa.com/hook/whatsapp/v2/message/send-plain-text', $args);
 	$code  = wp_remote_retrieve_response_code($response);
	$body = wp_json_encode( wp_remote_retrieve_body($response) , 1 );
	if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			wp_send_json_error(  $error_message ?? 'No connection' );
	}
			if($code == 200){
				wp_send_json_success(  'تم الارسال بنجاح' );
			}else {
				wp_send_json_error( $body['message'] ?? 'No connection');
			}


exit;

}
}
}
add_action( 'wp_ajax_apploxa_send_quick_msg', 'apploxa_send_quick_msg' ); 