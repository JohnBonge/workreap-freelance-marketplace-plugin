<?php
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

if ( ! did_action( 'elementor/loaded' ) ) {
	return;
}

if(!class_exists('Workreap_Template_Library')){

	class Workreap_Template_Library{

		private static $instance = null;
		private static $source;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->init();
			}

			return self::$instance;
		}

		public function init() {
			add_action( 'elementor/editor/after_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
			add_action( 'elementor/ajax/register_actions', array( __CLASS__, 'register_actions' ) );
			add_action( 'elementor/editor/footer', array( __CLASS__, 'print_template' ) );
		}

		public static function enqueue_assets(){
			wp_enqueue_style( 'workreap-editor', WORKREAP_DIRECTORY_URI . 'admin/css/template-library.css', array(), WORKREAP_VERSION,'all' );
			wp_enqueue_script( 'workreap-editor', WORKREAP_DIRECTORY_URI . 'admin/js/template-library.js', array('jquery'), WORKREAP_VERSION,true );
			wp_localize_script( 'workreap-editor', 'WorkreapLibraryArgs',
				array(
					'wr_icon' => WORKREAP_DIRECTORY_URI.'/public/images/wp-icon-workreap.png',
				)
			);
		}

		public static function print_template(){
			workreap_get_template_part('template', 'library');
		}

		public static function register_actions( Ajax $ajax ) {

			$ajax->register_ajax_action( 'get_workreap_templates', function ( $data ) {
					if ( ! current_user_can( 'edit_posts' ) ) {
						throw new Exception( 'Access Denied' );
					}
					if ( ! empty( $data['editor_post_id'] ) ) {
						$editor_post_id = absint( $data['editor_post_id'] );
						if ( ! get_post( $editor_post_id ) ) {
							throw new Exception( __( 'Post not found.', 'workreap' ) );
						}

						Plugin::instance()->db->switch_to_post( $editor_post_id );
					}
					return self::get_library_data( $data );
				}
			);

			$ajax->register_ajax_action(
				'import_workreap_template', function ( $data ) {

					if ( ! current_user_can( 'edit_posts' ) ) {
						throw new Exception( 'Access Denied' );
					}
					if ( ! empty( $data['editor_post_id'] ) ) {
						$editor_post_id = absint( $data['editor_post_id'] );
						if ( ! get_post( $editor_post_id ) ) {
							throw new Exception( __( 'Template not found', 'workreap' ) );
						}
						Plugin::instance()->db->switch_to_post( $editor_post_id );
					}
					if ( empty( $data['template_id'] ) ) {
						throw new Exception( __( 'Template id missing', 'workreap' ) );
					}
					return self::get_template_data( $data );
				}
			);

		}
		
		public static function get_library_data( array $args ) {
			$source = self::get_source();
			if ( ! empty( $args['sync'] ) ) {
				Workreap_Library_Source::get_library_data( true );
			}
			return array(
				'templates' => $source->get_items(),
				'tags'      => $source->get_tags(),
				'type_tags' => $source->get_type_tags()
			);
		}
		
		public static function get_source() {
			if ( is_null( self::$source ) ) {
				self::$source = new Workreap_Library_Source();
			}
			return self::$source;
		}

		public static function get_template_data( array $args ) {
			$source = self::get_source();

			return $source->get_data( $args );
		}
	}

	Workreap_Template_Library::instance();
}