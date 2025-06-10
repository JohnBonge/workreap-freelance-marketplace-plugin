<?php
/**
 * Related projects
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */

global $current_user, $workreap_settings;

$hide_related     = !empty($workreap_settings['hide_related']) ? $workreap_settings['hide_related'] : 'no';

if(!empty($hide_related) && $hide_related === 'yes'){return;}
 
$project_id = !empty($project_id) ? intval($project_id) : 0;
if( !empty($project_id) ){
    $skills         = wp_get_post_terms( $project_id, 'skills' ,array( 'fields' => 'slugs' ));
    $tax_queries    = array();
    $product_type_tax_args[] = array(
        'taxonomy' => 'product_type',
        'field'    => 'slug',
        'terms'    => 'projects',
      );
      
      $tax_queries = array_merge($tax_queries,$product_type_tax_args);
      if ( !empty($skills) ) {   
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
        // prepared query args
        $workreap_args = array(
            'post_type'         => 'product',
            'post_status'       => 'publish',
            'post__not_in'      => array($project_id),
            'posts_per_page'    => 3
        );
        if(!empty($tax_queries)){
            $workreap_args['tax_query']   = $tax_queries;
        }
        $workreap_query              = new WP_Query(apply_filters('workreap_project_related_listings_args', $workreap_args));
        if ( $workreap_query->have_posts() ) :
    
    ?>
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="wr-relatedproject_title">
                <h3><?php esc_html_e('Projects you may like','workreap');?></h3>
            </div>
        </div>
        <?php
            while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                $product            = wc_get_product();
            ?>
            <div class="col-md-6 col-lg-4">
                <?php do_action( 'workreap_project_grid_view_style_2', $product );?>
            </div>
        <?php endwhile;?>
    </div>
    <?php 
    endif;
    }
}