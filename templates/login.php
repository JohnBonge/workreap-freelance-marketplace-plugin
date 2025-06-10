<?php
/**
 * login form
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global  $workreap_settings;
$google_connect     = !empty($workreap_settings['enable_social_connect']) ? $workreap_settings['enable_social_connect'] : '';
$enable_fb_connect     = !empty($workreap_settings['enable_fb_social_connect']) ? $workreap_settings['enable_fb_social_connect'] : '';
$enable_linkedin_connect     = !empty($workreap_settings['enable_linkedin_social_connect']) ? $workreap_settings['enable_linkedin_social_connect'] : '';
$view_type          = !empty($workreap_settings['registration_view_type']) ? $workreap_settings['registration_view_type'] : 'pages';
$hide_registration  = !empty($workreap_settings['hide_registration']) ? $workreap_settings['hide_registration'] : 'no';
$redirect           = !empty($_GET['redirect']) ? $_GET['redirect'] : '';
$reg_class          = '';
$lost_pass          = '';

if( !empty($view_type) && $view_type === 'popup' ){
    $reg_class          = 'wr-signup-poup-btn';
    $lost_pass          = 'wr-pass-poup-btn';
    $registration_page  = 'javascript:void(0);';
    $forgot_pass_page   = 'javascript:void(0);';
}
?>
<div class="wr-loginconatiner">
    <?php if (!empty($background_banner)) { ?>
        <figure><img src="<?php echo esc_attr($background_banner); ?>" alt="<?php esc_attr_e('Sign In', 'workreap'); ?>"></figure>
    <?php } ?>
    <div class="wr-popupcontainer">
        <div class="wr-login_title">
            <?php if (!empty($logo)) { ?>
                <a href="<?php echo site_url(); ?>">
                    <img src="<?php echo esc_attr($logo); ?>" alt="<?php esc_attr_e('Sign In', 'workreap'); ?>">
                </a>
            <?php } ?>
            <?php if (!empty($tagline)) { ?>
                <h5><?php echo do_shortcode(nl2br($tagline)); ?></h5>
            <?php } ?>
        </div>
        <div class="wr-login-content wr-popup-content">
            <form class="wr-themeform wr-formlogin">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <?php do_action('workreap_render_fields_before');  ?>
                        <div class="form-group">
                            <div class="wr-placeholderholder">
                                <label for="email"><?php echo esc_html__('Email') ?></label>
                                <input type="hidden" name="redirect" value="<?php echo esc_attr($redirect);?>">
                                <input id="email" name="signin[email]" type="text" class="form-control" required="required" placeholder="<?php esc_attr_e('Please enter your email address', 'workreap'); ?>">
                            </div>
                        </div>
                       <div class="form-group">
    <div class="wr-placeholderholder" style="position: relative;">
        <label for="password"><?php echo esc_html__('Password') ?></label>

        <input id="password" type="password" name="signin[user_password]" class="form-control" required="required" placeholder="<?php esc_attr_e('Please enter your password', 'workreap'); ?>" style="padding-right: 40px;">

        <!-- Eye icon inside input -->
        <span onclick="togglePassword()" style="position: absolute; top: 70%; left: 360px; transform: translateY(-50%); cursor: pointer;">
            👁️
        </span>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const icon = event.target;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.textContent = "🙈"; // Hide icon
        } else {
            passwordInput.type = "password";
            icon.textContent = "👁️"; // Show icon
        }
    }
</script>

                        <?php do_action('workreap_render_fields_after'); ?>
                        <div class="form-group wr-form-action">
                            <div class="wr-checkbox">
                                <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                <label for="rememberme"><?php echo esc_html__('Remember me','workreap') ?></label>
                            </div>
                            <a href="<?php echo do_shortcode($forgot_pass_page); ?>" class="wr-password-clr_light <?php echo esc_attr($lost_pass);?>"><?php esc_html_e('Forget password?', 'workreap'); ?></a>
                        </div>
                        <div class="form-group wr-form-btn ">
                            <div class="wr-checkterm">
                                <button type="submit" class="wr-btn wr-signin-now"><?php esc_html_e('Sign In', 'workreap'); ?></button>
                            </div>
                            <?php if (!empty($google_connect) || !empty($enable_fb_connect) || !empty($enable_linkedin_connect)) { ?>
                                <div class="wr-optioanl-or">
                                    <span><?php esc_html_e('OR', 'workreap') ?></span>
                                </div>
                                <?php if(!empty($google_connect)){ ?>
                                <div class="wr-sginup-btn">
                                    <div id="google_signin"></div>
                                </div>
                                <?php } ?>
                                <?php 
                                if(!empty($enable_linkedin_connect)){
                                    do_action('workreap_social_login_linkedin_button'); 
                                }
                                if(!empty($enable_fb_connect)){
                                    do_action('workreap_social_login_facebook_button'); 
                                }
                            } ?>
                            <div class="wr-form-action-footer">
                                <?php if( !empty($hide_registration) && $hide_registration != 'yes' ){?>
                                    <?php echo esc_html__('Don’t have an account?','workreap'); ?>
                                    <a href="<?php echo do_shortcode($registration_page); ?>" class="wr-reg <?php echo esc_attr($reg_class);?>"><?php esc_html_e('Sign up', 'workreap'); ?></a>
                                <?php }?>
                            </div>
                        </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php
$script = "
jQuery(document).on('ready', function(){
    jQuery('.form-control').on('input', function () {
        jQuery(this).siblings('.wr-placeholder').hide();
        if (jQuery(this).val().length == 0)
        jQuery(this).siblings('.wr-placeholder').show();
    });
    jQuery('.form-control').blur();
});
";
wp_add_inline_script('workreap', $script, 'after');