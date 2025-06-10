<?php
/**
 * Api Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
Redux::setSection( $opt_name,
	array(
		'title'      => esc_html__( 'API settings', 'workreap' ),
		'id'         => 'api-settings',
		'subsection' => false,
		'desc'       => '',
		'icon'       => 'el el-key',
		'fields'     => array(
			array(
				'id'    => 'divider_1',
				'type'  => 'info',
				'title' => esc_html__( 'Google API key', 'workreap' ),
				'style' => 'info',
			),
			array(
				'id'       => 'enable_zipcode',
				'type'     => 'switch',
				'title'    => esc_html__( 'Zipcode settings', 'workreap' ),
				'subtitle' => esc_html__( 'Verify zipcode from google geocoding API.', 'workreap' ),
				'default'  => false,
			),
			array(
				'id'       => 'google_map',
				'type'     => 'text',
				'title'    => esc_html__( 'Google map key', 'workreap' ),
				'default'  => '',
				'required' => array( 'enable_zipcode', '=', true ),
			),
			array(
				'id'       => 'enable_social_connect',
				'type'     => 'switch',
				'title'    => esc_html__( 'Google connect', 'workreap' ),
				'subtitle' => esc_html__( 'Login and register by using google account.', 'workreap' ),
				'default'  => false,
			),
			array(
				'id'       => 'google_client_id',
				'type'     => 'text',
				'title'    => esc_html__( 'Client ID', 'workreap' ),
				'required' => array( 'enable_social_connect', '=', true ),
			),
			array(
				'id'       => 'google_client_secret',
				'type'     => 'text',
				'title'    => esc_html__( 'Client secret', 'workreap' ),
				'required' => array( 'enable_social_connect', '=', true ),
			),

			array(
				'id'       => 'enable_ai',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI assitant', 'workreap' ),
				'subtitle' => esc_html__( 'Enable to use AI assistant by openAI.', 'workreap' ),
				'default'  => false,
			),
			array(
				'id'       => 'enable_ai_packages',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI for packages', 'workreap' ),
				'subtitle' => esc_html__( 'Enable to use AI on package purchase.', 'workreap' ),
				'default'  => false,
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'ai_client_id',
				'type'     => 'text',
				'title'    => esc_html__( 'Open AI Key', 'workreap' ),
				'subtitle' => esc_html__( 'Insert openAI key here.', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'defaul_ai_img',
				'type'     => 'media',
				'title'    => esc_html__( 'Default AI icon', 'workreap' ),
				'default'  => array( 'url' => WORKREAP_DIRECTORY_URI . '/public/images/expertise.svg' ),
				'subtitle' => esc_html__( 'Upload AI icon here.', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_service',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI for task posting', 'workreap' ),
				'default'  => false,
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_service_title',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for task title', 'workreap' ),
				'subtitle' => esc_html__( 'Add prompt using the {{ai_content}} parameter.', 'workreap' ),
				'required' => array( 'enable_ai_service', '=', true ),
			),
			array(
				'id'       => 'enable_ai_service_content',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for task content', 'workreap' ),
				'subtitle' => esc_html__( 'Add prompt using the {{ai_content}} parameter.', 'workreap' ),
				'required' => array( 'enable_ai_service', '=', true ),
			),
			array(
				'id'       => 'enable_ai_job',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI for project posting', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_job_title',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for project title', 'workreap' ),
				'subtitle' => esc_html__( 'Add prompt using the {{ai_content}} parameter.', 'workreap' ),
				'required' => array( 'enable_ai_job', '=', true ),
			),
			array(
				'id'       => 'enable_ai_job_content',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for project content', 'workreap' ),
				'subtitle' => esc_html__( 'Add prompt using the {{ai_content}} parameter.', 'workreap' ),
				'required' => array( 'enable_ai_job', '=', true ),
			),
			array(
				'id'       => 'enable_ai_proposal',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI for submit proposal', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_proposal_content',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for proposal content', 'workreap' ),
				'subtitle' => esc_html__( "Add prompt using the {{ai_content}} parameter.", 'workreap' ),
				'required' => array( 'enable_ai_proposal', '=', true ),
			),
			array(
				'id'       => 'enable_ai_service_hiring',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI prompt for hired task', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_service_hiring_content',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for hired task content', 'workreap' ),
				'subtitle' => esc_html__( "Add prompt using the {{ai_content}} parameter.", 'workreap' ),
				'required' => array( 'enable_ai_service_hiring', '=', true ),
			),
			array(
				'id'       => 'enable_ai_project_hiring',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI for hired project', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_project_hiring_content',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for hired project content', 'workreap' ),
				'subtitle' => esc_html__( "Add prompt using the {{ai_content}} parameter.", 'workreap' ),
				'required' => array( 'enable_ai_project_hiring', '=', true ),
			),

			array(
				'id'       => 'enable_ai_user',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI for user profile', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_user_content',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for user profile description', 'workreap' ),
				'subtitle' => esc_html__( 'Add prompt using the {{ai_content}} parameter.', 'workreap' ),
				'required' => array( 'enable_ai_user', '=', true ),
			),
			array(
				'id'       => 'enable_ai_custom_offer',
				'type'     => 'switch',
				'title'    => esc_html__( 'AI for custom offers', 'workreap' ),
				'required' => array( 'enable_ai', '=', true ),
			),
			array(
				'id'       => 'enable_ai_custom_offer_content',
				'type'     => 'textarea',
				'title'    => esc_html__( 'AI prompt for custom offer content', 'workreap' ),
				'subtitle' => esc_html__( 'Add prompt using the {{ai_content}} parameter.', 'workreap' ),
				'required' => array( 'enable_ai_custom_offer', '=', true ),
			),
		)
	)
);