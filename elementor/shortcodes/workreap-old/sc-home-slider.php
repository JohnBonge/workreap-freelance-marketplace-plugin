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

if( !class_exists('Workreap_Home_Slider') ){
	class Workreap_Home_Slider extends Widget_Base {

		public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);
            wp_enqueue_style('venobox');
			wp_enqueue_style('owl.carousel');
			wp_enqueue_script('venobox');
            wp_enqueue_script('owl.carousel');
        }

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_slider_v1';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Search Banner v3', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-slider-album';
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
			$list_names	= array();
			if( function_exists('workreap_get_search_list') ){
				$list_names	= workreap_get_search_list('yes');
			}
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
					'label' 		=> esc_html__('Title', 'workreap'),
        			'description' 	=> esc_html__('Add title. leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'sub_title',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Sub Title', 'workreap'),
        			'description' 	=> esc_html__('Add sub title. leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label'     	=> esc_html__( 'Description', 'workreap' ),
					'rows' 			=> 10,
					'description'   => esc_html__( 'Add section description. Leave it empty to hide.', 'workreap' ),
				]
			);

			$this->add_control(
				'video_title',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Video title', 'workreap'),
					'rows' 			=> 10,
        			'description' 	=> esc_html__('Add video title. leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'video_url',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Video Url', 'workreap'),
        			'description' 	=> esc_html__('Add video url. leave it empty to hide.', 'workreap'),
				]
			);
			
			$this->add_control(
				'search',
				[
					'type'      	=> Controls_Manager::SELECT2,
					'label' 		=> esc_html__('Search options', 'workreap'),
        			'multiple' 		=> true,
					'options' 		=> $list_names,
					'default' 		=> array('project_search_page', 'freelancers_search_page'),
					'label_block' 	=> true,
				]
			);
			
			$this->add_control(
				'slider',
				[
					'label'  => esc_html__( 'Add slide', 'workreap' ),
					'type'   => Controls_Manager::REPEATER,
					'fields' => [
						[
							'name'  => 'title',
							'label' => esc_html__( 'Add Title', 'workreap' ),
							'type'  => Controls_Manager::TEXT,
						],
						
						[
							'name' 			=> 'image',
							'type'      	=> Controls_Manager::MEDIA,
							'label'     	=> esc_html__( 'Upload slide Image', 'workreap' ),
							'description'   => esc_html__( 'Upload image.', 'workreap' ),
							'default' => [
								'url' => \Elementor\Utils::get_placeholder_image_src(),
							],
						]
						,
					],
					'default' => [],
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
			$settings = $this->get_settings_for_display();

			$title				= !empty($settings['title']) ? $settings['title'] : '';
			$sub_title			= !empty($settings['sub_title']) ? $settings['sub_title'] : '';
			$description		= !empty($settings['description']) ? $settings['description'] : '';
			$video_title		= !empty($settings['video_title']) ? $settings['video_title'] : '';
			$video_url			= !empty($settings['video_url']) ? $settings['video_url'] : '';
			$text_color			= !empty($settings['text_color']) ? $settings['text_color'] : '';
			$searchs	    	= !empty($settings['search']) ? $settings['search'] : array();
			$defult_key			= !empty($searchs) ? reset($searchs) : '';
			$default_url			= '';
			if( function_exists('workreap_get_page_uri') ){
				$default_url			= !empty($defult_key) ? workreap_get_page_uri($defult_key) : '';
			}
			
			$list_names	= '';
			if( function_exists('workreap_get_search_list') ){
				$list_names	= workreap_get_search_list('yes');
			}
			
			$sliders			= !empty($settings['slider']) ? $settings['slider'] : '';
			$is_clone	= 'false';
			if(!empty($sliders) && count($sliders) > 1){
				$is_clone	= 'true';
			}
			
			$flag 				= rand(9999, 999999);
			?>
			<div class="wt-sc-slider wt-haslayout wt-bannerholdervtwo dynamic-secton-<?php echo esc_attr( $flag );?>">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-12 col-lg-8">
							<div class="wt-bannercontent">
								<?php if( !empty( $title ) || !empty( $sub_title ) || !empty( $description ) ) {?>
									<div class="wt-bannerhead">
										<?php if( !empty( $title ) || !empty( $sub_title ) ) {?>
											<div class="wt-title">
												<h1><?php if( !empty( $title )){ ?><span><?php echo esc_html( $title );?></span><?php } ?> <?php echo esc_html( $sub_title ); ?></h1>
											</div>
										<?php }?>
										<?php if( !empty( $description ) ) {?>
											<div class="wt-description"><?php echo do_shortcode( $description );?></div>
										<?php } ?>
									</div>
								<?php } ?>
								<?php if( !empty($searchs) ) {?>
									<form class="wt-formtheme wt-formbanner" action="<?php echo esc_url($default_url);?>" method="get">
										<fieldset>
											<div class="form-group">
												<input type="text" name="keyword" class="form-control" placeholder="<?php esc_attr_e('I’m looking for','workreap');?>">
												<div class="wt-formoptions">
													<?php if( !empty($list_names[$defult_key]) ) { ?>
														<div class="wt-dropdown">
															<span><em class="selected-search-type"><?php echo esc_html( $list_names[$defult_key] );?></em><i class="wr-icon-chevron-down"></i></span>
														</div>
													<?php } ?>
													<div class="wt-radioholder">
														<?php 
														  foreach( $searchs as $search ) {
															$action_url	= '';
															if( function_exists('workreap_get_page_uri') ){
																$action_url	= workreap_get_page_uri($search);
															}
															
															if( !empty($search) && $search === $defult_key) {
																$checked	= 'checked';
															} else {
																$checked	= '';
															}
															  
															$search_title	= !empty( $list_names[$search] ) ? $list_names[$search] : '';
															  
															$flag_key 	= rand(9999, 999999);
															?>
															<span class="wt-radio">
																<input id="wt-<?php echo esc_attr( $flag_key );?>" data-url="<?php echo esc_url($action_url);?>" data-title="<?php echo esc_attr( $search_title );?>" type="radio" name="searchtype" value="<?php echo esc_attr($search);?>" <?php echo esc_attr($checked);?>>
																<label for="wt-<?php echo esc_attr( $flag_key );?>"><?php echo esc_html( $search_title );?></label>
															</span>
														<?php } ?>
													</div>
													<button type="submit" class="wt-searchbtn"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</fieldset>
									</form>
								<?php } ?>

								<?php if( !empty($video_url) || !empty($video_title) ) {?>
									<div class="wt-videoholder">
										<div class="wt-videoshow">
											<a  class="venobox vbox-item" data-vbtype="video" data-autoplay="true" href="<?php echo esc_url($video_url); ?>"><i class="fa fa-play"></i></a>
										</div>
										<?php if( !empty($video_title) ) {?>
											<div class="wt-videocontent">
												<span><?php echo do_shortcode($video_title);?></span>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php if( !empty( $sliders ) ) {?>
					<div id="wt-bgworkslider-<?php echo intval( $flag );?>" class="wt-bgworkslider owl-carousel">
						<?php 
							foreach( $sliders as $slide ) {
								$image	= !empty( $slide['image']['url']) ? $slide['image']['url'] : '';
								$title	= !empty( $slide['title'] ) ? $slide['title'] : '';?>
								<div class="item">
									<div class="wt-coverphoto" style="background: url(<?php echo esc_url( $image );?>);"></div>
								</div>
						<?php }?>
					</div>
				<?php } ?>
			</div>
			
			<?php $script = '
					jQuery(document).ready(function () {
						jQuery("#wt-bgworkslider-'.esc_js($flag).'").owlCarousel({
							items: 				1,
							nav:				false,
							mouseDrag:			false,
							rtl: 				"'.workreap_splide_rtl_check().'",
							loop:				'.$is_clone.',
							dots: 				false,
							autoplay:			'.$is_clone.',
							autoplayTimeout:	5000,
							animateOut: 		"fadeOut",
							animateIn: 			"fadeIn",
							dotsClass: 			"jf-sliderdots",
						});
						jQuery(".venobox").venobox()
					});
			';
			wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
		}
	}

	Plugin::instance()->widgets_manager->register( new Workreap_Home_Slider ); 
	}