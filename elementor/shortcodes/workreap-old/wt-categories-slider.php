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

if( !class_exists('Workreap_Category_Slider') ){
	class Workreap_Category_Slider extends Widget_Base {

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
			return 'wt_element_category_slider';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Category Slider', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-slider-full-screen';
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
					'default' => 'project_search_page',
					'options' => [
						'project_search_page' => esc_html__('Projects', 'workreap'),
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

			$section_heading     	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$categories          	= !empty($settings['categories']) ? $settings['categories'] : array();
			$post_type           	= !empty($settings['post_type']) ? $settings['post_type'] : 'project_search_page';
			$search_page			= '';
			$taxonomy_type			= 'product_cat';

			if(!empty($categories)){
				$categories = get_terms( array(
					'taxonomy' 		=> $taxonomy_type,
					'hide_empty' 	=> false,
					'include'       => $categories,
				) );
			}else{
				$categories = get_terms( array(
					'taxonomy' 		=> $taxonomy_type,
					'hide_empty' 	=> false,
					'number'        => 50,
				) );
			}

			if(is_wp_error($categories)){$categories = array();}

			if( function_exists('workreap_get_page_uri') ){
				$search_page  = workreap_get_page_uri($post_type);
			}
			
			$uniq_flag  			= rand(9999, 999999);
			if( is_rtl() ) {
				$is_rtl	= 'true';
			} else {
				$is_rtl	= 'false';
			}
			?>
			<div class="wt-sc-categories-freelancer">
				<div class="wt-categoriesslider-holder wt-haslayout">
					<?php if(!empty($section_heading) ) {?>
						<div class="wt-title">
							<h2><?php echo esc_html($section_heading);?>&nbsp;</h2>
						</div>
					<?php }?>
					<?php if(!empty($categories) && count($categories)>0 ) {?>
						<div id="wt-categoriesslider-<?php echo esc_attr($uniq_flag); ?>" class="wt-categoriesslider owl-carousel">
							<?php foreach( $categories as $key => $category ) { 
								if(!empty($category)){
									$icon          				= array();
									$thumbnail_id 				= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
									$image        				= !empty($thumbnail_id) ? wp_get_attachment_url( $thumbnail_id ) : '';

									$query_arg['category']   	= urlencode($category->slug);
									$permalink                 	= add_query_arg( $query_arg, esc_url($search_page));
									?>
									<div class="wt-categoryslidercontent item">
										<?php if (!empty($image)) {?>
												<figure><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>"></figure>
										<?php } ?>
										<div class="wt-cattitle">
											<h3><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_html($category->name); ?></a></h3>
											<?php if(!empty($category) ){ ?>
												<span><?php esc_html_e('Items','workreap'); ?>: <?php echo intval($category->count);?></span>
											<?php } ?>
										</div>
									</div>
							<?php }}?>
						</div>
					<?php }?>
				</div>
			</div>
			<script type="application/javascript">
				jQuery(document).ready(function () {
					var _wt_categoriesslider = jQuery('#wt-categoriesslider-<?php echo esc_js($uniq_flag);?>');
					
					_wt_categoriesslider.owlCarousel({
						item: 6,
						loop:false,
						nav:false,
						margin: 0,
						rtl: <?php echo esc_attr($is_rtl);?>,
						autoplay:true,
						center: false,
						responsiveClass:true,
						responsive:{
							0:{items:1,},
							481:{items:2,},
							768:{items:3,},
							1440:{items:4,},
							1760:{items:6,}
						}
					});
				});
			</script>
	<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Category_Slider ); 
}