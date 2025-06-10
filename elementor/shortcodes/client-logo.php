<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_Client_Logo') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Client_Logo extends Widget_Base {

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
			return 'workreap-client-logo';
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
			return __( 'Client Logo', 'workreap' );
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
			return 'eicon-logo';
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
					'render_type'        => 'template',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'enable_shadow',
				[
					'label'        => esc_html__( 'Enable Shadow', 'workreap' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Hide', 'workreap' ),
					'label_off' 	=> esc_html__( 'Show', 'workreap' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition' => array(
						'layout' => '3',
					),
				]
			);

			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Title', 'workreap'),
					'default' 	=> esc_html__('Trusted by', 'workreap'),
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
					),
					'default' => 'h2',
				)
			);

			$repeater = new Repeater();

			$repeater->add_control(
				'image',
				array(
					'label'   => __( 'Logo', 'workreap' ),
					'type'    => Controls_Manager::MEDIA,
					'dynamic' => array(
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

			$repeater->add_control(
				'name',
				array(
					'label'       => __( 'Name', 'workreap' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => __( 'Name', 'workreap' ),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$this->add_control(
				'items',
				array(
					'label'       => __( 'Logos', 'workreap' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ name }}}',
					'separator'    => 'before',
					'default'     => array(
						array(
							'name' => 'Item #1',
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'      => 'thumbnail',
					'default'   => 'full',
					'exclude'   => array(
						'custom',
					),
				)
			);

			$this->add_responsive_control(
				'alignment',
				array(
					'label'     => __( 'Alignment', 'workreap' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
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
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .elementor-widget-container,{{WRAPPER}} .wr-client-logo-layout-3 .wr-title' => 'text-align: {{VALUE}};',
						'{{WRAPPER}} .wr-client-logo-layout-2' => 'justify-content: {{VALUE}};',
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
					'selector' => '{{WRAPPER}} .wr-client-logo-wrapper .wr-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => __( 'Color', 'workreap' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wr-client-logo-wrapper .wr-title' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .wr-client-logo-wrapper .wr-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_image_style',
				array(
					'label' => __( 'Logo', 'workreap' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'image_width',
				array(
					'label'      => __( 'Width', 'workreap' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
					'range'      => array(
						'px' => array(
							'min' => 1,
							'max' => 1000,
						)
					),
					'selectors'  => array(
						'{{WRAPPER}} .wr-client-logo-figure > img' => 'width: {{SIZE}}{{UNIT}};',
					),
					'render_type'        => 'template',
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
						'{{WRAPPER}} .wr-client-logo-figure > img' => 'height: {{SIZE}}{{UNIT}};',
					),
					'render_type'        => 'template',
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
						'{{WRAPPER}} .wr-client-logo-figure > img' => 'object-fit: {{VALUE}};',
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
			$rtl = is_rtl() ? 'rtl' : 'ltr';
			?>
            <div dir="<?php echo esc_attr($rtl); ?>" class="wr-client-logo-wrapper wr-client-logo-layout-<?php echo esc_attr($settings['layout']); ?>">
			<?php if(!empty($settings['title'])):?>
                <<?php echo esc_attr($title_tag); ?> class="wr-title"><?php echo wp_kses_post($settings['title']); ?></<?php echo esc_attr($title_tag); ?>>
			<?php endif; ?>
                <div class="wr-client-logo<?php echo esc_attr($settings['layout'] === '3' ? ' swiper' : ' wr-client-logo-static'); ?><?php echo $settings['enable_shadow'] === 'yes' ? ' wr-client-logo-shadow' : ''; ?>">
                    <div class="<?php echo esc_attr($settings['layout'] === '3' ? 'swiper-wrapper' : 'wr-client-logo-inner'); ?>">
		                <?php foreach ($settings['items'] as $i => $item){ ?>
                        <div class="<?php echo esc_attr($settings['layout'] === '3' ? ' swiper-slide' : ' wr-client-logo-item-wrapper'); ?>">
			                <?php
			                $html_tag = ( $item['link']['url'] ) ? 'a' : 'span';
			                $attr     = $item['link']['is_external'] ? ' target="_blank"' : '';
			                $attr    .= $item['link']['nofollow'] ? ' rel="nofollow"' : '';
			                $attr    .= $item['link']['url'] ? ' href="' . $item['link']['url'] . '"' : '';

			                if(isset($item['image']['id']) || isset($item['image']['url']) ){ ?>
                            <<?php echo esc_attr($html_tag) ?> class="wr-client-logo-item" <?php echo wp_kses_post($attr); ?>>
                            <figure class="wr-client-logo-figure"><?php
				                if(!empty($item['image']['id'])){
					                echo wp_get_attachment_image( $item['image']['id'], $settings['thumbnail_size'] );
				                } ?>
                            </figure>
                        </<?php echo esc_attr($html_tag) ?>>
	                <?php } ?>
                    </div>
	                <?php } ?>
                </div>
                </div>
            </div>
		<?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Client_Logo);
}
