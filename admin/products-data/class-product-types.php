<?php
namespace ProductTypes;
/**
 * 
 * Class 'Workreap_Admin_CPT_Product_Types' defines the product post type custom types
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/products_data
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
class Workreap_Admin_Products_Data_Product_Types {

	/**
	 * Add woocommerce filter 'product_type_selector' to define custom product types.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_filter( 'product_type_selector', array($this, 'workreap_add_custom_product_type'),999 );
	}

	/**
	* Add to product type drop down.
	*/
	function workreap_add_custom_product_type( $product_types ){
		$product_types[ 'tasks' ]		= apply_filters('workreap_product_type_task_title', esc_html__('Task listing', 'workreap'));
		$product_types[ 'subtasks' ]	= apply_filters('workreap_product_type_subtask_title', esc_html__('Sub task listing', 'workreap'));
		$product_types[ 'packages' ]	= apply_filters('workreap_product_type_package_title', esc_html__('Freelancer packages', 'workreap'));
		$product_types[ 'employer_packages' ]	= apply_filters('workreap_product_type_package_title', esc_html__('Employer packages', 'workreap'));
		$product_types[ 'funds' ]		= apply_filters('workreap_product_type_funds_title', esc_html__('Funds', 'workreap'));
		$product_types[ 'projects' ]	= apply_filters('workreap_product_type_projects_title', esc_html__('Projects', 'workreap'));
		return $product_types;
	}
}

new Workreap_Admin_Products_Data_Product_Types();
