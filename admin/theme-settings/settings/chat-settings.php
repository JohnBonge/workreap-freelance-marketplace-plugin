<?php
if (!defined('ABSPATH')) exit;
/**
 * Chat Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
if((in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins')))) ){
  Redux::set_section($opt_name, array(
      'title' => esc_html__('Chat Settings', 'workreap'),
      'id' => 'setting_chat_mesages',
      'desc' => '',
      'icon' => 'el el-comment-alt',
      'subsection' => false,
      'fields' => array(
        array(
          'id'       => 'hire_freelancer_chat_switch',
          'type'     => 'switch',
          'title'    => esc_html__( 'Send Message', 'workreap' ),
          'subtitle' => esc_html__( 'Set default message for freelancer on hiring.', 'workreap' ),
          'default'  => true,
        ),
        array(
          'id'      => 'divider_chat_message_to_freelancer',
          'desc'    => wp_kses( __( '{{taskname}} — To display the task name.<br>
                            {{tasklink}} — To display the task link.<br>'
            , 'workreap' ),
            array(
                'a'     => array(
                'href'  => array(),
                'title' => array()
              ),
              'br'      => array(),
              'em'      => array(),
              'strong'  => array(),
            ) ),
          'title'     => esc_html__( 'Message setting variables', 'workreap' ),
          'type'      => 'info',
          'class'     => 'dc-center-content',
          'icon'      => 'el el-info-circle',
          'required' 	=> array('hire_freelancer_chat_switch','equals','1')
        ),
        array(
          'id'        => 'hire_freelancer_chat_mesage',
          'type'      => 'textarea',
          'title'     => esc_html__('Chat Message', 'workreap'),
          'subtitle'  => esc_html__('Default chat message for freelancer on hiring', 'workreap'),
          'required' 	=> array('hire_freelancer_chat_switch','equals','1'),
          'default'   => wp_kses(__('Congratulations! You have hired for the task "{{taskname}}" {{tasklink}}', 'workreap'),
            array(
              'a' => array(
                'href' => array(),
                'title' => array()
              ),
              'br'      => array(),
              'em'      => array(),
              'strong'  => array(),
            )
          ),
        ),
      )
    )
  );
}

