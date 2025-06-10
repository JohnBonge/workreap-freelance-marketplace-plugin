<?php
/**
 *
 * Class 'Workreap_Login' add user login shortcode
 *
 * @package     Workreap
 * @subpackage  Workreap/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
class Workreap_Login {

    private $shortcode_name = 'workreap_signin';

	/**
	 * Add user registration shortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
        add_action( 'wp_ajax_workreap_signin', array($this, 'workreap_signin') );
        add_action('wp_ajax_nopriv_workreap_signin',  array($this, 'workreap_signin') );
		add_shortcode(  $this->shortcode_name, array($this, 'workreap_signin_form') );
    }

    /**
	 * User registration AJAX function
	 *
	 * @since    1.0.0
	 * @access   public
	 */
    public function workreap_signin(){

        $user_array     = array();
        $json           = array();
        $post_data      = !empty($_POST['data']) ?  $_POST['data'] : '';
        parse_str($post_data,$output);
        $user_array['user_login']       = sanitize_text_field($output['signin']['email']);
        $user_array['user_password']    = sanitize_text_field($output['signin']['user_password']);

        if(!empty($_POST['redirect'] )){
            $redirect                       = !empty( $_POST['redirect'] ) ? esc_url( $_POST['redirect'] ) : '';
        }else{
            $redirect                       = !empty( $output['redirect'] ) ? esc_url( $output['redirect'] ) : '';
        }

        if (isset($_POST['signin']['remember_me'])) {
            $remember = sanitize_text_field($_POST['signin']['remember_me']);
        } else {
            $remember = '';
        }

        if ($remember) {
            $user_array['remember'] = true;
        } else {
            $user_array['remember'] = false;
        }
        
        $message  = esc_html__( 'Oops!', 'workreap' );
        if ($user_array['user_login'] == '') {
            
            wp_send_json(
                array(
                    'type'      => 'error', 
                    'loggedin'  => false, 
                    'message'   => $message, 
                    'message_desc'  => esc_html__('Username or email address should not be empty.', 'workreap')
                )
            );
        } elseif ($user_array['user_password'] == '') {
            wp_send_json(
                array(
                    'type' => 'error', 
                    'loggedin' => false, 
                    'message' => $message, 
                    'message_desc' => esc_html__('Password should not be empty.', 'workreap')
                )
            );
        } else {
			$user = wp_signon($user_array, false);
            if (is_wp_error($user)) {

                wp_send_json(
                    array(
                        'type' => 'error', 
                        'loggedin' => false, 
                        'message' => $message, 
                        'message_desc' => esc_html__('Wrong email/username or password.', 'workreap')
                    )
                );
            } else {
                $message  = esc_html__( 'Woohoo!', 'workreap' );
                $user_type  = workreap_get_user_type($user->ID);

                if (!empty( $redirect )) {
                    $redirect   = $redirect;
                }else{
                    $redirect   = workreap_auth_redirect_page_uri('login',$user->ID);
                }
                
                wp_send_json(
                    array( 
                        'type'      => 'success', 
                        'redirect'  => $redirect, 
                        'url'       => home_url('/'), 
                        'loggedin'  => true,
                        'message'   => $message, 
                        'message_desc'  => esc_html__('Successfully logged in', 'workreap')
                    )
                );
            }
        }

    }

    /**
	 * user login form
	 *
	 * @since    1.0.0
	 * @access   public
	 */
    public function workreap_signin_form($atts){
        global $current_user, $workreap_settings;
        $atts = shortcode_atts(
            array(
                'background' => '',
                'logo'      => '',
                'tagline'   => '',
            ),
            $atts
        );

        ob_start();

        $bg_banner          = $atts['background'];
        $logo               = $atts['logo'];
        $tagline            = $atts['tagline'];
        $google_connect     = !empty($workreap_settings['enable_social_connect']) ? $workreap_settings['enable_social_connect'] : '';
        $registration_page  = !empty( $workreap_settings['tpl_registration'] )     ? get_permalink($workreap_settings['tpl_registration'])    : '';
        $forgot_pass_page   = !empty( $workreap_settings['tpl_forgot_password'] )  ? get_permalink($workreap_settings['tpl_forgot_password']) : '';
        $classes	        = '';

        if(empty($registration_page)){
            $classes    = 'wr-popupcontainervtwo';
        }

        if(!is_user_logged_in() || \Elementor\Plugin::$instance->editor->is_edit_mode()){

            //Register template
            workreap_get_template( 'login.php',
                array( 
                    'background_banner' => $bg_banner,
                    'logo'              => $logo,
                    'tagline'           => $tagline,
                    'registration_page' => $registration_page,
                    'forgot_pass_page'  => $forgot_pass_page,
                    'google_connect'    => $google_connect,
                    'classes'           => $classes
                )
            );
        }
        return ob_get_clean();
    }

}

new Workreap_Login();
