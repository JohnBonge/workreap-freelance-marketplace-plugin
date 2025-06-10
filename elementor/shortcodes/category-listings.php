<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('Workreap_Category_Listing') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Category_Listing extends Widget_Base {

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
			return 'workreap-category-listing';
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
			return __( 'Category Listing', 'workreap' );
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
			return 'eicon-carousel';
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
			return array('slider','category','categories');
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

			//Settings
			$this->start_controls_section(
				'section_settings',
				array(
					'label' => __( 'Settings', 'workreap' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'slide_speed',
				array(
					'label'              => __( 'Slide Speed', 'workreap' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => array( 'px' ),
					'default'            => array(
						'size' => 3,
					),
					'range'              => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_responsive_control(
				'space_between',
				array(
					'label'              => __( 'Space Between', 'workreap' ),
					'type'               => Controls_Manager::SLIDER,
					'devices'            => [ 'desktop', 'tablet', 'mobile' ],
					'size_units'         => array( 'px' ),
					'desktop_default'    => array(
						'size' => 15,
					),
					'tablet_default'     => array(
						'size' => 15,
					),
					'mobile_default'     => array(
						'size' => 15,
					),
					'range'              => array(
						'px' => array(
							'min' => 1,
							'max' => 100,
						),
					),
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_responsive_control(
				'item_per_row',
				array(
					'label'              => __( 'Items Show', 'workreap' ),
					'type'               => Controls_Manager::NUMBER,
					'devices'            => [ 'desktop', 'tablet', 'mobile' ],
					'placeholder'        => 2,
					'desktop_default'    => 3,
					'tablet_default'     => 2,
					'mobile_default'     => 1,
					'min'                => 1,
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_responsive_control(
				'slides_per_group',
				array(
					'label'              => __( 'Slides Per Group', 'workreap' ),
					'type'               => Controls_Manager::NUMBER,
					'devices'            => [ 'desktop', 'tablet', 'mobile' ],
					'placeholder'        => 1,
					'desktop_default'    => 1,
					'tablet_default'     => 1,
					'mobile_default'     => 1,
					'min'                => 1,
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'loop',
				array(
					'label'              => __( 'Loop', 'workreap' ),
					'type'               => Controls_Manager::SWITCHER,
					'return_value'       => 'yes',
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'mouse_drag',
				array(
					'label'              => __( 'Mouse Drag', 'workreap' ),
					'type'               => Controls_Manager::SWITCHER,
					'return_value'       => 'yes',
					'default'            => 'yes',
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'mouse_wheel',
				array(
					'label'              => __( 'Mouse Wheel', 'workreap' ),
					'type'               => Controls_Manager::SWITCHER,
					'return_value'       => 'yes',
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'autoplay',
				array(
					'label'              => __( 'Autoplay', 'workreap' ),
					'type'               => Controls_Manager::SWITCHER,
					'return_value'       => 'yes',
					'render_type'        => 'template',
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'autoplay_timeout',
				array(
					'label'              => __( 'Autoplay Timeout', 'workreap' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => array( 'px' ),
					'default'            => array(
						'size' => 3,
					),
					'range'              => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'render_type'        => 'template',
					'frontend_available' => true,
					'condition'          => array(
						'autoplay' => 'yes',
					),
				)
			);

			$this->add_control(
				'scroll_bar',
				array(
					'label'              => __( 'Scroll Bar', 'workreap' ),
					'type'               => Controls_Manager::SWITCHER,
					'return_value'       => 'yes',
					'render_type'        => 'template',
					'frontend_available' => true,
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
				'height',
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
						'{{WRAPPER}} .wr-category-listing-item' => 'height: {{SIZE}}{{UNIT}};',
					),
					'render_type'        => 'template',
				)
			);

			$this->add_responsive_control(
				'border_radius',
				array(
					'label'      => __( 'Border Radius', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-listing-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-category-name > a',
				)
			);

			$this->add_control(
				'name_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-name > a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-category-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_count',
				array(
					'label' => __( 'Count', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'count_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-category-count',
				)
			);

			$this->add_control(
				'count_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-count' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'count_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-category-count' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'count_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-category-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$rtl = is_rtl() ? 'rtl' : 'ltr';
			if( function_exists('workreap_get_page_uri') ){
				$search_page  = workreap_get_page_uri($post_type);
			}
			if($categories){ ?>
                <div dir="<?php echo esc_attr($rtl); ?>" class="wr-category-listing-wrapper">
                    <div dir="<?php echo esc_attr($rtl); ?>" class="wr-category-listing swiper">
                        <div class="swiper-wrapper">
                        <?php foreach( $categories as $key => $cat_id ) {
                            $category      = get_term($cat_id);
	                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
	                        $attachment_url = wp_get_attachment_url($thumbnail_id);
	                        $query_arg['category']   	= urlencode($category->slug);
	                        $permalink                 	= add_query_arg( $query_arg, esc_url($search_page));
	                        if(empty($attachment_url)){
		                        $attachment_url = \Elementor\Utils::get_placeholder_image_src();
	                        }
                            ?>
                            <div class="swiper-slide">
                                <div class="wr-category-listing-item" style="background-image: url('<?php echo esc_url($attachment_url); ?>')">
                                    <span class="wr-category-count"><?php echo intval($category->count);?> <?php esc_html_e('listing','workreap'); ?></span>
                                    <h4 class="wr-category-name"><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_html($category->name); ?></a></h4>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
					<?php if($settings['scroll_bar'] === 'yes'){ ?>
						<div class="swiper-scrollbar"></div>
					<?php } ?>
                </div>
            <?php }
		}

	}
	Plugin::instance()->widgets_manager->register(new Workreap_Category_Listing);
}
