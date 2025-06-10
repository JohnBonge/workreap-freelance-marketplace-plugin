<?php
/**
 * Shortcode for categories v3
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

if( !class_exists('WorkreapPopularServices') ){
	class WorkreapPopularServices extends Widget_Base {

		public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);
			wp_enqueue_style('owl.carousel');
            wp_enqueue_script('owl.carousel');
        }

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wtElementPoupularServices';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Explore popular services', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-product-categories';
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
			
			if( function_exists('workreap_elementor_get_taxonomies') ){
                $categories = workreap_elementor_get_taxonomies('product', 'product_cat');
            }
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
				'section_heading',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Heading', 'workreap' ),
					'description'   => esc_html__( 'Add section heading. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'post_type',
				[
					'label' => esc_html__( 'Post Type?', 'workreap' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'service_search_page',
					'options' => [
						'project_search_page' => esc_html__('Jobs', 'workreap'),
						'service_search_page' => esc_html__('Services', 'workreap'),
					],
				]
			);
			
			$this->add_control(
				'categories',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Project Categories?', 'workreap'),
					'desc' 			=> esc_html__('Select categories to display.', 'workreap'),
					'options'   	=> $categories,
					'multiple' 		=> true,
					'label_block' 	=> true,
				]
			);

			$this->add_control(
				'version',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label'     	=> esc_html__('Select version','workreap' ),
					'description'   => esc_html__('Select version', 'workreap' ),
					'default' 		=> 'v1',
					'options' 		=> [
										'v1' => esc_html__('V1', 'workreap'),
										'v2' => esc_html__('V2', 'workreap'),
										],
				]
			);

			$this->add_control(
				'btn_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore button text', 'workreap'),
					'rows' 			=> 5,
					'description' 	=> esc_html__('Add text. leave it empty to hide.', 'workreap'),
					'condition'		=> [
						'version'	=> 'v2'
					]
				]
			);
			$this->add_control(
				'btn_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Explore button URL', 'workreap'),
					'description' 	=> esc_html__('Add url. leave it empty to hide.', 'workreap'),
					'condition'		=> [
						'version'	=> 'v2'
					]
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
			$settings 			= $this->get_settings_for_display();
			$section_heading	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$categories         = !empty($settings['categories']) ? $settings['categories'] : array();
			$version			= !empty($settings['version']) ? $settings['version'] : 'v1';
			$post_type          = !empty($settings['post_type']) ? $settings['post_type'] : 'project_search_page ';
			
			$flag 				= rand(9999, 999999);
			
			$search_page	= '';
			if( function_exists('workreap_get_page_uri') ){
				$search_page  = workreap_get_page_uri($post_type);
			}

			$is_rtl	= 'false';
			if (is_rtl()) {
				$is_rtl	= 'true';
			}

			if( !empty($version) && $version == 'v1' ) { ?>
				<div class="wt-popularservice-section">
					<div class="row">
						<div class="col-12">
							<?php if( !empty($section_heading) ){?>
								<div class="wt-sectionhead wt-sectionheadvfour">
									<div class="wt-sectiontitle wt-sectiontitlevthree">
										<h2><?php echo esc_html($section_heading);?></h2>
									</div>
								</div>
							<?php } ?>
							<?php if( !empty($categories) ){?>
								<div id="wt-ourservices-<?php echo intval($flag);?>" class="wt-ourservices owl-carousel">
									<?php 
										foreach( $categories as $key => $cat_id ) {
											$query_arg     	= array();
											$term_data		= get_term($cat_id);
											$count			= !empty($term_data->count) ? intval($term_data->count) : 0;
											$term_name		= !empty($term_data->name) ? $term_data->name : '';
											$thumbnail_id	= get_woocommerce_term_meta( $cat_id, 'thumbnail_id', true );
											$image        	= !empty($thumbnail_id) ? wp_get_attachment_url( $thumbnail_id ) : '';
											
											
											
											$query_arg['category']   	= urlencode($term_data->slug);
											$permalink                  = add_query_arg( $query_arg, esc_url($search_page));
											
											if (!empty($image)) { ?>
											<figure class="wt-ourservices__item">
												<img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($term_name);?>">
												<?php if( !empty($term_name) || !empty($count) ){?>
													<figcaption>
														<h3><a href="<?php echo esc_url($permalink);?>"><?php echo esc_html($term_name);?></a></h3>
														<span><a href="<?php echo esc_url($permalink);?>"><?php echo sprintf(esc_html__('%s Listings','workreap'),$count);?></a></span>
													</figcaption>
												<?php } ?>
											</figure>
										<?php } ?>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<script>
					jQuery(document).ready(function () {
						var _wt_ourservices = jQuery("#wt-ourservices-<?php echo esc_js($flag);?>");
						_wt_ourservices.owlCarousel({
						items: 4,
						rtl: <?php echo esc_js($is_rtl);?>,
						loop: true,
						nav: true,
						autoplay: false,
						dots: false,
						margin: 30,
						smartSpeed: 500,
						responsiveClass: true,
						navClass: ["wr-prev", "wr-next"],
						navContainerClass: "wr-slidernav",
						navText: [
							'<span><i class="wr-icon-chevron-left"></i></span>',
							'<span><i class="wr-icon-chevron-right"></i></span>',
						],
						responsive:{
						0:{
							items:1,
						},
						480:{
							items:2,
						},
						767:{
							items:3,
						},
						991:{
							items:4,
						},
						}
						});
					});
				</script>
			<?php } elseif ( !empty($version) && $version == 'v2' ) {
				$btn_text			= !empty($settings['btn_text']) ? $settings['btn_text'] : '';
				$btn_url			= !empty($settings['btn_url']) ? $settings['btn_url'] : ''; ?>
				<div class="row">
                    <div class="col-12">
						<?php if( !empty($section_heading) ){?>
							<div class="wt-sectionhead wt-sectionheadvfour">
								<div class="wt-sectiontitle wt-sectiontitlevthree">
									<h2><?php echo esc_html($section_heading);?></h2>
								</div>
							</div>
						<?php } ?>
						<?php if( !empty($categories) ){?>
							<div class="wt-categorieslist">
								<ul>
									<?php
										foreach( $categories as $key => $cat_id ) {
											$query_arg     = array();
											$thumbnail_id	= get_woocommerce_term_meta( $cat_id, 'thumbnail_id', true );
											$image        	= !empty($thumbnail_id) ? wp_get_attachment_url( $thumbnail_id ) : '';
											
											$term_data		= get_term($cat_id);
											$count			= !empty($term_data->count) ? intval($term_data->count) : 0;
											$term_name		= !empty($term_data->name) ? $term_data->name : '';
											

											$query_arg['category']   	= urlencode($term_data->slug);
											$permalink                 	= add_query_arg( $query_arg, esc_url($search_page));
											$description				= !empty($term_data->description) ? $term_data->description : "";
										?>
										<li>
											<div class="wt-categories">
												<?php
													if (!empty($image)) {?>
														<span><figure><img src="<?php echo esc_url($image); ?>" alt="<?php esc_attr_e('Category','workreap'); ?>"></figure></span>
												<?php
													}
												?>
												<?php if( !empty($term_name) ){?>
													<h3>
														<a href="<?php echo esc_url($permalink);?>"><?php echo esc_html($term_name);?></a>
														<span><?php echo sprintf(esc_html__('%s Listings','workreap'),$count);?></span>
													</h3>
												<?php } ?>
												<?php if( !empty($description) ){?>
													<p><?php echo esc_html($description);?></p>
												<?php } ?>
												<a href="<?php echo esc_url($permalink);?>" class="wt-btn wt-btnv2"><?php esc_html_e('Explore','workreap');?> <i class="wr-arrow-right"></i></a>
											</div>
										</li>
									<?php } ?>
								</ul>
								<?php if( !empty($btn_text) ){?>
									<div class="wt-sectionbtn">
										<a href="<?php echo esc_url($btn_url);?>" class="wt-btn wt-btnv2"><?php echo esc_html($btn_text);?></a>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
                    </div>
                </div>
			<?php }
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new WorkreapPopularServices ); 
}