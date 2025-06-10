<?php
/**
 * The template part for displaying the dashboard Payouts History for freelancer
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard/earning_template
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user;
$user_identity      = intval($current_user->ID);
$ref                = !empty($_GET['ref']) ? esc_html($_GET['ref']) : 'earnings';
$earning_page_link  = Workreap_Profile_Menu::workreap_profile_menu_link($ref, $user_identity, true, '');
$earning_page_link  = !empty($earning_page_link) ? $earning_page_link : '';

// variable to store all query args for search
$query_args   	= array();
$post_status  	= array('pending', 'publish');
$withdraw_id  	= (!empty($_GET['withdraw_id']) ? intval($_GET['withdraw_id']) : "");
$qs_ref       	= (!empty($_GET['ref'])      ? esc_html($_GET['ref'])      : '');
$qs_identity  	= (!empty($_GET['identity']) ? intval($_GET['identity']) : 0);
$sort_by_status = (!empty($_GET['sort_by']) ? esc_html($_GET['sort_by']) : "");

if (!empty($withdraw_id)){
    $filtered_args['post_in'] = array(
        'post__in' => array($withdraw_id),
    );
	
    $query_args = array_merge($query_args,$filtered_args['post_in']);
}

// if sort by status exists, then update the $post_status array
if (!empty($sort_by_status)){
    $post_status    = array($sort_by_status);
}

// standard $query_args as $withdraw_args
$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$show_posts     = get_option('posts_per_page');
$withdraw_args  = array(
    'post_type'       => 'withdraw',
    'author'          => $user_identity,
    'post_status'     => $post_status,
    'posts_per_page'  => $show_posts,
    'paged'           => $paged,
);

$withdraw_args = array_merge_recursive($withdraw_args,$query_args);
$payrols		= workreap_get_payouts_lists();

?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="wr-payouthistory">
                <div class="wr-dhb-mainheading">
                    <h2><?php esc_html_e('Payouts history','workreap'); ?></h2>
                    <div class="wr-sortby">
                        <form class="wr-themeform wr-displistform" id="withdraw_search_form" action="<?php echo esc_url( $earning_page_link ); ?>">
                            <input type="hidden" name="ref" value="<?php echo esc_attr($qs_ref); ?>">
                            <input type="hidden" name="identity" value="<?php echo esc_attr($qs_identity); ?>">
                            <input type="hidden" name="sort_by" id="wr_sort_by_filter" value="<?php echo esc_attr($sort_by_status);?>">
                            <fieldset>
                                <div class="wr-themeform__wrap">
                                    <?php do_action('workreap_withdraw_search', $withdraw_id);?>
                                    <?php do_action('workreap_withdraw_sortby_filter', $sort_by_status);?>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <table class="table wr-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Ref#','workreap'); ?></th>
                            <th><?php esc_html_e('Status','workreap'); ?></th>
                            <th><?php esc_html_e('Method','workreap'); ?></th>
                            <th><?php esc_html_e('Date','workreap'); ?></th>
                            <th><?php esc_html_e('Amount','workreap'); ?></th>
                        </tr>
                    </thead>
					<tbody>
						<?php
						$withdraw_query     = new WP_Query( apply_filters('workreap_withdraw_listings_args', $withdraw_args) );
						$count_post 		= $withdraw_query->found_posts;
						if ( $withdraw_query->have_posts() ) :
							while ( $withdraw_query->have_posts() ) : $withdraw_query->the_post();
                                $post_id      = get_the_ID();
                                $date 		  = get_the_date();
                                $status       = get_post_status( $post_id );
                                if( !empty($status) && $status === 'publish' ){
                                    $status_text = esc_attr__( 'Approved', 'workreap' );
                                }else if( !empty($status) && ( $status === 'pending' || $status === 'draft') ){
                                    $status_text = esc_attr__( 'Pending', 'workreap' );
                                } else {
                                    $status_text = ucfirst($status);
                                }

                                $post_date          = !empty( $date ) ? date_i18n('F j, Y', strtotime($date)) : '';
                                $post_date          = date_i18n( get_option( 'date_format' ),  strtotime(get_the_date()));
                                $unique_key         = get_post_meta( $post_id, '_unique_key', true );
                                $payment_method     = get_post_meta( $post_id, '_payment_method', true );
                                $withdraw_amount    = get_post_meta( $post_id, '_withdraw_amount', true );

                                $withdraw_amount  = !empty($withdraw_amount) ? $withdraw_amount : '';
                                $payment_method   = !empty($payment_method)  ? $payment_method  : '';
                                $unique_key       = !empty($unique_key)      ? $unique_key      : $post_id;
						        $payment_method	= !empty($payrols[$payment_method]['label']) ? $payrols[$payment_method]['label'] : $payment_method;
                            ?>
                                <tr>
                                    <td data-label="<?php esc_attr_e('Ref #', 'workreap');?>">
                                        <div class="wr-checkbox">
                                            <span><?php echo esc_html($unique_key); ?></span>
                                        </div>
                                    </td>
                                    <td data-label="<?php esc_attr_e('Status', 'workreap');?>"><?php echo esc_attr($status_text);?></td>
                                    <td data-label="<?php esc_attr_e('Method', 'workreap');?>"><a href="javascript:void(0)"><?php echo ucfirst(esc_html($payment_method)); ?></a></td>
                                    <td data-label="<?php esc_attr_e('Date', 'workreap');?>"><?php echo esc_html($post_date); ?></td>
                                    <td data-label="<?php esc_attr_e('Amount', 'workreap');?>"><span><?php workreap_price_format($withdraw_amount);?></span></td>
                                </tr>
							<?php endwhile;
							wp_reset_postdata();
						endif; ?>
					</tbody>
                </table>
                <?php if ( !empty($count_post) && $count_post > $show_posts ) {?>
                    <div class="wr-tabfilteritem">
                        <?php workreap_paginate($withdraw_query); ?>
                    </div>
                <?php } ?>

                <?php
					if ( !$withdraw_query->have_posts() ) {
						do_action( 'workreap_empty_listing', esc_html__('Oops!! record not found', 'workreap') );
					}
                ?>
            </div>
        </div>
    </div>
</div>