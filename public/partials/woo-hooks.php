<?php
/**
 * Provide a public-facing hooks
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0 *
 * @package    Workreap
 * @subpackage Workreap/public/partials
 */

 /**
 * Billing deatils on checkout
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_custom_checkout_update_customer')) {
	add_action( 'woocommerce_checkout_fields', 'workreap_custom_checkout_update_customer', 10);
	function workreap_custom_checkout_update_customer( $fields ){
		$user 			= wp_get_current_user();
		$first_name 	= $user ? $user->user_firstname : '';
		$last_name 		= $user ? $user->user_lastname : '';

		$billing_company	= get_user_meta( $user->ID, 'billing_company', true );
		$billing_address_1	= get_user_meta( $user->ID, 'billing_address_1', true );
		$billing_country	= get_user_meta( $user->ID, 'billing_country', true );
		$billing_state		= get_user_meta( $user->ID, 'billing_state', true );
		$billing_phone		= get_user_meta( $user->ID, 'billing_phone', true );
		$billing_postcode	= get_user_meta( $user->ID, 'billing_city', true );
		$billing_city		= get_user_meta( $user->ID, 'billing_city', true );

		$billing_company	= !empty($billing_company) ? $billing_company : '';
		$billing_address_1	= !empty($billing_address_1) ? $billing_address_1 : '';
		$billing_country	= !empty($billing_country) ? $billing_country : '';
		$billing_state		= !empty($billing_state) ? $billing_state : '';
		$billing_phone		= !empty($billing_phone) ? $billing_phone : '';
		$billing_postcode	= !empty($billing_postcode) ? $billing_postcode : '';
		$billing_city		= !empty($billing_city) ? $billing_city : '';

		$fields['billing']['billing_first_name']['default'] = $first_name;
		$fields['billing']['billing_last_name']['default']  = $last_name;
		$fields['billing']['billing_company']['default']  	= $billing_company;
		$fields['billing']['billing_address_1']['default']  = $billing_address_1;
		$fields['billing']['billing_country']['default']  	= $billing_country;
		$fields['billing']['billing_state']['default']  	= $billing_state;
		$fields['billing']['billing_phone']['default']  	= $billing_phone;
		$fields['billing']['billing_postcode']['default']  	= $billing_postcode;
		$fields['billing']['billing_city']['default']  		= $billing_city;

		return $fields;
	}
}

/**
 * Get admin user
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('workreap_get_administrator_user_id')) {
	function workreap_get_administrator_user_id(){
		$args = array(
			'role'		=> 'administrator',
			'fields'	=> array( 'ID' ),
			'orderby' 	=> 'ID',
			'order'   	=> 'ASC'
		);
		$users 			= get_users( $args );
		$admin_user	= !empty($users[0]) ? $users[0] : '';
		if(!empty($admin_user)){
			return $admin_user->ID;
		}

	}
}


/**
 * Order options
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WooCommerce')) {
	if (!function_exists('workreap_employer_wallet_create')) {
		function workreap_employer_wallet_create(){
      		global $current_user;
			$args = array(
				'limit'     => -1, // All products
				'status'    => 'publish',
				'type'      => 'funds',
				'orderby'   => 'date',
				'order'     => 'DESC',
			);

			$workreap_funds = wc_get_products( $args );

			$wallet_post	= !empty($workreap_funds[0]) ? $workreap_funds[0] : '';
			if(!empty($wallet_post)){
				return (int)$wallet_post->get_id();
			}
		}
	}
}

/**
 * Order options
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WooCommerce')) {
	if (!function_exists('workreap_order_option')) {
		add_action( 'init', 'workreap_order_option' );

		function workreap_order_option(){
			add_filter( 'woocommerce_cod_process_payment_order_status','workreap_update_order_status', 10, 2 );
			add_filter( 'woocommerce_cheque_process_payment_order_status','workreap_update_order_status', 10, 2 );
			add_filter( 'woocommerce_bacs_process_payment_order_status','workreap_update_order_status', 10, 2 );

			if( is_admin() ){
				add_action( 'woocommerce_order_status_completed','workreap_payment_complete',10,1 );
			}

		}
	}
}

/**
 * change status for offline payment gateway
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_update_order_status')) {
	function workreap_update_order_status( $status,$order  ) {
		return 'on-hold';
	}
}


/**
 * Complete order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_payment_complete')) {
    add_action('woocommerce_payment_complete', 'workreap_payment_complete',10,1 );
    function workreap_payment_complete($order_id) {
		global $current_user, $wpdb;
		if (class_exists('WooCommerce')) {
			$order 		= wc_get_order($order_id);
			$user 		= $order->get_user_id( );
			$items 		= $order->get_items();
			if($order->get_status() != 'wc-failed'){
				$offset 		= get_option('gmt_offset') * intval(60) * intval(60);
				$current_date 	= date('Y-d-m H:i',current_time( 'timestamp' ));
				$gmt_time		= current_time( 'mysql', 1 );

				//Update order status
				$order->update_status( 'completed' );
				$order->save();

				$user_type	= apply_filters('workreap_get_user_type', $current_user->ID );
				$invoice_id = esc_html__('Order #','workreap') . '&nbsp;' . $order_id;
				foreach ($items as $key => $item) {
					if ($user) {
						$order_detail 	= wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
						
						if( !empty( $order_detail['payment_type'] ) && $order_detail['payment_type'] == 'tasks' && empty($order_detail['offers_id']) ) {
							workreap_update_tasks_data( $order_id,$order_detail );

							if( !empty($user_type) && $user_type === 'freelancers' ){
								update_post_meta( $order_id, 'freelancer_id', $current_user->ID );
							} else if( !empty($user_type) && $user_type === 'employers' ) {
								update_post_meta( $order_id, 'employer_id', $current_user->ID );
							}

						} else if( !empty($order_detail['payment_type']) && $order_detail['payment_type'] == 'tasks' && !empty($order_detail['offers_id']) ){
							do_action('workreap_update_custom_offer_data',$order_id,$order_detail);
							if( !empty($user_type) && $user_type === 'freelancers' ){
								update_post_meta( $order_id, 'freelancer_id', $current_user->ID );
							} else if( !empty($user_type) && $user_type === 'employers' ) {
								update_post_meta( $order_id, 'employer_id', $current_user->ID );
							}
						} else if( !empty($order_detail['payment_type']) && $order_detail['payment_type'] == 'package' ){
							workreap_update_packages_data( $order_id,$order_detail,$user);
						} else if( !empty($order_detail['payment_type']) && $order_detail['payment_type'] == 'wallet' ){
							workreap_update_wallet_data( $order_id,$order_detail,$user);
						}else if( !empty($order_detail['payment_type']) && $order_detail['payment_type'] == 'projects' ){
							workreap_update_project_data( $order_id,$order_detail,$user);
						} else {
							do_action( 'workreap_update_woocommerce_order_data', $order_id,$order_detail,$user );
						}

						update_post_meta($order_id,'wr_order_date',date('Y-m-d H:i:s', strtotime($current_date)));
						update_post_meta($order_id,'wr_order_date_gmt',strtotime($current_date));
					}
				}
			}
		}
    }
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('workreap_update_wallet_data')) {
    function workreap_update_wallet_data( $order_id,$order_detail,$user_id) {
		$price			= !empty($order_detail['price']) ? $order_detail['price'] : 0;
		$employer_amount	= get_user_meta( $user_id, '_employer_balance',true );
		$employer_amount	= !empty($employer_amount) ? ($employer_amount+$price) : $price;
		update_user_meta( $user_id,'_employer_balance',$employer_amount );
		update_post_meta( $order_id,'_employer_balance',$employer_amount );
		update_post_meta( $order_id,'_order_balance',$price );
    	update_post_meta( $order_id,'employer_id',$user_id );

	}
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('workreap_update_project_data')) {
    function workreap_update_project_data( $order_id,$order_detail ) {
    	global $workreap_settings;
		$gmt_time		= date('Y-d-m H:i',current_time( 'timestamp' ));
		$freelancer_shares	= !empty($order_detail['freelancer_shares']) ? $order_detail['freelancer_shares'] : 0;
		$admin_shares	= !empty($order_detail['admin_shares']) ? $order_detail['admin_shares'] : 0;
		$employer_id		= !empty($order_detail['employer_id']) ? $order_detail['employer_id'] : 0;
		$freelancer_id		= !empty($order_detail['freelancer_id']) ? $order_detail['freelancer_id'] : 0;
		$proposal_id	= !empty($order_detail['proposal_id']) ? $order_detail['proposal_id'] : 0;
		$project_id		= !empty($order_detail['project_id']) ? $order_detail['project_id'] : 0;
		$project_type	= !empty($order_detail['project_type']) ? $order_detail['project_type'] : 0;
		$wallet_price	= !empty($order_detail['wallet_price']) ? $order_detail['wallet_price'] : 0;
		$order_amount	= !empty($order_detail['price']) ? $order_detail['price'] : 0;
		if( !empty($wallet_price) ){
			$user_balance   = !empty($employer_id) ? get_user_meta( $employer_id, '_employer_balance',true ) : '';
			$user_balance   = !empty($user_balance) ? ($user_balance-$wallet_price) : 0;

			update_user_meta( $employer_id,'_employer_balance',$user_balance);
			update_post_meta( $order_id, '_wallet_amount', $wallet_price );
			update_post_meta( $order_id, '_task_type', 'wallet' );
		}

		update_post_meta( $order_id, 'employer_id', $employer_id );
		update_post_meta( $order_id, 'proposal_id', $proposal_id );
		update_post_meta( $order_id, 'freelancer_shares', $freelancer_shares );
		update_post_meta( $order_id, 'admin_shares', $admin_shares );
		update_post_meta( $order_id, 'project_id', $project_id );
		update_post_meta( $order_id, 'project_type', $project_type );
		update_post_meta( $order_id, 'freelancer_id', $freelancer_id );
		update_post_meta( $order_id, '_post_project_status', 'hired' );
		update_post_meta( $order_id, '_task_status', 'hired' );
		$employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
		$freelancer_id                          = get_post_field( 'post_author', $proposal_id );
		$freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
		$notifyDetails                      = array();
		$notifyDetails['employer_id']  	    = $employer_profile_id;
		$notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
		$notifyDetails['project_id']  	    = $project_id;
		$notifyDetails['proposal_id']  	    = $proposal_id;
		$notifyData['receiver_id']		    = $freelancer_id;
		$notifyData['linked_profile']	    = $freelancer_profile_id;
		$notifyData['user_type']		    = 'freelancers';
		$proposal_status	= get_post_status( $proposal_id );
		if( !empty($proposal_id) && $proposal_status != 'hired'){
			$proposal_post = array(
				'ID'           	=> $proposal_id,
				'post_status'   => 'hired'
			);
			wp_update_post( $proposal_post );
			update_post_meta( $proposal_id, 'hiring_date',$gmt_time );
			update_post_meta($proposal_id,'hiring_date_gmt',strtotime($gmt_time));
			update_post_meta( $proposal_id, '_hired_status',true );
			workreapUpdateProjectStatusOption($project_id,'hired');
			$notifyData['type']		            = 'hired_proposal';
			$notifyData['post_data']		    = $notifyDetails;
			do_action('workreap_notification_message', $notifyData );
			/* Email to freelancer */
			$proposal_hired_switch        = !empty($workreap_settings['email_proposal_hired_freelancer']) ? $workreap_settings['email_proposal_hired_freelancer'] : true;
			if(class_exists('Workreap_Email_helper') && !empty($proposal_hired_switch)){
				$emailData                      = array();
				$emailData['freelancer_email']      = get_userdata($freelancer_id)->user_email;
				$emailData['employer_name']        = workreap_get_username($employer_profile_id);
				$emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
				$emailData['project_title']     = get_the_title($project_id);
				$emailData['project_link']     = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'activity',$proposal_id);
				if (class_exists('WorkreapProposals')) {
					$email_helper = new WorkreapProposals();
					$email_helper->hired_proposal_freelancer_email($emailData);
				}
			}
		}

		if( !empty($project_type) && $project_type === 'fixed' && !empty($proposal_id) && !empty($order_detail['milestone_id'])){
			$proposal_meta	= get_post_meta( $proposal_id, 'proposal_meta',true );
			if( !empty($proposal_meta['milestone'][$order_detail['milestone_id']] )){
				$proposal_meta['milestone'][$order_detail['milestone_id']]['status']	= 'hired';
				$proposal_meta['milestone'][$order_detail['milestone_id']]['order_id']	= $order_id;
				update_post_meta( $proposal_id, 'proposal_meta',$proposal_meta );
				update_post_meta( $order_id, 'milestone_id', $order_detail['milestone_id'] );
				$notifyData['type']		            = 'hired_proposal_milestone';
				$notifyDetails['milestone_id']  	= $order_detail['milestone_id'];
				$notifyData['post_data']		    = $notifyDetails;
				do_action('workreap_notification_message', $notifyData );
				/* Email to freelancer */
				$milestone_hired_switch        = !empty($workreap_settings['email_milestone_hire_freelancer']) ? $workreap_settings['email_milestone_hire_freelancer'] : true;
				if(class_exists('Workreap_Email_helper') && !empty($milestone_hired_switch)){
					$emailData                      = array();
					$emailData['freelancer_email']      = get_userdata($freelancer_id)->user_email;
					$emailData['employer_name']        = workreap_get_username($employer_profile_id);
					$emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
					$emailData['project_title']     = get_the_title($project_id);
					$emailData['milestone_title'] 	= get_the_title($order_detail['milestone_id']);
					$emailData['project_link']     	= Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'activity',$proposal_id);
					if (class_exists('WorkreapMilestones')) {
						$email_helper = new WorkreapMilestones();
						$email_helper->hire_milestone_freelancer_email($emailData);
					}
				}

			}
		} else if( !empty($project_type) && $project_type === 'fixed'){
			update_post_meta( $proposal_id, 'order_id',$order_id );
		}

    }
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('workreap_update_tasks_data')) {
    function workreap_update_tasks_data( $order_id,$order_detail ) {
    	global $workreap_settings;
		$product_id		= !empty($order_detail['task_id']) ? $order_detail['task_id'] : 0;
		$freelancer_shares	= !empty($order_detail['freelancer_shares']) ? $order_detail['freelancer_shares'] : 0;
		$admin_shares	= !empty($order_detail['admin_shares']) ? $order_detail['admin_shares'] : 0;
		$employer_id		= !empty($order_detail['employer_id']) ? $order_detail['employer_id'] : 0;
		$freelancer_id		= !empty($order_detail['freelancer_id']) ? $order_detail['freelancer_id'] : 0;
		$wallet_price	= !empty($order_detail['wallet_price']) ? $order_detail['wallet_price'] : 0;
		$order_amount	= !empty($order_detail['price']) ? $order_detail['price'] : 0;
		$employer_amount	= $order_amount;

		if( !empty($wallet_price) ){
			$user_balance   = !empty($employer_id) ? get_user_meta( $employer_id, '_employer_balance',true ) : '';
			$user_balance   = !empty($user_balance) ? ($user_balance-$wallet_price) : 0;

			update_user_meta( $employer_id,'_employer_balance',$user_balance);
			update_post_meta( $order_id, '_wallet_amount', $wallet_price );
			update_post_meta( $order_id, '_task_type', 'wallet' );
			$employer_amount	= !empty($employer_amount) ? ($employer_amount + $wallet_price) : 0;
		}

		update_post_meta( $order_id, 'employer_id', $employer_id );
		update_post_meta( $order_id, 'freelancer_id', $freelancer_id );
		update_post_meta( $order_id, '_task_status', 'hired' );

		$contents	= workreap_tasks_order_details($order_detail,$product_id,'return');

		if( !empty($contents['key'])){

			$workreap_plans_values 	= get_post_meta($product_id, 'workreap_product_plans', TRUE);
			$workreap_plans_values	= !empty($workreap_plans_values) ? $workreap_plans_values : array();
			$delivery_id			= !empty($workreap_plans_values[$contents['key']]['delivery_time']) ? $workreap_plans_values[$contents['key']]['delivery_time'] : 0;
			
			if( class_exists('ACF') ) {
				$delivery_time	= 'delivery_time_'.$delivery_id;
				if(function_exists('get_field')){
					$days	= get_field('days', $delivery_time);
				}

				$days			= !empty($days) ? $days : 0;
				$gmt_time		= date('Y-m-d H:i',current_time( 'timestamp' ));
				$gmt_strtotime	= strtotime($gmt_time . " +".$days." days");
				update_post_meta( $order_id, 'delivery_date', $gmt_strtotime );
				
				$delivery_dattails									= array();
				$delivery_dattails[$delivery_id]['days']			= $days;
				$delivery_dattails[$delivery_id]['delivery_date']	= $gmt_strtotime;
				update_post_meta( $order_id, 'delivery_dattails', $delivery_dattails );
			}
		}

		//Send email to users/admin
		$employer      	= get_user_by( 'id', $employer_id );
		$employer_name 	= !empty($employer->first_name) ? $employer->first_name : '';
		$freelancer         = get_user_by( 'id', $freelancer_id );
		$freelancer_name    = !empty($freelancer->first_name) ? $freelancer->first_name : '';
		$product    	= wc_get_product( $product_id );
		$task_name  	= $product->get_title();
		$freelancer_email 	= !empty($freelancer->user_email) ? $freelancer->user_email : '';
		$employer_email 	= !empty($employer->user_email) ? $employer->user_email : '';
		$task_link   	= get_permalink($product_id);

		$freelancer_profile_id  = workreap_get_linked_profile_id($freelancer_id,'','freelancers');
		$employer_profile_id  	= workreap_get_linked_profile_id($employer_id,'','employers');

		$notifyData						= array();
        $notifyDetails					= array();
        $notifyDetails['task_id']     	= $product_id;
        $notifyDetails['freelancer_id']   	= $freelancer_profile_id;
		$notifyDetails['employer_id']   	= $employer_profile_id;
		$notifyDetails['order_id']   	= $order_id;
		$notifyDetails['freelancer_order_amount'] = $freelancer_shares;
		$notifyDetails['employer_amount'] 	= $employer_amount;
        $notifyData['receiver_id']		= $freelancer_id;
        $notifyData['type']			    = 'freelancer_new_order';
        $notifyData['linked_profile']	= $freelancer_profile_id;
        $notifyData['user_type']		= 'freelancers';
        $notifyData['post_data']		= $notifyDetails;
        do_action('workreap_notification_message', $notifyData );
		$notifyData['receiver_id']		= $employer_id;
		$notifyData['type']			    = 'employer_new_order';
        $notifyData['linked_profile']	= $employer_profile_id;
        $notifyData['user_type']		= 'employers';
        $notifyData['post_data']		= $notifyDetails;
        do_action('workreap_notification_message', $notifyData );

		if (class_exists('Workreap_Email_helper')) {
			if (class_exists('WorkreapOrderStatuses')) {
				$blogname                 = get_option( 'blogname' );
				$default_chat_mesage      = wp_kses(__('Congratulations! You have hired for the task "{{taskname}}" {{tasklink}}', 'workreap'), //default email content
					array(
						'a' => array(
						'href' => array(),
						'title' => array()
						),
						'br' => array(),
						'em' => array(),
						'strong' => array(),
					)
				);
				
				$emailData = array();
				$emailData['freelancer_name']       = $freelancer_name;
				$emailData['employer_name'] 		= $employer_name;
				$emailData['task_name']         = $task_name;
				$emailData['freelancer_email']      = $freelancer_email;
				$emailData['employer_email']       = $employer_email;
				$emailData['task_link']         = $task_link;
				$emailData['order_id'] 			= $order_id;
				$emailData['order_amount']      = $order_amount;
				$emailData['notification_type'] = 'noty_new_order';
				$emailData['sender_id']         = $freelancer_id; //freelancer id
				$emailData['receiver_id']       = $employer_id; //employer id

				/* New Order Email */
				$email_helper = new WorkreapOrderStatuses();
				if( !empty($workreap_settings['email_new_order_freelancer']) ) {
					$email_helper->new_order_freelancer_email($emailData);
					if( (in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins')))) && $workreap_settings['hire_freelancer_chat_switch']==true){
						$message = !empty($workreap_settings['hire_freelancer_chat_mesage']) ? $workreap_settings['hire_freelancer_chat_mesage'] : $default_chat_mesage;
						$chat_mesage  = str_replace("{{taskname}}", $task_name, $message);
						$chat_mesage  = str_replace("{{tasklink}}", $task_link, $chat_mesage);
						do_action('wpguppy_send_message_to_user',$employer_id,$freelancer_id,$chat_mesage);
					}
				}

				if ( !empty($workreap_settings['email_new_order_employer'])) {
					$email_helper->new_order_employer_email($emailData);
				}
				
				do_action('noty_push_notification', $emailData);
			}
		}

		update_post_meta( $order_id, 'order_details', $contents );
    }
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('workreap_update_packages_data')) {
    function workreap_update_packages_data( $order_id,$order_detail,$user_id) {
		global $workreap_settings;
		$package_id		= !empty($order_detail['package_id']) ? $order_detail['package_id'] : 0;
		$user_type		= !empty($order_detail['user_type']) ? $order_detail['user_type'] : 'freelancers';
		$package_id		= !empty($package_id) ? $package_id : 0;
		$package_type    		= get_post_meta( $package_id, 'package_type', true );
		$package_duration    	= get_post_meta( $package_id, 'package_duration', true );
		update_post_meta( $order_id, 'user_type', $user_type );
		$package_details		= array();
		$allow_ai 	= get_post_meta( $package_id, 'allow_ai', true );
		if( !empty($user_type) && $user_type == 'employers'){

			$number_projects_allowed   						= get_post_meta( $package_id, 'number_projects_allowed', true );
			$featured_projects_allowed 						= get_post_meta( $package_id, 'featured_projects_allowed', true );
			$featured_projects_duration    					= get_post_meta( $package_id, 'featured_projects_duration', true );
			$package_details['allow_ai']					= !empty($allow_ai) ? $allow_ai : '';
			$package_details['package_type']				= !empty($package_type) ? $package_type : '';
			$package_details['package_duration']			= !empty($package_duration) ? $package_duration : '';
			$package_details['number_projects_allowed']		= !empty($number_projects_allowed) ? $number_projects_allowed : '';
			$package_details['featured_projects_allowed']	= !empty($featured_projects_allowed) ? $featured_projects_allowed : '';
			$package_details['featured_projects_duration']	= !empty($featured_projects_duration) ? $featured_projects_duration : '';
			
		} else {
			
			$number_tasks_allowed   = get_post_meta( $package_id, 'number_tasks_allowed', true );
			$featured_tasks_allowed = get_post_meta( $package_id, 'featured_tasks_allowed', true );
			$task_plans_allowed    	= get_post_meta( $package_id, 'task_plans_allowed', true );
			$featured_tasks_duration    	= get_post_meta( $package_id, 'featured_tasks_duration', true );
			$number_project_credits    		= get_post_meta( $package_id, 'number_project_credits', true );

			$package_details							= array();
			$package_details['allow_ai']				= !empty($allow_ai) ? $allow_ai : '';
			$package_details['task_plans_allowed']		= !empty($task_plans_allowed) ? $task_plans_allowed : '';
			$package_details['package_type']			= !empty($package_type) ? $package_type : '';
			$package_details['package_duration']		= !empty($package_duration) ? $package_duration : '';
			$package_details['number_tasks_allowed']	= !empty($number_tasks_allowed) ? $number_tasks_allowed : '';
			$package_details['featured_tasks_allowed']	= !empty($featured_tasks_allowed) ? $featured_tasks_allowed : '';
			$package_details['featured_tasks_duration']	= !empty($featured_tasks_duration) ? $featured_tasks_duration : '';
			$package_details['number_project_credits']	= !empty($number_project_credits) ? $number_project_credits : '';
			
		}

		$type	= $package_type;

		$add_date_time			= $package_duration.' '.$type;
		$current_date_time		= date("Y-m-d H:i:s");

		$package_expriy_date	= date("Y-m-d H:i:s", strtotime($current_date_time. ' + '.$add_date_time));
		$package_details['package_create_date']	= $current_date_time;
		$package_details['package_expriy_date']	= $package_expriy_date;
		update_post_meta( $order_id, 'package_details',$package_details );

		if(!empty($user_type) && $user_type == 'employers'){
			update_post_meta( $order_id, 'employer_id', $user_id );
			update_user_meta( $user_id, 'employer_package_order_id', $order_id );
			update_user_meta( $user_id, 'remaining_employer_package_details', $package_details );
			update_user_meta( $user_id, 'employer_package_details', $package_details );
			update_user_meta( $user_id, 'employer_package_create_date', $current_date_time );
			update_user_meta( $user_id, 'employer_package_expriy_date', date("Y-m-d H:i:s", strtotime($current_date_time. ' + '.$add_date_time)) );
		} else {
			update_post_meta( $order_id, 'freelancer_id', $user_id );
			update_user_meta( $user_id, 'freelancer_package_details', $package_details );
			update_user_meta( $user_id, 'package_order_id', $order_id );
			update_user_meta( $user_id, 'package_create_date', $current_date_time );
			update_user_meta( $user_id, 'package_expriy_date', date("Y-m-d H:i:s", strtotime($current_date_time. ' + '.$add_date_time)) );
			$linked_profile = get_user_meta($user_id, '_linked_profile',true);
			if(!empty($linked_profile) ){
				do_action('workreap_update_freelancer_badge', $linked_profile,'packages');
			}
		}		

        $order_data    		= get_post_meta( $order_id, 'cus_woo_product_data',true );
        $freelancer_id     	= !empty($user_id) ? intval($user_id) : '' ;
        $order_amount  		= !empty($order_data['price']) ? ($order_data['price']) : '' ;
        $freelancer         = get_user_by( 'id', $freelancer_id );
        $freelancer_name    = !empty($freelancer) ? $freelancer->first_name : '';
        $product    	= wc_get_product( $package_id );
        $product_name  	= $product->get_title();		
        $freelancer_email = !empty($freelancer) ? $freelancer->user_email : '';

		/* prepare data and send email */
		if (class_exists('Workreap_Email_helper')) {
			$emailData = array();
			$emailData['freelancer_name']	= $freelancer_name;
			$emailData['freelancer_email'] 	= $freelancer_email;
			$emailData['order_id'] 		= $order_id;
			$emailData['order_amount'] 	= $order_amount;
			$emailData['package_name'] 	= $product_name;
			$email_package_freelancer	= !empty( $workreap_settings['email_package_freelancer'] ) ? $workreap_settings['email_package_freelancer'] : ''; //email freelancer package

			if(isset($email_package_freelancer) && !empty($email_package_freelancer )){
				if (class_exists('WorkreapPackagesStatuses')) {
					$email_helper = new WorkreapPackagesStatuses();
					$email_helper->package_purchase_freelancer_email($emailData);
					do_action('notification_message', $emailData );
				}
			}
		}

		$order_amount					= get_post_meta( $order_id, '_order_total',true );
		$freelancer_profile_id  			= workreap_get_linked_profile_id($freelancer_id,'','freelancers');
		$notifyData						= array();
        $notifyDetails					= array();
        $notifyDetails['package_id']    = $package_id;
		$notifyDetails['post_link_id']  = !empty( $workreap_settings['tpl_add_service_page'] ) ? $workreap_settings['tpl_add_service_page'] : 0;
        $notifyDetails['freelancer_id']   	= $freelancer_profile_id;
		$notifyDetails['order_id']   	= $order_id;
		$notifyDetails['order_amount'] 	= $order_amount;
        $notifyData['receiver_id']		= $freelancer_id;
        $notifyData['type']			    = 'package_purchases';
        $notifyData['linked_profile']	= $freelancer_profile_id;
        $notifyData['user_type']		= 'freelancers';
        $notifyData['post_data']		= $notifyDetails;
		do_action('workreap_notification_message', $notifyData );
	}
}

/**
 * Add meta on order item
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_woo_convert_item_session_to_order_meta')) {
	add_action( 'woocommerce_new_order_item', 'workreap_woo_convert_item_session_to_order_meta',  1, 3 );
	function workreap_woo_convert_item_session_to_order_meta( $item_id, $item, $order_id ) {
		$payment_type	= !empty($item->legacy_values['payment_type']) ? $item->legacy_values['payment_type'] : '';
		if ( !empty( $item->legacy_values['freelancer_id'] ) ) {
			update_post_meta( $order_id, 'freelancer_id', $item->legacy_values['freelancer_id'] );
		}
		if ( !empty( $item->legacy_values['employer_id'] ) ) {
			update_post_meta( $order_id, 'employer_id', $item->legacy_values['employer_id'] );
		}
		if( !empty($payment_type) && $payment_type === 'tasks' ){
			if ( !empty( $item->legacy_values['cart_data'] ) ) {
				wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
				update_post_meta( $order_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
			}

			if ( !empty( $item->legacy_values['product_id'] ) ) {
				update_post_meta( $order_id, 'task_product_id', $item->legacy_values['product_id'] );
			}

			if ( !empty( $item->legacy_values['payment_type'] ) ) {
				update_post_meta( $order_id, 'payment_type', $item->legacy_values['payment_type'] );
			}

			if ( !empty( $item->legacy_values['post_type'] ) ) {
				update_post_meta( $order_id, 'post_type', $item->legacy_values['post_type'] );
			}

			if ( !empty( $item->legacy_values['offers_id'] ) ) {
				update_post_meta( $order_id, 'offers_id', $item->legacy_values['offers_id'] );
				update_post_meta( $order_id, '_order_task_type', 'custom_offer' );
				if ( !empty( $item->legacy_values['offers_id'] ) ) {
					$contents	    = workreap_offers_order_details($item->legacy_values['cart_data'],$item->legacy_values['product_id'],$item->legacy_values['offers_id'],'return');
					update_post_meta( $order_id, 'order_details', $contents );
					update_post_meta( $item->legacy_values['offers_id'], 'order_id', $order_id );
				}
			}

			if ( !empty( $item->legacy_values['admin_shares'] ) ) {
				update_post_meta( $order_id, 'admin_shares', $item->legacy_values['admin_shares'] );
			}

			if ( !empty( $item->legacy_values['freelancer_shares'] ) ) {
				update_post_meta( $order_id, 'freelancer_shares', $item->legacy_values['freelancer_shares'] );
			}

			if ( !empty( $item->legacy_values['cart_data']['processing_fee'] ) ) {
				wc_add_order_item_meta( $item_id, 'processing_fee', $item->legacy_values['cart_data']['processing_fee'] );
				update_post_meta( $order_id, 'processing_fee', $item->legacy_values['cart_data']['processing_fee'] );
			}

		} else if( !empty($payment_type) && $payment_type === 'package' ){

			if ( !empty( $item->legacy_values['cart_data'] ) ) {
				wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
				update_post_meta( $order_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
			}

			if ( !empty( $item->legacy_values['package_id'] ) ) {
				update_post_meta( $order_id, 'package_id', $item->legacy_values['package_id'] );
			}

			if ( !empty( $item->legacy_values['payment_type'] ) ) {
				update_post_meta( $order_id, 'payment_type', $item->legacy_values['payment_type'] );
			}
			if ( !empty( $item->legacy_values['admin_shares'] ) ) {
				update_post_meta( $order_id, 'admin_shares', $item->legacy_values['admin_shares'] );
			}

			if ( !empty( $item->legacy_values['freelancer_shares'] ) ) {
				update_post_meta( $order_id, 'freelancer_shares', $item->legacy_values['freelancer_shares'] );
			}

		} else if( !empty($payment_type) && $payment_type === 'projects' ){

			if ( !empty( $item->legacy_values['cart_data'] ) ) {
				wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
				update_post_meta( $order_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
			}

			if ( !empty( $item->legacy_values['project_id'] ) ) {
				update_post_meta( $order_id, 'project_id', $item->legacy_values['project_id'] );
			}
			if ( !empty( $item->legacy_values['proposal_id'] ) ) {
				update_post_meta( $order_id, 'proposal_id', $item->legacy_values['proposal_id'] );
			}

			if ( !empty( $item->legacy_values['transaction_id'] ) ) {
				update_post_meta( $order_id, 'transaction_id', $item->legacy_values['transaction_id'] );
			}
			
			if ( !empty( $item->legacy_values['payment_type'] ) ) {
				update_post_meta( $order_id, 'payment_type', $item->legacy_values['payment_type'] );
			}

			if ( !empty( $item->legacy_values['cart_data']['processing_fee'] ) ) {
				wc_add_order_item_meta( $item_id, 'processing_fee', $item->legacy_values['cart_data']['processing_fee'] );
				update_post_meta( $order_id, 'processing_fee', $item->legacy_values['cart_data']['processing_fee'] );
			}

			if ( !empty( $item->legacy_values['cart_data']['milestone_id'] ) ) {
				update_post_meta( $order_id, 'milestone_id', $item->legacy_values['cart_data']['milestone_id'] );
				if(!empty($item->legacy_values['proposal_id'])){
					$proposal_id		= $item->legacy_values['proposal_id'];
					$proposal_meta	    = get_post_meta( $proposal_id, 'proposal_meta',true);
					$proposal_meta	    = !empty($proposal_meta) ? $proposal_meta : array();
					$milestone          = !empty($proposal_meta['milestone']) ? $proposal_meta['milestone'] : array(); 
					if(!empty($milestone[$item->legacy_values['cart_data']['milestone_id']])){
						$milestone[$item->legacy_values['cart_data']['milestone_id']]['milestone_order_id']	= $order_id;
						$proposal_meta['milestone']	= $milestone;
						update_post_meta( $proposal_id, 'proposal_meta',$proposal_meta );
					}
				}
			}
			

		} else if( !empty($payment_type) && $payment_type === 'wallet' ){

			if ( !empty( $item->legacy_values['cart_data'] ) ) {
				wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
				update_post_meta( $order_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
			}

			if ( !empty( $item->legacy_values['wallet_id'] ) ) {
				update_post_meta( $order_id, 'wallet_id', $item->legacy_values['wallet_id'] );
			}

			if ( !empty( $item->legacy_values['payment_type'] ) ) {
				update_post_meta( $order_id, 'payment_type', $item->legacy_values['payment_type'] );
			}
			
		} else {
			do_action( 'workreap_add_workreap_woo_convert_item_session_to_order_meta', $item_id, $item, $order_id );
		}
	}
}

if (!function_exists('workreap_admin_order_summery')) {
	add_action( 'add_meta_boxes', 'workreap_admin_order_summery' );
	function workreap_admin_order_summery() {
		add_meta_box(
			'workreap_shop_order',
			esc_html__('Order extra information','workreap'),
			'workreap_display_order_screen',
			'shop_order'
		);
	}
	function workreap_display_order_screen($post) {
		do_action( 'workreap_display_order_data', $post->ID );
	}
}

/**
 * Display order detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_display_order_data_success')) {
	add_action( 'woocommerce_thankyou', 'workreap_display_order_data_success', 20 );
	function workreap_display_order_data_success( $order_id ) {
		global $woocommerce,$current_user;
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		$order_detail 	= get_post_meta( $order_id, 'cus_woo_product_data', true );
		$payment_type 	= get_post_meta( $order_id, 'payment_type', true );
		$dashboard_url	= !empty($current_user->ID) ? Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $current_user->ID, true, 'insights') : '';
		if( !empty( $order_detail ) && !empty( $payment_type ) && $payment_type == 'tasks' ) {
			$offers_id 		= get_post_meta( $order_id, 'offers_id', true );
			if( !empty($offers_id) ){
				do_action( 'workreap_custom_offer_display_order_data_success', $order_id,$order_detail );
			} else {
				$product_id 	= get_post_meta( $order_id, 'task_product_id', true );
				$product_id		= !empty($product_id) ? $product_id : 0;
				$redirect_url	= !empty($current_user->ID) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $current_user->ID, true, 'detail',$order_id) : '';
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="cart-data-wrap">
							<div class="selection-wrap">
							<?php
								if( !empty( $order_detail ) ){
									$contents			= workreap_tasks_order_details($order_detail,$product_id);
									if( !empty($contents) ){ ?>
										<div class="wr-haslayout">
											<div class="cart-data-wrap">
												<h3><?php esc_html_e('Summary','workreap');?></h3>
												<div class="selection-wrap">
													<?php echo do_shortcode( $contents );?>
												</div>
											</div>
										</div>
									<?php
									}
								}?>
								<div class="wr-go-dbbtn">
									<a href="<?php echo esc_url_raw($redirect_url);?>" class="button"><?php esc_html_e('Go to task','workreap');?></a>
									<a href="<?php echo esc_url_raw($dashboard_url);?>" class="button"><?php esc_html_e('Go to dashboard','workreap');?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
		<?php }
		} else if( !empty( $order_detail ) && !empty( $payment_type ) && $payment_type == 'package' ) {
			$product_id 	= get_post_meta( $order_id, 'package_id', true );
			$product_id		= !empty($product_id) ? $product_id : 0;
			$package		= wc_get_product($product_id);
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="cart-data-wrap">
						<div class="selection-wrap">
							<div class="wr-haslayout">
								<div class="cart-data-wrap">
									<h3><?php esc_html_e('Summary','workreap');?></h3>
									<div class="selection-wrap">
										<?php do_action('workreap_package_details', $package,false );?>
									</div>
								</div>
								<?php if( !empty($redirect_url) ){?>
							        <div class="wr-go-dbbtn">
										<a href="<?php echo esc_url_raw($redirect_url);?>" class="button"><?php esc_html_e('Go to dashboard','workreap');?></a>
								    </div>
							   <?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}  else if( !empty($order_detail) && !empty( $payment_type )  && $payment_type == 'projects' ) {
			$proposal_id	= get_post_meta( $order_id, 'proposal_id', true );
			$project_url	= !empty($proposal_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $current_user->ID, true, 'activity',$proposal_id) : '';
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="cart-data-wrap">
						<div class="selection-wrap">
							<div class="wr-haslayout">
								<div class="cart-data-wrap">
									<h3><?php esc_html_e('Summary','workreap');?></h3>
									<div class="selection-wrap">
										<?php do_action('workreap_cart_project_details', $order_detail );?>
									</div>
								</div>
								<?php if( !empty($redirect_url) ){?>
							        <div class="wr-go-dbbtn">
										<a href="<?php echo esc_url_raw($redirect_url);?>" class="button"><?php esc_html_e('Go to dashboard','workreap');?></a>
								    </div>
							   <?php } ?>
							   <div class="wr-go-dbbtn">
									<a href="<?php echo esc_url_raw($project_url);?>" class="button"><?php esc_html_e('Go to project','workreap');?></a>
									<a href="<?php echo esc_url_raw($dashboard_url);?>" class="button"><?php esc_html_e('Go to dashboard','workreap');?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} else if( !empty( $payment_type ) && $payment_type == 'wallet' ){
			if (session_status() === PHP_SESSION_NONE) {
				session_start();
			}
			$redirect_url   = !empty($_SESSION["redirect_url"]) ? $_SESSION["redirect_url"] : '';
			$type           = !empty($_SESSION["redirect_type"]) ? $_SESSION["redirect_type"] : '';
			if( !empty($type) && $type == 'wallet_checkout' && !empty($redirect_url)){
				unset($_SESSION['redirect_url']);
				unset($_SESSION['redirect_type']); 
				if( !empty($redirect_url) ){?>
					<a href="<?php echo esc_url_raw($redirect_url);?>" class="button"><?php esc_html_e('Go back to cart page','workreap');?></a>
					<a href="<?php echo esc_url_raw($dashboard_url);?>" class="button"><?php esc_html_e('Go to dashboard','workreap');?></a>
			<?php }
			}
		} else {
			do_action( 'workreap_add_display_order_data_success', $order_detail );
		}
	}
}

/**
 * Display order detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_display_order_data')) {
	add_action( 'workreap_display_order_data', 'workreap_display_order_data', 20 );
	add_action( 'woocommerce_view_order', 'workreap_display_order_data', 20 );
	function workreap_display_order_data( $order_id ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		$order_detail 	= get_post_meta( $order_id, 'cus_woo_product_data', true );
		$payment_type 	= get_post_meta( $order_id, 'payment_type', true );
		if( !empty( $order_detail ) && !empty( $payment_type ) && $payment_type == 'tasks' ) {
			$product_id 	= get_post_meta( $order_id, 'task_product_id', true );
			$product_id		= !empty($product_id) ? $product_id : 0;
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="cart-data-wrap">
						<div class="selection-wrap">
						<?php
							if( !empty( $order_detail ) ){
								$contents			= workreap_tasks_order_details($order_detail,$product_id);
								if( !empty($contents) ){ ?>
									<div class="wr-haslayout">
										<div class="cart-data-wrap">
											<h3><?php esc_html_e('Summary','workreap');?></h3>
											<div class="selection-wrap">
												<?php echo do_shortcode( $contents );?>
											</div>
										</div>
									</div>
								<?php
								}
							}?>
						</div>
					</div>
				</div>
			</div>
		<?php } else if( !empty( $order_detail ) && !empty( $payment_type ) && $payment_type == 'package' ) {
			$product_id 	= get_post_meta( $order_id, 'package_id', true );
			$product_id		= !empty($product_id) ? $product_id : 0;
			$package		= wc_get_product($product_id);
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="cart-data-wrap">
						<div class="selection-wrap">
							<div class="wr-haslayout">
								<div class="cart-data-wrap">
									<h3><?php esc_html_e('Summary','workreap');?></h3>
									<div class="selection-wrap">
										<?php do_action('workreap_package_details', $package,false );?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} else if( !empty( $order_detail ) && !empty( $payment_type ) && $payment_type == 'projects' ) {
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="cart-data-wrap">
						<div class="selection-wrap">
							<div class="wr-haslayout">
								<div class="cart-data-wrap">
									<h3><?php esc_html_e('Summary','workreap');?></h3>
									<div class="selection-wrap">
									<?php do_action('workreap_cart_project_details', $order_detail );?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}else {
			do_action( 'workreap_add_apply_wallet_amount', $order_detail );
		}
	}
}

/**
 * Price total override
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_apply_wallet_amount')) {
	add_action( 'woocommerce_cart_calculate_fees','workreap_apply_wallet_amount',10,1);
	function workreap_apply_wallet_amount( $cart_object ) {
		global $current_user,$workreap_settings;
		$commission_text            =  !empty($workreap_settings['commission_text']) ? $workreap_settings['commission_text'] : esc_html__('Processing fee', 'workreap');

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ){
			return;
		}
		
		$fee	= 0.0;

		if( !WC()->session->__isset( "reload_checkout" )) {
			$item_count 	= 0;
			foreach ( $cart_object->cart_contents as $key => $value ) {
				if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'tasks' ){
					if( isset( $value['cart_data']['wallet_price'] ) ){
						WC()->cart->add_fee( esc_html__('Wallet amount','workreap'), -($value['cart_data']['wallet_price']) );
					}
					$fee	= !empty($value['cart_data']['processing_fee']) ? $value['cart_data']['processing_fee'] : 0.0;
				} else if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'projects' ){
					if( isset( $value['cart_data']['wallet_price'] ) ){
						WC()->cart->add_fee( esc_html__('Wallet amount','workreap'), -($value['cart_data']['wallet_price']) );
					}

					$fee	= !empty($value['cart_data']['processing_fee']) ? $value['cart_data']['processing_fee'] : 0.0;
				} else if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'hourly' ){
					$fee	= !empty($value['cart_data']['processing_fee']) ? $value['cart_data']['processing_fee'] : 0.0;
				} else {
					do_action( 'workreap_add_apply_wallet_amount', $value );
				}

				$item_count++;
			}

			
			if(!empty($fee)){
//				$fee = $item_count *  $fee;
				WC()->cart->add_fee( $commission_text, $fee, false );
			}

		}
	}
}

/**
 * Price override
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_apply_custom_price_to_cart_item')) {
	add_action( 'woocommerce_before_calculate_totals', 'workreap_apply_custom_price_to_cart_item', 99 );
	function workreap_apply_custom_price_to_cart_item( $cart_object ) {
		if( !WC()->session->__isset( "reload_checkout" )) {
			foreach ( $cart_object->cart_contents as $key => $value ) {
				$product 		= $value['data'];
				$product_id		= !empty($value['product_id']) ? $value['product_id'] : 0;

				if( isset( $value['cart_data']['price'] ) && !empty( $value['payment_type'] ) && in_array($value['payment_type'],array('tasks','wallet','projects')) ){
					$bk_price = floatval( $value['cart_data']['price'] );
					$value['data']->set_price($bk_price);

					if( !empty( $value['payment_type']) && $value['payment_type'] === 'projects' && isset($value['cart_data']['product_name']) ){
						$value['data']->set_name( $value['cart_data']['product_name'] );
					}

                } else {
					do_action( 'workreap_add_apply_custom_price_to_cart_item', $value );
				}	
				// else if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'wallet' ){
				// 	if( isset( $value['cart_data']['price'] ) ){
				// 		$bk_price = floatval( $value['cart_data']['price'] );
				// 		$value['data']->set_price($bk_price);
				// 	}
				// }
			}
		}
	}
}

/**
 * Add data in checkout
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_add_new_fields_checkout')) {
	add_filter( 'woocommerce_checkout_after_customer_details', 'workreap_add_new_fields_checkout', 10, 1 );
	function workreap_add_new_fields_checkout() {
		global $product,$woocommerce;
		$cart_data = WC()->session->get( 'cart', null );
		if( !empty( $cart_data ) ) {
			foreach( $cart_data as $key => $cart_items ){

				if( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'tasks' && !empty($cart_items['offers_id']) ) {
					do_action( 'workreap_custom_offer_details', $cart_items );
				} else if( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'tasks' ) {
					$product_id				= !empty($cart_items['product_id']) ? $cart_items['product_id'] : 0;

					if( !empty( $cart_items['cart_data'] ) ){
						$contents			= workreap_tasks_order_details($cart_items['cart_data'],$product_id);
						if( !empty($contents) ){ ?>
							<div class="wr-haslayout">
								<div class="cart-data-wrap">
								<h3><?php esc_html_e('Summary','workreap');?></h3>
								<div class="selection-wrap">
									<?php echo do_shortcode( $contents );?>
								</div>
								</div>
							</div>
						<?php
						}
					}
				} else if( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'package' ) {

					$product_id		= !empty($cart_items['package_id']) ? $cart_items['package_id'] : 0;
					$package		= wc_get_product($product_id);
					?>
					<div class="wr-haslayout">
						<div class="cart-data-wrap">
							<h3><?php esc_html_e('Summary','workreap');?></h3>
							<div class="selection-wrap">
								<?php do_action('workreap_package_details', $package,false );?>
							</div>
						</div>
					</div>
					<?php

				} else if( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'projects' ) {
					$cart_data	= !empty($cart_items['cart_data']) ? $cart_items['cart_data'] : array();

					?>
					<div class="wr-haslayout wr-project-checkout">
						<div class="cart-data-wrap">
							<h3><?php esc_html_e('Summary','workreap');?></h3>
							<div class="selection-wrap">
								<?php do_action('workreap_cart_project_details', $cart_data );?>
							</div>
						</div>
					</div>
				<?php
				} else {
					do_action('workreap_after_cart_details', $cart_items );
				}
			}
		}
	}
}

/**
 * Print order meta at package order detail page
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_task_custom_fields')) {
	function workreap_task_custom_fields( $post_id,$plan_key) {
		global $workreap_settings;
		$custom_field_option    =  !empty($workreap_settings['custom_field_option']) ? $workreap_settings['custom_field_option'] : false;
		$contents_array			= array();
		$contents				= "";
		$wr_custom_fields		= array();
		if( !empty($custom_field_option) ){
			$wr_custom_fields       = get_post_meta( $post_id, 'wr_custom_fields',true );
			$wr_custom_fields       = !empty($wr_custom_fields) ? $wr_custom_fields : array();
			if( !empty($wr_custom_fields) ){
				foreach($wr_custom_fields as $field_value){
					if( !empty($field_value['title']) ){
						$plan_value		= !empty($field_value[$plan_key]) ? $field_value[$plan_key] : '';
						$contents		.='<li class="user-acf-created"><span>'.esc_html($field_value['title']).'</span><em>('.esc_html($plan_value).')</em></li>';
					}
				}
			}
		}
		$contents_array['contents']			= $contents;
		$contents_array['wr_custom_fields']	= $wr_custom_fields;
		return $contents_array;
	}
}
/**
 * Print order meta at package order detail page
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_task_package_details')) {
	function workreap_task_package_details( $acf_field,$plan_value) {
		$tab_contents	= '';
		if(!empty($acf_field['type']) && in_array($acf_field['type'],array('text','textarea','number', 'radio'))){
			$tab_contents	.='<li><span>'.esc_html($acf_field['label']).'</span>(<em>'.esc_html($plan_value).'</em>)</li>';
		} else if(!empty($acf_field['type']) && $acf_field['type'] === 'url'){
			$tab_contents	.='<li><span>'.esc_html($acf_field['label']).'</span><em><a href="'.esc_url($plan_value).'" target="_blank">'.esc_html($plan_value).'</a></em></li>';
		} else if(!empty($acf_field['type']) && $acf_field['type'] === 'select'){

			if(!empty($plan_value) && is_array($plan_value) && count($plan_value)>0){
				$plan_value	= implode(', ', $plan_value);
			}

			$tab_contents	.='<li><span>'.esc_html($acf_field['label']).'</span><em>'.esc_html($plan_value).'</em></li>';
		} else if(!empty($acf_field['type']) && $acf_field['type'] === 'email'){
			$tab_contents	.='<li><span>'.esc_html($acf_field['label']).'</span><em><a href="mailto:'.esc_attr($plan_value).'" target="_blank">'.esc_html($plan_value).'</a></em></li>';
		} else if(!empty($acf_field['type']) && in_array($acf_field['type'],array('checkbox'))){
			$class 			= !empty($plan_value) && $plan_value === 'yes' ? 'wr-available' : 'wr-unavailable';
			$tab_contents	.='<li class="'.esc_attr($class).'"><span class="'.esc_attr($class).'">'.esc_html($acf_field['label']).'</span></li>';
		}
		return $tab_contents;
	}
}

/**
 * Print task details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_tasks_order_details')) {
	function workreap_tasks_order_details( $cart_items,$product_id=0,$type='') {
		$workreap_plans_values 	= get_post_meta($product_id, 'workreap_product_plans', TRUE);
		$workreap_plans_values	= !empty($workreap_plans_values) ? $workreap_plans_values : array();
		$subtasks				= !empty($cart_items['subtasks']) ? $cart_items['subtasks'] : array();
		$task_pan				= !empty($cart_items['task']) ? $cart_items['task'] : '';
		$product_cat 			= wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
		$workreap_subtask 		= get_post_meta($product_id, 'workreap_product_subtasks', TRUE);
		$plan_array				= array(
									'product_tabs' 			=> array('plan'),
									'product_plans_category'=> $product_cat
								);

		$acf_fields				= workreap_acf_groups($plan_array);
		$title					= !empty($workreap_plans_values[$task_pan]['title']) ? $workreap_plans_values[$task_pan]['title'] : '';
		$description			= !empty($workreap_plans_values[$task_pan]['description']) ? $workreap_plans_values[$task_pan]['description'] : '';
		$price					= !empty($workreap_plans_values[$task_pan]['price']) ? $workreap_plans_values[$task_pan]['price'] : '';
		$tab_contents			= '';
		$order_contents					= array();
		$order_contents['key']			= $task_pan;
		$order_contents['title']		= $title;
		$order_contents['description']	= $description;
		$order_contents['price']		= $price;
		$fields							= array();
		$contents_array					= workreap_task_custom_fields($product_id,$task_pan);
		if( !empty($acf_fields) || !empty($contents_array['wr_custom_fields']) ){
			$counter_checked	= 0;
			$tab_contents	.='<div class="wr-sectiontitle wr-sectiontitlev2">';
			if( !empty($title) ){
				$tab_contents	.='<h3 class="wr-theme-color">'.esc_html($title).'</h3>';
			}
			if( !empty($price) ){
				$tab_contents	.='<h4 class="wr-theme-color">'.workreap_price_format($price,'return').'</h4>';
			}
			if( !empty($description) ){
				$tab_contents	.='<p>'.esc_html($description).'</p>';
			}
			$tab_contents	.='<div class="wr-sectiontitle__list--title"><h6>'.esc_html__('Features included','workreap').'</h6><ul class="wr-sectiontitle__list wr-sectiontitle__listv2">';
			if( !empty($acf_fields)){
				foreach($acf_fields as $acf_field ){
					$plan_value	= !empty($acf_field['key']) && !empty($workreap_plans_values[$task_pan][$acf_field['key']]) ? $workreap_plans_values[$task_pan][$acf_field['key']] : '--';
					$counter_checked++;
					$tab_contents	.= workreap_task_package_details($acf_field,$plan_value);
					$field_values					= $acf_field;
					$field_values['selected_val']	= $plan_value;
					$fields[]	= $field_values;
				}
			}
			$tab_contents							.= !empty($contents_array['wr_custom_fields']) ? $contents_array['contents'] : '';
			$order_contents['wr_custom_fields']		= !empty($contents_array['wr_custom_fields']) ? $contents_array['wr_custom_fields'] : array();
			$order_contents['price']				= $price;
			$tab_contents	.='</ul></div>';
			$order_contents['fields']	= $fields;

			if( !empty($subtasks) ){
				$tab_contents	.='<div class="wr-sectiontitle__list--title"><h6>'.esc_html__('Sub tasks','workreap').'</h6><ul class="wr-sectiontitle__list wr-sectiontitle__listv2">';
				foreach($subtasks as $workreap_subtask_id ){
					$subtask_title	= get_the_title($workreap_subtask_id);
					$order_contents['subtasks'][$workreap_subtask_id]['id']	= $workreap_subtask_id;
					$subtask_price 	= wc_get_product( $workreap_subtask_id );
					$subtask_price	= !empty($subtask_price) ? $subtask_price->get_regular_price() : '';
					$order_contents['subtasks'][$workreap_subtask_id]['price']	= $subtask_price;
					$order_contents['subtasks'][$workreap_subtask_id]['title']	= $subtask_title;
					$order_contents['subtasks'][$workreap_subtask_id]['content']	= apply_filters( 'the_content', get_the_content(null, false, $workreap_subtask_id));

					$tab_contents	.='<li><span>'.esc_html($subtask_title).'</span><em>('.esc_html($subtask_price).')</em></li>';
				}
				$tab_contents	.='</ul></div>';
			}
			$tab_contents	.='</div>';
		}
		if( !empty($type) && $type == 'return' ){
			return $order_contents;
		}
		return $tab_contents;
	}
}


/**
 * Place order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_place_order')) {
	function workreap_place_order($user_id,$type='',$dispute_id='') {
		global $woocommerce;
		if( class_exists('WooCommerce') ) {
			$first_name         = get_user_meta( $user_id, 'billing_first_name',true );
			$last_name          = get_user_meta( $user_id, 'billing_last_name',true );
			$billing_city       = get_user_meta( $user_id, 'billing_city',true );
			$billing_email      = get_user_meta( $user_id, 'billing_email',true );
			$billing_postcode   = get_user_meta( $user_id, 'billing_postcode',true );
			$billing_phone      = get_user_meta( $user_id, 'billing_phone',true );
			$billing_state      = get_user_meta( $user_id, 'billing_state',true );
			$billing_country    = get_user_meta( $user_id, 'billing_country',true );

			$address_1         = get_user_meta( $user_id, 'billing_address_1',true );
			$billing_company   = get_user_meta( $user_id, 'billing_company',true );
			$address_2         = get_user_meta( $user_id, 'billing_address_2',true );

			$billing_email      = !empty($billing_email) ? $billing_email : get_userdata($user_id)->user_email;
			$first_name         = !empty($first_name) ? $first_name : '';
			$last_name          = !empty($last_name) ? $last_name : '';
			$billing_city       = !empty($billing_city) ? $billing_city : '';
			$billing_postcode   = !empty($billing_postcode) ? $billing_postcode : '';
			$billing_phone      = !empty($billing_phone) ? $billing_phone : '';
			$address_1      	= !empty($address_1) ? $address_1 : '';
			$address_2      	= !empty($address_2) ? $address_2 : '';
			
			$address = array(
				'first_name' => $first_name,
				'last_name'  => $last_name,
				'company'    => $billing_company,
				'email'      => $billing_email,
				'phone'      => $billing_phone,
				'address_1'  => $address_1,
				'address_2'  => $address_2,
				'city'       => $billing_city,
				'state'      => $billing_state,
				'postcode'   => $billing_postcode,
				'country'    => $billing_country
			);
			$order_data = array(
				'status'        => apply_filters('woocommerce_default_order_status', 'completed'),
				'customer_id'	=> $user_id
			);
			
			$order 		= wc_create_order( array('customer_id' => $user_id ) );
			$order_id 	= $order->get_id();
			$items 		= WC()->cart->get_cart();
			foreach($items as $item => $values) {
				$item_id = $order->add_product(
                    $values['data'], $values['quantity'], array(
                		'variation' => $values['variation'],
						'totals' => array(
							'subtotal' 		=> $values['line_subtotal'],
							'subtotal_tax' 	=> $values['line_subtotal_tax'],
							'total' 		=> $values['line_total'],
							'tax' 			=> $values['line_tax'],
							'tax_data' 		=> $values['line_tax_data']
						)
                    )
            	);
				if( !empty($item_id) ){
					if ( !empty( $values['cart_data'] ) ) {
						wc_update_order_item_meta( $item_id, 'cus_woo_product_data', $values['cart_data'] );
						update_post_meta( $order_id, 'cus_woo_product_data', $values['cart_data'] );
						if( !empty( $values['payment_type'] ) && $values['payment_type'] == 'tasks' ){
							if(!empty($value['offers_id']) ){
								do_action('workreap_update_custom_offer_data',$order_id,$values['cart_data']);
							} else {
								workreap_update_tasks_data( $order_id,$values['cart_data'] );
							}
							
						} else if( !empty($values['payment_type']) && $values['payment_type'] === 'wallet' ){
							update_post_meta( $order_id, 'wallet_id', $values['wallet_id'] );
						}
					}

					if ( !empty( $values['cart_data']['task_id'] ) ) {
						update_post_meta( $order_id, 'task_product_id', $values['cart_data']['task_id'] );
					}
					
					if ( !empty( $values['cart_data']['freelancer_id'] ) ) {
						update_post_meta( $order_id, 'freelancer_id', $values['cart_data']['freelancer_id'] );
					}
					if ( !empty( $values['cart_data']['employer_id'] ) ) {
						update_post_meta( $order_id, 'employer_id', $values['cart_data']['employer_id'] );
					}

					if ( !empty( $values['payment_type'] ) ) {
						update_post_meta( $order_id, 'payment_type', $values['payment_type'] );
					}

					if ( !empty( $values['admin_shares'] ) ) {
						update_post_meta( $order_id, 'admin_shares', $values['admin_shares'] );
					}

					if ( !empty( $values['freelancer_shares'] ) ) {
						update_post_meta( $order_id, 'freelancer_shares', $values['freelancer_shares'] );
					}
				}
			}

            $processing_fee = WC()->cart->get_fees();

			if(isset($processing_fee) && isset($processing_fee['processing-fee']) && isset($processing_fee['processing-fee']->total)){
                $fee = new WC_Order_Item_Fee();
                $fee->set_name($processing_fee['processing-fee']->name);
                $fee->set_total($processing_fee['processing-fee']->total);
                $order->add_item($fee);
            }

			$order->set_address( $address, 'billing' );
			$order->set_address( $address, 'shipping' );
			$order->calculate_totals();
			$order_id 		= $order->get_id();
			$order_id		= !empty($order_id) ? $order_id : 0;

			if( !empty($type) && $type === 'wallet' ){
				update_post_meta( $order_id, 'employer_id',$user_id );
				update_post_meta( $dispute_id, '_dispute_order_id',$order_id );
				update_post_meta( $order_id, '_dispute_order_id',$dispute_id );
				$dispute_order  = get_post_meta( $dispute_id, '_dispute_order', true );
            	$dispute_order  = !empty($dispute_order) ? intval($dispute_order) : 0;
				update_post_meta( $dispute_order, '_dispute_order_id',$dispute_id );
			} else if( !empty($type) && $type === 'task-wallet' ){
				$get_total  	= $order->get_total();
				if(function_exists('wmc_revert_price')){
					$get_total  = wmc_revert_price($order->get_total(),$order->get_currency());
				} 
				$user_balance   = !empty($user_id) ? get_user_meta( $user_id, '_employer_balance',true ) : '';
				$user_balance   = !empty($user_balance) ? ($user_balance-$get_total) : 0;
				update_user_meta( $user_id,'_employer_balance',$user_balance);
				update_post_meta( $order_id, '_wallet_amount', $get_total );
				update_post_meta( $order_id, '_task_type', 'wallet' );
			} else if( !empty($type) && $type === 'project-wallet' ){
				$get_total  	= $order->get_total();
				if(function_exists('wmc_revert_price')){
					$get_total  = wmc_revert_price($order->get_total(),$order->get_currency());
				} 
				$user_balance   = !empty($user_id) ? get_user_meta( $user_id, '_employer_balance',true ) : '';
				$user_balance   = !empty($user_balance) ? ($user_balance-$get_total) : 0;
				update_user_meta( $user_id,'_employer_balance',$user_balance);
				update_post_meta( $order_id, '_wallet_amount', $get_total );
				update_post_meta( $order_id, '_task_type', 'wallet' );
			} else if( !empty($type) && $type === 'employer-wallet' ){
				workreap_update_wallet_data( $order_id,$values['cart_data'],$user_id);
			} else if( !empty($type) && $type === 'package' ){
				workreap_update_packages_data( $order_id,$values,$user_id);
				update_post_meta( $order_id, 'package_id',$dispute_id );
			} else {
				$get_total  	= $order->get_total();
				if(function_exists('wmc_revert_price')){
					$get_total  = wmc_revert_price($order->get_total(),$order->get_currency());
				} 
				$user_balance   = !empty($user_id) ? get_user_meta( $user_id, '_employer_balance',true ) : '';
				$user_balance   = !empty($user_balance) ? ($user_balance-$get_total) : 0;
				update_user_meta( $user_id,'_employer_balance',$user_balance);
				update_post_meta( $order_id, '_wallet_amount', $get_total );
				update_post_meta( $order_id, '_task_type', 'wallet' );
			}
			workreap_complete_order($order_id);
			WC()->cart->empty_cart();
			return $order_id;
		}
	}
}

/**
 * update order query var
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( !function_exists( 'workreap_custom_query_var' ) ) {
	function workreap_custom_query_var( $query, $query_vars ) {
		if ( ! empty( $query_vars['freelancer_id'] ) ) {
			$query['meta_query'][] = array(
				'key' 	=> 'freelancer_id',
				'value' => intval( $query_vars['freelancer_id'] ),
			);
		}

		if ( ! empty( $query_vars['employer_id'] ) ) {
			$query['meta_query'][] = array(
				'key' 	=> 'employer_id',
				'value' => intval( $query_vars['employer_id'] ),
			);
		}

		if ( ! empty( $query_vars['payment_type'] ) ) {
			if( is_array($query_vars['payment_type']) ){
				$query['meta_query'][] = array(
					'key' 	=> 'payment_type',
					'value' =>  $query_vars['payment_type'],
					'compare' => 'IN'
				);
			} else {
				$query['meta_query'][] = array(
					'key' 	=> 'payment_type',
					'value' => esc_html( $query_vars['payment_type'] ),
				);
			}
		}

		if ( ! empty( $query_vars['proposal_id'] ) ) {
			$query['meta_query'][] = array(
				'key' 	=> 'proposal_id',
				'value' => esc_html( $query_vars['proposal_id'] ),
			);
		}

		return $query;
	}
	add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'workreap_custom_query_var', 10, 2 );
}

/**
 * Woocommerce update price tax
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( !function_exists( 'workreap_remove_tax' ) ) {
	function workreap_remove_tax( $tax_class, $product ) {
		$wallet_id	= workreap_employer_wallet_create();
		$product_id	= $product->get_id();
		if(!empty($product_id) && !empty($wallet_id) && $product_id === $wallet_id ){
			$tax_class = 'zero-rate';
		}
		
		return $tax_class;
	}
	add_filter( 'woocommerce_product_get_tax_class', 'workreap_remove_tax', 1, 2 );
}
/**
 * Hide order afin button after checkout
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );

/**
 * Replace product item name
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists('workreap_woocommerce_display_item_meta')){
	add_filter('woocommerce_display_item_meta', 'workreap_woocommerce_display_item_meta', 10, 3);
	function workreap_woocommerce_display_item_meta($html, $item, $args) {
		
		$meta_data 		= $item->get_meta_data();
		$custom_data 	= array();
		if(!empty($meta_data)){
			foreach ($meta_data as $meta) {
				if ($meta->key == 'cus_woo_product_data') {
					$custom_data = $meta->value;
					break;
				}
			}
			
			if($args['echo'] == 1 && !empty($custom_data['payment_type']) && in_array($custom_data['payment_type'],array('tasks','projects','package'))){
				$html = '';
			}
		}
		return $html;
	}
}

/**
 * Replace product name
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists('workreap_woocommerce_order_item_permalink')){
	add_filter('woocommerce_order_item_name', 'workreap_woocommerce_order_item_permalink', 10, 3);
	function workreap_woocommerce_order_item_permalink($name_html, $item, $is_visible) {
		$meta_data 		= !empty($item) ? $item->get_meta_data() : array();
		$custom_data 	= array();
		if(!empty($meta_data)){
			foreach ($meta_data as $meta) {
				if ($meta->key == 'cus_woo_product_data') {
					$custom_data = $meta->value;
					break;
				}
			}
			if(!empty($custom_data['payment_type']) && $custom_data['payment_type'] === 'projects'){
				if(!empty($custom_data['milestone_id'])){
					$name_html	= !empty($custom_data['proposal_meta']['milestone'][$custom_data['milestone_id']]['title']) ? $custom_data['proposal_meta']['milestone'][$custom_data['milestone_id']]['title'] : $name_html;
				}

			} else if(!empty($custom_data['payment_type']) && $custom_data['payment_type'] === 'wallet'){
				$name_html	= !empty($custom_data['product_name']) ? $custom_data['product_name'] : $name_html;
			}
		}
		return $name_html;
	}
}
