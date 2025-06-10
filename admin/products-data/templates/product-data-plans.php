<?php

/**
 * 
 * Template to display product data plan tabs fields
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/products_data
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $woocommerce, $post;
$workreap_plans_values   = get_post_meta($post->ID, 'workreap_product_plans', TRUE);
$workreap_service_plans  = Workreap_Service_Plans::service_plans();
do_action('workreap_plans_fields_before', $workreap_service_plans);
$author_id              = get_post_field ('post_author', $post->ID);
$package_detail	        = workreap_get_package($author_id);
$task_plans_allowed	    = 'yes';
$package_type	        = !empty($package_detail['type']) ? $package_detail['type'] : '';

if($package_type == 'paid'){
    $task_plans_allowed	= !empty($package_detail['package']['task_plans_allowed']) ? $package_detail['package']['task_plans_allowed'] : 'no';
}

$workreap_overlay_class  = 'wr-active-pricing';

if($task_plans_allowed == 'no'){
    $workreap_overlay_class  = 'wr-overley-pricing';
}

?>
<div class="wr-pricingcontainer">
  <div class="wr-pricing-items">
    <ul class="wr-pricingitems <?php echo esc_attr($workreap_overlay_class);?>">
        <li class="wr-emptyprice">
            <div class="wr-pricingitems__content">
                <div class="wr-pricingtitle"></div>
                <?php do_action('workreap_render_plans_fields', $workreap_service_plans, $workreap_plans_values, $task_plans_allowed);?>
            </div>
        </li>
        <?php
        if( class_exists('ACF') ) :
            $groups = acf_get_field_groups(array('product_tabs' => 'plan' ));
            $groups = acf_get_field_groups();
            foreach($groups as $group){
                foreach( $group['location'] as $group_locations ) {
                    $product_plans_category = 'am-plans-category';
                    $found_key = array_search('product_plans_category', array_column($group_locations, 'param'));
                    
                    if($found_key){
                        $group_location_category = $group_locations[$found_key];
                        
                        if(!empty($group_location_category['param']) && $group_location_category['param'] == 'product_plans_category'
                            && !empty($group_location_category['value']) ){
                            $product_plans_category .= ' am-category-'.$group_location_category['value'];
                        }
                    }

                    $product_plans_category = apply_filters('workreap_product_plans_category', $product_plans_category);
                    $found_key = '';
                    foreach( $group_locations as $rule ) {
                        
                        if( $rule['param'] == 'product_tabs' && $rule['operator'] == '==' && $rule['value'] == 'plan' ) {
                            do_action('workreap_plan_group_fields_before', $group, $workreap_service_plans, $workreap_plans_values, $product_plans_category);
                            do_action('workreap_acf_dynamically_render_fields', acf_get_fields( $group ), $workreap_service_plans, $workreap_plans_values, $product_plans_category);
                            do_action('workreap_plan_group_fields_after', $group, $workreap_service_plans, $workreap_plans_values, $product_plans_category);
                            break 2;
                        }
                    }
                }
            }
        endif;
        ?>
    </ul>
  </div>
</div>
<?php
do_action('workreap_plans_fields_after', $workreap_service_plans, $workreap_plans_values);
