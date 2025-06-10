<?php
/**
 * Template part for displaying projects content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package workreap
 */
global $grid_arg,$current_user,$workreap_settings;

$hide_filter_type               = !empty($workreap_settings['hide_project_filter_type']) ? $workreap_settings['hide_project_filter_type'] : false;
$hide_filter_location           = !empty($workreap_settings['hide_project_filter_location']) ? $workreap_settings['hide_project_filter_location'] : false;
$hide_filter_skills             = !empty($workreap_settings['hide_project_filter_skills']) ? $workreap_settings['hide_project_filter_skills'] : false;
$hide_filter_level              = !empty($workreap_settings['hide_project_filter_level']) ? $workreap_settings['hide_project_filter_level'] : false;
$hide_filter_languages          = !empty($workreap_settings['hide_project_filter_language']) ? $workreap_settings['hide_project_filter_language'] : false;
$hide_filter_price              = !empty($workreap_settings['hide_project_filter_price']) ? $workreap_settings['hide_project_filter_price'] : false;
$hide_filter_categories         = !empty($workreap_settings['hide_project_filter_categories']) ? $workreap_settings['hide_project_filter_categories'] : false;
$project_multilevel_cat         = !empty($workreap_settings['project_multilevel_cat']) ? $workreap_settings['project_multilevel_cat'] : 'disable';

$sort_by                    = !empty($grid_arg['sort_by']) ? $grid_arg['sort_by'] : '';
$show_posts                 = !empty($grid_arg['show_posts']) ? $grid_arg['show_posts'] : '';
$workreap_query              = !empty($grid_arg['workreap_query']) ? $grid_arg['workreap_query'] : array();
$result_count               = !empty($grid_arg['result_count']) ? $grid_arg['result_count'] : '';
$search_project_page        = !empty($grid_arg['search_project_page']) ? $grid_arg['search_project_page'] : '';
$hide_product_cat           = !empty($grid_arg['hide_product_cat']) ? $grid_arg['hide_product_cat'] : '';
$keyword                    = !empty($grid_arg['keyword']) ? $grid_arg['keyword'] : '';
$location                   = !empty($grid_arg['location']) ? $grid_arg['location'] : '';
$category                   = !empty($grid_arg['category']) ? $grid_arg['category'] : '';
$skills                     = !empty($grid_arg['skills']) ? $grid_arg['skills'] : array();
$expertise_level            = !empty($grid_arg['expertise_level']) ? $grid_arg['expertise_level'] : array();
$languages                  = !empty($grid_arg['languages']) ? $grid_arg['languages'] : array();
$min_product_price          = !empty($grid_arg['min_product_price']) ? $grid_arg['min_product_price'] : 0;
$max_product_price          = !empty($grid_arg['max_product_price']) ? $grid_arg['max_product_price'] : 5000;
$flag                       = rand(99, 9999);
$project_types_array    = array();
$project_types          = workreap_project_type();
$selected_type          = !empty($_GET['project_type']) ? $_GET['project_type'] : 'all';
if( !empty($project_types) ){
    $project_types_array['all'] = esc_html__('All','workreap-hourly-addon');
    foreach( $project_types as $key => $val ){
        $project_types_array[$key]  = !empty($val['title']) ? $val['title'] : "";
    }
}
?>
<div class="wr-main-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form id="wr_sort_form" class="wr-formsearch wr-formsearchvtwo">
                    <fieldset>
                        <div class="wr-taskform">
                            <div class="wr-inputicon">
                                <i class="wr-icon-search"></i>
                                <input type="hidden" name="sort_by" id="wr_sort_by_filter" value="<?php echo esc_attr($sort_by); ?>">
                                <?php do_action('workreap_keyword_search',$keyword); ?>
                            </div>
                            <?php if(!empty($hide_filter_categories)) { ?>
                            <div class="wr-select wr-inputicon">
                                <i class="wr-icon-layers"></i>
                                <?php
                                $workreap_args = array(
                                    'show_option_none'  => esc_html__('Select category', 'workreap'),
                                    'show_count'        => false,
                                    'hide_empty'        => false,
                                    'name'              => 'category',
                                    'class'             => 'form-control',
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
                                <button class="d-flex wr-btn-solid-lg wr-btn-<?php echo esc_attr($flag);?>"><?php esc_html_e('Search now','workreap');?></button>
                                <?php if(!empty($hide_filter_skills) || !empty($hide_filter_level) || !empty($hide_filter_languages) || !empty($hide_filter_type) || !empty($hide_filter_location) || !empty($hide_filter_location) || !empty($hide_filter_price) ){ ?>
                                <a data-bs-toggle="collapse" href="#collapse-project-search" role="button" aria-expanded="false" aria-controls="collapse-project-search" class="wr-advancebtn wr-btn-solid-lg">
                                    <span class="wr-icon-sliders"></span>
                                    <?php esc_html_e('Advanced search','workreap');?>
                                    <span class = "wr-icon-chevron-right"></span>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>
                    <div id="collapse-project-search" class="collapse wr-advancesearch">
                        <div class="wr-searchbar">
                            <?php if(!empty($hide_filter_skills)){ ?>
                            <div class="wr-advancecheck">
                                <?php do_action( 'workreap_render_term_filter_htmlv2', $skills,'skills','name="skills[]"',esc_html__('Skills','workreap') );?>
                            </div>
                            <?php } ?>
                            <?php if(!empty($hide_filter_level)){ ?>
                            <div class="wr-advancecheck">
                                <?php do_action( 'workreap_render_term_filter_htmlv2', $expertise_level,'expertise_level','name="expertise_level[]"',esc_html__('Expertise level','workreap') );?>
                            </div>
                            <?php } ?>
	                        <?php if(!empty($hide_filter_languages)){ ?>
                            <div class="wr-advancecheck">
                                <?php do_action( 'workreap_render_term_filter_htmlv2', $languages,'languages','name="languages[]"',esc_html__('Languages','workreap') );?>
                            </div>
                            <?php } ?>
                            <div class="form-group-wrap">
                                <?php if(!empty($hide_filter_type)){ ?>
                                <div class="form-group form-group-3half">
                                    <h6><?php esc_html_e('Project type','workreap');?></h6>
                                    <div class="wr-select">
                                        <?php do_action( 'workreap_custom_dropdown_html', $project_types_array,'project_type','wr-project-type',$selected_type );?>
                                    </div>
                                </div>
                                <?php } ?>
	                            <?php if(!empty($hide_filter_location)){ ?>
                                <div class="form-group form-group-3half">
                                    <h6><?php esc_html_e('Location','workreap');?></h6>
                                    <div class="wr-select">
                                        <?php do_action('workreap_country_dropdown', $location,'location');?>
                                    </div>
                                </div>
                                <?php } ?>
	                            <?php if(!empty($hide_filter_price)){ ?>
                                <div class="wr-pricerange form-group form-group-3half">
                                    <?php do_action( 'workreap_render_price_filter_htmlv2', esc_html__('Price range','workreap'),$min_product_price,$max_product_price,$flag );?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="wr-searchbar">
                            <div class="wr-btnarea">
                                <a href="<?php echo esc_url($search_project_page);?>" class="wr-advancebtn wr-btn-solid-lg"><?php esc_html_e('Clear all filters','workreap');?></a>
                                <button class="d-flex wr-btn-solid-lg wr-btn-<?php echo esc_attr($flag);?>"><?php esc_html_e('Apply filters','workreap');?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="wr-sort">
                    <?php if ($keyword){ ?>
                        <h3><?php echo sprintf( esc_html__('%d search result(s) "%s" found','workreap'), $result_count,$keyword);?></h3>
                    <?php } else { ?>
                        <h3><?php echo sprintf(esc_html__('%d search result(s) found','workreap'), $result_count);?></h3>
                    <?php } ?>
                    <div class="wr-sortby">
                        <?php do_action('workreap_get_project_price_sortby_filter_theme', $sort_by); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 wr-searchprojectlist">
            <?php
                if ( $workreap_query->have_posts() ) :
                    while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                        $product            = wc_get_product();
                        ?>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <?php do_action( 'workreap_project_grid_view_style_2', $product );?>
                        </div>
                        <?php
                    endwhile;
                    if( !empty($result_count) && $result_count > $show_posts ):
                        ?>
                        <div class="col-sm-12">
                            <?php workreap_paginate($workreap_query); ?>
                        </div>
                    <?php
                    endif;
                else:?>
                    <div class="col-lg-12">
                        <?php do_action( 'workreap_empty_listing', esc_html__('No projects found', 'workreap') );?>
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
    jQuery('.wr-project-type').select2({
        theme: 'default wr-select2-dropdown',
        minimumResultsForSearch: Infinity,
        language: {
            noResults: function(){
                return scripts_vars.select_not_found
            },
            searching: function(){
                return scripts_vars.select_searching
            },
        }
    });
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
