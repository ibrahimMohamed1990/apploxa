<?php  if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
   <h1>Templates  </h1>
   <?php apploxa_admin_nav('apploxa-templates'); ?>
   <div class="tab-content">
      <div class="_cust-container">
         <p>Add custom order templates based on order status.</p>
         <code>Available variables: {first_name}, {last_name}, {total}, {order_id}, {stat}</code>
         <form  action="" method="post">
          <div class="_cust-grid">
<?php

$mes_arr = [
    'pending'=>'Hi {first_name} {last_name},
    Thank you for your order! We have received your order #{order_id} for a total of {total}. Your order is currently Pending. Once we receive your payment, we will begin processing it.',
    'processing' => 'Hi {first_name} {last_name},
    Good news! Your order #{order_id} for {total} is now Processing. We are working on getting everything ready for shipment.',

    'on-hold' => 'Hi {first_name} {last_name},
    Your order #{order_id} for {total} is currently On-Hold. Please contact us if you have any questions or to resolve any issues so we can continue processing your order.',

    'completed' => 'Hi {first_name} {last_name},
    We are pleased to inform you that your order #{order_id} totaling {total} has been Completed. Thank you for shopping with us!',

    'cancelled' => 'Hi {first_name} {last_name},
    We regret to inform you that your order #{order_id} for {total} has been Cancelled. If this was unexpected, please contact us for more details.',

    'refunded' => 'Hi {first_name} {last_name},
    A refund for your order #{order_id} totaling {total} has been successfully Refunded. Please allow a few days for the funds to appear in your account.',

    'failed' => 'Hi {first_name} {last_name},
    Unfortunately, your order #{order_id} for {total} has Failed. Please try placing the order again or contact us for assistance.',

    'checkout-draft' => 'Hi {first_name} {last_name},
    Your order #{order_id} for {total} is currently in Draft status. Feel free to finalize it whenever you are ready!',
];


           	  $order_statuses = wc_get_order_statuses();
           		foreach ( $order_statuses as $status_slug => $status_name )  :
           			     $status_wp_trim =  str_replace( 'wc-', '', $status_slug );
           			     $slug = 'apploxa_tmp_'.$status_wp_trim ; ?>
                      <div class="_cust-card">
                         <div class="_cust-card-header">
                            <span class="_cust-card-title">Order <?php echo esc_html($status_name)?> </span>
                            <span class="_cust-card-deactivate">leave blank to deactivate</span>
                         </div>
                         <?php if(!empty(get_option($slug))): ?>
                         <textarea class="_cust-textarea" value="<?php echo esc_html (get_option($slug)) ?>" name="<?php echo esc_html($slug )?>" ><?php echo esc_html (get_option($slug)) ?></textarea>
                       <?php else: update_option($slug,$mes_arr[$status_wp_trim]);?>
                         <textarea class="_cust-textarea" value="<?php echo esc_html ($mes_arr[$status_wp_trim]) ?>" name="<?php echo esc_html ($slug) ?>" ><?php echo esc_html ($mes_arr[$status_wp_trim]) ?></textarea>
                       <?php endif; ?>
                      </div>
          <?php endforeach; ?>

         </div>
         <input type="hidden" name="save_tmp_nots" value="1">
         <button type="submit" class="_cust-save-button"><?php esc_html_e('Save Changes', 'apploxa'); ?></button>
         <?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
       </form>
      </div>
   </div>
</div>
