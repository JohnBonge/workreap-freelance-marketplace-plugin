<?php
/**
 *
 * Class 'WorkreapDisputeStatuses' defines dispute email
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if (!class_exists('WorkreapDisputeStatuses')) {
  class WorkreapDisputeStatuses extends Workreap_Email_helper{
	  
    /* Email Dispute received */
    public function dispute_received_admin_email($params = ''){
        global $workreap_settings;
        extract($params);
        $email_to 			   = !empty( $workreap_settings['disputes_admin_email'] ) ? $workreap_settings['disputes_admin_email'] : get_option('admin_email', 'info@example.com'); //admin email
        $freelancer_name_          = !empty($freelancer_name) ? $freelancer_name : '';
        $employer_name_           = !empty($employer_name) ? $employer_name : '';
        $task_name_            = !empty($task_name) ? $task_name : '';
        $task_link_            = !empty($task_link) ? $task_link : '';
        $order_id_             = !empty($order_id) ? $order_id : '';
        $order_amount_         = !empty($order_amount) ? $order_amount : '';
        $login_url_            = !empty($login_url) ? $login_url : '';

        $subject_default 	        = esc_html__('A new dispute received', 'workreap'); //default email subject
        $contact_default 	        = wp_kses(__('A new dispute has been created against the order #{{order_id}}', 'workreap'), //default email content
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

        $subject		    = !empty( $workreap_settings['disputes_admin_email_subject'] ) ? $workreap_settings['disputes_admin_email_subject'] : $subject_default; //getting subject
        $email_content  	= !empty( $workreap_settings['disputes_admin_mail_content'] ) ? $workreap_settings['disputes_admin_mail_content'] : $contact_default; //getting content

        $task_link__     = $this->process_email_links($task_link_, $task_name_);
        $login_url__     = $this->process_email_links($login_url_, esc_html__('Login','workreap'));

        $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']      = '';
        $greeting['greet_value']        = '';
        $greeting['greet_option_key']   = '';

        $body  = $this->workreap_email_body($email_content, $greeting);
        $body  = apply_filters('workreap_admin_dispute_email_content', $body);

        wp_mail($email_to, $subject, $body); //send Email

    }

    /* Email Dispute freelancer resolved */
    public function dispute_freelancer_resolved($params = ''){
        global $workreap_settings;
        extract($params);
        $email_to 			 = !empty($freelancer_email) ? $freelancer_email : '';
        $freelancer_name_          = !empty($freelancer_name) ? $freelancer_name : '';
        $task_name_            = !empty($task_name) ? $task_name : '';
        $task_link_            = !empty($task_link) ? $task_link : '';
        $order_id_             = !empty($order_id) ? $order_id : '';
        $order_amount_         = !empty($order_amount) ? $order_amount : '';
        $login_url_            = !empty($login_url) ? $login_url : '';

        $subject_default        = esc_html__('Dispute resolved', 'workreap'); //default email subject
        $contact_default        = wp_kses(__('Congratulations! <br/> We have gone through the refund and dispute and resolved the dispute in your favor. We completed the task and the amount has been added to your wallet.', 'workreap'), //default email content
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

        $subject         = !empty( $workreap_settings['disputes_resolved_freelancer_email_subject'] ) ? $workreap_settings['disputes_resolved_freelancer_email_subject'] : $subject_default;
        $email_content   = !empty( $workreap_settings['disputes_resolved_freelancer_mail_content'] ) ? $workreap_settings['disputes_resolved_freelancer_mail_content'] : $contact_default;
        $task_link__     = $this->process_email_links($task_link_, $task_name_);
        $login_url__     = $this->process_email_links($login_url_, esc_html__('Login','workreap'));

        $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']      = 'freelancer_name';
        $greeting['greet_value']        = $freelancer_name_;
        $greeting['greet_option_key']   = 'disputes_resolved_freelancer_email_greeting';

        $body   = $this->workreap_email_body($email_content, $greeting);
        $body   = apply_filters('workreap_freelancer_dispute_resolved_content', $body);

        wp_mail($email_to, $subject, $body); //send Email

    }

    /* Email Dispute freelancer canceled or not resolved in your fovour */
    public function dispute_freelancer_cancelled($params = ''){
        global $workreap_settings;
        extract($params);
        $email_to 			 = !empty($freelancer_email) ? $freelancer_email : '';
        $freelancer_name_          = !empty($freelancer_name) ? $freelancer_name : '';
        $task_name_            = !empty($task_name) ? $task_name : '';
        $task_link_            = !empty($task_link) ? $task_link : '';
        $order_id_             = !empty($order_id) ? $order_id : '';
        $order_amount_         = !empty($order_amount) ? $order_amount : '';
        $login_url_            = !empty($login_url) ? $login_url : '';

        $subject_default        = esc_html__('Dispute canceled', 'workreap'); //default email subject
        $contact_default        = wp_kses(__('The dispute has been cancelled.', 'workreap'), //default email content
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

        $subject         = !empty( $workreap_settings['disputes_cancelled_freelancer_email_subject'] ) ? $workreap_settings['disputes_cancelled_freelancer_email_subject'] : $subject_default;
        $email_content   = !empty( $workreap_settings['disputes_cancelled_freelancer_mail_content'] ) ? $workreap_settings['disputes_cancelled_freelancer_mail_content'] : $contact_default;
        $task_link__     = $this->process_email_links($task_link_, $task_name_);
        $login_url__     = $this->process_email_links($login_url_, esc_html__('Login','workreap'));

        $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']      = 'freelancer_name';
        $greeting['greet_value']        = $freelancer_name_;
        $greeting['greet_option_key']   = 'disputes_cancelled_freelancer_email_greeting';

        $body   = $this->workreap_email_body($email_content, $greeting);
        $body   = apply_filters('workreap_freelancer_dispute_cancelled_content', $body);

        wp_mail($email_to, $subject, $body); //send Email

    }

    /* Email Dispute employer resolved */
    public function dispute_employer_resolved($params = ''){
        global $workreap_settings;
        extract($params);
        $email_to                 = !empty($employer_email) ? $employer_email : '';
        $employer_name_              = !empty($employer_name) ? $employer_name : '';
        $task_name_               = !empty($task_name) ? $task_name : '';
        $task_link_               = !empty($task_link) ? $task_link : '';
        $order_id_                = !empty($order_id) ? $order_id : '';
        $order_amount_            = !empty($order_amount) ? $order_amount : '';
        $login_url_               = !empty($login_url) ? $login_url : '';

        $subject_default 	        = esc_html__('Dispute resolved', 'workreap'); //default email subject
        $contact_default 	        = wp_kses(__('Congratulations! <br/> We have gone through the dispute and resolved the dispute in your favor. The amount has been added to your wallet, you can try to hire someone else.', 'workreap'), //default email content
            array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            ));

        $subject		    = !empty( $workreap_settings['disputes_resolved_employer_email_subject'] ) ? $workreap_settings['disputes_resolved_employer_email_subject'] : $subject_default; //getting subject
        $email_content  = !empty( $workreap_settings['disputes_resolved_employer_mail_content'] ) ? $workreap_settings['disputes_resolved_employer_mail_content'] : $contact_default; //getting content

        $task_link__     = $this->process_email_links($task_link_, $task_name_);
        $login_url__     = $this->process_email_links($login_url_, esc_html__('Login','workreap'));

        $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']      = 'employer_name';
        $greeting['greet_value']        = $employer_name_;
        $greeting['greet_option_key']   = 'disputes_resolved_employer_email_greeting';

        $body = $this->workreap_email_body($email_content, $greeting);
        $body  = apply_filters('workreap_employer_dispute_resolved_content', $body);

        wp_mail($email_to, $subject, $body); //send Email
    }

    /* Email Dispute employer canceled or not resolved in yuor favour */
    public function dispute_employer_cancelled($params = ''){
        global $workreap_settings;
        extract($params);
        $email_to                 = !empty($employer_email) ? $employer_email : '';
        $employer_name_              = !empty($employer_name) ? $employer_name : '';
        $task_name_               = !empty($task_name) ? $task_name : '';
        $task_link_               = !empty($task_link) ? $task_link : '';
        $order_id_                = !empty($order_id) ? $order_id : '';
        $order_amount_            = !empty($order_amount) ? $order_amount : '';
        $login_url_               = !empty($login_url) ? $login_url : '';
        $subject_default 	        = esc_html__('Dispute canceled', 'workreap'); //default email subject
        $contact_default 	        = wp_kses(__('The dispute has been cancelled.', 'workreap'), //default email content
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

        $subject		    = !empty( $workreap_settings['disputes_cancelled_employer_email_subject'] ) ? $workreap_settings['disputes_cancelled_employer_email_subject'] : $subject_default; //getting subject
        $email_content  = !empty( $workreap_settings['disputes_cancelled_employer_mail_content'] ) ? $workreap_settings['disputes_cancelled_employer_mail_content'] : $contact_default; //getting content

        $task_link__     = $this->process_email_links($task_link_, $task_name_);
        $login_url__     = $this->process_email_links($login_url_, esc_html__('Login','workreap'));

        $email_content = str_replace("{{employer_name}}", $employer_name_, $email_content);
        $email_content = str_replace("{{task_name}}", $task_name_, $email_content);
        $email_content = str_replace("{{task_link}}", $task_link__, $email_content);
        $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
        $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
        $email_content = str_replace("{{login_url}}", $login_url__, $email_content);

        /* data for greeting */
        $greeting['greet_keyword']      = 'employer_name';
        $greeting['greet_value']        = $employer_name_;
        $greeting['greet_option_key']   = 'disputes_cancelled_employer_email_greeting';

        $body = $this->workreap_email_body($email_content, $greeting);
        $body  = apply_filters('workreap_employer_dispute_cancelled_content', $body);

        wp_mail($email_to, $subject, $body); //send Email

    }

  }
  new WorkreapDisputeStatuses();
}