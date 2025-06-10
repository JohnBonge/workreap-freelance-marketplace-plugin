<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_Dual_Button') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Dual_Button extends Widget_Base {

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
			return 'workreap-dual-button';
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
			return __( 'Dual Button', 'workreap' );
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
			return 'eicon-dual-button';
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
			return array('dual','button','buttons');
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
				'section_primary',
				array(
					'label' => esc_html__( 'Primary', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'primary_button_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Text', 'workreap'),
					'default'       => esc_html__('Find a Better Job','workreap'),
					'label_block'   => true,
				]
			);

			$this->add_control(
				'primary_button_icon',
				[
					'label' => esc_html__( 'Button Icon', 'workreap' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'exclude_inline_options' => 'svg',
				]
			);

			$this->add_responsive_control(
				'primary_button_spacing',
				array(
					'label'     => __( 'Icon Spacing', 'workreap' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'max' => 50,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn' => 'gap: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'primary_button_icon[value]!' => '',
					),
				)
			);

			$this->add_control(
				'primary_button_link',
				array(
					'label'       => __( 'Button URL', 'workreap' ),
					'type'        => Controls_Manager::URL,
					'label_block' => true,
					'dynamic'     => array(
						'active' => true,
					),
					'default'     => array(
						'url'         => '#',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_secondary',
				array(
					'label' => esc_html__( 'Secondary', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'secondary_button_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Text', 'workreap'),
					'default'       => esc_html__('Learn More','workreap'),
					'label_block'   => true,
				]
			);

			$this->add_control(
				'secondary_button_icon',
				[
					'label' => esc_html__( 'Button Icon', 'workreap' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'exclude_inline_options' => 'svg',
				]
			);

			$this->add_responsive_control(
				'secondary_button_spacing',
				array(
					'label'     => __( 'Icon Spacing', 'workreap' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'max' => 50,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn' => 'gap: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'secondary_button_icon[value]!' => '',
					),
				)
			);

			$this->add_control(
				'secondary_button_link',
				array(
					'label'       => __( 'Button URL', 'workreap' ),
					'type'        => Controls_Manager::URL,
					'label_block' => true,
					'dynamic'     => array(
						'active' => true,
					),
					'default'     => array(
						'url'         => '#',
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
				'primary_button_width',
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
						'{{WRAPPER}} .wr-dual-button-wrapper > a' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-dual-button-wrapper' => 'max-width: 100%;',
					)
				)
			);

			$this->add_responsive_control(
				'space_between',
				array(
					'label'      => __( 'Space Between', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-dual-button-wrapper' => 'grid-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'stack_on',
				array(
					'label'   => __( 'Stack On', 'workreap' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'none'   => __( 'None', 'workreap' ),
						'tablet'   => __( 'Tablet', 'workreap' ),
						'mobile'   => __( 'Mobile', 'workreap' ),
					),
					'default' => 'mobile',
					'selectors'  => array(
						'(tablet){{WRAPPER}} .wr-dual-button-stack-tablet' => 'flex-direction: column;',
						'(mobile){{WRAPPER}} .wr-dual-button-stack-mobile' => 'flex-direction: column;',
                    )
				)
			);

			$this->add_responsive_control(
				'margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'separator'  => 'before',
					'selectors'  => array(
						'{{WRAPPER}} .wr-dual-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_primary_style',
				array(
					'label' => __( 'Primary', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'primary_button',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn',
				)
			);

			$this->add_responsive_control(
				'primary_button_icon_size',
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
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn > svg' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'primary_button_icon[value]!' => '',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_primary_button' );

			$this->start_controls_tab(
				'tab_primary_button_normal',
				array(
					'label' => __( 'Normal', 'workreap' ),
				)
			);

			$this->add_control(
				'primary_button_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'primary_button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_primary_button_hover',
				array(
					'label' => __( 'Hover', 'workreap' ),
				)
			);

			$this->add_control(
				'primary_button_hover_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn:hover > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn:hover > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'primary_button_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'primary_button_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'primary_button_border',
					'selector'  => '{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn',
					'separator'  => 'before',
				)
			);

			$this->add_responsive_control(
				'primary_button_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'primary_button_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_secondary_style',
				array(
					'label' => __( 'Secondary', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'secondary_button',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn',
				)
			);

			$this->add_responsive_control(
				'secondary_button_icon_size',
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
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn > svg' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'secondary_button_icon[value]!' => '',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_secondary_button' );

			$this->start_controls_tab(
				'tab_secondary_button_normal',
				array(
					'label' => __( 'Normal', 'workreap' ),
				)
			);

			$this->add_control(
				'secondary_button_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn > 1' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'secondary_button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_secondary_button_hover',
				array(
					'label' => __( 'Hover', 'workreap' ),
				)
			);

			$this->add_control(
				'secondary_button_hover_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover > svg' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'secondary_button_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .wr-button-wrapper .wr-secondary-btn::before' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'secondary_button_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'secondary_button_border',
					'selector'  => '{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn',
					'separator'  => 'before',
				)
			);

			$this->add_responsive_control(
				'secondary_button_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'secondary_button_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		<div class="wr-dual-button-wrapper">
			<?php if($settings['primary_button_text']):
				$primary_btn_tag = ( $settings['primary_button_link']['url'] ) ? 'a' : 'span';
				$primary_attr     = $settings['primary_button_link']['is_external'] ? ' target="_blank"' : '';
				$primary_attr    .= $settings['primary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
				$primary_attr    .= $settings['primary_button_link']['url'] ? ' href="' . $settings['primary_button_link']['url'] . '"' : '';
				?>
				<<?php echo wp_kses_post($primary_btn_tag . $primary_attr); ?> class="wr-btn wr-primary-btn">
				<span><?php echo esc_html($settings['primary_button_text']); ?></span>
				<?php \Elementor\Icons_Manager::render_icon( $settings['primary_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</<?php echo wp_kses_post($primary_btn_tag); ?>>
			<?php endif; ?>
			<?php if($settings['secondary_button_text']):
				$secondary_btn_tag = ( $settings['secondary_button_link']['url'] ) ? 'a' : 'span';
				$secondary_attr     = $settings['secondary_button_link']['is_external'] ? ' target="_blank"' : '';
				$secondary_attr    .= $settings['secondary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
				$secondary_attr    .= $settings['secondary_button_link']['url'] ? ' href="' . $settings['secondary_button_link']['url'] . '"' : '';
				?>
				<<?php echo wp_kses_post($secondary_btn_tag . $secondary_attr); ?> class="wr-btn wr-secondary-btn">
				<span><?php echo esc_html($settings['secondary_button_text']); ?></span>
				<?php \Elementor\Icons_Manager::render_icon( $settings['secondary_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</<?php echo wp_kses_post($secondary_btn_tag); ?>>
			<?php endif; ?>
			</div>
		<?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Dual_Button);
}
