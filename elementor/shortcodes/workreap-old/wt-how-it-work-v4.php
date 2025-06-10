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

if( !class_exists('Workreap_How_Works_V4') ){
	class Workreap_How_Works_V4 extends Widget_Base {
		public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);
			wp_enqueue_style('venobox');
			wp_enqueue_script('venobox');
        }

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_how_works_v4';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'How It works V3', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-click';
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
			
			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Title', 'workreap' ),
					'description'   => esc_html__( 'Add section title. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Sub title', 'workreap' ),
					'description'   => esc_html__( 'Add section sub title. Leave it empty to hide.', 'workreap' ),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap'),
        			'description' 	=> esc_html__('Add description. Leave it empty to hide description.', 'workreap'),
				]
			);
			
			$this->add_control(
				'image',
				[
					'type'      	=> Controls_Manager::MEDIA,
					'default' => [
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					],
					'label'     	=> esc_html__( 'Add video Image', 'workreap' ),
					'description'   => esc_html__( 'Add Video image. Leave it empty to hide.', 'workreap' ),
				]
			);
			
			$this->add_control(
				'video_link',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label'     	=> esc_html__( 'Sub Video link', 'workreap' ),
					'description'   => esc_html__( 'Add video link. Leave it empty to hide.', 'workreap' ),
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
			$settings 		= $this->get_settings_for_display();
			$image       	= !empty($settings['image']['url']) ? $settings['image']['url'] : '';
			$title       	= !empty($settings['title']) ? $settings['title'] : '';
			$sub_title   	= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$desc  	     	= !empty($settings['description']) ? $settings['description'] : '';
			$video_link    	= !empty($settings['video_link']) ? $settings['video_link'] : '';
			$flag 			= rand(9999, 999999);
			?>
			<div class="wt-sc-how-it-work wt-workholder dynamic-secton-<?php echo esc_attr( $flag );?>">
				<div class="container">
					<div class="row justify-content-md-center">
						<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $desc ) ) {?>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
								<div class="wt-sectionhead wt-textcenter wt-topservices-title">
									<?php if( !empty( $title ) || !empty( $sub_title ) ) {?>
										<div class="wt-sectiontitle">
											<?php if( !empty( $sub_title )) { ?><span><?php echo esc_html( $sub_title );?></span><?php } ?>
											<?php if( !empty( $title )) { ?><h2><?php echo esc_html( $title );?></h2><?php } ?>
										</div>
									<?php } ?>
									<?php if( !empty( $desc ) ){?>
										<div class="wt-description"><?php echo do_shortcode( $desc );?></div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						<?php if( !empty( $image ) ) {?>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 push-lg-1">
								<div class="wt-workvideo-holder">
									<figure class="wt-workvideo-img">
										<?php if(!empty($video_link)){?>
											<a class="wt-venobox" data-autoplay="true" data-vbtype="video" href="<?php echo esc_url( $video_link );?>">
												<img src="<?php echo esc_url( $image );?>" alt="<?php esc_html_e('How it work','workreap');?>">
											</a>
										<?php }else{?>
											<img src="<?php echo esc_url( $image );?>" alt="<?php esc_html_e('','workreap');?>">
										<?php }?>
									</figure>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php
		$script	= "jQuery('.wt-venobox').venobox();";
		wp_add_inline_script( 'venobox', $script, 'after' );

		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_How_Works_V4 ); 
}