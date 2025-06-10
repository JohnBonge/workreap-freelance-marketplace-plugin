<?php
/**
 * Custom image size
 *
 */
add_image_size('workreap_post_thumbnail', 625, 455, true);
add_image_size('workreap_product_thumbnail', 624, 421, true);
add_image_size('workreap_task_popular_service', 320, 464, true);
add_image_size('workreap_task_our_professional', 315, 300, true);
add_image_size('workreap_task_shortcode_thumbnail', 306, 200, true);
add_image_size('workreap_employer_image', 260, 212, true);
add_image_size('workreap_thum_freelancer_image', 200, 200, true);
add_image_size('workreap_freelancer_image', 164, 164, true);
add_image_size('workreap_thumbnail', 100, 100, true);
add_image_size('workreap_icon_thumbnail', 50, 50, true);



/**
 * Application access
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( !function_exists( 'workreap_application_access' ) ) {
	function workreap_application_access( $type='') {
		global $workreap_settings;
		$application_access		= !empty($workreap_settings['application_access']) ? $workreap_settings['application_access'] : '';
		$return_type			= true;
		if( !empty($type) && $type === 'project'){
			$return_type	= !empty($application_access) && ($application_access == 'both' || $application_access == 'project_based') ? true : false;
		} else if( !empty($type) && $type === 'task'){
			$return_type	= !empty($application_access) && ($application_access === 'both' || $application_access === 'task_based') ? true : false;
		}
		return $return_type;
	}
}

/**
 * @init users online status
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_online_init')) {
	add_action('init', 'workreap_online_init');
	add_action('admin_init', 'workreap_online_init');
	function workreap_online_init(){
		$logged_in_users = get_transient('users_online_status');
		if ($logged_in_users === false) {
			$logged_in_users = array();
		}
		$user = wp_get_current_user();
		if (isset($user->ID) || (isset($logged_in_users[$user->ID]['last']) && $logged_in_users[$user->ID]['last'] <= time() - 300)) {
			$logged_in_users[$user->ID] = array(
				'id'       => $user->ID,
				'username' => $user->user_login,
				'last'     => time(),
			);
			set_transient('users_online_status', $logged_in_users, 300);
		}
	}
}

/**
 * @logout users online status update
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_logout_init')) {
	add_action('wp_logout', 'workreap_logout_init');
	function workreap_logout_init(){
		$logged_in_users = get_transient('users_online_status');
		$user = wp_get_current_user(); //Get the current user's data

		if( !empty( $user->ID ) ){

			if( !empty( $logged_in_users[$user->ID] ) ){
				unset($logged_in_users[$user->ID]);
				set_transient('users_online_status', $logged_in_users, 300);
			}
		}
	}
}

/**
 * @Check if user is online
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_is_user_online')) {
	add_filter('workreap_is_user_online','workreap_is_user_online',10,1);
	function workreap_is_user_online($id){
		$logged_in_users = get_transient('users_online_status');
		return isset($logged_in_users[$id]['last']) && $logged_in_users[$id]['last'] > time() - 300;
	}
}


/**
 * @Online status html
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_print_user_status')) {
	add_action('workreap_print_user_status','workreap_print_user_status',10,2);
	add_filter('workreap_print_user_status','workreap_print_user_status',10,2);
	function workreap_print_user_status($id, $return='no'){

		$is_online	= apply_filters('workreap_is_user_online',$id);
		$online		= '';

		if( $is_online === true ){
			$online	= '<figcaption class="wr-usertag wr-online" '.apply_filters('workreap_tooltip_attributes', 'online_user').'></figcaption>';
		} else {
			$online	= '<figcaption class="wr-usertag wr-offline" '.apply_filters('workreap_tooltip_attributes', 'offline_user').'></figcaption>';
		}

		$html	= apply_filters('workreap_fetch_online_status',$online);

		if( $return === 'yes' ){
			return $html;
		} else{
			echo do_shortcode( $html );
		}
	}
}

/**
 * Recursive sanitize array values
 *
 * @return array
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_recursive_sanitize_text_field')) {
	function workreap_recursive_sanitize_text_field($array) {
		foreach ( $array as $key => &$value ) {

			if ( is_array( $value ) ) {
				$value = workreap_recursive_sanitize_text_field($value);
			} else {

				if($key == 'post_content'){
					$value = sanitize_textarea_field( $value );
				} elseif ($key == 'answer'){
					$value = sanitize_textarea_field( $value );
				} else {
					$value = sanitize_text_field( $value );
				}
			}

		}

		return $array;
	}
}


/**
 * Get Administrator user ID
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_get_admin_user_id')) {
    function workreap_get_admin_user_id()
    {
		$user_id	= 1;
		$admin_users = get_users(
			array(
				'fields' => 'ID',
				'role' => 'administrator'
			)
		);
		foreach ( $admin_users as $user ) {

			if(!empty($user->ID)){
				$user_id	= $user->ID;
				break;
			}

		}

		return $user_id;

    }
}

/**
 * File uploader
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_temp_file_uploader')) {
    function workreap_temp_file_uploader()
    {

		if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        $json = array();
        /*=================== Wp Nonce Verification =================*/
        if (!wp_verify_nonce($_REQUEST['ajax_nonce'], 'ajax_nonce')) {
            $json['type']               = 'error';
            $json['message'] 		    = esc_html__('Oops!', 'workreap');
            $json['message_desc'] 		= esc_html__('You are not allowed to perform this action.', 'workreap');
            wp_send_json($json);
        }
		/*=================== End Wp Nonce Verification =================*/
        $response = Workreap_file_permission::uploadFile($_FILES['file_name']);
        wp_send_json($response);
    }

    add_action('wp_ajax_workreap_temp_file_uploader', 'workreap_temp_file_uploader');
    add_action('wp_ajax_nopriv_workreap_temp_file_uploader', 'workreap_temp_file_uploader');
}

/**
 * File uploader
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_filter_payouts')) {
    function workreap_filter_payouts($status='enable',$type=''){
		global $workreap_settings;
		$payout_item_hide = !empty($workreap_settings['payout_item_hide']) ? $workreap_settings['payout_item_hide'] : array();
		if(!empty($payout_item_hide) && in_array($type,$payout_item_hide) ){
			return 'disable';
		}

		return $status;
    }

    add_filter('workreap_filter_payouts', 'workreap_filter_payouts',10,2);
}

/**
 * Get user type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_linked_profile_id')) {
    function workreap_get_linked_profile_id($id='', $type='users',$role='') {

		if( $type === 'post') {
			$linked_profile = get_post_meta($id, '_linked_profile', true);
		} else {

			if(empty($role)){
				$role = get_user_meta($id,'_user_type',true);
			}

            if (!empty($role) && $role === 'freelancers') {
                $linked_profile = get_user_meta($id, '_linked_profile', true);
            } elseif (!empty($role) && $role === 'employers') {
               $linked_profile = get_user_meta($id, '_linked_profile_employer', true);
            }
		}

        $linked_profile	= !empty( $linked_profile ) ? $linked_profile : '';
        return intval( $linked_profile );
    }
	add_filter('workreap_get_linked_profile_id', 'workreap_get_linked_profile_id', 10, 3);
}


/**
 * get user type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_user_role_type')) {

    function workreap_get_user_role_type($user_identity) {

        if (!empty($user_identity)) {
            $data = get_userdata($user_identity);
			if ( in_array( 'freelancers', (array) $data->roles ) ) {
				return 'freelancers';
			} elseif ( in_array( 'employers', (array) $data->roles ) ) {
				return 'employers';
			} elseif ( in_array( 'administrator', (array) $data->roles ) ) {
				return 'administrator';
			} elseif ( in_array( 'subscriber', (array) $data->roles ) ) {
				return 'subscriber';
			} else {
                return false;
            }
        }

        return false;
    }

    add_filter('workreap_get_user_role_type', 'workreap_get_user_role_type', 10, 1);
}

/**
 * Get template page uri
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_get_page_uri' ) ) {
    function workreap_get_page_uri( $type = '' ) {
		global $workreap_settings;
		$tpl_page		= !empty($workreap_settings['tpl_'.$type]) ? $workreap_settings['tpl_'.$type] : '';
        $search_page 	= !empty($tpl_page) ? get_permalink((int) $tpl_page) : '';
        return $search_page;
    }
}

/**
 * Get dashbod page uri
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_dashboard_page_uri' ) ) {
    function workreap_dashboard_page_uri( $user_type = '' ) {
        $redirect_type	= !empty($_SESSION["redirect_type"]) ? $_SESSION["redirect_type"] : '';
		$redirect		= workreap_get_page_uri('dashboard');
		$redirect		= !empty($redirect) ? esc_url($redirect) : home_url('/');

		if( !empty($redirect_type) && ($redirect_type === 'post_task') && $user_type === 'freelancers'){
			$redirect	= !empty($_SESSION["redirect_url"]) ? $_SESSION["redirect_url"] : $redirect;
		} elseif ( !empty($redirect_type) && ($redirect_type === 'dashboard_page')){
			$redirect	= !empty($_SESSION["redirect_url"]) ? $_SESSION["redirect_url"] : $redirect;
		} elseif ( !empty($redirect_type) && ($redirect_type === 'task_cart') && $user_type === 'employers'){
			$redirect	= !empty($_SESSION["redirect_url"]) ? $_SESSION["redirect_url"] : $redirect;
		} elseif ( !empty($user_type) && $user_type === 'administrator' ){
			$redirect	= workreap_get_page_uri('admin_dashboard');
		}

        return $redirect;
    }
}

/**
 * Redirect after login and registration
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_auth_redirect_page_uri' ) ) {
    function workreap_auth_redirect_page_uri( $redirect_type = '', $user_id='' ) {
		global	$workreap_settings, $current_user;
		$user_type	= apply_filters('workreap_get_user_type', $user_id );
        $user_identity  = $current_user->ID;

		if( !empty($redirect_type) && ($redirect_type === 'login') && $user_type === 'freelancers'){
			$login_redirect	= !empty($workreap_settings['login_redirect_freelancer']) ? $workreap_settings['login_redirect_freelancer'] : 'home';

			if(!empty($login_redirect) && $login_redirect == 'dashboard'){
                $redirect		= Workreap_Profile_Menu::workreap_profile_menu_link('earnings', $user_id, true, 'insights');
			}else if(!empty($login_redirect) && $login_redirect == 'profile'){
				$redirect		= Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $user_id, true, 'profile');
			}else if(!empty($login_redirect) && $login_redirect == 'projects'){
				$redirect		= !empty($workreap_settings['tpl_project_search_page']) ? get_permalink($workreap_settings['tpl_project_search_page']) : home_url('/');
			}else{
				$redirect	= home_url('/');
			}

		} elseif( !empty($redirect_type) && ($redirect_type === 'login') && $user_type === 'employers'){
			$login_redirect	= !empty($workreap_settings['login_redirect_employer']) ? $workreap_settings['login_redirect_employer'] : 'home';

			if(!empty($login_redirect) && $login_redirect == 'dashboard'){
                $redirect		= Workreap_Profile_Menu::workreap_profile_menu_link('earnings', $user_id, true, 'insights');
			}else if(!empty($login_redirect) && $login_redirect == 'profile'){
				$redirect		= Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $user_id, true, 'profile');
			}else if(!empty($login_redirect) && $login_redirect == 'freelancer'){
				$redirect		= !empty($workreap_settings['tpl_freelancers_search_page']) ? get_permalink($workreap_settings['tpl_freelancers_search_page']) : home_url('/');
			}else if(!empty($login_redirect) && $login_redirect == 'task'){
				$redirect		= !empty($workreap_settings['tpl_service_search_page']) ? get_permalink($workreap_settings['tpl_service_search_page']) : home_url('/');
			}else{
				$redirect	= home_url('/');
			}
		}else{
			$redirect	= home_url('/');
		}

        return $redirect;
    }
}
/**
 * Get user role
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_user_type')) {

    function workreap_get_user_type($user_identity) {

        if (!empty($user_identity)) {

            $user_type = get_user_meta($user_identity,'_user_type',true);

            if (!empty($user_type) && $user_type === 'freelancers') {
                return 'freelancers';
            } elseif (!empty($user_type) && $user_type === 'employers') {
               return 'employers';
            } elseif (empty($user_type)) {

				$data = get_userdata( $user_identity );
				if ( !empty( $data->roles[0] ) && $data->roles[0] == 'administrator') {
					return 'administrator';
				}
			}
        }

        return 'administrator';
    }

    add_filter('workreap_get_user_type', 'workreap_get_user_type', 10);
}

/**
 * Get user role
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_get_profile_type')) {

    function workreap_get_profile_type($user_identity) {
		global	$workreap_settings;
        if (!empty($user_identity)) {

            $user_type = get_post_type($user_identity);

            if (!empty($user_type) && $user_type === 'freelancers') {
                return 'freelancers';
            } else {
               return 'employers';
            }
        }

        return 'employers';
    }

    add_filter('workreap_get_profile_type', 'workreap_get_profile_type', 10);
}

/**
 * Get user avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( !function_exists( 'workreap_get_user_avatar' ) ) {
	function workreap_get_user_avatar( $sizes = array(), $user_identity = '' ) {
		global	$workreap_settings;
        $width = '100';
        $height = '100';
		extract( shortcode_atts( array(
			"width" => '100',
			"height" => '100',
		), $sizes ) );

		$thumb_id = get_post_thumbnail_id( $user_identity );

		if ( !empty( $thumb_id ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id, array( $width, $height ), true );

			if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );

				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else {
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}

		} else {

			$default_avatar = array();
			$user_type		= apply_filters('workreap_get_profile_type', $user_identity );

			if( !empty($user_type) && $user_type == 'freelancers') {
				$default_avatar	= !empty($workreap_settings['defaul_freelancers_profile']) ? $workreap_settings['defaul_freelancers_profile'] : array();
			} elseif ( !empty($user_type) && $user_type == 'employers') {
				$default_avatar	= !empty($workreap_settings['defaul_employers_profile']) ? $workreap_settings['defaul_employers_profile'] : array();
			}

			if ( isset($default_avatar['id']) && !empty( $default_avatar['id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], array( $width, $height ), true );

				if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], "full", true );

					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{

						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}

				}

			} else {
				return false;
			}
		}
	}
}


/**
 * Render tippy
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_render_tippy' ) ) {
	add_action('workreap_render_tippy','workreap_render_tippy',10,2);
	add_filter('workreap_render_tippy','workreap_render_tippy',10,2);
    function workreap_render_tippy( $key = '', $return='no' ) {
		global $workreap_settings;
		$tip_page		= !empty($workreap_settings['tip_'.$key]) ? $workreap_settings['tip_'.$key] : '';
		ob_start(); ?>
      	<i class="wr-info-alt tippy" data-tippy-content="<?php echo do_shortcode($tip_page);?>"></i>
        <?php
        $tip_data	= ob_get_clean();

		if(!empty($return) && $return === 'yes'){
			return $tip_data;
		}

		echo do_shortcode( $tip_data );
    }
}

/**
 * Upload temp files to WordPress media
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_temp_upload_to_media')) {
    function workreap_temp_upload_to_media($file_url, $post_id, $encrypt_file=true) {
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

        $json   =  array();
        $upload_dir = wp_upload_dir();
		$folderRalativePath = $upload_dir['baseurl']."/workreap-temp";
		$folderAbsolutePath = $upload_dir['basedir']."/workreap-temp";

		$args = array(
			'timeout'	=> 15,
			'headers'	=> array('Accept-Encoding' => ''),
			'sslverify'	=> false
		);

		$response   	= wp_remote_get( $file_url, $args );
		$file_data		= wp_remote_retrieve_body($response);

		if(empty($file_data)){
			$json['attachment_id']  = '';
			$json['url']            = '';
			$json['name']			= '';
			return $json;
		}

		$filename 			= basename($file_url);
		$temp_filename 		= $filename;

        if (wp_mkdir_p($upload_dir['path'])){
			$file = $upload_dir['path'] . '/' . $filename;
		}  else {
            $file = $upload_dir['basedir'] . '/' . $filename;
		}

		$file_detail  		= workreap_file_permission::getEncryptFile($file, $post_id, true, $encrypt_file);
		$new_filename		= $file_detail['name'];
		$new_path 			= $upload_dir['path'] . '/' . $new_filename;
		$file				= $new_path;
		$filename 			= basename($file);
		$actual_filename 	= pathinfo($file, PATHINFO_FILENAME);
		//put content to the file
		file_put_contents($file, $file_data);
        $wp_filetype = wp_check_filetype($filename, null);

		$attachment = array(
            'post_mime_type' 	=> $wp_filetype['type'],
            'post_title' 		=> sanitize_file_name($filename),
            'post_content' 		=> '',
            'post_status' 		=> 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
		wp_update_attachment_metadata($attach_id, $attach_data);
		$post_type = get_post_type($post_id);
		update_post_meta($attach_id,'is_encrypted','1');
        $json['attachment_id']  = $attach_id;
        $json['url']            = $upload_dir['url'] . '/' . basename( $filename );
		$json['name']			= $filename;

		$target_path 			= $folderAbsolutePath . "/" . $temp_filename;
        if(file_exists($target_path)){
        	unlink($target_path); //delete file after upload
		}
        return $json;
    }
}

/**
 * Upload files from temp directory to task activity directory
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_temp_upload_to_activity_dir')) {
  function workreap_temp_upload_to_activity_dir($file_url, $post_id, $encrypt_file=true) {
    global $wp_filesystem;

    if (empty($wp_filesystem)) {
      require_once (ABSPATH . '/wp-admin/includes/file.php');
      WP_Filesystem();
    }

    $json               =  array();
    $upload_dir         = wp_upload_dir();

    // store the temp dir paths in variable
    $folderRalativePath = $upload_dir['baseurl']."/workreap-temp";
    $folderAbsolutePath = $upload_dir['basedir']."/workreap-temp";

    // custom path to create directory
    $workreapActivityRalativePath  = $upload_dir['baseurl']."/workreap_activity/$post_id";
    $workreapActivityrAbsolutePath = $upload_dir['basedir']."/workreap_activity/$post_id";

    $args = array(
      'timeout'   => 15,
      'headers'   => array('Accept-Encoding' => ''),
      'sslverify' => false
    );

    $response  = wp_remote_get( $file_url, $args );
    $file_data = wp_remote_retrieve_body($response);

    if(empty($file_data)){
      $json['attachment_id']  = '';
      $json['url']            = '';
      $json['name']			      = '';
      return $json;
    }

    $filename 		  = basename($file_url);
    $temp_filename 	= $filename;

    // create directory
    if (wp_mkdir_p($workreapActivityrAbsolutePath)){
    	$file = $workreapActivityrAbsolutePath . '/' . $filename;
    }  else {
    	$file = $upload_dir['basedir'] . '/' . $filename;
    }

    $file_detail	= workreap_file_permission::getEncryptFile($file, $post_id, true, $encrypt_file);
    $new_filename	= $file_detail['name'];
    $new_path		= $workreapActivityrAbsolutePath . '/' . $new_filename;
    $file			= $new_path;
    $filename			= basename($file);
    $actual_filename	= pathinfo($file, PATHINFO_FILENAME);
    //upload file to directory
    file_put_contents($file, $file_data);
    $wp_filetype	= wp_check_filetype($filename, null);
    $json['url']	= $workreapActivityRalativePath . '/' . basename( $filename );
    $json['name']	= $filename;
    $json['ext']	= $wp_filetype['ext'];
    $target_path	= $folderAbsolutePath . "/" . $temp_filename;
    // delete file from temp directory
    unlink($target_path);
    return $json;
  }
}


if(!function_exists('workreap_process_geocode_info')) {
    function workreap_process_geocode_info ($postal_code='',$region_name='',$type='') {
        global $workreap_settings;
		$geo_data			= array();
        $json				= array();
		$json['message']	= esc_html__('Postal code','workreap');
        $google_key			= !empty($workreap_settings['google_map']) ? $workreap_settings['google_map'] : '';

		if(empty($google_key)) {
			$json['type'] 			= 'error';
			$json['message'] 	= esc_html__('Oops!', 'workreap');
			$json['message_desc'] 	= esc_html__('You have not set google map API key yet', 'workreap');
        } else {

			$geo_zip_code   = !empty($postal_code) ? esc_html($postal_code) : '';
			$region  		= !empty($region_name) ? esc_html($region_name) : '';
			$geo_request 	= wp_remote_get( 'https://maps.googleapis.com/maps/api/geocode/json?address='.$geo_zip_code.'&region='.$region.'&key='.$google_key );

			if( is_wp_error( $geo_request ) ) {
				$json['type'] 			= 'error';
				$json['message'] 	= esc_html__('Oops!', 'workreap');
				$json['message_desc'] 	= esc_html__('Something went wrong', 'workreap');
			} else {
				$body = wp_remote_retrieve_body( $geo_request );

				if($body) {
					$response	= json_decode($body, true);
					if ($response['status'] == 'OK') {
						$geo_data 		= workreap_process_geocode_results($response['results'][0]);
						$found_region	= !empty($geo_data['country']['short_name']) ? $geo_data['country']['short_name'] : '';

						if(!empty($found_region) && $found_region != $region ){
							$json['type'] 			= 'error';
							$json['message'] 	= esc_html__("Oops!", 'workreap');
							$json['message_desc'] 	= esc_html__("Please enter the correct zip code", 'workreap');
						} else {

							$json['type']       = 'success';
							$json['message'] 	= esc_html__("Geo zip code data successfully found", 'workreap');
							$json['geo_data']   = $geo_data;
						}
					} else {
						$json['type'] 			= 'error';
						$json['message'] 	= esc_html__("Oops!", 'workreap');
						$json['message_desc'] 	= !empty($response['error_message']) ? $response['error_message'] : esc_html__("Please add the correct postal code", 'workreap');
					}
				}
			}
		}

		if( empty($type) ){
			if( !empty($json['type']) && $json['type'] == 'success' && !empty($geo_data) ){
				return $geo_data;
			} else {
				wp_send_json( $json );
			}

		} else {
			return $json;
		}
    }
}

/**
 * Get geocode location
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if(!function_exists('workreap_process_geocode_results')) {
	function workreap_process_geocode_results($geo_data = array()) {

		$geo_code_data = array();

		if(!empty($geo_data)) {

			for($i = 0; $i < count($geo_data['address_components']); $i++) {
				$addressType = $geo_data['address_components'][$i]['types'][0];

				if ($addressType == "locality") {
					$geo_code_data['locality']['long_name'] 	= $geo_data['address_components'][$i]['long_name'];
					$geo_code_data['locality']['short_name'] 	= $geo_data['address_components'][$i]['short_name'];
				}

				if ($addressType == "country") {
					$geo_code_data['country']['long_name'] 		= $geo_data['address_components'][$i]['long_name'];
					$geo_code_data['country']['short_name'] 	= $geo_data['address_components'][$i]['short_name'];
				}

				if($addressType == "administrative_area_level_1") {
					$geo_code_data['administrative_area_level_1']['long_name'] 		= $geo_data['address_components'][$i]['long_name'];
					$geo_code_data['administrative_area_level_1']['short_name'] 	= $geo_data['address_components'][$i]['short_name'];
				}

				if ($addressType == "administrative_area_level_2" ) {
					$geo_code_data['administrative_area_level_1']['long_name'] 		= $geo_data['address_components'][$i]['long_name'];
					$geo_code_data['administrative_area_level_1']['short_name'] 	= $geo_data['address_components'][$i]['short_name'];
				}

				$geo_code_data['address'] 	= $geo_data['formatted_address'];
				$geo_code_data['lng'] 		= $geo_data['geometry']['location']['lng'];
				$geo_code_data['lat'] 		= $geo_data['geometry']['location']['lat'];

			}

			return $geo_code_data;
		}
	}
}

/**
 * Get service list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_service_list' ) ) {
    function workreap_service_list( $type = '' ) {
		$list	= array(
			'1'	=> array(
				'title' 	=> esc_html__('Task introduction', 'workreap'),
				'class'		=> 'wr-addservice-step'
			),
			'2'	=> array(
				'title' 	=> esc_html__('Task pricing', 'workreap'),
				'class'		=> 'wr-addservice-step wr-addservice-step-2'
			),
			'3'	=> array(
				'title' 	=> esc_html__('Media/Attachments', 'workreap'),
				'class'		=> 'wr-addservice-step wr-addservice-step-3'
			),
			'4'	=> array(
				'title' 	=> esc_html__('Common FAQ’s', 'workreap'),
				'class'		=> 'wr-addservice-step wr-addservice-step-4'
			),
		);
		$list 	= apply_filters('workreap_filter_service_list',$list);
		return $list;
    }
}

/**
 * List ACF group
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_acf_groups' ) ) {
    function workreap_acf_groups( $plan_array = array() ) {
		$list	= array();

		if(function_exists('acf_get_field_groups')){
			$groups = acf_get_field_groups();

			if( !empty($groups) ){
				foreach($groups as $group){
					foreach( $group['location'] as $group_locations ) {
						$role_array			= array();
						$count_location		= !empty($group_locations) ? count($group_locations) : 0;
						$count_true			= 0;
						foreach( $group_locations as $rule ) {
							foreach( $plan_array as $plan_k => $plan_value ) {

								if( $rule['param'] == $plan_k && $rule['operator'] == '==' && in_array($rule['value'],$plan_value)){
									$count_true	= $count_true+1;
								} elseif( $rule['param'] == $plan_k && $rule['operator'] == '!=' && !in_array($rule['value'],$plan_value)){
									$count_true	= $count_true+1;
								}

							}
						}

						if( !empty($count_true) && $count_true === $count_location ){
							$fields 		= acf_get_fields($group['ID']);

							if( !empty($fields) ){
								foreach($fields as $field ){

									if(!empty($field['type']) && $field['type'] == 'group' ){
										foreach($field['sub_fields'] as $sub_fields ){
											$list[]	= $sub_fields;
										}
									} elseif (!empty($field['sub_fields'])){
										$list[]	= !empty($field['sub_fields']) ? $field['sub_fields'] : array();
									} else {
										$list[]	= !empty($field) ? $field : array();
									}
								}
							}

						}

					}
				}
			}

		}
		$list 	= apply_filters('workreap_filter_acf_groups',$list);
		return $list;
    }
}

/**
 * Update commisssion fee
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( !function_exists( 'workreap_commission_fee' ) ) {
	function workreap_commission_fee( $proposed_price='',$post_id='' ) {
		global $workreap_settings;
		$percentage		= !empty($workreap_settings['admin_commision']) ? $workreap_settings['admin_commision'] : 0;
		$admin_shares 	= $proposed_price/100 * $percentage;
		$freelancer_shares 	= $proposed_price - $admin_shares;

		$settings['admin_shares'] 	= !empty($admin_shares) && $admin_shares > 0 ? number_format($admin_shares,2,'.', '') : 0.0;
		$settings['freelancer_shares'] 	= !empty($freelancer_shares) && $freelancer_shares > 0 ? number_format($freelancer_shares,2,'.', '') : 0.0;

		return $settings;
	}
}

/**
 * Get order type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_invoice_order_types' ) ) {
    function workreap_invoice_order_types( $type = '' ) {
		$list	= array(
			'employers'	=> array(
				'wallet'	=> esc_html__('All','workreap'),
				'wallet'	=> esc_html__('Wallet','workreap'),
				'projects'	=> esc_html__('Projects','workreap'),
				'tasks'		=> esc_html__('Task','workreap')
			),
			'freelancers'	=> array(
				'package'	=> esc_html__('Package','workreap'),
				'projects'	=> esc_html__('Projects','workreap'),
				'tasks'		=> esc_html__('Task','workreap')
			)
		);
		$task		= workreap_application_access('task');
		$projects	= workreap_application_access('projects');

		if( empty($task) ){
			unset($list['employers']['tasks']);
			unset($list['freelancers']['tasks']);
		}

		if( empty($task) ){
			unset($list['employers']['projects']);
			unset($list['freelancers']['projects']);
		}

		$list 		= apply_filters('workreap_filter_invoice_order_types',$list);

		if( !empty($type) ){
			$list	= !empty($list[$type]) ? $list[$type] : array();
		}

		return $list;
    }
}


/**
 * Task order status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_post_status')) {
    add_action( 'workreap_post_status', 'workreap_post_status');
    function workreap_post_status($post_id)
    {
        $post_status    = get_post_status( $post_id );
        $post_status    = !empty($post_status) ? $post_status : '';
        $label_link     = '';
        switch($post_status){
            case 'pending':
                $label      = esc_html__('Pending', 'workreap');
                $label_link = '<span class="wr-tag-bordered">'.esc_html($label).'</span';
                break;
            case 'publish':
                $label      = _x('Completed', 'Title for post status', 'workreap' );
                $label_link = '<span class="bordr-green">'.esc_html($label).'</span';
                break;
            case 'rejected':
                $label      = esc_html__('Rejected', 'workreap');
                $label_link = '<span class="bordr-red">'.esc_html($label).'</span';
                break;
            default:
                $label      = esc_html__('New', 'workreap');
                $label_link = '<span class="wr-tag-bordered bordr-blue">'.esc_html($label).'</span';
        }

        ob_start();
        ?>
            <div class="wr-bordertags"><?php echo do_shortcode( $label_link );?></div>
        <?php
        echo ob_get_clean();

    }
}

/**
 * Task order status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_task_status')) {
    add_action( 'workreap_task_status', 'workreap_task_status');
    function workreap_task_status($post_id)
    {
        $post_status    = get_post_status( $post_id );
        $post_status    = !empty($post_status) ? $post_status : '';
        $label_link     = '';
        switch($post_status){
			case 'draft':
                $label      = esc_html__('Pending', 'workreap');
                $label_link = '<span class="wr-tag-bordered">'.esc_html($label).'</span>';
                break;
            case 'pending':
                $label      = esc_html__('Pending', 'workreap');
                $label_link = '<span class="wr-tag-bordered">'.esc_html($label).'</span>';
                break;
            case 'publish':
                $label      = esc_html__('Published', 'workreap');
                $label_link = '<span class="bordr-green">'.esc_html($label).'</span>';
                break;
            case 'rejected':
                $label      = esc_html__('Rejected', 'workreap');
                $label_link = '<span class="bordr-red">'.esc_html($label).'</span>';
                break;
            default:
                $label      = esc_html__('New', 'workreap');
                $label_link = '<span class="wr-tag-bordered bordr-blue">'.esc_html($label).'</span>';
        }

        ob_start();
        ?>
            <div class="wr-bordertags"><?php echo do_shortcode( $label_link );?></div>
        <?php
        echo ob_get_clean();

    }
}

/**
 * Project order status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_status')) {
    add_action( 'workreap_project_status', 'workreap_project_status');
    function workreap_project_status($post_id)
    {
        $post_status    = get_post_status( $post_id );
        $post_status    = !empty($post_status) ? $post_status : '';
        $label_link     = '';
        switch($post_status){
			case 'draft':
                $label      = esc_html__('Draft', 'workreap');
                $label_link = '<span class="wr-tag-bordered">'.esc_html($label).'</span>';
                break;
            case 'pending':
                $label      = esc_html__('Pending', 'workreap');
                $label_link = '<span class="wr-tag-bordered">'.esc_html($label).'</span>';
                break;
            case 'publish':
                $label      = esc_html__('Published', 'workreap');
                $label_link = '<span class="bordr-green">'.esc_html($label).'</span>';
                break;
            case 'rejected':
                $label      = esc_html__('Rejected', 'workreap');
                $label_link = '<span class="bordr-red">'.esc_html($label).'</span>';
                break;
            default:
                $label      = esc_html__('New', 'workreap');
                $label_link = '<span class="wr-tag-bordered bordr-blue">'.esc_html($label).'</span>';
        }

        ob_start();
        ?>
            <div class="wr-bordertags"><?php echo do_shortcode( $label_link );?></div>
        <?php
        echo ob_get_clean();

    }
}

/**
 * List Months
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_list_month' ) ) {
    function workreap_list_month( ) {
		$month_names = array(
			'01'	=> esc_html__("January",'workreap'),
			'02'	=> esc_html__("February",'workreap'),
			'03' 	=> esc_html__("March",'workreap'),
			'04'	=> esc_html__("April",'workreap'),
			'05'	=> esc_html__("May",'workreap'),
			'06'	=> esc_html__("June",'workreap'),
			'07'	=> esc_html__("July",'workreap'),
			'08'	=> esc_html__("August",'workreap'),
			'09'	=> esc_html__("September",'workreap'),
			'10'	=> esc_html__("October",'workreap'),
			'11'	=> esc_html__("November",'workreap'),
			'12'	=> esc_html__("December",'workreap')
		);
		return $month_names;

	}
}


/**
 * download activity attachments
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if( !function_exists( 'workreap_download_chat_attachments' ) ){
	function workreap_download_chat_attachments(){
		global $current_user;
		$json = array();

		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type']		= 'error';
			$json['message']	= esc_html__('Oops', 'workreap');
			$json['message']	= esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json($json);
		}

		$attachment_id	=  !empty( $_POST['comments_id'] ) ? intval($_POST['comments_id']) : '';

		if( empty( $attachment_id ) ){
			$json['type']			= 'error';
			$json['message']		= esc_html__('Oops!', 'workreap');
			$json['message_desc']	= esc_html__('Attachment is missing', 'workreap');
			wp_send_json($json);

		} else {

			$project_files = get_comment_meta( $attachment_id, 'message_files', true);

			if( !empty( $project_files ) ){

				if( class_exists('ZipArchive') ){
					$zip                  = new ZipArchive();
					$uploadspath	      = wp_upload_dir();
					$folderRalativePath   = $uploadspath['baseurl']."/downloads";
					$folderAbsolutePath   = $uploadspath['basedir']."/downloads";

					wp_mkdir_p($folderAbsolutePath);

					$rand	        = workreap_unique_increment(5);
					$filename	    = $rand.round(microtime(true)).'.zip';
					$zip_name     	= $folderAbsolutePath.'/'.$filename;
					$download_url	= $folderRalativePath.'/'.$filename;
					$zip->open($zip_name,  ZipArchive::CREATE);

					foreach($project_files as $key => $value) {
						$file_url	= workreap_add_http_protcol($value['url']);
						$response	= wp_remote_get( $file_url );
						$filedata = wp_remote_retrieve_body( $response );
						$zip->addFromString(basename( $file_url ), $filedata);
					}

					$zip->close();

				} else {
					$json['type'] 			= 'error';
					$json['message'] 		= esc_html__('Oops!', 'workreap');
					$json['message_desc'] 	= esc_html__('Zip library is not installed on the server, please contact to hosting provider', 'workreap');
					wp_send_json($json);
				}
			}

		$json['type'] 		= 'success';
		$json['attachment'] = workreap_add_http_protcol($download_url);
		$json['message'] 	= esc_html__('File has been downloaded', 'workreap');
		wp_send_json($json);
	  }
	}
	add_action('wp_ajax_workreap_download_chat_attachments', 'workreap_download_chat_attachments');
  }

  /**
 * @Init Pagination Code Start
 * @return
 */
if (!function_exists('workreap_order_budget_details')) {
    add_action( 'workreap_order_budget_details', 'workreap_order_budget_details', 10, 2);
    function workreap_order_budget_details($order_id, $user_type = 'freelancers') {
		global $workreap_settings;
		if ( !class_exists('WooCommerce') ) {
			return;
		}

		$commission_text            =  !empty($workreap_settings['commission_text']) ? $workreap_settings['commission_text'] : esc_html__('Processing fee', 'workreap');

		$order              = !empty($order_id) ? wc_get_order( $order_id ) : array();
		$order_price		= !empty($order_id) ? workreap_order_price($order_id) : 0;

		$order_meta         = get_post_meta( $order_id, 'cus_woo_product_data', true );
		$order_meta         = !empty($order_meta) ? $order_meta : array();
		$processing_fee		= !empty($order_meta['processing_fee']) ? $order_meta['processing_fee'] : 0.0;

		ob_start();?>
			<div class="wr-asideholder wr-taskdeadline">
				<?php if(!empty($order_price)){?>
				<div class="wr-asidebox wr-additonoltitleholder">
					<div data-bs-toggle="collapse" data-bs-target="#wr-additionolinfov2" aria-expanded="true" role="button">
						<div class="wr-additonoltitle">
							<div class="wr-startingprice">
								<i><?php esc_html_e('Total task budget', 'workreap');?></i>
								<span>
									<?php
										if(function_exists('wmc_revert_price')){
											workreap_price_format(wmc_revert_price($order_price,$order->get_currency()));
										} else {
											workreap_price_format($order_price);
										}
									?>
							</span>
							</div>
							<i class="wr-icon-chevron-down"></i>
						</div>
					</div>
				</div>
				<?php }?>
				<div id="wr-additionolinfov2" class="show">
					<div class="wr-budgetlist">
						<?php if(!empty($order)){?>
							<ul class="wr-planslist">
								<?php
								// Get and Loop Over Order Items
								foreach ( $order->get_items() as $item_id => $item ) { ?>
									<li>
										<h6>
											<?php echo esc_html($item->get_name());?>
											<span>
												(<?php
													if(function_exists('wmc_revert_price')){
														workreap_price_format(wmc_revert_price($item->get_subtotal(),$order->get_currency()));
													} else {
														workreap_price_format($item->get_subtotal());
													}
												?>)
											</span>
										</h6>
									</li>
								<?php }?>
							</ul>
						<?php }?>
						<?php if(!empty($user_type) && $user_type == 'employers' &&( !empty($order->get_total_tax()) || !empty($processing_fee) )){?>
							<ul class="wr-planslist wr-texesfee">
								<?php if(!empty($order->get_total_tax())){?>
									<li>
										<a href="javascript:void(0);">
											<h6><?php esc_html_e('Taxes & fees', 'workreap');?> <span>(<?php echo esc_html(workreap_price_format($order->get_total_tax()));?>) </span></h6>
										</a>
									</li>
								<?php }?>
								<?php if(!empty($processing_fee)){?>
									<li>
										<a href="javascript:void(0);">
											<h6><?php echo esc_attr($commission_text);?> <span>(<?php echo esc_html(workreap_price_format($processing_fee));?>) </span></h6>
										</a>
									</li>
								<?php }?>
							</ul>
						<?php }?>
						<ul class="wr-planslist wr-totalfee">
							<li>
								<a href="javascript:void(0);">
									<h6>
										<?php esc_html_e('Total task budget', 'workreap');?>:&nbsp;
										<span>
											(<?php
												if(function_exists('wmc_revert_price')){
													workreap_price_format(wmc_revert_price(workreap_order_price($order_id),$order->get_currency()));
												} else {
													workreap_price_format(workreap_order_price($order_id));
												}
											?>)
										</span>
									</h6>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		<?php
		echo ob_get_clean();
    }

}


/**
 * Refund request reply
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_submit_dispute_reply')) {

    function workreap_submit_dispute_reply() {
        global $current_user,$woocommerce,$workreap_settings;

        $json 		= array();
		$do_check	= check_ajax_referer('ajax_nonce', 'security', false);
		if( function_exists('workreap_is_demo_site') ) {
			workreap_is_demo_site();
		}
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Oops!', 'workreap');
			$json['message_desc'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		if ( !class_exists('WooCommerce') ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Uh!', 'workreap');
			$json['message_desc'] = esc_html__('WooCommerce plugin needs to be installed.', 'workreap');
			wp_send_json( $json );
		}

        $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
		parse_str($post_data,$data);
		//$get_user_type	= apply_filters('workreap_get_user_type', $current_user->ID );
		$fields	= array(
			'dispute_comment'	=> esc_html__('Please add reply comment','workreap'),
		);

		foreach( $fields as $key => $item ){

			if( empty( $data[$key] ) ){
				$json['type'] 	 = "error";
				$json['message'] = esc_html__('Oops!', 'workreap');
				$json['message_desc'] = $item;
				wp_send_json( $json );
			}
		}

		workreap_update_dispute_comments($current_user->ID,$data);

    }

    add_action('wp_ajax_workreap_submit_dispute_reply', 'workreap_submit_dispute_reply');
}

/**
 * Count custom earning array
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_tasks_earnings')) {
    function workreap_tasks_earnings($post_type = '', $status='any',$meta_array=array())
    {
        $previous_1_month   = date('F 01, Y');
        $previous_2_month   = date('F d, Y');
        $end_day    = date('d');
        $day_keys   = '';
        $day_values = array();
        for($i=1;$i<=$end_day;$i++){
            $day_keys       = !empty($day_keys) ? $day_keys.','.$i : $i;

            $day_values[$i]	= 0;
        }

		$args = array(
			'post_type'         => $post_type,
			'posts_per_page'    => -1,
			'post_status'       => $status,
			'date_query' => array(
				array(
					'after'     => $previous_1_month,
					'before'    => $previous_2_month,
					'inclusive' => true,
				),
			),
		);

		if (!empty($meta_array)) {
			foreach ($meta_array as $meta) {
				$args['meta_query'][]  = $meta;
			}
		}
		$day_amount     = 0;
		$workreap_posts = get_posts( $args );
		if( !empty($workreap_posts) ){
			foreach($workreap_posts as $post ){
				$date_completed = get_post_meta( $post->ID, '_date_completed', true );
				$date_completed = !empty($date_completed) ? intval($date_completed) : 0;
				$date_val		= !empty($date_completed) ? date('j',$date_completed) : 0;

				if( !empty($date_val) ){
					$day_amount		= $day_values[$date_val];
					$freelancer_shares 	= get_post_meta( $post->ID, 'freelancer_shares', true );
					$freelancer_shares 	= !empty($freelancer_shares) ? ($freelancer_shares) : 0;
					$day_amount		= $day_amount+$freelancer_shares;
					$day_values[$date_val]	= $day_amount;
				}

			}
		}

		$day_values = implode(",", $day_values);
		return array(
			'key'		=> $day_keys,
			'values'	=> $day_values
		);

    }
}

/**
 * @init            Bulk import Users
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 */
if (!function_exists('workreap_import_users_template')) {
	function  workreap_import_users_template(){
		$permalink = add_query_arg(
			array(
				'&type=file',
			)
		);

		//Import users via file
		if ( !empty( $_FILES['users_csv']['tmp_name'] ) ) {
			$import_user	= new WorkreapImportUser();
			$import_user->workreap_import_user();
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e('User imported successfully','workreap');?></p>
			</div>
			<?php
		}
	   ?>
       <h3 class="theme-name"><?php esc_html_e('Import freelancers/employers','workreap');?></h3>
       <div id="import-users" class="import-users">
            <div class="theme-screenshot">
                <img alt="<?php esc_attr_e('Import Users','workreap');?>" src="<?php echo esc_url(workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/users.jpg'));?>">
            </div>
			<h3 class="theme-name"><?php esc_html_e('Import users','workreap');?></h3>
            <div class="user-actions">
                <a href="javascript:void(0);"  class="button button-primary doc-import-users"><?php esc_html_e('Import dummy','workreap');?></a>
            </div>
	   </div>
       <div id="import-users" class="import-users custom-import" style="display:none;">
            <form method="post" action="<?php echo workreap_prepare_final_url('file','import_users'); ?>"  enctype="multipart/form-data">
				<div class="theme-screenshot">
					<img alt="<?php esc_attr_e('Import users','workreap');?>" src="<?php echo esc_url(workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/excel.jpg'));?>">
				</div>
				<h3 class="theme-name">
					<input id="upload-dummy-csv" type="file" name="users_csv" >
					<label for="upload-dummy-csv" class="button button-primary upload-dummy-csv"><?php esc_html_e('Choose file','workreap');?></label>
				</h3>
				<div class="user-actions">
					<input type="submit" class="button button-primary" value="<?php esc_attr_e('Import from file','workreap');?>">
				</div>
            </form>
		</div>
        <?php
	}
}

/**
 * @init            tab url
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('workreap_prepare_final_url')) {
    function workreap_prepare_final_url($tab='',$page='import_users') {
		$permalink = '';
		$permalink = add_query_arg(
			array(
				'?page'	=>   urlencode( $page ) ,
				'tab'	=>   urlencode( $tab ) ,
			)
		);
		return esc_url( $permalink );
	}
}

/**
 * @init            Import user
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 */
if (!function_exists('workreap_import_users')) {
	function  workreap_import_users(){
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);

		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		$import_user	= new WorkreapImportUser();
		$import_user->workreap_import_user();

		if (function_exists('workreap_migration_employer')) {
			workreap_migration_employer();
		}
		if (function_exists('workreap_migration_freelancer_packages')) {
			workreap_migration_freelancer_packages();
		}

		if (function_exists('workreap_migration_projects')) {
			workreap_migration_projects();
		}

		if (function_exists('workreap_migration_proposals')) {
			workreap_migration_proposals();
		}

		$json				= array();
		$json['type']		= 'success';
		$json['message']	= esc_html__('Users have been imported successfully','workreap' );
		echo json_encode( $json );
		die;
	}
	add_action('wp_ajax_workreap_import_users', 'workreap_import_users');
}

/**
 * @init            Import user
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 */
if (!function_exists('workreap_generate_profile')) {
	function  workreap_generate_profile(){
		//security check
		$do_check 	= check_ajax_referer('ajax_nonce', 'security', false);
		$user_id	= !empty($_POST['user_id'] ) ? $_POST['user_id'] : 0;
		$type		= !empty($_POST['type'] ) ? $_POST['type'] : 0;

		if ( empty($user_id) ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('User ID is required', 'workreap');
			wp_send_json( $json );
		}

		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		$user_meta	= get_userdata($user_id);

		$display_name	= $user_meta->first_name.' '.$user_meta->last_name;

		if( empty( $user_meta->first_name ) && empty($user_meta->last_name) ){
			$display_name   = $user_meta->display_name;
		}

		if( empty( $display_name ) ){
			$display_name   = $user_meta->user_login;
		}

		$wr_post_meta                   = array();
		$wr_post_meta['tagline']	    = '';
		$wr_post_meta['first_name']	    = !empty( $user_meta->first_name ) ? $user_meta->first_name : '';
		$wr_post_meta['last_name']	    = !empty( $user_meta->last_name ) ? $user_meta->last_name : '';

		if(!empty($type) && $type == 'freelancers'){
			$user_post = array(
				'post_title'    => wp_strip_all_tags($display_name),
				'post_status'   => 'publish',
				'post_author'   => $user_id,
				'post_type'     => 'freelancers',
			);

			$profile_freelancers = wp_insert_post($user_post);

			update_user_meta($user_id, '_linked_profile', $profile_freelancers);
			update_post_meta($profile_freelancers, '_linked_profile', $user_id);
			update_post_meta($profile_freelancers, 'wr_post_meta', $wr_post_meta);
			update_post_meta($profile_freelancers, '_is_verified', 'yes');
			do_action('workreap_update_freelancer_badge', $profile_freelancers,'profile_approved');
		}else if(!empty($type) && $type == 'employers'){
			$employer_post = array(
				'post_title'    => wp_strip_all_tags($display_name),
				'post_status'   => 'publish',
				'post_author'   =>  $user_id,
				'post_type'     => 'employers',
			);

			$employers_id = wp_insert_post($employer_post);
			update_user_meta($user_id, '_linked_profile_employer', $employers_id);
			update_post_meta($employers_id, 'wr_post_meta', $wr_post_meta);
			update_post_meta($employers_id, '_linked_profile', $user_id);
			update_post_meta($employers_id, '_is_verified', 'yes');
		}

		$json				= array();
		$json['type']		= 'success';
		$json['message']	= esc_html__('Profile has been created and links to that user','workreap' );
		wp_send_json( $json );
	}
	add_action('wp_ajax_workreap_generate_profile', 'workreap_generate_profile');
}

/**
 * @init            Add class on body
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('workreap_custom_body_classes')) {
	add_filter( 'body_class', 'workreap_custom_body_classes',5,1 );
	function workreap_custom_body_classes( $classes ) {
		global $current_user,$workreap_settings;

		if( is_page_template( 'templates/dashboard.php') || is_page_template( 'templates/add-task.php') || is_page_template( 'templates/add-project.php') || is_page_template( 'templates/add-offer.php') ) {
			$classes[] = 'et-offsidebar';
		}

		if (is_user_logged_in()) {
			$classes[] = 'wr-user-logged-in';
			$user_type	= workreap_get_user_type($current_user->ID);
				if( !empty($user_type) && $user_type === 'freelancers' ){
					$classes[] = 'wr-user-logged-freelancers';
				} else if( !empty($user_type) && $user_type === 'employers' ){
					$classes[] = 'wr-user-logged-employers';
				}
		}else{
			$classes[] = 'wr-user-logged-off';
		}

		return $classes;
	}
}

/**
 * @init            retunr user avatar
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('wpguppy_user_profile_avatar')) {
	add_filter('get_avatar_url','wpguppy_user_profile_avatar',10,3);
	function wpguppy_user_profile_avatar($avatar = '', $id_or_email='', $args=array()){
		if(!empty($id_or_email) && is_numeric($id_or_email)){
			$user_type		= workreap_get_user_type($id_or_email);
			$link_id		= workreap_get_linked_profile_id( $id_or_email );
			if( !empty($user_type) &&( $user_type ==='freelancers' || $user_type === 'employers' ) ){
				$avatar  = apply_filters(
					'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100)
				);
			}
		}

		return $avatar;
	}
}

/**
 * @init            Get user avatar
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('workreap_user_profile_avatar')) {
	add_filter('get_avatar','workreap_user_profile_avatar',10,5);
	function workreap_user_profile_avatar($avatar = '', $id_or_email='', $size = 60, $default = '', $alt = false ){
		if ( is_numeric( $id_or_email ) ) {
			$user_id = (int) $id_or_email;
		}elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ){
			$user_id = $user->ID;
		}elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ){
			$user_id = (int) $id_or_email->user_id;
		}

		if ( empty( $user_id ) ){return $avatar;}

		$user_type	= workreap_get_user_type($user_id);

		if( !empty($user_type) &&( $user_type ==='freelancers' || $user_type === 'employers' ) ){
			$profile_id	= workreap_get_linked_profile_id($user_id);
			$height		= $size;
			$width		= $size;
			$local_avatars    	= apply_filters(
				'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => $width, 'height' => $height), $profile_id), array('width' => $width, 'height' => $height)
			);
		}

		if ( empty( $local_avatars ) ){
			return $avatar;
		}

		$size = (int) $size;

		if ( empty( $alt ) ){
			$alt = get_the_author_meta( 'display_name', $user_id );
		}

		$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . esc_url( $local_avatars ) . "' class='avatar photo' width='".esc_attr( $size )."' height='".esc_attr( $size )."'  />";

		return $avatar;

	}
}

/**
 * @init            Site demo content
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('workreap_is_demo_site')) {
	function workreap_is_demo_site($message=''){
		$json = array();
		$message	= !empty( $message ) ? $message : esc_html__("Sorry! you are restricted to perform this action on demo site.",'workreap' );

		if( isset( $_SERVER["SERVER_NAME"] ) && $_SERVER["SERVER_NAME"] == 'demos.codingeasel.com' ){
				$json['type']	    	= "error";
				$json['message']		= esc_html__('Oops!','workreap');
				$json['message_desc'] 	= $message;
				wp_send_json( $json );
		}
	}
}

/**
 * @init            Verification
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('workreap_verified_user')) {
	function workreap_verified_user(){
		global $current_user,$workreap_settings;
		$json 					= array();
		$user_update_option		= !empty($workreap_settings['user_update_option']) ? $workreap_settings['user_update_option'] : false;
		$identity_verification	= !empty($workreap_settings['identity_verification']) ? $workreap_settings['identity_verification'] : false;

		if( empty($user_update_option) ){
			$identity_verified		= get_user_meta($current_user->ID,'_is_verified',true);
			$identity_verified		= !empty($identity_verified) ? $identity_verified : '';

			if( empty($identity_verified) || $identity_verified !='yes' ){
				$json['type']	    	= "error";
				$json['message']		= esc_html__('Email verification required','workreap');
				$json['message_desc'] 	= esc_html__('Your email is not verified, please contact to administrator for the verification.','workreap');

				if (!empty($workreap_settings['email_user_registration']) && $workreap_settings['email_user_registration'] == 'verify_by_link') {
					$button					= array();
					$button['option']	    = "yes";
					$button['buttonclass']	= "re-send-email btn-orange";
					$button['btntext']	    = esc_html__("Resend email",'workreap');
					$button['redirect']	    = 'javascript:;';
					$json['button'] 		= $button;
					$json['message'] 		= esc_html__('Verification', 'workreap');
					$json['message_desc'] 	= esc_html__('Your email is not verified, please verify your email to perform any action on the site. You can click button to get a verification link','workreap');
				}

				wp_send_json( $json );
			}
		}

		if( !empty($identity_verification) && empty($user_update_option) ){
			$identity_verified			= get_user_meta($current_user->ID,'identity_verified',true);
			$identity_verified			= !empty($identity_verified) ? $identity_verified : 0;
			$verification_attachments  	= get_user_meta($current_user->ID, 'verification_attachments', true);
			$verification_attachments	= !empty($verification_attachments) ? $verification_attachments : array();
			if( empty($identity_verified) ){
				$json['type']	    		= "error";
				if(!empty($verification_attachments) ){
					$json['message'] 		= esc_html__('Woohoo!', 'workreap');
					$json['message_desc'] 	= esc_html__('You have successfully submitted your documents. buckle up, we will verify and respond to your request very soon.','workreap');
				} else {
					$button					= array();
					$button['button']	    = "yes";
					$button['buttonclass']	= "btn-green";
					$button['btntext']	    = esc_html__("Let's verify account",'workreap');
					$button['redirect']	    = Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $current_user->ID, true, 'verification');
					$json['button'] 		= $button;
					$json['message'] 		= esc_html__('Verification', 'workreap');
					$json['message_desc'] 	= esc_html__('You must verify your identity, please submit the required documents to get verified.','workreap');
				}

				$json['message']		= esc_html__('Verification required','workreap');
				wp_send_json( $json );
			}
		}
	}
}

/**
 * return pending listings
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_allow_pending_listings') ) {
	function workreap_allow_pending_listings($query) {
        $post_type	= $query->get( 'post_type' );
		if( $query->is_main_query() && $query->is_singular() ){
			if( !empty($post_type) && $post_type === 'product' ){
				$query->set('post_status', array('draft','pending','publish','rejected','refunded','completed','hired','cancelled'));
			} else if( !empty($post_type) && $post_type === 'offers' ){
				$query->set('post_status', array('draft','pending','publish','rejected','refunded','completed','hired','cancelled'));
			}
        }
	}
	add_action('pre_get_posts','workreap_allow_pending_listings');
}


/**
 * Product post author support
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_author_support_to_posts') ) {
	function workreap_author_support_to_posts() {
		if (post_type_exists('product'))
		{
			add_post_type_support( 'product', 'author' );
		}
	}
	add_action( 'init', 'workreap_author_support_to_posts', 999 );
}

/**
 * Count custom post type status
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_post_count')) {
	add_filter( 'workreap_post_count', 'workreap_post_count',10,3 );
    function workreap_post_count($post_type = '', $status='any',$meta_data=array())
    {
        $args = array(
            'post_type' 		=> $post_type,
            'post_status' 		=> $status,
            'posts_per_page' 	=> -1,
        );
        if( !empty($meta_data) ){
            foreach($meta_data as $key => $val ){
                $args['meta_query'][] = array(
                    'key'       => $key,
                    'value'     => $val,
                    'compare' 	=> '=',
                );
            }
        }
        $workreap_posts  = get_posts( $args );
        $workreap_posts  = !empty($workreap_posts) && is_array($workreap_posts) ? count($workreap_posts) : 0;
        return $workreap_posts;

    }
}

/**
 * Count tatal proposals
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_count_proposals')) {
	add_action( 'workreap_count_proposals', 'workreap_count_proposals',10,3 );
    function workreap_count_proposals($post_type = '', $status='any',$meta_data=array())
    {
        $args = array(
            'post_type' 		=> $post_type,
            'post_status' 		=> $status,
            'posts_per_page' 	=> -1,
        );

        if( !empty($meta_data) ){
            foreach($meta_data as $key => $val ){
                $args['meta_query'][] = array(
                    'key'       => $key,
                    'value'     => $val,
                    'compare' 	=> '=',
                );
            }
        }

        $workreap_posts  = get_posts( $args );
        $workreap_posts  = !empty($workreap_posts) && is_array($workreap_posts) ? count($workreap_posts) : 0;
		?>
		<li>
			<i class="wr-icon-file-text accountsicon"></i>
			<div class="wr-project-requirement_content">
				<div class="wr-requirement-tags">
					<span><?php echo esc_html(sprintf("%02d", $workreap_posts));?></span>
				</div>
				<em><?php esc_html_e('Application received', 'workreap');?></em>
			</div>
		</li>
		<?php

    }
}

/**
 * Hide activity comments on admin listing
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_hide_comments_by_type') ) {
	function workreap_hide_comments_by_type($query) {
		if ( is_admin() && ($query->query_vars['type'] !== 'activity_detail' || $query->query_vars['type'] !== 'dispute_activities') ) {
			$query->query_vars['type__not_in'] = array_merge(
				(array) $query->query_vars['type__not_in'],
				array('activity_detail','dispute_activities')
			);
		 }
	}
	add_action( 'pre_get_comments', 'workreap_hide_comments_by_type' );
}

/**
 * search type
 * @return slug
 */
if (!function_exists('workreap_search_list_type')) {
	function workreap_search_list_type(){
		$list	= array(
			'freelancers_search_page'		=> esc_html__('Freelancers','workreap'),
			'service_search_page'		=> esc_html__('Services','workreap'),
			'project_search_page'		=> esc_html__('Projects','workreap')
		);
		$list	= apply_filters('workreap_filter_search_list_type', $list );
		return $list;
	}
}

/**
 * Wp guppy add images
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'wpguppy_get_post_image' ) ) {
	add_filter('wpguppy_get_post_image','wpguppy_get_post_image',10,1);
    function wpguppy_get_post_image( $postId=0 ) {
		global $current_user;
		if( !empty($postId) ){
			$post_type	= get_post_type( $postId );
			if( !empty($post_type) && $post_type === 'proposals'){
				$user_type	= workreap_get_user_type($current_user->ID);
				if( !empty($user_type) && $user_type === 'freelancers' ){
					$project_id		= get_post_meta( $postId, 'project_id', true );
					$post_author	= !empty($project_id) ? get_post_field('post_author', $project_id ) : 0;
					$profile_id		= workreap_get_linked_profile_id($post_author,'','employers');
					$avatar         = apply_filters(
						'workreap_avatar_fallback',
						workreap_get_user_avatar(array('width' => 80, 'height' => 80), $profile_id),
						array('width' => 80, 'height' => 80)
					);
					return $avatar;
				} else if( !empty($user_type) && $user_type === 'employers' ){
					$post_author	= get_post_field('post_author', $postId );
					$profile_id		= workreap_get_linked_profile_id($post_author,'','freelancers');
					$avatar         = apply_filters(
						'workreap_avatar_fallback',
						workreap_get_user_avatar(array('width' => 80, 'height' => 80), $profile_id),
						array('width' => 80, 'height' => 80)
					);
					return $avatar;
				}
			}
		}
    }
}

if ( ! function_exists( 'is_workreap_template' ) ) {
    function is_workreap_template(){

        global $post;

        $templates = array(
            'templates/dashboard.php',
            'templates/search-task.php',
            'templates/add-task.php',
            'templates/add-project.php',
            'templates/submit-proposal.php',
            'templates/search-freelancer.php',
            'templates/search-projects.php',
            'templates/pricing-plans.php',
            'templates/single-task.php',
            'templates/single-project.php',
            'templates/add-offer.php',
            'templates/single-freelancer.php',
            'templates/single-employer.php'
        );

        if (is_singular() && $post->post_type == 'product') {
            $product = wc_get_product($post->ID);
            $product_data = get_post_meta($post->ID, 'wr_service_meta', true);
            $wr_product_type = get_post_meta($post->ID, 'wr_product_type', true);
            if ($product->is_type('tasks') || !empty($product_data) || ($wr_product_type == 'tasks')) {
                return true;
            } else if ($product->is_type('projects') || $wr_product_type == 'projects') {
                return true;
            }
        }

        if (isset($post->post_type) && $post->post_type == 'freelancers') {
            return true;
        } else if (isset($post->post_type) && $post->post_type == 'employers') {
            return true;
        }

        return is_page_template($templates);

    }
}

if (!function_exists('workreap_prepare_thumbnail_from_id')) {

	function workreap_prepare_thumbnail_from_id($post_id, $width = '300', $height = '300') {
		global $post;
		$thumb_id = get_post_thumbnail_id($post_id);
		if (!empty($thumb_id)) {
			$thumb_url = wp_get_attachment_image_src($thumb_id, array(
				$width,
				$height
			), true);
			if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
				return !empty($thumb_url[0]) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
				return !empty($thumb_url[0]) ? $thumb_url[0] : '';
			}
		} else {
			return 0;
		}
	}

}

if (!function_exists('workreap_prepare_thumbnail')) {

	function workreap_prepare_thumbnail($post_id, $width = '300', $height = '300') {
		global $post;
		if (has_post_thumbnail()) {
			get_the_post_thumbnail();
			$thumb_id = get_post_thumbnail_id($post_id);
			$thumb_url = wp_get_attachment_image_src($thumb_id, array(
				$width,
				$height
			), true);
			if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
				return !empty($thumb_url[0]) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
				return !empty($thumb_url[0]) ? $thumb_url[0] : '';
			}
		} else {
			return;
		}
	}

}

if (!function_exists('workreap_get_post_author')) {

	function workreap_get_post_author($post_author_id = '', $linked = 'linked', $post_id = '') {
		$user_type 	= workreap_get_user_type($post_author_id);
		if( !empty($user_type) && ($user_type == 'freelancer' || $user_type == 'employer')){
			$profile_id = workreap_get_linked_profile_id($post_author_id);
			$url        = get_permalink($profile_id);
		} else {
			$url    = get_author_posts_url($post_author_id);
		}
		global $post;
		echo '<a href="' . esc_url($url). '"><i class="lnr lnr-user"></i><span>' . get_the_author() . '</span></a>';
	}

}

if (!function_exists('workreap_get_post_date')) {

	function workreap_get_post_date($post_id = '') {
		global $post;
		echo '<time datetime="' . date('Y-m-d', strtotime(get_the_date('Y-m-d', $post_id))) . '"><i class="lnr lnr-clock"></i><span>' . date_i18n(get_option('date_format'), strtotime(get_the_date('Y-m-d', $post_id))) . '</span></time>';
	}

}

if (!function_exists('workreap_get_image_metadata')) {

	function workreap_get_image_metadata($attachment_id) {

		if (!empty($attachment_id)) {
			$attachment = get_post($attachment_id);
			if (!empty($attachment)) {
				return array(
					'alt' 			=> get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
					'caption' 		=> $attachment->post_excerpt,
					'description' 	=> $attachment->post_content,
					'href' 			=> get_permalink($attachment->ID),
					'src' 			=> $attachment->guid,
					'title' 		=> $attachment->post_title
				);
			} else {
				return array();
			}
		}
	}

}

if (!function_exists('workreap_get_post_title')) {

	function workreap_get_post_title($post_id = '') {
		global $post;
		echo '<a href="' . esc_url(get_the_permalink($post_id)) . '">' . esc_html( get_the_title($post_id) ) . '</a>';
	}

}

if (!function_exists('workreapReadMoreDescription')) {

	function workreapReadMoreDescription($description, $maxLength = 100) {
		if (strlen($description) > $maxLength) {
			$shortDesc = substr($description, 0, $maxLength);
			$lastSpace = strrpos($shortDesc, ' ');
			$shortDesc = substr($shortDesc, 0, $lastSpace) . ' <a href="#" class="wr-read-more-link">'.esc_html__('Read more','workreap').'</a>';
			$fullDesc = '<span class="wr-full-description" style="display: none;">' . substr($description, $lastSpace) . '</span>';
			// Combine the short and full descriptions
			$finalDescription = $shortDesc . $fullDesc;
		} else {
			// If the description length is within the maximum length, no need to truncate
			$finalDescription = $description;
		}
		return $finalDescription;

	}

}

/**
 *Register meta boxes
 */
if(!function_exists('workreap_page_meta_box')){
	function workreap_page_meta_box() {
		add_meta_box(
			'workreap-page-meta-box',
			__('Workreap Settings', 'workreap'),
			'workreap_page_meta_box_html',
			'page',
			'side',
			'default'
		);
	}
	add_action('add_meta_boxes', 'workreap_page_meta_box');
}

if(!function_exists('workreap_page_meta_box_html')){
	function workreap_page_meta_box_html($post) {

		wp_nonce_field(basename(__FILE__), 'workreap_page_meta_box_nonce');

		$header_type = get_post_meta($post->ID, 'wr_header_style', true);
		$header_width = get_post_meta($post->ID, 'wr_header_container', true);
		$header_search = get_post_meta($post->ID, 'wr_header_search', true);
		$header_transparent = get_post_meta($post->ID, 'wr_header_transparent', true);
		$header_white = get_post_meta($post->ID, 'wr_header_white', true);
		$hide_topbar = get_post_meta($post->ID, 'wr_header_topbar_hide', true);

		?>
        <div class="wrokreap-page-meta-box-wrapper">
            <div class="wrokreap-page-meta-box-header-type wrokreap-page-meta-box-select">
                <label for="wrokreap-header-type"><?php echo esc_html__('Header Type','workreap') ?></label>
                <select id="wrokreap-header-type" name="wr_header_style">
                    <option value="" <?php echo selected($header_type, '', false) ?>><?php echo esc_html__('Default','workreap') ?></option>
                    <option value="one" <?php echo selected($header_type, 'one', false) ?>><?php echo esc_html__('Style 1','workreap') ?></option>
                    <option value="two" <?php echo selected($header_type, 'two', false) ?>><?php echo esc_html__('Style 2','workreap') ?></option>
                    <option value="three" <?php echo selected($header_type, 'three', false) ?>><?php echo esc_html__('Style 3','workreap') ?></option>
                    <option value="four" <?php echo selected($header_type, 'four', false) ?>><?php echo esc_html__('Style 4','workreap') ?></option>
                </select>
            </div>
            <div class="wrokreap-page-meta-box-header-container wrokreap-page-meta-box-select">
                <label for="wrokreap-header-container"><?php echo esc_html__('Header Container','workreap') ?></label>
                <select id="wrokreap-header-container" name="wr_header_container">
                    <option value="" <?php echo selected($header_width, '', false) ?>><?php echo esc_html__('Default','workreap') ?></option>
                    <option value="container" <?php echo selected($header_width, 'container', false) ?>><?php echo esc_html__('Contained','workreap') ?></option>
                    <option value="container-fluid" <?php echo selected($header_width, 'container-fluid', false) ?>><?php echo esc_html__('Full Width','workreap') ?></option>
                </select>
            </div>
            <div class="wrokreap-page-meta-box-header-search wrokreap-page-meta-box-select">
                <label for="wrokreap-header-container"><?php echo esc_html__('Header Search','workreap') ?></label>
                <select id="wrokreap-header-container" name="wr_header_search">
                    <option value="" <?php echo selected($header_search, '', false) ?>><?php echo esc_html__('Default','workreap'); ?></option>
                    <option value="1" <?php echo selected($header_search, '1', false) ?>><?php echo esc_html__('Show','workreap'); ?></option>
                    <option value="0" <?php echo selected($header_search, '0', false) ?>><?php echo esc_html__('Hide','workreap'); ?></option>
                </select>
            </div>
            <div class="wrokreap-page-meta-box-header-transparent wrokreap-page-meta-box-checkbox">
                <input id="wrokreap-header-transparent" name="wr_header_transparent" value="1" <?php echo checked($header_transparent, '1', false) ?> type="checkbox">
                <label for="wrokreap-header-transparent"><?php echo esc_html__('Header Transparent','workreap') ?></label>
            </div>
            <div class="wrokreap-page-meta-box-header-white wrokreap-page-meta-box-checkbox">
                <input id="wrokreap-header-white" name="wr_header_white" value="1" <?php echo checked($header_white, '1', false) ?> type="checkbox">
                <label for="wrokreap-header-white"><?php echo esc_html__('Header Menu White','workreap') ?></label>
            </div>
            <div class="wrokreap-page-meta-box-header-topbar-hide wrokreap-page-meta-box-checkbox">
                <input id="wrokreap-header-topbar-hide" name="wr_header_topbar_hide" value="1" <?php echo checked($hide_topbar, '1', false) ?> type="checkbox">
                <label for="wrokreap-header-topbar-hide"><?php echo esc_html__('Header Hide Topbar','workreap') ?></label>
            </div>
        </div>

	<?php }
}

if(!function_exists('workreap_save_page_meta_box_data')){
	function workreap_save_page_meta_box_data($post_id) {

		if (!isset($_POST['workreap_page_meta_box_nonce'])) {
			return;
		}

		if (!wp_verify_nonce($_POST['workreap_page_meta_box_nonce'], basename(__FILE__))) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if ('page' === $_POST['post_type'] && !current_user_can('edit_page', $post_id)) {
			return;
		}

		update_post_meta($post_id, 'wr_header_style', sanitize_text_field($_POST['wr_header_style']));
		update_post_meta($post_id, 'wr_header_container', sanitize_text_field($_POST['wr_header_container']));
		update_post_meta($post_id, 'wr_header_search', sanitize_text_field($_POST['wr_header_search']));
		update_post_meta($post_id, 'wr_header_transparent', sanitize_text_field($_POST['wr_header_transparent']));
		update_post_meta($post_id, 'wr_header_white', sanitize_text_field($_POST['wr_header_white']));
		update_post_meta($post_id, 'wr_header_topbar_hide', sanitize_text_field($_POST['wr_header_topbar_hide']));

	}
	add_action('save_post', 'workreap_save_page_meta_box_data');
}
