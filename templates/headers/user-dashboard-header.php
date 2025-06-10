<?php
/**
 * User dashboard header
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap_/public
 */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	global $workreap_settings;
	$dashboard_type		= !empty($workreap_settings['dashboard_header_type']) ? $workreap_settings['dashboard_header_type'] : '';
	$main_class	= 'wr-main-bg';
	if( is_page_template( 'templates/add-task.php') || is_page_template( 'templates/add-offer.php') ){
		$main_class			= 'workreap-main-wrapper';
	} ?>
    <?php if($dashboard_type !== 'workreap-sidebar'){
        do_action('workreap_process_headers');
        do_action('workreap_process_dashboard_menu');
        do_action('workreap_do_process_titlebar');
    } ?>
    <main class="wr-main overflow-hidden <?php echo esc_attr($main_class);?>">

