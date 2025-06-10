<?php
/**
 * Footer Settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
Redux::setSection( $opt_name, array(
		'title'            => esc_html__( '404 page settings', 'workreap' ),
		'id'               => 'page404_settings',
		'subsection'       => false,
		'icon'			   => 'el el-ban-circle',
		'fields'           => array(
			array(
				'id'        => 'title_404',
				'type'      => 'text',
				'title'     => esc_html__('404 title', 'workreap'),
				'subtitle'  => esc_html__('Insert page not found title here.', 'workreap'),
			),
			array(
				'id'        => 'subtitle_404',
				'type'      => 'text',
				'title'     => esc_html__('404 subtitle', 'workreap'),
				'subtitle'  => esc_html__('Insert page not found subtitle here.', 'workreap'),
			),
			array(
				'id'       => 'description_404',
				'type'     => 'textarea',
				'title'    => esc_html__('404 description', 'workreap' ),
				'subtitle'  => esc_html__('Insert page not found description here.', 'workreap'),
				'default'  => '',
			),
			array(
				'id'		=> 'image_404',
				'type' 		=> 'media',
				'url'		=> true,
				'title' 	=> esc_html__('404 image', 'workreap'),
				'subtitle'  => esc_html__('Upload page not found image here.', 'workreap'),
			),
		)
	)
);