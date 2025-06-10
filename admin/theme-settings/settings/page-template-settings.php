<?php
/**
 * Template Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
$add_page_template	= array(
	array(
		'id'    	=> 'tpl_admin_dashboard',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Administrator dashboard', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the administrator dashboard.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_dashboard',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'User dashboard', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the user dashboard.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_terms_conditions',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Terms & conditions', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the terms & conditions.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_privacy',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Privacy policy', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the privacy policy.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_login',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Login', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the login.', 'workreap'),
		'required'  => array('registration_view_type', '=', 'pages'),
	),
	array(
		'id'    	=> 'tpl_registration',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Registration', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the registration.', 'workreap'),
		'required'  => array('registration_view_type', '=', 'pages'),
	),
	  array(
		'id'    	=> 'tpl_forgot_password',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Forgot password', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the forgot password.', 'workreap'),
		'required'  => array('registration_view_type', '=', 'pages'),
	),
	  array(
		'id'    	=> 'tpl_service_search_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Search task', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the task search.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_project_search_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Search project', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the project search.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_freelancers_search_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Search freelancers', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the freelancers search.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_add_service_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Add/edit task', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the add/edit task.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_add_project_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Add/edit project', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the add/edit project.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_submit_proposal_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Submit proposal', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the submit proposal.', 'workreap'),
	),
	array(
		'id'    	=> 'tpl_package_page',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Select packages page', 'workreap' ),
		'data'  	=> 'pages',
		'subtitle'      => esc_html__('Select page for the packages.', 'workreap'),

	),
);
$add_page_template	= apply_filters( 'workreap_list_page_template', $add_page_template );
Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Template settings', 'workreap' ),
        'id'               => 'template_settings',
        'desc'       	   => '',
		'icon' 			   => 'el el-hdd',
		'subsection'       => false,
        'fields'           => $add_page_template
	)
);
