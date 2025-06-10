<?php
/**
 *
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package     Workreap
 * @subpackage  Workreap/public
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
class Workreap_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $workreap    The ID of this plugin.
     */
    private $workreap;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $workreap       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $workreap, $version ) {

        $this->workreap = $workreap;
        $this->version = $version;

        /**
         * The class responsible for ajax common functions
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/ajax-hooks.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/woo-hooks.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/hooks.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-dashboard-menu.php';
        add_action( 'elementor/widget/render_content', array($this, 'workreap_before_render_elementor_enqueue'), 10, 2);

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        $protocol = is_ssl() ? 'https' : 'http';
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Workreap_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Workreap_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if( !is_page_template( 'templates/admin-dashboard.php') ) {
			wp_register_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
            wp_register_style( 'fontawesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome/all.min.css', array(), $this->version, 'all' );
            wp_register_style( 'workreap-icons', plugin_dir_url( __FILE__ ) . 'css/workreap-icons.css', array(), $this->version, 'all' );
            wp_register_style( 'owl.carousel', plugin_dir_url( __FILE__ ) . 'css/owl.carousel.min.css', array(), $this->version, 'all' );
            wp_register_style( 'nouislider', plugin_dir_url( __FILE__ ) . 'css/nouislider.min.css', array(), $this->version, 'all' );
            wp_register_style( 'jquery-general', plugin_dir_url( __FILE__ ) . 'css/jquery-general.css', array(), $this->version, 'all' );
            wp_register_style( 'venobox', plugin_dir_url( __FILE__ ) . 'css/venobox.min.css', array(), $this->version, 'all' );
            wp_register_style( 'datetimepicker', plugin_dir_url( __FILE__ ) . 'css/datetimepicker.css', array(), $this->version, 'all' );
            wp_register_style( 'mCustomScrollbar', plugin_dir_url( __FILE__ ) . 'css/jquery.mCustomScrollbar.min.css', array(), $this->version, 'all' );
            wp_register_style( 'tagify', plugin_dir_url( __FILE__ ) . 'css/tagify.css', array(), $this->version, 'all' );
            wp_register_style( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css', array(), $this->version, 'all' );
            wp_register_style('splide', plugin_dir_url( __FILE__ ) . 'css/splide.min.css', array(), $this->version, 'all' );
            wp_register_style('swiper', plugin_dir_url( __FILE__ ) . 'css/swiper-bundle.min.css', array(), $this->version, 'all' );
            wp_register_style('croppie-style', plugin_dir_url( __FILE__ ) . 'css/croppie.min.css', array(), $this->version, 'all' );
            wp_register_style( 'workreap-styles', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
            wp_register_style( 'workreap-rtl-styles', plugin_dir_url( __FILE__ ) . 'css/rtl.css', array(), $this->version, 'all' );

            //Default theme font families
            $font_families	= array();
            $font_families[] = 'Inter:300,400,500,600,700,900';

            $query_args = array (
                'family' => implode('%7C' , $font_families) ,
                'subset' => 'latin,latin-ext' ,
            );

            $theme_fonts = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');

		    wp_enqueue_style('workreap-fonts-enqueue' , esc_url_raw($theme_fonts), array () , null);

            wp_enqueue_style( 'bootstrap' );
            wp_enqueue_style( 'nouislider' );
            wp_enqueue_style( 'select2' );
            wp_enqueue_style( 'fontawesome' );
            wp_enqueue_style( 'workreap-icons' );
            wp_enqueue_style( 'jquery-general' );
            wp_enqueue_style( 'mCustomScrollbar' );
            wp_enqueue_style( 'jquery-confirm' );

        }
    }



    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        global $workreap_settings,$workreap_notification;
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Workreap_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Workreap_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $pusher_notification	    = !empty($workreap_notification['pusher_notification']) ? $workreap_notification['pusher_notification'] : '';
        $task_listing_type          = 'v2';
        $view_type                  = !empty($workreap_settings['registration_view_type']) ? $workreap_settings['registration_view_type'] : 'pages';
        if( !is_page_template( 'templates/admin-dashboard.php') ) {
            wp_enqueue_script('jquery-ui-slider');
            wp_enqueue_script( 'underscore' );
            wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array('jquery'), $this->version, true);
            wp_register_script( 'jquery.ui.touch-punch', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery.ui.touch-punch.js', array( 'jquery' ), $this->version, true );
            wp_register_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/vendor/bootstrap.min.js', array( 'jquery' ), $this->version, true );
            wp_register_script( 'readmore', plugin_dir_url( __FILE__ ) . 'js/vendor/readmore.js', array( 'jquery' ), $this->version, true );
            wp_register_script( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.min.js', array(), $this->version, true );
            wp_register_script( 'swiper', plugin_dir_url( __FILE__ ) . 'js/vendor/swiper-bundle.min.js', array('jquery'), $this->version, true );
            wp_register_script( 'popper', plugin_dir_url( __FILE__ ) . 'js/vendor/popper-core.js', array( 'jquery' ), $this->version, true );
		    wp_register_script( 'tippy', plugin_dir_url( __FILE__ ) . 'js/vendor/tippy.js', array( 'jquery' ), $this->version, true );
            wp_register_script('owl.carousel', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel.min.js', array(), $this->version, true);
            wp_register_script('nouislider', plugin_dir_url( __FILE__ ) . 'js/vendor/nouislider.min.js', array(), $this->version, true);
            wp_register_script('particles', plugin_dir_url( __FILE__ ) . 'js/vendor/particles.min.js', array(), $this->version, true);
            wp_register_script('mCustomScrollbar', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery.mCustomScrollbar.concat.min.js', array(), $this->version, true);
            wp_register_script('sortable', plugin_dir_url( __FILE__ ) . 'js/vendor/sortable.min.js', array(), $this->version, true);
            wp_register_script('tagify', plugin_dir_url( __FILE__ ) . 'js/vendor/tagify.min.js', array(), $this->version, true);
            wp_register_script('jquery.downCount', plugin_dir_url( __FILE__ ) .  '/js/vendor/jquery.downCount.js', array(), $this->version, true);
            wp_register_script('venobox', plugin_dir_url( __FILE__ ) .  '/js/venobox.min.js', array(), $this->version, true);
            wp_register_script('datetimepicker', plugin_dir_url( __FILE__ ) .  '/js/datetimepicker.js', array(), $this->version, true);
            wp_register_script('splide', plugin_dir_url( __FILE__ ) .  'js/splide.min.js', array(), $this->version, true);
            wp_register_script( $this->workreap, plugin_dir_url( __FILE__ ) . 'js/workreap-public.js', array('wp-util'), $this->version, true );
            wp_register_script( $this->workreap.'-dashboard', plugin_dir_url( __FILE__ ) . 'js/workreap_dashboard.js', array('jquery', 'wp-util'), $this->version, true );
            wp_register_script('croppie-js', plugin_dir_url( __FILE__ ) . 'js/croppie.min.js', array('jquery', 'wp-util'), $this->version, true);
            wp_register_script( 'chart', plugin_dir_url( __FILE__ ) . 'js/vendor/chart.min.js', array(), $this->version, true );
            wp_register_script( 'utils-chart', plugin_dir_url( __FILE__ ) . 'js/utils.js', array(), $this->version, true );
            wp_register_script( 'linkify', plugin_dir_url( __FILE__ ) . 'js/vendor/linkify.min.js', array(), $this->version, true );
			wp_register_script( 'linkify-jquery', plugin_dir_url( __FILE__ ) . 'js/vendor/linkify-jquery.min.js', array(), $this->version, true );
			wp_register_script( 'modernizr', plugin_dir_url( __FILE__ ) . 'js/vendor/modernizr.min.js', array(), $this->version, true );
			wp_register_script( 'hoverdir', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery.hoverdir.js', array('modernizr'), $this->version, true );
			wp_register_script( 'isotope', plugin_dir_url( __FILE__ ) . 'js/vendor/isotope.pkgd.min.js', array('jquery'), $this->version, true );

			if($workreap_settings['enable_social_connect']){
				wp_register_script('google-signin-api-js', plugin_dir_url( __FILE__ ) . 'js/google-client.js', array('jquery'), $this->version, true);
				wp_register_script( 'google-signin-gconnect-js', plugin_dir_url( __FILE__ ) . 'js/gconnect.js', array('jquery'), $this->version, true );
			}

            wp_register_script('pusher', plugin_dir_url( __FILE__ ) . 'js/pusher.min.js', array('jquery'),$this->version, true);
            wp_register_script('pusher-notify', plugin_dir_url( __FILE__ ) . 'js/pusher-notify.js', array( 'jquery' ), $this->version, true);

            wp_enqueue_script('bootstrap');
            wp_enqueue_script('jquery.ui.touch-punch');
            wp_enqueue_script('nouislider');
            wp_enqueue_script('select2');
            wp_enqueue_script('readmore');
            wp_enqueue_script('popper');
            wp_enqueue_script('tippy');
            wp_enqueue_script('jquery-confirm');
            wp_enqueue_script('mCustomScrollbar');

            if( is_page_template( 'templates/dashboard.php') || is_page_template( 'templates/add-task.php') || is_page_template( 'templates/add-project.php') || is_page_template( 'templates/search-task.php') || is_page_template( 'templates/search-projects.php') ) {
                wp_enqueue_script('plupload');
                wp_enqueue_script( 'linkify' );
                wp_enqueue_script( 'linkify-jquery' );

                if( is_page_template( 'templates/add-task.php')) {
                    wp_enqueue_script('jquery-validate');
                    wp_enqueue_script('sortable');
                    wp_enqueue_style( 'tagify' );
                    wp_enqueue_script('tagify');
                    wp_enqueue_style('venobox');
                    wp_enqueue_script('venobox');
                }

                if( is_page_template( 'templates/dashboard.php')){
                    wp_enqueue_script('jquery.downCount');
                    wp_enqueue_style( 'datetimepicker' );
                    wp_enqueue_script('datetimepicker');
                    wp_enqueue_style('croppie-style');
                    wp_enqueue_script('croppie-js');
                }

                wp_enqueue_script($this->workreap.'-dashboard');
            }

            //If POP Enabled
            if(!is_user_logged_in() && !empty($view_type) && $view_type  === 'popup'){
                wp_enqueue_script('google-signin-api-js');
                wp_enqueue_script('google-signin-gconnect-js');
            }

            if( is_page_template( 'templates/submit-proposal.php') || is_page_template( 'templates/dashboard.php') ){
                wp_enqueue_script($this->workreap.'-dashboard');
                wp_enqueue_script('sortable');
            }
            if(is_page_template( 'templates/search-task.php') || is_page_template( 'templates/search-freelancer.php')){
                wp_enqueue_style( 'select2' );
                wp_enqueue_script('select2');
            }

            if(is_page_template( 'templates/search-task.php')){
                if( !empty($task_listing_type) && $task_listing_type === 'v1' ){
                    wp_enqueue_style('owl.carousel');
                    wp_enqueue_script('owl.carousel');
                }
                wp_enqueue_style('venobox');
                wp_enqueue_script('venobox');
            }

            if(is_singular( 'product' )){
                wp_enqueue_style('splide');
                wp_enqueue_script('splide');
                wp_enqueue_style('venobox');
                wp_enqueue_script('venobox');
            }

            if( !empty($pusher_notification) && current_user_can( 'subscriber' ) ){
                wp_enqueue_script('pusher');
                wp_enqueue_script('pusher-notify');
            }

            $upload_file_size	= !empty($workreap_settings['upload_file_size']) ? $workreap_settings['upload_file_size'].'mb' : '50mb';
            $date_format	    = !empty($workreap_settings['dateformat']) ? $workreap_settings['dateformat'] : 'Y-m-d';
            $tpl_dashboard	    = !empty($workreap_settings['tpl_dashboard']) ? $workreap_settings['tpl_dashboard'] : '';
            $gclient_id         = '';
            if (!empty($workreap_settings['enable_social_connect']) && $workreap_settings['enable_social_connect'] == '1'){
                $gclient_id    = !empty($workreap_settings['google_client_id']) ? $workreap_settings['google_client_id'] : '';
            }

            $user_type          = '';
            $current_user_key   = '';
            $cluster            = !empty($workreap_notification['pusher_app_cluster']) ? $workreap_notification['pusher_app_cluster'] : '';
            $pusher_app_key     = !empty($workreap_notification['pusher_app_key']) ? $workreap_notification['pusher_app_key'] : '';
            $maxnumber_fields   =  !empty($workreap_settings['maxnumber_fields']) ? $workreap_settings['maxnumber_fields'] : 5;

            $tpl_dashboard      = !empty($tpl_dashboard) ? get_the_permalink( $tpl_dashboard ) : '';
            $alertbox_autoclose	= !empty($workreap_settings['alertbox_autoclose']) ? intval($workreap_settings['alertbox_autoclose']) : 5000;
            $user_id            = get_current_user_id();
            $wallet_amount      = 0;
            if( is_user_logged_in(  ) ){
                $user_type       = workreap_get_user_type($user_id);
                if( current_user_can( 'administrator' ) ){
                    $current_user_key  = $user_id;
                } else {
                    $current_user_key       = workreap_get_linked_profile_id($user_id, '', $user_type);
                    $wallet_amount          = get_user_meta( $user_id, '_employer_balance', true );
                    $wallet_amount          = !empty($wallet_amount) ? $wallet_amount : 0;
                }
            }
            $enable_state		    = !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
            $data = array(
                'ajax_nonce'    => wp_create_nonce('ajax_nonce'),
                'home_url'    	=> home_url( '/' ),
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'username'      => esc_html__('Username required.', 'workreap'),
                'valid_email'   => esc_html__('Valid email required', 'workreap'),
                'first_name'    => esc_html__('First Name is required', 'workreap'),
                'last_name'     => esc_html__('Last Name is required', 'workreap'),
                'payout_methods_title' => esc_html__('Payouts method', 'workreap'),
                'user_password' => esc_html__('Password is required', 'workreap'),
                'user_password_confirm_match'	=> esc_html__('Password and confirm password should be same', 'workreap'),
                'user_agree_terms'              => esc_html__('You must agree our terms & conditions before signup.', 'workreap'),
                'upload_size'                   => $upload_file_size,
                'pusher_key'                    => $pusher_app_key,
                'cluster'                       => $cluster,
                'view_type'                     => $view_type,
                'alertbox_autoclose'            => $alertbox_autoclose,
                'remove_paymentmethod'          => esc_html__('Uh-Oh!', 'workreap'),
                'remove_paymentmethod_message'  => esc_html__('Are you sure, you want to remove this payment method?', 'workreap'),
                'service_type'                  => esc_html__('Task type', 'workreap'),
                'project_type'                  => esc_html__('Project type', 'workreap'),
                'select_option'                 => esc_html__('Select an option', 'workreap'),
                'select_not_found'              => esc_html__('No result found', 'workreap'),
                'select_searching'              => esc_html__('Searching…', 'workreap'),
                'file_size_error'               => esc_html__('File size is too big', 'workreap'),
                'error_title'                   => esc_html__('Uh-Oh!', 'workreap'),
                'file_size_error_title'         => esc_html__('Uh-Oh!', 'workreap'),
                'deactivate_account'            => esc_html__('Uh-Oh!', 'workreap'),
                'deactivate_account_message'    => esc_html__('Are you sure, you want to deactivate this account?', 'workreap'),
                'edu_date_error_title'          => esc_html__('Education','workreap'),
                'load_more'                     => esc_html__('Load more','workreap'),
                'edu_date_error'                => esc_html__('Please add a vaild dates','workreap'),
                'upload_max_images'             => esc_html__('Please upload files up to ','workreap'),
                'date_format'                   => $date_format,
                'tpl_dashboard'                 => $tpl_dashboard,
                'startweekday'                  => get_option( 'start_of_week' ),
                'remove_education'              => esc_html__('Remove education', 'workreap'),
                'remove_education_message'		=> esc_html__('Are you sure, you want to remove this education?', 'workreap'),
                'remove_experience'              => esc_html__('Remove experience', 'workreap'),
                'remove_experience_message'		=> esc_html__('Are you sure, you want to remove this experience?', 'workreap'),
                'remove_faq'				    => esc_html__('Remove FAQ', 'workreap'),
                'remove_faq_message'            => esc_html__('Are you sure, you want to remove this FAQ?', 'workreap'),
                'remove_task'                   => esc_html__('Remove task', 'workreap'),
                'remove_task_message'           => esc_html__('Are you sure, you want to delete this task permanently?', 'workreap'),
                'remove_portfolio'              => esc_html__('Remove portfolio', 'workreap'),
                'remove_portfolio_message'      => esc_html__('Are you sure, you want to delete this portfolio permanently?', 'workreap'),
                'remove_subtask'                => esc_html__('Remove subtask', 'workreap'),
                'remove_subtask_message'        => esc_html__('Are you sure, you want to remove this subtask?', 'workreap'),
                'active_account'    		    => esc_html__('Active account', 'workreap'),
			    'active_account_message'        => esc_html__('Are you sure you want active your account?', 'workreap'),
                'yes_btntext'    		        => esc_html__('Yes', 'workreap'),
                'cancel_verification'    		=> esc_html__('Cancel Verfication', 'workreap'),
                'btntext_cancelled'    		    => esc_html__('Cancel', 'workreap'),
			    'cancel_verification_message'   => esc_html__('Are you sure you want cancel your identity verification?', 'workreap'),
                'default_image_extensions'      => ! empty( $workreap_settings['default_image_extensions'] ) 		? $workreap_settings['default_image_extensions'] 		: '',
                'default_file_extensions'       => ! empty( $workreap_settings['default_file_extensions'] ) 		? $workreap_settings['default_file_extensions'] 		: '',
                'allow_tags'                    => !empty($workreap_settings['allow_tags'])? false : true,
                'task_max_images'               => ! empty($workreap_settings['task_max_images'] ) 	? $workreap_settings['task_max_images'] 	: 3,
                'portf_max_images'               => ! empty($workreap_settings['portf_max_images'] ) 	? $workreap_settings['portf_max_images'] 	: 3,
                'maxnumber_fields'              => $maxnumber_fields,
                'max_custom_fieds_error'        => sprintf(esc_html__('You are allowed to add only %s custom fields','workreap'),$maxnumber_fields),
                'empty_custom_field'    		=> esc_html__("Please don't leave empty custom fields. Either remove this or add the field title", 'workreap'),
                'gclient_id'                    => $gclient_id,
                'user_type'                     => $user_type,
                'login_required'                => esc_html__('You must login as employer to send a message to this freelancer','workreap'),
                'only_employer_option'             => esc_html__('You need to login as a employer to access this option', 'workreap'),
                'post_author_option'            => esc_html__('You are not allowed to perform this action', 'workreap'),
                'show_more'                     => esc_html__('Load more', 'workreap'),
                'show_less'                     => esc_html__('Show less', 'workreap'),
                'price_min_max_error_title'     => esc_html__('Wrong price range', 'workreap'),
                'price_min_max_error_desc'      => esc_html__('Minimum price should not be greater than maximum price', 'workreap'),
                'select_categories'               => esc_html__('Select categories', 'workreap'),
                'select_type'                   => esc_html__('Select type', 'workreap'),
                'select_category'               => esc_html__('Select category', 'workreap'),
                'search_category'               => esc_html__('Search category', 'workreap'),
                'select_sub_category'           => esc_html__('Select sub category', 'workreap'),
                'search_sub_category'           => esc_html__('Search sub category', 'workreap'),
                'choose_category'               => esc_html__('Choose category', 'workreap'),
                'choose_sub_category'           => esc_html__('Choose sub category', 'workreap'),
                'current_user_key'              => $current_user_key,
                'complate_project'                => esc_html__('Complate project', 'workreap'),
                'complate_project_message'        => esc_html__('Are you sure, you want to complate this project?', 'workreap'),
                'complate_without_project'                => esc_html__('Complate project without rating', 'workreap'),
                'complate_without_project_message'        => esc_html__('Are you sure, you want to complate this project without rating?', 'workreap'),
                'remove_project'                => esc_html__('Remove project', 'workreap'),
                'remove_project_message'        => esc_html__('Are you sure, you want to remove this project?', 'workreap'),
                'languages_option'              => esc_html__('Select languages', 'workreap'),
                'skills_option'                 => esc_html__('Select skills from the list', 'workreap'),
                'freelancer_skills_option'          => esc_html__('Select freelancer skills from the list', 'workreap'),
                'expertise_level_option'        => esc_html__('Select expertise level', 'workreap'),
                'num_freelancer_option'        => esc_html__('Select no of freelancer', 'workreap'),
                'select_project_type'           => esc_html__('Select project type', 'workreap'),
                'select_location'               => esc_html__('Select location', 'workreap'),
                'select_state'                  => esc_html__('Select State', 'workreap'),
                'apply_now'                     => esc_html__('Apply now','workreap'),
                'login_freelancer_required'         => esc_html__('You must login as freelancer to access this option','workreap'),
                'login_required_apply'          => esc_html__('You must login as freelancer to apply on this project','workreap'),
                'wallet_account'    		    => esc_html__('You can also use wallet', 'workreap'),
			    'wallet_account_message'        => sprintf(esc_html__('You have %s in your wallet. would you like to use wallet in this transaction?', 'workreap'), workreap_price_format($wallet_amount,'return')),
                'btn_with_wallet'    		    => esc_html__('Continue with wallet', 'workreap'),
                'btn_without_wallet'    		=> esc_html__('Continue without wallet', 'workreap'),
                'featured_title'                => esc_html__('Project featured','workreap'),
                'featured_details'              => esc_html__('Are you sure, you want to mark featured this project?','workreap'),
                'unfeatured_details'            => esc_html__('Are you sure, you want to remove mark featured this project?','workreap'),
                'milestone_title'    		    => esc_html__('Milestone', 'workreap'),
			    'milestone_request_message'     => esc_html__('Are you sure, you want to complete this project milestone?', 'workreap'),
                'hiring_title'    		        => esc_html__('Hiring process', 'workreap'),
			    'hiring_request_message'        => esc_html__('Are you sure, you want to hire this?', 'workreap'),
                'yes'                           => esc_html__('Yes','workreap'),
                'no'                            => esc_html__('No','workreap'),
                'enable_state'                  => $enable_state,
                'copied'                        => esc_html__('Copied','workreap'),
				'copy'                        	=> esc_html__('Copy','workreap'),
                'payment_validation'            => esc_html__('Validation', 'workreap'),
                'payment_validation_message'    => esc_html__('You must accept consent to continue', 'workreap'),

            );
            wp_localize_script($this->workreap, 'scripts_vars', $data );
            wp_enqueue_script( $this->workreap);
            wp_enqueue_style( 'workreap-styles' );
            if( is_rtl() ){ wp_enqueue_style( 'workreap-rtl-styles' );}


            $custom_css = workreap_add_dynamic_styles();
            wp_add_inline_style('workreap-styles', $custom_css);
        }
    }

    /**
     * Enqueue firles for elementor
     *
     * @package         Amentotech
     * @subpackage      workreap/public
     * @since           1.0
     */
    public function workreap_before_render_elementor_enqueue( $content, $widget ) {
        $widget_name  = $widget->get_name();
        do_action( 'workreap_elementor_files', $widget_name,$widget );

        if( $widget_name === 'workreap_element_popular_services'
            || $widget_name === 'workreap_element_feedback'
            || $widget_name === 'workreap_element_freelancers'
            || $widget_name === 'workreap_element_sort_faqs'
            || $widget_name === 'workreap_element_popular_categories'
            || $widget_name === 'workreap_element_services_slider'
        ){
            wp_enqueue_style('splide');
            wp_enqueue_script('splide');
        }

        if($widget_name === 'workreap_element_services_slider'){
            wp_enqueue_style('venobox');
            wp_enqueue_script('venobox');
        }

        if($widget_name === 'workreap_element_search_banner_v2' || $widget_name === 'workreap_element_search' ){
            wp_enqueue_style('select2');
            wp_enqueue_script('select2');
        }

        if($widget_name === 'workreap_element_index_operate'){
            wp_enqueue_style('venobox');
            wp_enqueue_script('venobox');
        }

        if($widget_name === 'workreap_element_authentication' && !is_user_logged_in()){
            wp_enqueue_script('google-signin-api-js');
            wp_enqueue_script('google-signin-gconnect-js');
        }

        if($widget_name === 'workreap_mailchimp'){
            wp_enqueue_script('particles');
        }

        return $content;
    }
}

/**
 * @Remove user
 * @type delete
 */
if (!function_exists('workreap_delete_wp_user')) {
	add_action('delete_user', 'workreap_delete_wp_user');
	function workreap_delete_wp_user($user_id)
	{
        $freelancer_profile = get_user_meta($user_id, '_linked_profile', true);
        $employer_profile  = get_user_meta($user_id, '_linked_profile_employer', true);
		if (!empty($employer_profile)) {
			wp_delete_post($employer_profile, true);
		}
        if (!empty($freelancer_profile)) {
			wp_delete_post($freelancer_profile, true);
		}
	}
}
