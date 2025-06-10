<?php
/**
 *
 * Class 'Workreap_Email_helper' defines email functions
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!class_exists('Workreap_Email_helper')) {

    class Workreap_Email_helper{

        public function __construct(){
            add_filter('wp_mail_content_type', array(&$this, 'workreap_set_content_type'));
            add_filter('wp_mail_from',  array(&$this,'workreap_sender_email') );
            add_filter('wp_mail_from_name', array(&$this,'workreap_sender_name') );
        }

        /**
         * Email Content type
         *
         * @since    1.0.0
         */
        public function workreap_set_content_type(){
            return "text/html";
        }
		
        /**
         * Sender name
         *
         * @since    1.0.0
         */
        public function workreap_sender_email($email_address){
            global $workreap_settings;
            $email_sender_email 	= !empty($workreap_settings['email_sender_email']) ? $workreap_settings['email_sender_email'] : '';
            $email_sender_email 	= !empty($email_sender_email) ? $email_sender_email : $email_address;
            return $email_sender_email;
        }

         /**
         * Sender Email address
         *
         * @since    1.0.0
         */
        public function workreap_sender_name($name){
            global $workreap_settings;
            $default_sender_name 	= !empty($workreap_settings['email_sender_name']) ? $workreap_settings['email_sender_name'] : '';
            $sender_name 			= !empty($default_sender_name) ? $default_sender_name : $name;
            return $sender_name;
        }
        
        /**
         * Get Email Header
         * Return email header html
         * @since    1.0.0
         */
        public function prepare_email_headers(){
			global $workreap_settings;
            $logo = !empty($workreap_settings['email_logo']['url']) ? workreap_add_http_protcol($workreap_settings['email_logo']['url']) : workreap_add_http_protcol(WORKREAP_DIRECTORY_URI . 'public/images/logo.png');
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            ob_start();
            ?>
            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #f4f4f4; border-radius: 20px;">
                <tr>
                    <td align="center">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" width="600" style="background: #ffffff;margin-top: 100px;border-radius: 20px 20px 0 0;">
                            <tbody>
                                <tr>
                                    <td style="direction:ltr;font-size:0px;padding:50px 55px;text-align:center;">
                                        <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="width:130px;">
                                                                        <img height="auto" src="<?php echo esc_url($logo);?>" alt="<?php echo esc_attr($blogname);?>" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;" width="130">
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php
            return ob_get_clean();
        }

        /**
         * Get Email Footer
         *
         * Return email footer html
         *
         * @since    1.0.0
         */
        public function prepare_email_footers($params = '')
        {
            global $workreap_settings;
            $email_copyrights = !empty($workreap_settings['email_copyrights']) ? $workreap_settings['email_copyrights'] : esc_html__('Copyright', 'workreap') . '&nbsp;&copy;&nbsp;' . date('Y') . esc_html__(' | All Rights Reserved', 'workreap');
            $email_footer_color = !empty($workreap_settings['email_footer_color']) ? $workreap_settings['email_footer_color'] : '#353648';
            $email_footer_color_text = !empty($workreap_settings['email_footer_color_text']) ? $workreap_settings['email_footer_color_text'] : '#FFF';

            ob_start();
            ?>
                <tr>
                    <td align="center">
                        <table align="center" role="presentation" border="0" cellspacing="0" cellpadding="0" width="600" style="background: <?php echo esc_attr($email_footer_color);?>; margin-bottom: 100px; border-radius: 0 0 20px 20px;">
                            <tbody>
                                <tr>
                                    <td style="font-size: 0px; padding: 30px 55px 30px; word-break: break-word;">
                                        <div style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; font-size: 13px; font-weight: 500; line-height: 20px; color: <?php echo esc_attr($email_footer_color_text);?>; text-align:center;">
                                            <p style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; text-align:center; width: 100%; font-size: 13px; color: <?php echo esc_attr($email_footer_color_text);?>; font-weight: 500; line-height: 20px; margin: 0;"><?php echo do_shortcode($email_copyrights); ?></p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <?php
            return ob_get_clean();
        }

        /**
         * Process Email Signature
         * @return {data}
         * @since 1.0.0
         *
         */
        public function prepare_email_signature($params = ''){
            global $workreap_settings;
            $blogname 				= wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $default_sender_name 	= !empty($workreap_settings['email_sender_name']) ? $workreap_settings['email_sender_name'] : $blogname;
            $sender_name 			= !empty($params) ? $params : $default_sender_name;

            $default_signature 		= esc_html__('Regards', 'workreap');
            $sender_signature 		= !empty($workreap_settings['email_signature']) ? $workreap_settings['email_signature'] : $default_signature;
			
            ob_start();
            if (!empty($sender_name) || !empty($sender_signature)) {
            
				if (!empty($sender_signature) ) {?>
				<tr>
					<td align="center">
						<table align="center" role="presentation" border="0" cellspacing="0" cellpadding="0" width="600" style="background: #ffffff;">
							<tbody>
								<tr>
									<td style="font-size: 0px; padding: 0 55px 5px; word-break: break-word;">
										<div style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; font-size: 16px; font-weight: 700; line-height: 20px; color: #303041;">
											<p style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; width: 100%; font-size: 15px; color: #303041; font-weight: 700; line-height: 20px; margin: 0;"><?php echo esc_html($sender_signature); ?></p>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<?php }?>
           		<?php if (!empty($sender_name) ) {?>
					<tr>
						<td align="center">
							<table align="center" role="presentation" border="0" cellspacing="0" cellpadding="0" width="600" style="background: #ffffff;">
								<tbody>
									<tr>
										<td style="font-size: 0px; padding: 0 55px 60px; word-break: break-word;">
											<div style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; font-size: 16px; font-weight: 500; line-height: 20px; color: #676767;">
												<p style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; width: 100%; font-size: 15px; color: #676767; font-weight: 500; line-height: 20px; margin: 0;"><?php echo esc_html($sender_name); ?></p>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
       			<?php }
        	}
            return ob_get_clean();
        }

        /**
         * Process Greeting Message
         * @return {data}
         * @since 1.0.0
         *
         */
        public function prepare_greeting_message($params = ''){
            global $workreap_settings;
            extract($params);
            $greet_keyword      = !empty($greet_keyword) ? $greet_keyword : '';
            $greet_option_key   = !empty($greet_option_key) ? $greet_option_key : '';
            $greet_value        = !empty($greet_value) ? $greet_value : '';

            ob_start();
            $greeting_text_default  = wp_kses(__('Hello {{greet_keyword_key}},', 'workreap'), array('a' => array('href' => array(), 'title' => array()), 'br' => array(), 'em' => array(), 'strong' => array(),));
            $greeting_text          = str_replace("{{greet_keyword_key}}", $greet_keyword, $greeting_text_default);

			if( !empty($greet_keyword) && !empty($greet_option_key) && !empty($greet_value) ){
                $greeting_info          = !empty($workreap_settings[$greet_option_key]) ? $workreap_settings[$greet_option_key] : $greeting_text_default;
                $greeting_text          = str_replace("{{" . $greet_keyword . "}}", $greet_value, $greeting_info);
            }
            ?>
            <tr>
                <td align="center">
                    <table align="center" role="presentation" border="0" cellspacing="0" cellpadding="0" width="600" style="background: #ffffff;">
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; padding: 0 0px;">
                                    <table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td style="font-size: 0px; padding: 14px 55px 20px; word-break: break-word;">
                                                    <div style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; font-size: 18px; font-weight: 700; line-height: 24px; color: #303041;">
                                                        <h3 style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; font-weight: 700; font-size: 18px; color: #303041; font-weight: bold; line-height: 24px; margin: 0;"><?php echo esc_html($greeting_text); ?></h3>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }

        /**
         * Process Links
         *
         * @since 1.0.0
         */
        public function process_email_links($params = '', $link_text = '')
        {
            $link_src = !empty($params) ? $params : 'javascript:void(0);';
            return '<a href="' . esc_url($link_src) . '" style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; width: 100%; font-size: 16px; color: #55acee; fontt-weight: 700; line-height: 24px; margin: 0;">' . esc_html($link_text) . '</a>';
        }

        /**
         * Email body
         *
         * @since 1.0.0
         */
        public function workreap_email_body($email_content = '', $greeting = '')
        {
            $body  = '';
            $body .= $this->prepare_email_headers();
            $body .= $this->prepare_greeting_message($greeting);
            $body .= '<tr>';
            $body .= '<td align="center">';
            $body .= '<table align="center" role="presentation" border="0" cellspacing="0" cellpadding="0" width="600" style="background: #ffffff;">';
            $body .= '<tbody>';
            $body .= '<tr>';
            $body .= '<td style="font-size: 0px; padding: 0 55px 20px; word-break: break-word;">';
            $body .= '<div style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; font-size: 16px; font-weight: 500; line-height: 24px; color: #676767;">';
            $body .= '<p style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; width: 100%; font-size: 16px; color: #676767; font-weight: 500; line-height: 24px; margin: 0;">';
            $body .=  wpautop(nl2br($email_content));
            $body .= '</p>';
            $body .= '</div>';
            $body .= '</td>';
            $body .= '</tr>';
            $body .= $this->prepare_email_signature();
            $body .= '</tbody>';
            $body .= '</table>';
            $body .= '</td>';
            $body .= '</tr>';
            $body .= $this->prepare_email_footers();

            return $body;
        }
    }

    new Workreap_Email_helper();
}