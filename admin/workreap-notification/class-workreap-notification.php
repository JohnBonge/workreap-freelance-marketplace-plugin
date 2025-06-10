<?php
/**
 *
 * Class 'Workreap_Notifications' defines to remove the product data default tabs
 *
 * @package     Workreap
 * @subpackage  Workreap/admin
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

class Workreap_Notifications {

	private static $instance = null;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add action hooks
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action( 'workreap_notification_message',  array(&$this, 'workreap_notification_message'),10,1);
		add_action( 'workreap_single_message',  array(&$this, 'workreap_single_message'),10,3);
		add_filter( 'workreap_single_message_filter',  array(&$this, 'workreap_single_message'),10,3);
		add_action( 'workreap_message_content',  array(&$this, 'workreap_message_content'),10,3);
		add_action( 'wp_ajax_workreap_update_notifications',  array(&$this, 'workreap_update_notifications'));
	}

	/**
	 * Update notifications
	 *
	 * @since    1.0.0
	*/
	public function workreap_update_notifications() {
		global $current_user;
        $json = array();
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        $do_check = check_ajax_referer('ajax_nonce', 'security', false);

        if ( $do_check == false ) {
            $json['type'] 			= 'error';
            $json['message'] 	= esc_html__('Oops!', 'workreap');
            $json['message_desc'] 	= esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }
		$post_id		= !empty($_POST['post_id']) ? $_POST['post_id'] : 0;
		$post_author	= !empty($post_id) ? get_post_field( 'post_author', $post_id ) : 0;
		if( !empty($post_author) && $post_author == $current_user->ID ){
			$notifications_updates[]= $post_id;
			update_post_meta( $post_id, 'status', 1 );
			$json['type']	= 'success';
		}
		wp_send_json( $json );
	}

	/**
	 * Save notifications
	 *
	 * @since    1.0.0
	*/
	public function workreap_notification_message($notifyData=array()) {
		global $workreap_notification;
		$notification		= !empty($workreap_notification['notify_module']) ? $workreap_notification['notify_module'] : '';
		$notify_option		= !empty($notifyData['type']) ? ($notifyData['type'].'_notify_module') : '';

		if( !empty($notification) && !empty($notify_option) ){
			$author_id		= !empty($notifyData['receiver_id']) ? intval($notifyData['receiver_id']) : 0;
			$notify_key		= !empty($notifyData['type']) ? ($notifyData['type'].'_notify_content') : '';
			$notify_content	= !empty($workreap_notification[$notify_key]) ? $workreap_notification[$notify_key] : '';

			$flash_message		= !empty($notifyData['type']) ? esc_html($notifyData['type'].'_flash_message') : '';

			$post_contents  = array(
				'post_title'    => wp_strip_all_tags( $notify_content ),
				'post_status'   => 'publish',
				'post_content'  => $notify_content,
				'post_author'   => $author_id,
				'post_type'     => 'notification',
			);

			$post_id = wp_insert_post( $post_contents );
			unset($notifyData['receiver_id']);
			foreach($notifyData as $key => $val ){
				update_post_meta( $post_id, $key, $val);
			}

			update_post_meta( $post_id, 'status', 0);
			$pusher_notification	= !empty($workreap_notification['pusher_notification']) ? $workreap_notification['pusher_notification'] : '';

			if( !empty($pusher_notification) ){
				$linked_profile			= !empty($notifyData['linked_profile'])? intval($notifyData['linked_profile']) : 0;
				$data['pusher_type']	= 'notification';
				$data['post_id']		= !empty($notifyData['linked_profile'])? intval($notifyData['linked_profile']) : 0;
				ob_start();
				$args['linked_profile']	= $linked_profile;
				workreap_get_template_part('dashboard/dashboard', 'list-notification', $args);
				$data['flash_message_html']	= '';

				if( !empty($flash_message) ){
					$data['flash_message_html']	= apply_filters( 'workreap_single_message_filter',$post_id,false );
				}

				$data['message_html']	= ob_get_clean();
				do_action('workreap_pusher_notification',$data);
			}
		}
	}

	/**
	 * filter message
	 *
	 * @since    1.0.0
	*/
	public function workreap_single_message($post_id='',$listing_type=true,$show_option='') {
		$type			= get_post_meta( $post_id, 'type', true );
		$type			= !empty($type) ? $type : '';
		$msg_read		= get_post_meta( $post_id, 'status', true );
		$msg_read		= !empty($msg_read) ? $msg_read : 0;

		$linked_profile	= get_post_meta( $post_id, 'linked_profile', true );
		$linked_profile	= !empty($linked_profile) ? intval($linked_profile) : 0;
		$settings       = apply_filters( 'workreap_list_notification', 'settings', $type );
		$image_html		= $this->workreap_notification_image($post_id,$settings,$show_option);
		$button_html	= $this->workreap_notification_button($post_id,$settings,$show_option);
		$content_html	= $this->workreap_notification_content($post_id,$settings);
		$admin_comments	= !empty($settings['admin_comments']) ? $settings['admin_comments'] : '';

		ob_start();
		?>
		<div class="wr-notification">
			<span class="wr-noti_icon">
				<?php if( empty($msg_read) ){?>
					<em class="wr-noti_new wr_read_notification" data-post_id="<?php echo intval($post_id);?>" title="<?php esc_attr_e('Mark as read','workreap');?>"></em>
				<?php } ?>
				<?php echo do_shortcode( $image_html );?>
			</span>
			<div class="wr-notification-info">
				<p><?php echo nl2br(do_shortcode($content_html));?></p>
				<div class="wr-noti-status">
					<em><?php echo sprintf( _x( '%s ago', '%s = human-readable time difference', 'workreap' ), human_time_diff( get_post_time( 'U',false,$post_id ), current_time( 'timestamp' ) ) ); ?></em>
					<?php if( empty($msg_read) ){?>
						<a href="#" class="wr_read_notification" title="<?php esc_attr_e('Mark as read','workreap');?>" data-post_id="<?php echo intval($post_id);?>"><em><?php esc_attr_e('Mark as read','workreap');?></em></a>
					<?php } ?>
				</div>
				<?php if( !empty($button_html) && empty($show_option) ){ ?>
					<div class="wr-viewtask"><?php echo do_shortcode($button_html);?></div>
				<?php } ?>
				<?php
					if( !empty($admin_comments) && $admin_comments === 'yes' && !empty($show_option) && $show_option === 'listing'){
						$post_data		= get_post_meta( $post_id, 'post_data', true);
						$post_data		= !empty($post_data) ? $post_data : array();
						$admin_feedback	= !empty($post_data['admin_feedback']) ? $post_data['admin_feedback'] : '';
						if( !empty($admin_feedback) ){ ?>
						<p class="wr-admincomment">
							<strong><?php esc_html_e('Admin comment','workreap');?></strong>
							<?php echo esc_html($admin_feedback);?>
						</p>
					<?php }
					}
				?>
			</div>
		</div>
		<?php if(!empty($show_option) && $show_option === 'listing' && !empty($button_html) ){?>
			<div class="wr-viewtask"><?php echo do_shortcode($button_html);?></div>
		<?php } ?>
		<?php
		if( !empty($admin_comments) && $admin_comments === 'yes' && empty($show_option) ){
			$post_data		= get_post_meta( $post_id, 'post_data', true);
			$post_data		= !empty($post_data) ? $post_data : array();
			$admin_feedback	= !empty($post_data['admin_feedback']) ? $post_data['admin_feedback'] : '';
			if( !empty($admin_feedback) ){ ?>
				<p class="wr-admincomment">
					<strong><?php esc_html_e('Admin comment','workreap');?></strong>
					<?php echo esc_html($admin_feedback);?>
				</p>
			<?php }
		}

		if( empty($listing_type)){
			$meata_keys     = array( 'linked_profile'=>$linked_profile,'status'=>0);
			$unread_message = workreap_post_count('notification','publish',$meata_keys);
			$data['message_html']		= ob_get_clean();
			$data['unread_messages']	= $unread_message;
			return $data;
		} else {
			echo ob_get_clean();
		}
	}

	/**
	 * Notification button
	 *
	 * @since    1.0.0
	*/
	public function workreap_notification_button($post_id,$settings=array(),$show_option='') {
		$btn_settings			= !empty($settings['btn_settings']) ? $settings['btn_settings'] : array();
		$link_class				= !empty($show_option) && $show_option === 'listing' ? 'wr-btn-solid' : '';
		$button_html			= '';
		if( !empty($btn_settings) ){
			$link_type	= !empty($btn_settings['link_type']) ? $btn_settings['link_type'] : '';
			$btn_link	= '';
			$post_data	= get_post_meta( $post_id, 'post_data', true);
			$post_data	= !empty($post_data) ? $post_data : array();
			if( !empty($link_type) && $link_type === 'project_link' ){
				$project_id		= !empty($post_data['project_id']) ? $post_data['project_id'] : 0;
				$btn_link		= !empty($project_id) ? get_the_permalink( $project_id ) : '';
			}else if( !empty($link_type) && $link_type === 'employer_proposal_link' ){
				$receiver_id	= !empty($post_data['employer_id']) ? get_post_field( 'post_author', $post_data['employer_id'] ) : 0;
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
				$btn_link		= !empty($proposal_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $receiver_id, true, 'detail',$proposal_id) : "";
			}else if( !empty($link_type) && $link_type === 'freelancer_proposals_link' ){
				$receiver_id	= !empty($post_data['freelancer_id']) ? get_post_field( 'post_author', $post_data['freelancer_id'] ) : 0;
				$btn_link		= !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $receiver_id, true, 'listing') : "";
			}else if( !empty($link_type) && $link_type === 'freelancer_proposal_activity' ){
				$receiver_id	= !empty($post_data['freelancer_id']) ? get_post_field( 'post_author', $post_data['freelancer_id'] ) : 0;
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
				$btn_link		= !empty($receiver_id) && !empty($proposal_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $receiver_id, true, 'activity',$proposal_id) : "";
			}else if( !empty($link_type) && $link_type === 'employer_proposal_activity' ){
				$receiver_id	= !empty($post_data['employer_id']) ? get_post_field( 'post_author', $post_data['employer_id'] ) : 0;
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
				$btn_link		= !empty($receiver_id) && !empty($proposal_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $receiver_id, true, 'activity',$proposal_id) : "";
			}else if( !empty($link_type) && $link_type === 'order_link' ){
				$receiver_id	= get_post_field( 'post_author', $post_id );
				$order_id		= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
				$btn_link		= !empty($order_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $receiver_id, true, 'detail',$order_id) : "";
			} else if( !empty($link_type) && $link_type === 'single_post' ){
				$btn_link	= !empty($post_data['post_link_id']) ? get_the_permalink($post_data['post_link_id']) : '';
			} else if( !empty($link_type) && $link_type === 'view_freelancer_order' ){
				$order_id		= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
				$freelancer_id		= !empty($post_data['freelancer_id']) ? $post_data['freelancer_id'] : 0;
				$freelancer_id		= !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id,'post') : 0;
				$btn_link		= !empty($order_id) && !empty($freelancer_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $freelancer_id, true, 'detail',$order_id) : "";
			} else if( !empty($link_type) && $link_type === 'employer_order_link' ){
				$order_id		= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
				$employer_id		= !empty($post_data['employer_id']) ? $post_data['employer_id'] : 0;
				$employer_id		= !empty($employer_id) ? workreap_get_linked_profile_id($employer_id,'post') : 0;
				$btn_link		= !empty($order_id) && !empty($employer_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $employer_id, true, 'detail',$order_id) : "";
			}else if( !empty($link_type) && $link_type === 'view_comments' ){
				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$receiver_id	= !empty($post_data['receiver_id']) ? $post_data['receiver_id'] : 0;
				$receiver_id	= !empty($receiver_id) ? workreap_get_linked_profile_id($receiver_id,'post') : 0;
				$btn_link		= !empty($dispute_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $receiver_id, true, 'detail',$dispute_id) : "";
			} else if( !empty($link_type) && $link_type === 'view_project_dispute_comments' ){
				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$receiver_id	= get_post_field( 'post_author', $post_id );
				$btn_link		= !empty($dispute_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $receiver_id, true, 'dispute',$dispute_id) : "";
			} else if( !empty($link_type) && $link_type === 'view_freelancer_refund_request' ){
				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$receiver_id	= !empty($post_data['freelancer_id']) ? $post_data['freelancer_id'] : 0;
				$receiver_id	= !empty($receiver_id) ? workreap_get_linked_profile_id($receiver_id,'post') : 0;
				$btn_link		= !empty($dispute_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $receiver_id, true, 'detail',$dispute_id) : "";
			}else if( !empty($link_type) && $link_type === 'view_freelancer_project_refund_request' ){
				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$receiver_id	= !empty($post_data['freelancer_id']) ? $post_data['freelancer_id'] : 0;
				$receiver_id	= !empty($receiver_id) ? workreap_get_linked_profile_id($receiver_id,'post') : 0;
				$btn_link		= !empty($dispute_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $receiver_id, true, 'dispute',$dispute_id) : "";
			}else if( !empty($link_type) && $link_type === 'project_activity_link' ){
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
				$receiver_id	= get_post_field( 'post_author', $post_id );
				$btn_link	= !empty($proposal_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('projects', $receiver_id, true, 'activity',$proposal_id) : "";
			}
			$button_html	= !empty($btn_settings['text']) ? '<a class="'.esc_attr($link_class).'" href="'.esc_url($btn_link).'">'.esc_html($btn_settings['text']).'</a>' : '';

		}
		$button_html   = apply_filters('workreap_filter_notification_button', $button_html,$post_id,$settings,$show_option);
		return $button_html;
	}

	/**
	 * Notification content
	 *
	 * @since    1.0.0
	*/
	public function workreap_notification_content($post_id,$settings=array()) {

		$params			= !empty($settings['tage']) ? $settings['tage'] : array();

		$post_contents	= get_post_field( 'post_content', $post_id );
		$post_contents	= !empty($post_contents) ? $post_contents : '';
		if( !empty($params)){
			foreach($params as $param){
				$param_key	= !empty($param) ? '{{'.$param.'}}' : '';
				if (strpos($post_contents, $param_key) !== false) {
					$param_value	= $this->workreap_notification_replaceparams($param,$post_id);
					$post_contents = str_replace('{{'.$param.'}}', $param_value, $post_contents);
				}
			}
		}
		return $post_contents;
	}

	/**
	 * Notification replace params
	 *
	 * @since    1.0.0
	*/
	public function workreap_notification_replaceparams($param,$post_id) {
		$param_value	= '';
		$post_data		= get_post_meta( $post_id, 'post_data', true );
		$post_data		= !empty($post_data) ? $post_data : array();
		switch ($param) {
			case "package_name":
				$param_value	= !empty($post_data['package_id']) ? get_the_title($post_data['package_id']) : '';
			break;
			case "admin_name":
				$user_id		= !empty($post_data['user_id']) ? $post_data['user_id'] : 0;
				$user_info 		= !empty($user_id) ? get_userdata($user_id) : array();
				$param_value	= !empty($user_info) ? $user_info->display_name : '';
			break;
			case "project_title":
				$param_value	= !empty($post_data['project_id']) ? get_the_title($post_data['project_id']) : '';
			break;
			case "employer_proposal_link":
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
				$receiver_id	= !empty($post_data['employer_id']) ? $post_data['employer_id'] : 0;
				$receiver_id	= !empty($receiver_id) ? workreap_get_linked_profile_id($receiver_id,'post') : 0;
				$param_value	= !empty($proposal_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $receiver_id, true, 'detail',$proposal_id) : "";
			break;
			case "project_activity_link":
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
				$receiver_id	= get_post_field( 'post_author', $post_id );
				$param_value	= !empty($proposal_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $receiver_id, true, 'detail',$proposal_id) : "";
			break;
			case "milestone_title":
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
				$milestone_id	= !empty($post_data['milestone_id']) ? $post_data['milestone_id'] : 0;
				$proposal_meta	= get_post_meta($proposal_id,'proposal_meta',true);
				$param_value	= !empty($proposal_meta['milestone'][$milestone_id]['title']) ? $proposal_meta['milestone'][$milestone_id]['title'] : "";
			break;
			case "project_id":
				$param_value	= !empty($post_data['project_id']) ? intval($post_data['project_id']) : '';
			break;
			case "post_a_task":
				$param_value	= !empty($post_data['post_link_id']) ? get_the_permalink($post_data['post_link_id']) : '';
			break;
			case "sender_name":
				$sender_id		= !empty($post_data['sender_id']) ? $post_data['sender_id'] : 0;
				$param_value	= !empty($sender_id) ? workreap_get_username($sender_id) : '';
			break;
			case "receiver_name":
				$receiver_id	= !empty($post_data['receiver_id']) ? $post_data['receiver_id'] : 0;
				$param_value	= !empty($receiver_id) ? workreap_get_username($receiver_id) : '';
			break;
			case "order_id":
				$param_value	= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
			break;
			case "order_link":
				$order_id		= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
				$receiver_id	= !empty($post_data['receiver_id']) ? $post_data['receiver_id'] : 0;
				$receiver_id	= !empty($receiver_id) ? workreap_get_linked_profile_id($receiver_id,'post') : 0;
				$param_value	= !empty($order_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $receiver_id, true, 'detail',$order_id) : "";
			break;
			case "sender_comments":
				$param_value		= !empty($post_data['sender_comments']) ? $post_data['sender_comments'] : '';
			break;
			case "view_comments":
				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$receiver_id	= !empty($post_data['receiver_id']) ? $post_data['receiver_id'] : 0;
				$receiver_id	= !empty($receiver_id) ? workreap_get_linked_profile_id($receiver_id,'post') : 0;
				$param_value	= !empty($dispute_id) && !empty($receiver_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $receiver_id, true, 'detail',$dispute_id) : "";
			break;
			case "dispute_comment":
				$param_value		= !empty($post_data['dispute_comment']) ? $post_data['dispute_comment'] : '';
			break;
			case "activity_comment":
				$param_value		= !empty($post_data['activity_comment']) ? $post_data['activity_comment'] : '';
			break;
			case "employer_rating":
				$param_value		= !empty($post_data['employer_rating']) ? $post_data['employer_rating'] : '';
			break;
			case "dispute_order_amount":
				$order_amount		= !empty($post_data['dispute_order_amount']) ? $post_data['dispute_order_amount'] : 0;
				$param_value		= workreap_price_format($order_amount,'return');
			break;
			case "freelancer_order_amount":
				$freelancer_amount		= !empty($post_data['freelancer_order_amount']) ? $post_data['freelancer_order_amount'] : 0;
				$param_value		= workreap_price_format($freelancer_amount,'return');
			break;
			case "freelancer_amount":
				$freelancer_amount		= !empty($post_data['freelancer_amount']) ? $post_data['freelancer_amount'] : 0;
				$param_value		= workreap_price_format($freelancer_amount,'return');
			break;
			case "order_amount":
				$order_amount		= !empty($post_data['order_amount']) ? $post_data['order_amount'] : 0;
				$param_value		= workreap_price_format($order_amount,'return');
			break;
			case "freelancer_order_link":
				$order_id		= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
				$freelancer_id		= !empty($order_id) ? get_post_meta($order_id,'freelancer_id',true) : 0;
				$freelancer_id		= !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id) : 0;
				$param_value	= !empty($order_id) && !empty($freelancer_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $freelancer_id, true, 'detail',$order_id) : "";
			break;
			case "view_freelancer_refund_request":

				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$freelancer_id		= !empty($post_data['freelancer_id']) ? $post_data['freelancer_id'] : 0;
				$freelancer_id		= !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id) : 0;
				$param_value	= !empty($dispute_id) && !empty($freelancer_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $freelancer_id, true, 'detail',$dispute_id) : "";
			break;
			case "view_freelancer_project_refund_request":

				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$freelancer_id		= !empty($post_data['freelancer_id']) ? $post_data['freelancer_id'] : 0;
				$freelancer_id		= !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id) : 0;
				$param_value	= !empty($dispute_id) && !empty($freelancer_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $freelancer_id, true, 'dispute',$dispute_id) : "";
			break;
			case "view_project_dispute_comments":
				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$user_id		= get_post_field( 'post_author', $post_id );
				$param_value	= !empty($dispute_id) && !empty($user_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $user_id, true, 'dispute',$dispute_id) : "";
			break;
			case "view_employer_refund_request":
				$dispute_id		= !empty($post_data['dispute_id']) ? $post_data['dispute_id'] : 0;
				$employer_id		= !empty($post_data['employer_id']) ? $post_data['employer_id'] : 0;
				$employer_id		= !empty($employer_id) ? workreap_get_linked_profile_id($employer_id) : 0;
				$param_value	= !empty($dispute_id) && !empty($employer_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $employer_id, true, 'detail',$dispute_id) : "";
			break;
			case "employer_order_link":
				$order_id		= !empty($post_data['order_id']) ? $post_data['order_id'] : 0;
				$employer_id		= !empty($post_data['employer_id']) ? $post_data['employer_id'] : 0;
				$employer_id		= !empty($employer_id) ? workreap_get_linked_profile_id($employer_id) : 0;
				$param_value	= !empty($order_id) && !empty($employer_id) ? Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $employer_id, true, 'detail',$order_id) : "";
			break;
			case "employer_order_amount":
				$employer_amount		= !empty($post_data['employer_amount']) ? $post_data['employer_amount'] : 0;
				$param_value		= workreap_price_format($employer_amount,'return');
			break;
			case "employer_comments":
				$param_value		= !empty($post_data['employer_comments']) ? $post_data['employer_comments'] : '';
			break;

			case "employer_name":
				$employer_id		= !empty($post_data['employer_id']) ? $post_data['employer_id'] : 0;
				$param_value	= !empty($employer_id) ? workreap_get_username($employer_id) : '';
			break;

			case "task_name":
				$task_id		= !empty($post_data['task_id']) ? $post_data['task_id'] : 0;
				$param_value	= !empty($task_id) ? get_the_title($task_id) : '';
			break;
			case "task_link":
				$task_id		= !empty($post_data['task_id']) ? $post_data['task_id'] : 0;
				$param_value	= !empty($task_id) ? get_the_permalink($task_id) : '';
			break;
			case "freelancer_name":
				$freelancer_id		= !empty($post_data['freelancer_id']) ? $post_data['freelancer_id'] : 0;
				$param_value	= !empty($freelancer_id) ? workreap_get_username($freelancer_id) : '';
			break;
			case "admin_feedback":
				$admin_feedback		= !empty($post_data['admin_feedback']) ? $post_data['admin_feedback'] : '';
				$param_value		= !empty($admin_feedback) ? nl2br($admin_feedback) : '';
			break;
			case "name":
				$linked_profile	= get_post_meta( $post_id, 'linked_profile', true );
				$linked_profile	= !empty($linked_profile) ? intval($linked_profile) : 0;
				$param_value	= workreap_get_username($linked_profile);
			break;
			case "email":
				$linked_profile	= get_post_meta( $post_id, 'linked_profile', true );
				$linked_profile	= !empty($linked_profile) ? intval($linked_profile) : 0;
				$user_id		= get_post_meta( $linked_profile, '_linked_profile', true );
				$user_id		= !empty($user_id) ? $user_id : '';
				$user_meta		= get_userdata($user_id);
				$param_value	= !empty($user_meta->user_email) ? $user_meta->user_email : '';
			break;
			case "sitename":
				$param_value	= get_bloginfo('name');
			break;
		}
		$param_value   = apply_filters('workreap_filter_notification_replaceparams', $param_value,$post_id,$param);
		return $param_value;
	}

	/**
	 * Return image html
	 *
	 * @since    1.0.0
	*/
	public function workreap_notification_image($post_id=0,$settings=array()) {
		global $workreap_notification;
		$image_type	= !empty($settings['image_type']) ? $settings['image_type'] : 'defult';
		$image_link	= '';

		if( !empty($image_type) && $image_type === 'defult'){
			$thumbnail_id	= !empty($workreap_notification['notify_logo']['id']) ? $workreap_notification['notify_logo']['id'] : 0;
			$image_src		= !empty($thumbnail_id) ? wp_get_attachment_image_src($thumbnail_id, 'workreap_thumbnail') : '';
			if( !empty($image_src[0]) ){
				$image_link	= '<img src="'.esc_url($image_src[0]).'" alt="'.esc_attr('Notification','workreap').'">';
			} else {
				$image_link	= '<i class="wr-icon-bell"></i>';
			}
		} else if( !empty($image_type) && $image_type === 'profile'){
			$linked_profile	= get_post_meta( $post_id, 'linked_profile', true );
			$linked_profile	= !empty($linked_profile) ? intval($linked_profile) : 0;
			$image_src = apply_filters(
				'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile), array('width' => 50, 'height' => 50)
			);

			if( !empty($image_src) ){
				$image_link	= '<img src="'.esc_url($image_src).'" alt="'.esc_attr('Notification','workreap').'">';
			} else {
				$image_link	= '<i class="wr-icon-bell"></i>';
			}

		} else if( !empty($image_type) && $image_type === 'employer_image'){
			$post_data		= get_post_meta( $post_id, 'post_data', true);
			$post_data		= !empty($post_data) ? $post_data : array();
			$linked_profile	= !empty($post_data['employer_id']) ? $post_data['employer_id'] : 0;
			$image_src = apply_filters(
				'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile), array('width' => 50, 'height' => 50)
			);

			if( !empty($image_src) ){
				$image_link	= '<img src="'.esc_url($image_src).'" alt="'.esc_attr('Notification','workreap').'">';
			} else {
				$image_link	= '<i class="wr-icon-bell"></i>';
			}

			$image_link	= '<img src="'.esc_url($image_src).'" alt="'.esc_attr('Notification','workreap').'">';
		}else if( !empty($image_type) && $image_type === 'freelancer_image'){
			$post_data		= get_post_meta( $post_id, 'post_data', true);
			$post_data		= !empty($post_data) ? $post_data : array();
			$linked_profile		= !empty($post_data['freelancer_id']) ? $post_data['freelancer_id'] : 0;
			$image_src = apply_filters(
				'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile), array('width' => 50, 'height' => 50)
			);

			if( !empty($image_src) ){
				$image_link	= '<img src="'.esc_url($image_src).'" alt="'.esc_attr('Notification','workreap').'">';
			} else {
				$image_link	= '<i class="wr-icon-bell"></i>';
			}

		}else if( !empty($image_type) && $image_type === 'sender_image'){
			$post_data		= get_post_meta( $post_id, 'post_data', true);
			$post_data		= !empty($post_data) ? $post_data : array();
			$linked_profile	= !empty($post_data['sender_id']) ? $post_data['sender_id'] : 0;

			$image_src = apply_filters(
				'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile), array('width' => 50, 'height' => 50)
			);

			if( !empty($image_src) ){
				$image_link	= '<img src="'.esc_url($image_src).'" alt="'.esc_attr('Notification','workreap').'">';
			} else {
				$image_link	= '<i class="wr-icon-bell"></i>';
			}
		}
		return $image_link;
	}


}
new Workreap_Notifications();
