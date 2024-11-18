<?php  if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
    <h1><?php esc_html_e('Apploxa Connection', 'apploxa'); ?></h1>

    <?php
    apploxa_admin_nav('apploxa');
    $token = get_option('apploxa_token');
    ?>

    <div class="tab-content">
        <div class="container">
          
            <div class="settings">
                <div class="section">
                    <div class="instructions">
                        <h2><?php esc_html_e('How to connect your WhatsApp:', 'apploxa'); ?></h2>
                        <ol>
                        <li>
                        <?php 
                            // Translators: %s is the URL link to wa.apploxa.com
                            printf(
                                /* translators: %s is the URL link to wa.apploxa.com */
                                esc_html__('Create a free account at %s', 'apploxa'),
                                '<a href="' . esc_url('https://wa.apploxa.com/auth/login') . '" target="_blank">wa.apploxa.com</a>'
                            ); 
                            ?>

                        </li>
                            <li><?php esc_html_e('Link your WhatsApp using a QR code.', 'apploxa'); ?></li>
                            <li><?php esc_html_e('Copy and paste access token.', 'apploxa'); ?></li>
                            <li><?php esc_html_e('Save, then perform a connection test', 'apploxa'); ?></li>
                        </ol>
                        <a href="https://wa.apploxa.com/auth/login" target="_blank" class="ma_btn btn btn-primary"><?php esc_html_e('Create free account', 'apploxa'); ?></a>
                        <p><?php esc_html_e('Note: You can link the same number for notifications and OTP.', 'apploxa'); ?></p>
                    </div>
                </div>
                <div class="section">
                    <h3><?php esc_html_e('Access Token and WhatsApp number', 'apploxa'); ?></h3>
                    <p><?php esc_html_e('Connect your WhatsApp number to send a One-Time Password (OTP) for login, register and checkout verification.', 'apploxa'); ?></p>
                    <form action="" method="post">
                        <label for="access-token-otp"><?php esc_html_e('Access Token', 'apploxa'); ?></label>
                        <input type="<?php echo $token ? 'password' : 'text'; ?>" id="access-token-otp" name="apploxa_token" placeholder="<?php esc_html_e('Your Access Token', 'apploxa'); ?>" value="<?php echo  esc_html($token) ?>">
                        <label for="admin_whatsapp"><?php esc_html_e('Admin WhatsApp number', 'apploxa'); ?></label>
                        <input type="text" id="admin_whatsapp" name="apploxa_admin_whatsapp" placeholder="<?php esc_html_e('WhatsApp number', 'apploxa'); ?>" value="<?php echo esc_html(get_option('apploxa_admin_whatsapp')) ?>">
                        <button type="submit"><?php esc_html_e('Save', 'apploxa'); ?></button>
                        <?php wp_nonce_field('apploxa_admin_action', 'apploxa_admin_nonce');?>
                    </form>
                </div>
            </div>
            <footer>
                <p><?php esc_html_e('Made with ♥ by the Apploxa Team', 'apploxa'); ?></p>
               <p>
               <?php 
                // Translators: %1$s opens the anchor tag, and %2$s closes it.
                printf(
                    wp_kses(
                        /* translators: %1$s and %2$s are opening and closing <a> tags */
                        __('Please rate %1$sApploxa%2$s on WordPress.org to help us spread the word.', 'apploxa'),
                        array(
                            'a' => array(
                                'href' => array(),
                                'target' => array(),
                            ),
                        )
                    ),
                    '<a href="' . esc_url( 'https://wordpress.org' ) . '" target="_blank">',
                    '</a>'
                );
                ?>
                </p>
            </footer>
        </div>
    </div>
</div>
