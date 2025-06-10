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

    if (!class_exists('WorkreapCategories')) {
        class WorkreapCategories extends Widget_Base
        {

        /**
         *
         * @since    1.0.0
         * @access   static
         * @var      base
         */
        public function get_name(){
            return 'workreap_element_categories';
        }

        /**
        *
        * @since    1.0.0
        * @access   static
        * @var      title
        */
        public function get_title(){
            return esc_html__('Product categories', 'workreap');
        }

        /**
        *
        * @since    1.0.0
        * @access   public
        * @var      icon
        */
        public function get_icon(){
            return 'eicon-theme-builder';
        }

        /**
        *
        * @since    1.0.0
        * @access   public
        * @var      category of shortcode
        */
        public function get_categories(){
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
                'layout_type',
                [
                    'type'      => Controls_Manager::SELECT2,
                    'label'     => esc_html__('layout type', 'workreap'),
                    'desc'      => esc_html__('Select layout type', 'workreap'),
                    'default'   => 'v1',
                    'options'   => [
                        'v1'   => esc_html__('V1', 'workreap'),
                        'v2'  => esc_html__('V2', 'workreap'),
                    ],
                ]
            );

	        $categories = workreap_elementor_get_taxonomies( 'product', 'product_cat', 0, '', 0 );

	        $this->add_control(
		        'hide_product_cat',
		        [
			        'type'        => Controls_Manager::SELECT2,
			        'label'       => esc_html__( 'Hide Category', 'workreap' ),
			        'options'     => $categories,
			        'multiple'    => true,
			        'label_block' 	=> true,
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
        protected function render(){
            global $workreap_settings;
            $settings       = $this->get_settings_for_display();
            $layout_type    = !empty($settings['layout_type']) ? $settings['layout_type'] : '';
           
            $flag 		    = rand(9999, 999999);
            $parent_cat     = !empty($_GET['parent_cat']) ? $_GET['parent_cat'] : "";
            $hide_cat       = !empty($workreap_settings['hide_product_cat']) ? $workreap_settings['hide_product_cat'] : array();
            $workreap_args   = array(
                'hide_empty'    => false,
                'parent'        => 0
            );
            if( !empty($hide_cat) ){
                $workreap_args['exclude']    = $hide_cat;
            }
            $categories         = get_terms('product_cat',$workreap_args);
            $parent_name        = "";
            $task_search_url    = workreap_get_page_uri('service_search_page');
            ?>
            <div class="container">
                <div class="row">
                    <?php if( !empty($layout_type) && $layout_type === 'v1' && !empty($categories)){?>
                        <div class="col-12 col-sm-4 col-md-3">
                            <aside>
                                <div class="wr-categoriestab">
                                    <h5><?php esc_html_e('Categories','workreap');?></h5>
                                    <ul>
                                        <?php $counter  = 0;
                                            foreach($categories as $category){
                                                $counter++;
                                                $active_class  = '';
                                                if($counter == 1 && empty($parent_cat) ){
                                                $parent_cat      = $category->term_id;
                                                $parent_name     =  $category->name;
                                                $active_class    = 'class="wr-active"';
                                                } else if(!empty($parent_cat) && $parent_cat == $category->term_id ){
                                                    $parent_name     =  $category->name;
                                                    $active_class    = 'class="wr-active"';
                                                }  
                                                
                                            ?>
                                            <li <?php echo do_shortcode($active_class);?>><a href="?parent_cat=<?php echo esc_attr($category->term_id);?>"><?php echo esc_html($category->name);?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </aside>
                        </div>
                        <?php
                            $children       = get_terms( 'product_cat', array( 'parent' => $parent_cat, 'hide_empty' => false ) );
                            $child_items    = !empty($children) && is_array($children) ? count($children) : 0;
                        ?>
                        <div class="col-12 col-sm-8 col-md-9">
                            <div class="wr-category">
                                <div class="wr-category_title">
                                    <h5><?php esc_html_e('Start from the best category','workreap');?></h5>
                                    <h3><?php echo sprintf(esc_html__('%s Items in "%s"','workreap'),$child_items,$parent_name);?></h3>
                                </div>
                                <?php if( !empty($children) ){?>
                                    <div class="wr-category_list">
                                        <ul>
                                            <?php 
                                            $parent_cat = get_term($parent_cat,'product_cat');
                                            if(!empty($parent_cat)) {
                                                $task_search_url = !empty($parent_cat->slug) ? add_query_arg('category', esc_attr($parent_cat->slug), $task_search_url) : '#';
                                            }
                                            foreach($children as $child ){
                                                $image          = "";
                                                $thumbnail_id   = get_term_meta( $child->term_id, 'thumbnail_id', true );
                                                $image          = wp_get_attachment_image_url( $thumbnail_id,'workreap_task_shortcode_thumbnail' );
                                                $task_cat_search_url    = '#';
                                                if(!empty($task_search_url)) {
                                                   $task_cat_search_url = !empty($child->slug) ? add_query_arg('sub_category', esc_attr($child->slug), $task_search_url) : '#';
                                                }
                                                ?>
                                                <li class="wr-category_item">
                                                    <?php if( !empty($image) ){?>
                                                        <figure class="wr-category_img">
                                                            <img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($child->name);?>">
                                                        </figure>
                                                    <?php } ?>
                                                    <div class="wr-category_info">
                                                        <h5><a href="<?php echo esc_url($task_cat_search_url);?>"><?php echo esc_html($child->name);?></a></h5>
                                                        <span><?php echo sprintf(esc_html__('%s listings','workreap'),$child->count);?></span>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else if(!empty($layout_type) && $layout_type === 'v2' && !empty($categories)){?>
                        <div class="col-12">
                            <div class="wr-category wr-subcategory">
                                <div class="wr-category_title">
                                    <h3><?php esc_html_e('Explore categories','workreap');?></h3>
                                </div>
                                <div class="wr-category_list">
                                    <ul>
                                        <?php
                                         foreach($categories as $category){
                                            $image         = "";
                                            $thumbnail_id   = get_term_meta( $category->term_id, 'thumbnail_id', true );
                                            $image          = wp_get_attachment_image_url( $thumbnail_id,'workreap_task_shortcode_thumbnail' );
                                            $task_cat_search_url    = '#';
                                            if(!empty($task_search_url)) {
                                                $task_cat_search_url = !empty($category->slug) ? add_query_arg('category', esc_attr($category->slug), $task_search_url) : '#';
                                            }
                                            $children       = get_terms( 'product_cat', array( 'parent' => $category->term_id, 'hide_empty' => false ) );

                                        ?>
                                            <li class="wr-category_item">
                                                <?php if( !empty($image) ){?>
                                                    <figure class="wr-category_img">
                                                        <img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($category->name);?>">
                                                    </figure>
                                                <?php } ?>
                                                <div class="wr-category_info">
                                                    <h6><a href="<?php echo esc_url($task_cat_search_url);?>"><?php echo esc_html($category->name);?></a></h6>
                                                    <?php
                                                        foreach($children as $child ){
                                                            $task_cat_child_url    = '';
                                                            if(!empty($task_cat_search_url)) {
                                                                $task_cat_child_url = !empty($child->slug) ? add_query_arg('sub_category', esc_attr($child->slug), $task_cat_search_url) : '#';
                                                            }
                                                        ?>
                                                        <a href="<?php echo esc_url($task_cat_child_url);?>"><?php echo esc_html($child->name);?></a>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php
            }
        }

        Plugin::instance()->widgets_manager->register(new WorkreapCategories);
    }
