<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_Creative_Testimonial') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Creative_Testimonial extends Widget_Base {

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
			return 'workreap-creative-testimonial';
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
			return __( 'Creative Testimonial', 'workreap' );
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
			return 'eicon-testimonial';
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
			return array('testimonial','slider');
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
			return array();
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

			$repeater = new Repeater();

			$repeater->add_control(
				'image',
				array(
					'label'   => __( 'Image', 'workreap' ),
					'type'    => Controls_Manager::MEDIA,
					'dynamic' => array(
						'active' => true,
					),
				)
			);

			$repeater->add_control(
				'description',
				array(
					'label'       => __( 'Description', 'workreap' ),
					'type'        => Controls_Manager::WYSIWYG,
					'label_block' => true,
					'default'     => __( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.', 'workreap' ),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$repeater->add_control(
				'rating',
				array(
					'label'      => __( 'Rating', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'default'    => array(
						'unit' => 'px',
						'size' => 4,
					),
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
					'dynamic'    => array(
						'active' => true,
					),
				)
			);

			$repeater->add_control(
				'name',
				array(
					'label'       => __( 'Name', 'workreap' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => __( 'Name', 'workreap' ),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$repeater->add_control(
				'designation',
				array(
					'label'       => __( 'Designation', 'workreap' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => __( 'Designation', 'workreap' ),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$this->add_control(
				'items',
				array(
					'label'       => __( 'Testimonials', 'workreap' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ name }}}',
					'separator'    => 'before',
					'default'     => array(
						array(
							'name' => 'Name #1',
						),
						array(
							'name' => 'Name #2',
						),
						array(
							'name' => 'Name #3',
						),
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_general',
				array(
					'label' => __( 'General', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'quote_size',
				array(
					'label'      => __( 'Quote Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-description svg' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'rating_size',
				array(
					'label'      => __( 'Star Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-content .wr-feedback-slider-ratting'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'star_color',
				array(
					'label'     => __( 'Star Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-ratting i:not(.wr-rating-filled)' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'star_fill_color',
				array(
					'label'     => __( 'Star Fill Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-ratting .wr-rating-filled' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'image_border_color',
				array(
					'label'     => __( 'Image Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slide .wr-feedback-item:nth-child(1),
						{{WRAPPER}} .wr-feedback-slide .wr-feedback-item:nth-child(2)' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_desc',
				array(
					'label' => __( 'Description', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'desc_width',
				array(
					'label'      => __( 'Width', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-description-content' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
					)
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'desc_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-feedback-slider-description-content, {{WRAPPER}} .wr-feedback-slider-description-content > *',
				)
			);

			$this->add_control(
				'desc_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-description-content, {{WRAPPER}} .wr-feedback-slider-description-content > *' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'desc_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-description-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_name',
				array(
					'label' => __( 'Name', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'name_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-feedback-slider-ratting .wr-name',
				)
			);

			$this->add_control(
				'name_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-ratting .wr-name' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'name_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-ratting .wr-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_designation',
				array(
					'label' => __( 'Designation', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'designation_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-feedback-slider-ratting .wr-designation',
				)
			);

			$this->add_control(
				'designation_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-ratting .wr-designation' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'designation_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-ratting .wr-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_arrows',
				array(
					'label' => __( 'Arrows', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'arrow_icon_size',
				array(
					'label'      => __( 'Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'arrow_bg_size',
				array(
					'label'      => __( 'Background Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button'   => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'arrow_space_between',
				array(
					'label'      => __( 'Space Between', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-button'   => 'gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_arrow' );

			$this->start_controls_tab(
				'tab_arrow_normal',
				array(
					'label' => __( 'Normal', 'workreap' ),
				)
			);

			$this->add_control(
				'arrow_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-feedback-slider-button > button > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-feedback-slider-button > button > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'arrow_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_arrow_hover',
				array(
					'label' => __( 'Hover', 'workreap' ),
				)
			);

			$this->add_control(
				'arrow_hover_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-feedback-slider-button > button:hover > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-feedback-slider-button > button:hover > svg' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'arrow_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button:hover' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .wr-feedback-slider-button > button::before' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'arrow_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'arrow_border',
					'selector'  => '{{WRAPPER}} .wr-feedback-slider-button > button',
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'arrow_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-button > button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'arrow_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-feedback-slider-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            <div class="wr-creative-testimonial-wrapper">
                <div class="wr-feedback-slider-container">
                    <div class="wr-feedback-slide">
	                <?php foreach ($settings['items'] as $i => $item){
		                $attachment_url = isset($item['image']['id']) && !empty($item['image']['id']) ? wp_get_attachment_image_url( $item['image']['id'], 'full' ) : '';
		                if(empty($attachment_url)){
			                $attachment_url = isset($item['image']['url']) && !empty($item['image']['url']) ? $item['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
		                } ?>
                        <div class="wr-feedback-item" style="background-image: url('<?php echo esc_url($attachment_url); ?>');">
                            <div class="wr-feedback-slider-content">
                                <div class="wr-feedback-slider-description">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 46 58">
                                        <path fill="#ee4710" d="M0 12.836C0 6.161 5.411.75 12.086.75c6.676 0 12.087 5.411 12.087 12.086V28.74L0 41.462V12.836Z"/>
                                        <path fill="#EAEAEA" stroke="#F7F7F8" stroke-width="4" d="M15.727 50.75v3.313l2.931-1.543 13.488-7.099A22 22 0 0 0 43.9 25.953v-3.829c0-7.78-6.307-14.086-14.087-14.086-7.78 0-14.086 6.306-14.086 14.086V50.75Z"/>
                                    </svg>
                                    <div class="wr-feedback-slider-description-content">
						                <?php echo wp_kses_post($item['description']); ?>
                                    </div>
                                </div>
                                <div class="wr-feedback-slider-ratting">
                                    <div class="wr-feedback-slider-ratting">
						                <?php
						                for ( $x = 1; $x <= 5; $x++ ) {
							                if ( isset($item['rating']['size']) && $x <= $item['rating']['size'] ) {
								                echo '<i class="fas fa-star wr-rating-filled"></i>';
							                } else {
								                echo '<i class="fas fa-star"></i>';
							                }
						                }
						                ?>
                                    </div>
					                <?php if(isset($item['name']) && $item['name']){ ?>
                                        <h5 class="wr-name"><?php echo esc_html($item['name']) ?></h5>
					                <?php }?>
					                <?php if(isset($item['designation']) && $item['designation']){ ?>
                                        <p class="wr-designation"><?php echo esc_html($item['designation']) ?></p>
					                <?php }?>
                                </div>
                            </div>
                        </div>
	                <?php } ?>
                    </div>
                    <div class="wr-feedback-slider-button">
                        <button class="prev wr-secondary-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="next wr-secondary-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
		<?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Creative_Testimonial);
}
