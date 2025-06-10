<?php
/**
 * Dispute listings
 * 
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user, $wp_roles, $userdata, $post, $workreap_settings;

$reference 		 = !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode 			 = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? $args['id'] : '';
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );

$label		= esc_html__('Employer name', 'workreap');
$meta_key	= '_freelancer_id';

if($user_type == 'employers'){
	$meta_key	= '_employer_id';
	$label		= esc_html__('Freelancer name', 'workreap');
}

if ( !class_exists('WooCommerce') ) {
	return;
}

$price_symbol		= workreap_get_current_currency();
$currency_symbol	= !empty($price_symbol['symbol']) ? $price_symbol['symbol'] : '$';

$paged	= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$search	= !empty($_GET['search']) ? esc_html($_GET['search']) : '';
$sortby	= !empty($_GET['sortby']) ? esc_html($_GET['sortby']) : '';

$dispute_status	= 'any';
if( !empty($sortby) ){
	if($sortby === 'disputed' ){
		$dispute_status	= array('disputed');
	} elseif($sortby === 'refund_requested' ){
		$dispute_status	= array('publish');
	} elseif($sortby === 'resolve' ){
		$dispute_status	= array('resolved','refunded');
	}
}

$workreap_args = array(
    'post_type'         => 'disputes',
    'post_status'       => $dispute_status,
    'posts_per_page'    => get_option('posts_per_page'),
    'paged'             => $paged,
    'orderby'           => 'date',
    'order'             => 'DESC',
	'meta_query' => array(
        array(
            'key'     => $meta_key,
            'value'   => $user_identity,
            'compare' => '=',
        ),
    ),
);

if(!empty($search)){
	$workreap_args['s'] = esc_html($search);
}

$workreap_query = new WP_Query( apply_filters('workreap_dispute_listings_args', $workreap_args) );
?>
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="wr-dhb-mainheading wr-dhb-mainheadingv2">
				<h2><?php esc_html_e('Disputes listings', 'workreap');?></h2>
				<div class="wr-sortby">
					<form class="wr-themeform wr-displistform">
						<input type="hidden" name="ref" value="<?php echo esc_attr($reference);?>" >
						<input type="hidden" name="mode" value="<?php echo esc_attr($mode);?>" >
						<input type="hidden" name="identity" value="<?php echo intval($user_identity);?>" >
						<fieldset>
							<div class="wr-themeform__wrap">
								<div class="form-group wo-inputicon wo-inputheight">
									<i class="wr-icon-search" id="dispute-search-btn"></i>
									<input type="text" id="dispute-search" name="search" value="<?php echo esc_attr($search);?>" class="form-control" placeholder="<?php esc_attr_e('Search task here', 'workreap');?>">
								</div>
								<div class="wo-inputicon">
									<div class="wr-actionselect">
										<span><?php esc_html_e('Sort by', 'workreap');?>: </span>
										<div class="wr-select">
											<select id="wr-selection1" name="sortby" class="form-control wr-selectv dispute-search-date">
												<option selected hidden><?php esc_html_e('All disputes', 'workreap'); ?></option>
												<option value="disputed" <?php if(!empty($sortby) && $sortby == 'disputed'){echo esc_attr('selected');}?>><?php esc_html_e('New disputes', 'workreap');?></option>
												<option value="resolve" <?php if(!empty($sortby) && $sortby == 'resolve'){echo esc_attr('selected');}?>><?php esc_html_e('Resolved disputes', 'workreap');?></option>
												<option value="refund_requested" <?php if(!empty($sortby) && $sortby == 'refund_requested'){echo esc_attr('selected');}?>><?php esc_html_e('Refund requested', 'workreap');?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<?php if ( $workreap_query->have_posts() ) :?>
				<table class="table wr-table">
					<thead>
					<tr>
						<th><span><?php esc_html_e('Ref #', 'workreap');?> </span></th>
						<th><?php echo esc_html($label);?></th>
						<th><?php esc_html_e('Dated', 'workreap');?></th>
						<th><?php esc_html_e('Amount', 'workreap');?></th>
						<th><?php esc_html_e('Status', 'workreap');?></th>
						<th><?php esc_html_e('Action', 'workreap');?></th>
					</tr>
					</thead>
					<tbody>
					<?php 
						while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
							$employer_id			= get_post_meta($post->ID, $meta_key, true);
							$order_id			= get_post_meta($post->ID, '_dispute_order', true);
							$dispute_type		= get_post_meta($post->ID, '_dispute_type', true);
							$dispute_type		= !empty($dispute_type) ? $dispute_type : "";
							$linked_user_id		= 0;
							if($user_type == 'employers'){
								$linked_user_id = get_post_meta($post->ID, '_freelancer_id', true);
							} else if($user_type == 'freelancers'){
								$linked_user_id = get_post_meta($post->ID, '_employer_id', true);
							}
							$linked_profile_id = workreap_get_linked_profile_id($linked_user_id);
							$dispute_detail_url	= Workreap_Profile_Menu::workreap_profile_menu_link('disputes', $user_identity, true, 'detail',$post->ID);
							if( get_post_type( $order_id ) === 'proposals' ){
								$dispute_detail_url	= Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $user_identity, true, 'dispute',$post->ID);
							}			
						?>
					<tr>
						<td data-label="<?php esc_attr_e('Ref #','workreap');?>">
							<span><?php echo intval($post->ID);?></span>
						</td>
						<td data-label="<?php echo esc_attr(workreap_get_username($linked_profile_id))?>"><a href="<?php echo esc_url(get_the_permalink($linked_profile_id));?>"><?php echo esc_html(workreap_get_username($linked_profile_id))?></a> </td>
						<td data-label="<?php esc_attr_e('Dated', 'workreap');?>"><?php echo esc_html(get_the_date());?></td>
						<td data-label="<?php esc_attr_e('Amount', 'workreap');?>">
							<span>
								<?php 
									if( !empty($dispute_type) && $dispute_type === 'proposals' ){
										$total_amount		= get_post_meta($post->ID, '_total_amount', true);
										$total_amount		= !empty($total_amount) ? $total_amount : 0;
										workreap_price_format($total_amount);
									} else {
										workreap_price_format( workreap_order_price($order_id));
									}
								?>
							</span>
						</td>
						<td data-label="<?php esc_attr_e('Status','workreap');?>">
							<div class="wr-bordertags">
								<span class="wr-tag-bordered wr-dispute-<?php echo esc_html(get_post_status($post->ID));?>"><?php echo esc_html(workreap_dispute_status($post->ID));?></span>
							</div>
							
						</td>
						<td data-label="<?php esc_attr_e('options','workreap');?>">
							<span class="wr-tag-bordered">
								<a href="<?php echo esc_url($dispute_detail_url);?>" class="wr-vieweye"><span class="wr-icon-eye"></span> <?php esc_html_e('View', 'workreap');?></a>
							</span>
						</td>
					</tr>
					<?php endwhile;?>
					</tbody>
				</table>
				<?php workreap_paginate($workreap_query); ?>
			<?php else:
                $image_url = !empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png';
                ?>
				<div class="wr-submitreview wr-submitreviewv3">
					<figure>
						<img src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('No disputes', 'workreap');?>">
					</figure>
					<h4><?php esc_html_e( 'There are no disputes against any task.', 'workreap'); ?></h4>
				</div>
			<?php endif;
			wp_reset_postdata();?>
		</div>
	</div>
</div>