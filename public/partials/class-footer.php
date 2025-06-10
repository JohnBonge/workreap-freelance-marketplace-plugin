<?php
/**
 * The override theme header
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */

if (!class_exists('WorkreapFooter')) {

    class WorkreapFooter {

        function __construct() {
            add_action( 'get_footer', array(&$this, 'workreap_do_process_footer'), 5, 2 );
        }

        /**
         * @Prepare footer for admin
         * @return {}
         * @author amentotech
         */
        public function workreap_do_process_footer($name, $args){
	        global $workreap_settings;
	        $user_id	  	= is_user_logged_in()  ? get_current_user_id() : 0 ;
	        $user_type		= !empty($user_id) ? workreap_get_user_type($user_id) : '';
	        $header_type		= !empty($workreap_settings['header_type_after_login']) ? $workreap_settings['header_type_after_login'] : '';
            if(is_page_template('templates/admin-dashboard.php') || ( !empty($user_type) && ( $user_type ==='freelancers' || $user_type === 'employers' ) && ($header_type === 'workreap-sidebar' && is_workreap_template()) ) ){
                $this->workreap_admin_dashboard_footer();
                include workreap_load_template( 'templates/footer/footer-admin-dashboard' );
				$templates = array();
				$name = (string) $name;

				if ( '' !== $name ) {
					$templates[] = "footer-{$name}.php";
				}
				$templates[] = 'footer.php';
				remove_all_actions( 'wp_footer' );

				ob_start();
				// It cause a `require_once` so, in the get_footer it self it will not be required again.
				locate_template( $templates, true );
				ob_get_clean();
            }
        }

        /**
         * @Prepare footer for admin
         * @return {}
         * @author amentotech
         */
        public function workreap_admin_dashboard_footer(){
            global $workreap_settings;
            if(is_page_template('templates/admin-dashboard.php') ){
                $footer_copyright   = !empty($workreap_settings['admin_dashboard_copyright']) ? $workreap_settings['admin_dashboard_copyright'] : sprintf(esc_html__('Copyright  &copy;%s, All Right Reserved', 'workreap'),date('Y'));
                ?>
                    </main>
                        <footer class="wr-footer-wrap">
                            <div class="theme-container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wr-copyright">
                                            <p><?php echo do_shortcode( $footer_copyright );?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </body>
                </html>
                <?php
            }
        }
	}

	new WorkreapFooter();
}
