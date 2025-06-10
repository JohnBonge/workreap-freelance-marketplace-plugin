<?php
/**
 * Freelancer orders listing
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user, $wp_roles, $userdata, $post;

$reference		= !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode			= !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity	= intval($current_user->ID);
$id				= !empty($args['id']) ? $args['id'] : '';
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
?>
<div class="container wr-dhb-orders-listing">
	<div class="row">
		<div class="col-lg-12">
			<!-- top filters section -->
			<div class="wr-dhb-mainheading">
				<h2><?php esc_html_e('All available tasks','workreap');?></h2>
				<div class="wr-sortby">
					<div class="wr-actionselect wr-actionselect2">
						<span><?php esc_html_e('Sort by:','workreap');?></span>
						<div class="wr-select">
						<select id="wr-selection1" class="form-control wr-selectv">
							<option selected hidden disabled> <?php esc_html_e('Deadline','workreap');?></option>
							<option> <?php esc_html_e('30 Days','workreap');?></option>
						</select>
						</div>
					</div>
				</div>
			</div>
			<!-- top filters section end -->
			<div class="wr-dhbtabs wr-tasktabs">
				<div class="nav nav-tabs wr-navtabs " id="myTab" role="tablist">
					<a class="nav-link <?php echo esc_attr( $mode == '' or $mode == 'all' ? 'active' : '' );?>" href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'all'));?>"><?php esc_html_e('All orders','workreap');?></a>
					<a class="nav-link <?php echo esc_attr( $mode == 'new' ? 'active' : '' );?>" href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'new'));?>"><?php esc_html_e('New orders','workreap');?></a>
					<a class="nav-link <?php echo esc_attr( $mode == 'ongoing' ? 'active' : '' );?>" href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'ongoing'));?>"><?php esc_html_e('Ongoing orders','workreap');?></a>
					<a class="nav-link <?php echo esc_attr( $mode == 'completed' ? 'active' : '' );?>" href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'completed'));?>"><?php esc_html_e('Completed orders','workreap');?></a>
					<a class="nav-link <?php echo esc_attr( $mode == 'cancelled' ? 'active' : '' );?>" href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'cancelled'));?>"><?php esc_html_e('Cancelled orders','workreap');?></a>
					<a class="nav-link <?php echo esc_attr( $mode == 'declined' ? 'active' : '' );?>" href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, 'declined'));?>"><?php esc_html_e('Declined orders','workreap');?></a>
				</div>
				<div class="tab-content tab-taskcontent" id="pills-tabContent">
					<?php
						if(!empty($mode) && $mode === 'new') {
							workreap_get_template_part('dashboard/dashboard', 'new-orders', array());
						}elseif(!empty($mode) && $mode === 'ongoing') {
							workreap_get_template_part('dashboard/dashboard', 'ongoing-orders', array());
						}elseif(!empty($mode) && $mode === 'completed') {
							workreap_get_template_part('dashboard/dashboard', 'completed-orders', array());
						}elseif(!empty($mode) && $mode === 'cancelled') {
							workreap_get_template_part('dashboard/dashboard', 'cancelled-orders', array());
						}elseif(!empty($mode) && $mode === 'declined') {
							workreap_get_template_part('dashboard/dashboard', 'declined-orders', array());
						}else{
							workreap_get_template_part('dashboard/dashboard', 'all-orders', array());
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
