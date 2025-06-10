<?php

/**
 * Freelancer invoice detail
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

global $workreap_settings;
$identity   = !empty($args['identity']) ? $args['identity'] : "";
$order_id   = !empty($args['order_id']) ? $args['order_id'] : "";
$site_logo  = !empty($workreap_settings['invoice_logo']['url']) ? $workreap_settings['invoice_logo']['url'] : '';


/* if order Id empty */
if (empty($order_id)) {
    return;
}

if (!class_exists('WooCommerce')) {
    return;
}

$date_format    = get_option('date_format');
$gmt_time       = strtotime(current_time('mysql', 1));
$order          = wc_get_order($order_id);
$data_created   = $order->get_date_created();
$data_created   = date_i18n($date_format, strtotime($data_created));
$get_taxes      = $order->get_taxes();
if(function_exists('wmc_revert_price')){
    $get_subtotal =  wmc_revert_price($order->get_subtotal(),$order->get_currency());
} else {
    $get_subtotal   = $order->get_subtotal();
}

$order_details  = get_post_meta($order_id, 'order_details', true);
$order_details  = !empty($order_details) ? $order_details : array();

$billing_address      = $order->get_formatted_billing_address();
$from_billing_address = !empty($identity) ? workreap_user_billing_address($identity) : '';
$order_meta           = get_post_meta($order_id, 'cus_woo_product_data', true);
$order_meta           = !empty($order_meta) ? $order_meta : array();

$payment_type   = get_post_meta($order_id, 'payment_type', true);
$payment_type   = !empty($payment_type) ? $payment_type : '';

$processing_fee         = 0;
$payment_method_title   = "";

$processing_fee   = get_post_meta($order_id, 'admin_shares', true);
$processing_fee   = isset($processing_fee) ? $processing_fee : 0;
$get_total        = get_post_meta($order_id, 'freelancer_shares', true);
$get_total        = !empty($get_total) ? $get_total : 0;

if (!empty($payment_type) && $payment_type === 'tasks') {
    $task_title       = !empty($order_meta['task_id']) ? get_the_title($order_meta['task_id']) : '';
} else if (!empty($payment_type) && $payment_type === 'package') {
    $get_total      = $order->get_total();
    if(function_exists('wmc_revert_price')){
        $get_total  = wmc_revert_price($get_total,$order->get_currency());
    }
    $processing_fee = $order->get_total_tax();
    $task_title     = !empty($order_meta['product_name']) ? esc_html($order_meta['product_name']) : '';
    $from_billing_address   = !empty($workreap_settings['invoice_billing_package']) ? $workreap_settings['invoice_billing_package'] : '';
    $payment_method_title   = get_post_meta($order_id, '_payment_method_title', true);
} else if( !empty($payment_type) && $payment_type === 'projects'){

    $project_id     = !empty($order_meta['project_id']) ? intval($order_meta['project_id']) : 0;
    $task_title     = !empty($project_id) ? get_the_title($project_id) : $order_meta['product_name'];
} else {
    $task_title     = apply_filters( 'workreap_filter_invoice_title', $order_id );
}
$invoice_terms      = !empty($workreap_settings['invoice_terms']) ? $workreap_settings['invoice_terms'] : '';
$invoice_billing_to = !empty($workreap_settings['invoice_billing_to']) ? $workreap_settings['invoice_billing_to'] : '';
$billing_address    = !empty($invoice_billing_to) && !empty($workreap_settings['invoice_billing_address']) ? $workreap_settings['invoice_billing_address'] : $billing_address;
?>
<div class="wr-main-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="wr-invoicedetal">
                    <div class="wr-printable">
                        <div class="wr-invoicebill">
                        <?php if( !empty($site_logo) ){
                                if( !empty($args['option']) && $args['option'] === 'pdf'){
                                    $type = pathinfo($site_logo, PATHINFO_EXTENSION);
                                    $data = file_get_contents($site_logo);
                                    $base64_logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                    echo do_shortcode( '<figure><img src="'.($base64_logo).'" alt="'.esc_attr__('invoice detail','workreap').'"></figure>' );
                                } else { ?>
                                    <figure>
                                        <img src="<?php echo esc_url($site_logo);?>" alt="<?php esc_attr_e('invoice detail','workreap');?>">
                                    </figure>
                            <?php } } ?>
                            <?php if(!empty($order_id)){?>
                                <div class="wr-billno">
                                    <h3><?php esc_html_e('Invoice', 'workreap'); ?></h3>
                                    <span># <?php echo intval($order_id); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if(!empty($task_title) || !empty($data_created)){?>
                            <div class="wr-tasksinfos">
                                <?php if(!empty($task_title)){?>
                                    <div class="wr-invoicetasks">
                                        <h5><?php esc_html_e('Task title', 'workreap'); ?>:</h5>
                                        <h3><?php echo esc_html($task_title); ?></h3>
                                    </div>
                                <?php }?>
                                <?php if(!empty($data_created) || !empty($order_id)){?>

                                    <div class="wr-tasksdates">
                                        <?php
                                            do_action( 'workreap_task_order_status', $order_id );
                                        ?>
                                        <?php if(!empty($data_created)){?>
                                            <span> <em><?php esc_html_e('Issue date:', 'workreap') ?>&nbsp;</em><?php echo esc_html($data_created); ?></span>
                                        <?php } ?>
                                        <?php if(!empty($payment_method_title)){?>
                                            <span> <em><?php esc_html_e('Payment method:', 'workreap') ?>&nbsp;</em><?php echo esc_html($payment_method_title); ?></span>
                                        <?php } ?>
                                    </div>
                                <?php }?>
                            </div>
                        <?php }?>
                        <div class="wr-invoicefromto">
                            <?php if (!empty(trim($from_billing_address))) { ?>
                                <div class="wr-fromreceiver">
                                    <h5><?php esc_html_e('From:', 'workreap'); ?></h5>
                                    <span><?php echo do_shortcode(nl2br($from_billing_address)); ?></span>
                                </div>
                            <?php } ?>
                            <?php if (!empty($billing_address)) { ?>
                                <div class="wr-fromreceiver">
                                    <h5><?php esc_html_e('To:', 'workreap'); ?></h5>
                                    <span><?php echo do_shortcode(nl2br($billing_address)); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <table class="wr-table wr-invoice-table">
                            <thead>
                                <tr>
                                    <th> #</th>
                                    <th><?php esc_html_e('Description', 'workreap'); ?></th>
                                    <?php do_action( 'workreap_employer_invoice_heading', $payment_type );?>
                                    <th><?php esc_html_e('Cost', 'workreap'); ?></th>
                                    <th><?php esc_html_e('Amount', 'workreap'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($payment_type) && $payment_type === 'tasks') {
                                    if (!empty($order_details['title']) && !empty($order_details['price'])) { ?>
                                        <tr>

                                            <td data-label="<?php esc_attr_e('#', 'workreap'); ?>"><?php echo intval(1); ?></td>
                                            <td data-label="<?php esc_attr_e('Description', 'workreap'); ?>"><?php echo esc_html($task_title . ' (' . $order_details['title'] . ')'); ?></td>
                                            <td data-label="<?php esc_attr_e('Cost', 'workreap'); ?>"><?php workreap_price_format($order_details['price']); ?></td>
                                            <td data-label="<?php esc_attr_e('Amount', 'workreap'); ?>"><?php workreap_price_format($order_details['price']); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (!empty($order_details['subtasks'])) { ?>
                                        <tr>
                                        <td data-label="<?php esc_attr_e('#', 'workreap'); ?>"><?php echo intval(2); ?></td>
                                        <td data-label="<?php esc_attr_e('Description', 'workreap'); ?>">
                                            <?php esc_html_e('Add-on services:', 'workreap'); ?>
                                            <ul class="wr-tablelist">
                                            <?php
                                            $sub_task_price   = '';
                                            $sub_task_total   = 0;
                                            foreach ($order_details['subtasks'] as $subtask) {
                                                $subtask_price  = !empty($subtask['price']) ? $subtask['price'] : 0;
                                                if(function_exists('wmc_revert_price')){
                                                    $subtask_price  = wmc_revert_price($subtask_price,$order->get_currency());
                                                }
                                                $sub_task_total = $sub_task_total + $subtask_price;
                                                $sub_task_price .= '<li>' . workreap_price_format($subtask_price, 'return') . '</li>';
                                            ?>
                                                <li><?php echo esc_html($subtask['title']); ?></li>
                                            <?php
                                            }
                                            ?>
                                            </ul>
                                        </td>
                                        <td data-label="<?php esc_attr_e('Cost', 'workreap'); ?>">
                                            <ul class="wr-tablelist wr-tablelistv2">
                                                <?php echo do_shortcode($sub_task_price); ?>
                                            </ul>
                                            <h6><?php workreap_price_format($sub_task_total); ?></h6>
                                        </td>
                                        <td data-label="<?php esc_attr_e('Amount', 'workreap'); ?>"><?php workreap_price_format($sub_task_total); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else if (!empty($payment_type) && $payment_type === 'package') { ?>
                                    <tr>
                                        <td data-label="<?php esc_attr_e('#', 'workreap'); ?>"><?php echo intval(1); ?></td>
                                        <td data-label="<?php esc_attr_e('Description', 'workreap'); ?>"><?php echo esc_html($task_title); ?></td>
                                        <td data-label="<?php esc_attr_e('Cost', 'workreap'); ?>"><?php workreap_price_format($order_meta['price']); ?></td>
                                        <td data-label="<?php esc_attr_e('Amount', 'workreap'); ?>"><?php workreap_price_format($order_meta['price']); ?></td>
                                    </tr>
                                <?php } else if( !empty($payment_type) && $payment_type === 'projects'){  ?>
                                    <tr>
                                        <td data-label="<?php esc_attr_e('#', 'workreap');?>"><?php echo intval(1);?></td>
                                        <td data-label="<?php esc_attr_e('Description', 'workreap');?>">
                                            <?php
                                                $project_title  = $task_title;
                                                $project_type   = !empty($order_meta['project_type']) ? ($order_meta['project_type']) : '';
                                                if( !empty($project_type) && $project_type === 'fixed' && !empty($order_meta['milestone_id']) && !empty($order_meta['proposal_meta']['milestone'][$order_meta['milestone_id']]['title'])){
                                                    $project_title  = $order_meta['proposal_meta']['milestone'][$order_meta['milestone_id']]['title'];
                                                }
                                                echo esc_html($project_title);
                                            ?>
                                        </td>
                                        <td data-label="<?php esc_attr_e('Cost', 'workreap');?>"><?php workreap_price_format($order_meta['price']);?></td>
                                        <td data-label="<?php esc_attr_e('Amount', 'workreap');?>"><?php workreap_price_format($order_meta['price']);?></td>
                                    </tr>
                                <?php } else {
                                    do_action( 'workreap_add_employer_invoice_details', $order_meta,$order_id );
                                } ?>
                            </tbody>
                        </table>
                        <div class="wr-subtotal">
                            <ul class="wr-subtotalbill">
                                <li><?php esc_html_e('Subtotal', 'workreap'); ?> : <h6><?php workreap_price_format($get_subtotal); ?></h6> </li>
                                <?php if (!empty($payment_type) && $payment_type === 'package' && !empty($processing_fee)) { ?>
                                    <li><?php esc_html_e('Taxes & fees', 'workreap'); ?>:<h6><?php workreap_price_format($processing_fee); ?></h6>
                                </li>
                                <?php } else { ?>
                                    <li><?php esc_html_e('Admin commission', 'workreap'); ?>: <h6>-<?php workreap_price_format($processing_fee); ?></h6></li>
                                <?php } ?>
                            </ul>
                            <div class="wr-sumtotal">
                                <?php esc_html_e('Total', 'workreap'); ?> : <h6><?php workreap_price_format($get_total); ?></h6>
                            </div>
                        </div>
                        <?php if (!empty($invoice_terms)) { ?>
                            <div class="wr-anoverview">
                                <div class="wr-description">
                                    <?php echo do_shortcode($invoice_terms); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
