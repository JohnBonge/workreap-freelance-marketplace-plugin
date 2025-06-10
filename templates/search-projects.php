<?php
/**
 *
 * Template Name: Search Projects
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $post, $workreap_settings,$current_user;

$hide_filter_type               = !empty($workreap_settings['hide_project_filter_type']) ? $workreap_settings['hide_project_filter_type'] : false;
$hide_filter_location           = !empty($workreap_settings['hide_project_filter_location']) ? $workreap_settings['hide_project_filter_location'] : false;
$hide_filter_skills             = !empty($workreap_settings['hide_project_filter_skills']) ? $workreap_settings['hide_project_filter_skills'] : false;
$hide_filter_level              = !empty($workreap_settings['hide_project_filter_level']) ? $workreap_settings['hide_project_filter_level'] : false;
$hide_filter_languages          = !empty($workreap_settings['hide_project_filter_language']) ? $workreap_settings['hide_project_filter_language'] : false;
$hide_filter_price              = !empty($workreap_settings['hide_project_filter_price']) ? $workreap_settings['hide_project_filter_price'] : false;
$hide_filter_categories         = !empty($workreap_settings['hide_project_filter_categories']) ? $workreap_settings['hide_project_filter_categories'] : false;
$project_multilevel_cat         = !empty($workreap_settings['project_multilevel_cat']) ? $workreap_settings['project_multilevel_cat'] : 'disable';

$tax_queries            = array();
$meta_queries           = array();
$query_args             = array();
$user_meta_queries      = array();
$user_ids               = array();
$product_type_tax_args  = array();
$min_price_meta_args 	= array();
$sorting                = !empty($_GET['sort_by']) ? esc_attr($_GET['sort_by']) : 'date_desc';
$owner                  = !empty($_GET['owner']) ? esc_attr($_GET['owner']) : '';

$user_type              = "";
if( is_user_logged_in( ) ){
    $user_type  = apply_filters('workreap_get_user_type', $current_user->ID );
}

$product_type_tax_args[] = array(
  'taxonomy' => 'product_type',
  'field'    => 'slug',
  'terms'    => 'projects',
);

$tax_queries = array_merge($tax_queries,$product_type_tax_args);

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
            'meta_key' 	=> 'min_price',
            'order' 	=> 'desc',
        );
    } elseif ($sorting == 'price_asc') {
        $filtered_args['sort_by'] = array(
            'orderby' 	=> 'meta_value_num',
            'meta_key' 	=> 'min_price',
            'order' 	=> 'asc',
        );
    } elseif ($sorting == 'views_desc') {
        $filtered_args['sort_by'] = array(
            'orderby' 	=> 'meta_value_num',
            'meta_key' 	=> 'workreap_project_views',
            'order' 	=> 'desc',
        );
    }

    $query_args = array_merge($query_args, $filtered_args['sort_by']);
} else {
    $filtered_args['sort_by'] = array(
        'orderby' 	=> 'meta_value',
        'meta_key' 	=> '_featured_task',
        'order' 	=> 'DESC',
    );
    $query_args = array_merge($query_args, $filtered_args['sort_by']);
}

// handled category filter in query args

$category           = '';
$category_id        = 0;
$sub_category       = '';
$sub_category_id    = 0;
$service_array      = array();
$service_ids        = array();
$skills             = !empty($_GET['skills']) ? $_GET['skills'] : array();
$expertise_level    = !empty($_GET['expertise_level']) ? $_GET['expertise_level'] : array();
$languages          = !empty($_GET['languages']) ? $_GET['languages'] : array();
$project_type       = !empty($_GET['project_type']) ? $_GET['project_type'] : '';

if (!empty($_GET['category']) && $_GET['category'] != -1) {
    // check and get parent category info
    // check and get parent category info
    $category = esc_html($_GET['category']);
    $category_obj = get_term_by('slug', $category, 'product_cat');
    if (!empty($category_obj)) {
        $category_id = $category_obj->term_id;
        $service_ids = $category_id;
    }

    // check and get sub category info
    if (!empty($_GET['sub_category'])) {
        $service_ids = array();
        $sub_category = esc_html($_GET['sub_category']);
        $sub_category_obj = get_term_by('slug', $sub_category, 'product_cat');
        if (!empty($sub_category_obj)) {
            $sub_category_id = $sub_category_obj->term_id;
            $service_ids = $sub_category_id;
        }
    }


    // check and get third level category info, on this level we have service array
    if (!empty($_GET['service'])) {
        $service_ids = array();
        $service_array = array_map('esc_attr', $_GET['service']);
        foreach ($service_array as $service) {

            $service_obj = get_term_by('slug', $service, 'product_cat');
            if (!empty($service_obj)) {
                $service_id = $service_obj->term_id;
                array_push($service_ids, $service_id);
            }

        }

    }

    // here we are having another taxonomy so let define the relation
    $query_relation = array('relation' => 'AND',);
    $tax_queries = array_merge($query_relation, $tax_queries);
    // handled searched by product cat taxonomy
    $product_cat_tax_args[] = array(
        'taxonomy'  => 'product_cat',
        'terms'     => $service_ids,
        'field'     => 'term_id',
        'operator'  => 'IN',
    );

    // append product_cat taxonomy args in $tax_queries array
    $tax_queries = array_merge($tax_queries, $product_cat_tax_args);
}

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
	$tax_queries[] = array_merge($query_relation, $type_args);
}

if ( !empty($expertise_level[0]) && is_array($expertise_level) ) {
	$query_relation = array('relation' => 'OR',);
	$type_args  	= array();
	foreach( $expertise_level as $key => $type ){
		$type_args[] = array(
			'taxonomy' => 'expertise_level',
			'field'    => 'slug',
			'terms'    => esc_attr($type),
		);
	}
	$tax_queries[] = array_merge($query_relation, $type_args);
}

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

	$tax_queries[] = array_merge($query_relation, $lang_args);
}
// check and store filter variable data
$keyword                    = !empty($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : "";
$location                   = !empty($_GET['location']) ? sanitize_text_field($_GET['location']) : "";
$min_product_price          = !empty($_GET['min_price']) ? ($_GET['min_price']) : '';
$max_product_price          = !empty($_GET['max_price']) ? ($_GET['max_price']) : '';
$state                      = !empty($_GET['state']) ? ($_GET['state']) : '';
if (class_exists('WooCommerce')) {
	$countries_obj   	= new WC_Countries();
	$countries   		= $countries_obj->get_allowed_countries('countries');
    // if( is_array($countries) && count($countries) === 1 ){
    //     $country                = array_key_first($countries);
    //     $location               = $country;
    // }
}
// if keyword field is set in search then append its args in $query_args
if (!empty($keyword)) {
    $filtered_args['keyword'] = array( 's' => $keyword,);
    $query_args = array_merge($query_args, $filtered_args['keyword']);
}

if( !empty($project_type) && $project_type != 'all' ){
    $project_type_meta_args[] = array(
        'key'       => 'project_type',
        'value'     => $project_type,
        'compare'   => '='
    );

    // store basic taxonomy in $tax_queries array
    $meta_queries = array_merge($meta_queries, $project_type_meta_args);
}

// if min price field is set in search then append it in meta query
if (!empty($min_product_price)) {
    $min_price_meta_args[] = array(
        'key'       => 'min_price',
        'value'     => $min_product_price,
        'compare'   => '>=',
        'type'      => 'NUMERIC',
    );

    // store basic taxonomy in $tax_queries array
    $meta_queries = array_merge($meta_queries, $min_price_meta_args);
}

// if max price field is set in search then append it in meta query
if (!empty($max_product_price)) {

    if (count($meta_queries) == 1) {
        $query_relation = array('relation' => 'AND',);
        $meta_queries = array_merge($query_relation, $meta_queries);
    }

    $max_price_meta_args[] = array(
        'key'       => 'max_price',
        'value'     => $max_product_price,
        'compare'   => '<=',
        'type'      => 'NUMERIC',
    );
    $meta_queries = array_merge($meta_queries, $max_price_meta_args);
}

// if location field is set in search then append it in meta query
if (!empty($location) && $location != -1 ) {

    if (count($meta_queries) == 1) {
        $query_relation = array('relation' => 'AND',);
        $meta_queries = array_merge($query_relation, $meta_queries);
    }

    $product_country_meta_args[] = array(
        'key'       => 'country',
        'value'     => $location,
        'compare'   => '=',
        'type'      => 'CHAR',
    );

    $meta_queries = array_merge($meta_queries, $product_country_meta_args);

    if( !empty($state) ){
        $countries_obj  = new WC_Countries();
        $countries      = $countries_obj->get_allowed_countries('countries');
        $states_list    = $countries_obj->get_states( $location );
        if( !empty($states_list[$state]) ){
            $product_state_meta_args[] = array(
                'key'       => 'state',
                'value'     => $state,
                'compare'   => '=',
                'type'      => 'CHAR',
            );

            $meta_queries = array_merge($meta_queries, $product_state_meta_args);
        }
    }
}

$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$show_posts = !empty(get_option('posts_per_page')) ? get_option('posts_per_page') : 10;

// prepared query args
$workreap_args = array(
  'post_type'         => 'product',
  'post_status'       => 'publish',
  'posts_per_page'    => $show_posts,
  'paged'             => $paged,
);

//Get posts from specific employer
if(!empty($owner)){
    $workreap_args['author'] = $owner;
}

if(!empty($tax_queries)){
    $workreap_args['tax_query']   = $tax_queries;
}

if(!empty($meta_queries)){
    $workreap_args['meta_query']   = $meta_queries;
}

$workreap_args               = array_merge($workreap_args, $query_args);
$workreap_query              = new WP_Query(apply_filters('workreap_project_listings_args', $workreap_args));
$result_count               = $workreap_query->found_posts;
$search_project_page        = !empty($workreap_settings['tpl_project_search_page']) ? get_permalink($workreap_settings['tpl_project_search_page']) : '';
$hide_product_cat           = !empty($workreap_settings['hide_product_cat']) ? $workreap_settings['hide_product_cat'] : array();
$sort_by                    = !empty($sorting) ? sanitize_text_field($sorting) : "";

$min_product_price          = !empty($min_product_price) ? $min_product_price : 0;
$max_product_price          = !empty($max_product_price) ? $max_product_price : 0;
$listing_type               = !empty($workreap_settings['projects_listing_view']) ? $workreap_settings['projects_listing_view'] : 'left';

$listing_param = isset($_GET['view_style']) ? $_GET['view_style'] : '';
if(isset($listing_param) && $listing_param == 'v2'){
    $listing_type = 'top';
}elseif (isset($listing_param) && $listing_param == 'v1'){
    $listing_type = 'left';
}
get_header();
if( !empty($listing_type) && $listing_type === 'top' ){

    $grid_arg                       = array();
    $grid_arg['sort_by']            = $sort_by;
    $grid_arg['show_posts']         = $show_posts;
    $grid_arg['location']           = $location;
    $grid_arg['workreap_query']      = $workreap_query;
    $grid_arg['result_count']       = $result_count;
    $grid_arg['search_project_page']   = $search_project_page;
    $grid_arg['hide_product_cat']   = $hide_product_cat;
    $grid_arg['keyword']            = $keyword;
    $grid_arg['category']           = $category;
    $grid_arg['skills']             = $skills;
    $grid_arg['expertise_level']    = $expertise_level;
    $grid_arg['languages']          = $languages;

    $grid_arg['min_product_price']  = $min_product_price;
    $grid_arg['max_product_price']  = $max_product_price;

    include workreap_load_template( 'templates/search-projects/search-projects-v2' );
} else {
?>
    <section class="wr-main-section wr-main-bg wr-searchproject ">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="wr-sort">
                        <?php if ($keyword){ ?>
                            <h3><?php echo sprintf( esc_html__('%d search result(s) "%s" found','workreap'), $result_count,$keyword);?></h3>
                        <?php } else { ?>
                            <h3><?php echo sprintf(esc_html__('%d search result(s) found','workreap'), $result_count);?></h3>
                        <?php } ?>
                        <?php do_action('workreap_get_project_price_sortby_filter_theme', $sort_by); ?>
                        <div class="wr-filtermenu">
                            <a href="javascript:();" class="wr-filtericon"><i class="wr-icon-sliders"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xxl-3 wr-mt0">
                    <aside>
                        <form id="wr_sort_form" action="<?php echo esc_url( $search_project_page ); ?>" method="get" class="wr-searchlist">
                            <input type="hidden" name="sort_by" id="wr_sort_by_filter" value="<?php echo esc_attr($sort_by); ?>">
                            <div class="wr-aside-holder">
                                <div class="wr-asidetitle" data-bs-toggle="collapse" data-bs-target="#wr_category_filter" role="button" aria-expanded="true">
                                    <h5><?php esc_html_e('Search','workreap'); ?></h5>
                                </div>
                                <div id="wr_category_filter" class="collapse show">
                                    <?php do_action('workreap_keyword_search_filter_theme', $keyword); ?>
                                </div>
                            </div>
                            <?php
                                if( !empty($hide_filter_type) ){
                                    do_action( 'workreap_project_search_option');
                                }
                            ?>
                            <?php if( !empty($hide_filter_categories) ){
                                 $cat_expanded           = !empty($category) ? 'true' : 'false';
                                 $cat_collapse           = !empty($category) ? '' : 'collapsed';
                                 $cat_collapse_content   = !empty($category) ? 'show' : '';
                                 if( !empty($project_multilevel_cat) && $project_multilevel_cat === 'enable' ){
                                 ?>
                                 <div class="wr-aside-holder">
                                     <div class="wr-asidetitle <?php echo esc_attr($cat_collapse);?>" data-bs-toggle="collapse" data-bs-target="#side2" role="button" aria-expanded="<?php echo esc_attr($cat_expanded);?>">
                                         <h5><?php esc_html_e('Categories','workreap'); ?></h5>
                                     </div>
                                     <div id="side2" class="collapse <?php echo esc_attr($cat_collapse_content);?>">
                                         <div class="wr-aside-content">
                                             <div class="wr-filterselect">
                                                 <div class="wr-select" id="task_search_wr_parent_category">
                                                     <?php
                                                         $workreap_args = array(
                                                             'show_option_none'  => esc_html__('Select category', 'workreap'),
                                                             'show_count'        => false,
                                                             'hide_empty'        => false,
                                                             'name'              => 'category',
                                                             'class'             => 'form-control wr-top-service-task-search',
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
                                             </div>
                                             <div class="wr-filterselect" id="task_search_wr_sub_category">
                                                 <?php
                                                     if (!empty($category)) {
                                                         do_action('workreap_task_search_get_terms', $category, $sub_category, 'wr-select');
                                                     }
                                                 ?>
                                             </div>
                                             <div class="wr-filterselect" id="task_search_wr_category_level3">
                                                 <?php
                                                     if (!empty($sub_category)) {
                                                         do_action('workreap_task_search_get_terms_subcategories', $sub_category, $service_array);
                                                     }
                                                 ?>
                                             </div>

                                         </div>
                                     </div>
                                 </div>
                                 <?php }  else {?>
                                <div class="wr-aside-holder">
                                    <div class="wr-asidetitle collapsed" data-bs-toggle="collapse" data-bs-target="#side2" role="button" aria-expanded="false">
                                        <h5><?php esc_html_e('Categories','workreap'); ?></h5>
                                    </div>
                                    <div id="side2" class="collapse">
                                        <div class="wr-aside-content">
                                            <div class="wr-filterselect">
                                                <div class="wr-select" id="task_search_wr_parent_category">
                                                    <?php
                                                        $workreap_args = array(
                                                            'show_option_none'  => esc_html__('Select category', 'workreap'),
                                                            'show_count'        => false,
                                                            'hide_empty'        => false,
                                                            'name'              => 'category',
                                                            'class'             => 'form-control wr-top-service-project-search',
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if( !empty($hide_filter_skills) ){ do_action('workreap_skills_filter_theme', $skills); }?>
                            <?php if( !empty($hide_filter_level) ){ do_action('workreap_expertise_level_filter_theme', $expertise_level); } ?>
                            <?php if( !empty($hide_filter_languages) ){ do_action('workreap_languages_filter_theme', $languages); } ?>
                            <?php if( !empty($hide_filter_price) ){ do_action('workreap_render_price_range_filter_html', esc_html__('Price range','workreap'), $min_product_price,  $max_product_price);} ?>
                            <?php if( !empty($hide_filter_location) ){ do_action('workreap_location_search_field', $location); }?>
                            <?php do_action('workreap_project_search_filters');?>
                            <?php do_action('workreap_search_clear_button_theme', esc_html__('Apply filters','workreap'),$search_project_page); ?>
                        </form>
                    </aside>
                </div>
                <div class="col-lg-8 col-xxl-9">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            if ( $workreap_query->have_posts() ) :
                                while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                                    $product            = wc_get_product();
                                    $product_author_id  = get_post_field ('post_author', $product->get_id());
                                    $linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','employers');
                                    $user_name          = workreap_get_username($linked_profile_id);
                                    $is_verified    	= !empty($linked_profile_id) ? get_post_meta( $linked_profile_id, '_is_verified',true) : '';
                                    $project_price      = workreap_get_project_price($product->get_id());
                                    $project_meta       = get_post_meta( $product->get_id(), 'wr_project_meta',true );
                                    $project_meta       = !empty($project_meta) ? $project_meta : array();
                                    $project_type       = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
                                    $post_status		= get_post_status( $product->get_id() );
                                    $workreap_user_proposal  = 0;
                                    if( is_user_logged_in() && !empty($user_type) && $user_type === 'freelancers' ){
                                        $proposal_args = array(
                                            'post_type' 	    => 'proposals',
                                            'post_status'       => 'any',
                                            'posts_per_page'    => -1,
                                            'author'            => $current_user->ID,
                                            'meta_query'        => array(
                                                array(
                                                    'key'       => 'project_id',
                                                    'value'     => intval($product->get_id()),
                                                    'compare'   => '=',
                                                    'type'      => 'NUMERIC'
                                                )
                                            )
                                        );

                                        $proposals                  = get_posts( $proposal_args );
                                        $workreap_user_proposal      = !empty($proposals) && is_array($proposals) ? count($proposals) : 0;
	                                    $proposal_id = isset($proposals[0]->ID) ? $proposals[0]->ID : 0;
	                                    $proposal_status	= get_post_status( $proposal_id );
                                        $proposal_edit_link         = !empty($proposals) ? workreap_get_page_uri('submit_proposal_page').'?id='.intval($proposal_id) : '';
                                    }

                                    $submint_class	= '';
                                    $page_url		= '';
                                    if( !is_user_logged_in( ) ){
                                        $submint_class	= 'wr-login-freelancer';
                                    } else {
                                        if( is_user_logged_in() && (current_user_can('administrator') || $current_user->ID == $product_author_id) ){
                                            $submint_class	= 'wr-login-freelancer';
                                        } else {
                                            $linked_profile     = workreap_get_linked_profile_id($current_user->ID, '', $user_type);
                                            if( !empty($user_type) && $user_type === 'freelancers' ){
                                                $submint_class	= 'wr-page-link';
                                                $page_url		= !empty($product->get_id()) ?workreap_get_page_uri('submit_proposal_page').'?post_id='.intval($product->get_id()) : '';
                                            } else if( !empty($user_type) && $user_type === 'employers' ){
                                                $submint_class	= 'wr-redirect-url';
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="wr-project-wrapper">
                                        <?php do_action( 'workreap_featured_item', $product,'featured_project' );?>
                                        <div class="wr-project-box">
                                            <div class="wr-price-holder">
                                                <div class="wr-project_head">
                                                    <div class="wr-verified-info">
                                                        <strong>
                                                            <?php echo esc_html($user_name);?>
                                                            <?php do_action( 'workreap_verification_tag_html', $linked_profile_id ); ?>
                                                        </strong>
                                                        <?php if( !empty($product->get_name()) ){?>
                                                            <h5><a href="<?php echo esc_url(get_the_permalink( $product->get_id() ));?>"><?php echo esc_html($product->get_name());?></a></h5>
                                                        <?php } ?>
                                                    </div>
                                                    <ul class="wr-template-view">
                                                        <?php do_action( 'workreap_posted_date_html', $product );?>
                                                        <?php do_action( 'workreap_location_html', $product );?>
                                                        <?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
                                                        <?php do_action( 'workreap_hiring_freelancer_html', $product );?>
                                                        <li><div class="wr-likev2"><?php do_action( 'workreap_project_saved_item', $product->get_id(), '','_saved_projects', 'list' );?></div></li>
                                                    </ul>
                                                </div>
                                                <?php if( isset($project_price) ){?>
                                                    <div class="wr-price">
                                                        <?php if( !empty($project_type) ){?>
                                                            <?php do_action( 'workreap_project_type_text', $project_type );?>
                                                        <?php } ?>
                                                        <h4><?php echo do_shortcode($project_price);?></h4>
                                                        <div class="wr-project-option">
                                                            <?php
                                                                if( is_user_logged_in() && !empty($user_type) && $user_type === 'freelancers' && !empty($workreap_user_proposal) ){
	                                                                if(isset($current_user->ID) && isset($proposal_status) && $proposal_status === 'hired' ){ ?>
                                                                        <span class="wr-btn-solid-lg-lefticon"><a href="<?php echo esc_url(Workreap_Profile_Menu::workreap_profile_menu_link('projects', $current_user->ID, true, 'activity',$proposal_id));?>"><?php esc_html_e('Project activity','workreap');?></a></span>
                                                                    <?php }else{ ?>
                                                                        <span class="wr-btn-solid-lg-lefticon"><a href="<?php echo esc_url($proposal_edit_link);?>"><?php esc_html_e('Edit proposal','workreap');?></a></span>
	                                                                <?php }
                                                                }else if( is_user_logged_in() &&  intval($current_user->ID) === intval( $product_author_id ) ){ ?>
                                                                    <span class="wr-btn-solid-lg-lefticon"><a href="<?php echo get_the_permalink($product->get_id());?>"><?php esc_html_e('View detail','workreap');?></a></span>
                                                                <?php } else{
                                                                    if( !empty($post_status) && $post_status === 'publish') {?>
                                                                        <span class="wr-btn-solid-lg-lefticon <?php echo esc_attr($submint_class);?>" data-url="<?php echo esc_url($page_url);?>"><?php esc_html_e('Apply now','workreap');?></span>
                                                                    <?php }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php the_excerpt(); ?>
                                            <?php do_action( 'workreap_term_tags', $product->get_id(),'skills','',7,'project' );?>
                                        </div>
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
                                else:
                                do_action( 'workreap_empty_listing', esc_html__('No projects found', 'workreap') );
                            endif;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
    if( !empty($skills) ){
        $scripts	        = "
        jQuery(function () {
            jQuery('#project_skill_search .show-more .wr-show_more').trigger('click');
        });";
	    wp_add_inline_script('workreap-callbacks', $scripts, 'after');
    }
}
get_footer();