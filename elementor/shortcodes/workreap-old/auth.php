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

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists('Workreap_Authentication') ){
	class Workreap_Authentication extends Widget_Base {

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      base
		 */
		public function get_name() {
			return 'workreap_element_authentication';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      title
		 */
		public function get_title() {
			return esc_html__( 'Authentication', 'workreap' );
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      icon
		 */
		public function get_icon() {
			return 'eicon-lock-user';
		}

		/**
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      category of shortcode
		 */
		public function get_categories() {
			return [ 'workreap-ele' ];
		}

		/**
		 * Register category controls.
		 * @since    1.0.0
		 * @access   protected
		 */
		protected function register_controls() {

			//Content
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'workreap' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'form_type',
				[
					'label' => esc_html__( 'Form type', 'workreap' ),
					'type'  => Controls_Manager::SELECT,
					'default' => 'register',
					'label_block' => true,
					'options' => [
						'register' 	=> esc_html__('Register form', 'workreap'),
						'login' 	=> esc_html__('Login form', 'workreap'),
						'forgot' 	=> esc_html__('Forgot password form', 'workreap')
					],
				]
			);

			// logo on top of signup
			$this->add_control(
				'logo',
				[
				'type' => Controls_Manager::MEDIA,
				'label' => __( 'Choose logo', 'workreap' ),
				'description' => esc_html__('Add logo. leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'tagline',
				[
				'type'        => Controls_Manager::TEXTAREA,
				'label'       => esc_html__('Short description', 'workreap'),
				'description' => esc_html__('Add short description. leave it empty to hide.', 'workreap'),
				]
			);

			$this->add_control(
				'reset_pass_tagline',
				[
				'type'      	=> Controls_Manager::TEXTAREA,
				'label'     	=> esc_html__( 'Short description for reset password', 'workreap' ),
				'description'   => esc_html__( 'Add short description. leave it empty to hide.', 'workreap' ),
				'condition' => [
					'form_type' => 'forgot'
					],
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
		protected function render() {
			$settings 	  	= $this->get_settings_for_display();
			$form_type    	= !empty( $settings['form_type'] )        ? $settings['form_type']        : '';
			$bg_image	 	= !empty( $settings['bg_image']['url'] )  ? $settings['bg_image']['url']  : '';
			$logo	      	= !empty( $settings['logo']['url'] ) && attachment_url_to_postid($settings['logo']['url']) ? $settings['logo']['url'] : WORKREAP_DIRECTORY_URI.'/public/images/logo.png';
			$tagline	  	= !empty( $settings['tagline'] )          ? $settings['tagline']          : '';
			$user_id	  	= is_user_logged_in()  ? get_current_user_id() : 0 ;
			$user_type		= !empty($user_id) ? workreap_get_user_type($user_id) : '';

			?>
			<div class="wr-sc-shortcode wr-haslayout">
				<?php 
					if( !empty( $form_type ) && $form_type === 'register' ){
						echo do_shortcode('[workreap_registration logo="'.$logo.'" tagline="'.$tagline.'" ]');
					} elseif( !empty( $form_type ) && $form_type === 'login' ){
						echo do_shortcode('[workreap_signin logo="'.$logo.'" tagline="'.$tagline.'" ]');
					} elseif( !empty( $form_type ) && $form_type === 'forgot' ){
						$reset_pass_tagline = !empty( $settings['reset_pass_tagline'] ) ? $settings['reset_pass_tagline'] : '';
						echo do_shortcode('[workreap_forgot logo="'.$logo.'" tagline="'.$tagline.'" reset_pass_tagline="'.$reset_pass_tagline.'" ]');
					}
				?>
			</div>
		<?php
			if( !empty($user_type) &&( $user_type ==='freelancers' || $user_type === 'employers' ) ){
				$page_url	= workreap_dashboard_page_uri($user_type);
				?>
				<script>
					jQuery(document).ready(function(){
						window.location.href = "<?php echo esc_url_raw($page_url);?>";
					});
				</script>
				<?php
			}
		}

	}

	Plugin::instance()->widgets_manager->register( new Workreap_Authentication );
}