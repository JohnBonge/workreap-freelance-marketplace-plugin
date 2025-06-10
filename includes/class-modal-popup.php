<?php
namespace thickboxmodel;
// die if accessed directly
if (!defined('ABSPATH')) {
  die('no kiddies please!');
}

/**
 *
 * Class 'Workreap_Modal_Popup' defines the bootstrap modal
 *
 * @package     Workreap
 * @subpackage  Workreap/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

if (!class_exists('Workreap_Modal_Popup')) {

    class Workreap_Modal_Popup
    {

        public function __construct()
        {
            add_action('wp_footer', array($this, 'workreap_prepare_modal_popup'));
            add_action('wp_footer', array($this, 'workreap_faq_modal_popup'));
            add_action('wp_footer', array($this, 'workreap_reject_task'));
        }

        /**
         * Task add-ons popup
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
        */
        public function workreap_prepare_modal_popup()
        {
            ob_start();
            ?>
            <div class="modal hidden fade workreap-profilepopup wr-addonspopup" tabindex="-1" role="dialog" id="workreap-modal-popup">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="workreap-modalcontent modal-content">
                        <div id="workreap-model-body"></div>
                    </div>
                </div>
            </div>
            
            <div class="modal hidden fade workreap-profilepopup wr-addonspopup" tabindex="-1" role="dialog" id="workreap-taskaddon-popup">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="workreap-modalcontent modal-content">
                        <div class="wr-popuptitle">
                            <h4></h4>
                            <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                        </div>
                        <div id="workreap-model-body" class="modal-body"></div>
                    </div>
                </div>
            </div>

            <div class="modal fade wr-creditwallet" id="tbcreditwallet" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="wr-popuptitle">
                        <h4><?php esc_html_e('Add credit to your wallet', 'workreap'); ?></h4>
                        <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                        </div>
                        <div class="modal-body">
                        <form class="wr-themeform">
                            <fieldset>
                            <div class="form-group">
                                <input type="text" id="wr_wallet_amount" class="form-control" placeholder="<?php esc_attr_e('Enter amount', 'workreap'); ?>" name="amount" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <span class="wr-btn"
                                id="wr_submit_fund"><?php esc_html_e('Add funds now', 'workreap'); ?><i
                                    class="wr-icon-arrow-right"></i></span>
                            </div>
                            </fieldset>
                        </form>
                        <div class="wr-checkoutbox">
                            <em>*</em>
                            <span><?php esc_html_e('You will be redirected to the checkout page to add your billing details.', 'workreap'); ?></span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade wr-modal" id="workreap-popup" tabindex="-1" role="dialog" aria-hidden="true"></div>
            <?php
            echo ob_get_clean();
        }

        /**
         * Task FAQ's popup
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
        */
        public static function workreap_faq_modal_popup()
        {
            global $workreap_settings;
            $view_type          = !empty($workreap_settings['registration_view_type']) ? $workreap_settings['registration_view_type'] : 'pages';
            ob_start();
            if ( !is_user_logged_in() && !empty($view_type) && $view_type === 'popup' ) { 
                $logo               = !empty($workreap_settings['popup_logo']['url']) ? $workreap_settings['popup_logo']['url'] : '';
                $bg_image           = '';
                $tagline            = esc_html__('We love to see you joining us','workreap');
                $logintagline       = esc_html__('Welcome! Nice to see you again','workreap');
                $reset_pass_tagline = esc_html__('Lost password? No need to worry we will send you the password reset link','workreap');
                $after_reset_pass_tagline = esc_html__('Reset your password','workreap');
                if ( isset($_GET['action']) && $_GET['action'] == 'reset_pwd' ) {
                    $script = "
                    jQuery(document).on('ready', function(){
                        jQuery('#wr-pass-model').modal('show');
                        jQuery('#wr-pass-model').removeClass('hidden');
                    });
                    ";
                    wp_add_inline_script( 'workreap', $script, 'after' );
                }
                ?>
                <div class="modal fade wr-auth-popup" id="wr-login-model" tabindex="-1" aria-labelledby="wr-login-modelLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content wr-login-popup-content">
                            <div class="modal-body">
                                <a href="javascript:void(0)" class="wr-loginclose-tag" data-bs-dismiss="modal"><i class="wr-icon-x"></i></a>
                                <?php echo do_shortcode('[workreap_signin background="'.$bg_image.'" logo="'.$logo.'" tagline="'.$logintagline.'" ]');?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade wr-auth-popup" id="wr-signup-model" tabindex="-1" aria-labelledby="wr-signup-modelLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content wr-signup-popup-content">
                            <div class="modal-body">
                                <a href="javascript:void(0)" class="wr-loginclose-tag" data-bs-dismiss="modal"><i class="wr-icon-x"></i></a>
                                <?php echo do_shortcode('[workreap_registration background="'.$bg_image.'" logo="'.$logo.'" tagline="'.$tagline.'" ]');?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade wr-auth-popup" id="wr-pass-model" tabindex="-1" aria-labelledby="wr-pass-modelLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content wr-pass-popup-content">
                            <div class="modal-body">
                                <a href="javascript:void(0)" class="wr-loginclose-tag" data-bs-dismiss="modal"><i class="wr-icon-x"></i></a>
                                <?php echo do_shortcode('[workreap_forgot background="'.$bg_image.'" logo="'.$logo.'" tagline="'.$reset_pass_tagline.'" reset_pass_tagline="'.$after_reset_pass_tagline.'" ]');?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
            <!-- Add New Faq Popup Start-->
            <div class="modal fade wr-addonpopup" id="addnewfaq" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog wr-modaldialog" role="document">
                    <div class="modal-content">

                        <div class="wr-popuptitle">
                            <h4><?php esc_html_e('Add new FAQ', 'workreap'); ?></h4>
                            <span class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></span>
                        </div>            
                        <div class="modal-body">
                            <form class="wr-themeform wr-formlogin">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="form-group-title"><?php esc_html_e('Add faq title', 'workreap'); ?>:</label>
                                        <input type="text" id="service-question" class="form-control" placeholder="<?php esc_attr_e('Enter question here', 'workreap'); ?>" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-group-title"><?php esc_html_e('Add faq description', 'workreap'); ?>:</label>
                                        <textarea class="form-control" id="service-answer" placeholder="<?php esc_attr_e('Enter brief answer', 'workreap'); ?>"></textarea>
                                    </div>
                                    <div class="form-group wr-form-btn">
                                        <div class="wr-savebtn">
                                        <span><?php esc_html_e('Click “Save & Update” to update your faq', 'workreap'); ?></span>
                                        <span class="wr-btn"
                                            id="wr-faqs-addlist"><?php esc_html_e('Save & Update', 'workreap'); ?></span>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }

        /**
         * Reject task popup
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
        */
        public function workreap_reject_task()
        {
            ?>
            <div class="modal fade wr-taskreject" id="wr-reject-task" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="wr-popuptitle">
                    <h4><?php esc_html_e('Reject task approval request', 'workreap'); ?></h4>
                    <span class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></span>
                    </div>
                    <div class="modal-body">
                    <form class="wr-themeform">
                        <fieldset>
                        <div class="form-group">
                            <textarea class="form-control" rows="6" cols="80" id="wr_reject_task_reason" name="wr_reject_task_reason" placeholder="<?php esc_attr_e('Add rejection reason', 'workreap'); ?>"></textarea>
                        </div>
                        <div class="form-group">
                            <span class="wr-btn wr_rejected_task" data-wr_task_id="" id="wr_submit_reject_task"><?php esc_html_e('Send', 'workreap'); ?></span>
                        </div>
                        </fieldset>
                    </form>
                    </div>
                </div>
                </div>
            </div>
            <?php
        }

    }

}

new Workreap_Modal_Popup();
