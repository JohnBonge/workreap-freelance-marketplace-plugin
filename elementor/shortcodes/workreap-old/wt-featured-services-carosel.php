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

if( !class_exists('Workreap_Services_Carousel') ){
	class Workreap_Services_Carousel extends Widget_Base {

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
			return 'wt_element_services_carousel';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Services Carousel', 'workreap' );
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
			$services	= workreap_elementor_get_taxonomies('product','product_cat');
			$services	= !empty($services) ? $services : array();
			
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
					'label' 		=> esc_html__('Title', 'workreap'),
        			'description' 	=> esc_html__('Add newsletter title. leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Sub title', 'workreap'),
        			'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap'),
        			'description' 	=> esc_html__('Add newsletter description. leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Title', 'workreap'),
        			'description' 	=> esc_html__('Add button title. Leave it empty to hide button.', 'workreap'),
				]
			);
			
			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Link', 'workreap'),
        			'description' 	=> esc_html__('Add button link. Leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'services',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Choose Category', 'workreap'),
					'desc' 			=> esc_html__('Select category services to display.', 'workreap'),
					'options'   	=> $services,
					'label_block' 	=> true,
					'multiple' 		=> true,
				]
			);
			
			$this->add_control(
				'show_posts',
				[
					'label' => esc_html__( 'Number of posts', 'workreap' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'posts' ],
					'range' => [
						'posts' => [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'posts',
						'size' => 6,
					]
				]
			);
			
			$this->add_control(
				'service_by',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Services by ID', 'workreap'),
        			'description' 	=> esc_html__('You can add comma separated ID\'s for the services to show specific services. Leave it empty to use above settings', 'workreap'),
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
		protected function render() {
			$settings = $this->get_settings_for_display();

			$title      = !empty( $settings['title'] ) ? $settings['title'] : '';
			$sub_title  = !empty( $settings['sub_title'] ) ? $settings['sub_title'] : '';
			$desc       = !empty( $settings['description'] ) ? $settings['description'] : '';
			$btn_title  = !empty( $settings['btn_title'] ) ? $settings['btn_title'] : '';
			$show_posts = !empty( $settings['show_posts']['size'] ) ? $settings['show_posts']['size'] : 6;
			$layout  	= !empty( $settings['layout'] ) ? $settings['layout'] : 'three';
			$listing_type   = !empty( $settings['listing_type'] ) ? $settings['listing_type'] : '';
			$service_by		= !empty( $settings['service_by'] ) ? $settings['service_by']  : '';
			
			$catgories		= !empty( $settings['services'] ) ? $settings['services']  : '';
			$catgories		= is_array($catgories) ? $catgories : array($catgories);
			
			$width			= 352;
			$height			= 200;
			$flag 			= rand(9999, 999999);

			$tax_queries            = array();
			$meta_queries			= array();
			$product_type_tax_args[] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => 'tasks',
			  );
			$tax_queries 	= array_merge($tax_queries,$product_type_tax_args);
			if( !empty($catgories) ){
				$product_cat_tax_args[] = array(
					'taxonomy'  => 'product_cat',
					'terms'     => $catgories,
					'field'     => 'term_id',
					'operator'  => 'IN',
				);
				$tax_queries = array_merge($tax_queries, $product_cat_tax_args);
			}

			
			$micro_services 	= array(
				'post_type'         => 'product',
				'post_status'       => 'publish',
				'orderby' 	 	  	=> 'ID',
				'order' 	 	  	=> 'ASC',
				'posts_per_page'    => $show_posts,
				'tax_query'         => $tax_queries,
				'meta_query'        => $meta_queries,
			);
			if(!empty($service_by)){
				$micro_services['post__in'] = explode(',',$service_by);
			}
			$service_data     = new \WP_Query($micro_services);

			$is_rtl = 'false';
			if( is_rtl() ){
				$is_rtl = 'true';
			}
			?>
			<div class="wt-sc-micro-services wt-featuredservicescarousel wt-haslayout">
				<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $desc ) ) {?>
					<div class="row justify-content-md-center">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
							<div class="wt-sectionhead wt-textcenter wt-topservices-title">
								<div class="wt-sectiontitle">
									<?php if( !empty( $sub_title ) ) { ?><span><?php echo esc_html( $sub_title);?></span><?php } ?>
									<?php if( !empty( $title ) ) { ?><h2><?php echo esc_html( $title );?></h2><?php } ?>
								</div>
								<?php if( !empty( $desc ) ) { ?>
									<div class="wt-description">
										<p><?php echo do_shortcode( $desc ) ;?></p>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div id="wt-services-silder-<?php echo esc_js($flag);?>" class="wt-services-silder owl-carousel">
						<?php 
						if ($service_data->have_posts()) {
							while( $service_data->have_posts() ) { 
								$service_data->the_post();
								global $post;
								
							?>
							<div class="wt-services-grid">
								<div class="wt-freelancers wt-freelancers-services-<?php echo esc_attr($flag);?>">
									<?php do_action( 'workreap_listing_task_html_v1', $post->ID );?>
								</div>
							</div>
						<?php } wp_reset_postdata();?>
						<?php }?>
					</div>
					<?php if( !empty( $page_link ) && !empty( $btn_title ) ) {?>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
							<div class="wt-btnarea btn-viewservices">
								<a href="<?php echo esc_url( $page_link );?>" class="wt-btn"><?php echo esc_html($btn_title);?></a>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<script type="application/javascript">
				
				window.onload = function () {
					jQuery("#wt-services-silder-<?php echo esc_js($flag);?>").owlCarousel({
						item: 5,
						rtl: <?php echo esc_attr($is_rtl);?>,
						loop:false,
						nav:false,
						margin: 30,
						autoplay:true,
						dots: true,
						autoHeight:true,
						dotsClass: 'wt-sliderdots',
						responsiveClass:true,
						responsive:{
							0:{items:1,},
							680:{items:2,},
							1081:{items:3,},
							1440:{items:4,},
							1760:{items:5,}
						}
					});
				};
			</script>
			
			<?php 
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

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Services_Carousel ); 
}