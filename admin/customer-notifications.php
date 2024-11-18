<?php  if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
   <h1>Customer notifications  </h1>
   <?php apploxa_admin_nav('apploxa-customer-notifications'); ?>
   <div class="tab-content">
      <div class="_cust-container">
            <p>Automatically send notification messages based on order status changes.</p>
         <code>Available variables: {first_name}, {last_name}, {total}, {order_id}, {stat}</code>
         <form  action="" method="post">
          <div class="_cust-grid">
           <?php
           	  $order_statuses = wc_get_order_statuses();
           		foreach ( $order_statuses as $status_slug => $status_name )  :
           			     $status_slug_with_underscores =  str_replace( 'wc-', '', $status_slug );
           			     $slug = 'apploxa_'.$status_slug_with_underscores ; ?>
                      <div class="_cust-card">
                         <div class="_cust-card-header">
                            <span class="_cust-card-title">Order <?php echo esc_attr($status_name);?> </span>
                            <span class="_cust-card-deactivate">leave blank to deactivate</span>
                         </div>
                         <select class="_cust-dropdown">
                           <option disabled selected>Select Template</option>
                           <?php foreach ($order_statuses as $tmp => $tmp_name) :
                             $slug_with_underscores =  str_replace( 'wc-', '', $tmp );
                             $temp = 'apploxa_tmp_'.$slug_with_underscores ;
                             $tmp_val=get_option($temp);?>
                              <option value="<?php echo esc_attr($tmp_val); ?>"><?php echo esc_attr($tmp_name); ?></option>
                           <?php endforeach; ?>
                        </select>
                         <textarea class="_cust-textarea" value="<?php echo esc_attr(get_option($slug)); ?>" name="<?php echo esc_attr($slug); ?>" ><?php echo esc_attr(get_option($slug)); ?></textarea>
                      </div>
          <?php endforeach; ?>

          <div class="_cust-card">
             <div class="_cust-card-header">
                <span class="_cust-card-title">Abandoned cart </span>
                <span class="_cust-card-deactivate">leave blank to deactivate</span>
                
             </div>
              
                 <span class="_cust-card-deactivate">Time interval max(48 hours)</span>
                <input type="number" value="<?php echo esc_attr(get_option('apploxa_custom_interval'));?>" max="48" min="1" name="apploxa_custom_interval" /> 
             <textarea class="_cust-textarea" value="<?php echo esc_attr(get_option('apploxa_abandoned_cart')); ?>" name="apploxa_abandoned_cart" ><?php echo esc_attr(get_option('apploxa_abandoned_cart')); ?></textarea>
          </div>

          <div class="_cust-card">
             <div class="_cust-card-header">
                <span class="_cust-card-title">Customer note </span>
                <span class="_cust-card-deactivate">leave blank to deactivate</span>
             </div>
             <label for="">Enable Customer note Notification
             <input type="checkbox" name="apploxa_order_note_msg" value="<?php echo esc_attr(get_option('apploxa_order_note_msg')); ?>" <?php if(get_option('apploxa_order_note_msg')){echo "checked";} ?>>
             </label>

          </div>

         </div>
         <input type="hidden" name="save_cust_nots" value="1">
         <button type="submit" class="_cust-save-button"><?php esc_html_e('Save Changes', 'apploxa'); ?></button>
         <?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
       </form>
      </div>
   </div>
</div>
