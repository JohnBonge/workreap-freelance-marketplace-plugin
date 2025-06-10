<?php
/**
 * Dashboard freelancer insights
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user, $workreap_settings;

$hide_languages       = !empty($workreap_settings['hide_languages']) ? $workreap_settings['hide_languages'] : 'no';
$reference		= !empty($_GET['ref'] ) ? esc_html($_GET['ref']) : '';
$mode			= !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity 	= intval($current_user->ID);
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$linked_profile	= workreap_get_linked_profile_id($user_identity,'',$user_type);
$user_name		= workreap_get_username($linked_profile);
$profile_link	= get_the_permalink( $linked_profile );
$switch_user    = !empty($workreap_settings['switch_user']) ? $workreap_settings['switch_user'] : false;
$width			= 315;
$height			= 300;
$avatar	= apply_filters(
	'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => $width, 'height' => $height), $linked_profile), array('width' => $width, 'height' => $height)
);

$wr_total_rating        = get_post_meta( $linked_profile, 'wr_total_rating', true );
$wr_total_rating		= !empty($wr_total_rating) ? $wr_total_rating : 0;
$wr_review_users		= get_post_meta( $linked_profile, 'wr_review_users', true );
$wr_review_users		= !empty($wr_review_users) ? $wr_review_users : 0;
$workreap_profile_views	= get_post_meta( $linked_profile, 'workreap_profile_views', true );
$workreap_profile_views	= !empty($workreap_profile_views) ? $workreap_profile_views : 0;

$meta_array	= array(
	array(
		'key'		=> 'freelancer_id',
		'value'		=> $user_identity,
		'compare'	=> '=',
		'type'		=> 'NUMERIC'
	),
	array(
		'key'		=> '_task_status',
		'value'		=> 'completed',
		'compare'	=> '=',
	),
	array(
		'key'		=> 'payment_type',
		'value'		=> 'tasks',
		'compare'	=> '=',
	)
);
$workreap_order_completed  = workreap_get_post_count_by_meta('shop_order',array('wc-completed'),$meta_array);
$meta_array	= array(
	array(
		'key'		=> 'freelancer_id',
		'value'		=> $user_identity,
		'compare'	=> '=',
		'type'		=> 'NUMERIC'
	),
	array(
		'key'		=> '_task_status',
		'value'		=> 'hired',
		'compare'	=> '=',
	),
	array(
		'key'		=> 'payment_type',
		'value'		=> 'tasks',
		'compare'	=> '=',
	)
);
$workreap_order_hired    	= workreap_get_post_count_by_meta('shop_order',array('wc-completed'),$meta_array);
$workreap_order_completed	= !empty($workreap_order_completed) ? intval($workreap_order_completed) : 0;
$workreap_order_hired		= !empty($workreap_order_hired) ? intval($workreap_order_hired) : 0;
$workreap_order_hired		= !empty($workreap_order_hired) ? (($workreap_order_completed + $workreap_order_hired)/$workreap_order_hired)*100 : 100;
$app_task_base      		= workreap_application_access('task');
$wr_post_meta   			= get_post_meta( $linked_profile,'wr_post_meta',true );
$wr_post_meta   			= !empty($wr_post_meta) ? $wr_post_meta : array();
$tagline        			= !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
$address        			= apply_filters( 'workreap_user_address', $linked_profile );
if(empty($workreap_order_completed) ){
	$workreap_order_hired = 0;
}?>
<div class="wr-insightcontainerv2 wr-freelancer-insights wr-dashboard-left">
	<div class="row">
		<div class="col-lg-4">
			<aside class="wr-tabasidebar">
				<div class="wr-asideholder wr-freelancer-profile-two">
					<div class="wr-asidebox">
						<div id="wr-asideprostatusv2">
							<?php if( !empty($avatar) ){?>
								<a id="profile-avatar" href="javascript:void(0);">
									<figure>
										<img id="user_profile_avatar" src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
									</figure>
								</a>
							<?php } ?>
						</div>
						<div class="wr-icondetails">
							<?php if( !empty($user_name) ){?>
								<div class="wr-freelancer-details">
									<h4>
										<a href="<?php echo esc_url(get_the_permalink($linked_profile));?>"><?php echo esc_html($user_name);?></a>
										<?php do_action( 'workreap_verification_tag_html', $linked_profile ); ?>
									</h4>
								</div>
							<?php } ?>
							<?php if( !empty($tagline) ){?>
								<h5><?php echo esc_html($tagline);?></h5>
							<?php } ?>
							<ul class="wr-rateviews">
							<?php do_action('workreap_get_freelancer_rating_count', $linked_profile); ?>
								<?php do_action('workreap_get_freelancer_views', $linked_profile); ?>

							</ul>
							<?php do_action( 'workreap_freelancer_hourly_rate_html', $linked_profile );?>
							<?php if( !empty($address) ){?>
								<div class="wr-sidebarcontent">
									<div class="wr-sidebarinnertitle">
										<h6><?php esc_html_e('Location:','workreap');?></h6>
										<h5><?php echo esc_html($address);?></h5>
									</div>
								</div>
							<?php } ?>
							<?php do_action( 'workreap_texnomies_static_html', $linked_profile,'freelancer_type',esc_html__('Freelancer type','workreap') );?>
							<?php do_action( 'workreap_texnomies_static_html', $linked_profile,'languages',esc_html__('Languages','workreap') );?>
							<?php if(!empty($hide_languages ) && $hide_languages == 'no'){ do_action( 'workreap_texnomies_static_html', $linked_profile,'english_level',esc_html__('English level','workreap') );}?>
							<div class="wr-profilebtnarea">
								<a href="<?php echo get_the_permalink($linked_profile);?>" target="_blank" class="wr-btn" ><?php esc_html_e('Public profile preview','workreap');?></a>
							</div>
						</div>
					</div>
					<?php if( !empty($switch_user) ){?>
						<div class="wr-switchaccount">
							<div class="wr-accouttitle">
								<h5><?php esc_html_e('Login to employer account','workreap');?></h5>
								<h6><?php esc_html_e('Switching to Employer account will take you to your Employer account where you can hire freelancers','workreap');?></h6>
							</div>
							<div class="wr-btnarea">
								<a href="javascript:void(0);" class="wr-btn btn-orange wr_switch_user" data-id="<?php echo intval($user_identity);?>"><?php esc_html_e('Switch to employer account','workreap');?></a>
							</div>
						</div>
					<?php } ?>
				</div>
			</aside>
		</div>
		<?php if( !empty($app_task_base) ){?>
			<div class="col-lg-8">
				<?php workreap_get_template_part('dashboard/dashboard', 'ongoing-tasks');?>
			</div>
		<?php } else { ?>
			<div class="col-lg-8">
				<?php workreap_get_template_part('dashboard/post-project/freelancer/dashboard', 'freelancer-projects');?>
			</div>
		<?php } ?>
	</div>
</div>
