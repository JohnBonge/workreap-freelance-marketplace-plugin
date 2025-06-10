<?php
/**
 * Directories settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
$theme_version 	  = wp_get_theme();
$listing_view     = array( 'left' => esc_html__('Layout 1','workreap'),
						   'top' => esc_html__('Layout 2','workreap')
						);

Redux::setSection( $opt_name, array(
        'title'             => esc_html__( 'Search settings', 'workreap' ),
        'id'                => 'search-settings',
        'desc'       	      => '',
        'icon' 			        => 'el el-search',
        'subsection'        => false,
            'fields'           => array(
                array(
                  'id'        => 'freelancer_listing_type',
                  'type'      => 'select',
                  'title'     => esc_html__('Freelancer listing style', 'workreap'),
                  'subtitle'      => esc_html__('Select freelancer listing style type.', 'workreap'),
                  'options'   => $listing_view,
                  'default'   => 'top',
                ),
				array(
					'id'        => 'projects_listing_view',
					'type'      => 'select',
					'title'     => esc_html__('Project listing style', 'workreap'),
					'subtitle'      => esc_html__('Select projects listing style type.', 'workreap'),
					'options'   => $listing_view,
					'default'   => 'top',
				),
				array(
					'id'        => 'task_listing_view',
					'type'      => 'select',
					'title'     => esc_html__('Task listing style', 'workreap'),
					'subtitle'      => esc_html__('Select tasks listing style type.', 'workreap'),
					'options'   => $listing_view,
					'default'   => 'top',
				),
	            array(
		            'id'        => 'disable_range_slider',
		            'type'      => 'switch',
		            'title'     => esc_html__('Disable price range slider', 'workreap'),
		            'default'   => false,
		            'subtitle'      => esc_html__('Disable price range slider on search pages.', 'workreap')
	            ),
	            array(
		            'id'        => 'min_search_price',
		            'type'      => 'text',
		            'title'     => esc_html__('Min search price', 'workreap'),
		            'subtitle'      => esc_html__('Set minimum search price here.', 'workreap'),
		            'default'   => 1,
	            ),
	            array(
		            'id'        => 'max_search_price',
		            'type'      => 'text',
		            'title'     => esc_html__('Max search price', 'workreap'),
		            'subtitle'      => esc_html__('Set maximum search price here.', 'workreap'),
		            'default'   => 5000,
	            ),
        )
	)
);
Redux::setSection( $opt_name, array(
    'title'             	=> esc_html__( 'Task search settings', 'workreap' ),
    'id'                	=> 'task_search_settings',
    'subsection'        	=> true,
    'fields'            	=>  array(
			array(
				'id'        => 'hide_task_filter_location',
				'type'      => 'switch',
				'title'     => esc_html__('Show location in task search page', 'workreap'),
				'subtitle'  => esc_html__('Show or hide location filter in task search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_task_filter_price',
				'type'      => 'switch',
				'title'     => esc_html__('Show price in task search page', 'workreap'),
				'subtitle'  => esc_html__('Show or hide price filter in task search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_task_filter_categories',
				'type'      => 'switch',
				'title'     => esc_html__('Show categories in task search page', 'workreap'),
				'subtitle'  => esc_html__('Show or hide categories filter in task search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_task_filter_tags',
				'type'      => 'switch',
				'title'     => esc_html__('Show tags in task search page', 'workreap'),
				'subtitle'  => esc_html__('Show or hide tags filter in task search page.', 'workreap'),
				'default'   => true,
			),
		)
	));

Redux::setSection( $opt_name, array(
	'title'             	=> esc_html__( 'Project search settings', 'workreap' ),
	'id'                	=> 'project_search_settings',
	'subsection'        	=> true,
	'fields'            	=>  array(
			array(
				'id'        => 'hide_project_filter_type',
				'type'      => 'switch',
				'title'     => esc_html__('Show project type in project search page', 'workreap'),
				'subtitle'  => esc_html__('Show or hide project type filter in project search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_location',
				'type'      => 'switch',
				'title'     => esc_html__('Show location in project search', 'workreap'),
				'subtitle'  => esc_html__('Show or hide location filter in project search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_skills',
				'type'      => 'switch',
				'title'     => esc_html__('Show project skills in project search', 'workreap'),
				'subtitle'  => esc_html__('Show or hide skills filter in project search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_level',
				'type'      => 'switch',
				'title'     => esc_html__('Show project expertise level in project search', 'workreap'),
				'subtitle'  => esc_html__('Show or hide expertise level filter in project search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_language',
				'type'      => 'switch',
				'title'     => esc_html__('Show project languages in project search', 'workreap'),
				'subtitle'  => esc_html__('Show or hide languages filter in project search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_price',
				'type'      => 'switch',
				'title'     => esc_html__('Show price in project search', 'workreap'),
				'subtitle'  => esc_html__('Show or hide price filter in project search page.', 'workreap'),
				'default'   => true,
			),
			array(
				'id'        => 'hide_project_filter_categories',
				'type'      => 'switch',
				'title'     => esc_html__('Show categories in project search', 'workreap'),
				'subtitle'  => esc_html__('Show or hide categories filter in project search page.', 'workreap'),
				'default'   => true,
			),
		)
	));

$searchFreelancerOptions	= array(
	array(
		'id'        => 'hide_freelancer_filter_type',
		'type'      => 'switch',
		'title'     => esc_html__('Show freelancer type in freelancer search', 'workreap'),
		'subtitle'  => esc_html__('Show or hide freelancer type filter in freelancer search page.', 'workreap'),
		'default'   => true,
	),
	array(
		'id'        => 'hide_freelancer_filter_location',
		'type'      => 'switch',
		'title'     => esc_html__('Show location in freelancer search', 'workreap'),
		'subtitle'  => esc_html__('Show or hide locations filter in freelancer search page.', 'workreap'),
		'default'   => true,
	),
	array(
		'id'        => 'hide_freelancer_filter_skills',
		'type'      => 'switch',
		'title'     => esc_html__('Show freelancer skills in freelancer search', 'workreap'),
		'subtitle'  => esc_html__('Show or hide skills filter in freelancer search page.', 'workreap'),
		'default'   => true,
	),
	array(
		'id'        => 'hide_freelancer_filter_level',
		'type'      => 'switch',
		'title'     => esc_html__('Show freelancer english level in freelancer search', 'workreap'),
		'subtitle'  => esc_html__('Show or hide english level filter in freelancer search page.', 'workreap'),
		'default'   => true,
	),
	array(
		'id'        => 'hide_freelancer_filter_language',
		'type'      => 'switch',
		'title'     => esc_html__('Show freelancer languages in freelancer search', 'workreap'),
		'subtitle'  => esc_html__('Show or hide languages filter in freelancer search page.', 'workreap'),
		'default'   => true,
	),
	array(
		'id'        => 'hide_freelancer_filter_price',
		'type'      => 'switch',
		'title'     => esc_html__('Show hourly rate in freelancer search', 'workreap'),
		'subtitle'  => esc_html__('Show or hide hourly rate filter in freelancer search page.', 'workreap'),
		'default'   => true,
	),
);
$searchFreelancerOptions	= apply_filters('workreap_search_freelancer_setting_filter', $searchFreelancerOptions);
Redux::setSection( $opt_name, array(
	'title'             	=> esc_html__( 'Freelancer search settings', 'workreap' ),
	'id'                	=> 'freelancer_search_settings',
	'subsection'        	=> true,
	'fields'            	=>  $searchFreelancerOptions
));
