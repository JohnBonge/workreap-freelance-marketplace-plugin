<?php
/**
 * Dashboard insights
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/admin_dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = !empty($_GET['ref'] ) ? $_GET['ref'] : '';
$mode 			 = !empty($_GET['mode']) ? $_GET['mode'] : '';
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? $args['id'] : '';
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );
wp_enqueue_script( 'chart' );
wp_enqueue_script( 'utils-chart');
workreap_get_template_part('admin-dashboard/dashboard', 'tasks-insights');
