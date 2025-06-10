<?php
/*
* Freelancer Search
*/
get_header();
global $paged, $current_user,$workreap_settings;

$hide_filter_type               = !empty($workreap_settings['hide_freelancer_filter_type']) ? $workreap_settings['hide_freelancer_filter_type'] : false;
$hide_filter_location           = !empty($workreap_settings['hide_freelancer_filter_location']) ? $workreap_settings['hide_freelancer_filter_location'] : false;
$hide_filter_skills             = !empty($workreap_settings['hide_freelancer_filter_skills']) ? $workreap_settings['hide_freelancer_filter_skills'] : false;
$hide_filter_level              = !empty($workreap_settings['hide_freelancer_filter_level']) ? $workreap_settings['hide_freelancer_filter_level'] : false;
$hide_filter_languages          = !empty($workreap_settings['hide_freelancer_filter_language']) ? $workreap_settings['hide_freelancer_filter_language'] : false;
$hide_filter_price              = !empty($workreap_settings['hide_freelancer_filter_price']) ? $workreap_settings['hide_freelancer_filter_price'] : false;
$hide_freelancer_without_avatar              = !empty($workreap_settings['hide_freelancer_without_avatar']) ? $workreap_settings['hide_freelancer_without_avatar'] : 'no';


$listing_type       = !empty($workreap_settings['freelancer_listing_type']) ? $workreap_settings['freelancer_listing_type'] : 'left';
$pg_page            = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged           = get_query_var('paged') ? get_query_var('paged') : 1; 
$tax_query_args     = $query_args = $meta_query_args = array();
$per_page           = get_option('posts_per_page');

$tax_queries            = array();

$search_keyword     = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$freelancer_type        = !empty($_GET['freelancer_type']) ? $_GET['freelancer_type'] : array();
$english_level      = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
$hourly_rate_start  = !empty( $_GET['min_price'] ) ? intval($_GET['min_price']) : 0;
$hourly_rate_end    = !empty( $_GET['max_price'] ) ? intval($_GET['max_price']) : 5000;
$freelancer_location    = !empty($_GET['location']) ? esc_html($_GET['location']) : '';
$state              = !empty($_GET['state']) ? esc_html($_GET['state']) : '';
$sorting            = !empty($_GET['sort_by']) ? esc_attr($_GET['sort_by']) : '';
$skills             = !empty($_GET['skills']) ? $_GET['skills'] : array();
$languages          = !empty($_GET['languages']) ? $_GET['languages'] : array();
if (class_exists('WooCommerce')) {
	$countries_obj   	= new WC_Countries();
	$countries   		= $countries_obj->get_allowed_countries('countries');
    if( is_array($countries) && count($countries) === 1 ){
        $country                = array_key_first($countries);
        $freelancer_location        = $country;
    }
}
$per_page           = !empty($per_page) ? $per_page : 10;

/* Freelancer type */
if ( is_array($freelancer_type) && !empty($freelancer_type) ) {
    $tax_query_args[] = array(
        'taxonomy' => 'freelancer_type',
        'field'    => 'slug',
        'terms'    => $freelancer_type,
        'operator' => 'IN',
    );
}

//skills
if ( !empty($skills[0]) && is_array($skills) ) {   
	$query_relation = array('relation' => 'OR',);
	$type_args  	= array();
	foreach( $skills as $key => $type ){
		$type_args[] = array(
			'taxonomy' => 'skills',
			'field'    => 'slug',
			'terms'    => esc_attr($type),
		);
	}

	$tax_query_args[] = array_merge($query_relation, $type_args);   
}

//Languages
if ( !empty($languages[0]) && is_array($languages) ) {   
	$query_relation = array('relation' => 'OR',);
	$lang_args  	= array();

	foreach( $languages as $key => $lang ){
		$lang_args[] = array(
				'taxonomy' => 'languages',
				'field'    => 'slug',
				'terms'    => esc_attr($lang),
			);
	}

	$tax_query_args[] = array_merge($query_relation, $lang_args);   
}

/* English Level */
if ( is_array($english_level) && !empty($english_level) ) {
    $tax_query_args[] = array(
        'taxonomy' => 'english_level',
        'field'    => 'slug',
        'terms'    => $english_level,
        'operator' => 'IN',
    );
}

/* Location */
if ( !empty($freelancer_location) ) {
    $meta_query_args[] = array(
        'key'       => 'country',
        'value'     => $freelancer_location,
        'compare'   => '=',
    );
    if( !empty($state) ){
        $countries_obj  = new WC_Countries();
        $countries      = $countries_obj->get_allowed_countries('countries');
        $states_list    = $countries_obj->get_states( $freelancer_location );
        if( !empty($states_list[$state]) ){
            $meta_query_args[] = array(
                'key'       => 'state',
                'value'     => $state,
                'compare'   => '=',
            );
        }
    }
}

/* Hourly Rate */
if ( !empty($hourly_rate_start) && !empty($hourly_rate_end) ) {
    $meta_query_args[] = array(
        'key'         => 'wr_hourly_rate',
        'value'       => array($hourly_rate_start, $hourly_rate_end),
        'compare'     => 'BETWEEN',
        'type'        => 'NUMERIC'
    );
}

$meta_query_args[] = array(
    'key'       => '_is_verified',
    'value'     => 'yes',
    'compare'   => '=',
);

if(!empty($hide_freelancer_without_avatar) && $hide_freelancer_without_avatar === 'yes'){
    $meta_query_args[] = array(
        'key'       => 'is_avatar',
        'value'     => 1,
        'compare'   => '=',
    );
}

$query_args = array(
    'posts_per_page'        => $per_page,
    'paged'                 => $paged,
    'post_type'             => 'freelancers',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1
);

// if keyword field is set in search then append its args in $query_args
if (!empty($search_keyword)){

    $filtered_args['keyword'] = array(
        's' => $search_keyword,
    );

    $query_args = array_merge($query_args,$filtered_args['keyword']);
}

//Meta Query
if (!empty($meta_query_args)) {
    $query_relation           = array('relation' => 'AND',);
    $meta_query_args          = array_merge($query_relation, $meta_query_args);
    $query_args['meta_query'] = $meta_query_args;
}

/* Taxonomy Query */
if (!empty($tax_query_args)) {
    $query_relation           = array('relation' => 'AND',);
    $tax_query_args           = array_merge($query_relation, $tax_query_args);
    $query_args['tax_query']  = $tax_query_args;
}


if (!empty($sorting)) {
    $filtered_args = array();
    // filter latest product
    if ($sorting == 'date_desc') {
        $filtered_args['sort_by'] = array(
            'orderby' 	=> 'date',
            'order' 	=> 'DESC',
        );
    } elseif ($sorting == 'price_desc') {
        $filtered_args['sort_by'] = array(
            'orderby' 	=> 'meta_value_num',
            'meta_key' 	=> 'wr_hourly_rate',
            'order' 	=> 'desc',
        );
    } elseif ($sorting == 'price_asc') {
        $filtered_args['sort_by'] = array(
            'orderby' 	=> 'meta_value_num',
            'meta_key' 	=> 'wr_hourly_rate',
            'order' 	=> 'asc',
        );
    } elseif ($sorting == 'views_desc') {
        $filtered_args['sort_by'] = array(
            'orderby' 	=> 'meta_value_num',
            'meta_key' 	=> 'workreap_profile_views',
            'order' 	=> 'desc',
        );
    }
	
    $query_args = array_merge($query_args, $filtered_args['sort_by']);
}

$freelancer_data = new WP_Query(apply_filters('workreap_freelancer_search_filter', $query_args));
$total_posts = $freelancer_data->found_posts;

$page_object_id     = get_queried_object_id();
$current_page_url   = get_permalink( $page_object_id );
if( !empty($listing_type) && $listing_type === 'top' ){
    $theme_version 	                = wp_get_theme();
    $grid_arg                       = array();
    $grid_arg['sorting']            = $sorting;
    $grid_arg['per_page']           = $per_page;
    $grid_arg['freelancer_type']        = $freelancer_type;
    $grid_arg['freelancer_data']        = $freelancer_data;
    $grid_arg['search_keyword']     = $search_keyword;
    $grid_arg['total_posts']        = $total_posts;
    $grid_arg['page_object_id']     = $page_object_id;
    $grid_arg['current_page_url']   = $current_page_url;
    $grid_arg['hourly_rate_start']  = $hourly_rate_start;
    $grid_arg['hourly_rate_end']    = $hourly_rate_end;
    $grid_arg['freelancer_location']    = $freelancer_location;
    $grid_arg['english_level']      = $english_level;
    $grid_arg['skills']      = $skills;
    if(!empty($theme_version->get( 'TextDomain' )) && ( $theme_version->get( 'TextDomain' ) === 'workreap' || $theme_version->get( 'TextDomain' ) === 'workreap-child' )){
        get_template_part( 'template-parts/find', 'freelancers', $grid_arg);
    }    
} else {
?>
<section class="wr-searchresult-section">
        <div class="container">
            <div class="wr-freelancersearch">
                <div class="row gy-4">
                    <div class="col-12">
                        <div class="wr-sort">
                            <h3><?php echo sprintf(esc_html__('%s search result(s)','workreap'), $total_posts) ?></h3>
							<?php do_action('workreap_get_project_price_sortby_filter_theme', $sorting); ?>
                            <div class="wr-filtermenu">
                                <a href="javascript:();" class="wr-filtericon"><i class="wr-icon-sliders"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xxl-3 wr-mt0">
                        <form id="wr_sort_form" class="wr-searchlist">
                            <input type="hidden" name="sort_by" id="wr_sort_by_filter" value="<?php echo esc_attr($sorting); ?>">
                            <aside class="wr-sidebar">
                                <div class="wr-aside-holder">
                                    <div class="wr-sidebartitle" data-bs-toggle="collapse" data-bs-target="#search" role="button" aria-expanded="true">
                                        <h5><?php esc_html_e('Narrow your search', 'workreap'); ?></h5>
                                    </div>
                                    <div class="wr-sidebarcontent collapse show" id="search">
										<?php do_action('workreap_keyword_search',$search_keyword); ?>
                                    </div>
                                </div>
								<?php
								if( !empty($hide_filter_type) ){ do_action('workreap_render_freelancer_type_filter_html',$freelancer_type); }
								if( !empty($hide_filter_level) ){ do_action('workreap_render_english_level_filter_html',$english_level);}
								if( !empty($hide_filter_location) ){ do_action('workreap_location_search_field', $freelancer_location);}
								if( !empty($hide_filter_skills) ){ do_action('workreap_skills_filter_theme', $skills);}
								if( !empty($hide_filter_languages) ){ do_action('workreap_languages_filter_theme', $languages); }
								if( !empty($hide_filter_price) ){ do_action('workreap_render_price_range_filter_html', esc_html__('Hourly rate','workreap'), $hourly_rate_start, $hourly_rate_end);}
								do_action('workreap_extra_filters_v2');
								do_action('workreap_search_clear_button_theme', esc_html__('Search now','workreap'), $current_page_url);
								?>
                            </aside>
                        </form>
                    </div>
                    <div class="col-lg-8 col-xxl-9">
						<?php if ($freelancer_data->have_posts()) {
							while ($freelancer_data->have_posts()) {
								$freelancer_data->the_post();
								$freelancer_id        = get_the_ID();
								$freelancer_name      = workreap_get_username($freelancer_id);
								$wr_post_meta     = get_post_meta($freelancer_id, 'wr_post_meta', true);
								$freelancer_tagline   = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
								$app_task_base      		    = workreap_application_access('task');
								$skills_base                    = 'project';
								if( !empty($app_task_base) ){
									$skills_base    = 'service';
								}
								?>
                                <div class="wr-bestservice">
                                    <div class="wr-bestservice__content wr-bestservicedetail">
                                        <div class="wr-bestservicedetail__user">
                                            <div class="wr-price-holder">
                                                <div class="wr-asideprostatus">
													<?php do_action('workreap_profile_image', $freelancer_id,'',array('width' => 600, 'height' => 600));?>
                                                    <div class="wr-bestservicedetail__title">
														<?php if( !empty($freelancer_name) ){?>
                                                            <h6>
                                                                <a href="<?php echo esc_url( get_permalink()); ?>"><?php echo esc_html($freelancer_name); ?></a>
																<?php do_action( 'workreap_verification_tag_html', $freelancer_id ); ?>
                                                            </h6>
														<?php } ?>
														<?php if( !empty($freelancer_tagline) ){?>
                                                            <h5><?php echo esc_html($freelancer_tagline); ?></h5>
														<?php } ?>
                                                        <ul class="wr-rateviews">
															<?php do_action('workreap_get_freelancer_rating_count', $freelancer_id); ?>
															<?php do_action('workreap_get_freelancer_views', $freelancer_id); ?>
															<?php do_action('workreap_save_freelancer_html', $current_user->ID, $freelancer_id, '_saved_freelancers', '', 'freelancers'); ?>
                                                        </ul>
                                                        <?php do_action('workreap_show_badges', $freelancer_id);?>
                                                    </div>
                                                </div>
												<?php do_action('workreap_user_hourly_starting_rate', $freelancer_id,'',true); ?>
                                            </div>
                                            <div class="wr-tags-holder">
												<?php the_excerpt(); ?>
												<?php do_action( 'workreap_term_tags', $freelancer_id,'skills','',7, 'freelancer' );?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php
							}
						} else {
							do_action( 'workreap_empty_listing', esc_html__('Oops!! Record not found', 'workreap') );
						}
						?>
						<?php if($total_posts > $per_page){?>
							<?php workreap_paginate($freelancer_data); ?>
						<?php }?>
                    </div>
					<?php wp_reset_postdata();?>
                </div>
            </div>
        </div>
    </section>
<?php }
get_footer();
