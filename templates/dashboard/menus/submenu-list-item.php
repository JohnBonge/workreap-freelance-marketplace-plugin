<?php
/**
 * Menus sub menu items
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard/menus
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $wp_roles, $userdata, $post,$workreap_settings;
$getReference   = (isset($_GET['ref']) && $_GET['ref'] <> '') ? esc_html($_GET['ref']) : '';
$reference 		 = (isset($args['ref']) && $args['ref'] <> '') ? $args['ref'] : '';
$mode 			 = (isset($args['mode']) && $args['mode'] <> '') ? $args['mode'] : '';
$title 			 = (isset($args['title']) && $args['title'] <> '') ? $args['title'] : '';
$id 			 = (isset($args['id']) && $args['id'] <> '') ? $args['id'] : '';
$icon_class 	 = (isset($args['icon']) && $args['icon'] <> '') ? $args['icon'] : '';
$class 			 = (isset($args['class']) && $args['class'] <> '') ? $args['class'] : '';
$user_identity 	 = $current_user->ID;
if($getReference == $reference && $reference === 'meeting'){
	if($mode === $_GET['mode']){
		$class .= ' active';
	}
} else if($getReference == $reference){
	$class .= ' active';
}

if( $reference == 'find-project' && is_page_template( 'templates/search-projects.php')){
	$class .= ' active';
}

if( $reference == 'create-task' && is_page_template( 'templates/add-task.php')){
	$class .= ' active';
}

if( $reference == 'find-task' && is_page_template( 'templates/search-task.php')){
	$class .= ' active';
}

if( $reference == 'find-freelancers' && is_page_template( 'templates/search-freelancer.php')){
	$class .= ' active';
}

if( $reference == 'offers' && is_page_template( 'templates/add-offer.php')){
	$class .= ' active';
}


if(empty($reference) && empty($mode)){
	$url	= '#';
} else if( !empty($reference) && $reference === 'create_project'){
    $url	= workreap_get_page_uri('add_project_page');
}else if( !empty($reference) && $reference === 'find-task'){
	$url	= workreap_get_page_uri('service_search_page');
} else if( !empty($reference) && $reference === 'find-freelancers'){
	$url	= workreap_get_page_uri('freelancers_search_page');
} else{
	$url	= Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, $mode);
	if( !empty($reference) && $reference === 'create-task'){
		$url = !empty($workreap_settings['tpl_add_service_page']) ? get_permalink($workreap_settings['tpl_add_service_page']) : '';
    } else if( !empty($reference) && $reference === 'find-project'){
		$url 			= !empty($workreap_settings['tpl_project_search_page']) ? get_permalink($workreap_settings['tpl_project_search_page']) : '';
    }
}
?>
<li class="<?php echo esc_attr($class); ?>">
	<a href="<?php echo esc_attr( $url ); ?>">
        <?php if(isset($icon_class) && !empty($icon_class)){?>
				<i class="<?php echo esc_attr($icon_class);?>"></i>
		<?php
			}
			if( !empty($title) ){
        		echo esc_html($title);
			}
        ?>
	</a>
</li>