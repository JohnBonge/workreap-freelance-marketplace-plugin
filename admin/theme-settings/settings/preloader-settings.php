<?php
/**
 * General Settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

$theme 		=  wp_get_theme();
$theme_name = $theme->get( 'Name' );
$parent_theme_name = $theme->get( 'Template' );
if($theme->get( 'TextDomain' ) === 'workreap' || $theme_name === 'Workreap' || $parent_theme_name === 'workreap' ){
	Redux::setSection( $opt_name, array(
			'title'            => esc_html__( 'Preloader settings', 'workreap' ),
			'id'               => 'preloader_settings',
			'subsection'       => false,
			'icon'			   => 'el el-globe',
			'fields'           => array(
				array(
					'id'       => 'site_loader',
					'type'     => 'switch',
					'title'    => esc_html__( 'Preloader', 'workreap' ),
					'default'  => false,
					'subtitle'     => esc_html__( 'Enable or disable site preloader', 'workreap' ),
				),
				array(
					'id'       => 'loader_type',
					'type'     => 'select',
					'title'    => esc_html__('Select Type', 'workreap'),
					'subtitle'     => esc_html__('Select preloader type.', 'workreap'),
					'options'  => array(
						'default' 	=> esc_html__('Default', 'workreap'),
						'custom' 	=> esc_html__('Custom', 'workreap'),
					),
					'default'  => 'default',
					'required' => array( 'site_loader', '=', true ),
				),
				array(
					'id'       => 'loader_image',
					'type'     => 'media',
					'url'      => true,
					'title'    => esc_html__( 'Preloader image', 'workreap' ),
					'compiler' => 'true',
					'subtitle'     => esc_html__('Uplaod preloader image here.', 'workreap'),
					'required' => array( 'loader_type', '=', 'custom' )
				),

				array(
					'id'       => 'loader_duration',
					'type'     => 'select',
					'title'    => esc_html__('Loader duration?', 'workreap'),
					'subtitle'     => esc_html__('Set site preloader speed', 'workreap'),
					'options'  => array(
						'250' 	=> esc_html__('1/4th Seconds', 'workreap'),
						'500' 	=> esc_html__('Half Second', 'workreap'),
						'1000' 	=> esc_html__('1 Second', 'workreap'),
						'2000' 	=> esc_html__('2 Seconds', 'workreap'),
					),
					'default'  => '250',
					'required' => array( 'site_loader', '=', true ),
				),
			)
		)
	);
}