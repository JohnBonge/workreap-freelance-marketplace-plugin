<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Custom Scripts Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

Redux::setSection( $opt_name, array(
		'title'      => esc_html__( 'CSS/JS scripts', 'workreap' ),
		'id'         => 'custom_code',
		'desc'       => '',
		'icon' 		 => 'el el-css',
		'subsection'       => false,
		'fields'     => array(
			array(
				'id'       => 'custom_css',
				'type'     => 'ace_editor',
				'title'    => esc_html__('Custom CSS', 'workreap'),
				'subtitle' => esc_html__('Insert your CSS code here', 'workreap'),
				'mode'     => 'css',
				'theme'    => 'monokai',
				'desc'     => '',
				'default'  => ""
			),
			array(
				'id'       => 'custom_js',
				'type'     => 'ace_editor',
				'title'    => esc_html__('Custom JS', 'workreap'),
				'subtitle' => esc_html__('Insert your JS code here', 'workreap'),
				'mode'     => 'css',
				'theme'    => 'monokai',
				'desc'     => '',
				'default'  => ""
			),
		)
	)
);
