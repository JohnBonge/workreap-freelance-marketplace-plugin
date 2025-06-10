<?php
/**
 * Mailchimp newsLetters widget.
 *
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/widgets
 */
 
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('Workreap_NewsLetters')) {
    class Workreap_NewsLetters extends WP_Widget {
        /**
         * Register widget with WordPress.
         */
        function __construct() {
            parent::__construct(
                    'workreap_newsletters' , // Base ID
                    esc_html__('News Letters | Workreap' , 'workreap') , // Name
                array (
                	'classname' 	=> '',
					'description' 	=> esc_html__('News Letters' , 'workreap') ,
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
			$title		    = (!empty($instance['title']) ) ? esc_html($instance['title']) : '';		
            $before			= ($args['before_widget']);
			$after	 		= ($args['after_widget']);                     
			echo do_shortcode($before);
            if(class_exists('Workreap_MailChimp')) { ?>
                <div class="tu-sidebar-newsletter">
                    <?php if(!empty($title)){?>
                        <h5><?php echo esc_html($title)?></h5>
                    <?php } ?>
                    <?php
                        $mailchimp = new \Workreap_MailChimp();
                        $mailchimp->workreap_mailchimp_form();
                    ?>
                </div>
            <?php }	
            
			echo do_shortcode( $after );
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance) {
            // outputs the options form on admin
			$title		    = (!empty($instance['title']) ) ? esc_html($instance['title']) : '';		
            ?>
			<p>
                <label for="<?php echo ( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title','workreap'); ?></label>
                <input class="widefat" id="<?php echo ( $this->get_field_id('title') ); ?>" name="<?php echo ( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>">
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
			$instance['title']		    = (!empty($new_instance['title']) ) ? sanitize_text_field($new_instance['title']) : '';

            return $instance;
        }
    }
}

//register widget
function workreap_register_NewsLetters_widgets() {
	register_widget( 'Workreap_NewsLetters' );
}
add_action( 'widgets_init', 'workreap_register_NewsLetters_widgets' );