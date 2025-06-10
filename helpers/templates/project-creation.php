<?php
/**
 *
 * Class 'WorkreapProjectCreation' defines task status
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if (!class_exists('WorkreapProjectCreation')) {


  class WorkreapProjectCreation extends Workreap_Email_helper
  {

    public function __construct()
    {
      //do something
    }

    /**
     * Post a Project Employer Email
     */
    public function post_project_employer_email($params = '') {
      global  $workreap_settings;
      extract($params);
      $email_to             = !empty($employer_email) ? $employer_email : '';
      $project_title 		    = !empty($project_title) ? $project_title: '';
      $employer_name 	        = !empty($employer_name) ? $employer_name : '';
      $project_link 		    = !empty($project_link) ? $project_link : '';
      $subject_default 	    = esc_html__('Project submission', 'workreap'); //default email subject
      $contact_default 	    = wp_kses(__('Thank you for submitting the project, we will review and approve the project after the review. <br/>{{signature}},<br/>', 'workreap'), //default email content
        array(
        'a'       => array(
          'href'  => array(),
          'title' => array()
        ),
        'br'      => array(),
        'em'      => array(),
        'strong'  => array(),
      )
      );

      $subject          = !empty( $workreap_settings['post_project_employer_email_subject'] ) ? $workreap_settings['post_project_employer_email_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['post_project_content'] ) ? $workreap_settings['post_project_content'] : $contact_default; //getting content
      $project_link     = $this->process_email_links($project_link, $project_title); //project/post link

      $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
      $email_content = str_replace("{{project_title}}", $project_title, $email_content);
      $email_content = str_replace("{{project_link}}", $project_link, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'employer_name';
      $greeting['greet_value']      = $employer_name;
      $greeting['greet_option_key'] = 'post_project_employer_email_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_post_project_email_content', $body);

      wp_mail($email_to, $subject, $body); //send Email

    }


    /**
     * Post a Project need Admin Approval
     */
    public function post_project_approval_admin_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $employer_name        = !empty($employer_name) ? $employer_name : '';
      $project_title     = !empty($project_title) ? $project_title : '';
      $project_link      = !empty($project_link) ? $project_link : '';
      $email_to 		     = !empty( $workreap_settings['admin_email_project_approval'] ) ? $workreap_settings['admin_email_project_approval'] : get_option('admin_email', 'info@example.com'); //admin email

      $subject_default 	  = esc_html__('Project approval', 'workreap'); //default email subject
      $contact_default 	  = wp_kses(__('A new project {{project_title}} approval request received from {{employer_name}}<br>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'), //default email content
        array(
          'a'         => array(
            'href'    => array(),
            'title'   => array()
          ),
          'br'        => array(),
          'em'        => array(),
          'strong'    => array(),
        )
      );

      $subject		    = !empty( $workreap_settings['project_approval_admin_email_subject'] ) ? $workreap_settings['project_approval_admin_email_subject'] : $subject_default; //getting subject
      $email_content  = !empty( $workreap_settings['project_approval_admin_mail_content'] ) ? $workreap_settings['project_approval_admin_mail_content'] : $contact_default; //getting content
      $project_link   = $this->process_email_links($project_link, $project_title); //task/post link

      $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
      $email_content = str_replace("{{project_title}}", $project_title, $email_content);
      $email_content = str_replace("{{project_link}}", $project_link, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = '';
      $greeting['greet_value']      = '';
      $greeting['greet_option_key'] = '';

      $body = $this->workreap_email_body($email_content, $greeting);

      $body  = apply_filters('workreap_post_project_admin_email_approval_content', $body);

      wp_mail($email_to, $subject, $body); //send Email

    }


    /**
     * Project approved employer Email
     */
    public function approved_project_employer_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $email_to 	        = !empty($employer_email) ? $employer_email : '';
      $employer_name 	      = !empty($employer_name) ? $employer_name : '';
      $project_title 	    = !empty($project_title) ? $project_title : '';
      $project_link 	    = !empty($project_link) ? $project_link : '';

      $subject_default 	  = esc_html__('Project approved!', 'workreap'); //default email subject
      $contact_default 	  = wp_kses(__('Woohoo! Your project {{project_title}} has been approved.<br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'), //default email content
        array(
          'a' => array(
            'href' => array(),
            'title' => array()
          ),
          'br' => array(),
          'em' => array(),
          'strong' => array(),
        )
      );

      $subject		      = !empty( $workreap_settings['project_approved_employer_subject'] ) ? $workreap_settings['project_approved_employer_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['project_approved_project_content'] ) ? $workreap_settings['project_approved_project_content'] : $contact_default; //getting content

      $project_link_      = $this->process_email_links($project_link, $project_title); //task/post link

      $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
      $email_content = str_replace("{{project_title}}", $project_title, $email_content);
      $email_content = str_replace("{{project_link}}", $project_link_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'employer_name';
      $greeting['greet_value']      = $employer_name;
      $greeting['greet_option_key'] = 'project_approved_project_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_freelancer_task_approved_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /**
     * Project rejected Email to employer
     */
    public function reject_project_employer_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $email_to             = !empty($employer_email) ? $employer_email : '';
      $employer_name 	        = !empty($employer_name) ? $employer_name : '';
      $project_title 	      = !empty($project_title) ? $project_title : '';
      $project_link 	      = !empty($project_link) ? $project_link : '';

      $subject_default 	    = esc_html__('Project rejection', 'workreap'); //default email subject
      $contact_default 	    = wp_kses(__('Oho! Your project {{project_title}} has been rejected.<br /> Please click on the link below to view the project. {{project_link}}', 'workreap'), //default email content
        array(
          'a' => array(
            'href' => array(),
            'title' => array()
          ),
          'br' => array(),
          'em' => array(),
          'strong' => array(),
        )
      );

      $subject		      = !empty( $workreap_settings['project_rejected_employer_subject'] ) ? $workreap_settings['project_rejected_employer_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['project_rejected_employer_content'] ) ? $workreap_settings['project_rejected_employer_content'] : $contact_default; //getting content

      $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

      $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
      $email_content = str_replace("{{project_title}}", $project_title, $email_content);
      $email_content = str_replace("{{project_link}}", $project_link, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'employer_name';
      $greeting['greet_value']      = $employer_name;
      $greeting['greet_option_key'] = 'project_rejected_employer_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_employer_project_rejected_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /**
     * Project invitation Email to freelancer
     */
    public function invitation_project_freelancer_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $email_to             = !empty($freelancer_email) ? $freelancer_email : '';
      $employer_name 	        = !empty($employer_name) ? $employer_name : '';
      $freelancer_name 	        = !empty($freelancer_name) ? $freelancer_name : '';
      $project_title 	      = !empty($project_title) ? $project_title : '';
      $project_link 	      = !empty($project_link) ? $project_link : '';

      $subject_default 	    = esc_html__('Project invitation', 'workreap'); //default email subject
      $contact_default 	    = wp_kses(__('You have received a project invitation from {{employer_name}} Please click on the link below to view the project. {{project_link}}', 'workreap'), //default email content
        array(
          'a' => array(
            'href' => array(),
            'title' => array()
          ),
          'br' => array(),
          'em' => array(),
          'strong' => array(),
        )
      );

      $subject		      = !empty( $workreap_settings['project_invitation_email_subject'] ) ? $workreap_settings['project_invitation_email_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['project_invitation_freelancer_mail_content'] ) ? $workreap_settings['project_invitation_freelancer_mail_content'] : $contact_default; //getting content

      $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

      $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
      $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
      $email_content = str_replace("{{project_title}}", $project_title, $email_content);
      $email_content = str_replace("{{project_link}}", $project_link, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'freelancer_name';
      $greeting['greet_value']      = $freelancer_name;
      $greeting['greet_option_key'] = 'project_invitation_freelancer_email_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_freelancer_project_invitation_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /**
     * Project activity Email to receiver
     * (comments)
     */
    public function project_activity_receiver_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $email_to             = !empty($reciever_email) ? $reciever_email : '';
      $sender_name 	        = !empty($sender_name) ? $sender_name : '';
      $receiver_name 	      = !empty($receiver_name) ? $receiver_name : '';
      $project_title 	      = !empty($project_title) ? $project_title : '';
      $project_link 	      = !empty($project_link) ? $project_link : '';

      $subject_default 	    = esc_html__('Project activity', 'workreap'); //default email subject
      $contact_default 	    = wp_kses(__('A new activity performed by {{sender_name}} on a {{project_title}} project<br/>Please click on the button below to view the activity.<br/>{{project_link}}', 'workreap'), //default email content
        array(
          'a' => array(
            'href' => array(),
            'title' => array()
          ),
          'br' => array(),
          'em' => array(),
          'strong' => array(),
        )
      );

      $subject		      = !empty( $workreap_settings['project_activity_receiver_email_subject'] ) ? $workreap_settings['project_activity_receiver_email_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['project_activity_receiver_mail_content'] ) ? $workreap_settings['project_activity_receiver_mail_content'] : $contact_default; //getting content
      $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

      $email_content = str_replace("{{sender_name}}", $sender_name, $email_content);
      $email_content = str_replace("{{receiver_name}}", $receiver_name, $email_content);
      $email_content = str_replace("{{project_title}}", $project_title, $email_content);
      $email_content = str_replace("{{project_link}}", $project_link, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'receiver_name';
      $greeting['greet_value']      = $receiver_name;
      $greeting['greet_option_key'] = 'project_activity_receiver_email_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_receiver_project_activity_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

}
  new WorkreapProjectCreation();
}



