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

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('Workreap_how_it_work')) {
    class Workreap_how_it_work extends Widget_Base {
        /**
         *
         * @since    1.0.0
         * @access   static
         * @var      base
         */
        public function get_name()
        {
        	return 'workreap_how_it_work';
        }

        /**
         *
         * @since    1.0.0
         * @access   static
         * @var      title
         */
        public function get_title()
        {
        	return esc_html__('How it work', 'workreap');
        }

        /**
         *
         * @since    1.0.0
         * @access   public
         * @var      icon
         */
        public function get_icon()
        {
            return 'eicon-background';
        }

        /**
         *
         * @since    1.0.0
         * @access   public
         * @var      category of shortcode
         */
        public function get_categories()
        {
        	return ['workreap-ele'];
        }

        /**
         * Register category controls.
         * @since    1.0.0
         * @access   protected
         */
        protected function register_controls(){

        //Content
            $this->start_controls_section(
                'content_section',
                [
                    'label' => esc_html__('Content', 'workreap'),
                    'tab'   => Controls_Manager::TAB_CONTENT,
                ]
            );
            $this->add_control(
                'image',
                [
                    'type'        => Controls_Manager::MEDIA,
                    'label'       => esc_html__('Dispaly image', 'workreap'),
                    'description' => esc_html__('Add display image.', 'workreap'),
                ]
            );
            $this->add_control(
                'img_align',
                [
                    'type' => Controls_Manager::SELECT2,
                    'label' => esc_html__('Slect alignment', 'workreap'),
                    'default' => 'right',
                    'options' => [
                        'right' => esc_html__('Right', 'workreap'),
                        'left'  => esc_html__('Left', 'workreap'),
                    ],
                ]
            );
            $this->add_control(
                'tag_line',
                [
                    'type' => Controls_Manager::TEXT,
                    'label' => esc_html__('Add tag line', 'workreap'),
                    'description' => esc_html__('Add text. leave it empty to hide.', 'workreap'),
                ]
            );

            $this->add_control(
                'title',
                [
                    'type' => Controls_Manager::TEXT,
                    'label' => esc_html__('Add title', 'workreap'),
                    'description' => esc_html__('Add title. leave it empty to hide.', 'workreap'),
                ]
            );

            $this->add_control(
                'description',
                [
                    'type' => Controls_Manager::TEXTAREA,
                    'label' => esc_html__('Add description', 'workreap'),
                    'description' => esc_html__('Add description. leave it empty to hide.', 'workreap'),
                ]
              );
            $this->end_controls_section();

            $this->start_controls_section(
                'addtional_information',
                [
                    'label' => esc_html__('Addtional information', 'workreap'),
                    'tab'   => Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
				'type',
				[
                    'type'      	=> Controls_Manager::SELECT,
                    'label'     	=> esc_html__('Select type','workreap' ),
                    'description'   => esc_html__('Select type', 'workreap' ),
                    'default' 		=> 'list',
                    'options' 		=> [
                                            'list'      => esc_html__('List', 'workreap'),
                                            'rating'    => esc_html__('Rating', 'workreap'),
                                            'link'      => esc_html__('Link', 'workreap'),
                                        ],
				]
			);

            /*
            * Point add
            */

            $this->add_control(
                'points',
                [
                    'label'     => esc_html__('Add points', 'workreap'),
                    'type'      => Controls_Manager::REPEATER,
                    'fields'    => [
                        [
                            'name'        => 'point',
                            'type'        => Controls_Manager::TEXT,
                            'label'       => esc_html__('Point which will display ', 'workreap'),
                            'description' => esc_html__('Add points.', 'workreap'),
                            'label_block' => true,
                        ]
                    ],
                    'condition'		=> ['type'=> 'list']
                ]
            );

            /*
            * Link Desciption Text && link
            */

            $this->add_control(
                'description_link',
                [
                    'type'          => Controls_Manager::URL,
                    'label'         => esc_html__('Link', 'workreap'),
                    'placeholder'   => esc_html__('https://your-link.com', 'workreap'),
                    'description'   => esc_html__('Add description link. leave it empty to hide.', 'workreap'),
                    'show_external' => true,
                    'default'       => [
                                            'url' => '',
                                            'is_external' => true,
                                            'nofollow' => false,
                                        ],
                    'condition'		=> ['type'=> 'link']
                ]
              );
            $this->add_control(
                'description_link_text',
                [
                    'type'        => Controls_Manager::TEXT,
                    'label'       => esc_html__('Link ext', 'workreap'),
                    'description' => esc_html__('Add description text. leave it empty to hide.', 'workreap'),
                    'label_block' => true,
                    'condition'	=> ['type'=> 'link']
                ]
            );


            /*
            * start image area
            */

            $this->add_control(
                'no_stars',
                [
                    'type'        => Controls_Manager::TEXT,
                    'label'       => esc_html__('Number of stars', 'workreap'),
                    'description' => esc_html__('Add number.', 'workreap'),
                    'label_block' => true,
                    'condition'		=> ['type'=> 'rating']
                ]
            );
            $this->add_control(
                'icon',
                [
                    'type'        => Controls_Manager::MEDIA,
                    'label'       => esc_html__('Select icon', 'workreap'),
                    'description' => esc_html__('Add icon.(63x53)', 'workreap'),
                    'condition'		=> ['type'=> 'rating']
                ]
            );
            $this->end_controls_section();

        }
        protected function render()
        {
            $settings           = $this->get_settings_for_display();
            $image              = !empty($settings['image']['url']) ? $settings['image']['url'] : '';
            $img_align          = !empty($settings['img_align']) ? $settings['img_align'] : '';
            $tag_line           = !empty($settings['tag_line']) ? $settings['tag_line'] : '';
            $title              = !empty($settings['title']) ? $settings['title'] : '';
            $description        = !empty($settings['description']) ? $settings['description'] : '';
            $type               = !empty($settings['type']) ? $settings['type'] : '';
            $class_vib          = !empty($img_align) && $img_align == 'right' ? 'wr-reverse-direction' : '';

            ?>
            <div class="wr-howitworks-v1">
                <div class="wr-howitwork <?php echo esc_attr($class_vib);?>">
                    <div class="container">
						<div class="row align-items-center">
							<?php if (!empty($image)) { ?>
								<div class="col-xl-6">
									<div class="wr-itworksitem">
										<img class="wr-imgclipped" data-aos="fade-up" src="<?php echo esc_url($image) ?>"  alt="<?php esc_attr_e('How it work', 'workreap'); ?>">
									</div>
								</div>
							<?php } ?>
							<div class="col-xl-6">
								<div class="wr-main-title-holder" data-aos="fade-up-left">
									<?php if (!empty($tag_line) || !empty($title)) { ?>
										<div class="wr-maintitle">
											<?php if (!empty($tag_line)) { ?>
                                                <h5 class="wr-orange"><?php echo esc_html($tag_line); ?></h5>
											<?php } ?>
											<?php if (!empty($title)) { ?>
												<h2><?php echo esc_html($title); ?> </h2>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if (!empty($description)) { ?>
										<div class="wr-main-description">
											<p><?php echo esc_html($description); ?></p>
										</div>
									<?php } ?>
									<?php if ( !empty($type) && $type === 'list') { 
										$points     = !empty($settings['points']) ? $settings['points'] : array();
										if( !empty($points) ){ ?>
											<ul class="wr-mainlist">
												<?php foreach ($points as $point) {
													$opportunity_point = !empty($point['point']) ? $point['point'] : '';
													if( !empty($opportunity_point) ){?>
														<li><?php echo esc_html($opportunity_point); ?></li>
													<?php } ?>
												<?php } ?>
											</ul>
										<?php } ?>
									<?php } ?>
									<?php if ( !empty($type) && $type === 'link') {
										$target                 = !empty($settings['description_link']['is_external']) ? ' target="_blank"' : '';
										$nofollow               = !empty($settings['description_link']['nofollow']) ? ' rel="nofollow"' : '';
										$description_link       = !empty($settings['description_link']['url']) ? $settings['description_link']['url'] : '';
										$description_link_text  = !empty($settings['description_link_text']) ? $settings['description_link_text'] : '';
										$link_                  = '<a href="' . esc_url($description_link) . '"' . do_shortcode($target).do_shortcode($nofollow) . '><span class="wr-icon-link-2"></span>'. esc_html($description_link_text) .'</a>';
										if (!empty($description_link_text)) { ?>
											<div class="wr-mainlink">
												<?php if(!empty($description_link_text)) { ?>
													<?php echo do_shortcode($link_); ?>
												<?php } ?>
											</div>
										<?php } ?>
									<?php } ?>
									<?php if ( !empty($type) && $type === 'rating') {
										$star_icon  = !empty($settings['icon']['url']) ? $settings['icon']['url'] : '';
										$no_stars   = !empty($settings['no_stars']) ? $settings['no_stars'] : '';
										?>
										<?php if(!empty($no_stars) || !empty($star_icon)) {?>
											<div class="wr-starrate">
												<?php
													if (!empty($no_stars) && $no_stars > 0) {
														for ($i = 0; $i < $no_stars; $i++) { ?>
															<span class="fa fa-star"></span>
														<?php }
													} ?>
												<?php
												if (!empty($star_icon)) { ?>
													<img src="<?php echo esc_url($star_icon) ?>" alt="<?php esc_attr_e('Icon stars', 'workreap') ?> ">
												<?php } ?>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
               		</div>
                </div>
            </div>
        <?php 
        }
    }
    Plugin::instance()->widgets_manager->register(new Workreap_how_it_work);
}
