<?php
/**
*  Project basic
*
* @package     Workreap
* @author      Amentotech <info@amentotech.com>
* @link        https://codecanyon.net/user/amentotech/portfolio
* @version     1.0
* @since       1.0
*/
global $workreap_settings,$current_user;
if ( !class_exists('WooCommerce') ) {
	return;
}
$post_id            = !empty($post_id) ? intval($post_id) : "";
$step_id            = !empty($step) ? intval($step) : "";
$pg_page            = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged           = get_query_var('paged') ? get_query_var('paged') : 1; 
$per_page           = !empty($per_page) ? $per_page : 10;
$options            = !empty($workreap_settings['project_recomended_freelancers']) ? $workreap_settings['project_recomended_freelancers'] : array();
$tax_query_args     = array();
if( !empty($options) ){
    foreach($options as $option ){
        $term_obj           = get_the_terms( $post_id, $option );
        $term_slug          = !empty($term_obj) ? wp_list_pluck($term_obj, 'slug') : array();
       
        $tax_query_args[]   = array(
            'taxonomy' => $option,
            'field'    => 'slug',
            'terms'    => $term_slug,
            'operator' => 'IN',
        );
    }
}

$query_args = array(
    'posts_per_page'        => $per_page,
    'paged'                 => $pg_paged,
    'post_type'             => 'freelancers',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1
);

if (!empty($tax_query_args)) {
    $query_relation           = array('relation' => 'OR',);
    $tax_query_args           = array_merge($query_relation, $tax_query_args);
    $query_args['tax_query']  = $tax_query_args;
}
$freelancer_data = new WP_Query(apply_filters('workreap_recomended_freelancer_filter', $query_args));
$total_posts = $freelancer_data->found_posts;
?>
<div class="row">
    <?php do_action( 'workreap_project_sidebar', $step_id,$post_id );?>
    <div class="col-xl-9 col-lg-8">
        <div class="wr-maintitle">
            <h4><?php esc_html_e('Recommended freelancers','workreap');?></h4>
        </div>
        <div class="wr-freelancers-list">
            <?php 
                if ($freelancer_data->have_posts()) {
                    while ($freelancer_data->have_posts()) {
                        $freelancer_data->the_post();
                        $freelancer_id        = get_the_ID();
                        $freelancer_name      = workreap_get_username($freelancer_id);
                        $wr_post_meta     = get_post_meta($freelancer_id, 'wr_post_meta', true);
                        $freelancer_tagline   = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
                        ?>
                        <div class="wr-bestservice">
                            <div class="wr-bestservice__content wr-bestservicedetail">
                                <div class="wr-bestservicedetail__user">
                                    <div class="wr-asideprostatus">
                                        <?php do_action('workreap_profile_image', $freelancer_id);?>
                                        <div class="wr-bestservicedetail__title">
                                            <?php if( !empty($freelancer_name) ){?>
                                                <h6><a href="<?php echo esc_url( get_permalink()); ?>"><?php echo esc_html($freelancer_name); ?></a></h6>
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
                                    <?php do_action('workreap_freelancer_invitation',$post_id,$freelancer_id); ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }
                } else {
                    do_action('workreap_empty_records_html', 'wr-empty-saved-items', esc_html__('No recommended freelancers found.', 'workreap'));
                }
                ?>
            <?php if($total_posts > $per_page){?>
                <?php workreap_paginate($freelancer_data); ?>
            <?php }?>
            <?php wp_reset_postdata();?>
        </div>
    </div>
</div>