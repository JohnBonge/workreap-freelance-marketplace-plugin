<?php

namespace Elementor;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Workreap_Post_Grid' ) ) {
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Post_Grid extends Widget_Base {

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
			return 'workreap-post-grid';
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
			return __( 'Post Grid', 'workreap' );
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
			return 'eicon-posts-grid';
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
			return array( 'post', 'grid' );
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

			$post_categories = array();
			if( function_exists('workreap_elementor_get_taxonomies') ){
				$post_categories = workreap_elementor_get_taxonomies();
			}
			$post_categories = !empty($post_categories) ? $post_categories : array();

			$this->add_control(
				'post_categories',
				[
					'type'          => Controls_Manager::SELECT2,
					'label'         => esc_html__('Categories', 'workreap'),
					'desc'          => esc_html__('Select categories.', 'workreap'),
					'options'       => $post_categories,
					'multiple'      => true,
					'label_block'   => true,
				]
			);

			$this->add_control(
				'no_post_show',
				[
					'label'      => esc_html__( 'Limit', 'workreap' ),
					'type'       => Controls_Manager::NUMBER,
					'min'         => 1,
					'max'         => 20,
					'step'        => 1,
					'default'     => 6,
				]
			);

			$this->add_control(
				'order_by',
				[
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__('Order', 'workreap'),
					'default'   => 'ASC',
					'options'   => [
						'ASC'   => esc_html__('Ascending', 'workreap'),
						'DESC'  => esc_html__('Descending', 'workreap'),
						'rand'  => esc_html__('Random', 'workreap'),
					],
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
						'{{WRAPPER}} .wr-post-grid-items' => 'display: grid; grid-template-columns:repeat({{SIZE}}, 1fr)',
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
						'{{WRAPPER}} .wr-post-grid-items' => 'grid-gap:{{SIZE}}px;',
					)
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_category_style',
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
					'selector' => '{{WRAPPER}} .wr-post-grid-category,{{WRAPPER}} .wr-post-grid-category > a',
				)
			);

			$this->add_control(
				'category_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-post-grid-category,{{WRAPPER}} .wr-post-grid-category > a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-post-grid-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-post-grid-date',
				)
			);

			$this->add_control(
				'date_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-post-grid-date' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-post-grid-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-post-grid-title > a',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-post-grid-title > a' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-post-grid-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .wr-post-grid-description, {{WRAPPER}} .wr-post-grid-description > *',
				)
			);

			$this->add_control(
				'description_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-post-grid-description, {{WRAPPER}} .wr-post-grid-description > *' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-post-grid-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$post_category_ids  = !empty($settings['post_categories']) ? $settings['post_categories'] : array();
			$no_post_show       = !empty($settings['no_post_show']) ? $settings['no_post_show'] : 3;
			$order_by           = !empty($settings['order_by']) ? $settings['order_by'] : '';
			$args               = array();

			if (is_array($post_category_ids) && !empty($post_category_ids)) {
				$args = array(
					'post_type'         => 'post',
					'paged'             => -1,
					'posts_per_page'    => $no_post_show,
					'order'             => $order_by,
					'orderby'           => 'title',
					'category__in'      => $post_category_ids
				);
			} else {
				$args = array(
					'post_type'         => 'post',
					'paged'             => -1,
					'posts_per_page'    => $no_post_show,
					'order'             => $order_by,
					'orderby'           => 'title'
				);
			}

			$posts      = new \WP_Query(apply_filters('workreap_post_grid_posts_args', $args));

			if($posts->have_posts()){ ?>
                <div class="wr-post-grid-wrapper">
                    <div class="wr-post-grid-items">
                        <?php while($posts->have_posts()){
	                        $posts->the_post();
	                        $post_id = get_the_ID();
	                        ?>
                            <div class="wr-post-grid-item">
                                <?php if(has_post_thumbnail($post_id)){ ?>
                                    <figure class="wr-post-grid-featured-image">
	                                    <?php echo get_the_post_thumbnail($post_id); ?>
                                    </figure>
                                <?php } ?>
                                <div class="wr-post-grid-header">
                                    <div class="wr-post-grid-category">
                                        <?php echo get_the_category_list(', ', '', $post_id); ?>
                                    </div>
                                    <div class="wr-post-grid-date">
                                        <?php echo get_the_date('M d, Y'); ?>
                                    </div>
                                </div>
                                <h2 class="wr-post-grid-title"><a href="<?php echo get_permalink($post_id); ?>"><?php echo get_the_title(); ?></a></h2>
                                <div class="wr-post-grid-description">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        <?php }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
				<?php
            }
		}
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Post_Grid );
}
