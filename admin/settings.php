<?php
/**
 * Custom settings for URL
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
if( !class_exists('WorkreapCustomSetting')){
    class WorkreapCustomSetting {

        function __construct() {	
        
            add_action( 'load-options-permalink.php', array($this,'workreap_save_settings') ); 
            add_action( 'admin_init', array($this,'workreap_setting_init') );
            add_action('init', array($this,'workreap_set_custom_rewrite_rule'));
        }

        function workreap_get_post_types(  ) {
            $list	= array(
                'freelancers'	=> esc_html__('Freelancers','workreap'),
            );
            $list 	= apply_filters('workreap_filter_get_post_types',$list);
            return $list;
        }
        
        function workreap_set_custom_rewrite_rule() {
            global $wp_rewrite;
            $settings 				= $this->workreap_get_post_types();
            $workreap_rewrit_url     = get_option( 'workreap_rewrit_url' );
            if( !empty( $settings ) ){
                foreach ( $settings as $post_type => $name ) {
                    $db_slug	= !empty($workreap_rewrit_url[$post_type]) ? $workreap_rewrit_url[$post_type] : '';
                    if(!empty( $post_type ) && !empty($db_slug) ){
                        $args = get_post_type_object($post_type);
                        if( !empty( $args ) ){
                            $args->rewrite["slug"] = $db_slug;
                            register_post_type($args->name, $args);
                        }
                    }
                }
            }
            $wp_rewrite->flush_rules();
        } 

        function workreap_save_settings() {
            if( isset( $_POST['workreap_rewrit_url'] ) ) {
                update_option( 'workreap_rewrit_url', ( $_POST['workreap_rewrit_url'] ) );
            }
        }

        function workreap_settings_field_callback($arg=array()) {
            $workreap_rewrit_url     = get_option( 'workreap_rewrit_url' );	
            $name                   = !empty($arg['name']) ? $arg['name'] : '';
            $value                  = !empty($workreap_rewrit_url[$name]) ? $workreap_rewrit_url[$name] : '';
            echo do_shortcode('<input type="text" value="' . esc_attr( $value ) . '" name="workreap_rewrit_url['.do_shortcode($name).']" id="wr-'.esc_attr($name).'" class="regular-text" />');
        }

        function workreap_custom_setting_section_form(){}
        
        function workreap_setting_init(){
            add_settings_section(
                'workreap_custom_setting_section',
                esc_html__('Rewrite Workreap post type URL(s)','workreap'),
                array($this,'workreap_custom_setting_section_form'),
                'permalink'
            );
            $post_types = $this->workreap_get_post_types();
            if( !empty($post_types) ){
                foreach($post_types as $key => $value ){
                    add_settings_field(
                        $key, 
                        $value, 
                        array($this,'workreap_settings_field_callback'), 
                        'permalink', 
                        'workreap_custom_setting_section',
                        array(
                            'name'      => $key
                        )
                    );
                }
            }
            
        }
    }
    new WorkreapCustomSetting();
}