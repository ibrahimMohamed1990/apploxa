<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div id="apploxa_p_popup" class="apploxa_p_popup">
    <div class="apploxa_p_popup_content">
        <span class="apploxa_p_close">&times;</span>
        <div class="apploxa_p_tab">
            <button class="apploxa_p_tablinks active" onclick="openTab(event, 'apploxa_p_login')">Log in</button>
            <button class="apploxa_p_tablinks" onclick="openTab(event, 'apploxa_p_signup')">Sign up</button>
        </div>
        <div id="apploxa_p_login" class="apploxa_p_tabcontent" style="display:block;">
            <h2>Login</h2>
            <form>
                <input type="text" placeholder="Full Name" name="fullname" required>
                <input type="text" placeholder="Phone Number" name="phone" required>
                <button type="submit">Log In</button>
                <?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
            </form>
        </div>
        <div id="apploxa_p_signup" class="apploxa_p_tabcontent">
            <h2>Sign Up</h2>
            <form>
                <input type="text" placeholder="Full Name" name="fullname" required>
                <input type="text" placeholder="Phone Number" name="phone" required>
                <button type="submit">Sign Up</button>
                <?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
            </form>
        </div>
        
    </div>
</div>
