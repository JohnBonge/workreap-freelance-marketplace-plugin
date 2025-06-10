<?php
/**
 * Header Settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

$search_list = array();
if ( function_exists( 'workreap_get_search_list' ) ) {
	$search_list = workreap_get_search_list( 'yes' );
}

Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Header settings', 'workreap' ),
	'id'               => 'header_settings',
	'icon'			   => 'el el-align-justify',
	'subsection'       => false,
	'fields'           => array(
		array(
			'id'        => 'wr_header_style',
			'type'      => 'select',
			'title'     => esc_html__('Header type', 'workreap'),
			'subtitle'      => esc_html__('Select header style type.', 'workreap'),
			'options'   => array(
				'one'         => esc_html__('Style 1', 'workreap'),
				'two'         => esc_html__('Style 2', 'workreap'),
				'three'         => esc_html__('Style 3', 'workreap'),
				'four'         => esc_html__('Style 4', 'workreap'),
			),
			'default'   => 'one',
		),
		array(
			'id'        => 'wr_header_container',
			'type'      => 'select',
			'title'     => esc_html__('Header container', 'workreap'),
			'subtitle'      => esc_html__('Select header container type.', 'workreap'),
			'options'   => array(
				'container'         => esc_html__('Contained', 'workreap'),
				'container-fluid'         => esc_html__('Full width', 'workreap'),
			),
			'default'   => 'container-fluid',
		),
		array(
			'id'		=> 'main_logo',
			'type' 		=> 'media',
			'url'		=> true,
			'title' 	=> esc_html__('Logo', 'workreap'),
			'subtitle' 		=> esc_html__('Set site header logo here.', 'workreap'),
		),
		array(
			'id'		=> 'transparent_logo',
			'type' 		=> 'media',
			'url'		=> true,
			'title' 	=> esc_html__('Transparent logo', 'workreap'),
			'subtitle' 		=> esc_html__('Set site header transparent white logo.', 'workreap'),
		),
		array(
			'id' 		=> 'logo_wide',
			'type' 		=> 'slider',
			'title' 	=> esc_html__('Set logo width', 'workreap'),
			'subtitle' 		=> esc_html__('Set logo width here in px.', 'workreap'),
			'default' 	=> 143,
			'min' 		=> 0,
			'step' 		=> 1,
			'max' 		=> 500,
			'display_value' => 'label',
		),
		array(
			'id'        => 'workreap_header_search',
			'type'      => 'switch',
			'default'   => false,
			'title'     => esc_html__('Header search', 'workreap'),
			'subtitle'      => esc_html__('Header search globally.', 'workreap'),
		),
		array(
			'id'        => 'workreap_header_search_type',
			'type'      => 'select',
			'title'     => esc_html__('Header search types', 'workreap'),
			'subtitle'      => esc_html__('Select header search type.', 'workreap'),
			'options'   => $search_list,
			'multi'     => true,
		),
		array(
			'id'        => 'workreap_header_top_bar',
			'type'      => 'switch',
			'default'   => false,
			'title'     => esc_html__('Header top bar', 'workreap'),
			'subtitle'      => esc_html__('Show Header topbar.', 'workreap'),
		),
		array(
			'id'      => 'workreap_header_top_bar_text',
			'type'    => 'text',
			'default' => '',
			'title'   => esc_html__( 'Header top bar text', 'workreap' ),
			'subtitle'      => esc_html__('Insert header topbar text here.', 'workreap'),
			'required' 	=> array('workreap_header_top_bar','equals','1')
		),
		array(
			'id'      => 'workreap_header_top_bar_btn_text',
			'type'    => 'text',
			'default' => esc_html__('Learn more','workreap'),
			'title'   => esc_html__( 'Header top bar button text', 'workreap' ),
			'subtitle'      => esc_html__('Insert header topbar button text here.', 'workreap'),
			'required' 	=> array('workreap_header_top_bar','equals','1')
		),
		array(
			'id'      => 'workreap_header_top_bar_btn_link',
			'type'    => 'text',
			'default' => '',
			'title'   => esc_html__( 'Header top bar button link', 'workreap' ),
			'subtitle'      => esc_html__('Insert header topbar button link here.', 'workreap'),
			'required' 	=> array('workreap_header_top_bar','equals','1')
		),
		array(
			'id'        => 'header_type_after_login',
			'type'      => 'select',
			'title'     => esc_html__('Header type after logged in', 'workreap'),
			'subtitle'      => esc_html__('Select header type for frontend pages when logged in.', 'workreap'),
			'options'   => array(
				'theme-header'         => esc_html__('Theme header', 'workreap'),
				'dashboard-header'         => esc_html__('Dashboard header', 'workreap'),
			),
			'default'   => 'theme-header',
		),
		array(
			'id'        => 'dashboard_header_type',
			'type'      => 'select',
			'title'     => esc_html__('Dashboard layout type', 'workreap'),
			'subtitle'      => esc_html__('Select layout type for the dashboard.', 'workreap'),
			'options'   => array(
				'workreap-topbar'         => esc_html__('Topbar', 'workreap'),
				'workreap-sidebar'         => esc_html__('Sidebar', 'workreap'),
			),
			'default'   => 'workreap-topbar',
		),
		array(
			'id'        => 'dashboard_sidebar_behaviour',
			'type'      => 'select',
			'title'     => esc_html__('Sidebar default behaviour', 'workreap'),
			'subtitle'      => esc_html__('Select the default behaviour of sidebar.', 'workreap'),
			'options'   => array(
				'expand'         => esc_html__('Expand', 'workreap'),
				'collapse'         => esc_html__('Collapse', 'workreap'),
			),
			'default'   => 'expand',
			'required'  => array('dashboard_header_type', '=', 'workreap-sidebar'),
		)
	)
));
