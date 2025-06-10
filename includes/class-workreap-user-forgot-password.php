<?php
/**
 *
 * Class 'Workreap_User_Forgot_Password' add user forgot password shortcode
 *
 * @package     Workreap
 * @subpackage  Workreap/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
class Workreap_User_Forgot_Password {

  private $shortcode_name = 'workreap_forgot';

	/**
	 * Add user registration shortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
        add_action( 'wp_ajax_workreap_forgot', array($this, 'workreap_forgot') );
        add_action('wp_ajax_nopriv_workreap_forgot',  array($this, 'workreap_forgot') );
        add_action('wp_ajax_workreap_reset',  array($this, 'workreap_reset') );
        add_action('wp_ajax_nopriv_workreap_reset',  array($this, 'workreap_reset') );
        add_shortcode(  $this->shortcode_name, array($this, 'workreap_forgot_form') );
    }

  /**
	 * Get Lost Password AJAX function
	 *
	 * @since    1.0.0
	 * @access   public
	 */
    public function workreap_forgot(){

        global $workreap_settings;
		    $json       = array();
		    $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
		    parse_str($post_data,$output);

            $user_email = !empty($output['fotgot']['email']) ? sanitize_email($output['fotgot']['email']) : '';

            workreapForgetPassword($user_email);
    }

    /**
     * Reset Password AJAX function
     *
     * @since    1.0.0
     * @access   public
     */
    public function workreap_reset(){

        global $workreap_settings;

        $json       = array();
        $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
        parse_str($post_data,$output);
        $password           = (!empty($output['fotgot']['password'])    ? sanitize_text_field($output['fotgot']['password'])     : '');
        $confirm_password   = (!empty($output['fotgot']['re_password']) ? sanitize_text_field($output['fotgot']['re_password'])  : '');

        if ( empty($password) || empty($confirm_password) ) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Oops!', 'workreap');
            $json['message_desc'] = esc_html__('Password should not be empty', 'workreap');
            wp_send_json( $json );
        }

        //Match password
        if ($password != $confirm_password) {
            $json['type']     = 'error';
            $json['message'] = esc_html__('Oops!', 'workreap');
            $json['message_desc']  = esc_html__('Password does not match.', 'workreap');
            wp_send_json($json);
        }

        do_action( 'workreap_strong_password_validation', $password );

        if (!empty($output['key']) && ( isset($output['reset_action']) && $output['reset_action'] == "reset_pwd" ) &&  (!empty($output['login']) ) ) {

            $reset_key  = sanitize_text_field($output['key']);
            $user_email = sanitize_text_field($output['login']);
            $user_data = get_user_by('email', $user_email);

            if (!empty($reset_key) && !empty($user_data)) {
                $user_identity 	= intval($user_data->ID);
                $user_type		= apply_filters('workreap_get_user_type', $user_identity );
                $redirect_url   = '';

                if( !empty($user_type) ){
                    $redirect_url  = !empty($workreap_settings['tpl_login']) ? get_the_permalink( $workreap_settings['tpl_login'] ) : wp_login_url();
                }

                $notifyDetails                  = array();
                $notifyData                     = array();
                $user_type		                = apply_filters('workreap_get_user_type', $user_data->ID);
                $linked_profile                 = workreap_get_linked_profile_id($user_data->ID, '', $user_type);
                $notifyData['user_type']		= $user_type;
                $notifyData['receiver_id']		= $user_data->ID;
                $notifyData['type']				= 'reset_password';
                $notifyData['post_data']		= $notifyDetails;
                $notifyData['linked_profile']	= $linked_profile;
                do_action('workreap_notification_message', $notifyData );

                wp_set_password($password, $user_data->ID);
                $json['redirect_url'] = $redirect_url;
                $json['type'] = "success";
                $json['message'] = esc_html__('Woohoo!', 'workreap');
                $json['message_desc'] = esc_html__("Congratulation! your password has been changed.", 'workreap');
                wp_send_json( $json );

            } else {
                $json['type'] = "error";
                $json['message'] = esc_html__('Oops!', 'workreap');
                $json['message_desc'] = esc_html__("Oops! Invalid request", 'workreap');
                wp_send_json( $json );
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Oops!', 'workreap');
            $json['message_desc'] = esc_html__('Some error occur, please try again later', 'workreap');
            wp_send_json( $json );
        }
  	}

    /**
	 * Forgot password form
	 *
	 * @since    1.0.0
	 * @access   public
	 */
    public function workreap_forgot_form($atts){
        global $current_user, $workreap_settings;
        $atts = shortcode_atts(
            array(
                'background'          => '',
                'logo'                => '',
                'reset_key'           => '',
                'reset_action'        => '',
                'user_email'          => '',
                'tagline'             => '',
                'reset_pass_tagline'  => '',
            ),$atts
        );
        ob_start();

        $bg_banner          = esc_url($atts['background']);
        $logo               = esc_url($atts['logo']);
        $reset_key          = '';
        $reset_action       = '';
        $user_email         = '';
        $google_connect     = !empty($workreap_settings['enable_social_connect']) ? $workreap_settings['enable_social_connect'] : '';
        $login_page         = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : '';
        $registration_page  = !empty( $workreap_settings['tpl_registration'] ) ? get_permalink($workreap_settings['tpl_registration']) : '';
        $classes	        = '';

        if(empty($registration_page)){
            $classes	= 'wr-popupcontainervtwo';
        }

        if(!is_user_logged_in()  || \Elementor\Plugin::$instance->editor->is_edit_mode()){

            if ( isset($_GET['action']) && $_GET['action'] == 'reset_pwd' ) {
                $tagline        = !empty($atts['reset_pass_tagline']) ? ($atts['reset_pass_tagline']) : '';
                $reset_key      = !empty($_GET['key'])    ? esc_html($_GET['key'])    : '';
                $reset_action   = !empty($_GET['action']) ? esc_html($_GET['action']) : '';
                $user_email     = !empty($_GET['login'])  ? esc_html($_GET['login'])  : '';
            } else {
                $tagline       = !empty($atts['tagline']) ? ($atts['tagline'])  : '';
            }

            // Forgot/Reset password template
            workreap_get_template( 'forgot.php',
                array( 
                    'google_connect'        => $google_connect,
                    'background_banner'		=> $bg_banner,
                    'logo'                  => $logo,
                    'tagline'               => $tagline,
                    'reset_key'             => $reset_key,
                    'reset_action'          => $reset_action,
                    'user_email'            => $user_email,
                    'login_page'            => $login_page,
                    'registration_page'		=> $registration_page,
                    'classes'				=> $classes
                )
            );
        }

        return ob_get_clean();
    }
}
new Workreap_User_Forgot_Password();
