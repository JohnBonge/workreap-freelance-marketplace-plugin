<?php

namespace Elementor;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Workreap_Services_Grid' ) ) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Services_Grid extends Widget_Base {

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
			return 'workreap-services-grid';
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
			return __( 'Services Grid', 'workreap' );
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
			return 'eicon-gallery-grid';
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
			return array( 'services', 'grid' );
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
			return array( 'isotope', 'workreap-elements' );
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
				'services',
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
				'service_by',
				[
					'type'      => Controls_Manager::TEXTAREA,
					'condition' => [ 'listing_type' => 'ids' ],
					'label'     => esc_html__( 'Services By IDs', 'workreap' ),
                    'placeholder' => esc_html__( '125, 250', 'workreap' )
				]
			);

			$this->add_control(
				'enable_filters',
				[
					'label'        => esc_html__( 'Enable Filter', 'workreap' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Hide', 'workreap' ),
					'label_off' 	=> esc_html__( 'Show', 'workreap' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'filter_all_text',
				array(
					'label'       => __( 'Filter "All" Text', 'workreap' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => __( 'All', 'workreap' ),
					'dynamic'     => array(
						'active' => true,
					),
					'condition'  => [ 'filter_all_text!' => 'ids' ],
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
						'{{WRAPPER}} .wr-services-grid-item' => 'float:left; width: 100%; max-width:calc( 100% / {{SIZE}} )',
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
						'{{WRAPPER}} .wr-services-grid-item' => 'padding: {{SIZE}}px;',
						'{{WRAPPER}} .wr-services-grid-items' => 'margin: 0 -{{SIZE}}px;',
					)
				)
			);

			$this->add_responsive_control(
				'item_content_height',
				array(
					'label'      => __( 'Content Min Height', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'default'    => array(
						'size' => 220,
					),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 1000,
						),
					),
					'selectors'      => array(
						'{{WRAPPER}} .wr-topservice__content' => 'min-height: {{SIZE}}px;',
					)
				)
			);

			$this->add_control(
				'item_background_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-topservice' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'item_separator_color',
				array(
					'label'     => __( 'Separator Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-topservice__content .wr-startingprice' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'item_border',
					'selector'  => '{{WRAPPER}} .wr-topservice',
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
						'{{WRAPPER}} .wr-topservice' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-topservice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_filters',
				array(
					'label' => __( 'Filters', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'alignment',
				array(
					'label'        => __( 'Alignment', 'workreap' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => array(
						'start'   => array(
							'title' => __( 'Left', 'workreap' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => __( 'Center', 'workreap' ),
							'icon'  => 'eicon-h-align-center',
						),
						'end'  => array(
							'title' => __( 'Right', 'workreap' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list' => 'justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'filter_space_between',
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
						'{{WRAPPER}} .wr-services-grid-filter-list' => 'gap: {{SIZE}}px;',
					)
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'filter_typo',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-services-grid-filter-list li',
				)
			);

			$this->start_controls_tabs( 'tabs_filter' );

			$this->start_controls_tab(
				'tab_filter_normal',
				array(
					'label' => __( 'Normal', 'workreap' ),
				)
			);

			$this->add_control(
				'filter_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'filter_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_filter_hover',
				array(
					'label' => __( 'Hover', 'workreap' ),
				)
			);

			$this->add_control(
				'filter_hover_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li:hover' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'filter_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li:hover' => 'background-color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'filter_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_filter_active',
				array(
					'label' => __( 'Active', 'workreap' ),
				)
			);

			$this->add_control(
				'filter_active_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li.active' => 'color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'filter_bg_active_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li.active' => 'background-color: {{VALUE}};'
					),
				)
			);

			$this->add_control(
				'filter_border_active_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-services-grid-filter-list li.active' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'button_border',
					'selector'  => '{{WRAPPER}} .wr-services-grid-filter-list li',
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
						'{{WRAPPER}} .wr-services-grid-filter-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-services-grid-filter-list li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_image',
				array(
					'label' => __( 'Image', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
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
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-card__img' => 'height: {{SIZE}}{{UNIT}};',
					)
				)
			);

			$this->add_responsive_control(
				'image_border_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-card__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-card__img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-card__img .wr_saved_items' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-card__img .wr_saved_items > span' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-card__img .wr_saved_items' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-card__img .wr_saved_items:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-card__img .wr_saved_items:hover > i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-card__img .wr_saved_items:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-card__img .wr_saved_items:hover' => 'border-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-card__img .wr_saved_items.bg-redheart' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .wr-card__img .wr_saved_items.bg-redheart i' => 'color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_control(
				'favourite_bg_active_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-card__img .wr_saved_items.bg-redheart' => 'background-color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_control(
				'favourite_border_active_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-card__img .wr_saved_items.bg-redheart' => 'border-color: {{VALUE}} !important;',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'favourite_border',
					'selector'  => '{{WRAPPER}} .wr-card__img .wr_saved_items',
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
						'{{WRAPPER}} .wr-card__img .wr_saved_items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-card__img .wr_saved_items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-card-title .wr-asideprostatus figure' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-card-title .wr-asideprostatus figure,{{WRAPPER}} .wr-card-title .wr-asideprostatus figure > img' => 'border-radius: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-card-title .wr-asideprostatus .wr-freelancer-details h4 .wr-icon-check-circle::before'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'name_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-topservice .wr-card-title a',
				)
			);

			$this->add_control(
				'name_color',
				array(
					'label'     => __( 'Name Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-topservice .wr-card-title a,{{WRAPPER}} .wr-topservice .wr-card-title a:hover' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-title-wrapper h5 a',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-title-wrapper h5 a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-title-wrapper h5 a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_reviews',
				array(
					'label' => __( 'Reviews', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'reviews_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-topservice__content .wr-featureRatingv2 h6, {{WRAPPER}} .wr-topservice__content .wr-featureRatingv2 > em',
				)
			);

			$this->add_control(
				'reviews_icon_color',
				array(
					'label'     => __( 'Icon Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-featureRating__stars::before' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'reviews_icon_fill_color',
				array(
					'label'     => __( 'Icon Fill Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-featureRating__stars span::after' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'reviews_color',
				array(
					'label'     => __( 'Text Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-topservice__content .wr-featureRatingv2 h6, {{WRAPPER}} .wr-topservice__content .wr-featureRatingv2 > em' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'reviews_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-topservice__content .wr-featureRating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_location',
				array(
					'label' => __( 'Location', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'location_icon',
				array(
					'label'      => __( 'Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-topservice__content .wr-featureRating address > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'location_typo',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-topservice__content .wr-featureRating address',
				)
			);

			$this->add_control(
				'location_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-topservice__content .wr-featureRating address' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'location_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-topservice__content .wr-featureRating address' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-topservice__content .wr-startingprice i',
				)
			);

			$this->add_control(
				'price_label_color',
				array(
					'label'     => __( 'Label Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-topservice__content .wr-startingprice i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'price_value_typo',
					'label'    => __( 'Value Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-topservice__content .wr-startingprice span',
				)
			);

			$this->add_control(
				'price_value_color',
				array(
					'label'     => __( 'Value Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-topservice__content .wr-startingprice span' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-startingprice' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-startingprice' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$service_by   = isset($settings['service_by']) ? $settings['service_by'] : '';
			$categories   = isset($settings['services']) ? $settings['services'] : '';

			$tax_queries = array();

			if (class_exists('WooCommerce')) {

				if(!empty($categories ) && empty($service_by) && ( $listing_type === 'categories_random' || $listing_type === 'categories_recent' ) ){
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
					'terms'    => 'tasks',
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
				if(!empty($service_by)){
					$args['post__in'] = explode(',',$service_by);
				}

				$query  = new WP_Query($args);
				$result_count   = $query->found_posts;

				$filters = [];

            ?>
            <div class="wr-services-grid-wrapper">

                <?php if($settings['enable_filters'] === 'yes'){ ?>
                    <div class="wr-services-grid-filters">
                        <ul class="wr-services-grid-filter-list">
                        <li class="wr-services-grid-filter-item active" data-filter="*"><?php echo esc_html($settings['filter_all_text']); ?></li>
                            <?php
	                        while ( $query->have_posts() ) {
		                        $query->the_post();
		                        $terms = get_the_terms( get_the_ID(), 'product_cat' );

		                        if ( $terms && ! is_wp_error( $terms ) ) {
			                        foreach ( $terms as $term ) {
			                            if($term->parent === 0){
				                            $filters[$term->slug] = $term->name;
			                            }
			                        }
		                        }
	                        }

	                        wp_reset_postdata();

	                        if(isset($filters) && !empty($filters)){
		                        foreach (array_unique($filters) as $f_key => $filter){ ?>
                                    <li class="wr-services-grid-filter-item" data-filter=".<?php echo esc_attr($f_key) ?>"><?php echo esc_html($filter); ?></li>
		                        <?php }
	                        }

	                        ?>

                        </ul>
                    </div>
                <?php } ?>

                <div class="wr-services-grid-items">
                    <?php
                    while ( $query->have_posts() ) {
	                    $query->the_post();
	                    $class = '';
	                    $terms = get_the_terms( get_the_ID(), 'product_cat' );

	                    if ( $terms && ! is_wp_error( $terms ) ) {
		                    foreach ( $terms as $term ) {
			                    if($term->parent === 0){
				                    $class .= ' ' . $term->slug;
			                    }
		                    }
	                    }

	                    ?>
                        <div class="wr-services-grid-item<?php echo esc_attr($class); ?>"><?php do_action( 'workreap_listing_task_html_v2', get_the_ID() ); ?></div>
                        <?php
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <?php }
		}
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Services_Grid );
}
