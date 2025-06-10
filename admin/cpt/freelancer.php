<?php
/**
 * 
 * Class 'Workreap_Admin_CPT_Freelancer' defines the cusotm post type
 * 
 * @package     Workreap
 * @subpackage  Workreap/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Workreap_Admin_CPT_Freelancer {

	/**
	 * Profiles post type
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action('init', array(&$this, 'init_post_type'));
		add_action('init', array(&$this, 'workreap_freelancer_type_taxonomy_register'));
		add_action('init', array(&$this, 'workreap_freelancer_english_level_taxonomy_register'));
		add_filter('manage_freelancers_posts_columns', array(&$this, 'freelancers_columns_add'));
		add_action('manage_freelancers_posts_custom_column', array(&$this, 'freelancers_columns'),10, 2);
	}
	/**
	 * @Prepare Columns
	 * @return {post}
	 */
	public function freelancers_columns_add($columns) {
		$columns['earning'] 		= esc_html__('Earning','workreap');
		$columns['commission'] 		= esc_html__('Admin commission','workreap');
		return $columns;
	}

		/**
	 * @Get Columns
	 * @return {}
	 */
	public function freelancers_columns($case) {
		global $post;
		$user_identity		= workreap_get_linked_profile_id($post->ID,'post');
		$account_blance 	= workreap_account_details($user_identity,array('wc-completed'),'hired');
		$completed_blance   = workreap_account_details($user_identity,array('wc-completed'),'completed');
		$total_amount       = $completed_blance+$account_blance;
		
		$admin_account_blance 	= workreap_account_details($user_identity,array('wc-completed'),'hired','admin_shares');
		$admin_completed_blance = workreap_account_details($user_identity,array('wc-completed'),'completed','admin_shares');
		$admin_total_amount     = $admin_completed_blance+$admin_account_blance;

		switch ($case) {
		case 'earning':
			workreap_price_format($total_amount);
		break;
		case 'commission':
			workreap_price_format($admin_total_amount);
		break;
		}
	}
	/**
	 * @Init post type
	*/
	public function init_post_type() {
		$this->register_posttype();
	}

	/**
	 *Regirster profiles post type
	*/
	public function register_posttype() {
		$labels = array(
			'name'                  => esc_html__( 'Freelancers', 'workreap' ),
			'singular_name'         => esc_html__( 'Freelancers','workreap' ),
			'menu_name'             => esc_html__( 'Freelancers', 'workreap' ),
			'name_admin_bar'        => esc_html__( 'Freelancers', 'workreap' ),
			'all_items'             => esc_html__( 'All freelancers', 'workreap' ),
			'add_new_item'          => esc_html__( 'Add new freelancer', 'workreap' ),
			'add_new'               => esc_html__( 'Add new freelancer', 'workreap' ),
			'new_item'              => esc_html__( 'New freelancer', 'workreap' ),
			'edit_item'             => esc_html__( 'Edit freelancer', 'workreap' ),
			'update_item'           => esc_html__( 'Update freelancer', 'workreap' ),
			'view_item'             => esc_html__( 'View freelancer', 'workreap' ),
			'view_items'            => esc_html__( 'View freelancer', 'workreap' ),
			'search_items'          => esc_html__( 'Search freelancer', 'workreap' ),
		);
		
		$args = array(
			'label'                 => esc_html__( 'Freelancers', 'workreap' ),
			'description'           => esc_html__( 'All freelancers.', 'workreap' ),
			'labels'                => apply_filters('workreap_product_taxonomy_duration_labels', $labels),
			'taxonomies'            => array( 'languages'),
			'public' 				=> true,
			'supports' 				=> array('title','editor','author','excerpt','thumbnail'),
			'show_ui' 				=> true,
			'capability_type' 		=> 'post',
			'map_meta_cap' 			=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> true,
			'hierarchical' 			=> false,
			'menu_position' 		=> 10,
			'rewrite' 				=> array('slug' => 'freelancer', 'with_front' => true),
			'query_var' 			=> false,
			'has_archive' 			=> false,
			'menu_icon'				=> WORKREAP_DIRECTORY_URI.'/public/images/wp-icon-workreap.png',
			'capabilities' 			=> array(
										'create_posts' => false
									),
			'rest_base'             => 'freelancer',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		register_post_type( apply_filters('workreap_profiles_post_type_name', 'freelancers'), $args );
	}

  /*
   * Freelancer type taxonomy
   */
  public function workreap_freelancer_type_taxonomy_register(){
    $freelancer_type_labels = array(
      'name' 				=> esc_html__('Freelancer type', 'workreap'),
      'singular_name' 		=> esc_html__('Freelancer type','workreap'),
      'search_items' 		=> esc_html__('Search freelancer type', 'workreap'),
      'all_items' 			=> esc_html__('All freelancer types', 'workreap'),
      'parent_item' 		=> esc_html__('Parent freelancer type', 'workreap'),
      'parent_item_colon' 	=> esc_html__('Parent freelancer type:', 'workreap'),
      'edit_item' 			=> esc_html__('Edit freelancer type', 'workreap'),
      'update_item' 		=> esc_html__('Update freelancer type', 'workreap'),
      'add_new_item' 		=> esc_html__('Add New freelancer type', 'workreap'),
      'new_item_name' 		=> esc_html__('New freelancer type name', 'workreap'),
      'menu_name' 			=> esc_html__('Freelancer type', 'workreap'),
    );
	  
    $freelancer_type_args = array(
      'hierarchical'		=> true,
      'labels' 				=> apply_filters('workreap_freelancer_type_taxonom_labels', $freelancer_type_labels),
      'show_ui' 			=> true,
      'show_admin_column' 	=> false,
      'show_in_nav_menus' 	=> false,
      'publicly_queryable'	=> true,
      'query_var' 			=> true,
      'show_in_rest' 		=> true,
      'rewrite' 			=> array('slug' => 'freelancer_type'),
    );
	  
    register_taxonomy('freelancer_type', array('freelancers'), $freelancer_type_args);
  }

  /*
   * English Level
   */
  public function workreap_freelancer_english_level_taxonomy_register(){
    $english_level_labels = array(
      'name' 				=> esc_html__('English level', 'workreap'),
      'singular_name' 		=> esc_html__('English level','workreap'),
      'search_items' 		=> esc_html__('Search English level', 'workreap'),
      'all_items' 			=> esc_html__('All English levels', 'workreap'),
      'parent_item' 		=> esc_html__('Parent English level', 'workreap'),
      'parent_item_colon' 	=> esc_html__('Parent English level:', 'workreap'),
      'edit_item' 			=> esc_html__('Edit English level', 'workreap'),
      'update_item' 		=> esc_html__('Update English level', 'workreap'),
      'add_new_item' 		=> esc_html__('Add New English level', 'workreap'),
      'new_item_name' 		=> esc_html__('New English level name', 'workreap'),
      'menu_name' 			=> esc_html__('English level', 'workreap'),
    );
    $english_level_args = array(
      'hierarchical'		=> true,
      'labels' 				=> apply_filters('workreap_english_level_taxonom_labels', $english_level_labels),
      'show_ui' 			=> true,
      'show_admin_column' 	=> false,
      'show_in_nav_menus' 	=> false,
      'publicly_queryable'	=> true,
      'query_var' 			=> true,
      'show_in_rest' 		=> true,
      'rewrite' 			=> array('slug' => 'english_level'),
    );
	  
    register_taxonomy('english_level', array('freelancers'), $english_level_args);
  }
}

new Workreap_Admin_CPT_Freelancer();