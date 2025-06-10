<?php
/**
 * Single task author details
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
global $post,$current_user;
$workreap_args               = array();
$post_id          = !empty($args['post_id']) ?  $args['post_id']: '';
$freelancer_name      = workreap_get_username($post_id);
$wr_post_meta     = get_post_meta($post_id, 'wr_post_meta', true);
$freelancer_tagline   = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
?>
<div class="wr-asideholder">
    <div class="wr-aboutfreelancer">
        <div class="wr-freelancer_detail">
            <div class="wr-topservicetask__content">
                <div class="wr-freelanlist">
                    <div class="wr-topservicetask__content">
                        <div class="wr-freeprostatus">
				            <?php do_action('workreap_profile_image', $post_id,'',array('width' => 600, 'height' => 600));?>
                        </div>
                        <div class="wr-title-wrapper">
                            <div class="wr-author-info">
					            <?php if( !empty($freelancer_name) ){?>
                                <div class="wr-author-name">
                                    <a href="<?php echo esc_url( get_permalink($post_id)); ?>"><?php echo esc_html($freelancer_name); ?></a>
						            <?php do_action( 'workreap_verification_tag_html', $post_id ); ?>
                                </div>
					            <?php } ?>
                                <?php if(!empty($freelancer_tagline)){ ?>
                                    <h5 class="wr-tag-line"><?php echo esc_html($freelancer_tagline); ?></h5>
                                <?php } ?>
                                <ul class="wr-blogviewdates wr-blogviewdatesmd">
						            <?php do_action('workreap_get_freelancer_rating_count', $post_id); ?>
                                </ul>
                            </div>
                            <ul class="wr-view-wrapper">
				                <?php do_action('workreap_get_freelancer_views', $post_id); ?>
                            </ul>
                        </div>
			            <?php $wr_hourly_rate = get_post_meta($post_id, 'wr_hourly_rate', true);
			            if (!empty($wr_hourly_rate) || !empty($display_button)) { ?>
                            <div class="wr-startingprice">
                                            <span class="wr-startingprice-title">
                                                <i class="wr-icon-credit-card" aria-hidden="true"></i>
                                                <?php echo esc_html__('Hourly Rate','workreap') ?>
                                            </span>
                                <span><?php echo sprintf(esc_html__('%s /hr', 'workreap'), workreap_price_format($wr_hourly_rate, 'return')); ?></span>
                            </div>
			            <?php }
			            $address  = apply_filters( 'workreap_user_address', $post_id );
			            if( !empty($address) ){ ?>
                            <div class="wr-address-view">
                                            <span class="wr-address-title">
                                                <i class="wr-icon-map-pin" aria-hidden="true"></i>
                                                <?php echo esc_html__('Location','workreap') ?>
                                            </span>
                                <span class="wr-address"><?php echo esc_html($address); ?></span>
                            </div>
			            <?php }
			            do_action( 'workreap_term_tags', $post_id, 'skills', '', 6, 'freelancer' );
			            ?>
                        <div class="wr-btnviewpro">
                            <a href="<?php echo esc_url( get_permalink($post_id)); ?>" class="wr-btn-solid-lg"><?php esc_html_e('View profile','workreap');?></a>
				            <?php do_action('workreap_save_freelancer_html', $current_user->ID, $post_id, '_saved_freelancers', 'v2', 'freelancers'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>