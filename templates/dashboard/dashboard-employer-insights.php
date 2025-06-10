<?php
/**
 *  Employer ongoing tasks
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/


global $current_user, $workreap_settings;

$reference 		  	= !empty($_GET['ref'] ) ? $_GET['ref'] : '';
$mode 			    = !empty($_GET['mode']) ? $_GET['mode'] : '';
$user_identity 		= intval($current_user->ID);
$user_type		  	= apply_filters('workreap_get_user_type', $user_identity );
$linked_profile 	= workreap_get_linked_profile_id($user_identity,'',$user_type);
$user_name		  	= workreap_get_username($linked_profile);
$profile_link	  	= get_the_permalink( $linked_profile );
$app_task_base      = workreap_application_access('task');
$switch_user    	= !empty($workreap_settings['switch_user']) ? $workreap_settings['switch_user'] : false;
$wr_post_meta   	= get_post_meta( $linked_profile,'wr_post_meta',true );
$wr_post_meta   	= !empty($wr_post_meta) ? $wr_post_meta : array();
$tagline        	= !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
$address        	= apply_filters( 'workreap_user_address', $linked_profile );
$width			= 315;
$height			= 300;
$avatar	= apply_filters(
	'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => $width, 'height' => $height), $linked_profile), array('width' => $width, 'height' => $height)
);

?>
<div class="wr-insightcontainerv2 wr-employer-insights">
	<div class="row">
		<div class="col-lg-4">
			<aside class="wr-tabasidebar">
				<div class="wr-asideholder wr-freelancer-profile-two">
					<div class="wr-asidebox">
						<div id="wr-asideprostatusv2">
							<?php if( !empty($avatar) ){?>
								<a href="javascript:void(0);" id="profile-avatar">
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
										<a><?php echo esc_html($user_name);?></a>
										<?php do_action( 'workreap_verification_tag_html', $linked_profile ); ?>
									</h4>
								</div>
							<?php } ?>
							<?php if( !empty($tagline) ){?>
								<h5><?php echo esc_html($tagline);?></h5>
							<?php } ?>
							<?php if( !empty($address) ){?>
								<div class="wr-sidebarcontent">
									<div class="wr-sidebarinnertitle">
										<h6><?php esc_html_e('Location:','workreap');?></h6>
										<h5><?php echo esc_html($address);?></h5>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<?php if( !empty($switch_user) ){?>
						<div class="wr-switchaccount wr-employeraccount">
							<div class="wr-accouttitle">
								<h5><?php esc_html_e('Switch account','workreap');?></h5>
								<h6><?php esc_html_e('Switching to freelancer account will take you to your freelancer account','workreap');?></h6>
							</div>
							<div class="wr-btnarea">
								<a href="javascript:void(0);" class="wr-btn btn-purple wr_switch_user" data-id="<?php echo intval($user_identity);?>"><?php esc_html_e('Switch to freelancer account','workreap');?></a>
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
		<?php } else {?>
			<div class="col-lg-8">
				<?php workreap_get_template_part('dashboard/post-project/employer/dashboard', 'employer-projects');?>
			</div>
		<?php } ?>
	</div>
</div>
