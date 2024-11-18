<?php  if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
   <h1>Admin notifications</h1>
   <?php apploxa_admin_nav('apploxa-admin-notifications'); ?>
   <div class="tab-content">
      <div class="_cust-container">
        <p>Automatically send notification messages based on the primary language of the user's WordPress account.</p>
         <code>Available variables: {first_name}, {last_name}, {total}, {order_id}, {stat}</code>
         <form  action="" method="post">
          <div class="_cust-grid">
           <?php
           	  $order_statuses = wc_get_order_statuses();
           		foreach ( $order_statuses as $status_slug => $status_name )  :
           			     $status_slug_with_underscores = str_replace( 'wc-', '', $status_slug );
           			     $slug = 'apploxa_adm_'.$status_slug_with_underscores ;?>
                      <div class="_cust-card">
                         <div class="_cust-card-header">
                            <span class="_cust-card-title">Order <?php echo esc_attr($status_name) ?> </span>
                            <span class="_cust-card-deactivate">leave blank to deactivate</span>
                         </div>
                         <select class="_cust-dropdown">
                           <option disabled selected>Select Template</option>
                           <?php foreach ($order_statuses as $tmp => $tmp_name) :
                             $slug_with_underscores =  str_replace( 'wc-', '', $tmp );
                             $temp = 'apploxa_tmp_'.$slug_with_underscores;
                             $tmp_val=get_option($temp);?>
                              <option value="<?php echo esc_attr($tmp_val) ?>"><?php echo esc_attr($tmp_name) ?></option>
                           <?php endforeach; ?>
                        </select>
                         <textarea class="_cust-textarea" value="<?php echo esc_attr(get_option($slug)) ?>" name="<?php echo esc_attr($slug )?>" ><?php echo esc_attr(get_option($slug)) ?></textarea>
                      </div>
          <?php endforeach; ?>
         </div>
         <input type="hidden" name="save_admin_nots" value="1">
         <button type="submit" class="_cust-save-button"> <?php esc_html_e('Save Changes', 'apploxa'); ?></button>
                  <?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
       </form>
      </div>
   </div>
</div>
