<?php
/**
 *
 * Class 'Workreap_Dashboard_Shortcodes_User_Registration' add user registration shortcode
 *
 * @package     Workreap
 * @subpackage  Workreap/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
class Workreap_Dashboard_Shortcodes_User_Registration
{

    private $shortcode_name = 'workreap_registration';

    /**
     * Add user registration shortcode
     *
     * @since    1.0.0
     * @access   public
     */
    public function __construct()
    {
        add_action('wp_ajax_nopriv_workreap_registeration', array($this, 'workreap_registeration'));
        add_shortcode($this->shortcode_name, array($this, 'workreap_user_registration_form'));
    }

    /**
     * User registration AJAX function
     *
     * @since    1.0.0
     * @access   public
     */
    public function workreap_registeration()
    {
        global $workreap_settings;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        $json           = array();
        $notifyData     = array();
        $notifyDetails	= array();

        $post_data          = !empty($_POST['data']) ? $_POST['data'] : '';

        parse_str($post_data, $output);

        $do_check         = check_ajax_referer('ajax_nonce', 'security', false);
        $json['message']  = esc_html__('Registration','workreap');

        if ($do_check == false) {
            $json['type']          = 'error';
            $json['message_desc']  = esc_html__('Security checks failed', 'workreap');
            wp_send_json($json);
        }
        workreapRegistration($output);
    }

    /**
     * user registration form
     *
     * @since    1.0.0
     * @access   public
     */
    public function workreap_user_registration_form($atts) {

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
        $bg_banner  = $atts['background'];
        $logo       = $atts['logo'];
        $tagline    = $atts['tagline'];

        $login_page                 = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : '';
        $terms_conditions_page      = !empty($workreap_settings['tpl_terms_conditions']) ? ($workreap_settings['tpl_terms_conditions']) : '';
        $tpl_privacy                = !empty($workreap_settings['tpl_privacy']) ? ($workreap_settings['tpl_privacy']) : '';
        $hide_role                  = !empty($workreap_settings['hide_role']) ? ($workreap_settings['hide_role']) : '';
        $google_connect             = !empty($workreap_settings['enable_social_connect']) ? $workreap_settings['enable_social_connect'] : '';
        $user_name_option           = !empty($workreap_settings['user_name_option']) ? $workreap_settings['user_name_option'] : false;
        $defult_register_type       = !empty($workreap_settings['defult_register_type']) ? $workreap_settings['defult_register_type'] : 'employers';
        $term_link                  = !empty($terms_conditions_page) ? '<a target="_blank" href="'.get_the_permalink($terms_conditions_page).'">'.get_the_title($terms_conditions_page).'</a>' : '';
        $privacy_link               = !empty($tpl_privacy) ? '<a target="_blank" href="'.get_the_permalink($tpl_privacy).'">'.get_the_title($tpl_privacy).'</a>' : '';
        $user_types                 = apply_filters('workreap_get_user_types','');

        if(!empty($hide_role) && $hide_role !== 'both'){
            unset($user_types[$hide_role]);
        }

        if (!is_user_logged_in() || \Elementor\Plugin::$instance->editor->is_edit_mode()) {

            //Register template
            workreap_get_template('register.php',
                array(
                    'background_banner'   => $bg_banner,
                    'logo'                => $logo,
                    'tagline'             => $tagline,
                    'login_page'          => $login_page,
                    'term_link'           => $term_link,
                    'privacy_link'        => $privacy_link,
                    'terms_conditions_page' => $terms_conditions_page,
                    'user_name_option'      => $user_name_option,
                    'google_connect'        => $google_connect,
                    'user_types'            => $user_types,
                    'defult_register_type'  => $defult_register_type

                )
            );
        }
        
        return ob_get_clean();
    }

}

new Workreap_Dashboard_Shortcodes_User_Registration();
