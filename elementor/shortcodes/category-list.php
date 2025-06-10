<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('Workreap_Category_List') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Category_List extends Widget_Base {

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
			return 'workreap-category-list';
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
			return __( 'Category List', 'workreap' );
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
			return 'eicon-bullet-list';
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
			return array('list','category','categories');
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
				'category_type',
				[
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Category Type', 'workreap' ),
					'options'     => array(
						'project_search_page' => esc_html__( 'Projects', 'workreap' ),
						'service_search_page' => esc_html__( 'Services', 'workreap' ),
                    ),
					'default'     => 'project_search_page',
				]
			);

			$categories = array();
			$terms = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => false,
				'parent' => 0,
			) );

			if ( $terms ) {
				foreach ( $terms as $term ) {
					$categories[$term->slug] = $term->name;
				}
			}
			$categories['none'] = __('None','workreap');

			$this->add_control(
				'category',
				[
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Choose Category', 'workreap' ),
					'options'     => $categories,
					'multiple'    => false,
					'default'     => 'none',
				]
			);

			$this->add_control(
				'category_icon',
				[
					'label'                  => esc_html__( 'Icon', 'workreap' ),
					'type'                   => Controls_Manager::ICONS,
					'skin'                   => 'inline',
					'label_block'            => false,
				]
			);

			$this->add_control(
				'sub_category_show',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Sub Category Show', 'workreap'),
					'options' 		=> [
						'ids' => esc_html__('By IDs', 'workreap'),
						'random' => esc_html__('Random', 'workreap'),
					],
					'default' 		=> 'random',
					'separator'     => 'before',
				]
			);

			$this->add_control(
				'sub_category_ids',
				[
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Custom IDs', 'workreap' ),
					'placeholder' => esc_html__( '210,252', 'workreap' ),
					'description' => esc_html__( 'Ensure that only comma-separated IDs are used.', 'workreap' ),
					'label_block' => true,
					'condition'   => [
						'sub_category_show' => 'ids',
					],
					'ai' => [
						'active' => false,
					],
				]
			);

			$this->add_control(
				'sub_category_limit',
				array(
					'label'       => __( 'Sub Category Limit', 'workreap' ),
					'type'        => Controls_Manager::NUMBER,
					'label_block' => false,
					'min'         => 1,
					'max'         => 20,
					'step'        => 1,
					'default'     => 5,
					'condition'   => [
						'sub_category_show' => 'random',
					],
				)
			);

			$this->end_controls_section();

			//Styling
			$this->start_controls_section(
				'section_style_category',
				array(
					'label' => __( 'Category', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'category_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-category-list-name > a',
				)
			);

			$this->add_responsive_control(
				'icon_size',
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
						'{{WRAPPER}} .wr-category-list-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-category-list-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_bg_size',
				array(
					'label'      => __( 'Icon Background Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 5,
							'max' => 300,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-list-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'category_color',
				array(
					'label'     => __( 'Text Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-list-name > a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'category_icon_color',
				array(
					'label'     => __( 'Icon Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-list-icon > i'   => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-category-list-icon > svg' => 'fill: {{VALUE}};',
						'{{WRAPPER}} .wr-category-list-icon' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'category_icon_bg',
				array(
					'label'     => __( 'Background Background', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-list-icon' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'category_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-list-name > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_sub_category',
				array(
					'label' => __( 'Sub Category', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_control(
				'sub_category_list_style',
				[
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'List Style', 'workreap' ),
					'options'     => array(
						'none' => esc_html__( 'None', 'workreap' ),
						'circle' => esc_html__( 'Circle', 'workreap' ),
						'square' => esc_html__( 'Square', 'workreap' ),
						'disc' => esc_html__( 'Disc', 'workreap' ),
					),
					'default'     => 'none',
					'selectors' => array(
						'{{WRAPPER}} .wr-category-list-sub'   => 'list-style-type: {{VALUE}};',
					),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sub_category_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-category-list-sub > li > a',
				)
			);

			$this->add_control(
				'sub_category_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-list-sub > li > a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'sub_category_space_between',
				array(
					'label'      => __( 'Space Between', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 5,
							'max' => 300,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-list-sub'   => 'gap: {{SIZE}}{{UNIT}};',
					)
				)
			);

			$this->add_responsive_control(
				'sub_category_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-list-sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'sub_category_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-list-sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$category = $settings['category'];

			if ( isset( $category ) && ! empty( $category ) && $category !== 'none' ) {
				$parent_cat = get_term_by( 'slug',$settings['category'],'product_cat' );
				if ( isset( $parent_cat ) && ! empty( $parent_cat ) ) {
					$search_page = '';
					if ( function_exists( 'workreap_get_page_uri' ) ) {
						$search_page = workreap_get_page_uri( $settings['category_type'] );
						$search_page = $search_page . '?category=' . $parent_cat->slug;
					} ?>
                    <div class="wr-category-list-wrapper">
                        <div class="wr-category-list-content">
                        <h4 class="wr-category-list-header">
                            <?php if($settings['category_icon']['value']): ?>
                            <span class="wr-category-list-icon">
                               <?php Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </span>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($search_page); ?>"><?php echo esc_html($parent_cat->name) ?></a>
                        </h4>
                        <div class="wr-category-list-body">
	                    <?php
	                    $product_type = $settings['category_type'] === 'project_search_page' ? 'projects' : 'tasks';
	                    $sub_category_args = array(
		                    'taxonomy'   => 'product_cat',
		                    'hide_empty' => false,
		                    'orderby'    => 'name',
		                    'order'      => 'ASC',
		                    'fields'     => 'all',
		                    'parent'     => $parent_cat->term_id,
		                    'number'     => $settings['sub_category_limit'] ? $settings['sub_category_limit'] : 0,
	                    );

	                    if($settings['sub_category_show'] === 'ids' && !empty($settings['sub_category_ids'])){
		                    $child_terms     = explode( ',', $settings['sub_category_ids'] );
		                    $sub_category_args['include'] = $child_terms;
	                    }

	                    $sub_categories = get_terms($sub_category_args);

	                    if(isset($sub_categories) && !empty($sub_categories)){ ?>
	                        <ul class="wr-category-list-sub">
                                <?php foreach ($sub_categories as $sub_category){ ?>
                                    <li>
                                        <a href="<?php echo esc_url($search_page . '&sub_category=' .$sub_category->slug) ?>">
                                            <span><?php echo esc_html($sub_category->name); ?></span>
                                        </a></li>
                                <?php }?>
                            </ul>
	                    <?php } ?>
                        </div>
                        </div>
                    </div>
					<?php
				}
			}
		}

	}
	Plugin::instance()->widgets_manager->register(new Workreap_Category_List);
}
