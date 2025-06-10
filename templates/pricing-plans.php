<?php
/**
 * Template Name: Pricing plans
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $post, $current_user;
$user_identity  = intval($current_user->ID);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
get_header();
?>
<section class="wr-main-section">
	<div class="container">
		<div class="wr-pricingholder">
			<?php 
				if(!empty($user_type) && in_array($user_type, array('employers','freelancers'))){
					do_action('workreap_packages_listing');
				} else { 
					do_action( 'workreap_notification', esc_html__('Restricted access','workreap'), esc_html__('Oops! you are not allowed to access this page','workreap') );
				}
				?>
		</div>
	</div>
</section>
<?php
get_footer();
