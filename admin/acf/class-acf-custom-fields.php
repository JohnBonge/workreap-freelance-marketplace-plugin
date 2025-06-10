<?php
/**
 * 
 * ACF custom input radio for dashboard menu
 * ACF custom image field for FAQ categories
 * ACF custom input field for Delivery time
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/acf
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
if ( function_exists( 'acf_add_local_field_group' ) ):
	/*
	 * Dashboard menu feild
	 */
	acf_add_local_field_group( array(
		'key' => 'group_workreap6193725c1ce90',
		'title' => esc_html__( 'Dashboard menu', 'workreap' ),
		'fields' => array(
			array(
				'key' => 'field_workreap619372a56a031',
				'label' => esc_html__( 'Show login user details', 'workreap' ),
				'name' => 'login_user_details',
				'type' => 'radio',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'no' => esc_html__( 'No', 'workreap' ),
					'yes' => esc_html__( 'Yes', 'workreap' ),
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'layout' => 'vertical',
				'return_format' => 'value',
				'save_other_choice' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'nav_menu',
					'operator' => '==',
					'value' => 'location/primary-menu',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	) );

/*
 * FAQ category image
 */
acf_add_local_field_group( array(
	'key' => 'group_workreap61973a33d70e2',
	'title' => esc_html__( 'FAQ category fields', 'workreap' ),
	'fields' => array(
		array(
			'key' => 'field_workreap61973a5389853',
			'label' => esc_html__( 'Faq Category Image', 'workreap' ),
			'name' => 'faq_category_image',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'faq_categories',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );

/*
 * Delivery time taxonomy field
 */
acf_add_local_field_group( array(
	'key' => 'group_workreap6178f863a4dd7',
	'title' => esc_html__( 'Delivery time', 'workreap' ),
	'fields' => array(
		array(
			'key' => 'field_workreap6178f8829fdfd',
			'label' => esc_html__( 'Days', 'workreap' ),
			'name' => 'days',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 1,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'delivery_time',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
) );

add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_668e24226c5e8',
	'title' => 'Portfolio',
	'fields' => array(
		array(
			'key' => 'field_668e242339c78',
			'label' => 'Type',
			'name' => 'type',
			'aria-label' => '',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Select type' => 'Select type',
				'video' => 'Video',
				'link' => 'Link',
				'document' => 'Document',
				'gallery' => 'Gallery',
			),
			'default_value' => false,
			'return_format' => 'value',
			'multiple' => 0,
			'allow_null' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_668e26d839c79',
			'label' => 'URL',
			'name' => 'url',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_668e242339c78',
						'operator' => '==',
						'value' => 'link',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_668e270539c7a',
			'label' => 'Video URL',
			'name' => 'video_url',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_668e242339c78',
						'operator' => '==',
						'value' => 'video',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_668e276239c7c',
			'label' => 'Document',
			'name' => 'document',
			'aria-label' => '',
			'type' => 'file',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_668e242339c78',
						'operator' => '==',
						'value' => 'document',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'portfolios',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
} );






endif;