<?php
/**
 *
 * Class 'WorkreapRefundsStatuses' defines refund
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

if (!class_exists('WorkreapRefundsStatuses')) {

  class WorkreapRefundsStatuses extends Workreap_Email_helper
  {
    public function __construct()
    {
      //do something
    }

    /* Refund freelancer comments Email */
    public function refund_admin_comments_email($params = ''){
      global $workreap_settings;
      extract($params);

      $email_to           = !empty($receiver_email) ? $receiver_email : '';
      $sender_name_       = !empty($sender_name) ? $sender_name : '';
      $receiver_name_     = !empty($receiver_name) ? $receiver_name : '';
      $task_name_         = !empty($task_name) ? $task_name : '';
      $task_link_         = !empty($task_link) ? $task_link : '';
      $order_id_          = !empty($order_id) ? $order_id : '';
      $order_amount_      = !empty($order_amount) ? $order_amount : 0;
      $login_url_         = !empty($login_url) ? $login_url : '';
      $sender_comments_   = !empty($sender_comments) ? $sender_comments : '';

      $subject_default 	        = esc_html__('A new comment on refund request', 'workreap'); //default email subject
      $contact_default 	        = wp_kses(__('The Admin has left some comments on the refund request against the order #{{order_id}} <br/>{{sender_comments}} <br/> {login_url}}', 'workreap'),
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

      $subject		            = !empty( $workreap_settings['refund_admin_comment_subject'] ) ? $workreap_settings['refund_admin_comment_subject'] : $subject_default; //getting subject
      $email_content          = !empty( $workreap_settings['refund_admin_comment_content'] ) ? $workreap_settings['refund_admin_comment_content'] : $contact_default; //getting content

      $task_link__            = $this->process_email_links($task_link_, $task_name_ );
      $login_url__            = $this->process_email_links($login_url_, esc_html__('Login','workreap') );

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
      $greeting['greet_option_key'] = 'order_refund_admin_comment_email_greeting';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_admin_refund_comments_email_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /* Email Refund for freelancer */
    public function refund_freelancer_email($params = ''){
      global $workreap_settings;
      extract($params);

      $email_to         = !empty($freelancer_email) ? $freelancer_email : '';
      $freelancer_name_     = !empty($freelancer_name) ? $freelancer_name : '';
      $employer_name_      = !empty($employer_name) ? $employer_name : '';
      $task_name_       = !empty($task_name) ? $task_name : '';
      $task_link_       = !empty($task_link) ? $task_link : '';
      $order_id_        = !empty($order_id) ? $order_id : '';
      $order_amount_    = !empty($order_amount) ? $order_amount : 0;
      $login_url_       = !empty($login_url) ? $login_url : '';
      $employer_comments_  = !empty($employer_comments) ? $employer_comments : '';

      $subject_default 	        = esc_html__('A new refund request received', 'workreap'); //default email subject
      $contact_default 	        = wp_kses(__('You have received a refund request from {{employer_name}} against the order #{{order_id}} <br/> {{employer_comments}} <br/> You can approve or decline the refund request.<br/>{{login_url}}', 'workreap'),
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

      $subject		            = !empty( $workreap_settings['new_freelancer_refund_subject'] ) ? $workreap_settings['new_freelancer_refund_subject'] : $subject_default; //getting subject
      $email_content          = !empty( $workreap_settings['new_freelancer_refund_content'] ) ? $workreap_settings['new_freelancer_refund_content'] : $contact_default; //getting content

      $task_link__            = $this->process_email_links($task_link_, $task_name_);
      $login_url__            = $this->process_email_links($login_url_, esc_html__('Login','workreap'));

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
      $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
      $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
      $email_content = str_replace("{{login_url}}", $login_url__, $email_content);
      $email_content = str_replace("{{employer_comments}}", $employer_comments_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword'] = 'freelancer_name';
      $greeting['greet_value'] = $freelancer_name_;
      $greeting['greet_option_key'] = 'new_freelancer_refund_email_greeting';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_freelancer_refund_email_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /* Refund Approved */
    public function refund_approved_employer_email($params = ''){
      global $workreap_settings;
      extract($params);

      $email_to           = !empty($employer_email) ? $employer_email : '';
      $freelancer_name_       = !empty($freelancer_name) ? $freelancer_name : '';
      $employer_name_        = !empty($employer_name) ? $employer_name : '';
      $task_name_         = !empty($task_name) ? $task_name : '';
      $task_link_         = !empty($task_link) ? $task_link : '';
      $order_id_          = !empty($order_id) ? $order_id : '';
      $order_amount_      = !empty($order_amount) ? $order_amount : 0;
      $login_url_         = !empty($login_url) ? $login_url : '';

      $subject_default 	        = esc_html__('Refund approved', 'workreap'); //default email subject
      $contact_default 	        = wp_kses(__('Congratulations! <br/> Your refund request has been approved by the {{freelancer_name}} against the order #{{order_id}}', 'workreap'),
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

      $subject		            = !empty( $workreap_settings['employer_approved_refund_subject'] ) ? $workreap_settings['employer_approved_refund_subject'] : $subject_default; //getting subject
      $email_content          = !empty( $workreap_settings['approved_employer_refund_content'] ) ? $workreap_settings['approved_employer_refund_content'] : $contact_default; //getting content

      $task_link__            = $this->process_email_links($task_link_, $task_name_ );

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
      $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
      $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
      $email_content = str_replace("{{login_url}}", $login_url_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword'] = 'employer_name';
      $greeting['greet_value'] = $employer_name_;
      $greeting['greet_option_key'] = 'employer_approved_refund_email_greeting';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_employer_refund_approved_email_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /* Refund Decline */
    public function refund_declined_employer_email($params = ''){
      global $workreap_settings;
      extract($params);

      $email_to       = !empty($employer_email) ? $employer_email : '';
      $freelancer_name_   = !empty($freelancer_name) ? $freelancer_name : '';
      $employer_name_    = !empty($employer_name) ? $employer_name : '';
      $task_name_     = !empty($task_name) ? $task_name : '';
      $task_link_     = !empty($task_link) ? $task_link : '';
      $order_id_      = !empty($order_id) ? $order_id : '';
      $order_amount_  = !empty($order_amount) ? $order_amount : 0;
      $login_url_     = !empty($login_url) ? $login_url : '';

      $subject_default 	        = esc_html__('Refund declined', 'workreap'); //default email subject
      $contact_default 	        = wp_kses(__('Your refund request has been declined by the {{freelancer_name}} against the order #{{order_id}} <br/> If you think that this was a valid request then you can raise a dispute from the ongoing task page. <br/> {login_url}}', 'workreap'),
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

      $subject		            = !empty( $workreap_settings['employer_declined_refund_subject'] ) ? $workreap_settings['employer_declined_refund_subject'] : $subject_default; //getting subject
      $email_content          = !empty( $workreap_settings['declined_employer_refund_content'] ) ? $workreap_settings['declined_employer_refund_content'] : $contact_default; //getting content

      $task_link__            = $this->process_email_links($task_link_, $task_name_ );
      $login_url__            = $this->process_email_links($login_url_, esc_html__("Login","workreap"));

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
      $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
      $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
      $email_content = str_replace("{{login_url}}", $login_url__, $email_content);

      /* data for greeting */
      $greeting['greet_keyword'] = 'employer_name';
      $greeting['greet_value'] = $employer_name_;
      $greeting['greet_option_key'] = 'employer_declined_refund_email_greeting';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_employer_refund_declined_email_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /* Refund freelancer comments Email */
    public function refund_freelancer_comments_email($params = ''){
      global $workreap_settings;
      extract($params);

      $email_to           = !empty($receiver_email) ? $receiver_email : '';
      $sender_name_       = !empty($sender_name) ? $sender_name : '';
      $receiver_name_     = !empty($receiver_name) ? $receiver_name : '';
      $task_name_         = !empty($task_name) ? $task_name : '';
      $task_link_         = !empty($task_link) ? $task_link : '';
      $order_id_          = !empty($order_id) ? $order_id : '';
      $order_amount_      = !empty($order_amount) ? $order_amount : 0;
      $login_url_         = !empty($login_url) ? $login_url : '';
      $sender_comments_   = !empty($sender_comments) ? $sender_comments : '';

      $subject_default 	        = esc_html__('A new comment on refund request', 'workreap'); //default email subject
      $contact_default 	        = wp_kses(__('The “{{sender_name}}” has left some comments on the refund request against the order #{{order_id}} <br/>{{sender_comments}} <br/> {login_url}}', 'workreap'),
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

      $subject		            = !empty( $workreap_settings['refund_freelancer_comment_subject'] ) ? $workreap_settings['refund_freelancer_comment_subject'] : $subject_default; //getting subject
      $email_content          = !empty( $workreap_settings['refund_freelancer_comment_content'] ) ? $workreap_settings['refund_freelancer_comment_content'] : $contact_default; //getting content

      $task_link__            = $this->process_email_links($task_link_, $task_name_ );
      $login_url__            = $this->process_email_links($login_url_, esc_html__('Login','workreap') );

      $email_content = str_replace("{{sender_name}}", $sender_name_, $email_content);
      $email_content = str_replace("{{receiver_name}}", $receiver_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
      $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
      $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
      $email_content = str_replace("{{login_url}}", $login_url__, $email_content);
      $email_content = str_replace("{{sender_comments}}", $sender_comments_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword'] = 'receiver_name';
      $greeting['greet_value'] = $receiver_name_;
      $greeting['greet_option_key'] = 'order_refund_freelancer_comment_email_greeting';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_freelancer_refund_comments_email_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }

    /* Refund employer comments Email */
    public function refund_employer_comments_email($params = ''){
      global $workreap_settings;
      extract($params);

      $email_to           = !empty($receiver_email) ? $receiver_email : '';
      $sender_name_       = !empty($sender_name) ? $sender_name : '';
      $receiver_name_     = !empty($receiver_name) ? $receiver_name : '';
      $task_name_         = !empty($task_name) ? $task_name : '';
      $task_link_         = !empty($task_link) ? $task_link : '';
      $order_id_          = !empty($order_id) ? $order_id : '';
      $order_amount_      = !empty($order_amount) ? $order_amount : 0;
      $login_url_         = !empty($login_url) ? $login_url : '';
      $sender_comments_   = !empty($sender_comments) ? $sender_comments : '';

      $subject_default 	        = esc_html__('A new comment on refund request', 'workreap'); //default email subject
      $contact_default 	        = wp_kses(__('The “{{sender_name}}” has left some comments on the refund request against the order #{{order_id}} <br/>{{sender_comments}} <br/> {login_url}}', 'workreap'),
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

      $subject		            = !empty( $workreap_settings['refund_employer_comment_subject'] ) ? $workreap_settings['refund_employer_comment_subject'] : $subject_default; //getting subject
      $email_content          = !empty( $workreap_settings['refund_employer_comment_content'] ) ? $workreap_settings['refund_employer_comment_content'] : $contact_default; //getting content

      $task_link__ = $this->process_email_links($task_link_, $task_name_ );
      $login_url__ = $this->process_email_links($login_url_, esc_html__('Login','workreap') );

      $email_content = str_replace("{{sender_name}}", $sender_name_, $email_content);
      $email_content = str_replace("{{receiver_name}}", $receiver_name_, $email_content);
      $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
      $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
      $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
      $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
      $email_content = str_replace("{{login_url}}", $login_url__, $email_content);
      $email_content = str_replace("{{sender_comments}}", $sender_comments_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword'] = 'receiver_name';
      $greeting['greet_value'] = $receiver_name_;
      $greeting['greet_option_key'] = 'refund_employer_comment_greeting';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_employer_refund_comments_content', $body);
      wp_mail($email_to, $subject, $body); //send Email

    }


  }

  new WorkreapRefundsStatuses();
}
