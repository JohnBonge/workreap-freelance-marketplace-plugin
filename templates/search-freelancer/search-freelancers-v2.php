<?php
/**
 * Template part for displaying freelancer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */
global $grid_arg,$current_user,$workreap_settings;

$hide_filter_type               = !empty($workreap_settings['hide_freelancer_filter_type']) ? $workreap_settings['hide_freelancer_filter_type'] : false;
$hide_filter_location           = !empty($workreap_settings['hide_freelancer_filter_location']) ? $workreap_settings['hide_freelancer_filter_location'] : false;
$hide_filter_skills             = !empty($workreap_settings['hide_freelancer_filter_skills']) ? $workreap_settings['hide_freelancer_filter_skills'] : false;
$hide_filter_level              = !empty($workreap_settings['hide_freelancer_filter_level']) ? $workreap_settings['hide_freelancer_filter_level'] : false;
$hide_filter_languages          = !empty($workreap_settings['hide_freelancer_filter_language']) ? $workreap_settings['hide_freelancer_filter_language'] : false;
$hide_filter_price              = !empty($workreap_settings['hide_freelancer_filter_price']) ? $workreap_settings['hide_freelancer_filter_price'] : false;

$max_search_price   = !empty($workreap_settings['max_search_price']) ? $workreap_settings['max_search_price'] : 5000;
$min_search_price   = !empty($workreap_settings['min_search_price']) ? $workreap_settings['min_search_price'] : 0;
$freelancer_data        = !empty($grid_arg['freelancer_data']) ? $grid_arg['freelancer_data'] : array();
$sorting            = !empty($grid_arg['sorting']) ? $grid_arg['sorting'] : '';
$per_page           = !empty($grid_arg['per_page']) ? $grid_arg['per_page'] : '';
$search_keyword     = !empty($grid_arg['search_keyword']) ? $grid_arg['search_keyword'] : '';
$total_posts        = !empty($grid_arg['total_posts']) ? $grid_arg['total_posts'] : 0;
$page_object_id     = !empty($grid_arg['page_object_id']) ? $grid_arg['page_object_id'] : 0;
$freelancer_type    = !empty($grid_arg['freelancer_type'][0]) ? $grid_arg['freelancer_type'][0] : '';
$current_page_url   = !empty($grid_arg['current_page_url']) ? $grid_arg['current_page_url'] : '';
$hourly_rate_start  = !empty($grid_arg['hourly_rate_start']) ? $grid_arg['hourly_rate_start'] : $min_search_price;
$hourly_rate_end    = !empty($grid_arg['hourly_rate_end']) ? $grid_arg['hourly_rate_end'] : $max_search_price;
$english_level      = !empty($grid_arg['english_level']) ? $grid_arg['english_level'] : '';
$skills             = !empty($grid_arg['skills']) ? $grid_arg['skills'] : '';
$languages          = !empty($grid_arg['languages']) ? $grid_arg['languages'] : '';
$freelancer_location    = !empty($grid_arg['freelancer_location']) ? $grid_arg['freelancer_location'] : '';
$flag               = rand(99, 9999);
$freelancer_type_array  = array();
$freelancer_type_data   = workreap_get_term_dropdown('freelancer_type', false, 0, false);
$selected_freelancer_type   = '';

if( !empty($freelancer_type_data) ){
    foreach ($freelancer_type_data as $value) {
        if( !empty($value->slug) ){
            $freelancer_type_array[$value->slug]    = $value->name;
        }
    }
}

?>
<div class="wr-main-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form  id="wr_sort_form" class="wr-formsearch wr-formsearchvtwo">
                    <fieldset>
                        <div class="wr-taskform ">
                            <div class="wr-inputicon">
                                <i class="wr-icon-search"></i>
                                <input type="hidden" name="sort_by" id="wr_sort_by_filter" value="<?php echo esc_attr($sorting); ?>">
                                <?php do_action('workreap_keyword_search',$search_keyword); ?>
                            </div>
                            <?php if(!empty($hide_filter_type)){ ?>
                            <div class="wr-select wr-inputicon wr-select">
                                <i class="wr-icon-layers"></i>
                                <?php do_action( 'workreap_custom_dropdown_html', $freelancer_type_array,'freelancer_type[]','freelancer_type',$freelancer_type,esc_attr__('Select freelancer type','workreap'));?>
                            </div>
                            <?php } ?>
                            <div class="wr-inputappend_right">
                                <button class="wr-btn-solid-lg wr-btn-<?php echo esc_attr($flag);?>"><?php esc_html_e('Search now','workreap');?></button>
	                            <?php if(!empty($hide_filter_price) || !empty($hide_filter_location) || !empty($hide_filter_level) || !empty($hide_filter_skills) || !empty($hide_filter_languages)){ ?>
                                <a data-bs-toggle="collapse" href="#collapse-search" role="button" aria-expanded="false" aria-controls="collapse-search" class="wr-advancebtn wr-btn-solid-lg">
                                    <span class="wr-icon-sliders"></span>
                                    <?php esc_html_e('Advanced search','workreap');?>
                                    <span class = "wr-icon-chevron-right"></span>
                                </a>
	                            <?php } ?>
                            </div>
                        </div>
                    </fieldset>
                    <div id="collapse-search" class="collapse wr-advancesearch">
                        <div class="wr-searchbar">
                            <div class="form-group-wrap">
                                <?php if( !empty($hide_filter_price) ){ ?>
                                <div class="wr-pricerange form-group form-group-half">
                                    <?php do_action( 'workreap_render_price_filter_htmlv2', esc_html__('Hourly Rate','workreap'),$hourly_rate_start,$hourly_rate_end,$flag );?>
                                </div>
                                <?php } ?>
                                <?php if( !empty($hide_filter_location) ){ ?>
                                <div class="form-group form-group-half">
                                    <h6><?php esc_html_e('Location','workreap');?></h6>
                                    <div class="wr-select">
                                        <?php do_action('workreap_country_dropdown', $freelancer_location,'location');?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <?php !empty($hide_filter_level) ? do_action( 'workreap_render_term_filter_htmlv2', $english_level,'english_level','name="english_level[]"',esc_html__('English level','workreap') ) : '';?>
	                        <?php !empty($hide_filter_skills) ? do_action( 'workreap_render_term_filter_htmlv2', $skills,'skills','name="skills[]"',esc_html__('Skills','workreap') ) : '';?>
	                        <?php !empty($hide_filter_languages) ? do_action( 'workreap_render_term_filter_htmlv2', $languages,'languages','name="languages[]"',esc_html__('Languages','workreap') ) : '';?>
                            <?php do_action('workreap_extra_filters');?>
                        </div>
                        <div class="wr-searchbar">
                            <div class="wr-btnarea">
                                <a href="<?php echo esc_url($current_page_url);?>" class="wr-advancebtn wr-btn-solid-lg"><?php esc_html_e('Clear all filters','workreap');?></a>
                                <button class="wr-btn-<?php echo esc_attr($flag);?> wr-btn-solid"><?php esc_html_e('Apply filters','workreap');?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="wr-sort">
                    <h3><?php echo sprintf(esc_html__('%s search result(s)','workreap'), $total_posts) ?></h3>
                    <?php do_action('workreap_get_project_price_sortby_filter_theme', $sorting); ?>
                </div>
            </div>
        </div>
        <div class="row gy-4 wr-searchtalentlist">
            <?php
                if ($freelancer_data->have_posts()) {
                    while ($freelancer_data->have_posts()) {
                        $freelancer_data->the_post();
                        $freelancer_id        = get_the_ID();
                        $freelancer_name      = workreap_get_username($freelancer_id);
                        $wr_post_meta     = get_post_meta($freelancer_id, 'wr_post_meta', true);
                        $freelancer_tagline   = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
                        ?>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="wr-freelanlist">
                                <div class="wr-topservicetask__content">
                                    <div class="wr-freeprostatus">
                                        <?php do_action('workreap_profile_image', $freelancer_id,'',array('width' => 600, 'height' => 600));?>
                                    </div>
                                    <div class="wr-title-wrapper">
                                        <div class="wr-author-info">
                                            <?php if( !empty($freelancer_name) ){?>
                                                <div class="wr-author-name">
                                                    <a href="<?php echo esc_url( get_permalink()); ?>"><?php echo esc_html($freelancer_name); ?></a>
	                                                <?php do_action( 'workreap_verification_tag_html', $freelancer_id ); ?>
                                                </div>
                                            <?php } ?>
	                                        <?php if(!empty($freelancer_tagline)){ ?>
                                                <h5 class="wr-tag-line"><?php echo esc_html($freelancer_tagline); ?></h5>
	                                        <?php } ?>
                                            <ul class="wr-blogviewdates wr-blogviewdatesmd">
		                                        <?php do_action('workreap_get_freelancer_rating_count', $freelancer_id); ?>
                                            </ul>
                                        </div>
                                        <ul class="wr-view-wrapper">
	                                        <?php do_action('workreap_get_freelancer_views', $freelancer_id); ?>
                                        </ul>
                                    </div>
	                                <?php do_action('workreap_show_badges', $freelancer_id);
                                    $wr_hourly_rate = get_post_meta($freelancer_id, 'wr_hourly_rate', true);
	                                if (!empty($wr_hourly_rate) || !empty($display_button)) { ?>
                                        <div class="wr-startingprice">
                                            <span class="wr-startingprice-title">
                                                <i class="wr-icon-credit-card" aria-hidden="true"></i>
                                                <?php echo esc_html__('Hourly Rate','workreap') ?>
                                            </span>
                                            <span><?php echo sprintf(esc_html__('%s /hr', 'workreap'), workreap_price_format($wr_hourly_rate, 'return')); ?></span>
                                        </div>
	                                <?php }
	                                $address  = apply_filters( 'workreap_user_address', $freelancer_id );
	                                if( !empty($address) ){ ?>
                                        <div class="wr-address-view">
                                            <span class="wr-address-title">
                                                <i class="wr-icon-map-pin" aria-hidden="true"></i>
                                                <?php echo esc_html__('Location','workreap') ?>
                                            </span>
                                            <span class="wr-address"><?php echo esc_html($address); ?></span>
                                        </div>
	                                <?php }
	                                do_action( 'workreap_term_tags', $freelancer_id, 'skills', '', 4 );
	                                ?>
                                    <div class="wr-btnviewpro">
                                        <a href="<?php echo esc_url( get_permalink()); ?>" class="wr-btn-solid-lg"><?php esc_html_e('View profile','workreap');?></a>
                                        <?php do_action('workreap_save_freelancer_html', $current_user->ID, $freelancer_id, '_saved_freelancers', 'v2', 'freelancers'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else {?>
                    <div class="col-lg-12">
                        <?php do_action( 'workreap_empty_listing', esc_html__('Oops!! Record not found', 'workreap') );?>
                    </div>
                <?php }
                if($total_posts > $per_page){
                    workreap_paginate($freelancer_data);
                }
            ?>
        </div>
    </div>
</div>

<?php
$scripts	= "
jQuery(function () {
    jQuery(document).on('click','.wr-btn-".esc_js($flag)."',function(e){
        jQuery('#wr_sort_form').submit();
    });
    // range slider
    var stepsSlider = document.getElementById('wr-rangeslider-" . esc_js($flag) . "');
    if(stepsSlider !== null){
        var input0 = document.getElementById('wr-min-value-" . esc_js($flag) . "');
        var input1 = document.getElementById('wr-max-value-" . esc_js($flag) . "');
        var inputs = [input0, input1];
        noUiSlider.create(stepsSlider, {
            start: [" . esc_js($hourly_rate_start) . ", " . esc_js($hourly_rate_end) . "],
            connect: true,
            range: {
            'min': 1,
            'max': 600
        },
            format: {
            to: (v) => parseFloat(v).toFixed(0),
            from: (v) => parseFloat(v).toFixed(0),
            suffix: ' (US $)'
        },
        });

        stepsSlider.noUiSlider.on('update', function (values, handle) {
            inputs[handle].value = values[handle];
        });

        // Listen to keydown events on the input field.
        inputs.forEach(function (input, handle) {
        input.addEventListener('change', function () {
            stepsSlider.noUiSlider.setHandle(handle, this.value);
        });
        input.addEventListener('keydown', function (e) {
            var values = stepsSlider.noUiSlider.get();
            var value = Number(values[handle]);
            var steps = stepsSlider.noUiSlider.steps();
            var step = steps[handle];
            var position;
            switch (e.which) {
            case 13:
                stepsSlider.noUiSlider.setHandle(handle, this.value);
                break;
            case 38:
                position = step[1];
                // false = no step is set
                if (position === false) {
                    position = 1;
                }
                if (position !== null) {
                    stepsSlider.noUiSlider.setHandle(handle, value + position);
                }
                break;
            case 40:
                position = step[0];
                if (position === false) {
                    position = 1;
                }
                if (position !== null) {
                    stepsSlider.noUiSlider.setHandle(handle, value - position);
                }
                break;
            }
        });
        });
    }
});";
wp_add_inline_script('workreap-callbacks', $scripts, 'after');