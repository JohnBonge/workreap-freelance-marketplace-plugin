<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 
 * Template to display package tab meta fields
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/products_data
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

global $woocommerce, $post, $workreap_settings;
$enable_ai          = !empty($workreap_settings['enable_ai']) ? $workreap_settings['enable_ai'] : false;
$enable_ai_packages = !empty($workreap_settings['enable_ai_packages']) ? $workreap_settings['enable_ai_packages'] : false;
$workreap_subtask_fields_values = get_post_meta($post->ID, 'workreap_product_subtasks', TRUE);?>
<div class="options_group product-data-packages-feilds">
    <?php do_action('workreap_packages_fields_before', $workreap_product_tasks_fields);
    
    woocommerce_wp_select( array(
        'id'          => 'package_type',
        'value'       => get_post_meta( get_the_ID(), 'package_type', true ),
        'label'       => esc_html__('Package type', 'workreap'),
        'options'     => array( 'days' => esc_html__('Day(s)', 'workreap'), 'month' => esc_html__('Month(s)', 'workreap'), 'year' => esc_html__('Year(s)', 'workreap')),
    ) );
    woocommerce_wp_text_input( array(
        'id'            => 'package_duration',
        'value'         => get_post_meta( get_the_ID(), 'package_duration', true ),
        'label'         => esc_html__('Package duration', 'workreap'),
        'description'   => '',
        'custom_attributes' => array(
            'step' 	=> 'any',
            'min'	=> '0'
        )     
    ) );
    woocommerce_wp_text_input( array(
        'id'            => 'number_tasks_allowed',
        'value'         => get_post_meta( get_the_ID(), 'number_tasks_allowed', true ),
        'label'         => esc_html__('Number of task to post', 'workreap'),
        'description'   => '',
        'custom_attributes' => array(
            'step' 	=> 'any',
            'min'	=> '0'
        )     
    ) );
    woocommerce_wp_text_input( array(
        'id'            => 'featured_tasks_allowed',
        'value'         => get_post_meta( get_the_ID(), 'featured_tasks_allowed', true ),
        'label'         => esc_html__('Featured task', 'workreap'),
        'description'   => '',
        'custom_attributes' => array(
            'step' 	=> 'any',
            'min'	=> '0'
        ) 
    ) );
    woocommerce_wp_text_input( array(
        'id'            => 'featured_tasks_duration',
        'value'         => get_post_meta( get_the_ID(), 'featured_tasks_duration', true ),
        'label'         => esc_html__('Featured task duration(in days)', 'workreap'),
        'description'   => esc_html__('You can add the featured task duration, which means how many days task will be in featured listing', 'workreap'),
        'desc_tip' => 'true',
        'custom_attributes' => array(
            'step' 	=> 'any',
            'min'	=> '0'
        ) 
    ) );
    woocommerce_wp_text_input( array(
        'id'            => 'number_project_credits',
        'value'         => get_post_meta( get_the_ID(), 'number_project_credits', true ),
        'label'         => esc_html__('Credits to apply on projects', 'workreap'),
        'description'   => '',
        'custom_attributes' => array(
            'step' 	=> 'any',
            'min'	=> '0'
        ) 
    ) );
    woocommerce_wp_checkbox( array(
	    'id'            => 'task_plans_allowed',
	    'value'         => get_post_meta( get_the_ID(), 'task_plans_allowed', true ),
	    'label'         => esc_html__('Allow task plans', 'workreap'),
	    'description'   => '',
    ) );
    if(!empty($enable_ai) && !empty($enable_ai_packages) ){
        woocommerce_wp_checkbox( array(
            'id'            => 'allow_ai',
            'value'         => get_post_meta( $post->ID, 'allow_ai', true ),
            'label'         => esc_html__('Allow AI feature', 'workreap'),
            'description'   => '',
        ) );
    }
    woocommerce_wp_checkbox(
	    array(
		    'id'          => 'most_popular_task_package',
		    'label'       => __( 'Most Popular', 'workreap' ),
		    'value'       => get_post_meta($post->ID, 'most_popular_task_package', true),
	    )
    );
    
    do_action('workreap_packages_fields_after', $workreap_product_tasks_fields);?>
</div>