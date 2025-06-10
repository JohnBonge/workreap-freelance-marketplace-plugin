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

if( !class_exists('Workreap_Terms') ){
	class Workreap_Terms extends Widget_Base {
		
		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_terms';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Terms And Conditions', 'workreap' );
		}
		
		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-product-description';
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
					'label'     	=> esc_html__( 'Heading', 'workreap' ),
					'description'   => esc_html__( 'Add section heading. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap' ),
					'description'   => esc_html__( 'Add section description. Leave it empty to hide.', 'workreap' ),
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

			$title      = !empty($settings['title']) ? $settings['title'] : '';
			$desc  	    = !empty($settings['description']) ? $settings['description'] : '';
			?>
			<div class="wt-terms-pages wt-haslayout">
				<?php  if( !empty( $title ) ) { ?>
					<div class="wt-submitreportholder wt-bgwhite">
						<div class="wt-titlebar">
							<h2><?php echo esc_html($title);?></h2>
						</div>
						<div class="wt-reportdescription">
							<?php echo wp_kses_post( wpautop( do_shortcode( $desc ) ) ); ?>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Terms ); 
}