<?php
/**
 * Employer packages
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user,$workreap_settings;
$date_format	= get_option( 'date_format' );
$order_id 		= get_user_meta($current_user->ID, 'employer_package_order_id', true);
$order_id		= !empty($order_id) ? intval($order_id) : 0;
$enable_ai          = !empty($workreap_settings['enable_ai']) ? $workreap_settings['enable_ai'] : false;
$enable_ai_packages = !empty($workreap_settings['enable_ai_packages']) ? $workreap_settings['enable_ai_packages'] : false;
if(!empty($order_id) && class_exists('WooCommerce')){ ?>
	<div class="wr-package-plan">
		<?php 
			$order 			= wc_get_order($order_id);
			if(empty($order )){
				return;
			}

			$order_status	= $order->get_status();
			
			if ( !empty($order_status) && $order_status === 'completed' ) {
				$remaing_days			= 0;
				$current_time			= time();
				$package_id				= get_post_meta($order_id, 'package_id', true);
				$package_details  		= get_user_meta($current_user->ID, 'employer_package_details', true);
				$remaining_option       = get_user_meta($current_user->ID, 'remaining_employer_package_details',true );   
        		$remaining_option       = !empty($remaining_option) ? $remaining_option : array();
				$package_details		= !empty($package_details) ? $package_details : array();
				$task_count       		= workreap_get_user_projects($current_user->ID, array('publish'));
				$featured_task       	= workreap_get_user_projects($current_user->ID, array('publish'),true);
				$package_create_date	= !empty($package_details['package_create_date']) ? $package_details['package_create_date'] : 0;
				$package_expriy_date	= !empty($package_details['package_expriy_date']) ? $package_details['package_expriy_date'] : 0;
				$package_create_date	= !empty($package_create_date) ? strtotime($package_create_date) : 0;
				$package_expriy_date	= !empty($package_expriy_date) ? strtotime($package_expriy_date) : 0;

				$number_projects_allowed   		= !empty($package_details['number_projects_allowed']) ? intval($package_details['number_projects_allowed']) : 0;
                $featured_projects_allowed 		= !empty($package_details['featured_projects_allowed']) ? intval($package_details['featured_projects_allowed']) : 0;
                $featured_projects_duration    	= !empty($package_details['featured_projects_duration']) ? ($package_details['featured_projects_duration']) : 0; 
				
                $featured_projects_duration 	= !empty( $featured_projects_duration ) ? intval( $featured_projects_duration ) : 0;
                $featured_projects_allowed 		= !empty( $featured_projects_allowed ) ? intval( $featured_projects_allowed ) : 0;
                $number_projects_allowed 		= !empty( $number_projects_allowed ) ? intval( $number_projects_allowed ) : 0;  

				$workreap_args = array(
                    'post_type'         => 'product',
                    'post_status'       => 'publish',
                    'posts_per_page'    => -1,            
                    'author'            => $current_user->ID,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                        ),
                        array(
                            'taxonomy' => 'product_type',
                            'field'    => 'slug',
                            'terms'    => 'projects',
                        ),
                    ),
                );
        
                $featured_task                  = get_posts($workreap_args);
                $featured_task                  = !empty($featured_task) && is_array($featured_task) ? count($featured_task) : 0;
				$featured_task_left				= empty($featured_projects_allowed) || ( !empty($featured_projects_allowed) && $featured_projects_allowed < $featured_task)	? 0 : $featured_projects_allowed - $featured_task;
				
				$package_id				= !empty($package_id) ? intval($package_id) : 0;
				$product_instant		= !empty($package_id)	? get_post( $package_id ) : '';
				$product_title			= !empty($product_instant) ? sanitize_text_field($product_instant->post_title) : '';
				$pkg_content			= !empty($product_instant) ? sanitize_text_field($product_instant->post_content) : '';
				$image_html				= !empty($package_id) ? get_the_post_thumbnail( $package_id, 'thumbnail' ) : '';
				   
				if($package_expriy_date >= $current_time ){
					$remaing_days	= $package_expriy_date-$current_time;
					$remaing_days	= round((($remaing_days/24)/60)/60); 
				}
 				?>
				<div class="wr-package-heading">
					<?php if( !empty($image_html) ){?>
						<figure><?php echo do_shortcode( $image_html );?></figure>
					<?php }?>
					<div class="wr-package-tags">
						<?php if( !empty($remaing_days)){?>
							<span class="wr-onging"><?php esc_html_e('Ongoing','workreap');?></span>
						<?php } else { ?> 
							<span class="wr-onging wr-expire"><?php esc_html_e('Expired','workreap');?></span>
						<?php } ?>
						<?php if( !empty($product_title) ){?>
							<h4><?php echo esc_html($product_title);?></h4>
						<?php } ?>
					</div>
				</div>
				<?php if( !empty($pkg_content) ){?>
					<div class="wr-description">
						<p><?php echo esc_html($pkg_content);?></p>
					</div>
				<?php } ?>
				<ul class="wr-package-list"> 
					<?php if( !empty($package_create_date) ){?>
						<li>
							<h6><?php esc_html_e('Purchased on','workreap');?></h6>
							<span><?php echo date_i18n( $date_format, $package_create_date );?></span>
						</li>
					<?php } ?>
					<?php if( !empty($package_expriy_date) ){?>
						<li>
							<h6><?php esc_html_e('Expiry date','workreap');?></h6>
							<span><?php echo date_i18n( $date_format, $package_expriy_date );?></span>
						</li>
					<?php } ?>
					<?php if( isset($remaing_days) ){?>
						<li>
							<h6><?php esc_html_e('Package duration','workreap');?></h6>
							<span><?php echo wp_sprintf( esc_html__('%s days left', 'workreap'), $remaing_days );?></span>
						</li>
					<?php } ?>
					<?php if( isset($remaining_option['number_projects_allowed']) ){?>
						<li>
							<h6><?php esc_html_e('No. of projects to post','workreap');?></h6>
							<h6><?php echo wp_sprintf( esc_html__('%s task left', 'workreap'), $remaining_option['number_projects_allowed'] );?><em>/<?php echo wp_sprintf( esc_html__('%s allowed', 'workreap'), $number_projects_allowed );?></em></h6>
						</li>
					<?php } ?>
					<?php if( isset($featured_task_left) ){?>
						<li>
							<h6><?php esc_html_e('Featured projects','workreap');?></h6>
							<h6><?php echo wp_sprintf( esc_html__('%s left', 'workreap'), $featured_task_left );?><em>/<?php echo wp_sprintf( esc_html__('%s allowed', 'workreap'), $featured_projects_allowed );?></em></h6>
						</li>
					<?php } ?>
					<?php if(!empty($enable_ai) && !empty($enable_ai_packages) ){ ?>
						<li>
							<h6><?php esc_html_e('AI feature','workreap');?></h6>
							<span>
								<?php 
									if(!empty($package_details['allow_ai']) && $package_details['allow_ai'] == 'yes'){
										esc_html_e('Allowed','workreap');
									} else {
										esc_html_e('Not allowed','workreap');
									}
								?>
							</span>
						</li>
					<?php } ?>
					<?php if( !empty($featured_projects_duration) ){?>
						<li>
							<h6><?php esc_html_e('Featured project duration','workreap');?></h6>
							<h6><span><?php echo wp_sprintf( esc_html__('%s days', 'workreap'), $featured_projects_duration );?></span></h6>
						</li>
					<?php } ?>
				</ul>
			<?php 
			}
		?>		
	</div>
<?php }?>