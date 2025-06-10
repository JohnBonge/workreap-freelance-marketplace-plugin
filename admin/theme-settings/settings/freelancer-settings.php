<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Task settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

$workreap_freelancers = array(
	array(
		'id'        => 'hide_english_level',
		'type'      => 'select',
		'title'     => esc_html__('Hide english level', 'workreap'),
		'subtitle'      => esc_html__('Hide english level from freelancer settings and profile page.', 'workreap'),
		'options'   => array(
			'yes'         => esc_html__('Yes', 'workreap'),
			'no'         => esc_html__('No', 'workreap')
		),
		'default'   => 'no',
	),
	array(
		'id'        => 'hide_skills',
		'type'      => 'select',
		'title'     => esc_html__('Hide skills', 'workreap'),
		'subtitle'      => esc_html__('Hide skills from freelancer settings and profile page.', 'workreap'),
		'options'   => array(
			'yes'         => esc_html__('Yes', 'workreap'),
			'no'         => esc_html__('No', 'workreap')
		),
		'default'   => 'no',
	),
	array(
		'id'        => 'hide_languages',
		'type'      => 'select',
		'title'     => esc_html__('Hide languages', 'workreap'),
		'subtitle'      => esc_html__('Hide languages from freelancer settings and profile page', 'workreap'),
		'options'   => array(
			'yes'         => esc_html__('Yes', 'workreap'),
			'no'         => esc_html__('No', 'workreap')
		),
		'default'   => 'no',
	),
	array(
		'id'        => 'hide_freelancer_without_avatar',
		'type'      => 'select',
		'title'     => esc_html__('Hide freelancers', 'workreap'),
		'subtitle'      => esc_html__('Hide freelancers without a profile image.', 'workreap'),
		'options'   => array(
			'yes'	=> esc_html__('Yes, hide profiles', 'workreap'),
			'no'	=> esc_html__('No', 'workreap'),
		),
		'default'   => 'no',
	),
	array(
		'id' 		    => 'portf_max_images',
		'type' 		=> 'slider',
		'title' 	    => esc_html__('Portfolio gallery images', 'workreap'),
		'subtitle' 		=> esc_html__('Set max number portfolio gallery images.', 'workreap'),
		'default' 	=> 3,
		'min' 		=> 1,
		'step' 		=> 1,
		'max'		    => 100,
		'display_value' => 'label',
	),
);


Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Freelancer settings ', 'workreap' ),
	'id'               => 'freelancer_settings',
	'desc'       	   => '',
	'subsection'       => false,
	'icon'			   => 'el el-adult',
	'fields'           => $workreap_freelancers
	)
);