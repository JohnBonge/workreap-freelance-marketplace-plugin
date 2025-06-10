<?php
/**
 * The apps download widgets functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 */

if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('Workreap_Apps')) {

    class Workreap_Apps extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                'workreap_apps' , // Base ID
                esc_html__('Get Mobile App | Workreap' , 'workreap') , // Name
                array (
                	'classname' 	=> 'wr-footerapp',
					'description' 	=> esc_html__('Workreap apps' , 'workreap') ,
				) // Args
            );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args , $instance) {
            // outputs the content of the widget
			global $post;

            extract($instance);

			$footer_logo 		= (!empty($instance['footer_logo']) ) ? ($instance['footer_logo']) : '';
            $footer_content 	= !empty($instance['footer_content']) ? ($instance['footer_content']) : '';
            $mac_img 		    = (!empty($instance['mac_img']) ) ? ($instance['mac_img']) : '';
            $mac_url 		    = (!empty($instance['mac_url']) ) ? ($instance['mac_url']) : '';
            $google_img 		= (!empty($instance['google_img']) ) ? ($instance['google_img']) : '';
            $google_url 		= (!empty($instance['google_url']) ) ? ($instance['google_url']) : '';

            $before		= ($args['before_widget']);
			$after	 	= ($args['after_widget']);

            echo do_shortcode($before);?>
            <div class="wr-footeritem">
                
                <?php if( !empty($footer_logo) ){?>
                    <figure>
                        <img class="wr-footer-logo" src="<?php echo esc_url($footer_logo);?>" alt="<?php echo esc_attr(get_bloginfo('name'));?>">
                    </figure>
                <?php } ?>

                <?php if( !empty($footer_content) ){?>
                    <div class="wr-main-description">
                        <p><?php echo esc_html($footer_content);?></p>
                    </div>
                <?php } ?>

                <?php if( !empty($mac_img) || !empty($google_img) ){?>
                    <ul class="wr-storeicons">
                        
                        <?php if( !empty($mac_img) ){?>
                            <li> <a href="<?php echo esc_url($mac_url);?>"><img src="<?php echo esc_url($mac_img);?>" alt="<?php esc_attr_e('App store','workreap');?>"></a> </li>
                        <?php } ?>

                        <?php if( !empty($google_img) ){?>
                            <li> <a href="<?php echo esc_url($google_url);?>"><img src="<?php echo esc_url($google_img);?>" alt="<?php esc_attr_e('Play store','workreap');?>"></a> </li>
                        <?php } ?>
                    </ul>
                <?php } ?>

            </div>
			<?php
			echo do_shortcode( $after );
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance) {
            // outputs the options form on admin
            $footer_logo 		= (!empty($instance['footer_logo']) ) ? ($instance['footer_logo']) : '';
            $footer_content 	= !empty($instance['footer_content']) ? ($instance['footer_content']) : '';

            $mac_img 		    = (!empty($instance['mac_img']) ) ? ($instance['mac_img']) : '';
            $mac_url 		    = (!empty($instance['mac_url']) ) ? ($instance['mac_url']) : '';
            $google_img 		= (!empty($instance['google_img']) ) ? ($instance['google_img']) : '';
            $google_url 		= (!empty($instance['google_url']) ) ? ($instance['google_url']) : '';

            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('footer_logo') ); ?>"><?php esc_html_e('Upload footer logo','workreap'); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('footer_logo') ); ?>" name="<?php echo esc_attr( $this->get_field_name('footer_logo') ); ?>" type="text" value="<?php echo esc_url($footer_logo); ?>">
                <span id="upload" class="button upload_button_wgt"><?php esc_html_e( 'Footer logo', 'workreap' ); ?><?php esc_html_e( 'Upload', 'workreap' ); ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('footer_content') ); ?>"><?php esc_html_e('Footer content','workreap'); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('footer_content') ); ?>" name="<?php echo esc_attr( $this->get_field_name('footer_content') ); ?>"><?php echo esc_html($footer_content); ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('google_img') ); ?>"><?php esc_html_e('Google play image URL','workreap'); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('google_img') ); ?>" name="<?php echo esc_attr( $this->get_field_name('google_img') ); ?>" type="text" value="<?php echo esc_url($google_img); ?>">
                <span id="upload" class="button upload_button_wgt"><?php esc_html_e( 'Logo', 'workreap' ); ?><?php esc_html_e( 'Upload', 'workreap' ); ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('google_url') ); ?>"><?php esc_html_e('Google app URL','workreap'); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('google_url') ); ?>" name="<?php echo esc_attr( $this->get_field_name('google_url') ); ?>" type="text" value="<?php echo esc_attr($google_url); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('mac_img') ); ?>"><?php esc_html_e('App stor image URL','workreap'); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('mac_img') ); ?>" name="<?php echo esc_attr( $this->get_field_name('mac_img') ); ?>" type="text" value="<?php echo esc_url($mac_img); ?>">
                <span id="upload" class="button upload_button_wgt"><?php esc_html_e( 'Logo', 'workreap' ); ?><?php esc_html_e( 'Upload', 'workreap' ); ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('mac_url') ); ?>"><?php esc_html_e('App store URL','workreap'); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('mac_url') ); ?>" name="<?php echo esc_attr( $this->get_field_name('mac_url') ); ?>" type="text" value="<?php echo esc_attr($mac_url); ?>">
            </p>

            <?php
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        public function update($new_instance , $old_instance) {
            // processes widget options to be saved
            $instance                   = $old_instance;
            $instance['footer_logo']	= (!empty($new_instance['footer_logo']) ) ? esc_url($new_instance['footer_logo']) : '';
            $instance['mac_img']	    = (!empty($new_instance['mac_img']) ) ? esc_url($new_instance['mac_img']) : '';
            $instance['mac_url']	    = (!empty($new_instance['mac_url']) ) ? esc_url($new_instance['mac_url']) : '';
            $instance['google_img']	    = (!empty($new_instance['google_img']) ) ? esc_url($new_instance['google_img']) : '';
            $instance['google_url']	    = (!empty($new_instance['google_url']) ) ? esc_url($new_instance['google_url']) : '';

            $instance['footer_content']	= (!empty($new_instance['footer_content']) ) ? sanitize_textarea_field($new_instance['footer_content']) : '';

            return $instance;
        }

    }

}

//register widget
function workreap_register_Apps_widgets() {
	register_widget( 'Workreap_Apps' );
}
add_action( 'widgets_init', 'workreap_register_Apps_widgets' );
