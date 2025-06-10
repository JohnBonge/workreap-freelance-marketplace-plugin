<?php
/**
 *
 * The template used for displaying freelancer post style
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
    $workreap_args                   = array();
    $workreap_args['post_id']        = $post->ID;
    $workreap_args['user_id']        = workreap_get_linked_profile_id( $post->ID,'post');
    $workreap_args['post_status']    = array('publish');
    $app_task_base      		    = workreap_application_access('task');
    
    ?>
    <div class="wr-main-section wr-main-bg wr-frelancer-v2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-xl-12">
                    <aside class="wr-tabasidebar wr-single-freelancer">
                        <div class="wr-asideholder">
                            <?php workreap_get_template( 'single-freelancer/profile-basic-freelancer.php',$workreap_args);?>
                        </div>
                    </aside>
                    <?php workreap_get_template( 'single-freelancer/profile-portfolio.php',$workreap_args);?>
                    <?php workreap_get_template( 'single-freelancer/profile-education-v1.php',$workreap_args);?>
                    <?php workreap_get_template( 'single-freelancer/profile-experience-v1.php',$workreap_args);?>
                </div>
                <?php if( !empty($app_task_base) ){?>
                    <div class="col-lg-12 col-xl-12">
                        <div class="wr-sort wr-freelancersort">
                            <h3><?php esc_html_e('Tasks','workreap');?></h3>
                        </div>
                        <?php workreap_get_template( 'single-freelancer/profile-services-v1.php',$workreap_args);?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php     
endwhile;

get_footer();