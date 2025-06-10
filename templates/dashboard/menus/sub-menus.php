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

global $workreap_settings,$current_user;
$user_identity 	 = intval($current_user->ID);
$workreap_menu_list 	        = Workreap_Profile_Menu::workreap_get_dashboard_sub_menu();
$workreap_profile_menu_list 	= Workreap_Profile_Menu::workreap_get_dashboard_profile_menu();
$sortorder                  = array_column($workreap_profile_menu_list, 'sortorder');
array_multisort($sortorder, SORT_ASC, $workreap_profile_menu_list);

$list_sortorder                  = array_column($workreap_menu_list, 'sortorder');
array_multisort($list_sortorder, SORT_ASC, $workreap_menu_list);
$user_type		 = apply_filters('workreap_get_user_type', $current_user->ID );
$create_task    = $user_type === 'employers' ? workreap_get_page_uri('add_project_page') : workreap_get_page_uri('add_service_page');
$create_task_btn_text    = $user_type === 'employers' ? __('Create a project','workreap') : __('Create a gig','workreap');
$application_access    = isset($workreap_settings['application_access']) ? $workreap_settings['application_access'] : '';
$workreap_user_role = apply_filters('workreap_get_user_type', $user_identity);
?>
<div class="wr-headerbottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="wr-freelancer-tabs">
                    <nav class="wr-navbar wr-navbarbtm navbar-expand-xl">
                        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNavvtwo" aria-expanded="false">
                            <i class="wr-icon-menu"></i>
                        </button>
                        <div class="navbar-collapse collapse" id="navbarNavvtwo" style="">
                            <ul class="navbar-nav wr-navbarnav" id="myTab" role="tablist">
                                <?php if( !empty( $workreap_menu_list ) ){
                                        foreach($workreap_menu_list as $key => $menu_item){
                                            if( !empty( $menu_item['type'] ) && ( $menu_item['type'] == $workreap_user_role || $menu_item['type'] == 'none' ) ){
                                                $menu_item['id'] = $key;                       
                                                workreap_get_template_part('dashboard/menus/menu', 'list-items', $menu_item);
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </nav>
                    <?php if( ($user_type === 'employers' && $application_access == 'task_based') || ($user_type === 'freelancers' && $application_access !== 'project_based') ){ ?>
                        <div id="support_container">
						<div class="wr-bootstraps-tabs-button">
                            <a href="https://nzuricoach.com/support-portal/" class="wr-tabs-button">
			                    Support
                                <i class="wr-icon-plus"></i>
                            </a>
                        </div>
						<div class="wr-bootstraps-tabs-button" style="display:none;">
                            <a href="<?php echo esc_url($create_task);?>" class="wr-tabs-button">
			                    <?php echo esc_html($create_task_btn_text);?>
                                <i class="wr-icon-plus"></i>
                            </a>
                        </div>
					
					</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>