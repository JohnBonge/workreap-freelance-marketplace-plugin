<?php

/**
 * Provide a proposal hooks
 *
 * This file is used to markup the proposal aspects of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/public/partials
 */
if (!class_exists('WorkreapProposalFunctions')) {
    class WorkreapProposalFunctions
    {
        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string    $workreap      The name of the plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct()
        {
            add_action('wp_ajax_workreap_submit_proposal', array($this,'workreap_submit_proposal'));
            add_action('wp_ajax_workreap_decline_proposal', array($this,'workreap_decline_proposal'));
            add_action('wp_ajax_workreap_project_activities', array($this,'workreap_project_activities'));
            add_action('wp_ajax_workreap_update_milestone', array($this,'workreap_update_milestone'));
            add_action('wp_ajax_workreap_add_milestone',  array($this,'workreap_add_milestone'));
            add_action('wp_ajax_workreap_complete_project_order', array($this,'workreap_complete_project_order'));
            add_action('wp_ajax_workreap_submit_project_dispute',  array($this,'workreap_submit_project_dispute'));
            add_action('wp_ajax_workreap_submit_project_dispute_reply',  array($this,'workreap_submit_project_dispute_reply'));
        }

        /**
         * Add milestone
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_add_milestone(){
            global $current_user;
            $json               = array();
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            if( function_exists('workreap_verify_token') ){
                workreap_verify_token($_POST['security']);
            }
            $post_data  = !empty($_POST['data']) ?  $_POST['data'] : array();
            parse_str($post_data,$data);

            workreapAddMilestone($current_user->ID,$data);
        }

        /**
         * Project dispute reply
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_submit_project_dispute_reply() {
            global $current_user,$woocommerce,$workreap_settings;

            $json 		= array();
            $do_check	= check_ajax_referer('ajax_nonce', 'security', false);
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            if ( $do_check == false ) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Oops!', 'workreap');
                $json['message_desc'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
                wp_send_json( $json );
            }

            if ( !class_exists('WooCommerce') ) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Uh!', 'workreap');
                $json['message_desc'] = esc_html__('WooCommerce plugin needs to be installed.', 'workreap');
                wp_send_json( $json );
            }

            $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
            parse_str($post_data,$data);
            $fields	= array(
                'dispute_comment'	=> esc_html__('Please add reply comment','workreap'),
            );

            foreach( $fields as $key => $item ){

                if( empty( $data[$key] ) ){
                    $json['type'] 	 = "error";
                    $json['message'] = esc_html__('Oops!', 'workreap');
                    $json['message_desc'] = $item;
                    wp_send_json( $json );
                }
            }

            workreapProjectDisputeComments($current_user->ID,$data);

        }

        /**
         * Create dispute
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */

        public function workreap_submit_project_dispute() {
            global $current_user;
            $json = array();
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            $do_check = check_ajax_referer('ajax_nonce', 'security', false);

            if ( $do_check == false ) {
                $json['type']           = 'error';
                $json['message']        = 'Oops!';
                $json['message_desc']   = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
                wp_send_json( $json );
            }

            $post_data  = !empty($_POST['data']) ?  $_POST['data'] : '';
            parse_str($post_data,$data);
            $get_user_type	= apply_filters('workreap_get_user_type', $current_user->ID );
            $fields	= array(
                'dispute_issue'     => esc_html__('Please select the dispute reason','workreap'),
                'dispute-details' 	=> esc_html__('Please add dispute details','workreap'),
                'dispute_terms' 	=> esc_html__('You must select terms and conditions','workreap'),
            );
            foreach( $fields as $key => $item ){
                if( empty( $data[$key] ) ){
                    $json['type'] 	        = "error";
                    $json['message']        = 'Oops!';
                    $json['message_desc']   = $item;
                    wp_send_json( $json );
                }
            }
            workreapProjectDispute($current_user->ID,$data);
        }

        /**
         * Complete proposal
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_complete_project_order(){
            global $current_user;
            $json               = array();
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            if( function_exists('workreap_verify_token') ){
                workreap_verify_token($_POST['security']);
            }
            workreapCompleteProposal($current_user->ID,$_POST);
        }

        /**
         * Update milestone
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_update_milestone(){
            global $current_user;
            $json               = array();
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            if( function_exists('workreap_verify_token') ){
                workreap_verify_token($_POST['security']);
            }
            workreapUpdateMilestoneStatus($current_user->ID,$_POST);
        }

        /**
         * Decline proposal
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_project_activities(){
            global $current_user;
            $json               = array();
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            if( function_exists('workreap_verify_token') ){
                workreap_verify_token($_POST['security']);
            }
            workreapProjectActivities($current_user->ID,$_POST);
        }

        /**
         * Decline proposal
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_decline_proposal(){
            global $current_user;
            $json               = array();
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            if( function_exists('workreap_verify_token') ){
                workreap_verify_token($_POST['security']);
            }

            $detail         = !empty($_POST['detail']) ? sanitize_textarea_field($_POST['detail']) : '';
            $proposal_id    = !empty($_POST['id']) ? intval($_POST['id']) : 0;
            workreapDeclineProposal($current_user->ID,$proposal_id,$detail);
        }

        /**
         * Proposal submition
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_submit_proposal(){
            global $current_user;
            $json               = array();
            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site();
            }
            if( function_exists('workreap_verify_token') ){
                workreap_verify_token($_POST['security']);
            }
            $project_id     = !empty($_POST['project_id']) ? intval($_POST['project_id']) : 0;
            $proposal_id    = !empty($_POST['proposal_id']) ? intval($_POST['proposal_id']) : 0;
            $status         = !empty($_POST['status']) ? ($_POST['status']) : '';
            $proposal_data  = !empty($_POST['data']) ? $_POST['data']: array();
            parse_str($proposal_data,$proposal_data);
            workreapSubmitProposal($current_user->ID,$project_id,$status,$proposal_data,$proposal_id);

        }
    }
    new WorkreapProposalFunctions();
}

/**
 * update project dispute comments
 *
*/
if( !function_exists('workreapProjectDisputeComments') ){
    function workreapProjectDisputeComments($user_id=0,$request=array(),$type=''){
        global $workreap_settings, $current_user;
        $get_user_type	    = apply_filters('workreap_get_user_type', $user_id );
        $dispute_id         = !empty($request['dispute_id'])?intval($request['dispute_id']):'';
        $parent_comment_id  = !empty($request['parent_comment_id'])?intval($request['parent_comment_id']):0;
        $dispute_comment    = !empty($request['dispute_comment'])?esc_textarea($request['dispute_comment']):'';
        $action_type        = !empty($request['action_type'])?esc_textarea($request['action_type']):'reply';
        $field  = array(
            'comment' 			=> $dispute_comment,
            'comment_parent' 	=> $parent_comment_id,
        );

        $comment_id             = workreap_wp_insert_comment($field, $dispute_id, $user_id, $type);
        $freelancer_id	        = get_post_meta( $dispute_id, '_freelancer_id', true );
        $employer_id	        = get_post_meta( $dispute_id, '_employer_id', true );
        $dispute_order	        = get_post_meta( $dispute_id, '_dispute_order', true );
        $project_id             = get_post_meta( $dispute_id, '_project_id',true );
        $freelancer_id          = !empty($freelancer_id) ? intval($freelancer_id) : 0;
        $employer_id            = !empty($employer_id) ? intval($employer_id) : 0;
        $dispute_order          = !empty($dispute_order) ? intval($dispute_order) : 0;
        $project_id             = !empty($project_id) ? intval($project_id) : 0;

        $freelancer_profile_id  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id,'','freelancers') : 0;
        $employer_profile_id   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id,'','employers') : 0;
        $freelancer_profile_id  = !empty($freelancer_profile_id) ? intval($freelancer_profile_id) : 0;
        $employer_profile_id   = !empty($employer_profile_id) ? intval($employer_profile_id) : 0;

        if( !empty($action_type) && $action_type === 'reply' ){
            $sender_id              = $reciver_id = 0;
            $reciver_profile_id     = $sender_profile_id = 0;
            if( !empty($get_user_type) && $get_user_type === 'freelancers' ){
                $sender_id              = $freelancer_id;
                $sender_profile_id      = $freelancer_profile_id;
                $reciver_id             = $employer_id;
                $reciver_profile_id     = $employer_profile_id;
            } else if( !empty($get_user_type) && $get_user_type === 'employers' ){
                $sender_id              = $employer_id;
                $sender_profile_id      = $employer_profile_id;
                $reciver_id             = $freelancer_id;
                $reciver_profile_id     = $freelancer_profile_id;
            };
            $json['message_desc']   = esc_html__('You have successfully reply on this dispute.','workreap');
            // Notification to reciver about reply
            if( !empty($get_user_type) && $get_user_type === 'administrator' ){
                $notifyDetails                      = array();
                $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
                $notifyDetails['employer_id']  	    = $employer_profile_id;
                $notifyDetails['user_id']  	        = $user_id;
                $notifyDetails['project_id']  	    = $project_id;
                $notifyDetails['dispute_id']        = $dispute_id;
                $notifyDetails['dispute_comment']   = $dispute_comment;
                $notifyDetails['project_id']        = $project_id;

                $notifyData['receiver_id']		    = $freelancer_id;
                $notifyData['linked_profile']	    = $freelancer_profile_id;
                $notifyData['user_type']		    = $get_user_type;
                $notifyData['type']		            = 'project_admin_dispute_comment';
                $notifyData['post_data']		    = $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
                /* Admin Email to freelancer on dispute comment */
                $project_dispute_admin_coment_switch    = !empty($workreap_settings['project_dispute_admin_comment_switch']) ? $workreap_settings['project_dispute_admin_comment_switch'] : true;
                if(class_exists('Workreap_Email_helper') && !empty($project_dispute_admin_coment_switch)){
                    $emailData                      = array();
                    $emailData['user_email']        = get_userdata( $freelancer_id )->user_email;
                    $emailData['user_name']         = workreap_get_username($freelancer_profile_id);
                    $emailData['admin_name']        = get_userdata( $current_user->ID )->user_email;
                    $emailData['dispute_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $freelancer_id, true, 'dispute',$dispute_id);
                    $emailData['dispute_comment']   = $dispute_comment;
                    if (class_exists('WorkreapProjectDisputes')) {
                        $email_helper = new WorkreapProjectDisputes();
                        $email_helper->project_dispute_admin_commnet_to_freelancer_employer($emailData);
                    }
                }

                $notifyData['receiver_id']		    = $employer_id;
                $notifyData['linked_profile']	    = $employer_profile_id;
                $notifyData['user_type']		    = $get_user_type;
                $notifyData['type']		            = 'project_admin_dispute_comment';
                $notifyData['post_data']		    = $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
                /* Admin email to employer on dispute comment */
                $project_dispute_admin_coment_switch    = !empty($workreap_settings['project_dispute_admin_comment_switch']) ? $workreap_settings['project_dispute_admin_comment_switch'] : true;
                if(class_exists('Workreap_Email_helper') && !empty($project_dispute_admin_coment_switch)){
                    $emailData                      = array();
                    $emailData['user_email']        = get_userdata( $employer_id )->user_email;
                    $emailData['user_name']         = workreap_get_username($employer_profile_id);
                    $emailData['admin_name']        = get_userdata( $current_user->ID )->user_email;
                    $emailData['dispute_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $employer_id, true, 'dispute',$dispute_id);
                    $emailData['dispute_comment']   = $dispute_comment;
                    if (class_exists('WorkreapProjectDisputes')) {
                        $email_helper = new WorkreapProjectDisputes();
                        $email_helper->project_dispute_admin_commnet_to_freelancer_employer($emailData);
                    }
                }

            } else {
                $notifyDetails                      = array();
                $notifyDetails['sender_id']  	    = $sender_profile_id;
                $notifyDetails['project_id']  	    = $project_id;
                $notifyDetails['dispute_id']        = $dispute_id;
                $notifyDetails['dispute_comment']   = $dispute_comment;
                $notifyDetails['project_id']        = $project_id;
                $notifyData['receiver_id']		    = $reciver_id;
                $notifyData['linked_profile']	    = $reciver_profile_id;
                $notifyData['user_type']		    = $get_user_type;
                $notifyData['type']		            = 'project_refund_comments';
                $notifyData['post_data']		    = $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
                /* Email on dispute comments to each other(sender & receiver) */
                $project_dispute_user_coment_switch    = !empty($workreap_settings['project_dispute_user_comment_switch']) ? $workreap_settings['project_dispute_user_comment_switch'] : true;
                if(class_exists('Workreap_Email_helper') && !empty($project_dispute_user_coment_switch)){
                    $emailData                      = array();
                    $emailData['receiver_email']    = get_userdata( $reciver_id )->user_email;
                    $emailData['sender_name']       = workreap_get_username($sender_profile_id);
                    $emailData['receiver_name']     = workreap_get_username($reciver_profile_id);
                    $emailData['dispute_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $reciver_id, true, 'dispute',$dispute_id);
                    $emailData['dispute_comment']   = $dispute_comment;

                    if (class_exists('WorkreapProjectDisputes')) {
                        $email_helper = new WorkreapProjectDisputes();
                        $email_helper->project_dispute_user_commnet_to_eachother($emailData);
                    }
                }

            }
        } else if( !empty($action_type) && $action_type === 'refund' ){
            $project_type	= get_post_meta( $project_id, 'project_type', true );
            $project_type   = !empty($project_type) ? $project_type : '';
            if( !empty($project_type) && $project_type === 'fixed' ){
                $total_amount   = get_post_meta( $dispute_id, '_total_amount', true );
                $order_ids      = get_post_meta( $dispute_id, '_order_ids', true );
                if( !empty($order_ids) ){
                    foreach($order_ids as $order_id ){
                        $order          = wc_get_order($order_id);
                        $order->set_status('refunded');
                        $order->save();
                        update_post_meta($order->get_id(), '_task_status', 'cancelled');
                    }
                }
                update_post_meta($dispute_order, '_task_status', 'cancelled');
                if ( class_exists('WooCommerce') ) {
                    global $woocommerce;
                    if( !empty($type) && $type === 'mobile' ){
                        check_prerequisites($user_id);
                    }
                    $woocommerce->cart->empty_cart();
                    $wallet_amount              = $total_amount;
                    $product_id                 = workreap_employer_wallet_create();
                    $cart_meta                  = array();
                    $cart_meta['task_id']     	= $product_id;
                    $cart_meta['wallet_id']     = $product_id;
                    $cart_meta['product_name']  = get_the_title($product_id);
                    $cart_meta['price']         = $wallet_amount;
                    $cart_meta['payment_type']  = 'wallet';

                    $cart_data = array(
                        'wallet_id' 		=> $product_id,
                        'cart_data'     	=> $cart_meta,
                        'price'				=> $wallet_amount,
                        'payment_type'     	=> 'wallet'
                    );
                    $woocommerce->cart->empty_cart();
                    $cart_item_data = apply_filters('workreap_project_dispute_comment_cart_data',$cart_data);
                    WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                    $new_order_id	= workreap_place_order($employer_id,'wallet',$dispute_id);
                    update_post_meta($new_order_id, '_fund_type', 'freelancer');
                    update_post_meta($new_order_id, '_task_dispute_type', 'project');
                    update_post_meta($new_order_id, '_task_dispute_order', $dispute_order);

                    update_post_meta($dispute_id, 'dispute_status', 'resolved');
                    update_post_meta($dispute_id, 'winning_party', $employer_id);
                    update_post_meta($dispute_id, 'resolved_by', 'freelancers');

                }
            } else {
                do_action( 'workreap_after_refund_dispute', $dispute_id,'employers',$type );
            }
            $args   = array(
                'ID'            => $dispute_id,
                'post_status'   => 'refunded',
            );
            wp_update_post($args);
            $proposal_args   = array(
                'ID'            => $dispute_order,
                'post_status'   => 'refunded',
            );
            wp_update_post($proposal_args);
            $project_id = get_post_meta( $dispute_order, 'project_id',true );
            if( !empty($project_id) ){
                workreapUpdateProjectStatusOption($project_id,'refunded');
                update_post_meta( $dispute_order, '_hired_status',false );
                do_action( 'workreap_completed_job_badge', $freelancer_profile_id,'' );
            } 
            $json['message_desc']   = esc_html__('You have successfully refunded this dispute.','workreap');
            // Notification to employer for refund
            $notifyDetails                      = array();
            $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
            $notifyDetails['employer_id']  	    = $employer_profile_id;
            $notifyDetails['project_id']  	    = $project_id;
            $notifyDetails['dispute_id']        = $dispute_id;
            $notifyDetails['project_id']        = $project_id;
            $notifyData['receiver_id']		    = $employer_id;
            $notifyData['linked_profile']	    = $employer_profile_id;
            $notifyData['user_type']		    = $get_user_type;
            $notifyData['type']		            = 'project_refund_approved';
            $notifyData['post_data']		    = $notifyDetails;
            do_action('workreap_notification_message', $notifyData );
            /* Email on project refund approved */
            $project_refund_approve_switch        = !empty($workreap_settings['refund_project_request_approved_employer_switch']) ? $workreap_settings['refund_project_request_approved_employer_switch'] : true;
            if(class_exists('Workreap_Email_helper') && !empty($project_refund_approve_switch)){
                $emailData                      = array();
                $emailData['employer_email']        = get_userdata( $employer_id )->user_email;
                $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                $emailData['dispute_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $employer_id, true, 'dispute',$dispute_id);
                if (class_exists('WorkreapProjectDisputes')) {
                    $email_helper = new WorkreapProjectDisputes();
                    $email_helper->project_dispute_refund_approve_by_freelancer($emailData);
                }
            }


        } else if( !empty($action_type) && $action_type === 'decline' ){
            $args   = array(
                'ID'            => $dispute_id,
                'post_status'   => 'declined',
            );
            wp_update_post($args);
            $json['message_desc']   = esc_html__('You have successfully decline this dispute.','workreap');
            // Notification to employer for decline
            $notifyDetails                      = array();
            $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
            $notifyDetails['employer_id']  	    = $employer_profile_id;
            $notifyDetails['project_id']  	    = $project_id;
            $notifyDetails['dispute_id']        = $dispute_id;
            $notifyDetails['project_id']        = $project_id;
            $notifyData['receiver_id']		    = $employer_id;
            $notifyData['linked_profile']	    = $employer_profile_id;
            $notifyData['user_type']		    = $get_user_type;
            $notifyData['type']		            = 'project_refund_decline';
            $notifyData['post_data']		    = $notifyDetails;
            do_action('workreap_notification_message', $notifyData );
            /* Email on project refund decline */
            $project_refund_decline_switch        = !empty($workreap_settings['refund_project_request_decline_employer_switch']) ? $workreap_settings['refund_project_request_decline_employer_switch'] : true;
            if(class_exists('Workreap_Email_helper') && !empty($project_refund_decline_switch)){
                $emailData                      = array();
                $emailData['employer_email']        = get_userdata( $employer_id )->user_email;
                $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                $emailData['dispute_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $employer_id, true, 'dispute',$dispute_id);
                if (class_exists('WorkreapProjectDisputes')) {
                    $email_helper = new WorkreapProjectDisputes();
                    $email_helper->project_dispute_refund_decline_by_freelancer($emailData);
                }
            }

        }
        $json['type']           = 'success';

        if( empty($type) ){
            wp_send_json( $json );
        } else {
            return $json;
        }
    }
}

/**
 * create a dispute
 *
 */
if( !function_exists('workreapProjectDispute') ){
    function workreapProjectDispute($user_id=0,$request=array(),$type=''){
        $json                   = array();
        $json['type']           = 'error';
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap');
        if( !empty($user_id) && !empty($request['proposal_id'])){
            $proposal_id        = !empty($request['proposal_id']) ? intval($request['proposal_id']) : 0;
            $dispute_issue      = !empty($request['dispute_issue']) ? esc_html($request['dispute_issue']):'';
            $dispute_details    = !empty($request['dispute-details']) ? sanitize_textarea_field($request['dispute-details']):'';

            $dispute_is         = get_post_meta( $proposal_id, 'dispute', true);
            if( !empty( $dispute_is ) && $dispute_is == 'yes' ){
                $json['type']           = "error";
                $json['message_desc']   = esc_html__("You have already submitted the refund request against this task.", 'workreap');
                if( empty($type) ){
                    wp_send_json( $json );
                } else {
                    return $json;
                }
            }
            $fields	= array(
                'dispute_issue'     => esc_html__('Please select the dispute reason','workreap'),
                'dispute-details' 	=> esc_html__('Please add dispute details','workreap'),
                'dispute_terms' 	=> esc_html__('You must select terms and conditions','workreap'),
            );
            foreach( $fields as $key => $item ){
                if( empty( $request[$key] ) ){
                    $json['type'] 	        = "error";
                    $json['message_desc']   = $item;
                    if( empty($type) ){
                        wp_send_json( $json );
                    } else {
                        return $json;
                    }

                }
            }
            $proposal_meta          = get_post_meta( $proposal_id, 'proposal_meta', true );
            $proposal_meta          = !empty($proposal_meta) ? $proposal_meta : array();

            $project_id             = get_post_meta( $proposal_id, 'project_id',true );
            $project_id             = !empty($project_id) ? intval($project_id) : 0;

            $employer_id               = get_post_field( 'post_author', $project_id );
            $employer_id               = !empty($employer_id) ? intval($employer_id) : 0;

            $freelancer_id               = get_post_field( 'post_author', $proposal_id );
            $freelancer_id               = !empty($freelancer_id) ? intval($freelancer_id) : 0;

            $gmt_time		        = current_time( 'mysql', 1 );
            $user_type              = apply_filters('workreap_get_user_type', $user_id );
            $linked_profile         = workreap_get_linked_profile_id($user_id,'',$user_type);
            $username   	        = workreap_get_username( $linked_profile );
            $dispute_title      	= get_the_title($project_id).' #'. $proposal_id;
            $post_status            = !empty($user_type) && $user_type === 'freelancers' ? 'disputed' : 'publish';
            $dispute_post  = array(
                'post_title'    => wp_strip_all_tags( $dispute_title ),
                'post_status'   => $post_status,
                'post_content'  => $dispute_details,
                'post_author'   => $user_id,
                'post_type'     => 'disputes',
            );
            $dispute_id     = wp_insert_post( $dispute_post );
            $post_type      = get_post_type($proposal_id);
            update_post_meta( $dispute_id, '_dispute_type',$post_type );
            update_post_meta( $dispute_id, '_sender_type', $user_type);
            update_post_meta( $dispute_id, '_send_by', $user_id);
            update_post_meta( $dispute_id, '_freelancer_id', $freelancer_id);
            update_post_meta( $dispute_id, '_employer_id', $employer_id);
            update_post_meta( $dispute_id, '_dispute_key', $dispute_issue);

            update_post_meta( $dispute_id, '_dispute_order', $proposal_id);
            update_post_meta( $dispute_id, '_proposal_id', $proposal_id);
            update_post_meta( $dispute_id, '_project_id', $project_id);
            update_post_meta( $proposal_id, 'dispute', 'yes');
            update_post_meta( $proposal_id, 'dispute_id', $dispute_id);
            $proposal_type          = !empty($proposal_meta['proposal_type']) ? $proposal_meta['proposal_type'] : '';
            $order_ids              = array();
            $total_amount           = 0;
            if( !empty($proposal_type) && $proposal_type === 'fixed'){
                $order_id   = get_post_meta( $proposal_id, 'order_id',true );
                if( !empty($order_id) ){
                    $order_ids[]    = $order_id;
                }
                update_post_meta( $order_id, 'dispute', 'yes');
                update_post_meta( $order_id, 'dispute_id', $dispute_id);
                $wallet_amount  = get_post_meta( $order_id, '_wallet_amount', true );
                $wallet_amount  = !empty($wallet_amount) ? $wallet_amount : 0;
                $order          = wc_get_order($order_id);
                $get_total      = !empty($order) ? $order->get_total() : 0;
                $total_amount   = $wallet_amount + $get_total;
            } else if( !empty($proposal_type) && $proposal_type === 'milestone'){
                $milestone  = !empty($proposal_meta['milestone']) ? $proposal_meta['milestone'] : array();
                if( !empty($milestone) ){
                    foreach( $milestone as $key => $value ){
                        $status     = !empty($value['status']) ? $value['status'] : '';
                        $order_id   = !empty($value['order_id']) ? intval($value['order_id']) : 0;
                        if( !empty($order_id) && !empty($status) && in_array($status, array('hired','decline','requested'))){
                            $order_ids[]    = $order_id;
                            update_post_meta( $order_id, 'dispute', 'yes');
                            update_post_meta( $order_id, 'dispute_id', $dispute_id);
                            $wallet_amount  = get_post_meta( $order_id, '_wallet_amount', true );
                            $wallet_amount  = !empty($wallet_amount) ? $wallet_amount : 0;
                            $order          = wc_get_order($order_id);
                            $get_total      = !empty($order) ? $order->get_total() : 0;
                            $total_amount   = $total_amount+$wallet_amount + $get_total;
                        }
                    }
                }
            } else if( empty($proposal_type) ){
                $order_id   = get_post_meta( $proposal_id, 'order_id',true );
                if( !empty($order_id) ){
                    $order_ids[]    = $order_id;
                }
                update_post_meta( $order_id, 'dispute', 'yes');
                update_post_meta( $order_id, 'dispute_id', $dispute_id);
                $wallet_amount  = get_post_meta( $order_id, '_wallet_amount', true );
                $wallet_amount  = !empty($wallet_amount) ? $wallet_amount : 0;
                $order          = wc_get_order($order_id);
                $get_total      = !empty($order) ? $order->get_total() : 0;
                $total_amount   = $wallet_amount + $get_total;
            }

            update_post_meta( $dispute_id, '_total_amount',$total_amount );
            update_post_meta( $dispute_id, '_order_ids',$order_ids );
            $proposal_post = array(
                'ID'           	=> $proposal_id,
                'post_status'   => 'disputed'
            );
            wp_update_post( $proposal_post );
            $employer_id                           = get_post_field( 'post_author', $project_id );
            $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
            $freelancer_id                          = get_post_field( 'post_author', $proposal_id );
            $freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
            $notifyDetails                      = array();
            $notifyDetails['employer_id']  	    = $employer_profile_id;
            $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
            $notifyDetails['project_id']  	    = $project_id;
            $notifyDetails['proposal_id']  	    = $proposal_id;
            $notifyDetails['dispute_id']        = $dispute_id;
            $notifyDetails['dispute_order_amount']  	    = $total_amount;
            if(!empty($user_type) && $user_type === 'freelancers'){
                // freelancer add dispute
                // $notifyData['receiver_id']		    = $employer_id;
                // $notifyData['linked_profile']	    = $employer_profile_id;
                // $notifyData['user_type']		        = 'employers';
                /// Add admin emial for creating a dispute request

            } else {
                // employer add dispute
                // Notification to freelancer on refund request
                $notifyDetails['employer_comments']    = $dispute_details;
                $notifyData['receiver_id']		    = $freelancer_id;
                $notifyData['linked_profile']	    = $freelancer_profile_id;
                $notifyData['user_type']		    = 'freelancers';
                $notifyData['type']		            = 'project_refund_request';
                $notifyData['post_data']		    = $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
                /* Project refund request */
                if(class_exists('Workreap_Email_helper')){
                    $emailData                      = array();
                    $emailData['freelancer_email']      = get_userdata( $freelancer_id )->user_email;
                    $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                    $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                    $emailData['project_title']     = get_the_title($project_id);

                    if (class_exists('WorkreapProjectDisputes')) {
                        $email_helper = new WorkreapProjectDisputes();
                        /* email to freelancer */
                        $emailData['dispute_link']  = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $freelancer_id, true, 'dispute',$dispute_id);
                        $email_helper->dispute_project_request_freelancer_email($emailData);
                    }
                }
            }
            $json['type']           = 'success';
	        $json['message']        = esc_html__( 'Submitted successfully', 'workreap' );
            $json['message_desc']   = esc_html__('You have successfully submit dispute request for this proposal.','workreap');
            if( empty($type) ){
                wp_send_json( $json );
            } else {
                return $json;
            }
        }
        if( empty($type) ){
            wp_send_json( $json );
        } else {
            return $json;
        }
    }
}

/**
 * Complete project
 *
 */
if( !function_exists('workreapCompleteProposal') ){
    function workreapCompleteProposal($user_id=0,$request='',$type=''){
        $json                   = array();
        $json['type']           = 'error';
	    $json['message'] 		= esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap');
        if( !empty($user_id) && !empty($request['proposal_id'])){
            $gmt_time		= current_time( 'mysql', 1 );
            $proposal_id    = !empty($request['proposal_id']) ? intval($request['proposal_id']) : 0;
            $rating_type    = !empty($request['type']) ? sanitize_text_field($request['type']) : '';
            $rating_details = !empty($request['rating_details']) ? sanitize_textarea_field($request['rating_details']) : '';

            $user_details       = get_user_by( 'ID', $user_id );
            $user_email         = !empty($user_details->user_email) ? $user_details->user_email : '';
            $freelancer_id          = get_post_field( 'post_author', $proposal_id );
            $linked_profile     = workreap_get_linked_profile_id($user_id, '', 'employers');
            $user_profiel_name  = workreap_get_username($linked_profile);

            if( !empty($rating_type) && $rating_type == 'rating' ){
                if(empty($rating_details)){
                    $json['type']           = 'error';
                    $json['message']        = esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = esc_html__('Please add the feedback', 'workreap');
                    wp_send_json( $json );
                }

                $rating_details = !empty($request['rating_details']) ? sanitize_textarea_field($request['rating_details']) : '';
                $rating_title   = !empty($request['rating_title']) ? sanitize_text_field($request['rating_title']) : '';
                $rating         = !empty($request['rating']) ? sanitize_text_field($request['rating']) : '';
                $comment_id = wp_insert_comment(array(
                    'comment_post_ID'      => $proposal_id,
                    'comment_author'       => $user_profiel_name,
                    'comment_author_email' => $user_email,
                    'comment_author_url'   => '',
                    'comment_content'      => $rating_details,
                    'comment_type'         => 'rating',
                    'comment_parent'       => 0,
                    'user_id'              => $user_id,
                    'comment_date'         => $gmt_time,
                    'comment_approved'     => 1,
                ));
                update_comment_meta($comment_id, 'rating', intval($rating));
                update_comment_meta($comment_id, '_project_order', intval($proposal_id));
                update_comment_meta($comment_id, '_rating_title', ($rating_title));
                update_comment_meta($comment_id, 'freelancer_id', intval($freelancer_id));
                update_comment_meta($comment_id, 'verified', 1);
                update_post_meta($proposal_id, '_rating_id', $comment_id);
                update_post_meta($proposal_id, '_rating', intval($rating));
                
            }
            $proposal_post = array(
                'ID'           	=> $proposal_id,
                'post_status'   => 'completed'
            );
            wp_update_post( $proposal_post );
            if( !empty($rating_type) && $rating_type == 'rating' ){
                workreap_freelancer_rating($freelancer_id);
            }
            $proposal_meta  = get_post_meta( $proposal_id, 'proposal_meta',true);
            $proposal_type  = !empty($proposal_meta['proposal_type']) ? $proposal_meta['proposal_type'] : '';
            if( !empty($proposal_type) && $proposal_type === 'milestone') {
                $allmilestone = !empty($proposal_meta['milestone']) ? $proposal_meta['milestone'] : array();
                foreach($allmilestone as $key => $value ){
                    $status     = !empty($value['status']) ? $value['status']  :'';
                    $order_id   = !empty($value['order_id']) ? intval($value['order_id'])  : 0;
                    if( !empty($order_id) && !empty($status) && $status === 'completed' ){
                        update_post_meta( $order_id, '_task_status' , 'completed');
                        update_post_meta( $order_id, '_task_completed_time', $gmt_time );
                    }
                }
            } else if( !empty($project_type) && $project_type === 'fixed'){
                $order_id   = get_post_meta( $proposal_id, 'order_id',true );
                update_post_meta( $order_id, '_task_status' , 'completed');
                update_post_meta( $order_id, '_task_completed_time', $gmt_time );
            } else if( empty($project_type)){
                $order_id   = get_post_meta( $proposal_id, 'order_id',true );
                update_post_meta( $order_id, '_task_status' , 'completed');
                update_post_meta( $order_id, '_task_completed_time', $gmt_time );
            }
            update_post_meta( $proposal_id, '_task_status' , 'completed');
            update_post_meta( $proposal_id, '_task_completed_time', $gmt_time );
            $project_id = get_post_meta( $proposal_id, 'project_id',true );
            $project_id = !empty($project_id) ? intval($project_id) : 0;

            workreapUpdateProjectStatusOption($project_id,'completed');
            update_post_meta( $proposal_id, '_hired_status',false );
            do_action( 'workreap_after_complete_proposal', $proposal_id,$type );
            $freelancer_profile_id     = workreap_get_linked_profile_id($freelancer_id, '', 'freelancers');
            do_action( 'workreap_completed_job_badge', $freelancer_profile_id,$rating_type );
            $json['type']           = 'success';
	        $json['message'] 		= esc_html__('Woohoo!', 'workreap');
            $json['message_desc']   = esc_html__('You have successfully completed this proposal.','workreap');
            if( empty($type) ){
                wp_send_json( $json );
            } else {
                return $json;
            }
        }
    }
}

/**
 * Update Milestone status
 *
 */
if( !function_exists('workreapUpdateMilestoneStatus') ){
    function workreapUpdateMilestoneStatus($user_id=0,$request='',$type=''){
        global $workreap_settings;
        $json                   = array();
        $json['type']           = 'error';
	    $json['message'] 		= esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap');

        if( !empty($user_id) && !empty($request['id']) && !empty($request['status']) && !empty($request['key'])){
            $status             = !empty($request['status']) ? $request['status'] : '';
            $proposal_id        = !empty($request['id']) ? intval($request['id']) : 0;
            $proposal_meta	    = get_post_meta( $proposal_id, 'proposal_meta',true);
            $proposal_meta	    = !empty($proposal_meta) ? $proposal_meta : array();
            $milestone_id       = !empty($request['key']) ? $request['key'] : '';
            $order_id           = !empty($proposal_meta['milestone'][$milestone_id]['order_id']) ? intval($proposal_meta['milestone'][$milestone_id]['order_id']) : 0;
            $project_id         = get_post_meta( $proposal_id, 'project_id',true);
            $project_id         = !empty($project_id) ? intval($project_id) : 0;
            $employer_id           = get_post_field( 'post_author', $project_id );

            $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
            $freelancer_id                          = get_post_field( 'post_author', $proposal_id );
            $freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
            $notifyDetails                      = array();
            $notifyData                         = array();
            $notifyDetails['employer_id']  	    = $employer_profile_id;
            $notifyDetails['freelancer_id']  	= $freelancer_profile_id;
            $notifyDetails['project_id']  	    = $project_id;
            $notifyDetails['proposal_id']  	    = $proposal_id;
            $notifyDetails['milestone_id']  	= $milestone_id;
            $notifyData['post_data']		    = $notifyDetails;
            if(!empty($proposal_meta['milestone'][$milestone_id])){
                $proposal_meta['milestone'][$milestone_id]['status']  = $status;
            }
            $time       = current_time('mysql');
            if( $status === 'completed'){
                if(!empty($proposal_meta['milestone'][$milestone_id])){
                    $proposal_meta['milestone'][$milestone_id]['completed_date']  = $time;
                    update_post_meta( $order_id, '_task_status' , 'completed');
                    update_post_meta( $order_id, '_task_completed_time', $time );
                    $notifyData['receiver_id']		    = $freelancer_id;
                    $notifyData['linked_profile']	    = $freelancer_profile_id;
                    $notifyData['user_type']		    = 'freelancers';
                    $notifyData['type']		            = 'milestone_completed';
                    do_action('workreap_notification_message', $notifyData );

                    /* Email to freelancer on milestone complete */
                    $milestone_complete_switch        = !empty($workreap_settings['email_milestone_complete_freelancer']) ? $workreap_settings['email_milestone_complete_freelancer'] : true;
                    if(class_exists('Workreap_Email_helper') && !empty($milestone_complete_switch)){
                        $emailData                      = array();
                        $emailData['freelancer_email']      = get_userdata( $freelancer_id )->user_email;
                        $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                        $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                        $emailData['project_title']     = get_the_title($project_id );
                        $emailData['milestone_title']   = !empty($proposal_meta['milestone'][$milestone_id]['title']) ? $proposal_meta['milestone'][$milestone_id]['title'] : '';
                        $emailData['project_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'activity', $proposal_id);

                        if (class_exists('WorkreapMilestones')) {
                            $email_helper = new WorkreapMilestones();
                            $email_helper->milestone_complete_freelancer_email($emailData);
                        }
                    }
                }
            } else if( $status === 'decline'){
                if(empty($request['decline_reason'])){
                    $json['type']           = 'error';
	                $json['message'] 		= esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = esc_html__('Decline reason is required','workreap');
                    if( empty($type) ){
                        wp_send_json( $json );
                    }
                } else {
                    if(!empty($proposal_meta['milestone'][$milestone_id])){
                        $proposal_meta['milestone'][$milestone_id]['decline_reason']  = $request['decline_reason'];
                        $proposal_meta['milestone'][$milestone_id]['decline_date']    = $time;
                        $notifyData['receiver_id']		    = $freelancer_id;
                        $notifyData['linked_profile']	    = $freelancer_profile_id;
                        $notifyData['user_type']		    = 'freelancers';
                        $notifyData['type']		            = 'milestone_decline';
                        do_action('workreap_notification_message', $notifyData );

                        /* milestone decline email */
                        $milestone_decline_switch        = !empty($workreap_settings['email_milestone_decline_freelancer']) ? $workreap_settings['email_milestone_decline_freelancer'] : true;
                        if(class_exists('Workreap_Email_helper') && !empty($milestone_decline_switch)){
                            $emailData                      = array();
                            $emailData['freelancer_email']      = get_userdata( $freelancer_id )->user_email;
                            $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                            $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                            $emailData['project_title']     = get_the_title($project_id );
                            $emailData['milestone_title']   = !empty($proposal_meta['milestone'][$milestone_id]['title']) ? $proposal_meta['milestone'][$milestone_id]['title'] : '';
                            $emailData['project_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'activity', $proposal_id);

                            if (class_exists('WorkreapMilestones')) {
                                $email_helper = new WorkreapMilestones();
                                $email_helper->milestone_decline_freelancer_email($emailData);
                            }

                        }
                    }
                }
            } else if( $status === 'requested'){
                $notifyData['receiver_id']		    = $employer_id;
                $notifyData['linked_profile']	    = $employer_profile_id;
                $notifyData['user_type']		    = 'employers';
                $notifyData['type']		            = 'milestone_request';
                do_action('workreap_notification_message', $notifyData );
                /* Email to employer on milestone approval request */
                $milestone_approval_req_switch        = !empty($workreap_settings['email_req_milestone_approval_employer']) ? $workreap_settings['email_req_milestone_approval_employer'] : true;
                if(class_exists('Workreap_Email_helper') && !empty($milestone_approval_req_switch)){
                    $emailData                      = array();
                    $emailData['employer_email']        = get_userdata( $employer_id )->user_email;
                    $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                    $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                    $emailData['project_title']     = get_the_title($project_id );
                    $emailData['milestone_title']   = !empty($proposal_meta['milestone'][$milestone_id]['title']) ? $proposal_meta['milestone'][$milestone_id]['title'] : '';
                    $emailData['milestone_link']    = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $employer_id, true, 'activity',$proposal_id);

                    if (class_exists('WorkreapMilestones')) {
                        $email_helper = new WorkreapMilestones();
                        $email_helper->approval_milestone_req_employer_email($emailData);
                    }
                }

            }
            update_post_meta( $order_id, '_post_project_status', $status );
            update_post_meta( $proposal_id, 'proposal_meta', $proposal_meta );

            $json['type']           = 'success';
	        $json['message'] 		= esc_html__('Woohoo!', 'workreap');
            $json['message_desc']   = esc_html__('You have successfully update milestone','workreap');
            if( empty($type) ){
                wp_send_json( $json );
            } else {
                return $json;
            }
        }
        if( empty($type) ){
            wp_send_json( $json );
        } else {
            return $json;
        }
    }
}

/**
 * Decline proposal
 *
 */
if( !function_exists('workreapProjectActivities') ){
    function workreapProjectActivities($user_id=0,$request='',$type=''){
        $json                   = array();
        $json['type']           = 'error';
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap');
        if( !empty($user_id) && !empty($request['id'])){
            if( empty($request['details']) ){
                $json['type']           = 'error';
	            $json['message']        = esc_html__( 'Required', 'workreap' );
                $json['message_desc']   = esc_html__('Activity detail field is required','workreap');
                if( empty($type) ){
                    wp_send_json( $json );
                }
            } else {
                $proposal_id 	= !empty( $request['id'] ) ? intval($request['id']) : '';
                $temp_items     = !empty( $request['attachments']) ? ($request['attachments']) : array();
                $content 	    = !empty( $request['details'] ) ? esc_textarea($request['details']) : '';

                $user_type         = apply_filters('workreap_get_user_type', $user_id);
                $linked_profile_id = workreap_get_linked_profile_id($user_id, '', $user_type);
                $user_name         = workreap_get_username($linked_profile_id);

                $project_files = array();
                if( !empty( $temp_items ) && empty($type) ) {
                    foreach ( $temp_items as $key => $file_temp_path ) {
                        $project_files[] = workreap_temp_upload_to_activity_dir($file_temp_path, $order_id,true);
                    }
                } elseif( !empty($type) && $type === 'mobile' ) {
                    $total_documents 		= !empty($request['document_size']) ? $request['document_size'] : 0;
                    if( !empty( $_FILES ) && $total_documents != 0 ){
                        require_once( ABSPATH . 'wp-admin/includes/file.php');
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        require_once( ABSPATH . 'wp-includes/pluggable.php');

                        for ($x = 1; $x <= $total_documents; $x++) {
                            $document_files 	= $_FILES['documents_'.$x];
                            $uploaded_image  	= wp_handle_upload($document_files, array('test_form' => false));
                            $project_files[]    = workreap_temp_upload_to_activity_dir($uploaded_image['url'], $order_id,true);
                        }
                    }
                }

                $userdata   = !empty($user_id)  ? get_userdata( $user_id ) : array();
                $user_email = !empty($userdata) ? $userdata->user_email : '';
                $project_id = get_post_meta( $proposal_id, 'project_id',true);
                $project_id = !empty($project_id) ? intval($project_id) : 0;

                $time       = current_time('mysql');
                // prepare data array for insertion
                $data = array(
                    'comment_post_ID' 		    => $proposal_id,
                    'comment_author' 		    => $user_name,
                    'comment_author_email' 	    => $user_email,
                    'comment_author_url' 	    => 'http://',
                    'comment_content' 		    => $content,
                    'comment_type' 			    => 'activity_detail',
                    'comment_parent' 		    => 0,
                    'user_id' 				    => $user_id,
                    'comment_date' 			    => $time,
                    'comment_approved' 		    => 1,
                );

                // insert data
                $comment_id = wp_insert_comment(apply_filters('project_proposal_activity_data_filter', $data));
                if( !empty( $project_files )) {
                    add_comment_meta($comment_id, 'message_files', $project_files);
                }

                $employer_id           = get_post_field( 'post_author', $project_id );
                $freelancer_id          = get_post_field( 'post_author', $proposal_id );
                add_comment_meta($comment_id, 'user_type', $user_type);
                add_comment_meta($comment_id, 'project_id', $project_id);
                add_comment_meta($comment_id, 'employer_id', $employer_id);
                add_comment_meta($comment_id, 'freelancer_id', $freelancer_id);

                $freelancer_profile_id      = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id,'','freelancers') : 0;
                $employer_profile_id       = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id,'','employers') : 0;
                $sender_id              = 0;
                $reciver_id             = 0;
                $reciver_profile_id     = 0;
                $sender_profile_id      = 0;
                if( !empty($user_type) && $user_type === 'freelancers' ){
                    $sender_id              = $freelancer_id;
                    $sender_profile_id      = $freelancer_profile_id;
                    $reciver_id             = $employer_id;
                    $reciver_profile_id     = $employer_profile_id;
                } else if( !empty($user_type) && $user_type === 'employers' ){
                    $sender_id              = $employer_id;
                    $sender_profile_id      = $employer_profile_id;
                    $reciver_id             = $freelancer_id;
                    $reciver_profile_id     = $freelancer_profile_id;
                }
                $notifyDetails                      = array();
                $notifyDetails['sender_id']  	    = $sender_profile_id;
                $notifyDetails['activity_comment']  = $content;
                $notifyDetails['project_id']        = $project_id;
                $notifyDetails['proposal_id']       = $proposal_id;
                $notifyData['receiver_id']		    = $reciver_id;
                $notifyData['linked_profile']	    = $reciver_profile_id;
                $notifyData['user_type']		    = $user_type;
                $notifyData['type']		            = 'project_activity_comments';
                $notifyData['post_data']		    = $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
                /* Email to receiver on project activity */
                $user_comment_switch        = !empty($workreap_settings['project_dispute_user_comment_switch']) ? $workreap_settings['project_dispute_user_comment_switch'] : true;
                if(class_exists('Workreap_Email_helper') && !empty($user_comment_switch)){
                    $emailData                      = array();
                    $emailData['reciever_email']    = get_userdata( $reciver_id )->user_email;
                    $emailData['sender_name']       = workreap_get_username($sender_profile_id);
                    $emailData['receiver_name']     = workreap_get_username($reciver_profile_id);
                    $emailData['project_title']     = get_the_title($project_id);
                    $emailData['project_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $reciver_id, true, 'activity',$proposal_id);
                    if (class_exists('WorkreapProjectCreation')) {
                        $email_helper = new WorkreapProjectCreation();
                        $email_helper->project_activity_receiver_email($emailData);
                    }
                }


                $json['type']           = 'success';
                $json['message']   = esc_html__('Activity added','workreap');
                $json['message_desc']   = esc_html__('You have successfully add activity','workreap');
                if( empty($type) ){
                    $json['redirect_url']   = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_id, true, 'listing');
                    wp_send_json( $json );
                }
            }
        }
        if( empty($type) ){
            wp_send_json( $json );
        }
    }
}
/**
 * Decline proposal
 *
 */
if( !function_exists('workreapDeclineProposal') ){
    function workreapDeclineProposal($user_id=0, $proposal_id=0, $detail='', $type=''){
        global $workreap_settings;
        $json                   = array();
        $json['type']           = 'error';
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap');
        if( !empty($user_id) && !empty($proposal_id)){
            if( empty($detail) ){
                $json['type']           = 'error';
                $json['message_desc']   = esc_html__('Decline detail field is required','workreap');
                if( empty($type) ){
                    wp_send_json( $json );
                }  else {
                    return $json;
                }
            } else {
                $wr_post_data                   = array();
                $wr_post_data['ID']             = $proposal_id;
                $wr_post_data['post_status']    = 'decline';
                wp_update_post( $wr_post_data );
                update_post_meta( $proposal_id, 'decline_detail',$detail);
                $project_id                         = get_post_meta( $proposal_id, 'project_id',true );
                $employer_id                           = !empty($project_id) ? get_post_field( 'post_author', $project_id ) : 0;
                $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
                $freelancer_id                          = get_post_field( 'post_author', $proposal_id );
                $freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
                $notifyDetails                      = array();
                $notifyDetails['employer_id']  	    = $employer_profile_id;
                $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
                $notifyDetails['project_id']  	    = $project_id;
                $notifyDetails['proposal_id']  	    = $proposal_id;
                $notifyData['post_data']		    = $notifyDetails;
                $notifyData['type']		            = 'rejected_proposal';
                $notifyData['receiver_id']		    = $freelancer_id;
                $notifyData['linked_profile']	    = $freelancer_profile_id;
                $notifyData['user_type']		    = 'freelancers';
                do_action('workreap_notification_message', $notifyData );
                /// Add proposal decline email
                $proposal_decline_switch        = !empty($workreap_settings['email_proposal_decline_freelancer']) ? $workreap_settings['email_proposal_decline_freelancer'] : true;
                if(class_exists('Workreap_Email_helper') && !empty($proposal_decline_switch)){
                    $emailData                      = array();
                    $emailData['freelancer_email']      = get_userdata($freelancer_id)->user_email;
                    $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                    $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                    $emailData['project_title']     = get_the_title($project_id);
                    $emailData['proposal_link']     = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $freelancer_id, true, 'listing');
                    if (class_exists('WorkreapProposals')) {
                        $email_helper = new WorkreapProposals();
                        $email_helper->decline_proposal_freelancer_email($emailData);
                    }
                }

                $json['type']           = 'success';
                $json['message_desc']   = esc_html__('You have successfully decline this proposal','workreap');
                if( empty($type) ){
                    $json['redirect_url']   = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_id, true, 'listing');
                    wp_send_json( $json );
                } else {
                    return $json;
                }
            }
        }
        if( empty($type) ){
            wp_send_json( $json );
        } else {
            return $json;
        }
    }
}
/**
 * Add Milestone
 *
 */
if( !function_exists('workreapAddMilestone') ){
    function workreapAddMilestone($user_id=0,$data=array(),$type=''){
        $json           = array();
        $proposal_id    = !empty($data['proposal_id']) ? intval($data['proposal_id']) : 0;

        if( empty($proposal_id) ){
            $json['type']           = 'error';
	        $json['message'] 		= esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap');
            if( empty($type) ){
                wp_send_json( $json );
            } else {
                return $json;
            }
        }

        $proposal_meta	= get_post_meta( $proposal_id, 'proposal_meta',true);
        $proposal_meta	= !empty($proposal_meta) ? $proposal_meta : array();
        $old_milestone  = !empty($proposal_meta['milestone']) ? $proposal_meta['milestone'] : array();
        $milestones     = !empty($data['milestone']) ? array_merge($old_milestone,$data['milestone']) : array();
        $milestone_price= 0;
        $proposal_price = isset($proposal_meta['price'])? $proposal_meta['price'] : 0;
        if( empty($milestones) ){
            $json['type']           = 'error';
	        $json['message'] 		= esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__( 'Please add atleaset one milestone', 'workreap' );
            if( empty($type) ){
                wp_send_json( $json );
            } else {
                return $json;
            }
        } else {
            foreach($milestones as $key => $value ){
                if( empty($value['price']) || $value['price'] < 0 ){
                    $json['type']           = 'error';
	                $json['message'] 		= esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = esc_html__( 'Milestone price must be greater then 0', 'workreap' );
                    if( empty($type) ){
                        wp_send_json( $json );
                    } else {
                        return $json;
                    }
                } else {
                    $milestone_price    = $milestone_price+$value['price'];
                }
                if( empty($value['title']) ){
                    $json['type']           = 'error';
	                $json['message'] 		= esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = esc_html__( 'Milestone title is required', 'workreap' );
                    if( empty($type) ){
                        wp_send_json( $json );
                    } else {
                        return $json;
                    }
                }

            }
        }

        if( !empty($milestone_price) && $milestone_price > $proposal_price ){
            $json['type']           = 'error';
	        $json['message'] 		= esc_html__('Oops!', 'workreap');
            $json['message_desc']   = esc_html__( 'Milestone total price is greater the proposal price', 'workreap' );
            if( empty($type) ){
                wp_send_json( $json );
            } else {
                return $json;
            }
        } else {
            $proposal_meta['milestone'] = $milestones;
            update_post_meta( $proposal_id, 'proposal_meta',$proposal_meta );
            // Notification and email to employer
            $project_id         = get_post_meta( $proposal_id, 'project_id',true);
            $project_id         = !empty($project_id) ? intval($project_id) : 0;
            $employer_id           = get_post_field( 'post_author', $project_id );
            $freelancer_id          = get_post_field( 'post_author', $proposal_id );

            $freelancer_profile_id  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id,'','freelancers') : 0;
            $employer_profile_id   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id,'','employers') : 0;

            $notifyDetails                      = array();
            $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
            $notifyDetails['employer_id']  	    = $employer_profile_id;
            $notifyDetails['project_id']  	    = $project_id;
            $notifyDetails['project_id']        = $project_id;
            $notifyData['receiver_id']		    = $employer_id;
            $notifyData['linked_profile']	    = $employer_profile_id;
            $notifyData['user_type']		    = 'employers';
            $notifyData['type']		            = 'milestone_creation';
            $notifyData['post_data']		    = $notifyDetails;
            do_action('workreap_notification_message', $notifyData );
            /* Email to employer on new milestone */
            $project_new_milestone_switch        = !empty($workreap_settings['email_new_project_milestone_employer_switch']) ? $workreap_settings['email_new_project_milestone_employer_switch'] : true;
            if(class_exists('Workreap_Email_helper') && !empty($project_new_milestone_switch)){
                $emailData                      = array();
                $emailData['employer_email']       = get_userdata( $employer_id )->user_email;
                $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                $emailData['project_title']     = get_the_title($project_id);
                $emailData['project_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $employer_id, true, 'activity',$proposal_id);
                if (class_exists('WorkreapMilestones')) {
                    $email_helper = new WorkreapMilestones();
                    $email_helper->project_new_milestone_employer_email($emailData);
                }
            }

            $json['type']           = 'success';
	        $json['message'] 		= esc_html__('Woohoo!', 'workreap');
            $json['message_desc']   = esc_html__( 'Milestone added successfully', 'workreap' );
            if( empty($type) ){
                wp_send_json( $json );
            } else {
                return $json;
            }
        }
    }
}

/**
 * Submit proposal
 *
 */
if( !function_exists('workreapSubmitProposal') ){
    function workreapSubmitProposal($user_id=0,$project_id=0,$status='',$data=array(),$proposal_id=0,$type=''){
        global $workreap_settings,$current_user;
        $package_option	        = !empty($workreap_settings['package_option']) ? $workreap_settings['package_option'] : '';
        $paid_proposal   = false;

        if(!empty($package_option) && ( $package_option == 'employer_free' || $package_option == 'paid' )){
            $paid_proposal   = true;
            $package_details  		= get_user_meta($current_user->ID, 'freelancer_package_details', true);
            $number_project_credits	= !empty($package_details['number_project_credits']) ? $package_details['number_project_credits'] : 0;

            if(empty($number_project_credits) ){
                $json['type']           = 'error';
	            $json['message'] 		= esc_html__('Oops!', 'workreap');
                $json['message_desc']   = esc_html__( 'You have consumed all the credits to apply on the project. Please renew your package to apply on this project', 'workreap' );
                wp_send_json( $json );
            }
        }
        if(empty($type)){
            if (!empty($current_user->ID)){
                $get_user_type	= apply_filters('workreap_get_user_type', $current_user->ID );
                if (!empty($get_user_type) && ($get_user_type == 'freelancers' || $get_user_type == 'employers')){
                    $profile_id  = workreap_get_linked_profile_id($current_user->ID);
                    do_action('workreap_check_user_account_status', $profile_id); //check if user is not blocked or deactive
                    do_action('workreap_check_user_account_identity', $current_user->ID);
                } else {
                    $json['type']           = 'error';
                    $json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap');
                    wp_send_json($json);
                }
            } else{
                $json['type']           = 'error';
                $json['message']           = esc_html__('Oops!','workreap');
                $json['message_desc']   = esc_html__('You must logged in to perform this action.', 'workreap');
                wp_send_json($json);
            }
        }

        $json                   = array();
        $json['type']           = 'error';
        $json['message_desc']   = esc_html__('You are not allowed to perform this action','workreap');

        if( !empty($project_id) && !empty($user_id) && !empty($status) ){
            $workreap_user_proposal  = 0;
            if( empty($proposal_id) ){
                $proposal_args = array(
                    'post_type' 	    => 'proposals',
                    // 'post_status'       => 'any',
                    'post_status'       => array('pending','completed','cancelled','hired', 'disputed'),
                    'posts_per_page'    => -1,
                    'author'            => $user_id,
                    'meta_query'        => array(
                        array(
                            'key'       => 'project_id',
                            'value'     => intval($project_id),
                            'compare'   => '=',
                            'type'      => 'NUMERIC'
                        )
                    )
                );
                $proposals                  = get_posts( $proposal_args );
                $workreap_user_proposal      = !empty($proposals) && is_array($proposals) ? count($proposals) : 0;
            } else {
                $proposal_status    = get_post_status( $proposal_id );
                if(!empty($proposal_status) && in_array($proposal_status,array('hired','completed','cancelled'))){
                    $workreap_user_proposal  = 1;
                }
            }

            if( !empty($workreap_user_proposal) ){
                $json['type']           = 'error';
                $json['message_desc']   = esc_html__('You have already submitted a proposal for this project.','workreap');
                if( empty($type) ){
                    wp_send_json( $json );
                }else {
                    return $json;
                }
            }

            $project_meta	= get_post_meta( $project_id, 'wr_project_meta',true);
            $project_type	= !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
            $is_milestone	= !empty($project_meta['is_milestone']) ? $project_meta['is_milestone'] : '';
            $required_fields    = array(
                'price'             => esc_html__( 'Proposal price is required', 'workreap' ),
                'description'       => esc_html__( 'Proposal description is required', 'workreap' ),
            );
            $offline_order          = false;
            $is_offline_enabled     = apply_filters('workreap_check_offline_payment_methods_enabled', false);
            if(!empty($is_offline_enabled) && !empty($proposal_id)){
                $shop_order_args = array(
                    'post_type' 	    => 'shop_order',
                    'post_status'       => array('wc-on-hold'),
                    'posts_per_page'    => 1,
                    'meta_query'        => array(
                        array(
                            'key'       => 'proposal_id',
                            'value'     => intval($proposal_id),
                            'compare'   => '=',
                            'type'      => 'NUMERIC'
                        )
                    )
                );
                $offline_orders         = get_posts( $shop_order_args );
                $offline_order          = !empty($offline_orders) && is_array($offline_orders) ? count($offline_orders) : false;
                if(!empty($offline_order)){
                    $required_fields['offline_order']    = esc_html__( 'You are not allowed to update the proposal while it is pending admin approval.', 'workreap' );
                }
            }

            if( !empty($project_type) && $project_type === 'fixed' && !empty($is_milestone) && $is_milestone === 'yes' ){
                $required_fields['proposal_type']   = esc_html__( 'Please select working type', 'workreap' );
            }

            if( !empty($required_fields) && $status !='draft' ){
                foreach($required_fields as $key=> $value){
                    if(empty($data[$key])){
                        $json['type']           = 'error';
                        $json['message']        = esc_html__( 'Required', 'workreap' );
                        $json['message_desc']   = $value;
                        if( empty($type) ){
                            wp_send_json( $json );
                        }else {
                            return $json;
                        }
                    } else if( $key === 'price' && $data[$key] < 0){
                        $json['type']           = 'error';
	                    $json['message']        = esc_html__( 'Required', 'workreap' );
                        $json['message_desc']   = esc_html__( 'Proposal price must be greater then 0', 'workreap' );
                        if( empty($type) ){
                            wp_send_json( $json );
                        }else {
                            return $json;
                        }
                    }
                }
            }
            do_action( 'workreap_proposal_validation', $proposal_id,$data );
            $milestone          = array();
            $milestone_price    = 0;
            $proposal_price     = !empty($data['price']) ? $data['price'] : 0;
            $milestone_option   = !empty($workreap_settings['milestone_option']) ? $workreap_settings['milestone_option'] : 'allow';



            if( !empty($is_milestone) && $is_milestone === 'yes' && !empty($data['proposal_type']) && $data['proposal_type'] === 'milestone'  && $status !='draft' ){
                $milestones     = !empty($data['milestone']) ? $data['milestone'] : array();

                if( empty($milestones) ){
                    $json['type']           = 'error';
                    $json['message_desc']   = esc_html__( 'Please add at least one milestone', 'workreap' );
                    if( empty($type) ){
                        wp_send_json( $json );
                    }else {
                        return $json;
                    }
                } else {
                    foreach($milestones as $key => $value ){
                        if( empty($value['price']) || $value['price'] < 0 ){
                            $json['type']           = 'error';
                            $json['message_desc']   = esc_html__( 'Milestone price must be greater then 0', 'workreap' );
                            if( empty($type) ){
                                wp_send_json( $json );
                            }else {
                                return $json;
                            }
                        } else {
                            $milestone_price    = $milestone_price+$value['price'];
                        }
                        if( empty($value['title']) ){
                            $json['type']           = 'error';
                            $json['message_desc']   = esc_html__( 'Milestone title is required', 'workreap' );
                            if( empty($type) ){
                                wp_send_json( $json );
                            }else {
                                return $json;
                            }
                        }

                    }
                }

                if( !empty($milestone_price) && $milestone_price > $proposal_price ){
                    $json['type']           = 'error';
                    $json['message_desc']   = esc_html__( 'Milestone total price is greater the proposal price', 'workreap' );
                    if( empty($type) ){
                        wp_send_json( $json );
                    }else {
                        return $json;
                    }
                } else if( !empty($milestone_option) && $milestone_option === 'restrict' && !empty($milestone_price) && $milestone_price < $proposal_price ){
                    $json['type']           = 'error';
                    $json['message_desc']   = esc_html__( 'Milestone total price must be equal to proposal price', 'workreap' );
                    if( empty($type) ){
                        wp_send_json( $json );
                    } else {
                        return $json;
                    }
                }
            } else if( empty($data['proposal_type']) ){
                $data['proposal_type']  = 'fixed';
            }

            $profile_id     = workreap_get_linked_profile_id($user_id,'','freelancers');
            $freelancer_name    = workreap_get_username($profile_id);
            $project_name   = get_the_title($project_id);
            $porposal_details   = !empty($data['description']) ? $data['description'] : '';
            $proposal_meta      = $data;
            $proposal_name      = $freelancer_name.'-'.$project_name;
            $proposal_status    = $status;
            $employer_id           = get_post_field( 'post_author', $project_id );
            $employer_id           = !empty($employer_id) ? intval($employer_id) : 0;

            if( !empty($status) && $status === 'publish' ){
                $proposal_status    = !empty($workreap_settings['proposal_status']) ? $workreap_settings['proposal_status'] : 'publish';
            }

            if( empty($proposal_id)){
                $wr_post_data = array(
                    'post_title'    => wp_strip_all_tags($proposal_name),
                    'post_content' => $porposal_details,
                    'post_type'    => 'proposals',
                    'post_author'  => $user_id,
                    'post_status'  => $proposal_status
                );

                $proposal_id = wp_insert_post( $wr_post_data );

                if(!empty($package_option) && ( $package_option == 'employer_free' || $package_option == 'paid' )){
                    $paid_proposal   = true;
                    $credits_required	    = !empty($workreap_settings['credits_required']) ? $workreap_settings['credits_required'] : 0;
                    $package_credit_details      = intval($package_details['number_project_credits'] ) - intval($credits_required);
                    $package_details['number_project_credits']  = $package_credit_details;

                    update_user_meta( $user_id, 'freelancer_package_details', $package_details );
                }

            } else {
                $wr_post_data['ID']             = $proposal_id;
                $wr_post_data['post_status']    = $proposal_status;
                $wr_post_data['post_content']   = ($porposal_details);
                wp_update_post( $wr_post_data );
            }

            update_post_meta( $proposal_id, 'proposal_meta',$data );
            update_post_meta( $proposal_id, 'project_id',$project_id );
            update_post_meta( $proposal_id, 'employer_id',$employer_id );
            update_post_meta( $proposal_id, 'proposal_type',$project_type );
            update_post_meta( $proposal_id, '_hired_status',false );
            do_action( 'workreap_update_proposal', $proposal_id,$data );
            $json['type']           = 'success';
            $json['redirect']       = Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_id, true, 'listing');

            if( !empty($proposal_status) && $proposal_status === 'publish'){
                // Email to employer and admin for proposal
                // Notification to employer and admin for proposal
                $employer_id                           = get_post_field( 'post_author', $project_id );
                $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
                $freelancer_id                          = get_post_field( 'post_author', $proposal_id );
                $freelancer_profile_id                  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id, '', 'freelancers') : '';
                $notifyDetails                      = array();
                $notifyDetails['employer_id']  	    = $employer_profile_id;
                $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
                $notifyDetails['project_id']  	    = $project_id;
                $notifyDetails['proposal_id']  	    = $proposal_id;
                $notifyData['post_data']		    = $notifyDetails;
                $notifyData['type']		            = 'recived_proposal';
                $notifyData['receiver_id']		    = $employer_id;
                $notifyData['linked_profile']	    = $employer_profile_id;
                $notifyData['user_type']		    = 'employers';
                do_action('workreap_notification_message', $notifyData );

                /* Email to employer and admin */
                $submit_proposal_employer_switch = !empty($workreap_settings['email_submit_proposal_employer']) ? $workreap_settings['email_submit_proposal_employer'] : true;
                $submit_proposal_admin_switch = !empty($workreap_settings['email_submited_proposal_admin']) ? $workreap_settings['email_submited_proposal_admin'] : true;
                if(class_exists('Workreap_Email_helper')){
                    $emailData                      = array();
                    $emailData['employer_email']       = get_userdata($employer_id)->user_email;
                    $emailData['employer_name']        = workreap_get_username($employer_profile_id);
                    $emailData['freelancer_name']       = workreap_get_username($freelancer_profile_id);
                    $emailData['project_title']     = get_the_title($project_id);

                    if (class_exists('WorkreapProposals')) {
                        $email_helper = new WorkreapProposals();
                        if(!empty($submit_proposal_employer_switch)){
                            $emailData['proposal_link']     = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $employer_id, true, 'detail', $proposal_id);
                            $email_helper->submit_proposal_employer_email($emailData);
                        }

                        if(!empty($submit_proposal_admin_switch)){
                            $emailData['proposal_link']     = admin_url( 'post.php?post=' . $proposal_id ) . '&action=edit';
                            $email_helper->submited_proposal_admin_email($emailData);
                        }
                    }
                }

                $json['message']   = esc_html__( 'Proposal sent', 'workreap' );
                $json['message_desc']   = esc_html__( 'Your proposal has sent successfully', 'workreap' );
            } else if( !empty($proposal_status) && $proposal_status === 'pending'){
                $json['type']           = 'success';
                // Email to admin for proposal
	            $json['message']   = esc_html__( 'Proposal sent', 'workreap' );
                $json['message_desc']   = esc_html__( 'Your proposal has sent successfully', 'workreap' );
            } else if( !empty($proposal_status) && $proposal_status === 'draft'){
                $json['type']           = 'success';
	            $json['message']   = esc_html__( 'Proposal saved', 'workreap' );
                $json['message_desc']   = esc_html__( 'Your proposal has saved successfully', 'workreap' );
            }

            if( empty($type) ){
                wp_send_json( $json );
            }else {
                return $json;
            }
        } else {
            if( empty($type) ){
                wp_send_json( $json );
            }else {
                return $json;
            }
        }

    }
}

/**
 * Project proposal html
 *
 */
if( !function_exists('workreap_project_proposal_icons_html') ){
    function workreap_project_proposal_icons_html($post_id=0,$limit=4,$show_link='') {
        global $current_user;
        $args = array(
            'post_type' 	    => 'proposals',
            'post_status'       => array('publish','hired','completed','cancelled','disputed','refunded'),
            'posts_per_page'    => $limit,
            'meta_query'        => array(
                array(
                    'key'       => 'project_id',
                    'value'     => intval($post_id),
                    'compare'   => '=',
                    'type'      => 'NUMERIC'
                )
            )
        );
        $proposals  = get_posts( $args );
        ob_start();
        if( !empty($proposals) ){
            foreach($proposals as $proposal){
                $linked_profile     = !empty($proposal->post_author) ? workreap_get_linked_profile_id($proposal->post_author,'','freelancers') : 0;
                $image_src          = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile), array('width' => 50, 'height' => 50));
                $username           = workreap_get_username($linked_profile);
                if( !empty($image_src) ){ ?>
                    <li><img src="<?php echo esc_url($image_src);?>" alt="<?php echo esc_attr($username);?>"></li>
            <?php }
            }
            if( !empty($show_link) && is_user_logged_in() && $show_link === 'yes' ){ ?>
                <li><a class="wr-view-proposal" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $current_user->ID, '', 'listing',$post_id);?>"><?php esc_html_e('View all proposals','workreap');?><i class="wr-icon-chevron-right"></i></a></li>
        <?php }
        } else { ?>
            <li><span><?php esc_html_e('No proposals received','workreap');?></span></li>
        <?php }
        echo ob_get_clean();
    }
    add_action('workreap_project_proposal_icons_html', 'workreap_project_proposal_icons_html',10,3);
}

/**
 * Project proposal html
 *
 */
if( !function_exists('workreap_proposal_icons_html') ){
    function workreap_proposal_icons_html($proposalList,$post_id=0,$limit=4,$show_link='') {
        global $current_user;
        ob_start();
        if( !empty($proposalList) ){ 
            foreach($proposalList as $proposal){
                $linked_profile     = !empty($proposal->post_author) ? workreap_get_linked_profile_id($proposal->post_author,'','freelancers') : 0;
                $image_src          = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile), array('width' => 50, 'height' => 50));
                $username           = workreap_get_username($linked_profile);
                if( !empty($image_src) ){ ?>
                    <li><img src="<?php echo esc_url($image_src);?>" alt="<?php echo esc_attr($username);?>"></li>
            <?php }
            }
            if( !empty($show_link) && is_user_logged_in() && $show_link === 'yes' ){ ?>
                <li><a class="wr-view-proposal" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $current_user->ID, '', 'listing',$post_id);?>"><?php esc_html_e('View all proposals','workreap');?><i class="wr-icon-chevron-right"></i></a></li>
        <?php }
        } else { ?>
            <li><span><?php esc_html_e('No proposals received','workreap');?></span></li>
        <?php }
        echo ob_get_clean();
    }
    add_action('workreap_proposal_icons_html', 'workreap_proposal_icons_html',10,4);
}
/**
 * List proposal filter status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_list_proposal_status_filter')) {
    function workreap_list_proposal_status_filter($type = '')
    {
        global $workreap_settings;
        $list = array(
            'any'       => esc_html__('All proposal', 'workreap'),
            'publish'   => esc_html__('Published', 'workreap'),
            'rejected'  => esc_html__('Rejected', 'workreap'),
            'disputed'     => esc_html__('Disputed', 'workreap'),
            'refunded'     => esc_html__('Refunded', 'workreap'),
            'hired'         => esc_html__('Ongoing', 'workreap'),
        );
        $proposal_status             = !empty($workreap_settings['proposal_status']) ? $workreap_settings['proposal_status'] : '';
        if( !empty($proposal_status) && $proposal_status === 'pending'){
            $list['publish']    = esc_html__('Approved', 'workreap');
        }
        $list = apply_filters('workreap_filters_list_proposal_status_filter_by', $list);
        return $list;
    }
    add_filter('workreap_list_proposal_status_filter', 'workreap_list_proposal_status_filter', 10, 1);
}

/**
 * Proposal status html
 *
 */
if( !function_exists('workreap_proposal_status_tag') ){
    function workreap_proposal_status_tag($post_id = '') {
        $proposal_status    = get_post_status( $post_id);
        $proposal_status    = !empty($proposal_status) ? $proposal_status : '';
        $lable              = "";
        $status_class       = "";
        switch($proposal_status){
            case 'pending':
                $label          = esc_html__('Pending', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'draft':
                $label          = esc_html__('Drafted', 'workreap');
                $status_class   = 'wr-project-tag';
                break;
            case 'publish':
                $label                  = esc_html__('In queue', 'workreap');
                $status_class           = 'wr-project-tag wr-new';
                break;
            case 'completed':
                $label          = _x('Completed', 'Title for proposal status', 'workreap' );
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'refunded':
                $label          = esc_html__('Refunded', 'workreap');
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'cancelled':
                $label          = esc_html__('Cancelled', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            case 'hired':
                $label          = esc_html__('Ongoing', 'workreap');
                $status_class   = 'wr-project-tag wr-ongoing';
                break;
            case 'disputed':
                $label          = esc_html__('Disputed', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            default:
                $label          = esc_html__('New', 'workreap');
                $status_class   = 'wr-project-tag';
                break;
        }
        if( !empty($label) ){
            ob_start();
            ?>
                <span class="<?php echo esc_attr($status_class);?>"><?php echo esc_html($label);?></span>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_proposal_status_tag', 'workreap_proposal_status_tag',10,1);
}

/**
 * Freelancer proposal status html
 *
 */
if( !function_exists('workreap_freelancer_proposal_status_tag') ){
    function workreap_freelancer_proposal_status_tag($post_id = '') {
        $proposal_status    = get_post_status( $post_id);
        $proposal_status    = !empty($proposal_status) ? $proposal_status : '';
        $lable              = "";
        $status_class       = "";
        switch($proposal_status){
            case 'pending':
                $label          = esc_html__('In queue', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'draft':
                $label          = esc_html__('Drafted', 'workreap');
                $status_class   = 'wr-project-tag wr-drafted';
                break;
            case 'publish':
                $label          = esc_html__('In queue', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'completed':
                $label          = _x('Completed', 'Title for proposal status', 'workreap' );
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'refunded':
                $label          = esc_html__('Refunded', 'workreap');
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'cancelled':
                $label          = esc_html__('Cancelled', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            case 'hired':
                $label          = esc_html__('Ongoing', 'workreap');
                $status_class   = 'wr-project-tag wr-ongoing';
                break;
            case 'decline':
                $label          = esc_html__('Decline', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            case 'disputed':
                $label          = esc_html__('Disputed', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            default:
                $label          = esc_html__('New', 'workreap');
                $status_class   = 'wr-project-tag';
                break;
        }
        if( !empty($label) ){
            ob_start();
            ?>
                <span class="<?php echo esc_attr($status_class);?>"><?php echo esc_html($label);?></span>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_freelancer_proposal_status_tag', 'workreap_freelancer_proposal_status_tag',10,1);
}

/**
 * Milestone status html
 *
 */
if( !function_exists('workreap_milestone_proposal_status_tag') ){
    function workreap_milestone_proposal_status_tag($status = '') {
        $label          = '';
        $status_class   = '';
        switch($status){
            case 'hired':
                $label          = esc_html__('Ongoing', 'workreap');
                $status_class   = 'wr-project-tag wr-ongoing';
                break;
            case 'completed':
                $label          = esc_html__('Approved', 'workreap');
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'cancelled':
                $label          = esc_html__('Cancelled', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            case 'requested':
                $label          = esc_html__('Awaiting for approval', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'decline':
                $label          = esc_html__('Decline', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            default:
                $label          = '';
                $status_class   = '';
                break;
        }
        if( !empty($label) ){
            ob_start();
            ?>
                <div class="wr-statusview_tag">
                    <span class="<?php echo esc_attr($status_class);?>"><?php echo esc_html($label);?></span>
                </div>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_milestone_proposal_status_tag', 'workreap_milestone_proposal_status_tag',10,1);
}


/**
 * Project invoice status html
 *
 */
if( !function_exists('workreap_proposal_invoice_status_tag') ){
    function workreap_proposal_invoice_status_tag($status = '',$return='') {
        $label          = '';
        $status_class   = '';
        switch($status){
            case 'hired':
                $label          = esc_html__('Pending', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'completed':
                $label          = _x('Completed', 'Title for invoice status', 'workreap' );
                $status_class   = 'wr-project-tag wr-success-tag';
                break;
            case 'cancelled':
                $label          = esc_html__('Cancelled', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            case 'pending':
                $label          = esc_html__('Pending', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'on-hold':
                $label          = esc_html__('On hold', 'workreap');
                $status_class   = 'wr-project-tag wr-awaiting';
                break;
            case 'decline':
                $label          = esc_html__('Decline', 'workreap');
                $status_class   = 'wr-project-tag wr-canceled';
                break;
            default:
                $label          = '';
                $status_class   = '';
                break;
        }
        if( !empty($return) ){
            return $label;
        } else {
            if( !empty($label) ){
                ob_start();
                ?>
                    <div class="wr-statusview_tag">
                        <span class="<?php echo esc_attr($status_class);?>"><?php echo esc_html($label);?></span>
                    </div>
                <?php
                echo ob_get_clean();
            }
        }
    }
    add_action('workreap_proposal_invoice_status_tag', 'workreap_proposal_invoice_status_tag',10,2);
    add_filter('workreap_proposal_invoice_status_tag', 'workreap_proposal_invoice_status_tag',10,2);
}
/**
 * Get activity chat history
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_project_comments_history')) {
    function workreap_project_comments_history($value = array())
    {
        $date           = !empty($value->comment_date) ? $value->comment_date : '';
        $author_id      = !empty($value->user_id) ? $value->user_id : '';
        $comments_id    = !empty($value->comment_ID) ? $value->comment_ID : '';
        $author         = !empty($value->comment_author) ? $value->comment_author : '';
        $message        = !empty($value->comment_content) ? $value->comment_content : '';
        $message_files  = get_comment_meta($value->comment_ID, 'message_files', true);
        $message_type   = get_comment_meta($value->comment_ID, '_message_type', true);
        $date           = !empty($date) ? date_i18n('F j, Y', strtotime($date)) : '';
        $user_type      = get_comment_meta($value->comment_ID, 'user_type', true);

        $author_user_type   = !empty($user_type) ? $user_type : apply_filters('workreap_get_user_type', $author_id);
        $author_profile_id  = workreap_get_linked_profile_id($author_id, '', $author_user_type);
        $auther_url         = !empty($author_user_type) && $author_user_type === 'freelancers' ? get_the_permalink($author_profile_id) : '#';
        $author_name        = workreap_get_username($author_profile_id);
        $avatar             = apply_filters(
            'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $author_profile_id), array('width' => 50, 'height' => 50)
        );
        $src                = WORKREAP_DIRECTORY_URI . 'public/images/doc.jpg';
        $count_fiels        = !empty($message_files) && is_array($message_files) ? count($message_files) : 0;
        ob_start();
        ?>
        <li>
            <figure class="wr-proactivity_img">
                <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author_name); ?>">
            </figure>
            <div class="wr-proactivity_info">
                <?php if( !empty($author_name) || !empty($date) ){?>
                    <h6>
                        <?php echo esc_attr($author_name); ?>
                        <?php if (!empty($date)) { ?><span><?php echo esc_html($date); ?></span><?php } ?>
                    </h6>
                <?php } ?>
                <?php if( !empty($message) ){?>
                    <p><?php echo esc_html(wp_strip_all_tags($message)); ?></p>
                <?php } ?>
                <?php if( !empty($message_files) ){?>
                    <div class="wr-proactivity_file">
                        <img src="<?php echo esc_url($src);?>" alt="<?php esc_attr_e('Download files','workreap');?>">
                        <span><?php echo sprintf(esc_html__('%s Attachments to download','workreap'),$count_fiels);?></span>
                        <span class="wr-download-attachment" data-id="<?php echo esc_attr($comments_id); ?>"><?php esc_html_e('Download file(s)','workreap');?></span>
                    </div>
                <?php } ?>
            </div>
        </li>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_project_comments_history', 'workreap_project_comments_history');
}

  /**
 * @Init Pagination Code Start
 * @return
 */
if (!function_exists('workreap_proposal_order_budget_details')) {
    add_action( 'workreap_proposal_order_budget_details', 'workreap_proposal_order_budget_details', 10, 2);
    function workreap_proposal_order_budget_details($proposal_id =0, $user_type = 'freelancers') {
		if ( !class_exists('WooCommerce') ) {
			return;
		}
        $dispute_id         = get_post_meta( $proposal_id, 'dispute_id',true );
        $dispute_id         = !empty($dispute_id) ? intval($dispute_id) : 0;

        $order_price        = get_post_meta( $dispute_id, '_total_amount',true );
        $proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
        $proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array();
        $order_ids          = get_post_meta( $dispute_id, '_order_ids',true );
        $order_ids          = !empty($order_ids) ? $order_ids : array();
        $total_tax          = 0;
		ob_start();?>
			<div class="wr-asideholder wr-taskdeadline">
				<?php if(isset($order_price)){?>
				<div class="wr-asidebox wr-additonoltitleholder">
					<div data-bs-toggle="collapse" data-bs-target="#wr-additionolinfov2" aria-expanded="true" role="button">
						<div class="wr-additonoltitle">
							<div class="wr-startingprice">
								<i><?php esc_html_e('Total project budget', 'workreap');?></i>
								<span><?php workreap_price_format($order_price);  ?></span>
							</div>
							<i class="wr-icon-chevron-down"></i>
						</div>
					</div>
				</div>
				<?php }?>
				<div id="wr-additionolinfov2" class="show">
					<div class="wr-budgetlist">
						<?php if(!empty($order_ids)){?>
							<ul class="wr-planslist">
								<?php
								// Get and Loop Over Order Items
								foreach ($order_ids as $order_id) {
                                    $order          = wc_get_order($order_id);

                                    $product_data   = get_post_meta( $order->get_id(),'cus_woo_product_data', true );
                                    $project_type   = !empty($product_data['project_type']) ? $product_data['project_type'] : '';
                                    $get_total      = $order->get_total();
                                    if(function_exists('wmc_revert_price')){
                                        $get_total =  wmc_revert_price($order->get_total(),$order->get_currency());
                                    }
                                    $invoice_title  = "";
                                    $milestone_id   = '';
                                    if( !empty($project_type) && $project_type === 'fixed' ){
                                        $milestone_id   = !empty($product_data['milestone_id']) ? $product_data['milestone_id'] : "";
                                        if( !empty($milestone_id)){
                                            $invoice_title  = !empty($proposal_meta['milestone'][$milestone_id]['title']) ? $proposal_meta['milestone'][$milestone_id]['title'] : "";
                                        } else if( empty($milestone_id) ){
                                            $project_id   = !empty($product_data['project_id']) ? $product_data['project_id'] : "";
                                            if( !empty($project_id) ){
                                                $invoice_title  = get_the_title( $project_id );
                                            }
                                        }
                                    }
                                    ?>
									<li>
										<h6>
											<?php echo esc_html($invoice_title);?>
											<span>(<?php workreap_price_format($get_total); ?>) </span>
										</h6>
									</li>
								<?php }?>
							</ul>
						<?php }?>
						<ul class="wr-planslist wr-totalfee">
							<li>
								<a href="javascript:void(0);">
									<h6>
										<?php esc_html_e('Total project budget', 'workreap');?>:&nbsp;
										<span>(<?php workreap_price_format($order_price);?>) </span>
									</h6>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		<?php
		echo ob_get_clean();
    }

}
