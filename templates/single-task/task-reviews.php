<?php
/**
 * Single task task reviews
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
global $current_user, $product, $userdata, $post;
$product	= !empty($product) ? $product : array();
$task_id	= !empty($task_id) ? intval($task_id) : 0;
$rating     = $product->get_average_rating();
$count		= $product->get_rating_count();
$rating_avg 	= !empty($rating) && !empty($count) ? ($rating/5) * 100 : 0;
$rating_avg     = !empty($rating_avg) ? 'style="width:'.$rating_avg.'%;"' : '';
$per_page		= 10;
$page 			= !empty($_GET['comment_page']) ? intval($_GET['comment_page']) : 1;

$offset 		= ($page * $per_page) - $per_page;
$args 			= array ( 'post_id' => $task_id,'offset'=> $offset,'number'=> $per_page);
$comments 		= get_comments( $args );

if( !empty($comments) ){
	$total_comments	= get_comments(array('post_id' => $task_id));
	$total_comments	= !empty($total_comments) ? count($total_comments) : 0;
	$pages 			= ceil($total_comments/$per_page);
	?>
	<div class="wr-singleservice-tile wr-reviews">
		<div class="wr-sectiontitle wr-sectiontitlev2">
			<div class="wr-featureRating">
				<h4><?php echo sprintf(esc_html__('%s Client reviews','workreap'), intval($count));?></h4>
				<em>( </em>
				<span class="wr-featureRating__stars"><span <?php echo do_shortcode( $rating_avg );?>></span></span>
				<em> <?php echo sprintf(esc_html__('%s Overall rating','workreap'), $rating);?> )</em>
			</div>
		</div>
		<div class="wr-addcommentblog">
			<div class="wr-blogcommentsholder">
				<?php
				$counter	= 0;
				foreach($comments as $comment){
					$employer_id   = !empty($comment->user_id) ? intval($comment->user_id) : 0;
					$employer_id   = !empty($employer_id) ? intval($employer_id) : 0;
					$link_id    = workreap_get_linked_profile_id( $employer_id,'','employers' );
					$avatar     = apply_filters(
						'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100)
					);

					$user_name      = !empty($link_id) ? workreap_get_username($link_id) : '';
					$rating         = !empty($comment->comment_ID) ? get_comment_meta($comment->comment_ID, 'rating', true) : 0;
					$title         	= !empty($comment->comment_ID) ? get_comment_meta($comment->comment_ID, '_rating_title', true) : '';
					$rating_avg     = !empty($rating) ? ($rating/5)*100 : 0;
					$rating_avg     = !empty($rating_avg) ? 'style="width:'.$rating_avg.'%;"' : '';
					if(!empty($rating)){ ?>
					<div class="wr-addcomment">
						<div class="wr-blogimg">
							<?php if( !empty($avatar) ){?>
								<figure>
									<img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
								</figure>
							<?php } ?>
							<div class="wr-blogcmntinfonames">
								<div class="wr-featureRating wr-featureRatingv2">
									<span class="wr-featureRating__stars"><span <?php echo do_shortcode( $rating_avg );?>></span></span>
									<?php if(!empty($rating)){?><h6><?php echo esc_attr(number_format($rating ,1, '.', ''));?></h6><?php } ?>
									<?php if( !empty($comment->comment_date) ){?>
										<span class="wr-featureRating__date">(<?php echo sprintf( esc_html__( '%s ago', 'workreap' ), human_time_diff(strtotime($comment->comment_date)) ); ?>)</span>
									<?php } ?>
								</div>
								<?php if( !empty($title) ){?>
									<div class="wr-comentinfodetail">
										<a href="javascript:void(0);">
											<h5><?php echo esc_html($title);?></h5>
										</a>
									</div>
								<?php } ?>
							</div>
						</div>
						<?php if( !empty($comment->comment_content) ){?>
							<div class="wr-description">
								<p><?php echo esc_html($comment->comment_content);?></p>
							</div>
						<?php } ?>
					</div>
				<?php }} ?>
				<?php if( !empty($total_comments) && $total_comments > $per_page ){?>
					<div class="wr-pagination">
						<?php
							$pagination_arg = array(
								'base'         => @add_query_arg('comment_page','%#%'),
								'format'       => '?comment_page=%#%',
								'total'        => $pages,
								'current'      => $page,
								'show_all'     => false,
								'end_size'     => 1,
								'mid_size'     => 2,
								'prev_next'    => true,
								'prev_text'    => esc_html__('Pre','workreap'),
								'next_text'    => esc_html__('Nex','workreap'),
								'type'         => 'list'
							);
							echo paginate_links( $pagination_arg );
						?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php }