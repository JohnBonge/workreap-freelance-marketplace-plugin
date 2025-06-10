<?php

/**
 *
 * Class 'WorkreapProjectDisputes' defines dispute email
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!class_exists('WorkreapProjectDisputes')) {
    class WorkreapProjectDisputes extends Workreap_Email_helper
    {
        public function __construct()
        {
            //do something
        }

        /* Email project dispute request to freelancer */
        public function dispute_project_request_freelancer_email($params = '')
        {
            global $workreap_settings;
            extract($params);

            $email_to           = !empty($freelancer_email) ? $freelancer_email: '';
            $employer_name 	    = !empty($employer_name) ? $employer_name: '';
            $freelancer_name 	    = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title 	    = !empty($project_title) ? $project_title : '';
            $dispute_link 	    = !empty($dispute_link) ? $dispute_link : '';

            $subject_default    = esc_html__('Project refund request', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('Project refund request received from {{employer_name}} of {{project_title}} project <br/>Please click on the button below to view the refund request.<br/>{{dispute_link}}', 'workreap'), //default email content
            array(
            'a'       => array(
            'href'  => array(),
            'title' => array()
            ),
            'br'      => array(),
            'em'      => array(),
            'strong'  => array(),
            ));

            $subject          = !empty( $workreap_settings['freelancer_project_dispute_req_email_subject'] ) ? $workreap_settings['freelancer_project_dispute_req_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty( $workreap_settings['project_dispute_req_freelancer_mail_content'] ) ? $workreap_settings['project_dispute_req_freelancer_mail_content'] : $contact_default; //getting content
            $proj_dispute_link     = $this->process_email_links($dispute_link, esc_html__('Project Dispute', 'workreap')); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{dispute_link}}", $proj_dispute_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'project_dispute_req_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_project_dispute_request_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Email project dispute request to admin by freelancer/employer */
        public function dispute_project_request_admin_email($params = '')
        {
            global $workreap_settings;
            extract($params);

            $email_to               = !empty( $workreap_settings['project_dispute_req_email_admin'] ) ? $workreap_settings['project_dispute_req_email_admin'] : get_option('admin_email', 'info@example.com');
            $user_name 	            = !empty($user_name) ? $user_name : '';
            $project_title          = !empty($project_title) ? $project_title : '';
            $admin_dispute_link     = !empty($admin_dispute_link) ? $admin_dispute_link : '';

            $subject_default    = esc_html__('Project dispute request', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('You have received a new dispute request from {{user_name}}<br/>Please click on the button below to view the dispute details.<br/>{{admin_dispute_link}}', 'workreap'), //default email content
            array(
            'a'       => array(
            'href'  => array(),
            'title' => array()
            ),
            'br'      => array(),
            'em'      => array(),
            'strong'  => array(),
            ));

            $subject            = !empty( $workreap_settings['admin_project_dispute_req_email_subject'] ) ? $workreap_settings['admin_project_dispute_req_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $workreap_settings['project_dispute_req_admin_mail_content'] ) ? $workreap_settings['project_dispute_req_admin_mail_content'] : $contact_default; //getting content
            $proj_dispute_link  = $this->process_email_links($admin_dispute_link, esc_html__('Project Dispute', 'workreap')); //project/post link

            $email_content = str_replace("{{user_name}}", $user_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{admin_dispute_link}}", $proj_dispute_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = '';
            $greeting['greet_value']      = '';
            $greeting['greet_option_key'] = '';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_admin_project_dispute_request_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Dispute refund in winner favour */
        public function project_dispute_refunded_resolved_in_favour($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to       = !empty($user_email) ? $user_email : '';
            $user_name 	    = !empty($user_name) ? $user_name: '';
            $admin_name     = !empty($admin_name) ? $admin_name: '';
            $dispute_link   = !empty($dispute_link) ? $dispute_link: '';

            $subject_default    = esc_html__('Project dispute refunded in favour', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('Woohoo! {{admin_name}} approved dispute refund request in your favor.<br/>Please click on the button below to view the dispute details.<br/>{{dispute_link}}', 'workreap'), //default email content
            array(
            'a'       => array(
            'href'  => array(),
            'title' => array()
            ),
            'br'      => array(),
            'em'      => array(),
            'strong'  => array(),
            ));

            $subject            = !empty( $workreap_settings['email_project_disputes_favour_winner_subject'] ) ? $workreap_settings['email_project_disputes_favour_winner_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $workreap_settings['project_disputes_favour_winner_content'] ) ? $workreap_settings['project_disputes_favour_winner_content'] : $contact_default; //getting content
            $dispute_link  = $this->process_email_links($dispute_link, esc_html__('Dispute link', 'workreap')); //project/post link

            $email_content = str_replace("{{user_name}}", $user_name, $email_content);
            $email_content = str_replace("{{admin_name}}", $admin_name, $email_content);
            $email_content = str_replace("{{dispute_link}}", $dispute_link, $email_content); 
            
            /* data for greeting */
            $greeting['greet_keyword']    = 'user_name';
            $greeting['greet_value']      = $user_name;
            $greeting['greet_option_key'] = 'project_disputes_favour_winner_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_user_dispute_in_favour_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* Dispute resolved in against looser */
        public function project_dispute_refunded_resolved_in_against($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to       = !empty($user_email) ? $user_email : '';
            $user_name 	    = !empty($user_name) ? $user_name: '';
            $admin_name     = !empty($admin_name) ? $admin_name: '';
            $dispute_link   = !empty($dispute_link) ? $dispute_link: '';

            $subject_default    = esc_html__('Project dispute not in favour', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('Oho! {{admin_name}} did not approve the dispute refund request in your favor.<br/>Please click on the button below to view the dispute details.<br/>{{dispute_link}}', 'workreap'), //default email content
            array(
                'a'       => array(
                'href'  => array(),
                'title' => array()
                ),
                'br'      => array(),
                'em'      => array(),
                'strong'  => array(),
            ));

            $subject            = !empty( $workreap_settings['email_project_disputes_against_looser_subject'] ) ? $workreap_settings['email_project_disputes_against_looser_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $workreap_settings['project_disputes_against_looser_content'] ) ? $workreap_settings['project_disputes_against_looser_content'] : $contact_default; //getting content
            $dispute_link       = $this->process_email_links($dispute_link, esc_html__('Dispute link', 'workreap')); //project/post link

            $email_content = str_replace("{{user_name}}", $user_name, $email_content);
            $email_content = str_replace("{{admin_name}}", $admin_name, $email_content);
            $email_content = str_replace("{{dispute_link}}", $dispute_link, $email_content); 
            
            /* data for greeting */
            $greeting['greet_keyword']    = 'user_name';
            $greeting['greet_value']      = $user_name;
            $greeting['greet_option_key'] = 'project_disputes_against_looser_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_user_dispute_in_against_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
            
        }

        /* Project dispute refund decline by freelancer */
        public function project_dispute_refund_decline_by_freelancer($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to       = !empty($employer_email) ? $employer_email : '';
            $employer_name     = !empty($employer_name) ? $employer_name: '';
            $freelancer_name    = !empty($freelancer_name) ? $freelancer_name: '';
            $dispute_link   = !empty($dispute_link) ? $dispute_link: '';

            $subject_default    = esc_html__('Project refund decline', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('Oho! A dispute has been declined by {{freelancer_name}}<br/>Please click on the button below to view the dispute details.<br/>{{dispute_link}}', 'workreap'), //default email content
            array(
                'a'       => array(
                'href'  => array(),
                'title' => array()
                ),
                'br'      => array(),
                'em'      => array(),
                'strong'  => array(),
            ));

            $subject            = !empty( $workreap_settings['refund_project_request_decline_employer_email_subject'] ) ? $workreap_settings['refund_project_request_decline_employer_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $workreap_settings['refund_project_request_decline_employer_mail_content'] ) ? $workreap_settings['refund_project_request_decline_employer_mail_content'] : $contact_default; //getting content
            $dispute_link       = $this->process_email_links($dispute_link, esc_html__('Dispute link', 'workreap')); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{dispute_link}}", $dispute_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'employer_name';
            $greeting['greet_value']      = $employer_name;
            $greeting['greet_option_key'] = 'refund_project_request_decline_employer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_project_refund_request_decline_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Project dispute refund approved by freelancer */
        public function project_dispute_refund_approve_by_freelancer($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to       = !empty($employer_email) ? $employer_email : '';
            $employer_name     = !empty($employer_name) ? $employer_name: '';
            $freelancer_name    = !empty($freelancer_name) ? $freelancer_name: '';
            $dispute_link   = !empty($dispute_link) ? $dispute_link: '';

            $subject_default    = esc_html__('Project refund approved', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('Woohoo! {{freelancer_name}} approved dispute refund request in your favour.<br/>Please click on the button below to view the dispute details.<br/>{{dispute_link}}', 'workreap'), //default email content
            array(
                'a'       => array(
                'href'  => array(),
                'title' => array()
                ),
                'br'      => array(),
                'em'      => array(),
                'strong'  => array(),
            ));

            $subject            = !empty( $workreap_settings['refund_project_request_approved_employer_email_subject'] ) ? $workreap_settings['refund_project_request_approved_employer_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $workreap_settings['refund_project_request_approved_employer_mail_content'] ) ? $workreap_settings['refund_project_request_approved_employer_mail_content'] : $contact_default; //getting content
            $dispute_link       = $this->process_email_links($dispute_link, esc_html__('Dispute link', 'workreap')); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{dispute_link}}", $dispute_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'employer_name';
            $greeting['greet_value']      = $employer_name;
            $greeting['greet_option_key'] = 'refund_project_request_approved_employer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_project_refund_request_approved_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Project dispute refund approved by freelancer */
        public function project_dispute_admin_commnet_to_freelancer_employer($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to           = !empty($user_email) ? $user_email : '';
            $user_name          = !empty($user_name) ? $user_name: '';
            $admin_name         = !empty($admin_name) ? $admin_name: '';
            $dispute_link       = !empty($dispute_link) ? $dispute_link: '';
            $dispute_comment    = !empty($dispute_comment) ? $dispute_comment: '';

            $subject_default    = esc_html__('Admin comment on dispute', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('You have received a new dispute comment from {{admin_name}}<br/>Please click on the button below to view the dispute comment.<br/>{{dispute_link}}', 'workreap'), //default email content
            array(
                'a'       => array(
                'href'  => array(),
                'title' => array()
                ),
                'br'      => array(),
                'em'      => array(),
                'strong'  => array(),
            ));

            $subject            = !empty( $workreap_settings['email_project_dispute_admin_comment_subject'] ) ? $workreap_settings['email_project_dispute_admin_comment_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $workreap_settings['project_dispute_admin_comment_content'] ) ? $workreap_settings['project_dispute_admin_comment_content'] : $contact_default; //getting content
            $dispute_link       = $this->process_email_links($dispute_link, esc_html__('Dispute link', 'workreap')); //project/post link

            $email_content = str_replace("{{user_name}}", $user_name, $email_content);
            $email_content = str_replace("{{admin_name}}", $admin_name, $email_content);
            $email_content = str_replace("{{dispute_link}}", $dispute_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'user_name';
            $greeting['greet_value']      = $user_name;
            $greeting['greet_option_key'] = 'project_dispute_admin_comment_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_admin_comment_on_dispute_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Project dispute refund approved by freelancer */
        public function project_dispute_user_commnet_to_eachother($params = ''){
            global $workreap_settings;
            extract($params);

            $email_to           = !empty($receiver_email) ? $receiver_email : '';
            $sender_name        = !empty($sender_name) ? $sender_name: '';
            $receiver_name      = !empty($receiver_name) ? $receiver_name: '';
            $dispute_link       = !empty($dispute_link) ? $dispute_link: '';
            $dispute_comment    = !empty($dispute_comment) ? $dispute_comment: '';

            $subject_default    = esc_html__('User comment on dispute', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('You have received a new dispute comment from {{sender_name}}<br/>Please click on the button below to view the dispute comment.<br/>{{dispute_link}}', 'workreap'), //default email content
            array(
                'a'       => array(
                'href'  => array(),
                'title' => array()
                ),
                'br'      => array(),
                'em'      => array(),
                'strong'  => array(),
            ));

            $subject            = !empty( $workreap_settings['email_project_dispute_user_comment_subject'] ) ? $workreap_settings['email_project_dispute_user_comment_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $workreap_settings['project_dispute_user_comment_content'] ) ? $workreap_settings['project_dispute_user_comment_content'] : $contact_default; //getting content
            $dispute_link       = $this->process_email_links($dispute_link, esc_html__('Dispute link', 'workreap')); //project/post link

            $email_content = str_replace("{{sender_name}}", $sender_name, $email_content);
            $email_content = str_replace("{{receiver_name}}", $receiver_name, $email_content);
            $email_content = str_replace("{{dispute_link}}", $dispute_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'receiver_name';
            $greeting['greet_value']      = $receiver_name;
            $greeting['greet_option_key'] = 'project_dispute_user_comment_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_admin_comment_on_dispute_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }



    }
}
