<?php
/**
 *
 * The template used for displaying freelancer portfolio
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global  $post,$current_user,$workreap_settings;
$post_id        = !empty($args['post_id']) ? intval($args['post_id']) : $post->ID;
$post_author    = get_post_field( 'post_author', $post_id );
$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
// $per_page   = get_option('posts_per_page');
$per_page   = 4;
$workreap_args   = array(
	'post_type'         => 'portfolios',
	'post_status'       => 'publish',
	'posts_per_page'    => $per_page,
	'paged'             => $paged,
	'orderby'           => 'date',
	'order'             => 'DESC',
	'author'			=> $post_author

  );
$workreap_query  = new WP_Query( apply_filters('workreap_portfolio_listings_args', $workreap_args) );
$selected_type			= array();
if(function_exists('get_field_object')){
	$field = get_field_object('field_668e242339c78');
	$selected_type	= !empty($field['choices']) ? $field['choices'] : array();
}
$show_slider    = false;
$show_venobox    = false;
?>

<?php if ( $workreap_query->have_posts() ) :?>
    <div class="wr-asidebox wr-freelancerinfo">
        <div class="wr-freesingletitle">
            <h4><?php esc_html_e('Portfolio','workreap');?></h4>
        </div>
       <div class="col-lg-12 col-xxl-12 wr-portfolio-wrapper">
        <?php
            while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                $post_id		= get_the_ID();
                $title          = get_the_title($post_id);
                $type	        = get_post_meta($post_id, 'type',true);
                $type           = !empty($type) ? $type : '';
                $link       = "";
                $img_id		= get_post_thumbnail_id( $post_id );
                $image_url	= wp_get_attachment_image_src( $img_id, 'medium' );
                $image_url	= !empty($image_url[0]) ? $image_url[0] : '';
                $link_attribute= "";
                if(!empty($type) && $type === 'link'){
                    $link_attribute = "target='_blank'";
                    $link			= get_post_meta($post_id, 'url',true);
                } else if(!empty($type) && $type === 'video'){
                    $show_venobox       = true;
                    $video_url			= get_post_meta($post_id, 'video_url',true);
                    $link               = $video_url;
                    $link_attribute     = "class='venobox-video wr-portfolio-action-icon wr-portfolio-icon-video' data-autoplay='true' data-vbtype='video'";
                } else if(!empty($type) && $type === 'gallery'){
	                $preview_url    = wp_get_attachment_url( $img_id );
	                $link_attribute     = "class='wr-portfolio-action-icon wr-portfolio-icon-gallery'";
                }else if(!empty($type) && $type === 'document'){
                    $link_attribute     = "target='_blank' download";
                    $document			= get_post_meta($post_id, 'document',true);
                    if(!empty($document) ){
                        $link	= wp_get_attachment_url($document);
                    }
                    if(empty($image_url) ){
                        $image_url = esc_url(  WORKREAP_DIRECTORY_URI . 'public/images/attachment.png');
                    }
                }
            ?>
            <div class="wr-portfolio-item">
                <div class="wr-portfolio-img">
                    <?php if( !empty($image_url) && (!empty($type) && $type != 'gallery')){ ?>
                        <figure>
                            <img src="<?php echo esc_url($image_url);?>" alt="<?php echo esc_attr($title);?>" />
                        </figure>
                    <?php } elseif(!empty($type) && $type === 'gallery'){
                        $show_venobox       = true;
                        $show_slider    = true;
                        $product_gallery			= get_post_meta($post_id, '_portfolio_gallery',true);
                        $product_gallery			= !empty($product_gallery) ? explode(',',$product_gallery) : array();
                        $product_gallery            = !empty($img_id) ? array_merge(array($img_id),$product_gallery) : $product_gallery;
                        if(!empty($product_gallery) ){ ?>
                        <div class="wr-gallery_tasks-slider owl-carousel wr-portfolio-slider wr-cards_wrapper">
                        <?php
                            foreach( $product_gallery as $attachment_id ){
                                if(!empty($attachment_id)){
                                    $url    = wp_get_attachment_url( $attachment_id );
                                    $name   = get_the_title($attachment_id);
                                    $image_url	= wp_get_attachment_image_src( $attachment_id, 'medium' );
                                    $image_url  = !empty($image_url[0]) ? $image_url[0] : '';
                                    ?>
                                    <div class="item">
                                        <a class="wr-cards__img venobox-gallery" data-gall="gallery-<?php echo esc_attr($post_id); ?>" href="<?php echo esc_url($url); ?>">
                                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name) ?>" />
                                        </a>
                                    </div>
                                <?php }
                            } ?>
                            </div>
                        <?php
                        } ?>
                    <?php } ?>
                    <a href="<?php echo esc_url($link);?>" <?php echo do_shortcode($link_attribute);?> class="wr-portfolio-action-icon wr-portfolio-icon-<?php echo esc_attr($type); ?>"></a>
                </div>
                <div class="wr-portfolio-title">
                    <h5><?php echo esc_html($title);?></h5>
                </div>
            </div>
        <?php  endwhile;?>
        </div>
        <div class="col-sm-12"><?php workreap_paginate($workreap_query,'wr-service-pagination');?></div>
    </div>
<?php endif;
wp_reset_postdata();

if( !empty($show_venobox) ){
    $script_video = '
    jQuery(document).ready(function () {
    
        jQuery(".wr-portfolio-icon-gallery").on("click",function(e){
            e.preventDefault();
            var _this = jQuery(this).parents(".wr-portfolio-item");
            _this.find(".wr-cards__img").eq(0).click();
            console.log(_this.find(".wr-cards__img").eq(0));
        });
    
        let venobox = document.querySelector(".venobox-gallery");
        if (venobox !== null) {
            jQuery(".venobox-gallery").venobox({
                spinner : "cube-grid",
            });
        }
        let venoboxVideo = document.querySelector(".venobox-video");
        if (venoboxVideo !== null) {
            jQuery(".venobox-video").venobox({
                spinner : "cube-grid",
            });
        }
    })
    ';
    wp_add_inline_script('venobox', $script_video, 'after');
}

if(!empty($show_slider) ){
    $is_rtl = 'false';
    if( is_rtl() ){
        $is_rtl = 'true';
    }
    $script	= "
    var owl_task	= jQuery('.wr-portfolio-slider').owlCarousel({
        rtl:".esc_js($is_rtl).",
        items: 1,
        loop:false,
        nav:true,
        margin: 0,
        autoplay:false,
        lazyLoad:false,
        navClass: ['wr-prev', 'wr-next'],
        navContainerClass: 'wr-search-slider-nav',
        navText: ['<i class=\"wr-icon-chevron-left\"></i>', '<i class=\"wr-icon-chevron-right\"></i>'],
    });

    setTimeout(function(){owl_task.trigger('refresh.owl.carousel');}, 3000);
    jQuery(window).load(function() {
        owl_task.trigger('refresh.owl.carousel');
        setTimeout(function(){owl_task.trigger('refresh.owl.carousel');}, 2000);
    });";
    wp_enqueue_style('owl.carousel');
    wp_enqueue_script('owl.carousel');
    wp_add_inline_script( 'owl.carousel', $script, 'after' );
}
