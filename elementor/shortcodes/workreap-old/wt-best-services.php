<?php
/**
 * Shortcode
 *
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 * @author     Amentotech <theamentotech@gmail.com>
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists('Workreap_Best_Services') ){
	class Workreap_Best_Services extends Widget_Base {

		public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);
			wp_enqueue_style('venobox');
			wp_enqueue_style('owl.carousel');
			wp_enqueue_script('venobox');
            wp_enqueue_script('owl.carousel');
        }

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_best_services';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Best Services', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-settings';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      category of shortcode
		 */
		public function get_categories() {
			return [ 'workreap-ele' ];
		}

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {
			$categories	= workreap_elementor_get_taxonomies('product','product_cat');
			$categories	= !empty($categories) ? $categories : array();

			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Title', 'workreap' ),
					'description' 	=> esc_html__('Add title or leave it empty to hide.', 'workreap'),
				]
			);

			
			$this->add_control(
				'link_target',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Select listing type', 'workreap'),
					'desc'			=> esc_html__('Do you want to list by categories or Services ids?', 'workreap'),
					'options' 		=> [
										'categories' => esc_html__('Categories', 'workreap'),
										'services_ids' => esc_html__('Services IDs', 'workreap'),
										],
					'default' 		=> 'project_ids',
				]
			);
			$this->add_control(
				'services_ids',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Services ID\'s', 'workreap'),
					'description' 	=> esc_html__('Add services ID\'s with comma(,) separated e.g(15,21). Leave it empty to show latest servicess.', 'workreap'),
					'condition' => [
						'link_target' => 'services_ids',
					],
				]
			);

			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Categories?', 'workreap'),
					'desc' 			=> esc_html__('Select categories to display.', 'workreap'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
					'condition' => [
						'link_target' => 'categories',
					],
				]
			);

			$this->add_control(
				'show_posts',
				[
					'label' => __( 'Number of posts', 'workreap' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'micro-services' ],
					'range' => [
						'micro-services' => [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'micro-services',
						'size' => 9,
					]
				]
			);

			$this->add_control(
				'order',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Order','workreap' ),
					'description'   => esc_html__('Select posts Order.', 'workreap' ),
					'default' 		=> 'DESC',
					'options' 		=> [
						'ASC' 	=> esc_html__('ASC', 'workreap'),
						'DESC' 	=> esc_html__('DESC', 'workreap'),
					],
				]
			);
			
			$this->add_control(
				'orderby',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Post Order','workreap' ),
					'description'   => esc_html__('View Posts By.', 'workreap' ),
					'default' 		=> 'ID',
					'options' 		=> [
						'ID' 		=> esc_html__('Order by post id', 'workreap'),
						'author' 	=> esc_html__('Order by author', 'workreap'),
						'title' 	=> esc_html__('Order by title', 'workreap'),
						'name' 		=> esc_html__('Order by post name', 'workreap'),
						'date' 		=> esc_html__('Order by date', 'workreap'),
						'rand' 		=> esc_html__('Random order', 'workreap'),
						'featured' 		=> esc_html__('Featured first', 'workreap'),
						'comment_count' => esc_html__('Order by number of comments', 'workreap'),
					],
				]
			);
			
			
			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Button Title', 'workreap' ),
					'description' 	=> esc_html__('Add button or leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__( 'Add Button Link', 'workreap' ),
					'description' 	=> esc_html__('Add button link, or default will be #.', 'workreap'),
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Render shortcode
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		/**
		 * Render shortcode
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();
			
			$pg_page  = get_query_var('page') ? get_query_var('page') : 1;
			$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$paged    	= max($pg_page, $pg_paged);

			$show_posts = !empty($settings['show_posts']['size']) ? $settings['show_posts']['size'] : -1;
			$order 		= !empty($settings['order']) ? $settings['order'] : 'ASC';
			$orderby 	= !empty($settings['orderby']) ? $settings['orderby'] : 'ID';

			$categories 	= !empty($settings['categories']) ? $settings['categories'] : array();
			$title     		= !empty($settings['title']) ? $settings['title'] : '';
			$btn_title 		= !empty($settings['btn_title']) ? $settings['btn_title'] : '';
			$btn_link  		= !empty($settings['btn_link']) ? $settings['btn_link'] : '';

			$link_target  = !empty($settings['link_target']) ? $settings['link_target'] : '';
			
			$tax_queries            = array();
			$meta_queries			= array();
			$product_type_tax_args[] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => 'tasks',
			  );
			$tax_queries 	= array_merge($tax_queries,$product_type_tax_args);
			if( !empty($categories) && $link_target === 'categories' ){
				$product_cat_tax_args[] = array(
					'taxonomy'  => 'product_cat',
					'terms'     => $categories,
					'field'     => 'term_id',
					'operator'  => 'IN',
				);
				$tax_queries = array_merge($tax_queries, $product_cat_tax_args);
			}

			
			$workreap_args 	= array(
				'post_type'         => 'product',
				'post_status'       => 'publish',
				'orderby' 	 	  	  => $orderby,
				'order' 	 	  	  => $order,
				'posts_per_page'    => $show_posts,
				'paged'             => $paged,
				'tax_query'         => $tax_queries,
				'meta_query'        => $meta_queries,
			);
			if( $link_target === 'services_ids' && !empty($settings['services_ids'])){
				$workreap_args['post__in']	= explode(',',$settings['services_ids']);
			}
			$services_posts      = new \WP_Query(apply_filters('workreap_service_listings_args', $workreap_args));
					 
			$total_posts   = $services_posts->found_posts;
			$flag 				= rand(9999, 999999);
			?>
			<div class="wt-bestservices-section">
				<div class="theme-container">
					<?php if( !empty($title) ){?>
						<div class="wt-sectionhead wt-sectionheadvfour">
							<div class="wt-sectiontitle wt-sectiontitlevthree">
								<h2><?php echo esc_html($title);?></h2>
							</div>
						</div>
					<?php } ?>
					<?php if ($services_posts->have_posts()) { ?>
						<div class="wt-bestserviceholder">
							<div class="row">
							<?php 
								while($services_posts->have_posts()) {
									$services_posts->the_post();
									global $post;
									$random = rand(1,9999);
									
									?>
									<div class="col-sm-6 col-lg-4 col-xl-3">
										<?php do_action( 'workreap_listing_task_html_v1', $post->ID );?>
									</div>
									<?php
									} 
									wp_reset_postdata();
								?>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty($btn_title) ){?>
						<div class="wt-sectionbtn">
							<a href="<?php echo esc_url($btn_link);?>" class="wt-btn wt-btnv2"><?php echo esc_html($btn_title);?></a>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
			$is_rtl = 'false';
			if( is_rtl() ){
				$is_rtl = 'true';
			}
			$script	= "
			var owl_task	= jQuery('.wr-tasks-slider').owlCarousel({
				rtl:".esc_js($is_rtl).",
				items: 1,
				loop:false,
				nav:true,
				margin: 0,
				autoplay:false,
				lazyLoad:false,
				navClass: ['wr-prev', 'wr-next'],
				navContainerClass: 'wr-search-slider-nav',
				navText: ['<i class=\"wr-icon-chevron-left\"></i>', '<i class=\"wr-icon-chevron-right\"></i>'],
			});
		
			setTimeout(function(){owl_task.trigger('refresh.owl.carousel');}, 3000);
			jQuery(window).load(function() {
				owl_task.trigger('refresh.owl.carousel');
				setTimeout(function(){owl_task.trigger('refresh.owl.carousel');}, 2000);
			});";
			wp_add_inline_script( 'owl.carousel', $script, 'after' );
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Best_Services ); 
}