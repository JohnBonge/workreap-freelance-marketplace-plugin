<?php
/**
 * Profile Services
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
$task_listing_type  = 'v2';
?>
<div class="wr-tasklist">
	<div class="col-lg-8 col-xxl-12 wr-tasks-list<?php echo esc_attr($task_listing_type);?>">
		<?php if ( $workreap_query->have_posts() ) : ?>
			<div class="row gy-4">
				<?php
				while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
					global $post;
					$product 	= wc_get_product( $post->ID );
					?>
					<div class="col-md-6 col-xxl-3">
						<?php
							if( !empty($task_listing_type) && $task_listing_type === 'v2'){
								do_action( 'workreap_listing_task_html_v2', $post->ID );
							} else {
								do_action( 'workreap_listing_task_html_v1', $post->ID );
							}
						?>
					</div>
				<?php endwhile; ?>
			</div>
			<div class="col-sm-12"> <?php workreap_paginate($workreap_query,'wr-service-pagination'); ?> </div>
			<?php
		else:
		do_action( 'workreap_empty_listing', esc_html__('No services found', 'workreap'));
		endif;
		wp_reset_postdata();?>
	</div>
</div>
<?php
if( !empty($task_listing_type) && $task_listing_type === 'v1' ){
    $is_rtl = 'false';
    if( is_rtl() ){
        $is_rtl = 'true';
    }
    $script	= "
    var owl_task	= jQuery('.wr-tasks-slider').owlCarousel({
        rtl:".esc_js($is_rtl).",
        items: 1,
        loop:false,
        nav:true,
        margin: 0,
        autoplay:false,
        lazyLoad:false,
        navClass: ['wr-prev', 'wr-next'],
        navContainerClass: 'wr-search-slider-nav',
        navText: ['<i class=\"wr-icon-chevron-left\"></i>', '<i class=\"wr-icon-chevron-right\"></i>'],
    });

    setTimeout(function(){owl_task.trigger('refresh.owl.carousel');}, 3000);
    jQuery(window).load(function() {
        owl_task.trigger('refresh.owl.carousel');
        setTimeout(function(){owl_task.trigger('refresh.owl.carousel');}, 2000);
    });";
	wp_enqueue_style('owl.carousel');
    wp_enqueue_script('owl.carousel');
    wp_add_inline_script( 'owl.carousel', $script, 'after' );
}