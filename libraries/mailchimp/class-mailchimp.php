<?php
if (!class_exists('Workreap_MailChimp')) {

    class Workreap_MailChimp {

        function __construct() {
            add_action('wp_ajax_nopriv_workreap_subscribe_mailchimp', array(&$this, 'workreap_subscribe_mailchimp'));
            add_action('wp_ajax_workreap_subscribe_mailchimp', array(&$this, 'workreap_subscribe_mailchimp'));
            add_action('workreap_mailchimp_array', array(&$this,'workreap_mailchimp_array'));
            add_action('wp_ajax_workreap_mailchimp_array', array(&$this,'workreap_mailchimp_array'));
        }

        /**
         * Update mailchimp array
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return 
         */
        public function workreap_mailchimp_array(){
            //security check
            $do_check = check_ajax_referer('ajax_nonce', 'security', false);
            if ( $do_check == false ) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
                wp_send_json( $json );
            }
            
            $json		= array();
            $transName 	= 'latest-mailchimp-list';
            $mailChip = get_transient( $transName );
            if( empty($mailChip) ){
                $list_array	= array();
                if( function_exists('workreap_mailchimp_list') ) {
                    $list_array	= workreap_mailchimp_list();
                    set_transient( $transName, $list_array, 60 * 60 * 24 );
                }
            }
            
            $json['type']	= 'success';	
            $json['message']	= esc_html__('MailChimp is updated','workreap' );
            wp_send_json($json);
        }

        public function workreap_mailchimp_form($class = '') {
            global $workreap_settings;
            $counter = 0;
            $counter++;
            ?>
            <form class="wr-formtheme wr-formnewslettervtwo">
                <fieldset>
                    <div class="form-group wr-email">
                        <i class="wr-icon-mail"></i>
                        <input type="email" name="email" class="form-control" placeholder="<?php esc_attr_e('Add your email', 'workreap'); ?>">
                    </div>
                    <button type="submit" class="wr-btn subscribe_me" data-counter="<?php echo intval($counter); ?>"><?php esc_html_e('Signup now', 'workreap'); ?><i class="wr-icon-send"></i></button>
                </fieldset>
            </form>
            <?php
        }

        /**
         * @get Mail chimp list
         *
         */
        public function workreap_mailchimp_list($apikey) {
			if ( $apikey <> '' && $apikey !== 'Add your key here' ) {
				$apikey	= $apikey;
			} else{
				return '';
			}
			
            $MailChimp = new Workreap_OATH_MailChimp($apikey);
            $mailchimp_list = $MailChimp->workreap_call('lists/list');
            return $mailchimp_list;
        }

        /**
         * @get Mail chimp list
         *
         */
        public function workreap_subscribe_mailchimp() {
            global $counter;
			
			//security check
			$do_check = check_ajax_referer('ajax_nonce', 'security', false);
			if ( $do_check == false ) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
				wp_send_json( $json );
			}
			
            $mailchimp_key  = !empty($workreap_settings['mailchimp_key']) ? $workreap_settings['mailchimp_key'] : '';
            $mailchimp_list = !empty($workreap_settings['mailchimp_list']) ? $workreap_settings['mailchimp_list'] : '';
            $json           = array();

            if (empty($_POST['email'])) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Email address is required.', 'workreap');
                echo json_encode($json);
                die();
            }

            if (isset($_POST['email']) && !empty($_POST['email']) && $mailchimp_key != '') {
                if (!empty( $mailchimp_key )) {
                    $MailChimp = new Workreap_OATH_MailChimp($mailchimp_key);
                } else{
					$json['type'] = 'error';
                	$json['message'] = esc_html__('Some error occur,please try again later.', 'workreap');
					echo json_encode($json);
					die();
				}

                $email = $_POST['email'];
                if (isset($_POST['fname']) && !empty($_POST['fname'])) {
                    $fname = $_POST['fname'];
                } else {
                    $fname = '';
                }

                if (isset($_POST['lname']) && !empty($_POST['lname'])) {
                    $lname = $_POST['lname'];
                } else {
                    $lname = '';
                }

                if (trim($mailchimp_list) == '') {
                    $json['type']       = 'error';
                    $json['message']    = esc_html__('No list selected yet! please contact administrator', 'workreap');
                    echo json_encode($json);
                    die;
                }
				
                $result = $MailChimp->workreap_call('lists/subscribe', array(
                    'id' 			=> $mailchimp_list,
                    'email' 		=> array('email' => $email),
                    'merge_vars' 	=> array('FNAME' => $fname, 'LNAME' => $lname),
                    'double_optin'	=> false,
                    'update_existing' 	=> false,
                    'replace_interests' => false,
                    'send_welcome' 		=> true,
                ));
				
                if ($result <> '') {
                    if (isset($result['status']) and $result['status'] == 'error') {
                        $json['type'] = 'error';
                        $json['message'] = $result['error'];
                    } else {
                        $json['type'] = 'success';
                        $json['message'] = esc_html__('Successfully subscribed', 'workreap');
                    }
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occur,please try again later.', 'workreap');
            }
			
            echo json_encode($json);
            die();
        }

    }

    new Workreap_MailChimp();
}