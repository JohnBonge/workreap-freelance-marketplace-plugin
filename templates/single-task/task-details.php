<?php
/**
 * Single task task details
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
global $current_user, $wp_roles, $userdata, $post;
extract($args);
$workreap_featured               = $product->get_featured();
$workreap_product_rating         = $product->get_average_rating();
$workreap_product_rating_count   = $product->get_rating_count();
$product_id                     = $product->get_id();
$workreap_service_views          = get_post_meta( $product_id, 'workreap_service_views', TRUE );
$workreap_service_views          = !empty($workreap_service_views) ? intval($workreap_service_views) : 0;
$user_type		                  = apply_filters('workreap_get_user_type', $current_user->ID);
$linked_profile                 = workreap_get_linked_profile_id($current_user->ID, '', $user_type);
?>
<ul class="wr-rateviews wr-rateviews3">
    <?php
		do_action('workreap_service_rating_count_theme_v2', $product);
		do_action( 'workreap_service_sales', $product,'v3' );
		do_action('workreap_service_item_views', $product);
		do_action( 'workreap_saved_item', $product_id, $linked_profile,'_saved_tasks', '' );
		do_action('workreap_service_extra_items', $product);
    ?>
</ul>