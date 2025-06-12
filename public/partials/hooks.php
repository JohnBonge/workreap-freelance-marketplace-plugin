<?php

/**
 * Provide a public-facing hooks
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/public/partials
 */
if (!class_exists('Workreap_Template_Functions')) {
    class Workreap_Template_Functions
    {
        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string    $workreap      The name of the plugin.
         * @param      string    $version    The version of this plugin.
         */


        public function __construct()
        {
            add_action('workreap_product_ads_content', array($this, 'workreap_product_ads_content'));
            add_action('workreap_saved_item', array($this, 'workreap_saved_item'), 10, 4);
            add_action('workreap_saved_item_theme', array($this, 'workreap_saved_item_theme'), 10, 3);
            add_action('workreap_package_details', array($this, 'workreap_package_details'), 10, 2);
            add_action('workreap_profile_image', array($this, 'workreap_profile_image'), 10, 3);
            add_action('workreap_profile_image_theme', array($this, 'workreap_profile_image_theme'), 10, 1);
            add_action('workreap_service_item_reviews', array($this, 'workreap_service_item_reviews'));
            add_action('workreap_service_item_status', array($this, 'workreap_service_item_status'));
            add_action('workreap_service_item_queue', array($this, 'workreap_service_item_queue'));
            add_action('workreap_service_item_completed', array($this, 'workreap_service_item_completed'));
            add_action('workreap_service_item_cancelled', array($this, 'workreap_service_item_cancelled'));
            add_action('workreap_user_hourly_starting_rate', array($this, 'workreap_user_hourly_starting_rate'), 10, 3);
            add_action('workreap_service_item_starting_price', array($this, 'workreap_service_item_starting_price'));
            add_action('workreap_service_item_starting_price_theme', array($this, 'workreap_service_item_starting_price_theme'));
            add_action('workreap_task_price_plans', array($this, 'workreap_task_price_plans'));
            add_action('workreap_task_additional_services', array($this, 'workreap_task_additional_services'));
            add_action('workreap_task_tags', array($this, 'workreap_task_tags'));
            add_action('workreap_task_rating', array($this, 'workreap_task_rating'));
            add_action('workreap_service_item_views', array($this, 'workreap_service_item_views'));
            add_action('workreap_service_item_views_theme', array($this, 'workreap_service_item_views_theme'));
            add_action('workreap_service_download', array($this, 'workreap_service_download'));
            add_action('workreap_service_ratings', array($this, 'workreap_service_ratings'));
            add_action('workreap_service_delivery_time', array($this, 'workreap_service_delivery_time'), 5, 2);
            add_action('workreap_service_sales', array($this, 'workreap_service_sales'), 5, 2);
            add_action('workreap_post_views', array($this, 'workreap_post_views'), 5, 2);
            add_action('workreap_term_tags', array($this, 'workreap_term_tags'), 5, 5);
            add_action('workreap_service_rating_count', array($this, 'workreap_service_rating_count'));
            add_action('workreap_service_rating_count_theme', array($this, 'workreap_service_rating_count_theme'));
            add_action('workreap_service_rating_count_theme_v2', array($this, 'workreap_service_rating_count_theme_v2'));
            add_action('workreap_service_featured_item', array($this, 'workreap_service_featured_item'));
            add_action('workreap_featured_item', array($this, 'workreap_featured_item'),10,2);
            add_action('workreap_service_featured_item_theme', array($this, 'workreap_service_featured_item_theme'));
            add_action('workreap_task_video_theme', array($this, 'workreap_task_video_theme'));
            add_action('workreap_service_gallery_count', array($this, 'workreap_service_gallery_count'));
            add_action('workreap_task_gallery_theme', array($this, 'workreap_task_gallery_theme'));
            add_action('workreap_task_gallery_theme_v2', array($this, 'workreap_task_gallery_theme_v2'),10,3);
            
        }

        /**
         * Ads display
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_product_ads_content() {
            global $workreap_settings;
            $ads_content = !empty($workreap_settings['ads_content']) ? $workreap_settings['ads_content'] : '';
            ob_start();
            if($ads_content){
                do_shortcode('<div class="wr-sidebarad">'.$ads_content.'</div>');
            }
            echo ob_get_clean();
        }

        /**
         * Freelancer package detail
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_package_details($package = '',$show_btn=true) {

            if ( !class_exists('WooCommerce') ||  empty($package)) {
                return;
            }
            global $workreap_settings;
            $enable_ai          = !empty($workreap_settings['enable_ai']) ? $workreap_settings['enable_ai'] : false;
            $enable_ai_packages = !empty($workreap_settings['enable_ai_packages']) ? $workreap_settings['enable_ai_packages'] : false;
            $package_id             = $package->get_id();
            $allow_ai    = get_post_meta( $package_id, 'allow_ai', true );

            $allowed_ai_class	= "fas fa-times wr-grey";
            if( !empty($allow_ai) && $allow_ai == 'yes'){
                $allowed_ai_class	= "fas fa-check wr-green";
            }
            if($package->get_type() == 'employer_packages'){

                $package_type    		= get_post_meta( $package_id, 'package_type', true );
                $type					= workreap_price_plans_duration($package_type);
                $package_duration    	= get_post_meta( $package_id, 'package_duration', true );
                $number_projects_allowed   = get_post_meta( $package_id, 'number_projects_allowed', true );
                $featured_projects_allowed = get_post_meta( $package_id, 'featured_projects_allowed', true );
                $featured_projects_duration    	= get_post_meta( $package_id, 'featured_projects_duration', true );
                $most_popular_project_package    	= get_post_meta( $package_id, 'most_popular_project_package', true );
                $featured_projects_duration = !empty( $featured_projects_duration ) ? intval( $featured_projects_duration ) : 0;
                $featured_projects_allowed = !empty( $featured_projects_allowed ) ? intval( $featured_projects_allowed ) : 0;
                $number_projects_allowed = !empty( $number_projects_allowed ) ? intval( $number_projects_allowed ) : 0;

                $btn_label  = esc_html__('Get Started', 'workreap');
                $description = $package->get_description();

                if(empty($package->get_price())){
                    $btn_label  = esc_html__('Subscribe', 'workreap');
                }

                ob_start();
                ?>
                <div class="wr-pricing__content<?php echo esc_attr(isset($most_popular_project_package) && $most_popular_project_package === 'yes' ? ' wr_popular': ''); ?>">
                    <?php if(isset($most_popular_project_package) && $most_popular_project_package === 'yes'){ ?>
                        <span class="wr-most-popular-tag"><?php echo esc_html__('Most Popular','workreap'); ?></span>
                    <?php }?>
                    <?php echo get_the_post_thumbnail( $package_id, 'thumbnail' );?>
                    <h4><?php echo esc_html($package->get_name());?></h4>
                    <?php if(isset($description) && !empty($description)){ ?>
                        <div class="wr-package-description"><?php echo wp_kses_post($package->get_description()); ?></div>
                    <?php } ?>
                    <h2><?php workreap_price_format($package->get_price(),'',true);?></h2>
                    <em><?php esc_html_e('Incl all taxes', 'workreap'); ?></em>
                    <h6 class="wr-pricinglist-title"><?php echo esc_html__('It Includes','workreap'); ?></h6>
                    <ul class="wr-pricinglist">
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Package duration', 'workreap'); ?></span>
                                <span> <?php echo wp_sprintf( _n( '%1$s %2$s', '%1$s %3$s', $package_duration, 'workreap' ), $package_duration, $type, $type );?></span>
                            </div>
                        </li>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Number of projects', 'workreap'); ?></span>
                                <span><?php echo wp_sprintf( _n( '%1$s projects', '%1$s Projects', $number_projects_allowed, 'workreap' ), $number_projects_allowed );?></span>
                            </div>
                        </li>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Featured projects', 'workreap'); ?></span>
                                <span><?php echo wp_sprintf( _n( '%1$s Allowed', '%1$s Allowed', $featured_projects_allowed, 'workreap' ), $featured_projects_allowed );?></span>
                            </div>
                        </li>
                        <?php if(!empty($enable_ai) && !empty($enable_ai_packages) ){ ?>
                            <li>
                                <div class="wr-pricinglist__content">
                                    <span><?php esc_html_e('AI feature', 'workreap'); ?></span>
                                    <i class="<?php echo esc_attr($allowed_ai_class);?>"></i>
                                </div>
                            </li>
                        <?php } ?>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Featured projects duration', 'workreap'); ?></span>
                                <span><?php echo wp_sprintf( esc_html__( '%1$s day(s)', 'workreap' ), $featured_projects_duration );?></span>
                            </div>
                        </li>
                        <?php do_action('workreap_package_fields', $package);?>
                    </ul>

                    <?php if( !empty($show_btn) ){?>
                        <a href="javascript:void(0);" class="wr-btn wr-btnv2 wr-secondary-btn wr-buy-package" data-package_id="<?php echo intval($package_id);?>"><?php echo apply_filters('workreap_package_btn_label', $btn_label, $package_id); ?></a>
                    <?php } ?>
                </div>
                <?php
                echo ob_get_clean();

            } else {

                $package_type    		= get_post_meta( $package_id, 'package_type', true );
                $type					= workreap_price_plans_duration($package_type);
                $package_duration    	= get_post_meta( $package_id, 'package_duration', true );
                $number_tasks_allowed   = get_post_meta( $package_id, 'number_tasks_allowed', true );
                $featured_tasks_allowed = get_post_meta( $package_id, 'featured_tasks_allowed', true );
                $task_plans_allowed    	= get_post_meta( $package_id, 'task_plans_allowed', true );

	            $most_popular_task_package    	= get_post_meta( $package_id, 'most_popular_task_package', true );

                $number_project_credits     = get_post_meta( $package_id, 'number_project_credits', true );
                $featured_tasks_duration    = get_post_meta( $package_id, 'featured_tasks_duration', true );
                $featured_tasks_duration    = !empty($featured_tasks_duration) ? $featured_tasks_duration : 0;
                $number_project_credits     = !empty( $number_project_credits ) ? intval( $number_project_credits ) : 0;
                $allowed_plans_class        = "fas fa-times wr-grey";

                if( !empty($task_plans_allowed) && $task_plans_allowed == 'yes'){
                    $allowed_plans_class	= "fas fa-check wr-green";
                }


                $btn_label  = esc_html__('Get Started', 'workreap');
	            $description = $package->get_description();

                if(empty($package->get_price())){
                    $btn_label  = esc_html__('Subscribe', 'workreap');
                }

                ob_start();
                ?>
                <div class="wr-pricing__content<?php echo esc_attr(isset($most_popular_task_package) && $most_popular_task_package === 'yes' ? ' wr_popular': ''); ?>">
	                <?php if(isset($most_popular_task_package) && $most_popular_task_package === 'yes'){ ?>
                        <span class="wr-most-popular-tag"><?php echo esc_html__('Most Popular','workreap'); ?></span>
	                <?php }?>
                    <?php echo get_the_post_thumbnail( $package_id, 'thumbnail' );?>
                    <h4><?php echo esc_html($package->get_name());?></h4>
	                <?php if(isset($description) && !empty($description)){ ?>
                        <div class="wr-package-description"><?php echo wp_kses_post($package->get_description()); ?></div>
	                <?php } ?>
                    <h2><?php workreap_price_format($package->get_price(),'',true);?></h2>
                    <em><?php esc_html_e('Incl all taxes', 'workreap'); ?></em>
                    <h6 class="wr-pricinglist-title"><?php echo esc_html__('It Includes','workreap'); ?></h6>
                    <ul class="wr-pricinglist">
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Package duration', 'workreap'); ?></span>
                                <span> <?php echo wp_sprintf( _n( '%1$s %2$s', '%1$s %3$s', $package_duration, 'workreap' ), $package_duration, $type, $type );?></span>
                            </div>
                        </li>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Number of task to post', 'workreap'); ?></span>
                                <span><?php echo wp_sprintf( _n( '%1$s task', '%1$s tasks', $number_tasks_allowed, 'workreap' ), $number_tasks_allowed );?></span>
                            </div>
                        </li>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Featured task', 'workreap'); ?></span>
                                <span><?php echo wp_sprintf( _n( '%1$s Allowed', '%1$s Allowed', $featured_tasks_allowed, 'workreap' ), $featured_tasks_allowed );?></span>
                            </div>
                        </li>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Task plans allowed', 'workreap'); ?></span>
                                <i class="<?php echo esc_attr($allowed_plans_class);?>"></i>
                            </div>
                        </li>
                        <?php if(!empty($enable_ai) && !empty($enable_ai_packages) ){ ?>
                            <li>
                                <div class="wr-pricinglist__content">
                                    <span><?php esc_html_e('AI feature', 'workreap'); ?></span>
                                    <i class="<?php echo esc_attr($allowed_ai_class);?>"></i>
                                </div>
                            </li>
                        <?php } ?>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Credits to apply on projects', 'workreap'); ?></span>
                                <span><?php echo wp_sprintf( esc_html__( '%1$s credits', 'workreap' ), $number_project_credits );?></span>
                            </div>
                        </li>
                        <li>
                            <div class="wr-pricinglist__content">
                                <span><?php esc_html_e('Featured task duration', 'workreap'); ?></span>
                                <span><?php echo wp_sprintf( _n( '%1$s day', '%1$s days', $featured_tasks_duration, 'workreap' ), $featured_tasks_duration );?></span>
                            </div>
                        </li>
                        <?php do_action('workreap_package_fields', $package);?>
                    </ul>

                    <?php if( !empty($show_btn) ){?>
                        <a href="javascript:void(0);" class="wr-btn wr-btnv2 wr-secondary-btn wr-buy-package" data-package_id="<?php echo intval($package_id);?>"><?php echo apply_filters('workreap_package_btn_label', $btn_label, $package_id); ?></a>
                    <?php } ?>
                </div>
                <?php
                echo ob_get_clean();
            }

        }

        /**
         * Post views
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_term_tags($post_id = '', $taxonomy='', $heading='', $show_tags = 7,$type='service')
        {
            $categories             = get_the_terms($post_id, $taxonomy);
            $categories             = !empty($categories) ? $categories : array();
            $search_page_link       = '';
            $array_val              = '';
            if( !empty($type) && $type === 'service'){
                $search_page_link    = workreap_get_page_uri('search_page_link');
            } else if( !empty($type) && $type === 'project'){
                $search_page_link    = workreap_get_page_uri('project_search_page');
                $array_val              = '[]';
            }

            if (!empty($categories)) { ?>
            <div class="wr-singleservice-tile">
                <div class="wr-blogtags">
                    <?php if (!empty($heading)) { ?>
                        <div class="wr-tagtittle">
                            <i class="wr-icon-tag"></i>
                            <span>
                                <?php echo esc_html($heading.":"); ?>
                            </span>
                        </div>
                    <?php } ?>
                    <ul class="wr-tags_links">
                        <?php
                        $counter    = 0;
                        foreach ($categories as $category) {
                            $counter++;
                            $class  = '';

                            if ($counter > $show_tags) {
                                $class  = 'class="d-none"';
                            }

                            if (!empty($category->name)) {
                                $task_tag_search_url    = '#';

                                if(is_singular('freelancers') || $type === 'freelancer'){
                                    $task_tag_search_url    = workreap_get_page_uri('freelancers_search_page');
                                    if(!empty($task_tag_search_url)) {
                                        $task_tag_search_url = add_query_arg('skills[]', esc_attr($category->slug), $task_tag_search_url);
                                    }
                                }else{
                                    if(!empty($search_page_link)) {
                                        $task_tag_search_url = !empty($category->slug) ? add_query_arg($taxonomy.$array_val, esc_attr($category->slug), $search_page_link) : '';
                                    }
                                }
                                ?>
                                <li <?php echo do_shortcode($class); ?>>
                                    <a href="<?php echo esc_url($task_tag_search_url);?>"><span class="wr-blog-tags"><?php echo esc_html($category->name); ?></span></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($counter > $show_tags) { ?>
                            <li>
                                <div class="wr-selected__showmore">
                                    <a href="javascript:void(0);">+<?php echo esc_html($counter - $show_tags); ?><?php esc_html_e(' more', 'workreap'); ?></a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php
            }
        }

        /**
         * Post views
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_profile_image($post_id = '',$show_rates='',$size=array('width' => 600, 'height' => 600))
        {
            $user_name      = workreap_get_username($post_id);
            $user_name      = !empty($user_name) ? $user_name : '';
            $user_id        = get_post_meta($post_id, '_linked_profile', true);
            $avatar         = apply_filters(
            'workreap_avatar_fallback',
            workreap_get_user_avatar($size, $post_id),$size);
            if (!empty($avatar)) {
                ob_start();?>
                <div class="wr-asideprostatus">
                    <figure>
                        <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($user_name); ?>">
                        <?php do_action('workreap_print_user_status', $user_id);?>
                    </figure>
                    <?php if( !empty($show_rates) ){?>
                        <div class="wr-freelancer-details">
                            <?php if( !empty($user_name) ){?>
                                <h4>
                                    <a href="<?php echo esc_url(get_the_permalink($post_id));?>"><?php echo esc_html($user_name);?></a>
                                    <?php do_action( 'workreap_verification_tag_html', $post_id ); ?>
                                </h4>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php
                echo ob_get_clean();
            }
        }

    /**
     * Post views
     *
     * @throws error
     * @author Amentotech <theamentotech@gmail.com>
     * @return
     */
        public function workreap_profile_image_theme($post_id = '')
        {
            $user_name = workreap_get_username($post_id);
            $user_name = !empty($user_name) ? $user_name : '';
            $avatar = apply_filters(
                'workreap_avatar_fallback',
                workreap_get_user_avatar(array('width' => 80, 'height' => 80), $post_id),
                array('width' => 80, 'height' => 80)
            );
            if (!empty($avatar)) {
                ob_start();
                ?>
                <figure>
                    <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($user_name); ?>">
                </figure>
                <?php
                echo ob_get_clean();
            }
        }

        /**
         * Post views
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_post_views($post_id = '', $key = 'set_blog_view')
        {
            if (!is_single())
                return;

            if (empty($post_id)) {
                global $post;
                $post_id = $post->ID;
            }

            if (!isset($_COOKIE[$key . $post_id])) {
                setcookie($key . $post_id, $key, time() + 3600);
                $view_key = $key;
                $count = get_post_meta($post_id, $view_key, true);

                if ($count == '') {
                    $count = 0;
                    delete_post_meta($post_id, $view_key);
                    add_post_meta($post_id, $view_key, '0');
                } else {
                    $count++;
                    update_post_meta($post_id, $view_key, $count);
                }
            }

        }

        /**
         * Favourite tasks
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_saved_item($post_id = '', $user_post_id='', $key='', $type = '')
        {
            global $current_user;
            if (empty($user_post_id)){
                $user_type      = apply_filters('workreap_get_user_type', $current_user->ID );
                $user_post_id   = workreap_get_linked_profile_id($current_user->ID,'',$user_type);
            }
            $post_type      = !empty($key) && $key === '_saved_tasks'? 'tasks' : 'projects';
            $saved_items     = get_post_meta($user_post_id, $key, true);
            $saved_class     = !empty($saved_items) && in_array($post_id, $saved_items) ? 'bg-redheart' : 'bg-heart';
            $action          = !empty($saved_items) && in_array($post_id, $saved_items) ? '' : 'saved';
            $text           = !empty($saved_items) && in_array($post_id, $saved_items) ? esc_html__('Saved', 'workreap') : esc_html__('Save', 'workreap');
            ob_start();
            if (!empty($type) && $type == 'list') { ?>
                <a href="javascript:void(0);" class="wr_saved_items <?php echo esc_attr($saved_class); ?>" data-action="<?php echo esc_attr($action); ?>" data-post_id="<?php echo intval($post_id); ?>" data-id="<?php echo intval($current_user->ID); ?>" data-type="<?php echo esc_attr($post_type);?>"><span class="wr-icon-heart"></span></a>
            <?php } else { ?>
                <li> <a href="javascript:void(0);" class="wr_saved_items <?php echo esc_attr($saved_class); ?>" data-action="<?php echo esc_attr($action); ?>" data-post_id="<?php echo intval($post_id); ?>" data-id="<?php echo intval($current_user->ID); ?>" data-type="<?php echo esc_attr($post_type);?>"><span class="wr-icon-heart"></span><?php echo esc_html($text);?> </a> </li>
            <?php
            }
            echo ob_get_clean();
        }

        /**
         * Favourite tasks
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_saved_item_theme($post_id = '', $user_post_id = '', $key = '')
        {
            global $current_user;
            if (empty($user_post_id)) {
                $user_type = apply_filters('workreap_get_user_type', $current_user->ID);
                $user_post_id = workreap_get_linked_profile_id($current_user->ID, '', $user_type);
            }
            $saved_items = get_post_meta($user_post_id, $key, true);
            $saved_class = !empty($saved_items) && in_array($post_id, $saved_items) ? 'bg-redheart' : 'bg-heart';
            $action = !empty($saved_items) && in_array($post_id, $saved_items) ? '' : 'saved';
            ob_start();
            ?>
            <div class="wr-like">
                <a href="javascript:void(0);" class="wr_saved_items <?php echo esc_attr($saved_class); ?>"
                data-action="<?php echo esc_attr($action); ?>" data-post_id="<?php echo intval($post_id); ?>"
                data-id="<?php echo intval($current_user->ID); ?>" data-type="tasks"><i class="wr-icon-heart"></i>
                </a>
            </div>
            <?php
            echo ob_get_clean();
        }

        /**
         * Featured tasks
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_featured_item($product)
        {
            if(empty($product)){return;}

            $workreap_featured = $product->get_featured();
            ob_start();
            if ($workreap_featured) {
            ?>
                <em class="wr-featuretag__shadow">
                    <span class="wr-featuretag"><i class="fa fa-bolt"></i><?php esc_html_e('Pro', 'workreap'); ?></span>
                </em>
            <?php
            }
            echo ob_get_clean();
        }

        /**
         * Featured item
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_featured_item($product,$type='featured_task')
        {
            if(empty($product)){return;}

            $workreap_featured = $product->get_featured();
            ob_start();
            if ($workreap_featured) {
            ?>
                <span class="wr-featureditem" <?php echo apply_filters('workreap_tooltip_attributes', $type);?>><i class="wr-icon-zap"></i></span>
            <?php
            }
            echo ob_get_clean();
        }

        /**
         * Featured tasks
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_featured_item_theme($product)
        {
            if(empty($product)){return;}

            $workreap_featured = $product->get_featured();
            ob_start();
            if ($workreap_featured) {
                ?>
                <span class="wr-featuretag"><i class="fa fa-bolt"></i><?php esc_html_e('Pro', 'workreap'); ?></span>
                <?php
            }
            echo ob_get_clean();
        }

        /**
         * Tasks video
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_task_video_theme($product)
        {
            if(empty($product)){return;}

            $video_url = get_post_meta($product->get_id(), '_product_video', true);
            $video_url = !empty($video_url) ? $video_url : '';
            $product_url = get_permalink($product->get_id());
            $url = parse_url($video_url);
            ob_start();
            if ($video_url) {

                $unique_id = 'venobox-' . $product->get_id();
                if (!empty($url) && ( $url['host'] == 'www.youtube.com' || $url['host'] == 'vimeo.com' )) {
                    ?>
                    <a class="venobox wr-servicesvideo <?php echo esc_attr($unique_id); ?>" data-vbtype="video"
                    data-gall="gall" href="<?php echo esc_url($video_url); ?>" data-autoplay="true"></a>
                    <?php
                } else {
                    ?>
                    <a class="venobox wr-servicesvideo <?php echo esc_attr($unique_id); ?>" data-vbtype="iframe"
                    data-gall="gall" href="<?php echo esc_url($video_url); ?>" data-autoplay="true"></a>
                    <?php
                }

                $script_video = 'jQuery(document).ready(function () {
            let venobox = document.querySelector(".venobox-' . esc_js($product->get_id()) . '");
                if (venobox !== null) {
                jQuery(".venobox-' . esc_js($product->get_id()) . '").venobox({
                    spinner : "cube-grid",
                });
                }
            })';
                wp_add_inline_script('venobox', $script_video, 'after');
            }
            echo ob_get_clean();
        }

        /**
         * Tasks gallery count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_gallery_count($product)
        {
            if(empty($product)){return;}

            $gallery_count = 0;
            $gallery_ids_arr = $product->get_gallery_image_ids();
            $video_url = get_post_meta($product->get_id(), '_product_video', true);
            $video_url = !empty($video_url) ? $video_url : '';

            if (!empty($gallery_ids_arr)) {
                $gallery_count = count($gallery_ids_arr);
            }
            if ($gallery_count > 1 && empty($video_url)) {
                ob_start();
                ?>
                    <span class="wr-noofslides"><i class="wr-icon-image"></i><?php echo esc_html($gallery_count); ?></span>
                <?php
                echo ob_get_clean();
            }

        }

        /**
         * Tasks gallery
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_task_gallery_theme($product)
        {
            if(empty($product)){return;}

            $gallery_ids      = $product->get_gallery_image_ids();
            $gallery_count    = !empty($gallery_ids) && is_array($gallery_ids) ? count($gallery_ids) : 0;
            $video_url        = get_post_meta($product->get_id(), '_product_video', true);
            $video_url        = !empty($video_url) ? $video_url : '';
            ob_start();
            if (!empty($gallery_count) && $gallery_count > 1 ){ ?>
                <div class="wr-tasksearch-slider owl-carousel wr-tasks-slider wr-cards__img">
                    <?php
                        foreach( $gallery_ids as $attachment_id ) {
                            $woocommerce_thumbnail  = wp_get_attachment_image_src( $attachment_id,'woocommerce_thumbnail' );
                            $post_title             = get_the_title($attachment_id);
                            $attachment_image_url   = !empty($woocommerce_thumbnail[0]) ? $woocommerce_thumbnail[0] : '';
                            if( !empty($attachment_image_url) ){ ?>
                                <div class="item">
                                    <?php do_action('workreap_task_video_theme', $product);?>
                                    <figure class="wr-cards__img">
                                        <img src="<?php echo esc_url($attachment_image_url); ?>" alt="<?php echo esc_attr($post_title) ?>" />
                                    </figure>
                            </div>
                            <?php } ?>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <figure class="wr-cards__img">
                    <?php
                        do_action('workreap_task_video_theme', $product);
                        echo woocommerce_get_product_thumbnail('workreap_task_shortcode_thumbnail');
                    ?>
                </figure>
            <?php
            }
            echo ob_get_clean();
        }

        /**
         * Tasks gallery v2
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_task_gallery_theme_v2($product,$thum_size='woocommerce_thumbnail',$full_size='workreap_task_shortcode_thumbnail')
        {
            if(empty($product)){return;}

            $gallery_ids      = $product->get_gallery_image_ids();
            $gallery_count    = !empty($gallery_ids) && is_array($gallery_ids) ? count($gallery_ids) : 0;
            if (!empty($gallery_count) && $gallery_count > 1 ){ ?>
                <figure>
                    <?php
                        $counter        = 0;
                        $thumbnail      = '';
                        $gallery        = '';
                        foreach( $gallery_ids as $attachment_id ) {
                            $counter++;

                            if($counter === 1){
                                $woocommerce_thumbnail  = wp_get_attachment_image_src( $attachment_id,$full_size );
                            } else {
                                $woocommerce_thumbnail  = wp_get_attachment_image_src( $attachment_id,$thum_size );
                            }

                            $post_title             = get_the_title($attachment_id);
                            $attachment_image_url   = !empty($woocommerce_thumbnail[0]) ? $woocommerce_thumbnail[0] : '';
                            if( !empty($attachment_image_url) && $counter === 1 ){
                                $thumbnail  .= '<img src="'.esc_url($attachment_image_url).'" alt="'.esc_attr($post_title) .'">';
                            } else{
                                $gallery    .= '<img src="'.esc_url($attachment_image_url).'" alt="'.esc_attr($post_title) .'">';
                            }
                     } ?>
                    <?php echo do_shortcode( $thumbnail );?>
                    <?php if(!empty($gallery)){?><figcaption><?php echo do_shortcode( $gallery );?></figcaption><?php }?>
                </figure>
            <?php } else { ?>
                <figure> <?php echo woocommerce_get_product_thumbnail($full_size);?></figure>
            <?php
            }
        }

        /**
         * Tasks rating count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_rating_count($product)
        {
            if(empty($product)){return;}

            $workreap_product_rating         = !empty($product) ? $product->get_average_rating() : 0;
            $workreap_product_rating_count   = !empty($product) ? $product->get_rating_count() : 0;
            ob_start();
            ?>
                <li><i class="fa fa-star wr-yellow"></i> <em> <?php echo esc_html($workreap_product_rating); ?> </em> <span>(<?php echo esc_html(number_format($workreap_product_rating_count)); ?>)</span></li>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks rating count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_rating_count_theme($product)
        {
            if(empty($product)){return;}

            $workreap_product_rating = !empty($product) ? $product->get_average_rating() : 0;
            $workreap_product_rating_count = !empty($product) ? $product->get_rating_count() : 0;
            ob_start();
            ?>
            <li>
                <i class="fa fa-star wr-yellow"></i>
                <em> <?php echo esc_html($workreap_product_rating); ?> </em>
                <span>(<?php echo esc_html(number_format($workreap_product_rating_count)); ?>)</span>
            </li>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks rating count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_rating_count_theme_v2($product)
        {
            if(empty($product)){return;}

            $rating     = $product->get_average_rating();
            $count		= $product->get_rating_count();
            $rating_avg 	= !empty($rating) && !empty($count) ? ($rating/5) * 100 : 0;
            $rating_avg     = !empty($rating_avg) ? 'style="width:'.$rating_avg.'%;"' : 'style="width:0%;"';
            ?>
           <div class="wr-featureRating wr-featureRatingv2">
                <span class="wr-featureRating__stars"><span <?php echo do_shortcode($rating_avg );?>></span></span>
                <h6><?php echo esc_attr($rating);?> <em><?php esc_html_e('/5.0','workreap');?></em></h6>
                <em><?php esc_html_e('User review','workreap');?></em>
            </div>
            <?php
        }

        /**
         * Tasks detail views
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_item_views($product)
        {
            if(empty($product)){return;}

            $product_id             = !empty($product) ? $product->get_id() : '';

            $workreap_service_views  = get_post_meta($product_id, 'workreap_service_views', TRUE);
            $workreap_service_views  = !empty($workreap_service_views) ? intval($workreap_service_views) : 0;
            ob_start();
            ?>
                <li>
                    <i class="wr-icon-eye"></i> <span><?php echo wp_sprintf( _n( '%s view', '%s views', $workreap_service_views, 'workreap' ), $workreap_service_views );?></span>
                </li>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks views
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_item_views_theme($product)
        {
            if(empty($product)){return;}

            $product_id = $product->get_id();
            $workreap_service_views = get_post_meta($product_id, 'workreap_service_views', TRUE);
            $workreap_service_views = !empty($workreap_service_views) ? sprintf("%02d", intval($workreap_service_views))  : 0;
            ob_start();
            ?>
                <li>
                    <span> <i class="wr-icon-eye"></i> <em><?php echo wp_sprintf( _n( '%s view', '%s views', $workreap_service_views, 'workreap' ), $workreap_service_views );?></em> </span>
                </li>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks reviews count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_item_reviews($product)
        {
            if(empty($product)){return;}

            if ($product->get_reviews_allowed()) {
                $units_sold = $product->get_total_sales();
                $units_sold = !empty($units_sold) ? sprintf("%02d", $units_sold) : 0;
                ob_start();
            ?>
                <li><i class="wr-icon-shopping-bag text-grey"></i><span><?php echo wp_sprintf( _n( '%s sale', '%s sales', $units_sold, 'workreap' ), $units_sold );?></span></li>
            <?php
                echo ob_get_clean();
            }
        }

        /**
         * Tasks reviews count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_item_status($product_id)
        {
            global $workreap_settings;
            $service_status = !empty($workreap_settings['service_status']) ? $workreap_settings['service_status'] : '';
            $task_status    = get_post_status( $product_id );
            $status_class   = !empty($task_status) ? 'class="wr_'.$task_status.'"'    : "";
            $label          = "";
            switch($task_status){
                case 'pending':
                  $label      = esc_html__('Pending', 'workreap');
                  break;
                case 'draft':
                  $label      = esc_html__('Drafted', 'workreap');
                  break;
                case 'rejected':
                    $reason         = get_post_meta( $product_id, '_rejection_reason', true );
                    $reason         = !empty($reason) ? $reason : '';
                    $label          = esc_html__('Rejected', 'workreap');
                    $status_class   = 'class="wr_'.esc_attr($task_status).' wr-rejected-reason" data-reason="'.esc_attr($reason).'"';
                    break;
                case 'publish':
                    if( !empty($service_status) && $service_status === 'pending'){
                        $label      = esc_html__('Approved', 'workreap');
                    } else {
                        $label      = esc_html__('Published', 'workreap');
                    }
                  break;
                default:
                  $label      = esc_html__('New', 'workreap');
            }

            if ($task_status) {
                ob_start();
            ?>
                <li <?php echo do_shortcode($status_class);?>><i class="wr-icon-clock"></i><span><?php echo esc_attr($label);?></span></li>
            <?php
                echo ob_get_clean();
            }
        }

        /**
         * Tasks queue count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_item_queue($product)
        {
            $product_id             = $product->get_id();
            $workreap_total_sales    = $product->get_total_sales();
            $meta_array = array(
                array(
                    'key' => 'task_product_id',
                    'value' => $product_id,
                    'compare' => '=',
                    'type' => 'NUMERIC'
                ),
                array(
                    'key' => '_task_status',
                    'value' => 'hired',
                    'compare' => '=',
                )
            );

            $workreap_order_queues = workreap_get_post_count_by_meta('shop_order', array('wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed'), $meta_array);
            $workreap_queued_order_percentage = 0;
            if ($workreap_total_sales > 0 && $workreap_order_queues > 0) {
                $workreap_queued_order_percentage = ($workreap_order_queues / $workreap_total_sales) * 100;
            }
            ob_start();
            ?>
            <li>
                <div class="wr-profiletime">
                    <span><?php esc_html_e('In Queue', 'workreap'); ?> (<?php echo esc_html(number_format($workreap_order_queues)); ?>)</span>
                    <div class="progress wr-profileprogress">
                        <div class="progress-bar" role="progressbar"
                            style="width: <?php echo intval($workreap_queued_order_percentage); ?>%;"
                            aria-valuenow="<?php echo intval($workreap_order_queues); ?>" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </li>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks completed count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
         */
        public function workreap_service_item_completed($product)
        {
            $product_id = $product->get_id();
            $workreap_total_sales = $product->get_total_sales();
            $meta_array = array(
                array(
                    'key' => 'task_product_id',
                    'value' => $product_id,
                    'compare' => '=',
                    'type' => 'NUMERIC'
                ),
                array(
                    'key' => '_task_status',
                    'value' => 'completed',
                    'compare' => '=',
                )
            );
            $workreap_order_completed = workreap_get_post_count_by_meta('shop_order', array('wc-completed'), $meta_array);
            $workreap_completed_order_percentage = 0;
            if ($workreap_total_sales > 0 && ($workreap_order_completed) > 0) {
                $workreap_completed_order_percentage = ($workreap_order_completed / $workreap_total_sales) * 100;
            }
            ob_start();
            ?>
            <li>
                <div class="wr-profiletime">
                    <span><?php echo _x('Completed', 'Title for service completed', 'workreap' ); ?> (<?php echo esc_html(number_format($workreap_order_completed)); ?>)</span>
                    <div class="progress wr-profileprogress">
                        <div class="progress-bar" role="progressbar"
                            style="width: <?php echo intval($workreap_completed_order_percentage); ?>%;"
                            aria-valuenow="<?php echo intval($workreap_order_completed); ?>" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </li>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks cancelled count
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_service_item_cancelled($product)
        {
            $product_id = $product->get_id();
            $workreap_total_sales = $product->get_total_sales();
            $meta_array = array(
                array(
                    'key' => 'task_product_id',
                    'value' => $product_id,
                    'compare' => '=',
                    'type' => 'NUMERIC'
                ),
                array(
                    'key' => '_task_status',
                    'value' => 'cancelled',
                    'compare' => '=',
                )
            );
            $workreap_order_cancelled = workreap_get_post_count_by_meta('shop_order', array('wc-cancelled', 'wc-refunded', 'wc-failed','wc-completed'), $meta_array);
            $workreap_cancelled_order_percentage = 0;
            if ($workreap_total_sales > 0 && ($workreap_order_cancelled) > 0) {
                $workreap_cancelled_order_percentage = ($workreap_order_cancelled / $workreap_total_sales) * 100;
            }
            ob_start();
            ?>
            <li>
                <div class="wr-profiletime">
                    <span><?php esc_html_e('Cancelled', 'workreap'); ?> (<?php echo esc_html(number_format($workreap_order_cancelled)); ?>)</span>
                    <div class="progress wr-profileprogress">
                        <div class="progress-bar" role="progressbar"
                            style="width: <?php echo intval($workreap_cancelled_order_percentage); ?>%;"
                            aria-valuenow="<?php echo intval($workreap_order_cancelled); ?>" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </li>
            <?php
            echo ob_get_clean();
        }

    /**
     * Freelancer hourly price starting from
     *
     * @throws error
     * @author Amentotech <theamentotech@gmail.com>
     * @return
     */
     
     // You can adjust the login status class based on whether the user is logged in
        
        public function workreap_user_hourly_starting_rate($freelancer_id = '', $wr_hourly_rate = 'wr_hourly_rate', $display_button = '')
{
    global $post, $current_user;

    $currentuser_id  = !empty($current_user->ID) ? intval($current_user->ID) : 0;

    // Fix post_user_id safely
    $post_user_id = !empty($freelancer_id) 
        ? intval($freelancer_id) 
        : (!empty($args['post_id']) ? intval($args['post_id']) : (!empty($post) ? $post->ID : 0));

    // Ensure $post_id and $post are defined
    $post_id = isset($post_id) ? $post_id : (!empty($post) ? $post->ID : 0);
    $post = isset($post) && is_object($post) ? $post : get_post($post_id);

    $post_author    = get_post_field('post_author', $post_user_id);
    $user_name      = workreap_get_username($post_user_id);
    $user_id        = get_post_meta($post_id, '_linked_profile', true);
    $user_type      = !empty($currentuser_id) ? apply_filters('workreap_get_user_type', $currentuser_id ) : '';
    $is_activeMeeting   = in_array('workreap-meetings/workreap-meetings.php', apply_filters('active_plugins', get_option('active_plugins'))) ? true : false;
    $login_user_class   = 'wr_btn_checkout';
    $wr_msgform         = 'data-type="task" data-url="'.get_the_permalink($post).'"';

    if (!empty($currentuser_id)) {
        $login_user_class = '';
        $wr_msgform = 'data-bs-toggle="modal" data-bs-target="#wr_msgform"';
    }

    $debug_data = [
        'User Type'         => $user_type,
        'Post Author'       => intval($post_author),
        'Current User ID'   => intval($currentuser_id),
        'Post User ID'      => intval($post_user_id),
        'User ID'           => intval($user_id),
        'User Name'         => $user_name,
        'is_activeMeeting'  => $is_activeMeeting,
        'wp-guppy'          => in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))),
        'wpguppy-lite'      => in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins'))),
        'Login User Class'  => $login_user_class,
        'wr_msgform'        => $wr_msgform,
    ];
        
        // var_dump($debug_data);


            if (!empty($freelancer_id)) {
                $wr_hourly_rate = get_post_meta($freelancer_id, 'wr_hourly_rate', true);
                if (!empty($wr_hourly_rate) || !empty($display_button)) {
                    ob_start();
                    ?>
                    <div class="wr-startingprice">
                        <i><?php esc_html_e('Starting from', 'workreap'); ?></i>
                        <em>
                            <span><?php echo sprintf(esc_html__('%s ', 'workreap'), workreap_price_format($wr_hourly_rate, 'return')); ?></span>
                        </em>
                        <?php if($display_button):?>
                            <!--<a class="wr-btn-solid-lg" href="<?php echo esc_url( get_permalink($freelancer_id)); ?>"><?php esc_html_e('View profile', 'workreap'); ?></a>-->
                            <a class="wr-btn-solid-lg" href="<?php echo esc_url( get_permalink($freelancer_id)); ?>"><?php esc_html_e('Contact Me', 'workreap'); ?></a>
                            
                            
                            
                        <?php endif; ?>
                    </div>
                    <div class="modal fade wr-startchat" id="wr_msgform_<?php echo $freelancer_id; ?>" role="dialog">
    <div class="modal-dialog wr-modaldialog" role="document">
        <div class="modal-content">
            <div class="wr-popuptitle">
                <h4 id="wr_ratingtitle"><?php echo sprintf(esc_html__('Send a message to “%s“','workreap'),$user_name);?></h4>
                <a href="javascript:void(0);" class="close"><i class="wr-icon-x" data-bs-dismiss="modal"></i></a>
            </div>
            <div class="modal-body" id="wr_startcaht_form">
                <div class="wr-startchat-field">
                    <textarea class="form-control" id="wr_message_<?php echo intval($freelancer_id); ?>" name="message" placeholder="<?php esc_attr_e('Type your message','workreap');?>"></textarea>
<a href="javascript:void(0);" data-post_id="<?php echo intval($post_user_id);?>" class="wr-btn wr_sentmsg_task"><?php esc_html_e('Send message','workreap');?></a>

                </div>
            </div>
        </div>
    </div>
</div>
             <script>
                jQuery(document).on('click', '.wr_sentmsg_task', function(e) {
    e.preventDefault();
    
    var message = jQuery(this).closest('.modal-body').find('textarea').val().trim();
    console.log("Message content:", message); // Debugging line

    if (message === '') {
        alert('Message is required');
        return false;
    }

    // Proceed with AJAX request
});


             </script>       

                    <?php
                    echo ob_get_clean();
                }
            }
        }

        /**
         * Tasks price starting from
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_service_item_starting_price($product)
        {
            $workreap_total_price = $product->get_price();
            if(!empty($workreap_total_price)){
                ob_start();
                ?>
                <div class="wr-startingprice">
                    <i><?php esc_html_e('Starting from:', 'workreap'); ?></i>
                    <span>
                        <?php
                            if(function_exists('wmc_revert_price')){
                                workreap_price_format(wmc_revert_price($workreap_total_price));
                            } else {
                                workreap_price_format($workreap_total_price);
                            }
                        ?>
                    </span>
                </div>
                <?php
                echo ob_get_clean();
            }
        }

        /**
         * Tasks price starting from
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_service_item_starting_price_theme($product)
        {
            $workreap_total_price = $product->get_price();
            ob_start();
            ?>
            <div class="wr-startingprice">
                <i><?php esc_html_e('Starting from', 'workreap'); ?></i>
                <span>
                    <?php
                        if( function_exists('wmc_revert_price') ){
                            workreap_price_format(wmc_revert_price($workreap_total_price));
                        } else {
                            workreap_price_format($workreap_total_price);
                        }?>
                    </span>
            </div>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks no of sales
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_service_sales($product, $version = 'v1')
        {
            if(!empty($product)){
                $product_id = $product->get_id();
                $meta_array = array(
                    array(
                        'key' => 'task_product_id',
                        'value' => $product_id,
                        'compare' => '=',
                        'type' => 'NUMERIC'
                    )
                );
                $sales = $product->get_total_sales();
            }
            $sales = !empty($sales) ? sprintf("%02d", intval($sales)) : 0;
            ob_start();
            if (!empty($version) && $version == 'v1') { ?>
                <li>
                    <span class="wr-pinkbox"><i class="wr-icon-shopping-cart"></i></span>
                    <div class="wr-sales__title">
                        <em><?php esc_html_e('No. of sales', 'workreap'); ?></em>
                        <h6><?php echo intval($sales); ?></h6>
                    </div>
                </li>
            <?php } else if (!empty($version) && $version == 'v2') { ?>
                <li>
                    <div class="wr-pkgresponse__content wr-purple">
                        <i class="wr-icon-shopping-cart"></i>
                        <h6><?php echo intval($sales); ?></h6>
                        <span><?php esc_html_e('No. of sales', 'workreap'); ?></span>
                    </div>
                </li>
            <?php }else if (!empty($version) && $version == 'v3') { ?>
                <li>
                    <div class="wr-pkgresponse__content wr-purple">
                        <i class="wr-icon-shopping-cart"></i>
                        <h6><?php echo intval($sales); ?> <?php esc_html_e('sales', 'workreap'); ?></h6>
                    </div>
                </li>
            <?php }
            echo ob_get_clean();
        }

        /**
         * Tasks delievery time
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_service_delivery_time($product, $version = 'v1')
        {
            $days   = 0;

            if(!empty($product)){
                $db_delivery_time   = get_post_meta( $product->get_id(), '_delivery_time',true );
                if( !empty($db_delivery_time) ){
                    $delivery_time = 'delivery_time_' . $db_delivery_time;
                    if (function_exists('get_field')) {
                        $days = get_field('days', $delivery_time);
                    }
                } else {
                    $delivery_terms     = wp_get_post_terms($product->get_id(), 'delivery_time', array('fields' => 'ids'));
                    $days = array();
                    foreach ($delivery_terms as $delivery_id) {
                        $delivery_time = 'delivery_time_' . $delivery_id;

                        if (function_exists('get_field')) {
                            $days[] = get_field('days', $delivery_time);
                        } else {
                            $days[] = 0;
                        }
                    }
                    $days = !empty($days) && is_array($days) ? min($days) : 0;
                }
            }

            ob_start();
            if (!empty($version) && $version == 'v1' && !empty($days)) { ?>
                <li>
                    <span class="wr-greenbox"><i class="fas fa-calendar-check"></i></span>
                    <div class="wr-sales__title">
                        <em><?php esc_html_e('Delivery time', 'workreap'); ?></em>
                        <h6><?php echo sprintf(_n( '%s Day', '%s Days', $days, 'workreap' ), $days); ?></h6>
                    </div>
                </li>
            <?php } elseif (!empty($version) && $version == 'v2' && !empty($days)) { ?>
                <li>
                    <div class="wr-pkgresponse__content wr-greenbox wr-change-timedays">
                        <i class="wr-icon-gift"></i>
                        <h6><?php echo sprintf(_n( '%s Day', '%s Days', $days, 'workreap' ), $days); ?></h6>
                        <span><?php esc_html_e('Delivery', 'workreap'); ?></span>
                    </div>
                </li>
            <?php }
            echo ob_get_clean();
        }

        /**
         * Tasks downloadable
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_service_download($product)
        {
            $download_able = esc_html__('No', 'workreap');

            if(!empty($product)){
                if ($product->is_downloadable('yes')) {
                    $download_able = esc_html__('Yes', 'workreap');
                }
            }
            ob_start();
            ?>
            <li>
                <span class="bg-lightorange"><i class="wr-icon-download-cloud"></i></span>
                <div class="wr-sales__title">
                    <em><?php esc_html_e('Downloadable', 'workreap'); ?></em>
                    <h6><?php echo esc_html($download_able); ?></h6>
                </div>
            </li>
            <?php
            echo ob_get_clean();
        }

        /**
         * Tasks user ratings
         *
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         * @return
        */
        public function workreap_service_ratings($product)
        {
            $rating = $product->get_average_rating();
            $count = $product->get_rating_count();
            $average_rating = !empty($rating) && !empty($count) ? ($rating / 5) * 100 : 0;
            ob_start();
            ?>
            <li>
                <div class="wr-pkgresponse__content wr-orange">
                    <i class="wr-icon-trending-up"></i>
                    <h6><?php echo intval($average_rating); ?>%</h6>
                    <span><?php esc_html_e('User rating', 'workreap'); ?></span>
                </div>
            </li>
            <?php
            echo ob_get_clean();
        }
    }
    new Workreap_Template_Functions();
}
?>

    
    <?php
    add_action('wp_ajax_send_message', 'handle_send_message');
    add_action('wp_ajax_nopriv_send_message', 'handle_send_message');

    function handle_send_message()
    {
        // Check if message is passed and it's not empty
        if (isset($_POST['message']) && !empty($_POST['message'])) {
            $message = sanitize_text_field($_POST['message']);
            $post_id = intval($_POST['post_id']);
            $post_author = intval($_POST['post_author']);

            // Get freelancer's email
            $freelancer_email = get_the_author_meta('user_email', $post_author);

            if ($freelancer_email) {
                // Send the email
                $subject = "Message from " . wp_get_current_user()->user_login;
                $body = $message;

                // Add headers to avoid possible email delivery issues
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $mail_sent = wp_mail($freelancer_email, $subject, $body, $headers);

                if ($mail_sent) {
                    wp_send_json_success(['message' => 'Message sent successfully']);
                } else {
                    wp_send_json_error(['message' => 'Failed to send message. Please try again.']);
                }
            } else {
                wp_send_json_error(['message' => 'Freelancer email not found.']);
            }
        } else {
            wp_send_json_error(['message' => 'Message cannot be empty.']);
        }
    }
    ?>
<?php

/**
 * Keyword search
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_keyword_search' ) ) {
    add_action( 'workreap_keyword_search', 'workreap_keyword_search', 10, 1);
    function workreap_keyword_search($search_keyword = '') {
	?>
        <div class="wr-aside-content">
            <div class="wr-inputiconbtn">
                <div class="wr-placeholderholder">
                    <input type="text" name="keyword" value="<?php echo esc_attr($search_keyword); ?>" class="form-control" placeholder="<?php esc_attr_e('Start your search','workreap');?>">
                </div>
                <a href="javascript:void(0);" class="wr-search-icon"><i class="wr-icon-search"></i></a>
            </div>
        </div>

    <?php
  }
}

/**
 * Price range
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_price_range_dropdown' ) ) {
    add_action( 'workreap_price_range_dropdown', 'workreap_price_range_dropdown');
    function workreap_price_range_dropdown() {
        $min_product_price = (isset($_GET['min_product_price']) && !empty($_GET['min_product_price']) ? $_GET['min_product_price'] : "");
        $max_product_price = (isset($_GET['max_product_price']) && !empty($_GET['max_product_price']) ? $_GET['max_product_price'] : "");
    ?>
        <div class="wr-aside-holder">
            <div class="wr-sidebartitle">
                <h5><?php esc_html_e('Price range','workreap');?></h5>
            </div>
            <div class="wr-sidebarcontent">
                <div class="wr-appendinput">
                    <input type="number" name="min_product_price" value="<?php echo intval($min_product_price)?>" class="form-control" placeholder="<?php esc_attr_e('Min price','workreap');?>">
                    <input type="number" name="max_product_price" value="<?php echo intval($max_product_price)?>" class="form-control" placeholder="<?php esc_attr_e('Max price','workreap');?>">
                </div>
            </div>
        </div>
	  <?php
  }
}


/**
 * Freelancer status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if ( ! function_exists( 'workreap_freelancer_status_filter' ) ) {
	add_action( 'workreap_freelancer_status_filter', 'workreap_freelancer_status_filter');
    function workreap_freelancer_status_filter() {
        $online_freelancer  = (isset($_GET['online_freelancer'])  && $_GET['online_freelancer']  == 'on' ? "checked" : "");
        $offline_freelancer = (isset($_GET['offline_freelancer']) && $_GET['offline_freelancer'] == 'on' ? "checked" : "");
        ?>
            <div class="wr-aside-holder">
                <div class="wr-sidebartitle">
                    <h5><i class="wr-icon-minus"></i> <?php esc_html_e('Freelancer type','workreap');?></h5>
                </div>
                <div class="wr-sidebarcontent">
                    <div class="wr-checkboxholder">
                        <div class="wr-checkbox">
                            <input name="online_freelancer" id="onlinefreelancer" type="checkbox" <?php echo esc_attr($online_freelancer) ?> >
                            <label for="onlinefreelancer"><span><?php esc_html_e('Online freelancer', 'workreap');?></span></label>
                        </div>
                        <div class="wr-checkbox">
                            <input name="offline_freelancer" id="offlinefreelancer" type="checkbox" <?php echo esc_attr($offline_freelancer) ?>>
                            <label for="offlinefreelancer"><span><?php esc_html_e('Offline freelancer', 'workreap');?></span></label>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
}

/**
 * Set notification data
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('workreap_set_notification_data')) {
    add_action('workreap_set_notification_data', 'workreap_set_notification_data', 10, 3);
    function workreap_set_notification_data($trigger_params='', $post_title = '', $type='')
    {
        global $current_user;
        $post_title = !empty($post_title) ? $post_title : esc_html__('Default notification', 'workreap');
        $notification_post = array(
            'post_title' => wp_strip_all_tags($post_title),
            'post_type' => $type,
            'post_status' => 'publish',
            'post_content' => '',
            'post_author' => $current_user->ID
        );
        $last_insert_id = wp_insert_post($notification_post);

        if (!empty($trigger_params)) {
            foreach ($trigger_params as $key => $param) {
                update_post_meta($last_insert_id, $key, $param);
            }
        }
    }
}

/**
 * Search and Clear Buttons
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_search_clear_button')) {
    add_action('workreap_search_clear_button', 'workreap_search_clear_button', 10, 2);
    function workreap_search_clear_button($title = 'Search', $page_url = '')
    {
        ?>
        <div class="wr-aside-holder">
            <div class="wr-filderbtns">
                <button type="submit" class="wr-btn btn-group-lg"><?php echo esc_html($title); ?></button>
                <a href="<?php echo esc_url($page_url); ?>"
                   class="wr-clearfilter"><?php esc_html_e('Clear filter', 'workreap'); ?></a>
            </div>
        </div>
        <?php
    }
}

/**
 * Price plans template heading
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_price_plans_content')) {
    add_action('workreap_packages_listing', 'workreap_price_plans_content');
    function workreap_price_plans_content()
    {
        ob_start();
        workreap_get_template(
            'packages.php',
            array()
        );
        echo ob_get_clean();
    }
}

/**
 * Price plans packages
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_price_plans_duration')) {
  function workreap_price_plans_duration($package_type)
  {
    $label  = '';
    switch($package_type){
        case 'year':
            $label  = esc_html__('Year', 'workreap');
            break;
        case 'month':
            $label  = esc_html__('Month', 'workreap');
            break;
        case 'days':
            $label  = esc_html__('Day', 'workreap');
            break;
        default:
            $label  = esc_html__('Day', 'workreap');
    }

    return $label;

  }
}

/**
 * Task order status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_task_order_status')) {
    add_action( 'workreap_task_order_status', 'workreap_task_order_status');
    function workreap_task_order_status($order_id)
    {
      $post_status    = get_post_meta( $order_id, '_task_status', true );
      $post_status    = !empty($post_status) ? $post_status : '';
    
      if(empty($post_status)){
        $post_status    = get_post_status($order_id);
      }
      $label_link     = '';
      switch($post_status){ 
        
        case 'wc-on-hold':
            $label          = esc_html__('On hold', 'workreap');
            $label_link     = '<span class="wr-project-tag wr-awaiting">'.esc_html($label).'</span>';
            break;
        case 'hired':
          $label      = esc_html__('Ongoing', 'workreap');
          $label_link = '<span class="wr-tag-ongoing">'.esc_html($label).'</span>';
          break;
        case 'completed':
          $label      = _x('Completed', 'Title for order status', 'workreap' );
          $label_link = '<span class="wr-tag-ongoing bg-complete">'.esc_html($label).'</span>';
          break;
        case 'cancelled':
          $label      = esc_html__('Cancelled', 'workreap');
          $label_link = '<span class="wr-tag-ongoing bg-cancel">'.esc_html($label).'</span>';
          break;
        default:
          $label      = esc_html__('New', 'workreap');
          $label_link = '<span class="wr-tag-ongoing bg-new">'.esc_html($label).'</span>';
      }

      ob_start(); ?>
        <div class="wr-tags"><?php echo do_shortcode( $label_link );?></div>
      <?php echo ob_get_clean();

    }
}

/**
 * Task order author details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_task_author')) {
    add_action( 'workreap_task_author', 'workreap_task_author', 10, 2);
    function workreap_task_author($user_id,$type='freelancers')
    {

        $link_id    = workreap_get_linked_profile_id( $user_id,'',$type );
        $task_by    = !empty($type) && $type === 'freelancers' ? esc_html__('Task from','workreap') : esc_html__('Task by','workreap');
        $user_name  = workreap_get_username($link_id);
        $avatar     = apply_filters(
                        'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 40, 'height' => 40), $link_id), array('width' => 40, 'height' => 40)
                    );
        ob_start();
        ?>
        <div class="wr-tabitemextras">
            <div class="wr-tabitemextrasinfo">
                <?php if( !empty($avatar) ){?>
                    <figure>
                        <img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
                    </figure>
                <?php } ?>
                <?php if( !empty($user_name) ){?>
                    <div class="wr-taskinfo">
                        <?php if( !empty($task_by) ){?>
                            <span><?php echo esc_html($task_by);?></span>
                        <?php } ?>
                        <h6><?php echo esc_html($user_name);?></h6>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        echo ob_get_clean();
    }
}

/**
 * Task order delivery date
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_delivery_date')) {
    add_action( 'workreap_delivery_date', 'workreap_delivery_date');
    function workreap_delivery_date($order_id)
    {
        $delivery_date  = get_post_meta( $order_id, 'delivery_date', true);
        $delivery_date  = !empty($delivery_date) ? date_i18n(get_option('date_format'), $delivery_date) : '';
        ob_start();
        if( !empty($delivery_date) ){?>
            <div class="wr-tabitemextras">
                <div class="wr-tabitemextrasinfo">

                    <div class="wr-taskinfo">
                        <span><?php esc_html_e('Task deadline','workreap');?></span>
                        <h6><?php echo esc_html($delivery_date);?></h6>
                    </div>
                </div>
            </div>
        <?php }
        echo ob_get_clean();
    }
}

/**
 * Task order delivery date
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_order_date')) {
    add_action( 'workreap_order_date', 'workreap_order_date');
    function workreap_order_date($order_id)
    {
        $order          = wc_get_order($order_id);
        $data_created   = isset($order) && $order ? $order->get_date_created() : '';
        $date_format    = get_option( 'date_format' );
        $data_created   = date_i18n($date_format, strtotime($data_created));
        ob_start();
        if( !empty($data_created) ){?>
            <div class="wr-tabitemextras">
                <div class="wr-tabitemextrasinfo">

                    <div class="wr-taskinfo">
                        <span><?php esc_html_e('Task order date','workreap');?></span>
                        <h6><?php echo esc_html($data_created);?></h6>
                    </div>
                </div>
            </div>
        <?php }
        echo ob_get_clean();
    }
}

/**
 * Task price plan
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_price_plan')) {
    add_action( 'workreap_price_plan', 'workreap_price_plan');
    function workreap_price_plan($order_id)
    {
        $order_details   = get_post_meta( $order_id, 'order_details', true);
        $order_details   = !empty($order_details) ? $order_details : array();
        ob_start();
        if( !empty($order_details['title']) ){
            $plan_title = apply_filters( 'workreap_plan_conetnet', $order_details['title'],$order_id );
            ?>
            <div class="wr-tabitemextras">
                <div class="wr-tabitemextrasinfo">

                    <div class="wr-taskinfo">
                        <span><?php esc_html_e('Pricing plan','workreap');?></span>
                        <h6><?php echo do_shortcode($plan_title);?></h6>
                    </div>
                </div>
            </div>
        <?php }
        echo ob_get_clean();
    }
}

/**
 * Task order linked
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_order_linked')) {
    add_action( 'workreap_order_linked', 'workreap_order_linked');
    function workreap_order_linked($order_id='')
    {
        global $current_user;
        $invoice_url  = !empty($order_id) && $current_user->ID ? Workreap_Profile_Menu::workreap_profile_menu_link('invoices', $current_user->ID, true, 'detail', intval($order_id)) : '';
        ob_start();
        if( !empty($order_id) ){?>
            <div class="wr-tabitemextras">
                <div class="wr-tabitemextrasinfo">

                    <div class="wr-taskinfo">
                        <span><?php esc_html_e('Order ID','workreap');?></span>
                        <h6>#<a href="<?php echo esc_url($invoice_url);?>" target="_blank"><?php echo intval($order_id);?></a></h6>
                    </div>
                </div>
            </div>
        <?php }
        echo ob_get_clean();
    }
}
/**
 * Task order author details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_subtasks_count')) {
    add_action( 'workreap_subtasks_count', 'workreap_subtasks_count');
    function workreap_subtasks_count($product_data)
    {
        ob_start();
        if( !empty($product_data['subtasks']) && is_array($product_data['subtasks']) ){?>
            <div class="wr-tabitemextras">
                <div class="wr-tabitemextrasinfo">
                    <div class="wr-taskinfo">
                        <span><?php esc_html_e('Additional services','workreap');?></span>
                        <h6><?php echo wp_sprintf( _n( '%s Addon requested', '%s Add-ons requested', count($product_data['subtasks']), 'workreap' ), count($product_data['subtasks']) );?></h6>
                    </div>
                </div>
            </div>
        <?php }
        echo ob_get_clean();
    }
}

/**
 * Task order author details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_task_download_file')) {
    add_action( 'workreap_task_download_file', 'workreap_task_download_file', 10, 2);
    function workreap_task_download_file($product_id,$order_id)
    {
        $image_url          = WORKREAP_DIRECTORY_URI . '/public/images/downlaod.jpg';
        $post_title         = get_the_title($product_id);
        $task_status        = get_post_meta( $order_id, '_task_status',true );
        $task_status        = !empty($task_status) ? $task_status : '';
        $attachments_files  = get_post_meta( $product_id, '_downloadable_files',true );
        if( !empty($task_status) && $task_status!= 'cancelled' && !empty($attachments_files)){
            ob_start();
            ?>
            <div class="wr-tabitemextras">
                <div class="wr-tabitemextrasinfo wr-tabitemcrad">
                    <figure>
                        <a href="javascript:void(0);" data-id="<?php echo intval($product_id);?>" data-order_id="<?php echo intval($order_id);?>" class="wr_download_files">
                            <img class="tippy" data-tippy-content="<?php esc_attr_e('Attachments','workreap');?>" src="<?php echo esc_url($image_url);?>" alt="<?php echo esc_attr($post_title);?>">
                        </a>
                    </figure>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * Task order author details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_task_complete_html')) {
    add_action( 'workreap_task_complete_html', 'workreap_task_complete_html', 10, 2);
    function workreap_task_complete_html($order_id,$type='employers')
    {
        $task_status    = get_post_meta( $order_id, '_task_status', true );
        $task_status    = !empty($task_status) ? $task_status : '';
        $rating_id  = get_post_meta( $order_id, '_rating_id', true );
        $rating_id  = !empty($rating_id) ? intval($rating_id) : 0;

        if( !empty($task_status) && $task_status == 'completed' && !empty($rating_id) || ($type =='employers' && $task_status == 'completed') ){
            $employer_id   = get_post_meta( $order_id, 'employer_id', true);
            $employer_id   = !empty($employer_id) ? intval($employer_id) : 0;
            $link_id    = workreap_get_linked_profile_id( $employer_id,'','employers' );
            $avatar     = apply_filters(
                'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 40, 'height' => 40), $link_id), array('width' => 40, 'height' => 40)
            );
            $user_name      = !empty($link_id) ? workreap_get_username($link_id) : '';
            $rating_class   = !empty($rating_id) ? 'wr_view_rating' : 'wr_add_rating';
            $rating_feature = !empty($rating_id) ? '' : 'wr-featureRating-nostar';
            $rating_title   = !empty($rating_id) ? esc_html__('View feedback','workreap') : esc_html__('Add your feedback','workreap');
            $rating         = !empty($rating_id) ? get_comment_meta($rating_id, 'rating', true) : 0;
            $rating_avg     = !empty($rating) ? ($rating/5)*100 : 0;
            $rating_avg     = !empty($rating_avg) ? 'style="width:'.$rating_avg.'%;"' : '';
            $task_id    = get_post_meta( $order_id, 'task_product_id', true);
            $task_id    = !empty($task_id) ? intval($task_id) : 0;
            $task_title = !empty($task_id) ? get_the_title( $task_id ) : '';
            ob_start();
            ?>
            <div class="wr-userfeedback">
                <?php if( !empty($avatar) ){?>
                    <img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
                <?php } ?>
                <div class="wr-userfeedback__title">
                    <div class="wr-featureRating wr-featureRatingv2">
                        <span class="wr-featureRating__stars <?php echo esc_attr($rating_feature);?>"><span <?php echo do_shortcode( $rating_avg );?>></span></span>
                        <h6><?php echo number_format((float)$rating, 1, '.', '');?></h6>
                        <a href="javascript:void(0);" data-task_id="<?php echo esc_attr($task_id);?>"  data-title="<?php echo esc_attr($task_title);?>" data-rating_id="<?php echo esc_attr($rating_id);?>" data-order_id="<?php echo intval($order_id);?>" class="<?php echo esc_attr($rating_class);?>">(<?php echo esc_html($rating_title);?>)</a>
                    </div>
                    <?php if( !empty($user_name) ){?>
                        <h6><?php echo esc_html($user_name);?></h6>
                    <?php } ?>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        } else if( !empty($task_status) && $task_status == 'cancelled') {
            $employer_id   = get_post_meta( $order_id, 'employer_id', true);
            $employer_id   = !empty($employer_id) ? intval($employer_id) : 0;
            $link_id    = workreap_get_linked_profile_id( $employer_id,'','employers' );
            $avatar     = apply_filters(
                'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 40, 'height' => 40), $link_id), array('width' => 40, 'height' => 40)
            );

            $user_name      = !empty($link_id) ? workreap_get_username($link_id) : '';

            $task_id    = get_post_meta( $order_id, 'task_product_id', true);
            $task_id    = !empty($task_id) ? intval($task_id) : 0;
            $task_title = !empty($task_id) ? get_the_title( $task_id ) : '';
            ob_start();
            ?>
            <div class="wr-userfeedback">
                <?php if( !empty($avatar) ){?>
                    <img src="<?php echo esc_url($avatar);?>" alt="<?php echo esc_attr($user_name);?>">
                <?php } ?>
                <div class="wr-userfeedback__title">
                    <?php if( !empty($user_name) ){?>
                        <h6><?php echo esc_html($user_name);?></h6>
                    <?php } ?>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
}

/**
 * @Init Pagination Code Start
 * @return
 */
if (!function_exists('workreap_prepare_pagination')) {
    add_action('workreap_prepare_pagination', 'workreap_prepare_pagination', 10, 2);
    function workreap_prepare_pagination($pages = '', $range = 4)
    {
        $max_num_pages = !empty($pages) && !empty($range) ? ceil($pages / $range) : 1;
        $big = 999999999;
        $pagination = paginate_links(array(
            'base' => str_replace($big, '%#%', get_pagenum_link($big, false)),
            'format' => '?paged=%#%',
            'type' => 'array',
            'current' => max(1, get_query_var('paged')),
            'total' => $max_num_pages,
            'prev_text' => '<i class="lnr lnr-chevron-left">' . esc_html__('Pre', 'workreap') . '</i>',
            'next_text' => '<i class="lnr lnr-chevron-right">' . esc_html__('Nex', 'workreap') . '</i>',
        ));

        ob_start();
        if (!empty($pagination)) { ?>
            <div class='wr-pagination'>
                <ul>
                    <?php
                    foreach ($pagination as $key => $page_link) {
                        $link = htmlspecialchars($page_link);
                        $link = str_replace(' current', '', $link);
                        $activ_class = '';

                        if (strpos($page_link, 'current') !== false) {
                            $activ_class = 'class="active"';
                        } else if (strpos($page_link, 'next') !== false) {
                            $activ_class = 'class="wr-nextpage"';
                        } else if (strpos($page_link, 'prev') !== false) {
                            $activ_class = 'class="wr-prevpage"';
                        }
                        ?>
                        <li <?php echo do_shortcode($activ_class); ?> > <?php echo wp_specialchars_decode($link, ENT_QUOTES); ?> </li>
                    <?php } ?>
                </ul>
            </div>
            <?php
        }
        echo ob_get_clean();
    }
}

/**
 * @Empty listing
 * @return
 */
if (!function_exists('workreap_empty_listing')) {
    add_action('workreap_empty_listing', 'workreap_empty_listing', 10, 2);
    function workreap_empty_listing($text = '', $class = '')
    {
        global $workreap_settings;
        $text = !empty($text) ? $text : esc_html__('No details to show here', 'workreap');
        $image_url = !empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png';
        ob_start();
        ?>
        <div class="wr-submitreview <?php echo esc_attr($class); ?>">
            <figure>
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($text); ?>">
            </figure>
            <h4><?php echo esc_html($text); ?></h4>
        </div>
        <?php
        echo ob_get_clean();
    }
}

/**
 * // User authorization
 * @return
 */
if (!function_exists('workreap_user_not_authorized')) {
    add_action('workreap_user_not_authorized', 'workreap_user_not_authorized');
    function workreap_user_not_authorized()
    {
        ob_start();
        workreap_get_template(
            'dashboard/user-not-authorized.php'
        );
        echo ob_get_clean();
    }
}

/**
* // Task categories link
* @return
*/
if (!function_exists('workreap_task_categories')) {
    add_action('workreap_task_categories', 'workreap_task_categories', 10, 2);
    function workreap_task_categories($post_id, $taxonomy = 'product_cat')
    {
        global $workreap_settings;
        $task_search_url    = !empty($workreap_settings['tpl_service_search_page']) ? $workreap_settings['tpl_service_search_page'] : '';
        $task_search_url    = get_the_permalink($task_search_url);
        $product_data       = get_post_meta($post_id, 'wr_service_meta', true);
        $product_category_links = '';
        $task_cat_search_url    = '';
        if (!empty($product_data['category'])) {
            $categories = $product_data['category'];
            foreach ($categories as $term_key => $term_name) {
                $term                   = get_term_by('slug', $term_key, $taxonomy);
                $term_name              = !empty($term->name) ? $term->name : '';
                if( !empty($term_name) ){
                    $task_cat_search_url    = add_query_arg('category', esc_attr($term->slug), $task_search_url);
                    $product_category_links .= '<li>';
                        $product_category_links .= '<h5><a href="' . esc_url($task_cat_search_url) . '" rel="tag">' . esc_html($term_name) . '</a></h5>';
                    $product_category_links .= '</li>';
                }
            }
        }

        if (!empty($product_data['subcategory'])) {
            $categories = $product_data['subcategory'];
            foreach ($categories as $term_key => $term_name) {
                $term                   = get_term_by('slug', $term_key, $taxonomy);
                $term_name              = !empty($term->name) ? $term->name : '';
                if( !empty($term_name) ){
                    $task_cat_search_url    = add_query_arg('sub_category', esc_attr($term->slug), $task_cat_search_url);
                    $product_category_links .= '<li>';
                        $product_category_links .= '<h5><a href="' . esc_url($task_cat_search_url) . '" rel="tag">' . esc_html($term_name) . '</a></h5>';
                    $product_category_links .= '</li>';
                }
            }
        }

        if (!empty($product_data['service_type'])) {
            $categories = $product_data['service_type'];
            foreach ($categories as $term_key => $term_name) {
                $term       = get_term_by('slug', $term_key, $taxonomy);
                $term_name  = !empty($term->name) ? $term->name : '';
                if( !empty($term_name) ){
                    $task_service_type_search_url = add_query_arg('service[]', esc_attr($term->slug), $task_cat_search_url);
                    $product_category_links .= '<li>';
                        $product_category_links .= '<h5><a href="' . esc_url($task_service_type_search_url) . '" rel="tag">' . esc_html($term_name) . '</a></h5>';
                    $product_category_links .= '</li>';
                }
            }
        }

        if (!empty($product_category_links)) {
            $product_categories = '<ul class="wr-desclinks">';
                $product_categories .= $product_category_links;
            $product_categories .= '</ul>';
            echo do_shortcode($product_categories);
        }
    }
}

/**
 * // Get user menu details
 * @return
 */
if (!function_exists('workreap_login_user_menu_details')) {
    function workreap_login_user_menu_details()
    {
        global $current_user,$workreap_notification;
        ob_start();
        $notification		        = !empty($workreap_notification['notify_module']) ? $workreap_notification['notify_module'] : '';
        $workreap_profile_menu_list  = Workreap_Profile_Menu::workreap_get_dashboard_profile_menu();
        $sortorder                  = array_column($workreap_profile_menu_list, 'sortorder');
        array_multisort($sortorder, SORT_ASC, $workreap_profile_menu_list);
        $user_identity              = intval($current_user->ID);
        $workreap_user_role          = apply_filters('workreap_get_user_type', $user_identity);
        $user_profile_id            = workreap_get_linked_profile_id($current_user->ID, '', $workreap_user_role);
        $user_name                  = workreap_get_username($user_profile_id);
        $args['linked_profile']    = $user_profile_id;
	    $is_guppy_active = in_array( 'wp-guppy/wp-guppy.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'wpguppy-lite/wpguppy-lite.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
        if( current_user_can('administrator') || ( !empty($workreap_user_role) && ($workreap_user_role == 'freelancers' || $workreap_user_role == 'employers') )){
            $messages_count = apply_filters('wpguppy_count_all_unread_messages', $user_identity );
        ?>
        <div class="wr-main-notiwrap">
            <?php if( !current_user_can('administrator') && ( !empty($notification) || (in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins'))))) ){?>
                <ul class="wr-notidropdowns">
                    <?php if( !empty($notification) ){?>
                        <li class="wr-menu-notifications"><?php workreap_get_template_part('dashboard/dashboard', 'list-notification', $args);?></li>
                    <?php } ?>
                    <?php if( $is_guppy_active ){ ?>
                        <li class="wr-headerchatbtn">
                            <a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('inbox', $user_identity, false);?>">
                                <i class="wr-icon-message-square"></i>
                                <?php if(!empty($messages_count) ){?><em class="wr-remaining-notification"><?php echo esc_html($messages_count);?></em><?php }?>
                                <span><?php esc_html_e('Messages','workreap');?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <div class="wr-navbarbtn sub-menu-holder">
                <a href="javascript:void(0);" id="profile-avatar-menue-icon" class="wr-nav-signin">
                    <?php Workreap_Profile_Menu::workreap_get_avatar(); ?>
                </a>
                <ul class="sub-menu">
                    <?php
                    if (!empty($workreap_user_role) && $workreap_user_role === 'administrator') {
                        workreap_get_template_part('dashboard/menus/admin/menu', 'list-items');
                    } else {
                        if (!empty($workreap_profile_menu_list)) {
                            foreach ($workreap_profile_menu_list as $key => $menu_item) {
                                if (!empty($menu_item['type']) && ($menu_item['type'] == $workreap_user_role || $menu_item['type'] == 'none')) {
                                    $menu_item['id'] = $key;
                                    workreap_get_template_part('dashboard/menus/menu', 'avatar-items', $menu_item);
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php }
        return ob_get_clean();
    }
}

/**
 * Custom user menu
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_custom_user_menu')) {
	add_filter( 'wp_nav_menu', 'workreap_custom_user_menu', 10, 2 );
	function workreap_custom_user_menu( $nav_menu, $args ) {

		global $workreap_settings;
		$search      = isset( $workreap_settings['workreap_header_search'] ) && ! empty( $workreap_settings['workreap_header_search'] ) ? $workreap_settings['workreap_header_search'] : '0';
		$search_type = isset( $workreap_settings['workreap_header_search_type'] ) && ! empty( $workreap_settings['workreap_header_search_type'] ) ? $workreap_settings['workreap_header_search_type'] : [];
		$header_search = get_post_meta(get_the_ID(), 'wr_header_search', true);
		$search = ($header_search == '0' || $header_search == '1') ? $header_search : $search;

		$term_id = ! empty( $args->menu->term_id ) ? intval( $args->menu->term_id ) : 0;
		if ( empty( $term_id ) && ! empty( $args->menu ) ) {
			$menudata = wp_get_nav_menu_object( $args->menu );
			if ( ! empty( $menudata->term_id ) ) {
				$term_id = $menudata->term_id;
			}
		}

		if ( 'navbarNav' === $args->container_id ) {

			$term         = get_term( $term_id );

			$user_details = '';
			if(function_exists('get_field')){
				$user_details = get_field( 'login_user_details', $term );
            }

			if ( $search || isset( $user_details ) && $user_details == 'yes' ) {

				ob_start();

				?>
                <div class="wr-header-actions-wrapper">

                <?php if($search){
	                $default_key = ! empty( $search_type ) ? reset( $search_type ) : '';
	                $default_url = '';
	                if ( function_exists( 'workreap_get_page_uri' ) ) {
		                $default_url = ! empty( $default_key ) ? workreap_get_page_uri( $default_key ) : '';
	                }
	                $list_names = '';
	                if ( function_exists( 'workreap_get_search_list' ) ) {
		                $list_names = workreap_get_search_list( 'yes' );
	                }
                    ?>
                    <div class="wr-header-search-wrapper">
                        <form class="wt-formtheme wt-formbanner" action="<?php echo esc_url( $default_url ); ?>" method="get">
                            <fieldset>
                                <div class="form-group">
                                    <label><input type="text" name="keyword" class="form-control" placeholder="<?php echo esc_attr__('Search', 'workreap'); ?>"></label>
                                    <div class="wt-formoptions">
					                    <?php if ( ! empty( $list_names[ $default_key ] ) ) { ?>
                                            <div class="wt-dropdown">
                                                <span><em class="selected-search-type"><?php echo esc_html( $list_names[ $default_key ] ); ?></em><i class="wr-icon-chevron-down"></i></span>
                                            </div>
					                    <?php } ?>
                                        <div class="wt-radioholder">
						                    <?php
						                    foreach ( $search_type as $search ) {
							                    $action_url = '';
							                    if ( function_exists( 'workreap_get_page_uri' ) ) {
								                    $action_url = workreap_get_page_uri( $search );
							                    }
							                    if ( ! empty( $search ) && $search === $default_key ) {
								                    $checked = 'checked';
							                    } else {
								                    $checked = '';
							                    }
							                    $search_title = ! empty( $list_names[ $search ] ) ? $list_names[ $search ] : '';
							                    $flag_key 	= rand(9999, 999999);
							                    ?>
                                                <span class="wt-radio wr-<?php echo esc_attr( $search ); ?>">
                                                    <input id="wt-<?php echo esc_attr( $flag_key ); ?>"
                                                           data-url="<?php echo esc_url( $action_url ); ?>"
                                                           data-title="<?php echo esc_html( $search_title ); ?>" type="radio"
                                                           name="searchtype"
                                                           value="<?php echo esc_attr( $search ); ?>" <?php echo esc_attr( $checked ); ?>>
                                                    <label for="wt-<?php echo esc_attr( $flag_key ); ?>"><?php echo esc_html( $search_title ); ?></label>
                                                </span>
						                    <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                <?php }?>
                <?php if ( class_exists( 'ACF' ) && function_exists( 'get_field' ) ) {
					if ( isset( $user_details ) && $user_details == 'yes' ) {
						if ( is_user_logged_in() ) { ?>
                            <div class="wr-user-menu-wrapper"><?php echo workreap_login_user_menu_details(); ?></div>
						<?php } else {
							$view_type   = ! empty( $workreap_settings['registration_view_type'] ) ? $workreap_settings['registration_view_type'] : 'pages';
							$login       = workreap_get_page_uri( 'login' );
							$login_class = '';
							if ( ! empty( $view_type ) && $view_type === 'popup' ) {
								$login       = 'javascript:void(0);';
								$login_class = 'wr-login-poup';
							}?>
                                <div class="wr-user-menu-wrapper">
                                    <div class="wr-navbarbtn">
                                        <a href="<?php echo do_shortcode($login); ?>" class="wr-btn wr-login <?php echo esc_attr($login_class); ?>"><?php echo esc_html__( 'Sign in', 'workreap' ); ?></a>
                                        <span data-type="post_task" id="wr_post_task" class="wr-btn-solid-lg"><?php echo esc_html__( 'Post a task', 'workreap' ); ?><i class="wr-icon-plus"></i></span>
                                    </div>
                                </div>
                                <?php
						}
					}
				}?>
                </div>
                <?php
				$html   = ob_get_clean();
				$nav_menu .= $html;
			}
		}

		return $nav_menu;
	}
}

/**
 * Custom footer menu
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_footer_custom_user_menu')) {
    function workreap_footer_custom_user_menu() {
        global $workreap_settings;
	    $user_id	  	= is_user_logged_in()  ? get_current_user_id() : 0 ;
	    $user_type		= !empty($user_id) ? workreap_get_user_type($user_id) : '';
	    $dashboard_type		= !empty($workreap_settings['dashboard_header_type']) ? $workreap_settings['dashboard_header_type'] : '';
	    if (is_user_logged_in() && ($dashboard_type === 'workreap-topbar' || ($user_type !=='freelancers' && $user_type !== 'employers') ) ) {
            ob_start();?>
                <div class="wr-user-menu d-xl-none d-xxl-none"><a href="javascript:void(0);" class="wr-dbmenu wr_user_profile"><?php Workreap_Profile_Menu::workreap_get_avatar(); ?><i class="wr-icon-x"></i></a><?php echo do_shortcode(workreap_login_user_menu_details());?></div>
            <?php
            echo ob_get_clean();
        }else if(is_user_logged_in() && $dashboard_type === 'workreap-sidebar' && ( $user_type ==='freelancers' || $user_type === 'employers' ) ){
	        do_action('workreap_dashboard_user_sidebar');
        }
    }
    add_action( 'wp_footer', 'workreap_footer_custom_user_menu' );
}

/**
 * // Get freelancer views
 */
if (!function_exists('workreap_get_freelancer_views')) {
    function workreap_get_freelancer_views($freelancer_id = '')
    {
        if (empty($freelancer_id)) {
            return;
        }

        $workreap_freelancer_views = get_post_meta($freelancer_id, 'workreap_profile_views', TRUE);
        $workreap_freelancer_views = !empty($workreap_freelancer_views) ? intval($workreap_freelancer_views) : 0;
        ob_start();
        ?>
        <li>
            <i class="wr-icon-eye"></i>
            <span>
                <?php
                    if( !empty($workreap_freelancer_views) ) {
                        echo wp_sprintf( _n( '%s view', '%s views', $workreap_freelancer_views, 'workreap' ), number_format_i18n($workreap_freelancer_views) );
                    } else {
                        esc_html_e('0 view','workreap');
                    }
                ?>
            </span>
        </li>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_get_freelancer_views', 'workreap_get_freelancer_views');
}

/**
 * getting freelancer rating and count
 */
if (!function_exists('workreap_get_freelancer_rating_count')) {
    function workreap_get_freelancer_rating_count($freelancer_id = '')
    {
        $user_rating                = get_post_meta( $freelancer_id, 'wr_total_rating', true );
        $user_rating                = !empty($user_rating) ? $user_rating : 0;
        $review_users               = get_post_meta( $freelancer_id, 'wr_review_users', true );
        $review_users               = !empty($review_users) ? intval($review_users) : 0;
        ob_start();
        ?>
        <li>
            <i class="fas fa-star wr-icon-yellow"></i>
            <em> <?php echo number_format($user_rating, 1, '.', ''); ?> </em>
            <span>
                (
                    <?php  if( !empty($review_users) ) {
                            echo wp_sprintf( _n( '%s review', '%s reviews', $review_users, 'workreap' ), number_format_i18n($review_users) );
                    } else {
                        esc_html_e('0 review','workreap');
                    }?>
                )
        </span>
        </li>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_get_freelancer_rating_count', 'workreap_get_freelancer_rating_count');
}

/**
 * Basic profile avatar,name,tagline hourly rate
 */
if (!function_exists('workreap_get_freelancer_profile_basic')) {
    function workreap_get_freelancer_profile_basic($post_id = '')
    {
        $user_name      = workreap_get_username($post_id);
        $user_name      = !empty($user_name) ? $user_name : '';
        $user_id        = get_post_meta($post_id, '_linked_profile', true);
        $wr_post_meta   = get_post_meta( $post_id,'wr_post_meta',true );
        $wr_post_meta   = !empty($wr_post_meta) ? $wr_post_meta : array();
        $tagline        = !empty($wr_post_meta['tagline']) ? $wr_post_meta['tagline'] : '';
        $wr_hourly_rate    = get_post_meta( $post_id, 'wr_hourly_rate', true );
        $wr_hourly_rate    = isset($wr_hourly_rate) ? $wr_hourly_rate : 0;
        $avatar         = apply_filters(
            'workreap_avatar_fallback',
            workreap_get_user_avatar(array('width' => 100, 'height' => 100), $post_id),array('width' => 100, 'height' => 100));
        ob_start();
        ?>
        <div class="wr-profile-basic">
            <?php if (!empty($avatar)) { ?>
                <div class="wr-profile-image">
                    <figure>
                        <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($user_name); ?>">
                        <?php do_action('workreap_print_user_status', $user_id);?>
                    </figure>
                </div>
            <?php } ?>
            <div class="wr-profile-title">
                <?php if( !empty($tagline) ){?>
                    <h5><?php echo esc_html($tagline);?></h5>
                <?php } ?>
                <?php if( !empty($user_name) ){?>
                    <h1>
                        <a href="<?php echo esc_url(get_the_permalink($post_id));?>"><?php echo esc_html($user_name);?></a>
                        <?php do_action( 'workreap_verification_tag_html', $post_id ); ?>
                    </h1>
                <?php } ?>
                <?php if(isset($wr_hourly_rate)){?>
                    <h6>
                        <?php esc_html_e("Starting from","workreap");?>
                        <span class="wr-freelancer-hourly-rate-value"><?php echo sprintf(esc_html__('%s /hr', 'workreap'), workreap_price_format($wr_hourly_rate, 'return')); ?></span>
                    </h6>
                <?php } ?>
            </div>
        </div>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_get_freelancer_profile_basic', 'workreap_get_freelancer_profile_basic');
}

/**
 * Mark freelancer as fav list
 * freelancers
 */
if (!function_exists('workreap_save_freelancer_html')) {
    function workreap_save_freelancer_html($current_user_id = '', $freelancer_id = '', $key = '', $type = '', $saved_type = '')
    {
        $user_id            = !empty($current_user_id) ? $current_user_id : 0;
        $user_type          = apply_filters('workreap_get_user_type', $current_user_id);
        $linked_profile_id  = workreap_get_linked_profile_id($user_id, '', $user_type);
        $saved_items        = get_post_meta($linked_profile_id, $key, true);
        $saved_class        = !empty($saved_items) && in_array($freelancer_id, $saved_items) ? 'bg-redheart' : 'bg-heart';
        $action             = !empty($saved_items) && in_array($freelancer_id, $saved_items) ? '' : 'saved';
        ob_start();
        if (!empty($type) && $type == 'list') { ?>
            <li>
                <a href="javascript:void(0);" class="wr-heart" data-action="<?php echo esc_attr($action); ?>"
                   data-post_id="<?php echo intval($freelancer_id); ?>" data-id="<?php echo intval($user_id); ?>"
                   data-type="<?php echo esc_attr($user_type); ?>">
                    <span class="<?php echo esc_attr($saved_class); ?> wr-icon-heart"></span><?php esc_html_e('Save', 'workreap'); ?>
                </a>
            </li>
        <?php } else if (!empty($type) && $type == 'v2') { ?>
                <a href="javascript:void(0);" class="wr_saved_items wr-save-item <?php echo esc_attr($saved_class); ?>"
                   data-action="<?php echo esc_attr($action); ?>"
                   data-post_id="<?php echo intval($freelancer_id); ?>" data-id="<?php echo intval($user_id); ?>"
                   data-type="<?php echo esc_attr($saved_type); ?>">
                    <span class="<?php echo esc_attr($saved_class); ?> wr-icon-heart"></span>
                </a>
        <?php }else { ?>
            <li>
                <a href="javascript:void(0);" class="wr_saved_items <?php echo esc_attr($saved_class); ?>"
                   data-action="<?php echo esc_attr($action); ?>"
                   data-post_id="<?php echo intval($freelancer_id); ?>" data-id="<?php echo intval($user_id); ?>"
                   data-type="<?php echo esc_attr($saved_type); ?>">
                    <span class="<?php echo esc_attr($saved_class); ?> wr-icon-heart"></span><?php esc_html_e('Save', 'workreap'); ?>
                </a>
            </li>
        <?php }
        echo ob_get_clean();
    }
    add_action('workreap_save_freelancer_html', 'workreap_save_freelancer_html', 10, 5);
}

/**
 * Render Freelancer type html
 */
if (!function_exists('workreap_render_price_filter_htmlv2')) {
    function workreap_render_price_filter_htmlv2($price_text ='',$min_price='',$max_price='',$flag='')
    {
        global $workreap_settings;
	    $disable_range_slider   = !empty($workreap_settings['disable_range_slider']) ? $workreap_settings['disable_range_slider'] : false;

        if( !empty($price_text) ){?>
            <h6><?php echo esc_html($price_text);?></h6>
        <?php } ?>
        <div class="wr-areasizebox">
            <div class="form-group-wrap" id="wr-range-wrapper" data-bs-target="#rangecollapse">
                <div class="form-group form-group-half" >
                    <input type="number" class="form-control" value="<?php echo esc_attr($min_price); ?>" name="min_price" min="<?php echo esc_attr($min_price);?>" max="<?php echo esc_attr($max_price);?>" step="1" placeholder="<?php esc_attr_e('Min price','workreap');?>" id="wr_amount_min">
                </div>
                <div class="form-group form-group-half">
                    <input type="number" class="form-control" value="<?php echo esc_attr($max_price); ?>" name="max_price" step="1" placeholder="<?php esc_attr_e('Max price','workreap');?>" id="wr_amount_max">
                </div>
            </div>
            <?php if(!$disable_range_slider){ ?>
                <div class="wr-distanceholder wr-distanceholder-v2">
                    <div class="collapse wr-distance" id="rangecollapse">
                        <div id="slider-range" class="wr-tooltiparrow wr-rangeslider"></div>
                    </div>
                </div>
            <?php }?>
        </div>
        <?php
	    if(!$disable_range_slider) {
		    $script = "jQuery(document).on('ready', function ($) {
                jQuery('#slider-range').slider({
                    range: true,
                    min: " . esc_attr( $min_price ) . ",
                    max: " . esc_attr( $max_price ) . ",
                    values: ['" . esc_attr( $min_price ) . "', '" . esc_attr( $max_price ) . "'],
                    slide: function(event, ui) {
                    jQuery('#wr_amount_min').val(ui.values[0]);
                    jQuery('#wr_amount_max').val(ui.values[1]);
                    }
                });

                jQuery('#wr_amount_min').val(jQuery('#slider-range').slider('values', 0));
                jQuery('#wr_amount_max').val(jQuery('#slider-range').slider('values', 1));
                jQuery('#wr_amount_min').change(function() {
                    jQuery('#slider-range').slider('values', 0, jQuery(this).val());
                });

                jQuery('#wr_amount_max').change(function() {
                    jQuery('#slider-range').slider('values', 1, jQuery(this).val());
                });

                jQuery(document).on('click', '.wr-reset-price-range', function (e) {
                    e.preventDefault();
                    jQuery('#wr_amount_min').val(" . esc_attr( $min_price ) . ");
                    jQuery('#wr_amount_max').val(" . esc_attr( $max_price ) . ");
                });
                
            });";
		    wp_add_inline_script( 'workreap', $script, 'after' );
	    }
    }
    add_action('workreap_render_price_filter_htmlv2', 'workreap_render_price_filter_htmlv2', 10, 4);
}

/**
 * Render term html
 */
if (!function_exists('workreap_render_term_filter_htmlv2')) {
    function workreap_render_term_filter_htmlv2($selected_type   = array(),$type='',$attribute='',$text='')
    {
        $term_data = workreap_get_term_dropdown($type, false, 0, false);
        ob_start();
        if (is_array($term_data) && !empty($term_data)) {
            ?>
            <div class="wr-advancecheck">
                <?php if( !empty($text) ){?>
                    <h6><?php echo esc_html($text);?></h6>
                <?php } ?>
                <ul class="wr-advancefilter">
                    <?php
                    foreach ($term_data as $value) {
                        $checked = !empty($value->slug) && !empty($selected_type) && in_array($value->slug, $selected_type) ? 'checked' : '';
                        ?>
                        <li>
                            <div class="wr-form-checkbox">
                                <input class="form-check-input wr-form-check-input-sm" id="<?php echo esc_html($value->term_id); ?>" value="<?php echo esc_html($value->slug); ?>" type="checkbox" <?php echo do_shortcode( $attribute )?> <?php echo esc_attr($checked); ?>>
                                <label for="<?php echo esc_html($value->term_id); ?>" class="form-check-label">
                                    <span><?php echo esc_html($value->name); ?></span>
                                </label>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        echo ob_get_clean();
    }
    add_action('workreap_render_term_filter_htmlv2', 'workreap_render_term_filter_htmlv2', 10, 4);
}

/**
 * Render Freelancer type html
 */
if (!function_exists('workreap_render_freelancer_type_filter_html')) {
    function workreap_render_freelancer_type_filter_html($selected_freelancer_type   = array())
    {
        $freelancer_type_data = workreap_get_term_dropdown('freelancer_type', false, 0, false);
        ob_start();
        if (is_array($freelancer_type_data) && !empty($freelancer_type_data)) {
            ?>
            <div class="wr-aside-holder">
                <div class="wr-sidebartitle collapsed" data-bs-toggle="collapse" data-bs-target="#freelancer-type" role="button" aria-expanded="false">
                    <h5><i class="wr-icon-minus"></i> <?php esc_html_e('Freelancer Type', 'workreap'); ?></h5>
                </div>
                <div class="wr-sidebarcontent collapse" id="freelancer-type">
                    <ul class="wr-categoriesfilter">
                        <?php
                        foreach ($freelancer_type_data as $value) {
                            $checked = !empty($value->slug) && !empty($selected_freelancer_type) && in_array($value->slug, $selected_freelancer_type) ? 'checked' : '';
                            ?>
                            <li>
                                <div class="wr-checkbox">
                                    <input id="<?php echo esc_html($value->term_id); ?>" value="<?php echo esc_html($value->slug); ?>" type="checkbox" name="freelancer_type[]" <?php echo esc_attr($checked); ?>>
                                    <label for="<?php echo esc_html($value->term_id); ?>">
                                        <span><?php echo esc_html($value->name); ?></span>
                                    </label>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
        }
        echo ob_get_clean();
    }
    add_action('workreap_render_freelancer_type_filter_html', 'workreap_render_freelancer_type_filter_html', 10, 1);
}

/**
 * Render Freelancer English Level html
 */
if (!function_exists('workreap_render_english_level_filter_html')) {
    function workreap_render_english_level_filter_html($selected_english_level   = array())
    {
        global $workreap_settings;
        $hide_languages       = !empty($workreap_settings['hide_languages']) ? $workreap_settings['hide_languages'] : 'no';
        $english_level_data = workreap_get_term_dropdown('english_level', false, 0, false);
        ob_start();
        if ( !empty($english_level_data) && is_array($english_level_data)) {
            ?>
            <div class="wr-aside-holder">
                <div class="wr-sidebartitle collapsed" data-bs-toggle="collapse" data-bs-target="#eng-level" role="button" aria-expanded="false">
                    <h5><i class="wr-icon-minus"></i> <?php esc_html_e('English Level', 'workreap'); ?></h5>
                </div>
                <div class="wr-sidebarcontent collapse" id="eng-level">
                    <ul class="wr-categoriesfilter">
                        <?php
                        foreach ($english_level_data as $value) {
                            $checked = !empty($value->slug) && !empty($selected_english_level) && in_array($value->slug, $selected_english_level) ? 'checked' : '';
                            ?>
                            <li>
                                <div class="wr-checkbox">
                                    <input id="<?php echo esc_html($value->term_id); ?>"
                                           value="<?php echo esc_html($value->slug); ?>" type="checkbox" name="english_level[]" <?php echo esc_attr($checked); ?>>
                                    <label for="<?php echo esc_html($value->term_id); ?>"><span><?php echo esc_html($value->name); ?></span></label>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
        }
        echo ob_get_clean();
    }
    add_action('workreap_render_english_level_filter_html', 'workreap_render_english_level_filter_html', 10, 1);
}

/**
 * Render price range html
 */
if (!function_exists('workreap_render_price_range_filter_html')) {
    function workreap_render_price_range_filter_html($title = '', $min_price = 0, $max_price = 0){
        global $workreap_settings;
        $min_search_price       = !empty($workreap_settings['min_search_price']) ? $workreap_settings['min_search_price'] : 1;
        $max_search_price       = !empty($workreap_settings['max_search_price']) ? $workreap_settings['max_search_price'] : 5000;
        $disable_range_slider   = !empty($workreap_settings['disable_range_slider']) ? $workreap_settings['disable_range_slider'] : false;

        if( empty($min_price) ){
            $min_price  = $min_search_price;
        }

        if( empty($max_price) ){
            $max_price  = $max_search_price;
        }

        ob_start();
        ?>
        <div class="wr-aside-holder">
            <div class="wr-sidebartitle collapsed" data-bs-toggle="collapse" data-bs-target="#price" role="button" aria-expanded="false">
                <h5><?php echo esc_html($title); ?></h5>
            </div>
            <div class="wr-areasizebox collapse" id="price">
                <div class="wr-rangevalue" data-bs-target="#wr-rangecollapse" role="list" aria-expanded="false">
                    <input type="number" value="<?php echo esc_attr($min_price);?>" name="min_price" id="wr_amount_min" class="form-control" autocomplete="off">
                    <input type="number" value="<?php echo esc_attr($max_price);?>" name="max_price" id="wr_amount_max" class="form-control" autocomplete="off">
                </div>
            </div>
            <?php  if(!$disable_range_slider){
                ?>
                <div class="wr-distanceholder">
                    <div id="wr-rangecollapse" class="collapse">
                        <div class="wr-distance">
                            <div id="slider-range"></div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
        <?php
        if(!empty(!$disable_range_slider)){
            $script = "jQuery(document).on('ready', function ($) {
                jQuery('#slider-range').slider({
                    range: true,
                    min: " . esc_attr($min_search_price) . ",
                    max: " . esc_attr($max_search_price) . ",
                    values: ['" . esc_attr($min_price) . "', '" .esc_attr( $max_price) . "'],
                    slide: function(event, ui) {
                    jQuery('#wr_amount_min').val(ui.values[0]);
                    jQuery('#wr_amount_max').val(ui.values[1]);
                    }
                });

                jQuery('#wr_amount_min').val(jQuery('#slider-range').slider('values', 0));
                jQuery('#wr_amount_max').val(jQuery('#slider-range').slider('values', 1));
                jQuery('#wr_amount_min').change(function() {
                    jQuery('#slider-range').slider('values', 0, jQuery(this).val());
                });

                jQuery('#wr_amount_max').change(function() {
                    jQuery('#slider-range').slider('values', 1, jQuery(this).val());
                });

                jQuery(document).on('click', '.wr-reset-price-range', function (e) {
                    e.preventDefault();
                    let lower_value_ = jQuery('#priceMin').val();
                    let upper_value_ = jQuery('#priceMax').val();
                    jQuery('#wr_amount_min').val(1);
                    jQuery('#wr_amount_max').val(10000);
                });

            });";
            wp_add_inline_script('workreap', $script, 'after');
        }
        echo ob_get_clean();
    }

    add_action('workreap_render_price_range_filter_html', 'workreap_render_price_range_filter_html', 10, 3);
}

/**
 * Render Location html
 */
if (!function_exists('workreap_render_location_filter_html')) {
    function workreap_render_location_filter_html($wr_location = '')
    {
        if (class_exists('WooCommerce')) {
            $countries_obj = new WC_Countries();
            $countries = $countries_obj->get_allowed_countries('countries');
        }
        ob_start();
        if (is_array($countries) && !empty($countries)) {
            ?>
            <div class="wr-aside-holder">
                <div class="wr-sidebartitle collapsed" data-bs-toggle="collapse" data-bs-target="#location" role="button" aria-expanded="false">
                    <h5><i class="wr-icon-minus"></i> <?php esc_html_e('Location', 'workreap'); ?></h5>
                </div>
                <div class="wr-select collapse" id="location">
                    <select id="wr_country" name="location" class="form-control" data-placeholderinput="<?php esc_attr_e('Search country', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose country', 'workreap'); ?>">
                        <option selected hidden disabled value=""><?php esc_html_e('Select location...', 'workreap'); ?></option>

                        <?php
                        foreach ($countries as $key => $item) {
                            $selected = (!empty($wr_location) && $wr_location === $key) ? 'selected' : '';
                            ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($item); ?> </option>
                            <?php
                        }
                        ?>

                    </select>
                </div>
            </div>
            <?php
        }
        echo ob_get_clean();
    }
    add_action('workreap_render_location_filter_html', 'workreap_render_location_filter_html', 10, 1);
}

/**
 * Set Terms Dropdown
 */
if (!function_exists('workreap_get_term_dropdown')) {
    function workreap_get_term_dropdown($taxonomy_name = 'category', $hierarical = false, $parent = 0, $hide_empty = false)
    {
        $term_data = array(
            'taxonomy' => $taxonomy_name,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => $hide_empty,
            'parent' => $parent,
            'number' => false, //can be 0, '0', '' too
            'offset' => '',
            'fields' => 'all',
            'name' => '',
            'slug' => '',
            'hierarchical' => $hierarical, //can be 1, '1' too
            'search' => '',
            'name__like' => '',
            'description__like' => '',
            'pad_counts' => false, //can be 0, '0', '' too
            'get' => '',
            'child_of' => false, //can be 0, '0', '' too
            'childless' => false,
            'cache_domain' => 'core',
            'update_term_meta_cache' => true, //can be 1, '1' too
            'meta_query' => '',
            'meta_key' => array(),
            'meta_value' => '',
        );
        return get_terms($term_data);
    }
}

/**
 * withdraw sort by
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_withdraw_sortby_filter')) {
    add_action('workreap_withdraw_sortby_filter', 'workreap_withdraw_sortby_filter', 10, 1);
    function workreap_withdraw_sortby_filter($sorted_val = '')
    {
        ?>
        <div class="wo-inputicon">
            <div class="wr-actionselect wr-actionselect2">
                <span><?php esc_html_e('Filter by withdraw', 'workreap'); ?>: </span>
                <div class="wr-select">
                    <select name="sort_by" id="wr-withdraw-sort" class="form-control" data-placeholder="<?php esc_attr_e('Select', 'workreap'); ?>" onchange="submit_withdraw_search()">
                        <option selected hidden disabled><?php esc_html_e('Select', 'workreap'); ?></option>
                        <option value="any"  <?php if (!empty($sorted_val) && $sorted_val == "any")  { echo esc_attr("selected"); } ?> > <?php esc_html_e('All', 'workreap');   ?> </option>
                        <option value="pending"  <?php if (!empty($sorted_val) && $sorted_val == "pending")  { echo esc_attr("selected"); } ?> > <?php esc_html_e('Pending', 'workreap');   ?> </option>
                        <option value="publish"  <?php if (!empty($sorted_val)  && $sorted_val == "publish")  { echo esc_attr("selected"); } ?> > <?php esc_html_e('Approved', 'workreap'); ?> </option>
                    </select>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * withdraw post id search
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_withdraw_search')) {
    add_action('workreap_withdraw_search', 'workreap_withdraw_search', 10, 1);
    function workreap_withdraw_search($withdraw_id   = '')
    {
        ?>
        <div class="form-group wo-inputicon wo-inputheight">
            <i class="wr-icon-search"></i>
            <input type="text" class="form-control" name="withdraw_id" value="<?php echo esc_attr($withdraw_id) ?>" placeholder="<?php esc_attr_e('Search withdrawn records here', 'workreap'); ?>">
        </div>
        <?php
    }
}

/**
 * Get activity chat history
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_activity_chat_history')) {
    function mask_sensitive_info($message) {
    $warning = '';

    // Mask email addresses
    $message = preg_replace_callback('/([a-zA-Z0-9._%+-]{1})[a-zA-Z0-9._%+-]*@([a-zA-Z])[a-zA-Z0-9.-]+\.([a-zA-Z]{2,})/', function($matches) use (&$warning) {
        $warning = "<br><span style='color: red; font-weight: bold;'>⚠️ Warning: Sharing contact details can lead to permanent account deletion.</span>";
        return $matches[1] . str_repeat('*', 5) . '@' . $matches[2] . '****.' . $matches[3];
    }, $message);

    // Mask phone numbers (9-10 digit standalone numbers, ensuring it's not part of a password or other text)
    $message = preg_replace_callback('/\b(\d{9,10})\b/', function ($matches) use (&$warning) {
        if (!preg_match('/[^0-9]/', $matches[1])) {
            $warning = "<br><span style='color: red; font-weight: bold;'>⚠️ Warning: Sharing contact details can lead to permanent account deletion.</span>";
            return '**********';
        }
        return $matches[1];
    }, $message);

    // Mask Skype and Telegram IDs (strict pattern match to avoid false positives)
    $message = preg_replace('/\b(skype[:]?|@)([A-Za-z0-9._-]{5,})\b/', '$1****', $message, -1, $count);
    if ($count > 0) {
        $warning = "<br><span style='color: red; font-weight: bold;'>⚠️ Warning: Sharing contact details can lead to permanent account deletion.</span>";
    }

    // Mask payment methods (ensure it's an exact word match to avoid partial matches)
    $payment_methods = ['paypal', 'credit card', 'debit card', 'venmo', 'cashapp', 'western union', 'bank transfer', 'stripe', 'payoneer', 'bitcoin', 'ethereum'];
    foreach ($payment_methods as $method) {
        $message = preg_replace('/\b' . preg_quote($method, '/') . '\b/i', '****', $message, -1, $count);
        if ($count > 0) {
            $warning = "<br><span style='color: red; font-weight: bold;'>⚠️ Warning: Sharing contact details can lead to permanent account deletion.</span>";
        }
    }

    // Append warning if any sensitive info was found
    if ($warning) {
        $message .= $warning;
    }

    return $message;
}


    function workreap_activity_chat_history($value = array(), $type = 'parent', $user_id = 0)
    {
        $date           = !empty($value->comment_date) ? $value->comment_date : '';
        $author_id      = !empty($value->user_id) ? $value->user_id : '';
        $comments_id    = !empty($value->comment_ID) ? $value->comment_ID : '';
        $author         = !empty($value->comment_author) ? $value->comment_author : '';
        $message        = !empty($value->comment_content) ? $value->comment_content : '';
        $message_files  = get_comment_meta($value->comment_ID, 'message_files', true);
        $message_type   = get_comment_meta($value->comment_ID, '_message_type', true);
        $child_class    = !empty($type) && $type == 'child' ? 'wr-addcomment-child' : '';
        $date           = !empty($date) ? date_i18n('F j, Y', strtotime($date)) : '';
        $author_user_type   = apply_filters('workreap_get_user_type', $author_id);
        $author_profile_id  = workreap_get_linked_profile_id($author_id, '', $author_user_type);
        $auther_url         = !empty($author_user_type) && $author_user_type === 'freelancers' ? get_the_permalink($author_profile_id) : '#';
        $author_name        = workreap_get_username($author_profile_id);
        $avatar             = apply_filters(
            'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 50, 'height' => 50), $author_profile_id), array('width' => 50, 'height' => 50)
        );
        
        
        ob_start();
        
        
        ?>
        <div class="wr-addcomment <?php echo esc_attr($child_class) ?>">
            <div class="wr-comentinfo">
                <figure>
                    <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author_name); ?>">
                </figure>
                <div class="wr-comentinfodetail">
                    <?php if (!empty($message_type) && $message_type == 'rejected') { ?>
                        <div class="wr-statustag">
                            <span class="wr-rejected">
                                <i class="fas fa-exclamation-circle"></i><?php esc_html_e('Rejected', 'workreap'); ?>
                            </span>
                        </div>
                    <?php } ?>
                    <?php if (!empty($message_type) && $message_type == 'final') { ?>
                        <div class="wr-statustag">
                            <span class="wr-final-package">
                                <i class="far fa-bell"></i><?php esc_html_e('Final package', 'workreap'); ?>
                            </span>
                        </div>
                    <?php } ?>
                    <a href="<?php echo esc_url($auther_url);?>">
                        <h5><span><?php echo esc_html($author_name); ?></span></h5>
                    </a>
                    <span><?php if (!empty($date)) { echo esc_html($date); } ?></span>
                </div>
            </div>
            <div class="wr-description">
                <p><?php echo wp_kses_post(mask_sensitive_info(wp_strip_all_tags($message))); ?></p>
            </div>

            <!-- message attachments -->
            <?php if (isset($message_files) && !empty($message_files)) { ?>
                <div class="wr-documentlist">
                    <ul class="wr-doclist">
                        <?php foreach ($message_files as $message_file) {
                            $src = WORKREAP_DIRECTORY_URI . 'public/images/doc.jpg';
                            $file_url = $message_file['url'];
                            $file_uname = $message_file['name'];
                            if (isset($message_file['ext']) && !empty($message_file['ext'])) {
                                if ($message_file['ext'] == 'pdf') {
                                    $src = WORKREAP_DIRECTORY_URI . 'public/images/pdf.jpg';
                                } elseif ($message_file['ext'] == 'png') {
                                    $src = WORKREAP_DIRECTORY_URI . 'public/images/png.jpg';
                                } elseif ($message_file['ext'] == 'ppt') {
                                    $src = WORKREAP_DIRECTORY_URI . 'public/images/ppt.jpg';
                                } elseif ($message_file['ext'] == 'psd') {
                                    $src = WORKREAP_DIRECTORY_URI . 'public/images/psd.jpg';
                                } elseif ($message_file['ext'] == 'php') {
                                    $src = WORKREAP_DIRECTORY_URI . 'public/images/php.jpg';
                                }
                            } ?>
                            <li>
                                <a href="<?php echo esc_url($file_url); ?>" class="wr-download-attachment" data-id="<?php echo esc_attr($comments_id); ?>">
                                    <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($file_uname); ?>">
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <a href="javascript:void(0);" class="wr-download-attachment" data-id="<?php echo esc_attr($comments_id); ?>"><?php esc_html_e('Download file(s)', 'workreap'); ?></a>
                </div>
            <?php } ?>
        </div>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_activity_chat_history', 'workreap_activity_chat_history', 10, 3);
}

/**
 * Load footer contents
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_load_footer_contents')) {
    add_action('init', 'workreap_load_footer_contents');
    function workreap_load_footer_contents()
    {
        if (!empty($_GET['key']) && !empty($_GET['verifyemail'])) {
            do_action('workreap_verify_user_account');
        }
    }
}

/**
 * // Account verification
 * @return
 */
if (!function_exists('workreap_verify_user_account')) {
    function workreap_verify_user_account()
    {
        if (!empty($_GET['key']) && !empty($_GET['verifyemail'])) {
            $verify_key = esc_html($_GET['key']);
            $user_email = esc_html($_GET['verifyemail']);
            $user_email = !empty($user_email) ? str_replace(' ', '+', $user_email) : '';
            $user_data = get_user_by('email', $user_email);
            $user_identity = !empty($user_data) ? $user_data->ID : 0;
            $user_type = apply_filters('workreap_get_user_type', $user_identity);
            if (!empty($user_identity)) {
                $confirmation_key = get_user_meta(intval($user_identity), 'confirmation_key', true);
                if ($confirmation_key === $verify_key) {
                    update_user_meta(intval($user_identity), 'confirmation_key', '');
                    update_user_meta(intval($user_identity), '_is_verified', 'yes');

                    // upon verification verify both profiles
                    $linked_freelancer_id = get_user_meta($user_identity, '_linked_profile', true);
                    $linked_employer_id = get_user_meta($user_identity, '_linked_profile_employer', true);
                    update_post_meta(intval($linked_freelancer_id), '_is_verified', 'yes');
                    update_post_meta(intval($linked_employer_id), '_is_verified', 'yes');
                    do_action('workreap_update_freelancer_badge', $linked_freelancer_id,'profile_approved');
                    if (!empty($user_type) && ($user_type == 'freelancers' || $user_type == 'employers')) {
                        $redirect = workreap_get_page_uri('dashboard');
                    } else {
                        $redirect = home_url('/');
                    }
                    if (!is_user_logged_in()) {
                        if (!is_wp_error($user_data) && isset($user_data->ID) && !empty($user_data->ID)) {
                            wp_clear_auth_cookie();
                            wp_set_current_user($user_data->ID, $user_data->user_login);
                            wp_set_auth_cookie($user_data->ID, true);
                            update_user_caches($user_data);
                            do_action('wp_login', $user_data->user_login, $user_data);
                            wp_redirect($redirect);
                            exit();
                        }
                    } else {
                        wp_redirect($redirect);
                        exit();
                    }
                }
            }
        }
    }
    add_action('workreap_verify_user_account', 'workreap_verify_user_account');
}

/**
 * Check user account status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_check_user_account_status')) {
    add_action('workreap_check_user_account_status', 'workreap_check_user_account_status', 10, 1);
    function workreap_check_user_account_status($postid)
    {
        $is_verified = get_post_meta($postid, '_is_verified', true);
        if (empty($is_verified) || $is_verified === 'no') {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Account not verified.', 'workreap');
            $json['message_desc'] = esc_html__('Your account is not verified, so you cannot process further.', 'workreap');
            wp_send_json($json);
        }
    }
}

/**
 * Check user account identity
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('workreap_check_user_account_identity')) {
    add_action('workreap_check_user_account_identity', 'workreap_check_user_account_identity', 10, 1);
    function workreap_check_user_account_identity($user_id)
    {
        global $workreap_settings;
        $identity_verification  = !empty($workreap_settings['identity_verification']) ? $workreap_settings['identity_verification'] : false;
        if(!empty($identity_verification)){
            $is_verified = get_user_meta($user_id, 'identity_verified', true);
            if (empty($is_verified) || $is_verified === 'no') {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Account not verified.', 'workreap');
                $json['message_desc'] = esc_html__('Your account is not verified, so you cannot process further.', 'workreap');
                wp_send_json($json);
            }
        }
    }
}
/**
 * workreap login/register with google
 * @return
 */
if (!function_exists('workreap_social_login')) {
    function workreap_social_login()
    {
        global $workreap_settings;
        $json = array();
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }

        $register_type       = !empty($workreap_settings['defult_register_type']) ? $workreap_settings['defult_register_type'] : 'employers';

        $json['message']    = esc_html__('Woohoo!','workreap');
        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type']           = 'error';
	        $json['message'] 		= esc_html__('Oops!', 'workreap');
            $json['message_desc']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json($json);
        }

        if (!empty($_POST['email'])) {
            $name       = sanitize_text_field($_POST['name']);
            $last_name  = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $first_name = trim(preg_replace('#' . preg_quote($last_name, '#') . '#', '', $name));
            $user_type  = $register_type;
            $user_email = !empty($_POST['email']) && is_email($_POST['email']) ? sanitize_email($_POST['email']) : '';
            $login_type = !empty($_POST['login_type']) ? sanitize_text_field($_POST['login_type']) : '';
            $ID         = email_exists($user_email);

            // User exists do login
            if (!empty($ID)) {
                $user_data      = get_user_by('email', $user_email);
                $user_identity  = !empty($user_data) ? $user_data->ID : 0;
                $user_type      = apply_filters('workreap_get_user_type', $user_identity);

                if (!empty($user_type) && ($user_type == 'freelancers' || $user_type == 'employers')) {
                    $redirect = workreap_get_page_uri('dashboard');
                } else {
                    $redirect = home_url('/');
                }

                if (!is_user_logged_in()) {
                    update_user_meta($user_data->ID, 'show_admin_bar_front', false);

                    if (!is_wp_error($user_data) && isset($user_data->ID) && !empty($user_data->ID)) {
                        wp_clear_auth_cookie();
                        wp_set_current_user($user_data->ID, $user_data->user_login);
                        wp_set_auth_cookie($user_data->ID, true);
                        update_user_caches($user_data);
                        do_action('wp_login', $user_data->user_login, $user_data);
                    }
                }

                $json['type']           = 'success';
                $json['redirect']       = $redirect;
                $json['message_desc']    = esc_html__('You have successfully logged in', 'workreap');
            } else {
                $user_nicename = sanitize_title($name);
                $userdata = array(
                    'user_login'    => $user_email,
                    'user_pass'     => '',
                    'user_email'    => $user_email,
                    'user_nicename' => $user_nicename,
                    'display_name'  => $name,
                );

                $user_identity = wp_insert_user($userdata);
                wp_update_user(array('ID' => esc_sql($user_identity), 'role' => 'subscriber', 'user_status' => 0));

                update_user_meta($user_identity, 'first_name', $first_name);
                update_user_meta($user_identity, 'last_name', $last_name);
                update_user_meta($user_identity, 'login_type', $login_type);
                update_user_meta($user_identity, 'show_admin_bar_front', false);

                if ($workreap_settings['email_user_registration'] == 'verify_by_link') {
                    update_user_meta($user_identity, '_is_verified', 'yes');
                    $linked_profile    = get_user_meta($user_identity, '_linked_profile', true);
                    if(!empty($linked_profile)){
                        do_action('workreap_update_freelancer_badge', $linked_profile,'profile_approved');
                    }
                } else {
                    update_user_meta($user_identity, '_is_verified', 'no');
                }


                $verify_new_user    = !empty($workreap_settings['verify_new_user']) ? $workreap_settings['verify_new_user'] : 'verify_by_link';

                if (!empty($verify_new_user) && $verify_new_user == 'verify_by_admin') {
                    $json_message = esc_html__("Your account have been created. Please wait while your account is verified by the admin.", 'workreap');
                } else {
                    $json_message = esc_html__("Your account have been created. Please verify your account, an email have been sent your email address.", 'workreap');
                }

                if (!empty($user_identity)) {
                    $user_data	= get_userdata($user_identity);
                    wp_clear_auth_cookie();
                    wp_set_current_user($user_data->ID, $user_data->user_login);
                    wp_set_auth_cookie($user_data->ID, true);
                    update_user_caches($user_data);
                    do_action('wp_login', $user_data->user_login, $user_data);
                }

                $dashboard              = workreap_auth_redirect_page_uri('login',$user_identity);
                $json['type']           = 'success';
                $json['message']        = esc_html__("Account created", 'workreap');
	            $json['message_desc']   = $json_message;
                $json['redirect']     = wp_specialchars_decode($dashboard);

            }
        }
        wp_send_json($json);
    }
    add_action('wp_ajax_workreap_social_login', 'workreap_social_login');
    add_action('wp_ajax_nopriv_workreap_social_login', 'workreap_social_login');
}

/**
 * Account verification notice
 */
if (!function_exists('workreap_notification')) {
    function workreap_notification($title = '', $content = '')
    { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="wr-orderrequest wr-orderrequestv2">
                    <div class="wr-ordertitle">
                        <?php if (!empty($title)) { ?>
                            <h5><?php echo esc_html($title); ?></h5>
                        <?php } ?>
                        <?php if (!empty($content)) { ?>
                            <p><?php echo esc_html($content); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    add_action('workreap_notification', 'workreap_notification', 10, 2);
}

/**
 * Account verification notice
 */
if (!function_exists('workreap_verify_account_notice')) {
    function workreap_verify_account_notice($is_verified = 'yes') {
        global $current_user, $workreap_settings;
        $identity_verification	= !empty($workreap_settings['identity_verification']) ? $workreap_settings['identity_verification'] : false;
        if (empty($is_verified) || $is_verified === 'no') {
            if (!empty($workreap_settings['email_user_registration']) && $workreap_settings['email_user_registration'] == 'verify_by_link') {
                ob_start();
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="wr-orderrequest wr-orderrequestv2 wr-email-verification wr-alert-information">
                            <div class="wr-ordertitle">
                                <h5><?php esc_html_e('Email verification required', 'workreap') ?></h5>
                                <p><?php esc_html_e('Your email is not verified, please verify your email to perform any action on the site. You can click button to get a verification link', 'workreap') ?></p>
                            </div>
                            <div class="wr-orderbtn">
                                <a class="wr-btn btn-orange re-send-email" href="javascript:void(0);"><?php esc_html_e('Resend email', 'workreap'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                echo ob_get_clean();
            } else {
                ob_start();
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="wr-orderrequest wr-orderrequestv2 wr-email-verification wr-alert-information">
                            <div class="wr-ordertitle">
                                <h5><?php esc_html_e('Email verification required', 'workreap') ?></h5>
                                <p><?php esc_html_e('Your email is not verified, please contact to administrator for the verification.', 'workreap') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                echo ob_get_clean();
            }
        }

        if( !empty($identity_verification) ){
            $verification_attachments  	= get_user_meta($current_user->ID, 'verification_attachments', true);
            $verification_attachments	= !empty($verification_attachments) ? $verification_attachments : array();
            $identity_verified  	    = get_user_meta($current_user->ID, 'identity_verified', true);
            $identity_verified		    = !empty($identity_verified) ? $identity_verified : 0;
            ?>
            <?php if(empty($identity_verified) && !empty($verification_attachments) ){?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="wr-orderrequest wr-id-verification wr-alert-information">
                            <div class="wr-ordertitle">
                                <h5><?php esc_html_e('Woohoo!', 'workreap') ?></h5>
                                <p><?php esc_html_e('You have successfully submitted your documents. buckle up, we will verify and respond to your request very soon.', 'workreap') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else if(empty($identity_verified) ){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="wr-orderrequest wr-orderrequestv2 wr-id-verification wr-alert-danger">
                            <div class="wr-ordertitle">
                                <h5><?php esc_html_e('Verification required', 'workreap') ?></h5>
                                <p><?php esc_html_e('You must verify your identity, please submit the required documents to get verified. As soon as you will be verified then you will be able to get online orders', 'workreap') ?></p>
                            </div>
                            <div class="wr-orderbtn">
                                <a class="wr-btn btn-green" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('dashboard', $current_user->ID, false, 'verification') ?>"><?php esc_html_e("let's verify account", 'workreap'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php }

    }
    add_action('workreap_verify_account_notice', 'workreap_verify_account_notice');
}

/**
 * Resend Account verification link
 */
if (!function_exists('workreap_resend_verification')) {
    add_action('wp_ajax_workreap_resend_verification', 'workreap_resend_verification');
    add_action('wp_ajax_nopriv_workreap_resend_verification', 'workreap_resend_verification');
    function workreap_resend_verification()
    {
        global $current_user, $workreap_settings;
        if( function_exists('workreap_is_demo_site') ) {
            workreap_is_demo_site();
        }
        //security check
        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Oops!', 'workreap');
            $json['message_desc'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
            wp_send_json($json);
        }

        $user_identity      = $current_user->ID;
        $user_data          = get_user_by('email', $current_user->user_email);
        $user_email         = $user_data->user_email;
        $user_profile_id    = workreap_get_linked_profile_id($user_identity);
        $username           = workreap_get_username($user_profile_id);
        $username           = !empty($username) ? $username : $user_data->display_name;
        $verify_new_user    = !empty($workreap_settings['verify_new_user']) ? $workreap_settings['verify_new_user'] : 'verify_by_link';

        if (!empty($verify_new_user) && $verify_new_user == 'verify_by_link') {
            //verification link
            $key_hash   = md5(uniqid(openssl_random_pseudo_bytes(32)));
            update_user_meta($user_identity, 'confirmation_key', $key_hash);
            $protocol       = is_ssl() ? 'https' : 'http';
            $verify_link    = esc_url(add_query_arg(array('key' => $key_hash . '&verifyemail=' . $user_email), home_url('/', $protocol)));

            if (class_exists('Workreap_Email_helper')) {
                $blogname               = get_option('blogname');
                $emailData              = array();
                $emailData['name']      = $username;
                $emailData['password']  = '';
                $emailData['email']     = $user_email;
                $emailData['site']      = $blogname;
                $emailData['verification_link'] = $verify_link;


                if (class_exists('WorkreapRegistrationStatuses')) {
                    $email_helper = new WorkreapRegistrationStatuses();
                    $email_helper->registration_user_email($emailData);
                }

                $json_message = esc_html__("An email has been sent to your email address.", 'workreap');
                $json['type'] = 'success';
                $json['message'] = esc_html__('Woohoo!', 'workreap');
                $json['message_desc'] = $json_message;
                wp_send_json($json);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Oops!', 'workreap');
            $json['message_desc'] = esc_html__('Some error occurs, please contact administrator to process verification', 'workreap');
            wp_send_json($json);
        }
    }
}

/**
 * theme sort by hook
 */
if (!function_exists('workreap_price_sortby_filter_theme')) {
    function workreap_price_sortby_filter_theme($sorted_val = '')
    {
        ?>
        <div class="wr-sortby">
            <div class="wr-actionselect">
                <span><?php esc_html_e('Sort by:', 'workreap'); ?></span>
                <div class="wr-select">
                    <select class="form-control wr-select-country wr-selectv" id="wr-sort" onchange="merge_search_field()">
                        <option value="date_desc"    <?php if (isset($sorted_val) && $sorted_val == "date_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Recent listings', 'workreap'); ?>            </option>
                        <option value="price_asc"    <?php if (isset($sorted_val) && $sorted_val == "price_asc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Price low to high', 'workreap'); ?> </option>
                        <option value="price_desc"   <?php if (isset($sorted_val) && $sorted_val == "price_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Price high to low', 'workreap'); ?> </option>
                        <option value="views_desc"   <?php if (isset($sorted_val) && $sorted_val == "views_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Listing views', 'workreap'); ?>             </option>
                        <option value="orders_desc"  <?php if (isset($sorted_val) && $sorted_val == "orders_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Listing popularity', 'workreap'); ?>           </option>
                        <option value="reviews_desc" <?php if (isset($sorted_val) && $sorted_val == "reviews_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Listing reviews', 'workreap'); ?>           </option>
                    </select>
                </div>
            </div>
        </div>
        <?php
    }
    add_action('workreap_price_sortby_filter_theme', 'workreap_price_sortby_filter_theme', 10, 1);
}

/**
 * theme sort by hook
 */
if (!function_exists('workreap_get_project_price_sortby_filter_theme')) {
    function workreap_get_project_price_sortby_filter_theme($sorted_val = '')
    {
        ?>
        <div class="wr-sortby">
            <div class="wr-actionselect">
                <span><?php esc_html_e('Sort by:', 'workreap'); ?></span>
                <div class="wr-select">
                    <select class="form-control wr-select-country wr-selectv" id="wr-sort" onchange="merge_search_field()">
                        <option value="date_desc"    <?php if (isset($sorted_val) && $sorted_val == "date_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Recent listings', 'workreap'); ?>            </option>
                        <option value="price_asc"    <?php if (isset($sorted_val) && $sorted_val == "price_asc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Price low to high', 'workreap'); ?> </option>
                        <option value="price_desc"   <?php if (isset($sorted_val) && $sorted_val == "price_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Price high to low', 'workreap'); ?> </option>
                        <option value="views_desc"   <?php if (isset($sorted_val) && $sorted_val == "views_desc") { echo esc_attr("selected"); } ?> > <?php esc_html_e('Listing views', 'workreap'); ?>             </option>
                    </select>
                </div>
            </div>
        </div>
        <?php
    }
    add_action('workreap_get_project_price_sortby_filter_theme', 'workreap_get_project_price_sortby_filter_theme', 10, 1);
}
/**
 * theme keyword search hook
 */
if (!function_exists('workreap_keyword_search_filter_theme')) {
    function workreap_keyword_search_filter_theme($search_keyword = '')
    {
        ?>
        <div class="wr-aside-content">
            <div class="wr-inputiconbtn">
                <div class="wr-placeholderholder">
                    <input type="text" name="keyword" placeholder="<?php esc_attr_e('Search with keyword', 'workreap'); ?>" value="<?php echo esc_attr($search_keyword); ?>" class="form-control">
                </div>
                <a href="javascript:void(0);" class="wr-search-icon"><i class="wr-icon-search"></i></a>
            </div>
        </div>
        <?php
    }
    add_action('workreap_keyword_search_filter_theme', 'workreap_keyword_search_filter_theme', 10, 1);
}

/**
 * theme location hook
 */
if (!function_exists('workreap_location_filter_theme')) {
    function workreap_location_filter_theme($location)
    {
        $countries  = array();
        if (class_exists('WooCommerce')) {
            $countries_obj = new WC_Countries();
            $countries = $countries_obj->get_allowed_countries('countries');
        }
        ob_start();
        if (is_array($countries) && !empty($countries)) {
            ?>
            <div class="wr-aside-holder">
                <div class="wr-asidetitle collapsed" data-bs-toggle="collapse" data-bs-target="#Location" role="button" aria-expanded="false">
                    <h5><?php esc_html_e('Location', 'workreap'); ?></h5>
                </div>
                <div id="Location" class="collapse">
                    <div class="wr-aside-content">
                        <div class="wr-filterselect">
                            <div class="wr-select-country wr-select">
                                <select id="wr_country" name="location" data-placeholderinput="<?php esc_attr_e('Search location', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Select location', 'workreap'); ?>" class="form-control">
                                    <option selected hidden disabled><?php esc_html_e('Select location...', 'workreap'); ?></option>
                                    <?php foreach ($countries as $key => $item) {
                                        $selected = (!empty($location) && $location === $key) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($item); ?></option>
                                        <?php
                                    }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        echo ob_get_clean();
    }
    add_action('workreap_location_filter_theme', 'workreap_location_filter_theme', 10, 1);
}

/**
 * theme location hook
 */
if (!function_exists('workreap_location_search_field')) {
    function workreap_location_search_field($location='')
    {
        global $workreap_settings;
        $countries  = array();
        if (class_exists('WooCommerce')) {
            $countries_obj = new WC_Countries();
            $countries = $countries_obj->get_allowed_countries('countries');
        }
        $enable_state		    = !empty($workreap_settings['enable_state']) ? $workreap_settings['enable_state'] : false;
        ob_start();
        if (is_array($countries) && !empty($countries)) {
            $cat_expanded           = !empty($location) ? 'true' : 'false';
            $cat_collapse           = !empty($location) ? '' : 'collapsed';
            $cat_collapse_content   = !empty($location) ? 'show' : '';
            ?>
            <div class="wr-aside-holder">
                <div class="wr-asidetitle <?php echo esc_attr($cat_collapse);?>" data-bs-toggle="collapse" data-bs-target="#tklocation" role="button" aria-expanded="<?php echo esc_attr($cat_expanded);?>">
                    <h5><?php esc_html_e('Location', 'workreap'); ?></h5>
                </div>
                <div id="tklocation" class="collapse <?php echo esc_attr($cat_collapse_content);?>">
                    <div class="wr-aside-content">
                        <div class="wr-filterselect wr-select">
                            <select id="task_location" name="location" data-placeholderinput="<?php esc_attr_e('Search location', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Select location', 'workreap'); ?>" class="form-control">
                                <option value=""><?php esc_html_e('Search location','workreap');?></option>
                                <?php foreach ($countries as $key => $item) {
                                    $selected = (!empty($location) && $location === $key) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($item); ?></option>
                                    <?php
                                }?>
                            </select>
                        </div>

                        <?php if( !empty($enable_state) ){
                            $states			 	= !empty($location) ? $countries_obj->get_states( $location ) : array();
                            $state              = !empty($_GET['state']) ? $_GET['state'] : '';
                            $state_country_class    = empty($location) || empty($states) ? 'd-none' : '';
                            ?>
                                <div class="wr-filterselect wr-state-parent <?php echo esc_attr($state_country_class);?>">
                                    <div class="wr-select">
                                        <select id="wr-search-state" class="wr-country-state" name="state" data-placeholderinput="<?php esc_attr_e('Search states', 'workreap'); ?>" data-placeholder="<?php esc_attr_e('Choose states', 'workreap'); ?>">
                                            <option selected hidden disabled value=""><?php esc_html_e('Select States', 'workreap'); ?></option>
                                            <?php if (!empty($states)) {
                                                foreach ($states as $key => $item) {
                                                    $selected = '';
                                                    if (!empty($state) && $state === $key) {
                                                        $selected = 'selected';
                                                    } ?>
                                                    <option class="wr-state-option" <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
                                            <?php }
                                            } ?>
                                    </select>
                                    </div>
                                </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
        echo ob_get_clean();
    }
    add_action('workreap_location_search_field', 'workreap_location_search_field', 10, 1);
}
/**
 * Theme search and clear buttons hook
 */
if (!function_exists('workreap_search_clear_button_theme')) {
    function workreap_search_clear_button_theme($title = 'Search', $page_url = '')
    {
        ?>
        <div class="wr-filterbtns">
            <button type="submit" class="wr-btn-solid-lg" id="workreap_apply_filter"><?php echo esc_html($title); ?></button>
            <a href="<?php echo esc_url($page_url); ?>" class="wr-btn-solid wr-btn-plain"><?php esc_html_e('Clear all filters', 'workreap'); ?></a>
        </div>
        <?php
    }
    add_action('workreap_search_clear_button_theme', 'workreap_search_clear_button_theme', 10, 2);
}

/**
 * No record found
 */
if (!function_exists('workreap_empty_records_html')) {
    function workreap_empty_records_html($class = '', $text = '')
    {
        global $workreap_settings;
        ob_start();
        $image_url = !empty($workreap_settings['empty_listing_image']['url']) ? $workreap_settings['empty_listing_image']['url'] : WORKREAP_DIRECTORY_URI . 'public/images/empty.png';
        ?>
        <div class="wr-submitreview wr-submitreviewv3">
            <figure>
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php esc_attr_e('add task', 'workreap'); ?>">
            </figure>
            <h4><?php echo esc_html($text); ?></h4>
        </div>
        <?php
        echo ob_get_clean();
    }
    add_action('workreap_empty_records_html', 'workreap_empty_records_html', 10, 2);
}

/**
 * @Show user post type on add user
 * @type create
 */
if (!function_exists('workreap_custom_user_profile_fields')) {
	function workreap_custom_user_profile_fields($user){
        ob_start();?>
		<h3><?php esc_html_e('Extra profile information','workreap');?></h3>
		<table class="form-table">
			<tr>
				<th><label for="company"><?php esc_html_e('User type','workreap');?></label></th>
				<td>
					<select name="type" id="workreap-type">
						<option value="employers"><?php esc_html_e('Employer','workreap');?></option>
						<option value="freelancers"><?php esc_html_e('Freelancer','workreap');?></option>
				   </select><br>
					<span class="description"><?php esc_html_e('User role should be subscriber to create user type post','workreap');?></span>
				</td>
			</tr>
		</table>
	  <?php echo ob_get_clean();
	}
	add_action( "user_new_form", "workreap_custom_user_profile_fields" );
}

/**
 * @Create profile from admin create user
 * @type create
 */
if (!function_exists('workreap_create_wp_user')) {
	add_action( 'user_register', 'workreap_create_wp_user',5,2 );
    function workreap_create_wp_user($user_id=array(),$user_array=array()) {
        global $workreap_settings,$woocommerce;
        $user_name_option   = !empty($workreap_settings['user_name_option']) ? $workreap_settings['user_name_option'] : false;
		$shortname_option   =  !empty($workreap_settings['shortname_option']) ? $workreap_settings['shortname_option'] : '';
        $package_option     =  !empty($workreap_settings['package_option']) ? $workreap_settings['package_option'] : array();

        $first_name_post	= '';
        $last_name_post	    = '';

        if( !empty( $user_id )  ) {
            $user_data_set	= get_userdata($user_id);
            $roles		    = !empty($user_data_set->roles) ? $user_data_set->roles : '';
            $email		    = !empty($user_data_set->user_email) ? $user_data_set->user_email : '';

            if( !empty($roles) && in_array('subscriber',$roles)){
                $user_type          = !empty($_POST['type']) ? $_POST['type'] : '';
                $post_data          = !empty($_POST['data']) ? $_POST['data'] : '';

                if(empty($user_type) && !empty($post_data) ){
                    parse_str($post_data, $output);
                    $user_type   = !empty($output['user_registration']['user_type']) ? $output['user_registration']['user_type'] : '';
                    $first_name_post   = !empty($output['user_registration']['first_name']) ? $output['user_registration']['first_name'] : '';
                    $last_name_post    = !empty($output['user_registration']['last_name']) ? $output['user_registration']['last_name'] : '';
                }

	            if(empty($user_type)){
		            $user_type = get_user_meta($user_id,'_user_type',true);
	            }

                //If no role is assigned then assign default role
                if(empty($user_type )){
                    $user_type       = !empty($workreap_settings['defult_register_type']) ? $workreap_settings['defult_register_type'] : 'employers';
                }

                $first_name     = get_user_meta($user_id, 'first_name', true);
                $last_name      = get_user_meta($user_id, 'last_name', true);
                $first_name     = !empty($first_name) ? $first_name : $first_name_post;
                $last_name      = !empty($last_name) ? $last_name : $last_name_post;

                $display_name   =  $first_name .  " " . $last_name;
                $display_name   = !empty($user_array['display_name']) ? $user_array['display_name'] : $display_name;

                update_user_meta($user_id, 'first_name', $first_name);
                update_user_meta($user_id, 'last_name', $last_name);
                update_user_meta($user_id, 'termsconditions', true);
                update_user_meta($user_id, 'show_admin_bar_front', false);
                update_user_meta($user_id, '_is_verified', 'no');

                $verify_link            = '';
                $verify_new_user        = !empty($workreap_settings['email_user_registration']) ? $workreap_settings['email_user_registration'] : 'verify_by_link';
                $identity_verification	= !empty($workreap_settings['identity_verification']) ? $workreap_settings['identity_verification'] : false;

                if (!empty($verify_new_user) && $verify_new_user == 'verify_by_link') {
                    //verification link
                    $key_hash     = md5(uniqid(openssl_random_pseudo_bytes(32)));
                    update_user_meta($user_id, 'confirmation_key', $key_hash);
                    $protocol     = is_ssl() ? 'https' : 'http';
                    $verify_link  = esc_url(add_query_arg(array('key' => $key_hash . '&verifyemail=' . $email), home_url('/', $protocol)));
                }

                //Short names
                $post_name      = $display_name;
                if (!empty($shortname_option)) {
                    $post_name      = explode(' ', $display_name);
                    $first_name_    = !empty($post_name[0]) ? ucfirst($post_name[0]) : '';
                    $second_name_   = !empty($post_name[1]) ? ' ' . strtoupper($post_name[1][0]) : '';
                    $post_name      = $first_name_ . $second_name_;
                }

                //Create Post
                $user_post_status = 'publish';
                if ($user_type === 'freelancers' || $user_type === 'freelancer') { // Check both singular/plural for $user_type
                    update_user_meta($user_id, 'freelancer_approval_status', 'pending');
                    $user_post_status = 'draft';
                }

                $user_post = array(
                    'post_title'    => wp_strip_all_tags($display_name),
                    'post_name'    	=> $post_name,
                    'post_status'   => $user_post_status,
                    'post_author'   => $user_id,
                    'post_type'     => apply_filters('workreap_profiles_user_post_type_name', $user_type),
                );

                $post_id = wp_insert_post($user_post);

                if (!is_wp_error($post_id)) {
                    $notifyDetails	  = array();
                    $dir_latitude     = !empty($workreap_settings['dir_latitude']) ? $workreap_settings['dir_latitude'] : 0.0;
                    $dir_longitude    = !empty($workreap_settings['dir_longitude']) ? $workreap_settings['dir_longitude'] : 0.0;

                    //add extra fields as a null
                    update_post_meta($post_id, '_address', '');
                    update_post_meta($post_id, '_latitude', $dir_latitude);
                    update_post_meta($post_id, '_longitude', $dir_longitude);
                    update_post_meta($post_id, '_linked_profile', $user_id);
                    update_post_meta($post_id, '_is_verified', 'no');
                    update_post_meta($post_id, 'zipcode', '');
                    update_post_meta($post_id, 'country', '');
                    update_user_meta($user_id, '_notification_email', $email);
                    update_post_meta( $post_id, 'is_avatar', 0 );

                    if (!empty($user_type) && $user_type === 'employers') {
                        update_user_meta($user_id, '_linked_profile_employer', $post_id);
                        update_user_meta($user_id, '_user_type', 'employers');
                        $notifyData['user_type']		= 'employers';
                        // Optional: Set a specific WordPress role for employers if it's different from default
                        // $employer_user_obj = new WP_User($user_id);
                        // $employer_user_obj->set_role('employer_role_slug');
                        // error_log("WORKREAP_DEBUG: User ID " . $user_id . " - Employer role set. Roles: " . implode(', ', $employer_user_obj->roles));

                    } else if (!empty($user_type) && ($user_type === 'freelancers' || $user_type === 'freelancer')) { // Robust check for freelancer type
                        update_post_meta($post_id, 'wr_hourly_rate', '');
                        update_user_meta($user_id, '_linked_profile', $post_id);
                        update_user_meta($user_id, '_user_type', 'freelancers'); // Use consistent 'freelancers' for meta key
                        $notifyData['user_type']		= 'freelancers';

                        // Set WordPress role for freelancer
                        // This block should be within the section for 'freelancer' user type processing
                        if (!is_wp_error($user_id)) { // Ensure user was created successfully

                            $user_obj = new WP_User($user_id);

                            // Explicitly remove the 'subscriber' role, if present.
                            if (in_array('subscriber', $user_obj->roles)) {
                                $user_obj->remove_role('subscriber');
                                // Re-fetch user object to accurately reflect role removal before next operations and logging
                                $user_obj = new WP_User($user_id);
                            }

                            // Attempt 1: Add role 'freelancer' (singular)
                            $target_role_singular = 'freelancer';
                            $user_obj->add_role($target_role_singular);

                            // Re-fetch user object from the database to get the most up-to-date roles
                            $user_obj = new WP_User($user_id);

                            // Attempt 2 (Fallback): If 'freelancer' wasn't successfully added AND 'freelancers' (plural) isn't already there, try adding 'freelancers' (plural)
                            if (!in_array($target_role_singular, $user_obj->roles) && !in_array('freelancers', $user_obj->roles)) {
                                $target_role_plural = 'freelancers';
                                $user_obj->add_role($target_role_plural);

                                // Re-fetch user object
                                $user_obj = new WP_User($user_id);
                            }

                            // Final check if any freelancer role was set
                            if (!in_array($target_role_singular, $user_obj->roles) && !in_array('freelancers', $user_obj->roles)) { // Check both again
                            } else {
                            }

                            // The line for setting _user_type meta should already be present from plugin's original code or previous steps.
                        }
                        // Add cache clearing and final role log for freelancers
                        if (isset($user_id)) {
                            clean_user_cache($user_id);

                            // Log the roles one last time AFTER clearing cache by fetching a fresh WP_User object
                            $final_user_obj_after_cache_clean = new WP_User($user_id);
                        }
                    }
                    $trail_pkg  = 0;
                    if(!empty($package_option) && !empty($user_type) && $user_type === 'employers' && ($package_option === 'paid' || $package_option === 'freelancer_free')){
                        $trail_pkg	= !empty($workreap_settings['emp_trail_pkg']) ? $workreap_settings['emp_trail_pkg'] : 0;
                    } else if(!empty($package_option) && !empty($user_type) && $user_type === 'freelancers' && ($package_option === 'paid' || $package_option === 'employer_free')){
                        $trail_pkg	= !empty($workreap_settings['free_trail_pkg']) ? $workreap_settings['free_trail_pkg'] : 0;
                    }

                    if(!empty($trail_pkg)){
                        if ( class_exists('WooCommerce') ) {
                            $woocommerce->cart->empty_cart();
                            $product                    = wc_get_product( $trail_pkg );
                            $cart_meta                  = array();
                            $cart_meta['package_id']    = $trail_pkg;
                            $cart_meta['product_name']  = $product->get_name();
                            $cart_meta['price']         = $product->get_price();
                            $cart_meta['payment_type']  = 'package';
                            $cart_meta['user_type']     = $user_type;
                            $cart_data  = array(
                                'package_id'    => $trail_pkg,
                                'cart_data'     => $cart_meta,
                                'payment_type'  => 'package',
                                'user_type'     => $user_type,
                            );
                            $woocommerce->cart->empty_cart();
                            $cart_item_data = apply_filters('workreap_package_update_cart_data',$cart_data);
                            WC()->cart->add_to_cart($trail_pkg, 1, null, null, $cart_item_data);
                            workreap_place_order($user_id,'package',$trail_pkg);
                        }
                    }
                    if (!empty($identity_verification) ){
                        update_user_meta($user_id, 'identity_verified', 0);
                    } else {
                        update_user_meta($user_id, 'identity_verified', 1);
                    }

                    $notifyData['receiver_id']		= $user_id;
                    $notifyData['type']				= 'registration';
                    $notifyData['post_data']		= $notifyDetails;
                    $notifyData['linked_profile']	= $post_id;

                    do_action('workreap_notification_message', $notifyData );

                    $wr_post_meta                 = array();
                    $wr_post_meta['first_name']   = $first_name;
                    $wr_post_meta['last_name']    = $last_name;
                    update_post_meta($post_id, 'wr_post_meta', $wr_post_meta);

                }

                $login_url    = !empty( $workreap_settings['tpl_login'] ) ? get_permalink($workreap_settings['tpl_login']) : wp_login_url();

                //Send email to users & admin
                if (class_exists('Workreap_Email_helper')) {
                    $blogname                       = get_option('blogname');
                    $emailData                      = array();
                    $emailData['name']              = $display_name;
                    $emailData['email']             = $email;
                    $emailData['verification_link'] = $verify_link;
                    $emailData['site']              = $blogname;
                    $emailData['login_url']         = $login_url;

                    //Welcome Email
                    if (class_exists('WorkreapRegistrationStatuses')) {
                        $email_helper = new WorkreapRegistrationStatuses();

                        $approval_status_for_email_check = get_user_meta($user_id, 'freelancer_approval_status', true);

                        $send_standard_user_email = true;
                        if (isset($user_type) && ($user_type === 'freelancers' || $user_type === 'freelancer')) {
                            if ($approval_status_for_email_check === 'pending') {
                                $send_standard_user_email = false;
                            } else {
                            }
                        } else {
                        }

                        if ($send_standard_user_email) {
                            if (!empty($verify_new_user) && $verify_new_user == 'verify_by_link') {
                                $email_helper->registration_user_email($emailData, $user_id);
                            } else {
                                $email_helper->registration_account_approval_request($emailData, $user_id);
                                $email_helper->registration_verify_by_admin_email($emailData, $user_id);
                            }
                        }

                        // Admin "New Coach Awaiting Approval" Email Logic
                        if (isset($user_type) && ($user_type === 'freelancers' || $user_type === 'freelancer')) {
                             $current_approval_status_for_admin_email = get_user_meta($user_id, 'freelancer_approval_status', true);
                             if ($current_approval_status_for_admin_email === 'pending') {
                                $admin_email_target = get_option('admin_email');
                                $coach_display_name = isset($display_name) ? $display_name : 'N/A'; // $display_name is in the outer scope
                                // $coach_user_id = $user_id; // $user_id is already the correct user ID
                                $admin_subject = "New Coach Registration - Awaiting Approval";

                                $user_info_for_email = get_userdata($user_id); // Get WP_User object for additional details
                                $admin_message_body = "A new coach, " . esc_html($coach_display_name) . ", has registered and is awaiting approval.\n\n";
                                $admin_message_body .= "User ID: " . $user_id . "\n";
                                $admin_message_body .= "Username: " . esc_html($user_info_for_email->user_login) . "\n";
                                $admin_message_body .= "Email: " . esc_html($user_info_for_email->user_email) . "\n\n";
                                $admin_message_body .= "You can moderate this user from the 'Coach Moderation' page in the WordPress admin area.";

                                $email_headers = array();
                                $site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                                $default_sender_email = get_option('admin_email');
                                $email_headers[] = 'From: ' . esc_html($site_name) . ' <' . $default_sender_email . '>';
                                // $email_headers[] = 'Content-Type: text/html; charset=UTF-8'; // Keep plain text for now


                                $admin_mail_result = wp_mail($admin_email_target, $admin_subject, $admin_message_body, $email_headers);
                             }
                        }

                        // General admin notification
                        if (isset($workreap_settings['email_admin_registration']) && $workreap_settings['email_admin_registration'] == true) {
                            $is_pending_freelancer_for_general_admin_email = false;
                            if (isset($user_type) && ($user_type === 'freelancers' || $user_type === 'freelancer')) {
                                if ($approval_status_for_email_check === 'pending') {
                                    $is_pending_freelancer_for_general_admin_email = true;
                                }
                            }
                            if (!$is_pending_freelancer_for_general_admin_email) {
                                $email_helper->registration_admin_email($emailData, $user_id);
                            } else {
                            }
                        }
                    }
                }
                do_action('workreap_update_freelancer_badge', $post_id,'signup');
            }
        }
	}
}

/**
 * @Rename Menu
 * @return {}
 */
if (!function_exists('workreap_rename_admin_menus')) {
	add_action( 'admin_menu', 'workreap_rename_admin_menus');
	function workreap_rename_admin_menus() {
		global $menu,$submenu;
		foreach( $menu as $key => $menu_item ) {
			if( $menu_item[2] == 'edit.php?post_type=freelancers' ){
				$menu[$key][0] = esc_html__('WR Core','workreap');
			}
		}

        add_submenu_page(
            'edit.php?post_type=freelancers',
            esc_html__('Import users','workreap'),
            esc_html__('Import users','workreap'),
            'manage_options',
            'import_users',
            'workreap_import_users_template'
        );

    }
}

/**
 * @Show product adds
 * @type create
 */
if (!function_exists('workreap_product_ads_content')) {
	function workreap_product_ads_content(){
        global $workreap_settings;
        $adds_contents  = !empty($workreap_settings['ads_content']) ? $workreap_settings['ads_content'] : '';
        if( !empty($adds_contents) ){
            ob_start();
        ?>
            <div class="wr-sidebarad"><?php echo do_shortcode( $adds_contents );?></div>
       <?php
            echo ob_get_clean();
        }
	}
	add_action( "workreap_product_ads_content", "workreap_product_ads_content" );
}
/**
 * View verification details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists(  'workreap_view_identity_detail' ) ) {
	function workreap_view_identity_detail(){
		$json       = array();
		$user_id    = !empty($_POST['user_id']) ? intval( $_POST['user_id'] ) : '';

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap');
			wp_send_json( $json );
		}

		$verification  = get_user_meta($user_id, 'verification_attachments', true);

		if(empty($verification)){
			$json['type']	= 'error';
			$json['message']	= esc_html__('No verification user details found','workreap' );
			wp_send_json($json);
		}

		$user_info	= !empty($verification['info']) ? $verification['info'] : array();
		$required = array(
			'name'   				=> esc_html__('Name', 'workreap'),
			'contact_number'  		=> esc_html__('Contact number', 'workreap'),
			'verification_number'   => esc_html__('Verification number', 'workreap'),
			'address'   			=> esc_html__('Address', 'workreap'),
		);

		if( !empty($verification['info'] ) ) {
			unset( $verification['info'] );
		}

		ob_start();
		?>
		<div class="cus-modal-bodywrap">
			<div class="cus-form cus-form-change-settings">
				<div class="edit-type-wrap">
					<?php if(!empty($user_info)){
						foreach($user_info as $key => $item){
							if(!empty($required[$key])){
						?>
						<div class="cus-options-data">
							<label><span><strong><?php echo esc_html( $required[$key] );?></strong></span></label>
							<div class="step-value">
								<span><?php echo esc_html( $item );?></span>
							</div>
						</div>
					<?php }}}?>

					<?php if(!empty($verification)){
						foreach($verification as $key => $item){
						?>
						<div class="cus-options-data cus-options-files">
							<div class="step-value">
								<span><a target="_blank" href="<?php echo esc_attr( $item['url'] );?>"><?php echo esc_attr( $item['name'] );?></a></span>
							</div>
						</div>
					<?php }}?>
				</div>
			</div>
		</div>
		<?php

		$data	= ob_get_clean();
		$json['type']	= 'success';
		$json['html']	= $data;
		$json['message']	= esc_html__('Verification user details','workreap' );
		wp_send_json($json);
	}
	add_action('wp_ajax_workreap_view_identity_detail', 'workreap_view_identity_detail');
}

/**
 * Author social accounts
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists('workreap_user_social_fields')){
	function workreap_user_social_fields($user_fields) {
		$user_fields['twitter'] 	= esc_html__('Twitter', 'workreap');
		$user_fields['facebook'] 	= esc_html__('Facebook', 'workreap');
		$user_fields['instagram'] 	= esc_html__('Instagram', 'workreap');
		$user_fields['pinterest'] 	= esc_html__('Pinterest', 'workreap');
		$user_fields['linkedin'] 	= esc_html__('Linkedin', 'workreap');

		return $user_fields;
	}
	add_filter('user_contactmethods', 'workreap_user_social_fields');
}

/**
 * custom select list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists('workreap_custom_dropdown_html')){
    function workreap_custom_dropdown_html($list=array(),$name='',$class_name="",$selected_item='',$placeholderinput='') {
        ob_start();
        ?>
        <select id="wr_project_type" class="wr-select-cat <?php echo esc_attr($class_name);?>" name="<?php echo esc_attr($name);?>" >
            <option value="" selected hidden disabled><?php echo esc_attr($placeholderinput); ?></option>
            <?php if (!empty($list)) {
                foreach ($list as $key => $item) {
                    $selected = '';
                    if (!empty($selected_item) && $selected_item === $key) {
                        $selected = 'selected';
                    }
                ?>
                    <option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html($item); ?></option>
                <?php }
            } ?>
        </select>
        <?php
        echo ob_get_clean();
    }
    add_action( "workreap_custom_dropdown_html", "workreap_custom_dropdown_html",10,5);
}

/**
 * custom term tags
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists('workreap_term_tags_html')){
    function workreap_term_tags_html($post_id=0,$taxnomy_name='',$title='',$type='') {
        global $product;
        if( !empty($post_id) ){
            $terms = !empty($post_id) ? wp_get_post_terms( $post_id, $taxnomy_name ) : array();
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                ob_start();
                ?>
                <div class="wr-project-holder">
                    <?php if( !empty($title) ){?>
                        <div class="wr-project-title">
                            <h4><?php echo esc_html($title);?></h4>
                        </div>
                    <?php } ?>
                    <div class="wr-blogtags wr-skillstags">
                        <ul class="wr-tags_links">
                            <?php foreach ( $terms as $term ) {

                                $task_search_url    = '#';
                                if(is_singular('product')){
                                    $type   = !empty( $product->get_type() ) ? $product->get_type() : '';

                                    if( !empty($type) && $type == 'projects' ){
                                        $task_search_url    = workreap_get_page_uri('project_search_page');
                                    }

                                    if(!empty($task_search_url)) {
                                        $task_search_url = add_query_arg('skills[]', esc_attr($term->slug), $task_search_url);
                                    }
                                }
                                ?>
                                <li>
                                    <a href="<?php echo esc_attr($task_search_url);?>"><span class="wr-blog-tags"><?php echo esc_html($term->name);?></span></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <?php
                echo ob_get_clean();
            }
        }
    }
    add_action( "workreap_term_tags_html", "workreap_term_tags_html",10,4);
}

/**
 * user verification tag
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if( !function_exists('workreap_verification_tag_html')){
    function workreap_verification_tag_html($post_id=0) {
        if( !empty($post_id) ){
            $is_verified    	= !empty($post_id) ? get_post_meta( $post_id, '_is_verified',true) : '';
            if ( ! empty( $is_verified ) && $is_verified === 'yes'  ) {
                ob_start();
                ?>
                <i class="wr-icon-check-circle" <?php echo apply_filters('workreap_tooltip_attributes', 'verified_user');?>></i>
                <?php
                echo ob_get_clean();
            }
        }
    }
    add_action( "workreap_verification_tag_html", "workreap_verification_tag_html",10,4);
}


/**
 * Skills
 */
if (!function_exists('workreap_skills_filter_theme')) {
    function workreap_skills_filter_theme($skills = array())
    {
        $taxonomies = get_terms( array(
            'taxonomy' => 'skills',
            'hide_empty' => false
        ) );

        if ( !empty($taxonomies) ) :
            $output = '<div class="wr-aside-holder">';
                $output .= '<div class="wr-asidetitle collapsed" data-bs-toggle="collapse" data-bs-target="#skills-search" role="button" aria-expanded="false">';
                    $output .= '<h5>'.esc_html__('Skills', 'workreap').'</h5>';
                $output .= '</div>';
                $output .= '<div id="skills-search" class="collapse">';
                    $output .= '<div class="wr-filterselect" id="project_skill_search">';
                        $output .= '<ul class="wr-categoriesfilter wr-skillstermsfilter">';
                        foreach( $taxonomies as $category ) {
                            if( $category->parent == 0 ) {
                                $checked    = '';
                                if(!empty($skills) && is_array($skills) && in_array($category->slug, $skills)){
                                    $checked    = 'checked';
                                }
                                $output.= '<li><div class="wr-form-checkbox">';
                                    $output.= '<input class="form-check-input wr-form-check-input-sm" id="term_'. intval( $category->term_id ) .'" type="checkbox" name="skills[]" value="'. esc_attr( $category->slug ) .'" '.do_shortcode($checked).'><label class="form-check-label" for="term_'. intval( $category->term_id ) .'"><span>'. esc_html( $category->name ) .'</span></label>';
                                $output.='</div></li>';
                            }
                        }
                        $output.='</ul>';

                        if(count($taxonomies)>5){
                            $output.='<div class="show-more"> <a href="javascript:void(0);" class="wr-readmorebtn wr-show_more" data-show_more="'.esc_attr__('Show more', 'workreap').'" data-show_less="'.esc_attr__('Show less', 'workreap').'">'.esc_attr__('Show more', 'workreap').'</a></div>';
                        }
                    $output.='</div>';
                $output.='</div>';
            $output.='</div>';
            echo do_shortcode($output);
        endif;
    }
    add_action('workreap_skills_filter_theme', 'workreap_skills_filter_theme', 10, 1);
}

/**
 * Product tags
 */
if (!function_exists('workreap_product_tags_filter_theme')) {
    function workreap_product_tags_filter_theme($product_tag = array())
    {
        $taxonomies = get_terms( array(
            'taxonomy'      => 'product_tag',
            'hide_empty'    => false
        ) );

        if ( !empty($taxonomies) ) :
            $output = '<div class="wr-aside-holder">';
                $output .= '<div class="wr-asidetitle collapsed" data-bs-toggle="collapse" data-bs-target="#product_tag-search" role="button" aria-expanded="false">';
                    $output .= '<h5>'.esc_html__('Tags', 'workreap').'</h5>';
                $output .= '</div>';
                $output .= '<div id="product_tag-search" class="collapse">';
                    $output .= '<div class="wr-filterselect" id="project_skill_search">';
                        $output .= '<ul class="wr-categoriesfilter wr-skillstermsfilter">';
                        foreach( $taxonomies as $category ) {
                            if( $category->parent == 0 ) {
                                $checked    = '';
                                if(!empty($product_tag) && is_array($product_tag) && in_array($category->slug, $product_tag)){
                                    $checked    = 'checked';
                                }
                                $output.= '<li><div class="wr-form-checkbox">';
                                    $output.= '<input class="form-check-input wr-form-check-input-sm" id="term_'. intval( $category->term_id ) .'" type="checkbox" name="product_tag[]" value="'. esc_attr( $category->slug ) .'" '.do_shortcode($checked).'><label class="form-check-label" for="term_'. intval( $category->term_id ) .'"><span>'. esc_html( $category->name ) .'</span></label>';
                                $output.='</div></li>';
                            }
                        }
                        $output.='</ul>';

                        if(count($taxonomies)>5){
                            $output.='<div class="show-more"> <a href="javascript:void(0);" class="wr-readmorebtn wr-show_more" data-show_more="'.esc_attr__('Show more', 'workreap').'" data-show_less="'.esc_attr__('Show less', 'workreap').'">'.esc_attr__('Show more', 'workreap').'</a></div>';
                        }
                    $output.='</div>';
                $output.='</div>';
            $output.='</div>';
            echo do_shortcode($output);
        endif;
    }
    add_action('workreap_product_tags_filter_theme', 'workreap_product_tags_filter_theme', 10, 1);
}

/**
 * Expertise level
 */
if (!function_exists('workreap_expertise_level_filter_theme')) {
    function workreap_expertise_level_filter_theme($expertise_level = array())
    {
        $taxonomies = get_terms( array(
            'taxonomy' => 'expertise_level',
            'hide_empty' => false
        ) );

        if ( !empty($taxonomies) ) :
            $output = '<div class="wr-aside-holder">';
                $output .= '<div class="wr-asidetitle collapsed" data-bs-toggle="collapse" data-bs-target="#expertise-search" role="button" aria-expanded="false">';
                    $output .= '<h5>'.esc_html__('Expertise level', 'workreap').'</h5>';
                $output .= '</div>';
                $output .= '<div id="expertise-search" class="collapse">';
                    $output .= '<div class="wr-filterselect" id="project_expertise_level_search">';
                        $output .= '<ul class="wr-categoriesfilter wr-expertisetermsfilter">';
                        foreach( $taxonomies as $category ) {
                            if( $category->parent == 0 ) {
                                $checked    = '';
                                if(!empty($expertise_level) && is_array($expertise_level) && in_array($category->slug, $expertise_level)){
                                    $checked    = 'checked';
                                }
                                $output.= '<li><div class="wr-form-checkbox">';
                                $output.= '<input class="form-check-input wr-form-check-input-sm" id="term_'. intval( $category->term_id ) .'" type="checkbox" name="expertise_level[]" value="'. esc_attr( $category->slug ) .'" '.do_shortcode($checked).'><label class="form-check-label" for="term_'. intval( $category->term_id ) .'"><span>'. esc_html( $category->name ) .'</span></label>';
                                $output.='</div></li>';
                            }
                        }
                        $output.='</ul>';

                        if(count($taxonomies)>5){
                            $output.='<div class="show-more"> <a href="javascript:void(0);" class="wr-readmorebtn wr-show_more" data-show_more="'.esc_html__('Show more', 'workreap').'" data-show_less="'.esc_html__('Show less', 'workreap').'">'.esc_html__('Show more', 'workreap').'</a></div>';
                        }
                    $output.='</div>';
                $output.='</div>';
            $output.='</div>';
            echo do_shortcode($output);
        endif;
    }
    add_action('workreap_expertise_level_filter_theme', 'workreap_expertise_level_filter_theme', 10, 1);
}

/**
 * Languages
 */
if (!function_exists('workreap_languages_filter_theme')) {
    function workreap_languages_filter_theme($languages = array())
    {
        $taxonomies = get_terms( array(
            'taxonomy' => 'languages',
            'hide_empty' => false
        ) );

        if ( !empty($taxonomies) ) :
            $output = '<div class="wr-aside-holder">';
                $output .= '<div class="wr-asidetitle collapsed" data-bs-toggle="collapse" data-bs-target="#languages-search" role="button" aria-expanded="false">';
                    $output .= '<h5>'.esc_html__('Languages', 'workreap').'</h5>';
                $output .= '</div>';
                $output .= '<div id="languages-search" class="collapse">';
                    $output .= '<div class="wr-filterselect" id="project_languages_search">';
                        $output .= '<ul class="wr-categoriesfilter wr-languagetermsfilter">';
                        foreach( $taxonomies as $category ) {
                            if( $category->parent == 0 ) {
                                $checked    = '';
                                if(!empty($languages) && is_array($languages) && in_array($category->slug, $languages)){
                                    $checked    = 'checked';
                                }
                                $output.= '<li><div class="wr-form-checkbox">';
                                $output.= '<input class="form-check-input wr-form-check-input-sm" id="term_'. intval( $category->term_id ) .'" type="checkbox" name="languages[]" value="'. esc_attr( $category->slug ) .'" '.do_shortcode($checked).'><label class="form-check-label" for="term_'. intval( $category->term_id ) .'"><span>'. esc_html( $category->name ) .'</span></label>';
                                $output.='</div></li>';
                            }
                        }
                        $output.='</ul>';

                        if(count($taxonomies)>5){
                            $output.='<div class="show-more"> <a href="javascript:void(0);" class="wr-readmorebtn wr-show_more" data-show_more="'.esc_html__('Show more', 'workreap').'" data-show_less="'.esc_html__('Show less', 'workreap').'">'.esc_html__('Show more', 'workreap').'</a></div>';
                        }
                    $output.='</div>';
                $output.='</div>';
            $output.='</div>';
            echo do_shortcode($output);
        endif;
    }
    add_action('workreap_languages_filter_theme', 'workreap_languages_filter_theme', 10, 1);
}

/**
 * Tooltip tags
 */
if (!function_exists('workreap_tooltip_tags')) {
    function workreap_tooltip_tags($title='', $content="")
    {
        $timestamp  = mt_rand();
        ob_start();
        if(!empty($title)){
            ?>
            <a id="wr-tooltip<?php echo esc_attr($timestamp);?>" href="javascript:void(0);"  data-tippy-trigger="click" data-template="wr-services_content<?php echo esc_attr($timestamp);?>" data-tippy-interactive="true" data-tippy-placement="top-start"> <?php echo do_shortcode($title);?></a>
            <?php
        }

        if(!empty($content)){
            ?>
            <div id="wr-services_content<?php echo esc_attr($timestamp);?>" class="wr-tippytooltip d-none">
                <div class="wr-selecttagtippy wr-tooltip ">
                    <?php if(is_array($content) && count($content)>0){
                        ?>
                        <ul class="wr-posttag wr-posttagv2">
                            <?php foreach($content as $content_item){?>
                                <li>
                                    <a href="javascript:void(0);"><?php echo do_shortcode($content_item);?></a>
                                </li>
                            <?php }?>
                        </ul>
                        <?php
                    } else {
                        echo do_shortcode($content);
                    }?>

                </div>
            </div>
            <?php
            $script = 'tooltipTagsInit("#wr-tooltip'.esc_attr($timestamp).'")';
            wp_add_inline_script( 'workreap', $script, 'after' );
        }
        echo ob_get_clean();

    }
    add_action('workreap_tooltip_tags', 'workreap_tooltip_tags', 10, 2);
}

/**
 * Tooltip
 */
if (!function_exists('workreap_tooltip')) {
    function workreap_tooltip($title='', $key="",$content='')
    {
        if(!empty($key) || !empty($content)){
            $timestamp      = mt_rand();
            $content        = !empty($key) ? workreap_tooltip_array($key) : $content;
            if(!empty($title)){
                ob_start();
                ?>
                <span class="wr-tooltip-data" id="wr-tooltip<?php echo esc_attr($key.$timestamp);?>"  href="javascript:void(0);"  data-template="wr-services_content<?php echo esc_attr($timestamp);?>" data-tippy-interactive="true" data-tippy-placement="top-start" data-tippy-content="<?php echo do_shortcode($content);?>"> <?php echo do_shortcode($title);?></span>
                <?php
                echo ob_get_clean();
            }
        }
    }
    add_action('workreap_tooltip', 'workreap_tooltip', 10, 3);
}


/**
 * search type
 * @return slug
 */
if (!function_exists('workreap_tooltip_array')) {
	function workreap_tooltip_array($key=''){
		$list	= array(
			'verified_user'		=> esc_html__('Verified user','workreap'),
            'online_user'		=> esc_html__('Online','workreap'),
            'offline_user'		=> esc_html__('Offline','workreap'),
            'featured_package'	=> esc_html__('Featured package','workreap'),
            'featured_project'	=> esc_html__('Featured project','workreap'),
            'featured_task'	    => esc_html__('Featured task','workreap'),
		);
		$list	= apply_filters('workreap_filter_tooltip_array', $list );
		if(!empty($key)){
			$list	= !empty($list[$key]) ? $list[$key] : '';
		}
		return $list;
	}
}

/**
 * Safe logout
 * @return slug
 */
if (!function_exists('workreap_logout_redirect')) {
    add_action('wp_logout','workreap_logout_redirect',5);
    function workreap_logout_redirect(){
        wp_safe_redirect( home_url() );
        exit();
    }
}

/**
 * search type
 * @return slug
 */
if (!function_exists('workreap_tooltip_html')) {
	function workreap_tooltip_html($key=''){
        if(!empty($key)){
            $label			= workreap_tooltip_array($key);
            if(!empty($key)){
                $timestamp      = mt_rand();
                $datattribute	= 'data-class="wr-tooltip-data" id="wr-tooltip'.$timestamp.'" data-tippy-interactive="true" data-tippy-placement="top-start" data-tippy-content="'.esc_attr($label).'"';
                return $datattribute;
            }
        }
	}

    add_filter('workreap_tooltip_attributes', 'workreap_tooltip_html');
}


/**
 * List task v2
 */
if (!function_exists('workreap_listing_task_html_v2')) {
    function workreap_listing_task_html_v2($product_id=0)
    {
        if(!empty($product_id)){
            $product            = wc_get_product($product_id);
            $product_author_id  = get_post_field ('post_author', $product->get_id());
            $linked_profile_id  = get_user_meta($product_author_id, '_linked_profile', true);
            $post_country       = get_post_meta( $product->get_id(), '_country', true );
            $user_name          = workreap_get_username($linked_profile_id);
            $verified_user      = get_post_meta( $linked_profile_id, '_is_verified', true);
            $verified_user      = !empty($verified_user) ? $verified_user : '';
            $image              = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'full' );
            $product_rating     = !empty($product) ? $product->get_average_rating() : 0;
            $address            = apply_filters( 'workreap_user_address', $linked_profile_id );
            ob_start();
            ?>
            <div class="wr-topservice">
                <?php if(!empty($image[0])){?>
                    <figure class="wr-card__img">
                        <a href="<?php the_permalink();?>">
                            <img src="<?php echo esc_url($image[0])?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                        </a>
	                    <?php do_action( 'workreap_saved_item_theme', $product->get_id(),'','_saved_tasks' ); ?>
                    </figure>
                <?php } ?>
                <div class="wr-sevicesinfo">
                    <div class="wr-topservice__content">
                        <div class="wr-title-wrapper">
                            <div class="wr-card-title">
                                <?php if(!empty($user_name)) {?>
	                                <?php do_action('workreap_profile_image', $linked_profile_id,true,array('width' => 50, 'height' => 50));?>
                                    <?php } ?>
    	                            <?php do_action('workreap_service_featured_item_theme', $product);?>
                            </div>
                            <?php if( $product->get_name() ){?>
                                <h5><a href="<?php the_permalink();?>"><?php echo esc_html($product->get_name()); ?></a></h5>
                            <?php } ?>
                        </div>
                        <div class="wr-featureRating">
                            <?php do_action('workreap_service_rating_count_theme_v2', $product); ?>
                            <?php if( !empty($address) ){?>
                                <address>
                                    <i class="wr-icon-map-pin"></i><?php echo esc_html($address) ?>
                                </address>
                            <?php } ?>
                        </div>
                        <?php do_action('workreap_service_item_starting_price_theme', $product); ?>
                    </div>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_listing_task_html_v2', 'workreap_listing_task_html_v2');
}

/**
 * List task v2
 */
if (!function_exists('workreap_listing_task_html_v1')) {
    function workreap_listing_task_html_v1($product_id=0)
    {
        if(!empty($product_id)){
            $product            = wc_get_product($product_id);
            $product_author_id  = get_post_field ('post_author', $product->get_id());
            $linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','freelancers');
            $user_name          = workreap_get_username($linked_profile_id);
            ob_start();
            ?>
                <div class="wr-bestservice">
                    <?php
                        do_action('workreap_task_gallery_theme', $product);
                        do_action('workreap_service_featured_item_theme', $product);
                        do_action('workreap_service_gallery_count', $product);
                    ?>
                    <div class="wr-sevicesinfo">
                        <div class="wr-bestservice__content">
                            <?php do_action( 'workreap_profile_image_theme', $linked_profile_id );?>
                            <div class="wr-cardtitle">
                                <?php
                                    do_action( 'workreap_saved_item_theme', $product->get_id(),'','_saved_tasks' );

                                    if( !empty($user_name) ){?>
                                    <a href="<?php echo get_the_permalink($linked_profile_id); ?>"><?php echo esc_html($user_name); ?></a>
                                <?php } ?>
                                <h5><a href="<?php the_permalink();?>"><?php echo esc_html($product->get_name()); ?></a></h5>
                            </div>
                            <ul class="wr-blogviewdates wr-blogviewdatessm">
                                <?php
                                    do_action('workreap_service_rating_count_theme', $product);
                                    do_action('workreap_service_item_views_theme', $product);
                                ?>
                            </ul>
                            <?php do_action('workreap_service_item_starting_price_theme', $product); ?>
                        </div>
                    </div>
                </div>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_listing_task_html_v1', 'workreap_listing_task_html_v1');
}

/**
 * Display a notice on the freelancer dashboard if their account is pending approval.
 */
function workreap_display_pending_approval_notice() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        if ( in_array( 'freelancer', (array) $user->roles ) || in_array( 'freelancers', (array) $user->roles ) ) { //Check for both singular and plural
            $status = get_user_meta( $user->ID, 'freelancer_approval_status', true );
            if ( $status !== 'approved' ) {
                $message = '';
                if ( $status === 'pending') {
                    $message = esc_html__( 'Your account is pending admin approval.', 'workreap' );
                } elseif ( $status === 'rejected' ) {
                    $message = esc_html__( 'Your account application has been rejected. Please contact support for more information.', 'workreap' );
                } else {
                    // Generic message if status is neither pending nor approved (and not rejected explicitly)
                    $message = esc_html__( 'Your account requires attention. Please contact support.', 'workreap' );
                }

                echo '<div class="workreap-notice workreap-warning-notice" style="background-color: #fff3cd; border-left: 4px solid #ffeeba; padding: 12px; margin-bottom: 20px; color: #856404;">';
                echo '<p style="margin:0;">' . $message . '</p>';
                echo '</div>';
            }
        }
    }
}
// Attempt to hook into a common dashboard hook. This might need adjustment.
// Common dashboard hooks could be 'workreap_before_dashboard_content', 'wp_dashboard_setup', or theme/plugin specific ones.
// For now, let's assume a generic hook that might exist or can be added.
// This hook 'workreap_freelancer_dashboard_top_notice' is speculative.
add_action( 'workreap_freelancer_dashboard_top_notice', 'workreap_display_pending_approval_notice' );
// As a fallback, if no specific hook is found, this function can be called directly in dashboard template files.
// Example: if (function_exists('workreap_display_pending_approval_notice')) { workreap_display_pending_approval_notice(); }


/**
 * Freelancer hourly rate
 */
if (!function_exists('workreap_freelancer_hourly_rate_html')) {
    function workreap_freelancer_hourly_rate_html($post_id=0)
    {
        if(!empty($post_id)){
            $wr_hourly_rate     = get_post_meta($post_id, 'wr_hourly_rate', true);
            $wr_hourly_rate     = isset($wr_hourly_rate) ? $wr_hourly_rate : 0;
            ob_start();
            ?>
                <div class="wr-sidebarcontent">
                    <div class="wr-sidebarinnertitle">
                        <h6><?php esc_html_e('Starting from:','workreap');?></h6>
                        <h5><?php echo sprintf(esc_html__('%s /hr','workreap'),workreap_price_format($wr_hourly_rate,'return'));?></h5>
                    </div>
                </div>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_freelancer_hourly_rate_html', 'workreap_freelancer_hourly_rate_html');
}


/**
 * Project grid view
 */
if (!function_exists('workreap_project_grid_view')) {
    function workreap_project_grid_view($product=array())
    {
        if( !empty($product) ){
            $product_author_id  = get_post_field ('post_author', $product->get_id());
            $linked_profile_id  = workreap_get_linked_profile_id($product_author_id, '','employers');
            $user_name          = workreap_get_username($linked_profile_id);
            $is_verified    	= !empty($linked_profile_id) ? get_post_meta( $linked_profile_id, '_is_verified',true) : '';
            $project_price      = workreap_get_project_price($product->get_id());
            $project_meta       = get_post_meta( $product->get_id(), 'wr_project_meta',true );
            $project_meta       = !empty($project_meta) ? $project_meta : array();
            $project_type       = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
            ob_start();
            ?>
                <div class="wr-project-wrapper wr-otherproject">
                    <?php do_action( 'workreap_featured_item', $product,'featured_project' );?>
                    <div class="wr-project-box">
                        <div class="wr-verified-info">
                            <strong>
                                <?php echo esc_html($user_name);?>
                                <?php do_action( 'workreap_verification_tag_html', $linked_profile_id ); ?>
                            </strong>
                            <?php if($product->get_name()){?>
                                <h5><a href="<?php the_permalink();?>"><?php echo esc_html($product->get_name());?></a></h5>
                            <?php } ?>
                        </div>
                        <ul class="wr-blogviewdates wr-projectinfo-list">
                            <?php do_action( 'workreap_posted_date_html', $product );?>
    						<?php do_action( 'workreap_location_html', $product );?>
                            <?php do_action( 'workreap_texnomies_html_v2', $product->get_id(),'expertise_level','wr-icon-briefcase' );?>
                            <?php do_action( 'workreap_hiring_freelancer_html', $product );?>
                        </ul>
                        <div class="wr-project-price wr-project-price-two">
                            <?php if( !empty($project_type) ){?>
                                <?php do_action( 'workreap_project_type_text', $project_type );?>
                            <?php } ?>
                            <?php if( isset($project_price) ){?>
                                <h4><?php echo do_shortcode($project_price);?></h4>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('workreap_project_grid_view', 'workreap_project_grid_view');
}

if (!function_exists('workreap_project_grid_view_style_2')) {
	function workreap_project_grid_view_style_2($product=array())
	{
		if( !empty($product) ){
			$project_id     = $product->get_id();
			$product 		 = wc_get_product( $project_id );
			$author_id 		 = get_the_author_meta( 'ID' );
			$linked_profile  = workreap_get_linked_profile_id($author_id);
			$employer_title  = workreap_get_username( $linked_profile );

			$employer_avatar = apply_filters(
				'workreap_avatar_fallback', workreap_get_user_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100)
			);
			$post_date = get_the_date('M d, Y');

			$no_of_freelancers       = get_post_meta($project_id, 'no_of_freelancers', true);
			$experties = wp_get_post_terms($project_id, 'expertise_level');
			$location	= get_post_meta( $project_id, '_project_location',true );
			$location	= !empty($location) ? ($location) : '';
			$location_text  = workreap_project_location_type($location);
			ob_start();
			?>
            <div class="wr-projects-grid-item">
                <div class="wr-project-header-wrapper">
	                <?php do_action( 'workreap_featured_item', $product,'featured_project' );?>
                    <div class="wr-project-header">
                        <div class="wr-project-header-content">
                            <span class="wr-project-posted-date"><?php echo esc_html($post_date); ?></span>
                            <h3 class="wr-project-title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        </div>
						<?php do_action( 'workreap_project_saved_item', $project_id, '','_saved_projects' ,'icon' );?>
                    </div>
                    <div class="wr-project-price-wrapper">
						<?php do_action( 'workreap_project_type_tag', $project_id );?>
						<?php do_action( 'workreap_get_project_price_html', $project_id );?>
                    </div>
                    <div class="wr-project-description">
						<?php the_excerpt(); ?>
                    </div>
                    <ul class="wr-project-info">
						<?php if($location_text){ ?>
                            <li class="wr-location">
                                <i class="wr-icon-map-pin"></i>
                                <span><?php echo esc_html($location_text); ?></span>
                            </li>
						<?php }?>
						<?php if(isset($experties[0])){
							?>
                            <li class="wr-expertiese">
                                <i class="wr-icon-briefcase"></i>
                                <span><?php echo esc_html($experties[0]->name) ?></span>
                            </li>
						<?php }?>
						<?php if($no_of_freelancers){ ?>
                            <li class="wr-freelancers">
                                <i class="wr-icon-users"></i>
                                <span><?php echo sprintf(_n('%s freelancer', '%s freelancers', $no_of_freelancers, 'workreap'), $no_of_freelancers); ?></span>
                            </li>
						<?php }?>
                    </ul>
					<?php do_action( 'workreap_term_tags', $project_id,'skills','',3,'project' );?>
                </div>
                <div class="wr-projects-grid-footer">
                    <div class="wr-author-info">
						<?php if( !empty($employer_avatar) ){?>
                            <figure class="wr-author-iamge">
                                <img src="<?php echo esc_url($employer_avatar); ?>" alt="<?php echo esc_attr($employer_title); ?>">
                            </figure>
						<?php } ?>
                        <h5 class="wr-name"><?php echo esc_html($employer_title); ?></h5>
                    </div>
                    <a class="wr-btn wr-secondary-btn" href="<?php echo get_the_permalink(); ?>"><?php echo esc_html__('View Job', 'workreap'); ?></a>
                </div>
            </div>
			<?php
			echo ob_get_clean();
		}
	}
	add_action('workreap_project_grid_view_style_2', 'workreap_project_grid_view_style_2');
}

if( !function_exists('workreap_check_offline_payment_methods_enabled')){
    function workreap_check_offline_payment_methods_enabled() {
        $enabled_gateways   = WC()->payment_gateways->get_available_payment_gateways();
        $offline_methods    = array('cod', 'bacs','cheque'); // List of offline payment method IDs (Cash on Delivery, Bank Transfer)
        $offline_methods    = apply_filters('workreap_filter_offline_paymet_methods', $offline_methods);
        $enabled_offline_methods = array();
        if(!empty($enabled_gateways)){
            foreach ($enabled_gateways as $gateway_id => $gateway) {
                if (in_array($gateway_id, $offline_methods) && 'yes' === $gateway->enabled) {
                    $enabled_offline_methods[] = $gateway->title;
                }
            }
        }
        $return_type = false;
        if (!empty($enabled_offline_methods)) {
            $return_type = true;
        }
        return $return_type;
    }
    add_filter('workreap_check_offline_payment_methods_enabled', 'workreap_check_offline_payment_methods_enabled', 10);
}