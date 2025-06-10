<?php
/**
 *  Employer task detail
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global  $current_user, $workreap_settings;
$order_id                   = !empty($_GET['id']) ? intval($_GET['id']) : 0;
$user_identity              = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;
$workreap_refund_title       = !empty($workreap_settings['employer_refund_req_title']) ? $workreap_settings['employer_refund_req_title'] : esc_html__('Create Refund Request', 'workreap');
$workreap_refund_subheading  = !empty($workreap_settings['employer_refund_req_subheading']) ? $workreap_settings['employer_refund_req_subheading'] : '';
$hide_deadline  = !empty($workreap_settings['hide_deadline']) ? $workreap_settings['hide_deadline'] : 'no';
$employer_dispute_issues       = !empty($workreap_settings['employer_dispute_issues']) ? $workreap_settings['employer_dispute_issues'] : array();

$tpl_terms_conditions   = !empty( $workreap_settings['tpl_terms_conditions'] ) ? $workreap_settings['tpl_terms_conditions'] : '';
$tpl_privacy            = !empty( $workreap_settings['tpl_privacy'] ) ? $workreap_settings['tpl_privacy'] : '';
$term_link              = !empty($tpl_terms_conditions) ? '<a target="_blank" href="'.get_the_permalink($tpl_terms_conditions).'">'.get_the_title($tpl_terms_conditions).'</a>' : '';
$privacy_link           = !empty($tpl_privacy) ? '<a target="_blank" href="'.get_the_permalink($tpl_privacy).'">'.get_the_title($tpl_privacy).'</a>' : '';


if (!class_exists('WooCommerce')) {
    return;
}

$task_id            = get_post_meta($order_id, 'task_product_id', true);
$task_id            = !empty($task_id) ? $task_id : 0;
$order              = wc_get_order($order_id);
$user_type          = apply_filters('workreap_get_user_type', $order->get_user_id());
$wallet_amount      = get_post_meta($order_id, '_wallet_amount', true);
$wallet_amount      = !empty($wallet_amount) ? $wallet_amount : 0;
$order_price        = $order->get_total();
$order_price        = !empty($order_price) ? ($order_price + $wallet_amount) : 0;
$freelancer_id      = get_post_meta($order_id, 'freelancer_id', true);
$freelancer_id      = !empty($freelancer_id) ? intval($freelancer_id) : 0;
$product_data       = get_post_meta($order_id, 'cus_woo_product_data', true);
$product_data       = !empty($product_data) ? $product_data : array();
$order_details      = get_post_meta($order_id, 'order_details', true);
$order_details      = !empty($order_details) ? $order_details : array();
$items              = $order->get_items();
$get_taxes          = $order->get_taxes();
$wr_order_gmt       = get_post_meta($order_id, 'delivery_date', true);
$wr_order_gmt       = !empty($wr_order_gmt) ? intval($wr_order_gmt) : 0;
$wr_order_date      = !empty($wr_order_gmt) ? date('m/d/Y H:i:s', $wr_order_gmt) : '';
$gmt_offset         = get_option('gmt_offset');
if($gmt_offset > 0 ){
    $gmt_offset = '+'.$gmt_offset;
} else {
    $gmt_offset = '-'.$gmt_offset;
}
$task_status        = get_post_meta($order_id, '_task_status', true);
$task_status        = !empty($task_status) ? $task_status : '';
$gmt_time           = current_time('mysql', 1);
$menu_order         = workreap_list_tasks_status();
$order_type         = $task_status;
$task_title         = get_the_title($task_id);
unset($menu_order['any']);
// check in query string if we have activity_id, it appears when employers access page using link from "Order Complete Request" email
$activity_id        = (isset($_GET['activity_id']) && !empty($_GET['activity_id']) ? $_GET['activity_id'] : 0);
$workreap_subtask_id = !empty($workreap_subtask_id) ? $workreap_subtask_id : 0;
?>
<div class="wr-main-section">
    <div class="container">
        <div class="row gy-4">
            <?php workreap_get_template_part('dashboard/dashboard', 'freelancers-dispute-notificaton'); ?>
            <div class="col-lg-12">
                <?php if (!empty($activity_id) && !empty($task_status) && $task_status === 'hired') { ?>
                    <div class="wr-orderrequest wr-finaldelivery-alert">
                        <div class="wr-ordertitle">
                            <h5><?php esc_html_e('Final revision approval request', 'workreap'); ?></h5>
                            <p><?php esc_html_e('You have received a final revision request from the freelancer, you can accept or reject', 'workreap'); ?></p>
                        </div>
                        <div class="wr-orderbtn">
                            <a class="wr-btnlink" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#exampleModalv2"><?php esc_html_e('Reject request', 'workreap'); ?></a>
                            <a class="wr-btn wr_approval_task_action" href="javascript:void(0);" data-title="<?php echo esc_attr($task_title); ?>" data-order_id="<?php echo intval($order_id); ?>" data-task_id="<?php echo intval($task_id); ?>"><?php esc_html_e('Approve request', 'workreap'); ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12 col-lg-8 col-md-12">
                <div class="wr-requestarea">
                    <div class="wr-profile-steps employer-task-detail">
                        <div class="wr-tabbitem__list">
                            <div class="wr-deatlswithimg">
                                <div class="wr-icondetails">
                                    <?php do_action('workreap_task_order_status', $order_id); ?>
                                    <?php if (!empty($task_id)) {
                                        echo do_action('workreap_task_categories', $task_id, 'product_cat');
                                    }?>
                                    <span></span>
                                    <h6><a href="<?php echo get_the_permalink($task_id); ?>"><?php echo esc_html($task_title); ?></a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="wr-extras wr-extrascompleted">
                            <?php do_action('workreap_task_author', $freelancer_id); ?>
                            <?php do_action('workreap_delivery_date', $order_id); ?>
                            <?php do_action('workreap_price_plan', $order_id); ?>
                            <?php do_action('workreap_order_linked', $order_id); ?>
                        </div>
                    </div>
                    <?php workreap_get_template_part('dashboard/dashboard', 'task-feature'); ?>
                    <?php if (!empty($order_details['subtasks']) && is_array($order_details['subtasks'])) { ?>
                        <div class="wr-additonolservices">
                            <div class="wr-additonoltitle" data-bs-toggle="collapse" data-bs-target="#wr-additionolinfo" aria-expanded="true" role="button">
                                <h5><?php esc_html_e('Additional add-ons:', 'workreap'); ?> <span>
                                <?php echo wp_sprintf( _n( '%s Addon requested', '%s Add-ons requested', count($product_data['subtasks']), 'workreap' ), count($product_data['subtasks']) );?>
                               <i class="wr-icon-chevron-down"></i>
                            </div>
                            <div id="wr-additionolinfo" class="wr-addservices_details collapse show">
                                <div class="wr-additionolinfo">
                                    <ul class="wr-additionollist">
                                        <?php
                                        foreach ($order_details['subtasks'] as $subtask) {
                                            $subtask_title  = !empty($subtask['title']) ? $subtask['title'] : '';
                                            $price          = !empty($subtask['price']) ? $subtask['price'] : 0;
                                            ?>
                                            <li>
                                                <h6><?php echo esc_html($subtask_title); ?><span>( +<?php workreap_price_format($price); ?> )</span></h6>
                                                <?php echo wpautop( apply_filters('the_content', get_the_content(null, false, $workreap_subtask_id))); ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php workreap_get_template_part('dashboard/dashboard', 'tasks-activity-history'); ?>
                </div>
            </div>
            <?php if (!empty($order_id)) { ?>
                <div class="col-sm-12 col-lg-4 col-md-12">
                    <aside>
                        <div class="wr-asideholder wr-taskdeadline">
                            <?php if (!empty($order_details)) { ?>
                                <?php do_action('workreap_order_budget_details', $order_id, $user_type); ?>
                            <?php } ?>
                            <div class="wr-taskdeadline">
                                <?php if (!empty($task_status) && $task_status === 'hired') { ?>

                                    <div class="wr-taskcountdown">
                                        <?php if (!empty($hide_deadline) && $hide_deadline === 'no' && !empty($wr_order_date) && strtotime($wr_order_date) > strtotime($gmt_time)) { ?>
                                            <h6><?php esc_html_e('Task deadline', 'workreap'); ?></h6>
                                            <ul class="wr-countdownno">
                                                <li><?php esc_html_e('D:', 'workreap'); ?><span class="days"></span></li>
                                                <li><?php esc_html_e('H:', 'workreap'); ?><span class="hours"></span></li>
                                                <li><?php esc_html_e('M:', 'workreap'); ?><span class="minutes"></span></li>
                                                <li><?php esc_html_e('S:', 'workreap'); ?><span class="seconds"></span></li>
                                            </ul>
                                        <?php } ?>
                                        <div class="wr-actionselect">
                                            <div class="wr-select">
                                                <select id="wr_order_status" class="form-control">
                                                    <?php foreach ($menu_order as $key => $val) {
                                                        $selected   = '';
                                                        if (!empty($order_type) && $order_type == $key) {
                                                            $selected   = 'selected';
                                                        } ?>
                                                        <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($val); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="wr-deadlinebtn">
                                                <button type="submit" data-title="<?php echo esc_attr($task_title); ?>" data-order_id="<?php echo intval($order_id); ?>" data-task_id="<?php echo intval($task_id); ?>" class="wr-btnvtwo wr_task_action"><i class="wr-icon-check"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($task_status) && $task_status === 'hired' || $task_status === 'cancelled') { ?>
                                    <div class="wr-raisedispute">
                                        <?php $dispute_args = array(
                                            'posts_per_page'    => -1,
                                            'post_type'         => array('disputes'),
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
                                        $dispute_is         = get_posts($dispute_args);
                                        $dispute            = !empty($dispute_is['0']) ? $dispute_is['0'] : 0;

                                        if (!empty($dispute->ID) && in_array(get_post_status($dispute->ID), array('publish', 'processing', 'pending'))) { ?>
                                            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $current_user->ID, false, 'detail',$dispute->ID);?>" class="wr-btn"><?php esc_html_e('Refund requested', 'workreap'); ?></a>
                                        <?php } elseif (!empty($dispute->ID) && get_post_status($dispute->ID) == 'refunded') { ?>
                                            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $current_user->ID, false, 'detail',$dispute->ID);?>" class="wr-btn"><?php esc_html_e('Refunded', 'workreap'); ?></a>
                                        <?php } elseif (!empty($dispute->ID) && get_post_status($dispute->ID) == 'resolved') { ?>
                                            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $current_user->ID, false, 'detail',$dispute->ID);?>" class="wr-btn"><?php esc_html_e('Resolved', 'workreap'); ?></a>
                                        <?php } elseif (!empty($dispute->ID) && !in_array(get_post_status($dispute->ID), array('publish', 'processing', 'pending', 'disputed', 'cancelled'))) { ?>
                                            <span id="taskdisputerequest" data-dispute_id="<?php echo intval($dispute->ID); ?>" class="wr-btn"><?php esc_html_e('Create dispute request', 'workreap'); ?></span>
                                        <?php } elseif (!empty($dispute->ID) && get_post_status($dispute->ID) == 'disputed') { ?>
                                            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $current_user->ID, false, 'detail',$dispute->ID);?>" class="wr-btn"><?php esc_html_e('Dispute created', 'workreap'); ?></a>
                                        <?php } elseif (!empty($dispute->ID) && get_post_status($dispute->ID) == 'cancelled') { ?>
                                            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $current_user->ID, false, 'detail',$dispute->ID);?>" class="wr-btn"><?php esc_html_e('Dispute cancelled', 'workreap'); ?></a>
                                        <?php } else { ?>
                                            <span id="taskrefundrequest" class="wr-btn"><?php esc_html_e('Create refund request', 'workreap'); ?></span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </aside>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if (!empty($activity_id) && !empty($task_status) && $task_status === 'hired' || $task_status === 'cancelled') { ?>
    <!-- Request Modal End-->
    <div class="modal fade" id="exampleModalv2" tabindex="-1" role="dialog" aria-hidden="true">
        <form class="wr-themeform wr-activity-reject-chat-form">
            <div class="modal-dialog wr-modaldialog" role="document">
                <div class="modal-content">
                    <div class="wr-popuptitle">
                        <h4><?php esc_html_e('Reject revision request', 'workreap'); ?></h4>
                        <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" name="rejection_reason" placeholder="<?php esc_attr_e('Enter description', 'workreap'); ?>"></textarea>
                        <div class="wr-popupbtnarea">
                            <a href="javascript:void(0)" class="wr-btn wr-submit-revision-reject-request" data-id="<?php echo esc_attr($order_id); ?>" data-activity_id="<?php echo esc_attr($activity_id); ?>"><?php esc_html_e('Submit', 'workreap'); ?><span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Request Modal End-->
<?php } ?>
<?php
if (!empty($task_status) && $task_status === 'hired' || $task_status === 'cancelled') {
    $script = "
    jQuery(document).on('ready', function($){
        workreap_countdown_by_date('" . esc_js($wr_order_date) . "'," . esc_js($gmt_offset) . ");
        jQuery('#wr_order_status').select2({
           theme: 'default wr-select2-dropdown',
           language: {
				noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });
    });
    ";
    wp_add_inline_script('workreap', $script, 'after');
    ?>
    <script type="text/template" id="tmpl-load-task-refund-request">
        <div class="modal-dialog wr-modaldialog" role="document">
            <div class="modal-content">
                <div class="wr-popuptitle">
                    <h4><?php echo esc_html($workreap_refund_title); ?></h4>
                    <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
                </div>
                <div class="modal-body">
                    <div class="wr-popupbodytitle">
                        <?php echo html_entity_decode($workreap_refund_subheading); ?>
                    </div>
                   <form name="refund-request" id="task-refund-request">
                       <input type="hidden" name="order_id" value="<?php echo intval($order_id); ?>">
                       <input type="hidden" name="freelancer_id" value="<?php echo intval($freelancer_id); ?>">
                       <input type="hidden" name="task_id" value="<?php echo intval($task_id); ?>">
                        <div class="wr-disputelist">
                            <ul class="wr-radiolist">
                                <?php if (!empty($employer_dispute_issues)) {
                                    foreach ($employer_dispute_issues as $key => $issue) { ?>
                                        <li>
                                            <div class="wr-radio">
                                                <input type="radio" id="f-option-<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($issue); ?>" name="dispute_issue">
                                                <label for="f-option-<?php echo esc_attr($key); ?>"><?php echo esc_html($issue); ?></label>
                                            </div>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                        <div class="wr-popupbodytitle">
                            <h5><?php esc_html_e('Add dispute details', 'workreap'); ?></h5>
                        </div>
                        <textarea class="form-control" placeholder="<?php esc_attr_e('Enter dispute details', 'workreap'); ?>" id="dispute-details" name="dispute-details"></textarea>
                        <div class="wr-popupbtnarea">
                            <div class="wr-checkterm">
                                <div class="wr-checkbox">
                                    <input id="check3" type="checkbox" name="dispute_terms">
                                    <label for="check3">
                                        <span>
                                            <?php echo sprintf(esc_html__('By clicking you agree with our %s and %s', 'workreap'), $term_link, $privacy_link); ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <a href="javascript:void(0);" id="taskrefundrequest-submit" class="wr-btn"><?php esc_html_e('Submit', 'workreap'); ?> <span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </script>
<?php
}