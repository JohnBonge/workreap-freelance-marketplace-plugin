<?php
/**
 * Dashboard insights
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user, $wp_roles, $userdata, $post;

$reference 		 = !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode 			 = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? $args['id'] : '';
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );

//if ( !empty($user_type) && $user_type === 'freelancers') {
//	workreap_get_template_part('dashboard/dashboard', 'freelancer-insights');
//} else {
//	workreap_get_template_part('dashboard/dashboard', 'employer-insights');
//}
