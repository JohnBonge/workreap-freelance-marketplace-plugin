<?php
/**
 *
 * Class 'WorkreapRegistrationStatuses' defines task status
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if (!class_exists('WorkreapTaskStatuses')) {


  class WorkreapTaskStatuses extends Workreap_Email_helper
  {

    public function __construct()
    {
      //do something
    }

    /**
     * Post a Task Freelancer Email
     */
    public function post_task_freelancer_email($params = '') {
      global  $workreap_settings;
      extract($params);
      $email_to             = !empty($freelancer_email) ? $freelancer_email : '';
      $task_name_ 		      = !empty($task_name) ? $task_name: '';
      $freelancer_name_ 	      = !empty($freelancer_name) ? $freelancer_name : '';
      $task_link 		        = !empty($task_link) ? $task_link : '';
      $subject_default 	    = esc_html__('Task submission', 'workreap'); //default email subject
      $contact_default 	    = wp_kses(__('Thank you for submitting the task, we will review and approve the task after the review. <br/>{{signature}},<br/>', 'workreap'), //default email content
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

      $subject          = !empty( $workreap_settings['post_task_freelancer_email_subject'] ) ? $workreap_settings['post_task_freelancer_email_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['post_task_content'] ) ? $workreap_settings['post_task_content'] : $contact_default; //getting content
      $task_link_       = $this->process_email_links($task_link, $task_name_); //task/post link

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'freelancer_name';
      $greeting['greet_value']      = $freelancer_name_;
      $greeting['greet_option_key'] = 'post_task_freelancer_email_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_post_task_email_content', $body);

      wp_mail($email_to, $subject, $body); //send Email

    }


    /**
     * Post a Task need Admin Approval
     */
    public function post_task_approval_admin_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $freelancer_name_        = !empty($freelancer_name) ? $freelancer_name : '';
      $task_name_          = !empty($task_name) ? $task_name : '';
      $task_link           = !empty($task_link) ? $task_link : '';
      $email_to 		   = !empty( $workreap_settings['admin_email_task_approval'] ) ? $workreap_settings['admin_email_task_approval'] : get_option('admin_email', 'info@example.com'); //admin email

      $subject_default 	  = esc_html__('Task approval', 'workreap'); //default email subject
      $contact_default 	  = wp_kses(__('A new task has been posted by the {{freelancer_name}}, your approval is required to make it live.<br/>', 'workreap'), //default email content
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

      $subject		    = !empty( $workreap_settings['task_approval_admin_email_subject'] ) ? $workreap_settings['task_approval_admin_email_subject'] : $subject_default; //getting subject
      $email_content  = !empty( $workreap_settings['task_approval_admin_mail_content'] ) ? $workreap_settings['task_approval_admin_mail_content'] : $contact_default; //getting content
      $task_link_      = $this->process_email_links($task_link, $task_name_); //task/post link

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword'] = '';
      $greeting['greet_value'] = '';
      $greeting['greet_option_key'] = '';

      $body = $this->workreap_email_body($email_content, $greeting);

      $body  = apply_filters('workreap_post_task_admin_email_approval_content', $body);

      wp_mail($email_to, $subject, $body); //send Email

    }


    /**
     * Task approved Freelancer Email
     */
    public function approved_task_freelancer_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $email_to 	    = !empty($freelancer_email) ? $freelancer_email : '';
      $freelancer_name_ 	= !empty($freelancer_name) ? $freelancer_name : '';
      $task_name_ 	    = !empty($task_name) ? $task_name : '';
      $task_link_ 	    = !empty($task_link) ? $task_link : '';

      $subject_default 	  = esc_html__('Task approved!', 'workreap'); //default email subject
      $contact_default 	  = wp_kses(__('Your task “{{task_name}}” has been approved. <br/> You can view your task here <br/> {{task_link}} <br/>', 'workreap'), //default email content
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

      $subject		      = !empty( $workreap_settings['task_approved_freelancer_subject'] ) ? $workreap_settings['task_approved_freelancer_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['task_approved_freelancer_content'] ) ? $workreap_settings['task_approved_freelancer_content'] : $contact_default; //getting content

      $task_link__      = $this->process_email_links($task_link_, $task_name_); //task/post link

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link__, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'freelancer_name';
      $greeting['greet_value']      = $freelancer_name_;
      $greeting['greet_option_key'] = 'task_approved_freelancer_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_freelancer_task_approved_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /**
     * Task rejected Email to freelancer
     */
    public function reject_task_freelancer_email($params = '') {
      global  $workreap_settings;
      extract($params);

      $email_to             = !empty($freelancer_email) ? $freelancer_email : '';
      $freelancer_name_ 	    = !empty($freelancer_name) ? $freelancer_name : '';
      $task_name_ 	        = !empty($task_name) ? $task_name : '';
      $task_link_ 	        = !empty($task_link) ? $task_link : '';
      $admin_feedback_ 	    = !empty($admin_feedback) ? $admin_feedback : '';

      $subject_default 	    = esc_html__('Task rejected', 'workreap'); //default email subject
      $contact_default 	    = wp_kses(__('Your task “{{task_name}}” has been rejected. <br/> Please make the required changes and submit it again.<br/> {{admin_feedback}} <br/> ', 'workreap'), //default email content
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

      $subject		      = !empty( $workreap_settings['task_rejected_freelancer_subject'] ) ? $workreap_settings['task_rejected_freelancer_subject'] : $subject_default; //getting subject
      $email_content    = !empty( $workreap_settings['task_rejected_freelancer_content'] ) ? $workreap_settings['task_rejected_freelancer_content'] : $contact_default; //getting content

      $task_link__      = $this->process_email_links($task_link_, $task_name_); //task/post link

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
      $email_content = str_replace("{{admin_feedback}}", $admin_feedback_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']    = 'freelancer_name';
      $greeting['greet_value']      = $freelancer_name_;
      $greeting['greet_option_key'] = 'task_rejected_freelancer_greeting';

      $body   = $this->workreap_email_body($email_content, $greeting);
      $body   = apply_filters('workreap_freelancer_task_rejected_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }
}
  new WorkreapTaskStatuses();
}



