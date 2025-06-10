<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Workreap_Search_Bar' ) ) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Search_Bar extends Widget_Base {

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
			return 'workreap-search-bar';
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
			return __( 'Search Bar', 'workreap' );
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
			return 'eicon-search';
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
			return array( 'search', 'bar' );
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
				'enable_search',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        => esc_html__( 'Enable Search', 'workreap' ),
					'label_on'     => esc_html__( 'Hide', 'workreap' ),
					'label_off'    => esc_html__( 'Show', 'workreap' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'layout',
				[
					'type'    => Controls_Manager::SELECT,
					'label'   => esc_html__( 'Layout', 'workreap' ),
					'options' => [
						'1' => esc_html__( 'Style 1', 'workreap' ),
						'2' => esc_html__( 'Style 2', 'workreap' ),
						'3' => esc_html__( 'Style 3', 'workreap' ),
					],
					'default' => '1',
					'condition'   => [
						'enable_search' => 'yes',
					],
				]
			);

			$this->add_control(
				'search_placeholder',
				[
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Placeholder', 'workreap' ),
					'default'     => esc_html__( 'Search by category', 'workreap' ),
					'label_block' => true,
					'condition'   => [
						'enable_search' => 'yes',
					],
				]
			);

			$search_list = array();
			if ( function_exists( 'workreap_get_search_list' ) ) {
				$search_list = workreap_get_search_list( 'yes' );
			}

			$this->add_control(
				'search',
				[
					'type'        => Controls_Manager::SELECT2,
					'label'       => esc_html__( 'Search type', 'workreap' ),
					'options'     => $search_list,
					'default'     => array( 'project_search_page', 'freelancers_search_page' ),
					'label_block' => true,
					'multiple'    => true,
					'condition'   => [
						'enable_search' => 'yes',
					],
				]
			);

			$this->add_control(
				'search_button_text',
				[
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Button Text', 'workreap' ),
					'default'     => esc_html__( 'Search', 'workreap' ),
					'label_block' => true,
					'condition'   => [
						'enable_search' => 'yes',
						'layout'        => '2'
					],
				]
			);

			$this->add_control(
				'search_button_icon',
				[
					'label'                  => esc_html__( 'Search Button Icon', 'workreap' ),
					'type'                   => Controls_Manager::ICONS,
					'label_block'            => false,
					'skin'                   => 'inline',
					'exclude_inline_options' => 'svg',
					'default'                => [
						'value'   => 'fas fa-search',
						'library' => 'solid',
					],
					'condition'   => [
						'enable_search' => 'yes',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_tags',
				array(
					'label' => esc_html__( 'Tags', 'workreap' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'enable_tags',
				[
					'type'         => Controls_Manager::SWITCHER,
					'label'        => esc_html__( 'Enable Tags', 'workreap' ),
					'label_on'     => esc_html__( 'Hide', 'workreap' ),
					'label_off'    => esc_html__( 'Show', 'workreap' ),
					'return_value' => 'yes',
					'separator'    => 'before'
				]
			);

			$this->add_control(
				'tags_title',
				[
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Title', 'workreap' ),
					'default'     => esc_html__( 'Popular categories', 'workreap' ),
					'label_block' => true,
					'condition'   => [
						'enable_tags' => 'yes',
					],
				]
			);

			$this->add_control(
				'tags_tag',
				array(
					'label'   => __( 'HTML Tag', 'workreap' ),
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
					'condition'   => [
						'enable_tags' => 'yes',
					],
				)
			);

			$this->add_control(
				'tags_type',
				[
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Type', 'workreap' ),
					'options'     => $search_list,
					'default'     => 'project_search_page',
					'label_block' => true,
					'condition'   => [
						'enable_tags' => 'yes',
					],
				]
			);

			$categories = array();
//			$categories = workreap_elementor_get_taxonomies( 'product', 'product_cat', 0, '', 0 );
			$terms = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => true,
				'parent' => 0,
			) );

			if ( $terms ) {
				foreach ( $terms as $term ) {
					$categories[$term->slug] = $term->name;
				}
			}

			$this->add_control(
				'category_tags',
				[
					'type'        => Controls_Manager::SELECT2,
					'label'       => esc_html__( 'Categories', 'workreap' ),
					'options'     => $categories,
					'multiple'    => true,
					'label_block' => true,
					'condition'   => [
						'enable_tags' => 'yes',
					],
				]
			);

			$this->end_controls_section();

			//Styling
			$this->start_controls_section(
				'section_style_input',
				array(
					'label' => __( 'Input', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'search_input_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wt-formbanner fieldset .form-group .form-control',
				)
			);

			$this->add_control(
				'search_input_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wt-formbanner fieldset .form-group .form-control' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'search_input_placeholder_color',
				array(
					'label'     => __( 'Placeholder', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wt-formbanner fieldset .form-group .form-control::placeholder' => 'color: {{VALUE}} !important; opacity: 1;',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_option',
				array(
					'label' => __( 'Options', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'search_options_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wt-formbanner .wt-dropdown > span,{{WRAPPER}} .wt-radioholder .wt-radio input[type=radio] + label',
				)
			);

			$this->add_control(
				'search_options_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wt-formbanner .wt-dropdown > span > em, {{WRAPPER}} .wt-radioholder .wt-radio input[type=radio] + label' => 'color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_button',
				array(
					'label' => __( 'Button', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'search_button_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wt-searchbtn',
				)
			);

			$this->add_responsive_control(
				'search_button_icon_size',
				array(
					'label'      => __( 'Size', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 100,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 20,
					),
					'selectors'  => array(
						'{{WRAPPER}} .wt-searchbtn > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wt-searchbtn > svg' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'search_button_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wt-searchbtn,{{WRAPPER}} .wt-searchbtn > i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wt-searchbtn > svg'                         => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'search_button_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wt-searchbtn' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_tags',
				array(
					'label' => __( 'Tags', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'tags_alignment',
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
						'{{WRAPPER}} .wr-search-bar-wrapper:not(.wr-search-bar-layout-2) .wr-search-bar-tags' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
						'{{WRAPPER}} .wr-search-bar-layout-2 .wr-search-bar-tags' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
						'{{WRAPPER}} .wr-search-bar-wrapper .wr-search-bar-tags-list' => 'justify-content: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'tags_title_typography',
					'label'    => __( 'Title Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-search-bar-tags .wr-tags-title',
				)
			);

			$this->add_control(
				'tags_title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-search-bar-tags .wr-tags-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'tags_typography',
					'label'    => __( 'Tags Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-search-bar-tags-list .wr-tag',
					'separator'    => 'before'
				)
			);

			$this->add_responsive_control(
				'tags_space_between',
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
						'{{WRAPPER}} .wr-search-bar-tags-list > li' => 'grid-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_tag' );

			$this->start_controls_tab(
				'tab_tags_normal',
				array(
					'label' => __( 'Normal', 'workreap' ),
				)
			);

			$this->add_control(
				'tags_tag_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-search-bar-tags-list .wr-tag' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'tags_tag_bg_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-search-bar-tags-list .wr-tag' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_tags_hover',
				array(
					'label' => __( 'Hover', 'workreap' ),
				)
			);

			$this->add_control(
				'tags_tag_hover_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-search-bar-tags-list .wr-tag:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'tags_tag_bg_hover_color',
				array(
					'label'     => __( 'Background Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-search-bar-tags-list .wr-tag:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_responsive_control(
				'tags_tag_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'separator'  => 'before',
					'selectors'  => array(
						'{{WRAPPER}} .wr-search-bar-tags-list .wr-tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$settings    = $this->get_settings_for_display();
			$searchs     = ! empty( $settings['search'] ) ? $settings['search'] : array();
			$default_key = ! empty( $searchs ) ? reset( $searchs ) : '';
			$default_url = '';
			$category_tags = $settings['category_tags'];
			$category_key = $settings['tags_type'];
			$category_url = '';
			if ( function_exists( 'workreap_get_page_uri' ) ) {
				$default_url = ! empty( $default_key ) ? workreap_get_page_uri( $default_key ) : '';
				$category_url = ! empty( $category_key ) ? workreap_get_page_uri( $category_key ) : '';
			}
			$list_names = '';
			if ( function_exists( 'workreap_get_search_list' ) ) {
				$list_names = workreap_get_search_list( 'yes' );
			}
			?>
            <div class="wr-search-bar-wrapper wr-search-bar-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
                <?php if($settings['enable_search'] === 'yes'){?>
                <form class="wt-formtheme wt-formbanner" action="<?php echo esc_url( $default_url ); ?>" method="get">
                    <fieldset>
                        <div class="form-group">
                            <label><input type="text" name="keyword" class="form-control" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>"></label>
                            <div class="wt-formoptions">
								<?php if ( ! empty( $list_names[ $default_key ] ) ) { ?>
                                    <div class="wt-dropdown">
                                        <span><em class="selected-search-type"><?php echo esc_html( $list_names[ $default_key ] ); ?></em><i class="wr-icon-chevron-down"></i></span>
                                    </div>
								<?php } ?>
                                <div class="wt-radioholder">
									<?php
									foreach ( $searchs as $search ) {
										$action_url = '';
										if ( function_exists( 'workreap_get_page_uri' ) ) {
											$action_url = workreap_get_page_uri( $search );
										}
										if ( ! empty( $search ) && $search === $default_key ) {
											$checked = 'checked';
										} else {
											$checked = '';
										}
										$search_title = ! empty( $list_names[ $search ] ) ? $list_names[ $search ] : '';
										$flag_key 	= rand(9999, 999999);
										?>
                                        <span class="wt-radio">
                                        <input id="wt-<?php echo esc_attr( $flag_key ); ?>"
                                               data-url="<?php echo esc_url( $action_url ); ?>"
                                               data-title="<?php echo esc_attr( $search_title ); ?>" type="radio"
                                               name="searchtype"
                                               value="<?php echo esc_attr( $search ); ?>" <?php echo esc_attr( $checked ); ?>>
                                        <label for="wt-<?php echo esc_attr( $flag_key ); ?>"><?php echo esc_html( $search_title ); ?></label>
                                    </span>
									<?php } ?>
                                </div>
                                <button type="submit" class="wt-searchbtn">
									<?php if ( $settings['search_button_text'] ): ?>
                                        <span><?php echo esc_html( $settings['search_button_text'] ) ?></span>
									<?php endif; ?>
									<?php Icons_Manager::render_icon( $settings['search_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php } ?>
                <?php if($settings['enable_tags'] === 'yes'){ ?>
                    <div class="wr-search-bar-tags">
                        <?php if(!empty($settings['tags_title'])):?>
                            <<?php echo esc_attr($settings['tags_tag']); ?> class="wr-tags-title"><?php echo wp_kses_post($settings['tags_title']); ?></<?php echo esc_attr($settings['tags_tag']); ?>>
                        <?php endif; ?>
	                    <?php
                        if(!empty($category_tags)){ ?>
                            <ul class="wr-search-bar-tags-list">
                            <?php
	                        foreach ($category_tags as $category){
		                        $term = get_term_by('slug',$category,'product_cat');
		                        if(isset($term->slug) && isset($term->name)){ ?>
                                    <li><a class="wr-tag" href="<?php echo esc_url($category_url .'?category='. $term->slug); ?>"><?php echo esc_html($term->name) ?></a></li>
                                <?php }
	                        } ?>
                            </ul>
                        <?php } ?>
                    </div>
                <?php }?>
            </div>
		<?php }
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Search_Bar );
}
