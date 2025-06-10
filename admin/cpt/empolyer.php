<?php
/**
 * 
 * Class 'Workreap_Admin_CPT_Employer' defines the custom post type Employers
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Workreap_Admin_CPT_Employer {

	/**
	 * Employers post type
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action('init', array(&$this, 'init_post_type'));		
	}

	/**
	 * @Init post type
	*/
	public function init_post_type() {
		$this->register_posttype();
	}

	/**
	 *Regirster employer post type
	*/ 
	public function register_posttype() {
		$labels = array(
			'name'                  => esc_html__( 'Employers', 'workreap' ),
			'singular_name'         => esc_html__( 'Employer', 'workreap' ),
			'menu_name'             => esc_html__( 'Employers', 'workreap' ),
			'name_admin_bar'        => esc_html__( 'Employers', 'workreap' ),
			'parent_item_colon'     => esc_html__( 'Parent employer:', 'workreap' ),
			'all_items'             => esc_html__( 'All employers', 'workreap' ),
			'add_new_item'          => esc_html__( 'Add new employer', 'workreap' ),
			'add_new'               => esc_html__( 'Add new employer', 'workreap' ),
			'new_item'              => esc_html__( 'New employer', 'workreap' ),
			'edit_item'             => esc_html__( 'Edit employer', 'workreap' ),
			'update_item'           => esc_html__( 'Update employer', 'workreap' ),
			'view_item'             => esc_html__( 'View employers', 'workreap' ),
			'view_items'            => esc_html__( 'View employers', 'workreap' ),
			'search_items'          => esc_html__( 'Search employers', 'workreap' ),
		);
		
		$args = array(
			'label'                 => esc_html__( 'Employer', 'workreap' ),
			'description'           => esc_html__( 'All employer.', 'workreap' ),
			'labels'                => apply_filters('workreap_product_taxonomy_duration_labels', $labels),
			'taxonomies'            => array( 'product_cat'),
			'public' 				=> true,
			'supports' 				=> array('title','editor','author','excerpt','thumbnail'),
			'show_ui' 				=> true,
			'capability_type' 		=> 'post',
			'map_meta_cap' 			=> true,
			'publicly_queryable' 	=> false,
			'exclude_from_search' 	=> true,
			'hierarchical' 			=> false,
			'menu_position' 		=> 10,
			'rewrite' 				=> array('slug' => 'employer', 'with_front' => true),
			'query_var' 			=> false,
			'has_archive' 			=> false,
			'show_in_menu' 			=> 'edit.php?post_type=freelancers',
			'capabilities' 			=> array(
										'create_posts' => false
									),	
			'rest_base'             => 'employer',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		
		register_post_type( apply_filters('workreap_employer_post_type_name', 'employers'), $args );

	}  
}

new Workreap_Admin_CPT_Employer();