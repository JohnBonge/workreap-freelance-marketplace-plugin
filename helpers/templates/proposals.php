<?php
/**
 *
 * Class 'WorkreapProposals' defines task status
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if (!class_exists('WorkreapProposals')) {
    class WorkreapProposals extends Workreap_Email_helper
  {
    public function __construct()
    {
      //do something
    }

    /**
     * Submit a proposal employer email
     */
    public function submit_proposal_employer_email($params = '') {
        global  $workreap_settings;
        extract($params);

        $email_to           = !empty($employer_email) ? $employer_email : '';
        $employer_name 	    = !empty($employer_name) ? $employer_name: '';
        $freelancer_name 	    = !empty($freelancer_name) ? $freelancer_name : '';
        $project_title 	    = !empty($project_title) ? $project_title : '';
        $proposal_link 	    = !empty($proposal_link) ? $proposal_link : '';

        $subject_default    = esc_html__('Submit Proposal', 'workreap'); //default email subject
        $contact_default    = wp_kses(__('{{freelancer_name}} submit a new proposal on {{project_title}} Please click on the button below to view the proposal. {{proposal_link}}', 'workreap'), //default email content
            array(
            'a'       => array(
            'href'  => array(),
            'title' => array()
            ),
            'br'      => array(),
            'em'      => array(),
            'strong'  => array(),
            ));

            $subject          = !empty( $workreap_settings['submit_proposal_employer_email_subject'] ) ? $workreap_settings['submit_proposal_employer_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty( $workreap_settings['submit_proposal_employer_mail_content'] ) ? $workreap_settings['submit_proposal_employer_mail_content'] : $contact_default; //getting content
            $proposal_link     = $this->process_email_links($proposal_link, esc_html__('Proposal link', 'workreap')); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{proposal_link}}", $proposal_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'employer_name';
            $greeting['greet_value']      = $employer_name;
            $greeting['greet_option_key'] = 'submit_proposal_employer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_employer_proposal_submission_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
    }

    /**
     * Submited proposal admin email
     */
    public function submited_proposal_admin_email($params = '') {
        global  $workreap_settings;
        extract($params);

        $email_to         = !empty( $workreap_settings['submited_proposal_admin_email'] ) ? $workreap_settings['submited_proposal_admin_email'] : get_option('admin_email', 'info@example.com');
        $employer_name 	    = !empty($employer_name) ? $employer_name: '';
        $freelancer_name 	    = !empty($freelancer_name) ? $freelancer_name : '';
        $project_title 	  = !empty($project_title) ? $project_title : '';
        $proposal_link     = !empty($proposal_link) ? $proposal_link : '';

        $subject_default    = esc_html__('Submited Proposal', 'workreap'); //default email subject
        $contact_default    = wp_kses(__('{{freelancer_name}} submit a new proposal on {{project_title}} Please click on the button below to view the project. {{proposal_link}}', 'workreap'), //default email content
            array(
            'a'       => array(
            'href'  => array(),
            'title' => array()
            ),
            'br'      => array(),
            'em'      => array(),
            'strong'  => array(),
            ));

            $subject          = !empty( $workreap_settings['submited_proposal_admin_email_subject'] ) ? $workreap_settings['submited_proposal_admin_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty( $workreap_settings['submited_proposal_admin_mail_content'] ) ? $workreap_settings['submited_proposal_admin_mail_content'] : $contact_default; //getting content
            $proposal_link     = $this->process_email_links($proposal_link, esc_html__('Proposal link', 'workreap')); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{proposal_link}}", $proposal_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = '';
            $greeting['greet_value']      = '';
            $greeting['greet_option_key'] = '';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_admin_proposal_submission_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        
    }

    /**
     * Decline proposal freelancer email
     */
    public function decline_proposal_freelancer_email($params = '') {
        global  $workreap_settings;
        extract($params);

        $email_to           = !empty($freelancer_email) ? $freelancer_email: '';
        $employer_name 	    = !empty($employer_name) ? $employer_name: '';
        $freelancer_name 	    = !empty($freelancer_name) ? $freelancer_name : '';
        $project_title 	    = !empty($project_title) ? $project_title : '';
        $proposal_link 	    = !empty($proposal_link) ? $proposal_link : '';

        $subject_default    = esc_html__('Proposal decline', 'workreap'); //default email subject
        $contact_default    = wp_kses(__('Oho! your proposal on {{project_title}} has been rejected by {{employer_name}}<br/>Please click on the button below to view the rejection reason.<br/>{{proposal_link}}', 'workreap'), //default email content
            array(
            'a'       => array(
            'href'  => array(),
            'title' => array()
            ),
            'br'      => array(),
            'em'      => array(),
            'strong'  => array(),
            ));

            $subject          = !empty( $workreap_settings['proposal_decline_email_subject'] ) ? $workreap_settings['proposal_decline_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty( $workreap_settings['proposal_decline_freelancer_mail_content'] ) ? $workreap_settings['proposal_decline_freelancer_mail_content'] : $contact_default; //getting content
            $proposal_link     = $this->process_email_links($proposal_link, esc_html__('Proposal link', 'workreap')); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{proposal_link}}", $proposal_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'proposal_decline_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_proposal_decline_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

    }

    /**
     * Hired proposal freelancer email
     */
    public function hired_proposal_freelancer_email($params = '') {
        global  $workreap_settings;
        extract($params);

        $email_to           = !empty($freelancer_email) ? $freelancer_email: '';
        $employer_name 	    = !empty($employer_name) ? $employer_name: '';
        $freelancer_name 	    = !empty($freelancer_name) ? $freelancer_name : '';
        $project_title 	    = !empty($project_title) ? $project_title : '';
        $project_link 	    = !empty($project_link) ? $project_link : '';

        $subject_default    = esc_html__('Hired proposal', 'workreap'); //default email subject
        $contact_default    = wp_kses(__('Woohoo! {{employer_name}} hired you for {{project_title}} project <br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'), //default email content
            array(
            'a'       => array(
            'href'  => array(),
            'title' => array()
            ),
            'br'      => array(),
            'em'      => array(),
            'strong'  => array(),
            ));

            $subject          = !empty( $workreap_settings['proposal_hired_email_subject'] ) ? $workreap_settings['proposal_hired_email_subject'] : $subject_default; //getting subject
            $email_content    = !empty( $workreap_settings['proposal_hired_freelancer_mail_content'] ) ? $workreap_settings['proposal_hired_freelancer_mail_content'] : $contact_default; //getting content
            $project_link     = $this->process_email_links($project_link, $project_title); //project/post link

            $email_content = str_replace("{{employer_name}}", $employer_name, $email_content);
            $email_content = str_replace("{{freelancer_name}}", $freelancer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'freelancer_name';
            $greeting['greet_value']      = $freelancer_name;
            $greeting['greet_option_key'] = 'proposal_hired_freelancer_email_greeting';

            $body   = $this->workreap_email_body($email_content, $greeting);
            $body   = apply_filters('workreap_freelancer_proposal_hired_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

    }

  }
}