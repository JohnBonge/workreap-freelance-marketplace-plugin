<?php
/**
 * The template part for displaying the dashboard Pending Income for freelancer
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
$icon           = 'wr-icon-server';
$account_blance = workreap_account_details($user_identity,array('wc-completed'),'hired');
$page_url       = Workreap_Profile_Menu::workreap_profile_menu_link('earnings', $user_identity, true);
?>
<div class="wr-earningcostvtwo">
    <div class="wr-earningcost__item">
        <i class="<?php echo esc_attr($icon); ?>"></i>
        <h4><?php esc_html_e('Pending income', 'workreap'); ?></h4>
        <span><?php workreap_price_format($account_blance);?></span>
        <a href="<?php echo esc_url($page_url);?>"><?php esc_html_e('Refresh', 'workreap'); ?></a>
    </div>
</div>