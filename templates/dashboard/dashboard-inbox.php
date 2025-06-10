<?php
/**
 * Dashboard Notifications
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user, $wp_roles, $userdata, $post;

$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? intval($args['id']) : '';
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );
?>
<div class="wr-message-wrapper">
	<div class="wr-dhb-mainheading">
		<h2><?php esc_html_e('Start a conversation','workreap');?></h2>
	</div> 
	<div class="wr-message">
		<?php if((in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins'))))){
				echo do_shortcode('[getGuppyConversation]');
		} ?>
	</div>
</div>