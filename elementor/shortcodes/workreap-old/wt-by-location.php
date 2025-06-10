<?php
/**
 * Shortcode
 *
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 * @author     Amentotech <theamentotech@gmail.com>
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists('Workreap_Location') ){
	class Workreap_Location extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_by_location';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Jobs by locations', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-google-maps';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      category of shortcode
		 */
		public function get_categories() {
			return [ 'workreap-ele' ];
		}

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {
			$countries  = workreap_get_countries();
			
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'section_heading',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Heading', 'workreap' ),
					'description'   => esc_html__( 'Add section heading. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'btn_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Button title', 'workreap' ),
					'description'   => esc_html__( 'Add button title. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'btn_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Button link', 'workreap' ),
					'description'   => esc_html__( 'Add button link. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'location',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label'			=> esc_html__('Location?', 'workreap'),
					'desc' 			=> esc_html__('Select location to display.', 'workreap'),
					'options'   	=> $countries,
					'multiple' 		=> true,
					'label_block' 	=> true,
				]
			);
			
			$this->add_control(
				'link_target',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' => esc_html__('Link Target', 'workreap'),
					'desc'			=> esc_html__('Do you want to search freelancers or jobs?', 'workreap'),
					'options' 		=> [
										'project_search_page' => esc_html__('Jobs', 'workreap'),
										'service_search_page' => esc_html__('Services', 'workreap'),
										'freelancers_search_page' => esc_html__('Freelancer', 'workreap'),
										],
					'default' 		=> 'project_search_page',
				]
			);
			
			$this->end_controls_section();
		}

		/**
		 * Render shortcode
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {
			$settings 				= $this->get_settings_for_display();
			$section_heading     	= !empty($settings['section_heading']) ? $settings['section_heading'] : '';
			$locations				= !empty($settings['location']) ? $settings['location'] : array();
			$view_title				= !empty( $settings['btn_title'] )  ? $settings['btn_title'] : '';
			$view_url				= !empty( $settings['btn_link'] )  ? $settings['btn_link'] : '';
			$link_target     		= !empty($settings['link_target']) ? $settings['link_target'] : 'project_search_page';
			$search_page			= '';
			if( function_exists('workreap_get_page_uri') ){
				$search_page     = workreap_get_page_uri($link_target);
			}
			
			$countries  = workreap_get_countries();

			?>
			<div class="wt-sc-by-location wt-haslayout">
				<?php if( !empty($locations) ) {?>
					<div class="wt-widgetskills">
						<div class="wt-fwidgettitle">
							<h3><?php echo esc_html($section_heading);?></h3>
						</div>
						<ul class="wt-fwidgetcontent">
							<?php foreach( $locations as $location ) { 
									if(!empty($countries[$location])){
									$query_arg['location'] 		= urlencode($location);
									$url                 		= add_query_arg( $query_arg, esc_url($search_page));?>
									<li><a href="<?php echo esc_url($url);?>"><?php echo esc_html($countries[$location]);?></a></li>
							<?php }}?>
							<?php if( !empty($view_title) ) {?>
								<li class="wt-viewmore"><a href="<?php echo esc_url($view_url);?>"><?php echo esc_html($view_title);?></a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</div>
		<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Location ); 
}