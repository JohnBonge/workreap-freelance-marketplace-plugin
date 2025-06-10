<?php
/**
 * The template part for displaying the dashboard Income in Account for freelancer
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard/earning_template
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user,$workreap_settings;
$user_id                    = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;
$user_identity              = $current_user->ID;
$icon                       = 'wr-icon-shopping-cart';
$account_blance             = workreap_account_details($user_identity,array('wc-completed'),'completed');
$withdrawn_amount           = workreap_account_withdraw_details($user_identity,array('pending','publish'));
$available_withdraw_amount = $account_blance - $withdrawn_amount;
$payout_list                = workreap_get_payouts_lists();
$package_details            = get_user_meta($user_identity, 'workreap_payout_method', true);
$package_details            = !empty($package_details) ? $package_details : array();

$tpl_terms_conditions   = !empty( $workreap_settings['tpl_terms_conditions'] ) ? $workreap_settings['tpl_terms_conditions'] : '';
$tpl_privacy            = !empty( $workreap_settings['tpl_privacy'] ) ? $workreap_settings['tpl_privacy'] : '';
$term_link              = !empty($tpl_terms_conditions) ? '<a target="_blank" href="'.get_the_permalink($tpl_terms_conditions).'">'.get_the_title($tpl_terms_conditions).'</a>' : '';
$privacy_link           = !empty($tpl_privacy) ? '<a target="_blank" href="'.get_the_permalink($tpl_privacy).'">'.get_the_title($tpl_privacy).'</a>' : '';

?>
<div class="wr-earningcostvtwo">
    <div class="wr-earningcost__item">
        <i class="<?php echo esc_attr($icon); ?>"></i>
        <h4><?php esc_html_e('Available in account', 'workreap') ?></h4>
        <span><?php workreap_price_format($available_withdraw_amount);?></span>
        <?php if (!empty($package_details)){?>
            <a href="javascript:void(0);" data-bs-target="#withdraw-saved-payment" data-bs-toggle="modal"><?php esc_html_e('Withdraw now', 'workreap'); ?></a>
        <?php } else { ?>
            <a href="javascript:void(0);" data-bs-target="#withdraw-non-saved-payment" data-bs-toggle="modal"><?php esc_html_e('Withdraw now', 'workreap'); ?></a>
        <?php } ?>
    </div>
</div>
<?php if (!empty($payout_list) && is_array($payout_list)) {?>
    <div class="modal fade wr-withdrawmoney" tabindex="-1" role="dialog" id="withdraw-saved-payment">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="wr-modalcontent modal-content">
                <div class="wr-popuptitle">
                    <h4><?php esc_html_e('Withdraw money', 'workreap'); ?></h4>
                    <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                </div>
                <div class="modal-body">
                    <form class="wr-themeform wr-formlogin wr-withdrawform">
                        <fieldset>
                            <div class="form-group">
                                <label class="form-group-title"><?php esc_html_e('Enter amount','workreap'); ?>:</label>
                                <div class="wr-limit">
                                <input type="number" placeholder="<?php esc_attr_e('Enter amount here', 'workreap'); ?>*" name="withdraw[amount]" class="form-control">
                                <em><?php esc_html_e('Max Limit','workreap'); ?>: <?php workreap_price_format($available_withdraw_amount);?></em>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group-title"><?php esc_html_e('Payout method','workreap'); ?>:</label>
                                <ul class="wr-payoutmethod wr-payoutmethodpopup">
                                <?php foreach ($payout_list as $pay_key => $pay_val) {?>
                                    <?php if (array_key_exists($pay_key, $package_details)) { ?>
                                        <li class="wr-radiobox">
                                            <input type="radio" id="<?php echo esc_attr($pay_val['id']); ?>" name="withdraw[gateway]" value="<?php echo esc_attr( $pay_val['id'] ); ?>" checked>
                                            <div class="wr-radioholder wr-packages__days">
                                                <div class="wr-radio">
                                                    <label for="<?php echo esc_attr($pay_val['id']); ?>" class="wr-radiolist payoutlists">
                                                        <span class="wr-payoutmode">
                                                            <?php if(!empty($pay_val['img_url'])){?><img src="<?php echo esc_url($pay_val['img_url']); ?>" alt="<?php echo esc_attr($pay_val['title']); ?>"><?php }?>
                                                            <span><?php echo esc_html($pay_val['title']); ?></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                    <?php }}?>
                                </ul>
                            </div>
                            <div class="form-group wr-popupbtnarea">
                                <div class="wr-checkterm">
                                    <div class="wr-checkbox">
                                        <input id="check3" type="checkbox" name="withdraw_consent">
                                        <label for="check3">
                                            <span>
                                                <?php echo sprintf(esc_html__('By clicking you agree with our %s and %s', 'workreap'), $term_link, $privacy_link); ?>
                                            </span>
                                        </label>
                                    </div>                            
                                </div>
                                <button type="button" data-id="<?php echo intval($user_id);?>" class="wr-btn wr-withdraw-money"><?php esc_html_e('Withdraw now','workreap'); ?></button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="withdraw-non-saved-payment">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="wr-modalcontent modal-content">
                <div class="wr-popuptitle">
                    <h4><?php esc_html_e('Withdraw money','workreap'); ?></h4>
                    <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                </div>
                <div class="modal-body">
                    <h4><?php esc_html_e('Select any payment method before withdrawal request','workreap'); ?></h4>
                </div>
            </div>
        </div>
    </div>
<?php }
