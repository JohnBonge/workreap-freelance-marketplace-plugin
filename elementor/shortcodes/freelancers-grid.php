<?php

namespace Elementor;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Workreap_Freelancers_Grid' ) ) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Freelancers_Grid extends Widget_Base {

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
			return 'workreap-freelancers-grid';
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
			return __( 'Freelancers Grid', 'workreap' );
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
			return 'eicon-post-list';
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
			return array( 'freelancers', 'grid' );
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
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Layout', 'workreap'),
					'options' 		=> [
						'1' => esc_html__('Style 1', 'workreap'),
						'2' => esc_html__('Style 2', 'workreap'),
					],
					'default' 		=> '1',
				]
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
						'ids'               => esc_html__( 'By IDs', 'workreap' ),
					],
					'label_block' => true,
				]
			);

			$categories = array();

			if ( function_exists( 'workreap_elementor_get_taxonomies' ) ) {
				$categories = workreap_elementor_get_taxonomies( 'freelancers', 'freelancer_type' );
			}

			$this->add_control(
				'freelancers',
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
				'freelancer_by',
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
						'{{WRAPPER}} .wr-freelancers-grid-items' => 'display: grid; grid-template-columns:repeat({{SIZE}}, 1fr)',
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
						'{{WRAPPER}} .wr-freelancers-grid-items' => 'grid-gap: {{SIZE}}px;',
					)
				)
			);

			$this->add_control(
				'item_background_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancers-grid-item' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'item_separator_color',
				array(
					'label'     => __( 'Separator Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-body .wr-singleservice-tile' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'item_border',
					'selector'  => '{{WRAPPER}} .wr-freelancers-grid-item',
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
						'{{WRAPPER}} .wr-freelancers-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-freelancers-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'label'      => __( 'Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-header .wr-asideprostatus figure' => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-freelancers-layout-2 .wr-freelancer-header .wr-asideprostatus figure' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-freelancers-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} ..wr-freelancer-header-content .wr-freelancer-author-info > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_name',
				array(
					'label' => __( 'Name', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'verified_icon',
				array(
					'label'      => __( 'Verified Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info > .wr-icon-check-circle::before'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'name_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info > a',
				)
			);

			$this->add_control(
				'name_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info > a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} ..wr-freelancer-header-content .wr-freelancer-author-info > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

			$this->add_responsive_control(
				'reviews_icon',
				array(
					'label'      => __( 'Reviews Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info .wr-freelancer-reviews li i'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'reviews_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info .wr-freelancer-reviews li em, {{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info .wr-freelancer-reviews li span',
				)
			);

			$this->add_control(
				'reviews_icon_color',
				array(
					'label'     => __( 'Icon Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info .wr-freelancer-reviews li i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'reviews_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info .wr-freelancer-reviews li em, {{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info .wr-freelancer-reviews li span' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-freelancer-header-content .wr-freelancer-author-info .wr-freelancer-reviews li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_views',
				array(
					'label' => __( 'Views', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'views_icon',
				array(
					'label'      => __( 'Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-views > li'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'views_typo',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-freelancer-views > li > span',
				)
			);

			$this->add_control(
				'views_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-views > li > span,{{WRAPPER}} .wr-freelancer-header-content > li > i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'views_bg_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-views > li' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'views_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-views' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_rate',
				array(
					'label' => __( 'Rate', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'rate_icon',
				array(
					'label'      => __( 'Icon Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-hourly-rate-title > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'rate_label_typo',
					'label'    => __( 'Value Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-freelancer-hourly-rate-title',
				)
			);

			$this->add_control(
				'rate_label_icon_color',
				array(
					'label'     => __( 'Label Icon Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-hourly-rate-title > i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'rate_label_color',
				array(
					'label'     => __( 'Label Text Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-hourly-rate-title' => 'color: {{VALUE}};',
					),
					'separator' => 'after',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'rate_value_typo',
					'label'    => __( 'Value Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-freelancer-hourly-rate-value',
				)
			);

			$this->add_control(
				'rate_value_color',
				array(
					'label'     => __( 'Value Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-hourly-rate-value' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'rate_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'separator' => 'before',
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-hourly-rate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-address-title > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'location_label_typo',
					'label'    => __( 'Label Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-address-title',
				)
			);

			$this->add_control(
				'location_label_icon_color',
				array(
					'label'     => __( 'Label Icon Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-address-title > i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'location_label_color',
				array(
					'label'     => __( 'Label Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-address-title' => 'color: {{VALUE}};',
					),
					'separator' => 'after',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'location_value_typo',
					'label'    => __( 'Value Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-address-value',
				)
			);

			$this->add_control(
				'location_value_color',
				array(
					'label'     => __( 'Value Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-address-value' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'location_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'separator' => 'before',
					'selectors'  => array(
						'{{WRAPPER}} .wr-freelancer-address' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

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
					'selector' => '{{WRAPPER}} .wr-freelancer-body .wr-singleservice-tile .wr-blogtags .wr-blog-tags,
                				   {{WRAPPER}} .wr-freelancer-body .wr-singleservice-tile .wr-blogtags .wr-selected__showmore a',
				)
			);

			$this->add_control(
				'tags_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-body .wr-singleservice-tile .wr-blogtags .wr-blog-tags,
						 {{WRAPPER}} .wr-freelancer-body .wr-singleservice-tile .wr-blogtags .wr-selected__showmore a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'tags_bg_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-body .wr-singleservice-tile .wr-blogtags .wr-blog-tags' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'tags_border_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-body .wr-singleservice-tile .wr-blogtags .wr-blog-tags' => 'border-color: {{VALUE}};',
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
					'selector' => '{{WRAPPER}} .wr-freelancer-footer .wr-secondary-btn',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr-secondary-btn' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-footer .wr-secondary-btn' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-freelancers-grid-item:hover .wr-secondary-btn' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancers-grid-item:hover .wr-secondary-btn' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .wr-freelancer-footer .wr-secondary-btn::before' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancers-grid-item:hover .wr-secondary-btn' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'button_border',
					'selector'  => '{{WRAPPER}} .wr-freelancer-footer .wr-secondary-btn',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr-secondary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr-secondary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items > span' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items:hover > span' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'favourite_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items:hover' => 'border-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items.bg-redheart' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items > span.bg-redheart' => 'color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_control(
				'favourite_bg_active_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items.bg-redheart' => 'background-color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_control(
				'favourite_border_active_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items.bg-redheart' => 'border-color: {{VALUE}} !important;',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'favourite_border',
					'selector'  => '{{WRAPPER}} .wr-freelancer-footer .wr_saved_items',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-freelancer-footer .wr_saved_items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		    global $current_user;

			$settings = $this->get_settings_for_display();
			$show_posts   = isset($settings['show_posts']) ? $settings['show_posts'] : -1;
			$listing_type = isset($settings['listing_type']) ? $settings['listing_type'] : '';
			$freelancer_by   = isset($settings['freelancer_by']) ? $settings['freelancer_by'] : '';
			$categories   = isset($settings['freelancers']) ? $settings['freelancers'] : '';

			$tax_queries = array();

			if (class_exists('WooCommerce')) {

				if(!empty($categories ) && empty($freelancer_by) && ( $listing_type === 'categories_random' || $listing_type === 'categories_recent' ) ){
					$query_relation = array('relation' => 'AND',);
					$tax_ar[] = array(
						'taxonomy'  => 'freelancer_type',
						'terms'     => $categories,
						'field'     => 'term_id',
						'operator'  => 'IN',
					);

					$tax_queries = array_merge($query_relation, $tax_ar);
				}

				//prepared query args
				$args = array(
					'post_type'         => 'freelancers',
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

				//specific posts
				if(!empty($freelancer_by)){
					$args['post__in'] = explode(',',$freelancer_by);
				}

				$query  = new WP_Query($args);

            ?>
            <div class="wr-freelancers-grid-wrapper wr-freelancers-layout-<?php echo esc_attr($settings['layout']); ?>">
                <div class="wr-freelancers-grid-items">
                    <?php
                    while ( $query->have_posts() ) {
	                    $query->the_post();
	                    $freelancer_id = get_the_ID();
	                    $freelancer_name      = workreap_get_username($freelancer_id);
	                    $wr_post_meta     = get_post_meta($freelancer_id, 'wr_post_meta', true);
	                    $freelancer_tagline   = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
	                    ?>
                        <div class="wr-freelancers-grid-item">
                            <div class="wr-freelancer-header">
	                        <?php do_action('workreap_profile_image', $freelancer_id,'',array('width' => 600, 'height' => 600)); ?>
                            <div class="wr-freelancer-header-content">
                                <div class="wr-freelancer-author-info">
		                            <?php if( !empty($freelancer_name) ){?>
                                        <div class="wr-author-name">
                                            <a href="<?php echo esc_url( get_permalink()); ?>"><?php echo esc_html($freelancer_name); ?></a>
                                            <?php do_action( 'workreap_verification_tag_html', $freelancer_id ); ?>
                                        </div>
		                            <?php } ?>
	                                <?php if(!empty($freelancer_tagline)){ ?>
                                        <h5 class="wr-tag-line"><?php echo esc_html($freelancer_tagline); ?></h5>
	                                <?php } ?>
                                    <ul class="wr-freelancer-reviews">
			                            <?php do_action('workreap_get_freelancer_rating_count', $freelancer_id); ?>
                                    </ul>
                                </div>
                                <ul class="wr-freelancer-views">
	                                <?php do_action('workreap_get_freelancer_views', $freelancer_id); ?>
                                </ul>
                            </div>
                            </div>
                            <div class="wr-freelancer-body">
                            <?php
                            $wr_hourly_rate = get_post_meta($freelancer_id, 'wr_hourly_rate', true);
                            if ($wr_hourly_rate) { ?>
                                <div class="wr-freelancer-hourly-rate">
                                    <span class="wr-freelancer-hourly-rate-title">
                                        <i class="wr-icon-credit-card" aria-hidden="true"></i>
                                        <?php echo esc_html__('Hourly Rate','workreap') ?>
                                    </span>
                                    <span class="wr-freelancer-hourly-rate-value"><?php echo sprintf(esc_html__('%s /hr', 'workreap'), workreap_price_format($wr_hourly_rate, 'return')); ?></span>
                                </div>
                            <?php }
                            $address  = apply_filters( 'workreap_user_address', $freelancer_id );
                            if( !empty($address) ){ ?>
                                <div class="wr-freelancer-address">
                                    <span class="wr-address-title">
                                        <i class="wr-icon-map-pin" aria-hidden="true"></i>
                                        <?php echo esc_html__('Location','workreap') ?>
                                    </span>
                                    <span class="wr-address-value"><?php echo esc_html($address); ?></span>
                                </div>
                            <?php }
                            do_action( 'workreap_term_tags', $freelancer_id, 'skills', '', 4, 'freelancer' );
                            ?>
                            </div>
                            <div class="wr-freelancer-footer">
                                <a href="<?php echo esc_url( get_permalink()); ?>" class="wr-btn-solid-lg wr-secondary-btn"><?php esc_html_e('View profile','workreap');?></a>
	                            <?php do_action('workreap_save_freelancer_html', $current_user->ID, $freelancer_id, '_saved_freelancers', 'v2', 'freelancers'); ?>
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

	Plugin::instance()->widgets_manager->register( new Workreap_Freelancers_Grid );
}