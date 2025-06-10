<?php
require WORKREAP_DIRECTORY.'libraries/vendor/autoload.php';
use Dompdf\Dompdf;

/**
 * Task PDF
 *
 */
if( !function_exists('WorkreapEmployerServicePDF') ){
    function WorkreapEmployerServicePDF($order_id = '',$user_id='',$type='employers') {
        if(!empty($order_id)) {

            $dompdf             = new Dompdf();
            $args               = array();
            $args['identity']   = $user_id;
            $args['option']     = 'pdf';
            $args['order_id']   = $order_id;
            ob_start();
            ?>
                <style scoped>
                    *, *::after, *::before {
                        margin: 0px;
                        padding: 0px;
                        box-sizing: border-box;
                    }
                    .wr-printable {
                        border: 1px solid #eee;
                        padding: 30px;
                        background: #fff;
                    }
                    body {
                        color: #0A0F26;
                        font-size: 14px;
                        line-height: 26px;
                        font-family: "DejaVu Sans", sans-serif;
                    }
                    .wr-invoicebill {
                        display: flex;
                        justify-content: space-between;
                        width: 100%;
                    }
                    .wr-invoicebill figure{
                        width: 50%;
                        margin: 0;
                        display: inline-block;
                        vertical-align: middle;
                    }
                    .wr-billno{
                        margin-left: -5px;
                        width: 50%;
                        text-align: right;
                        display: inline-block;
                    }
                    .wr-billno h3 {
                        margin: 0;
                        color: #ff5851;
                        font-size: 36px;
                        line-height: 37px;
                    }
                    .wr-billno span {
                        font-size: 18px;
                        font-weight: 700;
                        line-height: 22px;
                    }
                    .wr-tasksinfos {
                        margin: 29px 0 0;
                        width: 100%;
                    }

                    .wr-invoicetasks{
                        vertical-align: middle;
                        display: inline-block;
                    }
                    .wr-invoicetasks h5 {
                        margin: 0;
                        font-size: 18px;
                        font-weight: 400;
                        line-height: 26px;
                    }
                    .wr-invoicetasks h3 {
                        font-size: 18px;
                        font-weight: 700;
                        line-height: 24px;
                        margin: 0;
                    }
                    .wr-invoicefromto {
                        padding: 37px 0;
                        margin-top: 32px;
                        border-top: 1px solid #eee;
                    }
                    .wr-fromreceiver {
                        width: 50%;
                        display: inline-block;
                        vertical-align: middle;
                    }
                    .wr-fromreceiver + .wr-fromreceiver{
                        margin-left: -5px;
                        margin-top: -20px;
                    }
                    .wr-fromreceiver h5 {
                        margin: 0 0 10px;
                        font-size: 16px;
                        line-height: 20px;
                    }
                    .wr-fromreceiver span {
                        font-size: 14px;
                        display: block;
                        color: #676767;
                        line-height: 20px;
                    }
                    .wr-tasksdates{
                        float: right;
                        text-align: right;
                        display: inline-block;
                        vertical-align: middle;
                    }
                    .wr-tasksdates span{
                        font-size: 14px;
                        line-height: 20px;
                    }
                    .wr-tasksdates span em {
                        font-style: normal;
                    }
                    .wr-invoice-table.wr-table {
                        margin: 0;
                        border: 0;
                        width: 100%;
                        max-width: 100%;
                        border-collapse: collapse;
                        background-color: #fff;
                    }
                    .wr-invoice-table.wr-table > thead {
                        border-top: 1px solid #eee;
                    }
                    tbody, td, tfoot, th, thead, tr {
                        border-color: inherit;
                        border-style: solid;
                        border-width: 0;
                    }
                    .wr-invoice-table.wr-table > thead > tr {
                        border: 0;
                        border-bottom: 1px solid #eee;
                    }
                    .wr-invoice-table.wr-table > thead > tr > th{
                        border: 0;
                        color: #0A0F26;
                        text-align: left;
                        background: #fff;
                        font-size: 14px;
                        font-weight: 700;
                        line-height: 28px;
                        padding: 17px 28px;
                    }
                    .wr-invoice-table.wr-table > tbody > tr {
                        border: 0;
                    }
                    .wr-invoice-table.wr-table > tbody > tr td {
                        line-height: 21px;
                        text-align: left;
                        background: #fff;
                        color: #676767;
                        font-size: 14px;
                        vertical-align: bottom;
                        padding: 24px 28px 23px 28px;
                    }
                    .wr-tablelistv2{
                        top: 20px;
                        margin-top: 6px;
                        position: relative;
                    }
                    .wr-invoice-table.wr-table > tbody > tr:first-child td {
                        vertical-align: top;
                        padding-bottom: 10px;
                    }
                    .wr-tablelist {
                        padding: 0;
                        margin: 3px 0 0;
                        list-style: none;
                    }
                    .wr-tablelist li {
                        font-size: 14px;
                        line-height: 20px;
                        position: relative;
                        list-style-type: none;
                        padding: 0;
                        color: #353648;
                        margin-top: 3px;
                        padding: 0 0 0 10px;
                    }
                    .wr-tablelist li::after {
                        left: 0;
                        top: -5px;
                        content: ".";
                        color: #676767;
                        font-size: 19px;
                        position: absolute;
                    }
                    .wr-invoice-table.wr-table > tbody > tr td h6 {
                        left: 0;
                        margin: 0;
                        bottom: -22px;
                        color: #0A0F26;
                        position: relative;
                        letter-spacing: 0.5px;
                        font-weight: 700;
                        font-size: 16px;
                        line-height: 20px;
                    }
                    .wr-subtotal {
                        width: 100%;
                        text-align: right;
                        margin: 22px 0 35px;
                        border-top: 1px solid #eee;
                        padding: 25px 0 0;
                    }
                    .wr-subtotalbill {
                        width: 100%;
                        margin: 0;
                        display: inline-block;
                        list-style: none;
                        max-width: 350px;
                        padding: 0 20px 0 30px;
                    }
                    .wr-subtotalbill li {
                        width: 100%;
                        color: #0A0F26;
                        display: block;
                        padding: 0 0 10px;
                        list-style-type: none;
                        font-size: 14px;
                        text-align: left;
                        line-height: 20px;
                    }
                    .wr-subtotalbill li h6 {
                        margin: 0;
                        float: right;
                        color: #0A0F26;
                        letter-spacing: 0.5px;
                        display: inline-block;
                        font-size: 16px;
                        line-height: 20px;
                        font-weight: 700;
                    }
                    .wr-sumtotal {
                        text-align: left;
                        min-width: 350px;
                        padding: 14px 20px 14px 30px;
                        background: #ff5851;
                        border-radius: 4px;
                        margin-top: 14px;
                        list-style-type: none;
                        display: inline-block;
                        color: #1C1C1C;
                        font-size: 14px;
                        line-height: 20px;
                        font-weight: 700;
                    }
                    .wr-sumtotal h6 {
                        margin: 0;
                        float: right;
                        color: #1C1C1C;
                        font-size: 18px;
                        font-size: 18px;
                        line-height: 22px;
                    }
                    .wr-description{
                        font-size: 14px;
                        line-height: 18px;
                    }
                    .wr-invoice-table.wr-table > tbody > tr + tr td:first-child{
                        padding-top: 28px;
                        vertical-align: top;
                        padding-bottom: 28px;
                    }
                    .wr-tags span, .wr-tags a {
                        color: #fff;
                        padding: 0 10px;
                        font-size: 12px;
                        line-height: 26px;
                        border-radius: 3px;
                        background: #FF9E2B;
                        letter-spacing: 0.5px;
                        display: inline-block;
                        vertical-align: middle;
                    }
                    .bg-complete {
                       background: #63d594 !important;
                    }
                </style>
            <?php
			$order_type = get_post_meta( $order_id, 'project_type',true );

            if( !empty($type) && $type === 'employers'){
				if( !empty($order_type) && $order_type === 'hourly' ){
					do_action( 'workreap_employer_invoice_details', $args );
				} else {
					workreap_get_template_part('dashboard/dashboard', 'invoice-detail',$args);
				}

            } else if( !empty($type) && $type === 'freelancers'){
				if( !empty($order_type) && $order_type === 'hourly' ){
					do_action( 'workreap_freelancer_invoice_details', $args );
				} else {
                	workreap_get_template_part('dashboard/dashboard', 'freelancer-invoice-detail',$args);
				}
            }
            $output_html   = ob_get_clean();
            $dompdf->loadHtml($output_html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $upload             = wp_upload_dir();
            $upload_dir         = $upload['basedir'];
            $upload_rel_dir     = $upload['baseurl'] . '/invoices/';
            $upload_dir         = $upload_dir . '/invoices/';


            //create directory if not exists
            if (!is_dir($upload_dir)) {
                wp_mkdir_p($upload_dir);
            }

            $filename   = rand(100,2500).$order_id.date('Y-m-d-H-i-s').'.pdf';
            $file_name  = $upload_dir.$filename;
            $file_url   = $upload_rel_dir.$filename;
            ob_end_flush();

            $pdf_gen = $dompdf->output();

            if (!file_put_contents($file_name, $pdf_gen)) {
                return true;
            } else {

                return array(
                    'file_path' => $file_name,
                    'file_url'  => $file_url
                );
            }
        }
    }
}

/**
 * Project proposal basic
 */
if( !function_exists('workreapGetProposalBasic') ){
    function workreapGetProposalBasic( $proposal_id=0 ,$type='', $user_id=0){
		global $paged;
		$proposal_details	= array();
		$project_id			= get_post_meta( $proposal_id, 'project_id',true );
		$project_id			= !empty($project_id) ? intval($project_id) : 0;
		$proposal_data		= get_post_meta( $proposal_id, 'proposal_meta',true );
		$proposal_status	= get_post_status( $proposal_id );
		$project_price		= workreap_get_project_price($project_id);

		$proposal_price		= isset($proposal_data['price']) ? $proposal_data['price'] : 0;
		$proposal_type      = !empty($proposal_data['proposal_type']) ? $proposal_data['proposal_type'] : '';
		$price_options		= isset($proposal_price) ? workreap_commission_fee($proposal_price,'return') : array();
		$proposal_meta		= get_post($proposal_id);

		$proposal_details['rating_details']			= array();
		$proposal_details['price_format']  			= isset($proposal_data['price']) ? workreap_price_format($proposal_data['price'],'return') : '';
		$proposal_details['price']  				= isset($proposal_data['price']) ? $proposal_data['price'] : '';
		$proposal_details['proposal_type']  		= !empty($proposal_data['proposal_type']) ? $proposal_data['proposal_type'] : '';
		if( !empty($proposal_type) && $proposal_type === 'milestone'){
			$milestone              = !empty($proposal_data['milestone']) ? $proposal_data['milestone'] : array();
			$mileastone_array       = array();
			$completed_mil_array    = array();
			$hired_milestone        = array();
			$requested_milestone    = array();

			$hired_balance      = 0;
			$earned_balance     = 0;
			$remaning_balance   = 0;
			$milestone_total    = 0;
			if( !empty($milestone) ){
				foreach($milestone as $key => $value ){
					$status = !empty($value['status']) ? $value['status'] : '';
					$price  = !empty($value['price']) ? $value['price'] : 0;

					$value['price_format'] 	= workreap_price_format($price,'return') ;
					$milestone_total    	= $milestone_total  + $price;

					if( !empty($status) && $status === 'hired'){
						$hired_balance = $hired_balance + $price;
						$hired_milestone[$key] = $value;
					} else if( !empty($status) && $status === 'completed'){
						$earned_balance = $earned_balance + $price;
						$completed_mil_array[$key] = $value;

					} else if( !empty($status) && $status === 'requested'){
						$requested_milestone[$key] = $value;
						$hired_balance       = $hired_balance + $price;
					} else {
						$mileastone_array[$key] = $value;
						$remaning_balance       = $remaning_balance + $price;
					}

				}
				if( !empty($milestone_total) && $milestone_total == $earned_balance ){
					$proposal_details['complete_option']         = 'yes';
				}
				$requested_milestone    = array_merge($requested_milestone,$hired_milestone);
				$mileastone_array       = array_merge($requested_milestone,$mileastone_array);

				$proposal_details['earned_balance']         = $earned_balance;
				$proposal_details['hired_balance']          = $hired_balance;
				$proposal_details['remaning_balance']       = $remaning_balance;
				$proposal_details['completed_mil_array']    = $completed_mil_array;
				$proposal_details['milestone_total']        = $milestone_total;

				$proposal_details['earned_balance_format']         = workreap_price_format($earned_balance,'return');
				$proposal_details['hired_balance_format']          = workreap_price_format($hired_balance,'return');
				$proposal_details['remaning_balance_format']       = workreap_price_format($remaning_balance,'return');
				$proposal_details['mileastone_array_format']       = workreap_price_format($mileastone_array,'return');
				$proposal_details['completed_mil_array_format']    = workreap_price_format($completed_mil_array,'return');
				$proposal_details['milestone_total_format']        = workreap_price_format($milestone_total,'return');
			}
			$proposal_details['milestone']  		= $mileastone_array;

		} else if( empty($proposal_type) || (!empty($proposal_type) && $proposal_type === 'fixed')){
			$proposal_details['complete_option']         = 'yes';
		}
		if( !empty($proposal_status) && $proposal_status === 'completed' ){
			$rating_id      	= get_post_meta( $proposal_id, '_rating_id', true );
			if( !empty($rating_id) ){
				$rating         = !empty($rating_id) ? get_comment_meta($rating_id, 'rating', true) : 0;
				$rating			= !empty($rating) ? number_format((float)$rating, 1, '.', '') : 0;
				$title          = !empty($rating_id) ? get_comment_meta($rating_id, '_rating_title', true) : '';
				$comment_detail = !empty($rating_id) ? get_comment($rating_id) : array();
				$content        = !empty($comment_detail->comment_content) ? $comment_detail->comment_content : '';
				$proposal_details['rating_details']['content']	= $content;
				$proposal_details['rating_details']['rating']	= $rating;
				$proposal_details['rating_details']['title']	= $title;

			}
		}
		$format_date      = get_option('date_format') . ' ' . get_option('time_format');
		$proposal_date 							= !empty($proposal_meta->post_date) ? date_i18n( $format_date, strtotime(get_the_date($proposal_meta->post_date)) ) : '';
		$proposal_details['proposal_date']		= esc_html($proposal_date);
		$proposal_details['proposal_meta']		= !empty($proposal_meta) ? $proposal_meta : array();
		$proposal_details['proposal_status']	= $proposal_status;
		$proposal_details['proposal_id']		= intval($proposal_id);
		$proposal_details['freelancer_id']      	= (int)get_post_field( 'post_author', $proposal_id );
		$proposal_details['employer_id']       	= (int)get_post_field( 'post_author', $project_id );

		/* get author of dispute */
		$dispute_id     					= get_post_meta( $proposal_id, 'dispute_id', true);
		$dispute_id     					= !empty($dispute_id) ? $dispute_id : 0;
		$dispute_author_id    				= !empty($dispute_id) ? get_post_field( 'post_author', $dispute_id ) : 0;
		$proposal_details['dispute_id'] 	= $dispute_id;
		$proposal_details['dispute_type'] 	= '';
		$proposal_details['dispute_author'] = '';
		if(!empty($dispute_author_id)){
			$proposal_details['dispute_author'] = (int)$dispute_author_id;
				$proposal_details['dispute_type'] = workreap_dispute_status($dispute_id);
		}

		/* Dispute messages for employer */
		$dispute_messages = workreap_project_dispute_messages( $project_id, $proposal_id, $dispute_id, $user_id );
		$proposal_details['dispute_messages'] = !empty($dispute_messages) ? $dispute_messages : array();

		$proposal_details['proposal_price_formate']		= workreap_price_format($proposal_price,'return');
		$proposal_details['admin_shares_formate']		= isset($price_options['admin_shares']) ? workreap_price_format($price_options['admin_shares'],'return') : '';
		$proposal_details['freelancer_shares_formate']		= isset($price_options['freelancer_shares']) ? workreap_price_format($price_options['freelancer_shares'],'return') : '';
		if( !empty($type) && $type==='detail' ){
			$user_id     							= get_post_field( 'post_author', $proposal_id );
			$linked_profile_id  					= workreap_get_linked_profile_id($user_id, '','freelancers');
			$proposal_details['freelancer_detail']		= workreap_get_user_basic($linked_profile_id,$user_id);
			$proposal_details['project_detail']		= workreapProjectDetails($project_id);

			$user_rating            				= get_post_meta( $linked_profile_id, 'wr_total_rating', true );
			$review_users           				= get_post_meta( $linked_profile_id, 'wr_review_users', true );

			$proposal_details['freelancer_detail']['user_rating']	= isset($user_rating) ? $user_rating : '';
			$proposal_details['freelancer_detail']['review_users']	= isset($review_users) ? $review_users : '';

			$args   = array(
				'post_id'       => $proposal_id,
				'orderby'       => 'date',
				'order'         => 'ASC',
				'hierarchical' 	=> 'threaded',
				'type'			=> 'activity_detail'
			);
			$comments 			= get_comments( $args );
			$proposal_comments	= array();
			if (isset($comments) && !empty($comments)){
				foreach ($comments as $key => $value) {
					$comment_children 	= array();
					$comment_children 	= $value->get_children();
					$commentsData		= workreap_get_chat_history($value,$user_id);

					if (!empty($comment_children)){
						foreach ($comment_children as $comment_child){
							$commentsChildData		= workreap_get_chat_history($comment_child,$user_id);
							$commentsData['child']	= $commentsChildData;
						}
					}
					$proposal_comments[]	= $commentsData;
				}
			}
			$invoices_list		= array();
			$date_format    	= get_option( 'date_format' );
			$time_format    	= get_option( 'time_format' );
			$current_page   	= $paged;
			$order_arg  = array(
				'paginate'      => true,
				'limit'         => -1,
				'proposal_id'   => $proposal_id
			);
			$customer_orders = wc_get_orders( $order_arg );
			if (!empty($customer_orders->orders)) {
				foreach ($customer_orders->orders as $order) {
					$invoice_data	= array();
					$invoice_title  = "";
					$milestone_id   = '';

					$invoice_status = get_post_meta( $order->get_id(),'_task_status', true );
					$product_data   = get_post_meta( $order->get_id(),'cus_woo_product_data', true );
					$project_type   = !empty($product_data['project_type']) ? $product_data['project_type'] : '';
					$freelancer_price	= !empty($product_data['freelancer_shares']) ? $product_data['freelancer_shares'] : "";
					$invoice_price      = $order->get_total();
					if(function_exists('wmc_revert_price')){
						$invoice_price =  wmc_revert_price($order->get_total(),$order->get_currency());
					}
					if( !empty($project_type) && $project_type === 'fixed' ){
						$milestone_id   = !empty($product_data['milestone_id']) ? $product_data['milestone_id'] : "";
						if( !empty($milestone_id)){
							$invoice_title  = !empty($proposal_details['milestone'][$milestone_id]['title']) ? $proposal_details['milestone'][$milestone_id]['title'] : "";
						} else if( empty($milestone_id) ){
							$project_id   = !empty($product_data['project_id']) ? $product_data['project_id'] : "";
							if( !empty($project_id) ){
								$invoice_title  = get_the_title( $project_id );
							}
						}
					} else {
						$invoice_title  = apply_filters( 'workreap_filter_invoice_title', $order->get_id() );
					}
					$invoice_data['data_created']	= wc_format_datetime( $order->get_date_created(), $date_format . ', ' . $time_format );
					$invoice_data['invoice_status']	= !empty($invoice_status) ? $invoice_status : '';
					$invoice_data['invoice_status_title']	= !empty($invoice_status) ? apply_filters( 'workreap_proposal_invoice_status_tag',$invoice_status,true) : '';
					$invoice_data['invoice_title']	= $invoice_title;
					$invoice_data['freelancer_price']	= $freelancer_price;
					$invoice_data['order_id']		= $order->get_id();
					$invoice_data['employer_price']	= $invoice_price;

					$invoice_data['freelancer_price_format']	= workreap_price_format($freelancer_price,'return');
					$invoice_data['employer_price_format']		= workreap_price_format($invoice_price,'return');
					$invoices_list[]						= $invoice_data;
				}

			}
			$proposal_details['invoices_list']    		= $invoices_list;
			$proposal_details['proposal_comments']    	= $proposal_comments;
		} else if( !empty($type) && $type==='freelancer_detail' ){
			$user_id     							= get_post_field( 'post_author', $proposal_id );
			$linked_profile_id  					= workreap_get_linked_profile_id($user_id, '','freelancers');
			$user_rating            				= get_post_meta( $linked_profile_id, 'wr_total_rating', true );
			$review_users           				= get_post_meta( $linked_profile_id, 'wr_review_users', true );

			$proposal_details['freelancer_detail']					= workreap_get_user_basic($linked_profile_id,$user_id);
			$proposal_details['freelancer_detail']['user_rating']	= isset($user_rating) ? $user_rating : '';
			$proposal_details['freelancer_detail']['review_users']	= isset($review_users) ? $review_users : '';

			$args   = array(
				'post_id'       => $proposal_id,
				'orderby'       => 'date',
				'order'         => 'ASC',
				'hierarchical' 	=> 'threaded',
				'type'			=> 'activity_detail'
			);
			$comments 			= get_comments( $args );
			$proposal_comments	= array();
			if (isset($comments) && !empty($comments)){
				foreach ($comments as $key => $value) {
					$comment_children 	= array();
					$comment_children 	= $value->get_children();
					$commentsData		= workreap_get_chat_history($value,$user_id);

					if (!empty($comment_children)){
						foreach ($comment_children as $comment_child){
							$commentsChildData		= workreap_get_chat_history($comment_child,$user_id);
							$commentsData['child']	= $commentsChildData;
						}
					}
					$proposal_comments[]	= $commentsData;
				}
			}
			$invoices_list		= array();
			$date_format    	= get_option( 'date_format' );
			$time_format    	= get_option( 'time_format' );
			$current_page   	= $paged;
			$order_arg  = array(
				'paginate'      => true,
				'limit'         => -1,
				'proposal_id'   => $proposal_id
			);
			$customer_orders = wc_get_orders( $order_arg );
			if (!empty($customer_orders->orders)) {
				foreach ($customer_orders->orders as $order) {
					$invoice_data	= array();
					$invoice_title  = "";
					$milestone_id   = '';

					$invoice_status = get_post_meta( $order->get_id(),'_task_status', true );
					$product_data   = get_post_meta( $order->get_id(),'cus_woo_product_data', true );
					$project_type   = !empty($product_data['project_type']) ? $product_data['project_type'] : '';
					$freelancer_price	= !empty($product_data['freelancer_shares']) ? $product_data['freelancer_shares'] : "";
					$invoice_price      = $order->get_total();
					if(function_exists('wmc_revert_price')){
						$invoice_price =  wmc_revert_price($order->get_total(),$order->get_currency());
					}
					if( !empty($project_type) && $project_type === 'fixed' ){
						$milestone_id   = !empty($product_data['milestone_id']) ? $product_data['milestone_id'] : "";
						if( !empty($milestone_id)){
							$invoice_title  = !empty($proposal_details['milestone'][$milestone_id]['title']) ? $proposal_details['milestone'][$milestone_id]['title'] : "";
						} else if( empty($milestone_id) ){
							$project_id   = !empty($product_data['project_id']) ? $product_data['project_id'] : "";
							if( !empty($project_id) ){
								$invoice_title  = get_the_title( $project_id );
							}
						}
					} else {
						$invoice_title  = apply_filters( 'workreap_filter_invoice_title', $order->get_id() );
					}
					$invoice_data['data_created']	= wc_format_datetime( $order->get_date_created(), $date_format . ', ' . $time_format );
					$invoice_data['invoice_status']	= !empty($invoice_status) ? $invoice_status : '';
					$invoice_data['invoice_status_title']	= !empty($invoice_status) ? apply_filters( 'workreap_proposal_invoice_status_tag',$invoice_status,true) : '';
					$invoice_data['invoice_title']	= $invoice_title;
					$invoice_data['freelancer_price']	= $freelancer_price;
					$invoice_data['order_id']		= $order->get_id();
					$invoice_data['employer_price']	= $invoice_price;

					$invoice_data['freelancer_price_format']	= workreap_price_format($freelancer_price,'return');
					$invoice_data['employer_price_format']		= workreap_price_format($invoice_price,'return');
					$invoices_list[]						= $invoice_data;
				}

			}
			$proposal_details['invoices_list']    		= $invoices_list;
			$proposal_details['proposal_comments']    	= $proposal_comments;
		} else if( !empty($type) && $type==='projects_activity' ){
			$user_id     							= get_post_field( 'post_author', $proposal_id );
			$linked_profile_id  					= workreap_get_linked_profile_id($user_id, '','freelancers');
			$proposal_details['freelancer_detail']		= workreap_get_user_basic($linked_profile_id,$user_id);
			$proposal_details['project_detail']		= workreapProjectDetails($project_id,$type);

			$args   = array(
				'post_id'       => $proposal_id,
				'orderby'       => 'date',
				'order'         => 'ASC',
				'hierarchical' 	=> 'threaded',
				'type'			=> 'activity_detail'
			);
			$comments 			= get_comments( $args );
			$proposal_comments	= array();
			if (isset($comments) && !empty($comments)){
				foreach ($comments as $key => $value) {
					$comment_children 	= array();
					$comment_children 	= $value->get_children();
					$commentsData		= workreap_get_chat_history($value,$user_id);

					if (!empty($comment_children)){
						foreach ($comment_children as $comment_child){
							$commentsChildData		= workreap_get_chat_history($comment_child,$user_id);
							$commentsData['child']	= $commentsChildData;
						}
					}
					$proposal_comments[]	= $commentsData;
				}
			}
			$proposal_details['proposal_comments']    	= $proposal_comments;
		}
		return $proposal_details;
	}
}

/**
 * Project details
 *
 */
if( !function_exists('workreapProjectDetails') ){
    function workreapProjectDetails( $project_id=0,$type='', $user_id=0){
		$project_details	= array();
		$product            = wc_get_product($project_id);
		if( !empty($product) ){
			$product_author_id  = get_post_field ('post_author', $product->get_id());
			$linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','employers');
			$user_name          = workreap_get_username($linked_profile_id);
			$is_verified    	= !empty($linked_profile_id) ? get_post_meta( $linked_profile_id, '_is_verified',true) : '';
			$project_price      = workreap_get_project_price($product->get_id());
			$project_meta       = get_post_meta( $product->get_id(), 'wr_project_meta',true );
			$project_meta       = !empty($project_meta) ? $project_meta : array();
			$project_type       = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
			$userdata       	= get_userdata( $product_author_id );
			$registered_on     	= !empty($userdata->user_registered) ? $userdata->user_registered : '';
			$registered_date    = !empty( $registered_on ) ? date_i18n( get_option( 'date_format' ),  strtotime($registered_on)) : '';
			$avatar     = apply_filters(
				'workreap_avatar_fallback',
				workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile_id),
				array('width' => 100, 'height' => 100)
			);
			$post_status				= get_post_status( $product->get_id() );
			$publish_date				= get_post_meta( $product->get_id(), '_publish_datetime',true );
			$downloadable_doc 			= get_post_meta($project_id, '_downloadable_files', true);
			$selected_freelancers   	= !empty($product) ? get_post_meta( $product->get_id(), 'no_of_freelancers', true ) : '';
			$posted_project_count   	= workreap_get_user_projects($product_author_id);
			$hired_project_count    	= workreap_get_user_projects($product_author_id,'hired');
			$address        			= apply_filters( 'workreap_user_address', $linked_profile_id );
			$project_location_types     = workreap_project_location_type();
			$selected_location          = !empty($product) ? get_post_meta( $product->get_id(), '_project_location',true ) : '';
			$selected_location          = !empty($selected_location) ? $selected_location : '';
			$post_project_status		= get_post_meta( $product->get_id(), '_post_project_status',true );
			$project_details['posted_time']	= '';
			if(!empty($publish_date)){
				$publish_date		= !empty($publish_date) ? strtotime($publish_date) : 0;
				$offset 			= (float)get_option('gmt_offset') * intval(60) * intval(60);
				$publish_date       = $publish_date + $offset;
				if( !empty($publish_date) ){
					$project_details['posted_time']	= sprintf( _x( 'Posted %s ago', '%s = human-readable time difference', 'workreap-api' ), human_time_diff( $publish_date, current_time( 'timestamp' ) ) );
				}
			}
			$downloadable_files	= 'no';
			if(!empty($downloadable_doc) ){
				$downloadable_files	= 'yes';
			}
			$type_text	= '';
			if(  !empty($project_type) && $project_type === 'fixed'){
				$type_text    = esc_html__('Fixed price project','workreap-api');
			} else {
				$type_text =  apply_filters( 'workreap_filter_project_type_text', $project_type );
			}

			$project_details['is_featured']	= 'no';
			if($product->get_featured()){
				$project_details['is_featured']	= 'yes';
			}

			$project_details['project_id']				= $project_id;
			$project_details['project_url']				= get_the_permalink($project_id);
			$project_details['type_text']				= $type_text;
			$project_details['author_id']				= intval($product_author_id);
			$project_details['profile_id']				= $linked_profile_id;
			$project_details['user_name']				= $user_name;
			$project_details['is_verified']				= $is_verified;
			$project_details['post_project_status']		= !empty($post_project_status) ? $post_project_status : '';
			$project_details['employer_address']			= $address;
			$project_details['avatar']					= $avatar;
			$project_details['employer_hired_project']		= $hired_project_count;
			$project_details['employer_posted_project']	= $posted_project_count;
			$project_details['employer_registered_date']	= $registered_date;
			$project_details['freelancers']				= $selected_freelancers;
			$project_details['selected_location']		= $selected_location;
			$project_details['location_text']			= !empty($selected_location) && !empty($project_location_types[$selected_location]) ? $project_location_types[$selected_location] : '';
			$project_details['title']					= $product->get_name();
			$project_details['description']				= !empty($product) ? $product->get_description() : "";
			$project_details['project_meta']			= $project_meta;
			$project_details['project_price']			= $project_price;
			$project_details['downloadable_files']		= $downloadable_files;
			$project_details['downloadable_docs']		= $downloadable_doc;
			$project_details['skills']					= workreapTermsByPostID($project_id,'skills');
			$project_details['product_cat']				= workreapTermsByPostID($project_id,'product_cat');
			$project_details['duration']				= workreapTermsByPostID($project_id,'duration');
			$project_details['languages']				= workreapTermsByPostID($project_id,'languages');
			$project_details['expertise_level']			= workreapTermsByPostID($project_id,'expertise_level');
			if( !empty($type) && $type === 'proposals' ){
				$proposals_data	= array();
				$args = array(
					'post_type' 	    => 'proposals',
					'post_status'       => array('publish','hired','completed','cancelled','disputed','refunded'),
					'posts_per_page'    => -1,
					'meta_query'        => array(
						array(
							'key'       => 'project_id',
							'value'     => intval($project_id),
							'compare'   => '=',
							'type'      => 'NUMERIC'
						)
					)
				);
				$proposals  = get_posts( $args );
				if( !empty($proposals) ){
					foreach($proposals as $proposal){
						$proposals_data[]	= workreapGetProposalBasic($proposal->ID,'freelancer_detail', $user_id);
					}
				}
				$project_details['proposals']	= $proposals_data;
			}
		}
		return $project_details;

	}
}

/**
 * Project term by post ID
 *
 */
if( !function_exists('workreapTermsByPostID') ){
	function workreapTermsByPostID( $post_id=0,$tax_name='category' ) {
		$term_array			= array();
		$post_terms 		= wp_get_post_terms( $post_id, $tax_name );
		if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
			$term_array = array();
			if( !empty($post_terms) ){
				foreach ( $post_terms as $term ) {
					if( isset($term->term_id) ){
						$new_term				= array();
						$new_term['term_id']	= $term->term_id;
						$new_term['name']		= $term->name;
						$new_term['slug']		= $term->slug;
						$term_array[] 	= $new_term;
					}
				}
			}
		}
		return $term_array;
	}
}

/**
 * Dispute details
 *
 */
if( !function_exists('workreapDisputeDetails') ){
    function workreapDisputeDetails( $dispute_id){
        global $workreap_settings;
        $employer_dispute_days	    = !empty($workreap_settings['employer_dispute_option'])	? intval($workreap_settings['employer_dispute_option']) : 5;
        $post_date             = !empty($dispute_id) ? get_post_field( 'post_date', $dispute_id ) : 0;
        $disbuted_time         = !empty($post_date) ? strtotime($post_date. ' + '.intval($employer_dispute_days).' days') : 0;
        $current_time          = strtotime(current_time( 'mysql', 1 ));
        $post_author           = !empty($dispute_id) ? get_post_field( 'post_author', $dispute_id ) : 0;
        $dispute_status         = !empty($dispute_id) ? get_post_status( $dispute_id ) : '';
        $winning_party          = get_post_meta( $dispute_id, 'winning_party',true );
        $winning_party          = !empty($winning_party) ? intval($winning_party) : 0;
        $list                   = array();
        $list['employer_dispute_days'] = $employer_dispute_days;
        $list['disbuted_time']      = $disbuted_time;
        $list['current_time']       = $current_time;
        $list['post_author']        = $post_author;
        $list['winning_party']      = $winning_party;
        $list['dispute_status']     = $dispute_status;
        return $list;
    }
}

/**
 * Order tasks
 *
 */
if( !function_exists('workreapOrderTasks') ){
    function workreapOrderTasks( $user_id=0,$data=array(),$option_type=''){
        $wallet         = !empty($data['wallet']) ? esc_html($data['wallet']) : '';
        $product_id     = !empty($data['id']) ? intval($data['id']) : 0;
        $task           = !empty($data['product_task']) ? $data['product_task'] : '';
        $subtasks       = !empty($data['subtasks']) ? explode(',',$data['subtasks']) : array();
        $freelancer_id      = get_post_field( 'post_author', $product_id );
        $plans 	        = get_post_meta($product_id, 'workreap_product_plans', TRUE);
        $plans	        = !empty($plans) ? $plans : array();
        $user_balance   = !empty($user_id) ? get_user_meta( $user_id, '_employer_balance',true ) : '';
        $plan_price     = !empty($plans[$task]['price']) ? $plans[$task]['price'] : 0;
        $total_price    = $plan_price;
        if( !empty($subtasks) ){
            foreach($subtasks as $key => $subtask_id){
                $single_price   = get_post_meta( $subtask_id, '_regular_price',true );
                $single_price   = !empty($single_price) ? $single_price : 0;
                $total_price    = $total_price + $single_price;
            }
        }

        if ( class_exists('WooCommerce') ) {
            global $woocommerce;
            if( !empty($option_type) && $option_type === 'mobile' ){
                check_prerequisites($user_id);
            }
            $woocommerce->cart->empty_cart(); //empty cart before update cart
            $user_id        = $user_id;
            $service_fee    = workreap_commission_fee($total_price);
            $admin_shares   = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
            $freelancer_shares  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $total_price;

            if( !empty($wallet) && !empty($user_balance) && $user_balance < $total_price ){
                $cart_meta['wallet_price']		    = $user_balance;
            }
            $cart_meta['task_id']		    = $product_id;
            $cart_meta['total_amount']		= $total_price;
            $cart_meta['task']		        = $task;
            $cart_meta['price']		        = $plan_price;
            $cart_meta['subtasks']		    = $subtasks;
            $cart_meta['employer_id']		    = $user_id;
            $cart_meta['freelancer_id']		    = $freelancer_id;
            $cart_meta['admin_shares']		= $admin_shares;
            $cart_meta['freelancer_shares']		= $freelancer_shares;
            $cart_meta['payment_type']      = 'tasks';
            $cart_data = array(
                'product_id'        => $product_id,
                'cart_data'         => $cart_meta,
                'price'             => $plan_price,
                'payment_type'      => 'tasks',
                'admin_shares'      => $admin_shares,
                'freelancer_shares'     => $freelancer_shares,
                'employer_id'          => $user_id,
                'freelancer_id'         => $freelancer_id,
            );
            $woocommerce->cart->empty_cart();
            $cart_item_data = apply_filters('workreap_order_task_cart_data',$cart_data);
            WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

            if( !empty($subtasks) ){
                foreach($subtasks as $subtasks_id){
                    WC()->cart->add_to_cart( $subtasks_id, 1 );
                }
            }

            if( !empty($wallet) && !empty($user_balance) && $user_balance >= $total_price ){
                $order_id               = workreap_place_order($user_id,'task-wallet');
                $json['checkout_url']	= Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $user_id, true);
                $json['order_id']       = $order_id;

            } else {
                $linked_profile_id  = workreap_get_linked_profile_id($user_id);
                if( !empty($linked_profile_id) && !empty($cart_data) ){
                    update_post_meta( $linked_profile_id, 'mobile_checkout_data',$cart_data );
                    $mobile_checkout    = workreap_get_page_uri('mobile_checkout');
                    if(!empty($mobile_checkout) ){
                        $json['checkout_url']	= $mobile_checkout.'?post_id='.$linked_profile_id;
                    }
                }
            }

            $json['type'] 		        = 'success';
            if( !empty($option_type) && $option_type === 'mobile'){
                $order_id   = !empty($json['order_id']) ? intval($json['order_id']) : 0;
                if( !empty($json['order_id']) ){
                    $order_details  = !empty($json['order_id']) ? get_post_meta( $json['order_id'], 'cus_woo_product_data', true ) : array();
                    workreap_update_tasks_data($json['order_id'],$order_details);
                    workreap_complete_order($order_id);
                }
                return $json;
            } else{
                wp_send_json( $json );
            }
        }
    }
}

/**
 * Get profile
 *
 */
if( !function_exists('workreapGetProfile') ){
    function workreapGetProfile( $post_id=0, $user_id=0, $type=''){
        $list                           = array();
        $deactive_account	            = get_post_meta( $post_id, '_deactive_account', true );
        $deactive_account	            = !empty($deactive_account) ? $deactive_account : 0;
        $avatar                         = apply_filters('workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 315, 'height' => 300), $post_id),array('width' => 315, 'height' => 300));
        $is_online	                    = apply_filters('workreap_is_user_online',$user_id);
        $wr_post_meta                   = get_post_meta( $post_id, 'wr_post_meta', true);
        $is_verified                    = get_post_meta( $post_id, '_is_verified', true );
        $identity_verified  	        = get_user_meta( $user_id, 'identity_verified', true);
        $address                        = apply_filters( 'workreap_user_address', $post_id );

        $country		                = get_post_meta($post_id, 'country', true);
        $zipcode		                = get_post_meta($post_id, 'zipcode', true);

        $list['country']		        = !empty($country) ? $country : '';
        $list['zipcode']		        = !empty($zipcode) ? $zipcode : '';
        if( !empty($type) && $type === 'freelancers' ){
            $success_rate           = 0;
            $profile_views          = get_post_meta( $post_id,'workreap_profile_views',true );
            $review_users           = get_post_meta( $post_id, 'wr_review_users', true );
            $wr_hourly_rate         = get_post_meta( $post_id, 'wr_hourly_rate', true );
            $user_rating            = get_post_meta( $post_id, 'wr_total_rating', true );

            if(function_exists('workreap_success_rate')){
                $success_rate       = workreap_success_rate($user_id);
            }
            $list['success_rate']   = !empty($success_rate) ? esc_html($success_rate) : '';
            $list['review_users']   = !empty($review_users) ? intval($review_users) : 0;
            $list['user_rating']    = !empty($user_rating) ? $user_rating : 0;
            $list['hourly_rate']    = !empty($wr_hourly_rate) ? workreap_price_format($wr_hourly_rate,'return') : 0;
            $list['hourlyprice']    = !empty($wr_hourly_rate) ? $wr_hourly_rate : 0;

            $list['user_link']      = get_the_permalink($post_id);
            $list['profile_views']  = !empty($profile_views) ? intval($profile_views) : 0;
            $eduction_array  = array();
            if( !empty($wr_post_meta['education']) ){
                foreach($wr_post_meta['education'] as $key => $value ){
                    $eduction                   = array();
                    $eduction['degree_title']	= !empty($value['title']) ? $value['title'] : '';
                    $eduction['institute']		= !empty($value['institute']) ? $value['institute'] : '';
                    $eduction['key']		    = !empty($key) ? $key : 0;
                    $enddate 		            = !empty($value['end_date'] ) ? $value['end_date'] : '';
                    $eduction['end_date_format']= !empty( $enddate ) ? date_i18n(get_option( 'date_format' ), strtotime(apply_filters('workreap_date_format_fix',$enddate ))) : '';
                    $eduction['end_date'] 		= $enddate;
                    $eduction['start_date'] 	= !empty($value['start_date'] ) ? $value['start_date'] : '';
                    $eduction['description'] 	= !empty($value['description'] ) ? esc_html($value['description']) : '';
                    $eduction_array[]           = $eduction;
                }
            }
            $list['education']      = $eduction_array;
            $freelancer_type			= wp_get_object_terms($post_id, 'freelancer_type');
            $english_level			= wp_get_object_terms($post_id, 'english_level');
            $total_order_arg  = array(
                array(
                    'key'       => 'freelancer_id',
                    'value'     => $user_id,
                    'compare'   => '=',
                    'type'      => 'NUMERIC'
                ),
                array(
                    'key'       => 'payment_type',
                    'value'     => 'tasks',
                    'compare'   => '=',
                )
            );
            $total_order    = workreap_get_post_count_by_meta('shop_order', array('wc-completed', 'wc-pending', 'wc-on-hold', 'wc-cancelled', 'wc-refunded', 'wc-processing'), $total_order_arg);
            $order_id 		= get_user_meta($user_id, 'package_order_id', true);
            $order_id		= !empty($user_id) ? intval($order_id) : 0;
            $country        = get_post_meta( $post_id, 'country', true );
            $list['country']= !empty($country) ? $country : '';
            if( !empty($order_id) ){
                $package_details					= array();
                $package_id							= get_post_meta($order_id, 'package_id', true);
                $product_instant					= !empty($package_id)	? get_post( $package_id ) : '';
                $package_details['title']			= !empty($product_instant) ? sanitize_text_field($product_instant->post_title) : '';
                $package_details['content']			= !empty($product_instant) ? sanitize_text_field($product_instant->post_content) : '';
                $package_details['image']			= !empty($package_id) ? get_the_post_thumbnail_url( $package_id, array(315,300) ) : '';
                $list['package']				    = $package_details;
            }

            $list['first_name']		= !empty($wr_post_meta['first_name']) ? $wr_post_meta['first_name'] : '';
            $list['last_name']		= !empty($wr_post_meta['last_name']) ? $wr_post_meta['last_name'] : '';
            $list['total_order']    = !empty($total_order) ? intval($total_order) : 0;
            $list['freelancer_type']	= isset($freelancer_type[0]->term_id) ? $freelancer_type[0]->term_id : '';
            $list['english_level']	= isset($english_level[0]->term_id) ? $english_level[0]->term_id : '';
        }

        if( !empty($type) && $type === 'freelancers'){

            //Tasks
            $meta_array	= array(
                array(
                    'key'		=> 'freelancer_id',
                    'value'		=> $user_id,
                    'compare'	=> '=',
                    'type'		=> 'NUMERIC'
                ),
                array(
                    'key'		=> '_task_status',
                    'value'		=> 'completed',
                    'compare'	=> '=',
                ),
                array(
                    'key'		=> 'payment_type',
                    'value'		=> 'tasks',
                    'compare'	=> '=',
                )
            );

            $workreap_order_completed  = workreap_get_post_count_by_meta('shop_order',array('wc-completed'),$meta_array);
	        $workreap_order_completed	= !empty($workreap_order_completed) ? intval($workreap_order_completed) : 0;
	        $list['completed_order']    = $workreap_order_completed;

            $meta_array	= array(
                array(
                    'key'		=> 'freelancer_id',
                    'value'		=> $user_id,
                    'compare'	=> '=',
                    'type'		=> 'NUMERIC'
                ),
                array(
                    'key'		=> '_task_status',
                    'value'		=> 'hired',
                    'compare'	=> '=',
                ),
                array(
                    'key'		=> 'payment_type',
                    'value'		=> 'tasks',
                    'compare'	=> '=',
                )
            );
            $workreap_order_hired    	= workreap_get_post_count_by_meta('shop_order',array('wc-completed'),$meta_array);
            $workreap_order_hired		= !empty($workreap_order_hired) ? intval($workreap_order_hired) : 0;
            $list['hired_order']        = $workreap_order_hired;

	        $meta_array	= array(
		        array(
			        'key'		=> 'freelancer_id',
			        'value'		=> $user_id,
			        'compare'	=> '=',
			        'type'		=> 'NUMERIC'
		        ),
		        array(
			        'key'		=> '_task_status',
			        'value'		=> 'cancelled',
			        'compare'	=> '=',
		        ),
		        array(
			        'key'		=> 'payment_type',
			        'value'		=> 'tasks',
			        'compare'	=> '=',
		        )
	        );
	        $workreap_order_cancelled    	= workreap_get_post_count_by_meta('shop_order', array('wc-cancelled', 'wc-refunded', 'wc-failed','wc-completed'), $meta_array);;
	        $workreap_order_cancelled		= !empty($workreap_order_cancelled) ? intval($workreap_order_cancelled) : 0;
	        $list['cancelled_order']        = $workreap_order_cancelled;


	        //Projects
	        $workreap_projects_args = array(
		        'post_type'         => 'proposals',
		        'post_status'       => 'completed', //array('completed','hired','rejected')
		        'posts_per_page'    => -1,
		        'author'            => $user_id,
		        'order'             => 'DESC'
	        );

	        $workreap_projects_query  = new WP_Query( $workreap_projects_args );
	        $workreap_projects_count = !empty($workreap_projects_query->found_posts) ? intval($workreap_projects_query->found_posts) : 0;
	        $list['completed_project']    = $workreap_projects_count;

	        $workreap_projects_args = array(
		        'post_type'         => 'proposals',
		        'post_status'       => 'hired',
		        'posts_per_page'    => -1,
		        'author'            => $user_id,
		        'order'             => 'DESC'
	        );

	        $workreap_projects_query  = new WP_Query( $workreap_projects_args );
	        $workreap_projects_count = !empty($workreap_projects_query->found_posts) ? intval($workreap_projects_query->found_posts) : 0;
	        $list['hired_project']    = $workreap_projects_count;

	        $workreap_projects_args = array(
		        'post_type'         => 'proposals',
		        'post_status'       => 'rejected',
		        'posts_per_page'    => -1,
		        'author'            => $user_id,
		        'order'             => 'DESC'
	        );

	        $workreap_projects_query  = new WP_Query( $workreap_projects_args );
	        $workreap_projects_count = !empty($workreap_projects_query->found_posts) ? intval($workreap_projects_query->found_posts) : 0;
	        $list['cancelled_project']    = $workreap_projects_count;

        }

        $list['first_name']		    = !empty($wr_post_meta['first_name']) ? $wr_post_meta['first_name'] : '';
        $list['last_name']		    = !empty($wr_post_meta['last_name']) ? $wr_post_meta['last_name'] : '';
        $list['user_name']          = workreap_get_username($post_id);
        $list['is_verified']        = !empty($is_verified) ? $is_verified : '';
        $list['tagline']            = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
        $list['address']            = !empty($address) ? esc_html($address) : '';
        $list['status']             = $is_online;
        $list['avatar']             = esc_url($avatar);
        $list['profile_id']         = $post_id;
        $list['identity_verified']  = !empty($identity_verified) ? $identity_verified : '';
        return $list;
    }
}

/**
 * Get user basic
 *
 */
if( !function_exists('workreap_get_user_basic') ){
    function workreap_get_user_basic( $post_id=0, $user_id=0){
        $list                           = array();
        $avatar                         = apply_filters('workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 315, 'height' => 300), $post_id),array('width' => 315, 'height' => 300));
        $identity_verified  	        = get_user_meta($user_id, 'identity_verified', true);
        $is_verified                    = get_post_meta( $post_id, '_is_verified', true );
	    $meata_keys                     = array( 'linked_profile' => $post_id, 'status' => 0 );
	    $unread_notification           = workreap_post_count( 'notification', 'publish', $meata_keys );
	    $unread_notification           = !empty($unread_notification) ? $unread_notification : 0;
        $deactive_account	            = get_post_meta( $post_id, '_deactive_account', true );
        $deactive_account	            = !empty($deactive_account) ? $deactive_account : 0;

	    $list['user_id']                = $user_id;
	    $list['profile_id']             = $post_id;
	    $list['user_name']              = workreap_get_username($post_id);
	    $list['avatar']                 = esc_url($avatar);
	    $list['is_verified']            = !empty($is_verified) ? $is_verified : '';
	    $list['notifications']          = $unread_notification;
	    $list['deactive_account']       = $deactive_account;
        $list['identity_verified']      = !empty($identity_verified) ? $identity_verified : '';


        return $list;
    }
}

/**
 * Get task history
 *
 */
if (!function_exists('workreap_get_chat_history')) {
    function workreap_get_chat_history($value = array(), $type = 'parent', $user_id = 0)
    {
        $comment_data   = array();
        $comment_data['date']           = !empty($value->comment_date) ? $value->comment_date : '';
        $comment_data['author_id']      = !empty($value->user_id) ? $value->user_id : '';
        $comment_data['comments_id']    = !empty($value->comment_ID) ? $value->comment_ID : '';
        $comment_data['author']         = !empty($value->comment_author) ? $value->comment_author : '';
        $comment_data['message']        = !empty($value->comment_content) ? $value->comment_content : '';
        $message_files                  = get_comment_meta($value->comment_ID, 'message_files', true);
        $comment_data['message_type']   = get_comment_meta($value->comment_ID, '_message_type', true);

        $comment_data['date_formate']           = !empty($comment_data['date']) ? date_i18n('F j, Y', strtotime($comment_data['date'])) : '';
        $comment_data['author_user_type']       = apply_filters('workreap_get_user_type', $comment_data['author_id']);
        $comment_data['author_profile_id']      = workreap_get_linked_profile_id($comment_data['author_id'], '', $comment_data['author_user_type']);
        $comment_data['auther_url']             = !empty($comment_data['author_user_type']) && $comment_data['author_user_type'] === 'freelancers' ? get_the_permalink($comment_data['author_profile_id']) : '#';
        $comment_data['author_name']            = workreap_get_username($comment_data['author_profile_id']);
        $comment_data['avatar']                 = apply_filters('workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $comment_data['author_profile_id']), array('width' => 50, 'height' => 50));
        $file_list  = array();
        if( !empty($message_files) ){
            foreach($message_files as $message_file){
                $src        = WORKREAP_DIRECTORY_URI . 'public/images/doc.jpg';
                if (isset($message_file['ext']) && !empty($message_file['ext'])) {
                    if ($message_file['ext'] == 'pdf') {
                        $src = WORKREAP_DIRECTORY_URI . 'public/images/pdf.jpg';
                    } elseif ($message_file['ext'] == 'png') {
                        $src = WORKREAP_DIRECTORY_URI . 'public/images/png.jpg';
                    } elseif ($message_file['ext'] == 'ppt') {
                        $src = WORKREAP_DIRECTORY_URI . 'public/images/ppt.jpg';
                    } elseif ($message_file['ext'] == 'psd') {
                        $src = WORKREAP_DIRECTORY_URI . 'public/images/psd.jpg';
                    } elseif ($message_file['ext'] == 'php') {
                        $src = WORKREAP_DIRECTORY_URI . 'public/images/php.jpg';
                    }
                }
                $file_list[]    = $src;
            }
        }
        $comment_data['attachments']    = $file_list;
        return $comment_data;

    }
}

/**
 * Get freelancer details
 *
 */
 if( !function_exists('workreap_freelancer_details') ){
    function workreap_freelancer_details( $freelancer_id=0, $user_id=0,$type='' ){
        $list                   = array();
        $avatar                 = apply_filters('workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 315, 'height' => 300), $freelancer_id),array('width' => 300, 'height' => 300));
        $is_online	            = apply_filters('workreap_is_user_online',$user_id);
        $user_rating            = get_post_meta( $freelancer_id, 'wr_total_rating', true );
        $wr_post_meta           = get_post_meta($freelancer_id, 'wr_post_meta', true);
        $review_users           = get_post_meta( $freelancer_id, 'wr_review_users', true );
        $wr_hourly_rate         = get_post_meta( $freelancer_id, 'wr_hourly_rate', true );
        $is_verified            = get_post_meta( $freelancer_id, '_is_verified', true );
        $identity_verified  	= get_user_meta($user_id, 'identity_verified', true);
        $profile_views          = get_post_meta( $freelancer_id,'workreap_profile_views',true );
        $address                = apply_filters( 'workreap_user_address', $freelancer_id );
        $success_rate           = 0;

        if(function_exists('workreap_success_rate')){
            $success_rate       = workreap_success_rate($user_id);
        }
        $eduction_array  = array();
        if( !empty($wr_post_meta['education']) ){
            foreach($wr_post_meta['education'] as $key => $value ){
                $eduction                   = array();
                $eduction['degree_title']	= !empty($value['title']) ? $value['title'] : '';
                $eduction['institute']		= !empty($value['institute']) ? $value['institute'] : '';
                $eduction['key']		    = !empty($key) ? $key : 0;
                $enddate 		            = !empty($value['end_date'] ) ? $value['end_date'] : '';
                $eduction['end_date_format']= !empty( $enddate ) ? date_i18n(get_option( 'date_format' ), strtotime(apply_filters('workreap_date_format_fix',$enddate ))) : '';
                $eduction['end_date'] 		= $enddate;
                $eduction['start_date'] 	= !empty($value['start_date'] ) ? $value['start_date'] : '';
                $eduction['description'] 	= !empty($value['description'] ) ? esc_html($value['description']) : '';
                $eduction_array[]           = $eduction;
            }
        }
        if( !empty($type) && $type ='login' ){
            $freelancer_type			= wp_get_object_terms($freelancer_id, 'freelancer_type');
            $english_level			= wp_get_object_terms($freelancer_id, 'english_level');
            $total_order_arg  = array(
                array(
                    'key'       => 'freelancer_id',
                    'value'     => $user_id,
                    'compare'   => '=',
                    'type'      => 'NUMERIC'
                ),
                array(
                    'key'       => 'payment_type',
                    'value'     => 'tasks',
                    'compare'   => '=',
                )
            );
            $total_order    = workreap_get_post_count_by_meta('shop_order', array('wc-completed', 'wc-pending', 'wc-on-hold', 'wc-cancelled', 'wc-refunded', 'wc-processing'), $total_order_arg);
            $order_id 		= get_user_meta($user_id, 'package_order_id', true);
            $order_id		= !empty($user_id) ? intval($order_id) : 0;
            $country        = get_post_meta( $freelancer_id, 'country', true );
            $list['country']= !empty($country) ? $country : '';
            if( !empty($order_id) ){
                $package_details					= array();
                $package_id							= get_post_meta($order_id, 'package_id', true);
                $product_instant					= !empty($package_id)	? get_post( $package_id ) : '';
                $package_details['title']			= !empty($product_instant) ? sanitize_text_field($product_instant->post_title) : '';
                $package_details['content']			= !empty($product_instant) ? sanitize_text_field($product_instant->post_content) : '';
                $package_details['image']			= !empty($package_id) ? get_the_post_thumbnail_url( $package_id, array(315,300) ) : '';
                $list['package']				    = $package_details;
            }

            $list['first_name']		= !empty($wr_post_meta['first_name']) ? $wr_post_meta['first_name'] : '';
            $list['last_name']		= !empty($wr_post_meta['last_name']) ? $wr_post_meta['last_name'] : '';
            $list['total_order']    = !empty($total_order) ? intval($total_order) : 0;
            $list['freelancer_type']	= isset($freelancer_type[0]->term_id) ? $freelancer_type[0]->term_id : '';
            $list['english_level']	= isset($english_level[0]->term_id) ? $english_level[0]->term_id : '';
        }

        $list['freelancer_name']    = workreap_get_username($freelancer_id);
        $list['user_rating']    = !empty($user_rating) ? $user_rating : 0;
        $list['is_verified']    = !empty($is_verified) ? $is_verified : '';
        $list['review_users']   = !empty($review_users) ? intval($review_users) : 0;
        $list['tagline']        = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';

        $list['hourly_rate']    = !empty($wr_hourly_rate) ? workreap_price_format($wr_hourly_rate,'return') : 0;
        $list['hourlyprice']    = !empty($wr_hourly_rate) ? $wr_hourly_rate : 0;
        $list['profile_views']  = !empty($profile_views) ? intval($profile_views) : 0;
        $list['address']        = !empty($address) ? esc_html($address) : '';
        $list['success_rate']   = !empty($success_rate) ? esc_html($success_rate) : '';
        $list['status']         = $is_online;
        $list['avatar']         = esc_url($avatar);
        $list['education']      = $eduction_array;
        $list['profile_id']     = $freelancer_id;
        $list['user_id']        = $user_id;
        $list['user_link']      = get_the_permalink($freelancer_id);

        $list['identity_verified']    = !empty($identity_verified) ? $identity_verified : '';
        return $list;
    }
 }

/**
 * Get employer details
 *
 */
 if( !function_exists('workreap_employer_details') ){
    function workreap_employer_details( $employer_id=0, $user_id=0 ){
        $list                   = array();
        $avatar                 = apply_filters('workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 315, 'height' => 300), $employer_id),array('width' => 315, 'height' => 300));
        $is_online	            = apply_filters('workreap_is_user_online',$user_id);
        $wr_post_meta           = get_post_meta($employer_id, 'wr_post_meta', true);
        $is_verified            = get_post_meta( $employer_id, '_is_verified', true );
        $identity_verified  	= get_user_meta($user_id, 'identity_verified', true);

        $list['freelancer_name']    = workreap_get_username($employer_id);
        $list['is_verified']    = !empty($is_verified) ? $is_verified : '';
        $list['tagline']        = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
        $list['address']        = !empty($address) ? esc_html($address) : '';
        $list['status']         = $is_online;
        $list['avatar']         = esc_url($avatar);
        $list['profile_id']     = $employer_id;
        $list['user_id']        = $user_id;

        $list['identity_verified']    = !empty($identity_verified) ? $identity_verified : '';
        return $list;
    }
    add_filter( 'workreap_employer_details', 'workreap_employer_details',10,2 );
 }

/**
 * Get task details
 *
 */
 if( !function_exists('workreap_task_details') ){
     function workreap_task_details($post_id,$request=array()){
        $list                   = array();
        $user_id                = get_post_field( 'post_author', $post_id );
        $freelancer_id              = !empty($user_id) ? workreap_get_linked_profile_id($user_id, '','freelancers') : 0;
        $avatar                 = apply_filters('workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 315, 'height' => 300), $freelancer_id),array('width' => 300, 'height' => 300));
        $user_rating            = get_post_meta( $freelancer_id, 'wr_total_rating', true );
        $review_users           = get_post_meta( $freelancer_id, 'wr_review_users', true );
        $is_online	            = apply_filters('workreap_is_user_online',$user_id);
        $list['task_link']      = get_the_permalink($post_id);
        $list['freelancer_name']    = workreap_get_username($freelancer_id);
        $list['user_rating']    = !empty($user_rating) ? $user_rating : 0;
        $list['review_users']   = !empty($review_users) ? intval($review_users) : 0;
        $list['status']         = $is_online;
        $list['avatar']         = esc_url($avatar);
        $list['profile_id']     = $freelancer_id;
        $list['user_id']        = $user_id;

        $product 		                = wc_get_product( $post_id );
        $workreap_service_views          = get_post_meta( $post_id, 'workreap_service_views', TRUE );

		if(function_exists('workreap_api_task_item_status')){
			$list['post_status'] 	= workreap_api_task_item_status( $post_id );
		}

        $meta_array = array(
            array(
                'key'       => 'task_product_id',
                'value'     => $post_id,
                'compare'   => '=',
                'type'      => 'NUMERIC'
            )
        );

        $product_sales             = workreap_get_post_count_by_meta('shop_order', array('wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed'), $meta_array);
        $attachment_ids     = $product->get_gallery_image_ids();
        $product_video      = get_post_meta($product->get_id(), '_product_video', true);
        $featured           = $product->get_featured();
        $featured_image_id  = $product->get_image_id();
        $workreap_total_price = $product->get_price();
        $gallery_images     = array();
        if (!empty($attachment_ids)) {
            foreach ($attachment_ids as $attachment_id) {
                $full_thumb_url = wp_get_attachment_image_src($attachment_id, 'full', true);
                $full_thumb_url = !empty($full_thumb_url[0]) ? $full_thumb_url[0] : '';
                if( !empty($full_thumb_url) ){
                    $gallery_images[]   = $full_thumb_url;
                }
            }
        }
        $categories 			= wp_get_post_terms( $post_id, 'product_cat');
        $categories_array       = $product_cat = array();
        if( !empty($categories) ){
            foreach($categories as $term){
                $categories_array[$term->slug]   = $term->name;
                $product_cat[]                   = $term->term_id;
            }
        }

        $tags 			= wp_get_post_terms( $post_id, 'product_tag');
        $tags_array     = $tags_arr = array();
        if( !empty($tags) ){
            foreach($tags as $tag){
                $tags_array[$tag->slug]   = $tag->name;
				$tags_arr[]   = array(
					'id' 		=>	$tag->term_id,
					'name' 		=>	$tag->name,
					'slug' 		=> $tag->slug
				);
            }
        }

        $plan_array	= array(
            'product_tabs' 			=> array('plan'),
            'product_plans_category'=> $product_cat
        );
        $acf_fields		        = workreap_acf_groups($plan_array);
        $workreap_plans_values 	= get_post_meta($post_id, 'workreap_product_plans', TRUE);
        $workreap_plans_values	= !empty($workreap_plans_values) ? $workreap_plans_values : array();
        $wr_custom_fields       = get_post_meta( $post_id, 'wr_custom_fields',true );
        $wr_custom_fields       = !empty($wr_custom_fields) ? $wr_custom_fields : array();
        $workreap_subtask 		= get_post_meta($post_id, 'workreap_product_subtasks', TRUE);
        $faqs_data              = get_post_meta($post_id, 'workreap_service_faqs', true);

        $faqs                   = array();
        if( !empty($faqs_data) ){
            foreach($faqs_data as $faq_data){
                $faqs[] = $faq_data;
            }
        }
        $sub_tasks              = array();
        if(!empty($workreap_subtask)){
            foreach($workreap_subtask as $workreap_subtask_id){
                $sub_task           = array();
                $price              = get_post_meta( $workreap_subtask_id, '_regular_price', true);
                $sub_task['price']  = workreap_price_format($price,'return');
                $sub_task['title']  = get_the_title( $workreap_subtask_id );
                $sub_task['ID']     = $workreap_subtask_id;
                $sub_task['content']= apply_filters( 'the_content', get_the_content(null, false, $workreap_subtask_id));
                $sub_task['reg_price']  = $price;
                $sub_tasks[]            = $sub_task;
            }
        }

        $attributes             = array();
        if( !empty($workreap_plans_values) ){
            foreach ($workreap_plans_values as $key => $plans_value) {
                $workreap_icon_key	            = 'task_plan_icon_'.$key;
                $plan_array                     = array();
                $plan_array['key']              = $key;
                $plan_array['title']            = !empty($plans_value['title']) ? $plans_value['title'] : '';
                $plan_array['description']      = !empty($plans_value['description']) ? $plans_value['description'] : '';
                $plan_array['featured_package'] = !empty($plans_value['featured_package']) ? $plans_value['featured_package'] : '';
                $plan_array['price']            = !empty($plans_value['price']) ? workreap_price_format($plans_value['price'],'return') : 0;
                $plan_array['reg_price']        = !empty($plans_value['price']) ? $plans_value['price'] : 0;
                $delivery_time                  = !empty($plans_value['delivery_time']) ? $plans_value['delivery_time'] : '';
				$plan_array['delivery_time_id']	= !empty($delivery_time) ? intval($delivery_time) : '';
                $plan_array['delivery_title']   = !empty($delivery_time) ? get_term_by('id', $delivery_time, 'delivery_time')->name : '';
                $delivery_time_option           = 'delivery_time_' . $delivery_time;
                $days                           = 0;
                if (function_exists('get_field')) {
                    $days = get_field('days', $delivery_time_option);
                }
                $plan_array['delivery_time']    = $days;
                $plan_array['task_plan_icon']   = !empty($workreap_settings[$workreap_icon_key]['url']) ? $workreap_settings[$workreap_icon_key]['url'] : '';

                $plan_fields                    = array();
                if( !empty($acf_fields) ){
                    foreach ($acf_fields as $acf_field) {
                        $plan_field                 = array();
                        $plan_value                 = !empty($acf_field['key']) && !empty($plans_value[$acf_field['key']]) ? $plans_value[$acf_field['key']] : '--';
                        $plan_field                 = $acf_field;
                        $plan_field['plan_value']   = $plan_value;
                        $plan_fields[]  = $plan_field;
                    }
                }
                $plan_array['fields']           = $plan_fields;
                $attributes[]                   = $plan_array;
            }
        }
        $comment_page	        = !empty($request['comment_page']) ? intval($request['comment_page']) : 1;
        $per_page		        = 10;
        $offset 		        = ($comment_page * $per_page) - $per_page;
        $comment_args 			= array ( 'post_id' => $post_id,'offset'=> $offset,'number'=> $per_page);
        $comments 		        = get_comments( $comment_args );
        $task_commnets          = array();
        if( !empty($comments) ){
            $total_comments	= get_comments(array('post_id' => $post_id));
            $total_comments	= !empty($total_comments) ? count($total_comments) : 0;

            $commnets_pages                     = ceil($total_comments/$per_page);
            $task_commnets['totals_comments']   = !empty($total_comments) ? intval($total_comments) : 0;
            foreach($comments as $comment){
                $comment_array  = array();
                $employer_id       = !empty($comment->user_id) ? intval($comment->user_id) : 0;
                $employer_id       = !empty($employer_id) ? intval($employer_id) : 0;
                $link_id        = workreap_get_linked_profile_id( $employer_id,'','employers' );
                $employer_img      = apply_filters('workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 315, 'height' => 300), $link_id),array('width' => 315, 'height' => 300));

                $user_name      = !empty($link_id) ? workreap_get_username($link_id) : '';
                $rating         = !empty($comment->comment_ID) ? get_comment_meta($comment->comment_ID, 'rating', true) : 0;
                $title         	= !empty($comment->comment_ID) ? get_comment_meta($comment->comment_ID, '_rating_title', true) : '';

                $comment_array['employer_id']      = intval($employer_id);
                $comment_array['employer_img']     = esc_url($employer_img);
                $comment_array['employer_name']    = esc_html($user_name);
                $comment_array['content']       = esc_html($comment->comment_content);
                $comment_array['rating']        = esc_html($rating);
                $comment_array['title']         = esc_html($title);
                $comment_array['comment_date']  = sprintf( esc_html__( '%s ago', 'workreap' ), human_time_diff(strtotime($comment->comment_date)) );;
                $task_commnets['list'][]        = $comment_array;
            }
        }

		/* attachments gallery */
		$gallery_attachments 	= get_post_meta($post_id, '_product_attachments', true);
		$list['galleries'] 		= !empty($gallery_attachments) ? $gallery_attachments : array();

		/* video */
		$video_attachment = get_post_meta($post_id, '_product_video', true);
		$list['videos'] = !empty($video_attachment) ? $video_attachment : array();

		/* is download allow */
		$download_allow = get_post_meta($post_id, '_downloadable', true);
		$list['download_allow']  = $download_allow;

		/* downloadable */
		$download_attachments = get_post_meta($post_id, '_downloadable_files', true);
		$list['downloads'] = !empty($download_attachments) ? $download_attachments : array();

		$country 						= get_post_meta($post_id, '_country', true);
		$zipcode 						= get_post_meta($post_id, 'zipcode', true);

		$list['is_featured']			= !empty($featured) ? $featured : false;
		$list['country']				= !empty($country) ? $country : '';
		$list['zipcode']				= !empty($zipcode) ? $zipcode : '';

        $list['faqs']                   = !empty($faqs) ? $faqs : array();
        $list['total_price_format']     = isset($workreap_total_price) ? workreap_price_format($workreap_total_price,'return') : 0;
        $list['total_price']            = isset($workreap_total_price) ? $workreap_total_price : 0;

        $list['task_commnets']  = $task_commnets;
        $list['custom_fields']  = $wr_custom_fields;
        $list['task_id']        = $post_id;
        $list['sub_tasks']      = $sub_tasks;
        $list['attributes']     = $attributes;
        $list['task_name']      = $product->get_name();
        $list['average_rating'] = $product->get_average_rating();
        $list['rating_count']   = $product->get_rating_count();
        $list['task_content']   = $product->get_description();
        $list['task_status']   	= $product->get_status();

		/* task status */
		$task_status = false;
		if($product->get_status() == 'publish'){
			$task_status = true;
		}
		$list['task_status'] = $task_status;

        $list['product_sales']   = !empty($product_sales) ? intval($product_sales) : 0;
        $list['service_views']  = !empty($workreap_service_views) ? intval($workreap_service_views) : 0;
        $list['featured_image'] = !empty($featured_image_id) ? wp_get_attachment_url( $featured_image_id ) : '';
        $list['task_video']     = !empty($product_video) ? $product_video : '';
        $list['gallery']        = $gallery_images;
        $list['categories']     = $categories_array;
        $list['category_arr'] 	= $categories;
        $list['tags']           = $tags_array;
        $list['tags_arr']		= $tags_arr;
        $list['featured']       = $product->get_featured();
        return $list;
     }
 }

/**
 * Switch user
 *
*/
if( !function_exists('workreapSwitchUser') ){
    function workreapSwitchUser($user_id='',$option_type=''){
        global $workreap_settings;
        if( !empty($user_id)){
            $user_type		        = apply_filters('workreap_get_user_type', $user_id );
            $profile_id              = workreap_get_linked_profile_id($user_id,'',$user_type);

            $new_type               = '';
            $linked_profile_id      = '';

            if( !empty($user_type) && $user_type == 'freelancers' ){
                $new_type           = 'employers';
                $linked_profile_id  = get_user_meta( $user_id, '_linked_profile_employer', true );
                update_user_meta($user_id,'_user_type','employers');
	            $profile_id              = $linked_profile_id;
            } else {
                $new_type           = 'freelancers';
                $linked_profile_id  = get_user_meta( $user_id, '_linked_profile', true );
                update_user_meta($user_id,'_user_type','freelancers');
	            $profile_id              = $linked_profile_id;
            }

            if( empty($linked_profile_id) ){
                $first_name = get_user_meta( $user_id, 'first_name', true );
                $last_name  = get_user_meta( $user_id, 'last_name', true );
                $first_name = !empty($first_name) ? $first_name : '';
                $last_name  = !empty($last_name) ? $last_name : '';
                $full_name  = $first_name.' '.$last_name;
				$full_name	= empty($full_name) ? $first_name : $full_name;

                $user_post  = array(
                    'post_title'    => wp_strip_all_tags( $full_name ),
                    'post_status'   => 'publish',
                    'post_author'   => $user_id,
                    'post_type'     => $new_type,
                );

                $post_id        = wp_insert_post( $user_post );
                $dir_latitude 	= !empty( $workreap_settings['dir_latitude'] ) ? $workreap_settings['dir_latitude'] : 0.0;
                $dir_longitude 	= !empty( $workreap_settings['dir_longitude'] ) ? $workreap_settings['dir_longitude'] : 0.0;
                //add extra fields as a null
                update_post_meta($post_id, '_address', '');
                update_post_meta($post_id, '_latitude', $dir_latitude);
                update_post_meta($post_id, '_longitude', $dir_longitude);
                update_post_meta($post_id, '_linked_profile', $user_id);
                update_post_meta($post_id, 'zipcode', '');
                update_post_meta($post_id, 'country', '');

                $is_verified  = get_user_meta($user_id, '_is_verified', true);
                $is_verified  = !empty($is_verified) ? $is_verified : '';

                if (!empty($is_verified) && $is_verified == 'yes' ){
                    update_post_meta($post_id, '_is_verified', 'yes');
                } else {
                    update_post_meta($post_id, '_is_verified', 'no');
                }

                if( !empty($new_type) && $new_type == 'freelancers' ){
                    update_user_meta( $user_id, '_linked_profile', $post_id );
                    update_post_meta($post_id, 'wr_hourly_rate', '');
					do_action('workreap_update_freelancer_badge', $post_id,'profile_approved');
                } else {
                    update_user_meta( $user_id, '_linked_profile_employer', $post_id );
                }

                $profile_id = $post_id;
                $wr_post_meta               = array();
                $wr_post_meta['first_name'] = $first_name;
                $wr_post_meta['last_name']  = $last_name;
                update_post_meta($post_id,'wr_post_meta', $wr_post_meta);

            }

            $json['type']           = 'success';
            $json['message']        = esc_html__('Switch user', 'workreap');
            $json['profile_id']     = $profile_id;
            $json['message_desc']   = esc_html__('You have successfully switch the user.', 'workreap');

            if( !empty($option_type) && $option_type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }
        }
    }
}

/**
 * Registration
 *
*/
if( !function_exists('workreapRegistration') ){
    function workreapRegistration($output=array(),$option_type=''){

        global $workreap_settings;
        $user_name_option   = !empty($workreap_settings['user_name_option']) ? $workreap_settings['user_name_option'] : false;
		$shortname_option  =  !empty($workreap_settings['shortname_option']) ? $workreap_settings['shortname_option'] : '';
		$password_strength  =  !empty($workreap_settings['user_password_strength']) ? $workreap_settings['user_password_strength'] : array('length');

		if(!empty($_POST['redirect'] )){
            $redirect  = !empty( $_POST['redirect'] ) ? esc_url( $_POST['redirect'] ) : '';
        }else{
            $redirect  = !empty( $output['redirect'] ) ? esc_url( $output['redirect'] ) : '';
        }

        //Validation
        $validations = apply_filters('workreap_filter_registration_validations',array(
            'first_name'              => esc_html__('First name is required', 'workreap'),
            'last_name'               => esc_html__('Last name is required', 'workreap'),
            'user_email'              => esc_html__('Email is required', 'workreap'),
            'user_password'           => esc_html__('Password is required', 'workreap'),
            'user_agree_terms'        => esc_html__('You should agree to terms and conditions.', 'workreap'),
        ));

        if( !empty($user_name_option) ){
            $validations['user_name']   = esc_html__('User name is required', 'workreap');
        }

        foreach ($validations as $key => $value) {

            if (empty($output['user_registration'][$key])) {
                $json['type']         = 'error';
                $json['message']  = $value;
	            $json['message_desc']  = __("Required field could not be empty",'workreap');
                if( !empty($option_type) && $option_type === 'mobile' ){
                    return $json;
                }
	            wp_send_json($json);
            }

            //Validate email address
            if ($key === 'user_email') {
                if (!is_email($output['user_registration']['user_email'])) {
                    $json['type']           = 'error';
	                $json['message'] 		= esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = esc_html__('Please add a valid email address.', 'workreap');
                   if( !empty($option_type) && $option_type === 'mobile' ){
                        return $json;
                    }
	                wp_send_json($json);
                }

                $user_exists = email_exists($output['user_registration']['user_email']);
                if ($user_exists) {
                    $json['type']           = 'error';
	                $json['message'] 		= esc_html__('Oops!', 'workreap');
                    $json['message_desc']   = esc_html__('This email already registered', 'workreap');
                   if( !empty($option_type) && $option_type === 'mobile' ){
                        return $json;
                    }
	                wp_send_json($json);
                }
            }

            //Password
            if ($key === 'user_password') {

	            $choices = array(
		            'length'   			=> esc_html__('Password length should be minimum 6', 'workreap'),
		            'upper'				=> esc_html__('Password must contain one upper case', 'workreap'),
		            'lower'  			=> esc_html__('Password must contain one lower case', 'workreap'),
		            'special_character' => esc_html__('Password must contain one special character', 'workreap'),
		            'number'  			=> esc_html__('Password must contain one number', 'workreap'),
	            );

	            $password = $output['user_registration'][$key];

	            $number 		= preg_match('@[0-9]@', $password);
	            $uppercase 		= preg_match('@[A-Z]@', $password);
	            $lowercase 		= preg_match('@[a-z]@', $password);
	            $specialChars 	= preg_match('@[^\w]@', $password);
	            $password_error = '';

	            foreach($password_strength as $item){
		            if( $item === 'length'){
			            if( strlen($password) < 6 ) {
				            $password_error = $choices[$item];
			            }
		            }else if( $item === 'upper' && !$uppercase ){
			            $password_error = $choices[$item];
		            }else if( $item === 'lower' && !$lowercase ){
			            $password_error = $choices[$item];
		            }else if( $item === 'number' && !$number ){
			            $password_error = $choices[$item];
		            }else if( $item === 'special_character' && !$specialChars ){
			            $password_error = $choices[$item];
		            }
	            }

	            if(!empty($password_error)){
		            $json['type'] 		= 'error';
		            $json['message'] 		= esc_html__('Oops!', 'workreap');
		            $json['message_desc'] 	= $password_error;
		            if( !empty($option_type) && $option_type === 'mobile' ){
			            return $json;
		            }
		            wp_send_json($json);
	            }

            }

        }


        //Get user data from session
        $first_name         = !empty($output['user_registration']['first_name']) ? sanitize_text_field($output['user_registration']['first_name']) : '';
        $last_name          = !empty($output['user_registration']['last_name']) ? sanitize_text_field($output['user_registration']['last_name']) : '';
        $email              = !empty($output['user_registration']['user_email']) ? is_email($output['user_registration']['user_email']) : '';
        $password           = !empty($output['user_registration']['user_password']) ? ($output['user_registration']['user_password']) : '';
        $user_type          = !empty($output['user_registration']['user_type']) ? sanitize_text_field($output['user_registration']['user_type']) : 'employers';
        $user_agree_terms   = !empty($output['user_registration']['user_agree_terms']) ? esc_html($output['user_registration']['user_agree_terms']) : '';
        $user_name          = !empty($output['user_registration']['user_name']) ? sanitize_text_field($output['user_registration']['user_name']) : '';


        //Session data validation
        if (empty($first_name)
        || empty($last_name)
        || empty($email)
        || empty($user_type)
        ) {
            $json['type']           = 'error';
            $json['message_desc']    = esc_html__('All the fields are required added in first step', 'workreap');
            if( !empty($option_type) && $option_type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }
        }

        $user_name  = !empty($user_name_option) ? $user_name : $email;
        //User Registration
        $random_password  = $password;
        $full_name        = $first_name . ' ' . $last_name;
        $user_nicename    = sanitize_title($full_name);

        $userdata = array(
            'user_login'    => $user_name,
            'user_pass'     => $random_password,
            'user_email'    => $email,
            'user_nicename' => $user_nicename,
            'display_name'  => $full_name,
            'meta_input'    => array(
	            '_user_type'    => $user_type
            )
        );

        $user_identity = wp_insert_user($userdata);

        if (is_wp_error($user_identity)) {
            $json['type']           = "error";
            $json['message_desc']   = esc_html__("User already exists. Please try another one.", 'workreap');

            if( !empty($option_type) && $option_type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }

        } else {
            global $wpdb;
            wp_update_user(array('ID' => esc_sql($user_identity), 'role' => 'subscriber', 'user_status' => 0));

            $wpdb->update(
                $wpdb->prefix . 'users', array('user_status' => 0), array('ID' => esc_sql($user_identity))
            );

			//child theme compatibility
			do_action('workreap_registration_child_data',$output);

            //User Login
            $user_array                   = array();
            $user_array['user_login']     = $email;
            $user_array['user_password']  = $random_password;
            $status = wp_signon($user_array, false);

			$verify_new_user    = !empty($workreap_settings['verify_new_user']) ? $workreap_settings['verify_new_user'] : 'verify_by_link';

            if (!empty($verify_new_user) && $verify_new_user == 'verify_by_admin') {
                $json_message = esc_html__("Your account have been created. Please wait while your account is verified by the admin.", 'workreap');
            } else {
                $json_message = esc_html__("Your account have been created. Please verify your account, an email have been sent your email address.", 'workreap');
            }

			if (!empty( $redirect )) {
            	$dashboard            = $redirect;
			}else{
				$dashboard            = workreap_auth_redirect_page_uri('login',$user_identity);
			}

            $json['type']         = 'success';
            $json['message']      = esc_html__("Account created", 'workreap');;
            $json['message_desc'] = $json_message;
            $json['redirect']   	= wp_specialchars_decode($dashboard);

            if( !empty($option_type) && $option_type === 'mobile' ){
                $json['message_desc']   = $json_message;
                $json['user_id']        = !empty($user_identity) ? intval($user_identity) : 0;

                return $json;
            } else {
                wp_send_json($json);
            }
        }
    }
}
/**
 * Forget password
 *
*/
if( !function_exists('workreapForgetPassword') ){
    function workreapForgetPassword($user_email='',$option_type=''){
        global $workreap_settings;
        if (empty($user_email)) {
            $json['type']           = "error";
            $json['loggedin']       = false;
            $json['message']        = esc_html__('Oops!', 'workreap');;
            $json['message_desc']   = esc_html__("Email address should not be empty or invalid.", 'workreap');
            if( !empty($option_type) && $option_type === 'mobile' ){
                return $json;
            }
	        wp_send_json($json);
        }  else {
            $user_data = get_user_by('email', $user_email);

            if (empty($user_data) ) {
                    $json['type']           = "error";
                    $json['message']        = esc_html__("Oops", 'workreap');
                    $json['message_desc']   = esc_html__("The email address does not exist", 'workreap');
                    if( !empty($option_type) && $option_type === 'mobile' ){
                        return $json;
                    }
                    wp_send_json($json);
                }

            $user_id          = $user_data->ID;
            $user_login       = $user_data->user_login;
            $user_email       = $user_data->user_email;
            $user_profile_id  = workreap_get_linked_profile_id($user_id);
            $username 		  = workreap_get_username($user_profile_id);
            $username         = !empty($username) ? $username : $user_data->display_name;

            //generate reset key
            $key  = wp_generate_password(20, false);
            wp_update_user( array( 'ID' => $user_id, 'user_activation_key' => $key ) );

            $forgot_page_url  	= !empty( $workreap_settings['tpl_forgot_password'] ) ? get_permalink($workreap_settings['tpl_forgot_password']) : '';
            $view_type  		= !empty($workreap_settings['registration_view_type']) ? $workreap_settings['registration_view_type'] : 'pages';
			if( !empty($view_type) && $view_type === 'popup' ){
				$forgot_page_url	= get_home_url();
			}
			$reset_link       	= esc_url(add_query_arg(array('action' => 'reset_pwd', 'key' => $key, 'login' => $user_email), $forgot_page_url));

            //Send email to user
            if (class_exists('Workreap_Email_helper')) {

                $blogname                 = get_option('blogname');
                $emailData                = array();
                $emailData['name']        = $username;
                $emailData['email']       = $user_email;
                $emailData['reset_link']  = $reset_link;

                // Reset password email
                if (class_exists('WorkreapRegistrationStatuses')) {
                    $email_helper = new WorkreapRegistrationStatuses();
                    $email_helper->user_reset_password($emailData);
                }
            }

            $json['type']           = "success";
            $json['message']        = esc_html__("Woohoo!", 'workreap');
            $json['message_desc']   = esc_html__("Reset password link has been sent, please check your email.", 'workreap');

            if( !empty($option_type) && $option_type === 'mobile' ){
                return $json;
            }

	        wp_send_json($json);

        }
    }
}

/**
 * Update saved items
 *
*/
if( !function_exists('workreapUpdateSavedItems') ){
    function workreapUpdateSavedItems($user_id=0,$request=array(),$option_type=''){
        $user_type= apply_filters('workreap_get_user_type', $user_id );
        $type     = !empty($request['type']) ? sanitize_text_field($request['type']) : '';
        $action   = !empty($request['option']) ? sanitize_text_field($request['option']) : '';
        $saved_id = !empty($request['post_id']) ? intval($request['post_id']) : 0;

        if( !empty($type) && $type == 'tasks'){
            $key    = '_saved_tasks';
        } else if(!empty($type) && $type == 'freelancers'){
            $key    = '_saved_freelancers';
        }else if(!empty($type) && $type == 'projects'){
            $key    = '_saved_projects';
        }


        $post_id         = workreap_get_linked_profile_id($user_id,'',$user_type);
        $saved_items     = get_post_meta($post_id, $key, true);
        $saved_items     = !empty( $saved_items ) && is_array( $saved_items ) ? $saved_items : array();

        if (!empty($saved_id)) {

            if( !empty($action) && $action == 'saved' ){
                $saved_items[]  = $saved_id;
                $saved_items    = array_unique( $saved_items );
                $json_message   = esc_html__('Item saved', 'workreap');
                $message_desc   = esc_html__('Successfully! added to your saved list', 'workreap');
            } else {

                if (($key_change = array_search($saved_id, $saved_items)) !== false) {
                    unset($saved_items[$key_change]);
                }
            $json_message = esc_html__('Item removed', 'workreap');
            $message_desc = esc_html__('Successfully! removed from your saved list', 'workreap');
            }

            update_post_meta( $post_id, $key, $saved_items );
            $json['type'] 		    = 'success';
            $json['text'] 		    = esc_html__('Saved', 'workreap');
            $json['message']      = $json_message;
            $json['message_desc'] = $message_desc;
            if( !empty($option_type) && $option_type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }
        }
    }
}

/**
 * Workreap fund request
 *
*/
if( !function_exists('workreapWithdraqRequest') ){
    function workreapWithdraqRequest($user_id=0,$request=array(),$type=''){
		global $workreap_settings;
        $json['message']    = esc_html__('Withdraw Money','workreap');

        // get the info from requested form
        $payment_method     = !empty($request['withdraw']['gateway']) ? esc_html($request['withdraw']['gateway']) : '';
        $requested_amount   = !empty($request['withdraw']['amount']) ? floatval($request['withdraw']['amount']) : 0;
        $user_id            = !empty($user_id) ? intval($user_id) : '';
        $linked_profile_id  = workreap_get_linked_profile_id($user_id,'freelancers');
        $linked_profile_id  = !empty($linked_profile_id) ? $linked_profile_id : $user_id;
        $min_withdraw       = !empty($workreap_settings['min_amount']) ? $workreap_settings['min_amount'] : 0;

        // verify requested amount is selected
        if ( empty($requested_amount) ) {
            $json['message']        = esc_html__('Hold right there!','workreap');
            $json['type']           = 'error';

            $json['message_desc']   = sprintf(esc_html__("Minimum amount should be greater than %s to withdraw", 'workreap'),$min_withdraw);
            if( !empty($type) && $type === 'mobile' ){
                wp_send_json($json);
            } else {
                wp_send_json($json);
            }
        }

        if( !empty($min_withdraw) && $requested_amount < $min_withdraw ){
            $json['message']        = esc_html__('Hold right there!','workreap');
            $json['type']           = 'error';
            $json['message_desc']   = sprintf(esc_html__("Minimum amount should be greater than %s to withdraw", 'workreap'),$min_withdraw);
            if( !empty($type) && $type === 'mobile' ){
                wp_send_json($json);
            } else {
                wp_send_json($json);
            }
        }

        // get available amount to verify requested amount
        // get amount which is available to be withdraw
        $current_balance    = workreap_account_details($user_id,array('wc-completed'),'completed');
        $current_balance    = !empty($current_balance) ? $current_balance : 0;

        // get amount which is already withdrawn or withdraw requested
        $withdrawn_amount   = workreap_account_withdraw_details($user_id,array('pending','publish'));
        $withdrawn_amount   = !empty($withdrawn_amount) ? $withdrawn_amount : 0;
        $account_balance    = $current_balance - $withdrawn_amount;
//		$account_balance 	= number_format( $account_balance, 2);

        // verify amount before further process
        if ( $requested_amount > $account_balance) {
            $json['type']         = 'error';
            $json['message_desc'] = esc_html__("We are sorry, you haven't enough amount to withdraw", 'workreap');
             if( !empty($type) && $type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }
        }

        // verify minimum amount
        if ( $requested_amount <= 0) {
            $json['type']         = 'error';
            $json['message_desc'] = esc_html__("We are sorry, you must select greater amount to process", 'workreap');
             if( !empty($type) && $type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }
        }

        // get user's selected payment method details
        $contents	= get_user_meta($user_id,'workreap_payout_method',true);
		$payout_list = workreap_get_payouts_lists();
        // get user's specific selected payment method details
        // if selected method is payoneer
		$fields         = !empty($workreap_settings[$payment_method.'_fields']) ?$workreap_settings[$payment_method.'_fields'] : array();

		if( !empty($payment_method) && $payment_method === 'payoneer' ){

            if( !empty($contents) && array_key_exists($payment_method, $contents) ){
                $email		= !empty($contents['payoneer']['payoneer_email']) ? $contents['payoneer']['payoneer_email'] : "";
            }

            $insert_payouts		= serialize( array('payoneer_email' => $email) );
            //check if email is valid
            if(!empty($fields)){
				foreach($fields as $field){
					if(empty($contents['payoneer'][$field])){
						$json['type'] 	 = "error";
						$json['message_desc'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap');
						if( !empty($type) && $type === 'mobile' ){
							return $json;
						} else {
							wp_send_json($json);
						}
					}
				}
			}
        } elseif ( !empty($payment_method) && $payment_method === 'paypal' ){
            if( !empty($contents) && array_key_exists($payment_method, $contents) ){
                $email		= !empty($contents['paypal']['paypal_email']) ? $contents['paypal']['paypal_email'] : "";
            }
            $insert_payouts		= serialize( array('paypal_email' => $email) );
            //check if email is valid
            if(!empty($fields)){
				foreach($fields as $field){
					if(empty($contents['paypal'][$field])){
						$json['type'] 	 = "error";
						$json['message_desc'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap');
						if( !empty($type) && $type === 'mobile' ){
							return $json;
						} else {
							wp_send_json($json);
						}
					}
				}
			}
        } elseif( !empty($payment_method) && $payment_method === 'bank' ){
            // if selected method is bank

            if( !empty($contents) && array_key_exists($payment_method, $contents) ){
				if(!empty($fields)){
					foreach($fields as $field){
						if(empty($contents['bank'][$field])){
							$json['type'] 	 = "error";
							$json['message_desc'] = esc_html__("One or more required fields are missing please update the payout settings for the selected payment gateway in payout settings", 'workreap');
							if( !empty($type) && $type === 'mobile' ){
								return $json;
							} else {
								wp_send_json($json);
							}
						}
					}
				}
				$hide_fields   = !empty($workreap_settings[$payment_method.'_hide_fields']) ? $workreap_settings[$payment_method.'_hide_fields'] : array();

                $bank_details	= array();
                $bank_details['bank_account_title']		= !empty($contents['bank']['bank_account_title']) ? $contents['bank']['bank_account_title'] : '';
                $bank_details['bank_account_number']	= !empty($contents['bank']['bank_account_number']) ? $contents['bank']['bank_account_number'] : '';
                $bank_details['bank_account_name']		= !empty($contents['bank']['bank_account_name']) ? $contents['bank']['bank_account_name'] : '';
                $bank_details['bank_routing_number']	= !empty($contents['bank']['bank_routing_number']) ? $contents['bank']['bank_routing_number'] : '';
                $bank_details['bank_iban']	          = !empty($contents['bank']['bank_iban']) ? $contents['bank']['bank_iban'] : '';
                $bank_details['bank_bic_swift']	      = !empty($contents['bank']['bank_bic_swift']) ? $contents['bank']['bank_bic_swift'] : "";
				if(!empty($hide_fields)){
					foreach($hide_fields as $hide_field){
						if(isset($bank_details[$hide_field])){
							unset($bank_details[$hide_field]);
						}
					}
				}
                $bank_details                         = apply_filters('payout_bank_transfer_filter_details',$bank_details,$contents);
            }

            $insert_payouts		= serialize( $bank_details );
        } else{

            $payout_details	= array();
			$fields	= workreap_get_payouts_lists($payment_method);
			if( !empty($fields[$payment_method]['fields'])) {
				foreach( $fields[$payment_method]['fields'] as $key => $field ){
					if(!empty($contents[$payment_method][$key])){
                        $payout_details[$key]		= $contents[$payment_method][$key];
                    }
				}
			}

			$insert_payouts		= serialize( $payout_details );

            //check if email is valid
            if(empty($payout_details)){
                $json['type'] 	 = "error";
                $json['message_desc'] = esc_html__("Please update the payout settings for the selected payment gateway in payout settings", 'workreap');
                if( !empty($type) && $type === 'mobile' ){
                    return $json;
                } else {
                    wp_send_json($json);
                }
            }
        }

        // prepare data to insert in withdraw post_type
        $unique_key       = workreap_unique_increment(16);
        $account_details  = !empty($insert_payouts) ? $insert_payouts : array();
        $user_name        = !empty($user_id) ? workreap_get_username($linked_profile_id) . '-' . $requested_amount : '';
        $withdraw_post    = array(
            'post_title'    => wp_strip_all_tags($user_name),
            'post_status'   => 'pending',
            'post_author'   => $user_id,
            'post_type'     => 'withdraw',
        );

        // record withdrawal request into withdraw post_type
        $withdrawal_post_id    = wp_insert_post($withdraw_post);
        $current_date          = current_time('mysql');
        // update relevant info in medata
        update_post_meta($withdrawal_post_id, '_withdraw_amount', $requested_amount);
        update_post_meta($withdrawal_post_id, '_payment_method', $payment_method);
        update_post_meta($withdrawal_post_id, '_timestamp', strtotime($current_date));
        update_post_meta($withdrawal_post_id, '_year', date('Y',strtotime($current_date)));
        update_post_meta($withdrawal_post_id, '_month', date('m',strtotime($current_date)));
        update_post_meta($withdrawal_post_id, '_account_details', $account_details);
        update_post_meta($withdrawal_post_id, '_unique_key', $unique_key);

        // send withdrawal email notification to admin
        if (class_exists('Workreap_Email_helper')) {
            if (class_exists('WithDrawStatuses')) {
                $emailData                          = array();
                $post_id							= workreap_get_linked_profile_id($user_id);
				$user_name                          = workreap_get_username($post_id);
                $emailData['user_name']             = !empty($user_name) ? $user_name : '';
                $emailData['user_link']             = admin_url( 'post.php?post='.$post_id.'&action=edit');
                $emailData['amount']                = !empty($requested_amount) ? workreap_price_format($requested_amount,'return') : '';
                $emailData['detail']                = admin_url( 'edit.php?post_type=withdraw&author='.$user_id);
                $email_helper = new WithDrawStatuses();
                $email_helper->withdraw_admin_email_request($emailData);
            }
        }


        do_action('workreap_money_withdraw_activity', $withdrawal_post_id, $request);

        // everything gone well, lets send success response to actual request
        $json['type'] 	 		= "success";
        $json['message_desc']        = esc_html__('Your withdrawal request has been submitted. We will process your withdrawal request', 'workreap');
        if( !empty($type) && $type === 'mobile' ){
            return $json;
        } else {
            wp_send_json($json);
        }
     }
}

/**
 * update task dispute comments
 *
*/
if( !function_exists('workreap_update_dispute_comments') ){
    function workreap_update_dispute_comments($user_id=0,$request=array(),$type=''){
        global $workreap_settings;
        $get_user_type	    = apply_filters('workreap_get_user_type', $user_id );
        $dispute_id         = !empty($request['dispute_id'])?intval($request['dispute_id']):'';
        $parent_comment_id  = !empty($request['parent_comment_id'])?intval($request['parent_comment_id']):0;
        $dispute_comment    = !empty($request['dispute_comment'])?esc_textarea($request['dispute_comment']):'';
        $action_type        = !empty($request['action_type'])?esc_textarea($request['action_type']):'reply';
        $field  = array(
            'comment' 			=> $dispute_comment,
            'comment_parent' 	=> $parent_comment_id,
        );

        $comment_id = workreap_wp_insert_comment($field, $dispute_id);

        if(empty($comment_id)){
            $json['type']           = 'error';
			$json['message']        = esc_html__('Oops!', 'workreap');
			$json['message_desc']   = esc_html__('You are not allowed to reply dispute refund request', 'workreap');
			if( !empty($type) && $type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }
        }

        $json['type'] 			= "success";
		$json['message'] 		= esc_html__('Woohoo!', 'workreap');
        $json['message_desc'] 	= esc_html__("Your reply has been posted", 'workreap');

      	$freelancer_id	= get_post_meta( $dispute_id, '_freelancer_id', true );
        $employer_id	= get_post_meta( $dispute_id, '_employer_id', true );

		$dispute_order  = get_post_meta( $dispute_id, '_dispute_order', true );
		$dispute_order  = !empty($dispute_order) ? intval($dispute_order) : 0;
		/* email to freelancer and employer on commentig */
		if ($get_user_type == 'employers'){
			$sender_id          = $employer_id;
			$receiver_id        = $freelancer_id;
			$receiver_id        = !empty($receiver_id) ? intval($receiver_id) : 0;
			$receiver_user_type = 'freelancers';
		} else if ($get_user_type == 'freelancers'){
			$sender_id          = $freelancer_id;
			$receiver_id        = $employer_id;
			$receiver_id        = !empty($receiver_id) ? intval($receiver_id) : 0;
			$receiver_user_type = 'employers';
		} else {
			$sender_id          = $freelancer_id;
			$receiver_id        = $employer_id;
			$receiver_id        = !empty($receiver_id) ? intval($receiver_id) : 0;
			$receiver_user_type = 'employers';
		}
		$receiver_linked_profile_id = workreap_get_linked_profile_id($receiver_id, '', $receiver_user_type);
		$sender_type 				= $receiver_user_type=='freelancers' ? 'employers' : 'freelancers';
		$sender_linked_profile_id 	= workreap_get_linked_profile_id($sender_id, '', $sender_type);

		/* getting order detail */
		$order_id		= get_post_meta( $dispute_id, '_dispute_order', true );
		$order_id		= !empty($order_id) ? intval($order_id) :0;
		$order 		    = !empty($order_id) ? wc_get_order($order_id) : array();
		$order_price 	= !empty($order) ? $order->get_total() : 0;
		$order_amount 	= !empty($order_price) ? $order_price : 0;

        if($action_type == 'decline' && $get_user_type == 'freelancers') {

			$task_id	= get_post_meta( $dispute_id, '_task_id', true );

			update_post_meta($order_id, '_fund_type', 'admin');

			$employer      = get_user_by( 'id', $employer_id );
			$employer_name = $employer->display_name;

			$freelancer         = get_user_by( 'id', $freelancer_id );
			$freelancer_name    = $freelancer->display_name;

			$product    = wc_get_product( $task_id );
			$task_name  = $product->get_title();

			$freelancer_info  = get_userdata($freelancer_id);
			$freelancer_email = $freelancer_info->user_email;

			$employer_info  = get_userdata($employer_id);
			$employer_email = $employer_info->user_email;

			$login_url   =  !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
			$task_link   =  get_permalink($task_id);



			if (class_exists('Workreap_Email_helper')) {

				$blogname = get_option( 'blogname' );
				$emailData = array();
				$emailData['freelancer_name']       = $freelancer_name;
				$emailData['employer_name']        = $employer_name;
				$emailData['task_name']         = $task_name;
				$emailData['freelancer_email']      = $freelancer_email;
				$emailData['employer_email']       = $employer_email;
				$emailData['task_link']         = $task_link;
				$emailData['order_id'] 	        = $order_id;
				$emailData['order_amount']      = $order_amount;
				$emailData['login_url']         = $login_url;

				if($workreap_settings['email_refund_declined_employer'] == true){

					if (class_exists('WorkreapRefundsStatuses')) {
						$email_helper = new WorkreapRefundsStatuses();
						$email_helper->refund_declined_employer_email($emailData); //refund declined by freelancer
						do_action('notification_message', $emailData );
					}
				}
			}

			$notifyData								= array();
			$notifyDetails							= array();
			$notifyDetails['task_id']       		= $task_id;
			$notifyDetails['post_link_id']  		= $task_id;
			$notifyDetails['dispute_comment']		= $dispute_comment;
			$notifyDetails['freelancer_order_amount']  	= $order_amount;

			$notifyDetails['order_id']      = $dispute_order;
			$notifyDetails['dispute_id']    = $dispute_id;
			$notifyDetails['freelancer_id']     = $sender_linked_profile_id;
			$notifyDetails['employer_id']    	= $receiver_linked_profile_id;
			$notifyData['receiver_id']		= $receiver_id;
			$notifyData['type']			    = 'refund_decline';
			$notifyData['comment_id']		= $comment_id;
			$notifyData['linked_profile']	= $receiver_linked_profile_id;
			$notifyData['user_type']		= $receiver_user_type;
			$notifyData['post_data']		= $notifyDetails;
			do_action('workreap_notification_message', $notifyData );
			$post_status    = 'declined';
			$json['message'] = esc_html__("You have been declined the refund request", 'workreap');

        } elseif($action_type == 'refund' && $get_user_type == 'freelancers'){
            $post_status    = 'refunded';

            $send_by  		= !empty($employer_id) ? intval($employer_id) : 0;
            $order_total	= get_post_meta( $dispute_order, '_order_total', true );
            $order_total  	= !empty($order_total) ? ($order_total) : 0;

            if ( class_exists('WooCommerce') ) {
                global $woocommerce;
                if( !empty($type) && $type === 'mobile' ){
                    check_prerequisites($user_id);
                }

                $order = wc_get_order($dispute_order);
                $order->set_status('refunded');
                $order->save();

				update_post_meta($dispute_order, '_task_status', 'cancelled');

                $woocommerce->cart->empty_cart();
                $wallet_amount              = $order_total;
                $product_id                 = workreap_employer_wallet_create();
                $user_id			        = $send_by;
                $cart_meta                  = array();

                $cart_meta['task_id']     	= $product_id;
                $cart_meta['wallet_id']     = $product_id;
                $cart_meta['product_name']  = get_the_title($product_id);
                $cart_meta['price']         = $wallet_amount;
                $cart_meta['payment_type']  = 'wallet';
                $cart_meta['order_type']    = 'refunded';

                $cart_data = array(
                    'wallet_id' 		=> $product_id,
                    'cart_data'     	=> $cart_meta,
                    'price'				=> $wallet_amount,
                    'payment_type'     	=> 'wallet'
                );

                $woocommerce->cart->empty_cart();
                $cart_item_data = apply_filters('workreap_update_dispute_comment_cart_data',$cart_data);
                WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                $new_order_id	= workreap_place_order($user_id,'wallet',$dispute_id);

				update_post_meta($new_order_id, '_fund_type', 'admin');
				update_post_meta($new_order_id, '_task_dispute_order', $dispute_order);

                update_post_meta($dispute_id, 'dispute_status', 'resolved');
                update_post_meta($dispute_id, 'winning_party', $user_id);
				update_post_meta($dispute_id, 'resolved_by', 'freelancers');

                /* getting data for email */
            	if (class_exists('Workreap_Email_helper')) {
					$task_id	  = get_post_meta( $dispute_id, '_task_id', true );
					$product      = wc_get_product( $task_id );
					$task_name    = $product->get_title();

					$employer        = get_user_by( 'id', $employer_id );
					$employer_name   = $employer->display_name;
					$employer_info   = get_userdata($employer_id);
					$employer_email  = $employer_info->user_email;

					$freelancer       = get_user_by( 'id', $freelancer_id );
					$freelancer_name  = $freelancer->display_name;

					$emailData = array();
					$emailData['employer_email']       = $employer_email;
					$emailData['freelancer_name']       = $freelancer_name;
					$emailData['employer_name']        = $employer_name;
					$emailData['task_name']         = $task_name;
					$emailData['task_link']         = get_permalink($task_id);
					$emailData['order_id']          = $dispute_order;
					$emailData['order_amount']      = $order_total;
					$emailData['login_url']         = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();

					if($workreap_settings['email_refund_approv_employer'] == true){
						if (class_exists('WorkreapRefundsStatuses')) {
							$email_helper = new WorkreapRefundsStatuses();
							$email_helper->refund_approved_employer_email($emailData);
						}
					}

              	}
				$freelancer_profile_id	= get_user_meta($freelancer_id, '_linked_profile',true);
				if(!empty($freelancer_profile_id) ){
					do_action('workreap_completed_task_badge', $freelancer_profile_id,$dispute_order,'');
				}
            } else {
                $json['type']           = 'error';
				$json['message'] 		= esc_html__('Uh!', 'workreap');
                $json['message_desc']   = esc_html__('Please install WooCommerce plugin to process this order', 'workreap');
                if( !empty($type) && $type === 'mobile' ){
                    return $json;
                } else {
                    wp_send_json($json);
                }

            }
			$notifyData								= array();
			$notifyDetails							= array();
			$notifyDetails['task_id']       		= $task_id;
			$notifyDetails['post_link_id']  		= $task_id;
			$notifyDetails['dispute_comment']		= $dispute_comment;
			$notifyDetails['freelancer_order_amount']  	= $order_amount;

			$notifyDetails['order_id']      = $dispute_order;
			$notifyDetails['dispute_id']    = $dispute_id;
			$notifyDetails['freelancer_id']     = $sender_linked_profile_id;
			$notifyDetails['employer_id']    	= $receiver_linked_profile_id;
			$notifyData['receiver_id']		= $receiver_id;
			$notifyData['type']			    = 'refund_approved';
			$notifyData['comment_id']		= $comment_id;
			$notifyData['linked_profile']	= $receiver_linked_profile_id;
			$notifyData['user_type']		= $receiver_user_type;
			$notifyData['post_data']		= $notifyDetails;
			do_action('workreap_notification_message', $notifyData );
            $json['message_desc'] = esc_html__("You have approved the refund request", 'workreap');

        } else {
			/* receiver link profile id */
			$receiver_name              = workreap_get_username($receiver_linked_profile_id);
			$receiver_email 	        = get_userdata( $receiver_id )->user_email;
			$sender_name             	= workreap_get_username($sender_linked_profile_id);

			$task_id	    = get_post_meta( $dispute_id, '_task_id', true );
			$product      	= wc_get_product( $task_id );
			$task_name    	= $product->get_title();

			$dispute_order  = get_post_meta( $dispute_id, '_dispute_order', true );
			$dispute_order  = !empty($dispute_order) ? intval($dispute_order) : 0;

			$order_total  = get_post_meta( $dispute_order, '_order_total', true );
			$order_total  = !empty($order_total) ? ($order_total) : 0;

			$emailData    					= array();
			$emailData['sender_name']         = $sender_name;
			$emailData['receiver_name']       = $receiver_name;
			$emailData['receiver_email']      = $receiver_email;
			$emailData['task_name']           = $task_name;
			$emailData['task_link']           = get_permalink($task_id);
			$emailData['order_id']            = $dispute_order;
			$emailData['order_amount']        = $order_total;
			$emailData['login_url']           = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
			$emailData['sender_comments']     = $dispute_comment;
			$emailData['notification_type']   = 'noty_order_activity_chat';
			$emailData['sender_id']           = $sender_id; //freelancer id
			$emailData['receiver_id']         = $receiver_id; //employer id

			if (class_exists('Workreap_Email_helper')) {

				if (class_exists('WorkreapRefundsStatuses')) {
					$email_helper = new WorkreapRefundsStatuses();

					if($get_user_type == 'freelancers' && $workreap_settings['email_refund_comment_freelancer'] == true ){
						$email_helper->refund_employer_comments_email($emailData);
					} elseif($get_user_type == 'employers'  && $workreap_settings['email_refund_comment_employer'] == true ){
						$email_helper->refund_freelancer_comments_email($emailData);
					}

				}
			}

			$dispute_status		= get_post_status($dispute_id);
			$post_status        = $dispute_status;
			if( !empty($action_type) && $action_type === 'reply' ){
				$notifyData								= array();
				$notifyDetails							= array();
				$notifyDetails['task_id']       		= $task_id;
				$notifyDetails['post_link_id']  		= $task_id;
				$notifyDetails['dispute_comment']		= $dispute_comment;
				$notifyDetails['freelancer_order_amount']  	= $order_amount;

				$notifyDetails['order_id']      = $dispute_order;
				$notifyDetails['dispute_id']    = $dispute_id;
				$notifyDetails['sender_id']     = $sender_linked_profile_id;
				$notifyDetails['receiver_id']   = $receiver_linked_profile_id;
				$notifyData['comment_id']		= $comment_id;
				$notifyData['receiver_id']		= $receiver_id;
				$notifyData['type']			    = 'refund_comments';
				$notifyData['linked_profile']	= $receiver_linked_profile_id;
				$notifyData['user_type']		= $receiver_user_type;
				$notifyData['post_data']		= $notifyDetails;
				if( !empty($get_user_type) && $get_user_type === 'administrator'){
					unset($notifyDetails['sender_id']);
					$buye_linked_profile_id 	= workreap_get_linked_profile_id($employer_id, '', 'employers');
					$freelancers_linked_profile_id 	= workreap_get_linked_profile_id($freelancer_id, '', 'freelancers');
					$notifyData['type']			    = 'admin_refund_comments';
					$notifyDetails['receiver_id']   = $buye_linked_profile_id;
					$notifyData['linked_profile']	= $buye_linked_profile_id;
					$notifyData['post_data']		= $notifyDetails;
					do_action('workreap_notification_message', $notifyData );

					$notifyDetails['receiver_id']   = $freelancers_linked_profile_id;
					$notifyData['linked_profile']	= $freelancers_linked_profile_id;
					$notifyData['post_data']		= $notifyDetails;
					do_action('workreap_notification_message', $notifyData );
					$admin_reply_email	= !empty( $workreap_settings['email_refund_comment_admin'] ) ? $workreap_settings['email_refund_comment_admin'] : ''; //email freelancer new refend
            		if(isset($admin_reply_email) && !empty($admin_reply_email )){

						if (class_exists('WorkreapRefundsStatuses')) {
							$email_helper = new WorkreapRefundsStatuses();
							$freelancer_details	= !empty($freelancer_id) ? get_userdata( $freelancer_id ) : array();
							$employer_details	= !empty($employer_id) ? get_userdata( $employer_id ) : 0;
							$emailData    					 = array();
							$emailData['receiver_name']       = workreap_get_username($buye_linked_profile_id);
							$emailData['receiver_email']      = !empty($employer_details->user_email) ? $employer_details->user_email : '';
							$emailData['task_name']           = $task_name;
							$emailData['task_link']           = get_permalink($task_id);
							$emailData['order_id']            = $dispute_order;
							$emailData['order_amount']        = $order_total;
							$emailData['login_url']           = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
							$emailData['sender_comments']     = $dispute_comment;
							$email_helper->refund_admin_comments_email($emailData);
							$emailData['receiver_name']       = workreap_get_username($freelancers_linked_profile_id);
							$emailData['receiver_email']      = !empty($freelancer_details->user_email) ? $freelancer_details->user_email : '';
							$email_helper->refund_admin_comments_email($emailData);
						}
					}
				} else {
					do_action('workreap_notification_message', $notifyData );
				}

			}
			$json['message_desc'] = esc_html__("Your reply has been posted", 'workreap');
        }

        $args   = array(
            'ID'            => $dispute_id,
            'post_status'   => $post_status,
        );
        wp_update_post($args);

        do_action('workreap_refund_request_activity', $dispute_id);

		$json['message'] = esc_html__('Woohoo!', 'workreap');

        if( !empty($type) && $type === 'mobile' ){
            return $json;
        } else {
            wp_send_json($json);
        }

    }
}

/**
 * update task history comment
 *
 */
if( !function_exists('workreap_update_comments') ){
    function workreap_update_comments($user_id=0,$request=array(),$type=''){
    global $workreap_settings;
    $user_type         = apply_filters('workreap_get_user_type', $user_id);
    $linked_profile_id = workreap_get_linked_profile_id($user_id, '', $user_type);
    $user_name         = workreap_get_username($linked_profile_id);
    $avatar            = apply_filters(
        'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile_id), array('width' => 100, 'height' => 100)
    );
    $order_id 	    = !empty( $request['id'] ) ? intval($request['id']) : '';
    $temp_items     = !empty( $request['attachments']) ? ($request['attachments']) : array();
    $content 	    = !empty( $request['activity_detail'] ) ? esc_textarea($request['activity_detail']) : '';
    $message_type   = !empty( $request['message_type'] ) ? esc_html($request['message_type']) : '';

    //Upload files from temp folder to uploads
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
    $time       = current_time('mysql');
    // prepare data array for insertion
    $data = array(
        'comment_post_ID' 		    => $order_id,
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
    $comment_id = wp_insert_comment(apply_filters('task_activity_data_filter', $data));

    if( !empty( $comment_id ) ) {



        // if chat contains attachments then add in meta
        if( !empty( $project_files )) {
            add_comment_meta($comment_id, 'message_files', $project_files);
        }

        // update meta data
        $freelancer_id  = 0;
        $employers_id  = 0;
        if (!empty($user_type) && $user_type == 'freelancers'){
            update_comment_meta($comment_id, '_message_type', $message_type);
            $employers_id          = get_post_meta( $order_id, 'employer_id', true);
            $receiver_id        = !empty($employers_id) ? intval($employers_id) : 0;
            $receiver_user_type = 'employers';
            $sender_linked_profile_id = workreap_get_linked_profile_id($user_id, '', 'freelancers');
        } else if ( !empty($user_type) && $user_type == 'employers'){
            $freelancer_id          = get_post_meta( $order_id, 'freelancer_id', true);
            $receiver_id        = !empty($freelancer_id) ? intval($freelancer_id) : 0;
            $receiver_user_type = 'freelancers';
            $sender_linked_profile_id = workreap_get_linked_profile_id($user_id, '', 'employers');
        }

        $receiver_linked_profile_id = workreap_get_linked_profile_id($receiver_id, '', $receiver_user_type);
        $receiver_name              = workreap_get_username($receiver_linked_profile_id);
        $receiver_email 	        = !empty($receiver_id) ? get_userdata( $receiver_id )->user_email : '';
        $task_id        = get_post_meta( $order_id, 'task_product_id', true);
        $task_id        = !empty($task_id) ? $task_id : 0;
        $task_title     = !empty($task_id) ? get_the_title($task_id) : '';
        $task_link      = !empty($task_id) ? get_permalink( $task_id ) : '';
        $order 		    = wc_get_order($order_id);
        $order_amount   = !empty($order) ? $order->get_total() : '';
        $order_amount   = !empty($order_amount) ? $order_amount : 0;
        $login_url      = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
        $activity_email = !empty($workreap_settings['activity_email']) ? $workreap_settings['activity_email'] : false;

        if ($activity_email) {
            /* prepare data and send email */
            $is_email_send = 'no';
            if (class_exists('Workreap_Email_helper')) {
                if (class_exists('WorkreapTaskActivityNotify')) {
                    $email_helper = new WorkreapOrderStatuses();
                    $emailData    = array();
                    $emailData['sender_name']         = $user_name;
                    $emailData['receiver_name']       = $receiver_name;
                    $emailData['receiver_email']      = $receiver_email;
                    $emailData['task_name']           = $task_title;
                    $emailData['task_link']           = $task_link;
                    $emailData['order_id']            = $order_id;
                    $emailData['order_amount']        = $order_amount;
                    $emailData['login_url']           = $login_url;
                    $emailData['sender_comments']     = $content;
                    $emailData['notification_type']   = 'noty_order_activity_chat';
                    $emailData['sender_id']           = $freelancer_id; //freelancer id
                    $emailData['receiver_id']         = $employers_id; //employer id

                    // if message sender is employer then use freelancer email template or vise versa
                    if (!empty($user_type) && $user_type == 'employers'){
                        $email_helper->order_activities_freelancer_email($emailData);
                        $is_email_send = 'yes';
                    }else{
                        $email_helper->order_activities_employer_email($emailData);
                        $is_email_send = 'yes';
                    }

                    // if message sender is freelancer and marked message as final delivery
                    if ($user_type == 'freelancers' && !empty($message_type) && $message_type == 'final'){
                        // get employer's order detail page link
                        $activity_page_link	= Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $receiver_id, true, 'detail',$order_id);
                        $query_string       = 'activity_id='.$comment_id;
                        // append query string
                        $activity_page_link .= (parse_url($activity_page_link, PHP_URL_QUERY) ? '&' : '?').$query_string;
                        $emailData['employer_email']   = $receiver_email;
                        $emailData['employer_name']    = $receiver_name;
                        $emailData['freelancer_name']   = $user_name;
                        $emailData['activity_link'] = $activity_page_link;
                        $email_helper->order_complete_request_employer_email($emailData);
                        $is_email_send = 'yes';
                    }
                }
            }

            $notifyData						= array();
            $notifyDetails					= array();
            if ($user_type == 'freelancers' && !empty($message_type) && $message_type == 'final'){
                $notifyDetails['task_id']     	= $task_id;
                $notifyDetails['freelancer_id']   	= $linked_profile_id;
                $notifyDetails['employer_id']   	= $receiver_linked_profile_id;
                $notifyDetails['order_id']   	= $order_id;
                $notifyDetails['employer_amount']  = $order_amount;
                $notifyData['receiver_id']		= $receiver_id;
                $notifyData['type']			    = 'employer_order_request';
                $notifyData['linked_profile']	= $receiver_linked_profile_id;
                $notifyData['user_type']		= 'employers';
                $notifyData['post_data']		= $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
            } else {
                $notifyDetails['task_id']     	= $task_id;
                $notifyDetails['sender_id']   	= $sender_linked_profile_id;
                $notifyDetails['receiver_id']   = $receiver_linked_profile_id;
                $notifyDetails['order_id']   	= $order_id;
                $notifyDetails['sender_comments']= $content;
                $notifyData['receiver_id']		= $receiver_id;
                $notifyData['type']			    = 'user_activity';
                $notifyData['linked_profile']	= $receiver_linked_profile_id;
                $notifyData['user_type']		= $receiver_user_type;
                $notifyData['post_data']		= $notifyDetails;
                do_action('workreap_notification_message', $notifyData );
            }
            /* prepare data and send email end */
        }

        do_action('workreap_comments_activity', $comment_id);

        // prepare success response
        $json['comment_id']			    = $comment_id;
        $json['user_id']			    = intval( $user_id );
        $json['type'] 				    = 'success';
        $json['message'] 			    = esc_html__('Message Sent.', 'workreap');
        $json['message_desc'] 			    = esc_html__('Your message has been sent.', 'workreap');
        $json['content_message'] 	    = esc_html( wp_strip_all_tags( $content ) );
        $json['user_name'] 			    = $user_name;
        $json['date'] 				    = date_i18n(get_option('date_format'), strtotime($time));
        $json['img'] 				    = $avatar;
        $json['is_email_send'] 		    = $is_email_send;
        if( !empty($type) && $type === 'mobile' ){
            return $json;
        } else {
            wp_send_json($json);
        }

    }
    }
}

/**
 * create dispute for freelancer
 *
 */
if( !function_exists('workreapFreelancerCreateDispute') ){
    function workreapFreelancerCreateDispute($user_id=0,$request=array(),$type=''){
        global $workreap_settings;
        $order_id           = !empty($request['order_id']) ? intval($request['order_id']):'';
        $task_id            = !empty($request['task_id']) ? intval($request['task_id']):'';
        $dispute_issue      = !empty($request['dispute_issue']) ? esc_html($request['dispute_issue']):'';
        $dispute_details    = !empty($request['dispute-details']) ? sanitize_textarea_field($request['dispute-details']):'';
        //Create dispute
        $username   	        = workreap_get_username( $user_id );
        $linked_profile         = workreap_get_linked_profile_id($user_id);
        $dispute_title      	= get_the_title($task_id).' #'. $order_id;
        $dispute_args = array(
            'posts_per_page'    => -1,
            'post_type'         => array( 'disputes'),
            'orderby'           => 'ID',
            'order'             => 'DESC',
            'post_status'       => 'any',
            'suppress_filters'  => false,
            'meta_query'    => array(
                'relation'  => 'AND',
                array(
                    'key'       => '_dispute_order',
                    'value'     => $order_id,
                    'compare'   => '='
                )
            )
        );

        $dispute_is = get_posts($dispute_args);
        if( !empty( $dispute_is ) ){
            $json['type']           = "error";
            $json['message']        = 'Oops!';
            $json['message_desc']   = esc_html__("Refund request is already created.", 'workreap');
            if( !empty($type) && $type === 'mobile' ){
                return $json;
            } else {
                wp_send_json($json);
            }
        }
        $dispute_post  = array(
            'post_title'    => wp_strip_all_tags( $dispute_title ),
            'post_status'   => 'disputed',
            'post_content'  => $dispute_details,
            'post_author'   => $user_id,
            'post_type'     => 'disputes',
        );
        $dispute_id = wp_insert_post( $dispute_post );
        $post_type      = get_post_type($order_id);
        update_post_meta( $dispute_id, '_dispute_type',$post_type );

        $employer_id   = get_post_meta( $order_id, 'employer_id',true );
        $employer_id   = !empty($employer_id) ? intval($employer_id) : 0;
        $user_type  = apply_filters('workreap_get_user_type', $user_id );
        update_post_meta( $dispute_id, '_send_type', $user_type);
        update_post_meta( $dispute_id, '_send_by', $user_id);
        update_post_meta( $dispute_id, '_freelancer_id', $user_id);
        update_post_meta( $dispute_id, '_employer_id', $employer_id);
        update_post_meta( $dispute_id, '_dispute_key', $dispute_issue);
        update_post_meta( $dispute_id, '_dispute_order', $order_id);
        update_post_meta( $dispute_id, '_task_id', $task_id);
        update_post_meta( $order_id, 'dispute', 'yes');
        update_post_meta( $order_id, 'dispute_id', $dispute_id);
        if (class_exists('Workreap_Email_helper')) {
            $employer      = get_user_by( 'id', $employer_id );
            $employer_name = $employer->display_name;
            /* getting freelancer info */
            $freelancer         = get_user_by( 'id', $user_id );
            $freelancer_name    = $freelancer->display_name;
            /* getting product info */
            $product    = wc_get_product( $task_id );
            $task_name  = $product->get_title();
            /* getting task link */
            $task_link   =  get_permalink($task_id);
            /* getting dispute info */
            $order_total  = get_post_meta( $order_id, '_order_total', true );
            $order_total  = !empty($order_total) ? ($order_total) : 0;
            $emailData = array();
            $emailData['freelancer_name']       = $freelancer_name;
            $emailData['employer_name']        = $employer_name;
            $emailData['task_name']         = $task_name;
            $emailData['task_link']         = $task_link;
            $emailData['order_id']          = $order_id;
            $emailData['order_amount']      = $order_total;
            $status_freelancer_refund	= !empty( $workreap_settings['email_admin_new_dispute'] ) ? $workreap_settings['email_admin_new_dispute'] : '';
            if( !empty($status_freelancer_refund) ){
                if (class_exists('WorkreapDisputeStatuses')) {
                    $email_helper = new WorkreapDisputeStatuses();
                    $email_helper->dispute_received_admin_email($emailData);
                }
            }
        }

        do_action('workreap_after_submit_dispute', $dispute_id);

        $json['type']           = "success";
        $json['message']        =  esc_html__('Woohoo!','workreap');
        $json['message_desc']   = esc_html__("We have received your refund request, soon we will get back to you.", 'workreap');
        if( !empty($type) && $type === 'mobile' ){
            $json['dispute_id'] = $dispute_id;
            return $json;
        } else {
            wp_send_json($json);
        }
    }
}

/**
 * create dispute for employer
 *
 */
if( !function_exists('workreapEmployerCreateDispute') ){
    function workreapEmployerCreateDispute($user_id=0,$request=array(),$type=''){
        global $workreap_settings;
        $order_id           = !empty($request['order_id']) ? intval($request['order_id']):'';
        $dispute_is         = get_post_meta( $order_id, 'dispute', true);
        $order_data         = get_post_meta( $order_id, 'cus_woo_product_data', true );
        $order_data         = !empty($order_data) ? $order_data : array();

        $freelancer_id          = !empty($order_data['freelancer_id']) ? intval($order_data['freelancer_id']) : 0;
        $employer_id           = !empty($order_data['employer_id']) ? intval($order_data['employer_id']) : 0;
        $order_amount       = !empty($order_data['total_amount']) ? intval($order_data['total_amount']) : '' ;
        $task_id            = !empty($order_data['task_id']) ? intval($order_data['task_id']) : 0;

        $dispute_issue      = !empty($request['dispute_issue']) ? esc_html($request['dispute_issue']):'';
        $dispute_details    = !empty($request['dispute-details']) ? sanitize_textarea_field($request['dispute-details']):'';
        //Create dispute

        $linked_profile         = workreap_get_linked_profile_id($user_id,'','employers');
        $username   	        = workreap_get_username( $linked_profile );
        $dispute_title      	= get_the_title($task_id).' #'. $order_id;
        $user_type              = apply_filters('workreap_get_user_type', $user_id );
        $dispute_post  = array(
            'post_title'    => wp_strip_all_tags( $dispute_title ),
            'post_status'   => 'publish',
            'post_content'  => $dispute_details,
            'post_author'   => $user_id,
            'post_type'     => 'disputes',
        );
        $dispute_id     = wp_insert_post( $dispute_post );
        $post_type      = get_post_type($order_id);
        update_post_meta( $dispute_id, '_dispute_type',$post_type );
        update_post_meta( $dispute_id, '_sender_type', $user_type);
        update_post_meta( $dispute_id, '_send_by', $user_id);
        update_post_meta( $dispute_id, '_freelancer_id', $freelancer_id);
        update_post_meta( $dispute_id, '_employer_id', $employer_id);
        update_post_meta( $dispute_id, '_dispute_key', $dispute_issue);
        update_post_meta( $dispute_id, '_dispute_order', $order_id);
        update_post_meta( $dispute_id, '_task_id', $task_id);
        update_post_meta( $order_id, 'dispute', 'yes');
        update_post_meta( $order_id, 'dispute_id', $dispute_id);
        do_action( 'workreap_after_dispute_creation', $dispute_id );
        $employer          = get_user_by( 'id', $employer_id );
        $employer_name     = $employer->first_name;
        $freelancer         = get_user_by( 'id', $freelancer_id );
        $freelancer_name    = $freelancer->first_name;
        $product        = wc_get_product( $task_id );
        $task_name      = $product->get_title();
        $freelancer_info    = get_userdata($freelancer_id);
        $freelancer_email   = $freelancer_info->user_email;
        $employer_info     = get_userdata($employer_id);
        $employer_email    = $employer_info->user_email;
        $login_url      = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
        $task_link      = get_permalink($task_id);

        if (class_exists('Workreap_Email_helper')) {
            $blogname = get_option( 'blogname' );
            $emailData = array();
            $emailData['freelancer_name']       = $freelancer_name;
            $emailData['employer_name']        = $employer_name;
            $emailData['task_name']         = $task_name;
            $emailData['freelancer_email']      = $freelancer_email;
            $emailData['employer_email']       = $employer_email;
            $emailData['task_link']         = $task_link;
            $emailData['order_id'] 	        = $order_id;
            $emailData['order_amount']      = $order_amount;
            $emailData['employer_comments']    = $dispute_details;
            $emailData['login_url']         = $login_url;
            //Welcome Email
            $status_freelancer_refund	= !empty( $workreap_settings['email_new_refund_freelancer'] ) ? $workreap_settings['email_new_refund_freelancer'] : ''; //email freelancer new refend
            if(isset($status_freelancer_refund) && !empty($status_freelancer_refund )){
                if (class_exists('WorkreapRefundsStatuses')) {
                    $email_helper = new WorkreapRefundsStatuses();
                    $email_helper->refund_freelancer_email($emailData); //email to freelancer
                    $freelancer_profile_id              = workreap_get_linked_profile_id($freelancer_id, '', 'freelancers');
                    $notifyData						= array();
                    $notifyDetails					= array();
                    $notifyDetails['task_id']               = $task_id;
                    $notifyDetails['post_link_id']          = $task_id;
                    $notifyDetails['employer_comments']        = $dispute_details;
                    $notifyDetails['freelancer_order_amount']   = $order_amount;
                    $notifyDetails['order_id']              = $order_id;
                    $notifyDetails['dispute_id']            = $dispute_id;
                    $notifyDetails['employer_id']              = workreap_get_linked_profile_id($employer_id, '', 'employers');
                    $notifyDetails['freelancer_id']             = $freelancer_profile_id;
                    $notifyData['receiver_id']		        = $freelancer_id;
                    $notifyData['type']			            = 'refund_request';
                    $notifyData['linked_profile']	        = $freelancer_profile_id;
                    $notifyData['user_type']		        = 'freelancers';
                    $notifyData['post_data']		        = $notifyDetails;
                    do_action('workreap_notification_message', $notifyData );
                }
            }
        }
        $json['type']           = "success";
        $json['message']        = esc_html__('Woohoo!','workreap');
        $json['message_desc']   = esc_html__("We have received your refund request, soon we will get back to you.", 'workreap');
        if( !empty($type) && $type === 'mobile'){
            return $json;
        } else {
            wp_send_json($json);
        }
    }
}

/**
 * Freelancer update dispute status
 *
 */
if( !function_exists('workreapUpdateDisputeStatus') ){
    function workreapUpdateDisputeStatus($dispute_id='',$dispute_status='',$type=''){
        global $workreap_settings, $current_user;
        $args   = array(
            'ID'                => $dispute_id,
            'post_status'       => $dispute_status,
        );
        wp_update_post($args);

        $dispute_type   = get_post_meta($dispute_id,'_dispute_type',true);
        if( !empty($dispute_type) && $dispute_type === 'proposals'){

        $receiver_profile_id = 0;
        $project_id         = get_post_meta( $dispute_id, '_project_id',true );
        $get_user_type	    = apply_filters('workreap_get_user_type', $current_user->ID );
        if( !empty($get_user_type) && $get_user_type === 'freelancers' ){
            $freelancer_profile_id          = !empty($current_user->ID) ? workreap_get_linked_profile_id($current_user->ID,'','freelancers') : 0;
            $receiver_profile_id        = !empty($freelancer_profile_id) ? intval($freelancer_profile_id) : 0;
        } else if( !empty($get_user_type) && $get_user_type === 'employers' ){
            $employer_profile_id       = !empty($current_user->ID) ? workreap_get_linked_profile_id($current_user->ID,'','employers') : 0;
            $receiver_profile_id    = !empty($employer_profile_id) ? intval($employer_profile_id) : 0;
        }
        /* Email to admin on project dispute request by freelancer/employer */
        if(class_exists('Workreap_Email_helper')){
            $emailData                              = array();
            $emailData['user_name']                 = workreap_get_username($receiver_profile_id);
            $emailData['project_title']             = get_the_title($project_id);
            $emailData['admin_dispute_link']        = Workreap_Profile_Menu::workreap_profile_admin_menu_link('project', workreap_get_admin_user_id(), true, 'dispute', $dispute_id);
            if (class_exists('WorkreapProjectDisputes')) {
                $email_helper = new WorkreapProjectDisputes();
                $email_helper->dispute_project_request_admin_email($emailData);
            }
        }

        } else {
            /* Email to admin */
            $employer_id   = get_post_meta( $dispute_id, '_send_by', true);
            $freelancer_id  = get_post_meta( $dispute_id, '_freelancer_id', true);

            /* getting employer info */
            $employer      = get_user_by( 'id', $employer_id );
            $employer_name = $employer->display_name;
            /* getting freelancer info */
            $freelancer         = get_user_by( 'id', $freelancer_id );
            $freelancer_name    = $freelancer->display_name;

            /* getting dispute info */
            $dispute_order  = get_post_meta( $dispute_id, '_dispute_order', true );
            $dispute_order  = !empty($dispute_order) ? intval($dispute_order) : 0;
            $post_type      = get_post_type( $dispute_order );
            if( !empty($post_type) && $post_type === 'proposals' ){
                $order_total  = get_post_meta( $dispute_id, '_order_total', true );
                $order_total  = !empty($order_total) ? ($order_total) : 0;
            } else {
                $task_id    = get_post_meta( $dispute_id, '_task_id', true);
                /* getting product info */
                $product    = wc_get_product( $task_id );
                $task_name  = $product->get_title();
                /* getting task link */
                $task_link   =  get_permalink($task_id);
                $order_total  = get_post_meta( $dispute_order, '_total_amount', true );
                $order_total  = !empty($order_total) ? ($order_total) : 0;
            }
            if (class_exists('Workreap_Email_helper')) {
                $login_url    = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
                $emailData = array();
                $emailData['freelancer_name']       = $freelancer_name;
                $emailData['employer_name']        = $employer_name;

                if( !empty($post_type) && $post_type === 'proposals' ){

                } else {
                    $emailData['task_name']         = $task_name;
                    $emailData['task_link']         = $task_link;
                    $emailData['order_id']          = $dispute_order;
                }

                $emailData['order_amount']      = $order_total;
                $emailData['login_url']         = $login_url;
                //Welcome Email

                $status_freelancer_refund	= !empty( $workreap_settings['email_admin_new_dispute'] ) ? $workreap_settings['email_admin_new_dispute'] : '';

                if( $status_freelancer_refund == true ){
                    if (class_exists('WorkreapDisputeStatuses')) {
                        $email_helper = new WorkreapDisputeStatuses();
                        $email_helper->dispute_received_admin_email($emailData); //email to freelancer
                        do_action('notification_message', $emailData );
                    }
                }
            }
        }
        $json['type']           = "success";
        $json['message']        = esc_html__('Woohoo!','workreap');
        $json['message_desc']   = esc_html__("We have received your dispute, soon we will get back to you.", 'workreap');
        if( !empty($type) && $type === 'mobile'){
            return $json;
        } else {
            wp_send_json($json);
        }
    }
}

/**
 * Task completed
 *
 */
if( !function_exists('workreapTaskComplete') ){
    function workreapTaskComplete($user_id=0,$request=array(),$type=''){
        global $workreap_settings;
        $gmt_time		= current_time( 'mysql', 1 );
        $task_id        = !empty($request['task_id']) ? intval($request['task_id']) : 0;
        $order_id       = !empty($request['order_id']) ? intval($request['order_id']) : 0;
        $type           = !empty($request['type']) ? sanitize_text_field($request['type']) : '';
        $post_author    = get_post_meta( $order_id, 'employer_id',true );
        if( !empty($task_id) && !empty($order_id) ){

            if( !empty($type) && $type == 'rating' ){
                $rating_details = !empty($request['rating_details']) ? sanitize_textarea_field($request['rating_details']) : '';
                $rating_title   = !empty($request['rating_title']) ? sanitize_text_field($request['rating_title']) : '';
                $rating         = !empty($request['rating']) ? sanitize_text_field($request['rating']) : '';
                workreap_complete_task_ratings($order_id,$task_id,$rating,$rating_title,$rating_details,$user_id);
            }
            update_post_meta( $order_id, '_task_status' , 'completed');
            update_post_meta( $order_id, '_task_completed_time', $gmt_time );
            /* getting task detail */
            $task_name    = get_the_title($task_id);
            $task_link    = get_permalink( $task_id );
            $login_url    = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();
            /* getting employer name */
            $employer_profile_id   = workreap_get_linked_profile_id($post_author,'','employers');
            $employer_name 		= workreap_get_username($employer_profile_id);
            /* getting freelancer name and email */
            $freelancer_id          = get_post_field( 'post_author', $task_id );
            $freelancer_profile_id  = workreap_get_linked_profile_id($freelancer_id,'','freelancers');
            $freelancer_name 		= workreap_get_username($freelancer_profile_id);
            $freelancer_email 	    = get_userdata( $freelancer_id )->user_email;
            /* getting order detail */
            $order 		    = wc_get_order($order_id);
            $order_price = $order->get_total();
            $order_amount = !empty($order_price) ? $order_price : 0;

            if(class_exists('Workreap_Email_helper')){

                if(class_exists('WorkreapOrderStatuses')){

                    if( $workreap_settings['email_odr_cmpt_freelancer'] == true ){
                        $emailData                        = array();
                        $emailData['freelancer_email']        = $freelancer_email;
                        $emailData['freelancer_name']         = $freelancer_name;
                        $emailData['employer_name']          = $employer_name;
                        $emailData['task_name']           = $task_name;
                        $emailData['task_link']           = $task_link;
                        $emailData['order_id']            = $order_id;
                        $emailData['login_url']           = $login_url;
                        $emailData['order_amount']        = $order_amount;
                        $emailData['employer_comments']      = $rating_details;
                        $emailData['employer_rating']        = $rating;
                        $emailData['notification_type']   = 'noty_order_completed';
                        $emailData['sender_id']           = $freelancer_id; //freelancer id
                        $emailData['receiver_id']         = $post_author; //employer id
                        $email_helper                     = new WorkreapOrderStatuses();
                        $email_helper->order_completed_freelancer_email($emailData);
                    }
                }
            }


            do_action('workreap_complete_task_order_activity', $task_id, $order_id);

            $notifyData						= array();
            $notifyDetails					= array();
            $notifyDetails['task_id']       = $task_id;
            $notifyDetails['post_link_id']  = $task_id;
            $notifyDetails['employer_comments']= $rating_details;
            $notifyDetails['employer_rating']  = $rating;
            $notifyDetails['order_id']      = $order_id;
            $notifyDetails['employer_id']      = workreap_get_linked_profile_id($user_id, '', 'employers');
            $notifyDetails['freelancer_id']     = $freelancer_profile_id;
            $notifyData['receiver_id']		= $freelancer_id;
            $notifyData['type']			    = 'order_completed';
            $notifyData['linked_profile']	= $freelancer_profile_id;
            $notifyData['user_type']		= 'freelancers';
            $notifyData['post_data']		= $notifyDetails;
            do_action('workreap_notification_message', $notifyData );
			do_action('workreap_completed_task_badge', $freelancer_profile_id,$order_id,$type);


            $json['type']             = 'success';
            $json['message']     = esc_html__('Task completed', 'workreap');
            $json['message_desc']     = esc_html__('You have completed this task.', 'workreap');
            if( !empty($type) && $type === 'mobile'){
                return $json;
            } else {
                wp_send_json($json);
            }
        }
    }
}

/**
 * Task cancelled
 *
 */
if( !function_exists('workreapCancelledTask') ){
    function workreapCancelledTask($user_id=0,$request=array(),$type=''){
        global $workreap_settings;
        $task_id        = !empty($request['task_id']) ? intval($request['task_id']) : 0;
        $order_id       = !empty($request['order_id']) ? intval($request['order_id']) : 0;
        $details        = !empty($request['details']) ? sanitize_textarea_field($request['details']) : '';

        $gmt_time		 = current_time( 'mysql', 1 );
        update_post_meta( $order_id, '_task_status' , 'cancelled');
        update_post_meta( $order_id, '_task_cancellation_time', $gmt_time );
        update_post_meta( $order_id, '_task_cancellation_reason', $details );
        update_post_meta( $order_id, '_task_cancelled_by', $user_id );
        /* Send Email on task canceled */
        if(class_exists('Workreap_Email_helper')){

            if(class_exists('WorkreapTaskStatuses')){
                if( $workreap_settings['email_task_rej_freelancer'] == true ){
                    /* set data for email */
                    $task_name          = get_the_title($task_id);
                    $task_link          = get_permalink( $task_id );
                    /* getting freelancer name and email */
                    $freelancer_id          = get_post_field( 'post_author', $task_id );
                    $freelancer_profile_id  = workreap_get_linked_profile_id($freelancer_id, '', 'freelancers');
                    $freelancer_name 		= workreap_get_username($freelancer_profile_id);
                    $freelancer_email 	    = get_userdata( $freelancer_id )->user_email;

                    $emailData = array();
                    $emailData['freelancer_email']        = $freelancer_email;
                    $emailData['freelancer_name']         = $freelancer_name;
                    $emailData['task_name']           = $task_name;
                    $emailData['task_link']           = $task_link;
                    $emailData['employer_feedback']      = $details;
                    $email_helper = new WorkreapTaskStatuses();
                    $email_helper->reject_task_freelancer_email($emailData);
                }
            }

        }

        $notifyData						= array();
        $notifyDetails					= array();
        $notifyDetails['task_id']       = $task_id;
        $notifyDetails['post_link_id']  = $task_id;
        $notifyDetails['employer_comments']= $details;
        $notifyDetails['order_id']      = $order_id;
        $notifyDetails['employer_id']      = workreap_get_linked_profile_id($user_id, '', 'employers');
        $notifyDetails['freelancer_id']     = $freelancer_profile_id;
        $notifyData['receiver_id']		= $freelancer_id;
        $notifyData['type']			    = 'order_rejected';
        $notifyData['linked_profile']	= $freelancer_profile_id;
        $notifyData['user_type']		= 'freelancers';
        $notifyData['post_data']		= $notifyDetails;
        do_action('workreap_notification_message', $notifyData );
        do_action('workreap_after_cancelled_task', $order_id );
		do_action('workreap_completed_task_badge', $freelancer_profile_id,$order_id,'');
        $json['type']           = 'success';
        $json['message_desc']    = esc_html__('You have successfully cancelled this task.', 'workreap');
        wp_send_json($json);
    }
}

/**
 * Check any prerequisites for our REST request.
 */
function check_prerequisites($userId='') {
    if ( defined( 'WC_ABSPATH' ) ) {
        // WC 3.6+ - Cart and other frontend functions are not included for REST requests.

        include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
        include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
        include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
        include_once WC_ABSPATH . 'includes/wc-order-functions.php';
        include_once WC_ABSPATH . 'includes/wc-order-item-functions.php';
        include_once WC_ABSPATH . 'includes/class-wc-order.php';
    }

    if ( null === WC()->session ) {
        $session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );

        WC()->session = new $session_class();
        WC()->session->init();
    }
    if ( null === WC()->customer ) {
        WC()->customer = new WC_Customer( $userId, true );
    }
    if ( null === WC()->cart ) {
        WC()->cart = new WC_Cart();
        // We need to force a refresh of the cart contents from session here (cart contents are normally refreshed on wp_loaded, which has already happened by this point).
        WC()->cart->get_cart();
    }
}

/**
 * Task update status
 *
 */
if( !function_exists('workreapUpdateStatus') ){
    function workreapUpdateStatus($task_id=0){
        global $workreap_settings;
        $service_status             = !empty($workreap_settings['service_status']) ? $workreap_settings['service_status'] : '';
        $resubmit_service_status    = !empty($workreap_settings['resubmit_service_status']) ? $workreap_settings['resubmit_service_status'] : 'no';

        $task_status                = get_post_meta( $task_id, '_post_task_status',true );
        $task_status                = !empty($task_status) ? $task_status : '';

        $post_status                = get_post_status($task_id);
        $post_status                = !empty($post_status) ? $post_status : '';

        if( !empty($service_status) && $service_status === 'pending' && !empty($resubmit_service_status) && $resubmit_service_status === 'yes'){
            if( empty($task_status) || $task_status != 'rejected'){
                update_post_meta( $task_id, '_post_task_status', 'pending' );
                if(!empty($post_status) && $post_status != 'draft'){
                    $service_post = array(
                        'ID'            => $task_id,
                        'post_status'   => $service_status,
                    );
                    wp_update_post( $service_post );
                }
            }
        } else if( !empty($service_status) && $service_status === 'publish' && !empty($resubmit_service_status) && $resubmit_service_status === 'no'){
            update_post_meta( $task_id, '_post_task_status', 'publish' );
        }

    }
}

/**
 * Employer email
 * @return slug
 */
if (!function_exists('workreap_employer_email')) {
	function workreap_employer_email(){
		$employer_email = array(

			/* Employer Email on Account Approval Request */
				array(
					'id'      => 'divider_approvel_request_employer_registration_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Account approval request', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_acc_approv_employer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to freelancer on registration.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'employer_email_req_approvel_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Thank you for registration at {{sitename}}', 'workreap'),
					'required'  => array('email_acc_approv_employer','equals','1')
				),
				array(
					'id'      => 'divider_employer_email_request_approvel_information',
					'desc'    => wp_kses( __( '{{name}} — To display the user name.<br>{{email}} — To display the user email.<br>{{password}} — To display the user password.<br>{{sitename}} — To display the sitename.<br>','workreap' ),
					array(
						'a'	=> array(
							'href'  => array(),
							'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_acc_approv_employer','equals','1')
				),
				array(
					'id'      	=> 'employer_email_req_approvel_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{name}},', 'workreap'),
					'required'  => array('email_acc_approv_employer','equals','1')
				),
				array(
					'id'        => 'employer_email_request_approvel_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Thank you for the registration at "{{sitename}}" Your account will be approved  after the verification.', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_acc_approv_employer','equals','1')
				),

			/* Employer Email on Account approved */
				array(
					'id'      => 'divider_approved_employer_account_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Account approval confirmation', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_approv_confirm_employer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to freelancer on account approvel.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      => 'employer_mail_req_approved_subject',
					'type'    => 'text',
					'title'   => esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' => esc_html__( 'Account approved','workreap'),
					'required'  => array('email_approv_confirm_employer','equals','1')

				),
				array(
					'id'      => 'divider_employer_approved_information',
					'desc'    => wp_kses( __( '{{name}} — To display the user name.<br>{{email}} — To display the user email.<br>{{sitename}} — To display the sitename.<br>', 'workreap' ),
					array(
						'a'	=> array(
							'href'  => array(),
							'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
						) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_approv_confirm_employer','equals','1')
				),
				array(
					'id'      => 'employer_email_req_approved_greeting',
					'type'    => 'text',
					'title'   => esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' => esc_html__( 'Hello {{name}},','workreap'),
					'required'  => array('email_approv_confirm_employer','equals','1')
				),
				array(
					'id'        => 'approved_employer_account_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Congratulations!<br/>Your account has been approved by the admin.', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_approv_confirm_employer','equals','1')
				),

				/* Buer Email on Post project */
				array(
					'id'      => 'divider_post_project_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Post a project', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_post_project',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to employer on post a project.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'post_project_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Project submission','workreap' ),
					'required'  => array('email_post_project','equals','1')

				),
				array(
					'id'	=> 'divider_post_task_information',
					'desc'  => wp_kses( __( '{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project name.<br>{{project_link}} — To display the project link.<br>','workreap' ),
					array(
						'a'       => array(
							'href'  => array(),
							'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_post_project','equals','1')
				),
				array(
					'id'      	=> 'post_project_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array('email_post_project','equals','1')

				),
				array(
					'id'        => 'post_project_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Thank you for submitting the project, we will review and approve the project after the review.', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_post_project','equals','1')
				),

				/* Employer Email on Project Approved */
				array(
					'id'      => 'divider_project_approved_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Project approved', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_project_approve',
					'type'     => 'switch',
					'title'    => esc_html__( 'Send email', 'workreap' ),
					'subtitle' => esc_html__( 'Email to employer on posted task approvel.', 'workreap' ),
					'default'  => true,
				),
				array(
					'id'      	=> 'project_approved_employer_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Project approved!','workreap' ),
					'required'  => array('email_project_approve','equals','1')
				),
				array(
					'id'      => 'divider_project_approved_information',
					'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project name.<br>{{project_link}} — To display the project link.<br>','workreap' ),
					array(
						'a'       => array(
							'href'  => array(),
							'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_project_approve','equals','1')
				),
				array(
					'id'      	=> 'project_approved_project_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap'),
					'required'  => array('email_project_approve','equals','1')

				),
				array(
					'id'        => 'project_approved_project_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Woohoo! Your project {{project_title}} has been approved.<br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_project_approve','equals','1')

				),

			/* Employer Email on Project Rejected */
				array(
					'id'      => 'divider_project_rejected_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Employer project rejected', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_project_rej_employer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to employer on project rejected.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'project_rejected_employer_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Project rejection','workreap'),
					'required'  => array('email_project_rej_employer','equals','1')
				),
				array(
					'id'      => 'divider_project_rejected_information',
					'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project name.<br>{{project_link}} — To display the project link.<br>','workreap' ),
				array(
						'a'       => array(
							'href'  => array(),
							'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_project_rej_employer','equals','1')
				),
				array(
					'id'      	=> 'project_rejected_employer_greeting',
					'type'    	=> 'text',
					'title'   	=> 	esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> 	esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => 	array('email_project_rej_employer','equals','1')
				),
				array(
					'id'        => 'project_rejected_employer_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Oho! Your project {{project_title}} has been rejected.<br/> Please click on the button below to view the project.<br/> {{project_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     =>  esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  =>  array('email_project_rej_employer','equals','1')
				),


			/* Employer Email on Order */
				array(
					'id'      => 'divider_new_order_employer_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'New order', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_new_order_employer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to freelancer on new order.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'new_order_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'New order','workreap'),
					'required'  => array('email_new_order_employer','equals','1')
				),
				array(
					'id'      => 'new_order_employer_information',
					'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>','workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_new_order_employer','equals','1')

				),
				array(
					'id'      	=> 'new_order_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap'),
					'required'  => array('email_new_order_employer','equals','1')

				),
				array(
					'id'        => 'new_order_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Thank you so much for ordering my task. I will get in touch with you shortly.', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_new_order_employer','equals','1')

				),

			/* Employer Email on Order Complete Request */
				array(
					'id'      => 'divider_order_complete_request_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Order complete request', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_order_complete_freelancer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to freelancer on new order.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'order_complete_request_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Task completed request','workreap'),
					'required'  => array('email_order_complete_freelancer','equals','1')

				),
				array(
					'id'      => 'divider_order_complete_request_information',
					'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{login_url}} — To display the login url.<br>{{order_amount}} — To display the order amount.<br>','workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
						) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_order_complete_freelancer','equals','1')
				),
				array(
					'id'      	=> 'order_complete_request_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeeting', 'workreap' ),
					'desc'    	=> esc_html__( 'Please add text.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap'),
					'required'  => array('email_order_complete_freelancer','equals','1')
				),
				array(
					'id'        => 'order_complete_request_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'The freelancer “{{freelancer_name}}” has sent you the final delivery for the order #{{order_id}}<br/> You can accept or decline this. Please login to the site and take a quick action<br/> {{login_url}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_order_complete_freelancer','equals','1')
				),

			/* Employer Email on Order Activity */
				array(
					'id'      => 'divider_employer_order_activity_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Order activity', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_ord_activity_employer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to employer on order activity.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'order_activity_employer_subject',
					'type'    	=> 'text',
					'default'	=> esc_html__( 'Order activity', 'workreap' ),
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'required'  => array('email_ord_activity_employer','equals','1')
				),
				array(
					'id'      => 	'divider_employer_order_activity_information',
					'desc'    =>	wp_kses( __( '{{sender_name}} — To display the email sender name.<br>{{receiver_name}} — To display the email receiver name.<br>{{task_name}} — To display task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the task id.<br>{{order_amount}} — To display the task/order amount.<br>{{login_url}} — To display the site login url.<br>{{sender_comments}} — To display the sender comments/message.<br>','workreap' ),

					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_ord_activity_employer','equals','1')
				),
				array(
					'id'      	=> 'order_activity_employer_gretting',
					'type'    	=> 'text',
					'default' 	=> esc_html__( 'Hello {{receiver_name}}', 'workreap' ),
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'required'  => array('email_ord_activity_employer','equals','1')
				),
				array(
					'id'        => 'order_activity_employer_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'You have received a note from the "{{sender_name}}" on the ongoing task "{{task_name}}" against the order #{{order_id}} <br/>{{sender_comments}} <br/>You can login to take a quick action.<br/>{{login_url}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_ord_activity_employer','equals','1')

				),

			/* Employer Email on Refund Approved */
				array(
					'id'      => 'divider_employer_refund_approved_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Employer refund approved', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_refund_approv_employer',
					'type'     => 'switch',
					'title'    => esc_html__( 'Send email', 'workreap' ),
					'subtitle' => esc_html__( 'Email to employer on refund approve.', 'workreap' ),
					'default'  => true,
				),
				array(
					'id'      	=> 'employer_approved_refund_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Refund approved','workreap'),
					'required'  => array('email_refund_approv_employer','equals','1')

				),
				array(
					'id'      => 'divider_approved_employer_refund_information',
					'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
						) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_refund_approv_employer','equals','1')
				),
				array(
					'id'      	=> 'employer_approved_refund_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap'),
					'required'  => array('email_refund_approv_employer','equals','1')
				),
				array(
					'id'        => 'approved_employer_refund_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Congratulations!<br/>Your refund request has been approved by the "{{freelancer_name}}" against the order #{{order_id}}.', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_refund_approv_employer','equals','1')
				),

			/* Employer Email on Refund Declined */
				array(
					'id'      => 'divider_order_employer_refund_declined_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Employer refund declined', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_refund_declined_employer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to employer on refund request declined.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'employer_declined_refund_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Refund declined','workreap'),
					'required'  => array('email_refund_declined_employer','equals','1')
				),
				array(
					'id'      => 'divider_employer_declined_refund_information',
					'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>','workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
						) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array('email_refund_declined_employer','equals','1')
				),
				array(
					'id'      	=> 'employer_declined_refund_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap'),
					'required'  => array('email_refund_declined_employer','equals','1')

				),
				array(
					'id'        => 'declined_employer_refund_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Your refund request has been declined by the "{{freelancer_name}}" against the order #{{order_id}} <br/>If you think that this was a valid request then you can raise a dispute from the ongoing task page.<br/>{{login_url}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array('email_refund_declined_employer','equals','1')
				),

			/* Employer Email on Refund Comment */
				array(
					'id'      => 'divider_order_refund_employer_comment_templates',
					'type'    => 'info',
					'title'   => esc_html__( 'Refund comment', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_refund_comment_employer',
					'type'     => 'switch',
					'title'    => esc_html__('Send email', 'workreap'),
					'subtitle' => esc_html__('Email to employer on refund comment.', 'workreap'),
					'default'  => true,
				),
				array(
					'id'      	=> 'refund_employer_comment_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'A new comment on refund request','workreap'),
					'required'  => array( 'email_refund_comment_employer','equals','1')
				),
				array(
					'id'      => 'divider_declined_order_employer_refund_information',
					'desc'    => wp_kses( __( '{{sender_name}} — To display the sender name.<br>{{receiver_name}} — To display the receiver name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>{{sender_comments}} — To display the sender comment.<br>','workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'email_refund_comment_employer','equals','1')
				),
				array(
					'id'      	=> 'refund_employer_comment_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{receiver_name}},','workreap'),
					'required'  => array( 'email_refund_comment_employer','equals','1')
				),
				array(
					'id'        => 'refund_employer_comment_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'The “{{sender_name}}” has left some comments on the refund request against the order #{{order_id}}<br/> {{sender_comments}}<br/> {{login_url}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'email_refund_comment_employer','equals','1')
				),

			/* Employer Email on Dispute Resolved */
				array(
					'id'      => 'divider_disputes_resolved_employer_templates',
					'type'    => 'info',
					'title'   =>  esc_html__( 'Dispute resolved employer', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_dispt_resolve_employer',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email to employer on refund comment.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'disputes_resolved_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Dispute resolved','workreap'),
					'required'  => array( 'email_dispt_resolve_employer','equals','1')

				),
				array(
					'id'      => 'divider_disputes_resolved_employer_information',
					'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'email_dispt_resolve_employer','equals','1')
				),
				array(
					'id'      	=> 'disputes_resolved_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array( 'email_dispt_resolve_employer','equals','1')

				),
				array(
					'id'        => 'disputes_resolved_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Congratulations! We have gone through the dispute and resolved the dispute in your favor. The amount has been added to your wallet, you can try to hire someone else.', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content (dispute win)', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'email_dispt_resolve_employer','equals','1')
				),

				/* Employer Email on Dispute Canceled/resolve not in your favour */
				array(
					'id'      => 'divider_disputes_cancelled_employer_templates',
					'type'    => 'info',
					'title'   =>  esc_html__( 'Dispute not in your favour', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_disputes_cancelled_employer',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email to employer on resolve dispute.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'disputes_cancelled_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Dispute not in your favour','workreap'),
					'required'  => array( 'email_disputes_cancelled_employer','equals','1')

				),
				array(
					'id'      => 'divider_disputes_cancelled_employer_information',
					'desc'    => wp_kses( __( '{{employer_name}} — To display the freelancer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>'
								, 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'email_disputes_cancelled_employer','equals','1')
				),
				array(
					'id'      	=> 'disputes_cancelled_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array( 'email_disputes_cancelled_employer','equals','1')

				),
				array(
					'id'        => 'disputes_cancelled_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Dispute resolve by admin but not in your favour.', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content (dispute resolved)', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'email_disputes_cancelled_employer','equals','1')
				),

				/* Employer Email on submit proposal */
				array(
					'id'      => 'divider_submit_proposal_employer_templates',
					'type'    => 'info',
					'title'   =>  esc_html__( 'Submit proposal', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_submit_proposal_employer',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email to employer on submit proposal.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'submit_proposal_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Submit Proposal','workreap'),
					'required'  => array( 'email_submit_proposal_employer','equals','1')
				),
				array(
					'id'      => 'submit_proposal_employer_information',
					'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>{{freelancer_name}} — To display the freelancer name.<br>{{project_title}} — To display the project title.<br>{{proposal_link}} — To display the proposal link.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'email_submit_proposal_employer','equals','1')
				),
				array(
					'id'      	=> 'submit_proposal_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array( 'email_submit_proposal_employer','equals','1')
				),
				array(
					'id'        => 'submit_proposal_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( '{{freelancer_name}} submit a new proposal on {{project_title}} Please click on the button below to view the proposal. {{proposal_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'email_submit_proposal_employer','equals','1')
				),
				/* Employer Email on milestone approval request */
				array(
					'id'      => 'divider_req_milestone_approval_employer_templates',
					'type'    => 'info',
					'title'   =>  esc_html__( 'Milestone approval request', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_req_milestone_approval_employer',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email to employer on milestone approval.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'req_milestone_approval_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Milestone approval request','workreap'),
					'required'  => array( 'email_req_milestone_approval_employer','equals','1')
				),
				array(
					'id'      => 'req_milestone_approval_employer_information',
					'desc'    => wp_kses( __( '{{employer_name}} — To display the employer name.<br>{{freelancer_name}} — To display the freelancer name.<br>{{project_title}} — To display the project title.<br>{{milestone_title}} — To display the milestone title.<br>{{milestone_link}} — To display the milestone link.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'email_req_milestone_approval_employer','equals','1')
				),
				array(
					'id'      	=> 'req_milestone_approval_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array( 'email_req_milestone_approval_employer','equals','1')
				),
				array(
					'id'        => 'req_milestone_approval_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'A new milestone {{milestone_title}} of {{project_title}} approval received from {{freelancer_name}}<br/>Please click on the button below to view the milestone.<br/>{{milestone_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'email_req_milestone_approval_employer','equals','1')
				),
				/* Employer Email on new project milestone */
				array(
					'id'      => 'divider_new_project_milestone_employer_templates',
					'type'    => 'info',
					'title'   =>  esc_html__( 'New project milestone', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_new_project_milestone_employer_switch',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email to employer on new project milestone.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'new_project_milestone_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Project new milestone','workreap'),
					'required'  => array( 'email_new_project_milestone_employer_switch','equals','1')
				),
				array(
					'id'      => 'new_project_milestone_employer_information',
					'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project title.<br>{{project_link}} — To display the project link.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'email_new_project_milestone_employer_switch','equals','1')
				),
				array(
					'id'      	=> 'new_project_milestone_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array( 'email_new_project_milestone_employer_switch','equals','1')
				),
				array(
					'id'        => 'new_project_milestone_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( '{{freelancer_name}} add new milestone for the project {{project_title}}<br/>Please click on the button below to view the project history.<br/>{{project_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'email_new_project_milestone_employer_switch','equals','1')
				),

				/* Employer Email on project refund request decline by freelancer */
				array(
					'id'      => 'divider_refund_project_request_decline_employer_templates',
					'type'    => 'info',
					'title'   =>  esc_html__('Project refund request decline', 'workreap'),
					'style'   => 'info',
				),
				array(
					'id'       => 'refund_project_request_decline_employer_switch',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email to employer on refund request.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'refund_project_request_decline_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Project refund decline','workreap'),
					'required'  => array( 'refund_project_request_decline_employer_switch','equals','1')
				),
				array(
					'id'      => 'refund_project_request_decline_employer_information',
					'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{dispute_link}} — To display the dispute link.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'refund_project_request_decline_employer_switch','equals','1')
				),
				array(
					'id'      	=> 'refund_project_request_decline_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array( 'refund_project_request_decline_employer_switch','equals','1')
				),
				array(
					'id'        => 'refund_project_request_decline_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Oho! A dispute has been declined by {{freelancer_name}}<br/>Please click on the button below to view the dispute details.<br/>{{dispute_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'refund_project_request_decline_employer_switch','equals','1')
				),

				/* Employer Email on project refund request approve by freelancer */
				array(
					'id'      => 'divider_refund_project_request_approved_employer_templates',
					'type'    => 'info',
					'title'   =>  esc_html__('Project refund request approved', 'workreap'),
					'style'   => 'info',
				),
				array(
					'id'       => 'refund_project_request_approved_employer_switch',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email to employer on refund request approved.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'refund_project_request_approved_employer_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Project refund approved','workreap'),
					'required'  => array( 'refund_project_request_approved_employer_switch','equals','1')
				),
				array(
					'id'      => 'refund_project_request_approved_employer_information',
					'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{dispute_link}} — To display the dispute link.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'refund_project_request_approved_employer_switch','equals','1')
				),
				array(
					'id'      	=> 'refund_project_request_approved_employer_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{employer_name}},','workreap' ),
					'required'  => array( 'refund_project_request_approved_employer_switch','equals','1')
				),
				array(
					'id'        => 'refund_project_request_approved_employer_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'Woohoo! {{freelancer_name}} approved dispute refund request in your favour.<br/>Please click on the button below to view the dispute details.<br/>{{dispute_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__( 'Email content', 'workreap' ),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'refund_project_request_approved_employer_switch','equals','1')
				),


				/* Project activity email to receiver */
				array(
					'id'      => 'divider_project_activity_receiver_templates',
					'type'    => 'info',
					'title'   =>  esc_html__( 'Project activity', 'workreap' ),
					'style'   => 'info',
				),
				array(
					'id'       => 'email_project_activity_receiver_switch',
					'type'     => 'switch',
					'title'    =>  esc_html__('Send email', 'workreap'),
					'subtitle' =>  esc_html__('Email on project activity.', 'workreap'),
					'default'  =>  true,
				),
				array(
					'id'      	=> 'project_activity_receiver_email_subject',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Subject', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
					'default' 	=> esc_html__( 'Project activity','workreap'),
					'required'  => array( 'email_project_activity_receiver_switch','equals','1')
				),
				array(
					'id'      => 'project_activity_receiver_information',
					'desc'    => wp_kses( __( '{{sender_name}} — To display the sender name.<br>{{receiver_name}} — To display the receiver name.<br>{{project_title}} — To display the project title.<br>{{project_link}} — To display the project link.<br>', 'workreap' ),
					array(
							'a'       => array(
								'href'  => array(),
								'title' => array()
							),
							'br'      => array(),
							'em'      => array(),
							'strong'  => array(),
					) ),
					'title'     => esc_html__( 'Email variables', 'workreap' ),
					'type'      => 'info',
					'class'     => 'dc-center-content',
					'icon'      => 'el el-info-circle',
					'required'  => array( 'email_project_activity_receiver_switch','equals','1')
				),
				array(
					'id'      	=> 'project_activity_receiver_email_greeting',
					'type'    	=> 'text',
					'title'   	=> esc_html__( 'Greeting', 'workreap' ),
					'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
					'default' 	=> esc_html__( 'Hello {{receiver_name}},','workreap' ),
					'required'  => array( 'email_project_activity_receiver_switch','equals','1')
				),
				array(
					'id'        => 'project_activity_receiver_mail_content',
					'type'      => 'textarea',
					'default'   => wp_kses( __( 'A new activity performed by {{sender_name}} on a {{project_title}} project<br/>Please click on the button below to view the activity.<br/>{{project_link}}', 'workreap'),
					array(
						'a'	=> array(
						'href'  => array(),
						'title' => array()
						),
						'br'      => array(),
						'em'      => array(),
						'strong'  => array(),
					)),
					'title'     => esc_html__('Email content', 'workreap'),
					'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
					'required'  => array( 'email_project_activity_receiver_switch','equals','1')
				),

			);

		$employer_email    = apply_filters( 'workreap_filter_employer_email_fields', $employer_email );
		return	$employer_email;

	}
}

/* Freelancer Email Template fields */
/**
 * Freelancer email
 * @return slug
 */
if (!function_exists('workreap_freelancer_email')) {
	function workreap_freelancer_email(){
		$freelancer_email = array(
            /* Freelancer Email on Post Task */
		array(
			'id'      => 'divider_post_task_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Post a task', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_post_task',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on post a task.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'post_task_freelancer_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap'),
			'default' 	=> esc_html__( 'Task submission','workreap' ),
			'required'  => array('email_post_task','equals','1')

		),
		array(
			'id'	=> 'divider_post_task_information',
			'desc'  => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>', 	'workreap' ),
			array(
				'a'       => array(
					'href'  => array(),
					'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_post_task','equals','1')
		),
		array(
			'id'      	=> 'post_task_freelancer_email_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> esc_html__( 'Hello {{freelancer_name}},','workreap' ),
			'required'  => array('email_post_task','equals','1')

		),
		array(
			'id'        => 'post_task_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Thank you for submitting the task, we will review and approve the task after the review.', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_post_task','equals','1')
		),

    /* Freelancer Email on Task Approved */
		array(
			'id'      => 'divider_task_approved_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Task approved', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_task_approve',
			'type'     => 'switch',
			'title'    => esc_html__( 'Send email', 'workreap' ),
			'subtitle' => esc_html__( 'Email to freelancer on posted task approvel.', 'workreap' ),
			'default'  => true,
		),
		array(
			'id'      	=> 'task_approved_freelancer_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Task approved!','workreap' ),
			'required'  => array('email_task_approve','equals','1')
		),
		array(
			'id'      => 'divider_task_approved_information',
			'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>', 'workreap' ),
			array(
				'a'       => array(
					'href'  => array(),
					'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_task_approve','equals','1')
		),
		array(
			'id'      	=> 'task_approved_freelancer_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_task_approve','equals','1')

		),
		array(
			'id'        => 'task_approved_freelancer_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Your task “{{task_name}}” has been approved. You can view your task here {{task_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_task_approve','equals','1')

		),

    /* Freelancer Email on Task Rejected */
		array(
			'id'      => 'divider_task_rejected_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Freelancer task rejected', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_task_rej_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on posted task rejected.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'task_rejected_freelancer_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Task rejected','workreap'),
			'required'  => array('email_task_rej_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_task_rejected_information',
			'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{admin_feedback}} — To display the admin feedback.<br>', 'workreap' ),
		array(
				'a'       => array(
					'href'  => array(),
					'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_task_rej_freelancer','equals','1')
		),
		array(
			'id'      	=> 'task_rejected_freelancer_greeting',
			'type'    	=> 'text',
			'title'   	=> 	esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> 	esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> 	esc_html__( 'Hello {{freelancer_name}},','workreap' ),
			'required'  => 	array('email_task_rej_freelancer','equals','1')

		),
		array(
			'id'        => 'task_rejected_freelancer_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Your task “{{task_name}}” has been rejected. Please make the required changes and submit it again.<br/>{{admin_feedback}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     =>  esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  =>  array('email_task_rej_freelancer','equals','1')
		),

    /* Freelancer Email on New Order */
		array(
			'id'      => 'divider_new_order_freelancer_templates',
			'type'    => 'info',
			'title'   =>  esc_html__( 'New order', 'workreap' ),
			'style'   => 'info',
		),

		array(
			'id'       => 'email_new_order_freelancer',
			'type'     => 'switch',
			'title'    =>  esc_html__( 'Send email', 'workreap' ),
			'subtitle' =>  esc_html__( 'Email to freelancer on new order received.', 'workreap' ),
			'default'  =>  true,
		),

		array(
			'id'      	=> 'new_order_freelancer_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'A new task order.','workreap'),
			'required'  => array('email_new_order_freelancer','equals','1')

		),
		array(
			'id'      => 'divider_new_order_freelancer_information',
			'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{signature}} — To display the email signature.<br>', 'workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
			) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_new_order_freelancer','equals','1')

		),
		array(
			'id'      	=> 'new_order_freelancer_email_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_new_order_freelancer','equals','1')

		),
		array(
			'id'        => 'new_order_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'You have received a new order for the task “{{task_name}}”.', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),

			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_new_order_freelancer','equals','1')

		),

    /* Freelancer Email on Order Complete request declined */
		array(
			'id'      => 'divider_order_complete_req_declined_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Order complete request declined', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_odr_cmpt_dec_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on order complete request rejection.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'order_complete_request_declined_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Task completed request declined','workreap'),
			'required'  => array('email_odr_cmpt_dec_freelancer','equals','1')

		),
		array(
			'id'      => 'order_complete_employer_information',
			'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{employer_comments}} — To display the employer comment.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_odr_cmpt_dec_freelancer','equals','1')

		),
		array(
			'id'      => 'order_complete_request_declined_greeting',
			'type'    => 'text',
			'default' => esc_html__( 'Hello {{freelancer_name}},', 'workreap' ),
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'required'  => array('email_odr_cmpt_dec_freelancer','equals','1')
		),
		array(
			'id'        => 'order_complete_request_declined_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'The employer “{{employer_name}}” has declined the final revision and has left some comments against the order #{{order_id}} <br/> "{{employer_comments}}"', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
		),

    /* Freelancer Email on Order Completed */
		array(
			'id'      => 'divider_order_status_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Order completed', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_odr_cmpt_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on order complete.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      => 'order_completed_freelancer_subject',
			'type'    => 'text',
			'title'   => esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' => esc_html__( 'Task completed','workreap'),
			'required'  => array('email_odr_cmpt_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_order_completed_information',
			'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>{{employer_comments}} — To display the employer comments.<br>{{employer_rating}} — To display the employer rating.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_odr_cmpt_freelancer','equals','1')
		),
		array(
			'id'      	=> 'order_completed_freelancer_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_odr_cmpt_freelancer','equals','1')
		),
		array(
			'id'        => 'order_completed_freelancer_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Congratulations! The employer “{{employer_name}}” has closed the ongoing task with the order #{{order_id}} and has left some comments <br> "{{employer_comments}}" <br/>Employer rating: {{employer_rating}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_odr_cmpt_freelancer','equals','1')
		),

    /* Freelancer Email on Order Activity */
		array(
			'id'      => 'divider_email_order_activity_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Freelancer order activity', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_odr_activity_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on order activity.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      => 'order_activity_freelancer_subject',
			'type'    => 'text',
			'default' => esc_html__( 'Order activity', 'workreap' ),
			'title'   => esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'required'  => array('email_odr_activity_freelancer','equals','1')
		),

		array(
			'id'      =>	'divider_email_order_activity_information',
			'desc'    =>  	wp_kses( __( '{{sender_name}} — To display the email sender name.<br>{{receiver_name}} — To display the email receiver name.<br>{{task_name}} — To display task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the task id.<br>{{order_amount}} — To display the task/order amount.<br>{{login_url}} — To display the site login url.<br>{{sender_comments}} — To display the sender comments/message.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_odr_activity_freelancer','equals','1')
		),
		array(
			'id'      => 'order_activity_freelancer_gretting',
			'type'    => 'text',
			'default' => esc_html__( 'Hello {{receiver_name}}', 'workreap' ),
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'required'  => array('email_odr_activity_freelancer','equals','1')
		),
		array(
			'id'        => 'order_activity_freelancer_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'You have received a note from the {{sender_name}} on the ongoing task "{{task_name}}" against the order #{{order_id}}<br/>{{sender_comments}}<br/>You can login to take a quick action.<br/>{{login_url}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_odr_activity_freelancer','equals','1')
		),

    /* Freelancer Email on Refund request */
		array(
			'id'      => 'divider_order_freelancer_refund_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Freelancer refund', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_new_refund_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer refund request.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      => 'new_freelancer_refund_subject',
			'type'    => 'text',
			'title'   => esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' => esc_html__( 'A new refund request received','workreap'),
			'required'  => array('email_new_refund_freelancer','equals','1')

		),
		array(
			'id'      => 'divider_new_order_freelancer_refund_information',
			'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>{{employer_comments}} — To display the employer comments.<br>','workreap' ),
		array(
				'a'       => array(
					'href'  => array(),
					'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_new_refund_freelancer','equals','1')

		),
		array(
			'id'      	=> 'new_freelancer_refund_email_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_new_refund_freelancer','equals','1')

		),
		array(
			'id'        => 'new_freelancer_refund_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'You have received a refund request from "{{employer_name}}" against the order #{{order_id}}<br/>{{employer_comments}}<br/>You can approve or decline the refund request.<br/>{{login_url}}.', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_new_refund_freelancer','equals','1')

		),

    /* Freelancer Email on Refund Comment */
		array(
			'id'      => 'divider_order_refund_freelancer_comment_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Refund comment', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_refund_comment_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on refund comment .', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      => 'refund_freelancer_comment_subject',
			'type'    => 'text',
			'title'   => esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' => esc_html__( 'A new comment on refund request','workreap'),
			'required'  => array('email_refund_comment_freelancer','equals','1')

		),
		array(
			'id'      => 'divider_declined_order_freelancer_refund_information',
			'desc'    => wp_kses( __( '{{sender_name}} — To display the freelancer name.<br>{{receiver_name}} — To display the employer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>{{sender_comments}} — To display the sender comment.<br>','workreap' ),
		array(
				'a'       => array(
					'href'  => array(),
					'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_refund_comment_freelancer','equals','1')
		),
		array(
			'id'      => 'order_refund_freelancer_comment_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{receiver_name}},','workreap'),
			'required'  => array('email_refund_comment_freelancer','equals','1')
		),
		array(
			'id'        => 'refund_freelancer_comment_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'The “{{sender_name}}” has left some comments on the refund request against the order #{{order_id}}<br/>{{sender_comments}}<br/>{{login_url}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_refund_comment_freelancer','equals','1')

		),

    /* Freelancer Email on Dispute Resolved */
		array(
			'id'      => 'divider_disputes_resolved_freelancer_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Dispute resolved freelancer', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_dispt_resolve_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on dispute resolve.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'disputes_resolved_freelancer_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Dispute resolved','workreap'),
			'required'  => array('email_dispt_resolve_freelancer','equals','1'),
		),
		array(
			'id'    => 'divider_disputes_resolved_freelancer_information',
			'desc'  => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>', 'workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_dispt_resolve_freelancer','equals','1')
		),
		array(
			'id'      	=> 'disputes_resolved_freelancer_email_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_dispt_resolve_freelancer','equals','1')
		),
		array(
			'id'        => 'disputes_resolved_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Congratulations!<br/>We have gone through the refund and dispute and resolved the dispute in your favor. We completed the task and the amount has been added to your wallet.', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
			'required'  => array('email_dispt_resolve_freelancer','equals','1')

		),

		/* Employer Email on Dispute Canceled */
		array(
			'id'      => 'divider_disputes_cancelled_freelancer_templates',
			'type'    => 'info',
			'title'   =>  esc_html__( 'Dispute resolved against you.', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_disputes_cancelled_freelancer',
			'type'     => 'switch',
			'title'    =>  esc_html__('Send email', 'workreap'),
			'subtitle' =>  esc_html__('Email to freelancer on canceled/resolved dispute.', 'workreap'),
			'default'  =>  true,
		),
		array(
			'id'      	=> 'disputes_cancelled_freelancer_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Dispute not in your favaour','workreap'),
			'required'  => array( 'email_disputes_cancelled_freelancer','equals','1')

		),
		array(
			'id'      => 'divider_disputes_cancelled_freelancer_information',
			'desc'    => wp_kses( __( '{{freelancer_name}} — To display the freelancer name.<br>{{task_name}} — To display the task name.<br>{{task_link}} — To display the task link.<br>{{order_id}} — To display the order id.<br>{{order_amount}} — To display the order amount.<br>{{login_url}} — To display the login url.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
			) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array( 'email_disputes_cancelled_freelancer','equals','1')
		),
		array(
			'id'      	=> 'disputes_cancelled_freelancer_email_greeting',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' 	=> esc_html__( 'Hello {{freelancer_name}},','workreap' ),
			'required'  => array( 'email_disputes_cancelled_freelancer','equals','1')

		),
		array(
			'id'        => 'disputes_cancelled_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Dispute resolve by admin but not in your favour.', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content (dispute resolved)', 'workreap' ),
			'subtitle'     => esc_html__( 'Insert email content here.', 'workreap' ),
			'required'  => array( 'email_disputes_cancelled_freelancer','equals','1')
		),

    /* Freelancer Email on Package */
		array(
			'id'      => 'divider_freelancer_packages_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Packages', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_package_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on purchase package.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'packages_freelancer_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Thank you for purchasing the package','workreap'),
			'required'  => array('email_package_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_freelancer_packages_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{order_id}} — To display the Order id.<br>{{order_amount}} — To display the Order amount.<br>{{package_name}} — To display the Package Name.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_package_freelancer','equals','1')
		),
		array(
			'id'      => 'packages_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
		),
		array(
			'id'        => 'package_freelancer_purchase_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Thank you for purchasing the package “{{package_name}}” You can now post a task and get orders.', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_package_freelancer','equals','1')
		),
		/* Freelancer Email on Project invitation */
		array(
			'id'      => 'divider_freelancer_project_invitation_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Project invitation', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_project_invitation_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on project invitation.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'project_invitation_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Project invitation','workreap'),
			'required'  => array('email_project_invitation_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_freelancer_project_invitation_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project title.<br>{{project_link}} — To display the project link.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_project_invitation_freelancer','equals','1')
		),
		array(
			'id'      => 'project_invitation_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_project_invitation_freelancer','equals','1')
		),
		array(
			'id'        => 'project_invitation_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'You have received a project invitation from {{employer_name}} Please click on the link below to view the project. {{project_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_project_invitation_freelancer','equals','1')
		),

		/* Freelancer Email on proposal decline */
		array(
			'id'      => 'divider_freelancer_proposal_decline_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Proposal decline', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_proposal_decline_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on proposal decline.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'proposal_decline_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Proposal decline','workreap'),
			'required'  => array('email_proposal_decline_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_freelancer_proposal_decline_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project title.<br>{{proposal_link}} — To display the project link.<br>', 'workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_proposal_decline_freelancer','equals','1')
		),
		array(
			'id'      => 'proposal_decline_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_proposal_decline_freelancer','equals','1')
		),
		array(
			'id'        => 'proposal_decline_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Oho! your proposal on {{project_title}} has been rejected by {{employer_name}}<br/>Please click on the button below to view the rejection reason.<br/>{{proposal_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_proposal_decline_freelancer','equals','1')
		),
		/* Freelancer Email on hired proposal */
		array(
			'id'      => 'divider_freelancer_proposal_hired_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Proposal hired', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_proposal_hired_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on hired proposal.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'proposal_hired_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Proposal hired','workreap'),
			'required'  => array('email_proposal_hired_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_freelancer_proposal_hired_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project title.<br>{{project_link}} — To display the project link.<br>', 'workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_proposal_hired_freelancer','equals','1')
		),
		array(
			'id'      => 'proposal_hired_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_proposal_hired_freelancer','equals','1')
		),
		array(
			'id'        => 'proposal_hired_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Woohoo! {{employer_name}} hired you for {{project_title}} project <br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_proposal_hired_freelancer','equals','1')
		),
		/* Freelancer Email on hire milestone */
		array(
			'id'      => 'divider_freelancer_milestone_hire_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Hire milestone', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_milestone_hire_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on hire milestone.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'milestone_hired_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Milestone hired','workreap'),
			'required'  => array('email_milestone_hire_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_freelancer_milestone_hired_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{milestone_title}} — To display the milestone title.<br>{{project_title}} — To display the project title.<br>{{project_link}} — To display the project link.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_milestone_hire_freelancer','equals','1')
		),
		array(
			'id'      => 'milestone_hire_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_milestone_hire_freelancer','equals','1')
		),
		array(
			'id'        => 'milestone_hire_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'Your milestone {{milestone_title}} of {{project_title}} has been approved <br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_milestone_hire_freelancer','equals','1')
		),
		/* Freelancer Email on milestone completed */
		array(
			'id'      => 'divider_freelancer_milestone_complete_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Milestone completed', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_milestone_complete_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on milestone complete.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'milestone_complete_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Milestone completed','workreap'),
			'required'  => array('email_milestone_complete_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_freelancer_milestone_complete_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{milestone_title}} — To display the milestone title.<br>{{project_title}} — To display the project title.<br>{{project_link}} — To display the project link.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_milestone_complete_freelancer','equals','1')
		),
		array(
			'id'      => 'milestone_complete_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_milestone_complete_freelancer','equals','1')
		),
		array(
			'id'        => 'milestone_complete_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'You milestone {{milestone_title}} of {{project_title}} marked as completed by {{employer_name}}<br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_milestone_complete_freelancer','equals','1')
		),
		/* Freelancer Email on milestone decline */
		array(
			'id'      => 'divider_freelancer_milestone_decline_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Milestone Decline', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'       => 'email_milestone_decline_freelancer',
			'type'     => 'switch',
			'title'    => esc_html__('Send email', 'workreap'),
			'subtitle' => esc_html__('Email to freelancer on milestone decline.', 'workreap'),
			'default'  => true,
		),
		array(
			'id'      	=> 'milestone_decline_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Milestone decline','workreap'),
			'required'  => array('email_milestone_decline_freelancer','equals','1')
		),
		array(
			'id'      => 'divider_freelancer_milestone_decline_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{milestone_title}} — To display the milestone title.<br>{{project_title}} — To display the project title.<br>{{project_link}} — To display the project link.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
			'required'  => array('email_milestone_decline_freelancer','equals','1')
		),
		array(
			'id'      => 'milestone_decline_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
			'required'  => array('email_milestone_decline_freelancer','equals','1')
		),
		array(
			'id'        => 'milestone_decline_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses( __( 'You milestone {{milestone_title}} of {{project_title}} has been declined by {{employer_name}}<br/>Please click on the button below to view the project.<br/>{{project_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
			'required'  => array('email_milestone_decline_freelancer','equals','1')
		),
		/* Freelancer Email on project refund request */
		array(
			'id'      => 'divider_freelancer_project_dispute_req_templates',
			'type'    => 'info',
			'title'   => esc_html__( 'Project dispute request', 'workreap' ),
			'style'   => 'info',
		),
		array(
			'id'      	=> 'freelancer_project_dispute_req_email_subject',
			'type'    	=> 'text',
			'title'   	=> esc_html__( 'Subject', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email subject here.', 'workreap' ),
			'default' 	=> esc_html__( 'Project refund request','workreap'),
		),
		array(
			'id'      => 'divider_freelancer_project_dispute_req_information',
			'desc'    => wp_kses(__( '{{freelancer_name}} — To display the freelancer name.<br>{{employer_name}} — To display the employer name.<br>{{project_title}} — To display the project title.<br>{{dispute_link}} — To display the dispute link.<br>','workreap' ),
			array(
					'a'       => array(
						'href'  => array(),
						'title' => array()
					),
					'br'      => array(),
					'em'      => array(),
					'strong'  => array(),
				) ),
			'title'     => esc_html__( 'Email variables', 'workreap' ),
			'type'      => 'info',
			'class'     => 'dc-center-content',
			'icon'      => 'el el-info-circle',
		),
		array(
			'id'      => 'project_dispute_req_freelancer_email_greeting',
			'type'    => 'text',
			'title'   => esc_html__( 'Greeting', 'workreap' ),
			'subtitle'    => esc_html__( 'Insert greeting text here.', 'workreap' ),
			'default' => esc_html__( 'Hello {{freelancer_name}},','workreap'),
		),
		array(
			'id'        => 'project_dispute_req_freelancer_mail_content',
			'type'      => 'textarea',
			'default'   => wp_kses(__( 'Project refund request received from {{employer_name}} of {{project_title}} project <br/>Please click on the button below to view the refund request.<br/>{{dispute_link}}', 'workreap'),
			array(
				'a'	=> array(
				'href'  => array(),
				'title' => array()
				),
				'br'      => array(),
				'em'      => array(),
				'strong'  => array(),
			)),
			'title'     => esc_html__( 'Email content', 'workreap' ),
			'subtitle'    	=> esc_html__( 'Insert email content here.', 'workreap'),
		),
        );
        $freelancer_email	= apply_filters( 'workreap_filter_freelancer_email_fields', $freelancer_email );
		return	$freelancer_email;
    }
}

if( !function_exists('workreapPageTemplateRedirect') ){
	add_action( 'template_redirect', 'workreapPageTemplateRedirect' );
	function workreapPageTemplateRedirect(){
		global $workreap_settings;
		$return_type	= true;
		if(is_user_logged_in()){
			$access_option	= !empty($workreap_settings['user_restriction']) ? $workreap_settings['user_restriction'] : false;
			$user_identity	= get_current_user_id();
			$user_type		= apply_filters('workreap_get_user_type', $user_identity );
			if( !empty($user_type) && in_array($user_type,array('freelancers','employers')) && !empty($access_option)){
				if( !empty($user_type) && $user_type === 'employers'){
					$access_pages	= !empty($workreap_settings['employer_access_pages']) ? $workreap_settings['employer_access_pages'] : array();
					if( !empty($access_pages) && is_page($access_pages) && !is_singular('freelancers') && !is_singular( array( 'product' ))  ){
						$return_type	= false;
					}
				} else if( !empty($user_type) && $user_type === 'freelancers'){
					$access_pages	= !empty($workreap_settings['freelancer_access_pages']) ? $workreap_settings['freelancer_access_pages'] : array();
					if(  !empty($access_pages) && is_page($access_pages)  && !is_singular( array( 'product' )) ){
						$return_type	= false;
					}
				}
			}
		}

		if( empty($return_type) ){
			wp_redirect(workreap_get_page_uri('dashboard'));
			exit;
		}
	}
}

if( !function_exists('workreap_excerpt_more') ){
	function workreap_excerpt_more($more) {
		return '...';
	}
	add_filter('excerpt_more', 'workreap_excerpt_more');
}

/**
 * OpenAI prompt button & model
 *
 */

if( !function_exists('workreapAIContent')){
	function workreapAIContent($ai_id=1,$type="content"){
		global $workreap_settings,$current_user;
		$link_id    = workreap_get_linked_profile_id( $current_user->ID,'' );
		$user_name  = workreap_get_username($link_id);
        $avatar     = apply_filters(
                        'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 40, 'height' => 40), $link_id), array('width' => 40, 'height' => 40)
                    );
		$defaul_ai_img	= !empty($workreap_settings['defaul_ai_img']['url']) ? $workreap_settings['defaul_ai_img']['url'] : WORKREAP_DIRECTORY_URI.'/public/images/expertisev2.svg';
		$_ai_feature	= workreap_allow_ai_feature($current_user->ID);
		if(!empty($_ai_feature) ){ ?>
			<div class="wr-aibtn_section">
				<a href="javascript:;" class="wr-btn-solid-lg-lefticon wr-btn_ai" data-bs-toggle="modal" data-bs-target="#workreap-aicontent-<?php echo esc_attr($ai_id);?>">
					<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'/public/images/expertise.svg');?>" alt="<?php echo esc_attr_e("OpenAI","workreap");?>" />
					<?php esc_html_e("Write with AI","workreap");?>
				</a>
			</div>
			<div class="modal fade wr-aichat_popup" tabindex="-1" role="dialog" id="workreap-aicontent-<?php echo esc_attr($ai_id);?>" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="workreap-modalcontent modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="workreap-aicontent-<?php echo esc_attr($ai_id);?>ModalLabel">
								<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'/public/images/expertise.svg');?>" alt="<?php echo esc_attr_e("OpenAI","workreap");?>" />
								<?php esc_html_e("Write with AI","workreap");?>
							</h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="wr-aichat">
								<ul class="wr-aichat_list" id="wr-ailist-<?php echo esc_attr($ai_id);?>">
									<li class="wr-ai_empty" id="wr-ailist_empty-<?php echo esc_attr($ai_id);?>">
										<figure class="wr-ai_empty_img">
											<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'/public/images/expertise.svg');?>" alt="<?php echo esc_attr_e("OpenAI","workreap");?>" />
										</figure>
										<p><?php esc_html_e("Write with AI","workreap");?></p>
									</li>
									<li class="wr-ai_loader d-none" id="wr-ailist_loader-<?php echo esc_attr($ai_id);?>">
										<div class="wr-ai-reply">
											<figure class="wr-ai-reply_avatar">
												<img class="wr-aireply-loaderimg" src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'/public/images/expertise.svg');?>" alt="<?php echo esc_attr_e("OpenAI","workreap");?>" />
											</figure>
											<div class="wr-ai-reply_content">
												<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'/public/images/typing.gif');?>" alt="<?php echo esc_attr_e("Typeing","workreap");?>" />
											</div>
										</div>
									</li>
								</ul>
								<div class="wr-aichat_input">
									<input type="text" placeholder="<?php esc_attr_e("What would you like AI to write about?","workreap");?>" class="form-control" id="wr-aiText-<?php echo esc_attr($ai_id);?>">
									<button type="button" class="btn btn-primary wr-aibtn_request wr-aidisabled" data-ai_type="<?php echo esc_attr($type);?>"  data-ai_reply_type="reply" data-ai_id="<?php echo esc_attr($ai_id);?>"><i class="wr-icon-send"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script type="text/template" id="tmpl-load-aiUserReply-<?php echo esc_attr($ai_id);?>">
				<li class="wr-user-reply wr-ai-reply_{{data.type}} wr-ai-content-laoding" id="aiUserReply-{{data.counter}}" data-id="{{data.counter}}">
					<div class="wr-ai-reply">
						<# if (data.type === 'ai_reply') { #>
							<?php if(!empty($defaul_ai_img)){?>
								<figure class="wr-ai-reply_avatar">
									<img src="<?php echo esc_url($defaul_ai_img);?>" alt="<?php echo esc_attr($user_name);?>">
								</figure>
							<?php } ?>
						<# } else { #>
							<figure class="wr-ai-reply_avatar">
								<img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
							</figure>
						<# } #>

						<div class="wr-ai-reply_content" id="wr-ai-contenet-{{data.counter}}"><# if (data.type === 'ai_reply') { #>
							<# } else { #>
							{{{data.content}}}
							<# } #>
						</div>
						<div class="wr-ai-reply_content d-none" id="wr-ai-contenet_hiden-{{data.counter}}">{{{data.content}}}</div>
							<# if (data.type === 'ai_reply') { #>
								<div class="wr-ai-reply_tags">
									<a href="javascript:;" class="wr-btn wr-ai_replace" data-ai_id="<?php echo esc_attr($ai_id);?>" data-id="{{data.counter}}"><i class="wr-icon-arrow-up" aria-hidden="true"></i><?php esc_html_e('Replace','workreap');?></a>
									<a href="javascript:;" class="wr-btn wr-ai_insert" data-ai_id="<?php echo esc_attr($ai_id);?>" data-id="{{data.counter}}"><i class="wr-icon-arrow-down-left" aria-hidden="true"></i><?php esc_html_e('Insert','workreap');?></a>
									<a href="javascript:;" class="wr-btn wr-ai_copy" data-id="{{data.counter}}"><i class="wr-icon-copy" aria-hidden="true"></i><?php esc_html_e('Copy','workreap');?></a>
									<a href="javascript:;" class="wr-btn wr-ai_regenerate wr-aibtn_request d-none" data-ai_reply_type="regenerate" data-ai_type="<?php echo esc_attr($type);?>" data-ai_id="<?php echo esc_attr($ai_id);?>" data-id="{{data.counter}}" data-parent_id="{{data.parent_counter}}"><?php esc_html_e('Regenerate','workreap');?></a>
								</div>
							<# } #>
					</div>
				</li>
			</script>
		<?php
		}
	}
	add_action( 'workreapAIContent', 'workreapAIContent',10,2 );
}

/**
 * OpenAI prompt Request
 *
 */
if( !function_exists('workreapGetAIContent')){
	add_action('wp_ajax_workreapGetAIContent', 'workreapGetAIContent');
	function workreapGetAIContent() {
		global $current_user,$workreap_settings;
		$json               = array();
		if (function_exists('workreap_is_AIdemo_site')) {
			workreap_is_AIdemo_site();
		}
		if (function_exists('workreap_verify_token')) {
			workreap_verify_token($_POST['security']);
		}
		$ai_type	= !empty($_POST['ai_type']) ? $_POST['ai_type'] : 'content';
		$ai_content	= !empty($_POST['ai_content']) ? $_POST['ai_content'] : '';
		$ai_reply_type	= !empty($_POST['ai_reply_type']) ? $_POST['ai_reply_type'] : '';

		if(!empty($ai_type) && $ai_type === 'job_content' && !empty($workreap_settings['enable_ai_job']) && !empty($workreap_settings['enable_ai_job_content'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_job_content']);
		} elseif(!empty($ai_type) && $ai_type === 'job_title' && !empty($workreap_settings['enable_ai_job']) && !empty($workreap_settings['enable_ai_job_title'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_job_title']);
		} elseif(!empty($ai_type) && $ai_type === 'proposal_content' && !empty($workreap_settings['enable_ai_proposal']) && !empty($workreap_settings['enable_ai_proposal_content'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_proposal_content']);
		} elseif(!empty($ai_type) && $ai_type === 'hired_project' && !empty($workreap_settings['enable_ai_project_hiring']) && !empty($workreap_settings['enable_ai_project_hiring_content'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_project_hiring_content']);
		} elseif(!empty($ai_type) && $ai_type === 'service_content' && !empty($workreap_settings['enable_ai_service']) && !empty($workreap_settings['enable_ai_service_content'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_service_content']);
		} elseif(!empty($ai_type) && $ai_type === 'service_title' && !empty($workreap_settings['enable_ai_service']) && !empty($workreap_settings['enable_ai_service_title'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_service_title']);
		} elseif(!empty($ai_type) && $ai_type === 'profile_content' && !empty($workreap_settings['enable_ai_user']) && !empty($workreap_settings['enable_ai_user_content'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_user_content']);
		} elseif(!empty($ai_type) && $ai_type === 'custom_offer_content' && !empty($workreap_settings['enable_ai_custom_offer']) && !empty($workreap_settings['enable_ai_custom_offer_content'])){
			$ai_content	= str_replace('{{ai_content}}',$ai_content,$workreap_settings['enable_ai_custom_offer_content']);
		}

		if(!empty($ai_reply_type) && $ai_reply_type === 'regenerate'){

		}

		$messages = array(
			array(
				'role' => 'system',
				'content' => $ai_content
			)
		);
		$request_ui	= 'chat/completions';
		$model = 'gpt-3.5-turbo-1106';
		$json	= workreapGetAIContentRequest($request_ui,$model,$messages);
		wp_send_json($json);

	}
}

/**
 * OpenAI Request
 *
 */
if( !function_exists('workreapGetAIContentRequest') ){
	function workreapGetAIContentRequest($request_ui, $model, $messages) {
		global $workreap_settings;
		$api_key	= !empty($workreap_settings['ai_client_id']) && !empty($workreap_settings['enable_ai']) ? $workreap_settings['ai_client_id'] : '';
		$url 		= 'https://api.openai.com/v1/'.$request_ui;
		$headers = array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $api_key
		);
		$header_data	= array(
			'model' => $model,
			'messages' => $messages
		);

		$body = json_encode($header_data);

		// Make the API request
		$response = wp_remote_post($url, array(
			'headers' => $headers,
			'body' => $body
		));
		$return	= array();
		// Check for errors
		if (is_wp_error($response)) {
			$return['type']		= 'error';
			$return['message']	=  'Error: ' . $response->get_error_message();
		}
		$response_body 	= wp_remote_retrieve_body($response);
		$data 			= json_decode($response_body, true);
		if (isset($data['choices'][0]['message']['content'])) {
			$return['type']		= 'success';
			$return['content']	=   $data['choices'][0]['message']['content'];
			$return['message']	=   esc_html__('response from OpenAI','workreap');
		} else {
			$return['type']		= 'error';
			$return['message']	=  esc_html__('Oops!','workreap');
			$return['message_desc']	=  esc_html__('Something went wrong','workreap');
		}
		return $return;
	}
}

/**
 * @init            Site AI demo content
 * @package         Amentotech
 * @subpackage      workreap/includes
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('workreap_is_AIdemo_site')) {
    function workreap_is_AIdemo_site($message = '') {
        $json = array();
        $message = !empty($message) ? $message : esc_html__("Sorry! You have exceeded the OpenAI limit.", 'workreap');

        if (isset($_SERVER["SERVER_NAME"]) && $_SERVER["SERVER_NAME"] == 'demos.codingeasel.com') {
            if (isset($_COOKIE['ai_demo_request_count'])) {
                $request_count = intval($_COOKIE['ai_demo_request_count']);
            } else {
                $request_count = 0;
            }

            if ($request_count >= 3) {
                $json['type'] = "error";
                $json['message'] = esc_html__('Oops!', 'workreap');
                $json['message_desc'] = $message;
                wp_send_json($json);
            } else {
                $request_count++;
                setcookie('ai_demo_request_count', $request_count, time() + 86400, "/"); // 86400 seconds = 1 day
            }
        }
    }
}

if( !function_exists('workreap_portfolio_delete') ){
	add_action( 'wp_ajax_workreap_portfolio_delete', 'workreap_portfolio_delete' );
	/**
		 * Dashboard portfolios enable/disable
		 *
		 * @since    1.0.0
		 * @access   public
	*/
	function workreap_portfolio_delete(){
		global $current_user;
		$json 		= array();

		if( function_exists('workreap_is_demo_site') ) {
			workreap_is_demo_site();
		}

		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type']               = 'error';
			$json['message'] 		    = esc_html__('Restricted Access', 'workreap');
			$json['message_desc'] 		= esc_html__('You are not allowed to perform this action.', 'workreap');
			wp_send_json($json);
		}

		$service_id  = !empty($_POST['service_id']) ?  (int)$_POST['service_id'] : 0;

		if( function_exists('workreap_verify_post_author') ){
			workreap_verify_post_author($service_id);
		}

		if($service_id){

			$workreap_delete = wp_delete_post($service_id);

			if( $workreap_delete ){
				$json['type']               = 'success';
				$json['message'] 		    = esc_html__('Woohoo!', 'workreap');
				$json['message_desc'] 		= esc_html__('Portfolio has been deleted!', 'workreap');
				wp_send_json($json);
			} else {
				$json['type']               = 'error';
				$json['message'] 		    = esc_html__('Oops!', 'workreap');
				$json['message_desc'] 		= esc_html__('There is an error while removing the portfolio.', 'workreap');
				wp_send_json($json);
			}

		} else {
				$json['type']               = 'error';
				$json['message'] 		    = esc_html__('Oops!', 'workreap');
				$json['message_desc'] 		= esc_html__('There is an error while removing the portfolio.', 'workreap');
				wp_send_json($json);
		}

	}
}

/**
 * List packages
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

 if (!function_exists('workreap_get_packages_lists')) {
    function workreap_get_packages_lists() {
        $listEmpPackages = array();

        // Query arguments for WP_Query
        $emp_args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1, // All packages
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'ASC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'employer_packages',
                )
            )
        );

        // The Query
        $emp_query = new WP_Query($emp_args);

        // Process the posts
        if ($emp_query->have_posts()) {
            while ($emp_query->have_posts()) {
                $emp_query->the_post();
                $package_id = get_the_ID();
                $listEmpPackages[$package_id] = get_the_title($package_id);
            }
            wp_reset_postdata();
        }

        return $listEmpPackages;
    }
}


/**
 * Payouts List
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

 if (!function_exists('workreap_get_payouts_lists')) {
	function workreap_get_payouts_lists(){
		global $workreap_settings;
		$payout_bank_icon     = !empty($workreap_settings['payout_bank_icon']['url']) ? $workreap_settings['payout_bank_icon']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/earning/bank.png';
		$payout_paypal_icon   = !empty($workreap_settings['payout_paypal_icon']['url']) ? $workreap_settings['payout_paypal_icon']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/earning/paypal.png';
		$payout_stripe_icon   = !empty($workreap_settings['payout_stripe_icon']['url']) ? $workreap_settings['payout_stripe_icon']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/earning/stripe.png';
    	$payout_payoneer_icon = !empty($workreap_settings['payout_payoneer_icon']['url']) ? $workreap_settings['payout_payoneer_icon']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/earning/Payoneer.png';

    	$hide_fields = !empty($workreap_settings['bank_hide_fields']) ? $workreap_settings['bank_hide_fields'] : array();

	  	$lists = array(
			/* paypal */
			'paypal' => array(
				'id'                => 'paypal',
				'label'             => esc_html__('Paypal', 'workreap'),
				'title'             => esc_html__('Setup paypal account', 'workreap'),
				'img_url'           => esc_url($payout_paypal_icon),
				'status'            => apply_filters('workreap_filter_payouts','enable','paypal'),
				'desc'              => wp_kses(__('You need to add your PayPal email ID above. For more about <a target="_blank" href="https://www.paypal.com/"> PayPal </a> | <a target="_blank" href="https://www.paypal.com/signup/">Create an account</a>', 'workreap'), array(
					'a'               => array(
					'href'          => array(),
					'target'        => array(),
					'title'         => array()
					),
					'br'              => array(),
					'em'              => array(),
					'strong'          => array(),
				)),
				'fields'	=> array(
					'paypal_email'    => array(
						'type'          => 'text',
						'classes'       => '',
						'required'      => true,
						'show_this'     => true,
						'title'         => esc_html__('PayPal email address', 'workreap'),
						'placeholder'   => esc_html__('Enter paypal email here', 'workreap'),
						'message'       => esc_html__('PayPal Email Address is required', 'workreap'),
					)
				)
			),
			/* payoneer */
			'payoneer' => array(
				'id'		=> 'payoneer',
				'label'     => esc_html__('Payoneer', 'workreap'),
				'title'		=> esc_html__('Payoneer', 'workreap'),
				'img_url'	=> esc_url($payout_payoneer_icon),
				'status'	=> apply_filters('workreap_filter_payouts','enable','payoneer'),
				'desc'		=> wp_kses( __( 'You need to add your payoneer email ID below in the text field. For more about <a target="_blank" href="https://www.payoneer.com/"> Payoneer </a> | <a target="_blank" href="https://www.payoneer.com/accounts/">Create an account</a>', 'workreap' ),array(
					'a' => array(
						'href' => array(),
						'target' => array(),
						'title' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array(),
				)),
				'fields'	=> array(
					'payoneer_email' => array(
						'type'			=> 'text',
						'show_this'		=> true,
						'classes'		=> '',
						'required'		=> true,
						'title'			=> esc_html__('Payoneer email address','workreap'),
						'placeholder'	=> esc_html__('Add Payoneer email address','workreap'),
						'message'		=> esc_html__('Payoneer email address is required','workreap'),
					)
				)
			),
			/* bank */
			'bank'                => array(
				'id'                => 'bank',
				'label'             => esc_html__('Bank', 'workreap'),
				'title'             => esc_html__('Setup bank account', 'workreap'),
				'img_url'           => esc_url($payout_bank_icon),
				'status'            => apply_filters('workreap_filter_payouts','enable','bank'),
				'desc'              => wp_kses(__('Add all required settings for the bank transfer', 'workreap'), array(
					'a'               => array(
					'href'          => array(),
					'target'        => array(),
					'title'         => array()
					),
					'br'              => array(),
					'em'              => array(),
					'strong'          => array(),
				)),
				'fields'	=> array(
					'bank_account_title'	=> array(
						'type'          => 'text',
						'classes'       => '',
						'required'      => true,
						'show_this'     => !in_array('bank_account_title',$hide_fields),
						'title'         => esc_html__('Bank account title', 'workreap'),
						'placeholder'   => esc_html__('Bank account title', 'workreap'),
						'message'       => esc_html__('Bank Account Title is required', 'workreap'),
					),
					'bank_account_number' => array(
						'type'          => 'text',
						'classes'       => '',
						'required'      => true,
						'show_this'     => !in_array('bank_account_number',$hide_fields),
						'title'         => esc_html__('Bank account number', 'workreap'),
						'placeholder'   => esc_html__('Bank account number', 'workreap'),
						'message'       => esc_html__('Bank Account Number is required', 'workreap'),
					),
					'bank_account_name' => array(
						'type'          => 'text',
						'classes'       => '',
						'required'      => true,
						'show_this'     => !in_array('bank_account_name',$hide_fields),
						'title'         => esc_html__('Bank name', 'workreap'),
						'placeholder'   => esc_html__('Bank name', 'workreap'),
						'message'       => esc_html__('Bank Name is required', 'workreap'),
					),
					'bank_routing_number' => array(
						'type'          => 'text',
						'classes'       => '',
						'required'      => true,
						'show_this'     => !in_array('bank_routing_number',$hide_fields),
						'title'         => esc_html__('Bank routing number', 'workreap'),
						'placeholder'   => esc_html__('Bank routing number', 'workreap'),
						'message'       => esc_html__('Bank Routing Number is required', 'workreap'),
					),
					'bank_iban' => array(
						'type'          => 'text',
						'classes'       => '',
						'required'      => true,
						'show_this'     => !in_array('bank_iban',$hide_fields),
						'title'         => esc_html__('Bank IBAN', 'workreap'),
						'placeholder'   => esc_html__('Bank IBAN', 'workreap'),
						'message'       => esc_html__('Bank IBN is required', 'workreap'),
					),
					'bank_bic_swift' => array(
						'type'			=> 'text',
						'classes'		=> '',
						'required'		=> false,
						'show_this'     => !in_array('bank_bic_swift',$hide_fields),
						'title'	=> esc_html__('Bank BIC/SWIFT','workreap'),
						'placeholder'	=> esc_html__('Bank BIC/SWIFT','workreap'),
						'message'		=> esc_html__('Bank BIC/SWIFT is required','workreap'),
					)
				)
			),
	  );
	  $lists = apply_filters('workreap_filter_payouts_lists', $lists);
	  return $lists;
	}
}
