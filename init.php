<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap
 *
 * @Workreap
 * Plugin Name:       Workreap - Freelance Marketplace
 * Plugin URI:        https://codecanyon.net/user/amentotech/portfolio
 * Description:       Workreap is a Freelancer Marketplace plugin for Workreap theme.
 * Version:           3.1.3
 * Author:            Amentotech
 * Author URI:        https://amentotech.com/
 * Text Domain:       workreap
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WORKREAP_VERSION', '3.1.3' );
define( 'WORKREAP_DIRECTORY', plugin_dir_path( __FILE__ ));
define( 'WORKREAP_DIRECTORY_URI', plugin_dir_url( __FILE__ ));
define( 'WORKREAP_ACTIVE_THEME_DIRECTORY', get_stylesheet_directory());

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-workreap-core-activator.php
 */

function workreap_activate() {
	do_action('workreap_core_loaded');
	update_option( 'woocommerce_custom_orders_table_enabled', 'no' );
}

register_activation_hook( __FILE__, 'workreap_activate' );

if( !function_exists( 'workreap_load_last' ) ) {
	function workreap_load_last() {
		$workreap_file_path 		= preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
		$workreap_plugin 		= plugin_basename(trim($workreap_file_path));
		$workreap_active_plugins = get_option('active_plugins');
		$workreap_plugin_key 	= array_search($workreap_plugin, $workreap_active_plugins);
		array_splice($workreap_active_plugins, $workreap_plugin_key, 1);
		array_push($workreap_active_plugins, $workreap_plugin);
		update_option('active_plugins', $workreap_active_plugins);
	}
	add_action("activated_plugin", "workreap_load_last");
}

/**
 * Get template from plugin or theme.
 *
 * @param string $file  Templat`e file name.
 * @param array  $param Params to add to template.
 *
 * @return string
 */
function workreap_load_template( $file, $param = array() ) {
	extract( $param );
	if ( is_dir( WORKREAP_ACTIVE_THEME_DIRECTORY . '/extend/' ) ) {
		if ( file_exists( WORKREAP_ACTIVE_THEME_DIRECTORY . '/extend/' . $file . '.php' ) ) {
			$template_load = WORKREAP_ACTIVE_THEME_DIRECTORY . '/extend/' . $file . '.php';
		} else {
			$template_load = WORKREAP_DIRECTORY . '/' . $file . '.php';
		}
	} else {
		$template_load = WORKREAP_DIRECTORY . '/' . $file . '.php';
	}

	return $template_load;
}

if(defined('Workreap_Basename')){
	if ( !function_exists( 'deactivate_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugins_to_deactivate = array(
		'workreap_core/init.php',
		'workreap_api/init.php',
		'workreap_cron/workreap_cron.php',
	);
	foreach ( $plugins_to_deactivate as $plugin ) {
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
		}
	}
	return;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'helpers/register.php';
require plugin_dir_path( __FILE__ ) . 'includes/cron.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-workreap-core.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/funtions.php';
require plugin_dir_path( __FILE__ ) . 'public/public-function.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/projects-function.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/projects-hooks.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/proposal-hooks.php';
require workreap_load_template( 'public/partials/class-header');
require workreap_load_template( 'public/partials/class-footer');
require workreap_load_template( 'public/partials/template-loader');

/**
 * The class responsible for task plans
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-workreap-service-plans.php';
require plugin_dir_path( __FILE__ ) . 'elementor/library/class-workreap-library-source.php';
require plugin_dir_path( __FILE__ ) . 'elementor/library/class-workreap-template-library.php';

require plugin_dir_path( __FILE__ ) . 'elementor/base.php';
require plugin_dir_path( __FILE__ ) . 'elementor/config.php';
require plugin_dir_path( __FILE__ ) . '/admin/workreap-notification/init.php';

require plugin_dir_path( __FILE__ ) . '/admin/theme-settings/init.php'; //Theme Settings
require plugin_dir_path( __FILE__ ) . 'import-users/class-readcsv.php';
require plugin_dir_path( __FILE__ ) . 'includes/migration.php';
include workreap_load_template( 'import-users/class-import-user' );
require plugin_dir_path( __FILE__ ) . 'widgets/class-footer-info.php';
require plugin_dir_path( __FILE__ ) . 'widgets/class-footer-app-info.php';
require plugin_dir_path( __FILE__ ) . 'widgets/class-footer-contact-info.php';
require plugin_dir_path( __FILE__ ) . 'widgets/class-nav-menu-widget.php';
require plugin_dir_path( __FILE__ ) . 'widgets/class-recent-posts.php';
require plugin_dir_path( __FILE__ ) . 'widgets/class-news-letters.php';

include workreap_load_template( 'libraries/mailchimp/class-mailchimp' );
require plugin_dir_path( __FILE__ ) . 'libraries/mailchimp/class-mailchimp-oath.php';

/**
 * Add http from URL
 */
if (!function_exists('workreap_add_http_protcol')) {
  function workreap_add_http_protcol($url) {
	$url    = set_url_scheme($url);

	return $url;
  }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if( !function_exists( 'workreap_run' ) ) {
	function workreap_run() {
		$plugin = new Workreap();
		$plugin->run();
	}
	add_action( 'plugins_loaded', 'workreap_run' );
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'init', 'workreap_plugin_load_textdomain' );
function workreap_plugin_load_textdomain() {
	load_plugin_textdomain( 'workreap', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
