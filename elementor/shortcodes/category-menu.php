<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('Workreap_Category_Menu') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Category_Menu extends Widget_Base {

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
			return 'workreap-category-menu';
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
			return __( 'Category Menu', 'workreap' );
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
			return 'eicon-nav-menu';
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
			return array('menu','category','categories');
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
				'heading',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Heading', 'workreap'),
					'default' 	=> esc_html__('All Categories', 'workreap'),
				]
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
					'label_block' 	=> true,
				]
			);

			$categories = array();
			$categories = workreap_elementor_get_taxonomies( 'product', 'product_cat', 0, '', 0 );

			$this->add_control(
				'categories',
				[
					'type'        => Controls_Manager::SELECT2,
					'label'       => esc_html__( 'Choose Category', 'workreap' ),
					'options'     => $categories,
					'multiple'    => true,
					'label_block' 	=> true,
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_heading',
				array(
					'label' => __( 'Heading', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'heading_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-category-menu-all > a',
				)
			);

			$this->add_responsive_control(
				'heading_icon_size',
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
						'{{WRAPPER}} .wr-category-menu-all > a > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-category-menu-all > a > svg' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'primary_button_icon[value]!' => '',
					),
				)
			);

			$this->add_control(
				'heading_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-menu-all > a' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-category-menu-all > a > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'heading_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-menu-all > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_menu',
				array(
					'label' => __( 'Menu', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'menu_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-category-menu-list .wr-category-name > a',
				)
			);

			$this->add_responsive_control(
				'space_between',
				array(
					'label'              => __( 'Space Between', 'workreap' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => array( 'px' ),
					'default'    => array(
						'size' => 15,
					),
					'range'              => array(
						'px' => array(
							'min' => 1,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-menu-list > li' => 'grid-gap: {{SIZE}}px;',
					),
				)
			);

			$this->add_control(
				'menu_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-menu-list .wr-category-name > a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'menu_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-menu-list .wr-category-name > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$post_type           	= !empty($settings['category_type']) ? $settings['category_type'] : 'jobs';
			$categories          	= !empty($settings['categories']) ? $settings['categories'] : array();
			$search_page	= '';
			if( function_exists('workreap_get_page_uri') ){
				$search_page  = workreap_get_page_uri($post_type);
			}
			if($categories){ ?>
                <div class="wr-category-menu-wrapper">
                    <?php if($settings['heading']){ ?>
                        <div class="wr-category-menu-all">
                            <a href="<?php echo esc_url($search_page); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                </svg>
			                    <?php echo esc_html($settings['heading']); ?>
                            </a>
                        </div>
                    <?php }?>
                    <ul class="wr-category-menu-list">
	                    <?php foreach( $categories as $key => $cat_id ) {
		                    $category      = get_term($cat_id);
		                    if(isset($category->slug) && isset($category->name)){
		                    $query_arg['category']   	= urlencode($category->slug);
		                    $permalink                 	= add_query_arg( $query_arg, esc_url($search_page));
		                    ?>
                            <li class="wr-category-menu-item">
                                <span class="wr-category-name"><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_html($category->name); ?></a></span>
                            </li>
	                    <?php }
	                    } ?>
                    </ul>
                </div>
            <?php }
		}

	}
	Plugin::instance()->widgets_manager->register(new Workreap_Category_Menu);
}
