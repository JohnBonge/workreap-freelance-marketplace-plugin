<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

if( !class_exists('Workreap_Pricing') ){
	/**
	 * Workreap Elements
	 *
	 * Elementor widget.
	 *
	 * @since 1.0.0
	 */
	class Workreap_Pricing extends Widget_Base {

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
			return 'workreap-pricing';
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
			return __( 'Pricing', 'workreap' );
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
			return 'eicon-price-table';
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
			return array('pricing','plans');
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
				'type',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Type', 'workreap'),
					'options' 		=> [
						'freelancer' => esc_html__('Freelancer', 'workreap'),
						'employer' => esc_html__('Employer', 'workreap'),
						'both' => esc_html__('Both', 'workreap'),
					],
					'default' 		=> 'both',
				]
			);

			$this->add_control(
				'promotion_text',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Promotion Text', 'workreap'),
					'default' 	=> esc_html__('Explore best packages for', 'workreap'),
					'condition'  => array(
						'type' => 'both',
					),
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
						'size' => 3,
					),
					'tablet_default' => array(
						'size' => 1,
					),
					'mobile_default' => array(
						'size' => 1,
					),
					'selectors'      => array(
						'{{WRAPPER}} .wr-package-items' => 'display: grid; grid-template-columns:repeat({{SIZE}}, 1fr)',
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
						'{{WRAPPER}} .wr-package-items' => 'grid-gap:{{SIZE}}px;',
					)
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
            <div class="wr-pricing-wrapper wr-pricing-type-<?php echo esc_attr($settings['type']); ?>">
                <?php if($settings['type'] === 'both'){ ?>
					<div class="wr-pricing-toggle-wrapper">					
						<div class="wr-pricing-toggle">
							<?php if($settings['promotion_text']){ ?>
								<div class="wr-pricing-promotinal-wrapper">
									<span class="wr-pricing-promotion-text"><?php echo esc_html($settings['promotion_text']); ?></span>
									<svg xmlns="http://www.w3.org/2000/svg" width="36" height="20" viewBox="0 0 36 20" fill="none">
										<path opacity="0.4" d="M34.7392 5.52702C33.524 4.08733 32.2807 2.65198 31.0493 1.2004C30.0137 -0.00781184 28.0375 1.4628 29.0774 2.69913C29.7623 3.50064 30.4311 4.29025 31.116 5.09175C27.6236 3.72933 23.5937 3.64467 20.183 5.33626C18.6059 6.12631 17.0255 7.36316 15.8185 8.87365C15.152 8.65958 14.4824 8.51799 13.8538 8.45645C9.37273 7.96618 5.01548 9.96196 1.4286 12.4725C0.130578 13.3924 1.62932 15.3642 2.92734 14.4444C5.63913 12.515 8.8616 10.91 12.2618 10.8325C12.978 10.8085 13.7234 10.8809 14.4374 11.03C13.8319 12.3327 13.5173 13.7489 13.6658 15.1801C13.8544 16.9651 15.0393 18.5822 16.9207 18.7387C18.6408 18.8769 20.2785 17.7319 20.8364 16.1199C21.7609 13.4292 20.2643 11.2842 18.1284 9.94313C18.852 9.1262 19.6936 8.42067 20.6173 7.87524C23.4049 6.25085 26.737 6.19826 29.7144 7.20811C29.2558 7.5955 28.8015 8.01101 28.384 8.47845C27.298 9.65354 29.2586 11.1656 30.3327 10.0067C31.4186 8.83161 32.7134 7.98427 34.2246 7.42034C34.9461 7.15082 35.2651 6.13707 34.7392 5.52702ZM18.6248 14.4738C18.6618 15.2743 18.2216 16.2491 17.3043 16.2753C15.292 16.2973 16.2835 13.1068 16.8101 11.9459C16.9356 12.0129 17.0654 12.1081 17.1953 12.2033C17.9418 12.7505 18.5662 13.5326 18.6248 14.4738Z" fill="#585858" stroke="#F7F7F8"/>
									</svg>
								</div>
							<?php }?>
							<button class="wr-pricing-toggle-btn active" data-target-id="wr-freelance-packages"><?php echo esc_html__("Freelancer", 'workreap'); ?></button>
							<button class="wr-pricing-toggle-btn" data-target-id="wr-employer-packages"><?php echo esc_html__("Employer", 'workreap'); ?></button>
						</div>
					</div>
                <?php }?>
                <div class="wr-pricing-packages">
                    <?php if($settings['type'] === 'freelancer' || $settings['type'] === 'both' ){
	                    $args = array(
		                    'limit'     => -1,
		                    'status'    => 'publish',
		                    'type'      => 'packages',
		                    'orderby'   => 'date',
		                    'order'     => 'ASC',
	                    );
	                    $workreap_packages = wc_get_products( $args );
                        ?>
                        <div id="wr-freelance-packages" class="wr-freelance-packages wr-package-items<?php echo esc_attr( ($settings['type'] === 'freelancer' || $settings['type'] === 'both') ? ' active' : ''); ?>">
	                        <?php foreach($workreap_packages as $package){ ?>
                                <div class="wr-package-item">
			                        <?php do_action('workreap_package_details', $package ); ?>
                                </div>
	                        <?php } ?>
                        </div>
                    <?php } ?>
	                <?php if($settings['type'] === 'employer' || $settings['type'] === 'both' ){
		                $args = array(
			                'limit'     => -1,
			                'status'    => 'publish',
			                'type'      => 'employer_packages',
			                'orderby'   => 'date',
			                'order'     => 'ASC',
		                );
		                $workreap_packages = wc_get_products( $args );
		                ?>
                        <div id="wr-employer-packages" class="wr-employer-packages wr-package-items<?php echo esc_attr( ($settings['type'] === 'employer') ? ' active' : ''); ?>">
			                <?php foreach($workreap_packages as $package){ ?>
                                <div class="wr-package-item">
					                <?php do_action('workreap_package_details', $package ); ?>
                                </div>
			                <?php } ?>
                        </div>
	                <?php } ?>
                </div>
            </div>
		<?php }
	}
	Plugin::instance()->widgets_manager->register(new Workreap_Pricing);
}
