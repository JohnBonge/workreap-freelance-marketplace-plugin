<?php
/**
 * Freelancer packages
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $workreap_settings, $current_user;
$user_identity  = intval($current_user->ID);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$title			= !empty($workreap_settings['pkg_page_title']) ? $workreap_settings['pkg_page_title'] : '';
$sub_title		= !empty($workreap_settings['pkg_page_sub_title']) ? $workreap_settings['pkg_page_sub_title'] : '';
$details		= !empty($workreap_settings['pkg_page_details']) ? $workreap_settings['pkg_page_details'] : '';

?>
<div class="row">
	<div class="col-lg-12 col-xl-12">
		<div class="wr-sectioninfov2 wr-priceplantitle">
			<div class="wr-sectiontitle">
				<?php if( !empty($title) ){?>
					<h3><?php echo esc_html($title); ?></h3>
				<?php } ?>
				<?php if( !empty($sub_title) ){?>
					<h2><?php echo esc_html($sub_title) ?></h2>
				<?php } ?>
				<?php if( !empty($details) ){?>
					<div class="wr-description">
						<?php echo do_shortcode($details); ?>
					</div>
				<?php } ?>
			</div>

		</div>
	</div>
</div>
<?php
if($user_type == 'employers'){
	workreap_get_template_part('dashboard/employer/user-package-detail');
} else {
	workreap_get_template_part('dashboard/user-package-detail');
}

if($user_type == 'employers'){
	$emp_trail_pkg	= !empty($workreap_settings['emp_trail_pkg']) ? $workreap_settings['emp_trail_pkg'] : array();
	$args = array(
	   'limit'     => -1, // All packages
	   'status'    => 'publish',
	   'type'      => 'employer_packages',
	   'orderby'   => 'date',
	   'order'     => 'ASC',
   );
   	if(!empty($emp_trail_pkg)){
		$args['exclude']	= array($emp_trail_pkg);
	}
} else {
	global $woocommerce;
	$free_trail_pkg	= !empty($workreap_settings['free_trail_pkg']) ? $workreap_settings['free_trail_pkg'] : array();
	$args = array(
	   'limit'     => -1, // All packages
	   'status'    => 'publish',
	   'type'      => 'packages',
	   'orderby'   => 'date',
	   'order'     => 'ASC',
   );
	if(!empty($free_trail_pkg)){
			$args['exclude']	= array($free_trail_pkg);
	}
}
$workreap_packages = wc_get_products( $args );

if(isset($workreap_packages) && is_array($workreap_packages) && count($workreap_packages)>0){?>
	<div class="wr-pricing">
		<div class="row align-items-center">
			<?php foreach($workreap_packages as $package){ ?>
				<div class="col-md-6 col-lg-4">
					<?php do_action('workreap_package_details', $package ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
<?php }