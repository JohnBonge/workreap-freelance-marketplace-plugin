<?php
/**
 * 
 * Class 'Workreap_Admin_CPT_Employer' defines the custom post type Employers
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Workreap_Admin_CPT_Portfolios {

	/**
	 * Employers post type
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action('init', array(&$this, 'init_post_type'));		
	}

	/**
	 * @Init post type
	*/
	public function init_post_type() {
		$this->register_posttype();
	}

	/**
	 *Regirster employer post type
	*/ 
	public function register_posttype() {
		$labels = array(
			'name'                  => esc_html__( 'Portfolios', 'workreap' ),
			'singular_name'         => esc_html__( 'Portfolio', 'workreap' ),
			'menu_name'             => esc_html__( 'Portfolios', 'workreap' ),
			'name_admin_bar'        => esc_html__( 'Portfolios', 'workreap' ),
			'parent_item_colon'     => esc_html__( 'Parent portfolio:', 'workreap' ),
			'all_items'             => esc_html__( 'All portfolios', 'workreap' ),
			'add_new_item'          => esc_html__( 'Add new portfolio', 'workreap' ),
			'add_new'               => esc_html__( 'Add new portfolio', 'workreap' ),
			'new_item'              => esc_html__( 'New portfolio', 'workreap' ),
			'edit_item'             => esc_html__( 'Edit portfolio', 'workreap' ),
			'update_item'           => esc_html__( 'Update portfolio', 'workreap' ),
			'view_item'             => esc_html__( 'View portfolios', 'workreap' ),
			'view_items'            => esc_html__( 'View portfolios', 'workreap' ),
			'search_items'          => esc_html__( 'Search portfolios', 'workreap' ),
		);
		
		$args = array(
			'label'                 => esc_html__( 'Portfolio', 'workreap' ),
			'description'           => esc_html__( 'All portfolio.', 'workreap' ),
			'labels'                => apply_filters('workreap_product_taxonomy_portfolio_labels', $labels),
			'public' 				=> false,
			'supports' 				=> array('title','author'),
			'show_ui' 				=> true,
			'capability_type' 		=> 'post',
			'map_meta_cap' 			=> true,
			'publicly_queryable' 	=> false,
			'exclude_from_search' 	=> true,
			'hierarchical' 			=> false,
			'menu_position' 		=> 4,
			'rewrite' 				=> array('slug' => 'portfolios', 'with_front' => false),
			'query_var' 			=> false,
			'has_archive' 			=> false,
			'rest_base'             => 'portfolios',
			'show_in_menu' 			=> 'edit.php?post_type=freelancers',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		
		register_post_type( apply_filters('workreap_portfolio_post_type_name', 'portfolios'), $args );

	}  
}

new Workreap_Admin_CPT_Portfolios();

if(!function_exists('portfolio_meta_box')){
	function portfolio_meta_box() {
		add_meta_box(
			'portfolio_gallery',
			__('Gallery', 'workreap'),
			'portfolio_gallery_callback',
			'portfolios',
			'normal',
			'high'
		);
	}
	add_action('add_meta_boxes', 'portfolio_meta_box');
}

if(!function_exists('portfolio_gallery_callback')){
	function portfolio_gallery_callback($post) {
		wp_nonce_field('portfolio_gallery_nonce', 'portfolio_gallery_nonce');
		$gallery = get_post_meta($post->ID, '_portfolio_gallery', true);

		echo '<div id="portfolio_gallery_container">';
		echo '<ul class="portfolio_gallery">';

		if ($gallery) {
			$gallery_ids = explode(',', $gallery);
			foreach ($gallery_ids as $attachment_id) {
				$img = wp_get_attachment_image_src($attachment_id, 'thumbnail');
				echo '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '">
						<img src="' . esc_url($img[0]) . '" />
						<a href="#" class="remove" title="' . __('Remove image', 'workreap') . '">&times;</a>
					</li>';
			}
		}

		echo '</ul>';
		echo '<input type="hidden" id="portfolio_gallery-input" name="portfolio_gallery" value="' . esc_attr($gallery) . '" />';
		echo '</div>';
		echo '<p class="add_gallery_images hide-if-no-js"><a href="#">' . __('Add images', 'workreap') . '</a></p>';
	}
}

if(!function_exists('portfolio_save_meta_box_data')){
	function portfolio_save_meta_box_data($post_id) {
		if (!isset($_POST['portfolio_gallery_nonce']) || !wp_verify_nonce($_POST['portfolio_gallery_nonce'], 'portfolio_gallery_nonce')) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		$gallery = sanitize_text_field($_POST['portfolio_gallery']);
		update_post_meta($post_id, '_portfolio_gallery', $gallery);
	}
	add_action('save_post', 'portfolio_save_meta_box_data');
}