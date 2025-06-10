<?php
/**
 * Project listing
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
$user_identity	= intval($current_user->ID);
$id				= !empty($args['id']) ? intval($args['id']) : '';
$user_type		= apply_filters('workreap_get_user_type', $user_identity);
$linked_profile	= workreap_get_linked_profile_id($user_identity,'',$user_type);
$order_type     = !empty($_GET['order_type']) ? $_GET['order_type'] : 'any';
$workreap_args = array(
    'post_type'         => 'product',
    'post_status'       => 'any',
    'posts_per_page'    => $show_posts,
    'paged'             => $paged,
    'author'            => $current_user->ID,
    // 'orderby'           => 'meta_value_num',
    // 'meta_key'          => '_order_status',
    'order'             => 'DESC',
    'tax_query'         => array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'projects',
        ),
    ),
);

if(!empty($order_type) && $order_type!= 'any' ){
    $update_status  = array('hired','cancelled','rejected','completed');
    if(in_array($order_type,$update_status) ){
        $workreap_args['meta_query'] = array(
            array(
                'key'       => '_post_project_status',
                'value'     => $order_type,
                'compare'   => '=',
                'type'      => 'CHAR',
            )
        );
    } else {
        $workreap_args['post_status'] = $order_type;
    }
}
$workreap_query  = new WP_Query( apply_filters('workreap_project_dashbaord_listings_args', $workreap_args) );
$create_project = workreap_get_page_uri('add_project_page');
$page_url       = Workreap_Profile_Menu::workreap_profile_menu_link($ref, $user_identity, true, $mode);
$menu_order     = workreap_list_projects_status_filter();
$count_post     = $workreap_query->found_posts;
$employer_package_option	= !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'],array('paid','freelancer_free')) ? true : false;
?>
<div class="wr-dhb-mainheading">
    <h2><?php esc_html_e('My projects', 'workreap');?></h2>
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
if ( $workreap_query->have_posts() ) :
    ?>
    <?php 
    while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
        $product            = wc_get_product( $post->ID );
        $project_price      = workreap_get_project_price($post->ID);
        $post_status        = get_post_status( $post->ID );
        $project_meta       = get_post_meta( $post->ID, 'wr_project_meta',true );
        $project_meta       = !empty($project_meta) ? $project_meta : array();
        $project_type       = !empty($project_meta['project_type']) ? ($project_meta['project_type']) : '';
        $is_featured        = !empty($product) ? $product->get_featured() : false;
        $projecet_status    = get_post_meta($product->get_id(), '_post_project_status', true);
        $projecet_status    = !empty($projecet_status) ? $projecet_status : '';
        $status_array       = array('pending','draft','publish');

        $show_menu          = false;
        if( !empty($post_status) && $post_status != 'draft' && !empty($employer_package_option) ){  
            $show_menu          = true;
        } else if( !empty($post_status) && in_array($post_status,$status_array) && in_array($projecet_status,$status_array) ){
            $show_menu          = true;
        }
        
        if( !empty($product) ){ 
            $proposal_args = array(
                'post_type' 	    => 'proposals',
                'post_status'       => array('publish','hired','completed','cancelled','disputed','refunded'),
                'posts_per_page'    => 1,
                'meta_query'        => array(
                    array(
                        'key'       => 'project_id',
                        'value'     => $product->get_id(),
                        'compare'   => '=',
                        'type'      => 'NUMERIC'
                    )
                )
            );
            $proposalList       = get_posts( $proposal_args );
            $proposal_count     = !empty($proposalList) && is_array($proposalList) ? count($proposalList) : 0;
            ?>
            <div class="wr-project-wrapper-two">
                <div class="wr-project-box">
                    <?php do_action( 'workreap_featured_item', $product,'featured_project' );?>
                    <div class=" wr-price-holder">
                        <div class="wr-verified-info">
                            <?php do_action( 'workreap_project_status_tag', $product );?>
                            <?php if( !empty($product->get_name()) ){?>
                                <h5><a href="<?php echo get_the_permalink( $post );?>"><?php echo esc_html($product->get_name());?></a></h5>
                            <?php } ?>
                        </div>
                        <div class="wr-price">
                            <?php if( !empty($project_type) ){?>
                                <?php do_action( 'workreap_project_type_text', $project_type );?>
                            <?php } ?>
                            <?php if( isset($project_price) ){?>
                                <h4><?php echo do_shortcode($project_price);?></h4>
                            <?php } ?>
                        </div>
                        <?php if( !empty($show_menu) ){?>
                            <div class="wr-projectsstatus_option">
                                <a href="javascript:void(0);" data-id="<?php echo intval($post->ID);?>"><i class="wr-icon-more-horizontal"></i></a>
                                <ul class="wr-contract-list wr-contract-list-<?php echo intval($post->ID);?>">
                                    <?php if( !empty($post_status) && $post_status != 'draft' && !empty($employer_package_option) ){?>
                                        <?php if( !empty($is_featured) ){?>
                                            <li data-id="<?php echo intval($post->ID);?>" data-value="no" class="wr_project_featured">
                                                <span><?php esc_html_e('Remove featured','workreap');?></span>
                                            </li>
                                        <?php } else { ?>
                                            <li data-id="<?php echo intval($post->ID);?>" data-value="yes" class="wr_project_featured">
                                                <span><?php esc_html_e('Mark as featured','workreap');?></span>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if( !empty($post_status) && in_array($post_status,$status_array) && in_array($projecet_status,$status_array)  ){?>
                                        <li>
                                            <span data-id="<?php echo intval($post->ID);?>" class="wr_project_remove" ><?php esc_html_e('Delete project','workreap');?></span>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                    <ul class="wr-template-view"> 
                        <?php do_action( 'workreap_posted_date_html', $product );?>
                        <?php do_action( 'workreap_location_html', $product );?>
                        <?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
                        <?php do_action( 'workreap_hiring_freelancer_html', $product );?>
                    </ul>
                    <?php do_action( 'workreap_list_hiring_freelancer_html', $product->get_id() );?>
                </div>
                <div class="wr-project-box wr-project-box-two">
                    <ul class="wr-proposal-list">
                        <?php do_action( 'workreap_project_proposal_icons_html',$product->get_id(),3,'yes' );?>
                    </ul>
                    <div class="wr-project-detail">
                        <?php
                        if(empty($proposal_count)){
                            $project_creation   = !empty($create_project) && !empty($post->ID) ? $create_project.'?step=2&post_id='.intval($post->ID) : '';

                            if(get_post_status($post->ID) == 'pending' && $workreap_settings['project_edit_after_submit'] ){ ?>
                                <a class="wr-edit-project" href="<?php echo esc_url($project_creation);?>"><i class="wr-icon-edit-3"></i><?php esc_html_e('Edit project','workreap');?></a>
                            <?php }else if( !empty($product->get_status()) && in_array($product->get_status(),$status_array) && in_array($projecet_status,$status_array)){ ?>
                                <a class="wr-edit-project" href="<?php echo esc_url($project_creation);?>"><i class="wr-icon-edit-3"></i><?php esc_html_e('Edit project','workreap');?></a>
                            <?php } 
                        }?>
                        <a href="<?php echo get_the_permalink( $post );?>" class="wr-invite-bidbtn"><?php esc_html_e('View project','workreap');?></a>
                    </div>
                </div>
            </div>
        <?php }
    endwhile;
    if( !empty($count_post) && $count_post > $show_posts ):?>
        <?php workreap_paginate($workreap_query); ?>
    <?php endif;
else:
    $image_url = !empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png';
    ?>
    <div class="wr-submitreview wr-submitreviewv3">
        <figure>
            <img src="<?php echo esc_url($image_url)?>" alt="<?php esc_attr_e('Create project','workreap');?>">
        </figure>
        <h6><a href="<?php echo esc_url($create_project);?>"> <?php esc_html_e('Create a project', 'workreap'); ?> </a></h6>
    </div>
<?php
endif;
wp_reset_postdata();
$script = "
jQuery(document).on('ready', function(){
    jQuery('.wr-projectsstatus_option > a').on('click',function() {
        let id = jQuery(this).data('id');
        //jQuery('.wr-contract-list').slideUp();
        jQuery('.wr-contract-list-'+id).slideToggle();
    });
    jQuery(document).on('change', '#wr_order_type', function (e) {
        let _this       = jQuery(this);
        let page_url = _this.find(':selected').data('url');
		window.location.replace(page_url);
    });    
});
";
wp_add_inline_script( 'workreap', $script, 'after' );