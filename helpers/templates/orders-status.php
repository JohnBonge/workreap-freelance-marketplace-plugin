<?php
/**
 *
 * Class 'WorkreapOrderStatuses' defines order activities
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if (!class_exists('WorkreapOrderStatuses')) {

    class WorkreapOrderStatuses extends Workreap_Email_helper{

      public function __construct() {
			  //do stuff here
      }

      /* new order freelancer email */
      public function new_order_freelancer_email($params = '') {
        global  $workreap_settings;
        extract($params);
        $freelancer_name_       = !empty($freelancer_name) ? $freelancer_name : '';
        $employer_name_        = !empty($employer_name) ? $employer_name : '';
        $task_name_         = !empty($task_name) ? $task_name : '';
        $task_link          = !empty($task_link) ? $task_link: '';
        $order_id           = !empty($order_id) ? $order_id: '';
        $order_amount       = !empty($order_amount) ? $order_amount : '';
        $freelancer_email_      = !empty($freelancer_email) ? $freelancer_email : '';
        $email_to 			    = !empty( $freelancer_email_ ) ? $freelancer_email_ : ''; //admin email
        $subject_default 	  = esc_html__('A new task order', 'workreap'); //default email subject
        $contact_default 	  = wp_kses(__('You have received a new order for the task “{{task_name}}”', 'workreap'), //default email content
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

        $subject		    = !empty( $workreap_settings['new_order_freelancer_email_subject'] ) ? $workreap_settings['new_order_freelancer_email_subject'] : $subject_default; //getting subject
        $content		    = !empty( $workreap_settings['new_order_freelancer_mail_content'] ) ? $workreap_settings['new_order_freelancer_mail_content'] : $contact_default; //getting conetnt
        $email_content  = $content; //getting content
        $task_link_     = $this->process_email_links($task_link, $task_name_); //task/post link

        $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content = str_replace("{{employer_name}}", $employer_name_ , $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link_, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount, $email_content);

        /* data for greeting */
        $greeting['greet_keyword'] = 'freelancer_name';
        $greeting['greet_value'] = $freelancer_name_;
        $greeting['greet_option_key'] = 'new_order_freelancer_email_greeting';

        $body = $this->workreap_email_body($email_content, $greeting);

        $body  = apply_filters('workreap_new_order_freelancer_email_content', $body);

        wp_mail($email_to, $subject, $body); //send Email

      }

      /* new order employer email */
      public function new_order_employer_email($params = '') {
        global  $workreap_settings;
        extract($params);

        $freelancer_name_       = !empty($freelancer_name) ? $freelancer_name : '';
        $employer_name_        = !empty($employer_name) ? $employer_name : '';
        $task_name_         = !empty($task_name) ? $task_name : '';
        $task_link          = !empty($task_link) ? $task_link : '';
        $order_id           = !empty($order_id) ? $order_id : '';
        $order_amount       = !empty($order_amount) ? $order_amount : '';
        $freelancer_email_      = !empty($freelancer_email) ? $freelancer_email : '';
        $employer_email_       = !empty($employer_email) ? $employer_email : '';
        $email_to 			    = !empty( $employer_email_ ) ? $employer_email_ : get_option('admin_email', 'info@example.com'); //admin email
        $subject_default 	  = esc_html__('New order', 'workreap'); //default email subject
        $contact_default 	  = wp_kses(__('Thank you so much for ordering my task. I will get in touch with you shortly.<br/>', 'workreap'), //default email content
          array(
            'a' => array(
              'href'    => array(),
              'title'   => array()
            ),
            'br'        => array(),
            'em'        => array(),
            'strong'    => array(),
          )
        );

        $subject		    = !empty( $workreap_settings['new_order_employer_email_subject'] ) ? $workreap_settings['new_order_employer_email_subject'] : $subject_default; //getting subject
        $content		    = !empty( $workreap_settings['new_order_employer_mail_content'] ) ? $workreap_settings['new_order_employer_mail_content'] : $contact_default; //getting content
        $email_content  = $content; //getting content
        $task_link_     = $this->process_email_links($task_link, $task_name_); //task/post link

        $email_content  = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content  = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content  = str_replace("{{task_link}}", $task_link_, $email_content);
        $email_content  = str_replace("{{order_id}}", $order_id, $email_content);
        $email_content  = str_replace("{{order_amount}}", $order_amount, $email_content);
        $email_content  = str_replace("{{employer_name}}", $employer_name_, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']    = 'employer_name';
        $greeting['greet_value']      = $employer_name_;
        $greeting['greet_option_key'] = 'new_order_employer_email_greeting';

        $body = $this->workreap_email_body($email_content, $greeting);

        $body  = apply_filters('workreap_new_order_employer_email_content', $body);

        wp_mail($email_to, $subject, $body); //send Email

      }

      /* Order complete request */
      public function order_complete_request_employer_email($params = '') {
        global  $workreap_settings;
        extract($params);

        $email_to             = !empty($employer_email)  ? $employer_email    : '';
        $freelancer_name_         = !empty($freelancer_name)  ? $freelancer_name    : '';
        $employer_name_          = !empty($employer_name)   ? $employer_name     : '';
        $task_name_           = !empty($task_name)    ? $task_name      : '';
        $task_link_           = !empty($task_link)    ? $task_link      : '';
        $order_id_            = !empty($order_id)     ? $order_id       : '';
        $login_url_           = !empty($login_url)    ? $login_url      : '';
        $order_amount_        = !empty($order_amount) ? $order_amount   : 0;
        $activity_page_link_  = !empty($activity_link) ? $activity_link : '';

        $subject_default 	 = esc_html__('Task completed request', 'workreap'); //default email subject
        $contact_default 	 = wp_kses(__('The freelancer “{{freelancer_name}}” has sent you the final delivery for the order #{{order_id}} <br/> You can accept or decline this. Please login to the site {{login_url}} and take a quick action on this activity {{activity_link}}', 'workreap'),
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

        $subject		          = !empty( $workreap_settings['order_complete_request_subject'] ) ? $workreap_settings['order_complete_request_subject'] : $subject_default; //getting subject
        $email_content        = !empty( $workreap_settings['order_complete_request_content'] ) ? $workreap_settings['order_complete_request_content'] : $contact_default; //getting content
        $task_link__          = $this->process_email_links( $task_link_, $task_name_ );
        $login_url__          = $this->process_email_links( $login_url_, esc_html__('Login', 'workreap') );
        $activity_page_link__ = $this->process_email_links( $activity_page_link_,esc_html__('Activity page', 'workreap')  );

        $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{activity_link}}", $activity_page_link__, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']    = 'employer_name';
        $greeting['greet_value']      = $employer_name_;
        $greeting['greet_option_key'] = 'order_complete_request_greeting';

        $body   = $this->workreap_email_body($email_content, $greeting);
        $body   = apply_filters('workreap_order_complete_request_email_content', $body);
        wp_mail($email_to, $subject, $body); //send Email

      }

      /* Order complete request Decline */
      public function order_complete_request_decline_freelancer_email($params = ''){
        global  $workreap_settings;
        extract($params);

        $email_to         = !empty($freelancer_email) ? $freelancer_email : '';
        $freelancer_name_     = !empty($freelancer_name) ? $freelancer_name : '';
        $employer_name_      = !empty($employer_name) ? $employer_name : '';
        $task_name_       = !empty($task_name)  ? $task_name : '';
        $task_link_       = !empty($task_link) ? $task_link : '';
        $order_id_        = !empty($order_id) ? $order_id : '';
        $order_amount_    = !empty($order_amount) ? $order_amount : 0;
        $login_url_       = !empty($login_url) ? $login_url : '';
        $employer_comments_  = !empty($employer_comments) ? $employer_comments : '';

        $subject_default 	 = esc_html__('Task completed request declined', 'workreap'); //default email subject
        $contact_default 	 = wp_kses(__('The employer “{{employer_name}}” has declined the final revision and has left some comments against the order #{{order_id}} <br/> "{{employer_comments}}" <br/>', 'workreap'),
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

        $subject		     = !empty( $workreap_settings['order_complete_request_declined_subject'] ) ? $workreap_settings['order_complete_request_declined_subject'] : $subject_default; //getting subject
        $email_content   = !empty( $workreap_settings['order_complete_request_declined_content'] ) ? $workreap_settings['order_complete_request_declined_content'] : $contact_default; //getting content
        $task_link__     = $this->process_email_links( $task_link_, $task_name_ );
        $login_url__     = $this->process_email_links( $login_url_, esc_html__('Login', 'workreap') );

        $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);
        $email_content = str_replace("{{employer_comments}}", $employer_comments_, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']    = 'freelancer_name';
        $greeting['greet_value']      = $freelancer_name_;
        $greeting['greet_option_key'] = 'order_complete_request_declined_greeting';

        $body   = $this->workreap_email_body($email_content, $greeting);
        $body   = apply_filters('workreap_order_complete_request_declined_email_content', $body);
        wp_mail($email_to, $subject, $body); //send Email

      }

      /* Order Completed */
      public function order_completed_freelancer_email($params = ''){
        global  $workreap_settings;
        extract($params);

        $email_to         = !empty($freelancer_email) ? $freelancer_email : '';
        $freelancer_name_     = !empty($freelancer_name) ? $freelancer_name : '';
        $employer_name_      = !empty($employer_name) ? $employer_name : '';
        $task_name_       = !empty($task_name) ? $task_name : '';
        $task_link_       = !empty($task_link) ? $task_link : '';
        $order_id_        = !empty($order_id) ? $order_id : '';
        $login_url_       = !empty($login_url) ? $login_url : '';
        $order_amount_    = !empty($order_amount) ? $order_amount : '';
        $employer_comments_  = !empty($employer_comments) ? $employer_comments : '';
        $employer_rating_    = !empty($employer_rating) ? $employer_rating : '';

        $subject_default 	 = esc_html__('Task completed', 'workreap'); //default email subject
        $contact_default 	 = wp_kses(__('Congratulations! <br/> The employer “{{employer_name}}” has closed the ongoing task with the order #{{order_id}} and has left some comments <br/> "{{employer_comments}}" <br/> Employer rating: {{employer_rating}} <br/> ', 'workreap'),
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

        $subject		     = !empty( $workreap_settings['order_completed_freelancer_subject'] ) ? $workreap_settings['order_completed_freelancer_subject'] : $subject_default; //getting subject
        $email_content   = !empty( $workreap_settings['order_completed_freelancer_content'] ) ? $workreap_settings['order_completed_freelancer_content'] : $contact_default; //getting content
        $task_link__     = $this->process_email_links( $task_link_, $task_name_ );
        $login_link__     = $this->process_email_links( $login_url_, esc_html__('Login','workreap') );

        $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_link__, $email_content);
        $email_content = str_replace("{{employer_comments}}", $employer_comments_, $email_content);
        $email_content = str_replace("{{employer_rating}}", $employer_rating_, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']    = 'freelancer_name';
        $greeting['greet_value']      = $freelancer_name_;
        $greeting['greet_option_key'] = 'order_completed_freelancer_greeting';

        $body   = $this->workreap_email_body($email_content, $greeting);
        $body   = apply_filters('workreap_order_completed_freelancer_email_content', $body);
        wp_mail($email_to, $subject, $body); //send Email

      }

      /* Order freelancer Activities */
      public function order_activities_freelancer_email($params = ''){
        global  $workreap_settings;
        extract($params);

        $email_to           = !empty($receiver_email) ? $receiver_email : '';
        $sender_name_       = !empty($sender_name) ? $sender_name : '';
        $receiver_name_     = !empty($receiver_name) ? $receiver_name : '';
        $task_name_         = !empty($task_name) ? $task_name : '';
        $task_link_         = !empty($task_link) ? $task_link : '';
        $order_id_          = !empty($order_id) ? $order_id : '';
        $order_amount_      = !empty($order_amount) ? $order_amount : '';
        $login_url_         = !empty($login_url) ? $login_url : '';
        $sender_comments_   = !empty($sender_comments) ? $sender_comments : '';

        $subject_default 	 = esc_html__('Order activity', 'workreap'); //default email subject
        $contact_default 	 = wp_kses(__('You have received a note from the “{{sender_name}}” on the ongoing task “{{task_name}}” against the order #{{order_id}} <br/> {{sender_comments}} <br/> You can login to take a quick action. <br/> {{login_url}} <br/> ', 'workreap'),
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

        $subject		     = !empty( $workreap_settings['order_activity_freelancer_subject'] ) ? $workreap_settings['order_activity_freelancer_subject'] : $subject_default; //getting subject
        $email_content   = !empty( $workreap_settings['order_activity_freelancer_content'] ) ? $workreap_settings['order_activity_freelancer_content'] : $contact_default; //getting content
        $task_link__     = $this->process_email_links( $task_link_, $task_name_ );
        $login_url__     = $this->process_email_links( $login_url_, esc_html__('Login', 'workreap') );

        $email_content = str_replace("{{sender_name}}", $sender_name_, $email_content);
        $email_content = str_replace("{{receiver_name}}", $receiver_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);
        $email_content = str_replace("{{sender_comments}}", $sender_comments_, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']    = 'receiver_name';
        $greeting['greet_value']      = $receiver_name_;
        $greeting['greet_option_key'] = 'order_activity_freelancer_gretting';

        $body   = $this->workreap_email_body($email_content, $greeting);
        $body   = apply_filters('workreap_order_activity_freelancer_email_content', $body);
        wp_mail($email_to, $subject, $body); //send Email


      }

      /* Order Employer Activities */
      public function order_activities_employer_email($params = ''){
        global  $workreap_settings;
        extract($params);

        $email_to           = !empty($receiver_email) ? $receiver_email : '';
        $sender_name_       = !empty($sender_name) ? $sender_name : '';
        $receiver_name_     = !empty($receiver_name) ? $receiver_name : '';
        $task_name_         = !empty($task_name) ? $task_name : '';
        $task_link_         = !empty($task_link) ? $task_link : '';
        $order_id_          = !empty($order_id) ? $order_id : '';
        $order_amount_      = !empty($order_amount) ? $order_amount : '';
        $login_url_         = !empty($login_url) ? $login_url : '';
        $sender_comments_   = !empty($sender_comments) ? $sender_comments : '';

        $subject_default 	 = esc_html__('Order activity', 'workreap'); //default email subject
        $contact_default 	 = wp_kses(__('You have received a note from the “{{sender_name}}” on the ongoing task “{{task_name}}” against the order #{{order_id}} <br/> {{sender_comments}} <br/> You can login to take a quick action. <br/> {{login_url}} <br/> ', 'workreap'),
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

        $subject		     = !empty( $workreap_settings['order_activity_employer_subject'] ) ? $workreap_settings['order_activity_employer_subject'] : $subject_default; //getting subject
        $email_content   = !empty( $workreap_settings['order_activity_employer_content'] ) ? $workreap_settings['order_activity_employer_content'] : $contact_default; //getting content
        $task_link__     = $this->process_email_links( $task_link_, $task_name_ );
        $login_url__     = $this->process_email_links( $login_url_, esc_html__('Login', 'workreap') );

        $email_content = str_replace("{{sender_name}}", $sender_name_, $email_content);
        $email_content = str_replace("{{receiver_name}}", $receiver_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);
        $email_content = str_replace("{{sender_comments}}", $sender_comments_, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']    = 'receiver_name';
        $greeting['greet_value']      = $receiver_name_;
        $greeting['greet_option_key'] = 'order_activity_employer_gretting';

        $body   = $this->workreap_email_body($email_content, $greeting);
        $body   = apply_filters('workreap_order_activity_employer_email_content', $body);
        wp_mail($email_to, $subject, $body); //send Email

      }

	}

	new WorkreapOrderStatuses();
}
