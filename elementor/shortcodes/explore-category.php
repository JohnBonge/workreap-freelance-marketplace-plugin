<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('Workreap_Explore_Category') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Explore_Category extends Widget_Base {

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
			return 'workreap-explore-category';
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
			return __( 'Explore Category', 'workreap' );
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
			return 'eicon-featured-image';
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
			return array('explore','category','categories');
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
					],
					'default' 		=> '1',
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
				'category_image_type',
				[
					'label' => esc_html__( 'Category Image', 'workreap' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'none' => [
							'title' => esc_html__( 'None', 'workreap' ),
							'icon' => 'eicon-circle',
						],
						'featured' => [
							'title' => esc_html__( 'Featured', 'workreap' ),
							'icon' => 'eicon-image-bold',
						],
						'custom' => [
							'title' => esc_html__( 'Custom', 'workreap' ),
							'icon' => 'eicon-lightbox',
						],
					],
					'default' => 'featured',
					'toggle' => false,
				]
			);

			$this->add_control(
				'category_image_custom',
				[
					'label'     => esc_html__( 'Choose Image', 'workreap' ),
					'type'      => Controls_Manager::MEDIA,
					'condition' => [
						'category_image_type' => 'custom',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'      => 'thumbnail',
					'default'   => 'full',
					'separator' => 'before',
					'exclude'   => array(
						'custom',
					),
					'condition' => [
						'category_image_type' => 'custom',
					],
				)
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

			$this->add_control(
				'enable_button',
				[
					'label'        => esc_html__( 'Explore Button', 'workreap' ),
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
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Button Text', 'workreap'),
					'default'       => esc_html__('Explore All','workreap'),
					'label_block'   => true,
					'condition' => [
						'enable_button' => 'yes',
					],
					'ai' => [
						'active' => false,
					],
				]
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
					'selector' => '{{WRAPPER}} .wr-explore-category-name > a',
				)
			);

			$this->add_control(
				'category_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-explore-category-name > a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-explore-category-name > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sub_category_typography',
					'label'    => __( 'Label Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-explore-category-sub > li > a',
				)
			);

			$this->add_control(
				'sub_category_color',
				array(
					'label'     => __( 'Label Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-explore-category-sub > li > a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sub_category_count_typography',
					'label'    => __( 'Count Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-sub em',
				)
			);

			$this->add_control(
				'sub_category_count_color',
				array(
					'label'     => __( 'Count Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-sub em' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-explore-category-sub'   => 'gap: {{SIZE}}{{UNIT}};',
					)
				)
			);

			$this->add_responsive_control(
				'sub_category_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-explore-category-sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a',
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
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a' => 'width: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a > svg' => 'width: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_separator_color',
				array(
					'label'     => __( 'Separator Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer:before' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-explore-category-wrapper:hover .wr-explore-category-footer > a' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-explore-category-wrapper:hover .wr-explore-category-footer > a > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-explore-category-wrapper:hover .wr-explore-category-footer > a > svg' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-explore-category-wrapper:hover .wr-explore-category-footer > a' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a::before' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'button_border_hover_color',
				array(
					'label'     => __( 'Border Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-explore-category-wrapper:hover .wr-explore-category-footer > a' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'button_border',
					'selector'  => '{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a',
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
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-explore-category-wrapper .wr-explore-category-footer > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-explore-category-image > img' => 'height: {{SIZE}}{{UNIT}};',
					),
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
						'{{WRAPPER}} .wr-explore-category-image > img' => 'object-fit: {{VALUE}};',
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
						$search_page = $search_page . '?category=' . $parent_cat->slug; //&sub_category=
					} ?>
                    <div class="wr-explore-category-wrapper wr-explore-category-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
                        <div class="wr-explore-category-content">
                        <h4 class="wr-explore-category-name">
                            <a href="<?php echo esc_url($search_page); ?>"><?php echo esc_html($parent_cat->name) ?></a>
                        </h4>
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
	                        <ul class="wr-explore-category-sub">
                                <?php foreach ($sub_categories as $sub_category){ ?>
                                    <li>
                                        <a href="<?php echo esc_url($search_page . '&sub_category=' .$sub_category->slug) ?>">
                                            <span><?php echo esc_html($sub_category->name); ?></span>
                                            <em>(<?php echo esc_html( $sub_category->count ); ?>)</em>
                                        </a></li>
                                <?php }?>
                            </ul>
	                    <?php } ?>
                            <?php if($settings['enable_button'] === 'yes' && !empty($settings['button_text'])){ ?>
                                <div class="wr-explore-category-footer">
                                    <a href="<?php echo esc_url($search_page); ?>" class="wr-secondary-btn">
                                        <span><?php echo esc_html($settings['button_text']); ?></span>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if($settings['category_image_type'] !== 'none'){
	                        $thumbnail_id = get_term_meta( $parent_cat->term_id, 'thumbnail_id', true );
	                        $cat_image     = ! empty( $thumbnail_id ) ? wp_get_attachment_url( $thumbnail_id ) : '';
	                        ?>
                            <figure class="wr-explore-category-image">
                                <?php
                                if($settings['category_image_type'] === 'custom'){
	                                if ( !empty( $settings['category_image_custom']['id'] ) ) {
		                                echo wp_get_attachment_image( $settings['category_image_custom']['id'], $settings['thumbnail_size'] );
	                                }
                                }else{?>
                                    <img src="<?php echo esc_url($cat_image); ?>" alt="<?php echo esc_attr($parent_cat->name) ?>">
                                <?php } ?>
                            </figure>
                        <?php } ?>
                    </div>
					<?php
				}

			}
		}

	}
	Plugin::instance()->widgets_manager->register(new Workreap_Explore_Category);
}
