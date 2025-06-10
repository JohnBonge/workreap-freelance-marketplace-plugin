<?php
/**
 * Dispute listings summary
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/admin_dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

$dispute_posts_count	= wp_count_posts('disputes');
$count_disputes			= workreap_get_post_count_by_meta('disputes',array('disputed','resolved','refunded'));
$count_resolved			= workreap_get_post_count_by_meta('disputes',array('resolved','refunded'));
$count_disputed			= workreap_get_post_count_by_meta('disputes',array('disputed'));
$posts_count			= $dispute_posts_count;
$posts_count 			=  (array) $posts_count;
unset($posts_count['trash']);

$total_posts		= array_sum($posts_count);
$dispute_percentage	= workreap_disppute_date_query_count('disputes');
$percentChange		= !empty($dispute_percentage['percentChange']) ? $dispute_percentage['percentChange'] : '0';
$change				= !empty($dispute_percentage['change']) ? $dispute_percentage['change'] : 'decrease';
$change_class		= 'wr-icon-chevron-left';
$changearrow_class	= 'wr-icon-arrow-down';

if ($change == 'increase') {
	$change_class		= 'wr-icon-chevron-right';
	$changearrow_class	= 'wr-icon-arrow-up';
}
?>
<div class="wr-admindispute">
	<h2> <?php echo sprintf(_n('%s Dispute', '%s Disputes', $count_disputes, 'workreap'), $count_disputes); ?> <span></span></h2>
	<h6><?php esc_html_e('Total disputes in queue till now', 'workreap'); ?></h6>
</div>
<?php do_action('workreap_dispute_report_display');?>
<ul class="wr-totaldistupes">
	<li>
		<div class="wr-disputesicons">
			<figure>
				<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI . 'admin-dashboard/images/disputes/img-02.png'); ?>" alt="">
			</figure>
			<div class="wr-disputecount">
				<h5><?php echo intval($count_disputed); ?></h5>
				<h6><?php esc_html_e('New disputes', 'workreap'); ?></h6>
			</div>
		</div>
	</li>
	<li>
		<div class="wr-disputesicons">
			<figure>
				<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI . 'admin-dashboard/images/disputes/img-03.png'); ?>" alt="<?php echo esc_attr('img-dispute') ?>">
			</figure>
			<div class="wr-disputecount">
				<h5><?php echo intval($count_resolved); ?></h5>
				<h6><?php esc_html_e('Total resolved disputes', 'workreap'); ?></h6>
			</div>
		</div>
	</li>
</ul>