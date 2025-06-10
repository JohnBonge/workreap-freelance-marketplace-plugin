<?php
/**
 * This class used to activate envato license
 *
 *
 * @package    workreap
 * @subpackage workreap/admin
 * @author     Amentotech <info@amentotech.com>
 */
if ( ! class_exists( 'Workreap_Envato_Purchase_Verify_User' ) ) {
	
	class Workreap_Envato_Purchase_Verify_User {
		
		public function __construct() {
			
			$this->workreap_init_actions();
			add_action( 'wp_ajax_workreap_verifypurchase', array(&$this, 'workreap_verifypurchase') );
			add_action( 'wp_ajax_workreap_remove_license', array(&$this, 'workreap_remove_license') );
			add_action( 'admin_init', array(&$this, 'workreap_license_deactivated_menu'));
			add_action( 'admin_menu', array($this, 'workreap_license_activation_menu_page') );
		}

		public function workreap_license_deactivated_menu(){
			$options = get_option( 'workreap_verify_settings' );
			$verified	= !empty($options['verified']) ? $options['verified'] : '';

			if(empty($verified) && empty($this->isLocalhost()) && ($_SERVER["SERVER_NAME"] != 'amentotech.com')){
				remove_menu_page( 'edit.php?post_type=freelancers' );
				remove_menu_page( 'edit.php?post_type=faq' );
				remove_menu_page( 'edit.php?post_type=disputes' );
				remove_menu_page( 'edit.php?post_type=notification' );
				remove_menu_page( 'edit.php?post_type=employers' );
				remove_menu_page( 'tools.php?page=fw-backups-demo-content' );
				remove_menu_page( 'edit.php?post_type=withdraw' );		
			}
		}

		/**
		 * Register a custom menu page.
		 */
		public function workreap_license_activation_menu_page() {
			$options 	= get_option( 'workreap_verify_settings' );
			$verified	= !empty($options['verified']) ? $options['verified'] : '';
			if(empty($verified) && empty($this->isLocalhost()) &&  ($_SERVER["SERVER_NAME"] != 'amentotech.com')){
				add_menu_page(
					esc_html__( 'Workreap purchase code verification', 'workreap' ),
					esc_html__( 'WR Core', 'workreap' ),
					'manage_options', 
					'workreap_code_settings',
					array( $this, 'workreap_verify_purchase_section_callback' ),
					WORKREAP_DIRECTORY_URI.'/public/images/wp-icon-workreap.png',
					8
				);
			}
		}
		
		/**
		 * Local server check
		*/
		public function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
			if($_SERVER["SERVER_NAME"] === 'houzillo.com' || $_SERVER["SERVER_NAME"] === 'wp-guppy.com' || $_SERVER["SERVER_NAME"] === 'demos.codingeasel.com' ){
				return true;
			} else {
				return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
			}
			
		}

		/**
		 * Remove license
		 */
		public function workreap_remove_license(){
			$json = array();
			//security check
			if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
				$json['type'] 			= 'error';
				$json['message'] 		= esc_html__('Oops!', 'workreap');
				$json['message_desc'] 	= esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
				wp_send_json( $json );
			}

			$purchase_code	= !empty($_POST['purchase_code']) ? sanitize_text_field( $_POST['purchase_code'] ) : '';
			$domain			= get_home_url();
			
			$url = 'https://wp-guppy.com/verification/wp-json/atepv/v2/epvRemoveLicense';			
			$args = array(
				'timeout'		=> 45,
				'redirection'	=> 5,
				'httpversion'	=> '1.0',
				'blocking'		=> true,
				'headers'     => array(),
				'body'		=> array(
					'purchase_code'	=> $purchase_code, 
					'domain'	=> $domain 
				),
				'cookies'	=> array()
			);

			$response = wp_remote_post( $url, $args );
			// error check
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$json['type'] 	 	= 'error';
				$json['title']		= esc_html__('Failed!', 'workreap');
				$json['message']	= $error_message;
				wp_send_json($json);
			} else {
				$response = json_decode(wp_remote_retrieve_body( $response ));
				
				$response->redirect = admin_url( 'admin.php?page=workreap_code_settings' );

				if(!empty($response->type) && $response->type == 'success'){
					delete_option('workreap_verify_settings');
				}
				wp_send_json($response);
			}
		}

		/**
		 * Verify item purchase code
		 */
		public function workreap_verifypurchase(){
			$json = array();
			//security check
			if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
				$json['type'] 			= 'error';
				$json['message'] 		= esc_html__('Oops!', 'workreap');
				$json['message_desc'] 	= esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
				wp_send_json( $json );
			}
			$purchase_code	= !empty($_POST['purchase_code']) ? sanitize_text_field( $_POST['purchase_code'] ) : '';
			$domain			= get_home_url();
			
			$url = 'https://wp-guppy.com/verification/wp-json/atepv/v2/verifypurchase';
			$args = array(
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'body'        => array( 'purchase_code' => $purchase_code, 'domain' => $domain ),
				'cookies'     => array()
			);

			$response 	= wp_remote_post( $url, $args );
			$options 	= get_option( 'workreap_verify_settings' );
			$options['purchase_code']	= $purchase_code;

			// error check
			if ( is_wp_error( $response ) ) {
				update_option('workreap_verify_settings', $options);
				$error_message = $response->get_error_message();
				$json['type'] 	 	= 'error';
				$json['title']		= esc_html__('Failed!', 'workreap');
				$json['message']	= $error_message;
				wp_send_json($json);
			} else {
				$response = json_decode(wp_remote_retrieve_body( $response ));			
				$options = get_option( 'workreap_verify_settings' );
				$options['purchase_code']	= $purchase_code;

				if(!empty($response->type) && $response->type == 'success'){
					$options['verified']				= true;
					$response->redirect_url				= admin_url( 'admin.php?page=workreap_verify_purchase' );
				}
				update_option('workreap_verify_settings', $options);
				wp_send_json($response);
			}
		}

		/**
		 * Init all the actions of admin pages
		 */
		public function workreap_init_actions() {
			add_action( 'admin_menu', array( $this, 'workreap_purchase_verify_menu' ));
			add_action( 'admin_init', array( $this, 'workreap_purchase_verify_init' ) );
		}

		/**
		 * Revoke license
		 */
		public function workreap_purchase_verify_menu() {
			$options 	= get_option( 'workreap_verify_settings' );
			$verified	= !empty($options['verified']) ? $options['verified'] : '';
			if(!empty($verified) && empty($this->isLocalhost()) && ($_SERVER["SERVER_NAME"] != 'amentotech.com' || $_SERVER["SERVER_NAME"] != 'houzillo.com')){
				add_submenu_page(
					'edit.php?post_type=freelancers',
					esc_html__( 'Revoke license', 'workreap' ),
					esc_html__( 'Revoke license', 'workreap' ),
					'manage_options',
					'workreap_verify_purchase',
					array( $this, 'workreap_verify_purchase_section_callback' )
				);
			}
		}
		
		/**
		 * Purchase code verify menu
		*/
		public function workreap_purchase_verify_init(  ) {
			
			register_setting(
				'workreap_verify_settings',
				'workreap_verify_settings'
			);	

			add_settings_section(
				'user_purchase_code_verify',
				esc_html__( 'Workreap purchase code verification', 'workreap' ),
				array( $this, 'workreap_api_text' ),
				'workreap_verify_section'
			);
		
			add_settings_field(
				'purchase_code',
				esc_html__( 'Workreap purchase code', 'workreap' ),
				array( $this, 'workreap_purchase_code_field' ),
				'workreap_verify_section',
				'user_purchase_code_verify'
			);
		}

		/**
		 * Get purchase code text
		*/
		public function workreap_api_text() {
			$options = get_option( 'workreap_verify_settings' );
			$verified	= !empty($options['verified']) ? $options['verified'] : '';

			if(empty($verified)){		
				$message	= sprintf( __( '<p>To get all the workreap functionality, please verify your valid license copy. <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">How, i can find the purchase code</a>.</p>', 'workreap' ) );
			} else {
				$message	= sprintf( __( '<p>One license can only be used for your one live site, you can unlink this license to use our product for another site. You can check the license detail <a href="https://themeforest.net/licenses/standard">here</a> </p>', 'workreap' ) );
			}
			echo wp_kses_post( $message );
		}

		/**
		 * Purchase code text field
		*/
		public function workreap_purchase_code_field() {
			$options = get_option( 'workreap_verify_settings' );
			$purchase_code	= !empty($options['purchase_code']) ? $options['purchase_code'] : '';
			printf(
				'<input type="text" name="%s" id="workreap_purchase_code" value="%s" title="%s" />',
				esc_attr( 'workreap_verify_settings[purchase_code]' ),
				esc_attr( $purchase_code ),
				esc_html__( 'Enter purchase code', 'workreap' )
			);
		}

		/**
		 * Purchase code settings form
		 * 
		*/
		public function workreap_verify_purchase_section_callback() {
			$options = get_option( 'workreap_verify_settings' );
			$verified	= !empty($options['verified']) ? $options['verified'] : '';
			?>
			<div id="at-item-verification" class="at-wrapper">
				<div class="at-content">
					<div class="settings-section">
						<form action='options.php' method='post'>    
							<?php 
								do_action('workreap_form_render_before');
								settings_fields( 'workreap_verify_settings' );
								do_settings_sections( 'workreap_verify_section' );
								if(!empty($verified)){?>
										<input type="submit" name="remove" class="button button-primary" id="workreap_remove_license_btn" value="<?php esc_attr_e( 'Activate license', 'workreap' ); ?>" />
								<?php } else {?>
										<input type="submit" name="submit" class="button button-primary" id="workreap_verify_btn" value="<?php esc_attr_e( 'Activate license', 'workreap' ); ?>" />
									<?php
								}
								
								do_action('workreap_form_render_after');
							?>
						</form>
					</div>
				</div> 
			</div>
			<?php
		}

	}
	new Workreap_Envato_Purchase_Verify_User();
}