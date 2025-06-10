<?php

/**
 * Dispute summary
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('workreap_dispute_summary')) {
    function workreap_dispute_summary()
    {

        $json   = array();
        ob_start();
        workreap_get_template('admin-dashboard/dashboard-disputes-summary.php');

        $html   = ob_get_clean();
        $json['type']       = 'success';
        $json['html']       = $html;
        $json['message']    = esc_html__('Woohoo!', 'workreap');
        wp_send_json($json);

    }
    add_action('wp_ajax_workreap_dispute_summary', 'workreap_dispute_summary');
}


/**
 * Update earning
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_update_earning')) {
    function workreap_update_earning()
    {
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        $json               = array();
        $json['message']    = esc_html__('Earning request','workreap');

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $post_id        = !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $post_status    = !empty($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

        if( empty($post_id) || empty($post_status) ){
            $json['type']           = 'error';
	        $json['message']   = esc_html__('Not allowed', 'workreap');
			$json['message_desc']   = esc_html__('You are not allowed to perform this action', 'workreap');
			wp_send_json( $json );
        }

        wp_update_post(array(
            'ID'    	    =>  intval($post_id),
            'post_status'   =>  $post_status
        ));

        $json['type']           = 'success';
	    $json['message']   = esc_html__('Status updated', 'workreap');
        $json['message_desc']   = esc_html__('Earning status has been updated successfully', 'workreap');
        wp_send_json($json);

    }
    add_action('wp_ajax_workreap_update_earning', 'workreap_update_earning');
}
/**
 * Resolve project dispute
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_resolve_project_dispute')) {
    function  workreap_resolve_project_dispute(){
        global $current_user,$woocommerce, $workreap_settings;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        //security check
        $do_check       = check_ajax_referer('ajax_nonce', 'security', false);
        $json			= array();
        if ( $do_check == false ) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Oops!', 'workreap' );
            $json['message_desc']   = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }

        $user_id 				= !empty($_POST['user_id']) ? (int)$_POST['user_id'] : '';
        $dispute_id 			= !empty($_POST['dispute_id']) ? (int)$_POST['dispute_id'] : '';
        $dispute_feedback 		= !empty($_POST['dispute-detail']) ? esc_textarea($_POST['dispute-detail']) : '';

        $validation_fields  = array(
            'dispute-detail'    => esc_html__('Dispute feedback is required', 'workreap'),
            'user_id'           => esc_html__('Choose winning party', 'workreap'),
        );

        foreach($validation_fields as $key => $validation_field ){
            if( empty($_POST[$key]) ){
                $json['type']           = 'error';
                $json['message']        = esc_html__('Oops!', 'workreap' );
                $json['message_desc']   = $validation_field;
                wp_send_json($json);
            }
        }

        if (!empty($user_id) && !empty($dispute_feedback)) {

            $dispute_status = get_post_status($dispute_id);

            if($dispute_status == 'resolved' || $dispute_status == 'cancelled' || $dispute_status == 'refunded'){
                $json['type']           = 'error';
                $json['message']        = esc_html__('Oops!', 'workreap' );
                $json['message_desc']   = esc_html__('Dispute has been resolved already.', 'workreap');
                wp_send_json($json);
            }

            $linked_profile = workreap_get_linked_profile_id($user_id);
            $post_type  	= get_post_type($linked_profile);
            $employer_id		= get_post_meta($dispute_id, '_employer_id', true);
            $freelancer_id		= get_post_meta($dispute_id, '_freelancer_id', true);
            $proposal_id	= get_post_meta($dispute_id, '_dispute_order', true);
            $project_id     = get_post_meta( $dispute_id, '_project_id',true );
            $temp_items     = !empty( $_POST['attachments'])   ? ($_POST['attachments']) : array();

            $project_type	= get_post_meta( $project_id, 'project_type', true );
            $project_type   = !empty($project_type) ? $project_type : '';

            //Upload files from temp folder to uploads
            $project_files = array();
            if( !empty( $temp_items ) ) {
                foreach ( $temp_items as $key => $file_temp_path ) {
                    $project_files[] = workreap_temp_upload_to_activity_dir($file_temp_path, $proposal_id, true);
                }
            }

            $field  = array(
                'comment' => $dispute_feedback,
                'comment_parent' => 0,
            );

            $comment_id = workreap_wp_insert_comment($field, $dispute_id);
            add_comment_meta($comment_id, 'message_files', $project_files);
            $order_total        = get_post_meta( $order_id, '_total_amount', true );
            $order_total        = !empty($order_total) ? ($order_total) : 0;
            $notifyData		    = array();
            $notifyDetails		= array();
            $wallet_amount      = 0;
            $notifyDetails      = array();
            $freelancer_profile_id  = !empty($freelancer_id) ? workreap_get_linked_profile_id($freelancer_id,'','freelancers') : 0;
            $employer_profile_id   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id,'','employers') : 0;
            $loser_user_id      = 0;
            $loser_profile_id   = 0;
            $loser_post_type    = '';
            if( !empty($post_type) && $post_type == 'employers') {
                $loser_user_id      = $freelancer_id;
                $loser_profile_id   = $freelancer_profile_id;
                $winner_user_id     = $employer_id;
                $winner_profile_id  = $employer_profile_id;
                $loser_post_type    = 'freelancers';
                if( !empty($project_type) && $project_type ==='fixed' ){
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
                    if ( class_exists('WooCommerce') ) {
                        global $woocommerce;
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
                        $cart_item_data = apply_filters('workreap_resolve_project_dispute_cart_data',$cart_data);

                        WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                        $new_order_id	= workreap_place_order($employer_id,'wallet',$dispute_id);
                        update_post_meta($new_order_id, '_fund_type', 'freelancer');
                        update_post_meta($new_order_id, '_task_dispute_type', 'project');
                        update_post_meta($new_order_id, '_task_dispute_order', $proposal_id);
                    }  
                } else {
                    do_action( 'workreap_after_refund_dispute', $dispute_id,'employers' );
                }
                update_post_meta($proposal_id, '_task_status', 'cancelled');
                              
                $notifyData['type']         = 'employer_refunded';
            } else if( $post_type == 'freelancers' ) {
                $loser_user_id      = $employer_id;
                $loser_profile_id   = $employer_profile_id;
                $winner_user_id     = $freelancer_id;
                $winner_profile_id  = $freelancer_profile_id;
                $loser_post_type    = 'employers';
                $gmt_time       = current_time( 'mysql', 1 );
                if( !empty($project_type) && $project_type ==='fixed' ){
                    $order_ids      = get_post_meta( $dispute_id, '_order_ids', true );
                    if( !empty($order_ids) ){
                        foreach($order_ids as $order_id ){
                            update_post_meta($order_id, '_task_status', 'completed');
                            update_post_meta( $order_id, '_task_completed_time', $gmt_time );
                        }
                    }
                } else {
                    do_action( 'workreap_after_refund_dispute', $dispute_id,'freelancers' );
                }
                $notifyData['type']		= 'freelancer_refunded';
            }

            $args   = array(
                'ID'            => $dispute_id,
                'post_status'   => 'refunded',
            );
            wp_update_post($args);

            $proposal_args   = array(
                'ID'            => $proposal_id,
                'post_status'   => 'refunded',
            );
            wp_update_post($proposal_args);
            
            $project_id = get_post_meta( $proposal_id, 'project_id',true );
            if( !empty($project_id) ){
                workreapUpdateProjectStatusOption($project_id,'refunded');
                update_post_meta( $proposal_id, '_hired_status',false );
            }
           update_post_meta($dispute_id, 'winning_party', $user_id);
           update_post_meta($dispute_id, 'dispute_status', 'resolved');
           update_post_meta($dispute_id, 'resolved_by', 'admin');

           $notifyDetails['freelancer_id']  	    = $freelancer_profile_id;
           $notifyDetails['employer_id']  	        = $employer_profile_id;
           $notifyDetails['user_id']  	        = $winner_user_id;
           $notifyDetails['project_id']  	    = $project_id;
           $notifyDetails['dispute_id']         = $dispute_id;
           $notifyDetails['dispute_comment']    = $dispute_feedback;

           $notifyData['receiver_id']		    = $user_id;
           $notifyData['linked_profile']	    = $winner_profile_id;
           $notifyData['user_type']		        = $post_type;
           $notifyData['type']		            = 'admin_resolved_project_dispute_winning';
           $notifyData['post_data']		        = $notifyDetails;
           do_action('workreap_notification_message', $notifyData );
           /* Email to winner */
           $proj_dispu_fav_switch        = !empty($workreap_settings['project_disputes_favour_winner_switch']) ? $workreap_settings['project_disputes_favour_winner_switch'] : true;
           if(class_exists('Workreap_Email_helper') && !empty($proj_dispu_fav_switch)){
                $emailData                      = array();
                $emailData['user_email']        = get_userdata( $user_id )->user_email;
                $emailData['user_name']         = workreap_get_username($linked_profile);
                $emailData['admin_name']        = get_userdata($current_user->ID)->display_name;
                $emailData['dispute_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $user_id, true, 'dispute',$dispute_id);
                if (class_exists('WorkreapProjectDisputes')) {
                    $email_helper = new WorkreapProjectDisputes();
                    $email_helper->project_dispute_refunded_resolved_in_favour($emailData);
                }
            }

           $notifyData['receiver_id']		    = $loser_user_id;
           $notifyData['linked_profile']	    = $loser_profile_id;
           $notifyData['user_type']		        = $loser_post_type;
           $notifyData['type']		            = 'admin_resolved_project_dispute_loser';
           $notifyData['post_data']		        = $notifyDetails;
           do_action('workreap_notification_message', $notifyData );
           /* Email to looser */
           $proj_dispu_against_switch        = !empty($workreap_settings['project_disputes_against_looser_switch']) ? $workreap_settings['project_disputes_against_looser_switch'] : true;
           if(class_exists('Workreap_Email_helper') && !empty($proj_dispu_against_switch)){
                $emailData                      = array();
                $emailData['user_email']        = get_userdata( $loser_user_id )->user_email;
                $emailData['user_name']         = workreap_get_username($loser_profile_id);
                $emailData['admin_name']        = get_userdata($current_user->ID)->display_name;
                $emailData['dispute_link']      = Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $loser_user_id, true, 'dispute',$dispute_id);
                if (class_exists('WorkreapProjectDisputes')) {
                    $email_helper = new WorkreapProjectDisputes();
                    $email_helper->project_dispute_refunded_resolved_in_against($emailData);
                }
            }

           $json['type']		    = 'success';
           $json['message']         = esc_html__('Woohoo!', 'workreap' );
           $json['post_status']		= $post_status;
           $json['message_desc']    = esc_html__('Dispute has been resolved', 'workreap' );
           wp_send_json( $json );
       } else {
           $json['type']		    = 'error';
           $json['message']         = esc_html__('Oops!', 'workreap' );
           $json['message_desc']    = esc_html__('Something wrong! please try it again.', 'workreap' );
           wp_send_json( $json );
       }
   }
   add_action('wp_ajax_workreap_resolve_project_dispute', 'workreap_resolve_project_dispute');
}


/**
 * Resolve Dispute
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_resolve_dispute')) {
    function  workreap_resolve_dispute(){
        global $current_user,$woocommerce, $workreap_settings;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        //security check
        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        $json			= array();
        if ( $do_check == false ) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Oops!', 'workreap' );
            $json['message_desc']   = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json( $json );
        }

        $user_id 				= !empty($_POST['user_id']) ? (int)$_POST['user_id'] : '';
        $dispute_id 			= !empty($_POST['dispute_id']) ? (int)$_POST['dispute_id'] : '';
        $dispute_feedback 		= !empty($_POST['dispute-detail']) ? esc_textarea($_POST['dispute-detail']) : '';

        $validation_fields  = array(
            'dispute-detail'    => esc_html__('Dispute feedback is required', 'workreap'),
            'user_id'           => esc_html__('Choose winning party', 'workreap'),
        );

        foreach($validation_fields as $key => $validation_field ){
            if( empty($_POST[$key]) ){
                $json['type']           = 'error';
                $json['message']        = esc_html__('Oops!', 'workreap' );
                $json['message_desc']   = $validation_field;
                wp_send_json($json);
            }
        }

        if (!empty($user_id) && !empty($dispute_feedback)) {

            $dispute_status = get_post_status($dispute_id);

            if($dispute_status == 'resolved' || $dispute_status == 'cancelled' || $dispute_status == 'refunded'){
                $json['type']           = 'error';
                $json['message']        = esc_html__('Oops!', 'workreap' );
                $json['message_desc']   = esc_html__('Dispute has been resolved already.', 'workreap');
                wp_send_json($json);
            }

            $linked_profile = workreap_get_linked_profile_id($user_id);
            $post_type  	= get_post_type($linked_profile);
            $employer_id		= get_post_meta($dispute_id, '_employer_id', true);
            $freelancer_id		= get_post_meta($dispute_id, '_freelancer_id', true);
            $task_id		= get_post_meta($dispute_id, '_task_id', true);
            $order_id		= get_post_meta($dispute_id, '_dispute_order', true);
            $freelancer_id		= get_post_meta($order_id, '_freelancer_id', true);
            $temp_items     = !empty( $_POST['attachments'])   ? ($_POST['attachments']) : array();

            //Upload files from temp folder to uploads
            $project_files = array();
            if( !empty( $temp_items ) ) {
                foreach ( $temp_items as $key => $file_temp_path ) {
                    $project_files[] = workreap_temp_upload_to_activity_dir($file_temp_path, $order_id, true);
                }
            }

            $field  = array(
                'comment' => $dispute_feedback,
                'comment_parent' => 0,
            );

            $comment_id = workreap_wp_insert_comment($field, $dispute_id);
            add_comment_meta($comment_id, 'message_files', $project_files);
            $order_total        = get_post_meta( $order_id, '_order_total', true );

            $order_data         = get_post_meta( $order_id, 'cus_woo_product_data', true );
            $order_data         = !empty($order_data) ? $order_data : array();
    
            $freelancer_id          = !empty($order_data['freelancer_id']) ? intval($order_data['freelancer_id']) : 0;
            $employer_id           = !empty($order_data['employer_id']) ? intval($order_data['employer_id']) : 0;

            $order_total        = !empty($order_total) ? ($order_total) : 0;
            $notifyData		    = array();
            $notifyDetails		= array();
            $wallet_amount      = 0;
            
            if( !empty($post_type) && $post_type == 'employers') {

               $dispute_order   = get_post_meta( $dispute_id, '_dispute_order', true );
               $dispute_order   = !empty($dispute_order) ? intval($dispute_order) : 0;
               $send_by         = get_post_meta( $dispute_id, '_send_by', true );
               $send_by         = !empty($send_by) ? intval($send_by) : 0;

                if ( class_exists('WooCommerce') ) {
                   $order = wc_get_order($dispute_order);
                   $order->set_status('cancelled');
                   $order->save();

                   update_post_meta( $dispute_order, '_task_status', 'cancelled' );

                   $woocommerce->cart->empty_cart();
                   $wallet_amount              = $order_total;
                   $product_id                 = workreap_employer_wallet_create();
                   $user_id			           = $send_by;
                   $cart_meta                  = array();
                   $cart_meta['wallet_id']     = $product_id;
                   $cart_meta['product_name']  = get_the_title($product_id);
                   $cart_meta['price']         = $wallet_amount;
                   $cart_meta['payment_type']  = 'wallet';
                   $cart_meta['task_id']       = $task_id;

                   $cart_data = array(
                       'wallet_id' 		=> $product_id,
                       'cart_data'     	=> $cart_meta,
                       'price'			=> $wallet_amount,
                       'payment_type'   => 'wallet'
                   );
                   $woocommerce->cart->empty_cart();
                   $cart_item_data = apply_filters('workreap_resolve_dispute_cart_data',$cart_data);
                   WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                   $new_order_id    = workreap_place_order($user_id,'wallet',$dispute_id);
                    update_post_meta($new_order_id, '_fund_type', 'freelancer');
                    update_post_meta($new_order_id, '_task_dispute_order', $order_id);

                   $post_status    = 'refunded';

                } else {
                    $json['type']            = 'error';
                    $json['message']         = esc_html__('Oops!', 'workreap' );
                    $json['message_desc']    = esc_html__('Please install WooCommerce plugin to process this order', 'workreap');
                    wp_send_json($json);
                }

                $notifyData['type']         = 'employer_refunded';
            } else if( $post_type == 'freelancers' ) {
                $gmt_time   = current_time( 'mysql', 1 );
                update_post_meta( $order_id, '_task_status' , 'completed');
                update_post_meta( $order_id, '_task_completed_time', $gmt_time );
                $post_status            = 'refunded';
                $notifyData['type']		= 'freelancer_refunded';
            }
            $freelancer_profile_id      = workreap_get_linked_profile_id($freelancer_id, '', 'freelancers');
            $employer_profile_id       = workreap_get_linked_profile_id($employer_id, '', 'employers');

            $notifyDetails['task_id']           = $task_id;
            $notifyDetails['post_link_id']  	= $task_id;
            $notifyDetails['dispute_comment']	= $dispute_feedback;
            $notifyDetails['order_amount']  	= !empty($post_type) && $post_type === 'employers' ? $wallet_amount : $order_total;
            $notifyDetails['order_id']          = $order_id;
            $notifyDetails['dispute_id']        = $dispute_id;
            $notifyDetails['freelancer_id']         = $freelancer_profile_id;
            $notifyDetails['employer_id']          = $employer_profile_id;

            $notifyData['receiver_id']		    = !empty($post_type) && $post_type === 'freelancers' ? $freelancer_id : $employer_id;
            $notifyData['linked_profile']	    = !empty($post_type) && $post_type === 'freelancers' ? $freelancer_profile_id : $employer_profile_id;
            $notifyData['user_type']		    = $post_type;
            $notifyData['post_data']		    = $notifyDetails;
            do_action('workreap_notification_message', $notifyData );
            if(!empty($post_type) && $post_type === 'freelancers'){
                $notifyDetails['order_amount']  	= $wallet_amount;
                $notifyData['post_data']		    = $notifyDetails;
                $notifyData['type']		            = 'employer_cancelled_refunded';
                $notifyData['receiver_id']		    = $employer_id;
                $notifyData['linked_profile']	    = $employer_profile_id;
                $notifyData['user_type']		    = 'employers';
                do_action('workreap_notification_message', $notifyData );
            } else if(!empty($post_type) && $post_type === 'employers'){
                $notifyDetails['order_amount']  	= $order_total;
                $notifyData['post_data']		    = $notifyDetails;
                $notifyData['type']		            = 'freelancer_cancelled_refunded';
                $notifyData['receiver_id']		    = $freelancer_id;
                $notifyData['linked_profile']	    = $freelancer_profile_id;
                $notifyData['user_type']		    = 'freelancers';
                do_action('workreap_notification_message', $notifyData );
            }
            
           wp_update_post(array(
               'ID'    	    =>  intval($dispute_id),
               'post_status'   =>  $post_status
           ));

           update_post_meta($dispute_id, 'winning_party', $user_id);
           update_post_meta($dispute_id, 'dispute_status', 'resolved');
           update_post_meta($dispute_id, 'resolved_by', 'admin');

            /* Send Email on task canceled */
            if(class_exists('Workreap_Email_helper')){

                if(class_exists('WorkreapDisputeStatuses')){
                    $login_url           = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
                    /* set data for email */
                    $task_name           = get_the_title($task_id);
                    $task_link           = get_permalink( $task_id );
                    /* getting freelancer name and email */
                    $freelancer_id           = get_post_field( 'post_author', $task_id );
                    $freelancer_profile_id   = workreap_get_linked_profile_id($freelancer_id);
                    $freelancer_name 		 = workreap_get_username($freelancer_profile_id);
                    $freelancer_email 	      = get_userdata( $freelancer_id )->user_email;

                    /* getting employer name */
                    $employer_profile_id   = workreap_get_linked_profile_id($employer_id);
                    $employer_name         = workreap_get_username($employer_profile_id);
                    $employer_email        = get_userdata( $employer_id )->user_email;

                    $emailData = array();
                    $emailData['task_name']            = $task_name;
                    $emailData['task_link']            = $task_link;
                    $emailData['order_id']             = $order_id;
                    $emailData['order_amount']         = $order_total;
                    $emailData['login_url']            = $login_url;
                    $emailData['notification_type']    = 'noty_dispute_resolved';
                    $emailData['sender_id']            = $freelancer_id; //freelancer id
                    $emailData['receiver_id']          = $employer_id; //employer id
                    $email_helper = new WorkreapDisputeStatuses();
                    
                    if( $user_id == $freelancer_id ) {
                        $emailData['freelancer_email']         = $freelancer_email;
                        $emailData['freelancer_name']          = $freelancer_name;
                        $email_helper->dispute_freelancer_resolved($emailData);
                    } else {
                        $emailData['freelancer_email']         = $freelancer_email;
                        $emailData['freelancer_name']          = $freelancer_name;
                        $email_helper->dispute_freelancer_cancelled($emailData);
                    }

                    if( $user_id == $employer_id ) {
                        $emailData['employer_email']         = $employer_email;
                        $emailData['employer_name']          = $employer_name;
                        $email_helper->dispute_employer_resolved($emailData);
                    } else {
                        $emailData['employer_email']         = $employer_email;
                        $emailData['employer_name']          = $employer_name;
                        $email_helper->dispute_employer_cancelled($emailData);
                    }

                    do_action('noty_push_notification', $emailData);
                }
            }

            $proposal_id   = get_post_meta( $dispute_id, '_proposal_id', true );
            $proposal_id   = !empty($proposal_id) ? intval($proposal_id) : 0;
            if(!empty($proposal_id)){
                do_action( 'workreap_completed_job_badge', $freelancer_profile_id,'' );
            } elseif(!empty($order_id)){
                $payment_type   = get_post_meta($order_id, 'payment_type',true);
                if(!empty($payment_type) && $payment_type === 'tasks'){
                    do_action('workreap_completed_task_badge', $freelancer_profile_id,$order_id,'');
                }
            }

            $json['type']		    = 'success';
            $json['message']         = esc_html__('Woohoo!', 'workreap' );
            $json['post_status']		= $post_status;
            $json['message_desc']    = esc_html__('Dispute has been resolved', 'workreap' );
            wp_send_json( $json );
       } else {
           $json['type']		    = 'error';
           $json['message']         = esc_html__('Oops!', 'workreap' );
           $json['message_desc']    = esc_html__('Something wrong! please try it again.', 'workreap' );
           wp_send_json( $json );
       }

   }
   add_action('wp_ajax_workreap_resolve_dispute', 'workreap_resolve_dispute');
}

/**
 * Reject task
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_rejected_task')) {
    function workreap_rejected_task()
    {
        global $workreap_settings;

        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        $json               = array();
        $json['message']    = esc_html__('Task rejected','workreap');

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $post_id        = !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $feedback        = !empty($_POST['feedback']) ? $_POST['feedback'] : '';

        $service_status             = !empty( $workreap_settings['service_status'] ) ? $workreap_settings['service_status'] : 'publish';
        $resubmit_service_status    = !empty($workreap_settings['resubmit_service_status']) ? $workreap_settings['resubmit_service_status'] : 'no';


        if( empty($post_id) || !is_admin() ){
            $json['type']           = 'error';
	        $json['message']   = esc_html__('Oops!', 'workreap');
			$json['message_desc']   = esc_html__('You are not allowed to perfom this action', 'workreap');
			wp_send_json( $json );
        }

        wp_update_post(array(
            'ID'    	    =>  intval($post_id),
            'post_status'   =>  'rejected'
        ));
        
        if( !empty($service_status) && $service_status === 'pending' && !empty($resubmit_service_status) && $resubmit_service_status === 'yes'){
            update_post_meta( $post_id, '_post_task_status', 'rejected' );
        }

        /* gather email data */
        $freelancer_id          = get_post_field( 'post_author', $post_id );
        $freelancer_profile_id  = workreap_get_linked_profile_id($freelancer_id);
        $freelancer_name 		    = workreap_get_username($freelancer_profile_id);
        $freelancer_email 	    = get_userdata( $freelancer_id )->user_email;

        if (class_exists('Workreap_Email_helper')) {
            $emailData = array();
            $emailData['freelancer_name']       = $freelancer_name;
            $emailData['freelancer_email']      = $freelancer_email;
            $emailData['task_name']         = get_the_title($post_id);
            $emailData['task_link']         = get_permalink( $post_id );
            $emailData['admin_feedback']    = $feedback;
            update_post_meta( $post_id, '_rejection_reason', $feedback );

            if($workreap_settings['email_task_rej_freelancer'] == true){
                if (class_exists('WorkreapTaskStatuses')) {
                    $email_helper = new WorkreapTaskStatuses();
                    $email_helper->reject_task_freelancer_email($emailData);
                }
            }
            
            $notifyData						= array();
            $notifyDetails					= array();
            $notifyDetails['task_id']     = $post_id;
            $notifyDetails['post_link_id']= $post_id;
            $notifyDetails['admin_feedback']= $feedback;
            $notifyDetails['freelancer_id']   = $freelancer_profile_id;
            $notifyData['receiver_id']		= $freelancer_id;
            $notifyData['type']			    = 'task_rejected';
            $notifyData['linked_profile']	= $freelancer_profile_id;
            $notifyData['user_type']		= 'freelancers';
            $notifyData['post_data']		= $notifyDetails;
            do_action('workreap_notification_message', $notifyData );
        }

        $json['type']           = 'success';
        $json['message']   = esc_html__('Oops!', 'workreap');
        $json['message_desc']   = esc_html__('Task has been rejected', 'workreap');
        wp_send_json($json);

    }
    add_action('wp_ajax_workreap_rejected_task', 'workreap_rejected_task');
}

/**
 * Approved task
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_publish_task')) {
    function workreap_publish_task()
    {
        global $workreap_settings;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        
        $json               = array();
        $json['message']    = esc_html__('Task approved','workreap');

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $post_id        = !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if( empty($post_id) || !is_admin() ){
            $json['type']           = 'error';
			$json['message_desc']   = esc_html__('You are not allowd to perfom this action', 'workreap');
			wp_send_json( $json );
        }

        wp_update_post(array(
            'ID'    	    =>  intval($post_id),
            'post_status'   =>  'publish'
        ));

      /* gather email data */
      $freelancer_id          = get_post_field( 'post_author', $post_id );
      $freelancer_profile_id  = workreap_get_linked_profile_id($freelancer_id);
      $freelancer_name 		    = workreap_get_username($freelancer_profile_id);
      $freelancer_email 	    = get_userdata( $freelancer_id )->user_email;

      if (class_exists('Workreap_Email_helper')) {
        $blogname = get_option( 'blogname' );
        $emailData = array();
        $emailData['freelancer_name']       = $freelancer_name;
        $emailData['freelancer_email']      = $freelancer_email;
        $emailData['task_name']         = get_the_title($post_id);
        $emailData['task_link']         = get_permalink( $post_id );

        if($workreap_settings['email_task_rej_freelancer'] == true){

          if (class_exists('WorkreapTaskStatuses')) {
            $email_helper = new WorkreapTaskStatuses();
            $email_helper->approved_task_freelancer_email($emailData);
            do_action('notification_message', $emailData );
          }
          $notifyData					= array();
          $notifyDetails				= array();
          $notifyDetails['task_id']     = $post_id;
          $notifyDetails['post_link_id']= $post_id;
          $notifyDetails['freelancer_id']   = $freelancer_profile_id;
          $notifyData['receiver_id']	= $freelancer_id;
          $notifyData['type']			= 'task_approved';
          $notifyData['linked_profile']	= $freelancer_profile_id;
          $notifyData['user_type']		= 'freelancers';
          $notifyData['post_data']		= $notifyDetails;
          do_action('workreap_notification_message', $notifyData );
        }

      }

        $json['type']           = 'success';
        $json['message_desc']   = esc_html__('Task has been approved', 'workreap');
        wp_send_json($json);

    }
    add_action('wp_ajax_workreap_publish_task', 'workreap_publish_task');
}

/**
 * Approved project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_publish_project')) {
    function workreap_publish_project()
    {
        global $workreap_settings;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        $json               = array();
        $json['message']    = esc_html__('Project published','workreap');

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $post_id        = !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if( empty($post_id) || !is_admin() ){
            $json['type']           = 'error';
			$json['message_desc']   = esc_html__('You are not allowd to perfom this action', 'workreap');
			wp_send_json( $json );
        }

        wp_update_post(array(
            'ID'    	    =>  intval($post_id),
            'post_status'   =>  'publish'
        ));

        $gmt_time		           = current_time( 'mysql', 1 );
        update_post_meta( $post_id, '_post_project_status','publish' );
        update_post_meta( $post_id, '_publish_datetime', $gmt_time );

        // Notification to employer for task publish
        $employer_id                           = get_post_field( 'post_author', $post_id );
        $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
        $notifyDetails                      = array();
        $notifyDetails['project_id']  	    = $post_id;
        $notifyData['post_data']		    = $notifyDetails;
        $notifyData['type']		            = 'approve_project';
        $notifyData['receiver_id']		    = $employer_id;
        $notifyData['linked_profile']	    = $employer_profile_id;
        $notifyData['user_type']		    = 'employers';
        do_action('workreap_notification_message', $notifyData );
        
        /* Email on project approved */
        $project_approve_switch        = !empty($workreap_settings['email_project_approve']) ? $workreap_settings['email_project_approve'] : true;
        if(class_exists('Workreap_Email_helper') && !empty($project_approve_switch)){
            $emailData                      = array();
			$emailData['employer_email']        = get_userdata($employer_id)->user_email;
            $emailData['employer_name']        = workreap_get_username($employer_profile_id);
            $emailData['project_title']     = get_the_title($post_id);
            $emailData['project_link']      = get_the_permalink($post_id);
            if (class_exists('WorkreapProjectCreation')) {
				$email_helper = new WorkreapProjectCreation();
				$email_helper->approved_project_employer_email($emailData);
			}
        }

        $project_meta       = get_post_meta( $post_id, 'wr_project_meta',true );
        $invitation         = !empty($project_meta['invitation']) ? $project_meta['invitation'] : array();
        if( !empty($invitation) ){
            foreach($invitation as $profile_id => $value ){
                $status = !empty($status) ? $status : '';
                if( empty($status) || $status === 'pending'){
                    workreapFreelancerProjectInvitation($post_id,$profile_id);
                }
            }
        }
        
        $json['type']           = 'success';
        $json['message_desc']   = esc_html__('Project has been approved and public for the freelancer', 'workreap');
        wp_send_json($json);

    }
    add_action('wp_ajax_workreap_publish_project', 'workreap_publish_project');
}

/**
 * Reject task
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_rejected_project')) {
    function workreap_rejected_project()
    {
        global $workreap_settings;

        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        $json               = array();
        $json['message']    = esc_html__('Project rejection','workreap');

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $post_id                    = !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $feedback                   = !empty($_POST['feedback']) ? $_POST['feedback'] : '';
        $project_status             = !empty($workreap_settings['project_status']) ? $workreap_settings['project_status'] : '';
        $resubmit_project_status    = !empty($workreap_settings['resubmit_project_status']) ? $workreap_settings['resubmit_project_status'] : 'no';
        $reject_email_switch        = !empty($workreap_settings['email_project_rej_employer']) ? $workreap_settings['email_project_rej_employer'] : true;


        if( empty($post_id) || !is_admin() ){
            $json['type']           = 'error';
			$json['message_desc']   = esc_html__('You are not allowd to perfom this action', 'workreap');
			wp_send_json( $json );
        }

        wp_update_post(array(
            'ID'    	    =>  intval($post_id),
            'post_status'   =>  'rejected'
        ));
        if( !empty($project_status) && $project_status === 'pending' && !empty($resubmit_project_status) && $resubmit_project_status === 'yes'){
            update_post_meta( $post_id, '_post_project_status', 'rejected' );
        }

        $employer_id                           = get_post_field( 'post_author', $post_id );
        $employer_profile_id                   = !empty($employer_id) ? workreap_get_linked_profile_id($employer_id, '', 'employers') : '';
        // Notification employer

        $notifyDetails                      = array();
        $notifyDetails['project_id']  	    = $post_id;
        $notifyDetails['admin_feedback']  	= $feedback;
        $notifyData['post_data']		    = $notifyDetails;
        $notifyData['type']		            = 'rejected_project';
        $notifyData['receiver_id']		    = $employer_id;
        $notifyData['linked_profile']	    = $employer_profile_id;
        $notifyData['user_type']		    = 'employers';
        do_action('workreap_notification_message', $notifyData );

        /* Email to employer */
        if(class_exists('Workreap_Email_helper') && !empty($reject_email_switch)){
            $emailData                      = array();
            $emailData['employer_email']       = get_userdata($employer_id)->user_email;
            $emailData['employer_name']        = workreap_get_username($employer_profile_id);
            $emailData['project_title']     = get_the_title($post_id );
            $emailData['project_link']      = get_the_permalink($post_id);
            if (class_exists('WorkreapProjectCreation')) {
				$email_helper = new WorkreapProjectCreation();
				$email_helper->reject_project_employer_email($emailData);
			}
        }
        
        $json['type']           = 'success';
        $json['message_desc']   = esc_html__('Project has been rejected', 'workreap');
        wp_send_json($json);

    }
    add_action('wp_ajax_workreap_rejected_project', 'workreap_rejected_project');
}

/**
 * Remove task
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_remove_task')) {
    function workreap_remove_task()
    {
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        $json               = array();
        $json['message']    = esc_html__('Remove task','workreap');

        if (function_exists('workreap_verify_admin_token')) {
            workreap_verify_admin_token($_POST['security']);
        }

        $post_id        = !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if( empty($post_id) || !is_admin() ){
            $json['type']           = 'error';
			$json['message_desc']   = esc_html__('You are not allowd to perfom this action', 'workreap');
			wp_send_json( $json );
        }

        wp_trash_post($post_id);

        $json['type']           = 'success';
        $json['message_desc']   = esc_html__('Task has been removed successfully', 'workreap');
        wp_send_json($json);

    }
    add_action('wp_ajax_workreap_remove_task', 'workreap_remove_task');
}

/**
 * Change color
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
require_once(WORKREAP_DIRECTORY . '/libraries/scssphp/scss.inc.php');
if (!function_exists('workreap_change_colors')) {
    function  workreap_change_colors(){
        global $workreap_settings;
        $primary_color      =  !empty($workreap_settings['wr_primary_color']) ? $workreap_settings['wr_primary_color'] : '';
        $secondary_color    =  !empty($workreap_settings['wr_secondary_color']) ? $workreap_settings['wr_secondary_color'] : '';
        $tertiary_color     =  !empty($workreap_settings['wr_tertiary_color']) ? $workreap_settings['wr_tertiary_color'] : '';
        
        $compiler = new ScssPhp\ScssPhp\Compiler();
        $source_scss    = WORKREAP_DIRECTORY . '/public/scss/style.scss';
        $scssContents   = file_get_contents($source_scss);
        $import_path    = WORKREAP_DIRECTORY . '/public/scss';
        $compiler->addImportPath($import_path);
        $target_css = WORKREAP_DIRECTORY . '/public/css/style.css';
        $variables = [
            '$theme-color'          => $primary_color,
            '$theme-color-dark'     => $tertiary_color,
            '$secondary-color'      => $secondary_color,
        ];
        $compiler->setVariables($variables);

        $css = $compiler->compile($scssContents);
        if (!empty($css) && is_string($css)) {
            file_put_contents($target_css, $css);
        }
        $json                   = array();
        $json['type']           = 'success';
        $json['message']        = esc_html__('Workreap colors', 'workreap');
        $json['message_desc']   = esc_html__('Your site is successfully update workreap colors', 'workreap');
        wp_send_json($json);
    }
    add_action('wp_ajax_workreap_change_colors', 'workreap_change_colors');
}