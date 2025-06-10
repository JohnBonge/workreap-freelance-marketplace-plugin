<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_Mac_Book') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Mac_Book extends Widget_Base {

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
			return 'workreap-mac-book';
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
			return __( 'Mac Book', 'workreap' );
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
			return 'eicon-slider-device';
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
			return array('device','mockup','slider');
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
			return array( 'workreap-elements' );
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
				'mockup_content_type',
				[
					'label' => esc_html__( 'Mockup Content', 'workreap' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'image',
					'options' => [
						'image' => esc_html__('Image', 'workreap'),
						'video' => esc_html__('Video', 'workreap'),
						'slides' => esc_html__('Slides', 'workreap'),
					],
					'render_type'        => 'template',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'mockup_content_image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Upload Image', 'workreap' ),
					'condition' => [
						'mockup_content_type' => array('image'),
					],
				]
			);

			$this->add_control(
				'mockup_content_video',
				[
					'type'          => Controls_Manager::MEDIA,
					'label'     	=> esc_html__( 'Add Video', 'workreap' ),
					'media_types'   => ['video'],
					'condition' => [
						'mockup_content_type' => array('video'),
					],
				]
			);

			$this->add_control(
				'mockup_content_slides',
				[
					'type'      	=> Controls_Manager::GALLERY,
					'label' 		=> esc_html__('Slides', 'workreap'),
					'condition' => [
						'mockup_content_type' => array('slides'),
					],
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'      => 'thumbnail',
					'default'   => 'full',
					'separator' => 'before',
					'exclude'   => array(
						'custom',
					),
					'condition' => [
						'mockup_content_type' => array('image','slides'),
					],
				)
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

			$this->end_controls_section();

			$this->start_controls_section(
				'section_general_style',
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
					'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-frame > img' => 'width: {{SIZE}}{{UNIT}};',
					),
					'render_type'        => 'template',
				)
			);

			$this->add_responsive_control(
				'content_position',
				array(
					'label'                => __( 'Content Position', 'workreap' ),
					'type'                 => Controls_Manager::SELECT,
					'default'              => 'center-center',
					'options'              => array(
						'center-center' => __( 'Center Center', 'workreap' ),
						'center-left'   => __( 'Center Left', 'workreap' ),
						'center-right'  => __( 'Center Right', 'workreap' ),
						'top-center'    => __( 'Top Center', 'workreap' ),
						'top-left'      => __( 'Top Left', 'workreap' ),
						'top-right'     => __( 'Top Right', 'workreap' ),
						'bottom-center' => __( 'Bottom Center', 'workreap' ),
						'bottom-left'   => __( 'Bottom Left', 'workreap' ),
						'bottom-right'  => __( 'Bottom Right', 'workreap' ),
					),
					'selectors_dictionary' => array(
						'center-center' => 'center center',
						'center-left'   => 'center left',
						'center-right'  => 'center right',
						'top-center'    => 'top center',
						'top-left'      => 'top left',
						'top-right'     => 'top right',
						'bottom-center' => 'bottom center',
						'bottom-left'   => 'bottom left',
						'bottom-right'  => 'bottom right',
					),
					'selectors'            => array(
						'{{WRAPPER}} .wr-frame_content > video,{{WRAPPER}} .wr-frame_content img' => 'object-position: {{VALUE}};',
					),
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
			?>
			<div class="wr-frame">
				<img src="<?php echo esc_url(WORKREAP_DIRECTORY_URI.'/public/images/mackbook-mockup-01.png'); ?>" alt="<?php echo esc_attr__('Mackbook mockup','workreap') ?>">
				<div class="wr-frame_content wr-frame_content-type-<?php echo esc_attr($settings['mockup_content_type']); ?>">
					<?php if($settings['mockup_content_type'] === 'video' && !empty($settings['mockup_content_video']['url']) ): ?>
						<video src="<?php echo esc_url($settings['mockup_content_video']['url']); ?>" autoplay="" muted="" loop=""></video>
					<?php endif; ?>
					<?php if($settings['mockup_content_type'] === 'image' && !empty($settings['mockup_content_image']['url']) ):
                          echo wp_get_attachment_image( $settings['mockup_content_image']['id'], $settings['thumbnail_size'] );
                     endif; ?>
					<?php if($settings['mockup_content_type'] === 'slides' && !empty($settings['mockup_content_slides']) ): ?>
						<div id="wr-macbook-slider-<?php echo esc_attr($this->get_id()); ?>" class="swiper swiper-container wr-macbook-slider">
							<div class="swiper-wrapper">
								<?php foreach ($settings['mockup_content_slides'] as $gallery): ?>
									<div class="swiper-slide">
                                        <?php if(!empty($gallery['id'])) {
	                                        echo wp_get_attachment_image( $gallery['id'], $settings['thumbnail_size'] );
                                        } ?>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Mac_Book);
}
