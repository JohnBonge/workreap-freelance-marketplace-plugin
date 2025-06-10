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
$theme_version 	= wp_get_theme();
$datefomate_list= apply_filters('workreap_get_list_date_format', '');
$user_types     = apply_filters('workreap_get_user_types','');


Redux::setSection( $opt_name, array(
        'title'             => esc_html__( 'Directory settings', 'workreap' ),
        'id'                => 'directories-settings',
        'desc'       	      => '',
        'icon' 			        => 'el el-briefcase',
        'subsection'        => false,
            'fields'           => array(
                  array(
                    'id'        => 'application_access',
                    'type'      => 'select',
                    'title'     => esc_html__('Application Access', 'workreap'),
                    'subtitle'      => esc_html__('Choose the type based on the project, task or both.', 'workreap'),
                    'options'   => array(
                        'project_based'         => esc_html__('Project based application', 'workreap'),
                        'task_based'            => esc_html__('Task based application', 'workreap'),
                        'both'                  => esc_html__('Both Project and task based application', 'workreap'),
                    ),
                    'default'   => 'both',
                ),
                array(
                  'id'        => 'remove_cancel_order',
                  'type'      => 'select',
                  'title'     => esc_html__('Cancel order option', 'workreap'),
                  'subtitle'      => esc_html__('Hide cancel order option from the ongoing order page.', 'workreap'),
                  'default'   => 'no',
                  'options'   => array(
                    'yes'  	=> esc_html__('Yes', 'workreap'),
                    'no'  	=> esc_html__('No', 'workreap'),
                  ),
                ),
                array(
                    'id'       => 'user_update_option',
                    'type'     => 'switch',
                    'title'    => esc_html__( 'User action', 'workreap' ),
                    'default'  => false,
                    'subtitle'     => esc_html__( 'Account verification is required for submit any form.', 'workreap' )
                ),
                array(
                    'id'        => 'invoice_terms',
                    'type'      => 'editor',
                    'title'     => esc_html__('Invoice note', 'workreap'),
                    'default'   => '',
                    'subtitle'      => esc_html__('Add note to invoice.', 'workreap')
                ),

                array(
                    'id'        => 'invoice_billing_to',
                    'type'      => 'switch',
                    'title'     => esc_html__('Invoice billing to', 'workreap'),
                    'default'   => false,
                    'subtitle'      => esc_html__('Admin billing address on invoice.', 'workreap'),
                ),
                array(
                    'id'        => 'invoice_billing_address',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Add billing address', 'workreap'),
                    'subtitle'      => esc_html__('Add billing address to show on invoice.', 'workreap'),
                    'required'  => array('invoice_billing_to', '=', true),
                ),
                array(
                  'id'        => 'invoice_billing_wallet',
                  'type'      => 'textarea',
                  'title'     => esc_html__('Add billing address for wallet', 'workreap'),
                  'subtitle'      => esc_html__('Add billing address for wallet payments on the invoice.', 'workreap')
                ),
                array(
                  'id'        => 'invoice_billing_package',
                  'type'      => 'textarea',
                  'title'     => esc_html__('Add billing address for package', 'workreap'),
                  'subtitle'      => esc_html__('Add billing address for package to show on invoice.', 'workreap')
                ),
                array(
                    'id'        => 'default_image_extensions',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Image file extensions', 'workreap'),
                    'default'   => 'jpg,jpeg,gif,png',
                    'subtitle'  => esc_html__('Add image extensions as comma-separated text.', 'workreap'),
                ),
                array(
                    'id'        => 'default_file_extensions',
                    'type'      => 'textarea',
                    'title'     => esc_html__('File extensions', 'workreap'),
                    'default'   => 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,js,html,txt',
                    'subtitle'  => esc_html__('Add file extensions as comma-separated text.', 'workreap'),
                ),
                array(
                  'id'        => 'upload_file_size',
                  'type'      => 'slider',
                  "default" => 5,
                  "min" 		=> 1,
                  "step" 		=> 1,
                  "max" 		=> 500,
                  'title'     => esc_html__('Upload file size', 'workreap'),
                  'subtitle'   => esc_html__('Add upload file size in MB as an integer value.', 'workreap'),
               ),
                array(
                    'id'        => 'dateformat',
                    'type'      => 'select',
                    'title'     => esc_html__('Date format', 'workreap'),
                    'subtitle'      => esc_html__('Set the date format.', 'workreap'),
                    'options'   => $datefomate_list,
                    'default'   => 'Y-m-d',
                ),
                array(
                    'id'        => 'address_format',
                    'type'      => 'select',
                    'title'     => esc_html__('Profile address format', 'workreap'),
                    'subtitle'      => esc_html__('Set the profile address format.', 'workreap'),
                    'options'   => array(
                        'city_country'        => esc_html__('City, Country', 'workreap'),
                        'state_country'       => esc_html__('State, Country', 'workreap'),
                        'city_state_country'  => esc_html__('City, State, Country', 'workreap'),
                    ),
                    'default'   => 'state_country',
                ),
                array(
                    'id'        => 'activity_email',
                    'type'      => 'switch',
                    'title'     => esc_html__('Activity email', 'workreap'),
                    'default'   => true,
                    'subtitle'      => esc_html__('Enable or disable activity emails.', 'workreap')
                ),
                array(
                  'id'        => 'enable_state',
                  'type'      => 'switch',
                  'title'     => esc_html__('Enable states option', 'workreap'),
                  'default'   => false,
                  'subtitle'      => esc_html__('Enable or disable the country/state options.', 'workreap')
              ),
                array(
                    'id'        => 'shortname_option',
                    'type'      => 'switch',
                    'title'     => esc_html__('Short name', 'workreap'),
                    'default'   => false,
                    'subtitle'      => esc_html__('Enable or disable short names.', 'workreap')
                ),
                array(
                    'id'        => 'employer_refund_req_title',
                    'type'      => 'text',
                    'title'     => esc_html__('Refund request title', 'workreap'),
                    'default'   => esc_html__('Create refund request', 'workreap'),
                    'subtitle'      => esc_html__('Set refund request title here.', 'workreap')
                ),
                array(
                    'id'        => 'employer_refund_req_subheading',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Refund request subtitle', 'workreap'),
                    'default'   => '<h5>' . esc_html__('Choose issue you want to highlight', 'workreap') . '</h5>',
                    'subtitle'  => esc_html__('Refund request subtitle here with HTML tags.', 'workreap'),
                ),
                array(
                  'id' 		  => 'employer_dispute_option',
                  'type' 		=> 'slider',
                  'title' 	=> esc_html__('Set dispute option for employer', 'workreap'),
                  'subtitle' 		=> esc_html__('Set the minimum days for employer dispute.', 'workreap'),
                  "default" => 3,
                  "min" 		=> 1,
                  "step" 		=> 1,
                  "max" 		=> 50,
                  'display_value' => 'label',
                ),
                array(
                    'id'        => 'ads_content',
                    'type'      => 'editor',
                    'title'     => esc_html__('Ads content', 'workreap'),
                    'subtitle'  => esc_html__('Inset Ads content here.', 'workreap'),
                ),
                array(
                    'id'        => 'admin_dashboard_copyright',
                    'type'      => 'textarea',
                    'title'     => esc_html__('Admin dashboard footer text', 'workreap'),
                    'subtitle'      => esc_html__('Insert footer text for the admin dashboard here.', 'workreap'),
                    'default'   => sprintf(esc_html__('Copyright  &copy;%s, All Right Reserved', 'workreap'),date('Y')),
                ),
              array(
                'id'        => 'alertbox_autoclose',
                'type' 		  => 'slider',
                "default" 	=> 5000,
                "min" 		  => 1000,
                "step" 		  => 100,
                "max" 		  => 20000,
                'title'     => esc_html__('Popups timeout', 'workreap'),
                'subtitle'      => esc_html__('Set popup auto-close timeout in milliseconds.', 'workreap'),
            ),
        )
	)
);


Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Registration settings', 'workreap' ),
	'id'               => 'registration_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'fields'           =>  array(
        array(
          'id'        => 'defult_register_type',
          'type'      => 'select',
          'title'     => esc_html__('Default user registration', 'workreap'),
          'subtitle'      => esc_html__('Select the default user type on registration.', 'workreap'),
          'options'   => $user_types,
          'default'   => 'employers',
        ),
        array(
          'id'        => 'hide_registration',
          'type'      => 'select',
          'title'     => esc_html__('Remove registration', 'workreap'),
          'subtitle'      => esc_html__('Disable front-end registration.', 'workreap'),
          'default'   => 'no',
          'options'   => array(
            'no'  => esc_html__('No', 'workreap'),
            'yes'   => esc_html__('Yes', 'workreap'),
          ),
        ),
        array(
          'id'        => 'hide_role',
          'type'      => 'select',
          'title'     => esc_html__('Hide role', 'workreap'),
          'subtitle'      => esc_html__('Hide a role from registration.', 'workreap'),
          'default'   => 'both',
          'options'   => array(
            'both'    => esc_html__('None', 'workreap'),
            'freelancers'  => esc_html__('Freelancer', 'workreap'),
            'employers'   => esc_html__('Employer', 'workreap'),
          ),
        ),
        array(
          'id'        => 'registration_view_type',
          'type'      => 'select',
          'title'     => esc_html__('Login and registration type', 'workreap'),
          'subtitle'      => esc_html__('Select the login and registration type.', 'workreap'),
          'options'   => array(
            'pages'         => esc_html__('Page', 'workreap'),
            'popup'         => esc_html__('Popup', 'workreap'),
          ),
          'default'   => 'popup',
        ),
        array(
            'id'		  => 'popup_logo',
            'type' 		=> 'media',
            'url'		  => true,
            'title' 	=> esc_html__('Logo for popup', 'workreap'),
            'subtitle' 		=> esc_html__('Add logo for popup.', 'workreap'),
            'required'  => array('registration_view_type', '=', 'popup'),
        ),
        array(
          'id'        => 'email_user_registration',
          'type'      => 'select',
          'title'     => esc_html__('User verification', 'workreap'),
          'subtitle'      => esc_html__('Select verification type.', 'workreap'),
          'options'   => array(
            'verify_by_link'        => esc_html__('Verify by auto generated link', 'workreap'),
            'verify_by_admin'       => esc_html__('Verify by admin', 'workreap'),
          ),
          'default'   => 'verify_by_link',
        ),
		array(
			'id'        => 'user_password_strength',
			'type'      => 'select',
			'title'     => esc_html__('Password strength', 'workreap'),
			'subtitle'      => esc_html__('Set password strength options for registration.', 'workreap'),
			'options'   => array(
				'length'   			=> esc_html__('Length minimum 6 characters', 'workreap'),
				'upper'				=> esc_html__('1 Upper case letter', 'workreap'),
				'lower'  			=> esc_html__('1 Lower case letter', 'workreap'),
				'special_character' => esc_html__('Must have 1 special character', 'workreap'),
				'number'  			=> esc_html__('Must have 1 number', 'workreap')
			),
			'default'   => 'length',
			'multi'     => true,
		),
        array(
          'id'        => 'user_name_option',
          'type'      => 'switch',
          'title'     => esc_html__('Show username', 'workreap'),
          'subtitle'  => esc_html__('Show username field on registration', 'workreap'),
          'default'   => false,
        ),
        array(
          'id'        => 'identity_verification',
          'type'      => 'switch',
          'title'     => esc_html__('Identity verification', 'workreap'),
          'default'   => false,
          'subtitle'  => esc_html__('User identity verification option.', 'workreap')
        ),

        array(
          'id'        => 'remove_account_reasons',
          'type'      => 'multi_text',
          'title'     => esc_html__('Deactivate account', 'workreap'),
          'subtitle'  => esc_html__('Add multiple reasons for account deactivation.', 'workreap'),
          'default'   => array(
            esc_html__('Not interested anymore', 'workreap')
          )
        ),
        array(
          'id'        => 'switch_user',
          'type'      => 'switch',
          'title'     => esc_html__('Switch user', 'workreap'),
          'default'   => true,
          'subtitle'      => esc_html__('Enable or disable the switch user option.', 'workreap')
        ),
        array(
          'id'        => 'login_redirect_employer',
          'type'      => 'select',
          'title'     => esc_html__('Redirect employers after login', 'workreap'),
          'subtitle'      => esc_html__('Select page where to redirect employers after login.', 'workreap'),
          'default'   => 'profile',
          'options'   => array(
            'home'        => esc_html__('Home page', 'workreap'),
            'dashboard'   => esc_html__('Dashboard', 'workreap'),
            'freelancer'     => esc_html__('Freelancer search page', 'workreap'),
            'task'           => esc_html__('Task search page', 'workreap'),
          ),
        ),
        array(
          'id'        => 'login_redirect_freelancer',
          'type'      => 'select',
          'title'     => esc_html__('Redirect freelancers after login', 'workreap'),
          'subtitle'      => esc_html__('Select page where to redirect freelancers after login.', 'workreap'),
          'default'   => 'profile',
          'options'   => array(
            'home'        => esc_html__('Home page', 'workreap'),
            'dashboard'   => esc_html__('Dashboard', 'workreap'),
            'projects'    => esc_html__('Project search page', 'workreap'),
          ),
        ),
        array(
          'id'        => 'user_restriction',
          'type'      => 'switch',
          'title'     => esc_html__('Restrict users', 'workreap'),
          'default'   => false,
          'subtitle'      => esc_html__('Restrict user access to pages after login.', 'workreap')
        ),
        array(
          'id'    	=> 'employer_access_pages',
          'type'  	=> 'select',
          'title' 	=> esc_html__( 'Restrict pages for employer', 'workreap' ),
          'data'  	=> 'pages',
          'multi'    => true,
          'subtitle'      => esc_html__('Restrict employer access to pages after login.', 'workreap'),
          'required'  => array('user_restriction', '=', true),
        ),
        array(
          'id'    	=> 'freelancer_access_pages',
          'type'  	=> 'select',
          'title' 	=> esc_html__( 'Restrict pages for freelancer', 'workreap' ),
          'data'  	=> 'pages',
          'multi'    => true,
          'subtitle'      => esc_html__('Restrict freelancer access to pages after login.', 'workreap'),
          'required'  => array('user_restriction', '=', true),
        ),
      )
	)
);

$required_fields		= workreapProjectValidations();
$recomended_freelancers	= workreap_project_recomended_freelancers();
$project_plan_icon_fields = array(
	array(
		'id'        => 'fixed_projectmin_price',
		'type'      => 'text',
		'title'     => esc_html__('Fixed project minimum amount.', 'workreap'),
		'subtitle'  => esc_html__('Set the fixed project minimum amount.', 'workreap'),
		'default'   => 5,
	),
	array(
		'id'        => 'no_of_freelancers',
		'type'      => 'text',
		'title'     => esc_html__('Max freelancers for project', 'workreap'),
		'subtitle'      => esc_html__('Set Maximum freelancers for project.', 'workreap'),
		'default'   => 5,
	),
	array(
		'id'       => 'project_status',
		'type'     => 'select',
		'title'    => esc_html__('Default project status', 'workreap'),
		'subtitle'     => esc_html__('Select the default project status.', 'workreap'),
		'options'  => array(
			'publish' 	=> esc_html__('Publish', 'workreap'),
			'pending' 	=> esc_html__('Pending', 'workreap')
		),
		'default'  => 'publish',
	),
    array(
        'id'    	=> 'project_edit_after_submit',
        'type'  	=> 'switch',
        'title' 	=> esc_html__( 'Edit submit project', 'workreap' ),
        'subtitle' 	=> esc_html__( 'Enable/disable editing of projects before approval.', 'workreap' ),
        'required'  => array('project_status', '=', 'pending'),
        'default'  	=> true,
    ),
  array(
		'id'       => 'hide_fixed_milestone',
		'type'     => 'select',
		'title'    => esc_html__('Fixed project options', 'workreap'),
		'subtitle'     => esc_html__('Hide options if the employer requested milestones.', 'workreap'),
		'options'  => array(
			'yes' 	=> esc_html__('Yes, Hide it', 'workreap'),
			'no' 	  => esc_html__('No, Show both options to freelancers', 'workreap')
		),
		'default'  => 'no',
	),
  array(
		'id'       => 'project_multilevel_cat',
		'type'     => 'select',
		'title'    => esc_html__('Project sub categories', 'workreap'),
		'subtitle'     => esc_html__('Enable/disable project multilevel categories.', 'workreap'),
		'options'  => array(
			'enable' 	    => esc_html__('Enable', 'workreap'),
			'disable' 	  => esc_html__('Disable', 'workreap')
		),
		'default'  => 'disable',
	),
	array(
		'id'    	=> 'resubmit_project_status',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Approved task edit approval', 'workreap' ),
		'subtitle' 	=> esc_html__( 'Does approved task edit approval option', 'workreap' ),
		'options'  => array(
			'yes' 	=> esc_html__('Yes! It should get approved by the admin every time', 'workreap'),
			'no' 	=> esc_html__('No! Let it approve automatically', 'workreap')
		),
		'required'  => array('project_status', '=', 'pending'),
		'default'  	=> 'no',
	),
	array(
		'id'       	=> 'project_recomended_freelancers',
		'type'  	=> 'select',
		'title'    	=> esc_html__('Recommended freelancers','workreap'),
		'subtitle'      => esc_html__('Recommended freelancers for the project by','workreap'),
		'options'	=> $recomended_freelancers,
		'multi'    	=> true,
		'default'  	=> array(),
	),
	array(
		'id'        => 'employer_project_dispute_issues',
		'type'      => 'multi_text',
		'title'     => esc_html__('Employer dispute issues', 'workreap'),
		'default'   => array(
		  esc_html__('The freelancer is not responding', 'workreap'),
		  esc_html__('The freelancer sent me an unfinished product', 'workreap'),
		  esc_html__('Freelancer is abusive or using unprofessional language', 'workreap'),
		  esc_html__('Freelancer not sure with his/her skills set', 'workreap'),
		  esc_html__('Others', 'workreap'),
		),
		'subtitle'      => esc_html__('Add multiple employer dispute issues for project.', 'workreap')
	  ),
	  array(
		'id'        => 'freelancer_project_dispute_issues',
		'type'      => 'multi_text',
		'title'     => esc_html__('Freelancer dispute issues', 'workreap'),
		'default'   => array(
		  esc_html__('The employer is not responding', 'workreap'),
		  esc_html__("I’m too busy to complete this job", 'workreap'),
		  esc_html__('Due to personal reasons, I can not complete this job', 'workreap'),
		  esc_html__('Employer requesting unplanned additional work', 'workreap'),
		  esc_html__('Others', 'workreap'),
		),
		'subtitle'      => esc_html__('Add multiple freelancer dispute issues for project.', 'workreap')
	  ),
    array(
      'id'       => 'remove_languages',
      'type'     => 'select',
      'title'    => esc_html__('Language field', 'workreap'),
      'subtitle'     => esc_html__('Language field option for project posting.', 'workreap'),
      'options'  => array(
        'yes' 	=> esc_html__('Yes, Hide it', 'workreap'),
        'no' 	  => esc_html__('No, show languages options', 'workreap')
      ),
      'default'  => 'no',
    ),
);

if( !empty($required_fields) ){
	foreach($required_fields as $key => $fields){
		$default_key	= !empty($fields['default']) ? $fields['default'] : array();
		$project_title	= !empty($fields['title']) ? $fields['title'] : "";
		$project_des	= !empty($fields['details']) ? $fields['details'] : "";
		$fields			= !empty($fields['fields']) ? $fields['fields'] : array();
		$project_plan_icon_fields[] = array(
			'id'       	=> 'project_val_step'.$key,
			'type'  	=> 'select',
			'title'    	=> $project_title,
			'desc'      => $project_des,
			'options'	=> $fields,
			'multi'    	=> true,
			'default'  	=> $default_key,
		  );
	}
}

$project_plan_icon_fields[] = array(
    'id'    	=> 'enable_milestone_feature',
		'type'  	=> 'select',
		'title' 	=> esc_html__( 'Approved task edit approval', 'workreap' ),
		'subtitle' 	=> esc_html__( 'Does approved task edit approval required.', 'workreap' ),
		'options'   => array(
			'yes' 	  => esc_html__('Yes, Display milestone management in the project', 'workreap'),
			'no' 	    => esc_html__('No, Hide this', 'workreap')
		),
		'default'  	=> 'yes',
);

$project_plan_icon_fields[] = array(
  'id'       => 'hide_related',
  'type'     => 'select',
  'title'    => esc_html__('Hide related projects', 'workreap'),
  'subtitle'     => esc_html__('Hide related projects on project detail page.', 'workreap'),
  'options'  => array(
    'no' 	      => esc_html__('No', 'workreap'),
    'yes' 	    => esc_html__('Yes', 'workreap')
  ),
  'default'  => 'no',
);

$project_plan_icon_fields[] = array(
  'id'       => 'allow_hour_times',
  'type'     => 'select',
  'title'    => esc_html__('Freelancer add hours', 'workreap'),
  'subtitle'     => esc_html__('Allow freelancers to add past or future hours in the time card.', 'workreap'),
  'options'  => array(
    'past' 	    => esc_html__('Allow only past hours', 'workreap'),
    'both' 	    => esc_html__('Allow past and future hours', 'workreap'),
    'no' 	      => esc_html__('Don\'t allow to add hours', 'workreap'),
  ),
  'default'  => 'past',
);

Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Project settings ', 'workreap' ),
	'id'               => 'project_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'fields'           => $project_plan_icon_fields
	)
);


$proposal_settings = array(
	array(
		'id'       => 'proposal_status',
		'type'     => 'select',
		'title'    => esc_html__('Default proposal status', 'workreap'),
		'subtitle'     => esc_html__('Select the default proposal status.', 'workreap'),
		'options'  => array(
			'publish' 	=> esc_html__('Auto approved', 'workreap')
		),
		'default'  => 'publish',
	),
  array(
		'id'       => 'milestone_option',
		'type'     => 'select',
		'title'    => esc_html__('Milestone proposal amount', 'workreap'),
		'subtitle'    => esc_html__('Select milestone proposal amount option', 'workreap'),
		'options'  => array(
			'allow' 	  => esc_html__('Allow the freelancer to send less amount while submitting proposal', 'workreap'),
      'restrict' 	=> esc_html__('Restrict the freelancer to create milestones within proposed price', 'workreap')
		),
		'default'  => 'allow',
	),
  array(
    'id' 		=> 'credits_required',
    'type' 		=> 'slider',
    'title' 	=> esc_html__('Credits to apply', 'workreap'),
    'subtitle' 		=> esc_html__('Set the number of credits to apply to the project.', 'workreap'),
    'default' => 5,
    'min' 		=> 1,
    'step' 		=> 1,
    'max' 		=> 50
  )
);
Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Proposal settings ', 'workreap' ),
	'id'               => 'proposal_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'fields'           => $proposal_settings
	)
);
