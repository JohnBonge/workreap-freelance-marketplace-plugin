<?php
/**
 * forgot password form
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global  $workreap_settings;
$google_logo        = workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/google.png');
$google_logo        = !empty($google_logo) ? $google_logo : '';
$view_type          = !empty($workreap_settings['registration_view_type']) ? $workreap_settings['registration_view_type'] : 'pages';
$hide_registration          = !empty($workreap_settings['hide_registration']) ? $workreap_settings['hide_registration'] : 'no';

$reg_class          = '';
$login_class        = '';

if( !empty($view_type) && $view_type === 'popup' ){
    $reg_class          = 'wr-signup-poup-btn';
    $login_class        = 'wr-login-poup';
    $registration_page  = 'javascript:;';
    $login_page         = 'javascript:;';
}
?>
<div class="wr-loginconatiner">
    <?php if(!empty($background_banner)){?>
        <figure> <img src="<?php echo esc_url($background_banner);?>" alt="<?php esc_attr_e('Site banner', 'workreap');?>"></figure>
    <?php }?>
    <div class="wr-popupcontainer">
        <div class="wr-login_title">
            <?php if(!empty($logo)){?>
                <a href="<?php echo esc_url( site_url('/')); ?>">
                    <img src="<?php echo esc_url($logo);?>" alt="<?php esc_attr_e('Site logo', 'workreap');?>">
                </a>
            <?php }?>
            <?php if(!empty($tagline)){?>
                <h5><?php echo do_shortcode(nl2br($tagline));?></h5>
            <?php }?>
        </div>
        <div class="wr-login-content wr-popup-content">
            <form class="wr-themeform wr-forgot-password-form">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <?php if ( !empty($reset_key) && !empty($reset_action) && !empty($user_email)) { ?>
                            <input type="hidden" name="key" value="<?php echo esc_attr($reset_key); ?>" />
                            <input type="hidden" name="reset_action" value="<?php echo esc_attr($reset_action); ?>" />
                            <input type="hidden" name="login" value="<?php echo esc_attr($user_email); ?>" />
                            <div class="form-group">
                                <div class="wr-placeholderholder">
                                    <input type="password" name="fotgot[password]" class="form-control" required="required" placeholder="<?php esc_attr_e('Type password','workreap');?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="wr-placeholderholder">
                                    <input type="password" name="fotgot[re_password]" class="form-control" required="required" placeholder="<?php esc_attr_e('Re-type password','workreap');?>">
                                </div>
                            </div>
                            <div class="form-group wr-popup-terms">
                                <button type="submit" name="submit" class="wr-btn-solid-lg btn-reset-pass"><?php esc_html_e('Reset Password','workreap');?><i class="wr-icon-arrow-right"></i></button>
                            </div>
                        <?php } else {?>
                            <?php do_action('workreap_forgot_password_fields_before');?>
                            <div class="form-group">
                                <div class="wr-placeholderholder">
                                    <label for="email"><?php echo esc_html__('Email') ?></label>
                                    <input id="email" type="text" name="fotgot[email]" class="form-control" required="required" placeholder="<?php esc_attr_e('Please enter your email address','workreap');?>">
                                </div>
                            </div>
                            <div class="form-group wr-popup-terms">
                                <button type="submit" name="submit" class="wr-btn-solid-lg btn-forget-pass"><?php esc_html_e('Send reset link','workreap');?><i class="wr-icon-arrow-right"></i></button>
                            </div>
                        <?php } ?>
                        <div class="wr-form-action-footer">
                            <?php if( !empty($hide_registration) && $hide_registration != 'yes' ){?>
	                            <?php echo esc_html__('Don’t have an account?','workreap'); ?>
                                <a href="<?php echo do_shortcode($registration_page);?>" class="wr-reg <?php echo esc_attr($reg_class);?>"><?php esc_html_e('Sign up', 'workreap');?></a>
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
wp_add_inline_script( 'workreap', $script, 'after' );