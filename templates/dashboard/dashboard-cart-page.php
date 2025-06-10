<?php
/**
 *
 * The template used for displaying cart detail
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

global $current_user,$workreap_settings;
$custom_field_option    =  !empty($workreap_settings['custom_field_option']) ? $workreap_settings['custom_field_option'] : false;
$admin_commision_employers     =  !empty($workreap_settings['admin_commision_employers']) ? $workreap_settings['admin_commision_employers'] : 0;
$commission_text            =  !empty($workreap_settings['commission_text']) ? $workreap_settings['commission_text'] : esc_html__('Processing fee', 'workreap');

$product_id       = !empty($_GET['id']) ? intval($_GET['id']) : 0;
$post_author	  = get_post_field( 'post_author', $product_id );
$post_author      = !empty($post_author) ? intval($post_author) : 0;

if( !empty($current_user->ID) && intval($post_author) == $current_user->ID ){
	do_action( 'workreap_notification', esc_html__('Restricted access','workreap'), esc_html__('Oops! you are not allowed to perfom this action','workreap') );
} else {

    $key              = !empty($_GET['key']) ? $_GET['key'] : '';
    $sub_tasks        = !empty($_GET['sub_tasks']) ? $_GET['sub_tasks'] : array();

    $user_balance     = !empty($current_user->ID) ? get_user_meta($current_user->ID, '_employer_balance', true) : '';
    $user_balance     = !empty($user_balance) ? $user_balance : 0;

    $wallet_checked   = !empty($user_balance) ? 'checked' : '';
    $product          = wc_get_product($product_id);
    $image            = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), array(100, 100));
    $product_name     = $product->get_name();

    $list_html = '';
    /* product related categories */
    $prod_cate_html     = '';
    $product_cate_ids   = wc_get_product_term_ids($product_id, 'product_cat');

    foreach ($product_cate_ids as $cat_id) {
        $term           = get_term_by('id', $cat_id, 'product_cat');
        $prod_cate_html .= '<a href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html($term->name) . '</a>, ';
    }
    /* getting features */
    $product_cat  = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
    $plan_array   = array(
        'product_tabs'            => array('plan'),
        'product_plans_category'  => $product_cat
    );

    /* getting acf fields */
    $acf_fields             = workreap_acf_groups($plan_array);
    /* workreap plan Values */
    $workreap_plans_values   = get_post_meta($product_id, 'workreap_product_plans', TRUE);
    $workreap_plans_values   = !empty($workreap_plans_values) ? $workreap_plans_values : array();
    /* getting subtasks */
    $workreap_subtask        = get_post_meta($product_id, 'workreap_product_subtasks', TRUE);
    $workreap_subtask        = !empty($workreap_subtask) ? $workreap_subtask : array();

    $wr_custom_fields       = get_post_meta( $product_id, 'wr_custom_fields',true );
    $wr_custom_fields       = !empty($wr_custom_fields) ? $wr_custom_fields : array();
    ?>
    <div class="wr-main-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <div class="wr-servicedetail">
                        <div class="wr-checkoutinfo">
                            <?php if (!empty($image[0])) { ?>
                                <figure>
                                    <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>">
                                </figure>
                            <?php } ?>
                            <div class="wr-checkoutdetail">
                                <h6><?php echo do_shortcode($prod_cate_html); ?></h6>
                                <h5><?php echo esc_html($product_name); ?></h5>
                                <ul class="wr-blogviewdates wr-blogviewdatessm">
                                    <?php do_action('workreap_service_rating_count', $product); ?>
                                </ul>
                            </div>
                        </div>
                        <div class="wr-box">
                            <h4><?php esc_html_e('Features included', 'workreap'); ?>:</h4>
                            <?php
                            $counter_checked      = 0;
                            $price_package        = 0;
                            $package_title        = '';
                            $package_key          = '';
                            $pkg_image            = '';
                            foreach ($workreap_plans_values as $plan_key => $plan_val) {
                                $counter_checked++;
                                if (!empty($key) && $key == $plan_key) {
                                    $price_package    = !empty($plan_val['price']) ? $plan_val['price'] : 0;
                                    $package_title    = !empty($plan_val['title']) ? $plan_val['title'] : '';
                                    $pkg_image	      = !empty($workreap_settings['task_plan_icon_'.$plan_key]['url']) ? $workreap_settings['task_plan_icon_'.$plan_key]['url'] : '';
                                    $package_price    = $price_package;
                                    $package_key      = $plan_key;
                                }
                                
                                $feature_class = 'd-none';
                                if ($key === $plan_key) {
                                    $feature_class = '';
                                }
                                ?>
                                <ul class="wr-mainlist wr-mainlistvtwo wr-pkg-<?php echo esc_attr($plan_key) . ' ' . esc_attr($feature_class); ?>"
                                    id="wr-pkg-<?php echo esc_attr($plan_key); ?>">
                                    <?php
                                    foreach ($acf_fields as $acf_key => $acf_field) {
                                        $plan_value = !empty($acf_field['key']) && !empty($plan_val[$acf_field['key']]) ? $plan_val[$acf_field['key']] : '';
                                        
										if (!empty($acf_field['label'])) {
                                            if (!empty($acf_field['type']) && in_array($acf_field['type'], array('text', 'textarea', 'number'))) {
                                                echo do_shortcode('<li><span>' . esc_html($acf_field['label']) . '</span><em> (' . esc_html($plan_value) . ')</em></li>');
                                            } else if (!empty($acf_field['type']) && $acf_field['type'] === 'url' && !empty($plan_value)) {
                                                echo do_shortcode('<li><span>' . esc_html($acf_field['label']) . '</span><em><a href="' . esc_url($plan_value) . '" target="_blank"> (' . esc_html($plan_value) . ')</a></em></li>');
                                            } else if (!empty($acf_field['type']) && $acf_field['type'] === 'email' && !empty($plan_value)) {
                                                echo do_shortcode('<li><span>' . esc_html($acf_field['label']) . '</span><em><a href="mailto:' . esc_attr($plan_value) . '" target="_blank"> (' . esc_html($plan_value) . ')</a></em></li>');
                                            } else if (!empty($acf_field['type']) && in_array($acf_field['type'], array('checkbox'))) {
                                                $class = !empty($plan_value) && $plan_value === 'yes' ? 'wr-available' : 'wr-unavailable';
                                                echo do_shortcode('<li class="' . esc_attr($class) . '"><span>' . esc_html($acf_field['label']) . '</span></li>');
                                            }
                                        }
                                    }
                                    ?>
                                    <?php 
                                        if( !empty($wr_custom_fields) && !empty($custom_field_option) ){
                                            foreach($wr_custom_fields as $field_value){
                                                if( !empty($field_value['title']) ){?>
                                                <li>
                                                    <span><?php echo esc_html($field_value['title']); ?></span>
                                                    <em> (<?php echo esc_html($field_value[$plan_key]); ?>)</em>
                                                </li>
                                    <?php   }
                                        }
                                    } 
                                    ?>
                                </ul>
                            <?php } ?>
                        </div>
                        <?php if( !empty($workreap_subtask) ){?>
                            <div class="wr-box">
                                <div class="wr-boxtittle">
                                    <h4><?php esc_html_e('Additional services', 'workreap'); ?></h4>
                                    <div class="wr-inputiconbtn"></div>
                                </div>
                                <ul class="wr-additionalservices wr-additionalservicesvtwo" id="wr-show_more">
                                    <?php
                                    foreach ($workreap_subtask as $workreap_subtask_id) {
                                        $price_subtask  = get_post_meta($workreap_subtask_id, '_regular_price', true);
                                        $subtask_title  = get_the_title($workreap_subtask_id);
                                        $checked        = '';
                                        if (!empty($sub_tasks) && is_array($sub_tasks) && in_array($workreap_subtask_id, $sub_tasks)) {
                                            $checked      = 'checked';
                                            $price_package = $price_package + $price_subtask;
                                            $list_html .= '<li><span>' . esc_html($subtask_title) . '</span><em>' . workreap_price_format($price_subtask, 'return') . '</em></li>';
                                        }
                                        /* implement active class */
                                        $active_class = in_array($workreap_subtask_id, $sub_tasks) ? 'class=wr-services-checked' : '';
                                        ?>
                                        <li <?php echo esc_attr($active_class); ?>>
                                            <div class="wr-form-checkbox wr-additionalpackage">
                                                <input class="form-check-input wr-form-check-input-sm wr_subtask_check"
                                                    type="checkbox"
                                                    id="additionalservice-list-<?php echo intval($workreap_subtask_id); ?>"
                                                    name="task-additional-serives[]"
                                                    data-title="<?php echo esc_attr($subtask_title); ?>"
                                                    data-price="<?php echo workreap_price_format($price_subtask, 'return'); ?>"
                                                    data-id="<?php echo intval($workreap_subtask_id); ?>"
                                                    value="<?php echo intval($workreap_subtask_id); ?>"
                                                    <?php echo esc_attr($checked); ?>>
                                                <label class="wr-additionolinfo"
                                                    for="additionalservice-list-<?php echo intval($workreap_subtask_id); ?>">
                                                    <span><?php echo esc_html($subtask_title); ?></span>
                                                    <em><?php echo apply_filters('the_content', get_the_content(null, false, $workreap_subtask_id)); ?></em>
                                                </label>
                                                <div class="wr-addcartinfoprice">
                                                    <h6><?php workreap_price_format($price_subtask); ?></h6>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-xl-4">
                    <aside>
                        <form id="wr_cart_form" class="wr-orderdetailswrap">
                            <div class="wr-asideholder">
                                <div class='wr-asideboxsm'>
                                    <h5><?php esc_html_e('Order details', 'workreap'); ?></h5>
                                </div>
                                <div class="wr-pakagedetail wr-additonoltitleholder collapsed" role="button"
                                    data-bs-toggle="collapse" data-bs-target="#wr-pakagedetail" aria-expanded="false">
                                    <?php if( !empty($pkg_image) ){?>
                                        <figure>
                                        <img id="wr_pkg_image" src="<?php echo esc_url($pkg_image);?>" alt="<?php echo esc_attr($package_title); ?>">
                                    </figure>
                                    <?php } ?>
                                    <div class='wr-pakageinfo'>
                                        <h6><?php echo esc_html($package_title); ?></h6>
                                        <?php if( !empty($package_price) ) {?>
                                            <h4 id="wr_package_price"><?php workreap_price_format($package_price); ?></h4>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="wr-collapsepanel">
                                    <div class="collapse" id="wr-pakagedetail">
                                        <ul class="wr-pakagelist">
                                            <?php
                                            foreach ($workreap_plans_values as $plan_key => $plan_val) {
                                                $price_plan   = !empty($plan_val['price']) ? $plan_val['price'] : 0;
                                                if (!empty($plan_val['title'])) {
                                                    $selected   = '';
                                                    if (!empty($key) && $key == $plan_key) {
                                                        $selected     = 'selected';
                                                        $list_html    = '<li><span>' . esc_html($plan_val['title']) . '</span><em>' . workreap_price_format($price_plan, 'return') . '</em></li>' . $list_html;
                                                    }

                                                /* implement active class */
                                                $active_clas        = !empty($key) && $key == $plan_key ? 'active' : '';
                                                $task_plan_icon_url	= !empty($workreap_settings['task_plan_icon_'.$plan_key]['url']) ? $workreap_settings['task_plan_icon_'.$plan_key]['url'] : '';
                                                ?>
                                                <li class="wr-pakagelist-item <?php echo esc_attr($active_clas); ?>"
                                                    data-task_id="<?php echo intval($product_id); ?>"
                                                    data-package_key="<?php echo do_shortcode($plan_key); ?>" data-img="<?php echo esc_url($task_plan_icon_url);?>">
                                                    <a href="javascript:void(0);">
                                                        <?php if( !empty($task_plan_icon_url) ){ ?>
                                                            <img src="<?php echo esc_url($task_plan_icon_url);?>" alt="<?php echo esc_attr($plan_val['title']);?>" />
                                                        <?php } ?>
                                                        <span><?php echo esc_html($plan_val['title']); ?></span>
                                                        <em>
                                                            <?php workreap_price_format($price_plan); ?>
                                                            <i class="fas fa-check"></i>
                                                        </em>
                                                    </a>
                                                </li>
                                            <?php } }?>
                                        </ul>
                                        <input type="hidden" id="wr_task_cart" data-task_id="<?php echo intval($product_id); ?>">
                                        <input type="hidden" name="product_task" id="wr_project_task_key" value="<?php echo do_shortcode($package_key); ?>" data-task_key="<?php echo do_shortcode($package_key); ?>">
                                    </div>
                                </div>
                            </div>
                            <?php
                                if(!empty($admin_commision_employers )){
                                    $processing_fee     =  ($price_package/100) * $admin_commision_employers;
                                    $price_package      = $price_package + $processing_fee;
                                    $list_html          = $list_html.'<li><span>' . $commission_text . '</span><em>' . workreap_price_format($processing_fee, 'return') . '</em></li>';
                                }
                            ?>

                            <div class="wr-asideholder mt-0 border-top-0">
                                <div class="wr-asideboxv2">
                                    <div class="wr-sidetitle">
                                        <h5><?php esc_html_e('Selected additional features', 'workreap'); ?></h5>
                                    </div>
                                    <ul class="wr-exploremore" id="wr-planlist">
                                        <?php echo do_shortcode( $list_html );?>
                                    </ul>
                                </div>
                                
                                <ul class="wr-featuredlisted">
                                    <li><?php esc_html_e('Subtotal','workreap');?> <span id="wr_task_total"><?php workreap_price_format($price_package); ?></span></li>
                                </ul>
                                <div class="wr-walletsystem">
                                    <div class="wr-form-checkbox">
                                        <input class="form-check-input wr-form-check-input-sm" type="checkbox"
                                            id="wr_wallet_option" name="wallet" <?php echo esc_attr($wallet_checked);?>>
                                        <label class="wr-additionolinfo" for="wr_wallet_option">
                                            <span><?php esc_html_e('Use my wallet credit', 'workreap'); ?></span>
                                            <em><?php esc_html_e('Wallet credit will be used during the checkout process', 'workreap'); ?></em>
                                        </label>
                                        <span class="wr-walletamount">
                                            <span>( <?php workreap_price_format($user_balance); ?>)</span>
                                        </span>
                                    </div>
                                    <div class="wr-btnwalletfund">
                                        <a href="javascript:void(0);" class="wr-btn-solid-lg wr-btnsfund" data-bs-toggle="modal" data-bs-target="#tbcreditwallet">
                                            <?php esc_html_e('Add funds to wallet', 'workreap'); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="wr-btnwallet">
                                    <a href="javascript:void(0);" class="wr-btn-solid-lg-lefticon" data-id="<?php echo intval($product_id); ?>" id="wr_btn_cart">
                                        <i class="wr-icon-lock"></i>
                                        <?php esc_html_e('Proceed to secure checkout', 'workreap'); ?>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </aside>
                </div>
            </div>
        </div>
    </div>
<?php }
