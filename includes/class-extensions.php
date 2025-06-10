<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Workreap_Extension' ) ) {

	class Workreap_Extension {

		private static $_instance = null;

		private function __construct() {
			//Register Menu
			add_action( 'admin_menu', array( $this, 'extensions_submenu' ) );
			//Ajax Callbacks
			add_action( 'wp_ajax_workreap_extension_action', array( $this, 'extension_action' ) );
		}

		public static function instance() {
			if ( ! isset( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function extensions_submenu() {
			add_submenu_page(
				'edit.php?post_type=freelancers',
				esc_html__( 'Extensions', 'workreap' ),
				esc_html__( 'Extensions', 'workreap' ),
				'manage_options',
				'wr_extensions',
				array( $this, 'extensions_markup' )
			);
		}

		public function extension_list() {

			$list = array(
				'workreap-hourly-addon'     => array(
					'name'          => esc_html__( 'Hourly Addon', 'workreap' ),
					'description'   => esc_html__( 'Allow to create an customised offer to the buyers.', 'workreap' ),
					'slug'          => 'workreap-hourly-addon/workreap-hourly-addon.php',
					'preview_image' => WORKREAP_DIRECTORY_URI . 'admin/images/hourly-addon.jpg',
					'is_pro'        => false,
					'price'         => '$29',
					'tag'           => esc_html__('Free','Workreap'),
					'url'          => 'https://themeforest.net/item/workreap-freelance-marketplace-wordpress-theme/23712454'
				),
				'workreap-customized-offer' => array(
					'name'          => esc_html__( 'Custom Offer', 'workreap' ),
					'description'   => esc_html__( 'Allow the employers to post the hourly based projects.', 'workreap' ),
					'slug'          => 'workreap-customized-offer/workreap-customized-offer.php',
					'preview_image' => WORKREAP_DIRECTORY_URI . 'admin/images/custom-offer-addon.jpg',
					'is_pro'        => false,
					'price'         => '$29',
					'tag'           => esc_html__('Free','Workreap'),
					'url'          => 'https://themeforest.net/item/workreap-freelance-marketplace-wordpress-theme/23712454'
				),
				'workreap-badges'    => array(
					'name'          => esc_html__( 'Badges Addon', 'workreap' ),
					'description'   => esc_html__( 'Enable freelancers to earn badges as a recognition of their achievements.', 'workreap' ),
					'slug'          => 'workreap-badges/workreap-badges.php',
					'preview_image' => WORKREAP_DIRECTORY_URI . 'admin/images/badges-addon.jpg',
					'is_pro'        => true,
					'price'         => '$19',
					'tag'           => esc_html__('Pro','Workreap'),
					'url'          => 'https://codecanyon.net/item/workreap-social-connect-facebook-linkedin-signin/53785568'
				),
				'workreap-social-login'    => array(
					'name'          => esc_html__( 'Social Login', 'workreap' ),
					'description'   => esc_html__( 'Allow the freelancer and employer to login via facebook or linkedIn.', 'workreap' ),
					'slug'          => 'workreap-social-login/init.php',
					'preview_image' => WORKREAP_DIRECTORY_URI . 'admin/images/social-login.jpg',
					'is_pro'        => true,
					'price'         => '$19',
					'tag'           => esc_html__('Pro','Workreap'),
					'url'          => 'https://codecanyon.net/item/workreap-achievement-a-badges-extension-for-workreap-theme/53769848'
				)
			);

		    return apply_filters('workreap_extension_list', $list );

		}

		public function extensions_markup() {
		    $extensions = $this->extension_list();
			?>
            <div class="wr-extensions-wrapper">
                <div class="wr-extensions-header">
                    <div class="wr-extensions-header-left">
                        <h2 class="wr-title"><?php echo esc_html__( 'Workreap Extensions', 'workreap' ); ?></h2>
                        <p class="wr-description"><?php echo esc_html__( 'Expand the theme’s functionality with additional features.', 'workreap' ); ?></p>
                    </div>
                    <div class="wr-extensions-header-right">
                        <a href="https://demos.codingeasel.com/demo/workreap/" class="wr-btn btn-view-demo-btn"><?php echo esc_html__( 'View Demos', 'workreap' ); ?></a>
                        <a href="https://demos.codingeasel.com/docs/workreap/" class="wr-btn btn-read-doc-btn"><?php echo esc_html__( 'Documentation', 'workreap' ); ?></a>
                    </div>
                </div>
                <div class="wr-extensions-body">
                    <div class="wr-extension-items-grid">
                        <?php
                            foreach ($extensions as $ex => $extension){
                                ?>
                                <div class="wr-extension-item wr-extension-item-<?php echo esc_attr($ex); ?> wr-extension-item-type-<?php echo esc_attr($extension['is_pro'] ? 'pro' : 'free'); ?>">
                                    <div class="wr-extension-image-wrapper">
                                        <img src="<?php echo esc_url($extension['preview_image']); ?>" alt="<?php echo esc_attr($ex); ?>"/>
                                    </div>
                                    <div class="wr-extension-content">
                                        <h5 class="wr-title"><?php echo esc_html($extension['name']) ?> <span class="wr-extension-tag"><?php echo esc_html($extension['tag']); ?></span></h5>
                                        <p class="wr-description"><?php echo esc_html($extension['description']) ?></p>
                                    </div>
                                    <div class="wr-extension-action">
                                        <?php
                                            $btn_text = '';
                                            $btn_action = '';
                                            $btn_class = '';
                                            $btn_url = '#';
                                            $extension_slug = $extension['slug'];
                                            if(!$extension['is_pro']){
                                                if(is_plugin_active($extension_slug)){
	                                                $btn_text = esc_html__('Deactivate', 'workreap');
	                                                $btn_action = 'deactivate_plugin';
	                                                $btn_class = 'wr-btn-deactivate';
                                                }else if ( file_exists( WP_PLUGIN_DIR . "/" . $extension_slug ) ) {
		                                            $btn_text = esc_html__('Activate', 'workreap');
	                                                $btn_action = 'activate_plugin';
	                                                $btn_class = 'wr-btn-activate';
                                                }else{
	                                                $btn_text = esc_html__('Install', 'workreap');
	                                                $btn_action = 'install_plugin';
	                                                $btn_class = 'wr-btn-install';
                                                }
                                            }else{
	                                            if(is_plugin_active($extension_slug)){
		                                            $btn_text = esc_html__('Deactivate', 'workreap');
		                                            $btn_action = 'deactivate_plugin';
		                                            $btn_class = 'wr-btn-deactivate';
                                                }else if ( file_exists( WP_PLUGIN_DIR . "/" . $extension_slug ) ) {
		                                            $btn_text = esc_html__('Activate', 'workreap');
		                                            $btn_action = 'activate_plugin';
		                                            $btn_class = 'wr-btn-activate';
	                                            }else{
		                                            $btn_text = esc_html__('Buy Now', 'workreap');
		                                            $btn_action = 'buy';
		                                            $btn_url = $extension['url'];
		                                            $btn_class = 'wr-btn-buy';
	                                            }
                                            }
                                        ?>
                                        <a id="wr-extension-action-btn" href="<?php echo esc_url($btn_url); ?>" class="wr-extension-plugin-btn <?php echo esc_attr($btn_class);?>" data-action="<?php echo esc_attr( $btn_action ); ?>" data-name="<?php echo esc_attr( $ex ); ?>" data-slug="<?php echo esc_attr( $extension['slug'] ); ?>">
                                            <?php echo esc_html($btn_text); ?>
                                        </a>
                                        <a href="<?php echo esc_url($extension['url']); ?>" target="_blank" class="wr-extension-view"><?php echo esc_html__('View Demo','workreap'); ?></a>
                                    </div>
                                </div>
                            <?php }
                        ?>
                    </div>
                </div>
            </div>
		<?php }

		public function extension_action() {

			if ( ! check_ajax_referer( 'ajax_nonce', 'nonce' ) ) {
				wp_send_json_error( array( 'message' => __( 'Something went wrong with nonce!', 'workreap' ) ) );
			}

			if ( ! current_user_can( 'activate_plugins' ) && ! current_user_can( 'install_plugins' ) ) {
				wp_send_json_error( array( 'message' => __( 'you don\'t have access to perform this action!', 'workreap' ) ) );
			}

			$name = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$slug = ! empty( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : '';
			$type = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

			if ( $type === 'deactivate_plugin' ) {

				deactivate_plugins( $slug );

				if ( is_plugin_active( $slug ) ) {
					wp_send_json_error( array( 'message' => __( 'Failed to deactivate the plugin', 'workreap' ) ) );
				}

			} else if ( $type === 'activate_plugin' ) {

				activate_plugin( $slug );

				if ( ! is_plugin_active( $slug ) ) {
					wp_send_json_error( array( 'message' => __( 'Failed to activate the plugin', 'workreap' ) ) );
				}

			} else if ( $type === 'install_plugin' ) {

				include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				include_once ABSPATH . 'wp-admin/includes/file.php';

				$upgrader = new Plugin_Upgrader();

				$plugin_url = get_template_directory() . "/inc/plugins/$name.zip";

				if ( ! file_exists( $plugin_url ) ) {
					wp_send_json_error( array( 'message' => __( 'Plugin does not exists', 'workreap' ) ) );
				}

				$install = $upgrader->install( $plugin_url );

				if ( ! $install ) {
					wp_send_json_error( array( 'message' => __( 'Failed to install the plugin', 'workreap' ) ) );
				}

				activate_plugin( $slug );

				if ( ! is_plugin_active( $slug ) ) {
					wp_send_json_error( array( 'message' => __( 'Failed to activate the plugin', 'workreap' ) ) );
				}

			}

			wp_send_json_success( array( 'message' => __( 'Setting update successfully!', 'workreap' ) ) );

		}

	}

	Workreap_Extension::instance();

}
