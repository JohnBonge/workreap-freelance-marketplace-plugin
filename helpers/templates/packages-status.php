<?php
/**
 *
 * Class 'WorkreapPackagesStatuses' defines freelancer package status
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

if (!class_exists('WorkreapPackagesStatuses')) {
  class WorkreapPackagesStatuses extends Workreap_Email_helper
  {
    public function __construct()
    {
      //do something
    }

    /* Package Purchase */
    public function package_purchase_freelancer_email($params = '')
    {
      global $workreap_settings;
      extract($params);

      $email_to       = !empty($freelancer_email) ? $freelancer_email : '';
      $freelancer_name_   = !empty($freelancer_name) ? $freelancer_name : '';
      $order_id_      = !empty($order_id) ? $order_id : '';
      $order_amount_  = !empty($order_amount) ? $order_amount : '';
      $package_name_  = !empty($package_name) ? $package_name : '';

      $subject_default 	        = esc_html__('Thank you for purchasing the package.', 'workreap'); //default email subject
      $contact_default 	        = wp_kses(__('Thank you for purchasing the package “{{package_name}}” <br/> You can now post a task and get orders.', 'workreap'), //default email content
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

      $subject		    = !empty( $workreap_settings['packages_freelancer_email_subject'] ) ? $workreap_settings['packages_freelancer_email_subject'] : $subject_default; //getting subject
      $email_content  = !empty( $workreap_settings['package_freelancer_purchase_mail_content'] ) ? $workreap_settings['package_freelancer_purchase_mail_content'] : $contact_default; //getting content

      $email_content = str_replace("{{freelancer_name}}", $freelancer_name_, $email_content);
      $email_content = str_replace("{{order_id}}", $order_id_, $email_content);
      $email_content = str_replace("{{order_amount}}", $order_amount_, $email_content);
      $email_content = str_replace("{{package_name}}", $package_name_, $email_content);

      /* data for greeting */
      $greeting['greet_keyword']      = 'freelancer_name';
      $greeting['greet_value']        = $freelancer_name_;
      $greeting['greet_option_key']   = 'packages_freelancer_email_greeting';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_freelancer_package_email_content', $body);

      wp_mail($email_to, $subject, $body); //send Email
    }

  }

  new WorkreapPackagesStatuses();
}
