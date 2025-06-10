<?php
/**
 * The template part for displaying the dashboard Withdraw requested for freelancer
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard/earning_template
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user;
$user_identity = $current_user->ID;
$icon = 'wr-icon-briefcase';
$total_amount   = 0;
if( function_exists('workreap_account_withdraw_details') ){
  $total_amount = workreap_account_withdraw_details($user_identity);
}

$invoice_url  = Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $user_identity, true, 'listing');
?>
<div class="wr-earningcostvtwo">
    <div class="wr-earningcost__item">
        <i class="<?php echo esc_html($icon); ?>"></i>
        <h4><?php esc_html_e('Withdraw requested', 'workreap') ?></h4>
        <span><?php workreap_price_format($total_amount);?></span>
        <a href="<?php echo esc_url($invoice_url);?>"><?php esc_html_e('Show all invoices', 'workreap'); ?></a>
    </div>
</div>