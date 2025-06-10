<?php

/**
 *
 * The template used for displaying task detail
 *
 * @package     Workreap
 * @subpackage  Workreap/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/

global $post, $thumbnail,$current_user;
do_action('workreap_post_views', $post->ID,'workreap_service_views');
get_header();
while (have_posts()) : the_post();
	$product 				= wc_get_product( $post->ID );
	$workreap_plans_values 	= get_post_meta($post->ID, 'workreap_product_plans', TRUE);
	$workreap_plans_values	= !empty($workreap_plans_values) ? $workreap_plans_values : array();
	$plans_count			= !empty($workreap_plans_values) && is_array($workreap_plans_values) ? count($workreap_plans_values) : 0;
	$product_cat 			= wp_get_post_terms( $post->ID, 'product_cat', array( 'fields' => 'ids' ) );
	$workreap_subtask 		= get_post_meta($product->get_id(), 'workreap_product_subtasks', TRUE);
	$post_status			= get_post_status( $post->ID );
	$post_author			= get_post_field( 'post_author', $post->ID );
	$post_id    			= !empty($post_author) ? workreap_get_linked_profile_id($post_author,'','freelancers') :'';
	$allow_user				= true;

	$user_name      = workreap_get_username($post_id);

	if(!empty($post_status) && in_array($post_status,array('draft','rejected','pending'))){
		if( !is_user_logged_in( ) ){
			$allow_user			= false;
		} else {
			if( is_user_logged_in() && (current_user_can('administrator') || $current_user->ID == $post_author) ){
				$allow_user		= true;
			} else {
				$allow_user		= false;
			}
		}
	}	
	
	$plan_array	= array(
		'product_tabs' 			=> array('plan'),
		'product_plans_category'=> $product_cat
	);

	$acf_fields		= workreap_acf_groups($plan_array);
	$workreap_attr	= array(
		'task_id'   	=> $product->get_id(),
		'product'   	=> $product,
		'post_id'   	=> $post_id,
		'plan_array'	=> $plan_array,
		'acf_fields'	=> $acf_fields,
		'product'		=> $product,
		'workreap_subtask'		=> $workreap_subtask,
		'workreap_plans_values'	=> $workreap_plans_values,
	);
	?>
	<section class="wr-main-section overflow-hidden wr-main-bg">
		<div class="container">
			<?php
				if( empty($allow_user) ){
					do_action( 'workreap_notification', esc_html__('Restricted access','workreap'), esc_html__('Oops! you are not allowed to access this page','workreap') );
				} else {?>
				<div class="row gy-4">
					<div class="col-lg-12">
                        <div class="wr-serviesbann">
                            <div class="wr-serviesbann__content">
                                <div class="wr-contentleft">
									<?php do_action( 'workreap_featured_item', $product,'featured_task' );?>
                                    <h3><?php the_title();?></h3>
                                    <div class="wr-contenthasfig">
										<?php workreap_get_template( 'single-task/task-details.php',$workreap_attr); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-lg-7 col-xl-8">
						<div class="wr-servicewrap">
							<?php workreap_get_template( 'single-task/gallery.php',$workreap_attr); ?>
							<div class="wr-singleservice-tile">
								<div class="wr-text-wrapper">
									<?php
										echo wpautop(get_the_content());
										wp_link_pages( array(
											'before'	=> '<div class="wr-paginationvtwo"><nav class="wr-pagination"><ul>',
											'after'		=> '</ul></nav></div>',
										) );
									?>
								</div>
							</div>
							<div class="wr-servicedetailcontent">
								<?php
									workreap_get_template('single-task/additional-services.php',$workreap_attr);
									workreap_get_template('single-task/task-tags.php',$workreap_attr);
									workreap_get_template('single-task/task-faqs.php',$workreap_attr);
									workreap_get_template('single-task/task-reviews.php',$workreap_attr);
								?>
							</div>
						</div>
					</div>
					<div class="col-lg-5 col-xl-4">
						<aside class="wr-tabasidebar" id="workreap-price-plans">
							<?php
								workreap_get_template( 'single-task/price-plan-tabs.php',$workreap_attr);
								workreap_get_template( 'single-task/task-cart.php',$workreap_attr);
								workreap_get_template( 'single-task/author-box.php',$workreap_attr);
								do_action('workreap_product_ads_content');
							?>
						</aside>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
<?php
endwhile;

get_footer();