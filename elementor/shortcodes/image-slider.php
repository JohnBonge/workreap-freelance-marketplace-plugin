<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_Image_Slider') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Image_Slider extends Widget_Base {

		/**
		 * Get widget name.
		 *
		 * Retrieve image widget name.
		 *
		 * @return string Widget name.
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function get_name() {
			return 'workreap-image-slider';
		}

		/**
		 * Get widget title.
		 *
		 * Retrieve image widget title.
		 *
		 * @return string Widget title.
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function get_title() {
			return __( 'Image Slider', 'workreap' );
		}

		/**
		 * Get widget icon.
		 *
		 * Retrieve image widget icon.
		 *
		 * @return string Widget icon.
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function get_icon() {
			return 'eicon-slider-vertical';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the image widget belongs to.
		 *
		 * Used to determine where to display the widget in the editor.
		 *
		 * @return array Widget categories.
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function get_categories() {
			return array( 'workreap-elements' );
		}

		/**
		 * Get widget keywords.
		 *
		 * Retrieve the list of keywords the widget belongs to.
		 *
		 * @return array Widget keywords.
		 * @since 1.0.0
		 * @access public
		 *
		 */
		public function get_keywords() {
			return array('image','slider');
		}

		/**
		 * Retrieve the list of style the widget depended on.
		 *
		 * Used to set style dependencies required to run the widget.
		 *
		 * @return array Widget style dependencies.
		 * @since 1.0.0
		 *
		 * @access public
		 *
		 */
		public function get_style_depends() {
			return array( 'swiper' );
		}

		/**
		 * Retrieve the list of scripts the widget depended on.
		 *
		 * Used to set scripts dependencies required to run the widget.
		 *
		 * @return array Widget scripts dependencies.
		 * @since 1.0.0
		 *
		 * @access public
		 *
		 */
		public function get_script_depends() {
			return array('workreap-elements');
		}

		/**
		 * Register widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function register_controls() {

			$this->start_controls_section(
				'section_general',
				array(
					'label' => esc_html__( 'General', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'slides',
				[
					'type'      	=> Controls_Manager::GALLERY,
					'label' 		=> esc_html__('Slides', 'workreap'),
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'      => 'thumbnail',
					'default'   => 'full',
					'exclude'   => array(
						'custom',
					),
				)
			);

			$this->add_control(
				'orientation',
				[
					'label' => esc_html__( 'Orientation', 'workreap' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal' => esc_html__('Horizontal', 'workreap'),
						'vertical' => esc_html__('Vertical', 'workreap'),
					],
					'render_type'        => 'template',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect',
				[
					'label' => esc_html__( 'Effect', 'workreap' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'slide',
					'options' => [
						'slide' => esc_html__('Slide', 'workreap'),
						'fade' => esc_html__('Fade', 'workreap'),
					],
					'render_type'        => 'template',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'slide_speed',
				array(
					'label'       => __( 'Slide Speed', 'workreap' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'default'     => array(
						'size' => 2,
					),
					'range'       => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'render_type' => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'autoplay_timeout',
				array(
					'label'       => __( 'Autoplay Timeout', 'workreap' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'default'     => array(
						'size' => 5,
					),
					'range'       => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'render_type' => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'enable_dots',
				[
					'label'        => esc_html__( 'Show Dots', 'workreap' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Hide', 'workreap' ),
					'label_off' 	=> esc_html__( 'Show', 'workreap' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_responsive_control(
				'dots_offset',
				array(
					'label'      => __( 'Dots Offset', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'default'     => array(
						'size' => 20,
					),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						)
					),
					'condition' => array(
						'enable_dots' => 'yes',
					),
					'selectors'  => array(
						'{{WRAPPER}} .swiper-pagination-horizontal.swiper-pagination-bullets' => 'margin-top: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .swiper-pagination-vertical.swiper-pagination-bullets' => 'right: -{{SIZE}}{{UNIT}};',
						'body.rtl {{WRAPPER}} .swiper-pagination-vertical.swiper-pagination-bullets' => 'right:auto; left: -{{SIZE}}{{UNIT}};',
					)
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_image_style',
				array(
					'label' => __( 'General', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'alignment',
				array(
					'label'        => __( 'Alignment', 'workreap' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => array(
						'left'   => array(
							'title' => __( 'Left', 'workreap' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => __( 'Center', 'workreap' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'  => array(
							'title' => __( 'Right', 'workreap' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'separator'    => 'before',
					'default'      => 'center',
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'image_width',
				array(
					'label'      => __( 'Width', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'default'     => array(
						'size' => 300,
					),
					'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-image-slider' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
						'{{WRAPPER}} .swiper-slide > img' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
					)
				)
			);

			$this->add_responsive_control(
				'image_height',
				array(
					'label'      => __( 'Height', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'default'     => array(
						'size' => 300,
					),
                    'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-image-slider' => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .swiper-slide > img' => 'height: {{SIZE}}{{UNIT}};',
					),
					'render_type'        => 'template',
				)
			);

			$this->add_responsive_control(
				'object-fit',
				array(
					'label'     => __( 'Object Fit', 'workreap' ),
					'type'      => Controls_Manager::SELECT,
					'condition' => array(
						'image_height[size]!' => '',
					),
					'options'   => array(
						''        => __( 'Default', 'workreap' ),
						'fill'    => __( 'Fill', 'workreap' ),
						'cover'   => __( 'Cover', 'workreap' ),
						'contain' => __( 'Contain', 'workreap' ),
					),
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .swiper-slide > img' => 'object-fit: {{VALUE}};',
					),
					'render_type'        => 'template',
				)
			);

			$this->end_controls_section();

		}

		/**
		 * Render image widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();
			$rtl = is_rtl() ? 'rtl' : 'ltr';
            ?>
            <div dir="<?php echo esc_attr($rtl); ?>" class="wr-image-slider-wrapper wr-image-slider-orientation-<?php echo esc_attr($settings['orientation']); ?>">
                <div class="swiper swiper-container wr-image-slider">
                    <div class="swiper-wrapper">
		                <?php foreach ($settings['slides'] as $slide): ?>
                            <div class="swiper-slide">
				                <?php if(!empty($slide['id'])) {
					                echo wp_get_attachment_image( $slide['id'], $settings['thumbnail_size'] );
				                } ?>
                            </div>
		                <?php endforeach; ?>
                    </div>
                </div>
	            <?php if($settings['enable_dots'] === 'yes'){?>
                    <div class="swiper-pagination"></div>
	            <?php } ?>
            </div>
		<?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Image_Slider);
}
