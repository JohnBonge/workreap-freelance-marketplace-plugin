<?php
/**
 *
 * Class 'WithDrawStatuses' defines dispute email
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!class_exists('WithDrawStatuses')) {
    class WithDrawStatuses extends Workreap_Email_helper
    {

        /* Email to admin withdraw request */
        public function withdraw_admin_email_request($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to       = !empty( $workreap_settings['withdraw_request_admin_email'] ) ? $workreap_settings['withdraw_request_admin_email'] : get_option('admin_email', 'info@example.com');
            $user_name= !empty($user_name) ? $user_name : '';
            $user_link= !empty($user_link) ? $user_link : '';
            $amount= !empty($amount) ? $amount : 0;
            $detail_link= !empty($detail) ? $detail : '';

            $subject_default 	        = esc_html__('New withdrawal request has been received', 'workreap'); //default email subject
            $contact_default 	        = wp_kses(__('You have received a new withdraw request from the {{user_name}} <br/> You can click <a href="{{detail}}">this link</a> to view the withdrawal details <br/>', 'workreap'), //default email content
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
            $subject		    = !empty( $workreap_settings['withdraw_request_admin_subject'] ) ? $workreap_settings['withdraw_request_admin_subject'] : $subject_default; //getting subject
            $email_content  = !empty( $workreap_settings['withdraw_request_mail_content'] ) ? $workreap_settings['withdraw_request_mail_content'] : $contact_default; //getting content


            $email_content = str_replace("{{user_name}}", $user_name, $email_content);
            $email_content = str_replace("{{user_link}}", $user_link, $email_content);
            $email_content = str_replace("{{amount}}", $amount, $email_content);
            $email_content = str_replace("{{detail}}", $detail_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = '';
            $greeting['greet_value']        = '';
            $greeting['greet_option_key']   = '';

            $body = $this->workreap_email_body($email_content, $greeting);
            $body  = apply_filters('workreap_admin_withdraw_request_email_content', $body);

            wp_mail($email_to, $subject, $body); //send Email

        }

        public function withdraw_approved_user_email($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to = '';
            $user_name= !empty($user_name) ? $user_name : '';
            $user_link= !empty($user_link) ? $user_link : '';
            $amount= !empty($amount) ? $amount : 0;
            $detail_link= !empty($detail) ? $detail : '';

            $subject_default 	        = esc_html__('Your withdrawal request has been approved', 'workreap'); //default email subject
            $contact_default 	        = wp_kses(__('Your withdraw request has been approved. <br/> You can click <a href="{{detail}}">this link</a> to view the withdrawal details.<br/>', 'workreap'), //default email content
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
            $subject		    = !empty( $workreap_settings['withdraw_approve_user_subject'] ) ? $workreap_settings['withdraw_approve_user_subject'] : $subject_default; //getting subject
            $email_content  = !empty( $workreap_settings['withdraw_approved_mail_content'] ) ? $workreap_settings['withdraw_approved_mail_content'] : $contact_default; //getting content

            $email_content = str_replace("{{user_name}}", $user_name, $email_content);
            $email_content = str_replace("{{user_link}}", $user_link, $email_content);
            $email_content = str_replace("{{amount}}", $amount, $email_content);
            $email_content = str_replace("{{detail}}", $detail_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $user_name;
            $greeting['greet_option_key']   = 'withdraw_approve_user_greeting';

            $body = $this->workreap_email_body($email_content, $greeting);
            $body  = apply_filters('workreap_user_withdraw_approved_email_content', $body);

            wp_mail($email_to, $subject, $body); //send Email
        }
    }
}
