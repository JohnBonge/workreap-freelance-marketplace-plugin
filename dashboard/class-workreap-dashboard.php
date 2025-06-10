<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package     Workreap
 * @subpackage  Workreap/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
class Workreap_Dashboard {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $workreap   The ID of this plugin.
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
	 * @param      string    $workreap      The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $workreap, $version ) {
		$this->workreap = $workreap;
		$this->version = $version;
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'dashboard/class-dashboard-hooks.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'dashboard/shortcodes/class-dashboard-manage-services.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'dashboard/shortcodes/class-dashboard-add-service.php';
	}

}


