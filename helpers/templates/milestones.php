<?php

/**
 *
 * Class 'WorkreapMilestones' defines task status
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

if (!class_exists('WorkreapMilestones')) {
    class WorkreapMilestones extends Workreap_Email_helper
    {
        public function __construct()
        {
            //do something
        }

        /**
         * Hire milestone freelancer email
         */
        public function hire_milestone_freelancer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);

            $email_to           = !empty($freelancer_email) ? $freelancer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name        = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';
            $milestone_title    = !empty($milestone_title) ? $milestone_title : '';

            $subject_default    = esc_html__('Milestone hired', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('Your milestone {{milestone_title}} of {{project_title}} has been approved <br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'), //default email content
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

            $subject          = !empty($workreap_settings['milestone_hired_email_subject']) ? $workreap_settings['milestone_hired_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty($workreap_settings['milestone_hire_freelancer_mail_content']) ? $workreap_settings['milestone_hire_freelancer_mail_content'] : $contact_default; //getting content
            $project_link     = $this->process_email_links($project_link, $project_title); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{milestone_title}}", $milestone_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'milestone_hire_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_milestone_hired_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /**
         * Milestone approval request
         */
        public function approval_milestone_req_employer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);

            $email_to           = !empty($employer_email) ? $employer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name        = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $milestone_link     = !empty($milestone_link) ? $milestone_link : '';
            $milestone_title    = !empty($milestone_title) ? $milestone_title : '';

            $subject_default    = esc_html__('Milestone approval request', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('A new milestone {{milestone_title}} of {{project_title}} approval received from {{freelancer_name}}<br/>Please click on the button below to view the milestone.<br/>{{milestone_link}}', 'workreap'), //default email content
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

            $subject          = !empty($workreap_settings['req_milestone_approval_employer_email_subject']) ? $workreap_settings['req_milestone_approval_employer_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty($workreap_settings['req_milestone_approval_employer_mail_content']) ? $workreap_settings['req_milestone_approval_employer_mail_content'] : $contact_default; //getting content
            $milestone_link     = $this->process_email_links($milestone_link, $milestone_title); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{milestone_title}}", $milestone_title, $email_content);
            $email_content = str_replace("{{milestone_link}}", $milestone_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'employer_name';
            $greeting['greet_value']      = $employer_name;
            $greeting['greet_option_key'] = 'req_milestone_approval_employer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_milestone_approval_request_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /**
         * Milestone complete
         */
        public function milestone_complete_freelancer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);

            $email_to           = !empty($freelancer_email) ? $freelancer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name         = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title         = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';
            $milestone_title    = !empty($milestone_title) ? $milestone_title : '';

            $subject_default    = esc_html__('Milestone completed', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('You milestone {{milestone_title}} of {{project_title}} marked as completed by {{employer_name}}<br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'), //default email content
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

            $subject          = !empty($workreap_settings['milestone_complete_email_subject']) ? $workreap_settings['milestone_complete_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty($workreap_settings['milestone_complete_freelancer_mail_content']) ? $workreap_settings['milestone_complete_freelancer_mail_content'] : $contact_default; //getting content
            $project_link     = $this->process_email_links($project_link, $project_title); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{milestone_title}}", $milestone_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'milestone_complete_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_milestone_complete_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /**
         * Milestone decline
         */
        public function milestone_decline_freelancer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);

            $email_to           = !empty($freelancer_email) ? $freelancer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name        = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';
            $milestone_title    = !empty($milestone_title) ? $milestone_title : '';

            $subject_default    = esc_html__('Milestone decline', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('Your milestone {{milestone_title}} of {{project_title}} has been declined by {{employer_name}}<br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'), //default email content
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

            $subject          = !empty($workreap_settings['milestone_decline_email_subject']) ? $workreap_settings['milestone_decline_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty($workreap_settings['milestone_decline_freelancer_mail_content']) ? $workreap_settings['milestone_decline_freelancer_mail_content'] : $contact_default; //getting content
            $project_link     = $this->process_email_links($project_link, $project_title); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{milestone_title}}", $milestone_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'milestone_decline_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_milestone_decline_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /**
         * Milestone decline
         */
        public function project_new_milestone_employer_email($params = '')
        {
            global  $workreap_settings;
            extract($params);

            $email_to           = !empty($employer_email) ? $employer_email : '';
            $employer_name         = !empty($employer_name) ? $employer_name : '';
            $freelancer_name        = !empty($freelancer_name) ? $freelancer_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';

            $subject_default    = esc_html__('Project new milestone', 'workreap'); //default email subject
            $contact_default    = wp_kses(__('{{freelancer_name}} add new milestone for the project {{project_title}}<br/>Please click on the button below to view the project history.<br/>{{project_link}}', 'workreap'), //default email content
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

            $subject          = !empty($workreap_settings['new_project_milestone_employer_email_subject']) ? $workreap_settings['new_project_milestone_employer_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty($workreap_settings['new_project_milestone_employer_mail_content']) ? $workreap_settings['new_project_milestone_employer_mail_content'] : $contact_default; //getting content
            $project_link     = $this->process_email_links($project_link, $project_title); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'employer_name';
            $greeting['greet_value']      = $employer_name;
            $greeting['greet_option_key'] = 'new_project_milestone_employer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_new_project_milestone_content', $body);
            wp_mail($email_to, $subject, $body); //send Email


        }

    }
}
