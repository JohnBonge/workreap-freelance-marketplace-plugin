<?php

namespace Elementor;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Workreap_Info_Box' ) ) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Info_Box extends Widget_Base {

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
			return 'workreap-info-box';
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
			return __( 'Info Box', 'workreap' );
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
			return 'eicon-info-box';
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
			return array( 'info', 'box' );
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
					'tab' => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'layout',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Layout', 'workreap'),
					'options' 		=> [
						'1' => esc_html__('Style 1', 'workreap'),
						'2' => esc_html__('Style 2', 'workreap'),
						'3' => esc_html__('Style 3', 'workreap'),
					],
					'default' 		=> '1',
				]
			);

			$this->add_control(
				'subtitle',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Sub Title', 'workreap'),
					'default' 	=> esc_html__('Boost Your Working Flow', 'workreap'),
					'separator'    => 'before',
				]
			);

			$this->add_control(
				'subtitle_tag',
				array(
					'label'   => __( 'Sub Title Tag', 'workreap' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'h1'   => 'H1',
						'h2'   => 'H2',
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'h6'   => 'H6',
						'div'  => 'div',
						'span' => 'span',
						'a'     => 'a',
					),
					'default' => 'h6',
				)
			);

			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Title', 'workreap'),
					'default' 	=> esc_html__('Thrive in the {{World of Freelance}} Excellence Marketplace!', 'workreap'),
					'separator'    => 'before',
				]
			);

			$this->add_control(
				'title_tag',
				array(
					'label'   => __( 'Title Tag', 'workreap' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'h1'   => 'H1',
						'h2'   => 'H2',
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'h6'   => 'H6',
						'div'  => 'div',
						'span' => 'span',
						'a' => 'a',
					),
					'default' => 'h2',
				)
			);

			$this->add_control(
				'title_link',
				array(
					'label'       => __( 'Title Link', 'workreap' ),
					'type'        => Controls_Manager::URL,
					'dynamic'     => array(
						'active' => true,
					),
					'condition'   => array(
						'title_tag'    => 'a',
					),
				)
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap' ),
					'default' 	=> esc_html__('Flourish in a thriving freelance ecosystem dedicated to excellence and limitless opportunities.', 'workreap'),
					'rows' 			=> 5,
					'separator'    => 'before',
				]
			);

			$this->add_control(
				'image',
				array(
					'label'   => __( 'Image', 'workreap' ),
					'type'    => Controls_Manager::MEDIA,
					'dynamic' => array(
						'active' => true,
					),
				)
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
				'enable_button',
				[
					'label'        => esc_html__( 'Enable Button', 'workreap' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Hide', 'workreap' ),
					'label_off' 	=> esc_html__( 'Show', 'workreap' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'separator'     => 'before',
				]
			);

			$this->add_control(
				'button_text',
				[
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Button Text', 'workreap' ),
					'default'     => esc_html__( 'Find a Better Job', 'workreap' ),
					'label_block' => true,
					'condition'   => array(
						'enable_button'    => 'yes',
					),
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
					'condition'   => array(
						'enable_button'    => 'yes',
					),
				]
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
					'condition'   => array(
						'enable_button'    => 'yes',
					),
				)
			);

			$this->end_controls_section();

			//Styling Tab
			$this->start_controls_section(
				'section_style_subtitle',
				array(
					'label' => __( 'Sub Title', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition'  => array(
						'subtitle!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'subtitle_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-info-box-wrapper .wr-subtitle',
				)
			);

			$this->add_control(
				'subtitle_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-subtitle' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'subtitle_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_title',
				array(
					'label' => __( 'Title', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition'  => array(
						'title!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-info-box-wrapper .wr-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'title_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);


			$this->add_control(
				'highlight_title_heading',
				array(
					'label'     => __( 'Highlight', 'workreap' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'highlight_title_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-info-box-wrapper .wr-highlighted-text',
				)
			);

			$this->add_control(
				'highlight_title_color',
				array(
					'label'     => __( 'Primary Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-highlighted-text' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'highlight_title_effect_color',
				array(
					'label'     => __( 'Effect Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-highlighted-text::after' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'highlight_title_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-highlighted-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_desc',
				array(
					'label' => __( 'Description', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition'  => array(
						'description!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'desc_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-info-box-wrapper .wr-description, {{WRAPPER}} .wr-info-box-wrapper .wr-description > *',
				)
			);

			$this->add_control(
				'desc_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-info-box-wrapper .wr-description, {{WRAPPER}} .wr-info-box-wrapper .wr-description > *' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-info-box-wrapper .wr-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_image_style',
				array(
					'label' => __( 'Image', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'image_width',
				array(
					'label'      => __( 'Width', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
					'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-image > img' => 'width: {{SIZE}}{{UNIT}};',
					)
				)
			);

			$this->add_responsive_control(
				'image_height',
				array(
					'label'      => __( 'Height', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-image > img' => 'height: {{SIZE}}{{UNIT}};',
					)
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
						'{{WRAPPER}} .wr-client-logo-figure > img' => 'object-fit: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'image_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-image > img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_button_style',
				array(
					'label' => __( 'Button', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition'   => array(
						'enable_button'    => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'button',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-btn',
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
						'{{WRAPPER}} .wr-btn > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-btn > svg' => 'width: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-btn' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-btn' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-btn:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-btn:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-btn:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'button_border',
					'selector'  => '{{WRAPPER}} .wr-btn',
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
						'{{WRAPPER}} .wr-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$title_tag = $settings['title_tag'];
			$subtitle_tag = $settings['subtitle_tag'];
			$attr = '';
			if($title_tag === 'a' && !empty($settings['title_link']['url'])){
				$attr .= ' href="' . $settings['title_link']['url'] . '"';
				$attr .= $settings['title_link']['is_external'] ? ' target="_blank"' : '';
				$attr .= $settings['title_link']['nofollow'] ? ' rel="nofollow"' : '';
			}
			?>
        <div class="wr-info-box-wrapper wr-info-box-layout-<?php echo esc_attr($settings['layout']); ?>">
            <div class="wr-info-box-content">
			<?php if(!empty($settings['subtitle'])):?>
                <<?php echo esc_attr($subtitle_tag); ?> class="wr-subtitle"><?php echo wp_kses_post($settings['subtitle']); ?></<?php echo esc_attr($subtitle_tag); ?>>
		    <?php endif; ?>
			<?php if($settings['title']):
				$title = str_replace( '{{', '<span class="wr-highlighted-text">', $settings['title'] );
				$title = str_replace( '}}', '</span>', $title );
				?>
                <<?php echo esc_attr($title_tag . $attr); ?> class="wr-title"><?php echo wp_kses_post($title); ?></<?php echo esc_attr($title_tag); ?>>
			<?php endif; ?>
			<?php if(!empty($settings['description'])):?>
                <div class="wr-description"><?php echo wp_kses_post($settings['description']); ?></div>
			<?php endif; ?>
			<?php
            if($settings['enable_button'] === 'yes' && $settings['layout'] !== '3'){
			$btn_tag = ( $settings['button_link']['url'] ) ? 'a' : 'span';
			$attr = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
			$attr .= $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
			$attr .= $settings['button_link']['url'] ? ' href="' . $settings['button_link']['url'] . '"' : '';
			?>
            <<?php echo wp_kses_post( $btn_tag . $attr ); ?> class="wr-btn wr-primary-btn">
			<?php if($settings['button_text']): ?>
                <span><?php echo esc_html( $settings['button_text'] ); ?></span>
			<?php endif; ?>
			<?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </<?php echo wp_kses_post( $btn_tag ); ?>>
            <?php } ?>
            </div>
            <?php
			if(!empty($settings['image']['id'])){ ?>
                <figure class="wr-image">
					<?php echo wp_get_attachment_image( $settings['image']['id'], $settings['thumbnail_size'] ); ?>
                </figure>
			<?php }
            if($settings['enable_button'] === 'yes' && $settings['layout'] === '3'){
	            $btn_tag = ( $settings['button_link']['url'] ) ? 'a' : 'span';
	            $attr = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
	            $attr .= $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
	            $attr .= $settings['button_link']['url'] ? ' href="' . $settings['button_link']['url'] . '"' : '';
	            ?>
                <<?php echo wp_kses_post( $btn_tag . $attr ); ?> class="wr-btn wr-primary-btn">
	            <?php if($settings['button_text']): ?>
                    <span><?php echo esc_html( $settings['button_text'] ); ?></span>
	            <?php endif; ?>
	            <?php Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </<?php echo wp_kses_post( $btn_tag ); ?>>
            <?php } ?>
            </div>
		<?php }
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Info_Box );
}
