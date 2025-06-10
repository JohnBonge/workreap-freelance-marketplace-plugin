<?php
/**
 * The override theme header
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/public
 */

if (!class_exists('Workreap_Profile_Menu')) {

    class Workreap_Profile_Menu {

        protected static $instance = null;

        public function __construct() {

			add_action('workreap_process_headers_menu', array(__CLASS__, 'workreap_profile_menu'));
			add_action('workreap_process_headers_sub_menu', array(__CLASS__, 'workreap_profile_sub_menu'));
        }

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function getInstance() {

            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

		public static function workreap_get_admin_menu() {
			$workreap_menu_list = array(
				'dashboard'	=> array(
					'title' 	=> esc_html__('Dashboard', 'workreap'),
					'class'		=> 'wr-dashboard',
					'icon'		=> 'wr-icon-archive',
					'type'		=> 'none',
					'ref'		=> 'earnings',
					'mode'		=> 'insights',
					'sortorder'	=> 1,
				),
				'disputes'	=> array(
					'title' 	=> esc_html__('Disputes', 'workreap'),
					'class'		=> 'wr-dispute',
					'icon'		=> 'wr-icon-alert-circle',
					'type'		=> 'none',
					'ref'		=> 'disputes',
					'mode'		=> 'listing',
					'sortorder'	=> 3,
				),
				'earings'	=> array(
					'title' 	=> esc_html__('Manage earnings', 'workreap'),
					'class'		=> 'wr-earnings',
					'icon'		=> 'wr-icon-credit-card',
					'type'		=> 'none',
					'ref'		=> 'earnings',
					'mode'		=> 'manage',
					'sortorder'	=> 4,
				),
				'task'	=> array(
					'title' 	=> esc_html__('Manage task', 'workreap'),
					'class'		=> 'wr-tasks',
					'icon'		=> 'wr-icon-activity',
					'type'		=> 'none',
					'ref'		=> 'task',
					'mode'		=> 'listing',
					'sortorder'	=> 5,
				),
				'projects'	=> array(
					'title' 	=> esc_html__('Manage projects', 'workreap'),
					'class'		=> 'wr-tasks',
					'icon'		=> 'wr-icon-grid',
					'type'		=> 'none',
					'ref'		=> 'projects',
					'mode'		=> 'listing',
					'sortorder'	=> 6,
				),
				'logout'	=> array(
					'title' 	=> esc_html__('Logout', 'workreap'),
					'class'		=> 'wr-notification',
					'icon'		=> 'wr-icon-power',
					'ref'		=> 'logout',
					'mode'		=> '',
					'type'		=> 'none',
					'sortorder'	=> 7,
				)
			);
			if(in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins')))){
				$workreap_menu_list['inbox']	= array(
					'title' 	=> esc_html__('Inbox', 'workreap'),
					'class'		=> 'wr-dispute',
					'icon'		=> 'wr-icon-message-square',
					'type'		=> 'none',
					'ref'		=> 'inbox',
					'mode'		=> 'listing',
					'sortorder'	=> 2,
				);
			}
			$workreap_menu_list 	= apply_filters('workreap_filter_admin_menu', $workreap_menu_list);
			return $workreap_menu_list;
		}

		public static function workreap_get_dashboard_menu() {
			global $workreap_settings,$workreap_notification,$current_user;
			$app_task_base      = workreap_application_access('task');
			$app_project_base   = workreap_application_access('project');
			$is_guppy_active = in_array( 'wp-guppy/wp-guppy.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'wpguppy-lite/wpguppy-lite.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );

			$workreap_menu_list = array(
				'find-projects'	=> array(
					'title' => esc_html__('Explore projects', 'workreap'),
					'class'		=> 'wr-find-projects',
					'icon'		=> '',
					'ref'		=> 'find-project',
					'mode'		=> '',
					'sortorder'	=> 3,
					'type'		=> 'freelancers',
				),
			);

			$workreap_menu_list['find-task']	= array(
				'title' 	=> esc_html__('Explore tasks', 'workreap'),
				'class'		=> 'wr-search-task',
				'icon'		=> '',
				'sortorder'	=> 0,
				'ref'		=> 'find-task',
				'mode'		=> '',
				'type'		=> 'employers'
			);

			if( empty($app_project_base) ){
				unset($workreap_menu_list['find-projects']);
			}

			if( empty($app_task_base) ){
				unset($workreap_menu_list['find-task']);
			}

			if(!empty($workreap_notification['notify_module'])){
				$workreap_menu_list['notifications']	= array(
					'title' 	=> '',
					'class'		=> 'wr-menu-notifications',
					'icon'		=> '',
					'type'		=> 'none',
					'ref'		=> 'notifications',
					'mode'		=> '',
					'sortorder'	=> 6,
				);
			}

			if($is_guppy_active){
				$workreap_menu_list['inbox']	= array(
					'title' 	=> esc_html__('', 'workreap'),
					'class'		=> 'wr-inbox',
					'icon'		=> 'wr-icon-message-square',
					'type'		=> 'none',
					'ref'		=> 'inbox',
					'mode'		=> '',
					'sortorder'	=> 7,
				);
			}

			$workreap_menu_list 	= apply_filters('workreap_filter_dashboard_menu', $workreap_menu_list);

			return $workreap_menu_list;
		}

		public static function workreap_get_dashboard_sub_menu() {
			global $workreap_settings,$workreap_notification,$current_user;
			$app_task_base      = workreap_application_access('task');
			$app_project_base   = workreap_application_access('project');
			$workreap_menu_list = array(
				'earnings'	=> array(
					'title' 	=> esc_html__('Insights', 'workreap'),
					'class'		=> 'wr-earnings',
					'icon'		=> 'wr-icon-layers',
					'type'		=> 'freelancers',
					'ref'		=> 'earnings',
					'mode'		=> '',
					'sortorder'	=> 1,
				),
				'manageprojects'	=> array(
					'title' 	=> esc_html__('Manage projects', 'workreap'),
					'class'		=> 'wr-projectlistings',
					'icon'		=> 'wr-icon-external-link',
					'type'		=> 'employers',
                    'ref'		=> 'projects',
                    'mode'		=> 'listing',
					'sortorder'	=> 2,
				),
				'manage-projects'	=> array(
					'title' 	=> esc_html__('Manage projects', 'workreap'),
					'class'		=> 'wr-manageprojects',
					'icon'		=> 'wr-icon-external-link',
					'type'		=> 'freelancers',
					'ref'		=> '',
					'mode'		=> '',
					'sortorder'	=> 3,
					'submenu'	=> apply_filters('workreap_dasboard_projects_menu_filter', array(
							'find-projects'	=> array(
								'title' => esc_html__('Explore projects', 'workreap'),
								'class'	=> 'wr-find-projects',
								'icon'	=> '',
								'ref'		=> 'find-project',
								'mode'		=> '',
								'sortorder'	=> 3,
								'type'		=> 'freelancers',
							),
							'projectlistings'	=> array(
								'title' => esc_html__('My projects', 'workreap'),
								'class'	=> 'wr-projectlistings',
								'icon'	=> '',
								'ref'		=> 'projects',
								'mode'		=> 'listing',
								'sortorder'	=> 4,
								'type'		=> 'freelancers',
							),
						)
					),
				),
				'managetasks'	=> array(
					'title' 	=> esc_html__('Manage task', 'workreap'),
					'class'		=> 'wr-managetask',
					'icon'		=> 'wr-icon-file-text',
					'type'		=> 'freelancers',
					'ref'		=> '',
					'mode'		=> '',
					'sortorder'	=> 3,
					'submenu'	=> apply_filters('workreap_dasboard_tasks_menu_filter', array(
							'create_task'	=> array(
								'title' => esc_html__('Create a task', 'workreap'),
								'class'	=> 'wr-tasklistings',
								'icon'	=> '',
								'ref'		=> 'create-task',
								'mode'		=> 'create',
								'sortorder'	=> 3,
								'type'		=> 'freelancers',
							),

							'tasklistings'	=> array(
								'title' => esc_html__('Task listings', 'workreap'),
								'class'	=> 'wr-tasklistings',
								'icon'	=> '',
								'ref'		=> 'task',
								'mode'		=> 'listing',
								'sortorder'	=> 4,
								'type'		=> 'freelancers',
							),
							'orders'	=> array(
								'title' 	=> esc_html__('Orders', 'workreap'),
								'class'		=> 'wr-orders',
								'icon'		=> '',
								'type'		=> 'freelancers',
								'ref'		=> 'orders',
								'mode'		=> '',
								'sortorder'	=> 6,
							),
						)
					),
				),
				'myorders'	=> array(
					'title' 	=> esc_html__('Manage task', 'workreap'),
					'class'		=> 'wr-myorders',
					'icon'		=> 'wr-icon-file-text',
					'type'		=> 'employers',
					'ref'		=> '',
					'mode'		=> '',
					'sortorder'	=> 5,
					'submenu'	=> apply_filters('workreap_dasboard_employer_tasks_menu_filter', array(
							'find-freelancer'	=> array(
								'title' => esc_html__('Find Coach', 'workreap'),
								'class'	=> 'wr-find-freelancers',
								'icon'	=> '',
								'ref'		=> 'find-freelancers',
								'mode'		=> 'listing',
								'sortorder'	=> 1,
								'type'		=> 'employers',
							),
					        'find-task'	=> array(
								'title' => esc_html__('Explore Services', 'workreap'),
								'class'	=> 'wr-find-tasks',
								'icon'	=> '',
								'ref'		=> 'find-task',
								'mode'		=> '',
								'sortorder'	=> 2,
								'type'		=> 'employers',
							),
							'taskslistings'	=> array(
								'title' => esc_html__('My Purchases', 'workreap'),
								'class'	=> 'wr-taskslistings',
								'icon'	=> '',
								'ref'		=> 'tasks-orders',
								'mode'		=> 'listing',
								'sortorder'	=> 3,
								'type'		=> 'employers',
							),
						)
					),
				),
				'disputes'		=> array(
					'title' 	=> esc_html__('Disputes', 'workreap'),
					'class'		=> 'wr-disputes',
					'icon'		=> 'wr-icon-refresh-ccw',
					'ref'		=> 'disputes',
					'mode'		=> 'listing',
					'sortorder'	=> 6,
					'type'		=> 'none',
				),
				'invoices'	=> array(
					'title' 	=> esc_html__('Invoices', 'workreap'),
					'class'		=> 'wr-invoices',
					'icon'		=> 'wr-icon-shopping-bag',
					'ref'		=> 'invoices',
					'mode'		=> 'listing',
					'sortorder'	=> 6,
					'type'		=> 'none',
				),
			);

			if( empty($app_project_base) ){
				unset($workreap_menu_list['manageprojects']);
				unset($workreap_menu_list['manage-projects']);
			}

			if( empty($app_task_base) ){
				unset($workreap_menu_list['myorders']);
				unset($workreap_menu_list['managetasks']);
			}

			if( empty($app_task_base) ){
				unset($workreap_menu_list['find-task']);
			}

			$workreap_menu_list 	= apply_filters('workreap_filter_dashboard_submenu', $workreap_menu_list);
			return $workreap_menu_list;
		}


		public static function workreap_get_dashboard_profile_menu() {
			global $workreap_settings,$current_user;
			$user_type		 = apply_filters('workreap_get_user_type', $current_user->ID );
			$workreap_menu_list = array(
				'wallet'	=> array(
					'title' 	=> esc_html__('Wallet balance:', 'workreap'),
					'class'		=> 'wr-wallet',
					'icon'		=> '',
					'sortorder'	=> 0,
					'ref'		=> '',
					'mode'		=> '',
					'type'		=> 'employers',
				),
				'balance'	=> array(
					'title' 	=> esc_html__('Account balance:', 'workreap'),
					'class'		=> 'wr-wallet',
					'icon'		=> '',
					'sortorder'	=> 0,
					'ref'		=> 'earnings',
					'mode'		=> '',
					'type'		=> 'freelancers',
				),
				'home'	=> array(
					'title' 	=> esc_html__('Visit home', 'workreap'),
					'class'		=> 'wr-visit-home',
					'icon'		=> 'wr-icon-home',
					'type'		=> 'none',
					'ref'		=> 'home',
					'mode'		=> 'public',
					'sortorder'	=> 1,
				),
				'profile'	=> array(
					'title' 	=> esc_html__('View profile', 'workreap'),
					'class'		=> 'wr-view-profile',
					'icon'		=> 'wr-icon-external-link',
//					'data-attr'		=> array('target'=> '_blank'),
					'type'		=> 'freelancers',
					'ref'		=> 'profile',
					'mode'		=> 'public',
					'sortorder'	=> 2,
				),
				'dashboard'	=> array(
					'title' 	=> esc_html__('Dashboard', 'workreap'),
					'class'		=> 'wr-dashboard',
					'icon'		=> 'wr-icon-layers',
					'type'		=> 'none',
					'ref'		=> 'earnings',
					'mode'		=> 'insights',
					'sortorder'	=> 2,
				),
				'settings'	=> array(
					'title' 	=> esc_html__('Settings', 'workreap'),
					'class'		=> 'wr-account-settings',
					'icon'		=> 'wr-icon-settings',
					'sortorder'	=> 5,
					'ref'		=> 'dashboard',
					'mode'		=> 'profile',
					'type'		=> 'freelancers',
				),
				'saveditems'	=> array(
					'title' 	=> esc_html__('Saved items', 'workreap'),
					'class'		=> 'wr-saveditems',
					'icon'		=> 'wr-icon-heart',
					'ref'		=> 'saved',
					'mode'		=> 'listing',
					'sortorder'	=> 6,
					'type'		=> 'none',
				),
				'logout'		=> array(
					'title' 	=> esc_html__('Logout', 'workreap'),
					'class'		=> 'wr-logout',
					'icon'		=> 'wr-icon-power',
					'ref'		=> 'logout',
					'mode'		=> '',
					'sortorder'	=> 9,
					'type'		=> 'none',
				),
			);

			$package_option			= !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'],array('paid','employer_free')) ? true : false;
			$employer_package_option	= !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'],array('paid','freelancer_free')) ? true : false;
			$identity_verification	= !empty($workreap_settings['identity_verification']) ? $workreap_settings['identity_verification'] : false;
			$switch_user    		= !empty($workreap_settings['switch_user']) ? $workreap_settings['switch_user'] : false;

			if( !empty($package_option) && !empty($user_type) && $user_type === 'freelancers'){
				$workreap_menu_list['packages']	= array(
					'title' 	=> esc_html__('Packages', 'workreap'),
					'class'		=> 'wr-earnings',
					'icon'		=> 'wr-icon-package',
					'ref'		=> 'packages',
					'mode'		=> '',
					'sortorder'	=> 3,
					'type'		=> 'freelancers',
				);
			}

			if(!empty($user_type) && $user_type === 'employers' ){
				$workreap_menu_list['dashboard']['ref'] = 'dashboard';
				$workreap_menu_list['dashboard']['mode'] = 'profile';
			}

			if( !empty($employer_package_option) && !empty($user_type) && $user_type === 'employers' ){
				$workreap_menu_list['packages']	= array(
					'title' 	=> esc_html__('Packages', 'workreap'),
					'class'		=> 'wr-earnings',
					'icon'		=> 'wr-icon-package',
					'ref'		=> 'packages',
					'mode'		=> '',
					'sortorder'	=> 3,
					'type'		=> 'employers',
				);
			}

			if( !empty($identity_verification) ){
				$identity_verified  		= get_user_meta($current_user->ID, 'identity_verified', true);
				$identity_verified			= !empty($identity_verified) && $identity_verified === '1' ? 'wr-identity-approved' : '';

				$workreap_menu_list['verification']	= array(
					'title' 	=> esc_html__('Identity verification', 'workreap'),
					'class'		=> 'wr-earnings'.' '.$identity_verified,
					'icon'		=> 'wr-icon-user-check',
					'ref'		=> 'dashboard',
					'mode'		=> 'verification',
					'sortorder'	=> 3,
					'type'		=> 'none',
				);
			}

            $user_type		 = apply_filters('workreap_get_user_type', $current_user->ID );

			if( !empty($switch_user) ){
				$workreap_menu_list['switch']	= array(
                    'title' 	=> $user_type === 'employers' ? __('Switch to freelancer','workreap') : __('Switch to employer','workreap'),
					'class'		=> 'wr-earnings wr_switch_user',
					'icon'		=> 'wr-icon-repeat',
					'data-attr'		=> array('data-id'=> $current_user->ID),
					'ref'		=> '',
					'mode'		=> '',
					'sortorder'	=> 2,
					'type'		=> 'none',
				);
			}


			$workreap_menu_list 	= apply_filters('workreap_filter_dashboard_profile_menu', $workreap_menu_list);
			return $workreap_menu_list;
		}

		/**
		 * Profile Menu
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function workreap_profile_menu() {
            global $current_user, $wp_roles, $userdata, $post;
			$user_identity 	 = intval($current_user->ID);

			$url_identity = $user_identity;
			if (isset($_GET['identity']) && !empty($_GET['identity'])) {
				$url_identity = intval($_GET['identity']);
			}

			$workreap_user_role = apply_filters('workreap_get_user_type', $user_identity);
			ob_start();

			if($workreap_user_role == 'freelancers' || $workreap_user_role == 'employers'){
				$workreap_menu_args = array(
					'user_identity'		=> $user_identity,
					'workreap_user_role'	=> $workreap_user_role,
				);

				//manage services template
				workreap_get_template(
					'dashboard/menus/menus.php', $workreap_menu_args
				);
			} else if(is_admin()){
				//current_user_can('administrator')
			} else {
				workreap_get_template(
					'dashboard/menus/primary-menu.php'
				);
			}

            $data	= ob_get_clean();
			echo apply_filters( 'workreap_profile_menu', $data );
        }

		/**
		 * Profile sub menu
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function workreap_profile_sub_menu() {
            global $current_user, $wp_roles, $userdata, $post;
			$user_identity 	 = intval($current_user->ID);

			$url_identity = $user_identity;
			if (isset($_GET['identity']) && !empty($_GET['identity'])) {
				$url_identity = intval($_GET['identity']);
			}

			$workreap_user_role = apply_filters('workreap_get_user_type', $user_identity);
			ob_start();

			if($workreap_user_role == 'freelancers' || $workreap_user_role == 'employers'){
				$workreap_menu_args = array(
					'user_identity'		=> $user_identity,
					'workreap_user_role'	=> $workreap_user_role,
				);

				//manage services template
				workreap_get_template(
					'dashboard/menus/sub-menus.php', $workreap_menu_args
				);
			}
            $data	= ob_get_clean();
			echo apply_filters( 'workreap_profile_sub_menus', $data );
        }

		/**
		 * Generate Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function workreap_custom_profile_menu_link($ref = '', $id = '', $key='', $return = true ) {

			//$profile_page = '';
			$profile_page = workreap_get_page_uri('dashboard');

            if ( empty( $profile_page ) ) {
                $permalink = home_url('/');
            } else {
                $query_arg['ref'] = urlencode($ref);

                //id for edit record
                if (!empty($id)) {
                    $query_arg['id'] = urlencode($id);
                }

				if (!empty($key)) {
                    $query_arg['key'] = urlencode($key);
                }

                $permalink = add_query_arg(
                        $query_arg, esc_url( $profile_page  )
                );

            }

            if ($return) {
                return esc_url_raw($permalink);
            } else {
                echo esc_url_raw($permalink);
            }
        }

		/**
		 * Generate Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function workreap_profile_admin_menu_link($ref = '', $user_identity = '', $return = false, $mode = '', $id = '') {

			$profile_page = workreap_get_page_uri('admin_dashboard');

            if ( empty( $profile_page ) ) {
                $permalink = home_url('/');
            } else {
                $query_arg['ref'] = urlencode($ref);

                //mode
                if (!empty($mode)) {
                    $query_arg['mode'] = urlencode($mode);
                }

                //id for edit record
                if (!empty($id)) {
                    $query_arg['id'] = urlencode($id);
                }

                $query_arg['identity'] = urlencode($user_identity);

                $permalink = add_query_arg(
                        $query_arg, esc_url( $profile_page  )
                );

            }

            if ($return) {
                return esc_url_raw($permalink);
            } else {
                echo esc_url_raw($permalink);
            }
        }

		/**
		 * Generate Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function workreap_profile_menu_link($ref = '', $user_identity = '', $return = false, $mode = '', $id = '') {

			//$profile_page = '';
			$profile_page = workreap_get_page_uri('dashboard');

            if ( empty( $profile_page ) ) {
                $permalink = home_url('/');
            } else {
                $query_arg['ref'] = urlencode($ref);

                //mode
                if (!empty($mode)) {
                    $query_arg['mode'] = urlencode($mode);
                }

                //id for edit record
                if (!empty($id)) {
                    $query_arg['id'] = urlencode($id);
                }

				if(!empty($user_identity)){
					$query_arg['identity'] = urlencode($user_identity);
				}
               

                $permalink = add_query_arg(
                        $query_arg, esc_url( $profile_page  )
                );

            }

            if ($return) {
                return esc_url_raw($permalink);
            } else {
                echo esc_url_raw($permalink);
            }
        }

		/**
		 * Generate admin Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function workreap_admin_profile_menu_link($ref = '', $user_identity = '', $return = false, $mode = '', $id = '') {

			//$profile_page = '';
			$profile_page = workreap_get_page_uri('admin_dashboard');

            if ( empty( $profile_page ) ) {
                $permalink = home_url('/');
            } else {
                $query_arg['ref'] = urlencode($ref);

                //mode
                if (!empty($mode)) {
                    $query_arg['mode'] = urlencode($mode);
                }

                //id for edit record
                if (!empty($id)) {
                    $query_arg['id'] = urlencode($id);
                }

                $query_arg['identity'] = urlencode($user_identity);

                $permalink = add_query_arg(
                        $query_arg, esc_url( $profile_page  )
                );

            }

            if ($return) {
                return esc_url_raw($permalink);
            } else {
                echo esc_url_raw($permalink);
            }
        }
		/**
		 * Generate Profile Avatar Image Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
        public static function workreap_get_avatar() {
        	global $current_user, $wp_roles, $userdata, $post;
          	$user_identity  = $current_user->ID;
			$user_type		= apply_filters('workreap_get_user_type', $user_identity );
			$link_id		= workreap_get_linked_profile_id( $user_identity );
			$avatar = apply_filters(
				'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 50, 'height' => 50)
			);

			if (empty($avatar)){
				$user_dp = workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/fravatar-50x50.jpg');
				$avatar = !empty($workreap_settings['workreap_default_user_image']) ? $workreap_settings['defaul_freelancers_profile'] : $user_dp;
			}

			if( !empty($user_type) && $user_type === 'administrator'){
				$avatar	= get_avatar_url($user_identity,array('size' => 50));
			}
			?>
			<img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_attr_e('User profile', 'workreap'); ?>">
			<?php
        }
    }

    new Workreap_Profile_Menu();
}
