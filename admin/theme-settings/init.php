<?php
/**
 * Theme Settings
 *
 * @package     Workreap
 * @subpackage  Workreap/admin/Theme_Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

if ( ! class_exists( 'Redux' ) ) { return;}
require_once(WORKREAP_DIRECTORY . '/libraries/scssphp/scss.inc.php');
$opt_name 	= "workreap_settings";
$opt_name   = apply_filters( 'workreap_settings_option_name', $opt_name );

$args = array(
    'opt_name'    		=> $opt_name,
    'display_name' 		=> esc_html__('Workreap Settings','workreap') ,
    'display_version' 	=> WORKREAP_VERSION,
    'menu_type' 		=> 'menu',
    'allow_sub_menu' 	=> true,
    'menu_title' 		=> esc_html__('WR Settings', 'workreap'),
	'page_title'        => esc_html__('Workreap Settings', 'workreap') ,
    'google_api_key' 	=> '',
    'google_update_weekly' => false,
    'async_typography' 	   => true,
    'admin_bar' 		=> true,
    'admin_bar_icon' 	=> 'dashicons-admin-settings',
    'admin_bar_priority'=> 50,
    'global_variable' 	=> $opt_name,
    'dev_mode' 			=> false,
    'update_notice' 	=> false,
    'customizer' 		=> false,
    'page_priority' 	=> null,
    'page_parent' 		=> 'themes.php',
    'page_permissions'  => 'manage_options',
    'menu_icon' 		=> 'dashicons-admin-settings',
    'last_tab' 			=> '',
    'page_icon' 		=> 'wr-icon-themes',
    'page_slug' 		=> 'workreap_settings',
    'save_defaults' 	=> true,
    'default_show' 		=> false,
    'default_mark' 		=> '',
    'show_import_export' => true
);
 
Redux::setArgs ($opt_name, $args);

$scan = glob(WORKREAP_DIRECTORY."/admin/theme-settings/settings/*");
foreach ( $scan as $path ) {
    $file = pathinfo($path);
				
    if( !empty( $file['filename'] ) ){
        @include_once workreap_load_template( '/admin/theme-settings/settings/'.$file['filename'] );
    } 

}

do_action( 'workreap_settings_files');

if( !function_exists('workreap_after_change_option') ){
    add_action ('redux/options/workreap_settings/saved', 'workreap_after_change_option');
    function workreap_after_change_option($value){
	    $body_font          =  !empty($value['wr_body_font']['font-family']) ? $value['wr_body_font']['font-family'] : 'Inter';
	    $heading_font       =  !empty($value['wr_heading_font']['font-family']) ? $value['wr_heading_font']['font-family'] : 'Inter';
        $primary_color      =  !empty($value['wr_primary_color']) ? $value['wr_primary_color'] : '#ff5851';
        $secondary_color    =  !empty($value['wr_secondary_color']) ? $value['wr_secondary_color'] : '#0A0F26';
        $tertiary_color     =  !empty($value['wr_tertiary_color']) ? $value['wr_tertiary_color'] : '#1C1C1C';
        $link_color         =  !empty($value['wr_link_color']) ? $value['wr_link_color'] : '#1DA1F2';
        $button_color       =  !empty($value['wr_button_color']) ? $value['wr_button_color'] : '#1C1C1C';
        
        $compiler       = new ScssPhp\ScssPhp\Compiler();
        $source_scss    = WORKREAP_DIRECTORY . '/public/scss/style.scss';
        $scssContents   = file_get_contents($source_scss);
        $import_path    = WORKREAP_DIRECTORY . '/public/scss';
        $compiler->addImportPath($import_path);

        $target_css = WORKREAP_DIRECTORY . '/public/css/style.css';
        $variables  = array(
				        '$body-font-family'     => $body_font.', sans-serif',
				        '$heading-font-family'  => $heading_font.', sans-serif',
                        '$button-color'         => $button_color,
                        '$theme-color'          => $primary_color,
                        '$dark'                 => $secondary_color,
                        '$heading-font-color'   => $tertiary_color,
                        '$anchor-color'         => $link_color
                    );
        $compiler->setVariables($variables);
        
        $css = $compiler->compile($scssContents);
        if (!empty($css) && is_string($css)) {
            file_put_contents($target_css, $css);
        }
    }
}



//Redux design wrapper start
if( !function_exists('system_redux_style_start') ){
    add_action ('redux/'.$opt_name.'/panel/before', 'system_redux_style_start');
    function system_redux_style_start($value){
        echo '<div class="amt-redux-design">';
    }
}

//Redux design wrapper end
if( !function_exists('system_redux_style_end') ){
    add_action ('redux/'.$opt_name.'/panel/after', 'system_redux_style_end');
    function system_redux_style_end($value){
        echo '</div>';
    }
}