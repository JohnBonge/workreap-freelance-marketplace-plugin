<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package     Workreap
 * @subpackage  Workreap/admin
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
class Workreap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_filter('add_meta_boxes', array($this, 'remove_product_acf_metaboxes'), 9999999);

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/acf/class-acf-tabs-location.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/acf/class-acf-category-location.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/acf/class-acf-custom-fields.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/products-data/class-product-types.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/products-data/class-product-tabs.php';

		/**
		 * The class used to define hooks
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-workreap-hooks.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/metabox/class-metabox-thickbox-popup.php';

		/**
		 * The classes used to register custom pos types
		*/
		foreach ( glob( plugin_dir_path( __FILE__ ) . "cpt/*.php" ) as $file ) {
			include_once $file;
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings.php';
		/**
		* The classes used to register taxonomies
		*/
		foreach ( glob( plugin_dir_path( __FILE__ ) . "taxonomy/*.php" ) as $file ) {
			include_once $file;
		}

		/**
		 * The class responsible for defining activate license functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-user-purchase-verify.php';

	}

	/**
	 * ACF metabox remove
	 *
	 * @since    1.0.0
	*/
	public function remove_product_acf_metaboxes(){

		if(function_exists('acf_get_field_groups')){
			$acf_field_groups = acf_get_field_groups();
			foreach($acf_field_groups as $group){
				remove_meta_box('acf-'.$group['key'], 'product', 'normal');
			}
		}
	}


	/**
	 * Register the stylesheets for the admin area.
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
		wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
    	wp_enqueue_style( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/workreap-core-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $post;
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

		wp_enqueue_script( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.min.js', array(), $this->version, true );
		if (isset($post->post_type) && 'portfolios' === $post->post_type) {
			wp_enqueue_media();
			wp_enqueue_script('portfolios-gallery', plugin_dir_url( __FILE__ ) . '/js/portfolios-gallery.js', array('jquery'), null, true);
		}
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/workreap-core-admin.js', array( 'jquery' ), $this->version, false );

		$data = array(
			'ajax_nonce'					=> wp_create_nonce('ajax_nonce'),
			'import' 						=> esc_html__('Import users', 'workreap'),
			'import_message' 				=> esc_html__('Are you sure, you want to import users?', 'workreap'),
			'migrate' 						=> esc_html__('Migrate data', 'workreap'),
			'migrate_message' 				=> esc_html__('Are you sure you want to migrate data? Please ensure you have a full site backup, as this action cannot be undone.', 'workreap'),
			'migration_start' 				=> esc_html__('Migrating', 'workreap'),
			'migration_start_message' 		=> esc_html__('Data migration is currently in progress', 'workreap'),
			'migration_progress_message' 	=> esc_html__('Migration data please wait', 'workreap'),
			'ajaxurl'						=> admin_url( 'admin-ajax.php' ),
			'deactivate_account'			=> esc_html__('Uh-Oh!', 'workreap'),
			'deactivate_account_message'	=> esc_html__('Are you sure, you want to deactivate this account?', 'workreap'),
			'reject_account'				=> esc_html__('Reject user account', 'workreap'),
			'reject_account_message'		=> esc_html__('Are you sure, you want to reject this account? After reject, this account will no longer visible in the search listing', 'workreap'),
			'account_verification'			=> esc_html__('Account verification', 'workreap'),
			'reason' 						=> esc_html__('Please add reason why you want to reject user uploaded documents?', 'workreap'),
			'approve_identity'				=> esc_html__('Identity Verify', 'workreap'),
			'approve_identity_message'		=> esc_html__('Are you sure, you want to verify identity of this user?', 'workreap'),
			'reject_identity'				=> esc_html__('Identity Reject', 'workreap'),
			'reject_identity_message'		=> esc_html__('Are you sure, you want to reject identity of this user?', 'workreap'),
			'reject_reason_text'			=> esc_html__('Please add reason why you want to reject?', 'workreap'),
			'approve_account'				=> esc_html__('Approve user account', 'workreap'),
			'approve_account_message'		=> esc_html__('Are you sure, you want to approve this account? An email will be sent to this user.', 'workreap'),
			'yes'			                => esc_html__('Yes', 'workreap'),
			'close'			                => esc_html__('Close', 'workreap'),
			'no' 			                => esc_html__('No', 'workreap'),
			'accept' 		                => esc_html__('Accept', 'workreap'),
			'reject' 		                => esc_html__('Reject', 'workreap'),
			'select_option'	                => esc_html__('Select an option', 'workreap'),
			'select_not_found'              => esc_html__('No result found', 'workreap'),
			'select_searching'              => esc_html__('Searching…', 'workreap'),
		);

		wp_localize_script($this->plugin_name, 'admin_scripts_vars', $data );

	}

}


