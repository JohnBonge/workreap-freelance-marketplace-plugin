<?php
/**
 * Template part for displaying freelancer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */
global $grid_arg,$current_user,$workreap_settings;

$hide_task_filter_price         = !empty($workreap_settings['hide_task_filter_price']) ? $workreap_settings['hide_task_filter_price'] : '';
$hide_task_filter_location      = !empty($workreap_settings['hide_task_filter_location']) ? $workreap_settings['hide_task_filter_location'] : '';
$hide_task_filter_categories    = !empty($workreap_settings['hide_task_filter_categories']) ? $workreap_settings['hide_task_filter_categories'] : '';
$hide_task_filter_tags          = !empty($workreap_settings['hide_task_filter_tags']) ? $workreap_settings['hide_task_filter_tags'] : '';

$sort_by                    = !empty($grid_arg['sort_by']) ? $grid_arg['sort_by'] : '';
$show_posts                 = !empty($grid_arg['show_posts']) ? $grid_arg['show_posts'] : '';
$workreap_query              = !empty($grid_arg['workreap_query']) ? $grid_arg['workreap_query'] : array();
$result_count               = !empty($grid_arg['result_count']) ? $grid_arg['result_count'] : '';
$search_task_page           = !empty($grid_arg['search_task_page']) ? $grid_arg['search_task_page'] : '';
$hide_product_cat           = !empty($grid_arg['hide_product_cat']) ? $grid_arg['hide_product_cat'] : '';
$task_listing_type          = !empty($grid_arg['task_listing_type']) ? $grid_arg['task_listing_type'] : '';
$keyword                    = !empty($grid_arg['keyword']) ? $grid_arg['keyword'] : '';
$location                   = !empty($grid_arg['location']) ? $grid_arg['location'] : '';
$category                   = !empty($grid_arg['category']) ? $grid_arg['category'] : '';
$product_tag                = !empty($grid_arg['tags']) ? $grid_arg['tags'] : array();
$sub_category               = !empty($grid_arg['sub_category']) ? $grid_arg['sub_category'] : '';
$service_array              = !empty($grid_arg['service_array']) ? $grid_arg['service_array'] : '';
$min_product_price          = !empty($grid_arg['min_product_price']) ? $grid_arg['min_product_price'] : 0;
$max_product_price          = !empty($grid_arg['max_product_price']) ? $grid_arg['max_product_price'] : 5000;
$flag                       = rand(99, 9999);
?>
<div class="wr-main-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form class="wr-formsearch wr-formsearchvtwo" id="wr_sort_form">
                    <fieldset>
                        <div class="wr-taskform">
                            <div class="wr-inputicon">
                                <i class="wr-icon-search"></i>
                                <input type="hidden" name="sort_by" id="wr_sort_by_filter" value="<?php echo esc_attr($sort_by); ?>">
                                <?php do_action('workreap_keyword_search',$keyword); ?>
                            </div>
                            <?php if(!empty($hide_task_filter_categories)){ ?>
                            <div class="wr-select wr-inputicon">
                                <i class="wr-icon-layers"></i>
                                <?php
                                    $workreap_args = array(
                                        'show_option_none'  => esc_html__('Select category', 'workreap'),
                                        'show_count'        => false,
                                        'hide_empty'        => false,
                                        'name'              => 'category',
                                        'class'             => 'form-control wr-top-service-task-option',
                                        'taxonomy'          => 'product_cat',
                                        'id'                => 'task_category',
                                        'value_field'       => 'slug',
                                        'orderby'           => 'name',
                                        'selected'          => $category,
                                        'hide_if_empty'     => false,
                                        'echo'              => true,
                                        'required'          => false,
                                        'parent'            => 0,
                                    );
                                    if( !empty($hide_product_cat) ){
                                        $workreap_args['exclude']    = $hide_product_cat;
                                    }
                                    do_action('workreap_task_search_taxonomy_dropdown', $workreap_args);
                                ?>
                            </div>
                            <?php } ?>
                            <div class="wr-inputappend_right">
                                <button class="d-flex wr-btn-solid-lg wr-btn-<?php echo intval($flag);?>"><?php esc_html_e('Search now','workreap');?></button>
                                <?php if(!empty($hide_task_filter_price) || !empty($hide_task_filter_categories) || !empty($hide_task_filter_location)) { ?>
                                <a data-bs-toggle="collapse" href="#wr-search-tags" role="button" aria-expanded="false" aria-controls="wr-search-tags" class="wr-advancebtn wr-btn-solid-lg">
                                    <span class="wr-icon-sliders"></span>
                                    <?php esc_html_e('Advanced search','workreap');?>
                                    <span class = "wr-icon-chevron-right"></span>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>
                    <div id="wr-search-tags" class="collapse wr-advancesearch">
                        <div class="wr-searchbar">
                            <div class="form-group-wrap">
                                <?php if(!empty($hide_task_filter_price)){ ?>
                                <div class="wr-pricerange form-group form-group-half">
                                    <?php do_action( 'workreap_render_price_filter_htmlv2', esc_html__('Price range','workreap'),$min_product_price,$max_product_price,$flag );?>
                                </div>
                                <?php } ?>
	                            <?php if(!empty($hide_task_filter_location)){ ?>
                                <div class="form-group form-group-half">
                                    <h6><?php esc_html_e('Location','workreap');?></h6>
                                    <div class="wr-select">
                                        <?php do_action('workreap_country_dropdown', $location,'location');?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <?php if(!empty($hide_task_filter_categories)){ ?>
                            <div class="form-group-wrap-two">
                                <div class="wr-advancecheck wr-make-fullwidth">
                                    <div class="wr-filterselect" id="task_search_wr_sub_category">
                                        <?php
                                            if (!empty($category)) {
                                                do_action('workreap_task_search_get_terms', $category, $sub_category, 'wr-select','title');
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="wr-advancecheck wr-make-fullwidth">
                                    <div class="wr-filterselect" id="task_search_wr_category_level3">
                                        <?php
                                            if (!empty($sub_category)) {
                                                do_action('workreap_task_search_get_terms_subcategories', $sub_category, $service_array,'title');
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php }
                            if(!empty($hide_task_filter_tags)){ ?>
                            <div class="form-group-wrap">
                            <?php  do_action( 'workreap_render_term_filter_htmlv2', $product_tag,'product_tag','name="product_tag[]"',esc_html__('Tags','workreap') ); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="wr-searchbar">
                            <div class="wr-btnarea">
                                <a href="<?php echo esc_url($search_task_page);?>" class="wr-advancebtn wr-btn-solid-lg"><?php esc_html_e('Clear all filters','workreap');?></a>
                                <button class="d-flex wr-btn-solid-lg wr-btn-<?php echo intval($flag);?>"><?php esc_html_e('Apply filters','workreap');?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="wr-sort">
                    <?php if ($keyword){ ?>
                        <h3><?php echo sprintf( esc_html__('%d search result(s) "%s" found','workreap'), $result_count,$keyword); ?></h3>
                    <?php } else { ?>
                        <h3><?php echo sprintf(esc_html__('%d search result(s) found','workreap'), $result_count); ?></h3>
                    <?php } ?>
                    <?php do_action('workreap_price_sortby_filter_theme', $sort_by); ?>
                </div>
            </div>
        </div>
        <div class="row gy-4 wr-tasks-list<?php echo esc_attr($task_listing_type);?>">
            <?php
                if ( $workreap_query->have_posts() ) :?>
                        <?php while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                            global $post;
                            ?>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                <?php
                                    if( !empty($task_listing_type) && $task_listing_type === 'v2'){
                                        do_action( 'workreap_listing_task_html_v2', $post->ID );
                                    } else {
                                        do_action( 'workreap_listing_task_html_v1', $post->ID );
                                    }
                                ?>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php if( !empty($result_count) && $result_count > $show_posts ): ?>
                            <div class="col-sm-12">
                                <?php workreap_paginate($workreap_query); ?>
                            </div>
                        <?php
                        endif;
                    else:?>
                    <div class="col-lg-12">
                        <?php do_action( 'workreap_empty_listing', esc_html__('Oops!! Record not found', 'workreap') );?>
                    </div>
                <?php 
                endif;
                wp_reset_postdata();
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
            start: [" . esc_js($min_product_price) . ", " . esc_js($max_product_price) . "],
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