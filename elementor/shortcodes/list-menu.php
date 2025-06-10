<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_List_Menu') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_List_Menu extends Widget_Base {

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
			return 'workreap-list-menu';
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
			return __( 'List Menu', 'workreap' );
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
			return 'eicon-toggle';
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
			return array('menu','list');
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
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Title', 'workreap'),
					'default' 	=> esc_html__('List Title', 'workreap'),
				]
			);

			$this->add_control(
				'list_icon',
				[
					'label'                  => esc_html__( 'Icon', 'workreap' ),
					'type'                   => Controls_Manager::ICONS,
					'skin'                   => 'inline',
					'label_block'            => false,
				]
			);

			$repeater = new Repeater();

			$repeater->add_control(
				'title',
				array(
					'label'       => __( 'List Title', 'workreap' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => __( 'Menu Link', 'workreap' ),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$repeater->add_control(
				'link',
				array(
					'label'       => __( 'URL', 'workreap' ),
					'type'        => Controls_Manager::URL,
					'label_block' => true,
					'dynamic'     => array(
						'active' => true,
					),
					'default'     => array(
						'url'         => '#',
						'is_external' => true,
						'nofollow'    => true,
					),
				)
			);

			$this->add_control(
				'items',
				array(
					'label'       => __( 'List', 'workreap' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ name }}}',
					'separator'    => 'before',
					'default'     => array(
						array(
							'title' => 'Menu Link #1',
						),
						array(
							'title' => 'Menu Link #2',
						),
						array(
							'title' => 'Menu Link #3',
						),
					),
				)
			);

			$this->end_controls_section();

			//Styling
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
					'selector' => '{{WRAPPER}} .wr-list-menu-title',
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
						'{{WRAPPER}} .wr-list-menu-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wr-list-menu-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-list-menu-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Text Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-list-menu-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'icon_color',
				array(
					'label'     => __( 'Icon Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-list-menu-icon > i'   => 'color: {{VALUE}};',
						'{{WRAPPER}} .wr-list-menu-icon > svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'icon_bg',
				array(
					'label'     => __( 'Icon Background', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-list-menu-icon' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-list-menu-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

			$this->add_control(
				'menu_list_style',
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
						'{{WRAPPER}} .wr-list-menu'   => 'list-style-type: {{VALUE}};',
					),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'menu_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-list-menu > li > a',
				)
			);

			$this->add_control(
				'menu_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-list-menu > li > a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'menu_space_between',
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
						'{{WRAPPER}} .wr-list-menu'   => 'gap: {{SIZE}}{{UNIT}};',
					)
				)
			);

			$this->add_responsive_control(
				'menu_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-list-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .wr-list-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			?>
            <div class="wr-list-menu-wrapper">
                <?php if($settings['title']){ ?>
                <h5 class="wr-list-menu-title">
	                <?php if($settings['list_icon']['value']): ?>
                        <span class="wr-list-menu-icon">
                           <?php Icons_Manager::render_icon( $settings['list_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
	                <?php endif; ?>
	                <?php echo esc_html($settings['title']); ?>
                </h5>
                <?php } ?>
                <?php if(!empty($settings['items'])){ ?>
                    <ul class="wr-list-menu">
                        <?php
                            foreach ($settings['items'] as $item){
	                            $html_tag = ( $item['link']['url'] ) ? 'a' : 'span';
	                            $attr     = $item['link']['is_external'] ? ' target="_blank"' : '';
	                            $attr    .= $item['link']['nofollow'] ? ' rel="nofollow"' : '';
	                            $attr    .= $item['link']['url'] ? ' href="' . $item['link']['url'] . '"' : '';
	                            ?>
                                <li>
                                    <<?php echo esc_attr($html_tag) ?> class="wr-list-menu-item" <?php echo wp_kses_post($attr); ?>>
                                    <?php echo esc_html($item['title']); ?>
                                    </<?php echo esc_attr($html_tag) ?>>
                                </li>
                            <?php }
                        ?>
                    </ul>
                <?php }?>
            </div>
         <?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_List_Menu);
}
