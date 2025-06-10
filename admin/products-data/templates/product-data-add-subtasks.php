<?php
// die if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 
 * Template to display product data type subtaks tabs fields
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/products_data
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

global $woocommerce, $post;
$workreap_subtask_details_fields_values = get_post_meta($post->ID, 'workreap_product_subtasks', TRUE);
$workreap_subtask_details_fields = array(
  'title' => array(
      'id'          => 'title',
      'label'       => esc_html__('Add title', 'workreap'),
      'type'        => 'text',
      'value'       =>'',
      'class'       => 'title',
      'placeholder' => esc_html__('Add title', 'workreap'),
  ),
  'description'     => array(
      'id'          => 'description',
      'label'       => esc_html__('Description', 'workreap'),
      'type'        => 'textarea',
      'default_value'   => '',
      'class'           => 'description',
      'placeholder'     => esc_html__('Description', 'workreap'),
  ),
  'price'   => array(
      'id'      => 'price',
      'label'   => esc_html__('Price', 'workreap'),
      'type'    => 'text',
      'default_value'   => '',
      'class'           => 'price',
      'placeholder'     => esc_html__('Price', 'workreap'),
  ),
);

$workreap_subtask_details_fields = apply_filters('workreap_product_subtasks_details_fields', $workreap_subtask_details_fields);
do_action('workreap_subtasks_details_fields_before', $workreap_subtask_details_fields);
do_action('workreap_render_subtasks_details_fields', $workreap_subtask_details_fields, $workreap_subtask_details_fields_values);
do_action('workreap_subtasks_details_fields_after', $workreap_subtask_details_fields);
