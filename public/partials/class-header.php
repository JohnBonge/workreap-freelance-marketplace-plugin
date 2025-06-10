<?php
/**
 * The override theme header
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */

use Elementor\Modules\ElementManager\Options;

if (!class_exists('WorkreapHeader')) {

    class WorkreapHeader {

        function __construct() {
			add_action( 'get_header', array(&$this, 'workreap_do_process_headers'), 5, 2 );
			add_action('workreap_process_headers', array(&$this, 'workreap_do_process_headers_v1'));
			add_action('workreap_dashboard_user_sidebar', array(&$this, 'workreap_dashboard_user_sidebar'));
            add_action('workreap_process_admin_headers', array(&$this, 'workreap_do_process_admin_headers'));
        }
		// Method to get the header
		public function workreap_do_process_headers($name, $args){
			global $workreap_settings;
            $user_id	  	= is_user_logged_in()  ? get_current_user_id() : 0 ;
            $user_type		= !empty($user_id) ? workreap_get_user_type($user_id) : '';
            $header_type		= !empty($workreap_settings['header_type_after_login']) ? $workreap_settings['header_type_after_login'] : '';
			if ( is_user_logged_in() && ( !empty($user_type) && ( $user_type ==='freelancers' || $user_type === 'employers' ) && ($header_type === 'dashboard-header' || is_workreap_template()) ) ) {

                include workreap_load_template( 'templates/headers/user-dashboard-header' );

                $templates      = array();
                $name           = (string) $name;

                if ( '' !== $name ) {
                    $templates[] = "header-{$name}.php";
                }

                $templates[]        = 'header.php';
                remove_all_actions( 'wp_head' );

                ob_start();
                // It cause a `require_once` so, in the get_header it self it will not be required again.
                locate_template( $templates, true );
                ob_get_clean();
            } elseif(is_page_template('templates/admin-dashboard.php') ){
                include workreap_load_template( 'templates/headers/admin-dashboard-header' );
				$templates = array();
				$name = (string) $name;

				if ( '' !== $name ) {
					$templates[] = "header-{$name}.php";
				}
				$templates[] = 'header.php';
				remove_all_actions( 'wp_head' );

				ob_start();
				// It cause a `require_once` so, in the get_header it self it will not be required again.
				locate_template( $templates, true );
				ob_get_clean();
            } else{
                //do some actions
            }
		}

        /**
         * @Prepare headers
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_admin_headers() {
            global $current_user;
			$this->workreap_do_process_admin_header_v1();

        }

	    public function workreap_dashboard_user_sidebar() {
		    global $current_user, $post, $workreap_settings;
		    $user_identity              = intval( $current_user->ID );
		    $workreap_user_role         = apply_filters( 'workreap_get_user_type', $user_identity );
		    $user_profile_id            = workreap_get_linked_profile_id( $current_user->ID, '', $workreap_user_role );
		    $user_name                  = ! empty( $current_user->first_name || $current_user->last_name ) ? ( $current_user->first_name . ( $current_user->last_name ? ' ' . $current_user->last_name : '' ) ) : $current_user->display_name;
		    $is_online                  = apply_filters( 'workreap_is_user_online', $user_identity );
		    $workreap_profile_menu_list = Workreap_Profile_Menu::workreap_get_dashboard_profile_menu();
		    $sortorder                  = array_column( $workreap_profile_menu_list, 'sortorder' );
		    array_multisort( $sortorder, SORT_ASC, $workreap_profile_menu_list );
		    $workreap_menu_list = Workreap_Profile_Menu::workreap_get_dashboard_sub_menu();
		    $list_sortorder     = array_column( $workreap_menu_list, 'sortorder' );
		    array_multisort( $list_sortorder, SORT_ASC, $workreap_menu_list );
		    $meata_keys      = array( 'linked_profile' => $user_profile_id, 'status' => 0 );
		    $unread_message  = workreap_post_count( 'notification', 'publish', $meata_keys );
		    $messages_count  = apply_filters( 'wpguppy_count_all_unread_messages', $user_identity );
		    $is_guppy_active = in_array( 'wp-guppy/wp-guppy.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'wpguppy-lite/wpguppy-lite.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
		    $sidebar_fold = isset($workreap_settings['dashboard_sidebar_behaviour']) ? $workreap_settings['dashboard_sidebar_behaviour'] : '';
		    $user_type		 = apply_filters('workreap_get_user_type', $current_user->ID );
		    $create_task    = $user_type === 'employers' ? workreap_get_page_uri('add_project_page') : workreap_get_page_uri('add_service_page');
		    $create_task_btn_text    = $user_type === 'employers' ? __('Create a project','workreap') : __('Create a gig','workreap');
		    $application_access    = isset($workreap_settings['application_access']) ? $workreap_settings['application_access'] : '';
		    $header_type		= !empty($workreap_settings['header_type_after_login']) ? $workreap_settings['header_type_after_login'] : '';
		    $class = 'wr-dashboard-sidebar-wrapper';
		    $logo   = WORKREAP_DIRECTORY_URI . '/public/images/logo.png';
		    $logo   = !empty($workreap_settings['defaul_site_logo']['url']) ? $workreap_settings['defaul_site_logo']['url'] : $logo;
		    if(is_page_template('templates/admin-dashboard.php') || $header_type === 'dashboard-header' || is_workreap_template() ){
                $class .= ' wr-sidebar-show';
            }else{
		        $class .= ' wr-sidebar-responsive-only';
            }
		    if($sidebar_fold == 'collapse'){
		        $class .= ' wr-folded';
            }
		    ?>
		    <div class="<?php echo esc_attr($class); ?>">
                <div class="wr-dashboard-sidebar-inner">
                    <div class="wr-dashboard-sidebar-header">
                        <button type="button" class="wr-dashboard-sidebar-toggle-btn">
		                    <?php Workreap_Profile_Menu::workreap_get_avatar(); ?>
                            <i class="wr-icon-layout" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="wr-dashboard-sidebar-user-wrapper">
                        <div class="wr-dashboard-sidebar-user-info">
                            <figure class="wr-dashboard-sidebar-user-avatar">
                                <?php Workreap_Profile_Menu::workreap_get_avatar(); ?>
                                <figcaption class="wr-user-tag wr-<?php esc_attr_e($is_online ? 'online' : 'offline'); ?>"></figcaption>
                            </figure>
                            <div class="wr-dashboard-sidebar-user-content">
                                <?php if( !empty($user_name) ){?>
                                    <h4>
                                        <?php echo esc_html($user_name);?>
                                        <?php
                                        if(isset($post->ID)){
	                                        do_action( 'workreap_verification_tag_html', $post->ID );
                                        } ?>
                                    </h4>
                                <?php } ?>
                                <figcaption class="wr-user-tag wr-<?php esc_attr_e($is_online ? 'online' : 'offline'); ?>"><?php echo $is_online ? esc_html__('Active','workreap') : esc_html__('In Active','workreap') ?></figcaption>
                            </div>
                            <i class="wr-icon-chevron-down" aria-hidden="true"></i>
                        </div>
                        <div class="wr-dashboard-sidebar-profile-menu">
                            <?php if( current_user_can('administrator') || ( !empty($workreap_user_role) && ($workreap_user_role == 'freelancers' || $workreap_user_role == 'employers') )){
                                ?>
                                <ul class="wr-dashboard-sidebar-menu-list"><?php
                                    if (!empty($workreap_user_role) && ($workreap_user_role == 'freelancers' || $workreap_user_role == 'employers') ) {
                                        if (!empty($workreap_profile_menu_list)) {

                                            unset($workreap_profile_menu_list['balance']);
                                            unset($workreap_profile_menu_list['wallet']);
                                            unset($workreap_profile_menu_list['logout']);

                                            foreach ($workreap_profile_menu_list as $key => $menu_item) {
                                                if (!empty($menu_item['type']) && ($menu_item['type'] == $workreap_user_role || $menu_item['type'] == 'none')) {
                                                    $menu_item['id'] = $key;
                                                    workreap_get_template_part('dashboard/menus/menu', 'avatar-items', $menu_item);
                                                }
                                            }
                                        }
                                    } ?>
                                </ul>
                            <?php } ?>
                        </div>
                        <?php
                        ?>
                    </div>
                    <?php if( ($user_type === 'employers' && $application_access !== 'task_based') || ($user_type === 'freelancers' && $application_access !== 'project_based') ){ ?>
                        <div class="wr-dashboard-sidebar-action-button">
                            <a href="<?php echo esc_url($create_task);?>" class="wr-dashboard-sidebar-buttons">
                                <span><?php echo esc_html($create_task_btn_text);?></span>
                                <i class="wr-icon-edit"></i>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="wr-dashboard-sidebar-setting-menu">
                        <div class="wr-sidebarwrapper wr-scroll-hover wr-customSrollnone">
                            <ul class="wr-dashboard-sidebar-menu-list">
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
                    </div>
                    <div class="wr-dashboard-sidebar-footer-menu">
                        <div class="wr-dashboard-sidebar-notifications">
                            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('notifications', $user_identity, false, 'listing');?>" class="wr-notification-menu-item">
                            <span class="wr-notification-icon">
                                <i class="wr-icon-bell"></i>
                                <?php if(!empty($unread_message) ){?><em class="wr-remaining-notification wr-notfy-counter"><?php echo intval($unread_message);?></em><?php } ?>
                            </span>
                                <span><?php esc_html_e('Notifications','workreap');?></span>
                            </a>
                            <?php if($is_guppy_active){ ?>
                                <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('inbox', $user_identity, false);?>" class="wr-messages-menu-item">
                                <span class="wr-notification-icon">
                                    <i class="wr-icon-message-square"></i>
                                     <?php if(!empty($messages_count) ){?><em class="wr-remaining-notification"><?php echo esc_html($messages_count);?></em><?php } ?>
                                </span>
                                <span><?php esc_html_e('Messages','workreap');?></span>
                                </a>
                            <?php }?>
                        </div>
                        <div class="wr-dashboard-sidebar-balance">
                            <?php if($user_type === 'employers'){
	                            $user_balance    = get_user_meta( $user_identity, '_employer_balance', true );
	                            $user_balance    = ! empty( $user_balance ) ? $user_balance : 0;
                                ?>
                                <a href="javascript:void(0);" data-bs-target="#tbcreditwallet" data-bs-toggle="modal">
                                    <i class="wr-icon-credit-card" aria-hidden="true"></i>
                                    <span>
                                    <?php echo esc_html__('Wallet balance: ','workreap')?> <strong>
                                    <?php workreap_price_format($user_balance);?></strong>
                                    </span>
                                </a>
                            <?php }else{
	                            $account_balance             = workreap_account_details($user_identity,array('wc-completed'),'completed');
	                            $withdrawn_amount           = workreap_account_withdraw_details($user_identity,array('pending','publish'));
	                            $available_withdraw_amount = $account_balance - $withdrawn_amount;
                                ?>
                                <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('earnings', $user_identity)?>">
                                    <i class="wr-icon-credit-card" aria-hidden="true"></i>
                                    <span>
                                    <?php echo esc_html__('Account balance: ','workreap')?> <strong>
                                    <?php workreap_price_format($available_withdraw_amount);?></strong>
                                    </span>
                                </a>
                            <?php }?>
                        </div>
                        <div class="wr-dashboard-sidebar-logout">
                            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">
                                <i class="wr-icon-power" aria-hidden="true"></i>
                                <span><?php esc_html_e('Logout','workreap'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    <?php }

		/**
         * @Prepare headers
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_headers_v1() {
            global $current_user;
			$this->workreap_do_process_header_v1();
            $this->workreap_do_process_sub_menu();
        }

        /**
         * @Prepare admin header v1
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_admin_header_v1() {
            global $workreap_settings,$current_user;
			$logo   = WORKREAP_DIRECTORY_URI . '/public/images/logo-white.png';
            $logo   = !empty($workreap_settings['defaul_site_logo']['url']) ? $workreap_settings['defaul_site_logo']['url'] : $logo;
            $dashboard_url	    = Workreap_Profile_Menu::workreap_admin_profile_menu_link('dashboard', $current_user->ID, true, 'insights');            ?>
            <header class="wr-header wr-dashboard-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="wr-headercontent">                               
                                <div class="wr-frontendsite">
                                    <a href="<?php echo esc_url(get_home_url());?>" target="_blank">
                                        <div class="wr-frontendsite__title">
                                            <h5><?php esc_html_e('Visit frontend site','workreap');?></h5>
                                        </div>
                                        <i class="wr-icon-external-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <?php
        }

		/**
         * @Prepare header v1
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_header_v1() {
            global $workreap_settings;
			$logo   = WORKREAP_DIRECTORY_URI . '/public/images/logo-white.png';
            $logo   = !empty($workreap_settings['defaul_site_logo']['url']) ? $workreap_settings['defaul_site_logo']['url'] : $logo;
            ?>
            <header class="wr-header wr-dashboard-header">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="wr-headerwrap">
								<strong class="wr-logo">
									<a href="<?php echo esc_url(home_url('/')); ?>"><img class="amsvglogo" src="<?php echo esc_url($logo);?>" alt="<?php echo esc_attr(get_bloginfo('name'));?>"></a>
								</strong>
								<?php do_action('workreap_process_headers_menu'); ?>
							</div>
						</div>
					</div>
				</div>
			</header>
            <?php
        }

        /**
         * @Prepare sub header
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_sub_menu() {
            global $workreap_settings;
            ?>
            <div class="wr-headerbottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="wr-freelancer-tabs">
                                <?php do_action('workreap_process_headers_sub_menu'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * @Main Navigation
         * @return {}
         */
        public static function workreap_prepare_navigation($location = '', $id = 'menus', $class = '', $depth = '0') {

            if (has_nav_menu($location)) {
                $defaults = array(
                    'theme_location'        => "$location",
                    'menu'                  => '',
                    'container'             => 'ul',
                    'container_class'       => '',
                    'container_id'          => '',
                    'menu_class'            => "$class",
                    'menu_id'               => "$id",
                    'echo'                  => false,
                    'fallback_cb'           => 'wp_page_menu',
                    'before'                => '',
                    'after'                 => '',
                    'link_before'           => '',
                    'link_after'            => '',
                    'items_wrap'            => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'                 => "$depth",
                );
                echo do_shortcode(wp_nav_menu($defaults));
            } else {
                $defaults = array(
                    'theme_location'            => "$location",
                    'menu'                      => '',
                    'container'                 => 'ul',
                    'container_class'           => '',
                    'container_id'              => '',
                    'menu_class'                => "$class",
                    'menu_id'                   => "$id",
                    'echo'                      => false,
                    'fallback_cb'               => 'wp_page_menu',
                    'before'                    => '',
                    'after'                     => '',
                    'link_before'               => '',
                    'link_after'                => '',
                    'items_wrap'                => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'                     => "$depth",
                );
                echo do_shortcode(wp_nav_menu($defaults));
            }
        }


	}

	new WorkreapHeader();
}
