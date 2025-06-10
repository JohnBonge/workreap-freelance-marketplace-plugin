<?php
/**
 * Dispute listings
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
global  $post,$workreap_settings;
$task_downloadable    =  !empty($workreap_settings['task_downloadable']) ? $workreap_settings['task_downloadable'] : '';
$post_id    = !empty($args['post_id']) ? intval($args['post_id']) : $post->ID;
$user_id	= !empty($post_id) ? workreap_get_linked_profile_id( $post_id, 'post' ) : 0;
$user_id	= !empty($user_id) ? $user_id : 0;

$user_name  = !empty($post_id) ? workreap_get_username($post_id) : '';
$is_verified= !empty($post_id) ? get_post_meta( $post_id, '_is_verified',true) : '';
$paged 		= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$status		= !empty($post_status) ? $post_status : 'any';
$workreap_args = array(
    'post_status'       => $status,
    'limit'    			=> get_option('posts_per_page'),
    'page'             	=> $paged,
    'author'            => $user_id,
    'orderby'           => 'ID',
    'order'             => 'DESC',
    'tax_query'         => array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'tasks',
        ),
    ),
);
$workreap_query = new WP_Query( apply_filters('workreap_service_listings_args', $workreap_args) );
?>
<div class="wr-tasklist">
	<?php if ( $workreap_query->have_posts() ) :
		while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
			global $post;
			$product 	= wc_get_product( $post->ID );
			?>
			<div class="wr-tasksitem">
				<div class="wr-tasksitem_head">
					<div class="wr-cards__title">
						<?php if( !empty($user_name) ){?>
							<a href="<?php echo get_the_permalink($post_id);?>">
								<?php echo esc_html($user_name);?>
								<?php if( !empty($is_verified) && $is_verified === 'yes'){?>
									<i class="wr-icon-check-circle" <?php echo apply_filters('workreap_tooltip_attributes', 'verified_user');?>></i>
								<?php } ?>
							</a>
						<?php } ?>
						<h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
						<ul class="wr-rateviews">
							<?php
								do_action('workreap_service_rating_count', $product);
								do_action('workreap_service_item_views', $product);
							?>
							<li><div class="wr-likev2"><?php do_action( 'workreap_project_saved_item', $post->ID,'','_saved_tasks','list' );?></div></li>
						</ul>
					</div>
					<?php do_action('workreap_service_item_starting_price', $product);?>
				</div>
				<div class="wr-tasksitem__footer">
					<ul class="wr-sales">
						<?php do_action( 'workreap_service_sales', $product );?>
						<?php do_action( 'workreap_service_delivery_time', $product );?>
						<?php 
							if( !empty($task_downloadable) ){
								do_action( 'workreap_service_download', $product );
							}
						?>
					</ul>
					<div class="wr-tasksitem__btn">
						<a href="<?php the_permalink();?>" class="wr-btn wr-btnv2"><?php esc_html_e('View details','workreap');?></a>
					</div>
				</div>
			</div>
		<?php endwhile;
		workreap_paginate($workreap_query,'wr-service-pagination');
	else:
      do_action( 'workreap_empty_listing', esc_html__('No services found', 'workreap'));
	endif;
	wp_reset_postdata();?>
</div>