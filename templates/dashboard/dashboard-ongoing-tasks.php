<?php
/**
 * Ongoing Tasks
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user,$workreap_settings;
$task_downloadable    =  !empty($workreap_settings['task_downloadable']) ? $workreap_settings['task_downloadable'] : '';
$show_posts 	      = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$user_identity 	    = !empty($_GET['identity']) ? intval($_GET['identity']) : intval($current_user->ID);
$user_type		      = apply_filters('workreap_get_user_type', $user_identity );
$user_type_key      =  ($user_type === 'employers') ? 'freelancer_id' : 'employer_id';
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

if (!class_exists('WooCommerce')) {
    return;
}

$meta_query_args  = array();

$order 			      = 'DESC';
$sorting 		      = 'date';
$order_status     = array('wc-completed', 'wc-pending', 'wc-on-hold', 'wc-cancelled', 'wc-refunded', 'wc-processing');

$workreap_args = array(
  'post_type'       => 'shop_order',
  'post_status' 		=> $order_status,
  'posts_per_page'  => $show_posts,
  'paged'           => $paged,
  'orderby'         => $sorting,
  'order'           => $order,
);

if($user_type === 'employers'){
  $meta_query_args[] = array(
    'key' 		      => 'employer_id',
    'value' 	      => $user_identity,
    'compare' 	    => '='
  );
} else{
  $meta_query_args[] = array(
    'key' 		      => 'freelancer_id',
    'value' 	      => $user_identity,
    'compare' 	    => '='
  );
}
$meta_query_args[] = array(
  'key' 		     => 'payment_type',
  'value' 	     => 'tasks',
  'compare' 	   => '='
);

$meta_query_args[] = array(
  'key' 		     => '_task_status',
  'value' 	     => 'hired',
  'compare' 	   => '='
);

$query_relation = array('relation' => 'AND',);
$workreap_args['meta_query'] = array_merge($query_relation, $meta_query_args);

$tasks_result       = new WP_Query( apply_filters('workreap_service_ongoing_listings_args', $workreap_args) );
$found_tasks        = $tasks_result->found_posts;
?>
<div class="wr-sort">
    <h3><?php esc_html_e('Ongoing tasks','workreap'); ?></h3>
</div>
<?php
if( $tasks_result->have_posts() ){
    while ($tasks_result->have_posts()) {
        $tasks_result->the_post();
        global $post;
        $order_id       = $post->ID;
        $task_id        = get_post_meta( $order_id, 'task_product_id', true);
        $task_id        = !empty($task_id) ? $task_id : 0;
        $task_title     = !empty($task_id) ? get_the_title( $task_id ) : '';

        $order 		      = wc_get_order($order_id);
        $order_price    = $order->get_total();
        if(function_exists('wmc_revert_price')){
          $order_price  = wmc_revert_price($order->get_total(),$order->get_currency());
        } 

        $order_price    = !empty($order_price) ? $order_price : 0;

        $employer_id       = get_post_meta( $order_id, $user_type_key, true);
        $employer_id       = !empty($employer_id) ? intval($employer_id) : 0;
        $link_id        = workreap_get_linked_profile_id( $employer_id,'',$user_type );
        $is_verified    = !empty($link_id) ? get_post_meta( $link_id, '_is_verified',true) : '';
        $user_name      = workreap_get_username($link_id);

        $product_data   = get_post_meta( $order_id, 'cus_woo_product_data', true);
        $product_data   = !empty($product_data) ? $product_data : array();
        $task_type      = get_post_meta( $order_id, '_task_type', true);
        
        if( !empty($user_type) && ($user_type === 'freelancers') ) {
          $order_price    = get_post_meta( $order_id, 'freelancer_shares', true);
          $order_price    = !empty($order_price) ? ($order_price) : 0;
        }
        
        $downloadable   = get_post_meta( $task_id, '_downloadable', true);
        $downloadable   = !empty($downloadable) ? ucfirst($downloadable) : 0;
       
        $product        = !empty($task_id) ? wc_get_product( $task_id ) : array();

        $order_url              = Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $user_identity, true, 'detail',$order_id);
        $workreap_service_views  = get_post_meta( $task_id, 'workreap_service_views', TRUE );
        $workreap_service_views  = !empty($workreap_service_views) ? intval($workreap_service_views) : 0;
        ?>
        <div class="wr-tasksitem">
            <div class="wr-tasksitem_head">
                <div class="wr-cards__title">
                    <span class="wr-username">
                        <?php echo esc_html($user_name); ?>
                        <?php if( !empty($is_verified) && $is_verified === 'yes'){?>
                            <i class="wr-icon-check-circle" <?php echo apply_filters('workreap_tooltip_attributes', 'verified_user');?>></i>
                        <?php } ?>
                    </span>
                    <h5><a href="<?php echo esc_url($order_url);?>"><?php echo esc_html($task_title); ?></a></h5>
                    <ul class="wr-rateviews">
                        <?php 
                            do_action('workreap_service_rating_count', $product); 
                            do_action('workreap_service_item_views', $product);
                        ?>
                        <li>
                            <div class="wr-likev2"><?php do_action( 'workreap_project_saved_item', $task_id,'','_saved_tasks','list' );?></div>
                        </li>
                    </ul>
                </div>
                <div class="wr-startingprice">
                    <i><?php esc_html_e('Total task budget','workreap'); ?>:</i>
                    <span><?php workreap_price_format($order_price);?></span>
                </div>
            </div>
            <div class="wr-tasksitem__footer">
                <ul class="wr-sales">
                    <?php do_action( 'workreap_service_sales', $product );?>
                    <?php do_action( 'workreap_service_delivery_time', $product ); ?>
                    <?php 
                      if( !empty($task_downloadable) ){
                          do_action( 'workreap_service_download', $product );
                      }
                    ?>
                </ul>
                <div class="wr-tasksitem__btn">
                    <a href="<?php echo esc_url($order_url);?>"
                        class="wr-btn wr-btnv2"><?php esc_html_e('View details','workreap'); ?></a>
                </div>
            </div>
        </div>
    <?php }
    
    workreap_paginate($tasks_result);
} else {
    do_action( 'workreap_empty_listing', esc_html__('No ongoing task', 'workreap'));
}

wp_reset_postdata();
