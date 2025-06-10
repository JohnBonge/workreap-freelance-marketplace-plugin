<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package     Workreap
 * @subpackage  Workreap/Admin_Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

class Workreap_Admin_Dashboard {

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-dashboard-menu.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin-dashboard/partials/functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin-dashboard/partials/ajax-hooks.php';
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$protocol = is_ssl() ? 'https' : 'http';
		if( is_page_template( 'templates/admin-dashboard.php') ) {
			wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'workreap-icons', plugin_dir_url( __FILE__ ) . 'css/workreap-icons.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mCustomScrollbar', plugin_dir_url( __FILE__ ) . 'css/jquery.mCustomScrollbar.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'admin-dashboard', plugin_dir_url( __FILE__ ) . 'css/admin-dashboard.css', array(), $this->version, 'all' );
			if( is_rtl() ){
				wp_enqueue_style( 'admin-workreap-rtl-styles', plugin_dir_url( __FILE__ ) . 'css/rtl.css', array(), $this->version, 'all' );
			}
			
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $workreap_settings;

		if( is_page_template( 'templates/admin-dashboard.php') ) {

			$alertbox_autoclose	= !empty($workreap_settings['alertbox_autoclose']) ? intval($workreap_settings['alertbox_autoclose']) : 5000;

			wp_enqueue_script('plupload');
			wp_enqueue_script( 'underscore' );
			wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/vendor/bootstrap.min.js', array('jquery'), $this->version, true );
			wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/vendor/select2.min.js', array(), $this->version, true );
			wp_enqueue_script( 'particles', plugin_dir_url( __FILE__ ) . 'js/vendor/particles.min.js', array(), $this->version, true );
			wp_enqueue_script( 'mCustomScrollbar', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery.mCustomScrollbar.concat.min.js', array(), $this->version, true );
			wp_enqueue_script( 'linkify', plugin_dir_url( __FILE__ ) . 'js/vendor/linkify.min.js', array(), $this->version, true );
			wp_enqueue_script( 'linkify-jquery', plugin_dir_url( __FILE__ ) . 'js/vendor/linkify-jquery.min.js', array(), $this->version, true );
			wp_enqueue_script( 'popper-core', plugin_dir_url( __FILE__ ) . 'js/vendor/popper-core.js', array(), $this->version, true );
			wp_enqueue_script( 'tippy', plugin_dir_url( __FILE__ ) . 'js/vendor/tippy.js', array(), $this->version, true );
			wp_enqueue_script( 'readmore', plugin_dir_url( __FILE__ ) . 'js/vendor/readmore.js', array(), $this->version, true );
			wp_enqueue_script( 'moment.min', plugin_dir_url( __FILE__ ) . 'js/vendor/moment.min.js', array(), $this->version, true );
			wp_enqueue_script( 'chart', plugin_dir_url( __FILE__ ) . 'js/chart.min.js', array(), $this->version, true );
			wp_register_script( 'utils-chart', plugin_dir_url( __FILE__ ) . 'js/utils.js', array(), $this->version, true );
			wp_register_script( 'chart-custom', plugin_dir_url( __FILE__ ) . 'js/chart-custom.js', array(), $this->version, true );
			wp_enqueue_script( 'admin-dashboard', plugin_dir_url( __FILE__ ) . 'js/admin-dashboard.js', array( 'jquery', 'wp-util' ), $this->version, true );
			wp_enqueue_script( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery-confirm.min.js', array(), $this->version, true );
			$data	= array(
				'ajax_nonce'		=> wp_create_nonce('ajax_nonce'),
				'ajaxurl'			=> admin_url( 'admin-ajax.php' ),
				'rejected_task'		=> esc_html__('Rejected task','workreap'),
				'rejected_task_msg'	=> esc_html__('Are you sure, you want to reject this task','workreap'),
				'publish_task'		=> esc_html__('Publish a task','workreap'),
				'publish_task_msg'	=> esc_html__('Are you sure, you want to approved this task','workreap'),
				'publish_project'		=> esc_html__('Publish a project','workreap'),
				'publish_project_msg'	=> esc_html__('Are you sure, you want to approved this project','workreap'),
				'upload_max_images'	=> esc_html__('You have exceeded max file upload limit','workreap'),
				'remove_task'                   => esc_html__('Remove task', 'workreap'),
                'remove_task_message'           => esc_html__('Are you sure, you want to delete this task permanently?', 'workreap'),
				'alertbox_autoclose'            => $alertbox_autoclose,
				'yes'                           => esc_html__('Yes','workreap'),
                'no'                            => esc_html__('No','workreap'),
			);
			wp_localize_script('admin-dashboard', 'scripts_vars', $data);
		}

	}

}
