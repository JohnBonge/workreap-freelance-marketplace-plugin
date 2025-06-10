<?php
/**
 *  Task plans
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global  $workreap_settings;
$package_option         =  !empty($workreap_settings['package_option']) && in_array($workreap_settings['package_option'],array('paid','employer_free')) ? true : false;
$custom_field_option    =  !empty($workreap_settings['custom_field_option']) ? $workreap_settings['custom_field_option'] : false;
$packages_listing_page  = 'javascript:void(0);';
if (!empty($package_option)) {
    $packages_listing_page  = !empty($workreap_settings['tpl_package_page']) ? get_the_permalink($workreap_settings['tpl_package_page']) : 'javascript:void(0);';
}

if(!empty($_GET['postid'])){
    $post_id = $_GET['postid'];
}

if ( !class_exists('WooCommerce') ) {
	return;
}


$current_page_url   = get_the_permalink();
if(isset($_GET['post']) && !empty($_GET['post'])){
    $post_id = intval($_GET['post']);
    $current_page_url   = add_query_arg('post', $post_id, $current_page_url);
}

if(isset($_GET['step']) && !empty($_GET['step'])){
    $step = intval($_GET['step']);
    $current_page_url   = add_query_arg('step', $step, $current_page_url);
}
if( !empty($custom_field_option) ){
    $wr_custom_fields       = get_post_meta( $post_id, 'wr_custom_fields',true );
    $wr_custom_fields       = !empty($wr_custom_fields) ? $wr_custom_fields : array();
}
$workreap_plans_values       = get_post_meta($post_id, 'workreap_product_plans', TRUE);
$workreap_subtasks_selected  = get_post_meta($post_id, 'workreap_product_subtasks', TRUE);
$workreap_service_plans  = Workreap_Service_Plans::service_plans();
$workreap_service_keys   = !empty($workreap_service_plans) && is_array($workreap_service_plans) ? array_keys($workreap_service_plans) : array();
$workreap_overlay_class  = 'wr-active-pricing';
if($task_plans_allowed == 'no'){
    $workreap_overlay_class  = 'wr-overley-pricing';
}

$user_id = get_current_user_id();
$args = array(
    'limit'     => -1, // All products
    'status'    => 'publish',
    'type'      => 'subtasks',
    'orderby'   => 'date',
    'order'     => 'DESC',
    'author'    => $user_id
);
$workreap_subtasks = wc_get_products( $args );?>
<div id="service-pricing-wrapper">
    <form id="service-plans-form" class="wr-themeform service-plans-form" name="service-plans-form" action="<?php echo esc_url($current_page_url);?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="form-group">
                <?php do_action('workreap_plans_fields_before', $workreap_service_plans); ?>
                <div class="wr-pricingcontainer wr-pricingcontainertwo">
                    <div class="wr-pricing-items">
                        <ul class="wr-pricingitems <?php echo esc_attr($workreap_overlay_class);?>">
                            <li class="wr-pricingplan">
                                <div class="wr-pricingitems__content">
                                    <div class="wr-pricingtitle"></div>
                                    <?php do_action('workreap_render_plans_fields', $workreap_service_plans, $workreap_plans_values, $task_plans_allowed);?>
                                </div>
                            </li>
                            <?php
                            if( class_exists('ACF') ) :
                               $groups = acf_get_field_groups();
                                foreach($groups as $group){ 
                                    foreach( $group['location'] as $group_locations ) {
                                        $workreap_plan_category = '';
                                        $product_plans_category = 'am-plans-category';
                                        $found_key = array_search('product_plans_category', array_column($group_locations, 'param'));

                                        if($found_key){
                                            $group_location_category = $group_locations[$found_key];

                                            if(isset($group_location_category['param']) && $group_location_category['param'] == 'product_plans_category'
                                                && !empty($group_location_category['value']) ){
                                                $product_plans_category .= ' am-category-'.$group_location_category['value'];
                                                $workreap_plan_category = $group_location_category['value'];
                                            }

                                        }

                                        $product_plans_category = apply_filters('workreap_product_plans_category', $product_plans_category);
                                        $found_key = '';

                                        if(!empty($service_categories) && is_array($service_categories) && !in_array($workreap_plan_category, $service_categories)){
                                            continue;
                                        }
                                        
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
                        <?php if($task_plans_allowed == 'no'){?>
                            <div class="wr-overleymodel">
                                <span class="wr-icon-bookmark"></span>
                                <h5><?php esc_html_e('Need more slots?', 'workreap');?></h5>
                                <p><?php esc_html_e('Unlock to add more package option to your employers and get hired instantly', 'workreap');?></p>
                                <div class="wr-lockbtn">
                                    <a href="<?php echo esc_url($packages_listing_page);?>" class="btn-lock"><?php esc_html_e('Unlock', 'workreap');?> <span class="wr-icon-unlock"></span></a>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <?php do_action('workreap_plans_fields_after', $workreap_service_plans, $workreap_plans_values); ?>
            </div>
            
            <div class="form-group">
                <div class="wr-postserviceholder">
                    <div class="wr-postservicetitle">
                        <h4><?php esc_html_e('Add add-ons', 'workreap');?></h4>
                        <a href="javascript:void(0);" data-target="#addon" data-toggle="modal" id="wr_add_new_task" data-heading="<?php esc_attr_e('Add task add-on', 'workreap');?>" title="<?php esc_attr_e('Add more', 'workreap');?>"><?php esc_html_e('Add more', 'workreap');?></a>
                    </div>
                    <?php do_action('workreap_subtask_before', $post_id); ?>
                    <ul id="tbslothandle" class="wr-addon">

                        <?php if(!empty($workreap_subtasks) && is_array($workreap_subtasks) && count($workreap_subtasks)>0){
                            foreach($workreap_subtasks as $subtask){
                                $checked    = '';
                                
                                if(!empty($workreap_subtasks_selected) && is_array($workreap_subtasks_selected) && in_array($subtask->get_id(), $workreap_subtasks_selected)){
                                    $checked    = 'checked="checked"';
                                }?>
                                <li id="subtask-<?php echo (int)$subtask->get_id()?>" class="workreap-subtasklist">
                                    <input Type="hidden" name="subtasks_ids[]" value="<?php echo (int)$subtask->get_id()?>" />
                                    <div class="wr-addon__content">
                                        <div class="wr-checkbox">
                                            <input class="wr-service-subtask" id="subtask<?php echo (int)$subtask->get_id()?>" name="subtasks_ids[]" value="<?php echo (int)$subtask->get_id()?>" type="checkbox" <?php echo do_shortcode($checked);?>>
                                            <label for="subtask<?php echo (int)$subtask->get_id()?>"><span><?php echo esc_html($subtask->get_name());?></span></label>
                                        </div>
                                        <h5><?php workreap_price_format($subtask->get_price(),'',true); ?></h5>
                                    </div>
                                    <a href="javascript:void(0);" class="wr-addon__right wr-subtask-edit" data-subtask_id="<?php echo (int)$subtask->get_id()?>" data-heading="<?php esc_attr_e('Edit task add-on', 'workreap');?>"><i class="wr-icon-edit-2"></i></a>
                                </li>
                                <?php
                            }
                        } ?>
                    </ul>
                </div>
            </div>
            <div class="form-group wr-postserviceformbtn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save & Update” to update pricings', 'workreap'); ?></span>
                    <button type="submit" class="wr-btn"><?php esc_html_e('Save & Update', 'workreap'); ?></button>
                    <input type="hidden" id="service_id" name="post_id" value="<?php echo (int)$post_id; ?>">
                    <input type="hidden" id="add_service_step" name="step" value="<?php echo (int)$step; ?>">
                </div>
            </div>
        </fieldset>

    </form>
    <script type="text/template" id="tmpl-load-service-subtask">
        <li id="subtask-{{data.id}}" class="workreap-subtasklist">
            <input Type="hidden" name="subtasks_ids[]" value="{{data.id}}" />
            <div class="wr-addon__content">
                <div class="wr-checkbox">
                    <input id="subtask{{data.id}}" class="wr-service-subtask" name="subtasks_ids[]" value="{{data.id}}" type="checkbox" checked="checked">
                    <label for="subtask{{data.id}}"><span>{{data.title}}</span></label>
                </div>
                <h5>{{data.price}}</h5>
            </div>
            <a href="javascript:void(0);" class="wr-addon__right wr-subtask-edit"  data-heading="<?php esc_attr_e('Edit task add-on', 'workreap');?>" data-subtask_id="{{data.id}}"><i class="wr-icon-edit-2"></i></a>
        </li>
    </script>
    <script type="text/template" id="tmpl-load-service-add-subtask">
        <fieldset id="wr-subtask-form">
            <div class="form-group">
                <label class="form-group-title"><?php esc_html_e('Add add-on title', 'workreap');?>:</label>
                <input type="text" id="subtask-title" value="{{data.title}}" class="form-control" placeholder="<?php esc_attr_e('Enter title here', 'workreap');?>" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-group-title"><?php esc_html_e('Add add-on price', 'workreap');?>:</label>
                <input type="number" min="0" id="subtask-price" value="{{data.price}}" class="form-control" autocomplete="off" placeholder="<?php esc_attr_e('Enter price', 'workreap');?>">
            </div>
            <div class="form-group">
                <label class="form-group-title"><?php esc_html_e('Add add-on description', 'workreap');?>:</label>
                <textarea class="form-control" id="subtask-description" placeholder="<?php esc_attr_e('Enter description', 'workreap');?>">{{data.content}}</textarea>
            </div>
            <div class="form-group wr-form-btn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save & Update” to update your add-ons', 'workreap');?></span>
                    <a href="javascript:void(0);" class="wr-btn" id="wr-add-subtask-service"><?php esc_html_e('Save & Update', 'workreap');?></a>
                </div>
            </div>
            <input type="hidden" name="subtask_id" id="subtask_id" value="{{data.id}}">
        </fieldset>
    </script>
    <?php if( !empty($custom_field_option) ){?>
        <script type="text/template" id="tmpl-load-service-custom_fields">
            <li id="fields-{{data.id}}" class="wr-pricing-input am-plans-category am-category-33 content_upload_to_zip_file">
                <div class="wr-pricingitems__content">
                    <div class="form-field wr-remove-field">
                        <div class="wr-trashlink">
                            <i class="wr-icon-trash-2"></i>
                        </div>
                    </div>
                    <div class="wr-pricingtitle form-field wr-pricing-input">
                            <input type="text" name="custom_fields[{{data.id}}][title]" value="" class="form-control wr-cf-title-input" placeholder="<?php esc_attr_e('Add title','workreap');?>" autocomplete="off">
                        </div>
                    <?php foreach($workreap_service_keys as $workreap_keys){?>
                        <div class="form-field wr-pricing-input">
                            <input type="text" name="custom_fields[{{data.id}}][<?php echo esc_attr($workreap_keys);?>]" value="" class="form-control " placeholder="<?php esc_attr_e('Add value','workreap');?>" autocomplete="off">
                        </div>
                    <?php } ?>
                </div>
            </li>
        </script>
    <?php } ?>
</div>