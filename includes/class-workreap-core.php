<?php
/**
 *
 * Class 'Workreap_Render_Fields' core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package     Workreap
 * @subpackage  Workreap/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

class Workreap {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Workreap_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $workreap    The string used to uniquely identify this plugin.
	 */
	protected $workreap;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'WORKREAP_VERSION' ) ) {
			$this->version = WORKREAP_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->workreap = 'workreap';

		$this->load_dependencies();
		$this->set_locale();

		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Workreap_Loader. Orchestrates the hooks of the plugin.
	 * - Workreap_i18n. Defines internationalization functionality.
	 * - Workreap_Admin. Defines all hooks for the admin area.
	 * - Workreap_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {


		/**
		 * The class responsible for modal dialogue
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-modal-popup.php';

		/**
		 * The class responsible for file upload rename & permission
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-file-permission.php';

		/**
		 * The class responsible for render form fields dynamically
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-workreap-core-render-fileds.php';

		/**
		 * The class responsible for common functions used in plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-workreap-core-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-user-login.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-workreap-user-forgot-password.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dashboard-user-regisration.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-migrations.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-extensions.php';


		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-workreap-core-i18n.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-workreap-core-admin.php';


		/**
		 * The class responsible for defining all actions that occur in the user dashbaord of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'dashboard/class-workreap-dashboard.php';

		/**
		 * The class responsible for defining all actions that occur in the admin dashbaord of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin-dashboard/class-admin-dashboard.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-workreap-core-public.php';

		$this->loader = new Workreap_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Workreap_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Workreap_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Workreap_Admin( $this->get_workreap(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		if(is_user_logged_in() && current_user_can('administrator')) {
			// Administrator dashboard class
			$admin_dashboard = new Workreap_Admin_Dashboard($this->get_workreap(), $this->get_version());
			$this->loader->add_action('wp_enqueue_scripts', $admin_dashboard, 'enqueue_styles');
			$this->loader->add_action('wp_enqueue_scripts', $admin_dashboard, 'enqueue_scripts');
		}

		// Dashboard class
		new Workreap_Dashboard( $this->get_workreap(), $this->get_version() );
		$plugin_public = new Workreap_Public( $this->get_workreap(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_workreap() {
		return $this->workreap;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Workreap_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
