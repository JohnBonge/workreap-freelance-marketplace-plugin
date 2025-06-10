<?php
/**
 * Freelancer task detail
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global  $current_user,$workreap_settings;
$order_id                   = !empty($_GET['id']) ? intval($_GET['id']) : 0;
$user_identity 	            = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;
$freelancer_dispute_issues      = !empty($workreap_settings['freelancer_dispute_issues']) ? $workreap_settings['freelancer_dispute_issues'] : array();
$hide_deadline  = !empty($workreap_settings['hide_deadline']) ? $workreap_settings['hide_deadline'] : 'no';

$tpl_terms_conditions   = !empty( $workreap_settings['tpl_terms_conditions'] ) ? $workreap_settings['tpl_terms_conditions'] : '';
$tpl_privacy            = !empty( $workreap_settings['tpl_privacy'] ) ? $workreap_settings['tpl_privacy'] : '';
$term_link              = !empty($tpl_terms_conditions) ? '<a target="_blank" href="'.get_the_permalink($tpl_terms_conditions).'">'.get_the_title($tpl_terms_conditions).'</a>' : '';
$privacy_link           = !empty($tpl_privacy) ? '<a target="_blank" href="'.get_the_permalink($tpl_privacy).'">'.get_the_title($tpl_privacy).'</a>' : '';

$task_id    = get_post_meta( $order_id, 'task_product_id', true);
$task_id    = !empty($task_id) ? $task_id : 0;

if (!class_exists('WooCommerce')) {
    return;
}

$order 		    = wc_get_order($order_id);
$order_price    = $order->get_total();
$order_price    = !empty($order_price) ? $order_price : 0;
$freelancer_id      = get_post_meta( $order_id, 'freelancer_id', true);
$freelancer_id      = !empty($freelancer_id) ? intval($freelancer_id) : 0;

$employer_id       = get_post_meta( $order_id, 'employer_id', true);
$employer_id       = !empty($employer_id) ? intval($employer_id) : 0;

$admin_shares   = get_post_meta( $order_id, 'admin_shares', true);
$admin_shares   = !empty($admin_shares) ? ($admin_shares) : 0;
$freelancer_shares  = get_post_meta( $order_id, 'freelancer_shares', true);
$freelancer_shares  = !empty($freelancer_shares) ? ($freelancer_shares) : 0;
$product_data   = get_post_meta( $order_id, 'cus_woo_product_data', true);
$product_data   = !empty($product_data) ? $product_data : array();
$order_details  = get_post_meta( $order_id, 'order_details', true);
$order_details  = !empty($order_details) ? $order_details : array();
$items 		    = $order->get_items();
$get_taxes		= $order->get_taxes();
$wr_order_gmt   = get_post_meta( $order_id, 'delivery_date', true);
$wr_order_gmt   = !empty($wr_order_gmt) ? intval($wr_order_gmt) : 0;
$wr_order_date  = !empty($wr_order_gmt) ? date('Y/m/d H:i:s',$wr_order_gmt) : '';
$gmt_offset         = get_option('gmt_offset');
if($gmt_offset > 0 ){
    $gmt_offset = '+'.$gmt_offset;
} else {
    $gmt_offset = '-'.$gmt_offset;
}
$task_status    = get_post_meta( $order_id, '_task_status', true);
$task_status    = !empty($task_status) ? $task_status : '';
$gmt_time		= current_time( 'mysql', 1 );
$order_type     = $task_status;
$order_status   = $order->get_status();
$dispute_id     = get_post_meta( $order_id, 'dispute_id', true);
$dispute_id     = !empty($dispute_id) ? $dispute_id : 0;


?>
<section class="wr-main-section">
    <div class="container">
        <div class="row">
            <?php workreap_get_template_part('dashboard/dashboard', 'freelancers-dispute-notificaton'); ?>
            <div class="col-sm-12 col-lg-8 col-md-12">
                <div class="wr-requestarea">
                    <div class="wr-profile-steps freelancer-task-detail">
                        <div class="wr-tabbitem__list">
                            <div class="wr-deatlswithimg">
                                <div class="wr-icondetails">
                                    <?php do_action( 'workreap_task_order_status', $order_id );?>
                                    <?php if( !empty($task_id) ){ echo do_action('workreap_task_categories', $task_id, 'product_cat'); } ?>
                                    <span></span>
                                    <h6><a href="<?php echo get_the_permalink( $task_id );?>"><?php echo get_the_title($task_id);?></a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="wr-extras wr-extrascompleted">
                            <?php do_action( 'workreap_task_author', $employer_id,'employers');?>
                            <?php do_action( 'workreap_delivery_date', $order_id );?>
                            <?php do_action( 'workreap_price_plan', $order_id );?>
                            <?php do_action('workreap_order_linked', $order_id); ?>
                        </div>
                    </div>
                    <?php workreap_get_template_part('dashboard/dashboard', 'task-feature'); ?>
                    <?php if( !empty($order_details['subtasks']) && is_array($order_details['subtasks']) ){?>
                        <div class="wr-additonolservices">
                            <div class="wr-additonoltitle" data-bs-toggle="collapse" data-bs-target="#wr-additionolinfo"
                                aria-expanded="true" role="button">
                                <h5><?php esc_html_e('Additional services','workreap');?>
                                    <span><?php echo wp_sprintf( _n( '%s Addon requested', '%s Add-ons requested', count($product_data['subtasks']), 'workreap' ), count($product_data['subtasks']) );?></span>
                                </h5>
                                <i class="wr-icon-chevron-down"></i>
                            </div>
                            <div id="wr-additionolinfo" class="wr-addservices_details collapse show">
                                <div class="wr-additionolinfo">
                                    <ul class="wr-additionollist">
                                        <?php
                                        foreach($order_details['subtasks'] as $subtask ){
                                            $subtask_title      = !empty($subtask['title']) ? $subtask['title'] : '';
                                            $price              = !empty($subtask['price']) ? $subtask['price'] : 0;
                                            $workreap_subtask_id = !empty($subtask['id']) ? $subtask['id'] : 0;
                                        ?>
                                        <li>
                                            <h6><?php echo esc_html($subtask_title);?><span>(+<?php workreap_price_format($price);?> )</span></h6>
                                            <?php echo wpautop( apply_filters( 'the_content', get_the_content(null, false, $workreap_subtask_id)) );?>
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
            <?php if( !empty($freelancer_shares) ){?>
                <div class="col-sm-12 col-lg-4 col-md-12">
                    <aside>
                        <div class="wr-asideholder wr-taskdeadline">
                            <div class="wr-asidebox wr-additonoltitleholder">
                                <div data-bs-toggle="collapse" data-bs-target="#wr-additionolinfov2" aria-expanded="true"
                                    role="button">
                                    <div class="wr-additonoltitle">
                                        <div class="wr-startingprice">
                                            <i><?php esc_html_e('Total task budget','workreap');?></i>
                                            <span><?php workreap_price_format($freelancer_shares); ?></span>
                                        </div>
                                        <?php if( !empty($order_details['subtasks']) || !empty($admin_shares) ){?>
                                            <i class="wr-icon-chevron-down"></i>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if( !empty($order_details['subtasks']) || !empty($admin_shares) ){?>
                                <div id="wr-additionolinfov2" class="show">
                                    <div class="wr-budgetlist">
                                        <ul class="wr-planslist">
                                            
                                        <?php if( !empty($order_details['title']) && !empty($order_details['price'])){ ?>
                                            <li>
                                                <h6><?php echo esc_html($order_details['title']);?>
                                                <span>(<?php workreap_price_format($order_details['price']);?>)</span></h6>
                                            </li>
                                            <?php }?>

                                            <?php 
                                                if( !empty($order_details['subtasks']) ){
                                                    foreach($order_details['subtasks'] as $subtask ){
                                                        $subtask_title  = !empty($subtask['title']) ? $subtask['title'] : '';
                                                        $price          = !empty($subtask['price']) ? $subtask['price'] : 0;
                                                        if(function_exists('wmc_revert_price')){
                                                            $price  = wmc_revert_price($price,$order->get_currency());
                                                        } 
                                                    ?>
                                                        <li>
                                                            <h6><?php echo esc_html($subtask_title);?><span>(<?php workreap_price_format($price);?>)</span></h6>
                                                        </li>
                                                <?php } 
                                                }?>
                                        </ul>

                                        <?php if(!empty($admin_shares)){?>
                                            <ul class="wr-planslist wr-texesfee">
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <h6>
                                                            <?php esc_html_e('Admin commission','workreap');?>&nbsp;<span>(<?php workreap_price_format($admin_shares);?>)</span>
                                                        </h6>
                                                    </a>
                                                </li>
                                            </ul>
                                        <?php }?>
                                        <ul class="wr-planslist wr-totalfee">
                                            <li>
                                                <a href="javascript:void(0);">
                                                    <h6><?php esc_html_e('You will get','workreap');?><span>(<?php workreap_price_format($freelancer_shares); ?>)</span></h6>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if( !empty($task_status) && $task_status === 'hired'){?>
                                <div class="wr-taskdeadline">
                                    <?php if(!empty($hide_deadline) && $hide_deadline === 'no' && !empty($wr_order_date) && strtotime($wr_order_date) > strtotime($gmt_time)){?>
                                        <div class="wr-taskcountdown">
                                            <h6><?php esc_html_e('Task deadline','workreap');?></h6>
                                            <ul class="wr-countdownno">
                                                <li>
                                                    <?php esc_html_e('D:','workreap');?>
                                                    <span class="days"></span>
                                                </li>
                                                <li>
                                                    <?php esc_html_e('H:','workreap');?>
                                                    <span class="hours"></span>
                                                </li>
                                                <li>
                                                    <?php esc_html_e('M:','workreap');?>
                                                    <span class="minutes"></span>
                                                </li>
                                                <li>
                                                    <?php esc_html_e('S:','workreap');?>
                                                    <span class="seconds"></span>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <?php if(empty($dispute_id) ){?>
                                        <div class="wr-raisedispute">
                                            <span class="wr-btn" id="taskrefundrequest"><?php esc_html_e('Create dispute','workreap');?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                          <?php } ?>
                        </div>
                    </aside>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<script type="text/template" id="tmpl-load-task-refund-request">
    <div class="modal-dialog wr-modaldialog" role="document">
        <div class="modal-content">
            <div class="wr-popuptitle">
                <h4><?php esc_html_e('Create refund request', 'workreap') ?></h4>
                <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
            </div>
            <div class="modal-body">
                <div class="wr-popupbodytitle">
                    <?php esc_html_e('Choose issue you want to highlight', 'workreap') ?>
                </div>
            <form name="refund-request" id="task-refund-request">
                <input type="hidden" name="order_id" value="<?php echo intval($order_id); ?>">
                <input type="hidden" name="task_id" value="<?php echo intval($task_id); ?>">
                    <div class="wr-disputelist">
                        <ul class="wr-radiolist">
                            <?php if (!empty($freelancer_dispute_issues)) {
                                foreach ($freelancer_dispute_issues as $key => $issue) { ?>
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
                        <a href="javascript:void(0);" id="freelancer-request-submit" class="wr-btn"><?php esc_html_e('Submit', 'workreap'); ?> <span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>
<?php
if( !empty($task_status) && $task_status === 'hired' ){
    $script = "
        jQuery(document).on('ready', function(){
            workreap_countdown_by_date('" . esc_js($wr_order_date) . "'," . esc_js($gmt_offset) . ");
        });
    ";
    wp_add_inline_script( 'workreap', $script, 'after' );
}
