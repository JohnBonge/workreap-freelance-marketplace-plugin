<?php
/**
 * Class 'Workreap_CPT_Notification' defines the cusotm post type
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Workreap_CPT_Notification {

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
  }

  /**
   *Regirster Notification post type
   */
  public function register_posttype() {


    $labels = array(
      'name'                  => esc_html__( 'Notification', 'workreap' ),
      'singular_name'         => esc_html__( 'Notification', 'workreap' ),
      'menu_name'             => esc_html__( 'Notification', 'workreap' ),
      'name_admin_bar'        => esc_html__( 'Notification', 'workreap' ),
      'archives'              => esc_html__( 'Notification Archives', 'workreap' ),
      'attributes'            => esc_html__( 'Notification Attributes', 'workreap' ),
      'parent_item_colon'     => esc_html__( 'Parent Notification:', 'workreap' ),
      'all_items'             => esc_html__( 'All notification', 'workreap' ),
      'add_new_item'          => esc_html__( 'Add new Notification', 'workreap' ),
      'add_new'               => esc_html__( 'Add new Notification', 'workreap' ),
      'new_item'              => esc_html__( 'New Notification', 'workreap' ),
      'edit_item'             => esc_html__( 'Edit Notification', 'workreap' ),
      'update_item'           => esc_html__( 'Update Notification', 'workreap' ),
      'view_item'             => esc_html__( 'View Notification', 'workreap' ),
      'view_items'            => esc_html__( 'View Notification', 'workreap' ),
      'search_items'          => esc_html__( 'Search Notification', 'workreap' ),
      'not_found'             => esc_html__( 'Not found', 'workreap' ),
      'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'workreap' ),
      'items_list'            => esc_html__( 'Notification list', 'workreap' ),
      'items_list_navigation' => esc_html__( 'Notification list navigation', 'workreap' ),
      'filter_items_list'     => esc_html__( 'Filter Notification list', 'workreap' ),
    );

    $args = array(
      'label'                 => esc_html__( 'Notification', 'workreap' ),
      'description'           => esc_html__( 'All notifications', 'workreap' ),
      'labels'                => apply_filters('workreap_notification_cpt_labels', $labels),
      'supports'              => array('title','editor','author'),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'menu_position'         => 6,
      'menu_icon'             => 'dashicons-bell',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => true,
      'publicly_queryable' 	  => false,
      'capability_type'       => 'page',
      'show_in_rest'          => true,
      'rest_base'             => 'notification',
      'show_in_menu' 			    => 'edit.php?post_type=freelancers',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type( apply_filters('workreap_notification_post_type_name', 'notification'), $args );

  }

}

new Workreap_CPT_Notification();