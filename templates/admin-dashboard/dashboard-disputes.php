<?php
/**
 * Dispute listings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/admin_dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user, $wp_roles, $userdata, $post, $workreap_settings;

$reference 		 = !empty($_GET['ref'] ) ? $_GET['ref'] : '';
$mode 			 = !empty($_GET['mode']) ? $_GET['mode'] : '';
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? $args['id'] : '';
$user_type		 = apply_filters('workreap_get_user_type', $user_identity );
$label			 = esc_html__('Employer name', 'workreap');
$meta_key		 = '_freelancer_id';

if($user_type == 'employers'){
	$meta_key	= '_send_by';
	$label		= esc_html__('Freelancer name', 'workreap');
}

if ( !class_exists('WooCommerce') ) {
	return;
}

$dispute_posts_count	= wp_count_posts('disputes');
$price_symbol			= workreap_get_current_currency();
$currency_symbol		= !empty($price_symbol['symbol']) ? $price_symbol['symbol'] : '$';

$paged	= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$search	= !empty($_GET['search']) ? esc_html($_GET['search']) : '';
$status	= !empty($_GET['status']) ? esc_html($_GET['status']) : array('disputed','resolved','refunded');

if($status	== 'new'){
	$status	= 'publish';
} elseif($status	== 'resolved'){
	$status	= 'refunded';
}

$posts_per_page	= get_option('posts_per_page');
$workreap_args = array(
    'post_type'         => 'disputes',
    'post_status'       => $status,
    'posts_per_page'    => $posts_per_page,
    'paged'             => $paged,
    'orderby'           => 'date',
    'order'             => 'DESC',	
);

if(!empty($search)){
	$workreap_args['s'] = esc_html($search);
}

if(!empty($days)){
	$workreap_args['date_query'] = array(
        'after' => date('Y-m-d', strtotime("-$days days")) 
    );
}

$workreap_query	= new WP_Query( apply_filters('workreap_dispute_listings_args', $workreap_args) );
$total_posts	= (int)$workreap_query->found_posts;

$dispute_percentage	= workreap_disppute_date_query_count('disputes');
$percentChange		= !empty($dispute_percentage['percentChange']) ? $dispute_percentage['percentChange'] : '0';
$change				= !empty($dispute_percentage['change']) ? $dispute_percentage['change'] : 'decrease';

$change_class		= 'wr-icon-chevron-left';
$changearrow_class	= 'wr-icon-arrow-down';

if($change == 'increase'){
	$change_class		= 'wr-icon-chevron-right';
	$changearrow_class	= 'wr-icon-arrow-up';
}
$search_status	= !empty($status) && !is_array($status) ? $status : '';
?>
<div class="col-md-4 wr-md-50 wr-disputes-col">
	<div class="wr-dbholder">
		<div class="wr-dbbox wr-dbboxtitle">
			<h5><?php esc_html_e('Dispute summary', 'workreap');?></h5>
		</div>
		<div class="wr-dbbox wr-asideboxvtwo" id="dispute-summary1">
			<?php workreap_get_template_part('admin-dashboard/dashboard', 'disputes-summary');?>
		</div>
	</div>
</div>
<div class="col-md-8 wr-md-50 wr-disputes-col">
	<div class="wr-dhb-mainheading">
		<h2><?php esc_html_e('Disputes listings', 'workreap');?></h2>
		<div class="wr-sortby">
			<form class="wr-themeform wr-displistform">
				<input type="hidden" name="ref" value="<?php echo esc_attr($reference);?>" >
				<input type="hidden" name="mode" value="<?php echo esc_attr($mode);?>" >
				<input type="hidden" name="identity" value="<?php echo intval($user_identity);?>" >

				<fieldset>
					<div class="wr-themeform__wrap">
						<div class="form-group wr-inputicon wr-inputheight wr-dbholder">
							<i class="wr-icon-search"></i>
							<input type="text" class="form-control" name="search" onkeyup="tablecellsearch()" id="myInputTwo" autocomplete="off" placeholder="<?php esc_attr_e('Search dispute listing', 'workreap');?>">
						</div>
						<?php echo esc_attr($search_status);?>
						<div class="wr-actionselect">
						<span><?php esc_html_e('Sort by', 'workreap');?>: </span>
							<div class="wr-select wr-dbholder border-0">
								<select id="wr-selection1" class="form-control dispute-status-select" name="status">
									<option value=""><?php esc_html_e('All disputes', 'workreap');?></option>
									<option value="refunded" <?php if(!empty($search_status) && $search_status == 'refunded'){echo esc_attr('selected');}?>><?php esc_html_e('Resolved disputes', 'workreap');?></option>
									<option value="disputed" <?php if(!empty($search_status)  &&  $search_status == 'disputed'){echo esc_attr('selected');}?>><?php esc_html_e('New disputes', 'workreap');?></option>
								</select>
							</div>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<?php if ( $workreap_query->have_posts() ) :?>
		<div class="wr-disputetable wr-disputetablev2">
			<table class="table wr-table wr-dbholder">
				<thead>
					<tr>
						<th> <?php esc_html_e('Ref #', 'workreap');?></th>
						<th><?php esc_html_e('Employer Name', 'workreap');?></th>
						<th><?php esc_html_e('Freelancer Name', 'workreap');?></th>
						<th><?php esc_html_e('Dated', 'workreap');?></th>
						<th><?php esc_html_e('Status', 'workreap');?></th>
						<th><?php esc_html_e('Action', 'workreap');?></th>
					</tr>
				</thead>
				<tbody>
				<?php while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
					$meta_key	= '_freelancer_id';
					if($user_type == 'employers'){
						$meta_key	= '_send_by';
						$label	= esc_html__('Freelancer name', 'workreap');
					}
					
					$freelancer_id	= get_post_meta($post->ID, '_freelancer_id', true);
					$employer_id	= get_post_meta($post->ID, '_employer_id', true);
					$order_id	= get_post_meta($post->ID, '_dispute_order', true);
					$employer_profile_id	= workreap_get_linked_profile_id($employer_id,'','employers');
					$freelancer_profile_id	= workreap_get_linked_profile_id($freelancer_id,'','freelancers');
					$post_type		= get_post_type( $order_id );
					if( !empty($post_type) && $post_type === 'proposals' ){
						$dispute_url	= Workreap_Profile_Menu::workreap_profile_admin_menu_link('project', $user_identity, true, 'dispute');
					} else {
						$dispute_url	= Workreap_Profile_Menu::workreap_profile_admin_menu_link('disputes', $user_identity, true, 'detail');
					}
					
					$dispute_url	= add_query_arg('id', $post->ID, $dispute_url);					
					?>
					<tr>
						<td data-label="<?php esc_attr_e('Ref #','workreap');?>">
							<?php echo intval($post->ID);?>
						</td>
						<td data-label="<?php esc_html_e('Employer Name', 'workreap');?>">
							<?php if( !empty($employer_profile_id) ){?>
								<a href="<?php echo esc_url(get_edit_post_link($employer_profile_id));?>"><?php echo esc_html(workreap_get_username($employer_profile_id))?></a>
							<?php } ?>
						</td>
						<td data-label="<?php esc_html_e('Freelancer Name', 'workreap');?>"><a href="<?php echo esc_url(get_the_permalink($freelancer_profile_id));?>"><?php echo esc_html(workreap_get_username($freelancer_profile_id))?></a> </td>
						<td data-label="<?php esc_attr_e('Dated', 'workreap');?>"><?php echo esc_html(get_the_date());?></td>
						<td data-label="<?php esc_attr_e('Status','workreap');?>">
							<div class="wr-bordertags">
								<span class="wr-tag-bordered wr-dispute-<?php echo esc_html(get_post_status($post->ID));?>"><?php echo esc_html(workreap_dispute_status($post->ID));?></span>
							</div>
							
						</td>
						<td data-label="<?php esc_attr_e('Action','workreap');?>">
							<span class="wr-tag-bordered">
								<a href="<?php echo esc_url($dispute_url);?>" class="wr-vieweye"><span class="wr-icon-eye"></span> <?php esc_html_e('View', 'workreap');?></a>
							</span>
						</td>				
					</tr>
				  <?php endwhile;?>					
				</tbody>
			</table>
			<?php if($total_posts > $posts_per_page){?>
				<div class="wr-tabfilteritem">
					<?php workreap_paginate($workreap_query); ?>
				</div>
			<?php }?>
		</div>
	<?php else:
        $image_url = !empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png';
        ?>
		<div class="wr-submitreview wr-submitreviewv3">
			<figure>
				<img src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e( 'There are no disputes against any task.', 'workreap'); ?>">
			</figure>
			<h4><?php esc_html_e( 'There are no disputes against any task.', 'workreap'); ?></h4>
		</div>
	<?php endif;?>
</div>
<?php
wp_reset_postdata();
