<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_Content_Box') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Content_Box extends Widget_Base {

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
			return 'workreap-content-box';
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
			return __( 'Content Box', 'workreap' );
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
			return 'eicon-heading';
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
			return array('content','heading','description');
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
						'3' => esc_html__('Style 3', 'workreap'),
					],
					'default' 		=> '1',
				]
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
						'{{WRAPPER}} .wr-content-box-wrapper' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'subtitle',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Sub Title', 'workreap'),
					'default' 	=> esc_html__('Boost Your Working Flow', 'workreap'),
					'separator'    => 'before',
				]
			);

			$this->add_control(
				'subtitle_tag',
				array(
					'label'   => __( 'Sub Title Tag', 'workreap' ),
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
				)
			);

			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Title', 'workreap'),
					'default' 	=> esc_html__('Thrive in the {{World of Freelance}} Excellence Marketplace!', 'workreap'),
					'separator'    => 'before',
				]
			);

			$this->add_control(
				'title_tag',
				array(
					'label'   => __( 'Title Tag', 'workreap' ),
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
						'a' => 'a',
					),
					'default' => 'h2',
				)
			);

			$this->add_control(
				'title_link',
				array(
					'label'       => __( 'Title Link', 'workreap' ),
					'type'        => Controls_Manager::URL,
					'dynamic'     => array(
						'active' => true,
					),
					'condition'   => array(
						'title_tag'    => 'a',
					),
				)
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap' ),
					'default' 	=> esc_html__('Flourish in a thriving freelance ecosystem dedicated to excellence and limitless opportunities.', 'workreap'),
					'rows' 			=> 5,
					'separator'    => 'before',
				]
			);

			$this->end_controls_section();

			//Styling Tab
			$this->start_controls_section(
				'section_style_subtitle',
				array(
					'label' => __( 'Sub Title', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition'  => array(
						'subtitle!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'subtitle_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-content-box-wrapper .wr-subtitle',
				)
			);

			$this->add_control(
				'subtitle_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-subtitle' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'subtitle_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_title',
				array(
					'label' => __( 'Title', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition'  => array(
						'title!' => '',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-content-box-wrapper .wr-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-title' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-content-box-wrapper .wr-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'highlight_title_heading',
				array(
					'label'     => __( 'Highlight', 'workreap' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'highlight_title_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-content-box-wrapper .wr-highlighted-text',
				)
			);

			$this->add_control(
				'highlight_title_color',
				array(
					'label'     => __( 'Primary Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-highlighted-text' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'highlight_title_secondary_color',
				array(
					'label'     => __( 'Secondary Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-highlighted-text' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: linear-gradient(90deg, {{highlight_title_color.VALUE}} 0%, {{VALUE}} 100%)',
					),
					'condition'  => array(
						'layout' => array('3'),
					),
				)
			);

			$this->add_control(
				'highlight_title_effect_color',
				array(
					'label'     => __( 'Effect Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-highlighted-text::after' => 'fill: {{VALUE}};',
					),
					'condition'  => array(
						'layout' => array('1','2'),
					),
				)
			);

			$this->add_responsive_control(
				'highlight_title_padding',
				array(
					'label'      => __( 'Padding', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-highlighted-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style_desc',
				array(
					'label' => __( 'Description', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition'  => array(
						'description!' => '',
					),
				)
			);

			$this->add_responsive_control(
				'desc_width',
				array(
					'label'      => __( 'Width', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-description' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
					)
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'desc_typography',
					'label'    => __( 'Typography', 'workreap' ),
					'selector' => '{{WRAPPER}} .wr-content-box-wrapper .wr-description, {{WRAPPER}} .wr-content-box-wrapper .wr-description > *',
				)
			);

			$this->add_control(
				'desc_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-description, {{WRAPPER}} .wr-content-box-wrapper .wr-description > *' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'desc_margin',
				array(
					'label'      => __( 'Margin', 'workreap' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors'  => array(
						'{{WRAPPER}} .wr-content-box-wrapper .wr-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$title_tag = $settings['title_tag'];
			$subtitle_tag = $settings['subtitle_tag'];
			$attr = '';
			if($title_tag === 'a' && !empty($settings['title_link']['url'])){
				$attr .= ' href="' . $settings['title_link']['url'] . '"';
				$attr .= $settings['title_link']['is_external'] ? ' target="_blank"' : '';
				$attr .= $settings['title_link']['nofollow'] ? ' rel="nofollow"' : '';
            }
			?>
			<div class="wr-content-box-wrapper wr-content-box-layout-<?php echo esc_attr($settings['layout']); ?>">
                <?php if(!empty($settings['subtitle'])):?>
                    <<?php echo esc_attr($subtitle_tag); ?> class="wr-subtitle"><?php echo wp_kses_post($settings['subtitle']); ?></<?php echo esc_attr($subtitle_tag); ?>>
                <?php endif; ?>
                <?php if($settings['title']):
					$title = str_replace( '{{', '<span class="wr-highlighted-text">', $settings['title'] );
					$title = str_replace( '}}', '</span>', $title );
					?>
					<<?php echo esc_attr($title_tag . $attr); ?> class="wr-title"><?php echo wp_kses_post($title); ?></<?php echo esc_attr($title_tag); ?>>
				<?php endif; ?>
                <?php if(!empty($settings['description'])):?>
                    <div class="wr-description"><?php echo wp_kses_post($settings['description']); ?></div>
                <?php endif; ?>
			</div>
		<?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Content_Box);
}
