<?php

/**
 * Dashboard saved items
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user, $wp_roles, $userdata, $post;

$reference			= !empty($_GET['ref']) ? esc_html($_GET['ref']) : '';
$mode 			    = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$type 			    = !empty($_GET['type']) ? esc_html($_GET['type']) : 'freelancers';
$user_identity		= intval($current_user->ID);
$id					= !empty($args['id']) ? intval($args['id']) : '';
$user_type		  	= apply_filters('workreap_get_user_type', $current_user->ID);
$linked_profile 	= workreap_get_linked_profile_id($user_identity, '', $user_type);
$user_type		  	= apply_filters('workreap_get_user_type', $user_identity);
$paged 			    = (get_query_var('paged')) ? get_query_var('paged') : 1;
$show_posts     	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
if( !empty($type) && $type === 'product' ){
	$saved_tasks		= get_post_meta($linked_profile, '_saved_tasks', true);
	$saved_items		= !empty($saved_tasks) ? $saved_tasks : array();
} elseif( !empty($type) && $type === 'freelancers' ){
	$saved_freelancers		= get_post_meta($linked_profile, '_saved_freelancers', true);
	$saved_items		= !empty($saved_freelancers) ? $saved_freelancers : array();
}elseif( !empty($type) && $type === 'projects' ){
	$saved_freelancers		= get_post_meta($linked_profile, '_saved_projects', true);
	$saved_items		= !empty($saved_freelancers) ? $saved_freelancers : array();
}

$app_task_base      = workreap_application_access('task');
$app_project_base   = workreap_application_access('project');
$page_url 			= Workreap_Profile_Menu::workreap_profile_menu_link($reference, $user_identity, true, $mode);
?>
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="wr-dhb-mainheading">
				<h2><?php esc_html_e('Saved items', 'workreap'); ?></h2>
				<div class="wr-sortby">
					<div class="wr-actionselect wr-actionselect2">
						<span><?php esc_html_e('Show only:','workreap');?></span>
						<div class="wr-select">
							<select id="wr_order_type" name="type" class="form-control wr-selectv">
								<option value="freelancers" data-url="<?php echo esc_url($page_url);?>&type=freelancers" <?php if( !empty($type) && $type === 'freelancers'){ echo do_shortcode('selected');}?>><?php esc_html_e('Freelancers','workreap');?></option>
								<?php if( !empty($app_task_base) ){?>
									<option value="product" data-url="<?php echo esc_url($page_url);?>&type=product" <?php if( !empty($type) && $type === 'product'){ echo do_shortcode('selected');}?>><?php esc_html_e('Task','workreap');?></option>
								<?php } ?>
								<?php if( !empty($app_project_base) ){?>
									<option value="product" data-url="<?php echo esc_url($page_url);?>&type=projects" <?php if( !empty($type) && $type === 'projects'){ echo do_shortcode('selected');}?>><?php esc_html_e('Projects','workreap');?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<?php if (!empty($saved_items)) {
				$paged 			= (get_query_var('paged')) ? get_query_var('paged') : 1;
				$workreap_args 	= array(
					'post_type'         => array($type),
					'post_status'       => 'any',
					'posts_per_page'    => $show_posts,
					'paged'             => $paged,
					'orderby'           => 'date',
					'order'             => 'DESC',
					'post__in' 			=> $saved_items,
				);

				if( !empty($type) && $type === 'projects' ){
					$workreap_args['post_type']	= array('product');
				}
				$workreap_query = new WP_Query(apply_filters('workreap_service_listings_args', $workreap_args));
				
				if ($workreap_query->have_posts()) { ?>
					<?php if( !empty($type) && $type === 'product' ){?>
						<ul class="wr-savelisting">
							<?php do_action('workreap_service_listing_before'); ?>
							<?php
							while ($workreap_query->have_posts()) {
								$workreap_query->the_post();
								$product = wc_get_product($post->ID);
								?>
								<li id="post-<?php the_ID(); ?>" <?php post_class('wr-tabbitem'); ?>>
									<?php if (!empty($product)) {
										do_action('workreap_service_item_before', $product);
									}
									?>
									<div class="wr-tabbitem__list wr-tabbitem__listtwo">
										<div class="wr-deatlswithimg">
											<figure>
												
												<?php if (!empty($product)) {
													echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
													do_action('workreap_service_featured_item', $product);
												} else {
													$avatar = apply_filters(
														'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $post->ID), array('width' => 100, 'height' => 100)
													);?>
													<img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_attr_e('User profile', 'workreap'); ?>">
													<?php
												}
												?>
											</figure>
											<div class="wr-icondetails">
												<?php echo do_action('workreap_task_categories', $post->ID, 'product_cat');?>
												<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
												<ul class="wr-rateviews wr-rateviews2">
													<?php if (!empty($product)) {
														do_action('workreap_service_rating_count', $product);
														do_action('workreap_service_item_views', $product);
														do_action('workreap_service_item_reviews', $product);
													} else {
														do_action('workreap_get_freelancer_rating_count', $post->ID);
														do_action('workreap_get_freelancer_views', $post->ID);
													}
													?>
												</ul>
											</div>
										</div>
										<div class="wr-itemlinks">
											<?php
												if (!empty($product)) {
													do_action('workreap_service_item_starting_price', $product);
												}
												?>
											<ul class="wr-tabicon">
												<li><a href="<?php echo get_the_permalink($post->ID); ?>"><span class="wr-icon-external-link bg-gray"></span></a></li>
												<?php if (!empty($product)) {?>
														<li><?php do_action('workreap_saved_item', $post->ID, $linked_profile, '_saved_tasks','list');?></li>
													<?php } else {
														do_action('workreap_save_freelancer_html', $current_user->ID, $post->ID, '_saved_freelancers', '', 'freelancers');
													}
												?>
											</ul>
										</div>
									</div>
									<?php
										if (!empty($product)) {
											do_action('workreap_service_item_after', $product);
										}
									?>
								</li>
							<?php
							}

							do_action('workreap_service_listing_after');
							?>
						</ul>
					<?php } else if( !empty($type) && $type === 'projects' ){?>
						<ul class="wr-saved-item wr_saveprojectlisting">
							<?php do_action('workreap_project_listing_before'); ?>
							<?php
							while ($workreap_query->have_posts()) {
								$workreap_query->the_post();
								global $post;
								$product            = wc_get_product();
                                $product_author_id  = get_post_field ('post_author', $product->get_id());
                                $linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','employers');
                                $user_name          = workreap_get_username($linked_profile_id);
                                $is_verified    	= !empty($linked_profile_id) ? get_post_meta( $linked_profile_id, '_is_verified',true) : '';
                                $project_price      = workreap_get_project_price($product->get_id());
                                $project_meta       = get_post_meta( $product->get_id(), 'wr_project_meta',true );
                                $project_meta       = !empty($project_meta) ? $project_meta : array();
                                $project_type       = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
                                $post_status		= get_post_status( $product->get_id() );
								?>
								<li id="post-<?php the_ID(); ?>" <?php post_class('wr-tabbitem'); ?>>
									<?php if (!empty($product)) {
										do_action('workreap_sproject_item_before', $product);
									}
									?>
									<div class="wr-project-wrapper">
                                    	<?php do_action( 'workreap_featured_item', $product,'featured_project' );?>
										<div class="wr-project-box">
											<div class="wr-price-holder">
												<div class="wr-project_head">
													<div class="wr-verified-info">
														<strong>
															<?php echo esc_html($user_name);?>
															<?php do_action( 'workreap_verification_tag_html', $linked_profile_id ); ?>
														</strong>
														<?php if( !empty($product->get_name()) ){?>
															<h5><a href="<?php echo esc_url(get_the_permalink( $product->get_id() ));?>"><?php echo esc_html($product->get_name());?></a></h5>
														<?php } ?>
													</div>
													<ul class="wr-template-view">
														<?php do_action( 'workreap_posted_date_html', $product );?>
														<?php do_action( 'workreap_location_html', $product );?>
														<?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
														<?php do_action( 'workreap_hiring_freelancer_html', $product );?>
														<li><div class="wr-likev2"><?php do_action( 'workreap_project_saved_item', $product->get_id(), '','_saved_projects', 'list' );?></div></li>
													</ul>
												</div>
												<?php if( isset($project_price) ){?>
													<div class="wr-price">
														<?php if( !empty($project_type) ){?>
															<?php do_action( 'workreap_project_type_text', $project_type );?>
														<?php } ?>
														<h4><?php echo do_shortcode($project_price);?></h4>
														<div class="wr-project-option">
															<span class="wr-btn-solid-lg-lefticon"><a href="<?php echo get_the_permalink($product->get_id());?>"><?php esc_html_e('View detail','workreap');?></a></span>
														</div>
													</div>
												<?php } ?>
											</div>
											<?php do_action( 'workreap_term_tags', $product->get_id(),'skills','',7,'project' );?>
										</div>
									</div>
									<?php
										if (!empty($product)) {
											do_action('workreap_project_item_after', $product);
										}
									?>
								</li>
							<?php
							}

							do_action('workreap_project_listing_after');
							?>
						</ul>
					<?php }   else if( !empty($type) && $type === 'freelancers' ){?>
						<div class="wr-freelancersearch">
							<?php while ($workreap_query->have_posts()) {
								$workreap_query->the_post();
								$freelancer_id        = get_the_ID();
								$freelancer_name      = workreap_get_username($freelancer_id);
								$wr_post_meta     = get_post_meta($freelancer_id, 'wr_post_meta', true);
								$freelancer_tagline   = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
								$app_task_base      		    = workreap_application_access('task');
								$skills_base                    = 'project';
								if( !empty($app_task_base) ){
									$skills_base    = 'service';
								}
								?>
								<div class="wr-bestservice">
									<div class="wr-bestservice__content wr-bestservicedetail">
										<div class="wr-bestservicedetail__user">
											<div class="wr-price-holder">
												<div class="wr-asideprostatus">
													<?php do_action('workreap_profile_image', $freelancer_id,'',array('width' => 600, 'height' => 600));?>
													<div class="wr-bestservicedetail__title">
														<?php if( !empty($freelancer_name) ){?>
															<h6>
																<a href="<?php echo esc_url( get_permalink()); ?>"><?php echo esc_html($freelancer_name); ?></a>
																<?php do_action( 'workreap_verification_tag_html', $freelancer_id ); ?>
															</h6>
														<?php } ?>
														<?php if( !empty($freelancer_tagline) ){?>
															<h5><?php echo esc_html($freelancer_tagline); ?></h5>
														<?php } ?>
														<ul class="wr-rateviews">
															<?php do_action('workreap_get_freelancer_rating_count', $freelancer_id); ?>
															<?php do_action('workreap_get_freelancer_views', $freelancer_id); ?>
															<?php do_action('workreap_save_freelancer_html', $current_user->ID, $freelancer_id, '_saved_freelancers', '', 'freelancers'); ?>
														</ul>
													</div>
												</div>
												<?php do_action('workreap_user_hourly_starting_rate', $freelancer_id,'',true); ?>
											</div>
											<div class="wr-tags-holder">
												<?php the_excerpt(); ?>
												<?php do_action( 'workreap_term_tags', $workreap_query->ID,'skills','',7,'freelancer' );?>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					<?php
					workreap_paginate($workreap_query);
				} else {
					do_action('workreap_empty_records_html', 'wr-empty-saved-items', esc_html__('No saved item found.', 'workreap'));
				}
			} else {
				do_action('workreap_empty_records_html', 'wr-empty-saved-items', esc_html__('No saved item found.', 'workreap'));
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</div>
<?php
$script = "
jQuery(document).on('ready', function(){
    jQuery(document).on('change', '#wr_order_type', function (e) {
        let _this 	= $(this);
		let page_url = _this.find(':selected').data('url');
		window.location.replace(page_url);
    });
});
";
wp_add_inline_script( 'workreap', $script, 'after' );