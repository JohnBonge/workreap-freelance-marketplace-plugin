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

if (!class_exists('Workreap_Contact_Information')) {

    class Workreap_Contact_Information extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                'workreap_contact_information' , // Base ID
                esc_html__('Contact information | Workreap' , 'workreap') , // Name
                array (
                	'classname' 	=> 'wr-footercontact-info',
					'description' 	=> esc_html__('Workreap contact information' , 'workreap') ,
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
            $title              = !empty($instance['title']) ? ($instance['title']) : '';
            $phone_number       = !empty($instance['phone_number']) ? ($instance['phone_number']) : '';
            $phone_call_time    = !empty($instance['phone_call_time']) ? ($instance['phone_call_time']) : '';
            $email_address      = !empty($instance['email_address']) ? ($instance['email_address']) : '';
            $fax_number         = !empty($instance['fax_number']) ? ($instance['fax_number']) : '';
            $whatsapp_number    = !empty($instance['whatsapp_number']) ? ($instance['whatsapp_number']) : '';
            $whatsapp_call_time = !empty($instance['whatsapp_call_time']) ? ($instance['whatsapp_call_time']) : '';
            $before		        = ($args['before_widget']);
			$after	 	        = ($args['after_widget']);

            echo do_shortcode($before);?>
            <div class="wr-fwidget">
                <?php if( !empty($title) ){?>
                    <div class="wr-fwidget_title">
                        <h5><?php echo esc_html($title);?></h5>
                    </div>
                <?php } ?>
                <?php if( !empty($phone_number) || !empty($phone_call_time) || !empty($email_address) || !empty($fax_number) || !empty($whatsapp_number) || !empty($whatsapp_call_time) ){?>
                    <ul class="wr-fwidget_contact_list">
                        <?php if( !empty($phone_number) || !empty($phone_call_time) ){?>
                            <li>
                                <i class="wr-icon-phone-call"></i>
                                <?php if( !empty($phone_number) ){?>
                                    <a href="tel:<?php echo do_shortcode($phone_number);?>"><?php echo esc_html($phone_number);?></a>
                                <?php } ?>
                                <?php if( !empty($phone_call_time) ){?>
                                    <span><?php echo esc_html($phone_call_time);?></span>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        <?php if( !empty($email_address) ){?>
                            <li>
                                <i class="wr-icon-mail"></i>
                                <?php if( !empty($email_address) ){?>
                                    <a href="mailto:<?php echo do_shortcode($email_address);?>"><?php echo esc_html($email_address);?></a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        <?php if( !empty($fax_number) ){?>
                            <li>
                                <i class="wr-icon-printer"></i>
                                <?php if( !empty($fax_number) ){?>
                                    <a href="fax:<?php echo do_shortcode($fax_number);?>"><?php echo esc_html($fax_number);?></a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        <?php if( !empty($whatsapp_number) || !empty($whatsapp_call_time) ){?>
                            <li>
    <i class="fab fa-whatsapp"></i>
    <?php if( !empty($whatsapp_number) ){?>
        <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $whatsapp_number); ?>" target="_blank">
            <?php echo esc_html($whatsapp_number); ?>
        </a>
    <?php } ?>
    <?php if( !empty($whatsapp_call_time) ){?>
        <span><?php echo esc_html($whatsapp_call_time); ?></span>
    <?php } ?>
</li>

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
            $title              = !empty($instance['title']) ? ($instance['title']) : '';
            $fax_number         = !empty($instance['fax_number']) ? ($instance['fax_number']) : '';
            $phone_number       = !empty($instance['phone_number']) ? ($instance['phone_number']) : '';
            $email_address      = !empty($instance['email_address']) ? ($instance['email_address']) : '';
            $whatsapp_number    = !empty($instance['whatsapp_number']) ? ($instance['whatsapp_number']) : '';
            $phone_call_time   = !empty($instance['phone_call_time']) ? ($instance['phone_call_time']) : '';
            $whatsapp_call_time = !empty($instance['whatsapp_call_time']) ? ($instance['whatsapp_call_time']) : '';
            ?>
            
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title','workreap'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('phone_number') ); ?>"><?php esc_html_e('Phone number','workreap'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('phone_number') ); ?>" type="text" value="<?php echo esc_attr($phone_number); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('phone_call_time') ); ?>"><?php esc_html_e('Phone call time content','workreap'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('phone_call_time') ); ?>" type="text" value="<?php echo esc_attr($phone_call_time); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('email_address') ); ?>"><?php esc_html_e('Email address','workreap'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('email_address') ); ?>" type="text" value="<?php echo esc_attr($email_address); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('fax_number') ); ?>"><?php esc_html_e('Fax number','workreap'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('fax_number') ); ?>" type="text" value="<?php echo esc_attr($fax_number); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('whatsapp_number') ); ?>"><?php esc_html_e('Whatsapp number','workreap'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('whatsapp_number') ); ?>" type="text" value="<?php echo esc_attr($whatsapp_number); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('whatsapp_call_time') ); ?>"><?php esc_html_e('Whatsapp call time content','workreap'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('whatsapp_call_time') ); ?>" type="text" value="<?php echo esc_attr($whatsapp_call_time); ?>">
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
            $instance                           = $old_instance;
            $instance['phone_number']           = !empty($new_instance['phone_number']) ? sanitize_text_field($new_instance['phone_number']) : '';
            $instance['phone_call_time']        = !empty($new_instance['phone_call_time']) ? sanitize_text_field($new_instance['phone_call_time']) : '';
            $instance['email_address']          = !empty($new_instance['email_address']) && sanitize_email($new_instance['email_address']) ? ($new_instance['email_address']) : '';
            $instance['fax_number']             = !empty($new_instance['fax_number']) ? sanitize_text_field($new_instance['fax_number']) : '';
            $instance['whatsapp_number']       = !empty($new_instance['whatsapp_number']) ? sanitize_text_field($new_instance['whatsapp_number']) : '';
            $instance['whatsapp_call_time']     = !empty($new_instance['whatsapp_call_time']) ? sanitize_text_field($new_instance['whatsapp_call_time']) : '';
            $instance['title']                  = !empty($new_instance['title']) ? ($new_instance['title']) : '';
            return $instance;
        }
    }
}

//register widget
function workreap_register_contact_info_widgets() {
	register_widget( 'Workreap_Contact_Information' );
}
add_action( 'widgets_init', 'workreap_register_contact_info_widgets' );