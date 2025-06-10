<?php
$proposal_id    = !empty($args['proposal_id']) ? intval($args['proposal_id']) : 0;
$project_id     = !empty($args['project_id']) ? intval($args['project_id']) : 0;
$freelancer_id      = !empty($args['freelancer_id']) ? intval($args['freelancer_id']) : 0;
$employer_id       = !empty($args['employer_id']) ? intval($args['employer_id']) : 0;
$proposal_meta  = !empty($args['proposal_meta']) ? ($args['proposal_meta']) : array();
$user_identity  = !empty($args['user_identity']) ? intval($args['user_identity']) : 0;
$user_type      = !empty($args['user_type']) ? esc_attr($args['user_type']) : '';

$date_format    = get_option( 'date_format' );
$time_format    = get_option( 'time_format' );
$pg_page        = get_query_var('page') ? get_query_var('page') : 1;
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1;
$per_page_itme  = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$paged          = max($pg_page, $pg_paged);
$current_page   = $paged;
$order_arg  = array(
    'page'          => $current_page,
    'paginate'      => true,
    'limit'         => $per_page_itme,
    'proposal_id'   => $proposal_id
);
if (!empty($user_type) && $user_type === 'freelancers') {
    $order_arg['freelancer_id']    = $freelancer_id;
} else if (!empty($user_type) && $user_type === 'employers') {
    $order_arg['employer_id']    = $employer_id;
}
$customer_orders = wc_get_orders( $order_arg );
?>
<div class="tab-pane fade" id="proposal-invoices" role="tabpanel" aria-labelledby="proposal-invoices-tab">
    <div class="wr-proinvoices">
        <div class="wr-proinvoices_title">
            <h5><?php esc_html_e('Invoices','workreap');?></h5>
        </div>
        <table class="table wr-proinvoices_table wr-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Date','workreap');?></th>
                    <th><?php esc_html_e('Title','workreap');?></th>
                    <th><?php esc_html_e('Status','workreap');?></th>
                    <th><?php esc_html_e('Amount','workreap');?></th>
                    <th><?php esc_html_e('Action','workreap');?></th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($customer_orders->orders)) {
                    $count_post = count($customer_orders->orders);
                    foreach ($customer_orders->orders as $order) {
                        $data_created	= wc_format_datetime( $order->get_date_created(), $date_format . ', ' . $time_format );
                        $invoice_status = get_post_meta( $order->get_id(),'_task_status', true );
                        $invoice_status = !empty($invoice_status) ? $invoice_status : '';
                        if(empty($invoice_status)){
                            $invoice_status = $order->get_status();
                        }
                        $product_data   = get_post_meta( $order->get_id(),'cus_woo_product_data', true );
                        $project_type   = !empty($product_data['project_type']) ? $product_data['project_type'] : '';
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
                        } else {
                            $invoice_title  = apply_filters( 'workreap_filter_invoice_title', $order->get_id() );
                        }
                        $invoice_price  = 0;
                        if( !empty($user_type) && $user_type === 'freelancers' ){
                            $invoice_price  = !empty($product_data['freelancer_shares']) ? $product_data['freelancer_shares'] : "";
                        } else if( !empty($user_type) && $user_type === 'employers' ){
                            $invoice_price      = $order->get_total();
                            if(function_exists('wmc_revert_price')){
                                $invoice_price =  wmc_revert_price($order->get_total(),$order->get_currency());
                            }
                        }
                        $invoice_url  = Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $user_identity, true, 'detail', intval($order->get_id()));
                ?>
                <tr>
                    <td data-label="<?php esc_attr_e('Date','workreap');?>"><?php echo esc_html($data_created); ?></td>
                    <td data-label="<?php esc_attr_e('Title','workreap');?>">
                        <p><?php echo esc_html($invoice_title);?></p>
                    </td>
                    <td data-label="<?php esc_attr_e('Status','workreap');?>">
                        <?php do_action( 'workreap_proposal_invoice_status_tag', $invoice_status );?>
                    </td>
                    <td data-label="<?php esc_attr_e('Amount','workreap');?>">
                        <?php workreap_price_format($invoice_price); ?>
                    </td>
                    <td data-label="<?php esc_attr_e('Action','workreap');?>">
                        <a href="<?php echo esc_url($invoice_url);?>"><?php esc_html_e('View invoice','workreap');?></a>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
        <?php 
            workreap_paginate($customer_orders);
            if (empty($customer_orders->orders)) {
                do_action( 'workreap_empty_listing', esc_html__('No invoices & bills found', 'workreap'));
            }
        ?>
    </div>
</div>
