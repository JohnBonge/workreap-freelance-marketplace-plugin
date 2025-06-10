<?php
/**
 * Template Name: Add Task
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $post, $thumbnail,$workreap_settings,$current_user;
$package_option	        = !empty($workreap_settings['package_option']) ? $workreap_settings['package_option'] : '';
$user_type			= apply_filters('workreap_get_user_type', $current_user->ID );
$post_id        	= (isset($_GET['post'])) ? intval($_GET['post']) : '';
$step           	= (isset($_GET['step'])) ? intval($_GET['step']) : '1';
$workreap_args   	= array( 'post_id'=>$post_id, 'step' => $step );
$task_allowed    	= workreap_task_create_allowed($current_user->ID);
$package_detail  	= workreap_get_package($current_user->ID);
$task_plans_allowed	= 'yes';
$package_type    	=  !empty($package_detail['type']) ? $package_detail['type'] : '';

if( !empty($package_type) && $package_type == 'paid'){
	$task_plans_allowed    =  !empty($package_detail['package']['task_plans_allowed']) ? $package_detail['package']['task_plans_allowed'] : 'no';
	$number_tasks_allowed  =  !empty($package_detail['package']['number_tasks_allowed']) ? $package_detail['package']['number_tasks_allowed'] : 0;
}

if((!empty($post_id) && $user_type == 'freelancers') ||  (!empty($user_type) && $user_type == 'freelancers' && $task_allowed)){
	$task_allowed	= true;
} else {
	$redirect_url  = !empty($workreap_settings['tpl_dashboard']) ? get_the_permalink( $workreap_settings['tpl_dashboard'] ) : '';
	if(!empty($package_option) && ( $package_option == 'employer_free' || $package_option == 'paid' )){
		$redirect_url	= workreap_get_page_uri('package_page');
	}
	
	wp_redirect( $redirect_url );
    exit;
}

get_header();
?>
<div class="wr-main-section">
	<div class="container">
		<div class="row wr-blogs-bottom">
			<div class="col-xl-12">
				<?php if((!empty($post_id) && $user_type == 'freelancers') ||  (!empty($user_type) && $user_type == 'freelancers' && $task_allowed)){
					 workreap_get_template( 'dashboard/post-service/add-service.php', $workreap_args);
				 } else {					
					 do_action('workreap_user_not_authorized');
				 } 
				?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
