<?php  if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
    <h1>Apploxa - Quick Message</h1>
    <?php apploxa_admin_nav('apploxa-quick-message'); ?>

    <div class="tab-content">
      <div class="container">
    <h3 class="_qck-header">Send messages to any WhatsApp number from this section.
    </h3>
    <p>You must add country code with phone number example: +966xxxxxxxxx</p>
    <div class="_qck-form">
		<form> 
        <label class="_qck-label" for="whatsapp-number">Send WhatsApp message to:</label>
        <input type="text" id="whatsapp-number" class="_qck-input" placeholder="Enter WhatsApp number">

        <label class="_qck-label" for="message">Write your message...</label>
        <textarea id="message" class="_qck-textarea"></textarea> 
        <button class="_qck-send-btn">Send Message</button>
		<?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
	   </form>
    </div>
</div>
    </div>
</div>
