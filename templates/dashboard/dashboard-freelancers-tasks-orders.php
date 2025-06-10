<?php
/**
 * Freelancer order listings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global  $current_user;
$ref                = !empty($_GET['ref']) ? esc_html($_GET['ref']) : '';
$mode               = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity      = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;
$current_page_link  = Workreap_Profile_Menu::workreap_profile_menu_link($ref, $user_identity, true, '');
$current_page_link  = !empty($current_page_link) ? $current_page_link : '';
$page_title_key     = !empty($_GET['order_type']) ? esc_html($_GET['order_type']) : esc_html__('All','workreap');

if (!class_exists('WooCommerce')) {
    return;
}

if (!empty($page_title_key) && $page_title_key == 'hired'){
    $page_title_key   = esc_html__('Ongoing','workreap');
} elseif (!empty($page_title_key) && $page_title_key == 'any'){
    $page_title_key   = esc_html__('All','workreap');
}

$page_title     = wp_sprintf('%s %s',$page_title_key,esc_html__('order listings', 'workreap'));
$show_posts     = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
$task_id        = !empty($_GET['id']) ? intval($_GET['id']) : 0;
$paged          = max($pg_page, $pg_paged);
$order_type     = !empty($_GET['order_type']) ? $_GET['order_type'] : 'any';
$menu_order     = workreap_list_tasks_order_status_filter();
$order          = 'DESC';
$sorting        = 'ID';
$order_status   = array('wc-completed', 'wc-pending', 'wc-on-hold', 'wc-cancelled', 'wc-refunded', 'wc-processing');
$page_url       = Workreap_Profile_Menu::workreap_profile_menu_link($ref, $user_identity, true, $mode);
// basic order query args
$args = array(
    'posts_per_page'    => $show_posts,
    'post_type'         => 'shop_order',
    'orderby'           => $sorting,
    'order'             => $order,
    'post_status'       => $order_status,
    'paged'             => $paged,
    'suppress_filters'  => false
);

// check and get values from search form
$search_keyword  = (isset($_GET['search_keyword'])  && !empty($_GET['search_keyword'])   ? $_GET['search_keyword']   : "");

/* search in product snippet */
// if $search_keyword field is set then prepare query to find and get product/task ids that contains search keyword
if (!empty($search_keyword)){

    $tax_queries  = array();
    $meta_queries = array();
    $product_ids  = array();
    // product_type taxonomy args
    $product_type_tax_args[] = array(
        'taxonomy' => 'product_type',
        'field'    => 'slug',
        'terms'    => 'tasks',
    );

    // append product_type taxonomy args in $tax_queries array
    $tax_queries  = array_merge($tax_queries,$product_type_tax_args);

    // prepared query args
    $search_args  = array(
        'post_type'         => 'product',
        'fields'            => 'ids',
        'post_status'       => 'any',
        's'                 => $search_keyword,
        'tax_query'         => $tax_queries,
    );

    // get product ids
    $product_ids = get_posts( $search_args );

    // if no product ids found against search keyword then set $product_ids as -1 in order to make meta query formatted
    if (empty($product_ids)){
        $product_ids  = array(-1);
    }

    $meta_query_args[] = array(
        'key' 		=> 'task_product_id',
        'value' 	=> $product_ids,
        'compare'   => 'IN'
    );
}

/* search in product snippet end */
$meta_query_args[] = array(
    'key' 		=> 'payment_type',
    'value' 	=> 'tasks',
    'compare'   => '='
);
$meta_query_args[] = array(
    'key' 		=> 'freelancer_id',
    'value' 	=> $user_identity,
    'compare'   => '='
);

if(!empty($order_type) && $order_type!= 'any' ){
    $meta_query_args[]  = array(
        'key' 		=> '_task_status',
        'value' 	=> $order_type,
        'compare'   => '='
    );
}

$query_relation     = array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);
$query              = new WP_Query($args);
$count_post         = $query->found_posts;

?>
<div class="wr-dhb-mainheading">
    <h2><?php esc_html_e('All orders','workreap');?></h2>
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
<div class="wr-dhbtabs wr-tasktabs">
    <div class="tab-content tab-taskcontent">
        <div class="tab-pane fade active show">
            <div class="wr-tabtasktitle">
                <h5 class="selected-filter"><?php echo esc_html($page_title);?></h5>
                <form class="wr-themeform" action="<?php echo esc_url( $current_page_link ); ?>">
                    <input type="hidden" name="ref" value="<?php echo esc_attr($ref); ?>">
                    <input type="hidden" name="identity" value="<?php echo esc_attr($user_identity); ?>">
                    <fieldset>
                        <div class="wr-themeform__wrap ">
                            <div class="form-group wo-inputicon">
                                <i class="wr-icon-search"></i>
                                <input type="text" name="search_keyword" class="form-control"
                                    value="<?php echo esc_attr($search_keyword) ?>"
                                    placeholder="<?php esc_attr_e('Search orders here','workreap');?>">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <?php if (class_exists('WooCommerce')) {?>
                <?php if( $query->have_posts() ){?>
                    <div class="wr-tasklist">
                        <?php while ($query->have_posts()) : $query->the_post();
                            global $post;
                            $order_id   = $post->ID;
                            $task_id    = get_post_meta( $order_id, 'task_product_id', true);
                            $task_id    = !empty($task_id) ? $task_id : 0;
                            $task_title = !empty($task_id) ? get_the_title( $task_id ) : '';

                            $freelancer_id      = get_post_meta( $order_id, 'freelancer_id', true);
                            $freelancer_id      = !empty($freelancer_id) ? intval($freelancer_id) : 0;

                            $order 		    = wc_get_order($order_id);
                            $order_price    = get_post_meta( $order_id, 'freelancer_shares', true);
                            $order_price    = !empty($order_price) ? ($order_price) : 0;

                            $employer_id      = get_post_meta( $order_id, 'employer_id', true);
                            $employer_id      = !empty($employer_id) ? intval($employer_id) : 0;

                            $product_data   = get_post_meta( $order_id, 'cus_woo_product_data', true);
                            $product_data   = !empty($product_data) ? $product_data : array();
                            $order_url      = Workreap_Profile_Menu::workreap_profile_menu_link('tasks-orders', $user_identity, true, 'detail',$order_id);
                            ?>
                            <div class="wr-tabfilteritem">
                                <div class="wr-tabbitem__list">
                                    <div class="wr-deatlswithimg">
                                        <div class="wr-icondetails">
                                            <?php do_action( 'workreap_task_order_status', $order_id );?>

                                            <?php if( !empty($task_id) ){
                                                echo do_action('workreap_task_categories', $task_id, 'product_cat');
                                            }?>

                                            <?php if( !empty($task_title) ){?>
                                                <a href="<?php echo esc_url($order_url);?>">
                                                    <h5><?php echo esc_html($task_title);?></h5>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php if( !empty($order_price) ){?>
                                    <div class="wr-itemlinks">
                                        <div class="wr-startingprice">
                                            <i><?php esc_html_e('Total task budget','workreap');?></i>
                                            <span><?php workreap_price_format($order_price);?></span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php do_action( 'workreap_task_complete_html', $order_id,'freelancers');?>
                                <div class="wr-extras">
                                    <?php do_action( 'workreap_task_author', $employer_id, 'employers' );?>
                                    <?php do_action( 'workreap_order_date', $order_id );?>
                                    <?php do_action( 'workreap_delivery_date', $order_id );?>
                                    <?php do_action( 'workreap_subtasks_count', $product_data );?>
                                    <?php do_action( 'workreap_price_plan', $order_id );?>
                                </div>
                            </div>
                        <?php endwhile;
                        ?>
                    </div>
                <?php } else {
                    do_action( 'workreap_empty_listing', esc_html__('No orders found', 'workreap'));
                } ?>
            <?php } else {              
                do_action( 'workreap_empty_listing', esc_html__('WooCommerce plugin not installed', 'workreap'));
            } ?>
        </div>
    </div>
</div>
<?php if( !empty($count_post) && $count_post > $show_posts ):?>
    <?php workreap_paginate($query); ?>
<?php endif;?>
<?php wp_reset_postdata(); ?>
<?php
$script = "
jQuery(document).on('ready', function(){
    jQuery(document).on('change', '#wr_order_type', function (e) {
        let _this       = jQuery(this);
        let page_url = _this.find(':selected').data('url');
		window.location.replace(page_url);
    });
    jQuery('.wr_view_cancellation').on('click', function(){
        var _this       = jQuery(this);
        var order_id    = _this.data('order_id');
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: {
                'action'		: 'workreap_wr_cancelled_view',
                'security'		: scripts_vars.ajax_nonce,
                'order_id'		: order_id,
            },
            dataType: 'json',
            success: function (response) {
                jQuery('.wr-preloader-section').remove();

                if (response.type === 'success') {
                    jQuery('#wr_wr_viewrating').html(response.html);
                    jQuery('#wr_excfreelancerpopup').modal('show');
                    jQuery('#wr_excfreelancerpopup').removeClass('hidden');;
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
                }
            }
        });
    });

    jQuery(document).on('click', '.wr_view_rating', function (e) {
        let rating_id		= jQuery(this).data('rating_id');

        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: {
                'action'		: 'workreap_wr_rating_view',
                'security'		: scripts_vars.ajax_nonce,
                'rating_id'		: rating_id,
            },
            dataType: 'json',
            success: function (response) {
                jQuery('.wr-preloader-section').remove();

                if (response.type === 'success') {
                    jQuery('#wr_wr_viewrating').html(response.html);
                    jQuery('#wr_excfreelancerpopup').modal('show');
                    jQuery('#wr_excfreelancerpopup').removeClass('hidden');
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
                }
            }
        });

    });
});
";
wp_add_inline_script( 'workreap', $script, 'after' );
