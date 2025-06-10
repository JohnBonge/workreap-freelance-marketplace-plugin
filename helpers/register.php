<?php
/**
 *
 * Register all email templates
 *
 * @package     Workreap
 * @subpackage  Workreap/helpers
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
$dir = WORKREAP_DIRECTORY;
require plugin_dir_path( __FILE__ ) . 'EmailHelper.php';
$scan_PostTypes = glob($dir."helpers/templates/*.*");
if( !empty( $scan_PostTypes ) ){
	foreach ($scan_PostTypes as $filename) {
		$file = pathinfo($filename);
    	if( !empty( $file['filename'] ) ){
			@include workreap_load_template( 'helpers/templates/'.$file['filename'] );
		}
	}
}
