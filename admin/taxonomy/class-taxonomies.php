<?php
/**
 * 
 * Class 'Workreap_Admin_Taxonomies' defines the product post type custom taxonomy languages
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Taxonomy
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
class Workreap_Admin_Taxonomies {

	/**
	 * Language Taxonomy
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action('init', array(&$this, 'init_taxonomy'));
		
	}
	/**
	 * @Init taxonomy
	*/
	public function init_taxonomy() {
		$this->register_custom_taxonomy();
	}

	/**
	 * Regirster location Taxonomy
	*/
	public function register_custom_taxonomy() {
		$languages_labels = array(
			'name' 				=> esc_html__('Languages', 'workreap'),
			'singular_name' 	=> esc_html__('Language','workreap'),
			'search_items' 		=> esc_html__('Search language', 'workreap'),
			'all_items' 		=> esc_html__('All languages', 'workreap'),
			'parent_item' 		=> esc_html__('Parent language', 'workreap'),
			'parent_item_colon' => esc_html__('Parent language:', 'workreap'),
			'edit_item' 		=> esc_html__('Edit language', 'workreap'),
			'update_item' 		=> esc_html__('Update language', 'workreap'),
			'add_new_item' 		=> esc_html__('Add new language', 'workreap'),
			'new_item_name' 	=> esc_html__('New language name', 'workreap'),
			'menu_name' 		=> esc_html__('Languages', 'workreap'),
		);

		$language_args = array(
			'hierarchical'          => true,
			'labels'                => apply_filters('workreap_product_taxonomy_languages_labels', $languages_labels),
			'show_ui'               => true,
			'show_in_nav_menus' 	=> false,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => array('slug' => 'languages'),			
			'show_in_rest'              => true,
			'rest_base'                 => 'languages',
			'rest_controller_class'     => 'WP_REST_Terms_Controller',
			
		);
		register_taxonomy(apply_filters('workreap_product_taxonomy_languages_name', 'languages'), array('product','freelancers', apply_filters('workreap_profiles_post_type_name', 'profiles')), apply_filters('workreap_product_taxonomy_languages', $language_args));
		
		$duration_labels = array(
			'name' 				=> esc_html__('Duration', 'workreap'),
			'singular_name' 	=> esc_html__('Duration','workreap'),
			'search_items' 		=> esc_html__('Search duration', 'workreap'),
			'all_items' 		=> esc_html__('All Duration', 'workreap'),
			'parent_item' 		=> esc_html__('Parent duration', 'workreap'),
			'parent_item_colon' => esc_html__('Parent duration:', 'workreap'),
			'edit_item' 		=> esc_html__('Edit duration', 'workreap'),
			'update_item' 		=> esc_html__('Update duration', 'workreap'),
			'add_new_item' 		=> esc_html__('Add new duration', 'workreap'),
			'new_item_name' 	=> esc_html__('New duration name', 'workreap'),
			'menu_name' 		=> esc_html__('Duration', 'workreap'),
		);

		$duration_args = array(
			'hierarchical'          => true,
			'labels'                => apply_filters('workreap_product_taxonomy_durations_labels', $duration_labels),
			'show_ui'               => true,
			'show_in_nav_menus' 	=> false,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => array('slug' => 'duration'),			
			'show_in_rest'              => true,
			'rest_base'                 => 'duration',
			'rest_controller_class'     => 'WP_REST_Terms_Controller',
			
		);

		register_taxonomy(apply_filters('workreap_product_taxonomy_durations_name', 'duration'), array('product', apply_filters('workreap_profiles_post_type_name', 'profiles')), apply_filters('workreap_product_taxonomy_duration', $duration_args));
		
		$expertise_labels = array(
			'name' 				=> esc_html__('Expertise level', 'workreap'),
			'singular_name' 	=> esc_html__('Experty level','workreap'),
			'search_items' 		=> esc_html__('Search Experty level', 'workreap'),
			'all_items' 		=> esc_html__('All Experty level', 'workreap'),
			'parent_item' 		=> esc_html__('Parent Experty level', 'workreap'),
			'parent_item_colon' => esc_html__('Parent Experty level:', 'workreap'),
			'edit_item' 		=> esc_html__('Edit Experty level', 'workreap'),
			'update_item' 		=> esc_html__('Update Experty level', 'workreap'),
			'add_new_item' 		=> esc_html__('Add new Experty level', 'workreap'),
			'new_item_name' 	=> esc_html__('New Experty level name', 'workreap'),
			'menu_name' 		=> esc_html__('Expertise level', 'workreap'),
		);

		$expertise_args = array(
			'hierarchical'          => true,
			'labels'                => apply_filters('workreap_product_taxonomy_skills_labels', $expertise_labels),
			'show_ui'               => true,
			'show_in_nav_menus' 	=> false,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => array('slug' => 'expertise_level'),			
			'show_in_rest'              => true,
			'rest_base'                 => 'expertise_level',
			'rest_controller_class'     => 'WP_REST_Terms_Controller',
			
		);

		register_taxonomy(apply_filters('workreap_product_taxonomy_expertises_name', 'expertise_level'), array('product', apply_filters('workreap_profiles_post_type_name', 'profiles')), apply_filters('workreap_product_taxonomy_expertise', $expertise_args));
		$skills_labels = array(
			'name' 				=> esc_html__('Skills', 'workreap'),
			'singular_name' 	=> esc_html__('Skill','workreap'),
			'search_items' 		=> esc_html__('Search skill', 'workreap'),
			'all_items' 		=> esc_html__('All skill', 'workreap'),
			'parent_item' 		=> esc_html__('Parent skill', 'workreap'),
			'parent_item_colon' => esc_html__('Parent skill:', 'workreap'),
			'edit_item' 		=> esc_html__('Edit skill', 'workreap'),
			'update_item' 		=> esc_html__('Update skill', 'workreap'),
			'add_new_item' 		=> esc_html__('Add new skill', 'workreap'),
			'new_item_name' 	=> esc_html__('New skill name', 'workreap'),
			'menu_name' 		=> esc_html__('Skills', 'workreap'),
		);

		$skills_arg = array(
			'hierarchical'          => true,
			'labels'                => apply_filters('workreap_product_taxonomy_skills_labels', $skills_labels),
			'show_ui'               => true,
			'show_in_nav_menus' 	=> false,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => array('slug' => 'skills'),			
			'show_in_rest'              => true,
			'rest_base'                 => 'skills',
			'rest_controller_class'     => 'WP_REST_Terms_Controller',
			
		);

		register_taxonomy(apply_filters('workreap_product_taxonomy_skills_name', 'skills'), array('product','freelancers', apply_filters('workreap_profiles_post_type_name', 'profiles')), apply_filters('workreap_product_taxonomy_skills', $skills_arg));
		

		$delivery_time = array(
			'name' 				=> esc_html__('Delivery time', 'workreap'),
			'singular_name' 	=> esc_html__('Delivery time','workreap'),
			'search_items' 		=> esc_html__('Search delivery time', 'workreap'),
			'all_items' 		=> esc_html__('All delivery time', 'workreap'),
			'parent_item' 		=> esc_html__('Parent delivery time', 'workreap'),
			'parent_item_colon' => esc_html__('Parent delivery time:', 'workreap'),
			'edit_item' 		=> esc_html__('Edit delivery time', 'workreap'),
			'update_item' 		=> esc_html__('Update delivery time', 'workreap'),
			'add_new_item' 		=> esc_html__('Add New delivery time', 'workreap'),
			'new_item_name' 	=> esc_html__('New delivery time name', 'workreap'),
			'menu_name' 		=> esc_html__('Delivery time', 'workreap'),
		);

		$delivery_time_args = array(
			'hierarchical'          => true,
			'labels'                => apply_filters('workreap_product_taxonomy_delivery_time_labels', $delivery_time),
			'show_ui'               => true,
			'show_in_nav_menus' 	=> false,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => array('slug' => 'delivery_time'),			
			'show_in_rest'              => true,
			'rest_base'                 => 'delivery_time',
			'rest_controller_class'     => 'WP_REST_Terms_Controller',
			
		);	
		
		register_taxonomy(apply_filters('workreap_product_taxonomy_delivery_time_name', 'delivery_time'), array('product', apply_filters('workreap_profiles_post_type_name', 'profiles')), apply_filters('workreap_product_taxonomy_delivery_time', $delivery_time_args));

	}

}

new Workreap_Admin_Taxonomies();
