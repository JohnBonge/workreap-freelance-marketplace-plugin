<?php
/**
 * Dashboard task listings
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/admin_dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $current_user,$workreap_settings;

$ref                = !empty($_GET['ref'])  ? esc_html($_GET['ref'])  : '';
$mode 			    = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$user_identity      = !empty($_GET['identity']) ? intval($_GET['identity']) : 0;
$user_type		    = apply_filters('workreap_get_user_type', $user_identity );
$status_list        = workreap_project_status_list();
$post_status        = array('draft','rejected','pending','publish','completed','cancelled','disputed','declined','hired','refunded','processing','resolved');
$current_page_link  = Workreap_Profile_Menu::workreap_profile_admin_menu_link($ref, $user_identity, true, $mode);
$current_page_link  = !empty($current_page_link) ? $current_page_link : '';
// check and get values from search form
$search_keyword  = (isset($_GET['search_keyword'])  && !empty($_GET['search_keyword'])   ? esc_html($_GET['search_keyword'])   : "");

$project_status             = !empty($workreap_settings['project_status']) ? $workreap_settings['project_status'] : '';
$resubmit_project_status    = !empty($workreap_settings['resubmit_project_status']) ? $workreap_settings['resubmit_project_status'] : 'no';

// sort by status
$sort_by_status = (isset($_GET['sort_by']) && !empty($_GET['sort_by']) ? $_GET['sort_by'] : "");

// if sort by status exists, then update the $post_status array
if (!empty($sort_by_status) && $sort_by_status != 'any'){
  $post_status  = array($sort_by_status);
}

// basic query args
$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$per_page       = get_option('posts_per_page');
$workreap_args   = array(
  'post_type'         => 'product',
  'post_status'       => $post_status,
  'posts_per_page'    => $per_page,
  'paged'             => $paged,
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

// if keyword field is set in search then append its args in $query_args
if (!empty($search_keyword)){

  $filtered_args = array(
    's' => $search_keyword,
  );

  $workreap_args = array_merge($workreap_args,$filtered_args);
}

if( !empty($project_status) && $project_status === 'pending' && !empty($resubmit_project_status) && $resubmit_project_status === 'yes'){
    $meta_query_args    = array();
    if( !empty($sort_by_status) && $sort_by_status === 'pending'){
        $meta_query_args[] = array(
            'key' 		     => '_post_project_status',
            'value' 	     => 'requested',
            'compare' 	   => '='
          );
    } 
    if( !empty($meta_query_args) ){
        $query_relation = array('relation' => 'AND',);
        $workreap_args['meta_query'] = array_merge($query_relation, $meta_query_args);
    }
    
}

$workreap_query  = new WP_Query( apply_filters('workreap_admin_service_listings_args', $workreap_args) );
$date_format    = get_option( 'date_format' );
?>
<div class="col-xl-12">
    <div class="wr-dhb-mainheading">
        <h2><?php esc_html_e('Manage projects','workreap');?></h2>
        <div class="wr-sortby">
            <form class="wr-themeform wr-displistform" id="wr-search-task-form" action="<?php echo esc_url( $current_page_link ); ?>">
                <input type="hidden" name="ref"             value="<?php echo esc_attr($ref); ?>">
                <input type="hidden" name="identity"        value="<?php echo esc_attr($user_identity); ?>">
                <input type="hidden" name="mode"            value="<?php echo esc_attr($mode); ?>">
                <fieldset>
                    <div class="wr-themeform__wrap">
                        <div class="form-group wr-inputicon wr-inputheight wr-dbholder border-0">
                            <i class="wr-icon-search"></i>
                            <input type="text" name="search_keyword" class="form-control" value="<?php echo esc_attr($search_keyword) ?>"  placeholder="<?php esc_attr_e('Search project listing','workreap');?>">
                        </div>
                        <div class="wr-actionselect">
                            <span><?php esc_html_e('By urgency:','workreap');?></span>
                            <div class="wr-select wr-dbholder border-0">
                                <select id="wr_admin_order_type" name="sort_by" class="form-control " data-select2-id="wr-selection1" tabindex="-1" aria-hidden="true">
                                    <?php
										foreach($status_list as $key => $val ){
										$selected   = '';
										if( !empty($sort_by_status) && $sort_by_status == $key ){
											$selected   = 'selected';
										}
                                    ?>
                                    <option value="<?php echo esc_attr($key);?>" <?php echo esc_attr($selected);?>><?php echo esc_html($val);?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="wr-dbholder border-0 wr-todolist">
        <?php if ( $workreap_query->have_posts() ) :?>
        <table class="table wr-table wr-dbholder">
            <thead>
                <tr>
                    <th><?php esc_html_e('Title','workreap');?></th>
                    <th><?php esc_html_e('Date','workreap');?></th>
                    <th><?php esc_html_e('Featured','workreap');?></th>
                    <th><?php esc_html_e('Project author','workreap');?></th>
                    <th><?php esc_html_e('Status','workreap');?></th>
                    <th><?php esc_html_e('Action','workreap');?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                        $product    = wc_get_product( $post->ID );
                        $user_id    = get_post_field( 'post_author', $post->ID );
                        $link_id    = workreap_get_linked_profile_id( $user_id,'','employers' );
                        $user_link  = get_the_permalink( $link_id );
                        $user_name  = workreap_get_username($link_id);
                        $post_status= get_post_status( $post );

                        $workreap_featured = $product->get_featured();?>
                        <tr>
                            <td data-label="<?php esc_attr_e('Title','workreap');?>">
                                <div class="wr-checkboxwithimg">
                                    <div class="wr-tasks-image">
                                        <figure><?php echo woocommerce_get_product_thumbnail('workreap_thumbnail');?></figure>
                                        <a href="<?php the_permalink();?>"><?php the_title();?></a>
                                    </div>
                                </div>
                            </td>
                            <td data-label="<?php esc_attr_e('Date','workreap');?>">
                                <?php echo date_i18n( $date_format,  strtotime(get_the_date()));?>
                            </td>
                            <td data-label="<?php esc_attr_e('Featured','workreap');?>">
                                <?php 
                                    if( !empty($workreap_featured) ){
                                        esc_html_e('Yes','workreap');
                                    } else {
                                        esc_html_e('No','workreap');
                                    }
                                ?>
                            </td>
                            <td data-label="<?php esc_attr_e('Project author','workreap');?>">
                                <a href="<?php echo esc_url($user_link);?>" target="_balnk"><?php echo esc_html($user_name);?></a>
                            </td>
                            <td class="wr-task-status" data-label="<?php esc_attr_e('Status','workreap');?>">
                                <div class="wr-bordertags"><?php do_action( 'workreap_project_status_tag', $product );?></div>
                            </td>
                            <td data-label="<?php esc_attr_e('Action','workreap');?>">
                                <ul class="wr-tabicon wr-invoicecon">
                                    <li>
                                        <?php if( !empty($post_status) && in_array($post_status,array('pending','publish'))){ ?>
                                            <a href="javascript:void(0)" class="wr-canceled wr_rejected_project_model" data-id="<?php echo esc_attr(intval($post->ID)); ?>"><span class="wr-icon-x"></span>&nbsp;<?php esc_attr_e('Reject project','workreap');?></a>
                                        <?php } if( !empty($post_status) && in_array($post_status,array('pending','rejected'))){ ?>
                                            <a href="javascript:void(0);" class="wr-publish wr_publish_project" data-id="<?php echo intval($post->ID);?>"><span class="wr-icon-check"></span>&nbsp;<?php esc_attr_e('Approve project','workreap');?></a>
                                        <?php } ?>
                                    </li>
                                    <li class="wr-delete"><div href="javascript:void(0);" class="wr-red wr_remove_task" data-id="<?php echo intval($post->ID);?>"><span class="wr-icon-trash"></span></div> </li>
                                    <li> <a href="<?php echo get_the_permalink( $post );?>" target="_blank"><span class="wr-icon-eye wr-gray"></span></a> </li>
                                </ul>
                            </td>
                        </tr>
                <?php  endwhile;?>
                
            </tbody>
        </table>
        <?php else: ?>
            <?php do_action( 'workreap_empty_listing', esc_html__('No tasks found', 'workreap')); ?>
        <?php endif; ?>
        <?php workreap_paginate($workreap_query,'wr-tabfilteritem');?>
        <?php wp_reset_postdata();?>
    </div>
</div>
<div class="modal fade wr-taskreject" id="wr-reject-project" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="wr-popuptitle">
            <h4><?php esc_html_e('Reject project approval request', 'workreap'); ?></h4>
            <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
            </div>
            <div class="modal-body">
            <form class="wr-themeform">
                <fieldset>
                <div class="form-group">
                    <textarea class="form-control" rows="6" cols="80" id="wr_reject_project_reason" name="wr_reject_project_reason" placeholder="<?php esc_attr_e('Add rejection reason', 'workreap'); ?>"></textarea>
                </div>
                <div class="form-group">
                    <a href="javascript:void(0);" class="wr-btn wr_rejected_project" data-wr_project_id="" id="wr_submit_reject_project"><?php esc_html_e('Send', 'workreap'); ?></a>
                </div>
                </fieldset>
            </form>
            </div>
        </div>
    </div>
</div>