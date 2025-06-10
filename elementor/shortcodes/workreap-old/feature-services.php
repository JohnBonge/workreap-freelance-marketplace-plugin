<?php
    /**
     * Services sliders
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

    if (!class_exists('Workreap_Feature_Services_Slider')) {
        class Workreap_Feature_Services_Slider extends Widget_Base
        {
            public function __construct($data = [], $args = null) {
                parent::__construct($data, $args);
                wp_enqueue_style('swiper');
                wp_enqueue_script('swiper');
            }
            /**
             *
             * @since    1.0.0
             * @access   static
             * @var      base
             */
            public function get_name()
            {
                return 'wt_element_micro_services';
            }

            /**
            *
            * @since    1.0.0
            * @access   static
            * @var      title
            */
            public function get_title()
            {
                return esc_html__('Featured services slider', 'workreap');
            }

            /**
            *
            * @since    1.0.0
            * @access   public
            * @var      icon
            */
            public function get_icon()
            {
                return 'eicon-person';
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
            protected function register_controls()
            {
                $pages      = array();
                $categories = array();

                if( function_exists('workreap_elementor_get_taxonomies') ){
                    $categories = workreap_elementor_get_taxonomies('product', 'product_cat');
                }
                if( function_exists('workreap_elementor_get_taxonomies') ){
                    $pages  = workreap_elementor_get_posts(array('page'));
                }

                $categories = !empty($categories) ? $categories : array();
                $pages      = !empty($pages) ? $pages : array();

                //Content
                $this->start_controls_section(
                    'content_section',
                    [
                        'label' => esc_html__('Content', 'workreap'),
                        'tab'   => Controls_Manager::TAB_CONTENT,
                    ]
                );
    
                $this->add_control(
                    'title',
                    [
                        'type'        => Controls_Manager::TEXT,
                        'label'       => esc_html__('Title', 'workreap'),
                        'placeholder' => esc_html__('Type your title here', 'workreap'),
                        'description' => esc_html__('Add title. leave it empty to hide.', 'workreap'),
                        'label_block' => true,
                    ]
                );

                $this->add_control(
                    'separator',
                    [
                        'type'          => Controls_Manager::SWITCHER,
                        'label'         => esc_html__('Separator', 'workreap'),
                        'label_on'      => esc_html__( 'Show', 'workreap' ),
                        'label_off'     => esc_html__( 'Hide', 'workreap' ),
                        'return_value'  => 'yes',
                        'selectors' => [
                            '{{WRAPPER}} .wr-maintitle:after' => 'content: "";',
                        ],
                        'prefix_class' => 'wr-title-separator-',
                        'condition' => [
                            'title!' => ' ',
                        ],
                    ]
                );
    
                $this->add_control(
                    'sub_title',
                    [
                        'type'          => Controls_Manager::TEXTAREA,
                        'label'         => esc_html__('Sub title', 'workreap'),
                        'placeholder'   => esc_html__('Type your sub title here', 'workreap'),
                        'rows'          => 5,
                        'description'   => esc_html__('Add title. leave it empty to hide.', 'workreap'),
                    ]
                );

                $this->add_control(
                    'description',
                    [
                        'type'          => Controls_Manager::TEXTAREA,
                        'label'         => esc_html__('Description', 'workreap'),
                        'placeholder'   => esc_html__('Type your description here', 'workreap'),
                        'rows'          => 5,
                        'description'   => esc_html__('Add title. leave it empty to hide.', 'workreap'),
                    ]
                );
    
                $this->add_control(
                    'listing_type',
                    [
                        'type'      	=> Controls_Manager::SELECT,
                        'label' 		=> esc_html__('Show services by', 'workreap'),
                        'description' 	=> esc_html__('Select type to list services by categories or specific', 'workreap'),
                        'default' 		=> 'categories_random',
                        'options' 		=> [
                            '' 			=> esc_html__('Select services listing type', 'workreap'),
                            'rating' 	=> esc_html__('Order by rating', 'workreap'),
                            'random' 	=> esc_html__('Random from all categories', 'workreap'),
                            'recent' 	=> esc_html__('Recent from all categories', 'workreap'),
                            'categories_random' 	=> esc_html__('Random by categories', 'workreap'),
                            'categories_recent' 	=> esc_html__('Recent by categories', 'workreap'),
                            'ids' 	                => esc_html__('By IDs', 'workreap'),
                        ]
                    ]
                );
                
                $this->add_control(
                    'show_posts',
                    [
                        'label' => esc_html__( 'Number of posts', 'workreap' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'posts' ],
                        'condition'		=> ['listing_type!'=> 'ids'],
                        'range' => [
                            'posts' => [
                                'min'   => 1,
                                'max'   => 100,
                                'step'  => 1,
                            ]
                        ],
                        'default' => [
                            'unit' => 'posts',
                            'size' => 6,
                        ]
                    ]
                );
                $this->add_control(
                    'services',
                    [
                        'type'          => Controls_Manager::SELECT2,
                        'label'         => esc_html__('Categories?', 'workreap'),
                        'desc'          => esc_html__('Select categories to display.', 'workreap'),
                        'options'       => $categories,
                        'condition'		=> ['listing_type'=> ['categories_random','categories_recent']],
                        'multiple'      => true,
                        'label_block'   => true,
                    ]
                );
                $this->add_control(
                    'service_by',
                    [
                        'type'      	=> Controls_Manager::TEXTAREA,
                        'condition'		=> ['listing_type'=> 'ids'],
                        'label' 		=> esc_html__('Services by ID', 'workreap'),
                        'description' 	=> esc_html__('You can add comma separated ID\'s for the services to show specific services. Leave it empty to use above settings', 'workreap'),
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
            protected function render()
            {
                
                $settings     = $this->get_settings_for_display();
                $title        = !empty($settings['title']) ? $settings['title'] : '';
                $sub_title    = !empty($settings['sub_title']) ? $settings['sub_title'] : '';
                $description  = !empty($settings['description']) ? $settings['description'] : '';
                $show_posts   = !empty($settings['show_posts']['size']) ? $settings['show_posts']['size'] : -1;
                $listing_type = !empty($settings['listing_type']) ? $settings['listing_type'] : '';
                $service_by   = !empty($settings['service_by']) ? $settings['service_by'] : '';
                $categories   = !empty($settings['services']) ? $settings['services'] : '';
                
                $rand_team               = rand(99, 9999);
                $tax_queries             = array();
                $product_cat_tax_args    = array();
                
                if (class_exists('WooCommerce')) {
                    if(!empty($categories ) && empty($service_by) && ( $listing_type == 'categories_random' || $listing_type == 'categories_recent' ) ){
                        $query_relation = array('relation' => 'AND',);
                        $product_cat_tax_args[] = array(
                            'taxonomy'  => 'product_cat',
                            'terms'     => $categories,
                            'field'     => 'term_id',
                            'operator'  => 'IN',
                        );
                    
                        // append product_cat taxonomy args in $tax_queries array
                        $tax_queries = array_merge($query_relation, $product_cat_tax_args);
                    }

//                    $product_type_tax_args[] = array(
//                        'taxonomy' => 'product_visibility',
//                        'field'    => 'name',
//                        'terms'    => 'featured',
//                        'operator' => 'IN',
//                    );
//                    $tax_queries = array_merge($tax_queries,$product_type_tax_args);

                    $product_type_tax_args[] = array(
                        'taxonomy' => 'product_type',
                        'field'    => 'slug',
                        'terms'    => 'tasks',
                    );
                    $tax_queries = array_merge($tax_queries,$product_type_tax_args);
                    
                    // prepared query args
                    $workreap_args = array(
                        'post_type'         => 'product',
                        'post_status'       => 'publish',
                        'tax_query'         => $tax_queries
                    );

                    //order by
                    if(!empty($listing_type) && ( $listing_type == 'random' ||  $listing_type == 'categories_random' )){
                        $workreap_args['orderby'] = 'rand';
                    }

                    if(!empty($listing_type) && ( $listing_type == 'recent' ||  $listing_type == 'categories_recent' )){
                        $workreap_args['orderby']    = 'ID';
                        $workreap_args['order']      = 'DESC';
                    }
                    if(!empty($listing_type) && ( $listing_type === 'rating' )){
                        $workreap_args['orderby']    = 'meta_value';
                        $workreap_args['order']      = 'DESC';
                        $workreap_args['meta_key']   = '_wc_average_rating';
                    }

                    //specific posts
                    if(!empty($service_by)){
                        $workreap_args['post__in'] = !empty($service_by) ? explode(',',$service_by) : '';
                    }

                    $workreap_query  = new \WP_Query(apply_filters('workreap_service_listings_args', $workreap_args));
                    $result_count   = $workreap_query->found_posts;

                    ?>
                    <div class="wr-main-section-two">
                        <div class="container">
                            <div class="row justify-content-center">
                                <?php if( !empty($title) || !empty($sub_title) || !empty($description) ){?>
                                    <div class="col-lg-10 col-xl-8">
                                        <div class="wr-main-title-holder text-center">
                                            <?php if(!empty($title) || !empty($sub_title) ){?>
                                                <div class="wr-maintitle">
                                                    <?php do_action( 'workreap_section_shaper_html' );?>
	                                                <?php if( !empty($sub_title) ){?>
                                                        <h3><?php echo esc_html($sub_title)?></h3>
	                                                <?php } ?>
                                                    <?php if( !empty($title) ){?>
                                                        <h2><?php echo esc_html($title)?></h2>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php if(!empty($description)){?>
                                                <div class="wr-main-description">
                                                    <p><?php echo esc_html($description)?></p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if( !empty($workreap_query->have_posts()) ){?>
                                    <div class="col-sm-12">
                                        <div id="wr-trendingserviceslider-<?php echo esc_attr($rand_team);?>" class="swiper wr-swiper wr-featureslider wr-swiperdots">
                                            <div class="swiper-wrapper">
                                            <?php
                                                $count = 0;
                                                while ( $workreap_query->have_posts() ) : $workreap_query->the_post();
                                                    global $post;
                                                    if($count >= $show_posts){
                                                        continue;
                                                    }
                                                    ?>
                                                    <div class="swiper-slide">
                                                        <?php do_action( 'workreap_listing_task_html_v2', $post->ID );?>
                                                    </div>
                                                 <?php
	                                                $count++;
                                                endwhile;
                                                wp_reset_postdata(); ?>
                                            </div>
                                            <div class="swiper-pagination"></div>
                                        </div>
                                    </div>
                                <?php }?>

                            </div>
                        </div>
                    </div>
                    <?php
                    $freelancer_script  = '
                    jQuery(document).ready(function () {
                        var wr_swiper = document.querySelector("#wr-trendingserviceslider-'.esc_js($rand_team).'")
                        if(wr_swiper !== null){
                        var swiper = new Swiper("#wr-trendingserviceslider-'.esc_js($rand_team).'", {
                            slidesPerView: 1,
                            spaceBetween: 24,
                            pagination: {
                                el: ".swiper-pagination",
                                clickable: true,
                            },
                            breakpoints: {
                            
                            480: {
                                slidesPerView: 1,
                                spaceBetween: 24
                            },
                            767: {
                                slidesPerView: 2,
                                spaceBetween: 24
                            },
                            991: {
                                slidesPerView: 3,
                                spaceBetween: 24
                            },
                            1199: {
                                slidesPerView: 3,
                                spaceBetween: 24
                            },
                        }
                        });
                        }
                    });
                    ';
                    wp_add_inline_script('swiper', $freelancer_script, 'after');
                }
            }
        }
        Plugin::instance()->widgets_manager->register(new Workreap_Feature_Services_Slider);
    }
