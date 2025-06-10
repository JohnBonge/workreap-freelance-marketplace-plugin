<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapIdentityVerification')) {

    class WorkreapIdentityVerification extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send identity verification to admin
		 *
		 * @since 1.0.0
		 */
		public function send_verification_to_admin($params = '') {
			global  $workreap_settings;
			extract($params);
			
			$subject		    = !empty( $workreap_settings['admin_verified_subject'] ) ? $workreap_settings['admin_verified_subject'] : ''; 
			$email_content		= !empty( $workreap_settings['admin_verified_content'] ) ? $workreap_settings['admin_verified_content'] : ''; 
			$email_to       	= !empty( $workreap_settings['admin_email_verify_identity'] ) ? $workreap_settings['admin_email_verify_identity'] : get_option('admin_email', 'info@example.com');
			
			$email_content = str_replace("{{user_name}}", $user_name, $email_content);
			$email_content = str_replace("{{user_link}}", $user_link , $email_content);
			$email_content = str_replace("{{user_email}}", $user_email , $email_content);
			/* data for greeting */
			$greeting						= array();
			$greeting['greet_keyword']      = '';
            $greeting['greet_value']        = '';
            $greeting['greet_option_key']   = '';

			$body = $this->workreap_email_body($email_content, $greeting);

			$body  = apply_filters('workreap_send_verification_to_admin_email_content', $body);

			wp_mail($email_to, $subject, $body); //send Email
		}
		
		/**
		 * @Verification email to Freelancer
		 *
		 * @since 1.0.0
		 */
		public function approve_identity_verification($params = '') {
			global  $workreap_settings;
			extract($params);
			$subject		    = !empty( $workreap_settings['approved_verify_subject'] ) ? $workreap_settings['approved_verify_subject'] : ''; 
			$email_content		= !empty( $workreap_settings['approved_verify_content'] ) ? $workreap_settings['approved_verify_content'] : ''; 
			$email_to       	= !empty( $user_email ) ? $user_email : '';
			
			$email_content = str_replace("{{user_name}}", $user_name, $email_content);
			$email_content = str_replace("{{user_link}}", $user_link , $email_content);
			$email_content = str_replace("{{user_email}}", $user_email , $email_content);
			/* data for greeting */
			$greeting						= array();
			$greeting['greet_keyword']      = 'user_name';
			$greeting['greet_value']        = $user_name;
			$greeting['greet_option_key']   = 'approved_verify_email_greeting';

			$body = $this->workreap_email_body($email_content, $greeting);

			$body  = apply_filters('workreap_approve_identity_verification_email_content', $body);

			wp_mail($email_to, $subject, $body);
		}
		
		/**
		 * @Rejection email to Freelancer
		 *
		 * @since 1.0.0
		 */
		public function reject_identity_verification($params = '') {
			global  $workreap_settings;
			extract($params);
			
			$subject		    = !empty( $workreap_settings['rejected_verify_subject'] ) ? $workreap_settings['rejected_verify_subject'] : ''; 
			$email_content		= !empty( $workreap_settings['rejected_verify_content'] ) ? $workreap_settings['rejected_verify_content'] : ''; 
			$email_to       	= !empty( $user_email ) ? $user_email : '';
			
			$email_content = str_replace("{{user_name}}", $user_name, $email_content);
			$email_content = str_replace("{{user_link}}", $user_link , $email_content);
			$email_content = str_replace("{{user_email}}", $user_email , $email_content);
			$email_content = str_replace("{{admin_message}}", $admin_message , $email_content);
			/* data for greeting */
			$greeting						= array();
			$greeting['greet_keyword']      = 'user_name';
			$greeting['greet_value']        = $user_name;
			$greeting['greet_option_key']   = 'rejected_verify_email_greeting';
			$body = $this->workreap_email_body($email_content, $greeting);

			$body  = apply_filters('workreap_reject_identity_verification_email_content', $body);

			wp_mail($email_to, $subject, $body); //send Email
		}

	}

	new WorkreapIdentityVerification();
}