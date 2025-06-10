<?php

namespace Elementor;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Workreap_Projects_Grid' ) ) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Projects_Grid extends Widget_Base {

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
			return 'workreap-projects-grid';
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
			return __( 'Projects Grid', 'workreap' );
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
			return 'eicon-posts-masonry';
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
			return array( 'projects', 'grid' );
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
				'listing_type',
				[
					'type'    => Controls_Manager::SELECT,
					'label'   => esc_html__( 'Listing Type', 'workreap' ),
					'default' => 'random',
					'options' => [
						'random'            => esc_html__( 'Random From All Categories', 'workreap' ),
						'recent'            => esc_html__( 'Recent From All Categories', 'workreap' ),
						'categories_random' => esc_html__( 'Random By Categories', 'workreap' ),
						'categories_recent' => esc_html__( 'Recent By Categories', 'workreap' ),
						'rating'            => esc_html__( 'Order By Rating', 'workreap' ),
						'ids'               => esc_html__( 'By IDs', 'workreap' ),
					],
					'label_block' => true,
				]
			);

			$categories = array();

			if ( function_exists( 'workreap_elementor_get_taxonomies' ) ) {
				$categories = workreap_elementor_get_taxonomies( 'product', 'product_cat' );
			}

			$this->add_control(
				'projects',
				[
					'type'        => Controls_Manager::SELECT2,
					'label'       => esc_html__( 'Choose Categories', 'workreap' ),
					'options'     => $categories,
					'condition'   => [ 'listing_type' => [ 'categories_random', 'categories_recent' ] ],
					'multiple'    => true,
					'label_block' => true,
				]
			);

			$this->add_control(
				'show_posts',
				[
					'label'      => esc_html__( 'Limit', 'workreap' ),
					'type'       => Controls_Manager::NUMBER,
					'condition'  => [ 'listing_type!' => 'ids' ],
					'min'         => 1,
					'max'         => 20,
					'step'        => 1,
					'default'     => 8,
				]
			);

			$this->add_control(
				'order_by',
				[
					'type'      => Controls_Manager::TEXTAREA,
					'condition' => [ 'listing_type' => 'ids' ],
					'label'     => esc_html__( 'Services By IDs', 'workreap' ),
                    'placeholder' => esc_html__( '125, 250', 'workreap' )
				]
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
				'item_per_row',
				array(
					'label'          => __( 'Item Per Row', 'workreap' ),
					'type'           => Controls_Manager::SLIDER,
					'size_units'     => array( 'px' ),
					'range'          => array(
						'px' => array(
							'min'  => 1,
							'max'  => 12,
							'step' => 1,
						),
					),
					'default'        => array(
						'size' => 4,
					),
					'tablet_default' => array(
						'size' => 2,
					),
					'mobile_default' => array(
						'size' => 1,
					),
					'selectors'      => array(
						'{{WRAPPER}} .wr-projects-grid-items' => 'display: grid; grid-template-columns:repeat({{SIZE}}, 1fr)',
					)
				)
			);

			$this->add_responsive_control(
				'item_space_between',
				array(
					'label'      => __( 'Space Between', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'default'    => array(
						'size' => 15,
					),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'      => array(
						'{{WRAPPER}} .wr-projects-grid-items' => 'grid-gap: {{SIZE}}px;',
					)
				)
			);

			$this->add_control(
				'item_background_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'item_separator_color',
				array(
					'label'     => __( 'Separator Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-projects-grid-footer' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'item_border',
					'selector'  => '{{WRAPPER}} .wr-projects-grid-item',
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'item_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'item_border_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_date_style',
				array(
					'label' => __( 'Date', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'date_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr-project-posted-date',
				)
			);

			$this->add_control(
				'date_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr-project-posted-date' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'date_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr-project-posted-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_title_style',
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
					'selector' => '{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr-project-title > a',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr-project-title > a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr-project-title > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_favourite_style',
				array(
					'label' => __( 'Favourite', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->start_controls_tabs( 'tabs_favourite' );

			$this->start_controls_tab(
				'tab_favourite_normal',
				array(
					'label' => __( 'Normal', 'workreap' ),
				)
			);

			$this->add_control(
				'favourite_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items > span' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_favourite_hover',
				array(
					'label' => __( 'Hover', 'workreap' ),
				)
			);

			$this->add_control(
				'favourite_hover_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items:hover > i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_favourite_active',
				array(
					'label' => __( 'Active', 'workreap' ),
				)
			);

			$this->add_control(
				'favourite_active_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items.bg-redheart' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items.bg-redheart i' => 'color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_control(
				'favourite_bg_active_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items.bg-redheart' => 'background-color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_control(
				'favourite_border_active_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items.bg-redheart' => 'border-color: {{VALUE}} !important;',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'favourite_border',
					'selector'  => '{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items',
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'favourite_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'favourite_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-header .wr_saved_items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_price',
				array(
					'label' => __( 'Price', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'price_label_typo',
					'label'    => __( 'Label Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-projects-grid-item .wr-project-price-wrapper .wr-project-tag',
				)
			);

			$this->add_control(
				'price_label_color',
				array(
					'label'     => __( 'Label Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-price-wrapper .wr-project-tag' => 'color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'price_value_typo',
					'label'    => __( 'Value Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-projects-grid-item .wr-project-price-wrapper .wr-project-price',
				)
			);

			$this->add_control(
				'price_value_color',
				array(
					'label'     => __( 'Value Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-price-wrapper .wr-project-price' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'price_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-price-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'price_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-price-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_description_style',
				array(
					'label' => __( 'Description', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'description_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-projects-grid-item .wr-project-description p',
				)
			);

			$this->add_control(
				'description_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-description p' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'description_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-description p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_categorise',
				array(
					'label' => __( 'Categorise', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'category_icon_size',
				array(
					'label'      => __( 'Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'.wr-projects-grid-item .wr-project-info li > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'category_typo',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '.wr-projects-grid-item .wr-project-info li > span',
				)
			);

			$this->start_controls_tabs( 'tabs_category' );

			$this->start_controls_tab(
				'tab_category_type',
				array(
					'label' => __( 'Type', 'workreap' ),
				)
			);

			$this->add_control(
				'category_type_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-location > i,{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-location > span' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'category_type_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-location' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_category_level',
				array(
					'label' => __( 'Expertise', 'workreap' ),
				)
			);

			$this->add_control(
				'category_level_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-expertiese > i,{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-expertiese > span' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'category_level_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-expertiese' => 'background-color: {{VALUE}};'
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_category_freelancers',
				array(
					'label' => __( 'Freelancers', 'workreap' ),
				)
			);

			$this->add_control(
				'category_freelancers_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-freelancers > i,{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-freelancers > span' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'category_freelancers_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-project-info .wr-freelancers > i' => 'background-color: {{VALUE}};'
					),
				)
			);


			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'section_tags',
				array(
					'label' => __( 'Tags', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'tags_typo',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-projects-grid-item .wr-singleservice-tile .wr-tags_links li span,
                				   {{WRAPPER}} .wr-projects-grid-item .wr-singleservice-tile .wr-tags_links li .wr-selected__showmore a',
				)
			);

			$this->add_control(
				'tags_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-singleservice-tile .wr-tags_links li span,
						 {{WRAPPER}} .wr-projects-grid-item .wr-singleservice-tile .wr-tags_links li .wr-selected__showmore a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'tags_bg_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-singleservice-tile .wr-tags_links li span' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'tags_border_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-singleservice-tile .wr-tags_links li span' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'tags_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-blogtags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_profile_style',
				array(
					'label' => __( 'Profile', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'avatar_image_height',
				array(
					'label'      => __( 'Avatar Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-projects-grid-footer .wr-author-info .wr-author-iamge' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					)
				)
			);

			$this->add_responsive_control(
				'avatar_border_radius',
				array(
					'label'      => __( 'Avatar Border Radius', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-projects-grid-footer .wr-author-info .wr-author-iamge img' => 'border-radius: {{SIZE}}{{UNIT}};',
					)
				)
			);


			$this->add_responsive_control(
				'verified_icon',
				array(
					'label'      => __( 'Verified Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-projects-grid-footer .wr-author-info .wr-icon-check-circle::before'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'name_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-projects-grid-item .wr-projects-grid-footer .wr-author-info .wr-name',
				)
			);

			$this->add_control(
				'name_color',
				array(
					'label'     => __( 'Name Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-projects-grid-footer .wr-author-info .wr-name' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'profile_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-projects-grid-item .wr-projects-grid-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_button_style',
				array(
					'label' => __( 'Button', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'button',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '.wr-projects-grid-item .wr-projects-grid-footer .wr-secondary-btn',
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
						'.wr-projects-grid-item .wr-projects-grid-footer .wr-secondary-btn' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.wr-projects-grid-item .wr-projects-grid-footer .wr-secondary-btn' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-projects-grid-item:hover .wr-projects-grid-footer .wr-secondary-btn' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'button_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item:hover .wr-projects-grid-footer .wr-secondary-btn' => 'background-color: {{VALUE}};',
						'.wr-projects-grid-item .wr-projects-grid-footer .wr-secondary-btn::before' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-projects-grid-item:hover .wr-projects-grid-footer .wr-secondary-btn' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'button_border',
					'selector'  => '.wr-projects-grid-item .wr-projects-grid-footer .wr-secondary-btn',
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
						'.wr-projects-grid-item .wr-projects-grid-footer .wr-secondary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'.wr-projects-grid-item .wr-projects-grid-footer .wr-secondary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$show_posts   = isset($settings['show_posts']) ? $settings['show_posts'] : -1;
			$listing_type = isset($settings['listing_type']) ? $settings['listing_type'] : '';
			$order_by   = isset($settings['order_by']) ? $settings['order_by'] : '';
			$categories   = isset($settings['projects']) ? $settings['projects'] : '';

			$tax_queries = array();

			if (class_exists('WooCommerce')) {

				if(!empty($categories ) && empty($order_by) && ( $listing_type === 'categories_random' || $listing_type === 'categories_recent' ) ){
					$query_relation = array('relation' => 'AND',);
					$product_cat_tax_args[] = array(
						'taxonomy'  => 'product_cat',
						'terms'     => $categories,
						'field'     => 'term_id',
						'operator'  => 'IN',
					);

					$tax_queries = array_merge($query_relation, $product_cat_tax_args);
				}

				$product_type_tax_args[] = array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'projects',
				);

				$tax_queries = array_merge($tax_queries,$product_type_tax_args);

				// prepared query args
				$args = array(
					'post_type'         => 'product',
					'post_status'       => 'publish',
					'posts_per_page'    => $show_posts,
					'tax_query'         => $tax_queries
				);

				//order by
				if(!empty($listing_type) && ( $listing_type == 'random' ||  $listing_type == 'categories_random' )){
					$args['orderby'] = 'rand';
				}

				if(!empty($listing_type) && ( $listing_type == 'recent' ||  $listing_type == 'categories_recent' )){
					$args['orderby']    = 'ID';
					$args['order']      = 'DESC';
				}
				if(!empty($listing_type) && ( $listing_type === 'rating' )){
					$args['orderby']    = 'meta_value';
					$args['order']      = 'DESC';
					$args['meta_key']   = '_wc_average_rating';
				}

				//specific posts
				if(!empty($order_by)){
					$args['post__in'] = explode(',',$order_by);
				}

				$query  = new WP_Query($args);
				$result_count   = $query->found_posts;

				$filters = [];

            ?>
            <div class="wr-projects-grid-wrapper">
                <div class="wr-projects-grid-items">
                    <?php
                    while ( $query->have_posts() ) {
	                    $query->the_post();
                        $project_id     = get_the_ID();
	                    $product 		 = wc_get_product( $project_id );
	                    $author_id 		 = get_the_author_meta( 'ID' );
	                    $linked_profile  = workreap_get_linked_profile_id($author_id);
	                    $employer_title  = workreap_get_username( $linked_profile );

	                    $employer_avatar = apply_filters(
		                    'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100)
	                    );
	                    $post_date = get_the_date('M d, Y');

	                    $no_of_freelancers       = get_post_meta($project_id, 'no_of_freelancers', true);
	                    $experties = wp_get_post_terms($project_id, 'expertise_level');
	                    $location	= get_post_meta( $project_id, '_project_location',true );
	                    $location	= !empty($location) ? ($location) : '';
	                    $location_text  = workreap_project_location_type($location);
	                    ?>
                        <div class="wr-projects-grid-item">
							<div class="wr-project-header-wrapper">
								<?php do_action( 'workreap_featured_item', $product,'featured_project' );?>
                                <div class="wr-project-header">
									<div class="wr-project-header-content">
										<span class="wr-project-posted-date"><?php echo esc_html($post_date); ?></span>
										<h3 class="wr-project-title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h3>
									</div>
									<?php do_action( 'workreap_project_saved_item', $project_id, '','_saved_projects' ,'icon' );?>
								</div>
								<div class="wr-project-price-wrapper">
									<?php do_action( 'workreap_project_type_tag', $project_id );?>
									<?php do_action( 'workreap_get_project_price_html', $project_id );?>
								</div>
								<div class="wr-project-description">
									<?php the_excerpt(); ?>
								</div>
								<ul class="wr-project-info">
									<?php if($location_text){ ?>
										<li class="wr-location">
											<i class="wr-icon-map-pin"></i>
											<span><?php echo esc_html($location_text); ?></span>
										</li>
									<?php }?>
									<?php if(isset($experties[0])){
										?>
										<li class="wr-expertiese">
											<i class="wr-icon-briefcase"></i>
											<span><?php echo esc_html($experties[0]->name) ?></span>
										</li>
									<?php }?>
									<?php if($no_of_freelancers){ ?>
										<li class="wr-freelancers">
											<i class="wr-icon-users"></i>
											<span><?php echo sprintf(_n('%s freelancer', '%s freelancers', $no_of_freelancers, 'workreap'), $no_of_freelancers); ?></span>
										</li>
									<?php }?>
								</ul>
								<?php do_action( 'workreap_term_tags', $project_id,'skills','',3,'project' );?>
							</div>
                            <div class="wr-projects-grid-footer">
                                <div class="wr-author-info">
		                            <?php if( !empty($employer_avatar) ){?>
                                        <figure class="wr-author-iamge">
                                            <img src="<?php echo esc_url($employer_avatar); ?>" alt="<?php echo esc_attr($employer_title); ?>">
                                        </figure>
		                            <?php } ?>
                                    <h5 class="wr-name"><?php echo esc_html($employer_title); ?></h5>
                                </div>
                                <a class="wr-btn wr-secondary-btn" href="<?php echo get_the_permalink(); ?>"><?php echo esc_html__('View Job', 'workreap'); ?></a>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <?php }
		}
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Projects_Grid );
}
