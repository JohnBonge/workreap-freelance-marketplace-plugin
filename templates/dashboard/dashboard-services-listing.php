<?php
/**
 * Freelancer task listings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $post, $current_user,$workreap_settings;
$ref                = !empty($_GET['ref']) ? esc_html($_GET['ref']) : '';
$mode               = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity      = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;

$user_type         = apply_filters('workreap_get_user_type', $current_user->ID );
$task_allowed      = workreap_task_create_allowed($current_user->ID);
$package_detail    = workreap_get_package($current_user->ID);

$order_type     = !empty($_GET['order_type']) ? $_GET['order_type'] : 'any';
$menu_order     = workreap_list_tasks_status_filter();
$page_url       = Workreap_Profile_Menu::workreap_profile_menu_link($ref, $user_identity, true, $mode);

$package_option               = !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'],array('paid','employer_free')) ? true : false;
$workreap_add_service_page_url = '';
$workreap_add_service_page_url = !empty($workreap_settings['tpl_add_service_page']) ? get_permalink($workreap_settings['tpl_add_service_page']) : '';
?>
<div class="wr-dhb-mainheading">
    <div class="wr-dhb-mainheading__rightarea">
        <em><?php esc_html_e('Add task for each service you offer to increase chances of getting hired', 'workreap');?></em>
        <a href="<?php echo esc_url($workreap_add_service_page_url);?>" class="wr-btn">
            <?php esc_html_e('Add new', 'workreap');?>
            <span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span>
        </a>
    </div>
</div>
<div class="wr-dhb-mainheading">
    <h2><?php esc_html_e('Manage task', 'workreap');?></h2>
    <?php do_action('workreap_service_listing_notice');?>
    <div class="wr-sortby">
        <div class="wr-actionselect wr-actionselect2">
            <span><?php esc_html_e('Filter by:','workreap');?></span>
            <div class="wr-select">
                <select id="wr_order_type" name="order_type" class="form-control wr-selectv">
                    <?php foreach($menu_order as $key => $val ){
                        $selected   = '';

                        if( !empty($order_type) && $order_type == $key ){
                            $selected   = 'selected';
                        }
                        ?>
                        <option data-url="<?php echo esc_url($page_url);?>&order_type=<?php echo esc_attr($key);?>" value="<?php echo esc_attr($key);?>" <?php echo esc_attr($selected);?>>
                            <?php echo esc_html($val);?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
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
            'terms'    => 'tasks',
        ),
    ),
);
if(!empty($order_type) && $order_type!= 'any' ){

    $workreap_args['post_status'] = $order_type;
}

$workreap_query = new WP_Query( apply_filters('workreap_service_listings_args', $workreap_args) );

if ( $workreap_query->have_posts() ) :
    ?>
    <ul class="wr-savelisting">
        <?php do_action('workreap_service_listing_before');?>
        <?php
        while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
            $product = wc_get_product( $post->ID );
            $workreap_add_service_page_edit_url = 'javascript:void(0);';
            
            if($workreap_add_service_page_url){
                $workreap_add_service_page_edit_url = add_query_arg( array(
                    'post'    => $post->ID,
                    'step'    => 1,
                ), $workreap_add_service_page_url );
            }

            $workreap_featured   = $product->get_featured();
            $task_order_url     = get_the_permalink($post->ID);
            ?>
            <li id="post-<?php the_ID(); ?>" <?php post_class('wr-tabbitem'); ?>>
                <?php do_action('workreap_service_item_before', $product);?>
                <div class="wr-tabbitem__list wr-tabbitem__listtwo">
                    <div class="wr-deatlswithimg">
                        <figure>
                            <?php
                                echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                                do_action('workreap_service_featured_item', $product);
                            ?>
                        </figure>
                        <div class="wr-icondetails">
                            <?php echo do_action('workreap_task_categories', $post->ID, 'product_cat');?>
                            <h6><a href="<?php the_permalink();?>"><?php the_title();?></a></h6>
                            <ul class="wr-rateviews wr-rateviews2">
                                <?php
                                    do_action('workreap_service_rating_count', $product);
                                    do_action('workreap_service_item_views', $product);
                                    do_action('workreap_service_item_reviews', $product);
                                    do_action('workreap_service_item_status', $post->ID);
                                ?>
                            </ul>
                            <ul class="wr-profilestatus">
                                <?php
                                    do_action('workreap_service_item_queue', $product);
                                    do_action('workreap_service_item_completed', $product);
                                    do_action('workreap_service_item_cancelled', $product);
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="wr-itemlinks">
                        <?php do_action('workreap_service_item_starting_price', $product);?>
                        <?php if($product->get_status() == 'publish' || $product->get_status() == 'private' ){?>
                            <div class="wr-switchservice">
                                <span><?php esc_html_e('Task on / off', 'workreap');?></span>
                                <div class="wr-onoff">
                                    <input type="checkbox" id="service-enable-switch-<?php echo intval($post->ID);?>" data-id="<?php echo (int)$post->ID;?>" name="service-enable-disable" <?php if($product->get_status() == 'publish'){echo do_shortcode('checked="checked"');}?>>
                                    <label for="service-enable-switch-<?php echo intval($post->ID);?>"><em><i></i></em><span class="wr-enable"></span><span class="wr-disable"></span></label>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(!empty($package_option) ){?>
                            <div class="wr-switchservice">
                                <span><?php esc_html_e('Featured Task', 'workreap');?></span>
                                <div class="wr-onoff">
                                    <input type="checkbox" id="service-featured-switch-<?php echo intval($post->ID);?>" data-id="<?php echo (int)$post->ID;?>" name="service-featured-disable" <?php if(!empty($workreap_featured)){echo do_shortcode('checked="checked"');}?>>
                                    <label for="service-featured-switch-<?php echo intval($post->ID);?>"><em><i></i></em><span class="wr-enable"></span><span class="wr-disable"></span></label>
                                </div>
                            </div>
                        <?php } ?>
                        <ul class="wr-tabicon">
                            <li data-class="wr-tooltip-data" id="wr-tooltip-10<?php echo esc_attr($post->ID) ?>" data-tippy-interactive="true" data-tippy-placement="top" data-tippy-content="<?php esc_html_e('Edit','workreap'); ?>"><a href="<?php echo esc_url($workreap_add_service_page_edit_url);?>"><span class="wr-icon-edit-2"></span></a> </li>
                            <li data-class="wr-tooltip-data" id="wr-tooltip-20<?php echo esc_attr($post->ID) ?>" data-tippy-interactive="true" data-tippy-placement="top" data-tippy-content="<?php esc_html_e('Delete','workreap'); ?>" class="wr-delete"> <a href="javascript:void(0);"  class="workreap-service-delete" data-id="<?php echo (int)$post->ID;?>"><span class="wr-icon-trash-2 bg-redheart"></span></a> </li>
                            <li data-class="wr-tooltip-data" id="wr-tooltip-30<?php echo esc_attr($post->ID) ?>" data-tippy-interactive="true" data-tippy-placement="top" data-tippy-content="<?php esc_html_e('View','workreap'); ?>"><a href="<?php echo esc_url( $task_order_url );?>"><span class="wr-icon-external-link bg-gray"></span></a></li>
                        </ul>
                    </div>
                </div>
                <?php do_action('workreap_service_item_after', $product);?>
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
            <img src="<?php echo esc_url($image_url)?>" alt="<?php esc_attr_e('add task','workreap');?>">
        </figure>
        <h4><?php esc_html_e( 'Add your new Task and start getting orders', 'workreap'); ?></h4>
        <h6><a href="<?php echo esc_url($workreap_add_service_page_url);?>"> <?php esc_html_e('Add new task', 'workreap'); ?> </a></h6>
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