<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Workreap_Migration' ) ) {

	class Workreap_Migration {

		private static $_instance = null;

		private function __construct() {
			//Register Menu
			add_action( 'admin_menu', array( $this, 'migration_submenu' ) );
			//Ajax Callbacks
			add_action( 'wp_ajax_workreap_migrate_data', array( $this, 'data_migration' ) );
		}

		public static function instance() {
			if ( ! isset( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function migration_submenu() {

			$unyson_option = get_option('fw_theme_settings_options:workreap');

			if(isset($unyson_option) && !empty($unyson_option) ) {
				add_submenu_page(
					'edit.php?post_type=freelancers',
					esc_html__( 'Migrations', 'workreap' ),
					esc_html__( 'Migrations', 'workreap' ),
					'manage_options',
					'wr_migration',
					array( $this, 'migration_markup' )
				);
			}

		}

		public function import_dashboard_templates(){

			global $current_user;

			$response = [];

			$previous_template = [
				'directory/dashboard.php' => 'templates/dashboard.php',
				'directory/services-search.php' => 'templates/search-task.php',
				'directory/project-search.php' => 'templates/search-projects.php',
				'directory/freelancer-search.php' => 'templates/search-freelancer.php',
				'directory/project-proposal.php' => 'templates/submit-proposal.php',
			];

			foreach ($previous_template as $prev_slug => $slug){

				$get_previous_post = get_posts(array(
					'post_type' => 'page',
					'post_status' => 'any',
					'posts_per_page' => 1,
					'meta_key' => '_wp_page_template',
					'meta_value' => $prev_slug,
				));

				$previous_post_id = isset($get_previous_post[0]) ? $get_previous_post[0] : '';

				if(!empty($previous_post_id)){
					update_post_meta($previous_post_id,'_wp_page_template',$slug);
				}

			}

			$new_templates = [
				'templates/admin-dashboard.php' => __('Admin dashboard','workreap'),
				'templates/dashboard.php' => __('User dashboard','workreap'),
				'templates/search-task.php' => __('Search task','workreap'),
				'templates/search-projects.php' => __('Search project','workreap'),
				'templates/search-freelancer.php' => __('Search freelancer','workreap'),
				'templates/add-project.php' => __('Add/edit project','workreap'),
				'templates/submit-proposal.php' => __('Submit proposal','workreap'),
				'templates/add-task.php' => __('Create task','workreap'),
				'templates/pricing-plans.php' => __('Price Plans','workreap'),
			];

			$new_templates_redux_fields = [
				'templates/admin-dashboard.php' => 'tpl_admin_dashboard',
				'templates/dashboard.php' => 'tpl_dashboard',
				'templates/search-task.php' => 'tpl_service_search_page',
				'templates/search-projects.php' => 'tpl_project_search_page',
				'templates/search-freelancer.php' => 'tpl_freelancers_search_page',
				'templates/add-project.php' => 'tpl_add_project_page',
				'templates/submit-proposal.php' => 'tpl_submit_proposal_page',
				'templates/add-task.php' => 'tpl_add_service_page',
				'templates/pricing-plans.php' => 'tpl_package_page',
			];

			foreach ($new_templates as $template_slug => $template_name){

				$get_post = get_posts(array(
					'post_type' => 'page',
					'post_status' => 'any',
					'posts_per_page' => 1,
					'meta_key' => '_wp_page_template',
					'meta_value' => $template_slug,
				));

				if(!isset($get_post[0])){
					$post_id = wp_insert_post(array(
						'post_type' => 'page',
						'post_status' => 'publish',
						'post_title' => $template_name,
						'meta_input' => array(
							'_wp_page_template' => $template_slug,
						),
					));
				}else{
					$post_id = isset($get_post[0]->ID) ? $get_post[0]->ID : '';
				}

				if(!empty($post_id)){
					$opt_name = 'workreap_settings';
					if(isset($new_templates_redux_fields[$template_slug])){
						Redux::set_option( $opt_name, $new_templates_redux_fields[$template_slug], $post_id );
					}
				}

			}

			//Create Wallet Product
			$funds_products = wc_get_products(array(
				'limit' => 1,
				'status' => 'any',
				'type' => 'funds',
				'return' => 'ids',
			));

			$funds_product = !empty($funds_products) ? reset( $funds_products ) : [];

			if(empty($funds_product)){
				$funds_product_id = wp_insert_post(array(
					'post_title'    => esc_html__('Wallet','workreap'),
					'post_author'   => $current_user->ID,
					'post_status'   => 'publish',
					'post_type'     => 'product',
				));
				if ($funds_product_id) {
					wp_set_object_terms($funds_product_id, 'funds', 'product_type');
					update_post_meta($funds_product_id, '_price', 1);
					update_post_meta($funds_product_id, '_regular_price', 1);
					update_post_meta($funds_product_id, '_virtual', 'yes');
					wc_delete_product_transients($funds_product_id);
				}
			}

			$response['type'] = 'success';
			$response['next_step'] = 4;
			$response['next_message'] = __('Migrating freelancers and employers','workreap');

			wp_send_json($response);

		}

		public function redux_json_import(){

			$json_file = WORKREAP_ACTIVE_THEME_DIRECTORY . '/demo-data/redux.json';
			$opt_name = 'workreap_settings';

			$response = [];

			if(file_exists($json_file)){
				$options = json_decode(file_get_contents($json_file), true);

				foreach ($options as $key => $option){
					Redux::set_option( $opt_name, $key, $option );
				}

				$response['type'] = 'success';
				$response['message'] = __('Redux demo content has been imported','workreap');
			}

			if(empty($response)){
				$response['type'] = 'failure';
				$response['message'] = __('Something went wrong in redux demo imports.','workreap');
			}

			$response['next_step'] = 2;
			$response['next_message'] = __('Import unyson fields to redux.','workreap');

			wp_send_json($response);

		}

		public function unyson_to_redux_fields() {

			$response = [];

			if ( class_exists( 'Redux' ) ) {

				global $workreap_settings;
				$opt_name = 'workreap_settings';
				$unyson_option = get_option('fw_theme_settings_options:workreap');

				if(isset($unyson_option) && !empty($unyson_option) ){
					
					//404 Page Settings
					$not_found_image 	= $unyson_option[ '404_banner'];
					$not_found_title 	= $unyson_option[ '404_title'];
					$not_found_desc 	= $unyson_option[ '404_description'];
					$system_access		= $unyson_option['system_access'];
					
					Redux::set_option( $opt_name, 'package_option', $system_access );
					Redux::set_option( $opt_name, 'title_404', $not_found_title ? $not_found_title : '' );
					Redux::set_option( $opt_name, 'description_404', $not_found_desc ? $not_found_desc : '' );
					Redux::set_option( $opt_name, 'image_404', array(
						'url' => isset($not_found_image['url']) && !empty($not_found_image['url']) ? $not_found_image['url'] : '',
						'id'  => isset($not_found_image['attachment_id']) && !empty($not_found_image['attachment_id']) ? $not_found_image['attachment_id'] : ''
					));

					//Custom CSS
					$custom_css = $unyson_option[ 'custom_css'];
					Redux::set_option( $opt_name, 'custom_css', isset($custom_css) && !empty($custom_css) ? $custom_css : '' );

					//Directory
					Redux::set_option( $opt_name, 'registration_view_type', 'popup' );

					//Preloader
					$preloader        = $unyson_option[ 'preloader'];
					$preloader_custom = isset( $preloader['enable']['preloader']['custom']['loader'] ) ? $preloader['enable']['preloader']['custom']['loader'] : '';
					Redux::set_option( $opt_name, 'site_loader', isset( $preloader['gadget'] ) && $preloader['gadget'] === 'enable' ? 1 : 0 );
					Redux::set_option( $opt_name, 'loader_type', isset( $preloader['enable']['preloader']['gadget'] ) ? $preloader['enable']['preloader']['gadget'] : 'default' );
					Redux::set_option( $opt_name, 'loader_image', array(
						'url' => isset($preloader_custom['url']) && !empty($preloader_custom['url']) ? $preloader_custom['url'] : '',
						'id'  => isset($preloader_custom['attachment_id']) && !empty($preloader_custom['attachment_id']) ? $preloader_custom['attachment_id'] : ''
					));

					//Default Images
					$default_employer_avatar   = $unyson_option[ 'default_employer_avatar'];
					$default_freelancer_avatar = $unyson_option[ 'default_freelancer_avatar'];
					$main_logo = $unyson_option[ 'main_logo'];
					$nrf_found = $unyson_option[ 'nrf_found'];
					Redux::set_option( $opt_name, 'defaul_employers_profile', array(
						'url' => isset($default_employer_avatar['url']) && !empty($default_employer_avatar['url']) ? $default_employer_avatar['url'] : '',
						'id'  => isset($default_employer_avatar['attachment_id']) && !empty($default_employer_avatar['attachment_id']) ? $default_employer_avatar['attachment_id'] : ''
					));
					Redux::set_option( $opt_name, 'defaul_freelancers_profile', array(
						'url' => isset($default_freelancer_avatar['url']) && !empty($default_freelancer_avatar['url']) ? $default_freelancer_avatar['url'] : '',
						'id'  => isset($default_freelancer_avatar['attachment_id']) && !empty($default_freelancer_avatar['attachment_id']) ? $default_freelancer_avatar['attachment_id'] : ''
					));
					Redux::set_option( $opt_name, 'defaul_site_logo', array(
						'url' => isset($main_logo['url']) && !empty($main_logo['url']) ? $main_logo['url'] : '',
						'id'  => isset($main_logo['attachment_id']) && !empty($main_logo['attachment_id']) ? $main_logo['attachment_id'] : ''
					));
					Redux::set_option( $opt_name, 'invoice_logo', array(
						'url' => isset($main_logo['url']) && !empty($main_logo['url']) ? $main_logo['url'] : '',
						'id'  => isset($main_logo['attachment_id']) && !empty($main_logo['attachment_id']) ? $main_logo['attachment_id'] : ''
					));
					Redux::set_option( $opt_name, 'empty_listing_image', array(
						'url' => isset($nrf_found['url']) && !empty($nrf_found['url']) ? $nrf_found['url'] : '',
						'id'  => isset($nrf_found['attachment_id']) && !empty($nrf_found['attachment_id']) ? $nrf_found['attachment_id'] : ''
					));

					//Header
					$logo_width = $unyson_option['logo_x'];
					$copyright      = fw_get_db_settings_option('copyright');
					Redux::set_option( $opt_name, 'main_logo', array(
						'url' => isset($main_logo['url']) && !empty($main_logo['url']) ? $main_logo['url'] : '',
						'id'  => isset($main_logo['attachment_id']) && !empty($main_logo['attachment_id']) ? $main_logo['attachment_id'] : ''
					));
					Redux::set_option( $opt_name, 'transparent_logo', array(
						'url' => isset($main_logo['url']) && !empty($main_logo['url']) ? $main_logo['url'] : '',
						'id'  => isset($main_logo['attachment_id']) && !empty($main_logo['attachment_id']) ? $main_logo['attachment_id'] : ''
					));
					Redux::set_option( $opt_name, 'logo_wide', isset($logo_width) && !empty($logo_width) ? $logo_width : 125 );
					Redux::set_option( $opt_name, 'logo_wide', isset($copyright) && !empty($copyright) ? $copyright : '' );

					//Directory
					$system_access = $unyson_option['application_access'];
					$service_type = $system_access === 'service_base' ? 'task_based' : ($system_access === 'job_base' ? 'project_based' : 'both');
					Redux::set_option( $opt_name, 'application_access', $service_type );

					//Google map API key
					$google_key = $unyson_option['google_key'];
					$google_key_switch = (isset( $google_key ) && !empty($google_key))  ? 1 : 0;
					Redux::set_option( $opt_name, 'enable_zipcode', $google_key_switch );
					Redux::set_option( $opt_name, 'google_map', ($google_key_switch == 1) ? $google_key : ''  );

					//Google Connect/login
					$google_connect = $unyson_option['enable_google_connect'];
					$google_connect = isset( $google_connect ) && $google_connect == 'enable'  ? 1 : 0;
					Redux::set_option( $opt_name, 'enable_social_connect', $google_connect ); //set switch

					$gclient_id			= $unyson_option['client_id'];
					Redux::set_option( $opt_name, 'google_client_id', (isset($gclient_id) && !empty($gclient_id)) ? $gclient_id : '' );
					$gclient_secret	    = $unyson_option['client_secret'];
					Redux::set_option( $opt_name, 'google_client_secret', (isset($gclient_secret) && !empty($gclient_secret)) ? $gclient_secret : '' );

					//Color settings
					$color_base = $unyson_option['color_settings'];
					if (isset($color_base['color_settings']['gadget']) && $color_base['gadget'] === 'custom') {
						if (!empty($color_base['custom']['primary_color']) || !empty($color_base['custom']['secondary_color'])){
							$primary_color 	 = !empty( $color_base['custom']['primary_color'] ) ? $color_base['custom']['primary_color'] : '';
							$secondary_color = !empty( $color_base['custom']['secondary_color'] ) ? $color_base['custom']['secondary_color'] : '';
						}

						if(!empty($primary_color)){
							Redux::set_option( $opt_name, 'wr_primary_color', $primary_color );
						}

						if(!empty($secondary_color)){
							Redux::set_option( $opt_name, 'wr_secondary_color', $secondary_color );
						}
					}

					$response['type'] = 'success';
					$response['message'] = __('Unyson to redux fields has been migrated','workreap');

				}

			}

			if(empty($response)){
				$response['type'] = 'failure';
				$response['message'] = __('No unyson fields found for migration!','workreap');
			}

			$response['next_step'] = 3;
			$response['next_message'] = __('Dashboard templates import','workreap');

			wp_send_json($response);

		}

		public function migrate_users(){

			$response = [];
			// freelancer packages updates
			$packages = get_posts(array(
				'post_type' 		=> array('product'),
				'post_status' 		=> 'any',
				'posts_per_page' 	=> -1,
				'fields' 			=> 'ids',
				'meta_key'       	=> 'package_type',
				'meta_value'     	=> 'freelancer',
			));
			
			if(!empty($packages)){
				foreach($packages as $pkg_key => $package_id){
					$featured_tasks_allowed	= get_post_meta($package_id, 'wt_featured_services',true);
					$featured_tasks_allowed	= !empty($featured_tasks_allowed) ? $featured_tasks_allowed : 0;
					update_post_meta( $package_id, 'featured_tasks_allowed',$featured_tasks_allowed );
			
					$number_tasks_allowed	= get_post_meta($package_id, 'wt_services',true);
					$number_tasks_allowed	= !empty($number_tasks_allowed) ? $number_tasks_allowed : 0;
					update_post_meta( $package_id, 'number_tasks_allowed',$number_tasks_allowed );
			
					$featured_projects_duration	= get_post_meta($package_id, 'wt_featured_job_duration',true);
					$featured_projects_duration	= !empty($featured_projects_duration) ? $featured_projects_duration : 0;
					update_post_meta( $package_id, 'featured_projects_duration',$featured_projects_duration );
			
					$package_type		= get_post_meta($package_id, 'wt_duration_type',true);
					$package_type_val	= "";
					$package_duration	= 0;
					$featured_tasks_duration	= 0;
					if(!empty($package_type)){
						if($package_type==='yearly' || $package_type==='2yearly' || $package_type==='3yearly' || $package_type==='4yearly' || $package_type==='5yearly'){
							$package_type_val	= 'year';
							if($package_type==='yearly'){
								$package_duration	= 1;
								$featured_tasks_duration = 360;
							} else if($package_type==='2yearly'){
								$package_duration	= 2;
								$featured_tasks_duration = 360*2;
							}else if($package_type==='3yearly'){
								$package_duration	= 3;
								$featured_tasks_duration = 360*3;
							}else if($package_type==='4yearly'){
								$package_duration	= 4;
								$featured_tasks_duration = 360*4;
							}else if($package_type==='5yearly'){
								$package_duration	= 5;
								$featured_tasks_duration = 360*5;
							}
						} else if($package_type==='weekly' || $package_type==='biweekly' || $package_type==='bimonthly' || $package_type==='quarterly' ){
							$package_type_val	= 'days';
							if($package_type==='weekly'){
								$package_duration	= 7;
							} else if($package_type==='biweekly'){
								$package_duration	= 14;
							}else if($package_type==='bimonthly'){
								$package_duration	= 60;
							}else if($package_type==='quarterly'){
								$package_duration	= 90;
							}
							$featured_tasks_duration = $package_duration;
						} else if($package_type==='monthly' || $package_type==='biannually'){
							$package_type_val	= 'month';
							if($package_type==='monthly'){
								$package_duration	= 1;
								$featured_tasks_duration = 30;
							} else if($package_type==='biannually'){
								$package_duration	= 6;
								$featured_tasks_duration = 30*2;
							}
						}
					}
					update_post_meta( $package_id, 'featured_tasks_duration',$featured_tasks_duration );
					update_post_meta( $package_id, 'package_duration',$package_duration );
					wp_set_object_terms($package_id, 'packages', 'product_type');
					update_post_meta( $package_id, 'package_type',$package_type_val );
					update_post_meta( $package_id, 'task_plans_allowed','yes' );
				}
			}
			// employer packages updates
			$emp_packages = get_posts(array(
				'post_type' 		=> array('product'),
				'post_status' 		=> 'any',
				'posts_per_page' 	=> -1,
				'fields' 			=> 'ids',
				'meta_key'       	=> 'package_type',
				'meta_value'     	=> 'employer',
			));
			
			if(!empty($emp_packages)){
				foreach($emp_packages as $pkg_key => $package_id){
					$number_projects_allowed	= get_post_meta($package_id, 'wt_jobs',true);
					$number_projects_allowed	= !empty($number_projects_allowed) ? $number_projects_allowed : 0;
					update_post_meta( $package_id, 'number_projects_allowed',$number_projects_allowed );
			
					$featured_projects_allowed	= get_post_meta($package_id, 'wt_featured_jobs',true);
					$featured_projects_allowed	= !empty($featured_projects_allowed) ? $featured_projects_allowed : 0;
					update_post_meta( $package_id, 'featured_projects_allowed',$featured_projects_allowed );
			
					$featured_projects_duration	= get_post_meta($package_id, 'wt_featured_job_duration',true);
					$featured_projects_duration	= !empty($featured_projects_duration) ? $featured_projects_duration : 0;
					update_post_meta( $package_id, 'featured_projects_duration',$featured_projects_duration );
			
					$package_type		= get_post_meta($package_id, 'wt_duration_type',true);
					$package_type_val	= "";
					$package_duration	= 0;
					$featured_tasks_duration	= 0;
					if(!empty($package_type)){
						if($package_type==='yearly' || $package_type==='2yearly' || $package_type==='3yearly' || $package_type==='4yearly' || $package_type==='5yearly'){
							$package_type_val	= 'year';
							if($package_type==='yearly'){
								$package_duration	= 1;
								$featured_tasks_duration = 360;
							} else if($package_type==='2yearly'){
								$package_duration	= 2;
								$featured_tasks_duration = 360*2;
							}else if($package_type==='3yearly'){
								$package_duration	= 3;
								$featured_tasks_duration = 360*3;
							}else if($package_type==='4yearly'){
								$package_duration	= 4;
								$featured_tasks_duration = 360*4;
							}else if($package_type==='5yearly'){
								$package_duration	= 5;
								$featured_tasks_duration = 360*5;
							}
						} else if($package_type==='weekly' || $package_type==='biweekly' || $package_type==='bimonthly' || $package_type==='quarterly' ){
							$package_type_val	= 'days';
							if($package_type==='weekly'){
								$package_duration	= 7;
							} else if($package_type==='biweekly'){
								$package_duration	= 14;
							}else if($package_type==='bimonthly'){
								$package_duration	= 60;
							}else if($package_type==='quarterly'){
								$package_duration	= 90;
							}
							$featured_tasks_duration = $package_duration;
						} else if($package_type==='monthly' || $package_type==='biannually'){
							$package_type_val	= 'month';
							if($package_type==='monthly'){
								$package_duration	= 1;
								$featured_tasks_duration = 30;
							} else if($package_type==='biannually'){
								$package_duration	= 6;
								$featured_tasks_duration = 30*2;
							}
						}
					}
					update_post_meta( $package_id, 'featured_tasks_duration',$featured_tasks_duration );
					update_post_meta( $package_id, 'package_duration',$package_duration );
					wp_set_object_terms($package_id, 'employer_packages', 'product_type');
					update_post_meta( $package_id, 'package_type',$package_type_val );
					update_post_meta( $package_id, 'task_plans_allowed','yes' );
				}
			}

			//Freelancers
			$freelancers = get_posts(array(
				'post_type' => 'freelancers',
				'post_status' => 'any',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key' => '_linked_profile',
						'compare' => 'EXISTS',
					),
				),
			));

			foreach ($freelancers as $freelancer) {
				$id = $freelancer->ID;
				$linked_profile = get_post_meta($id,'_linked_profile',true);
				$identity_verified = get_post_meta($id,'identity_verified',true);
				update_post_meta($id,'_is_verified',$identity_verified ? 'yes' : 'no');

				$user = get_user_by('ID',$linked_profile);
				if($user){
					update_user_meta($user->ID,'login_type','buyers');
					update_user_meta($user->ID,'_user_type','freelancers');
					update_user_meta($user->ID,'_linked_profile',$id);
					update_user_meta($user->ID,'_is_verified',$identity_verified ? 'yes' : 'no');
					update_user_meta($user->ID,'identity_verified',$identity_verified);
					$payrols	= get_user_meta($user->ID, 'payrols',true);
					$workreap_payout_method	= array();
					if(!empty($payrols['type'])){
						$workreap_payout_method[$payrols['type']]	= $payrols;
						update_user_meta($user->ID,'workreap_payout_method',$workreap_payout_method);
					}
					wp_update_user(array('ID' => $user->ID,'role' => 'subscriber'));
				}
			}

			//Employers
			$employees = get_posts(array(
				'post_type' => 'employers',
				'post_status' => 'any',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key' => '_linked_profile',
						'compare' => 'EXISTS',
					),
				),
			));

			foreach ($employees as $employee) {
				$id = $employee->ID;
				$linked_profile = get_post_meta($id,'_linked_profile',true);
				$identity_verified = get_post_meta($id,'identity_verified',true);
				update_post_meta($id,'_is_verified',$identity_verified ? 'yes' : 'no');

				$user = get_user_by('ID',$linked_profile);
				if($user){
					update_user_meta($user->ID,'_linked_profile_employer',$id);
					update_user_meta($user->ID,'_linked_profile_buyer',$id);
					update_user_meta($user->ID,'_user_type','employers');
					update_user_meta($user->ID,'login_type','sellers');
					update_user_meta($user->ID,'_is_verified',$identity_verified ? 'yes' : 'no');
					update_user_meta($user->ID,'identity_verified',$identity_verified);
					wp_update_user(array('ID' => $user->ID,'role' => 'subscriber'));
				}
			}

			$response['type'] = 'success';
			$response['next_step'] = 5;
			$response['next_message'] = __('Updating profiles data','workreap');

			wp_send_json($response);

		}

		public function migrate_profiles_data(){

			$response = [];

			//Employers
			$profiles = get_posts(array(
				'post_type' => array('freelancers'),
				'post_status' => 'any',
				'posts_per_page' => -1,
			));

			if (class_exists('WooCommerce')) {
				$countries_obj   	= new WC_Countries();
				$countries = $countries_obj->get_allowed_countries();
				$countries_symbol = array_keys($countries);
				$countries_name = array_values($countries);
			}

			foreach ($profiles as $profile){

				$profile_id = $profile->ID;
				$title = get_the_title($profile->ID);
				$full_name = explode(' ',$title);
				$first_name = isset($full_name[0]) && !empty($full_name[0]) ? $full_name[0] : $full_name;
				$last_name = isset($full_name[1]) && !empty($full_name[1]) ? $full_name[1] : '';
				$tagline = get_post_meta($profile_id,'_tag_line',true);
				
				$fw_options = get_post_meta($profile_id,'fw_options',true);
				$english_level = get_post_meta($profile_id,'_english_level',true);
				$education = !empty($fw_options['education']) ? $fw_options['education'] : array();
				$experience = !empty($fw_options['experience']) ? $fw_options['experience'] : array();
				$wr_post_meta  = get_post_meta($profile_id,'wr_post_meta',true);
				$wr_post_meta = isset($wr_post_meta) && !empty($wr_post_meta) ? $wr_post_meta : [];

				$location  = get_post_meta($profile_id,'location',true);
				$location = isset($location) && !empty($location) ? $location : [];
				$education_arry	= array();
				if( !empty($education)){
					foreach($education as $key=>$val){
						$education_data	= $val;
						if(!empty($education_data['startdate'])){
							$education_data['start_date']	= $education_data['startdate'];
							unset($education_data['startdate']);
						}
						if(!empty($education_data['enddate'])){
							$education_data['end_date']	= $education_data['enddate'];
							unset($education_data['enddate']);
						}
						$education_arry[$key]= $education_data;
					}
				}

				$experience_arry	= array();
				if( !empty($experience)){
					foreach($experience as $key=>$val){
						$experience_data	= $val;
						if(!empty($experience_data['startdate'])){
							$experience_data['start_date']	= $experience_data['startdate'];
							unset($experience_data['startdate']);
						}
						if(!empty($experience_data['enddate'])){
							$experience_data['end_date']	= $experience_data['enddate'];
							unset($experience_data['enddate']);
						}
						if(!empty($experience_data['title'])){
							$experience_data['job_title']	= $experience_data['title'];
							unset($experience_data['title']);
						}
						if(!empty($experience_data['institute'])){
							$experience_data['company']	= $experience_data['institute'];
							unset($experience_data['institute']);
						}
						$experience_arry[$key]= $experience_data;
					}
				}
				$wr_post_meta['first_name'] = $first_name;
				$wr_post_meta['last_name'] = $last_name;
				$wr_post_meta['tagline'] = isset($tagline) && !empty($tagline) ? $tagline : '';
				$wr_post_meta['education'] = isset($education_arry) && !empty($education_arry) ? $education_arry : [];
				$wr_post_meta['experience'] = isset($experience_arry) && !empty($experience_arry) ? $experience_arry : [];

				$hourly_rate = isset($fw_options['_perhour_rate']) && !empty($fw_options['_perhour_rate']) ? $fw_options['_perhour_rate'] : '';
				$country = isset($fw_options['country'][0]) && isset($countries_symbol[$fw_options['country'][0]]) ? $countries_symbol[$fw_options['country'][0]] : '';
				$english_level_term = isset($english_level) && !empty($english_level) ? get_term_by('slug', $english_level, 'english_level') : '';

				$location['address'] = isset($fw_options['address']) && !empty($fw_options['address']) ? $fw_options['address'] : '';
				$location['lng'] = isset($fw_options['longitude']) && !empty($fw_options['longitude']) ? $fw_options['longitude'] : '';
				$location['lat'] = isset($fw_options['latitude']) && !empty($fw_options['latitude']) ? $fw_options['latitude'] : '';
				$location['locality']['long_name'] = isset($fw_options['country'][0]) && isset($countries_name[$fw_options['country'][0]]) ? $countries_name[$fw_options['country'][0]] : '';
				$location['locality']['short_name'] = isset($fw_options['country'][0]) && isset($countries_symbol[$fw_options['country'][0]]) ? $countries_symbol[$fw_options['country'][0]] : '';
				$location['country']['long_name'] = isset($fw_options['country'][0]) && isset($countries_name[$fw_options['country'][0]]) ? $countries_name[$fw_options['country'][0]] : '';
				$location['country']['short_name'] = isset($fw_options['country'][0]) && isset($countries_symbol[$fw_options['country'][0]]) ? $countries_symbol[$fw_options['country'][0]] : '';

				update_post_meta($profile->ID,'wr_post_meta',$wr_post_meta);
				update_post_meta($profile->ID,'wr_hourly_rate',$hourly_rate);
				update_post_meta($profile->ID,'country',$country);
				update_post_meta($profile->ID,'location',$location);

				if(isset($english_level_term->term_id) && !empty($english_level_term->term_id)){
					wp_set_object_terms($profile_id, $english_level_term->term_id, 'english_level', true);
				}

			}

			$response['type'] = 'success';
			$response['next_step'] = 6;
			$response['next_message'] = __('Migrating services and gigs','workreap');

			wp_send_json($response);

		}

		public function migrate_services(){

			global $wpdb;

			$response = [];

			if (class_exists('WooCommerce')) {
				$countries_obj   	= new WC_Countries();
				$countries = $countries_obj->get_allowed_countries();
				$countries_symbol = array_keys($countries);
				$countries_name = array_values($countries);
			}

			//Services Delivery Terms
			$service_delivery_args = array(
				'taxonomy' => 'delivery',
				'hide_empty' => false,
			);

			$service_delivery_query = new WP_Term_Query($service_delivery_args);
			$service_delivery = $service_delivery_query->get_terms();

			if(isset($service_delivery) && !empty($service_delivery)){
				foreach ($service_delivery as $delivery){
					$term_args = array(
						'taxonomy' => 'delivery_time',
						'hide_empty' => false,
						'number' => 1,
						'meta_query' => array(
							array(
								'key' => '_wr_linked_old_service_term_id',
								'value' => $delivery->term_id,
							),
						),
					);
					$term_query = new WP_Term_Query($term_args);
					$terms = $term_query->get_terms();
					$term = !empty($terms) ? reset( $terms ) : [];
					if(empty($term)){
						$existing_term = term_exists($delivery->slug, 'delivery_time');
						if ($existing_term !== 0 && $existing_term !== null) {
							$term_id = $existing_term['term_id'];
						}else{
							$post_term = wp_insert_term($delivery->name, 'delivery_time', array(
								'description' => $delivery->description,
								'slug' => $delivery->slug,
							));
							$term_id = $post_term['term_id'];
						}
						if ( ! empty( $term_id ) && ! is_wp_error( $term_id ) ) {
							$term_slug = get_term_field('slug', $term_id, 'delivery_time');
							$term_numbers = preg_replace('/[^0-9]/', '', $term_slug);
							$term_numbers = floatval($term_numbers);
							$term_numbers = abs($term_numbers);
							$term_days = isset($term_numbers) && !empty($term_numbers) ? intval($term_numbers) : 2;
							update_term_meta($term_id, '_wr_linked_old_service_term_id', $delivery->term_id);
							update_term_meta($term_id, 'days', $term_days);
						}
					}
				}
			}

			//Services Category Terms
			$service_category_args = array(
				'taxonomy' => 'service_categories',
				'hide_empty' => false,
			);

			$service_category_query = new WP_Term_Query($service_category_args);
			$service_category = $service_category_query->get_terms();

			if(isset($service_category) && !empty($service_category)){
				//migrate services categories
				foreach ($service_category as $category){
					$term_args = array(
						'taxonomy' => 'product_cat',
						'hide_empty' => false,
						'number' => 1,
						'meta_query' => array(
							array(
								'key' => '_wr_linked_old_service_term_id',
								'value' => $category->term_id,
							),
						),
					);
					$term_query = new WP_Term_Query($term_args);
					$terms = $term_query->get_terms();
					$term = !empty($terms) ? reset( $terms ) : [];
					if(empty($term)){
						$existing_term = term_exists($category->slug, 'product_cat');
						if ($existing_term !== 0 && $existing_term !== null) {
							$term_id = $existing_term['term_id'];
						}else{
							$post_term = wp_insert_term($category->name, 'product_cat', array(
								'description' => $category->description,
								'slug' => $category->slug,
							));
							$term_id = $post_term['term_id'];
						}
						if ( ! empty( $term_id ) && ! is_wp_error( $term_id ) ) {
							update_term_meta($term_id, '_wr_linked_old_service_term_id', $category->term_id);
						}

					}
				}
				//update services category parent child relation
				foreach ($service_category as $category) {
					if(isset($category->parent) && $category->parent > 0){

						$term_args = array(
							'taxonomy' => 'product_cat',
							'hide_empty' => false,
							'number' => 1,
							'meta_query' => array(
								array(
									'key' => '_wr_linked_old_service_term_id',
									'value' => $category->term_id,
								),
							),
						);

						$term_query = new WP_Term_Query($term_args);
						$terms = $term_query->get_terms();
						$term = !empty($terms) ? reset( $terms ) : [];

						$parent_term_args = array(
							'taxonomy' => 'product_cat',
							'hide_empty' => false,
							'number' => 1,
							'meta_query' => array(
								array(
									'key' => '_wr_linked_old_service_term_id',
									'value' => $category->parent,
								),
							),
						);

						$parent_term_query = new WP_Term_Query($parent_term_args);
						$parent_terms = $parent_term_query->get_terms();
						$parent_term = !empty($parent_terms) ? reset( $parent_terms ) : [];

						if(!empty($term) && !empty($parent_term) ){
							$term_id = $term->term_id;
							$parent_term_id = $parent_term->term_id;
							wp_update_term($term_id, 'product_cat', array(
								'parent' => $parent_term_id,
							));
						}
					}

				}
			}

			//Addon Services
			$addons_services = get_posts(array(
				'post_type' => array('addons-services'),
				'post_status' => 'any',
				'posts_per_page' => -1,
				'fields' => 'ids',
			));
			foreach ($addons_services as $addon_service){
				$addon_service_id = $addon_service;
				$addon_service_title = get_the_title($addon_service_id);
				$addon_service_content = get_the_excerpt($addon_service_id);
				$addon_service_author_id = get_post_field('post_author', $addon_service_id);
				$addon_service_fw_options = get_post_meta($addon_service_id,'fw_options',true);
				$addon_service_price = isset($addon_service_fw_options['price']) & !empty($addon_service_fw_options['price']) ? $addon_service_fw_options['price'] : 0;

				$addon_service_products = wc_get_products(array(
					'limit' => 1,
					'status' => 'any',
					'type' => 'subtasks',
					'return' => 'ids',
					'meta_key' => '_wr_linked_old_product',
					'meta_value' => $addon_service_id,
				));

				$addon_service_product = !empty($addon_service_products) ? reset( $addon_service_products ) : [];
				if(empty($addon_service_product)){
					$addon_service_product_id = wp_insert_post(array(
						'post_title' => $addon_service_title,
						'post_content' => $addon_service_content,
						'post_author'   => $addon_service_author_id,
						'post_status' => get_post_status($addon_service_id),
						'post_type' => 'product',
					));
					if ($addon_service_product_id) {
						wp_set_object_terms($addon_service_product_id, 'subtasks', 'product_type');
						update_post_meta($addon_service_product_id, '_wr_linked_old_product', $addon_service_id);
						update_post_meta($addon_service_product_id, '_price', $addon_service_price);
						update_post_meta($addon_service_product_id, '_regular_price', $addon_service_price);
						update_post_meta($addon_service_product_id, '_virtual', 'yes');
						wc_delete_product_transients($addon_service_product_id);
					}
				}

			}

			//Services
			$services = get_posts(array(
				'post_type' => array('micro-services'),
				'post_status' => 'any',
				'posts_per_page' => -1,
				'fields' => 'ids',
			));

			foreach ($services as $service){

				$service_id = $service;
				$service_title = get_the_title($service_id);
				$service_content = get_post_field('post_content', $service_id);
				$service_author_id = get_post_field('post_author', $service_id);

				$service_products = wc_get_products(array(
					'limit' => 1,
					'status' => 'any',
					'type' => 'tasks',
					'return' => 'ids',
					'meta_key' => '_wr_linked_old_product',
					'meta_value' => $service_id,
				));
				
				$service_product = !empty($service_products) ? reset( $service_products ) : [];

				if(empty($service_product)){

					$service_product_id = wp_insert_post(array(
						'post_title' => $service_title,
						'post_content' => $service_content,
						'post_author'   => $service_author_id,
						'post_status' => get_post_status($service_id),
						'post_type' => 'product',
					));

					if ($service_product_id) {

						$service_addons = get_post_meta($service_id,'_addons',true);
						$service_views = get_post_meta($service_id,'services_views',true);
						$service_fw_options = get_post_meta($service_id,'fw_options',true);
						$service_featured = isset($service_fw_options['featured_post']) ? 'yes' : 'no';
						$service_downloadable = isset($service_fw_options['downloadable']) ? $service_fw_options['downloadable'] : 'no';
						$service_price = get_post_meta($service_id,'_price',true);
						$service_country = isset($service_fw_options['country'][0]) && !empty($service_fw_options['country'][0]) ? intval($service_fw_options['country'][0]) : '';
						$service_country = isset($countries_symbol[$service_country]) && !empty($countries_symbol[$service_country]) ? $countries_symbol[$service_country] : '';
						$service_gallery = isset($service_fw_options['docs']) && !empty($service_fw_options['docs']) ? $service_fw_options['docs'] : [];
						$service_videos = isset($service_fw_options['videos'][0]) && !empty($service_fw_options['videos'][0]) ? $service_fw_options['videos'][0] : '';
						$service_faqs = isset($service_fw_options['faq']) && !empty($service_fw_options['faq']) ? $service_fw_options['faq'] : [];
						$service_longitude = isset($service_fw_options['longitude']) && !empty($service_fw_options['longitude']) ? $service_fw_options['longitude'] : '';
						$service_latitude = isset($service_fw_options['latitude']) && !empty($service_fw_options['latitude']) ? $service_fw_options['latitude'] : '';

						$service_faqs = array_map(function($item) {
							return array(
								'question' => $item['faq_question'],
								'answer' => $item['faq_answer']
							);
						}, $service_faqs);

						$delivery_new_term_data = [];
						$delivery_old_term_data = $wpdb->get_results(
							$wpdb->prepare("SELECT t.term_id
                    FROM $wpdb->terms AS t
                    INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tt.taxonomy = %s
                    AND tr.object_id = %d","delivery", $service_id
							)
						);
						foreach ($delivery_old_term_data as $term_data){
							$term_args = array(
								'taxonomy' => 'delivery_time',
								'hide_empty' => false,
								'number' => 1,
								'meta_query' => array(
									array(
										'key' => '_wr_linked_old_service_term_id',
										'value' => $term_data->term_id,
									),
								),
							);
							$term_query = new WP_Term_Query($term_args);
							$terms = $term_query->get_terms();
							$term = !empty($terms) ? reset( $terms ) : [];
							if($term){
								$delivery_new_term_data[] = $term->term_id;
							}
						}

						$categories_new_term_data = [];
						$categories_old_term_data = $wpdb->get_results(
							$wpdb->prepare("SELECT t.term_id
                    FROM $wpdb->terms AS t
                    INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tt.taxonomy = %s
                    AND tr.object_id = %d","service_categories", $service_id
							)
						);
						foreach ($categories_old_term_data as $term_data){
							$term_args = array(
								'taxonomy' => 'product_cat',
								'hide_empty' => false,
								'number' => 1,
								'meta_query' => array(
									array(
										'key' => '_wr_linked_old_service_term_id',
										'value' => $term_data->term_id,
									),
								),
							);
							$term_query = new WP_Term_Query($term_args);
							$terms = $term_query->get_terms();
							$term = !empty($terms) ? reset( $terms ) : [];
							if($term){
								$categories_new_term_data[] = $term->term_id;
							}
						}

						//Addons Tasks
						$service_new_addons = [];
						$subtasks_addons_list	= [];
						foreach ($service_addons as $service_addon){
							$service_addons_products = wc_get_products(array(
								'limit' => 1,
								'status' => 'any',
								'type' => 'subtasks',
								'return' => 'ids',
								'meta_key' => '_wr_linked_old_product',
								'meta_value' => $service_addon,
							));
							$service_addons_products = !empty($service_addons_products) ? reset( $service_addons_products ) : [];
							if(!empty($service_addons_products)){
								$service_new_addons[] = $service_addons_products;
								$subtasks_addons_list[$service_addon]	= $service_addons_products;
							}
						}

						$workreap_product_plans = get_post_meta($service_product_id,'workreap_product_plans',true);
						$workreap_product_plans = isset($workreap_product_plans) && !empty($workreap_product_plans) ? $workreap_product_plans : [];
						$workreap_product_plans['basic']['title'] = __('Basic','workreap');
						$workreap_product_plans['basic']['description'] = '';
						$workreap_product_plans['basic']['price'] = $service_price;
						$workreap_product_plans['basic']['delivery_time'] = isset($delivery_new_term_data[0]) ? $delivery_new_term_data[0] : '';
						$workreap_product_plans['basic']['featured_package'] = $service_featured;

						$wr_service_meta = get_post_meta($service_product_id,'wr_service_meta',true);
						$wr_service_meta = isset($wr_service_meta) && !empty($wr_service_meta) ? $wr_service_meta : [];
						$wr_service_meta['country'] = $service_country;
						$wr_service_meta['latitude'] = $service_latitude;
						$wr_service_meta['longitude'] = $service_longitude;
						$wr_service_meta['categories'] = [];
						$wr_service_meta['category'] = [];
						$wr_service_meta['subcategory'] = [];
						$wr_service_meta['product_tag'] = [];

						foreach ($categories_new_term_data as $c => $category_new_term_data){
							$new_term_data = get_term_by('ID',$category_new_term_data,'product_cat');
							$wr_service_meta['categories'][$new_term_data->slug] = $new_term_data->name;
							if($new_term_data->parent == 0){
								$wr_service_meta['category'][$new_term_data->slug] = $new_term_data->name;
							}
						}

						wp_set_object_terms($service_product_id, 'tasks', 'product_type');
						update_post_meta($service_product_id, '_price', $service_price);
						update_post_meta($service_product_id, '_regular_price', $service_price);
						update_post_meta($service_product_id, '_min_price', $service_price);
						update_post_meta($service_product_id, '_max_price', intval($service_price) + 1000);
						update_post_meta($service_product_id, '_wr_linked_old_product', $service_id);
						wp_set_post_terms($service_product_id, $delivery_new_term_data, 'delivery_time');
						wp_set_post_terms($service_product_id, $categories_new_term_data, 'product_cat');
						update_post_meta($service_product_id, '_featured_task', $service_featured);
						update_post_meta($service_product_id, '_downloadable', $service_downloadable);
						update_post_meta($service_product_id, '_product_video', $service_videos);
						update_post_meta($service_product_id, 'workreap_product_plans', $workreap_product_plans);
						update_post_meta($service_product_id, 'workreap_product_subtasks', $service_new_addons);
						update_post_meta($service_product_id, 'workreap_service_views', $service_views ? $service_views : 0);
						update_post_meta($service_product_id, '_country', $service_country);
						update_post_meta($service_product_id, 'workreap_service_faqs', $service_faqs);
						update_post_meta($service_product_id, 'wr_service_meta', $wr_service_meta);

						if(!empty($service_downloadable) && $service_downloadable === 'yes' ){
							
						}
						if($service_featured === 'yes'){
							update_post_meta($service_product_id, '_featured_package', 'basic');
						}
						$attachments_files	= array();
						if(isset($service_gallery) && !empty($service_gallery)){
							if(!empty($service_gallery)){
								update_post_meta($service_product_id, '_product_attachments', $service_gallery);
							}
							$service_gallery_ids = array_map(function($image) {
								return $image["attachment_id"];
							}, $service_gallery);
							if(isset($service_gallery_ids[0])){
								set_post_thumbnail($service_product_id, $service_gallery_ids[0]);
								array_shift($service_gallery_ids);
							}
							if(!empty($service_gallery_ids)){
								update_post_meta($service_product_id, '_product_image_gallery', implode(',', $service_gallery_ids));
							}
						}
						$service_orders = get_posts(
							array(
								'meta_key'     => '_service_id',
								'meta_value'   => $service_id,
								'post_type'    => 'services-orders',
								'numberposts'  => -1,
								'post_status' => 'any',
							)
						);
						
						
						if (!empty($service_orders)) {
							foreach ($service_orders as $service_order) {
								
								$addons              	= get_post_meta($service_order->ID, '_addons', true);
								$order_id            	= get_post_meta($service_order->ID, '_order_id', true);
								$service_author      	= get_post_meta($service_order->ID, '_service_author', true);
								$cus_woo_product_data 	= get_post_meta($order_id, 'cus_woo_product_data', true);

								$admin_shares       = get_post_meta($order_id, 'admin_shares', true);
								$employer_id        = get_post_meta($order_id, 'employer_id', true);
								$freelancer_id      = get_post_meta($order_id, 'freelancer_id', true);
								$freelancer_shares  = get_post_meta($order_id, 'freelancer_shares', true);
								
								$rating              	= get_post_meta($service_order->ID, '_hired_service_rating', true);
								$freelancer_user_id		= get_post_field('post_author',$freelancer_id);
								$employer_user_id		= get_post_field('post_author',$employer_id);
								$service_order_options  = get_post_meta($service_order->ID, 'fw_options', true);
								$review_date			= get_post_meta($order_id, '_review_date', true);
								
								if(!empty($order_id)){
									$order	= wc_get_order( $order_id );
									$order->remove_order_items();
								}
								if(!empty($service_product_id)){
									$order->add_product(get_product($service_product_id), 1);
								}
								$service_update						= array();
								$addons_array						= array();
								if (!empty($cus_woo_product_data['addons'])) {
									foreach ($cus_woo_product_data['addons'] as $cus_addon) {
										$cus_addon_id	= !empty($cus_addon['id']) ? intval($cus_addon['id']) : 0;
										$subtasks_prod = get_posts(array(
											'post_type' 		=> array('product'),
											'post_status' 		=> 'any',
											'posts_per_page' 	=> -1,
											'fields' 			=> 'ids',
											'meta_key'       	=> '_wr_linked_old_product',
											'meta_value'     	=> $cus_addon_id,
										));
										if( !empty($subtasks_prod[0])){
											$order->add_product(get_product($subtasks_prod[0]), 1);
											$addons_array[]					= $subtasks_prod[0];
											$new_woo_data['subtasks'][]    	= $subtasks_prod[0];
										}
									}
								}
								$order->calculate_totals();
								$order->save();

								$service_update['addons']			= $addons_array;
								$service_update['service_id']		= !empty($cus_woo_product_data['service_id']) ? $cus_woo_product_data['service_id'] : 0;
								$service_update['service_price']	= isset($cus_woo_product_data['service_price']) ? $cus_woo_product_data['service_price'] : 0;
								
								$order_details                     = array();
								$order_details['key']              = 'basic';
								$order_details['title']            = 'Basic';
								$order_details['description']      = '';
								$order_details['price']            = isset($cus_woo_product_data['price']) ? $cus_woo_product_data['price'] : '';
								$new_order_details                 = $order_details;

								$new_woo_data                      = array();
								$new_woo_data['task_id']           = $service_product_id;
								$new_woo_data['task']              = 'basic';
								$new_woo_data['payment_type']      = 'tasks';
								$new_woo_data['employer_id']       = $employer_id;
								$new_woo_data['freelancer_id']     = $freelancer_id;
								$new_woo_data['admin_shares']      = $admin_shares;
								$new_woo_data['freelancer_shares'] = $freelancer_shares;
								update_post_meta($order_id, 'update_workreap_data', $service_update);
								update_post_meta($order_id, 'order_details', $new_order_details);
								update_post_meta($order_id, 'task_product_id', $service_product_id);
								update_post_meta($order_id, 'cus_woo_product_data', $new_woo_data);
								update_post_meta($order_id, '_task_status', $service_order->post_status);
								update_post_meta($order_id, 'payment_type', 'tasks');
								
								$comments = get_comments(array(
									'post_id' => $service_order->ID,
								));
								if ($comments) {
									foreach ($comments as $comment) {
											$comment_array						= array();
											$comment_array['comment_ID'] 		= $comment->comment_ID;
											$comment_array['comment_type'] 		= 'activity_detail'; 
											$comment_array['comment_post_ID'] 	= $order_id; 
											wp_update_comment($comment_array);
									}
								}

								if(isset($rating) && get_post_status($service_order->ID) === 'completed' ){
									$review_date		= get_post_meta($service_order->ID, '_review_date', true);
									$emp_id				= get_post_field('post_author', $service_order->ID);
									$employer_user 		= get_user_by('id', $emp_id);
									$user_type          = 'employers';
        							$linked_profile     = workreap_get_linked_profile_id($emp_id, '', $user_type);
									$user_profiel_name  = workreap_get_username($linked_profile);
									$comment_id 		= wp_insert_comment(array(
										'comment_post_ID'      => $service_product_id,
										'comment_author'       => $user_profiel_name,
										'comment_author_email' => $employer_user->user_email,
										'comment_author_url'   => '',
										'comment_content'      => $service_order_options['feedback'],
										'comment_type'         => 'rating',
										'comment_parent'       => 0,
										'user_id'              => $emp_id,
										'comment_date'         => $review_date,//date('Y-m-d H:i:s',strtotime($review_date)),
										'comment_approved'     => 1,
									));
									update_comment_meta($comment_id, '_rating_title', '');
									update_comment_meta($comment_id, 'rating', intval($rating));
									update_comment_meta($comment_id, '_task_order', intval($order_id));
									update_comment_meta($comment_id, 'freelancer_id', intval($freelancer_user_id));
									update_comment_meta($comment_id, 'verified', 1);
									update_post_meta($order_id, '_rating_id', $comment_id);
									workreap_product_rating($service_product_id);
									
								}

								workreap_freelancer_rating($freelancer_user_id);
							}
						}
						
						wc_delete_product_transients($service_product_id);
					}

				}
			}

			$response['type'] = 'success';
			$response['next_step'] = 7;
			$response['next_message'] = __('Migrating projects','workreap');

			wp_send_json($response);

		}

		public function migrate_projects(){

			global $wpdb;

			$response = [];

			if (class_exists('WooCommerce')) {
				$countries_obj   	= new WC_Countries();
				$countries = $countries_obj->get_allowed_countries();
				$countries_symbol = array_keys($countries);
				$countries_name = array_values($countries);
			}

			//Project Category Terms
			$project_category_args = array(
				'taxonomy' => 'project_cat',
				'hide_empty' => false,
			);

			$project_category_query = new WP_Term_Query($project_category_args);
			$project_category = $project_category_query->get_terms();

			if(isset($project_category) && !empty($project_category)){
				//migrate projects categories
				foreach ($project_category as $category){
					$term_args = array(
						'taxonomy' => 'product_cat',
						'hide_empty' => false,
						'number' => 1,
						'meta_query' => array(
							array(
								'key' => '_wr_linked_old_project_term_id',
								'value' => $category->term_id,
							),
						),
					);
					$term_query = new WP_Term_Query($term_args);
					$terms = $term_query->get_terms();
					$term = !empty($terms) ? reset( $terms ) : [];
					if(empty($term)){
						$existing_term = term_exists($category->slug, 'product_cat');
						if ($existing_term !== 0 && $existing_term !== null) {
							$term_id = $existing_term['term_id'];
						}else{
							$post_term = wp_insert_term($category->name, 'product_cat', array(
								'description' => $category->description,
								'slug' => $category->slug,
							));
							$term_id = $post_term['term_id'];
						}
						if ( ! empty( $term_id ) && ! is_wp_error( $term_id ) ) {
							update_term_meta($term_id, '_wr_linked_old_project_term_id', $category->term_id);
						}

					}
				}
				//update projects category parent child relation
				foreach ($project_category as $category) {
					if(isset($category->parent) && $category->parent > 0){

						$term_args = array(
							'taxonomy' => 'product_cat',
							'hide_empty' => false,
							'number' => 1,
							'meta_query' => array(
								array(
									'key' => '_wr_linked_old_project_term_id',
									'value' => $category->term_id,
								),
							),
						);

						$term_query = new WP_Term_Query($term_args);
						$terms = $term_query->get_terms();
						$term = !empty($terms) ? reset( $terms ) : [];

						$parent_term_args = array(
							'taxonomy' => 'product_cat',
							'hide_empty' => false,
							'number' => 1,
							'meta_query' => array(
								array(
									'key' => '_wr_linked_old_project_term_id',
									'value' => $category->parent,
								),
							),
						);

						$parent_term_query = new WP_Term_Query($parent_term_args);
						$parent_terms = $parent_term_query->get_terms();
						$parent_term = !empty($parent_terms) ? reset( $parent_terms ) : [];

						if(!empty($term) && !empty($parent_term) ){
							$term_id = $term->term_id;
							$parent_term_id = $parent_term->term_id;
							wp_update_term($term_id, 'product_cat', array(
								'parent' => $parent_term_id,
							));
						}
					}

				}
			}

			//Project Duration Terms
			$project_duration_args = array(
				'taxonomy' => 'durations',
				'hide_empty' => false,
			);

			$project_duration_query = new WP_Term_Query($project_duration_args);
			$project_duration = $project_duration_query->get_terms();

			if(isset($project_duration) && !empty($project_duration)){
				//migrate projects durations
				foreach ($project_duration as $duration){
					$term_args = array(
						'taxonomy' => 'duration',
						'hide_empty' => false,
						'number' => 1,
						'meta_query' => array(
							array(
								'key' => '_wr_linked_old_project_term_id',
								'value' => $duration->term_id,
							),
						),
					);
					$term_query = new WP_Term_Query($term_args);
					$terms = $term_query->get_terms();
					$term = !empty($terms) ? reset( $terms ) : [];
					if(empty($term)){
						$existing_term = term_exists($duration->slug, 'duration');
						if ($existing_term !== 0 && $existing_term !== null) {
							$term_id = $existing_term['term_id'];
						}else{
							$post_term = wp_insert_term($duration->name, 'duration', array(
								'description' => $duration->description,
								'slug' => $duration->slug,
							));
							$term_id = $post_term['term_id'];
						}
						if ( ! empty( $term_id ) && ! is_wp_error( $term_id ) ) {
							update_term_meta($term_id, '_wr_linked_old_project_term_id', $duration->term_id);
						}

					}
				}
			}

			//Project Experience Terms
			$project_experience_args = array(
				'taxonomy' => 'project_experience',
				'hide_empty' => false,
			);

			$project_experience_query = new WP_Term_Query($project_experience_args);
			$project_experience = $project_experience_query->get_terms();

			if(isset($project_experience) && !empty($project_experience)){
				//migrate projects experiences
				foreach ($project_experience as $experience){
					$term_args = array(
						'taxonomy' => 'expertise_level',
						'hide_empty' => false,
						'number' => 1,
						'meta_query' => array(
							array(
								'key' => '_wr_linked_old_project_term_id',
								'value' => $experience->term_id,
							),
						),
					);
					$term_query = new WP_Term_Query($term_args);
					$terms = $term_query->get_terms();
					$term = !empty($terms) ? reset( $terms ) : [];
					if(empty($term)){
						$existing_term = term_exists($experience->slug, 'expertise_level');
						if ($existing_term !== 0 && $existing_term !== null) {
							$term_id = $existing_term['term_id'];
						}else{
							$post_term = wp_insert_term($experience->name, 'expertise_level', array(
								'description' => $experience->description,
								'slug' => $experience->slug,
							));
							$term_id = $post_term['term_id'];
						}
						if ( ! empty( $term_id ) && ! is_wp_error( $term_id ) ) {
							update_term_meta($term_id, '_wr_linked_old_project_term_id', $experience->term_id);
						}

					}
				}
			}

			//Projects
			$projects = get_posts(array(
				'post_type' => array('projects'),
				'post_status' => 'any',
				'posts_per_page' => -1,
				'fields' => 'ids',
			));

			foreach ($projects as $project) {

				$project_id = $project;
				$project_title = get_the_title($project_id);
				$project_content = get_post_field('post_content', $project_id);
				$project_author_id = get_post_field('post_author', $project_id);

				$project_products = wc_get_products(array(
					'limit' => 1,
					'status' => 'any',
					'type' => 'projects',
					'return' => 'ids',
					'meta_key' => '_wr_linked_old_product',
					'meta_value' => $project_id,
				));

				$project_product = !empty($project_products) ? reset( $project_products ) : [];

				if(empty($project_product)){

					$project_product_id = wp_insert_post(array(
						'post_title' => $project_title,
						'post_content' => $project_content,
						'post_author'   => $project_author_id,
						'post_status' => get_post_status($project_id),
						'post_type' => 'product',
					));

					if ($project_product_id) {

						//Categories
						$categories_new_term_data = [];
						$categories_old_term_data = $wpdb->get_results(
							$wpdb->prepare("SELECT t.term_id
                    FROM $wpdb->terms AS t
                    INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tt.taxonomy = %s
                    AND tr.object_id = %d","project_cat", $project_id
							)
						);
						foreach ($categories_old_term_data as $term_data){
							$term_args = array(
								'taxonomy' => 'product_cat',
								'hide_empty' => false,
								'number' => 1,
								'meta_query' => array(
									array(
										'key' => '_wr_linked_old_project_term_id',
										'value' => $term_data->term_id,
									),
								),
							);
							$term_query = new WP_Term_Query($term_args);
							$terms = $term_query->get_terms();
							$term = !empty($terms) ? reset( $terms ) : [];
							if($term){
								$categories_new_term_data[] = $term->term_id;
							}
						}

						//Project Experience
						$experience_new_term_data = [];
						$experience_old_term_data = $wpdb->get_results(
							$wpdb->prepare("SELECT t.term_id
                    FROM $wpdb->terms AS t
                    INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tt.taxonomy = %s
                    AND tr.object_id = %d","project_experience", $project_id
							)
						);
						foreach ($experience_old_term_data as $term_data){
							$term_args = array(
								'taxonomy' => 'expertise_level',
								'hide_empty' => false,
								'number' => 1,
								'meta_query' => array(
									array(
										'key' => '_wr_linked_old_project_term_id',
										'value' => $term_data->term_id,
									),
								),
							);
							$term_query = new WP_Term_Query($term_args);
							$terms = $term_query->get_terms();
							$term = !empty($terms) ? reset( $terms ) : [];
							if($term){
								$experience_new_term_data[] = $term->term_id;
							}
						}

						//Skills
						$skill_new_term_data = [];
						$skills_old_term_data = $wpdb->get_results(
							$wpdb->prepare("SELECT t.term_id
                    FROM $wpdb->terms AS t
                    INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tt.taxonomy = %s
                    AND tr.object_id = %d","skills", $project_id
							)
						);
						foreach ($skills_old_term_data as $term_data){
							if($term_data){
								$skill_new_term_data[] = $term_data->term_id;
							}
						}

						//Languages
						$language_new_term_data = [];
						$languages_old_term_data = $wpdb->get_results(
							$wpdb->prepare("SELECT t.term_id
                    FROM $wpdb->terms AS t
                    INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                    INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tt.taxonomy = %s
                    AND tr.object_id = %d","languages", $project_id
							)
						);
						foreach ($languages_old_term_data as $term_data){
							if($term_data){
								$language_new_term_data[] = $term_data->term_id;
							}
						}

						$project_duration = get_post_meta($project_id,'_project_duration',true);
						$project_type = get_post_meta($project_id,'_project_type',true);
						$project_job_type = get_post_meta($project_id,'_job_option',true);
						$project_latitude = get_post_meta($project_id,'_latitude',true);
						$project_longitude = get_post_meta($project_id,'_longitude',true);
						$project_price = get_post_meta($project_id,'_project_cost',true);
						$project_hourly_rate = get_post_meta($project_id,'_hourly_rate',true);
						$project_estimated_hours = get_post_meta($project_id,'_estimated_hours',true);
						$project_max_price = get_post_meta($project_id,'_max_price',true);
						$project_fw_options = get_post_meta($project_id,'fw_options',true);
						$project_milestone = get_post_meta($project_id,'_milestone',true);
						$project_milestone = isset($project_milestone) && $project_milestone === 'on' ? 'yes' : 'no';
						$project_country = isset($project_fw_options['country'][0]) && !empty($project_fw_options['country'][0]) ? intval($project_fw_options['country'][0]) : '';
						$project_country = isset($countries_symbol[$project_country]) && !empty($countries_symbol[$project_country]) ? $countries_symbol[$project_country] : '';
						$project_featured = isset($project_fw_options['featured_post']) ? 'yes' : 'no';
						$project_documents = isset($project_fw_options['project_documents']) ? $project_fw_options['project_documents'] : [];
						$project_duration_term = get_term_by('slug',$project_duration,'duration');
						$project_duration_term = isset($project_duration_term->term_id) ? $project_duration_term->term_id : [];
						$wr_project_meta = get_post_meta($project_product_id,'wr_project_meta',true);
						$wr_project_meta = isset($wr_project_meta) && !empty($wr_project_meta) ? $wr_project_meta : [];

						if(empty($project_price)){
							$project_price = $project_hourly_rate;
						}

						$wr_project_meta['project_type'] = $project_type;
						$wr_project_meta['name'] = $project_title;
						$wr_project_meta['is_milestone'] = $project_milestone;
						$wr_project_meta['min_price'] = $project_price;
						$wr_project_meta['max_price'] = $project_max_price;
						$wr_project_meta['max_hours'] = $project_estimated_hours;

						if(!empty($project_duration_term)){
							$project_duration_slug = get_post_meta($project_id,'_project_duration',true);
							$project_duration_term = get_term_by('slug',$project_duration_slug,'duration');
							$project_duration_term = isset($project_duration_term->term_id) ? $project_duration_term->term_id : [];
						}

						//Set terms
						wp_set_object_terms($project_product_id, 'projects', 'product_type');
						wp_set_post_terms($project_product_id, $categories_new_term_data, 'product_cat');
						wp_set_post_terms($project_product_id, $skill_new_term_data, 'skills');
						wp_set_post_terms($project_product_id, $experience_new_term_data, 'expertise_level');
						wp_set_post_terms($project_product_id, $language_new_term_data, 'languages');
						wp_set_post_terms($project_product_id, $project_duration_term, 'duration');

						//Update metas
						update_post_meta($project_product_id, 'min_price', $project_price);
						update_post_meta($project_product_id, 'max_price', $project_max_price);
						update_post_meta($project_product_id, 'max_hours', $project_estimated_hours);
						update_post_meta($project_product_id,'wr_product_type','projects');
						update_post_meta($project_product_id,'no_of_freelancers','1');
						update_post_meta($project_product_id,'project_type',$project_type);
						update_post_meta($project_product_id,'_project_status_type','public');
						update_post_meta($project_product_id,'_project_location',$project_job_type);
						update_post_meta($project_product_id,'is_featured',$project_featured);
						update_post_meta($project_product_id,'is_milestone',$project_milestone);
						update_post_meta($project_product_id,'_latitude',$project_latitude);
						update_post_meta($project_product_id,'_longitude',$project_longitude);
						update_post_meta($project_product_id,'_order_status','');
						update_post_meta($project_product_id,'_post_project_status',get_post_status($project_id));
						update_post_meta($project_product_id,'wr_project_meta',$wr_project_meta);
						update_post_meta($project_product_id,'_wr_linked_old_product',$project_id);

						if(!empty($project_documents)){
							$downloadable_files = array_map(function($item) {
								return array(
									'id' => $item['attachment_id'],
									'file' => $item['url'],
									'enabled' => true,
								);
							}, $project_documents);

							update_post_meta( $project_product_id, '_downloadable_files', $downloadable_files );
							update_post_meta($project_product_id,'_downloadable','yes');

						}

					}

				}

			}

			$response['type'] = 'success';
			$response['next_step'] = 8;
			$response['next_message'] = __('Migrating projects proposals','workreap');

			wp_send_json($response);

		}

		public function migrate_proposals(){

			$response = [];
			$product_id                         = workreap_employer_wallet_create();
			//Projects
			$proposals = get_posts(array(
				'post_type' => array('proposals'),
				'post_status' => 'any',
				'posts_per_page' => -1,
				'fields' => 'ids',
			));

			foreach ($proposals as $proposal){

				$proposal_id = $proposal;

				$order_id = get_post_meta($proposal_id,'_order_id',true);
				$freelancer_author_id = get_post_field( 'post_author', $proposal_id );
				$content = get_post_field('post_content', $proposal_id);
				$post_status = get_post_status($proposal_id);
				$post_status_code = isset($post_status) && ($post_status === 'hired' || $post_status === 'completed') ? 1 : 0;
				$status = get_post_meta($proposal_id,'_status',true);
				$amount = get_post_meta($proposal_id,'_amount',true);
				$per_hour_amount = get_post_meta($proposal_id,'_per_hour_amount',true);
				$estimeted_time = get_post_meta($proposal_id,'_estimeted_time',true);
				$freelancer_amount = get_post_meta($proposal_id,'_freelancer_amount',true);
				$proposal_type = empty($per_hour_amount) ? 'fixed' : 'hourly';
				$proposal_project_old_id = get_post_meta($proposal_id,'_project_id',true);

				
				$author_id	= get_post_meta($proposal_id,'_employer_user_id',true);
				//$author_id	= get_post_field( 'post_author', $proposal_project_old_id );
				$buyer_id	= get_user_meta($author_id, '_linked_profile_buyer',true);
				$seller_id	= get_user_meta($freelancer_author_id, '_linked_profile',true);

				$proposal_meta = get_post_meta($proposal_id,'proposal_meta',true);
				$proposal_meta = isset($proposal_meta) && !empty($proposal_meta) ? $proposal_meta : [];
				$_proposal_type	= get_post_meta($proposal_id,'_proposal_type',true);
				
				if(!empty($_proposal_type) && $_proposal_type === 'milestone' ){
					$milestones = get_posts(
						array(
							'post_type'      => 'wt-milestone',
							'posts_per_page' => 500,
							'post_status'    => 'any',
							'meta_key'       => '_propsal_id',
							'meta_value'     => $proposal_id,
						)
					);
					$milestone_array	= array();
					if( !empty($milestones) ){
						$_proposal_status	= get_post_meta($proposal_id, '_proposal_status',true);
						foreach($milestones as $milestone){
							$milestone_id	= $milestone->ID;
							$m_price	= get_post_meta($milestone_id, '_price',true);
							$m_status	= get_post_meta($milestone_id, '_status',true);
							$m_due_date	= get_post_meta($milestone_id, '_due_date',true);
							$m_order_id	= get_post_meta($milestone_id, '_order_id',true);
							
							if($_proposal_status === 'pending'){
								$m_status	= '';
							} 
							$m_status	= !empty($m_status) && $m_status === 'pay_now' ? '' : $m_status;
							$milestone_array['aIopk'.$milestone_id]	= array(
								'price' => $m_price,
								'title' => $milestone->post_title,
								'detail' => $milestone->post_content,
								'status' => $m_status,
							);
							if(!empty($m_order_id) ){
								$m_freelancer_share										= get_post_meta($m_order_id, 'freelancer_shares',true);
								$milestone_array['aIopk'.$milestone_id]['order_id']				= $m_order_id;
								update_post_meta( $m_order_id, 'milestone_id', $milestone_id);
								update_post_meta( $m_order_id, 'project_id', $proposal_project_old_id);
								update_post_meta( $m_order_id, 'project_type', 'fixed');
								update_post_meta( $m_order_id, 'payment_type', 'projects');
								update_post_meta( $m_order_id, 'seller_shares',  get_post_meta($m_order_id, 'freelancer_shares',true));
								update_post_meta( $m_order_id, 'proposal_id', $proposal_id);
								update_post_meta( $m_order_id, 'seller_id', $seller_id);
								update_post_meta( $m_order_id, 'buyer_id', $buyer_id);
								update_post_meta( $m_order_id, '_task_status', $m_status);
								$cus_woo_product_data				= get_post_meta($m_order_id, 'cus_woo_product_data',true);
								$cart_meta							= array();
								$cart_meta							= !empty($cus_woo_product_data) ? $cus_woo_product_data : array();
								$cart_meta['freelancer_shares']	= $m_freelancer_share;
								$cart_meta['title']				= $milestone->post_title;
								$cart_meta['project_type']		= 'fixed';
								update_post_meta( $m_order_id, 'cus_woo_product_data', $cart_meta);
							}
							if(!empty($m_status) && $m_status === 'completed' ){
								$m_completed_date	= get_post_meta($milestone_id, '_completed_date',true);
								$milestone_array['aIopk'.$milestone_id]['completed_date']	= $m_completed_date;

							}
						}	
					}
					$proposal_meta['milestone'] = $milestone_array;
					$proposal_type	= 'milestone';
				}
				$proposal_meta['price'] = $amount;
				$proposal_meta['description'] = $content;
				$proposal_meta['proposal_type'] = $proposal_type;

				update_post_meta($proposal_id,'proposal_meta',$proposal_meta);
				update_post_meta($proposal_id,'proposal_type',$proposal_type);
				update_post_meta($proposal_id,'_hired_status',$post_status_code);

				$proposal_project = wc_get_products(array(
					'limit' => 1,
					'status' => 'any',
					'type' => 'projects',
					'return' => 'ids',
					'meta_key' => '_wr_linked_old_product',
					'meta_value' => $proposal_project_old_id,
				));

				$proposal_project_id = !empty($proposal_project) ? reset( $proposal_project ) : [];
				$project_status = get_post_field('post_status',$proposal_project_id);
				$author_id = get_post_field( 'post_author', $proposal_project_id );
				if(isset($proposal_project_id) && !empty($proposal_project_id)){
					$proposal_status = get_post_field('post_status',$proposal_id);
					update_post_meta($proposal_id,'project_id',$proposal_project_id);
					update_post_meta($proposal_id,'buyer_id',$author_id);
					wp_update_post( array('ID' => $proposal_id, 'post_status' => $proposal_status,));
				}

				//Comments
				$proposal_comments = get_comments( array(
					'post_id' => $proposal_id,
					'post_type' => 'proposals',
					'status' => 'any',
				));

				foreach ( $proposal_comments as $proposal_comment ) {
					if(isset($proposal_comment->comment_ID)){
						wp_update_comment(array(
							'comment_ID' => $proposal_comment->comment_ID,
							'comment_type' => 'activity_detail',
						));
					}
				}

				if( !empty($order_id) ){
					$order_status	= $post_status;

					if( get_post_status($proposal_project_old_id) === 'completed' ){
						$employer_user_id	= get_post_meta($proposal_id,'_employer_user_id',true);
						$user_profiel_name  = workreap_get_username($buyer_id);
						$employer_user 		= get_user_by('id', $employer_user_id);
						
						$hiring_post	= get_posts(array(
							'post_type' 		=> array('reviews'),
							'post_status' 		=> 'any',
							'posts_per_page' 	=> -1,
							'fields' 			=> 'ids',
							'meta_key'       	=> '_proposal_id',
							'meta_value'     	=> $proposal_id,
						));
						$proposal_array					= array();
						$proposal_array['ID']			= $proposal_id;
						$proposal_array['post_status']	= 'completed';
						wp_update_post($proposal_array);
						//workreap_complete_order($order_id);
						$review_id			= !empty($hiring_post[0]) ? $hiring_post[0] : 0;
						$rating				= get_post_meta($review_id, 'user_rating',true);
						$review_content		= get_post_field('post_content', $review_id);
						$comment_id 		= wp_insert_comment(
							array(
								'comment_post_ID'      => $proposal_id,
								'comment_author'       => $user_profiel_name,
								'comment_author_email' => $employer_user->user_email,
								'comment_author_url'   => '',
								'comment_content'      => $review_content,
								'comment_type'         => 'rating',
								'comment_parent'       => 0,
								'user_id'              => $employer_user_id,
								'comment_date'         => get_the_date('Y-m-d H:i:s',$review_id),
								'comment_approved'     => 1,
							)
						);
						update_comment_meta($comment_id, 'rating', ($rating));
						update_comment_meta($comment_id, '_project_order', intval($proposal_project_old_id));
						update_comment_meta($comment_id, 'freelancer_id', intval($freelancer_author_id));
						update_comment_meta($comment_id, 'verified', 1);
						update_post_meta($order_id, '_rating_id', $comment_id);
						update_post_meta($proposal_id, '_rating_id', $comment_id);
						update_post_meta($proposal_id, 'rating', $rating);
						update_post_meta($order_id, 'rating', $rating);
						
						workreap_product_rating($proposal_project_old_id);
						workreap_freelancer_rating($freelancer_author_id);
					}

					update_post_meta( $order_id, 'proposal_id',$proposal_id );
					update_post_meta( $order_id, 'project_id',$proposal_project_old_id );
					update_post_meta( $order_id, 'buyer_id',$buyer_id );
					update_post_meta( $order_id, 'seller_id',$seller_id );

					$seller_shares	= get_post_meta($order_id, 'freelancer_shares',true);
					update_post_meta( $order_id, 'seller_shares',$seller_shares );
					update_post_meta( $order_id, 'payment_type','projects' );
					update_post_meta( $order_id, 'project_type',$proposal_type );
					$_paid_date	= get_post_meta($order_id, '_paid_date',true);
					if($_paid_date){
						update_post_meta( $order_id, 'tb_order_date',$_paid_date );
					}
					if( get_post_status($proposal_project_old_id) === 'completed' ){
						workreap_complete_order($order_id);
						update_post_meta($order_id, '_task_status', 'completed');
					} else if( get_post_status($proposal_project_old_id) === 'cancelled' ){
						update_post_meta($order_id, '_task_status', 'cancelled');
					}
					$admin_shares						= get_post_meta($order_id, 'admin_shares',true);
					$cus_woo_product_data				= get_post_meta($order_id, 'cus_woo_product_data',true);
					$cart_meta							= !empty($cus_woo_product_data) ? $cus_woo_product_data : array();

					$cart_meta_data							= array();
					$cart_meta_data['hiring_product_id']    = $product_id;
                    $cart_meta_data['product_name']         = esc_html__('Hiring project', 'workreap');
					$cart_meta_data['payment_type']         = 'projects';
					$cart_meta_data['project_type']         = $proposal_type;
					$cart_meta_data['buyer_id']				= $buyer_id;
					$cart_meta_data['seller_id']			= $seller_id;
					$cart_meta_data['admin_shares']			= $admin_shares;
					$cart_meta_data['seller_shares']		= $seller_shares;
					$cart_meta_data['freelancer_shares']	= $seller_shares;
					$cart_meta_data['project_id']			= $proposal_project_old_id;
					$cart_meta_data['proposal_id']			= $proposal_id;
					$cart_meta_data['price']				= isset($cart_meta['price']) ? $cart_meta['price'] : 0;
					$cart_meta_data['proposal_meta']		= array(
						'price'			=> isset($cart_meta['price']) ? $cart_meta['price'] : 0,
						'description'	=> $content,
						'proposal_type'	=> $proposal_type
					);
					if(!empty($proposal_type) && $proposal_type === 'hourly'){
						$cart_meta_data['max_hours']		= $estimeted_time;
						$cart_meta_data['hourly_rate']		= $per_hour_amount;
						$cart_meta_data['interval_name'] 	= !empty($proposal_project_id) ? get_the_title($proposal_project_id) : esc_html__('Hiring project', 'workreap');
						// $cart_meta_data['approved_total_time']	= $estimeted_time;
						// $cart_meta_data['approved_amount']	= $amount;
					}
					update_post_meta( $order_id, 'cus_woo_product_data', $cart_meta_data);
					update_post_meta( $order_id, '_task_status', 'completed');
				}
			}

			$response['next_step'] = 9;
			$response['next_message'] = __('Migrating services and projects orders','workreap');

			wp_send_json($response);

		}

		public function migrate_orders()
		{

			$response = [];

			// $orders_args = array(
			// 	'limit' => -1,
			// 	'status' => 'any',
			// 	'return' => 'ids',
			// );

			// $orders = wc_get_orders( $orders_args );
			$args = array(
				'posts_per_page'    => -1,
				'post_type'         => 'shop_order',
				'orderby'           => 'desc',
				'post_status'       => array('wc-completed', 'wc-pending', 'wc-on-hold', 'wc-cancelled', 'wc-refunded', 'wc-processing'),
				'suppress_filters'  => false
			);
			$meta_query_args[] = array(
				'key' 		=> 'payment_type',
				'value' 	=> 'subscription',
				'compare'   => '='
			);
			$query_relation     = array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query              = new WP_Query($args);
			if( $query->have_posts() ){
				while ($query->have_posts()) : $query->the_post();
					global $post;
                    $order_id   				= $post->ID;
					$customer_id				= get_post_meta($order_id, '_customer_user',true);
					$wt_subscription			= get_user_meta($customer_id, 'wt_subscription',true);
					$cus_woo_product_data		= get_post_meta($order_id, 'cus_woo_product_data',true);
					$package_id					= !empty($wt_subscription['subscription_id']) ? intval($wt_subscription['subscription_id']) : 0;
					$get_user_type	= workreap_get_user_type($customer_id);
					if( !empty($package_id) ){
						$_completed_date	= get_post_meta($order_id, '_completed_date',true);
						$package_type		= get_post_meta($package_id, 'wt_duration_type', true);
						$subscription_featured_expiry	= !empty($wt_subscription['subscription_featured_expiry']) ? $wt_subscription['subscription_featured_expiry'] : '';
						
						update_user_meta($customer_id,'package_create_date',$_completed_date);
						update_user_meta($customer_id,'package_expriy_date',$subscription_featured_expiry);
						
						$package_type_val			= "";
						$package_duration			= 0;
						$featured_tasks_duration	= 0;
						if(!empty($package_type)){
							if($package_type==='yearly' || $package_type==='2yearly' || $package_type==='3yearly' || $package_type==='4yearly' || $package_type==='5yearly'){
								$package_type_val	= 'year';
								if($package_type==='yearly'){
									$package_duration	= 1;
									$featured_tasks_duration = 360;
								} else if($package_type==='2yearly'){
									$package_duration	= 2;
									$featured_tasks_duration = 360*2;
								}else if($package_type==='3yearly'){
									$package_duration	= 3;
									$featured_tasks_duration = 360*3;
								}else if($package_type==='4yearly'){
									$package_duration	= 4;
									$featured_tasks_duration = 360*4;
								}else if($package_type==='5yearly'){
									$package_duration	= 5;
									$featured_tasks_duration = 360*5;
								}
							} else if($package_type==='weekly' || $package_type==='biweekly' || $package_type==='bimonthly' || $package_type==='quarterly' ){
								$package_type_val	= 'days';
								if($package_type==='weekly'){
									$package_duration	= 7;
								} else if($package_type==='biweekly'){
									$package_duration	= 14;
								}else if($package_type==='bimonthly'){
									$package_duration	= 60;
								}else if($package_type==='quarterly'){
									$package_duration	= 90;
								}
								$featured_tasks_duration = $package_duration;
							} else if($package_type==='monthly' || $package_type==='biannually'){
								$package_type_val	= 'month';
								if($package_type==='monthly'){
									$package_duration	= 1;
									$featured_tasks_duration = 30;
								} else if($package_type==='biannually'){
									$package_duration	= 6;
									$featured_tasks_duration = 30*2;
								}
							}
						}
						$number_tasks_allowed		= !empty($cus_woo_product_data['wt_services']) ? intval($cus_woo_product_data['wt_services']) : 0;
						$featured_tasks_allowed		= !empty($cus_woo_product_data['wt_featured_services']) ? intval($cus_woo_product_data['wt_featured_services']) : 0;
						
						if(!empty($get_user_type) && $get_user_type === 'employers'){
							$employer_package_details	= array(
								'package_type' => $package_type_val,
								'package_duration' => $package_duration,
								'number_projects_allowed' => !empty($cus_woo_product_data['wt_jobs']) ? intval($cus_woo_product_data['wt_jobs']) : 0,
								'featured_projects_allowed' => !empty($cus_woo_product_data['wt_featured_jobs']) ? intval($cus_woo_product_data['wt_featured_jobs']) : 0,
								'featured_projects_duration' => $featured_tasks_duration,
								'package_create_date' => !empty($_completed_date) ? $_completed_date : '',
								'package_expriy_date' => $subscription_featured_expiry,
							);
							update_post_meta($order_id,'cus_woo_product_data',$employer_package_details);
							update_user_meta($customer_id,'employer_package_details',$employer_package_details);
							$db_cus_woo_product_data	= array(
								'package_type' => $package_type_val,
								'package_duration' => $package_duration,
								'number_projects_allowed' => !empty($wt_subscription['wt_jobs']) ? intval($wt_subscription['wt_jobs']) : 0,
								'featured_projects_allowed' => !empty($wt_subscription['wt_featured_jobs']) ? intval($wt_subscription['wt_featured_jobs']) : 0,
								'featured_projects_duration' => $featured_tasks_duration,
								'package_create_date' => !empty($_completed_date) ? $_completed_date : '',
								'package_expriy_date' => $subscription_featured_expiry,
							);
							
							update_user_meta($customer_id,'remaining_employer_package_details',$db_cus_woo_product_data);
							update_user_meta($customer_id,'employer_package_order_id',$order_id);
						} else {
							$freelancer_package_details	= array(
								'task_plans_allowed' 		=> 'yes',
								'package_type' 				=> $package_type_val,
								'package_duration' 			=> $package_duration,
								'number_tasks_allowed' 		=> $number_tasks_allowed,
								'featured_tasks_allowed' 	=> $featured_tasks_allowed,
								'featured_tasks_duration' 	=> $featured_tasks_duration,
								'number_project_credits' 	=> !empty($cus_woo_product_data['wt_connects']) ? $cus_woo_product_data['wt_connects'] : '',
								'package_create_date' 		=> !empty($_completed_date) ? $_completed_date : '',
								'package_expriy_date' 		=> $subscription_featured_expiry,
							);
							update_post_meta($order_id,'cus_woo_product_data',$freelancer_package_details);
							$db_cus_woo_product_data	= array(
								'task_plans_allowed' 		=> 'yes',
								'package_type' 				=> $package_type_val,
								'package_duration' 			=> $package_duration,
								'number_tasks_allowed' 		=> !empty($wt_subscription['wt_services']) ? $wt_subscription['wt_services'] : 0,
								'featured_tasks_allowed' 	=> !empty($wt_subscription['wt_featured_services']) ? $wt_subscription['wt_featured_services'] : 0,
								'featured_tasks_duration' 	=> $featured_tasks_duration,
								'number_project_credits' 	=> !empty($wt_subscription['wt_connects']) ? $wt_subscription['wt_connects'] : 0,
								'package_create_date' 		=> !empty($_completed_date) ? $_completed_date : '',
								'package_expriy_date' 		=> $subscription_featured_expiry,
							);
							update_user_meta($customer_id,'freelancer_package_details',$db_cus_woo_product_data);
							
							update_user_meta($customer_id,'package_order_id',$order_id);
						}

					}
				endwhile;
			}
			$response['type'] = 'success';
			$response['next_step'] = 11;
			$response['next_message'] = __('Data has been migrated','workreap');

			wp_send_json($response);

		}

		public function migrate_disputes()
		{

			$response = [];

			$disputes = get_posts(array(
				'post_type' => array('disputes'),
				'post_status' => 'any',
				'posts_per_page' => -1,
			));
			if( !empty($disputes) ){
				foreach($disputes as $dispute){
					$dispute_id		= $dispute->ID;
					$hiring_id		= get_post_meta($dispute_id, '_dispute_project',true);
					$hiring_post	= get_posts(array(
						'post_type' 		=> array('shop_order'),
						'post_status' 		=> 'any',
						'posts_per_page' 	=> -1,
						'fields' 			=> 'ids',
						'meta_key'       	=> '_hiring_id',
						'meta_value'     	=> $hiring_id,
					));
					$order_id	= !empty($hiring_post[0]) ? $hiring_post[0] : 0;
					if(!empty($order_id)){
						$dispute_status	= get_post_status($dispute_id);
						$update_dispute_status = '';
						$my_post['ID']	= $dispute_id;
						if($dispute_status === 'pending'){
							$my_post['post_status']	= 'disputed';
						} else if($dispute_status === 'publish'){
							$my_post['post_status']	= 'refunded';
							update_post_meta($dispute_id,'resolved_by','admin');
							update_post_meta($dispute_id,'dispute_status','resolved');
							$fw_options	= get_post_meta($dispute_id, 'fw_options', true);
							if( !empty($fw_options['feedback'])){
								$comment_data = array(
									'comment_post_ID' => $dispute_id,  
									'comment_content' => $fw_options['feedback'], 
									'comment_type' => '',
									'comment_parent' => 0, 
									'user_id' => 1,
									'comment_approved' => 1, 
								);
								$comment_id = wp_insert_comment($comment_data);
							}
							
						}
						wp_update_post( $my_post );
						update_post_meta($dispute_id,'_dispute_order',$order_id);
						update_post_meta($dispute_id,'_dispute_type','shop_order');
						update_post_meta($order_id,'dispute','yes');
						update_post_meta($order_id,'dispute_id',$dispute_id);
						$send_by		= get_post_meta($order_id, '_send_by',true);
						$freelancer_id	= get_post_meta($order_id, 'freelancer_id',true);
						$employer_id	= get_post_meta($order_id, 'employer_id',true);
						update_post_meta($dispute_id,'_buyer_id',$employer_id);
						update_post_meta($dispute_id,'_seller_id',$freelancer_id);
						update_post_meta($dispute_id,'_freelancer_id',$freelancer_id);
						update_post_meta($dispute_id,'_employer_id',$employer_id);
						$get_user_type	= workreap_get_user_type($send_by);
						update_post_meta($dispute_id,'_sender_type',$get_user_type);

						$winning_party		= get_post_meta($dispute_id, 'winning_party',true);
						if(!empty($winning_party) ){
							$winning_party_user_type	= workreap_get_user_type($winning_party);
							if(!empty($winning_party_user_type) && $winning_party_user_type === 'employers'){
								$order = wc_get_order($order_id);
								$order->set_status('cancelled');
								$order->save();
								update_post_meta( $order_id, '_task_status', 'cancelled' );
							} else if(!empty($winning_party_user_type) && $winning_party_user_type === 'freelancers'){
								$_edit_lock		= get_post_meta($dispute_id, '_edit_lock',true);
								update_post_meta( $order_id, '_task_status' , 'completed');
								update_post_meta( $order_id, '_task_completed_time', $_edit_lock );
							}
							update_post_meta($dispute_id, 'dispute_status', 'resolved');
           					update_post_meta($dispute_id, 'resolved_by', 'admin');
						}

					}
				}
			}

			$response['type'] = 'success';
			$response['next_step'] = 10;
			$response['next_message'] = __('Data has been migrated','workreap');

			wp_send_json($response);

		}
		public function data_migration() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( 'you don\'t have access to perform this action!', 'workreap' ) ) );
			}

			$response = [];

			$step    = sanitize_text_field( $_GET['step'] );

			switch ($step) {
				case "1":
					$this->redux_json_import();
					break;
				case "2":
					$this->unyson_to_redux_fields();
					break;
				case "3":
					$this->import_dashboard_templates();
					break;
				case "4":
					$this->migrate_users();
					break;
				case "5":
					$this->migrate_profiles_data();
					break;
				case "6":
					$this->migrate_services();
					break;
				case "7":
					$this->migrate_projects();
					break;
				case "8":
					$this->migrate_proposals();
					break;
				case "9":
					$this->migrate_disputes();
					break;
				case "10":
					$this->migrate_orders();
					break;
				default:
					$response['type'] = 'success';
					$response['message'] = __('Data has been migrated successfully','workreap');
			}

			wp_send_json($response);

		}

		public function migration_markup() {
			?>
            <div class="wr-migrations-wrapper">
                <h2 class="wr-title"><?php esc_html_e( 'Workreap Migrations', 'workreap' ); ?></h2>
                <p class="wr-description"><?php esc_html_e( 'Effortlessly migrate your WordPress site data with our seamless tools. Before proceeding, always perform a full backup of your site or use a staging environment.', 'workreap' ); ?></p>
                <button id="wr-migrate" class="wr-migrations-btn"><?php esc_html_e( 'Run Migration', 'workreap' ); ?></button>
            </div>
		<?php }

	}

	Workreap_Migration::instance();

}