<?php

/**
 * Class 'Workreap_Dispute' defines the cusotm post type
 * 
 * @package     Workreap
 * @subpackage  Workreap/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

if (!class_exists('Workreap_Dispute')) {

    class Workreap_Dispute {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_directory_type'));
            add_action('add_meta_boxes', array(&$this, 'workreap_dispute_detail'), 10, 2);
        }

        /**
         * @Init Post Type
         * @return {post}
         */
        public function init_directory_type() {
            $this->prepare_post_type();
        }

        /**
         * @Prepare Post Type Category
         * @return post type
         */
        public function prepare_post_type() {            
            $labels = array(
                'name'				=> esc_html__('Disputes', 'workreap'),
                'all_items' 		=> esc_html__('Disputes', 'workreap'),
                'singular_name' 	=> esc_html__('Disputes', 'workreap'),
                'add_new' 			=> esc_html__('Add dispute', 'workreap'),
                'add_new_item' 		=> esc_html__('Add new dispute', 'workreap'),
                'edit' 				=> esc_html__('Edit', 'workreap'),
                'edit_item' 		=> esc_html__('Edit dispute', 'workreap'),
                'new_item' 			=> esc_html__('New dispute', 'workreap'),
                'view' 				=> esc_html__('View dispute', 'workreap'),
                'view_item' 		=> esc_html__('View dispute', 'workreap'),
                'search_items' 		=> esc_html__('Search dispute', 'workreap'),
                'not_found' 		=> esc_html__('No dispute found', 'workreap'),
                'not_found_in_trash'=> esc_html__('No dispute found in trash', 'workreap'),
                'parent' 			=> esc_html__('Parent dispute', 'workreap'),
            );
			
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new Dispute ', 'workreap'),
                'public' 				=> false,
                'supports' 				=> array('title','editor', 'comments'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> false,
                'exclude_from_search' 	=> true,
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'dispute', 'with_front' => true),
                'show_in_menu' 			=> 'edit.php?post_type=freelancers',
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array('create_posts' => false)
            );
			
            register_post_type('disputes', $args);     
        }

        /**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function workreap_dispute_detail($post_type, $post) {
            $user_id        = get_post_meta($post->ID, '_send_by', true);
            $linked_profile = workreap_get_linked_profile_id( $user_id );
            
            if(empty($linked_profile)) {return;}

            add_meta_box(
                'linked_profile', esc_html__('Linked details', 'workreap'), array(&$this, 'workreap_dispute_detail_meta'), 'disputes', 'side', 'high'
            );

        }
        
        /**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function workreap_dispute_detail_meta($post) {
            $_dispute_key           = get_post_meta($post->ID, '_dispute_key', true);
            $task_id                = get_post_meta($post->ID, '_task_id', true);
            $disputed_order_id      = get_post_meta($post->ID, '_dispute_order', true);
            $freelancer_id              = get_post_meta($post->ID, '_freelancer_id', true);
            $post_type              = get_post_type($disputed_order_id);
            $post_status            = get_post_status( $post->ID );
            $title                      = esc_html__('View task order', 'workreap');
            $employer_id                   = get_post_field('post_author', $post->ID);
            $linked_employer_profile	= workreap_get_linked_profile_id($freelancer_id);
            $linked_freelancer_profile	= workreap_get_linked_profile_id($employer_id);
            $proj_serv_id               = !empty($service_order_id) ? $service_order_id : '';
            ?>
			<ul class="review-info">
                <li> <?php echo esc_html($_dispute_key);?></li>
                <li>
                    <span class="push-right">
                        <a target="_blank" href="<?php echo esc_url(get_edit_post_link( $disputed_order_id ));?>"><?php echo esc_html($title); ?></a>
                    </span>
                </li>
                <li>
                    <span class="push-right">
                        <a target="_blank" href="<?php echo esc_url(get_edit_post_link($linked_freelancer_profile));?>"><?php esc_html_e('View employer profile', 'workreap'); ?></a>
                    </span>
                </li>
                <li>
                    <span class="push-right">
                        <a target="_blank" href="<?php echo esc_url(get_the_permalink( $linked_employer_profile ));?>"><?php esc_html_e('View freelancer profile', 'workreap'); ?></a>
                    </span>
                </li>
                
			</ul>
            
			<?php
        }
    
    }

    new Workreap_Dispute();
}