<?php

namespace Elementor;

if (!defined('ABSPATH')) {
	exit;
}

use Elementor\Widget_Base;

if (!class_exists('Workreap_Hero_Slider')) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Hero_Slider extends Widget_Base
	{

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
		public function get_name()
		{
			return 'workreap-hero-slider';
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
		public function get_title()
		{
			return __('Hero Slider', 'workreap');
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
		public function get_icon()
		{
			return 'eicon-slider-push';
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
		public function get_categories()
		{
			return array('workreap-elements');
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
		public function get_keywords()
		{
			return array('hero', 'slider');
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
		public function get_style_depends()
		{
			return array('swiper');
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
		public function get_script_depends()
		{
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
		protected function register_controls()
		{

			$this->start_controls_section(
				'section_general',
				array(
					'label' => esc_html__('General', 'workreap'),
					'tab' => Controls_Manager::TAB_CONTENT,
				)
			);

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'tabs_slide' );

			$repeater->start_controls_tab(
				'tab_slide_content',
				array(
					'label' => __( 'Content', 'workreap' ),
				)
			);

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
				'title',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Title', 'workreap'),
					'default' 	=> esc_html__('Thrive in the {{World of Freelance}} Excellence Marketplace!', 'workreap'),
					'separator'    => 'before',
				]
			);

			$repeater->add_control(
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
					),
					'default' => 'h2',
				)
			);

			$repeater->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap' ),
					'default' 	=> esc_html__('Flourish in a thriving freelance ecosystem dedicated to excellence and limitless opportunities.', 'workreap'),
					'rows' 			=> 5,
					'separator'    => 'before',
				]
			);

			$repeater->add_control(
				'primary_button_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Primary Button Text', 'workreap'),
					'default'       => esc_html__('Try It Free','workreap'),
					'label_block'   => true,
					'separator'   => 'before',
				]
			);

			$repeater->add_control(
				'primary_button_icon',
				[
					'label' => esc_html__( 'Primary Button Icon', 'workreap' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'exclude_inline_options' => 'svg',
				]
			);

			$repeater->add_control(
				'primary_button_link',
				array(
					'label'       => __( 'Primary Button URL', 'workreap' ),
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

			$repeater->add_control(
				'secondary_button_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Secondary Button Text', 'workreap'),
					'default'       => esc_html__('Learn More','workreap'),
					'label_block'   => true,
					'separator'   => 'before',
				]
			);

			$repeater->add_control(
				'secondary_button_icon',
				[
					'label' => esc_html__( 'Secondary Button Icon', 'workreap' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'exclude_inline_options' => 'svg',
				]
			);

			$repeater->add_control(
				'secondary_button_link',
				array(
					'label'       => __( 'Secondary Button URL', 'workreap' ),
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

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'tab_slide_styling',
				array(
					'label' => __( 'Styling', 'workreap' ),
				)
			);

			$repeater->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'background',
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .wr-herobannerwraper',
				]
			);

			$repeater->end_controls_tab();
			$repeater->end_controls_tabs();

			$this->add_control(
				'items',
				array(
					'label'       => __( 'Slides', 'workreap' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ title }}}',
					'separator'    => 'before',
					'default'     => array(
						array(
							'name' => 'Item #1',
						),
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
				'slide_speed',
				array(
					'label'       => __('Slide Speed', 'workreap'),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array('px'),
					'default'     => array(
						'size' => 1,
					),
					'range'       => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'render_type' => 'template',
					'frontend_available' => true,
					'separator' => 'before',
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_title',
				array(
					'label' => __( 'Title', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-heroslider_content .wr-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-heroslider_content .wr-title' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-heroslider_content .wr-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-heroslider_content .wr-highlighted-text',
				)
			);

			$this->add_control(
				'highlight_title_color',
				array(
					'label'     => __( 'Primary Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-heroslider_content .wr-highlighted-text' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'highlight_title_secondary_color',
				array(
					'label'     => __( 'Secondary Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-heroslider_content .wr-highlighted-text' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: linear-gradient(90deg, {{highlight_title_color.VALUE}} 0%, {{VALUE}} 100%)',
					),
					'condition'  => array(
						'layout' => array('3'),
					),
				)
			);

			$this->add_control(
				'highlight_title_effect_color',
				array(
					'label'     => __( 'Effect Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-heroslider_content .wr-highlighted-text::after' => 'fill: {{VALUE}};',
					),
					'condition'  => array(
						'layout' => array('1','2'),
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
						'{{WRAPPER}} .wr-heroslider_content .wr-highlighted-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'desc_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-heroslider_content .wr-description, {{WRAPPER}} .wr-heroslider_content .wr-description > *',
				)
			);

			$this->add_control(
				'desc_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-heroslider_content .wr-description, {{WRAPPER}} .wr-heroslider_content .wr-description > *' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-heroslider_content .wr-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_primary_style',
				array(
					'label' => __( 'Primary Button', 'workreap' ),
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
				)
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
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn,{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn > i' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn:hover,{{WRAPPER}} .wr-dual-button-wrapper .wr-primary-btn:hover > i' => 'color: {{VALUE}};',
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

			$this->end_controls_section();

			$this->start_controls_section(
				'section_secondary_style',
				array(
					'label' => __( 'Secondary Button', 'workreap' ),
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
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn,{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn > i' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover,{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-dual-button-wrapper .wr-secondary-btn:hover > svg' => 'fill: {{VALUE}};',
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
			<div dir="<?php echo esc_attr($rtl); ?>" class="wr-hero-slider-wrapper">
				<div class="swiper swiper-container wr-hero-slider">
					<div class="swiper-wrapper">
                        <?php foreach ($settings['items'] as $item){ ?>
                            <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                                <div class="wr-herobannerwraper">
                                    <div class="wr-heroslider_content">
	                                    <?php
	                                    $title_tag = $item['title_tag'];
                                        if($item['title']):
	                                    $title = str_replace( '{{', '<span class="wr-highlighted-text">', $item['title'] );
	                                    $title = str_replace( '}}', '</span>', $title );
	                                    ?>
                                        <<?php echo esc_attr($title_tag); ?> class="wr-title"><?php echo wp_kses_post($title); ?></<?php echo esc_attr($title_tag); ?>>
	                                <?php endif; ?>
	                                <?php if(!empty($item['description'])):?>
                                        <div class="wr-description"><?php echo wp_kses_post($item['description']); ?></div>
	                                <?php endif; ?>
                                    <div class="wr-dual-button-wrapper">
		                                <?php if($item['primary_button_text']):
		                                $primary_btn_tag = ( $item['primary_button_link']['url'] ) ? 'a' : 'span';
		                                $primary_attr     = $item['primary_button_link']['is_external'] ? ' target="_blank"' : '';
		                                $primary_attr    .= $item['primary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
		                                $primary_attr    .= $item['primary_button_link']['url'] ? ' href="' . $item['primary_button_link']['url'] . '"' : '';
		                                ?>
                                        <<?php echo wp_kses_post($primary_btn_tag . $primary_attr); ?> class="wr-btn wr-primary-btn">
                                        <span><?php echo esc_html($item['primary_button_text']); ?></span>
		                                <?php \Elementor\Icons_Manager::render_icon( $item['primary_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </<?php echo wp_kses_post($primary_btn_tag); ?>>
                                        <?php endif; ?>
                                            <?php if($item['secondary_button_text']):
                                            $secondary_btn_tag = ( $item['secondary_button_link']['url'] ) ? 'a' : 'span';
                                            $secondary_attr     = $item['secondary_button_link']['is_external'] ? ' target="_blank"' : '';
                                            $secondary_attr    .= $item['secondary_button_link']['nofollow'] ? ' rel="nofollow"' : '';
                                            $secondary_attr    .= $item['secondary_button_link']['url'] ? ' href="' . $item['secondary_button_link']['url'] . '"' : '';
                                            ?>
                                            <<?php echo wp_kses_post($secondary_btn_tag . $secondary_attr); ?> class="wr-btn wr-secondary-btn">
                                            <span><?php echo esc_html($item['secondary_button_text']); ?></span>
                                            <?php \Elementor\Icons_Manager::render_icon( $item['secondary_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                        </<?php echo wp_kses_post($secondary_btn_tag); ?>>
                                        <?php endif; ?>
                                    </div>
                                    </div>
                                    <figure class="wr-hero-slider-image">
                                        <?php
                                        if(!empty($item['image']['id'])){
	                                        echo wp_get_attachment_image( $item['image']['id'], $settings['thumbnail_size'] );
                                        } ?>
                                    </figure>
                                </div>
                            </div>
                        <?php } ?>
					</div>
					<div class="wr-paginationarea">
						<div class="swiper-pagination"></div>
						<div class="swiper-button-next"> <i class="fa fa-chevron-right"></i></div>
						<div class="swiper-button-prev"> <i class="fa fa-chevron-left"></i></div>
					</div>
				</div>
			</div>
        <?php
		}
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Hero_Slider);
}
