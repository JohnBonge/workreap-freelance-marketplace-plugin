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
$order_id           = !empty($_GET['id']) ? intval($_GET['id']) : 0;
$user_identity 	    = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;


$task_id                = get_post_meta( $order_id, 'task_product_id', true);
$task_id                = !empty($task_id) ? $task_id : 0;

if (!class_exists('WooCommerce')) {
    return;
}
$employer_id       = get_post_meta( $order_id, 'employer_id',true );
$employer_id       = !empty($employer_id) ? intval($employer_id) : 0;

$freelancer_id       = get_post_meta( $order_id, 'freelancer_id',true );
$freelancer_id       = !empty($freelancer_id) ? intval($freelancer_id) : 0;

$order 		    = wc_get_order($order_id);
$order_status   = $order->get_status();
$task_status    = get_post_meta( $order_id, '_task_status', true);
$task_status    = !empty($task_status) ? $task_status : '';
$dispute_id     = get_post_meta( $order_id, 'dispute_id', true);
$dispute_id     = !empty($dispute_id) ? $dispute_id : 0;

$dispute_detail_url	= Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $user_identity, true, 'detail');
$dispute_detail_url	= !empty($dispute_id) ? add_query_arg('id', $dispute_id, $dispute_detail_url) : '';	
$post_author        = !empty($dispute_id) ? get_post_field( 'post_author', $dispute_id ) : 0;
$dispute_status     = !empty($dispute_id) ? get_post_status( $dispute_id ) : '';
if( !empty($freelancer_id) && $freelancer_id == $user_identity ){
    if(!empty($dispute_id) && $order_status == 'completed' && !empty($dispute_status) && in_array($dispute_status,array('disputed','publish')) ){
        
        ?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <?php if( !empty($post_author) && $post_author == $user_identity){?>
                        <h5><?php esc_html_e('Dispute created', 'workreap');?></h5>
                        <p><?php esc_html_e('You have created a dispute for this order. You can check the status by clicking the link below.', 'workreap');?></p>
                    <?php } else {?>
                        <h5><?php esc_html_e('Refund requested', 'workreap');?></h5>
                        <p><?php esc_html_e('The employer has created a refund request for this order, you can process or decline this refund request', 'workreap');?></p>
                    <?php } ?>
                </div>
                <div class="wr-orderbtn">
                    <a class="wr-btn btn-orange" href="<?php echo esc_url($dispute_detail_url);?>"><?php esc_html_e('View details', 'workreap');?></a>
                </div>
            </div>                   
        </div>
    <?php } elseif (!empty($dispute_id) && ($order_status == 'refunded' || $order_status == 'cancelled')) { ?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refunded/Cancelled', 'workreap');?></h5>
                    <p><?php esc_html_e('This order was refunded, you can check more detail on the refund and dispute page', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                    <a class="wr-btn btn-orange" href="<?php echo esc_url($dispute_detail_url);?>"><?php esc_html_e('View Details', 'workreap');?></a>
                </div>
            </div>                   
        </div>
    <?php } elseif (empty($dispute_id) && !empty($task_status)  && $task_status == 'cancelled') {?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Order cancelled', 'workreap');?></h5>
                    <p><?php esc_html_e('Employer has cancelled this order, if you think this is not good then you can create a dispute for this order', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                    <span id="taskrefundrequest" class="wr-btn btn-orange"><?php esc_html_e('Create dispute', 'workreap');?></span>
                </div>
            </div>                   
        </div>
        
    <?php } elseif ( !empty($dispute_id) && !empty($dispute_status) && $dispute_status === 'declined' ) {?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refund declined', 'workreap');?></h5>
                    <p><?php esc_html_e('You have declined the refund request for this order. You may create the dispute for this order', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                    <span class="wr-btn btn-orange" id="taskdisputerequest" data-dispute_id="<?php echo intval($dispute_id); ?>"><?php esc_html_e('Create dispute', 'workreap');?></span>
                </div>
            </div>                   
        </div>
    <?php } elseif ( !empty($dispute_id) && !empty($dispute_status) && $dispute_status === 'declined' ) {?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refund declined', 'workreap');?></h5>
                    <p><?php esc_html_e('You have declined the refund request for this order. You may create the dispute for this order', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                    <span class="wr-btn btn-orange" id="taskdisputerequest" data-dispute_id="<?php echo intval($dispute_id); ?>"><?php esc_html_e('Create dispute', 'workreap');?></span>
                </div>
            </div>                   
        </div>
    <?php } else if(!empty($dispute_status) && $dispute_status === 'refunded'){ 
         $winning_party  = get_post_meta( $dispute_id, 'winning_party',true );
         if( !empty($winning_party) && intval($winning_party) === intval($user_identity) ){?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-alert-success">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refund approved', 'workreap');?></h5>
                    <p><?php esc_html_e('The admin has approved your refund request, the amount has been added to your wallet. You can use this amount for your next order', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                    <a class="wr-btn btn-orange" href="<?php echo esc_url($dispute_detail_url);?>"><?php esc_html_e('View Details', 'workreap');?></a>
                </div>
            </div>                   
        </div>
    <?php } }
} elseif( !empty($employer_id) && $employer_id === $user_identity && !empty($dispute_id)){ 
    if(!empty($dispute_status) && $dispute_status === 'disputed'){ ?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <?php if( !empty($post_author) && intval($post_author) == intval($user_identity) ){?>
                        <h5><?php esc_html_e('Dispute created', 'workreap');?></h5>
                        <p><?php esc_html_e('You have created a dispute for this order. You can check the status by clicking the link below.', 'workreap');?></p>
                    <?php } else {?>
                        <h5><?php esc_html_e('Dispute created', 'workreap');?></h5>
                        <p><?php esc_html_e('The freelancer has created a dispute against that order, admin will review the history of this order and make the final decision', 'workreap');?></p>
                    <?php } ?>
                </div>
                <div class="wr-orderbtn">
                    <a class="wr-btn btn-orange" href="<?php echo esc_url($dispute_detail_url);?>"><?php esc_html_e('View details', 'workreap');?></a>
                </div>
            </div>                   
        </div>
    <?php } else if(!empty($dispute_status) && $dispute_status === 'publish'){ 
         $employer_dispute_days	= !empty($workreap_settings['employer_dispute_option'])	? intval($workreap_settings['employer_dispute_option']) : 5;
         $post_date             = !empty($dispute_id) ? get_post_field( 'post_date', $dispute_id ) : 0;
         $disbuted_time         = !empty($post_date) ? strtotime($post_date. ' + '.intval($employer_dispute_days).' days') : 0;
         $current_time          = current_time( 'mysql', 1 );
        ?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refund  request', 'workreap');?></h5>
                    <p><?php esc_html_e('You have created a refund request for this order. You can check the status by clicking the link below.', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                    <a class="wr-btn btn-orange" href="<?php echo esc_url($dispute_detail_url);?>"><?php esc_html_e('View Details', 'workreap');?></a>
                </div>
            </div>                   
        </div>
        <?php if( !empty($disbuted_time) && $disbuted_time < $current_time ){ ?>
            <div class="col-lg-12">
                <div class="wr-orderrequest wr-orderrequestv2">
                    <div class="wr-ordertitle">
                        <h5><?php esc_html_e('Create dispute', 'workreap');?></h5>
                        <p><?php esc_html_e('The freelancer has not replied to your refund request, you can now raise a dispute to acknowledge the admin', 'workreap');?></p>
                    </div>
                    <div class="wr-orderbtn">
                        <span class="wr-btn btn-orange"  id="taskdisputerequest" data-dispute_id="<?php echo intval($dispute_id); ?>" ><?php esc_html_e('Create dispute', 'workreap');?></span>
                    </div>
                </div>                   
            </div>
        <?php } ?>
    <?php } else if(!empty($dispute_status) && $dispute_status === 'declined'){ ?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refund declined', 'workreap');?></h5>
                    <p><?php esc_html_e('The freelancer has declined your refund request, you can now create the dispute for this order.', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                    <span class="wr-btn btn-orange"  id="taskdisputerequest" data-dispute_id="<?php echo intval($dispute_id); ?>" ><?php esc_html_e('Create dispute', 'workreap');?></span>
                </div>
            </div>                   
        </div>
    <?php } else if(!empty($dispute_status) && $dispute_status === 'refunded'){ 
        $winning_party  = get_post_meta( $dispute_id, 'winning_party',true );
        if( !empty($winning_party) && intval($winning_party) === intval($user_identity) ){
            $resolved_by    = get_post_meta( $dispute_id, 'winning_party',true );
            $resolved_by    = !empty($resolved_by) && $resolved_by === 'freelancer' ? esc_html__('freelancer','workreap') : esc_html__('admin','workreap');
        ?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-alert-success">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refund approved', 'workreap');?></h5>
                    <p><?php echo sprintf(esc_html__('The %s has approved your refund request, the amount has been added to your wallet. You can use this amount for your next order', 'workreap'),$resolved_by);?></p>
                </div>
                <div class="wr-orderbtn">
                    <a class="wr-btn btn-orange" href="<?php echo esc_url($dispute_detail_url);?>"><?php esc_html_e('View Details', 'workreap');?></a>
                </div>
            </div>                   
        </div>
    <?php } else { ?>
        <div class="col-lg-12">
            <div class="wr-orderrequest wr-orderrequestv2">
                <div class="wr-ordertitle">
                    <h5><?php esc_html_e('Refund', 'workreap');?></h5>
                    <p><?php esc_html_e('This order was refunded, you can check more detail on the refund and dispute page.', 'workreap');?></p>
                </div>
                <div class="wr-orderbtn">
                <a class="wr-btn btn-orange" href="<?php echo esc_url($dispute_detail_url);?>"><?php esc_html_e('View Details', 'workreap');?></a>
                </div>
            </div>                   
        </div>
    <?php } ?>
<?php } ?>

<?php }
