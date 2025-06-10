<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Workreap_Button' ) ) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Button extends Widget_Base {

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
			return 'workreap-button';
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
			return __( 'Creative Button', 'workreap' );
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
			return 'eicon-button';
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
			return array( 'button', 'action' );
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
			return array();
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
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'layout',
				[
					'type'               => Controls_Manager::SELECT,
					'label'              => esc_html__( 'Layout', 'workreap' ),
					'options'            => [
						'primary'   => esc_html__( 'Style 1', 'workreap' ),
						'secondary' => esc_html__( 'Style 2', 'workreap' ),
					],
					'default'            => 'primary',
				]
			);

			$this->add_control(
				'button_text',
				[
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Button Text', 'workreap' ),
					'default'     => esc_html__( 'Find a Better Job', 'workreap' ),
					'label_block' => true,
				]
			);

			$this->add_control(
				'button_icon',
				[
					'label'                  => esc_html__( 'Button Icon', 'workreap' ),
					'type'                   => Controls_Manager::ICONS,
					'skin'                   => 'inline',
					'label_block'            => false,
					'exclude_inline_options' => 'svg',
				]
			);

			$this->add_responsive_control(
				'button_spacing',
				array(
					'label'     => __( 'Icon Spacing', 'workreap' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'max' => 50,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn' => 'gap: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'button_icon[value]!' => '',
					),
				)
			);

			$this->add_control(
				'button_link',
				array(
					'label'       => __( 'Button URL', 'workreap' ),
					'type'        => Controls_Manager::URL,
					'label_block' => true,
					'dynamic'     => array(
						'active' => true,
					),
					'default'     => array(
						'url' => '#',
					),
				)
			);

			$this->end_controls_section();

			//Styling Tab
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
					'label'     => __( 'Alignment', 'workreap' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
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
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'button',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-button-wrapper .wr-btn',
				)
			);

			$this->add_responsive_control(
				'button_width',
				array(
					'label'      => __( 'Width', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 1000,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-button-wrapper' => 'max-width:100%;',
					)
				)
			);

			$this->add_responsive_control(
				'button_icon_size',
				array(
					'label'      => __( 'Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 5,
							'max' => 300,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-button-wrapper .wr-btn > svg' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'button_icon[value]!' => '',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_button' );

			$this->start_controls_tab(
				'tab_button_normal',
				array(
					'label' => __( 'Normal', 'workreap' ),
				)
			);

			$this->add_control(
				'button_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-button-wrapper .wr-btn > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-button-wrapper .wr-btn > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_button_hover',
				array(
					'label' => __( 'Hover', 'workreap' ),
				)
			);

			$this->add_control(
				'button_hover_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-button-wrapper .wr-btn:hover > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-button-wrapper .wr-btn:hover > svg' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn:hover' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .wr-button-wrapper .wr-secondary-btn::before' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'button_border',
					'selector'  => '{{WRAPPER}} .wr-button-wrapper .wr-btn',
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'button_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'button_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-button-wrapper .wr-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        <div class="wr-button-wrapper">
			<?php if ( $settings['button_text'] ):
				$btn_tag = ( $settings['button_link']['url'] ) ? 'a' : 'span';
				$attr = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
				$attr .= $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
				$attr .= $settings['button_link']['url'] ? ' href="' . $settings['button_link']['url'] . '"' : '';
				?>
                <<?php echo wp_kses_post( $btn_tag . $attr ); ?> class="wr-btn wr-<?php echo esc_attr($settings['layout']); ?>-btn">
                <span><?php echo esc_html( $settings['button_text'] ); ?></span>
				<?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </<?php echo wp_kses_post( $btn_tag ); ?>>
			<?php endif; ?>
            </div>
		<?php }
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Button );
}
