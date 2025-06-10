<?php
/**
 * Dashboard earnings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/admin_dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user;
$reference 		 = !empty($_GET['ref'] ) ? $_GET['ref'] : '';
$mode 			 = !empty($_GET['mode']) ? $_GET['mode'] : '';
$user_identity 	 = intval($current_user->ID);
$id 			 = !empty($args['id']) ? $args['id'] : '';
$status			 = !empty($_GET['status']) ? $_GET['status'] : 'any';
$paged			 = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$post_per_page	 = get_option('posts_per_page');
$workreap_args	= array(
    'post_type'         => 'withdraw',
    'post_status'       => $status,
    'posts_per_page'	=> $post_per_page,
    'paged'             => $paged,
    'orderby'           => 'ID',
    'order'             => 'DESC'
);
$workreap_query 	= new WP_Query( apply_filters('workreap_earning_listings_args', $workreap_args) );
$total_posts	= (int)$workreap_query->found_posts;
$date_format	= get_option( 'date_format' );

$month	= !empty($_POST['months']) ? sprintf("%02d", $_POST['months']) : '';
$year	= !empty($_POST['years']) ? $_POST['years'] : '';
$years 	= array_combine(range(date("Y"), 2018), range(date("Y"), 2018));
$months	= array();

if( function_exists('workreap_list_month') ) {
	$months	= workreap_list_month();
}
?>
<div class="col-md-12">
	<div class="wr-dhb-mainheading">
		<h2><?php esc_html_e('Withdraw requests','workreap');?></h2>
		<div class="wr-sortby wr-payout-sort">
			<form class="wr-themeform wr-displistform" id="wr-withdraw-form" method="post">
				<fieldset>
					<div class="wr-themeform__wrap">
						<div class="wr-actionselect">
							<span><?php esc_html_e('Show only:','workreap');?></span>
							<div class="wr-select wr-dbholder border-0">
								<select name="months" id="bulk-action-selector-top">
									<option value=""><?php esc_html_e('Select month','workreap');?></option>
									<?php if( !empty( $months ) ) {?>
										<?php foreach ( $months as $key	=> $val ) {
											$selected_m = '';

											if( !empty($month) && $month == $key ){
												$selected_m = 'selected';
											}
											?>
											<option value="<?php echo intval($key);?>" <?php echo esc_attr($selected_m);?>><?php echo esc_html($val);?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="wr-profileform__content">
							<div class="wr-select wr-dbholder border-0">
								<select name="years" id="bulk-action-selector-top">
									<option value=""><?php esc_html_e('Select year','workreap');?></option>
									<?php if( !empty( $years ) ) {?>
										<?php foreach ( $years as $key	=> $val ) {
											$selected_y = '';

											if( !empty($year) && $year == $key ){
												$selected_y = 'selected';
											} ?>
											<option value="<?php echo intval($key);?>" <?php echo esc_attr($selected_y);?>><?php echo esc_html($val);?></option>
										<?php } ?>
									<?php } ?>

								</select>
							</div>
						</div>
						<div class="wr-downloadbtn">
							<a href="javascript:void(0);" class="wr-btn wr-doownload-withdraw"><?php esc_html_e('Download','workreap');?><span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span></a>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<div class="wr-dbholder border-0 wr-payout">
		<table class="table wr-table wr-dbholder">
			<thead>
				<tr>
					<th>
						<div class="wr-checkbox">
							<span><?php esc_html_e('Ref #','workreap');?></span>
						</div>
					</th>
					<th><?php esc_html_e('Freelancer name','workreap');?></th>
					<th><?php esc_html_e('Payout method','workreap');?></th>
					<th><?php esc_html_e('Payout amount','workreap');?></th>
					<th><?php esc_html_e('Dated','workreap');?></th>
					<th><?php esc_html_e('Status','workreap');?></th>
				</tr>
			</thead>
			<tbody>
			<?php if ( $workreap_query->have_posts() ) :?>
				<?php
					while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
						global $post;
						$withdraw_key		= get_post_meta( $post->ID, '_unique_key', true );
						$withdraw_key		= !empty($withdraw_key) ? $withdraw_key : $post->ID;
						$withdraw_amount	= get_post_meta( $post->ID, '_withdraw_amount', true );
						$withdraw_amount	= !empty($withdraw_amount) ? $withdraw_amount : '';

						$payment_method	= get_post_meta( $post->ID, '_payment_method', true );
						$payment_method	= !empty($payment_method) ? $payment_method : '';
						$post_author	= get_post_field( 'post_author', $post );
						$post_author	= !empty($post_author) ? $post_author : '';
						$profile_id		= workreap_get_linked_profile_id($post_author,'','freelancers');
						$user_name		= workreap_get_username($profile_id);
						$payrols		= workreap_get_payouts_lists();
						$payment_method	= !empty($payrols[$payment_method]['label']) ? $payrols[$payment_method]['label'] : '';
						$post_status	= get_post_status( $post );?>
						<tr>
							<td data-label="<?php esc_attr_e('Ref #','workreap');?>">
								<div class="wr-checkbox">
									<span> <?php echo esc_html($withdraw_key);?></span>
								</div>
							</td>
							<td data-label="<?php esc_attr_e('Freelancer name','workreap');?>"><a href="<?php echo get_the_permalink( $profile_id );?>"><?php echo esc_html($user_name);?></a></td>
							<td data-label="<?php esc_attr_e('Payout method','workreap');?>"><?php echo esc_html($payment_method);?></td>
							<td data-label="<?php esc_attr_e('Payout amount','workreap');?>"><span><?php workreap_price_format($withdraw_amount);?></span></td>
							<td data-label="<?php esc_attr_e('Dated','workreap');?>"><?php echo esc_html(date_i18n( $date_format,  strtotime(get_the_date($post->ID))));?></td>
							<td data-label="<?php esc_attr_e('Status','workreap');?>" class="wr-dispuitstatus">
								<div class="wr-dispueitems wr-dispueitemsv2">
									<?php do_action( 'workreap_post_status', $post->ID );?>
								</div>
								<div class="wr-dispueitems wr-dispueitemsv2">
									<?php if( !empty($post_status) && $post_status === 'pending'){?>
										<div class="wr-bordertags">
											<a href="javascript:void(0);" data-status="publish" data-id="<?php echo intval($post->ID);?>" class="wr-update-earning bordr-green"><?php esc_html_e( 'Approve', 'workreap' );?></a>
										</div>
										<?php
									} ?>
								</div>
							</td>
						</tr>
					<?php endwhile;
					wp_reset_postdata();
					endif;?>
			</tbody>
		</table>
		<?php if ( $workreap_query->have_posts() && $total_posts >= $post_per_page ) :?>
			<div class="wr-tabfilteritem">
				<?php workreap_paginate($workreap_query); ?>
			</div>
		<?php endif;?>

		<?php if ( !$workreap_query->have_posts() ) {
			do_action( 'workreap_empty_listing', esc_html__('Oops!! record not found', 'workreap') );
		} ?>
		<?php wp_reset_postdata();?>
	</div>
</div>