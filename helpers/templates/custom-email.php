<?php
/**
 *
 * Class 'WorkreapCustomEmail' defines user registration
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

if (!class_exists('WorkreapCustomEmail')) {

  class WorkreapCustomEmail extends Workreap_Email_helper{
    public function __construct(){
      //do something
    }

    /* Custom Email send to user */
    public function send($email_to,$subject,$email_content,$params=array()){
      
      /* data for greeting */
      $greeting                       = array();
      $greeting['greet_keyword']      = !empty($params['name']) ? $params['name'] : '';
      $greeting['greet_value']        = '';
      $greeting['greet_option_key']   = '';

      $body = $this->workreap_email_body($email_content, $greeting);
      $body  = apply_filters('workreap_user_registration_email_content', $body);
      wp_mail($email_to, $subject, $body); //send Email
    }
  }
  new WorkreapCustomEmail();
}
