<?php
/**
 * Template Name: Add Project
 *
 * @package     Workreap
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0 http://localhost/www/workreap/create-project/?step=1&page=projects
*/
global $post, $current_user,$workreap_settings;
$package_option	        = !empty($workreap_settings['package_option']) ? $workreap_settings['package_option'] : '';
$user_type			= apply_filters('workreap_get_user_type', $current_user->ID );
$post_id        	= (isset($_GET['post_id'])) ? intval($_GET['post_id']) : '';
$step           	= (isset($_GET['step'])) ? intval($_GET['step']) : '1';
$page_temp          = (isset($_GET['page_temp'])) ? esc_attr($_GET['page_temp']) : '';
$post_url			= workreap_get_page_uri('add_project_page');
$allow_project		= false;
$product			= array();
if(($user_type == 'employers') ||  (!empty($user_type) && $user_type == 'employers' && $allow_project)){
	$allow_project	= true;
	if(!empty($post_id)){
		$post_type  = get_post_type( $post_id );
		if( !empty($post_type) && $post_type === 'product' ){
			$post_author    = get_post_field( 'post_author', $post_id );
			if( !empty($current_user->ID) && $current_user->ID == $post_author){
				$product = wc_get_product( $post_id );
				if( $product->is_type( 'projects' ) ) {
				} else {
					$allow_project	= false;
				}
			} else {
				$allow_project	= false;
			}
		} else {
			$allow_project	= false;
		}
	}
} 

if(!empty($package_option) && ( $package_option == 'freelancer_free' || $package_option == 'paid' )){
	$remaining_option       = get_user_meta( $current_user->ID, 'remaining_employer_package_details',true );    
	$package_details  		= get_user_meta($current_user->ID, 'employer_package_details', true);
	$remaining_option       = !empty($remaining_option) ? $remaining_option : array();
	$expriy_time            = !empty($remaining_option['package_expriy_date']) ? strtotime($remaining_option['package_expriy_date']) : 0;
	$number_projects_allowed    = !empty($remaining_option['number_projects_allowed']) ? ($remaining_option['number_projects_allowed']) : 0;
	
	$current_time           = strtotime("now");
	if( empty($expriy_time) || ( !empty($current_time) && !empty($expriy_time) && $current_time > $expriy_time ) ){
		$redirect_url	= workreap_get_page_uri('package_page');
		wp_redirect( $redirect_url );
		exit;
	}
}

$workreap_args   	= array( 'page_temp'=>$page_temp,'post_id'=>$post_id, 'step' => $step, 'post_url' => $post_url,'product'=>$product	 );

if( !$allow_project ){
	$redirect_url  = !empty($workreap_settings['tpl_dashboard']) ? get_the_permalink( $workreap_settings['tpl_dashboard'] ) : '';
	wp_redirect( $redirect_url );
	exit;
}

get_header();
?>
<section class="wr-main-section">
	<div class="container">
		<?php
        if( !empty($page_temp) && $page_temp === 'projects'){
            workreap_get_template(
                'dashboard/post-project/list-projects.php',
                $workreap_args
            );
        } else if($step == 1){
            workreap_get_template(
                'dashboard/post-project/create-project.php',
                $workreap_args
            );
        } elseif($step == 2){
            workreap_get_template(
                'dashboard/post-project/project-basic.php',
                $workreap_args
            );
        } elseif($step == 3){
            workreap_get_template(
                'dashboard/post-project/project-prefrences.php',
                $workreap_args
            );
        }elseif($step == 4){
            workreap_get_template(
                'dashboard/post-project/recomended-freelancers.php',
                $workreap_args
            );
        }
		?>		
	</div>
</section>
<?php

get_footer();
