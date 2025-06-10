<?php
/**
 * Account settings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user, $workreap_settings;
$reference 		 = !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode 			 = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';

if((!empty($mode) && !in_array($mode,array('profile','verification','billing','account','portfolios','update-portfolio')) || empty($mode))){
	$reference  = 'dashboard';
	$mode   = 'profile';
}
if(!empty($reference) && $reference!='dashboard'){
	$reference  = 'dashboard';
	$mode   = 'profile';
}
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? intval($args['id']) : '';
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );

$identity_verification	= !empty($workreap_settings['identity_verification']) ? $workreap_settings['identity_verification'] : false;
?>
<div class="wr-settings-page-wrap">
	<div class="row">
		<div class="col-lg-4 col-xl-3">
			<aside>
				<div class="wr-asideholder">
					<div class="wr-asidebox wr-settingtabholder">
						<ul class="wr-settingtab">
							<li class="<?php echo esc_attr( $reference == 'dashboard' && $mode == 'profile' ? 'active' : '' );?>"><a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'profile'));?>"><i class="wr-icon-user"></i><?php esc_html_e('Profile settings','workreap');?></a></li>
                            <?php if($user_type === 'freelancers'){ ?>
                                <li class="<?php echo esc_attr( $reference == 'dashboard' && ($mode == 'portfolios' || $mode == 'update-portfolio') ? 'active' : '' );?>"><a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'portfolios'));?>"><i class="wr-icon-edit"></i><?php esc_html_e('Manage Portfolio','workreap');?></a></li>
                            <?php } ?>
							<?php if( !empty($identity_verification) ){?>
                            <li class="<?php echo esc_attr( $reference == 'dashboard' && $mode == 'verification' ? 'active' : '' );?>"><a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'verification'));?>"><i class="wr-icon-check-square"></i><?php esc_html_e('Identity verification','workreap');?></a></li>
							<?php } ?>
							<li class="<?php echo esc_attr( $reference == 'dashboard' && $mode == 'billing' ? 'active' : '' );?>"><a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'billing'));?>"><i class="wr-icon-credit-card"></i><?php esc_html_e('Billing information','workreap');?></a></li>
							<li class="<?php echo esc_attr( $reference == 'dashboard' && $mode == 'account' ? 'active' : '' );?>"><a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'account'));?>"><i class="wr-icon-settings"></i><?php esc_html_e('Account settings','workreap');?></a></li>
						</ul>
					</div>
				</div>
			</aside>
		</div>
		<div class="col-lg-8 col-xl-9">
			<?php if ( !empty($reference) && !empty($mode) && $reference == 'dashboard' && $mode == 'billing') {
				workreap_get_template_part('dashboard/dashboard', 'billing-settings');
			} else if ( !empty($reference) && !empty($mode) && $reference === 'dashboard' && $mode === 'profile') {
				
				if( !empty($user_type) && $user_type == 'freelancers' ){
					workreap_get_template_part('dashboard/dashboard', 'profile-settings');
					workreap_get_template_part('dashboard/dashboard', 'education');
					workreap_get_template_part('dashboard/dashboard', 'experience');
					
				} else {
					workreap_get_template_part('dashboard/dashboard', 'employer-setting');
				}

			} else if ( !empty($reference) && !empty($mode) && $reference === 'dashboard' && $mode === 'account') { 
				workreap_get_template_part('dashboard/dashboard', 'account-settings');
			} else if ( !empty($reference) && !empty($mode) && $reference === 'dashboard' && $mode === 'portfolios') { 
				workreap_get_template_part('dashboard/dashboard', 'list-portfolio');
			} else if ( !empty($reference) && !empty($mode) && $reference === 'dashboard' && $mode === 'update-portfolio') { 
				workreap_get_template_part('dashboard/dashboard', 'update-portfolio');
			} else if ( !empty($reference) && !empty($mode) && $reference === 'dashboard' && $mode === 'verification') { 
				workreap_get_template_part('dashboard/dashboard', 'identity-verification');
			} ?>
		</div>
	</div>
</div>