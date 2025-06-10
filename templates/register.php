<?php
    /**
     * User registration
     *
     * @package     Workreap
     * @subpackage  Workreap/templates
     * @author      Amentotech <info@amentotech.com>
     * @version     1.0
     * @since       1.0
     */
    global  $workreap_settings;
    $view_type          = !empty($workreap_settings['registration_view_type']) ? $workreap_settings['registration_view_type'] : 'pages';
    $hide_registration  = !empty($workreap_settings['hide_registration']) ? $workreap_settings['hide_registration'] : 'no';
    $redirect           = !empty($_GET['redirect']) ? $_GET['redirect'] : '';
    $login_class        = '';

    if( !empty($hide_registration) && $hide_registration == 'yes' ){

        echo wp_sprintf(__('Registration is disabled by the admin, <a href="%s">click here</a> to go back to the site ', 'workreap'), home_url('/'));
        return;
    }

    if( !empty($view_type) && $view_type === 'popup' ){
        $login_class        = 'wr-login-poup';
        $login_page         = 'javascript:';
    }
    ?>
    <div class="wr-loginconatiner">
        <?php if (!empty($background_banner)) { ?>
            <figure> <img src="<?php echo esc_attr($background_banner); ?>" alt="<?php esc_attr_e('Registration', 'workreap'); ?>"></figure>
        <?php } ?>
        <div class="wr-popupcontainer">
            <div class="wr-login_title">
                <?php if (!empty($logo)) { ?>
                    <a href="<?php echo site_url(); ?>">
                        <img src="<?php echo esc_attr($logo); ?>" alt="<?php esc_attr_e('Registration', 'workreap'); ?>">
                    </a>
                <?php } ?>
                <?php if (!empty($tagline)) { ?>
                    <h5><?php echo do_shortcode(nl2br($tagline)); ?></h5>
                <?php } ?>
            </div>
            <div class="wr-login-content wr-popup-content">
                <form class="wr-themeform user-registration-form" id="userregistration-from">
                    <fieldset>
                        <div class="wr-themeform__wrap">
                            <?php do_action('workreap_user_registration_fields_before'); ?>
                            <div class="form-group half-group">
                                <div class="wr-placeholderholder">
                                    <label for="firstname"><?php echo esc_html__('First Name','workreap') ?></label>
                                    <input type="hidden" name="redirect" value="<?php echo esc_attr($redirect);?>">
                                    <input id="firstname" type="text" name="user_registration[first_name]" class="form-control" required="required" placeholder="<?php esc_attr_e('First name*', 'workreap'); ?>">
                                </div>
                            </div>

                            <div class="form-group half-group">
                                <div class="wr-placeholderholder">
                                    <label for="lastname"><?php echo esc_html__('Last Name','workreap') ?></label>
                                    <input id="lastname" type="text" name="user_registration[last_name]" class="form-control" required="required" placeholder="<?php esc_attr_e('Last name*', 'workreap'); ?>">
                                </div>
                            </div>
                            <?php if (!empty($user_name_option)) { ?>
                                <div class="form-group">
                                    <div class="wr-placeholderholder">
                                        <label for="username"><?php echo esc_html__('User Name','workreap') ?></label>
                                        <input id="username" type="text" name="user_registration[user_name]" class="form-control" required="required" placeholder="<?php esc_attr_e('User name*', 'workreap'); ?>">
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group half-group">
                                <div class="wr-placeholderholder">
                                    <label for="email"><?php echo esc_html__('Email','workreap') ?></label>
                                    <input type="email" name="user_registration[user_email]" class="form-control" required="required" placeholder="<?php esc_attr_e('Your email address*', 'workreap'); ?>">
                                </div>
                            </div>
                           <div class="form-group half-group">
    <div class="wr-placeholderholder" style="position: relative;">
        <label for="password"><?php echo esc_html__('Password','workreap') ?></label>
        
        <input type="password" id="user_password" name="user_registration[user_password]" class="wr-password form-control" required="required" placeholder="<?php esc_attr_e('Enter password*', 'workreap'); ?>" style="padding-right: 40px;" maxlength="13">

        <!-- Eye icon inside input -->
        <span onclick="togglePassword()" style="position: absolute; top: 70%; left: 200px; transform: translateY(-50%); cursor: pointer;">
            👁️
        </span>
    </div>
</div>


<!-- Toggle Password Script -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("user_password");
        const icon = event.target;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.textContent = "🙈"; // Optional: Change icon when visible
        } else {
            passwordInput.type = "password";
            icon.textContent = "👁️"; // Optional: Revert icon
        }
    }
</script>

                            <?php if (!empty($user_types)) { ?>
                                <div class="form-group">
                                    <label><?php echo esc_html__('Choose Type','workreap') ?></label>
                                    <div class="wr-reg-option">
                                    <?php foreach ($user_types as $key => $value) {
                                        $checked    = '';
                                        if (!empty($defult_register_type) && $defult_register_type === $key) {
                                            $checked    = 'checked';
                                        }
                                    ?>
                                        <div class="wr-radio">
                                            <input <?php echo esc_attr($checked); ?> id="wr_<?php echo esc_attr($key); ?>" type="radio" value="<?php echo esc_attr($key); ?>" name="user_registration[user_type]">
                                            <label for="wr_<?php echo esc_attr($key); ?>">
                                                <span><?php echo esc_html($value); ?> </span>
                                            </label>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group wr-form-btn am-registration-terms">
                                <div class="wr-checkterm">
                                    <div class="wr-checkbox">
                                        <input type="checkbox" value="" name="user_registration[user_agree_terms]">
                                        <input id="user_agree_terms" value="yes" type="checkbox" name="user_registration[user_agree_terms]">
                                        <label for="user_agree_terms">
                                            <span><?php echo sprintf(esc_html__('I have read and agree to all %s and %s', 'workreap'), $term_link, $privacy_link); ?> </span>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="wr-btn wr-signup-now"><?php esc_html_e('Join Now', 'workreap'); ?></button>
                            </div>
                            <?php
                            do_action('workreap_user_registration_fields_after');
                            if (!empty($google_connect)) {?>
                                <div class="wr-optioanl-or">
                                    <span><?php esc_html_e('OR', 'workreap') ?></span>
                                </div>
                                <div class="form-group wr-sginup-btn">
                                    <div id="google_signup"></div>
                                </div>
                            <?php } ?>
                            <div class="wr-form-action-footer">
	                            <?php echo esc_html__('Already have an account','workreap'); ?>
                                <a href="<?php echo do_shortcode($login_page); ?>" class="wr-login-btn <?php echo esc_attr($login_class);?>"><?php esc_html_e('Sign In', 'workreap'); ?></a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <?php