<?php
/**
 *  Task introduction
 *
 * @package     Workreap
 * @subpackage  Workreap/templates/post_services
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $workreap_settings,$current_user;
if ( !class_exists('WooCommerce') ) {
	return;
}

$hide_product_cat   = !empty($workreap_settings['hide_product_cat']) ? $workreap_settings['hide_product_cat'] : array();

$countries  = array();

$states				    = array();
$state				    = get_post_meta( $post_id, 'state',true );
$enable_state		    = !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
$state_country_class	= !empty($enable_state) && empty($billing_country) ? 'd-none' : '';
if (class_exists('WooCommerce')) {
	$countries_obj   	= new WC_Countries();
	$countries   		= $countries_obj->get_allowed_countries('countries');
    $country            = !empty($billing_country) ? $billing_country : '';
    if( empty($country) && is_array($countries) && count($countries) === 1 ){
        $country                = array_key_first($countries);
        $billing_country        = $country;
        $state_country_class    = '';
    }
	$states			 	= $countries_obj->get_states( $country );
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
$content_id     = rand();
$title_id       = rand();
$country_class = "form-group-half";
$enable_ai      = !empty($workreap_settings['enable_ai']) && !empty($workreap_settings['enable_ai_service']) ? true : false;
$allow_tags      = !empty($workreap_settings['allow_tags']) ? $workreap_settings['allow_tags'] : false;
$ai_classs      = !empty($enable_ai) ? 'wr-input-ai' : '';
?>
<div id="service-introduction-wrapper">
    <form id="service-introduction-form" class="wr-themeform" action="<?php echo esc_url($current_page_url);?>" method="post" novalidate enctype="multipart/form-data">
        <fieldset>
            <?php do_action('workreap_service_before_title', $args); ?>
            <div class="form-group form-group_vertical <?php echo esc_attr($ai_classs);?>">
                <label class="form-group-title"><?php esc_html_e('Add task title:', 'workreap'); ?><sup class="asterik_red">*</sup></label>
                <?php
                    if(!empty($enable_ai)){
                        do_action( 'workreapAIContent', 'service_title-'.$title_id,'service_title' );
                    }
                ?>
                <input type="text" data-ai_content_id="service_title-<?php echo esc_attr($title_id);?>" value="<?php echo esc_attr($service_title); ?>" class="form-control" name="workreap_service[post_title]" placeholder="<?php esc_attr_e('Enter your task title', 'workreap'); ?>" autocomplete="off" required="required">

            </div>
            <div class="form-group form-group-3half form-group_vertical" id="wr-task-category-level1">
                <label class="form-group-title"><?php esc_html_e('Choose category:', 'workreap'); ?><sup class="asterik_red">*</sup></label>
                <span class="wr-select">
                    <?php
						$workreap_args = array(
							'show_option_none'  => esc_html__('Choose category', 'workreap'),
							'option_none_value' => '',
							'show_count'    => false,
							'hide_empty'    => false,
							'name'          => 'workreap_service[category]',
							'class'         => 'service-dropdwon wr-top-service',
							'taxonomy'      => 'product_cat',
							'id'            => 'wr-top-service',
							'value_field'   => 'term_id',
							'orderby'       => 'name',
							'selected'      => $service_cat,
							'hide_if_empty' => false,
							'echo'          => true,
							'required'      => false,
							'parent'        => 0,

						);
                        if( !empty($hide_product_cat) ){
                            $workreap_args['exclude']    = $hide_product_cat;
                        }
						do_action('workreap_taxonomy_dropdown', $workreap_args);
                    ?>
                </span>
            </div>

            <div class="form-group form-group-3half form-group_vertical" id="wr_sub_category">
                <?php if (!empty($sub_cat)) { do_action('workreap_get_terms', $service_cat, $sub_cat); } ?>
            </div>
            <div class="form-group form-group-3half form-group_vertical" id="wr_category_level3" data-type="task">
                <?php if (!empty($sub_cat2)) {do_action('workreap_get_terms_subcategories', $sub_cat, $sub_cat2);} ?>
            </div>
            <div class="<?php echo esc_attr($country_class);?> form-group_vertical">
                <label class="form-group-title"><?php esc_html_e('Country', 'workreap'); ?>:<sup class="asterik_red">*</sup></label>
                <span class="wr-select">
                    <select id="wr_country" name="workreap_service[locations]" data-placeholderinput="<?php esc_attr_e('Search country', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose country', 'workreap'); ?>" >
                        <option value="" selected hidden disabled><?php esc_html_e('Choose country', 'workreap'); ?></option>
                        <?php if (!empty($countries)) {
                            foreach ($countries as $key => $item) {
                                $selected = '';

                                if (!empty($billing_country) && $billing_country === $key) {
                                    $selected = 'selected';
                                } ?>
                            <option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
                            <?php }
                        } ?>
                    </select>
                </span>
            </div>
            <?php if( !empty($enable_state) ){?>
                <div class="form-group-half form-group_vertical wr-state-parent <?php echo esc_attr($state_country_class);?>">
                    <label class="form-group-title"><?php esc_html_e('States', 'workreap'); ?><sup class="asterik_red">*</sup></label>
                    <span class="wr-select wr-select-country">
                        <select class="wr-country-state" name="workreap_service[state]" data-placeholderinput="<?php esc_attr_e('Search states', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose states', 'workreap'); ?>">
                            <option selected hidden disabled value=""><?php esc_html_e('States', 'workreap'); ?></option>
                            <?php if (!empty($states)) {
                                foreach ($states as $key => $item) {
                                    $selected = '';
                                    if (!empty($state) && $state === $key) {
                                        $selected = 'selected';
                                    } ?>
                                    <option class="wr-state-option" <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
                            <?php }
                            } ?>
                        </select>
                    </span>
                </div>
            <?php } ?>
            <?php if(!empty($workreap_settings['enable_zipcode']) ){?>
                <div class="form-group-half form-group_vertical">
                    <label class="form-group-title"><?php esc_html_e('Zipcode', 'workreap'); ?>:<sup class="asterik_red">*</sup></label>
                    <input type="text" class="form-control" name="zipcode" placeholder="<?php esc_attr_e('Enter zipcode', 'workreap'); ?>" autocomplete="off" value="<?php echo esc_attr($zipcode);?>">
                </div>
            <?php } ?>
            <div class="form-group form-group_vertical <?php echo esc_attr($ai_classs);?>">
                    <label class="form-group-title"><?php esc_html_e('Task introduction', 'workreap'); ?><sup class="asterik_red">*</sup></label>
                    <?php
                        if(!empty($enable_ai)){
                            do_action( 'workreapAIContent', 'service_content-'.$content_id,'service_content' );
                        }
                        $editor_content   = do_shortcode($service_content);
                        $editor_id = 'service_content-' . $content_id;
                        $editor_settings = array(
                            'media_buttons' => false,
                            'textarea_name' => 'workreap_service[post_content]',
                            'textarea_rows' => get_option('default_post_edit_rows', 10),
                            'quicktags' => false,
                        );
                        wp_editor( $editor_content, $editor_id, $editor_settings );
                    ?>
            </div>
            <?php if(!empty($allow_tags)){ ?>
            <div class="form-group form-group_vertical">
                <label class="form-group-title"><?php esc_html_e('Tags', 'workreap'); ?>:<sup class="asterik_red">*</sup></label>
                <input name="workreap_service[product_tag][]" value='<?php echo esc_attr($service_tag); ?>' placeholder="<?php esc_attr_e('Add tags', 'workreap'); ?>" class="form-control wr-tagscroll">
            </div>
            <?php } ?>
            <?php do_action('workreap_service_step_1_fields', $args); ?>
            <div class="form-group wr-postservicebtn">
                <div class="wr-savebtn">
                    <span><?php esc_html_e('Click “Save & Continue” to add latest changes made by you', 'workreap'); ?></span>
                    <button type="submit" class="wr-btn"><?php esc_html_e('Save & Continue', 'workreap'); ?></button>
                    <input type="hidden" id="service_id" name="post_id" value="<?php echo intval($post_id); ?>">
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
$script = "
    jQuery(document).on('ready', function(){
        if ( $.isFunction($.fn.select2) ) {
            jQuery('.wr-service-select2').select2({
                theme: 'default wr-select2-dropdown',
                multiple: true,
                placeholder: scripts_vars.service_type,
                language: {
                    noResults: function(){
                        return scripts_vars.select_not_found
                    },
                    searching: function(){
                        return scripts_vars.select_searching
                    },
                }
            });
        }
        let input = document.querySelector('.wr-tagscroll');
		if (input !== null) {
			new Tagify(input, {
                enforceWhitelist : scripts_vars.allow_tags,
                whitelist: ".json_encode($product_term_array).",
                maxTags: 20,
                dropdown: {
                    maxItems: 1000,
                    classname: 'tags-look',
                    enabled: 0,
                    
                    closeOnSelect: true
                }
            });
		}
    });
";
wp_add_inline_script( 'workreap', $script, 'after' );
