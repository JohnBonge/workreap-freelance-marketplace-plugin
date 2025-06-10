<?php
/**
 *
 * The template used for displaying employer post style
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $post;
do_action('workreap_post_views', $post->ID,'workreap_profile_views');
get_header();
while (have_posts()) : the_post();
    global $post;
    $workreap_args               = array();
    $workreap_args['post_id']    = $post->ID;
    ?>
    <div class="wr-main-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <aside class="wr-tabasidebar">
                        <div class="wr-asideholder"> </div>
                    </aside>
                </div>
                <div class="col-lg-8">
                    <div class="wr-sort">
                        <h3><?php esc_html_e('Offered tasks','workreap');?></h3>
                    </div>                        
                </div>
            </div>
        </div>
    </div>
    <?php 
endwhile;
get_footer();