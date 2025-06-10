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

$workreap_plan_icon_fields = array(
	array(
		'id'       => 'custom_field_option',
		'type'     => 'switch',
		'title'    => esc_html__( 'Custom fields for freelancers', 'workreap' ),
		'default'  => false,
		'subtitle'     => esc_html__( 'Custom fields for freelancers when creating a task.', 'workreap' )
	),
	array(
		'id' 		=> 'maxnumber_fields',
		'type' 		=> 'slider',
		'title' 	=> esc_html__('Custom fields limit', 'workreap'),
		'subtitle' 		=> esc_html__('Set the max number of fields for task creation.', 'workreap'),
		'default' 	=> 5,
		'min' 		=> 1,
		'step' 		=> 1,
		'max' 		=> 100,
		'display_value' => 'label',
		'required'  => array('custom_field_option', '=', true),
	),
	array(
		'id' 		=> 'task_max_images',
		'type' 		=> 'slider',
		'title' 	=> esc_html__('Task gallery images', 'workreap'),
		'subtitle' 		=> esc_html__('Set the maximum number of gallery images for the task.', 'workreap'),
		'default' 	=> 3,
		'min' 		=> 1,
		'step' 		=> 1,
		'max' 		=> 100,
		'display_value' => 'label',
	),
	array(
		'id'       => 'task_downloadable',
		'type'     => 'switch',
		'title'    => esc_html__( 'Downloadable Task', 'workreap' ),
		'default'  => true,
		'subtitle'     => esc_html__( 'Option to add downloadable files to the task.', 'workreap' )
	),
	array(
		'id'       => 'allow_tags',
		'type'     => 'switch',
		'title'    => esc_html__( 'Allow tags', 'workreap' ),
		'default'  => true,
		'subtitle'     => esc_html__( 'Allow tags while creating task.', 'workreap' )
	),
    array(
        'id'       => 'task_description_length_option',
        'type'     => 'switch',
        'title'    => esc_html__( 'Task description length', 'workreap' ),
        'default'  => false,
        'desc'     => esc_html__( 'Add minimum and maximum description length conditions.', 'workreap' )
    ),
    array(
        'id' => 'task_description_length',
        'type' => 'slider',
        'title' => __('Task description range', 'workreap'),
        'subtitle' => __('Define the minimum and maximum task description word length.', 'workreap'),
        'default' => array(
            1 => 50,
            2 => 500,
        ),
        'min' => 0,
        'step' => 5,
        'max' => 1000,
        'display_value' => 'select',
        'handles' => 2,
        'required'  => array('task_description_length_option', '=', true),
    ),
	array(
		'id'    	=> 'hide_product_cat',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Hide product category', 'workreap' ),
		'data' 		=> 'terms',
		'args' 		=> array('taxonomies' => array( 'product_cat' ),'hide_empty' => false,),
		'multi'    	=> true,
		'subtitle'  => esc_html__('Select product for hiding on the search page', 'workreap'),
	),
);

$workreap_service_plans = Workreap_Service_Plans::service_plans();
foreach($workreap_service_plans as $plan_key => $plan_feilds){
  $workreap_plan_icon_fields[] = array(
    'id'       => 'task_plan_icon_'.$plan_key,
    'type'     => 'media',
    'title'    => wp_sprintf( '%s %s', ucfirst($plan_key), esc_html__( ' plan icon', 'workreap' ) ),
    'subtitle'  => wp_sprintf( 'Set %s %s', ucfirst($plan_key), esc_html__( ' plan icon here', 'workreap' ) ),
    'default'  => array( 'url' => WORKREAP_DIRECTORY_URI.'/public/images/task-plan-icon.jpg' ),
  );
}

$workreap_plan_icon_fields[] = array(
	'id'        => 'employer_dispute_issues',
	'type'      => 'multi_text',
	'title'     => esc_html__('Employer dispute issues', 'workreap'),
	'default'   => array(
		esc_html__('The freelancer is not responding', 'workreap'),
		esc_html__('The freelancer sent me an unfinished product', 'workreap'),
		esc_html__('Freelancer is abusive or using unprofessional language', 'workreap'),
		esc_html__('Freelancer not sure with his/her skills set', 'workreap'),
		esc_html__('Others', 'workreap'),
	),
	'subtitle'      => esc_html__('Add multiple employer dispute issues for task.', 'workreap')
);

$workreap_plan_icon_fields[] = array(
	'id'        => 'freelancer_dispute_issues',
	'type'      => 'multi_text',
	'title'     => esc_html__('Freelancer dispute issues', 'workreap'),
	'default'   => array(
		esc_html__('The employer is not responding', 'workreap'),
		esc_html__("I’m too busy to complete this job", 'workreap'),
		esc_html__('Due to personal reasons, I can not complete this job', 'workreap'),
		esc_html__('Employer requesting unplanned additional work', 'workreap'),
		esc_html__('Others', 'workreap'),
	),
	'subtitle'      => esc_html__('Add multiple freelancer dispute issues for task.', 'workreap')
);

$workreap_plan_icon_fields[] = array(
	'id'       => 'hide_deadline',
	'type'     => 'select',
	'title'    => esc_html__('Hide task deadline', 'workreap'),
	'subtitle'     => esc_html__('Hide task deadline from ongoing orders.', 'workreap'),
	'options'  => array(
		'yes' 	=> esc_html__('Yes', 'workreap'),
		'no' 	=> esc_html__('No', 'workreap')
	),
	'default'  => 'no',
);

$workreap_plan_icon_fields[] = array(
	'id'       => 'service_status',
	'type'     => 'select',
	'title'    => esc_html__('Default Task status', 'workreap'),
	'subtitle'     => esc_html__('Select the default task status.', 'workreap'),
	'options'  => array(
		'publish' 	=> esc_html__('Publish', 'workreap'),
		'pending' 	=> esc_html__('Pending', 'workreap')
	),
	'default'  => 'publish',
);

$workreap_plan_icon_fields[] = array(
	'id'       => 'remove_price_plans',
	'type'     => 'select',
	'title'    => esc_html__('Show packages', 'workreap'),
	'subtitle'     => esc_html__('Show the packages option when posting a task.', 'workreap'),
	'options'  => array(
		'yes' 	=> esc_html__('Show one package', 'workreap'),
		'no' 	=> esc_html__('Show three packages', 'workreap')
	),
	'default'  => 'no',
);

$workreap_plan_icon_fields[] = array(
	'id'    	=> 'resubmit_service_status',
	'type'  	=> 'select',
	'title' 	=> esc_html__( 'Approved task edit approval', 'workreap' ),
	'subtitle' 	=> esc_html__( 'Does approved task edit approval require.', 'workreap' ),
	'options'  => array(
		'yes' 	=> esc_html__('Yes! It should get approved by the admin every time', 'workreap'),
		'no' 	=> esc_html__('No! Let it approve automatically', 'workreap')
	),
	'required'  => array('service_status', '=', 'pending'),
	'default'  	=> 'no',
);


Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Task settings ', 'workreap' ),
	'id'               => 'task_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'fields'           => $workreap_plan_icon_fields
	)
);