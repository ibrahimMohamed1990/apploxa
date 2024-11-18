<?php  if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">

    <h1>Apploxa - Login & Signup OTP</h1>
    <?php apploxa_admin_nav('apploxa-login-signup-otp'); ?>

 <div class="container mt-3">


<form action="" method="post">
  <h1 class="_otp-title">Login/SignUp with OTP</h1>
    <p class="_otp-description">Ensures secure login with a quick one-time password sent via WhatsApp.</p>
     <p>Enable Apploxa login with OTP <span class="_otp-shortcode">
       <input type="checkbox" class="form-control" value="1" name="apploxa_enable_otp" <?php if(get_option('apploxa_enable_otp')){echo "checked";} ?>>
     </span></p>

     <?php
     $emnu = site_url() . '/wp-admin/nav-menus.php' ;
     if(get_option('apploxa_enable_otp')){echo '<p>Now you can add Apploxa Login Popup link form <a href="'.esc_html($emnu).'" target="_blank"> here </a> <br>Or add a custom link with href <code>#apploxa-popup</code></p>';} ?>

    <div class="_otp-grid">
        <div class="_otp-input-group">
            <label class="_otp-label">OTP Message</label>
            <textarea class="_otp-textarea" name="apploxa_otp_txt"><?php echo esc_html(get_option('apploxa_otp_txt')) ?></textarea>
            <div class="_otp-shortcodes">
                Shortcodes: <span class="_otp-shortcode">{username}</span> Member name — <span class="_otp-shortcode">{otp}</span> Generated OTP code
            </div>
        </div>
        <div class="_otp-input-group">
            <label class="_otp-label">URL redirection</label>
            <input type="text" class="_otp-input" name="apploxa_login_url" value="<?php echo esc_url(get_option('apploxa_login_url')) ?>">
            <p class="_otp-note">* Redirection only work for WooCommerce native forms.</p>
            <p class="_otp-deactivate">leave blank to deactivate</p>
        </div>
    </div>
  </div>
  <input type="hidden" name="save_otp_nots" value="1">
      <button type="submit" class="_otp-save-btn"><?php esc_html_e('Save Changes', 'apploxa'); ?></button>
      <?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
 </form>

</div>
