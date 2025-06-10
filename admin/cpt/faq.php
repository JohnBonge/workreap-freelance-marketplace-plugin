<?php
/**
 * Class 'Workreap_Admin_CPT_FAQ' defines the cusotm post type
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Workreap_CPT_FAQ {

  /**
   * FAQ post type
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
    $this->register_taxonomy();
  }

  /**
   *Regirster FAQ post type
   */
  public function register_posttype() {


    $labels = array(
      'name'                  => esc_html__( 'FAQ', 'workreap' ),
      'singular_name'         => esc_html__( 'FAQ', 'workreap' ),
      'menu_name'             => esc_html__( 'FAQ', 'workreap' ),
      'name_admin_bar'        => esc_html__( 'FAQ', 'workreap' ),
      'archives'              => esc_html__( 'FAQ Archives', 'workreap' ),
      'attributes'            => esc_html__( 'FAQ Attributes', 'workreap' ),
      'parent_item_colon'     => esc_html__( 'Parent FAQ:', 'workreap' ),
      'all_items'             => esc_html__( 'All FAQ', 'workreap' ),
      'add_new_item'          => esc_html__( 'Add new FAQ', 'workreap' ),
      'add_new'               => esc_html__( 'Add new FAQ', 'workreap' ),
      'new_item'              => esc_html__( 'New FAQ', 'workreap' ),
      'edit_item'             => esc_html__( 'Edit FAQ', 'workreap' ),
      'update_item'           => esc_html__( 'Update FAQ', 'workreap' ),
      'view_item'             => esc_html__( 'View FAQ', 'workreap' ),
      'view_items'            => esc_html__( 'View FAQ', 'workreap' ),
      'search_items'          => esc_html__( 'Search FAQ', 'workreap' ),
      'not_found'             => esc_html__( 'Not found', 'workreap' ),
      'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'workreap' ),
      'featured_image'        => esc_html__( 'Featured Image', 'workreap' ),
      'set_featured_image'    => esc_html__( 'Set featured image', 'workreap' ),
      'remove_featured_image' => esc_html__( 'Remove featured image', 'workreap' ),
      'use_featured_image'    => esc_html__( 'Use as featured image', 'workreap' ),
      'insert_into_item'      => esc_html__( 'Insert into Profile', 'workreap' ),
      'uploaded_to_this_item' => esc_html__( 'Uploaded to this Profile', 'workreap' ),
      'items_list'            => esc_html__( 'FAQ list', 'workreap' ),
      'items_list_navigation' => esc_html__( 'FAQ list navigation', 'workreap' ),
      'filter_items_list'     => esc_html__( 'Filter FAQ list', 'workreap' ),
    );

    $args = array(
      'label'                 => esc_html__( 'FAQ', 'workreap' ),
      'description'           => esc_html__( 'All FAQs', 'workreap' ),
      'labels'                => apply_filters('workreap_faq_cpt_labels', $labels),
      'supports'              => array( 'title','editor'),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-editor-help',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => true,
      'publicly_queryable'    => true,
      'capability_type'       => 'page',
      'show_in_rest'          => true,
      'rest_base'             => 'FAQ',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type( apply_filters('workreap_faq_post_type_name', 'faq'), $args );

  }

  /**
   *Regirster FAQ post type
   */
  public function register_taxonomy() {
    $cat_labels = array(
      'name' 					=> esc_html__('Categories', 'workreap'),
      'singular_name' 		=> esc_html__('Category','workreap'),
      'search_items'			=> esc_html__('Search Category', 'workreap'),
      'all_items' 				=> esc_html__('All Category', 'workreap'),
      'parent_item' 			=> esc_html__('Parent Category', 'workreap'),
      'parent_item_colon' => esc_html__('Parent Category:', 'workreap'),
      'edit_item' 				=> esc_html__('Edit Category', 'workreap'),
      'update_item' 			=> esc_html__('Update Category', 'workreap'),
      'add_new_item' 			=> esc_html__('Add New Category', 'workreap'),
      'new_item_name' 		=> esc_html__('New Category Name', 'workreap'),
      'menu_name' 				=> esc_html__('Categories', 'workreap'),
    );

    $cat_args = array(
      'hierarchical'              => true,
      'labels'			              => apply_filters('workreap_faq_taxonomy_labels', $cat_labels),
      'show_ui'                   => true,
      'show_admin_column'         => true,
      'query_var'                 => true,
      'rewrite'                   => array('slug' => 'faq_categories'),
      'show_in_rest'              => true,
      'rest_base'                 => 'faq_categories',
      'rest_controller_class'     => 'WP_REST_Terms_Controller'
      
    );

    register_taxonomy('faq_categories', array('faq'), $cat_args);
  }
}

new Workreap_CPT_FAQ();