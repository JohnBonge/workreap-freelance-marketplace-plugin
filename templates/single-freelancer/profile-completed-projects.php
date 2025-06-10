<?php
/**
 * Completed projects
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user,$workreap_settings;

$show_posts		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$paged 			= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$ref		    = !empty($_GET['ref']) ? esc_html($_GET['ref']) : '';
$mode			= !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity	= !empty($args['user_id']) ? intval($args['user_id']) : 0;
$user_type		= apply_filters('workreap_get_user_type', $user_identity);
$linked_profile	= workreap_get_linked_profile_id($user_identity,'',$user_type);
$order_type     = !empty($_GET['order_type']) ? $_GET['order_type'] : 'any';
$find_project   = workreap_get_page_uri('project_search_page');
$workreap_args = array(
    'post_type'         => 'proposals',
    'post_status'       => array('completed'),
    'posts_per_page'    => $show_posts,
    'paged'             => $paged,
    'author'            => $user_identity,
    'orderby'           => 'meta_value_num',
    'meta_key'          => '_hired_status',
    'order'             => 'DESC'
);

$workreap_query  = new WP_Query( apply_filters('workreap_project_dashbaord_listings_args', $workreap_args) );
$create_project = workreap_get_page_uri('add_project_page');
$page_url       = Workreap_Profile_Menu::workreap_profile_menu_link($ref, $user_identity, true, $mode);
$menu_order     = workreap_list_proposal_status_filter();
$count_post     = $workreap_query->found_posts;
?>
<?php
if ( $workreap_query->have_posts() ) :
    ?>
    <?php
    while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
        global $post;
        $post_status        = get_post_status( $post );
        $project_id         = get_post_meta( $post->ID, 'project_id',true );
        $project_id         = !empty($project_id) ? intval($project_id) : 0;
        $product            = wc_get_product( $project_id );
        $project_price      = workreap_get_project_price($project_id);
        $project_meta       = get_post_meta( $project_id, 'wr_project_meta',true );
        $project_meta       = !empty($project_meta) ? $project_meta : array();
        $project_type       = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
        $freelancer_id          = get_post_field( 'post_author', $post );
        $freelancer_profile_id  = workreap_get_linked_profile_id($freelancer_id, '','freelancers');
        $freelancer_name        = workreap_get_username($freelancer_profile_id);

        $employer_id           = get_post_field( 'post_author', $project_id );
        $linked_profile_id  = workreap_get_linked_profile_id($employer_id, '','employers');
        $user_name          = workreap_get_username($linked_profile_id);
        $is_verified    	= !empty($linked_profile_id) ? get_post_meta( $linked_profile_id, '_is_verified',true) : '';
        $employer_avatar       = apply_filters( 'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $linked_profile_id), array('width' => 50, 'height' => 50));


        if( !empty($product) ){ ?>
            <div class="wr-project-wrapper-two">
                <div class="wr-project-box">
                    <div class="wr-employerproject">
                        <div class="wr-employerproject-title">
                            <?php do_action( 'workreap_freelancer_proposal_status_tag', $post->ID );?>
                            <?php do_action('workreap_service_featured_item_theme', $product);?>
                            <div class="wr-verified-info">
                                <?php if( !empty($user_name) ){?>
                                    <strong>
                                        <?php echo esc_html($user_name);?>
                                        <?php do_action( 'workreap_verification_tag_html', $linked_profile_id ); ?>
                                    </strong>
                                <?php } ?>
                                <?php if( !empty($product->get_name()) ){?>
                                    <h5><a href="<?php echo esc_url(get_the_permalink( $project_id ));?>"><?php echo esc_html($product->get_name());?></a></h5>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if( isset($project_price) ){?>
                            <div class="wr-price">
                                <?php if( !empty($project_type['title']) ){?>
                                    <?php do_action( 'workreap_project_type_text', $project_type['title'] );?>
                                <?php } ?>
                                <h4><?php echo do_shortcode($project_price);?></h4>
                            </div>
                        <?php } ?>
                    </div>
                    <ul class="wr-template-view">
                        <?php do_action( 'workreap_posted_date_html', $product );?>
                        <?php do_action( 'workreap_location_html', $product );?>
                        <?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
                        <?php do_action( 'workreap_hiring_freelancer_html', $product );?>
                    </ul>
					<?php if( !empty($post_status) && $post_status === 'completed' ){ ?>
						<div class="wr-freelancer-holder">
							<div class="wr-tagtittle">
								<span><?php esc_html_e('Rating form employer','workreap');?></span>
							</div>
							<ul class="wr-hire-freelancer wr-hire-freelancer-two">
								<li>
									<div class="wr-hire-freelancer_content wr-completed-proposal">
										<?php if( !empty($employer_avatar) ){?>
											<img src="<?php echo esc_url($employer_avatar);?>" alt="<?php echo esc_attr($user_name);?>">
										<?php } ?>
										<div class="wr-hire-freelancer-info">
											<h6>
												<?php echo esc_html($user_name);
												$rating_id      = get_post_meta( $post->ID, '_rating_id', true );
												$rating_feature = !empty($rating_id) ? '' : 'wr-featureRating-nostar';
												$rating         = !empty($rating_id) ? get_comment_meta($rating_id, 'rating', true) : 0;
												$rating_avg     = !empty($rating) ? ($rating/5)*100 : 0;
												$rating_avg     = !empty($rating_avg) ? 'style="width:'.$rating_avg.'%;"' : '';

												$rating_class   = !empty($rating_id) ? 'wr_view_rating' : 'wr_add_project_rating';
												$rating_feature = !empty($rating_id) ? '' : 'wr-featureRating-nostar';
												$rating_title   = !empty($rating_id) ? esc_html__('Read review','workreap') : esc_html__('Add review','workreap');
												?>
												<?php if( !empty($rating_avg) ){?>
													<span class="wr-blogviewdates <?php echo esc_attr($rating_feature);?>">
														<i class="fas fa-star wr-yellow" <?php echo do_shortcode( $rating_avg );?>></i>
														<em> <?php echo number_format((float)$rating, 1, '.', '');?> </em>
													</span>
												<?php } ?>
											</h6>
											<a href="javascript:;" data-title="<?php echo esc_attr($project_title);?>" data-rating_id="<?php echo esc_attr($rating_id);?>" class="<?php echo esc_attr($rating_class);?>" ><?php echo esc_html($rating_title);?></a>
										</div>
									</div>
								</li>
							</ul>
						</div>
					<?php } ?>
                </div>
            </div>
        <?php }
    endwhile;
	?>
	<div class="modal fade wr-excfreelancerpopup" id="wr_excfreelancerpopup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog wr-modaldialog" role="document">
            <div class="modal-content" id="wr_wr_viewrating">
            </div>
        </div>
    </div>
	<?php
    if( !empty($count_post) && $count_post > $show_posts ):?>
        <?php workreap_paginate($workreap_query); ?>
    <?php endif;
else:
    $image_url = !empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png';
    ?>
    <div class="wr-submitreview wr-submitreviewv3">
        <figure>
            <img src="<?php echo esc_url($image_url)?>" alt="<?php esc_attr_e('Explore projects','workreap');?>">
        </figure>
        <h6><a href="<?php echo esc_url($find_project);?>"> <?php esc_html_e('Explore projects', 'workreap'); ?> </a></h6>
    </div>
<?php
endif;
wp_reset_postdata();
$script = "
jQuery(document).on('ready', function(){
    jQuery(document).on('change', '#wr_order_type', function (e) {
        let _this       = jQuery(this);
        let page_url = _this.find(':selected').data('url');
		window.location.replace(page_url);
    });    
});
";
wp_add_inline_script( 'workreap', $script, 'after' );
