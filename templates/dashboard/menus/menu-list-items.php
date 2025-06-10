<?php
/**
 * Menus list items
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
$reference      = (isset($args['ref']) && $args['ref'] <> '') ? esc_html($args['ref']) : '';
$mode           = (isset($args['mode']) && $args['mode'] <> '') ? esc_html($args['mode']) : '';
$title          = (isset($args['title']) && $args['title'] <> '') ? esc_html($args['title']) : '';
$type           = (isset($args['type']) && $args['type'] <> '') ? esc_html($args['type']) : '';
$id             = (isset($args['id']) && $args['id'] <> '') ? intval($args['id']) : '';
$icon_class     = (isset($args['icon']) && $args['icon'] <> '') ? esc_html($args['icon']) : '';
$class          = (isset($args['class']) && $args['class'] <> '') ? esc_html($args['class']) : '';
$user_identity  = $current_user->ID;

$user_type		= apply_filters('workreap_get_user_type', $user_identity);
$linked_profile	= workreap_get_linked_profile_id($user_identity,'',$user_type);
$activeMenu     = '';
$sub_meu        = false;

if(isset($args['submenu']) && is_array($args['submenu']) && count($args['submenu'])>0){
    $list_submenus              = !empty($args['submenu']) ? $args['submenu'] : array();
    $sortorder                  = array_column($list_submenus, 'sortorder');
    array_multisort($sortorder, SORT_ASC, $list_submenus);
    $sub_meu            = true;
    $class              .= ' menu-item-has-children';

    foreach($list_submenus as $key => $submenu_item){
        $submenu_ref = $submenu_item['ref'];
        if( $submenu_ref == 'find-project' && is_page_template( 'templates/search-projects.php')){
            $activeMenu = 'active';
        }
	    if( $submenu_ref == 'find-task' && is_page_template( 'templates/search-task.php')){
		    $activeMenu = 'active';
	    }
	    if( $submenu_ref == 'find-freelancers' && is_page_template( 'templates/search-freelancer.php')){
		    $activeMenu = 'active';
	    }
        if($submenu_ref == 'projects' && $getReference == 'projects'){
            $activeMenu = 'active';
        }
        if( $submenu_ref == 'create-task' && is_page_template( 'templates/add-task.php')){
            $activeMenu = 'active';
        }
        if($submenu_ref == 'task' && $getReference == 'task'){
            $activeMenu = 'active';
        }
        if($submenu_ref == 'orders' && $getReference == 'orders'){
            $activeMenu = 'active';
        }
        if($submenu_ref == 'tasks-orders' && $getReference == 'tasks-orders'){
            $activeMenu = 'active';
        }

	    if($submenu_ref == 'offers' && is_page_template( 'templates/add-offer.php')){
		    $activeMenu = 'active';
        }

	    if($submenu_ref == 'offers' && $getReference == 'offers'){
		    $activeMenu = 'active';
	    }

        if($submenu_ref == 'meeting' && $getReference == 'meeting'){
		    $activeMenu = 'active';
	    }

    }
}

if(empty($reference) && empty($mode)){
	$url	= '#';
} else {
    $url	= Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, $mode);

    if( !empty($reference) && $reference === 'packages'){
        $url	= workreap_get_page_uri('package_page');
    } else if( !empty($reference) && $reference === 'find-task'){
        $url	= workreap_get_page_uri('service_search_page');
    } else if( !empty($reference) && $reference === 'find-freelancers'){
        $url	= workreap_get_page_uri('freelancers_search_page');
    } else if( !empty($reference) && $reference === 'create_project'){
        $url	= workreap_get_page_uri('add_project_page');
    } else if( !empty($reference) && $reference === 'find-project'){
        $url 			= !empty($workreap_settings['tpl_project_search_page']) ? get_permalink($workreap_settings['tpl_project_search_page']) : '';
    }else if( !empty($reference) && !empty($getReference) && $reference === 'invoices' && $getReference === 'invoices' ){
        $activeMenu = 'active';
    }else if( !empty($reference) && !empty($getReference) && $reference === 'disputes' && $getReference === 'disputes'){
        $activeMenu = 'active';
    }else if( !empty($reference) && !empty($getReference) && $reference === 'projects' && $getReference === 'projects'){
        $activeMenu = 'active';
    }else if( !empty($reference) && !empty($getReference) && $reference === 'earnings' && $getReference === 'earnings'){
        $activeMenu = 'active';
    }else if( !empty($reference) && !empty($getReference) && $reference === 'disputes' && $getReference === 'disputes'){
        $activeMenu = 'active';
    }else if( !empty($reference) && !empty($getReference) && $reference === 'tasks-orders' && $getReference === 'tasks-orders'){
        $activeMenu = 'active';
    } else if( !empty($reference) && !empty($getReference) && $reference === 'meeting' && $getReference === 'meeting'){
        $activeMenu = 'active';
    }
}

$messages_count = apply_filters('wpguppy_count_all_unread_messages', $user_identity );
?>
<li class="<?php echo esc_attr($class); ?>">
    <?php if( !empty($reference) && $reference === 'notifications'){
        $args['linked_profile']    = $linked_profile;
        workreap_get_template_part('dashboard/dashboard', 'list-notification', $args);
    } else {?>
        <a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr($activeMenu); ?>">
            <?php if(isset($icon_class) && !empty($icon_class)){?>
                <i class="<?php echo esc_attr($icon_class);?>"></i>
            <?php }
                if(!empty($title)){?>
                        <span><?php echo esc_html($title); ?></span>
                <?php }
                if( !empty($reference) && $reference === 'inbox' && (in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins')))) ){ ?>
                    <?php if(!empty($messages_count) ){?><em class="wr-remaining-notification"><?php echo esc_html($messages_count);?></em><?php } ?>
                    <span><?php esc_html_e('Messages','workreap');?></span>
                <?php } 
            ?>
        </a>
        <?php if( !empty($sub_meu)){ ?>
            <ul class="sub-menu">
                <?php foreach($list_submenus as $key => $submenu_item){
                    $submenu_item['id']         = $key;
                    $submenu_item['reference']  = $reference;
                    workreap_get_template_part('dashboard/menus/submenu', 'list-item', $submenu_item);
                } ?>
            </ul>
        <?php }?>
    <?php } ?>
</li>
