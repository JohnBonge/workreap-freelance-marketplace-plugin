<?php
/**
 * Shortcode for the Top Freelancers V2
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

if( !class_exists('Workreap_Top_Freelancers_V2') ){
	class Workreap_Top_Freelancers_V2 extends Widget_Base {

		public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);
			wp_enqueue_style('owl.carousel');
            wp_enqueue_script('owl.carousel');
        }

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'wt_element_top_freelancers_v2';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Top Freelancers V2', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-person';
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
					'label' 		=> esc_html__('Title', 'workreap'),
        			'description' 	=> esc_html__('Add title. leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'description',
				[
					'type'      	=> Controls_Manager::WYSIWYG,
					'label' 		=> esc_html__('Description', 'workreap'),
        			'description' 	=> esc_html__('Add description. leave it empty to hide.', 'workreap'),
				]
			);
			
			

			$this->add_control(
				'freelancers',
				[
					'type'      	=> Controls_Manager::TEXTAREA,
					'label' 		=> esc_html__('Freelancers', 'workreap'),
        			'description' 	=> esc_html__('Add top freelancer\'s with comma separated ID\'s e.g(12,20). Leave it empty to show freelancers by below settings', 'workreap'),
				]
			);
			
			$this->add_control(
				'listing_type',
				[
					'type'      	=> Controls_Manager::SELECT,
					'label' 		=> esc_html__('Show freelancer by', 'workreap'),
					'description' 	=> esc_html__('Select type to list freelancers by featured, verified, latest', 'workreap'),
					'default' 		=> '',
					'options' 		=> [
										'' 			=> esc_html__('Select freelancer listing type', 'workreap'),
										'featured' 	=> esc_html__('Featured', 'workreap'),
										'DESC' 		=> esc_html__('Recents', 'workreap'),
										'ASC' 		=> esc_html__('Former', 'workreap'),
										'rand' 		=> esc_html__('Random', 'workreap'),
										]
				]
			);

			$this->add_control(
				'listing_numbers',
				[
					'type'      	=> Controls_Manager::TEXT,
					'label' 		=> esc_html__('Number of freelancers', 'workreap'),
        			'description' 	=> esc_html__('Add no of freelancer that show on listing.If empty then 4 freelancers will be listed.', 'workreap'),
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
			global $workreap_settings,$current_user;
			$settings = $this->get_settings_for_display();

			$title       = !empty($settings['title']) ? $settings['title'] : '';
			$desc  	     = !empty($settings['description']) ? $settings['description'] : '';

			$listing_numbers  	 = !empty($settings['listing_numbers']) ? $settings['listing_numbers'] : intval(4);
			$listing_type  	     = !empty($settings['listing_type']) ? $settings['listing_type'] : '';
			$freelancers_ids  	 = !empty($settings['freelancers']) ? explode(',',$settings['freelancers']) : array();
			
			$freelancer_avatar_search              = !empty($workreap_settings['hide_freelancer_without_avatar']) ? $workreap_settings['hide_freelancer_without_avatar'] : 'no';
						
			$args = array(
				'post_type'		=> 'freelancers',
				'post_status'   => 'publish',
			);

			$args['posts_per_page']	= $listing_numbers;

			$meta_query			= array();
			$meta_query[]		= array(
										'key'   	=> '_is_verified',
										'compare' 	=> '=',
										'value' 	=> 'yes');
			
			if(!empty($freelancer_avatar_search) && $freelancer_avatar_search === 'yes'){
				$meta_query[]		= array(
										'key'   	=> '_have_avatar',
										'value' 	=> 1,
										'compare' 	=> '='
									);
			}
			
			$loop = 'true';
			if( !empty( $freelancers_ids ) ){
				$args['post__in']	= $freelancers_ids;
				$loop = 'false';
			} else if( !empty($listing_type) ) {
				if( $listing_type === 'featured' ){
					$meta_query[]		= array(
						'key'   => '_featured_timestamp',
						'value' => 1);
				} else if( $listing_type === 'DESC' ){
					$args['order']			= 'DESC';
				} else if( $listing_type === 'ASC' ){
					$args['order']			= 'ASC';
				}
				
				if( $listing_type === 'rand' ){
					$args['orderby']			= 'rand';
				} else{
					$args['orderby']		= 'ID';
				}
			}
			
			$args['meta_query']		= $meta_query;

			$freelancers = get_posts($args);
			
			$flag	= rand(999,99999);
			$is_rtl = 'false';
			if( is_rtl() ){
				$is_rtl = 'true';
			}
			?>
			<div class="wt-sc-top-freelancers-v2 wt-haslayout">
				<div class="row justify-content-center">
					<?php if( !empty( $title ) || !empty( $desc ) ) {?>
						<div class="col-12 col-lg-8">
							<div class="wt-sectionheadvtwo wt-textcenter">
								<?php if( !empty( $title ) ) {?>
									<div class="wt-sectiontitlevtwo">
										<?php if( !empty( $title ) ) {?><h2><?php echo do_shortcode( $title );?></h2><?php }?>
									</div>
								<?php } ?>
								<?php if( !empty( $desc ) ) {?>
									<div class="wt-description"><?php echo wpautop(do_shortcode($desc));?></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<?php if( !empty( $freelancers ) ) {?>
					<div class="row">
						<div id="wt-freelancers-silder-<?php echo esc_attr( $flag );?>" class="wt-freelancers-silder owl-carousel">
							<?php 
								foreach( $freelancers as $freelancer ){
									$freelancer_id        = $freelancer->ID;
									$freelancer_name      = workreap_get_username($freelancer_id);
									$wr_post_meta     = get_post_meta($freelancer_id, 'wr_post_meta', true);
									?>
                                    <div class="wr-freelanlist">
                                        <div class="wr-topservicetask__content">
                                            <div class="wr-freeprostatus">
												<?php do_action('workreap_profile_image', $freelancer_id,'',array('width' => 600, 'height' => 600));?>
                                            </div>
                                            <div class="wr-title-wrapper">
                                                <div class="wr-author-info">
													<?php if( !empty($freelancer_name) ){?>
                                                        <a href="<?php echo esc_url( get_permalink()); ?>"><?php echo esc_html($freelancer_name); ?></a>
														<?php do_action( 'workreap_verification_tag_html', $freelancer_id ); ?>
													<?php } ?>
                                                    <ul class="wr-blogviewdates wr-blogviewdatesmd">
														<?php do_action('workreap_get_freelancer_rating_count', $freelancer_id); ?>
                                                    </ul>
                                                </div>
												<?php do_action('workreap_get_freelancer_views', $freelancer_id); ?>
                                            </div>
											<?php $wr_hourly_rate = get_post_meta($freelancer_id, 'wr_hourly_rate', true);
											if (!empty($wr_hourly_rate) || !empty($display_button)) { ?>
                                                <div class="wr-startingprice">
                                            <span class="wr-startingprice-title">
                                                <i class="wr-icon-credit-card" aria-hidden="true"></i>
                                                <?php echo esc_html__('Hourly Rate','workreap') ?>
                                            </span>
                                                    <span><?php echo sprintf(esc_html__('%s /hr', 'workreap'), workreap_price_format($wr_hourly_rate, 'return')); ?></span>
                                                </div>
											<?php }
											$address  = apply_filters( 'workreap_user_address', $freelancer_id );
											if( !empty($address) ){ ?>
                                                <div class="wr-address-view">
                                            <span class="wr-address-title">
                                                <i class="wr-icon-map-pin" aria-hidden="true"></i>
                                                <?php echo esc_html__('Location','workreap') ?>
                                            </span>
                                                    <span class="wr-address"><?php echo esc_html($address); ?></span>
                                                </div>
											<?php }
											do_action( 'workreap_term_tags', $freelancer_id, 'skills', '', 6 );
											?>
                                            <div class="wr-btnviewpro">
                                                <a href="<?php echo esc_url( get_permalink()); ?>" class="wr-btn-solid-lg"><?php esc_html_e('View profile','workreap');?></a>
												<?php do_action('workreap_save_freelancer_html', $current_user->ID, $freelancer_id, '_saved_freelancers', 'v2', 'freelancers'); ?>
                                            </div>
                                        </div>
                                    </div>
							<?php }?>
						</div>
					</div>
					<script>
						jQuery(document).on('ready',function () {
							var carousel_init = jQuery("#wt-freelancers-silder-<?php echo esc_attr( $flag );?>").owlCarousel({
								item: 5,
								rtl: <?php echo esc_attr($is_rtl);?>,
								loop:<?php echo esc_js($loop);?>,
								nav:false,
								margin: 30,
								autoplay:false,
								dots: true,
								dotsClass: 'wt-sliderdots',
								responsiveClass:true,
								responsive:{
									0:{items:1},
									680:{items:2},
									1081:{items:3},
									1440:{items:4},
									1760:{items:5}
								}
							});

							carousel_init.trigger('refresh.owl.carousel');
							setTimeout( function(){carousel_init.trigger('refresh.owl.carousel');}, 500);
						});
					</script>
				<?php } ?>
			</div>
		<?php 
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Workreap_Top_Freelancers_V2 ); 
}