<?php
/**
*  Project list
*
* @package     Workreap
* @author      Amentotech <info@amentotech.com>
* @link        https://codecanyon.net/user/amentotech/portfolio
* @version     1.0
* @since       1.0
*/
global $workreap_settings,$current_user;
$post_url       = !empty($post_url) ? esc_url($post_url) : "";
$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$search	        = !empty($_GET['search']) ? esc_html($_GET['search']) : '';
$workreap_args = array(
    'post_type'         => 'product',
    'post_status'       => 'any',
    'posts_per_page'    => get_option('posts_per_page'),
    'paged'             => $paged,
    'author'            => $current_user->ID,
    'orderby'           => 'date',
    'order'             => 'DESC',
    'tax_query'         => array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'projects',
        ),
    ),
);
if(!empty($search)){
	$workreap_args['s'] = esc_html($search);
}
$workreap_query = new WP_Query( apply_filters('workreap_project_listings_args', $workreap_args) );

?>
<div class="row justify-content-center">
    <div class="col-lg-6 text-center">
        <div class="wr-postproject-title">
            <h3><?php esc_html_e('Choose template from your posted projects','workreap');?></h3>
            <p><?php esc_html_e('Using previously posted project help you not to add all data again from scratch','workreap');?></p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="wr-template-serach">
            <a href="<?php echo esc_url($post_url);?>" class="wr-btnline"><i class=" wr-icon-chevron-left"></i><?php esc_html_e('Go back','workreap');?></a>
            <form method="get">
                <div class="wr-inputicon">
                    <input type="hidden" name="page_temp" value="projects">
                    <input type="text" name="search" class="form-control" value="<?php echo esc_attr($search);?>" placeholder="<?php esc_attr_e('Search project here', 'workreap');?>">
                    <i class="wr-icon-search"></i>
                </div>
            </form>
        </div>
        <?php if ( $workreap_query->have_posts() ) : ?>
        <ul class="wr-template-list">
        <?php
        while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
            global $post;
            $product = wc_get_product( $post->ID );
            ?>
            <li>
                <div class="wr-template-list_content">
                    <div class="wr-template-info">
                        <?php do_action( 'workreap_project_type_tag', $product->get_id() );?>
                        <h5><?php echo esc_html($product->get_name());?></h5>
                        <ul class="wr-template-view"> 
                            <?php do_action( 'workreap_posted_date_html', $product );?>
                            <?php do_action( 'workreap_location_html', $product );?>
                            <?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
                            <?php do_action( 'workreap_hiring_freelancer_html', $product );?>
                        </ul>
                    </div>
                    <span class="wr-btn-solid-lg-lefticon wr-duplicate-project" data-id="<?php echo intval($product->get_id());?>"><?php esc_html_e('Use this template','workreap');?></span>
                </div>
            </li>
            <?php
                endwhile;
                do_action('workreap_service_listing_after');
            ?>
        </ul>
        <?php
            workreap_paginate($workreap_query);
        else:
            $image_url = !empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png';
            ?>
            <div class="wr-submitreview wr-submitreviewv3">
                <figure>
                    <img src="<?php echo esc_url($image_url)?>" alt="<?php esc_attr_e('add project','workreap');?>">
                </figure>
                <h4><?php esc_html_e( 'No projects found', 'workreap'); ?></h4>
                <h6><a href="<?php echo esc_url($post_url);?>"> <?php esc_html_e('Add new project', 'workreap'); ?> </a></h6>
            </div>
            <?php
        endif;?>
    </div>
</div>