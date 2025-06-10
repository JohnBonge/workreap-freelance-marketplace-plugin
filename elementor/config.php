<?php
/**
 * Elementor Page builder config
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap
 *
 */

// If this file is called directly, abort.
use Elementor\Plugin;

if ( ! defined( 'WPINC' ) ) {
	die('No kiddies please!');
}

if( !class_exists( 'Workreap_Elementor' ) ) {

	final class Workreap_Elementor{
		private static $_instance = null;
		
		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      string    workreap
		 */
        public function __construct() {
            add_action( 'elementor/init', array( $this, 'workreap_init_elementor_widgets' ) );
	        add_action( 'elementor/frontend/after_register_scripts', array($this, 'widget_scripts'));
			add_action( 'elementor/widgets/register', array( $this, 'workreap_elementor_shortcodes' ) );
        }
		
	
		/**
		 * class init
         * @since 1.1.0
         * @static
         * @var      string    workreap
         */
        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
		
		/**
		 * Add category
		 * @since    1.0.0
		 * @access   static
		 * @var      string    workreap
		 */
        public function workreap_init_elementor_widgets() {

        	Plugin::$instance->elements_manager->add_category(
		        'workreap-elements',
		        array(
			        'title' => esc_html__( 'Workreap 3.0', 'workreap' ),
			        'icon'  => 'eicon-settings',
		        )
	        );

        	Plugin::$instance->elements_manager->add_category(
		        'workreap-ele',
		        array(
			        'title' => esc_html__( 'Workreap', 'workreap' ),
			        'icon'  => 'eicon-settings',
		        )
	        );

        }

        /**
		 * Add widgets
		 * @since    1.0.0
		 * @access   static
		 * @var      string    workreap
		 */
        public function workreap_elementor_shortcodes() {
			$dir = WORKREAP_DIRECTORY;
			$scan_shortcodes = glob("$dir/elementor/shortcodes/*");
			foreach ($scan_shortcodes as $filename) {
				$file = pathinfo($filename);
				if( !empty( $file['filename'] ) && isset($file['extension']) && $file['extension'] === 'php' ){
					@include_once workreap_load_template( '/elementor/shortcodes/'.$file['filename'] );
				}
			}

	        $workreap_shortcodes = glob("$dir/elementor/shortcodes/workreap-old/*");
	        if( !empty($workreap_shortcodes) ){
		        foreach ($workreap_shortcodes as $file_name) {
			        $fileData = pathinfo($file_name);
			        if( !empty( $fileData['filename'] ) && isset($fileData['extension']) && $fileData['extension'] === 'php' ){
				        @include_once workreap_load_template( '/elementor/shortcodes/workreap-old/'.$fileData['filename'] );
			        }
		        }
	        }

			//Theme
			$dir = WORKREAP_ACTIVE_THEME_DIRECTORY;
			$scan_shortcodes = glob("$dir/extend/elementor/shortcodes/*");

			foreach ($scan_shortcodes as $filename) {
				if( !empty( $file['filename'] ) ){
					@include_once $filename;
				} 
			}
        }

		public function widget_scripts(){
			wp_register_script( 'workreap-elements',
				WORKREAP_DIRECTORY_URI . 'public/js/workreap-elements.js',
				array( 'jquery', 'elementor-frontend' ),
				WORKREAP_VERSION, true );
		}
		 
	}

}

//Init class
if ( did_action( 'elementor/loaded' ) ) {
    Workreap_Elementor::instance();
}