<?php
/**
 * Menus listing
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard/menus
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/ 

global $workreap_settings;
$workreap_menu_list 	        = Workreap_Profile_Menu::workreap_get_dashboard_menu();
$workreap_profile_menu_list 	= Workreap_Profile_Menu::workreap_get_dashboard_profile_menu();
$sortorder                  = array_column($workreap_profile_menu_list, 'sortorder');
array_multisort($sortorder, SORT_ASC, $workreap_profile_menu_list);

$list_sortorder                  = array_column($workreap_menu_list, 'sortorder');
array_multisort($list_sortorder, SORT_ASC, $workreap_menu_list);

?>
<nav class="wr-navbar navbar-expand-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#tenavbar" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation','workreap');?>">
        <i class="wr-icon-menu"></i>
    </button>
    <div id="tenavbar" class="collapse navbar-collapse">
        <ul class="navbar-nav wr-navbarnav">
            <?php if( !empty( $workreap_menu_list ) ){
                foreach($workreap_menu_list as $key => $menu_item){
                    if( !empty( $menu_item['type'] ) && ( $menu_item['type'] == $workreap_user_role || $menu_item['type'] == 'none' ) ){
                        $menu_item['id'] = $key;                       
                        workreap_get_template_part('dashboard/menus/menu', 'list-items', $menu_item);
                    }
                }
            }?>
        </ul>
    </div>
</nav>
<div class="wr-headerwrap__right">
    <div class="wr-userlogin sub-menu-holder">
        <a href="javascript:void(0);" id="profile-avatar-menue-icon">
            <?php Workreap_Profile_Menu::workreap_get_avatar();?>
        </a>
        <ul class="sub-menu">
            <?php if( !empty( $workreap_profile_menu_list ) ){
                foreach($workreap_profile_menu_list as $key => $menu_item){
                    if( !empty( $menu_item['type'] ) && ( $menu_item['type'] == $workreap_user_role || $menu_item['type'] == 'none' ) ){
                        $menu_item['id'] = $key;
                        workreap_get_template_part('dashboard/menus/menu', 'avatar-items', $menu_item);
                    }
                }
			} ?>
        </ul>
    </div>
</div>