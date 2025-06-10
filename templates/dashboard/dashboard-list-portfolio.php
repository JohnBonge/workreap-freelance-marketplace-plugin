<?php

/**
 * List Portfolio
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user, $workreap_settings, $userdata, $post;

$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$per_page   = get_option('posts_per_page');
$workreap_args   = array(
	'post_type'         => 'portfolios',
	'post_status'       => 'any',
	'posts_per_page'    => $per_page,
	'paged'             => $paged,
	'orderby'           => 'date',
	'order'             => 'DESC',
	'author'			=> $current_user->ID
	
  );
$workreap_query  = new WP_Query( apply_filters('workreap_portfolio_listings_args', $workreap_args) );
$selected_type			= array();
if(function_exists('get_field_object')){
	$field = get_field_object('field_668e242339c78');
	$selected_type	= !empty($field['choices']) ? $field['choices'] : array();
}
?>
<div class="wr-dhb-mainheading">
    <h2><?php esc_html_e('Manage Portfolio','workreap');?></h2>
    <div class="wr-dhb-mainheading__rightarea">
        <a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $current_user->ID, true, 'update-portfolio'));?>" class="wr-btn">
            <?php esc_html_e('Add new', 'workreap');?>
            <span class="rippleholder wr-jsripple"><em class="ripplecircle"></em></span>
        </a>
    </div>
</div>
<div class="wr-dbholder border-0 wr-todolist">
    <?php if ( $workreap_query->have_posts() ) :?>
    <table class="table wr-table wr-dbholder">
        <thead>
            <tr>
                <th><?php esc_html_e('Title','workreap');?></th>
                <th><?php esc_html_e('Type','workreap');?></th>
                <th><?php esc_html_e('Action','workreap');?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                $post_id		= get_the_ID();
                $type	        = get_post_meta($post_id, 'type',true);
                $type           = !empty($selected_type[$type]) ? $selected_type[$type] : ucfirst($type); 
                $edit_link		= Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $current_user->ID, true, 'update-portfolio',$post_id);
                ?>
                    <tr>
                        <td data-label="<?php esc_attr_e('Title','workreap');?>">
                            <div class="wr-checkboxwithimg">
                                <div class="wr-tasks-image">
                                    <a href="<?php echo esc_url($edit_link);?>"><?php the_title();?></a>
                                </div>
                            </div>
                        </td>
                        <td class="wr-task-status" data-label="<?php esc_attr_e('Type','workreap');?>">
                            <?php echo esc_html($type);?>
                        </td>
                        <td data-label="<?php esc_attr_e('Action','workreap');?>">
                            <ul class="wr-tabicon wr-invoicecon">
                                <li class="wr-delete"><a href="javascript:void(0);" class="wr-red workreap-portfolio-delete" data-id="<?php echo intval($post_id);?>"><span class="wr-icon-trash"></span></a> </li>
                                <li> <a href="<?php echo esc_url($edit_link);?>"><span class="wr-icon-edit-2"></span></a> </li>
                            </ul>
                        </td>
                    </tr>
            <?php  endwhile;?>
            
        </tbody>
    </table>
    <?php else: ?>
        <?php do_action( 'workreap_empty_listing', esc_html__('No portfolios found', 'workreap')); ?>
    <?php endif; ?>
    <?php workreap_paginate($workreap_query,'wr-tabfilteritem');?>
    <?php wp_reset_postdata();?>
</div>

<?php
